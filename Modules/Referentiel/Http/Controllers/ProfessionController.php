<?php

namespace Modules\Referentiel\Http\Controllers;

use App\Sifec\Sifec;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Modules\Referentiel\Entities\Profession;

class ProfessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        // Récupérer uniquement les professions non supprimées (SoftDeletes)
        // Limiter à 20 résultats par défaut pour améliorer les performances
        $professions = Profession::orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return view('referentiel::profession.index', compact('professions'));
    }

    /**
     * Filtrer les professions côté serveur
     */
    public function filterProfessions(Request $request)
    {
        try {
            $query = Profession::query();

            // Filtre par libellé de profession
            if ($request->filled('lib_profession') && strlen(trim($request->lib_profession)) > 0) {
                $query->where('lib_profession', 'LIKE', '%'.trim($request->lib_profession).'%');
            }

            $countInitial = $query->count();

            // Trier par date de création (plus récentes en premier)
            $professions = $query->orderBy('created_at', 'desc')->get();

            $countResultat = $professions->count();

            // Limiter les résultats à 500 maximum pour éviter les problèmes de performance
            $maxResults = 500;
            if ($countResultat > $maxResults) {
                $professions = $professions->take($maxResults);
            }

            return response()->json([
                'code' => '200',
                'data' => view('referentiel::profession.partials.table-professions', compact('professions'))->render(),
                'count' => $countResultat,
                'count_affiché' => $professions->count(),
                'limite_atteinte' => $countResultat > $maxResults,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => '500',
                'message' => 'Erreur lors de la recherche des professions',
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
            'lib_profession' => [
                'required',
                'string',
                'min:2',
                Rule::unique('tr_profession', 'lib_profession')->whereNull('deleted_at'),
            ],
        ], [
            'lib_profession.unique' => 'Cette profession existe déjà dans le système.',
        ]);

        try {
            $profession = new Profession;
            $profession->code_profession = Sifec::genererCodeUniqueReferentiel($profession, 'code_profession', 4, 'PROF_');
            $profession->lib_profession = $request->lib_profession;
            $profession->save();

            return redirect()->route('profession.index')->with('success', 'Profession enregistrée avec succès');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
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
        $profession = Profession::where('code_profession', $id)->first();

        if ($profession == null) {
            return redirect()->back()->with('error', 'Impossible de charger cette page');
        }

        $request->validate([
            'lib_profession' => [
                'required',
                'string',
                'min:2',
                Rule::unique('tr_profession', 'lib_profession')->whereNull('deleted_at')->ignore($profession->code_profession, 'code_profession'),
            ],
        ], [
            'lib_profession.unique' => 'Cette profession existe déjà dans le système.',
        ]);

        try {
            $profession->lib_profession = $request->lib_profession;
            $profession->save();

            return redirect()->route('profession.index')->with('success', 'Profession modifiée avec succès');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
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
        $profession = Profession::where('code_profession', $id)->first();

        if ($profession == null) {
            return redirect()->back()->with('error', 'Impossible de charger cette page');
        }

        try {
            // Vérifier les relations avant suppression
            if ($profession->personnes()->count() > 0) {
                return redirect()->back()->with('error', 'Impossible de supprimer cette profession car elle est utilisée par des personnes');
            }

            $profession->delete();

            return redirect()->route('profession.index')->with('success', 'Suppression effectuée avec succès');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
