<?php

namespace Modules\Deces\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Deces\Services\ActeDecesGuotValidationService;

/**
 * Signature électronique .p12 de l'acte de décès (remplace le flux OTP).
 */
class ActeDecesSignatureController extends Controller
{
    public function __construct(private ActeDecesGuotValidationService $signature) {}

    public function prepare(Request $request)
    {
        if (! Gate::allows('module.acteDeces.signature')) {
            return response()->json(['code' => '181', 'message' => "Vous n'êtes pas autorisé à signer un acte de décès."]);
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

    public function finalize(Request $request)
    {
        if (! Gate::allows('module.acteDeces.signature')) {
            return response()->json(['code' => '181', 'message' => "Vous n'êtes pas autorisé à signer un acte de décès."]);
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
