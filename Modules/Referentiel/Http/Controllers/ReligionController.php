<?php

namespace Modules\Referentiel\Http\Controllers;

use App\Sifec\Sifec;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Modules\Referentiel\Entities\Religion;

class ReligionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        // Récupérer uniquement les religions non supprimées (SoftDeletes)
        // Limiter à 20 résultats par défaut pour améliorer les performances
        $religions = Religion::orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return view('referentiel::religion.index', compact('religions'));
    }

    /**
     * Filtrer les religions côté serveur
     */
    public function filterReligions(Request $request)
    {
        try {
            $query = Religion::query();

            // Filtre par libellé de religion
            if ($request->filled('lib_religion') && strlen(trim($request->lib_religion)) > 0) {
                $query->where('lib_religion', 'LIKE', '%'.trim($request->lib_religion).'%');
            }

            $countInitial = $query->count();

            // Trier par date de création (plus récentes en premier)
            $religions = $query->orderBy('created_at', 'desc')->get();

            $countResultat = $religions->count();

            // Limiter les résultats à 500 maximum pour éviter les problèmes de performance
            $maxResults = 500;
            if ($countResultat > $maxResults) {
                $religions = $religions->take($maxResults);
            }

            return response()->json([
                'code' => '200',
                'data' => view('referentiel::religion.partials.table-religions', compact('religions'))->render(),
                'count' => $countResultat,
                'count_affiché' => $religions->count(),
                'limite_atteinte' => $countResultat > $maxResults,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => '500',
                'message' => 'Erreur lors de la recherche des religions',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'lib_religion' => [
                'required',
                'string',
                'min:2',
                Rule::unique('tr_religion', 'lib_religion')->whereNull('deleted_at'),
            ],
        ], [
            'lib_religion.unique' => 'Cette religion existe déjà dans le système.',
        ]);

        try {
            $religion = new Religion;
            $religion->code_religion = Sifec::genererCodeUniqueReferentiel($religion, 'code_religion', 4, 'RELI_');
            $religion->lib_religion = $request->lib_religion;
            $religion->save();

            flash()->success('Réligion enregistrée avec succès');

            return redirect()->route('religion.index');
        } catch (Exception $e) {
            flash()->error($e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $religion = Religion::where('code_religion', $id)->first();

        if ($religion == null) {
            flash()->error('Impossible de charger cette page');

            return redirect()->back();
        }

        $request->validate([
            'lib_religion' => [
                'required',
                'string',
                'min:2',
                Rule::unique('tr_religion', [], 'lib_religion')->whereNull('deleted_at')->ignore($religion->code_religion, 'code_religion'),
            ],
        ], [
            'lib_religion.unique' => 'Cette religion existe déjà dans le système.',
        ]);

        try {
            $religion->lib_religion = $request->lib_religion;
            $religion->save();

            flash()->success('Réligion modifiée avec succès');

            return redirect()->route('religion.index');
        } catch (Exception $e) {
            flash()->error($e->getMessage());

            return redirect()->back()->withInput();
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
        $religion = Religion::where('code_religion', $id)->first();

        if ($religion == null) {
            flash()->error('Impossible de charger cette page');

            return redirect()->back();
        }

        try {
            // Vérifier les relations avant suppression
            if ($religion->declarationsDeces()->count() > 0) {
                flash()->error('Impossible de supprimer cette religion car elle est utilisée par des déclarations de décès');

                return redirect()->back();
            }

            $religion->delete();
            flash()->success('Suppression a été effectuée avec succès');

            return redirect()->route('religion.index');
        } catch (Exception $e) {
            flash()->error($e->getMessage());

            return redirect()->back();
        }
    }
}
