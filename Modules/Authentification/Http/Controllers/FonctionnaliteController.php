<?php

namespace Modules\Authentification\Http\Controllers;

use App\Sifec\Sifec;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Authentification\Entities\Fonctionnalite;
use Modules\Authentification\Entities\Module;

class FonctionnaliteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        $fonctionnalites = Fonctionnalite::all();
        $modules = Module::all();

        return view('authentification::fonctionnalite.index', compact('fonctionnalites', 'modules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        $fonctionnalites = Fonctionnalite::where('code_fonctionnalite_parent', null)->get();
        $modules = Module::all();

        return view('authentification::fonctionnalite.create', compact('fonctionnalites', 'modules'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {

        // code_fonctionnalite	lib_fonctionnalite	lib_technique	description_fonctionnalite	code_module	etat_fonctionnalite
        $request->validate([
            'lib_fonctionnalite' => ['required', 'string', 'unique:tr_fonctionnalite'],
            'lib_technique' => ['required', 'string', 'min:3'],
            'description_fonctionnalite' => ['required', 'string', 'min:15'],
            'code_module' => ['required', 'string'],

            'etat_fonctionnalite' => ['required', 'string'],
        ]);

        DB::beginTransaction();
        try {
            $fonctionalite = new Fonctionnalite;

            $code = Sifec::genererCodeUniqueReferentiel($fonctionalite, 'code_fonctionnalite', 4, 'FNC_');

            $fonctionalite->code_fonctionnalite = $code;
            $fonctionalite->lib_fonctionnalite = $request->lib_fonctionnalite;
            $fonctionalite->code_module = $request->code_module;
            $fonctionalite->description_fonctionnalite = $request->description_fonctionnalite;
            $fonctionalite->lib_technique = $request->lib_technique;
            $fonctionalite->etat_fonctionnalite = $request->etat_fonctionnalite;
            $fonctionalite->code_fonctionnalite_parent = $request->code_fonctionnalite_parent;
            $fonctionalite->save();

            DB::commit();
            flash()->success('Fonctionnalité créée avec succès', [], 'Gestion des fonctionnalités');

            return back();

        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error($e->getMessage());
            flash()->error($e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        $fonctionalite = Fonctionnalite::find($id);

        if ($fonctionalite != null) {
            flash()->error("Impossible d'effectuer cette opération", [], 'Gestion des modules');

            return back();
        }

        return view('authentification::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {

        $fonctionnalite = Fonctionnalite::find($id);
        // dd($fonctionalite);
        if ($fonctionnalite == null) {
            flash()->error("Impossible d'effectuer cette opération", [], 'Gestion des modules');

            return back();
        }

        $modules = Module::all();
        $fonctionnalites = Fonctionnalite::where('code_fonctionnalite_parent', null)->get();

        return view('authentification::fonctionnalite.edit', compact('fonctionnalite', 'modules', 'fonctionnalites'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        // code_fonctionnalite	lib_fonctionnalite	lib_technique	description_fonctionnalite	code_module	etat_fonctionnalite
        $request->validate([
            // "lib_fonctionnalite"=>["required","string","unique:tr_fonctionnalite"],
            'lib_technique' => ['required', 'string'],
            'description_fonctionnalite' => ['required', 'string'],
            'code_module' => ['required', 'string'],

            'etat_fonctionnalite' => ['required', 'string'],
        ]);

        $fonctionalite = Fonctionnalite::find($id);

        if ($fonctionalite == null) {
            flash()->error("Impossible d'effectuer cette opération", [], 'Gestion des fonctionnalités');

            return back();
        }

        DB::beginTransaction();

        try {
            $fonctionalite->lib_fonctionnalite = $request->lib_fonctionnalite;
            $fonctionalite->code_module = $request->code_module;
            $fonctionalite->description_fonctionnalite = $request->description_fonctionnalite;
            $fonctionalite->lib_technique = $request->lib_technique;
            $fonctionalite->etat_fonctionnalite = $request->etat_fonctionnalite;
            $fonctionalite->code_fonctionnalite_parent = $request->code_fonctionnalite_parent;
            $fonctionalite->save();
            DB::commit();
            flash()->success('Fonctionnalité modifiée avec succès', [], 'Gestion des fonctionnalités');

            return redirect()->route('fonctionnalite.index');

        } catch (Exception $e) {
            DB::rollBack();
            flash()->error($e->getMessage());

            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $fonctionalite = Fonctionnalite::find($id);

        if ($fonctionalite == null) {
            flash()->error("Impossible d'effectuer cette opération", [], 'Gestion des modules');

            return back();
        }

        try {
            $fonctionalite->delete();
            flash()->success('Fonctionnalité supprimée avec succès', [], 'Gestion des modules');

            return redirect()->route('fonctionnalite.index');
        } catch (Exception $e) {
            flash()->error($e->getMessage());

            return back();
        }

    }
}
