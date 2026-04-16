<?php

namespace Modules\Authentification\Http\Controllers;

use App\Sifec\Sifec;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Authentification\Entities\Module;
use Modules\Referentiel\Entities\Fonction;

class FonctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        $fonctions = Fonction::query()
            ->where(function ($q): void {
                $q->where('supprimer', 0)->orWhereNull('supprimer');
            })
            ->orderBy('lib_fonction')
            ->get();

        return view('authentification::fonction.index', compact('fonctions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'lib_fonction' => ['required', 'string'],
        ]);

        DB::beginTransaction();

        try {

            $fonctions = new Fonction;
            $code = Sifec::genererCodeUniqueReferentiel($fonctions, 'code_fonction', 4, 'FONC_');

            $fonctions->code_fonction = $code;
            $fonctions->lib_fonction = $request->lib_fonction;
            $fonctions->save();

            DB::commit();

            return redirect()->to(route('fonction.index', [], false))
                ->with('success', 'La fonction a été créée avec succès.');
        } catch (Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', $e->getMessage());
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('authentification::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $fonction = Fonction::find($id);

        if ($fonction === null) {
            return back()->with('error', "Impossible d'effectuer cette opération.");
        }

        $request->validate([
            'lib_fonction' => ['required', 'string'],
        ]);

        DB::beginTransaction();

        try {
            $fonction->lib_fonction = $request->lib_fonction;
            $fonction->save();

            DB::commit();

            return redirect()->to(route('fonction.index', [], false))
                ->with('success', 'La modification a été effectuée avec succès.');
        } catch (Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', $e->getMessage());
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
        $fonction = Fonction::find($id);

        if ($fonction === null) {
            return back()->with('error', "Impossible d'effectuer cette opération.");
        }

        Fonction::where('code_fonction', $id)->update(['supprimer' => 1]);

        return redirect()->to(route('fonction.index', [], false))
            ->with('success', 'La fonction a été supprimée avec succès.');
    }

    public function assigner($id)
    {
        $fonction = Fonction::with('fonctionnalites')->find($id);

        if ($fonction === null) {
            return back()->with('error', 'Impossible de charger cette page.');
        }

        $modules = Module::with('fonctionnalites')->get();

        $assignedCodes = $fonction->fonctionnalites
            ->pluck('code_fonctionnalite')
            ->unique()
            ->values()
            ->all();

        $totalFonctionnalites = (int) $modules->sum(static function ($m) {
            return $m->fonctionnalites->count();
        });

        return view('authentification::fonction.assignation', compact(
            'modules',
            'fonction',
            'assignedCodes',
            'totalFonctionnalites'
        ));
    }

    public function storeAssigner(Request $request, $id)
    {
        $fonction = Fonction::find($id);

        if ($fonction === null) {
            return back()->with('error', 'Impossible de charger cette page.');
        }

        DB::beginTransaction();
        try {
            if ($request->fonctionnalites != null) {
                $fonction->fonctionnalites()->sync($request->fonctionnalites);
            } else {
                $fonction->fonctionnalites()->sync([]);
            }

            DB::commit();

            return redirect()->to(route('fonction.index', [], false))
                ->with('success', 'Les fonctionnalités ont été enregistrées avec succès.');
        } catch (Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
