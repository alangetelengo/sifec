<?php

namespace Modules\Deces\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Deces\Services\DeclarationDecesSignatureService;
use Modules\Deces\Services\MouvementService;
use Modules\Notification\Notifications\DeclarationEnvoyeeCentreNotification;
use Modules\Notification\Services\NotificationService;
use Modules\Referentiel\Entities\Institution;

/**
 * Signature électronique .p12 des documents de décès (FS, CH, CEC/PF).
 */
class DeclarationDecesSignatureController extends Controller
{
    public function __construct(
        private DeclarationDecesSignatureService $signature,
        private MouvementService $mouvementService,
    ) {}

    public function prepare(Request $request)
    {
        $phase = $this->resolvePhase($request);
        if ($phase === null) {
            return response()->json(['code' => '180', 'message' => 'Phase de signature invalide.']);
        }

        if (! $this->userCanSignPhase($phase)) {
            return response()->json([
                'code' => '181',
                'message' => "Vous n'êtes pas autorisé à signer ce document.",
            ]);
        }

        $codes = $this->resolveCodes($request);
        if ($codes === []) {
            return response()->json(['code' => '180', 'message' => 'Aucun document sélectionné.']);
        }

        $observation = $request->input('observation');
        $aReprendre = [];
        $aSigner = [];

        foreach ($codes as $code) {
            $declaration = DeclarationDeces::find($code);
            if ($declaration === null) {
                return response()->json(['code' => '183', 'message' => $code.': document introuvable.']);
            }

            if ($this->signature->estSignee($declaration, $phase)) {
                if ($this->signature->workflowIncomplet($declaration, $phase)) {
                    $aReprendre[] = $code;
                } else {
                    return response()->json([
                        'code' => '183',
                        'message' => $code.': document déjà signé et traité.',
                    ]);
                }
            } else {
                $aSigner[] = $code;
            }
        }

        // Reprise pure : signature déjà enregistrée, seule l'action métier a échoué précédemment.
        if ($aReprendre !== [] && $aSigner === []) {
            return $this->reprendreWorkflow($aReprendre, $phase, is_string($observation) ? $observation : null);
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

    public function finalize(Request $request)
    {
        $phase = $this->resolvePhase($request);
        if ($phase === null) {
            return response()->json(['code' => '180', 'message' => 'Phase de signature invalide.']);
        }

        if (! $this->userCanSignPhase($phase)) {
            return response()->json([
                'code' => '181',
                'message' => "Vous n'êtes pas autorisé à signer ce document.",
            ]);
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

        return $this->appliquerWorkflowApresSignature(
            $result['codes'],
            $phase,
            is_string($observation) ? $observation : null,
            $result['signed']
        );
    }

    /**
     * @param  list<string>  $codes
     * @return \Illuminate\Http\JsonResponse
     */
    private function reprendreWorkflow(array $codes, string $phase, ?string $observation)
    {
        $response = $this->appliquerWorkflowApresSignature($codes, $phase, $observation, count($codes));
        $payload = $response->getData(true);
        if (is_array($payload)) {
            $payload['completed'] = (($payload['code'] ?? '') === '200');
            if (($payload['code'] ?? '') === '200') {
                $payload['message'] = match ($phase) {
                    DeclarationDecesSignatureService::PHASE_FS => 'Certificat déjà signé — envoi repris avec succès.',
                    DeclarationDecesSignatureService::PHASE_CH => 'Constatation déjà signée — envoi repris avec succès.',
                    default => 'Document déjà signé — confirmation reprise avec succès.',
                };
            }

            return response()->json($payload);
        }

        return $response;
    }

    /**
     * @param  list<string>  $codes
     * @return \Illuminate\Http\JsonResponse
     */
    private function appliquerWorkflowApresSignature(array $codes, string $phase, ?string $observation, int $signed)
    {
        $workflowErrors = [];
        foreach ($codes as $code) {
            try {
                if ($phase === DeclarationDecesSignatureService::PHASE_CEC) {
                    $this->confirmerDossier($code, $observation);
                } else {
                    $this->envoyerDocument($code, $observation);
                }
            } catch (Exception $e) {
                Log::channel('sifec')->error('Action post-signature décès', [
                    'code' => $code,
                    'phase' => $phase,
                    'error' => $e->getMessage(),
                ]);
                $workflowErrors[] = $code.': '.$e->getMessage();
            }
        }

        $message = match ($phase) {
            DeclarationDecesSignatureService::PHASE_FS => 'Certificat de décès signé et envoyé.',
            DeclarationDecesSignatureService::PHASE_CH => 'Certificat de constatation signé et envoyé.',
            default => 'Document signé et dossier confirmé.',
        };

        if ($workflowErrors !== []) {
            $failMessage = count($workflowErrors) === count($codes)
                ? 'Signature enregistrée, mais l\'action suivante a échoué : '.implode(' | ', $workflowErrors)
                    .' Relancez l\'action pour reprendre uniquement l\'envoi/confirmation.'
                : $message.' Certaines actions ont échoué : '.implode(' | ', $workflowErrors);

            return response()->json([
                'code' => '207',
                'message' => $failMessage,
                'signed' => $signed,
                'retryable' => true,
            ]);
        }

        return response()->json([
            'code' => '200',
            'message' => $message,
            'signed' => $signed,
        ]);
    }

    private function confirmerDossier(string $code, ?string $observation): void
    {
        $declaration = DeclarationDeces::findOrFail($code);
        $affectation = Auth::user()->affectationActive();

        [$ok, $result] = $this->mouvementService->confirmerDeclarationDeces(
            $affectation,
            $declaration,
            'Confirmée',
            null,
            $observation
        );

        if (! $ok) {
            throw new Exception($result ?: 'Échec de la confirmation du dossier.');
        }
    }

    private function envoyerDocument(string $code, ?string $observation): void
    {
        $dd = DeclarationDeces::findOrFail($code);

        $mappingTypeEvenement = [
            'DECLARATION DE DECES' => 'declaration_deces',
            'CERTIFICAT DE CONSTATATION DE DECES' => 'certificat_constatation_deces',
        ];
        $typeEvenement = $mappingTypeEvenement[$dd->type_declaration] ?? 'declaration_deces';

        DB::transaction(function () use ($dd, $typeEvenement, $observation) {
            [$ok, $statutResult] = $this->mouvementService->envoyerDeclaration(
                Auth::user(),
                $dd,
                $typeEvenement,
                'Envoyée',
                $observation
            );

            if (! $ok) {
                throw new Exception($statutResult ?: "Échec de l'envoi.");
            }

            $institutionDestinataire = Institution::find($dd->code_institution_destinataire);
            if ($institutionDestinataire !== null) {
                NotificationService::notifierAgentsInstitution(
                    $institutionDestinataire,
                    new DeclarationEnvoyeeCentreNotification($dd, $institutionDestinataire, 'envoyée')
                );
            }
        });
    }

    private function userCanSignPhase(string $phase): bool
    {
        if (in_array($phase, [
            DeclarationDecesSignatureService::PHASE_FS,
            DeclarationDecesSignatureService::PHASE_CH,
        ], true)) {
            return Gate::allows('module.certificat.deces.signature')
                || Gate::allows('module.certificat.signature');
        }

        return Gate::allows('module.acteDeces.generate');
    }

    private function resolvePhase(Request $request): ?string
    {
        $phase = (string) $request->input('phase', '');

        return in_array($phase, [
            DeclarationDecesSignatureService::PHASE_FS,
            DeclarationDecesSignatureService::PHASE_CH,
            DeclarationDecesSignatureService::PHASE_CEC,
        ], true) ? $phase : null;
    }

    /** @return list<string> */
    private function resolveCodes(Request $request): array
    {
        $codes = $request->input('codes', []);
        if (! is_array($codes) || $codes === []) {
            $single = (string) $request->input('code_declaration_deces', '');
            $codes = $single !== '' ? [$single] : [];
        }

        return array_values(array_unique(array_filter(array_map('strval', $codes))));
    }
}
