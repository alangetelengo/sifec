<?php

namespace Modules\Deces\Services;

use App\Models\Appareil;
use App\Sifec\SifecFacade;
use Modules\Notification\Jobs\SendSmsJob;
use Modules\Deces\Entities\ActeDeces;
use Modules\Notification\Jobs\ValidationacteDecesJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OtpDecesService
{
    public function envoyerOtpValidationActes($user, $actes)
    {
        $otp = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
        $expireAt = now()->addMinute();

        foreach ($actes as $acte) {
            $acte->otp_approbation_pompe_funebre = $otp;
            $acte->otp_expire_at = $expireAt;
            $acte->save();
        }

        if (count($actes) > 1) {
            $temp = config("sifec.sms.templates.actions.validation_multiples_acte_deces");
        } else {
            $temp = config("sifec.sms.templates.actions.validation_acte_deces");
            $temp = str_replace(":code_acte_deces", $actes[0]->code_acte_deces, $temp);
        }
        $temp = str_replace(":pompe_funebre", $user->personne->nom, $temp);
        $temp = str_replace(":nombre", count($actes), $temp);
        $temp = str_replace(":code_otp", $otp, $temp);

        $contact = $user->personne->contacts->last();
        if ($contact) {
            SifecFacade::sendSms($contact->indicatif . $contact->telephone, $temp);
            dispatch(new SendSmsJob($contact->indicatif . $contact->telephone, $temp));
            dispatch(new ValidationacteDecesJob(
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

        $actes = ActeDeces::whereIn("code_declaration_deces", $codes)->get();
        if ($actes->count() == 0) {
            return [false, "Aucun acte trouvé"];
        }

        $premierActe = $actes->last();

        if ($otp != $premierActe->otp_approbation_pompe_funebre) {
            return [false, "Code de validation incorrect"];
        }

        if ($premierActe->otp_expire_at && now()->gt($premierActe->otp_expire_at)) {
            return [false, "Code OTP expiré. Veuillez en demander un nouveau."];
        }

        $user = Auth::user();
        $affectation = $user->affectationActive();
        $cui = $affectation ? $affectation->cui : null;
        $signature = $user->personne->signature;

        foreach ($actes as $ad) {
            $ad->approbation_pompe_funebre = $cui;
            $ad->signature_pompe_funebre = $signature;
            $ad->date_heure_approbation_pompe_funebre = now();
            $ad->otp_expire_at = null;
            $ad->save();

            $mouvementService = app(\Modules\Deces\Services\MouvementService::class);
            $declaration = $ad->declaration;
            $mouvementService->ajouterEvenementActe($user, $declaration, 'non_retiré');
        }

        foreach ($actes as $ad) {
            $contactDeclarant = $ad->declaration->declarant->contacts->last();
            if ($contactDeclarant != null) {
                $temp = config("sifec.sms.templates.actions.acte_deces");
                $temp = str_replace(":declarant", $ad->declaration->declarant->nomcomplet(), $temp);
                $temp = str_replace(":code_acte_deces", $ad->code_acte_deces, $temp);
                $temp = str_replace(":defunt", $ad->declaration->defunt->nomcomplet(), $temp);
                $temp = str_replace(":libCec", $ad->institution->lib_institution, $temp);
                SifecFacade::sendSms($contactDeclarant->indicatif ?? "+242" . $contactDeclarant->telephone, $temp);
                dispatch(new SendSmsJob($contactDeclarant->indicatif ?? "+242" . $contactDeclarant->telephone, $temp));
                dispatch(new ValidationacteDecesJob(
                    $user->personne->nomComplet(),
                    $ad->count(),
                    $otp,
                    $contactDeclarant->email_professionnelle
                ));
            }
        }

        return [true, $actes];
    }
}
