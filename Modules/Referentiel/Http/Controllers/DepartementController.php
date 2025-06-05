<?php

namespace Modules\Referentiel\Http\Controllers;

use Exception;
use App\Sifec\Sifec;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\Referentiel\Entities\Departement;
use Modules\Referentiel\Entities\Localite;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $departements = Localite::whereIn("code_type_localite",["TPLOC_0001"])->orderBy('code_localite', 'DESC')->get();

        return view('referentiel::departement.index',compact('departements'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'lib_localite'=> ['required','string']
        ]);

        try {
            $localite = new Localite();
            $localite->code_localite = Sifec::genererCodeUniqueReferentiel($localite,"code_localite",4,"LOC_");
            $localite->lib_localite = strtoupper($request->lib_localite);
            $localite->code_type_localite = "TPLOC_0001";
            $localite->save();
            toastr()->success("$localite->lib_localite crée avec succès");
            return redirect()->route('departement.index');

        } catch (Exception $e) {

            toastr()->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function update(Request $request, $id)
    {
        $departement = Localite::find($id);

        if($departement == null){
            toastr()->error("Impossible de charger cette page");
            return back();
        }

        $request->validate([
            'lib_localite'=> ['required','string']
        ]);

        try {

            $departement->lib_localite = strtoupper($request->lib_localite);
            $departement->save();
            toastr()->success("$departement->lib_localite modifié avec succès");
            return redirect()->route('departement.index');

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
        $departement = Localite::find($id);

        if($departement == null){
            toastr()->error("Impossible de charger cette page");
            return back();
        }

        $departement->delete();
        toastr()->success("Suppression a été effectuée avec succès");
        return redirect()->route("departement.index");
    }
}
