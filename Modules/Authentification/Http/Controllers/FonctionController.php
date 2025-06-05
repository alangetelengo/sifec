<?php

namespace Modules\Authentification\Http\Controllers;

use App\Sifec\Sifec;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\Fonction;
use Illuminate\Contracts\Support\Renderable;
use Modules\Authentification\Entities\Module;

class FonctionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $fonctions = Fonction::all();
        return view('authentification::fonction.index',compact("fonctions"));
    }



    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'lib_fonction' =>  ["required","string"]
        ]);

        DB::beginTransaction();

        try {

            $fonctions = new Fonction();
            $code = Sifec::genererCodeUniqueReferentiel($fonctions,"code_fonction",4,"FONC_");

            $fonctions->code_fonction = $code;
            $fonctions->lib_fonction = $request->lib_fonction;
            $fonctions->save();

            DB::commit();
            toastr()->success("Fonction créée avec succès","Gestion des fonctions");
            return redirect()->route("fonction.index");
        } catch (Exception $e) {
            DB::rollBack();
            toastr()->error($e->getMessage());
            return back()->withInput();
        }

    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('authentification::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $fonction = Fonction::find($id);

        if($fonction == null){
            toastr()->error("Impossible d'effectuer cette opération","Gestion des fonctions");
            return back();
        }

        $request->validate([
            'lib_fonction' =>  ["required","string"]
        ]);

        DB::beginTransaction();

        try {

            $fonction->lib_fonction = $request->lib_fonction;
            $fonction->save();

            DB::commit();
            toastr()->success("Fonction modifiée avec succès","Gestion des fonctions");
            return redirect()->route("fonction.index");
        } catch (Exception $e) {
            DB::rollBack();
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $fonction = Fonction::find($id);

        if($fonction == null){
            toastr()->error("Impossible d'effectuer cette opération","Gestion des fonctions");
            return back();
        }

        Fonction::where("code_fonction",$id)->update(['supprimer'=>1]);
        toastr()->success("Fonction supprimée avec succès","Gestion des fonctions");
        return redirect()->route("fonction.index");
    }


    public function assigner($id)
    {
        $fonction = Fonction::find($id);

        if($fonction == null){
            toastr()->error("Impossible de charger cette page");
            return back();
        }

        $modules = Module::all();

        return view("authentification::fonction.assignation",compact("modules","fonction"));
    }

    public function storeAssigner(Request $request, $id)
    {
        $fonction = Fonction::find($id);

        if($fonction == null){
            toastr()->error("Impossible de charger cette page");
            return back();
        }


        DB::beginTransaction();
        try{

            if($request->fonctionnalites != null){
                $fonction->fonctionnalites()->sync($request->fonctionnalites);
            }

            DB::commit();

            toastr()->success("Fonctionnalité assignée avec succès","Gestion des fonctions");
            return redirect()->route("fonction.index");

        }catch(Exception $e){
            DB::rollBack();
            toastr()->error($e->getMessage());
            return back()->withInput();

        }
    }
}
