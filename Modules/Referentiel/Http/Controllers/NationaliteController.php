<?php

namespace Modules\Referentiel\Http\Controllers;

use Exception;
use App\Sifec\Sifec;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Validation\Rule;
use Modules\Referentiel\Entities\Nationalite;

class NationaliteController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        // Récupérer uniquement les nationalités non supprimées (SoftDeletes)
        // Limiter à 20 résultats par défaut pour améliorer les performances
        $nationalites = Nationalite::orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return view('referentiel::nationalite.index', compact("nationalites"));
    }

    /**
     * Filtrer les nationalités côté serveur
     */
    public function filterNationalites(Request $request)
    {
        try {
            $query = Nationalite::query();

            // Filtre par libellé de nationalité
            if ($request->filled('lib_nationalite') && strlen(trim($request->lib_nationalite)) > 0) {
                $query->where('lib_nationalite', 'LIKE', '%' . trim($request->lib_nationalite) . '%');
            }

            $countInitial = $query->count();

            // Trier par date de création (plus récentes en premier)
            $nationalites = $query->orderBy('created_at', 'desc')->get();

            $countResultat = $nationalites->count();

            // Limiter les résultats à 500 maximum pour éviter les problèmes de performance
            $maxResults = 500;
            if ($countResultat > $maxResults) {
                $nationalites = $nationalites->take($maxResults);
            }

            return response()->json([
                'code' => '200',
                'data' => view('referentiel::nationalite.partials.table-nationalites', compact('nationalites'))->render(),
                'count' => $countResultat,
                'count_affiché' => $nationalites->count(),
                'limite_atteinte' => $countResultat > $maxResults
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => '500',
                'message' => 'Erreur lors de la recherche des nationalités',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'lib_nationalite'=> [
                'required',
                'string',
                'min:3',
                Rule::unique('tr_nationalite', 'lib_nationalite')->whereNull('deleted_at')
            ]
        ], [
            'lib_nationalite.unique' => 'Cette nationalité existe déjà dans le système.'
        ]);

        try {
            $nationalite = new Nationalite();
            $nationalite->code_nationalite = Sifec::genererCodeUniqueReferentiel($nationalite,"code_nationalite",4,"NAT_");
            $nationalite->lib_nationalite = $request->lib_nationalite;
            $nationalite->save();
            toastr()->success('Nationalité ajoutée avec succès');
            return redirect()->route('nationalite.index');

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
        $nationalite = Nationalite::where("code_nationalite" ,$id)->first();

        if($nationalite == null){
            toastr()->error("Impossible de charger cette page");
            return redirect()->back();
        }

        $request->validate([
            'lib_nationalite'=> [
                'required',
                'string',
                Rule::unique('tr_nationalite', 'lib_nationalite')->whereNull('deleted_at')->ignore($nationalite->code_nationalite, 'code_nationalite')
            ]
        ], [
            'lib_nationalite.unique' => 'Cette nationalité existe déjà dans le système.'
        ]);

        try {
            $nationalite->lib_nationalite = $request->lib_nationalite;
            $nationalite->save();
            toastr()->success('Nationalité modifiée avec succès');
            return redirect()->route('nationalite.index');

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
        $nationalite = Nationalite::where("code_nationalite" ,$id)->first();

        if($nationalite == null){
            toastr()->error("Impossible de charger cette page");
            return redirect()->back();
        }

        try {
            // Vérifier les relations avant suppression
            if ($nationalite->personnes()->count() > 0) {
                toastr()->error("Impossible de supprimer cette nationalité car elle est utilisée par des personnes");
                return redirect()->back();
            }

            $nationalite->delete();
            toastr()->success('suppression effectuée avec succès');
            return redirect()->route('nationalite.index');
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back();
        }
    }
}
