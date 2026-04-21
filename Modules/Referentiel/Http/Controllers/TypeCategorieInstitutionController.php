<?php

namespace Modules\Referentiel\Http\Controllers;

use App\Sifec\Sifec;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Referentiel\Entities\TypeCategorieInstitution;

class TypeCategorieInstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        $typeCategorieInstitutions = TypeCategorieInstitution::orderBy('lib_type_categorie_institution')->get();

        return view('referentiel::type-categorie-institution.index', compact('typeCategorieInstitutions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'lib_type_categorie_institution' => ['required', 'string', 'max:255', 'unique:tr_type_categorie_ins,lib_type_categorie_institution'],
            'image_illustrative' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        try {
            $typeCategorieInstitution = new TypeCategorieInstitution;
            $typeCategorieInstitution->code_type_categorie_ins = Sifec::genererCodeUniqueReferentiel(
                $typeCategorieInstitution,
                'code_type_categorie_ins',
                4,
                'TCINS_'
            );
            $typeCategorieInstitution->lib_type_categorie_institution = strtoupper(trim($request->lib_type_categorie_institution));

            if ($request->hasFile('image_illustrative')) {
                $file = $request->file('image_illustrative');
                if ($file->isValid()) {
                    $image = $file->store('type_categorie_institution');
                    $typeCategorieInstitution->image_illustrative = $image;
                }
            }

            $typeCategorieInstitution->save();

            Log::channel('sifec')->info('Catégorie d\'institution créée avec succès', [
                'code_type_categorie_ins' => $typeCategorieInstitution->code_type_categorie_ins,
                'lib_type_categorie_institution' => $typeCategorieInstitution->lib_type_categorie_institution,
            ]);

            return redirect()
                ->route('typeCategorieInstitution.index')
                ->with('success', 'Catégorie d\'institution ajoutée avec succès.');

        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de la création d\'une catégorie d\'institution: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  string  $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $typeCategorieInstitution = TypeCategorieInstitution::find($id);

        if ($typeCategorieInstitution == null) {
            return redirect()->back()->with('error', 'Élément introuvable.');
        }

        $request->validate([
            'lib_type_categorie_institution' => ['required', 'string', 'max:255', 'unique:tr_type_categorie_ins,lib_type_categorie_institution,'.$id.',code_type_categorie_ins'],
            'image_illustrative' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        try {
            $typeCategorieInstitution->lib_type_categorie_institution = strtoupper(trim($request->lib_type_categorie_institution));

            if ($request->hasFile('image_illustrative')) {
                $file = $request->file('image_illustrative');
                if ($file->isValid()) {
                    $image = $file->store('type_categorie_institution');
                    $typeCategorieInstitution->image_illustrative = $image;
                }
            }

            $typeCategorieInstitution->save();

            Log::channel('sifec')->info('Catégorie d\'institution modifiée avec succès', [
                'code_type_categorie_ins' => $typeCategorieInstitution->code_type_categorie_ins,
                'lib_type_categorie_institution' => $typeCategorieInstitution->lib_type_categorie_institution,
            ]);

            return redirect()
                ->route('typeCategorieInstitution.index')
                ->with('success', 'Catégorie d\'institution modifiée avec succès.');

        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de la modification d\'une catégorie d\'institution: '.$e->getMessage(), [
                'code_type_categorie_ins' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $typeCategorieInstitution = TypeCategorieInstitution::find($id);

        if ($typeCategorieInstitution == null) {
            return redirect()->back()->with('error', 'Élément introuvable.');
        }

        try {
            // Vérifier si des types d'institutions utilisent cette catégorie
            $typeInstitutionsCount = $typeCategorieInstitution->typeInstitutions()->count();
            if ($typeInstitutionsCount > 0) {
                return redirect()->back()->with(
                    'error',
                    "Impossible de supprimer cette catégorie : {$typeInstitutionsCount} type(s) d'institution l'utilise(nt)."
                );
            }

            // Utiliser softDeletes()
            $typeCategorieInstitution->delete();

            Log::channel('sifec')->info('Catégorie d\'institution supprimée (soft delete)', [
                'code_type_categorie_ins' => $typeCategorieInstitution->code_type_categorie_ins,
                'lib_type_categorie_institution' => $typeCategorieInstitution->lib_type_categorie_institution,
            ]);

            return redirect()
                ->route('typeCategorieInstitution.index')
                ->with('success', 'Suppression effectuée avec succès.');
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de la suppression d\'une catégorie d\'institution: '.$e->getMessage(), [
                'code_type_categorie_ins' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
