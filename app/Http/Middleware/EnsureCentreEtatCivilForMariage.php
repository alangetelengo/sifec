<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Accès aux écrans web Mariage (déclaration, actes, états) : même règle que le tableau de bord
 * (catégorie centre d'état civil TCINS_0001, hors pompes funèbres TPINS_0003).
 * Tribunal, formation sanitaire et ambassade hors périmètre CEC pour ce module sont exclus.
 */
class EnsureCentreEtatCivilForMariage
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user) {
            abort(403);
        }

        $affectation = $user->affectationActive();
        if (!$affectation) {
            abort(403, 'Aucune affectation active.');
        }

        $institution = $affectation->institution;
        if (!$institution) {
            abort(403, 'Institution d\'affectation introuvable.');
        }

        $typeInstitution = $institution->typeInstitution;
        if (!$typeInstitution || !$typeInstitution->typeCategorieInstitution) {
            abort(403, 'Type d\'institution ou catégorie non défini.');
        }

        $codeCategorie = $typeInstitution->typeCategorieInstitution->code_type_categorie_ins;
        $codeTypeInstitution = $typeInstitution->code_type_institution;

        if ($codeCategorie !== 'TCINS_0001' || $codeTypeInstitution === 'TPINS_0003') {
            $message = 'Cette section est réservée au centre d\'état civil.';
            if ($request->expectsJson()) {
                return response()->json(['code' => '403', 'message' => $message], 403);
            }
            abort(403, $message);
        }

        return $next($request);
    }
}
