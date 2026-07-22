<?php

namespace Modules\Mariage\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Mariage\Entities\DeclarationMariage;
use Modules\Mariage\Services\DeclarationMariageSignatureService;
use Modules\Mariage\Services\MouvementMariageService;

/**
 * Signature électronique .p12 de la déclaration de mariage par le responsable du centre d'état
 * civil, au moment de la confirmation du dossier (prérequis à la génération de l'acte).
 * Étapes : prepare (hash PDF) → signature côté client → finalize (vérif L2 + cachet L3 + confirmation).
 */
class DeclarationMariageSignatureController extends Controller
{
    public function __construct(
        private DeclarationMariageSignatureService $signature,
        private MouvementMariageService $mouvementService,
    ) {}

    /**
     * Étape 1 : préparer les empreintes PDF à signer localement.
     */
    public function prepare(Request $request)
    {
        $codes = $this->resolveCodes($request);
        if ($codes === []) {
            return response()->json(['code' => '180', 'message' => 'Aucun document sélectionné.']);
        }

        $result = $this->signature->prepare(Auth::user(), $codes);

        return response()->json([
            'code' => $result['ok'] ? '200' : '183',
            'message' => $result['message'],
            'token' => $result['token'] ?? null,
            'expected_serial' => $result['expected_serial'] ?? null,
            'items' => $result['items'] ?? [],
        ]);
    }

    /**
     * Étape 3 : vérifier les signatures, cacheter (L3), puis confirmer le dossier.
     */
    public function finalize(Request $request)
    {
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
            $request->ip(),
            $request->userAgent()
        );

        if (! $result['ok']) {
            return response()->json(['code' => '183', 'message' => $result['message'], 'signed' => 0]);
        }

        // Confirmation du dossier consécutive à la signature.
        $workflowErrors = [];
        foreach ($result['codes'] as $code) {
            try {
                $this->confirmerDossier($code, $observation);
            } catch (Exception $e) {
                Log::channel('sifec')->error('Confirmation post-signature mariage', [
                    'code' => $code,
                    'error' => $e->getMessage(),
                ]);
                $workflowErrors[] = $code.': '.$e->getMessage();
            }
        }

        // Si toutes les confirmations ont échoué, on ne prétend pas au succès : la signature est
        // bien enregistrée mais le dossier reste non confirmé (l'acte ne pourra pas être généré
        // tant que la confirmation n'a pas abouti).
        if ($workflowErrors !== [] && count($workflowErrors) === count($result['codes'])) {
            return response()->json([
                'code' => '207',
                'message' => 'Signature enregistrée, mais la confirmation du dossier a échoué : '.implode(' | ', $workflowErrors).'. Réessayez la confirmation.',
                'signed' => $result['signed'],
            ]);
        }

        $message = 'Déclaration de mariage signée et dossier confirmé.';
        if ($workflowErrors !== []) {
            $message .= ' Certaines confirmations ont échoué : '.implode(' | ', $workflowErrors);
        }

        return response()->json([
            'code' => '200',
            'message' => $message,
            'signed' => $result['signed'],
        ]);
    }

    private function confirmerDossier(string $code, ?string $observation): void
    {
        $declaration = DeclarationMariage::findOrFail($code);
        $affectation = Auth::user()->affectationActive();

        [$ok, $result] = $this->mouvementService->confirmerDeclaration(
            $affectation,
            $declaration,
            'Confirmée',
            $observation
        );

        if (! $ok) {
            throw new Exception($result ?: 'Échec de la confirmation du dossier.');
        }
    }

    /**
     * @return list<string>
     */
    private function resolveCodes(Request $request): array
    {
        $codes = $request->input('codes', []);
        if (! is_array($codes) || $codes === []) {
            $single = (string) $request->input('code_declaration_mariage', '');
            $codes = $single !== '' ? [$single] : [];
        }

        return array_values(array_unique(array_filter(array_map('strval', $codes))));
    }
}
