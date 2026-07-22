<?php

namespace Modules\Mariage\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Mariage\Services\ActeMariageGuotValidationService;

/**
 * Signature électronique .p12 de l'acte de mariage par l'officier d'état civil
 * (remplace le flux OTP). Étapes : prepare (hash PDF) → signature côté client → finalize.
 */
class ActeMariageSignatureController extends Controller
{
    public function __construct(private ActeMariageGuotValidationService $signature) {}

    /**
     * Étape 1 : préparer les empreintes PDF à signer localement.
     */
    public function prepare(Request $request)
    {
        if (! Gate::allows('module.acteMariage.signature')) {
            return response()->json(['code' => '181', 'message' => "Vous n'êtes pas autorisé à signer un acte de mariage."]);
        }

        $codes = $this->resolveCodes($request);
        if ($codes === []) {
            return response()->json(['code' => '180', 'message' => 'Aucun acte sélectionné.']);
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
     * Étape 3 : vérifier les signatures, cacheter (L3) et persister.
     */
    public function finalize(Request $request)
    {
        if (! Gate::allows('module.acteMariage.signature')) {
            return response()->json(['code' => '181', 'message' => "Vous n'êtes pas autorisé à signer un acte de mariage."]);
        }

        $token = (string) $request->input('token', '');
        $signatures = $request->input('signatures', []);
        if (! is_array($signatures)) {
            $signatures = [];
        }

        $result = $this->signature->finalize(
            Auth::user(),
            $token,
            $signatures,
            $request->ip(),
            $request->userAgent()
        );

        return response()->json([
            'code' => $result['ok'] ? '200' : '183',
            'message' => $result['message'],
            'signed' => $result['signed'],
        ]);
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
