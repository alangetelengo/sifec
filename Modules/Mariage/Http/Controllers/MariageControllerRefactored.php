<?php

namespace Modules\Mariage\Http\Controllers;

use Exception;
use App\Sifec\Sifec;
use App\Sifec\SifecFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Modules\Referentiel\Entities\Regime;
use Illuminate\Support\Facades\Validator;
use Modules\Referentiel\Entities\Commune;
use Modules\Referentiel\Entities\District;
use Modules\Referentiel\Entities\Localite;
use Modules\Referentiel\Entities\Personne;
use Modules\Referentiel\Entities\Filiation;
use Illuminate\Contracts\Support\Renderable;
use Locale;
use Modules\Referentiel\Entities\Profession;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Referentiel\Entities\Departement;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\Nationalite;
use Modules\Mariage\Entities\MouvementMariage;
use Modules\Referentiel\Entities\TypeDocument;
use Modules\Referentiel\Entities\OptionMariage;
use Modules\Mariage\Entities\DeclarationMariage;
use Modules\Mariage\Entities\DetailLivretFamille;
use Modules\Mariage\Entities\LivretFamille;
use Modules\Referentiel\Entities\AdressePersonne;
use Modules\Referentiel\Entities\Arrondissement;
use Modules\Referentiel\Entities\LieuSurvenance;
use Modules\Referentiel\Entities\SituationMatrimoniale;
use Modules\Mariage\Services\DeclarationMariageService;

class MariageController extends Controller
{
    public function index()
    {
        $dms = Auth::user()->institution()->declarationsMariages();

        return view('mariage::declaration.index', compact("dms"));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $situationMatrimoniales = SituationMatrimoniale::all();
        $filiations = Filiation::all();
        $typedocuments = TypeDocument::all();
        $regimes = Regime::all();
        $optionmariages = OptionMariage::all();
        $LieuCeremonie = ["Centre d'état civil", "Hors centre d'état civil"];
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $countries = Nationalite::all();
        $lieuNaissances = Localite::where('code_type_localite', 'TPLOC_0003')->get();
        $cecNaissances = Institution::where("code_type_institution", "TPIN_0002")->get();
        $arrondissement = Localite::where('code_type_localite', 'TPLOC_0004')->Orwhere('code_type_localite', 'TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite', 'TPLOC_0007')->Orwhere('code_type_localite', 'TPLOC_0008')->get();

        return view('mariage::declaration.create', compact("arrondissement", "quartierVillages", "situationMatrimoniales", "filiations", "typedocuments", "regimes", "optionmariages", "LieuCeremonie", "professions", "nationalites", "countries", "lieuNaissances", "cecNaissances"));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $rules = [
            "nom_epoux" => ["required", "string", "min:2"],
            "date_naissance_epoux" => ["required", "date"],
            "num_acte_naissance_epoux" => ["required", "string"],
            "code_profession_epoux" => ["required"],
            "sit_matrimoniale_epoux" => ["required"],
            "nom_pere_epoux" => ["required", "string", "min:2"],
            "nom_mere_epoux" => ["required", "string", "min:2"],
            "nom_epouse" => ["required", "string", "min:2"],
            "date_naissance_epouse" => ["required", "date"],
            "num_acte_naissance_epouse" => ["required", "string"],
            "code_profession_epouse" => ["required"],
            "sit_matrimoniale_epouse" => ["required"],
            "nom_pere_epouse" => ["required", "string", "min:2"],
            "nom_mere_epouse" => ["required", "string", "min:2"],
            "chef_famille" => ["required", "string", "min:2"],
            "filiation" => ["required"],
            "nom_t_epoux_1" => ["required", "string", "min:2"],
            "date_naissance_t_epoux_1" => ["required"],
            "lieu_naissance_t_epoux_1" => ["required", "string"],
            "code_profession_t_epoux_1" => ["required"],
            "code_nationalite_t_epoux_1" => ["required"],
            "nom_t_epoux_2" => ["required", "string", "min:2"],
            "date_naissance_t_epoux_2" => ["required"],
            "lieu_naissance_t_epoux_2" => ["required", "string"],
            "code_profession_t_epoux_2" => ["required"],
            "code_nationalite_t_epoux_2" => ["required"],
            "nom_t_epouse_1" => ["required", "string", "min:2"],
            "date_naissance_t_epouse_1" => ["required"],
            "lieu_naissance_t_epouse_1" => ["required", "string"],
            "code_profession_t_epouse_1" => ["required"],
            "code_nationalite_t_epouse_1" => ["required"],
            "nom_t_epouse_2" => ["required", "string", "min:2"],
            "date_naissance_t_epouse_2" => ["required"],
            "lieu_naissance_t_epouse_2" => ["required", "string"],
            "code_profession_t_epouse_2" => ["required"],
            "code_nationalite_t_epouse_2" => ["required"],
            "date_ceremonie_mariage" => ["required"]
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                "code" => "150",
                "message" => $validator->errors(),
                "flashAlert" => [
                    "type" => "error",
                    "message" => "Veuillez corriger les erreurs de validation"
                ]
            ]);
        }

        try {
            $declarationService = new DeclarationMariageService();
            [$success, $declaration] = $declarationService->enregistrer($request, Auth::user());

            return response()->json([
                "code" => "200",
                "message" => ["reponse" => "Déclaration de mariage enregistrée avec succès."],
                "flashAlert" => [
                    "type" => "success",
                    "message" => "Déclaration de mariage enregistrée avec succès"
                ],
                "data" => [
                    "code_declaration" => $declaration->code_declaration_mariage,
                    "type_declaration" => $declaration->type_declaration
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                "code" => "99",
                "message" => ["error" => $e->getMessage()],
                "flashAlert" => [
                    "type" => "error",
                    "message" => $e->getMessage()
                ]
            ]);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('mariage::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('mariage::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function recherchePersonne(Request $request)
    {
        $niupp = $request->numero_acte_naissance;
        return SifecFacade::rechercheIdentite($niupp);
    }

    public function getRegime()
    {
        $id = request('optionmariage');
        $regimes = collect();
        if (in_array($id, ['OMRG_0001', 'OPM_0002'], true)) {
            $regimes = Regime::where('code_regime', 'RGIM_0002')->get();
        } elseif (in_array($id, ['OMRG_0002', 'OPM_0001'], true)) {
            $regimes = Regime::all();
        }

        return $regimes;
    }
}
