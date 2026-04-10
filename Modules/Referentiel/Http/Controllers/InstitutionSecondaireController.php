<?php

namespace Modules\Referentiel\Http\Controllers;

use Exception;
use App\Sifec\Sifec;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Referentiel\Entities\Localite;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Services\InstitutionLienSyncService;
use Modules\Referentiel\Entities\Departement;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\TypeInstitution;

class InstitutionSecondaireController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $departements = Departement::all();
        $typeInstitutions = TypeInstitution::all();
        $institutions = Institution::whereIn("code_type_institution",["TPINS_0004"])->get();

        return view('referentiel::institution.secondaire.index',compact("institutions","typeInstitutions","departements"));
    }

    public function intitutionData(){
        $institutions = Institution::whereIn("code_type_institution",["TPINS_0009"])->get();
        $out = "";
        $count = 1;
        foreach($institutions as $institution){
            $out .= "
                <tr>
                <td>".$count ++. "</td>
                <td>".($institution->lib_institution)."</td>
                <td>".($institution->institutionParent ? $institution->institutionParent->lib_institution : '')."</td>
                <td>".($institution->pompeFunebre ? $institution->pompeFunebre->lib_institution : '')."</td>
                <td>
                    <div class='btn-group btn-group-xs'>
                        <form action='".route("institutionSecondaire.destroy",$institution->code_institution)."' method='POST'>
                           
                            <button class='btn btn-danger shadow btn-xs sharp' type='submit'><i class='fa fa-trash'></i></button>
                        </form>
                    </div>
                </td>
                </tr>
            ";
        }

        return $out;
    }


    public function store(Request $request)
    {


        $rules = [
            "lib_institution" => ["required", "string","unique:tr_institution"],
            "code_institution_parent" => ["required","string"]
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json([
                "code"=>"180",
                "message"=>"Il y a des erreurs à corriger",
                "errors"=>$validator->errors()
            ]);
        }

        // $parent = Institution::find($request->code_institution_parent);

        // if($parent == null){
        //     return response()->json([
        //         "code"=>"181",
        //         "message"=>"L'institution rattachée n'existe pas"
        //     ]);
        // }

        // $localite = "";

        // $localites = [$parent->code_district,$parent->code_commune,$parent->code_arrondissement,$parent->code_communaute_urbaine];
        // $columns = ["code_district","code_commune","code_arrondissement","code_communaute_urbaine"];
        // $column_chosen = "";

        // for($i = 0; $i < count($localites); $i++){
        //     if($localites[$i] != null){
        //         $localite = $localites[$i];
        //         $column_chosen = $columns[$i];
        //     }
        // }
        // return response()->json($request->all());

        try {
            $institution = new Institution();
            $institution->code_institution = Sifec::genererCodeUniqueReferentiel($institution,"code_institution",4,"INST_");
            $institution->lib_institution = strtoupper($request->lib_institution) ;
            $institution->code_type_institution = "TPINS_0009";
            $institution->code_institution_parent = $request->code_institution_parent;
            $institution->statut = 1;
            $institution->code_localite = "LOC_0026";

            $institution->save();

            app(InstitutionLienSyncService::class)->syncFromRequest($institution, [
                'liens_cec_naissance' => array_values(array_filter([(string) ($request->code_pompe_funebre ?? '')])),
                'liens_cec_deces' => [],
                'liens_tribunal_ressort' => [],
            ], true);

            return response()->json([
                "code"=>"200",
                "message"=>"Formation sanitaire créée avec succès"
            ]);
        } catch (Exception $e) {
            Log::channel("sifec")->error($e->getMessage());
            return response()->json([
                "code"=>"182",
                "message"=>$e->getMessage()
            ]);
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
            return toastr()->error("Impossible de charger cette page");
        }

        $request->validate([
            "lib_institution" => ["required", "string"],
            "code_type_institution" => ["required","string"],
            "code_localite" => ["required","string"],
            "code_institution_parent" => ["required","string"],
            "statut" => ["required"]
        ]);

        try {
            $institution->lib_institution = strtoupper($request->lib_institution) ;
            $institution->code_type_institution = $request->code_type_institution;
            $institution->code_institution_parent = $request->code_institution_parent;
            $institution->statut = $request->statut;
            $institution->code_localite = $request->code_localite;
            if($request->sceau){
                $sceau = $request->sceau->store("sceau");
                $institution->sceau = $sceau;
            }
            $institution->save();

            response()->json(["message"=>"$institution->lib_institution  modifié(e) avec succès","Gestion du référentiel"]);

        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        $institution = Institution::where("code_institution", $id)->first();
        if($institution == null){
            response()->json(["message"=>"Impossible de charger cette page","Gestion du référentiel"]);

        }

        $institution->delete();
        response()->json(["message"=>"Suppression a été effectuée avec succès","Gestion du référentiel"]);
    }
}
