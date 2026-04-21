<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Sifec\Sifec;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Naissance\Entities\Declarationnaissance;
use Modules\Referentiel\Entities\Personne;

class DocumentEtatCivilController extends Controller
{
    //
    public function verificationActe(Request $request)
    {
        Log::channel('sifec')->info($request->all());

        $numDec = $request->numero_declaration;
        $identiteDeclarant = $request->identite_declarant;

        if ($numDec != null) {

            $dn = Declarationnaissance::find($numDec);
            $dd = DeclarationDeces::find($numDec);

            if ($dn != null) {
                if ($dn->acte != null) {
                    return response()->json([
                        'message' => 'Acte de naissance disponible',
                        'numero_acte_naissance' => $dn->acte->niupp,
                    ]);
                }

                return response()->json([
                    'message' => 'Acte de naissance encours de production',
                ]);
            }
            if ($dd != null) {
                if ($dd->acte != null) {
                    return response()->json([
                        'message' => 'Acte de décès disponible',
                        'numero_acte_deces' => $dd->acte->code_acte_deces,
                    ]);
                }

                return response()->json([
                    'message' => 'Acte de naissance encours de production',
                ]);
            }

            return response()->json([
                'message' => "Ce numéro ne correspond a aucune déclaration d'acte !",
            ]);
        }

        if ($identiteDeclarant != null) {

            $request->validate([
                'nom_declarant' => ['required', 'string'],
                'sexe_declarant' => ['required', 'string'],
                'date_naissance_declarant' => ['required'],
                'lieu_naissance_declarant' => ['required'],
            ]);

            $nom = $request->nom_declarant;
            $prenom = $request->prenom_declarant;
            $sexe = $request->sexe_declarant;
            $dateNaissance = $request->date_naissance_declarant;
            $lieuNaissance = $request->lieu_naissance_declarant;

            $uniqueString = Sifec::uniqueString($request, '_declarant', $sexe);
            $personne = Personne::where('personne_string', $uniqueString)->first();

            if ($personne != null) {
                $dn = Declarationnaissance::where('code_declarant', $personne->code_personne)->first();
                $dd = DeclarationDeces::where('code_declarant', $personne->code_personne)->first();

                if ($dn != null) {
                    if ($dn->acte != null) {
                        return response()->json([
                            'message' => 'Acte de naissance disponible',
                            'numero_acte_naissance' => $dn->acte->niupp,
                        ]);
                    }

                    return response()->json([
                        'message' => 'Acte de naissance encours de production',
                    ]);
                }
                if ($dd != null) {
                    if ($dd->acte != null) {
                        return response()->json([
                            'message' => 'Acte de décès disponible',
                            'numero_acte_deces' => $dd->acte->code_acte_deces,
                        ]);
                    }

                    return response()->json([
                        'message' => 'Acte de naissance encours de production',
                    ]);
                }

                return response()->json([
                    'message' => "Ce déclarant ne correspond a aucune déclaration d'acte !",
                ]);
            }

            return response()->json([
                'message' => "Ce déclarant ne correspond a aucune déclaration d'acte !",
            ]);

        }

        return response()->json([
            'message' => 'Fournir numero_declaration ou identite_declarant avec les champs requis.',
        ], 422);
    }
}
