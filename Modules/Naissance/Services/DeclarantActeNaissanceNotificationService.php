<?php

namespace Modules\Naissance\Services;

use App\Sifec\Sifec;
use App\Sifec\SifecFacade;
use Illuminate\Support\Facades\Log;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Notification\Jobs\DeclarantActeDisponibleInformationJob;

/**
 * Notification SMS / e-mail du déclarant après signature électronique de l'acte
 * par l'officier d'état civil (certificat .p12 / GUOT).
 */
class DeclarantActeNaissanceNotificationService
{
    public function notify(ActeNaissance $acte): void
    {
        $acte->loadMissing(['declaration.declarant.contacts', 'institutionUser.institution']);

        $declarant = $acte->declaration?->declarant;
        $contactDeclarant = SifecFacade::contactPourNotification($declarant);

        if ($contactDeclarant !== null) {
            $contactDeclarant = $contactDeclarant->fresh();
        }

        $msisdn = SifecFacade::msisdnFromContact($contactDeclarant);

        if ($msisdn === null) {
            Log::channel('sifec')->warning('SMS déclarant après signature : aucun numéro exploitable', [
                'code' => $acte->code_declaration_naissance,
                'code_declarant' => $declarant?->code_personne,
                'has_contact' => $contactDeclarant !== null,
            ]);

            return;
        }

        $message = $this->buildMessage($acte);
        $codeDeclaration = $acte->code_declaration_naissance;

        Log::channel('sifec')->info('SMS déclarant après signature électronique (planifié)', [
            'code' => $codeDeclaration,
            'code_declarant' => $declarant?->code_personne,
            'contact_id' => $contactDeclarant?->id,
            'telephone_declarant' => $contactDeclarant?->telephone,
            'msisdn' => $msisdn,
            'phone_masque' => SifecFacade::maskMsisdnForLog($msisdn),
            'texte_longueur' => mb_strlen($message),
            'note' => 'Envoi différé après réponse HTTP (hors contexte GUOT).',
        ]);

        // Envoi après la réponse JSON : même processus que Tinkerwell (requête « propre »).
        dispatch(function () use ($msisdn, $message, $codeDeclaration) {
            try {
                Log::channel('sifec')->info('SMS déclarant après signature électronique (envoi)', [
                    'code' => $codeDeclaration,
                    'msisdn' => $msisdn,
                    'phone_masque' => SifecFacade::maskMsisdnForLog($msisdn),
                ]);

                $responseBody = Sifec::sendSms($msisdn, $message);
                $wirepick = Sifec::parseWirepickResponseDetails($responseBody);

                Log::channel('sifec')->info('SMS déclarant après signature électronique (résultat Wirepick)', [
                    'code' => $codeDeclaration,
                    'msisdn' => $msisdn,
                    'phone_masque' => SifecFacade::maskMsisdnForLog($msisdn),
                    'wirepick_status' => $wirepick['status'],
                    'wirepick_msgid' => $wirepick['msgid'],
                    'wirepick_num_sms' => $wirepick['num_sms'],
                ]);
            } catch (\Throwable $e) {
                Log::channel('sifec')->warning('SMS déclarant après signature électronique échoué', [
                    'code' => $codeDeclaration,
                    'error' => $e->getMessage(),
                ]);
            }
        })->afterResponse();

        $emailsDecl = $contactDeclarant?->adressesEmailPourNotification() ?? [];
        if ($emailsDecl === []) {
            return;
        }

        try {
            dispatch(new DeclarantActeDisponibleInformationJob(
                $emailsDecl,
                $message,
                'SIFEC — Acte de naissance disponible'
            ));
        } catch (\Throwable $e) {
            Log::channel('sifec')->warning('E-mail déclarant après signature électronique échoué', [
                'code' => $codeDeclaration,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function buildMessage(ActeNaissance $acte): string
    {
        $temp = (string) config('sifec.sms.templates.actions.acte_naissance');
        $temp = str_replace(':declarant', $acte->declaration->declarant->nomcomplet(), $temp);
        $temp = str_replace(':code_acte_naissance', $acte->niupp ?? '', $temp);
        $temp = str_replace(':libCec', $acte->institutionUser->institution->lib_institution ?? '', $temp);

        return $temp;
    }
}
