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
        $otp = substr(time(),2);

        // Stocker l'OTP sur tous les actes concernés
        foreach ($actes as $acte) {
            $acte->otp_approbation_mairie = $otp;
            $acte->save();
        }

        // Choix du template selon le nombre d'actes
        if (count($actes) > 1) {
            $temp = config("sifec.sms.templates.actions.validation_multiples_acte_naissances");
        } else {
            $temp = config("sifec.sms.templates.actions.validation_acte_naissance");
        }
        $temp = str_replace(":maire", $user->personne->nom, $temp);
        $temp = str_replace(":nombre", count($actes), $temp);
        $temp = str_replace(":code_otp", $otp, $temp);

        // Envoyer le SMS
        $contact = $user->personne->contacts->first();
        if ($contact) {
            SifecFacade::sendSms($contact->indicatif . $contact->telephone, $temp);
            // SifecFacade::infobipSms($contact->indicatif . $contact->telephone, $temp);
            dispatch(new SendSmsJob($contact->indicatif . $contact->telephone, $temp));
            dispatch(new ValidationacteNaissanceJob($user->personne->nomComplet(), count($actes), $otp, $contact->email_professionnelle));
        }

        return $otp;
    }

    public function validerOtpActes($codes, $otp)
    {
        $actes = ActeNaissance::whereIn("code_declaration_naissance", $codes)->get();
        if ($actes->count() == 0) {
            return [false, "Aucun acte trouvé"];
        }

        // Vérifier que tous les actes ont le même OTP
        $premierActe = $actes->first();
        if ($otp != $premierActe->otp_approbation_mairie) {
            return [false, "Code de validation incorrect ou expiré"];
        }
        // Mettre à jour chaque acte validé
        $user = Auth::user();
        $affectation = $user->affectationActive();
        $cui = $affectation ? $affectation->cui : null;
        $signature = $user->personne->signature;
        foreach ($actes as $an) {
            $an->approbation_mairie = $cui;
            $an->signature_mairie = $signature;
            $an->date_heure_approbation_mairie = now();
            $an->save();

            // Ajout du mouvement MOUV_0015 (Acte produit non rétiré)
            $mouvementService = app(\Modules\Naissance\Services\MouvementService::class);
            $declaration = $an->declaration;
            $mouvementService->ajouterEvenementActe($user, $declaration, 'non_retiré');
        }
        // Envoi de notification au déclarant pour chaque acte validé
        foreach ($actes as $an) {
            $contactDeclarant = $an->declaration->declarant->contacts->first();
             // Envoyer le SMS

            if ($contactDeclarant != null) {
                $temp = config("sifec.sms.templates.actions.acte_naissance");
                $temp = str_replace(":declarant", $an->declaration->declarant->nomcomplet(), $temp);
                $temp = str_replace(":code_acte_naissance", $an->niupp, $temp);
                $temp = str_replace(":libCec", $an->institutionUser->institution->lib_institution, $temp);
                SifecFacade::sendSms($contactDeclarant->indicatif . $contactDeclarant->telephone, $temp);
                // SifecFacade::infobipSms($contactDeclarant->indicatif . $contactDeclarant->telephone, $temp);
                dispatch(new SendSmsJob($contactDeclarant->indicatif . $contactDeclarant->telephone, $temp));
                dispatch(new ValidationacteNaissanceJob(Auth::user()->personne->nomcomplet(), $an->count(), $otp, Auth::user()->personne->email));
            }
        }
        return [true, $actes];
    }
}
