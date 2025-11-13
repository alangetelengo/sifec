<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Referentiel\Entities\Personne;

class PersonneSearchController extends Controller
{
    public function recherche(Request $request)
    {
        $nom = $request->input('nom');
        $prenom = $request->input('prenom');
        $sexe = $request->input('sexe');
        $date_naissance = $request->input('date_naissance');
        $lieu_naissance = $request->input('lieu_naissance');

        $query = Personne::query();

        if ($nom) {
            $query->where('nom', 'like', "%$nom%");
        }
        if ($prenom) {
            $query->where('prenom', 'like', "%$prenom%");
        }
        if ($sexe) {
            $query->where('sexe', $sexe);
        }
        if ($date_naissance) {
            $query->where('date_naissance', $date_naissance);
        }
        if ($lieu_naissance) {
            $query->where('lieu_naissance', 'like', "%$lieu_naissance%");
        }

        $personnes = $query->limit(10)->get(['code_personne', 'nom', 'prenom', 'date_naissance', 'lieu_naissance', 'sexe']);

        return response()->json($personnes);
    }
}
