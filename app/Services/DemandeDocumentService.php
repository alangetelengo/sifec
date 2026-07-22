<?php

namespace App\Services;

use App\Mail\DocumentPretPourSignatureMail;
use App\Mail\NouvelleDemandeCentreMail;
use App\Models\User;
use App\Notifications\DocumentPretPourSignature;
use App\Notifications\NouvelleDemandeCentre;
use App\Sifec\Sifec;
use App\Support\GuotSignataires;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

            // Paiement portail temporairement désactivé : passage direct en traitement
            // $demande->statut = 'En attente de paiement';
            $demande->statut = 'En traitement';
            $demande->date_demande = now();
            $demande->date_traitement = now();

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
     * Indique si le PDF de consultation (avec bloc PKI complet) est déjà distinct du binaire scellé.
     */
    public function estPdfConsultationPret(DemandeDocument $demande): bool
    {
        $chemin = (string) ($demande->chemin_document ?? '');
        if ($chemin === '' || ! is_file($chemin)) {
            return false;
        }

        if (filled($demande->pdf_path)) {
            $scelle = storage_path('app/'.$demande->pdf_path);
            if (is_file($scelle)) {
                $realChemin = realpath($chemin) ?: $chemin;
                $realScelle = realpath($scelle) ?: $scelle;
                if ($realChemin === $realScelle) {
                    return false;
                }
            }
        }

        // Même contenu que le PDF hashé à la signature = encore le binaire scellé.
        if (filled($demande->pdf_content_hash)) {
            $hashFichier = @hash_file('sha256', $chemin);
            if (is_string($hashFichier) && hash_equals((string) $demande->pdf_content_hash, $hashFichier)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Garantit un PDF de consultation (certificat + empreinte affichés).
     * Ne régénère que si le fichier servi est encore le binaire scellé ou absent.
     */
    public function assurerPdfConsultation(DemandeDocument $demande): string
    {
        if ($this->estPdfConsultationPret($demande)) {
            return (string) $demande->chemin_document;
        }

        return $this->regenererPdfApresSignature($demande);
    }

    /**
     * Régénère le PDF après signature (même emplacement logique : le modèle inclut signature_officier / date_signature).
     * Le binaire scellé (pdf_path / pdf_content_hash) n'est pas modifié.
     */
    public function regenererPdfApresSignature(DemandeDocument $demande): string
    {
        $demande->refresh();
        $demande->load(['signataire.user.personne', 'institution', 'typeActe', 'typeDocumentDemande']);

        if (empty($demande->code_signataire) || empty($demande->date_signature)) {
            throw new Exception(
                "Impossible de produire un PDF signé : signataire ou date de délivrance manquant pour {$demande->code_demande_document}."
            );
        }

        if (filled($demande->signature_officier)) {
            $cheminImageSignature = public_path('app/'.$demande->signature_officier);
            if (! is_file($cheminImageSignature)) {
                Log::channel('sifec')->warning('Image de paraphe absente — PDF régénéré avec le nom du signataire uniquement', [
                    'code_demande' => $demande->code_demande_document,
                    'chemin' => $cheminImageSignature,
                ]);
                $demande->signature_officier = null;
            }
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
        // Paiement temporairement désactivé : accepter aussi « En attente de paiement »
        if (! $demande->estEnTraitement() && ! $demande->estEnAttentePaiement()) {
            return false;
        }

        $demande->statut = 'En attente de signature';
        // Pas de signataire figé à la génération : signature = officier en fonction au moment de signer
        $demande->signature_officier = null;
        $demande->code_signataire = null;
        $demande->date_signature = null;
        $demande->save();

        Log::channel('sifec')->info('Demande passée en attente de signature de délivrance', [
            'code_demande' => $demande->code_demande_document,
        ]);

        try {
            $this->notifierSignataires($demande);
        } catch (\Throwable $e) {
            Log::channel('sifec')->warning('Notification signataires après génération PDF (non bloquant)', [
                'code_demande' => $demande->code_demande_document,
                'error' => $e->getMessage(),
            ]);
        }

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
     * Utilisateurs affectés activement à une institution et disposant d'une permission
     * (via tr_uf OU via la fonction de l'affectation / tr_ff — même logique que Gate).
     *
     * @return Collection<int, User>
     */
    protected function usersInstitutionAvecPermission(string $codeInstitution, string $libTechnique): Collection
    {
        return User::query()
            ->whereHas('affectations', function ($query) use ($codeInstitution) {
                $query->where('code_institution', $codeInstitution)
                    ->where('active', 1);
            })
            ->where(function ($query) use ($codeInstitution, $libTechnique) {
                $query->whereHas('fonctionnalites', function ($q) use ($libTechnique) {
                    $q->where('lib_technique', $libTechnique);
                })->orWhereHas('affectations', function ($q) use ($codeInstitution, $libTechnique) {
                    $q->where('code_institution', $codeInstitution)
                        ->where('active', 1)
                        ->whereHas('fonction.fonctionnalites', function ($fq) use ($libTechnique) {
                            $fq->where('lib_technique', $libTechnique);
                        });
                });
            })
            ->get();
    }

    /**
     * Officiers / signataires GUOT affectés activement à l'institution (ex. FONC_0002).
     *
     * @return Collection<int, User>
     */
    protected function officiersInstitution(string $codeInstitution): Collection
    {
        $codes = GuotSignataires::codes();
        if ($codes === []) {
            $codes = ['FONC_0002'];
        }

        return User::query()
            ->whereHas('affectations', function ($query) use ($codeInstitution, $codes) {
                $query->where('code_institution', $codeInstitution)
                    ->where('active', 1)
                    ->whereIn('code_fonction', $codes);
            })
            ->get();
    }

    protected function emailNotificationUser(User $user): ?string
    {
        foreach ([$user->email ?? null, $user->email_professionnel ?? null] as $candidate) {
            $email = trim((string) $candidate);
            if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $email;
            }
        }

        return null;
    }

    /**
     * Notifier les agents du centre et l’officier d’état civil à chaque nouvelle demande.
     */
    protected function notifierAgentsCentre(DemandeDocument $demande): void
    {
        try {
            if (! $demande->code_institution) {
                Log::channel('sifec')->warning('[DemandeDocument][Notification] Aucune institution sur la demande — agents et officiers non notifiés.', [
                    'code_demande' => $demande->code_demande_document,
                    'origine_demande' => $demande->origine_demande,
                ]);

                return;
            }

            $demande->loadMissing('institution');

            $libInstitution = $demande->institution?->lib_institution;
            $codeInstitution = (string) $demande->code_institution;

            $agentsDemande = $this->usersInstitutionAvecPermission($codeInstitution, 'module.demande_document');

            $permissionSignature = $demande->getPermissionSignature();
            $officiersPermission = $this->usersInstitutionAvecPermission($codeInstitution, $permissionSignature);

            // Garantie : toujours notifier l'officier d'état civil du centre
            $officiersFonction = $this->officiersInstitution($codeInstitution);

            $recipients = $agentsDemande
                ->merge($officiersPermission)
                ->merge($officiersFonction)
                ->unique('code_user')
                ->values();

            Log::channel('sifec')->info('[DemandeDocument][Notification] Destinataires pour nouvelle demande (centre + officier).', [
                'code_demande' => $demande->code_demande_document,
                'code_institution' => $codeInstitution,
                'lib_institution' => $libInstitution,
                'nb_agents_module_demande_document' => $agentsDemande->count(),
                'permission_signature' => $permissionSignature,
                'nb_officiers_permission_signature' => $officiersPermission->count(),
                'nb_officiers_fonction' => $officiersFonction->count(),
                'nb_destinataires_uniques' => $recipients->count(),
                'code_users' => $recipients->pluck('code_user')->all(),
            ]);

            if ($recipients->isEmpty()) {
                Log::channel('sifec')->warning('[DemandeDocument][Notification] Aucun destinataire — vérifier les affectations actives et la fonction officier au centre.', [
                    'code_demande' => $demande->code_demande_document,
                    'code_institution' => $codeInstitution,
                    'permission_signature' => $permissionSignature,
                ]);

                return;
            }

            $recipients->loadMissing('personne');

            $sifec = app(Sifec::class);
            $smsCount = 0;

            foreach ($recipients as $user) {
                $user->notify(new NouvelleDemandeCentre($demande));

                $to = $this->emailNotificationUser($user);
                if ($to !== null) {
                    Log::channel('sifec')->info('Envoi e-mail nouvelle demande document (centre / officier)', [
                        'code_demande' => $demande->code_demande_document,
                        'code_user' => $user->code_user,
                        'to_masque' => preg_replace('/(^.).*(@.*$)/', '$1…$2', $to),
                    ]);
                    try {
                        Mail::to($to)->send(new NouvelleDemandeCentreMail($demande, $user));
                        Log::channel('sifec')->info('E-mail nouvelle demande document : envoi SMTP terminé sans exception.', [
                            'code_demande' => $demande->code_demande_document,
                            'code_user' => $user->code_user,
                            'to_masque' => preg_replace('/(^.).*(@.*$)/', '$1…$2', $to),
                        ]);
                    } catch (\Throwable $e) {
                        Log::channel('sifec')->error('Échec envoi e-mail nouvelle demande document', [
                            'code_demande' => $demande->code_demande_document,
                            'code_user' => $user->code_user,
                            'to_masque' => preg_replace('/(^.).*(@.*$)/', '$1…$2', $to),
                            'exception' => $e->getMessage(),
                        ]);
                    }
                }

                $tel = trim((string) (optional($user->personne)->telephone ?? ''));
                if ($tel !== '') {
                    $sms = 'SIFEC: Nouvelle demande '.$demande->getLibelleTypeDocument().
                        ' ('.$demande->getLibelleTypeActe().'). Acte N° '.$demande->numero_acte.
                        '. Demandeur: '.$demande->getNomCompletDemandeur().
                        '. Connectez-vous sur SIFEC pour la traiter.';
                    try {
                        $sifec->sendSms($tel, $sms);
                        $smsCount++;
                    } catch (Exception $smsEx) {
                        Log::channel('sifec')->warning('[DemandeDocument][Notification] SMS nouvelle demande échoué', [
                            'code_demande' => $demande->code_demande_document,
                            'code_user' => $user->code_user,
                            'error' => $smsEx->getMessage(),
                        ]);
                    }
                }
            }

            Log::channel('sifec')->info('[DemandeDocument][Notification] Notifications envoyées (base de données, e-mail si renseigné, SMS si téléphone).', [
                'code_demande' => $demande->code_demande_document,
                'nombre_destinataires' => $recipients->count(),
                'code_users' => $recipients->pluck('code_user')->all(),
                'sms_envoyes' => $smsCount,
            ]);
        } catch (Exception $e) {
            Log::channel('sifec')->error('[DemandeDocument][Notification] Erreur notification centre / officier', [
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
            $codeInstitution = (string) $demande->code_institution;

            // Signataires : permission (tr_uf + tr_ff) + officiers de fonction du centre
            $signataires = $this->usersInstitutionAvecPermission($codeInstitution, $permissionLibTechnique)
                ->merge($this->officiersInstitution($codeInstitution))
                ->unique('code_user')
                ->values();

            Log::channel('sifec')->info('Notification signataires', [
                'code_demande' => $demande->code_demande_document,
                'permission' => $permissionLibTechnique,
                'nb_signataires' => $signataires->count(),
                'code_users' => $signataires->pluck('code_user')->all(),
            ]);

            if ($signataires->isEmpty()) {
                Log::channel('sifec')->warning('[DemandeDocument][Notification] Aucun signataire à notifier.', [
                    'code_demande' => $demande->code_demande_document,
                    'code_institution' => $codeInstitution,
                    'permission' => $permissionLibTechnique,
                ]);

                return;
            }

            $signataires->loadMissing('personne');
            $sifec = app(Sifec::class);

            foreach ($signataires as $signataire) {
                $signataire->notify(new DocumentPretPourSignature($demande));

                $to = $this->emailNotificationUser($signataire);
                if ($to !== null) {
                    Log::channel('sifec')->info('Envoi e-mail document prêt pour signature (signataire)', [
                        'code_demande' => $demande->code_demande_document,
                        'code_user' => $signataire->code_user,
                        'to_masque' => preg_replace('/(^.).*(@.*$)/', '$1…$2', $to),
                    ]);
                    try {
                        Mail::to($to)->send(new DocumentPretPourSignatureMail($demande, $signataire));
                        Log::channel('sifec')->info('E-mail document prêt pour signature : envoi SMTP terminé sans exception.', [
                            'code_demande' => $demande->code_demande_document,
                            'code_user' => $signataire->code_user,
                            'to_masque' => preg_replace('/(^.).*(@.*$)/', '$1…$2', $to),
                        ]);
                    } catch (\Throwable $e) {
                        Log::channel('sifec')->error('Échec envoi e-mail document prêt pour signature', [
                            'code_demande' => $demande->code_demande_document,
                            'code_user' => $signataire->code_user,
                            'to_masque' => preg_replace('/(^.).*(@.*$)/', '$1…$2', $to),
                            'exception' => $e->getMessage(),
                        ]);
                    }
                }

                // Notification SMS
                if ($signataire->personne && $signataire->personne->telephone) {
                    $message = 'SIFEC: Document prêt à signer - '.
                               $demande->getLibelleTypeDocument().
                               ' de '.$demande->getLibelleTypeActe().
                               ' (Acte N° '.$demande->numero_acte.'). '.
                               'Connectez-vous pour signer.';

                    try {
                        $sifec->sendSms($signataire->personne->telephone, $message);
                    } catch (Exception $smsEx) {
                        Log::channel('sifec')->warning('[DemandeDocument][Notification] SMS signataire échoué', [
                            'code_demande' => $demande->code_demande_document,
                            'code_user' => $signataire->code_user,
                            'error' => $smsEx->getMessage(),
                        ]);
                    }
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
