<?php

namespace Modules\Referentiel\Http\Controllers;

use App\Sifec\Sifec;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Modules\Referentiel\Entities\CauseDeces;

class CausedecesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        // Récupérer uniquement les causes de décès non supprimées (SoftDeletes)
        // Limiter à 20 résultats par défaut pour améliorer les performances
        $causeDeces = CauseDeces::orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return view('referentiel::cause-deces.index',compact('causeDeces'));
    }

    /**
     * Filtrer les causes de décès côté serveur
     */
    public function filterCauseDeces(Request $request)
    {
        try {
            $query = CauseDeces::query();

            // Filtre par libellé de cause de décès
            if ($request->filled('lib_cause_deces') && strlen(trim($request->lib_cause_deces)) > 0) {
                $query->where('lib_cause_deces', 'LIKE', '%' . trim($request->lib_cause_deces) . '%');
            }

            $countInitial = $query->count();

            // Trier par date de création (plus récentes en premier)
            $causeDeces = $query->orderBy('created_at', 'desc')->get();

            $countResultat = $causeDeces->count();

            // Limiter les résultats à 500 maximum pour éviter les problèmes de performance
            $maxResults = 500;
            if ($countResultat > $maxResults) {
                $causeDeces = $causeDeces->take($maxResults);
            }

            return response()->json([
                'code' => '200',
                'data' => view('referentiel::cause-deces.partials.table-cause-deces', compact('causeDeces'))->render(),
                'count' => $countResultat,
                'count_affiché' => $causeDeces->count(),
                'limite_atteinte' => $countResultat > $maxResults
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => '500',
                'message' => 'Erreur lors de la recherche des causes de décès',
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
            'lib_cause_deces'=> [
                'required',
                'string',
                'min:3',
                Rule::unique('tr_cause_deces', 'lib_cause_deces')->whereNull('deleted_at')
            ]
        ], [
            'lib_cause_deces.unique' => 'Cette cause de décès existe déjà dans le système.'
        ]);

        try {
            $causeDeces = new CauseDeces();
            $causeDeces->code_cause_deces = Sifec::genererCodeUniqueReferentiel($causeDeces,"code_cause_deces",4,"CDCES_");
            $causeDeces->lib_cause_deces = $request->lib_cause_deces;
            $causeDeces->save();
            toastr()->success('Cause décès ajoutée avec succès');
            return redirect()->route('causedeces.index');

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

        $causeDeces = CauseDeces::where("code_cause_deces" ,$id)->first();

        if($causeDeces == null){
            toastr()->error("Impossible de charger cette page");
            return redirect()->back();
        }

        $request->validate([
            'lib_cause_deces'=> [
                'required',
                'string',
                Rule::unique('tr_cause_deces', 'lib_cause_deces')->whereNull('deleted_at')->ignore($causeDeces->code_cause_deces, 'code_cause_deces')
            ]
        ], [
            'lib_cause_deces.unique' => 'Cette cause de décès existe déjà dans le système.'
        ]);

        try {
            $causeDeces->lib_cause_deces = $request->lib_cause_deces;
            $causeDeces->save();
            toastr()->success('Cause décès modifiée avec succès');
            return redirect()->route('causedeces.index');

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
        $causeDeces = CauseDeces::where("code_cause_deces" ,$id)->first();

        if($causeDeces == null){
            toastr()->error("Impossible de charger cette page");
            return redirect()->back();
        }
        try {
            // Vérifier les relations avant suppression
            if ($causeDeces->declarationsDeces()->count() > 0) {
                toastr()->error("Impossible de supprimer cette cause de décès car elle est utilisée par des déclarations");
                return redirect()->back();
            }

            $causeDeces->delete();
            toastr()->success("Suppression effectuée avec succès");
            return redirect()->route("causedeces.index");
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
