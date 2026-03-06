<?php

namespace Modules\Naissance\Services;

use App\Models\Appareil;
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

    public function validerOtpActes($codes, $otp, string $adresseMac = null)
    {
        if ($adresseMac && !Appareil::estAutorise($adresseMac)) {
            return [false, "Appareil non autorisé. Veuillez contacter l'administrateur."];
        }

        $actes = ActeNaissance::whereIn("code_declaration_naissance", $codes)->get();
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

        foreach ($actes as $an) {
            $an->approbation_mairie = $cui;
            $an->signature_mairie = $signature;
            $an->date_heure_approbation_mairie = now();
            $an->otp_expire_at = null;
            $an->save();

            $mouvementService = app(\Modules\Naissance\Services\MouvementService::class);
            $declaration = $an->declaration;
            $mouvementService->ajouterEvenementActe($user, $declaration, 'non_retiré');
        }

        foreach ($actes as $an) {
            $contactDeclarant = $an->declaration->declarant->contacts->first();
            if ($contactDeclarant != null) {
                $temp = config("sifec.sms.templates.actions.acte_naissance");
                $temp = str_replace(":declarant", $an->declaration->declarant->nomcomplet(), $temp);
                $temp = str_replace(":code_acte_naissance", $an->niupp, $temp);
                $temp = str_replace(":libCec", $an->institutionUser->institution->lib_institution, $temp);
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
}
