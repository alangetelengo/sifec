<?php

namespace Modules\Referentiel\Http\Controllers;

use App\Sifec\Sifec;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Referentiel\Entities\TypeLocalite;

class TypeLocaliteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        $typeLocalites = TypeLocalite::query()->orderBy('lib_type_localite')->get();

        return view('referentiel::type-localite.index', compact('typeLocalites'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'lib_type_localite' => ['required', 'string', 'max:150', 'unique:tr_type_localite,lib_type_localite'],
        ]);

        try {
            $typeLocalite = new TypeLocalite;
            $typeLocalite->code_type_localite = Sifec::genererCodeUniqueReferentiel($typeLocalite, 'code_type_localite', 4, 'TPLOC_');
            $typeLocalite->lib_type_localite = $request->lib_type_localite;
            $typeLocalite->save();

            return redirect()->route('typelocalite.index')->with('success', 'Type de localité ajouté avec succès');

        } catch (Exception $e) {
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
        $typeLocalite = TypeLocalite::where('code_type_localite', $id)->first();

        if ($typeLocalite == null) {
            return redirect()->back()->with('error', 'Impossible de charger cette page');
        }

        $request->validate([
            'lib_type_localite' => ['required', 'string', 'max:150', 'unique:tr_type_localite,lib_type_localite,'.$id.',code_type_localite'],
        ]);

        try {
            $typeLocalite->lib_type_localite = $request->lib_type_localite;
            $typeLocalite->save();

            return redirect()->route('typelocalite.index')->with('success', 'Type de localité modifié avec succès');

        } catch (Exception $e) {
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
        try {

            $typeLocalite = TypeLocalite::where('code_type_localite', $id)->first();

            if ($typeLocalite == null) {
                Log::channel('sifec')->error('Type de localité non trouvé: '.$id);

                return redirect()->back()->with('error', 'Impossible de charger cette page');
            }

            // Vérifier si des localités utilisent ce type
            $countLocalites = $typeLocalite->localites()->count();

            if ($countLocalites > 0) {
                Log::channel('sifec')->warning('Impossible de supprimer le type de localité '.$id.' car il est utilisé par '.$countLocalites.' localité(s)');

                return redirect()->back()->with('error', 'Impossible de supprimer ce type de localité car il est utilisé par des localités');
            }

            $typeLocalite->delete();

            return redirect()->route('typelocalite.index')->with('success', 'Suppression effectuée avec succès');
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de la suppression du type de localité '.$id.': '.$e->getMessage());
            Log::channel('sifec')->error('Stack trace: '.$e->getTraceAsString());

            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
