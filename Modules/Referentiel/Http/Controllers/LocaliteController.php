<?php

namespace Modules\Referentiel\Http\Controllers;

use Exception;
use App\Sifec\Sifec;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller;
use Modules\Referentiel\Entities\Localite;
use Illuminate\Contracts\Support\Renderable;
use Modules\Referentiel\Entities\TypeLocalite;

class LocaliteController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        // Récupérer uniquement les localités non supprimées (SoftDeletes) avec leurs relations
        // Limiter à 20 résultats par défaut pour améliorer les performances
        $localites = Localite::with(['typelocalite', 'localiteParent'])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        // Charger toutes les localités racines pour le filtre de hiérarchie
        $localitesRacines = Localite::with('typelocalite')
            ->whereNull('code_localite_parent')
            ->orderBy('lib_localite')
            ->get();

        $typeLocalites = TypeLocalite::where("supprimer", 0)->get();

        // Log pour déboguer
        Log::channel('sifec')->info('=== CHARGEMENT PAGE LOCALITÉS ===', [
            'count_localites' => $localites->count(),
            'count_localites_racines' => $localitesRacines->count(),
            'count_type_localites' => $typeLocalites->count(),
            'premiere_localite' => $localites->first() ? $localites->first()->lib_localite : 'Aucune'
        ]);

        return view('referentiel::localite.index', compact("localites", "localitesRacines", "typeLocalites"));
    }

    /**
     * Filtrer les localités côté serveur
     */
    public function filterLocalites(Request $request)
    {
        try {
            // Logger les critères de recherche
            Log::channel('sifec')->info('=== RECHERCHE LOCALITÉS ===', [
                'criteres' => [
                    'lib_localite' => $request->input('lib_localite'),
                    'code_type_localite' => $request->input('code_type_localite'),
                ]
            ]);

            $query = Localite::with(['typelocalite', 'localiteParent']);

            // Filtre par libellé de localité
            if ($request->filled('lib_localite') && strlen(trim($request->lib_localite)) > 0) {
                $query->where('lib_localite', 'LIKE', '%' . strtoupper(trim($request->lib_localite)) . '%');
            }

            // Filtre par type de localité
            if ($request->filled('code_type_localite') && strlen(trim($request->code_type_localite)) > 0) {
                $query->where('code_type_localite', $request->code_type_localite);
            }

            $countInitial = $query->count();

            // Trier par date de création (plus récentes en premier)
            $localites = $query->orderBy('created_at', 'desc')->get();

            $countResultat = $localites->count();

            // Limiter les résultats à 500 maximum pour éviter les problèmes de performance
            $maxResults = 500;
            if ($countResultat > $maxResults) {
                $localites = $localites->take($maxResults);
                Log::channel('sifec')->warning('=== RECHERCHE LOCALITÉS - LIMITE ATTEINTE ===', [
                    'count_total' => $countResultat,
                    'count_affiché' => $maxResults,
                    'message' => "Plus de {$maxResults} résultats trouvés. Affinez vos critères de recherche pour voir tous les résultats."
                ]);
            }

            // Logger les résultats de la recherche
            Log::channel('sifec')->info('=== RÉSULTATS RECHERCHE LOCALITÉS ===', [
                'count_initial' => $countInitial,
                'count_resultat' => $countResultat,
                'count_affiché' => $localites->count(),
                'filtres_appliques' => $request->only(['lib_localite', 'code_type_localite'])
            ]);

            return response()->json([
                'code' => '200',
                'data' => view('referentiel::localite.partials.table-localites', compact('localites'))->render(),
                'count' => $countResultat,
                'count_affiché' => $localites->count(),
                'limite_atteinte' => $countResultat > $maxResults
            ]);
        } catch (\Exception $e) {
            Log::channel('sifec')->error('=== ERREUR RECHERCHE LOCALITÉS ===', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'criteres' => $request->only(['lib_localite', 'code_type_localite', 'code_localite_parent', 'pompes_funebres'])
            ]);

            return response()->json([
                'code' => '500',
                'message' => 'Erreur lors de la recherche des localités',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function departement(Request $request)
    {
        $request->validate([
            'lib_departement'=> ['required','string'],
        ]);

        try {
            $localite = new Localite();
            $localite->code_localite = Sifec::genererCodeUniqueReferentiel($localite,"code_localite",4,"LOC_");
            $localite->lib_localite = strtoupper($request->lib_departement);
            $localite->code_type_localite = "TPLOC_0001";
            $localite->save();
            toastr()->success("$localite->lib_localite crée avec succès");
            return redirect()->route('localite.index');

        } catch (Exception $e) {

            toastr()->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }



    public function store(Request $request)
    {
        $request->validate([
            'lib_localite' => ['required', 'string', 'max:150'],
            'code_type_localite' => ['required', 'string'],
            'code_localite_parent' => ['nullable', 'string'],
            'pompes_funebres' => ['nullable', 'boolean']
        ]);

        try {
            // Valider la hiérarchie
            $validation = $this->validateHierarchy($request->code_type_localite, $request->code_localite_parent);
            if (!$validation['valid']) {
                toastr()->error($validation['message']);
                return redirect()->back()->withInput();
            }

            // Vérifier que pompes_funebres n'est activé que pour Commune ou Arrondissement
            if ($request->has('pompes_funebres') && $request->pompes_funebres) {
                if (!in_array($request->code_type_localite, ['TPLOC_0003', 'TPLOC_0004'])) {
                    toastr()->error("Les pompes funèbres ne peuvent être activées que pour une Commune ou un Arrondissement");
                    return redirect()->back()->withInput();
                }
            }

            $localite = new Localite();
            $localite->code_localite = Sifec::genererCodeUniqueReferentiel($localite, "code_localite", 4, "LOC_");
            $localite->lib_localite = strtoupper($request->lib_localite);
            $localite->code_type_localite = $request->code_type_localite;
            $localite->code_localite_parent = $request->code_localite_parent ?: null;
            $localite->pompes_funebres = ($request->has('pompes_funebres') && in_array($request->code_type_localite, ['TPLOC_0003', 'TPLOC_0004'])) ? true : false;
            $localite->save();

            toastr()->success("$localite->lib_localite créée avec succès");
            return redirect()->route('localite.index');

        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de la création de localité: ' . $e->getMessage());
            toastr()->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $localite = Localite::find($id);

        if ($localite == null) {
            toastr()->error("Impossible de charger cette page");
            return redirect()->back();
        }

        $request->validate([
            'code_type_localite' => ['required', 'string'],
            'lib_localite' => ['required', 'string', 'max:150'],
            'code_localite_parent' => ['nullable', 'string'],
            'pompes_funebres' => ['nullable', 'boolean']
        ]);

        try {
            // Valider la hiérarchie
            $validation = $this->validateHierarchy($request->code_type_localite, $request->code_localite_parent);
            if (!$validation['valid']) {
                toastr()->error($validation['message']);
                return redirect()->back()->withInput();
            }

            // Vérifier que la localité parent n'est pas elle-même ou un de ses descendants
            if ($request->code_localite_parent) {
                // descendants() inclut la localité elle-même
                $descendants = $localite->descendants()->pluck('code_localite')->toArray();
                if (in_array($request->code_localite_parent, $descendants)) {
                    toastr()->error("Une localité ne peut pas être son propre parent ou avoir un de ses descendants comme parent");
                    return redirect()->back()->withInput();
                }
            }

            // Vérifier que pompes_funebres n'est activé que pour Commune ou Arrondissement
            $pompesFunebres = false;
            if ($request->has('pompes_funebres') && $request->pompes_funebres) {
                if (!in_array($request->code_type_localite, ['TPLOC_0003', 'TPLOC_0004'])) {
                    toastr()->error("Les pompes funèbres ne peuvent être activées que pour une Commune ou un Arrondissement");
                    return redirect()->back()->withInput();
                }
                $pompesFunebres = true;
            }

            $localite->lib_localite = strtoupper($request->lib_localite);
            $localite->code_type_localite = $request->code_type_localite;
            $localite->code_localite_parent = $request->code_localite_parent ?: null;
            $localite->pompes_funebres = $pompesFunebres;
            $localite->save();

            toastr()->success("Localité modifiée avec succès");
            return redirect()->route('localite.index');

        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de la modification de localité: ' . $e->getMessage());
            toastr()->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            Log::channel('sifec')->info("Tentative de suppression de la localité: " . $id);

            $localite = Localite::find($id);

            if ($localite == null) {
                Log::channel('sifec')->error("Localité non trouvée: " . $id);
                toastr()->error("Impossible de charger cette page");
                return redirect()->back();
            }

            // Vérifier si la localité a des enfants directs
            $countEnfants = $localite->localitesEnfants()->count();
            if ($countEnfants > 0) {
                Log::channel('sifec')->warning("Impossible de supprimer la localité " . $id . " car elle a " . $countEnfants . " localité(s) enfant(s)");
                toastr()->error("Impossible de supprimer cette localité car elle a des localités enfants");
                return redirect()->back();
            }

            // Vérifier si des institutions utilisent cette localité
            $countInstitutions = $localite->institutions()->count();
            if ($countInstitutions > 0) {
                Log::channel('sifec')->warning("Impossible de supprimer la localité " . $id . " car elle est utilisée par " . $countInstitutions . " institution(s)");
                toastr()->error("Impossible de supprimer cette localité car elle est utilisée par des institutions");
                return redirect()->back();
            }

            // Vérifier si des personnes utilisent cette localité
            $countPersonnes = $localite->personnes()->count();
            if ($countPersonnes > 0) {
                Log::channel('sifec')->warning("Impossible de supprimer la localité " . $id . " car elle est utilisée par " . $countPersonnes . " personne(s)");
                toastr()->error("Impossible de supprimer cette localité car elle est utilisée par des personnes");
                return redirect()->back();
            }

            // Suppression logique avec SoftDeletes
            $localite->delete();
            Log::channel('sifec')->info("Localité supprimée avec succès: " . $id);
            toastr()->success("Suppression effectuée avec succès");
            return redirect()->route("localite.index");
        } catch (Exception $e) {
            Log::channel('sifec')->error("Erreur lors de la suppression de la localité " . $id . ": " . $e->getMessage());
            Log::channel('sifec')->error("Stack trace: " . $e->getTraceAsString());
            toastr()->error($e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Récupérer les types de parents autorisés pour un type de localité donné
     */
    private function getAuthorizedParentTypes($codeTypeLocalite)
    {
        // Mapping des types de localités vers leurs parents autorisés
        $hierarchy = [
            'TPLOC_0001' => [], // DEPARTEMENT - pas de parent
            'TPLOC_0002' => ['TPLOC_0001'], // DISTRICT - parent: DEPARTEMENT
            'TPLOC_0003' => ['TPLOC_0001'], // COMMUNE - parent: DEPARTEMENT
            'TPLOC_0004' => ['TPLOC_0003'], // ARRONDISSEMENT - parent: COMMUNE
            'TPLOC_0005' => ['TPLOC_0002'], // COMMUNAUTE URBAINE - parent: DISTRICT
            'TPLOC_0006' => ['TPLOC_0002'], // COMMUNAUTE RURALE - parent: DISTRICT
            'TPLOC_0007' => ['TPLOC_0003', 'TPLOC_0005', 'TPLOC_0006'], // QUARTIER - parent: COMMUNE, COMMUNAUTE URBAINE, COMMUNAUTE RURALE
            'TPLOC_0008' => ['TPLOC_0005'], // VILLAGE - parent: COMMUNAUTE URBAINE
        ];

        return $hierarchy[$codeTypeLocalite] ?? [];
    }

    /**
     * Valider la hiérarchie selon les règles métier
     */
    private function validateHierarchy($codeTypeLocalite, $codeLocaliteParent = null)
    {
        // Si pas de parent, vérifier que le type peut être racine
        if (!$codeLocaliteParent) {
            $authorizedTypes = $this->getAuthorizedParentTypes($codeTypeLocalite);
            // Seul le DEPARTEMENT peut être racine (pas de parent)
            if ($codeTypeLocalite !== 'TPLOC_0001') {
                return [
                    'valid' => false,
                    'message' => 'Ce type de localité doit avoir un parent. Seul le Département peut être une localité racine.'
                ];
            }
            return ['valid' => true];
        }

        // Vérifier que le parent existe
        $parent = Localite::find($codeLocaliteParent);
        if (!$parent) {
            return [
                'valid' => false,
                'message' => 'La localité parent sélectionnée n\'existe pas.'
            ];
        }

        // Vérifier que le type de parent est autorisé
        $authorizedTypes = $this->getAuthorizedParentTypes($codeTypeLocalite);
        if (!in_array($parent->code_type_localite, $authorizedTypes)) {
            $parentType = $parent->typelocalite ? $parent->typelocalite->lib_type_localite : 'inconnu';
            $currentType = TypeLocalite::find($codeTypeLocalite);
            $currentTypeName = $currentType ? $currentType->lib_type_localite : 'inconnu';

            return [
                'valid' => false,
                'message' => "Un(e) {$currentTypeName} ne peut pas avoir un(e) {$parentType} comme parent."
            ];
        }

        return ['valid' => true];
    }

    /**
     * Récupérer les localités disponibles comme parent selon le type de localité
     */
    public function getAvailableParents(Request $request, $id = null)
    {
        $codeTypeLocalite = $request->input('type');
        $query = Localite::with('typelocalite');

        // Si un type de localité est fourni, filtrer selon la hiérarchie
        if ($codeTypeLocalite) {
            $authorizedTypes = $this->getAuthorizedParentTypes($codeTypeLocalite);
            if (!empty($authorizedTypes)) {
                $query->whereIn('code_type_localite', $authorizedTypes);
            } else {
                // Si aucun type autorisé, retourner un tableau vide
                return response()->json([]);
            }
        }

        // Exclure la localité courante et ses descendants
        if ($id) {
            $localite = Localite::find($id);
            if ($localite) {
                $descendants = $localite->descendants()->pluck('code_localite')->toArray();
                $query->whereNotIn('code_localite', $descendants);
            }
        }

        $localites = $query->orderBy('lib_localite')->get();

        return response()->json($localites);
    }

    /**
     * Récupérer les parents disponibles selon le type de localité (pour AJAX)
     */
    public function getAvailableParentsByType($codeTypeLocalite)
    {
        $authorizedTypes = $this->getAuthorizedParentTypes($codeTypeLocalite);

        if (empty($authorizedTypes)) {
            return response()->json([]);
        }

        $localites = Localite::with('typelocalite')
            ->whereIn('code_type_localite', $authorizedTypes)
            ->orderBy('lib_localite')
            ->get();

        return response()->json($localites);
    }

    public function communedistricts(Request $request){
        $request->validate([
            'lib_localite'=> ['required','string'],
            'code_type_localite'=> ['required','string'],
            'code_localite_parent'=> ['required','string'],
        ]);

        try {
            $localite = new Localite();
            $localite->code_localite = Sifec::genererCodeUniqueReferentiel($localite,"code_localite",4,"LOC_");
            $localite->lib_localite = strtoupper($request->lib_localite);
            $localite->code_type_localite = $request->code_type_localite;
            $localite->code_localite_parent = $request->code_localite_parent;
            $localite->save();
            toastr()->success("$localite->lib_localite crée avec succès");
            return back();
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    public function arrcomurbain(Request $request){
        $request->validate([
            'lib_localite'=> ['required','string'],
            'code_type_localite'=> ['required','string'],
            'code_localite_parent'=> ['required','string'],
        ]);

        try {
            $localite = new Localite();
            $localite->code_localite = Sifec::genererCodeUniqueReferentiel($localite,"code_localite",4,"LOC_");
            $localite->lib_localite = strtoupper($request->lib_localite);
            $localite->code_type_localite = $request->code_type_localite;
            $localite->code_localite_parent = $request->code_localite_parent;
            $localite->save();
            toastr()->success("$localite->lib_localite crée avec succès");
            return redirect()->route('localite.index');
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    //get districts
    public function district($id)
    {
        if($id == null)
        {
            return [];
        }
        return  Localite::where(["code_localite_parent"=>$id, "code_type_localite"=>"TPLOC_0002"])->get();

    }
    //get Communes
    public function commune($id)
    {
        if($id == null)
        {
            return [];
        }
        return  Localite::where(["code_localite_parent"=>$id, "code_type_localite"=>"TPLOC_0003"])->get();

    }
    //get arrondissements
    public function arrondissement($id)
    {
        if($id == null)
        {
            return [];
        }
        return Localite::where(["code_localite_parent"=>$id,"code_type_localite"=>"TPLOC_0004"])->get();
    }
    //get communautés urbaines
    public function communauteUrbaine($id)
    {
        if($id == null)
        {
            return [];
        }
        return  Localite::where(["code_localite_parent"=>$id,"code_type_localite"=>"TPLOC_0005"])->get();

    }
    //get commune de District
    public function getSubDepartement($id)
    {
    //    return $id;
        if($id == null)
        {
            return [];
        }
        //récuperer les communes et districts dont le type_localite=TPLOC_0003 et type_localite=TPLOC_0003
        return Localite::where(function($query) use ($id) {
            $query->where("code_localite_parent", $id)
                  ->whereIn("code_type_localite", ["TPLOC_0003", "TPLOC_0002"]);
        })->get();
    }


    public function getSubCommuneDistrict($id)
    {
        if($id == null)
        {
            return [];
        }

        //récuperer les arrondissements et les communautés urbaines dont le type_localite=TPLOC_0004 et type_localite=TPLOC_0005
        return Localite::where(function($query) use ($id) {
            $query->where("code_localite_parent", $id)
                  ->whereIn("code_type_localite", ["TPLOC_0004", "TPLOC_0005"]);
        })->get();
    }

    public function getSubArrondissementComUrbaine($id)
    {
        if($id == null)
        {
            return [];
        }
        //récuperer les quertiers et les villages dont le type_localite=TPLOC_0006 et type_localite=TPLOC_0007
        return Localite::where(function($query) use ($id) {
            $query->where("code_localite_parent", $id)
                  ->whereIn("code_type_localite", ["TPLOC_0006", "TPLOC_0007"]);
        })->get();
    }

}
