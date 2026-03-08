<?php

namespace Modules\Naissance\Services;

use App\Sifec\SifecFacade;
use Modules\Notification\Jobs\SendSmsJob;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Notification\Jobs\ValidationacteNaissanceJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OtpService
{
    public function envoyerOtpValidationActes($user, $actes)
    {
        $otp = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
        $expireAt = now()->addMinute();

        foreach ($actes as $acte) {
            $acte->otp_approbation_mairie = $otp;
            $acte->otp_expire_at = $expireAt;
            $acte->save();
        }

        if (count($actes) > 1) {
            $temp = config("sifec.sms.templates.actions.validation_multiples_acte_naissances");
        } else {
            $temp = config("sifec.sms.templates.actions.validation_acte_naissance");
        }
        $temp = str_replace(":maire", $user->personne->nom, $temp);
        $temp = str_replace(":nombre", count($actes), $temp);
        $temp = str_replace(":code_otp", $otp, $temp);

        $contact = $user->personne->contacts->first();
        if ($contact) {
            SifecFacade::sendSms($contact->indicatif . $contact->telephone, $temp);
            dispatch(new SendSmsJob($contact->indicatif . $contact->telephone, $temp));
            dispatch(new ValidationacteNaissanceJob(
                $user->personne->nomComplet(),
                count($actes),
                $otp,
                $contact->email_professionnelle
            ));
        }

        return $otp;
    }

    public function validerOtpActes($codes, $otp, ?string $ipAddress = null, ?string $userAgent = null)
    {
        // ── 1. Chargement des actes ───────────────────────────────────────
        $actes = ActeNaissance::whereIn("code_declaration_naissance", $codes)->get();
        if ($actes->count() == 0) {
            return [false, "Aucun acte trouvé"];
        }

        $premierActe = $actes->first();

        // ── 3. Anti-replay : acte déjà validé ─────────────────────────────
        if (!is_null($premierActe->approbation_mairie)) {
            return [false, "Cet acte a déjà été validé."];
        }

        // ── 4. OTP null → expiré/purgé par le scheduler ──────────────────
        if (is_null($premierActe->otp_approbation_mairie)) {
            return [false, "Code OTP expiré. Veuillez en demander un nouveau."];
        }

        // ── 5. Expiration temporelle (1 minute) ───────────────────────────
        if ($premierActe->otp_expire_at && now()->gt($premierActe->otp_expire_at)) {
            return [false, "Code OTP expiré (1 minute). Veuillez en demander un nouveau."];
        }

        // ── 6. Correspondance OTP ─────────────────────────────────────────
        if ($otp !== $premierActe->otp_approbation_mairie) {
            return [false, "Code de validation incorrect."];
        }

        // ── 7. Vérification des champs de sécurité de l'officier ─────────
        $user        = Auth::user();
        $affectation = $user->affectationActive();
        $cui         = $affectation ? $affectation->cui : null;
        $signature   = $user->personne->signature ?? null;

        if (is_null($cui)) {
            return [false, "Validation impossible : aucune affectation active trouvée pour cet officier."];
        }
        if (is_null($signature)) {
            return [false, "Validation impossible : la signature numérique de l'officier est absente. Veuillez contacter l'administrateur."];
        }

        // ── 8. Application de la signature sur chaque acte ───────────────
        foreach ($actes as $an) {
            $an->approbation_mairie            = $cui;
            $an->signature_mairie              = $signature;
            $an->date_heure_approbation_mairie = now();
            // OTP conservé pour traçabilité QR code — expiry nullé pour bloquer réutilisation
            $an->otp_expire_at                 = null;
            // Traçabilité forensique : IP de connexion + plateforme navigateur
            $an->adresse_mac_approbation       = $ipAddress;
            $an->nom_appareil_approbation      = $this->simplifierUserAgent($userAgent);
            $an->save();

            $mouvementService = app(\Modules\Naissance\Services\MouvementService::class);
            $mouvementService->ajouterEvenementActe($user, $an->declaration, 'non_retiré');
        }

        // ── 8. Notification du déclarant ─────────────────────────────────
        foreach ($actes as $an) {
            $contactDeclarant = $an->declaration->declarant->contacts->first();
            if ($contactDeclarant !== null) {
                $temp = config("sifec.sms.templates.actions.acte_naissance");
                $temp = str_replace(":declarant",         $an->declaration->declarant->nomcomplet(), $temp);
                $temp = str_replace(":code_acte_naissance", $an->niupp, $temp);
                $temp = str_replace(":libCec",            $an->institutionUser->institution->lib_institution, $temp);
                SifecFacade::sendSms($contactDeclarant->indicatif . $contactDeclarant->telephone, $temp);
                dispatch(new SendSmsJob($contactDeclarant->indicatif . $contactDeclarant->telephone, $temp));
                dispatch(new ValidationacteNaissanceJob(
                    $user->personne->nomComplet(),
                    $an->count(),
                    $otp,
                    $contactDeclarant->email_professionnelle
                ));
            }
        }

        return [true, $actes];
    }

    /**
     * Extrait une description lisible du navigateur et du système d'exploitation
     * à partir du User-Agent HTTP pour la traçabilité forensique.
     */
    private function simplifierUserAgent(?string $ua): ?string
    {
        if (!$ua) return null;

        if (preg_match('/Android/i', $ua))          $os = 'Android';
        elseif (preg_match('/iPhone|iPad/i', $ua))  $os = 'iOS';
        elseif (preg_match('/Windows/i', $ua))      $os = 'Windows';
        elseif (preg_match('/Macintosh|Mac OS/i', $ua)) $os = 'macOS';
        elseif (preg_match('/Linux/i', $ua))        $os = 'Linux';
        else                                         $os = 'Inconnu';

        if (preg_match('/Edg\//i', $ua))                                    $browser = 'Edge';
        elseif (preg_match('/OPR\//i', $ua))                                $browser = 'Opera';
        elseif (preg_match('/Chrome\//i', $ua) && !preg_match('/Chromium/i', $ua)) $browser = 'Chrome';
        elseif (preg_match('/Firefox\//i', $ua))                            $browser = 'Firefox';
        elseif (preg_match('/Safari\//i', $ua) && !preg_match('/Chrome/i', $ua))   $browser = 'Safari';
        else                                                                  $browser = 'Navigateur';

        return "{$browser} / {$os}";
    }
}
