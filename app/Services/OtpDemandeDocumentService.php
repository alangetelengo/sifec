<?php

namespace App\Services;

use App\Events\DemandeDocumentEvent;
use App\Jobs\ValidationDemandeDocumentJob;
use App\Models\DemandeDocumentConfig;
use App\Models\User;
use App\Sifec\SifecFacade;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\Mobile\Entities\DemandeDocument;
use Modules\Notification\Jobs\DeclarantActeDisponibleInformationJob;
use Throwable;

class OtpDemandeDocumentService
{
    /**
     * Durée de validité de l'OTP en minutes
     */
    const OTP_VALIDITY_MINUTES = 2;

    /**
     * Générer un OTP pour un batch de demandes
     *
     * @param  array  $codesDemandes  Tableau des codes de demandes à signer
     * @return array [success, message, otp]
     */
    public function genererOtp(array $codesDemandes): array
    {
        if (empty($codesDemandes)) {
            return [false, 'Aucune demande sélectionnée.', null];
        }

        // Récupérer les demandes
        $demandes = DemandeDocument::whereIn('code_demande_document', $codesDemandes)
            ->where('statut', 'En attente de signature')
            ->get();

        if ($demandes->isEmpty()) {
            return [false, 'Aucune demande valide en attente de signature trouvée.', null];
        }

        // Vérifier que l'utilisateur est un officier d'état civil
        $user = Auth::user();
        $user->loadMissing('personne');
        $affectation = $user->affectationActive();
        $cui = $affectation ? $affectation->cui : null;

        if (is_null($cui)) {
            return [false, 'Aucune affectation active trouvée pour cet officier.', null];
        }

        // Image de paraphe optionnelle : la délivrance reste valide avec nom + OTP / signature électronique

        // Générer un code OTP à 6 chiffres
        $otp = sprintf('%06d', random_int(0, 999999));
        $expireAt = now()->addMinutes(self::OTP_VALIDITY_MINUTES);

        // Appliquer l'OTP à toutes les demandes du batch
        foreach ($demandes as $demande) {
            $demande->otp_code = $otp;
            $demande->otp_expire_at = $expireAt;
            $demande->save();
        }

        // Envoyer l'OTP par SMS et email (comme validation actes)
        try {
            $this->envoyerOtpParSmsEtEmail($user, $otp, $demandes->count(), $demandes);

            Log::channel('sifec')->info('OTP généré et envoyé', [
                'user' => $user->code_user,
                'nb_demandes' => $demandes->count(),
                'expire_at' => $expireAt->toDateTimeString(),
            ]);

            return [true, 'Code OTP généré et envoyé par SMS et email.', $otp];

        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur envoi OTP', [
                'user' => $user->code_user,
                'nb_demandes' => $demandes->count(),
                'error' => $e->getMessage(),
            ]);

            return [false, "Erreur lors de l'envoi de l'OTP : ".$e->getMessage(), null];
        }
    }

    /**
     * Envoyer l'OTP par SMS et email au signataire
     * (Même logique que OtpService pour actes de naissance)
     */
    private function envoyerOtpParSmsEtEmail(User $signataire, string $otp, int $nbDemandes, $demandes): void
    {
        $personne = $signataire->personne;

        if (! $personne) {
            throw new Exception("Aucune fiche personne associée à l'utilisateur : impossible d'envoyer l'OTP.");
        }

        $contacts = $personne->contacts()->orderBy('id')->get();

        if ($contacts->isEmpty()) {
            throw new Exception("Aucune fiche contact pour le signataire : impossible d'envoyer le code OTP.");
        }

        // 1. TÉLÉPHONE : premier contact actif
        $contact = $contacts->first();
        $telephone = trim(($contact->indicatif ?? '').($contact->telephone ?? ''));

        if ($telephone === '') {
            throw new Exception("Numéro de téléphone du signataire manquant : impossible d'envoyer le code OTP par SMS.");
        }

        // 2. E-MAILS : toutes les adresses email valides
        $emails = $personne->adressesEmailPourNotificationAgregees($contacts);

        if (empty($emails)) {
            throw new Exception("E-mail professionnel ou personnel du signataire manquant ou invalide : impossible d'envoyer le code OTP par e-mail.");
        }

        // 3. ANALYSER LE CONTENU DU BATCH POUR CONSTRUIRE UN MESSAGE PRÉCIS
        $stats = [
            'copies' => [],
            'extraits' => [],
        ];

        foreach ($demandes as $demande) {
            $typeActe = $demande->getLibelleTypeActe();

            if ($demande->estCopie()) {
                if (! isset($stats['copies'][$typeActe])) {
                    $stats['copies'][$typeActe] = 0;
                }
                $stats['copies'][$typeActe]++;
            } elseif ($demande->estExtrait()) {
                if (! isset($stats['extraits'][$typeActe])) {
                    $stats['extraits'][$typeActe] = 0;
                }
                $stats['extraits'][$typeActe]++;
            }
        }

        // 4. CONSTRUIRE LE MESSAGE SELON LE CONTENU
        $messageDocuments = $this->construireMessageDocuments($stats);

        $messageSms = "M.(Mme) {$personne->nomcomplet()}, votre code pour signer {$messageDocuments} est : {$otp}. Valide pendant 2 minutes.";

        // 5. ENVOYER SMS
        SifecFacade::sendSms($telephone, $messageSms);

        // 6. ENVOYER EMAIL(S) - Exécution synchrone comme validation actes
        // (si QUEUE_CONNECTION ≠ sync, l'email OTP pourrait ne jamais partir sans worker)
        foreach ($emails as $email) {
            ValidationDemandeDocumentJob::dispatchSync(
                $personne->nomcomplet(),
                $nbDemandes,
                $otp,
                $email
            );
        }

        Log::channel('sifec')->info('OTP envoyé par SMS et email', [
            'signataire' => $signataire->code_user,
            'telephone' => $telephone,
            'emails' => $emails,
            'nb_demandes' => $nbDemandes,
            'message_docs' => $messageDocuments,
        ]);
    }

    /**
     * Construire le message détaillé des documents à signer
     */
    private function construireMessageDocuments(array $stats): string
    {
        $parties = [];

        // CAS 1 : Un seul type d'acte (le plus fréquent)
        $tousLesTypesActes = array_unique(array_merge(
            array_keys($stats['copies']),
            array_keys($stats['extraits'])
        ));

        if (count($tousLesTypesActes) === 1) {
            $typeActe = strtolower($tousLesTypesActes[0]);
            $nbCopies = array_sum($stats['copies']);
            $nbExtraits = array_sum($stats['extraits']);

            // Sous-cas 1.1 : Seulement des copies
            if ($nbCopies > 0 && $nbExtraits === 0) {
                return "{$nbCopies} copie(s) d'acte de {$typeActe}";
            }

            // Sous-cas 1.2 : Seulement des extraits
            if ($nbExtraits > 0 && $nbCopies === 0) {
                return "{$nbExtraits} extrait(s) d'acte de {$typeActe}";
            }

            // Sous-cas 1.3 : Copie(s) ET extrait(s) du même type d'acte
            if ($nbCopies > 0 && $nbExtraits > 0) {
                return "{$nbCopies} copie(s) et {$nbExtraits} extrait(s) d'acte de {$typeActe}";
            }
        }

        // CAS 2 : Plusieurs types d'actes (générique)
        $nbTotalCopies = array_sum($stats['copies']);
        $nbTotalExtraits = array_sum($stats['extraits']);

        if ($nbTotalCopies > 0 && $nbTotalExtraits === 0) {
            return "{$nbTotalCopies} copie(s) d'actes";
        }

        if ($nbTotalExtraits > 0 && $nbTotalCopies === 0) {
            return "{$nbTotalExtraits} extrait(s) d'actes";
        }

        if ($nbTotalCopies > 0 && $nbTotalExtraits > 0) {
            return "{$nbTotalCopies} copie(s) et {$nbTotalExtraits} extrait(s) d'actes";
        }

        // Fallback (ne devrait jamais arriver)
        $nbTotal = $nbTotalCopies + $nbTotalExtraits;

        return "{$nbTotal} demande(s) de document";
    }

    /**
     * Valider la signature avec OTP
     */
    public function validerOtpEtSigner(string $otp, string $ipAddress, string $userAgent): array
    {
        $otp = trim($otp);

        if (empty($otp)) {
            return [false, 'Le code OTP est requis.'];
        }

        // Récupérer les demandes avec cet OTP encore valide
        $demandes = DemandeDocument::where('otp_code', $otp)
            ->where('otp_expire_at', '>', now())
            ->where('statut', 'En attente de signature')
            ->get();

        if ($demandes->isEmpty()) {
            return [false, 'Code OTP invalide ou expiré.'];
        }

        // Vérifier l'officier
        $user = Auth::user();
        $user->loadMissing('personne');
        $affectation = $user->affectationActive();
        $cui = $affectation ? $affectation->cui : null;
        $signature = filled($user->personne?->signature) ? (string) $user->personne->signature : null;

        if (is_null($cui)) {
            return [false, 'Validation impossible : aucune affectation active trouvée pour cet officier.'];
        }

        $listePourNotif = [];

        try {
            DB::transaction(function () use ($demandes, $signature, $cui, $ipAddress, $userAgent, &$listePourNotif) {
                $docService = app(DemandeDocumentService::class);

                $moisValidite = DemandeDocumentConfig::validiteEnMois();

                foreach ($demandes as $demande) {
                    $ancienStatut = $demande->statut;

                    // Chemin image paraphe si présent ; sinon PDF avec nom du signataire seul
                    $demande->signature_officier = $signature;
                    $demande->code_signataire = $cui;
                    $demande->date_signature = now();
                    $demande->document_valide_de = $demande->date_signature->copy()->startOfDay();
                    $demande->document_valide_jusquau = $demande->date_signature->copy()->addMonths($moisValidite)->endOfDay();
                    $demande->statut = 'Traitée';
                    $demande->otp_expire_at = null;
                    $demande->ip_signature = $ipAddress;
                    $demande->user_agent_signature = $this->simplifierUserAgent($userAgent);

                    $demande->save();

                    // PDF avec image de signature : obligatoire avant envoi du mail avec pièce jointe
                    $docService->regenererPdfApresSignature($demande);

                    $listePourNotif[] = [
                        'code' => $demande->code_demande_document,
                        'ancien' => $ancienStatut,
                    ];
                }
            });
        } catch (Throwable $e) {
            Log::channel('sifec')->error('Signature OTP : échec (transaction annulée)', [
                'user' => $user->code_user,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [false, 'La signature n\'a pas pu être finalisée : '.$e->getMessage()];
        }

        $codes = array_column($listePourNotif, 'code');
        $demandesFraiches = DemandeDocument::whereIn('code_demande_document', $codes)
            ->with('institution')
            ->get()
            ->keyBy('code_demande_document');

        foreach ($listePourNotif as $row) {
            $demande = $demandesFraiches->get($row['code']);
            if ($demande === null) {
                continue;
            }
            $this->notifierDemandeurApresSignature($demande);
            event(new DemandeDocumentEvent($demande, $row['ancien'], 'Traitée'));
        }

        Log::channel('sifec')->info('Demandes signées avec succès (PDF régénéré avec signature)', [
            'user' => $user->code_user,
            'nb_demandes' => count($listePourNotif),
            'ip' => $ipAddress,
        ]);

        return [true, sprintf('%d demande(s) signée(s) avec succès.', count($listePourNotif))];
    }

    /**
     * Simplifier le user agent pour le stockage
     */
    private function simplifierUserAgent(string $userAgent): string
    {
        $ua = strtolower($userAgent);

        // Extraire le navigateur principal
        if (str_contains($ua, 'chrome')) {
            return 'Chrome';
        } elseif (str_contains($ua, 'firefox')) {
            return 'Firefox';
        } elseif (str_contains($ua, 'safari')) {
            return 'Safari';
        } elseif (str_contains($ua, 'edge')) {
            return 'Edge';
        } elseif (str_contains($ua, 'opera')) {
            return 'Opera';
        }

        return substr($userAgent, 0, 100);
    }

    /**
     * Notifier le demandeur par SMS et email après signature (.p12 ou legacy OTP).
     */
    public function notifierDemandeurApresSignature(DemandeDocument $demande): void
    {
        $this->notifierDemandeur($demande);
    }

    /**
     * Notifier le demandeur par SMS et email après signature
     * (Même pattern que OtpService pour actes de naissance)
     */
    private function notifierDemandeur(DemandeDocument $demande): void
    {
        try {
            $nomCec = $demande->institution->lib_institution ?? 'Centre d\'état civil';
            $typeDocument = $demande->getLibelleTypeDocument();
            $typeActe = $demande->getLibelleTypeActe();
            $numeroActe = $demande->numero_acte;
            $codeDemande = $demande->code_demande_document;

            $pdfPath = $demande->chemin_document;
            $pdfOk = $pdfPath !== null && $pdfPath !== '' && is_file($pdfPath);
            $nomPieceJointe = 'Document_'.$codeDemande.'.pdf';

            $emailValide = $demande->email_demandeur
                && filter_var($demande->email_demandeur, FILTER_VALIDATE_EMAIL);

            // SMS court (pas de pièce jointe)
            if ($demande->telephone_demander) {
                if ($emailValide) {
                    $sms = "SIFEC : {$typeDocument} ({$typeActe} n°{$numeroActe}) signé. PDF par e-mail. Code {$codeDemande}.";
                } else {
                    $sms = "SIFEC : document signé. Retrait au centre d'état civil {$nomCec}. Code {$codeDemande}. Pièce d'identité requise.";
                }
                SifecFacade::sendSms($demande->telephone_demander, $sms);
            }

            if ($emailValide) {
                $lignes = [
                    'Bonjour,',
                    '',
                    'Votre document signé est joint à cet e-mail.',
                    '',
                    "{$typeDocument} — acte de {$typeActe} (n° {$numeroActe}). Code demande : {$codeDemande}.",
                ];

                if ($demande->estSurSite()) {
                    $lignes[] = '';
                    $lignes[] = "Vous pouvez aussi le retirer au centre d'état civil {$nomCec}.";
                }

                if (! $pdfOk) {
                    $lignes[] = '';
                    $lignes[] = 'La pièce jointe PDF est indisponible : présentez-vous au centre avec votre code demande.';
                }

                $messageEmail = implode("\n", $lignes);

                dispatch(new DeclarantActeDisponibleInformationJob(
                    [$demande->email_demandeur],
                    $messageEmail,
                    'SIFEC — Votre document est disponible',
                    $pdfOk ? $pdfPath : null,
                    $pdfOk ? $nomPieceJointe : null
                ));
            }

            Log::channel('sifec')->info('Demandeur notifié', [
                'code_demande' => $demande->code_demande_document,
                'origine' => $demande->origine_demande,
                'telephone' => $demande->telephone_demander,
                'email' => $demande->email_demandeur,
                'pdf_joint' => $pdfOk,
            ]);

        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur notification demandeur', [
                'code_demande' => $demande->code_demande_document,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Vérifier si une demande a un OTP valide
     */
    public function otpEstValide(DemandeDocument $demande): bool
    {
        return ! empty($demande->otp_code)
            && $demande->otp_expire_at
            && $demande->otp_expire_at > now();
    }

    /**
     * Annuler l'OTP d'un batch de demandes
     */
    public function annulerOtp(array $codesDemandes): bool
    {
        DemandeDocument::whereIn('code_demande_document', $codesDemandes)
            ->update([
                'otp_code' => null,
                'otp_expire_at' => null,
            ]);

        Log::channel('sifec')->info('OTP annulé pour demandes', [
            'codes_demandes' => $codesDemandes,
        ]);

        return true;
    }
}
