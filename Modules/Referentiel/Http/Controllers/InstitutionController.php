<?php

namespace Modules\Referentiel\Http\Controllers;

use Exception;
use App\Sifec\Sifec;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\Localite;
use Modules\Referentiel\Entities\Tribunal;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Log;
use Modules\Referentiel\Entities\Arrondissement;
use Modules\Referentiel\Entities\CommunauteUrbaine;
use Modules\Referentiel\Entities\Commune;
use Modules\Referentiel\Entities\Departement;
use Modules\Referentiel\Entities\District;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\TypeCategorieInstitution;
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
        $localites = Localite::whereIn("code_type_localite",["TPLOC_0002","TPLOC_0003","TPLOC_0004"])->get();
        $typeInstitutions = TypeInstitution::all();
        $institutions = Institution::orderBy('code_institution', 'DESC')->get();
        $typeLocalites = TypeLocalite::all();


        $tribunaux = Institution::whereIn("code_type_institution",["TPINS_0008","TPINS_0001"])->get();

        return view('referentiel::institution.index',compact("institutions","localites","typeInstitutions","tribunaux","typeLocalites"));
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

    //    dd($request->all());
        $request->validate([
            "lib_institution" => ["required","string"],
            "code_type_institution" => ["required","string"],
            "code_localite" => ["required","string"]

        ]);

        // $localites = [$request->code_district, $request->code_commune,$request->code_arrondissement,$request->code_communaute_urbaine];
        // $columns = ["code_district","code_commune","code_arrondissement","code_communaute_urbaine"];
        // $column_chosen = "";

        // for ($i=0; $i < count($localites) ; $i++) {
        //    if($localites[$i] != null){
        //         $localite = $localites[$i];
        //         $column_chosen = $columns[$i];
        //    }
        // }


        try {
            DB::beginTransaction();

            $institution = new Institution();
            $institution->code_institution = Sifec::genererCodeUniqueReferentiel($institution,"code_institution",4,"INST_");
            $institution->lib_institution = strtoupper($request->lib_institution);
            $institution->code_type_institution = $request->code_type_institution;
            $institution->statut = 1;
            // $institution->$column_chosen = $localite;
            $institution->code_institution_parent = $request->code_institution_parent;
           $institution->code_localite = $request->code_localite;
           if($request->sceau){
                $sceau = $request->sceau->store("sceau");
                $institution->sceau = $sceau;
            }
            $institution->save();

            DB::commit();

            toastr()->success("$institution->lib_institution  enregistré(e) avec succès","Gestion du référentiel");
            return redirect()->route("institution.index");
        } catch (Exception $e) {
            DB::rollBack();
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

            $institution->lib_institution = strtoupper($request->lib_institution);
            $institution->code_type_institution = $request->code_type_institution;
            $institution->statut = $request->statut;
            $institution->code_institution_parent = $request->code_institution_parent;
            $institution->code_localite = $request->code_localite;

            if($request->sceau){
                $sceau = $request->sceau->store("sceau");
                $institution->sceau = $sceau;
            }
            $institution->save();

            toastr()->success("$institution->lib_institution modifié avec succès","Gestion du référentiel");
            return redirect()->route("institution.index");

        } catch (Exception $e) {
            Log::channel("sifec")->info($request->all());
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
        $institution = Institution::where("code_institution", $id)->first();
        if($institution == null){
            toastr()->error("Impossible de charger cette page");
            return redirect()->back();
        }

        Institution::where("code_institution", $id)->update(["supprimer"=>1]);
        toastr()->success("Suppression a été effectuée avec succès","Gestion du référentiel");
        return redirect()->route("institution.index");
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
}
