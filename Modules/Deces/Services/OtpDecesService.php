<?php

namespace Modules\Deces\Services;

use App\Sifec\SifecFacade;
use Exception;
use Modules\Notification\Jobs\SendSmsJob;
use Modules\Deces\Entities\ActeDeces;
use Modules\Notification\Jobs\ValidationacteDecesJob;
use Modules\Authentification\Entities\Affectation;
use Modules\Authentification\Entities\InstitutionUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OtpDecesService
{
    public function envoyerOtpValidationActes($user, $actes)
    {
        $otp = substr(time(),2);

        // Stocker l'OTP sur tous les actes concernés
        foreach ($actes as $acte) {
            $acte->otp_approbation_pompe_funebre = $otp;
            $acte->save();
        }

        // Choix du template selon le nombre d'actes
        if (count($actes) > 1) {
            $temp = config("sifec.sms.templates.actions.validation_multiples_acte_deces");
        } else {
            $temp = config("sifec.sms.templates.actions.validation_acte_deces");
            $temp = str_replace(":code_acte_deces", $actes[0]->code_acte_deces, $temp);
        }
        $temp = str_replace(":pompe_funebre", $user->personne->nom, $temp);
        $temp = str_replace(":nombre", count($actes), $temp);
        $temp = str_replace(":code_otp", $otp, $temp);

        // Envoyer le SMS
        // $contact = $user->personne->contacts->first();
        $contact = $user->personne->contacts->last();
        if ($contact) {
            SifecFacade::sendSms($contact->indicatif . $contact->telephone, $temp);
            dispatch(new SendSmsJob($contact->indicatif . $contact->telephone, $temp));
            dispatch(new ValidationacteDecesJob($user->personne->nomComplet(), count($actes), $otp, $contact->email_professionnelle));
        }

        return $otp;
    }

    /**
     * Valide les OTP pour une liste d'actes de décès
     *
     * @param array $codes Liste des codes de déclaration
     * @param string $otp Code OTP à valider
     * @return array [bool $success, mixed $result]
     */
    public function validerOtpActes($codes, $otp)
    {
        $actes = ActeDeces::whereIn("code_declaration_deces", $codes)->get();
        if ($actes->count() == 0) {
            return [false, "Aucun acte trouvé"];
        }
        if ($otp != $actes->last()->otp_approbation_pompe_funebre) {
            return [false, "Code de validation incorrect ou expiré"];
        }
        // Mettre à jour chaque acte validé
        $user = Auth::user();
        $affectation = $user->affectationActive();
        $cui = $affectation ? $affectation->cui : null;
        $signature = $user->personne->signature;
        foreach ($actes as $ad) {
            $ad->approbation_pompe_funebre = $cui;
            $ad->signature_pompe_funebre = $signature;
            $ad->date_heure_approbation_pompe_funebre = now();
            $ad->save();

            // Ajout du mouvement MOUV_0015 (Acte produit non rétiré)
            $mouvementService = app(\Modules\Deces\Services\MouvementService::class);
            $declaration = $ad->declaration;
            $mouvementService->ajouterEvenementActe($user, $declaration, 'non_retiré');
        }
        // Envoi de notification au déclarant pour chaque acte validé
        foreach ($actes as $ad) {
            $contactDeclarant = $ad->declaration->declarant->contacts->last();
            if ($contactDeclarant != null) {
                $temp = config("sifec.sms.templates.actions.acte_deces");
                $temp = str_replace(":declarant", $ad->declaration->declarant->nomcomplet(), $temp);
                $temp = str_replace(":code_acte_deces", $ad->code_acte_deces, $temp);
                $temp = str_replace(":defunt",$ad->declaration->defunt->nomcomplet(), $temp);
                $temp = str_replace(":libCec", $ad->institution->lib_institution, $temp);
                SifecFacade::sendSms($contactDeclarant->indicatif ?? "+242" . $contactDeclarant->telephone, $temp);
                dispatch(new SendSmsJob($contactDeclarant->indicatif ?? "+242" . $contactDeclarant->telephone, $temp));
                dispatch(new ValidationacteDecesJob(Auth::user()->personne->nomComplet(), $ad->count(), $otp, Auth::user()->email));
            }
        }
        return [true, $actes];
    }
}
