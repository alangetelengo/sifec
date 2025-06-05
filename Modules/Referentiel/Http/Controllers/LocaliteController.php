<?php

namespace Modules\Referentiel\Http\Controllers;

use Exception;
use App\Sifec\Sifec;
use Illuminate\Http\Request;
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

        $localites = Localite::all();
        $typeLocalites = TypeLocalite::all();

        return view('referentiel::localite.index',compact("localites","typeLocalites"));
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
       // dd($request->all());
        $request->validate([
            'lib_localite'=> ['required','string'],
            'code_type_localite'=> ['required','string']
        ]);
        //dd($request->all());

        try {
            $localite = new Localite();
            $localite->code_localite = Sifec::genererCodeUniqueReferentiel($localite,"code_localite",4,"LOC_");
            $localite->lib_localite = strtoupper($request->lib_localite);
            $localite->code_localite_parent = $request->code_localite_parent;
            $localite->code_type_localite = $request->code_type_localite;
            $localite->save();
            toastr()->success("$localite->lib_localite crée avec succès");
            return redirect()->route('localite.index');

        } catch (Exception $e) {

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

        if ($localite == null){
            toastr()->error("Impossible de charger cette page");
            return redirect()->back();
        }

        $request->validate([
            'code_type_localite'=> ['required','string'],
            'lib_localite'=> ['required','string']
        ]);

        try {
            $localite->lib_localite = strtoupper($request->lib_localite);
            $localite->code_localite_parent = $request->code_localite_parent;
            $localite->code_type_localite = $request->code_type_localite;
            $localite->save();
            toastr()->success("Type d'institution modifié avec succès");
            return redirect()->route('localite.index');

        } catch (Exception $e) {

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
        $localite = Localite::find($id);

        if ($localite == null){
            toastr()->error("Impossible de charger cette page");
            return redirect()->back();
        }

        $localite->supprimer = 1;
        $localite->save();
        toastr()->success("Suppression a été effectuée avec succès");
        return redirect()->route("localite.index");
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
