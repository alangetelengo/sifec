<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\DocumentPretPourSignature;
use App\Notifications\NouvelleDemandeCentre;
use App\Sifec\Sifec;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Mobile\Entities\DemandeDocument;
use Modules\Mobile\Entities\Tarificatrion;

class DemandeDocumentService
{
    /**
     * Créer une demande sur site
     */
    public function creerDemandeSurSite(array $data, User $user): DemandeDocument
    {
        DB::beginTransaction();
        try {
            $demande = new DemandeDocument;
            $demande->code_demande_document = Sifec::genererCodeUniqueReferentiel(
                $demande,
                'code_demande_document',
                10,
                'DMDS_'
            );

            $demande->origine_demande = 'sur_site';
            $demande->nom_demandeur = $data['nom_demandeur'];
            $demande->prenom_demander = $data['prenom_demandeur'] ?? null;
            $demande->sexe_demander = $data['sexe_demandeur'] ?? 'M';
            $demande->telephone_demander = $data['telephone_demandeur'];
            $demande->email_demandeur = $data['email_demandeur'] ?? null;

            $demande->numero_acte = $data['numero_acte'];
            $demande->code_type_acte = $data['code_type_acte'];
            $demande->code_type_document_demande = $data['code_type_document_demande'];

            $affectation = $user->affectationActive();
            $demande->code_institution = $affectation->institution->code_institution;
            $demande->cui = $affectation->cui;

            // Calculer le prix (avec priorité tarif institution, puis national)
            $demande->prix = $this->calculerPrix(
                $data['code_type_document_demande'],
                $affectation->code_institution
            );

            $demande->statut = 'En traitement';
            $demande->date_demande = now();
            $demande->observations = $data['observations'] ?? null;

            $demande->save();

            DB::commit();

            Log::channel('sifec')->info('Demande sur site créée', [
                'code_demande' => $demande->code_demande_document,
                'user' => $user->code_user,
            ]);

            // Notifier les agents du centre
            $this->notifierAgentsCentre($demande);

            return $demande;
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('Erreur création demande sur site', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Créer une demande depuis le portail
     */
    public function creerDemandePortail(array $data): DemandeDocument
    {
        DB::beginTransaction();
        try {
            $demande = new DemandeDocument;
            $demande->code_demande_document = Sifec::genererCodeUniqueReferentiel(
                $demande,
                'code_demande_document',
                10,
                'DMDP_'
            );

            $demande->origine_demande = 'portail';
            $demande->nom_demandeur = $data['nom_demandeur'];
            $demande->prenom_demander = $data['prenom_demandeur'] ?? null;
            $demande->sexe_demander = $data['sexe_demandeur'] ?? 'M';
            $demande->telephone_demander = $data['telephone_demandeur'];
            $demande->email_demandeur = $data['email_demandeur'] ?? null;

            $demande->numero_acte = $data['numero_acte'];
            $demande->code_type_acte = $data['code_type_acte'];
            $demande->code_type_document_demande = $data['code_type_document_demande'];
            $demande->code_institution = $data['code_institution'] ?? null;

            $demande->prix = $this->calculerPrix(
                $data['code_type_document_demande'],
                $demande->code_institution
            );

            $demande->statut = 'En attente de paiement';
            $demande->date_demande = now();

            $demande->save();

            DB::commit();

            Log::channel('sifec')->info('Demande portail créée', [
                'code_demande' => $demande->code_demande_document,
            ]);

            // Notifier les agents du centre
            $this->notifierAgentsCentre($demande);

            return $demande;
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('Erreur création demande portail', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Passer une demande en traitement (après paiement pour portail)
     */
    public function passerEnTraitement(DemandeDocument $demande): bool
    {
        if (! $demande->estEnAttentePaiement()) {
            Log::channel('sifec')->warning("Tentative passage en traitement d'une demande non en attente paiement", [
                'code_demande' => $demande->code_demande_document,
                'statut_actuel' => $demande->statut,
            ]);

            return false;
        }

        $demande->statut = 'En traitement';
        $demande->date_traitement = now();
        $demande->save();

        Log::channel('sifec')->info('Demande passée en traitement', [
            'code_demande' => $demande->code_demande_document,
        ]);

        return true;
    }

    /**
     * Générer le document PDF et passer en attente signature
     */
    public function genererDocumentPDF(DemandeDocument $demande): string
    {
        if (! $demande->peutEtreGeneree()) {
            throw new Exception('La demande ne peut pas être générée dans son état actuel');
        }

        $documentPdfService = app(DocumentPdfService::class);

        if ($demande->estCopie()) {
            $cheminPdf = $documentPdfService->genererCopie($demande);
        } else {
            $cheminPdf = $documentPdfService->genererExtrait($demande);
        }

        $demande->chemin_document = $cheminPdf;
        $demande->save();

        Log::channel('sifec')->info('Document PDF généré', [
            'code_demande' => $demande->code_demande_document,
            'chemin' => $cheminPdf,
        ]);

        return $cheminPdf;
    }

    /**
     * Régénère le PDF après signature (même emplacement logique : le modèle inclut signature_officier / date_signature).
     */
    public function regenererPdfApresSignature(DemandeDocument $demande): string
    {
        $demande->refresh();
        $demande->load(['signataire.user.personne', 'institution', 'typeActe', 'typeDocumentDemande']);

        if (empty($demande->signature_officier)) {
            throw new Exception(
                "Impossible de produire un PDF signé : aucune signature officier enregistrée pour {$demande->code_demande_document}."
            );
        }

        $cheminImageSignature = public_path('app/'.$demande->signature_officier);
        if (! is_file($cheminImageSignature)) {
            throw new Exception(
                "Impossible de produire un PDF signé : image de signature introuvable ({$cheminImageSignature})."
            );
        }

        if (empty($demande->numero_acte) || empty($demande->code_type_acte)) {
            throw new Exception("Données acte manquantes pour régénérer le PDF ({$demande->code_demande_document}).");
        }

        if (! $demande->acteExiste()) {
            throw new Exception("Acte introuvable pour la demande {$demande->code_demande_document}.");
        }

        $documentPdfService = app(DocumentPdfService::class);

        if ($demande->estCopie()) {
            $cheminPdf = $documentPdfService->genererCopie($demande);
        } else {
            $cheminPdf = $documentPdfService->genererExtrait($demande);
        }

        $demande->chemin_document = $cheminPdf;
        $demande->save();

        Log::channel('sifec')->info('Document PDF régénéré après signature', [
            'code_demande' => $demande->code_demande_document,
            'chemin' => $cheminPdf,
        ]);

        return $cheminPdf;
    }

    /**
     * Passer en attente de signature
     */
    public function passerEnAttenteSignature(DemandeDocument $demande): bool
    {
        if (! $demande->estEnTraitement()) {
            return false;
        }

        $demande->statut = 'En attente de signature';
        $demande->save();

        Log::channel('sifec')->info('Demande passée en attente de signature', [
            'code_demande' => $demande->code_demande_document,
        ]);

        // Notifier les signataires autorisés
        $this->notifierSignataires($demande);

        return true;
    }

    /**
     * Rejeter une demande
     */
    public function rejeterDemande(DemandeDocument $demande, string $motif): bool
    {
        $demande->statut = 'Rejetée';
        $demande->observations = ($demande->observations ?? '')."\n\nMotif rejet: ".$motif;
        $demande->save();

        Log::channel('sifec')->info('Demande rejetée', [
            'code_demande' => $demande->code_demande_document,
            'motif' => $motif,
        ]);

        return true;
    }

    /**
     * Marquer comme livrée
     */
    /**
     * Après expiration : remet la demande en circuit (génération PDF + signature OTP).
     */
    public function preparerRenouvellementApresExpiration(DemandeDocument $demande): void
    {
        if (! $demande->estExpiree()) {
            throw new Exception('Seules les demandes au statut « Expirée » peuvent être renouvelées.');
        }

        if (! empty($demande->chemin_document) && is_file($demande->chemin_document)) {
            @unlink($demande->chemin_document);
        }

        $demande->statut = 'En traitement';
        $demande->signature_officier = null;
        $demande->code_signataire = null;
        $demande->date_signature = null;
        $demande->document_valide_de = null;
        $demande->document_valide_jusquau = null;
        $demande->otp_code = null;
        $demande->otp_expire_at = null;
        $demande->ip_signature = null;
        $demande->user_agent_signature = null;
        $demande->chemin_document = null;
        $demande->compteur_renouvellement = (int) ($demande->compteur_renouvellement ?? 0) + 1;
        $demande->save();

        Log::channel('sifec')->info('Demande document : renouvellement préparé après expiration', [
            'code_demande' => $demande->code_demande_document,
            'compteur_renouvellement' => $demande->compteur_renouvellement,
        ]);
    }

    public function marquerLivree(DemandeDocument $demande): bool
    {
        if (! $demande->estTraitee()) {
            return false;
        }

        $demande->statut = 'Livrée';
        $demande->date_livraison = now();
        $demande->save();

        Log::channel('sifec')->info('Demande marquée livrée', [
            'code_demande' => $demande->code_demande_document,
        ]);

        return true;
    }

    /**
     * Calculer le prix d'une demande selon la tarification paramétrée
     * Logique de priorité:
     * 1. Tarif spécifique à l'institution (si code_institution fourni)
     * 2. Tarif général/national (code_institution = null)
     * 3. Prix par défaut en fallback
     */
    public function calculerPrix(string $codeTypeDocument, ?string $codeInstitution = null): float
    {
        try {
            // 1. Chercher un tarif spécifique à l'institution (prioritaire)
            if ($codeInstitution) {
                $tarifSpecifique = Tarificatrion::where('code_type_document_demande', $codeTypeDocument)
                    ->where('code_institution', $codeInstitution)
                    ->where('actif', 1)
                    ->where(function ($q) {
                        $q->whereNull('date_debut_validite')
                            ->orWhere('date_debut_validite', '<=', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('date_fin_validite')
                            ->orWhere('date_fin_validite', '>=', now());
                    })
                    ->orderBy('date_debut_validite', 'desc')
                    ->first();

                if ($tarifSpecifique) {
                    Log::channel('sifec')->info('Tarif spécifique institution trouvé', [
                        'code_type_document' => $codeTypeDocument,
                        'code_institution' => $codeInstitution,
                        'prix' => $tarifSpecifique->prix,
                        'code_tarification' => $tarifSpecifique->code_tarification,
                    ]);

                    return (float) $tarifSpecifique->prix;
                }
            }

            // 2. Chercher un tarif général/national (code_institution = null)
            $tarifGeneral = Tarificatrion::where('code_type_document_demande', $codeTypeDocument)
                ->whereNull('code_institution')
                ->where('actif', 1)
                ->where(function ($q) {
                    $q->whereNull('date_debut_validite')
                        ->orWhere('date_debut_validite', '<=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('date_fin_validite')
                        ->orWhere('date_fin_validite', '>=', now());
                })
                ->orderBy('date_debut_validite', 'desc')
                ->first();

            if ($tarifGeneral) {
                Log::channel('sifec')->info('Tarif national trouvé', [
                    'code_type_document' => $codeTypeDocument,
                    'prix' => $tarifGeneral->prix,
                    'code_tarification' => $tarifGeneral->code_tarification,
                ]);

                return (float) $tarifGeneral->prix;
            }

            // 3. Prix par défaut en dernier recours (devrait rarement arriver maintenant)
            $prixDefaut = match ($codeTypeDocument) {
                'TDD_0001' => 5000.00, // Copie
                'TDD_0002' => 3000.00, // Extrait
                default => 1000.00,
            };

            Log::channel('sifec')->warning('Aucun tarif paramétré trouvé, utilisation prix par défaut', [
                'code_type_document' => $codeTypeDocument,
                'code_institution' => $codeInstitution,
                'prix_defaut' => $prixDefaut,
            ]);

            return $prixDefaut;

        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors du calcul du prix', [
                'code_type_document' => $codeTypeDocument,
                'code_institution' => $codeInstitution,
                'error' => $e->getMessage(),
            ]);

            // Retourner un prix par défaut en cas d'erreur
            return 1000.00;
        }
    }

    /**
     * Rechercher un acte à partir des informations fournies
     */
    public function rechercherActe(string $typeActe, ?string $numeroActe, array $criteres = [])
    {
        try {
            // Si on a le numéro d'acte, recherche directe
            if (! empty($numeroActe)) {
                $sifec = app(Sifec::class);

                return $sifec->rechercherActe($typeActe, $numeroActe);
            }

            // Sinon recherche par critères (nom, prénom, date, lieu)
            // Cette méthode doit être implémentée selon les besoins
            return null;
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de la recherche d\'acte', [
                'type_acte' => $typeActe,
                'numero_acte' => $numeroActe,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Notifier les agents du centre lors de l'enregistrement d'une demande
     */
    protected function notifierAgentsCentre(DemandeDocument $demande): void
    {
        try {
            if (! $demande->code_institution) {
                Log::channel('sifec')->warning("Impossible de notifier : pas d'institution", [
                    'code_demande' => $demande->code_demande_document,
                ]);

                return;
            }

            // Récupérer tous les utilisateurs affectés à l'institution avec la permission demande_document
            $agents = User::whereHas('affectations', function ($query) use ($demande) {
                $query->where('code_institution', $demande->code_institution)
                    ->where('active', 1);
            })
                ->whereHas('fonctionnalites', function ($query) {
                    $query->where('lib_technique', 'module.demande_document');
                })
                ->get();

            Log::channel('sifec')->info('Notification nouvelle demande', [
                'code_demande' => $demande->code_demande_document,
                'nb_agents' => $agents->count(),
            ]);

            foreach ($agents as $agent) {
                $agent->notify(new NouvelleDemandeCentre($demande));
            }

        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur notification agents centre', [
                'code_demande' => $demande->code_demande_document,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Notifier les signataires autorisés lorsqu'un document est généré
     */
    protected function notifierSignataires(DemandeDocument $demande): void
    {
        try {
            if (! $demande->code_institution) {
                Log::channel('sifec')->warning("Impossible de notifier signataires : pas d'institution", [
                    'code_demande' => $demande->code_demande_document,
                ]);

                return;
            }

            // Récupérer la permission lib_technique appropriée pour cette demande
            $permissionLibTechnique = $demande->getPermissionSignature();

            // Récupérer tous les utilisateurs autorisés à signer ce type de document
            $signataires = User::whereHas('affectations', function ($query) use ($demande) {
                $query->where('code_institution', $demande->code_institution)
                    ->where('active', 1);
            })
                ->whereHas('fonctionnalites', function ($query) use ($permissionLibTechnique) {
                    $query->where('lib_technique', $permissionLibTechnique);
                })
                ->get();

            Log::channel('sifec')->info('Notification signataires', [
                'code_demande' => $demande->code_demande_document,
                'permission' => $permissionLibTechnique,
                'nb_signataires' => $signataires->count(),
            ]);

            $sifec = app(Sifec::class);

            foreach ($signataires as $signataire) {
                // Notification système et email
                $signataire->notify(new DocumentPretPourSignature($demande));

                // Notification SMS
                if ($signataire->personne && $signataire->personne->telephone) {
                    $message = 'SIFEC: Document prêt à signer - '.
                               $demande->getLibelleTypeDocument().
                               ' de '.$demande->getLibelleTypeActe().
                               ' (Acte N° '.$demande->numero_acte.'). '.
                               'Connectez-vous pour signer.';

                    $sifec->envoyerSMS($signataire->personne->telephone, $message);
                }
            }

        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur notification signataires', [
                'code_demande' => $demande->code_demande_document,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
