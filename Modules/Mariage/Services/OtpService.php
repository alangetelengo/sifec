<?php

namespace Modules\Mariage\Services;

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
        // Générer un OTP plus stable (8 chiffres)
        $otp = str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);

        // Stocker l'OTP sur tous les actes concernés
        foreach ($actes as $acte) {
            $acte->otp_approbation_mairie = $otp;
            $acte->save();
        }

        // Choix du template selon le nombre d'actes
        if (count($actes) > 1) {
            $temp = config("sifec.sms.templates.actions.validation_multiples_acte_mariages");
        } else {
            $temp = config("sifec.sms.templates.actions.validation_acte_mariages");
        }
        $temp = str_replace(":maire", $user->personne->nom, $temp);
        $temp = str_replace(":code_declaration_mariages", $actes[0]->code_declaration_mariage, $temp);
        $temp = str_replace(":nombre", count($actes), $temp);
        $temp = str_replace(":code_otp", $otp, $temp);

        // Envoyer le SMS
        $contact = $user->personne->contacts->first();
        if ($contact) {
            SifecFacade::sendSms($contact->indicatif . $contact->telephone, $temp);
            dispatch(new SendSmsJob($contact->indicatif . $contact->telephone, $temp));
            dispatch(new ValidationActeMariageJob($user->personne->nomComplet(), count($actes), $otp, $contact->email_professionnelle));
        }

        return $otp;
    }

    public function validerOtpActes($codes, $otp)
    {
        $actes = ActeMariage::whereIn("code_declaration_mariage", $codes)->get();
        if ($actes->count() == 0) {
            return [false, "Aucun acte trouvé"];
        }

        if ($otp != $actes->first()->otp_approbation_mairie) {
            return [false, "Code de validation incorrect ou expiré"];
        }

        // Mettre à jour chaque acte validé
        $user = Auth::user();
        $affectation = $user->affectationActive();
        $cui = $affectation ? $affectation->cui : null;
        $signature = $user->personne->signature;
        foreach ($actes as $am) {
            $am->approbation_mairie = $cui;
            $am->signature_maire = $signature;
            $am->date_heure_approbation_mairie = now();
            $am->save();

            // Ajout du mouvement MOUV_0015 (Acte produit non rétiré)
            $mouvementService = app(\Modules\Mariage\Services\MouvementMariageService::class);
            $declaration = $am->declaration;
            $mouvementService->ajouterEvenementActe($user, $declaration, 'non_retiré');
        }

        return [true, $actes];
    }
}
