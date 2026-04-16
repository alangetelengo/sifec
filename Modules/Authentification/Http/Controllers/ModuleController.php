<?php

namespace Modules\Authentification\Http\Controllers;

use App\Sifec\Sifec;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Authentification\Entities\Module;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Module::where('supprimer', 0)->get();

        return view('authentification::module.index', compact('modules'));
    }

    public function create()
    {
        return view('authentification::module.create');
    }

    public function fonctionnalites($id)
    {
        $module = Module::with('fonctionnalites')->find($id);

        if ($module === null) {
            return response()->json([
                'fonctionnalites' => [],
            ]);
        }

        return response()->json([
            'fonctionnalites' => $module->fonctionnalites,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'lib_module' => ['required', 'string', 'unique:tr_module'],
            'description_module' => ['required', 'string', 'min:15'],
            'etat_module' => ['required', 'string'],
        ]);
        DB::beginTransaction();
        try {
            $module = new Module;

            $code = Sifec::genererCodeUniqueReferentiel($module, 'code_module', 4, 'MOD_');

            $module->code_module = $code;
            $module->lib_module = $request->lib_module;
            $module->description_module = $request->description_module;
            $module->etat_module = $request->etat_module;
            $module->save();
            DB::commit();

            return redirect()->to(route('module.index', [], false))
                ->with('success', 'Module créé avec succès.');
        } catch (Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $module = Module::find($id);

        if ($module === null) {
            return back()->with('error', "Impossible d'effectuer cette opération.");
        }

        return view('authentification::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $module = Module::find($id);

        if ($module === null) {
            return back()->with('error', "Impossible d'effectuer cette opération.");
        }

        return view('authentification::module.edit', compact('module'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'lib_module' => ['required', 'string'],
            'description_module' => ['required', 'string', 'min:15'],
            'etat_module' => ['required', 'string'],
        ]);

        $module = Module::find($id);

        if ($module === null) {
            return back()->with('error', "Impossible d'effectuer cette opération.");
        }

        DB::beginTransaction();
        try {
            $module->lib_module = $request->lib_module;
            $module->description_module = $request->description_module;
            $module->etat_module = $request->etat_module;
            $module->save();
            DB::commit();

            return redirect()->to(route('module.index', [], false))
                ->with('success', 'Module modifié avec succès.');
        } catch (Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $module = Module::find($id);

        if ($module === null) {
            return back()->with('error', "Impossible d'effectuer cette opération.");
        }

        try {
            $module->update(['supprimer' => 1]);

            return redirect()->to(route('module.index', [], false))
                ->with('success', 'Module supprimé avec succès.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
