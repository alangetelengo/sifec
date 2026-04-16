<?php

namespace Modules\Referentiel\Http\Controllers;

use App\Sifec\Sifec;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\Commune;
use Modules\Referentiel\Entities\Departement;

class CommuneController extends Controller
{
    public function index()
    {
        $communes = Commune::all();
        $departements = Departement::all();

        return view('referentiel::commune.index', compact('communes', 'departements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lib_commune' => ['required', 'string'],
            'code_departement' => ['required', 'string'],
        ]);
        DB::beginTransaction();
        try {
            $commune = new Commune;
            $commune->code_commune = Sifec::genererCodeUniqueReferentiel($commune, 'code_commune', 4, 'COM_');
            $commune->lib_commune = strtoupper($request->lib_commune);
            $commune->code_departement = $request->code_departement;
            $commune->save();
            DB::commit();

            flash()->success("$commune->lib_commune crée avec succès");

            return redirect()->route('commune.index');

        } catch (Exception $e) {
            DB::rollback();
            flash()->error($e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    public function update(Request $request, $id)
    {

        $commune = Commune::find($id);

        if ($commune == null) {
            flash()->error('Impossible de charger cette page');

            return back();
        }

        try {

            $commune->code_commune = Sifec::genererCodeUniqueReferentiel($commune, 'code_commune', 4, [], 'COM_');
            $commune->lib_commune = strtoupper($request->lib_commune);
            $commune->code_departement = $request->code_departement;
            $commune->save();
            flash()->success("$commune->lib_commune crée avec succès");

            return redirect()->route('commune.index');

        } catch (Exception $e) {

            flash()->error($e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        $commune = Commune::find($id);

        if ($commune == null) {
            flash()->error('Impossible de charger cette page');

            return back();
        }

        $commune->supprimer = 1;
        $commune->save();
        flash()->success('Suppression a été effectuée avec succès');

        return redirect()->route('commune.index');
    }
}
