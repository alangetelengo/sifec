<?php

namespace Modules\Mariage\Services;

use App\Sifec\SifecFacade;
use Modules\Mariage\Entities\ActeMariage;
use Modules\Notification\Jobs\ValidationActeMariageJob;
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
            $temp = config("sifec.sms.templates.actions.validation_multiples_acte_mariages");
        } else {
            $temp = config("sifec.sms.templates.actions.validation_acte_mariages");
        }
        $temp = str_replace(":maire", $user->personne->nom, $temp);
        $temp = str_replace(":code_declaration_mariages", $actes[0]->code_declaration_mariage, $temp);
        $temp = str_replace(":nombre", count($actes), $temp);
        $temp = str_replace(":code_otp", $otp, $temp);

        $contact = $user->personne->contacts->first();
        if ($contact) {
            SifecFacade::sendSms($contact->indicatif . $contact->telephone, $temp);
            dispatch(new ValidationActeMariageJob(
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
        $actes = ActeMariage::whereIn("code_declaration_mariage", $codes)->get();
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
        foreach ($actes as $am) {
            $am->approbation_mairie            = $cui;
            $am->signature_maire               = $signature;
            $am->date_heure_approbation_mairie = now();
            // OTP conservé pour traçabilité QR code — expiry nullé pour bloquer réutilisation
            $am->otp_expire_at                 = null;
            // Traçabilité forensique : IP de connexion + plateforme navigateur
            $am->adresse_mac_approbation       = $ipAddress;
            $am->nom_appareil_approbation      = $this->simplifierUserAgent($userAgent);
            $am->save();

            $mouvementService = app(\Modules\Mariage\Services\MouvementMariageService::class);
            $mouvementService->ajouterEvenementActe($user, $am->declaration, 'non_retiré');
        }

        return [true, $actes];
    }

    private function simplifierUserAgent(?string $ua): ?string
    {
        if (!$ua) return null;

        if (preg_match('/Android/i', $ua))              $os = 'Android';
        elseif (preg_match('/iPhone|iPad/i', $ua))      $os = 'iOS';
        elseif (preg_match('/Windows/i', $ua))          $os = 'Windows';
        elseif (preg_match('/Macintosh|Mac OS/i', $ua)) $os = 'macOS';
        elseif (preg_match('/Linux/i', $ua))            $os = 'Linux';
        else                                             $os = 'Inconnu';

        if (preg_match('/Edg\//i', $ua))                                            $browser = 'Edge';
        elseif (preg_match('/OPR\//i', $ua))                                        $browser = 'Opera';
        elseif (preg_match('/Chrome\//i', $ua) && !preg_match('/Chromium/i', $ua)) $browser = 'Chrome';
        elseif (preg_match('/Firefox\//i', $ua))                                    $browser = 'Firefox';
        elseif (preg_match('/Safari\//i', $ua) && !preg_match('/Chrome/i', $ua))   $browser = 'Safari';
        else                                                                          $browser = 'Navigateur';

        return "{$browser} / {$os}";
    }
}
