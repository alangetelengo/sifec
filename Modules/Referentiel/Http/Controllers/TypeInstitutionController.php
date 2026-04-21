<?php

namespace Modules\Referentiel\Http\Controllers;

use App\Sifec\Sifec;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Referentiel\Entities\TypeCategorieInstitution;
use Modules\Referentiel\Entities\TypeInstitution;

class TypeInstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        // Utiliser SoftDeletes au lieu de supprimer=1
        $typeInstitutions = TypeInstitution::with('typeCategorieInstitution')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        // Charger toutes les catégories pour le formulaire
        $typeCategorieInstitutions = TypeCategorieInstitution::orderBy('lib_type_categorie_institution')->get();

        // Tous les types (modales d’édition) pour que le filtre AJAX reste utilisable
        $typeInstitutionsForModals = TypeInstitution::with('typeCategorieInstitution')
            ->orderBy('lib_type_institution')
            ->get();

        return view('referentiel::type-institution.index', compact(
            'typeInstitutions',
            'typeCategorieInstitutions',
            'typeInstitutionsForModals'
        ));
    }

    /**
     * Filter type institutions
     *
     * @return JsonResponse
     */
    public function filterTypeInstitutions(Request $request)
    {
        try {
            $query = TypeInstitution::with('typeCategorieInstitution');

            // Filtre par libellé
            if ($request->filled('lib_type_institution')) {
                $query->where('lib_type_institution', 'like', '%'.$request->lib_type_institution.'%');
            }

            // Filtre par catégorie
            if ($request->filled('code_type_categorie_ins')) {
                $query->where('code_type_categorie_ins', $request->code_type_categorie_ins);
            }

            $typeInstitutions = $query->orderBy('created_at', 'desc')->get();

            $maxResults = 500;
            $total = $typeInstitutions->count();
            $limiteAtteinte = $total > $maxResults;
            if ($limiteAtteinte) {
                $typeInstitutions = $typeInstitutions->take($maxResults);
            }

            $html = view('referentiel::type-institution.partials.table-type-institutions', compact('typeInstitutions'))->render();

            return response()->json([
                'success' => true,
                'html' => $html,
                'count' => $typeInstitutions->count(),
                'limite_atteinte' => $limiteAtteinte,
                'message' => $typeInstitutions->count().' type(s) d\'institution trouvé(s)',
            ]);

        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors du filtrage des types d\'institution: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du filtrage: '.$e->getMessage(),
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
            'lib_type_institution' => ['required', 'string', 'max:150'],
            'code_type_categorie_ins' => ['required', 'string', 'exists:tr_type_categorie_ins,code_type_categorie_ins'],
        ]);

        try {
            $typeInstitution = new TypeInstitution;
            $typeInstitution->code_type_institution = Sifec::genererCodeUniqueReferentiel($typeInstitution, 'code_type_institution', 4, 'TPINS_');
            $typeInstitution->lib_type_institution = strtoupper(trim($request->lib_type_institution));
            $typeInstitution->code_type_categorie_ins = $request->code_type_categorie_ins;
            $typeInstitution->save();

            Log::channel('sifec')->info('Type d\'institution créé avec succès', [
                'code_type_institution' => $typeInstitution->code_type_institution,
                'lib_type_institution' => $typeInstitution->lib_type_institution,
            ]);

            return redirect()
                ->route('typeInstitution.index')
                ->with('success', 'Type d\'institution ajouté avec succès.');

        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de la création d\'un type d\'institution: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $typeInstitution = TypeInstitution::find($id);

        if ($typeInstitution == null) {
            return redirect()->back()->with('error', 'Élément introuvable.');
        }

        $request->validate([
            'lib_type_institution' => ['required', 'string', 'max:150'],
            'code_type_categorie_ins' => ['required', 'string', 'exists:tr_type_categorie_ins,code_type_categorie_ins'],
        ]);

        try {
            $typeInstitution->lib_type_institution = strtoupper(trim($request->lib_type_institution));
            $typeInstitution->code_type_categorie_ins = $request->code_type_categorie_ins;
            $typeInstitution->save();

            Log::channel('sifec')->info('Type d\'institution modifié avec succès', [
                'code_type_institution' => $typeInstitution->code_type_institution,
                'lib_type_institution' => $typeInstitution->lib_type_institution,
            ]);

            return redirect()
                ->route('typeInstitution.index')
                ->with('success', 'Type d\'institution modifié avec succès.');

        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de la modification d\'un type d\'institution: '.$e->getMessage(), [
                'code_type_institution' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

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
        $typeInstitution = TypeInstitution::find($id);

        if ($typeInstitution == null) {
            return redirect()->back()->with('error', 'Élément introuvable.');
        }

        try {
            // Vérifier si des institutions utilisent ce type
            $institutionsCount = $typeInstitution->institutions()->count();
            if ($institutionsCount > 0) {
                return redirect()->back()->with(
                    'error',
                    "Impossible de supprimer ce type d'institution : {$institutionsCount} institution(s) l'utilise(nt)."
                );
            }

            // Utiliser softDeletes() au lieu de supprimer=1
            $typeInstitution->delete();

            Log::channel('sifec')->info('Type d\'institution supprimé (soft delete)', [
                'code_type_institution' => $typeInstitution->code_type_institution,
                'lib_type_institution' => $typeInstitution->lib_type_institution,
            ]);

            return redirect()
                ->route('typeInstitution.index')
                ->with('success', 'Suppression effectuée avec succès.');
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de la suppression d\'un type d\'institution: '.$e->getMessage(), [
                'code_type_institution' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
