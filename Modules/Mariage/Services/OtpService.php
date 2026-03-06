<?php

namespace Modules\Mariage\Services;

use App\Models\Appareil;
use App\Sifec\SifecFacade;
use Modules\Notification\Jobs\SendSmsJob;
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
            dispatch(new SendSmsJob($contact->indicatif . $contact->telephone, $temp));
            dispatch(new ValidationActeMariageJob(
                $user->personne->nomComplet(),
                count($actes),
                $otp,
                $contact->email_professionnelle
            ));
        }

        return $otp;
    }

    public function validerOtpActes($codes, $otp, string $adresseMac = null)
    {
        if ($adresseMac && !Appareil::estAutorise($adresseMac)) {
            return [false, "Appareil non autorisé. Veuillez contacter l'administrateur."];
        }

        $actes = ActeMariage::whereIn("code_declaration_mariage", $codes)->get();
        if ($actes->count() == 0) {
            return [false, "Aucun acte trouvé"];
        }

        $premierActe = $actes->first();

        if ($otp != $premierActe->otp_approbation_mairie) {
            return [false, "Code de validation incorrect"];
        }

        if ($premierActe->otp_expire_at && now()->gt($premierActe->otp_expire_at)) {
            return [false, "Code OTP expiré. Veuillez en demander un nouveau."];
        }

        $user = Auth::user();
        $affectation = $user->affectationActive();
        $cui = $affectation ? $affectation->cui : null;
        $signature = $user->personne->signature;

        foreach ($actes as $am) {
            $am->approbation_mairie = $cui;
            $am->signature_maire = $signature;
            $am->date_heure_approbation_mairie = now();
            $am->otp_expire_at = null;
            $am->save();

            $mouvementService = app(\Modules\Mariage\Services\MouvementMariageService::class);
            $declaration = $am->declaration;
            $mouvementService->ajouterEvenementActe($user, $declaration, 'non_retiré');
        }

        return [true, $actes];
    }
}
