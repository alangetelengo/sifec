<?php

namespace Modules\Authentification\Http\Controllers;

use Exception;
use App\Sifec\Sifec;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Renderable;
use Modules\Authentification\Entities\Module;

class ModuleController extends Controller
{

    public function index()
    {
        $modules = Module::where("supprimer",0)->get();

        return view("authentification::module.index", compact("modules"));
    }

    public function create()
    {
        return view("authentification::module.create");
    }

    public function fonctionnalites($id)
    {
        $module = Module::with("fonctionnalites")->find($id);

        if($module == null){
            return response()->json([
                "fonctionnalites" => []
            ]);
        }

        return response()->json([
            "fonctionnalites" => $module->fonctionnalites
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //code_module	lib_module	description_module	etat_module
        $request->validate([
            "lib_module"=>["required","string","unique:tr_module"],
            "description_module"=>["required","string","min:15"],
            "etat_module"=>["required","string"]
        ]);
        DB::beginTransaction();
        try{

            $module = new Module;

            $code = Sifec::genererCodeUniqueReferentiel($module,"code_module",4,"MOD_");

            $module->code_module = $code;
            $module->lib_module = $request->lib_module;
            $module->description_module = $request->description_module;
            $module->etat_module = $request->etat_module;
            $module->save();
            DB::commit();
            toastr()->success("Module créé avec succès","Gestion des modules");
            return redirect()->route("module.index");

        }catch(Exception $e){
            DB::rollBack();
            toastr()->error($e->getMessage());
            return back();
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $module = Module::find($id);

        if($module == null){
            toastr()->error("Impossible d'effectuer cette opération","Gestion des modules");
            return back();
        }
        return view('authentification::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $module = Module::find($id);

        if($module == null){
            toastr()->error("Impossible d'effectuer cette opération","Gestion des modules");
            return back();
        }

        return view('authentification::module.edit', compact("module"));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {


        $request->validate([
            "lib_module"=>["required","string"],
            "description_module"=>["required","string","min:15"],
            "etat_module"=>["required","string"]
        ]);

        $module = Module::find($id);

        if($module == null){
            toastr()->error("Impossible d'effectuer cette opération","Gestion des modules");
            return back();
        }

        DB::beginTransaction();
        try{
            $module->lib_module = $request->lib_module;
            $module->description_module = $request->description_module;
            $module->etat_module = $request->etat_module;
            $module->save();
            DB::commit();
            toastr()->success("Module créé avec succès","Gestion des modules");
            return redirect()->route("module.index");
        }catch(Exception $e){
            DB::rollBack();
            toastr()->error($e->getMessage());
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $module = Module::find($id);

        if($module == null){
            toastr()->error("Impossible d'effectuer cette opération","Gestion des modules");
            return back();
        }

        try{
            $module->update(["supprimer"=>1]);
            toastr()->success("Module supprimé avec succès","Gestion des modules");
            return redirect()->route("module.index");
        }catch(Exception $e){
            toastr()->error($e->getMessage());
            return back();
        }

    }
}
