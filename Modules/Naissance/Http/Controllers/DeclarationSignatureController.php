<?php

namespace Modules\Naissance\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Naissance\Entities\Declarationnaissance;
use Modules\Naissance\Services\DeclarationNaissanceSignatureService;
use Modules\Naissance\Services\MouvementService;
use Modules\Notification\Notifications\DeclarationEnvoyeeCentreNotification;
use Modules\Notification\Services\NotificationService;
use Modules\Referentiel\Entities\Institution;

/**
 * Signature électronique .p12 du certificat de naissance (formation sanitaire → envoi CEC)
 * et de la déclaration de naissance (confirmation CEC, prérequis à la génération de l'acte).
 */
class DeclarationSignatureController extends Controller
{
    public function __construct(
        private DeclarationNaissanceSignatureService $signature,
        private MouvementService $mouvementService,
    ) {}

    /**
     * Étape 1 : préparer les empreintes PDF à signer localement.
     */
    public function prepare(Request $request)
    {
        $phase = $this->resolvePhase($request);
        if ($phase === null) {
            return response()->json(['code' => '180', 'message' => 'Phase de signature invalide.']);
        }

        $codes = $this->resolveCodes($request);
        if ($codes === []) {
            return response()->json(['code' => '180', 'message' => 'Aucun document sélectionné.']);
        }

        $result = $this->signature->prepare(Auth::user(), $codes, $phase);

        return response()->json([
            'code' => $result['ok'] ? '200' : '183',
            'message' => $result['message'],
            'token' => $result['token'] ?? null,
            'expected_serial' => $result['expected_serial'] ?? null,
            'items' => $result['items'] ?? [],
        ]);
    }

    /**
     * Étape 3 : vérifier les signatures, cacheter (L3), puis exécuter l'action métier
     * (envoi au CEC pour la formation sanitaire, confirmation du dossier pour le CEC).
     */
    public function finalize(Request $request)
    {
        $phase = $this->resolvePhase($request);
        if ($phase === null) {
            return response()->json(['code' => '180', 'message' => 'Phase de signature invalide.']);
        }

        $token = (string) $request->input('token', '');
        $signatures = $request->input('signatures', []);
        if (! is_array($signatures)) {
            $signatures = [];
        }
        $observation = $request->input('observation');

        $result = $this->signature->finalize(
            Auth::user(),
            $token,
            $signatures,
            $phase,
            $request->ip(),
            $request->userAgent()
        );

        if (! $result['ok']) {
            return response()->json(['code' => '183', 'message' => $result['message'], 'signed' => 0]);
        }

        // Action métier consécutive à la signature.
        $workflowErrors = [];
        foreach ($result['codes'] as $code) {
            try {
                if ($phase === DeclarationNaissanceSignatureService::PHASE_FS) {
                    $this->envoyerAuCentre($code, $observation);
                } else {
                    $this->confirmerAuCentre($code, $observation);
                }
            } catch (Exception $e) {
                Log::channel('sifec')->error('Action post-signature naissance', [
                    'code' => $code,
                    'phase' => $phase,
                    'error' => $e->getMessage(),
                ]);
                $workflowErrors[] = $code.': '.$e->getMessage();
            }
        }

        $message = $phase === DeclarationNaissanceSignatureService::PHASE_FS
            ? "Certificat de naissance signé et envoyé au centre d'état civil."
            : 'Déclaration de naissance signée et dossier confirmé.';

        if ($workflowErrors !== []) {
            $message .= ' Signature enregistrée, mais action suivante en échec : '.implode(' | ', $workflowErrors);
        }

        return response()->json([
            'code' => '200',
            'message' => $message,
            'signed' => $result['signed'],
        ]);
    }

    private function envoyerAuCentre(string $code, ?string $observation): void
    {
        $dn = Declarationnaissance::findOrFail($code);

        [$ok, $statutResult] = $this->mouvementService->envoyerDeclaration(
            Auth::user(),
            $dn,
            'MOUV_0035',
            'Envoyée',
            $observation
        );

        if (! $ok) {
            throw new Exception($statutResult ?: "Échec de l'envoi au centre d'état civil.");
        }

        $institutionDestinataire = Institution::find($dn->code_institution_destinataire);
        if ($institutionDestinataire !== null) {
            NotificationService::notifierAgentsInstitution(
                $institutionDestinataire,
                new DeclarationEnvoyeeCentreNotification($dn, $institutionDestinataire, 'envoyée')
            );
        }
    }

    private function confirmerAuCentre(string $code, ?string $observation): void
    {
        $dn = Declarationnaissance::findOrFail($code);
        $affectation = Auth::user()->affectationActive();

        [$ok, $result] = $this->mouvementService->confirmerDeclarationNaissance(
            $affectation,
            $dn,
            'Confirmée',
            null,
            $observation
        );

        if (! $ok) {
            throw new Exception($result ?: 'Échec de la confirmation du dossier.');
        }
    }

    private function resolvePhase(Request $request): ?string
    {
        $phase = (string) $request->input('phase', '');

        return in_array($phase, [
            DeclarationNaissanceSignatureService::PHASE_FS,
            DeclarationNaissanceSignatureService::PHASE_CEC,
        ], true) ? $phase : null;
    }

    /**
     * @return list<string>
     */
    private function resolveCodes(Request $request): array
    {
        $codes = $request->input('codes', []);
        if (! is_array($codes) || $codes === []) {
            $single = (string) $request->input('code_declaration_naissance', '');
            $codes = $single !== '' ? [$single] : [];
        }

        return array_values(array_unique(array_filter(array_map('strval', $codes))));
    }
}
