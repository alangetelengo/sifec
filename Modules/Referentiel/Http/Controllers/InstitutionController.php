<?php

namespace Modules\Referentiel\Http\Controllers;

use Exception;
use App\Sifec\Sifec;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\Localite;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Log;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\TypeInstitution;
use Modules\Referentiel\Entities\TypeLocalite;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        // Récupérer uniquement les institutions non supprimées (SoftDeletes) avec leurs relations
        // Limiter à 20 résultats par défaut pour améliorer les performances
        $institutions = Institution::with(['typeInstitution', 'institutionParent', 'lieu'])
            ->whereNotIn("code_type_institution", ["TPINS_0004", "TPINS_0001"]) // Exclure les types spécifiques
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        // Charger toutes les localités pour le filtre (districts, communes, arrondissements)
        $localites = Localite::whereIn("code_type_localite", ["TPLOC_0002", "TPLOC_0003", "TPLOC_0004"])->get();
        
        // Charger tous les types d'institutions pour le filtre
        $typeInstitutions = TypeInstitution::all();
        
        // Charger tous les types de localités pour le formulaire
        $typeLocalites = TypeLocalite::all();

        // Charger les tribunaux pour le formulaire (si nécessaire)
        $tribunaux = Institution::whereIn("code_type_institution", ["TPINS_0008", "TPINS_0001"])->get();

        // Log pour déboguer
        Log::channel('sifec')->info('=== CHARGEMENT PAGE INSTITUTIONS ===', [
            'count_institutions' => $institutions->count(),
            'count_type_institutions' => $typeInstitutions->count(),
            'premiere_institution' => $institutions->first() ? $institutions->first()->lib_institution : 'Aucune'
        ]);

        return view('referentiel::institution.index', compact("institutions", "localites", "typeInstitutions", "tribunaux", "typeLocalites"));
    }

    /**
     * Filtrer les institutions côté serveur
     */
    public function filterInstitutions(Request $request)
    {
        try {
            // Logger les critères de recherche
            Log::channel('sifec')->info('=== RECHERCHE INSTITUTIONS ===', [
                'criteres' => [
                    'lib_institution' => $request->input('lib_institution'),
                    'code_type_institution' => $request->input('code_type_institution'),
                    'code_localite' => $request->input('code_localite'),
                ]
            ]);

            $query = Institution::with(['typeInstitution', 'institutionParent', 'lieu'])
                ->whereNotIn("code_type_institution", ["TPINS_0004", "TPINS_0001"]); // Exclure les types spécifiques

            // Filtre par libellé d'institution
            if ($request->filled('lib_institution') && strlen(trim($request->lib_institution)) > 0) {
                $query->where('lib_institution', 'LIKE', '%' . strtoupper(trim($request->lib_institution)) . '%');
            }

            // Filtre par type d'institution
            if ($request->filled('code_type_institution') && strlen(trim($request->code_type_institution)) > 0) {
                $query->where('code_type_institution', $request->code_type_institution);
            }

            // Filtre par localité
            if ($request->filled('code_localite') && strlen(trim($request->code_localite)) > 0) {
                $query->where('code_localite', $request->code_localite);
            }

            $countInitial = $query->count();

            // Trier par date de création (plus récentes en premier)
            $institutions = $query->orderBy('created_at', 'desc')->get();

            $countResultat = $institutions->count();

            // Limiter les résultats à 500 maximum pour éviter les problèmes de performance
            $maxResults = 500;
            if ($countResultat > $maxResults) {
                $institutions = $institutions->take($maxResults);
                Log::channel('sifec')->warning('=== RECHERCHE INSTITUTIONS - LIMITE ATTEINTE ===', [
                    'count_total' => $countResultat,
                    'count_affiché' => $maxResults,
                    'message' => "Plus de {$maxResults} résultats trouvés. Affinez vos critères de recherche pour voir tous les résultats."
                ]);
            }

            // Logger les résultats de la recherche
            Log::channel('sifec')->info('=== RÉSULTATS RECHERCHE INSTITUTIONS ===', [
                'count_initial' => $countInitial,
                'count_resultat' => $countResultat,
                'count_affiché' => $institutions->count(),
                'filtres_appliques' => $request->only(['lib_institution', 'code_type_institution', 'code_localite'])
            ]);

            return response()->json([
                'success' => true,
                'html' => view('referentiel::institution.partials.table-institutions', compact('institutions'))->render(),
                'count' => $institutions->count()
            ]);
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors du filtrage des institutions: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du filtrage : ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Show the form for editing the specified resource.
     * @param string $id Code de l'institution (code_institution)
     * @return Renderable
     */
    public function edit($id)
    {
        $institution = Institution::with(['typeInstitution', 'institutionParent', 'lieu'])
            ->find($id);

        if ($institution === null) {
            toastr()->error("Institution introuvable.");
            return redirect()->route("institution.index");
        }

        // Exclure les types TPINS_0004 et TPINS_0001 pour l'édition
        if (in_array($institution->code_type_institution, ["TPINS_0004", "TPINS_0001"])) {
            toastr()->error("La modification de ce type d'institution n'est pas autorisée.");
            return redirect()->route("institution.index");
        }

        $localites = Localite::whereIn("code_type_localite", ["TPLOC_0002", "TPLOC_0003", "TPLOC_0004"])->get();
        $typeInstitutions = TypeInstitution::all();
        $typeLocalites = TypeLocalite::all();
        $tribunaux = Institution::whereIn("code_type_institution", ["TPINS_0008", "TPINS_0001"])->get();

        // Parents disponibles (exclure l'institution et ses descendants)
        $descendants = $institution->descendants()->pluck('code_institution')->toArray();
        $availableParents = Institution::with('typeInstitution')
            ->whereNotIn("code_type_institution", ["TPINS_0004", "TPINS_0001"])
            ->where('code_institution', '!=', $institution->code_institution)
            ->whereNotIn('code_institution', $descendants)
            ->orderBy('lib_institution')
            ->get();

        return view('referentiel::institution.edit', compact('institution', 'localites', 'typeInstitutions', 'tribunaux', 'typeLocalites', 'availableParents'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            "lib_institution" => ["required","string","max:255"],
            "code_type_institution" => ["required","string"],
            "code_localite" => ["required","string"],
            "code_institution_parent" => ["nullable","string"],
            "code_pompe_funebre" => ["nullable","string"],
            "statut" => ["nullable","boolean"]
        ]);

        try {
            DB::beginTransaction();

            // Valider la hiérarchie (éviter les boucles)
            if ($request->code_institution_parent) {
                $parent = Institution::find($request->code_institution_parent);
                if ($parent) {
                    $descendants = $parent->descendants()->pluck('code_institution')->toArray();
                    // L'institution parent ne peut pas être un descendant de l'institution créée
                    // (mais comme on crée, on vérifie juste que le parent existe et n'est pas lui-même)
                }
            }

            $institution = new Institution();
            $institution->code_institution = Sifec::genererCodeUniqueReferentiel($institution,"code_institution",4,"INST_");
            $institution->lib_institution = strtoupper($request->lib_institution);
            $institution->code_type_institution = $request->code_type_institution;
            $institution->statut = $request->statut ?? 1;
            $institution->code_institution_parent = $request->code_institution_parent ?: null;
            $institution->code_pompe_funebre = $request->code_pompe_funebre ?: null;
            $institution->code_localite = $request->code_localite;

            if($request->hasFile('sceau')){
                $file = $request->file('sceau');
                if ($file->isValid()) {
                    $sceau = $file->store("sceau");
                    $institution->sceau = $sceau;
                } else {
                    toastr()->error("Le fichier du sceau est corrompu ou inaccessible.");
                    DB::rollBack();
                    return redirect()->back()->withInput();
                }
            }

            $institution->save();

            DB::commit();

            Log::channel('sifec')->info('Institution créée avec succès', [
                'code_institution' => $institution->code_institution,
                'lib_institution' => $institution->lib_institution
            ]);

            toastr()->success("$institution->lib_institution enregistré(e) avec succès","Gestion du référentiel");
            return redirect()->route("institution.index");
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('Erreur lors de la création d\'institution: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
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

        $institution = Institution::find($id);

        if($institution == null){
            toastr()->error("Impossible de charger cette page");
            return redirect()->back();
        }

        try {
            $request->validate([
                "lib_institution" => ["required","string","max:255"],
                "code_type_institution" => ["required","string"],
                "code_localite" => ["required","string"],
                "code_institution_parent" => ["nullable","string"],
                "code_pompe_funebre" => ["nullable","string"],
                "statut" => ["nullable","boolean"]
            ]);

            // Valider la hiérarchie (éviter les boucles)
            if ($request->code_institution_parent && $request->code_institution_parent !== $institution->code_institution_parent) {
                $parent = Institution::find($request->code_institution_parent);
                if ($parent) {
                    $descendants = $institution->descendants()->pluck('code_institution')->toArray();
                    if (in_array($request->code_institution_parent, $descendants)) {
                        toastr()->error("Une institution ne peut pas être son propre parent ou avoir un de ses descendants comme parent");
                        return redirect()->back()->withInput();
                    }
                }
            }

            $institution->lib_institution = strtoupper($request->lib_institution);
            $institution->code_type_institution = $request->code_type_institution;
            $institution->statut = $request->statut ?? $institution->statut;
            $institution->code_institution_parent = $request->code_institution_parent ?: null;
            $institution->code_pompe_funebre = $request->code_pompe_funebre ?: null;
            $institution->code_localite = $request->code_localite;

            if($request->hasFile('sceau')){
                $file = $request->file('sceau');
                if ($file->isValid()) {
                    $sceau = $file->store("sceau");
                    $institution->sceau = $sceau;
                } else {
                    toastr()->error("Le fichier du sceau est corrompu ou inaccessible.");
                    return redirect()->back()->withInput();
                }
            }

            $institution->save();

            Log::channel('sifec')->info('Institution modifiée avec succès', [
                'code_institution' => $institution->code_institution,
                'lib_institution' => $institution->lib_institution
            ]);

            toastr()->success("$institution->lib_institution modifié avec succès","Gestion du référentiel");
            return redirect()->route("institution.index");

        } catch (Exception $e) {
            Log::channel("sifec")->error('Erreur lors de la modification d\'institution: ' . $e->getMessage(), [
                'code_institution' => $id,
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
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
            $institution = Institution::find($id);
            
            if($institution == null){
                toastr()->error("Impossible de charger cette page");
                return redirect()->back();
            }

            // Utiliser softDeletes() au lieu de supprimer=1
            $institution->delete();

            Log::channel('sifec')->info('Institution supprimée (soft delete)', [
                'code_institution' => $institution->code_institution,
                'lib_institution' => $institution->lib_institution
            ]);

            toastr()->success("Suppression a été effectuée avec succès","Gestion du référentiel");
            return redirect()->route("institution.index");
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de la suppression d\'institution: ' . $e->getMessage(), [
                'code_institution' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            toastr()->error("Erreur lors de la suppression : " . $e->getMessage());
            return redirect()->back();
        }
    }

    public function getInstitution()
    {
        $id = request('id');
        $institutions = Institution::where("code_type_institution", $id)->get();
        return $institutions;
    }

    public function getLocalite()
    {
       $id = request('id');
       $localites = Localite::where("code_type_localite",$id)->get();
       return $localites;
    }

    /**
     * Récupérer les institutions parents disponibles (excluant l'institution et ses descendants)
     * @param string $id Code de l'institution à exclure
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableParents($id = null)
    {
        try {
            $query = Institution::whereNotIn("code_type_institution", ["TPINS_0004", "TPINS_0001"])
                ->with('typeInstitution');

            // Exclure l'institution elle-même et ses descendants si un ID est fourni
            if ($id) {
                $institution = Institution::find($id);
                if ($institution) {
                    // Exclure l'institution elle-même
                    $query->where('code_institution', '!=', $id);
                    
                    // Exclure tous les descendants
                    $descendants = $institution->descendants()->pluck('code_institution')->toArray();
                    if (!empty($descendants)) {
                        $query->whereNotIn('code_institution', $descendants);
                    }
                }
            }

            $parents = $query->orderBy('lib_institution')->get();

            return response()->json($parents);
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de la récupération des parents disponibles: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la récupération des parents'], 500);
        }
    }

    /**
     * Récupérer les institutions parents disponibles par type d'institution
     * @param string $codeTypeInstitution Code du type d'institution
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableParentsByType($codeTypeInstitution)
    {
        try {
            $parents = Institution::whereNotIn("code_type_institution", ["TPINS_0004", "TPINS_0001"])
                ->where("code_type_institution", $codeTypeInstitution)
                ->with('typeInstitution')
                ->orderBy('lib_institution')
                ->get();

            return response()->json($parents);
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de la récupération des parents par type: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la récupération des parents'], 500);
        }
    }
}
