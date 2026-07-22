<?php

namespace App\Support;

use App\Models\InstitutionUser;
use App\Models\User;

/**
 * Affichage du rôle du signataire sur les documents signés électroniquement (GUOT).
 */
class GuotSignatureAffichage
{
    /**
     * Fonction institutionnelle du signataire (tr_fonction.lib_fonction).
     */
    public static function fonctionUtilisateur(User $user): ?string
    {
        $user->loadMissing(['affectations.fonction']);
        $lib = $user->affectationActive()?->fonction?->lib_fonction;

        return filled($lib) ? (string) $lib : null;
    }

    /**
     * Préremplit actor_fonction (et optionnellement actor_nom) en mémoire avant génération du PDF à signer.
     *
     * @param  string  $prefix  Préfixe des colonnes (ex. « sig_cec_ ») ou chaîne vide pour les actes.
     */
    public static function applySignerPreview(object $document, User $user, string $prefix = ''): void
    {
        $fonction = self::fonctionUtilisateur($user);
        if ($fonction !== null) {
            $document->setAttribute($prefix.'actor_fonction', $fonction);
        }

        if ($prefix === '' && ! filled($document->actor_nom ?? null)) {
            $user->loadMissing('personne');
            $nom = trim(($user->personne?->nom ?? '').' '.($user->personne?->prenom ?? ''));
            if ($nom !== '') {
                $document->setAttribute('actor_nom', $nom);
            }
        }
    }

    /**
     * Rôle à afficher sur le pied de page (persisté, CUI ou repli contextuel).
     *
     * @param  string  $prefix  Préfixe des colonnes (ex. « sig_cec_ ») ou chaîne vide pour les actes.
     */
    public static function roleSignataire(object $document, string $prefix = '', ?string $fallback = null): ?string
    {
        $col = $prefix.'actor_fonction';
        if (filled($document->{$col} ?? null)) {
            return (string) $document->{$col};
        }

        // Priorité au CUI du *signataire* (pas le propriétaire du document : tr_registre.cui = CEC).
        if ($prefix !== '') {
            $cui = $document->{$prefix.'cui'} ?? null;
        } else {
            $cui = $document->approbation_mairie
                ?? $document->code_signataire
                ?? $document->approbation_tribunal
                ?? ($document->cui ?? null);
        }

        if (filled($cui)) {
            $lib = InstitutionUser::with('fonction')->find($cui)?->fonction?->lib_fonction;
            if (filled($lib)) {
                return (string) $lib;
            }
        }

        return $fallback;
    }

    /**
     * Construit un descripteur de bloc PKI pour le PDF (null si rien à afficher).
     *
     * @return array{titre: string, couleur: string, fond: string, role: string, nom: ?string, date: mixed, empreinte: ?string, certificat: ?string}|null
     */
    public static function blocPki(
        object $document,
        string $prefix,
        string $titre,
        string $roleFallback,
        string $couleur = '#006B31',
        string $fond = '#f4faf6',
    ): ?array {
        if ($prefix !== '') {
            if (! filled($document->{$prefix.'proof_id'} ?? null)) {
                return null;
            }
        } else {
            $hasProof = filled($document->proof_id ?? null)
                || filled($document->signed_at ?? null)
                || filled($document->actor_nom ?? null);
            if (! $hasProof) {
                return null;
            }
        }

        $nomKey = $prefix === '' ? 'actor_nom' : $prefix.'actor_nom';
        $dateKey = $prefix === '' ? 'signed_at' : $prefix.'signed_at';
        $dateKeyAlt = $prefix === '' ? 'doc_sig_signed_at' : $prefix.'doc_sig_signed_at';
        $hashKey = $prefix === '' ? 'pdf_content_hash' : $prefix.'pdf_content_hash';
        $hashKeyAlt = $prefix === '' ? 'payload_hash' : $prefix.'payload_hash';
        $certKey = $prefix === '' ? 'certificate_ref' : $prefix.'certificate_ref';

        $nom = $document->{$nomKey} ?? null;
        $date = $document->{$dateKey}
            ?? $document->{$dateKeyAlt}
            ?? ($prefix === '' ? ($document->date_signature ?? $document->date_heure_approbation_mairie ?? null) : null);
        $empreinte = $document->{$hashKey} ?? $document->{$hashKeyAlt} ?? null;
        $certificat = $document->{$certKey} ?? null;
        $role = self::roleSignataire($document, $prefix, $roleFallback) ?? $roleFallback;

        return [
            'titre' => $titre,
            'couleur' => $couleur,
            'fond' => $fond,
            'role' => $role,
            'nom' => filled($nom) ? (string) $nom : null,
            'date' => $date,
            'empreinte' => filled($empreinte) ? (string) $empreinte : null,
            'certificat' => filled($certificat) ? (string) $certificat : null,
        ];
    }

    /**
     * Contexte d'affichage déclaration/certificat de naissance (FS vs CEC).
     */
    public static function contexteDeclarationNaissance(object $dn, ?string $contexte = null): string
    {
        $ctx = $contexte;
        if ($ctx === null || $ctx === '') {
            $type = (string) ($dn->type_declaration ?? '');
            $ctx = str_contains($type, 'CERTIFICAT') ? 'formation_sanitaire' : 'centre_etat_civil';
        }

        return $ctx === 'formation_sanitaire' ? 'formation_sanitaire' : 'centre_etat_civil';
    }

    /**
     * Blocs PKI pour le PDF déclaration/certificat de naissance selon le contexte d'affichage.
     *
     * - formation_sanitaire (certificat) : uniquement le signataire FS ;
     * - centre_etat_civil (déclaration) : FS si présent + CEC.
     *
     * @return list<array{titre: string, couleur: string, fond: string, role: string, nom: ?string, date: mixed, empreinte: ?string, certificat: ?string}>
     */
    public static function blocsPkiDeclarationNaissance(object $dn, ?string $contexte): array
    {
        $ctx = self::contexteDeclarationNaissance($dn, $contexte);

        $blocs = [];

        if ($ctx === 'formation_sanitaire') {
            $blocFs = self::blocPki(
                $dn,
                'sig_fs_',
                'RÉFÉRENCES DE LA SIGNATURE ÉLECTRONIQUE PKI — CERTIFICAT',
                'Chef de service',
                '#1a5fb4',
                '#f5f9fc',
            );
            if ($blocFs) {
                $blocs[] = $blocFs;
            }

            return $blocs;
        }

        // Déclaration CEC : certificat amont (si signé) + déclaration
        $blocFs = self::blocPki(
            $dn,
            'sig_fs_',
            'RÉFÉRENCES DE LA SIGNATURE ÉLECTRONIQUE PKI — CERTIFICAT',
            'Chef de service',
            '#1a5fb4',
            '#f5f9fc',
        );
        if ($blocFs) {
            $blocs[] = $blocFs;
        }

        $blocCec = self::blocPki(
            $dn,
            'sig_cec_',
            'RÉFÉRENCES DE LA SIGNATURE ÉLECTRONIQUE PKI — DÉCLARATION',
            "Officier d'état civil",
            '#006B31',
            '#f4faf6',
        );
        if ($blocCec) {
            $blocs[] = $blocCec;
        }

        return $blocs;
    }
}
