<?php

namespace Modules\Referentiel\Http\Controllers;

use Exception;
use App\Sifec\Sifec;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\Referentiel\Entities\TypeLocalite;

class TypeLocaliteController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $typeLocalites = TypeLocalite::where("supprimer", 0)->get();
        return view('referentiel::type-localite.index', compact("typeLocalites"));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'lib_type_localite' => ['required', 'string', 'max:150', 'unique:tr_type_localite,lib_type_localite']
        ]);

        try {
            $typeLocalite = new TypeLocalite();
            $typeLocalite->code_type_localite = Sifec::genererCodeUniqueReferentiel($typeLocalite, "code_type_localite", 4, "TPLOC_");
            $typeLocalite->lib_type_localite = $request->lib_type_localite;
            $typeLocalite->save();

            toastr()->success('Type de localité ajouté avec succès');
            return redirect()->route('typelocalite.index');

        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $typeLocalite = TypeLocalite::where('code_type_localite', $id)->first();

        if ($typeLocalite == null) {
            toastr()->error("Impossible de charger cette page");
            return redirect()->back();
        }

        $request->validate([
            'lib_type_localite' => ['required', 'string', 'max:150', 'unique:tr_type_localite,lib_type_localite,' . $id . ',code_type_localite']
        ]);

        try {
            $typeLocalite->lib_type_localite = $request->lib_type_localite;
            $typeLocalite->save();

            toastr()->success('Type de localité modifié avec succès');
            return redirect()->route('typelocalite.index');

        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param string $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {

            $typeLocalite = TypeLocalite::where('code_type_localite', $id)->first();

            if ($typeLocalite == null) {
                Log::channel('sifec')->error("Type de localité non trouvé: " . $id);
                toastr()->error("Impossible de charger cette page");
                return redirect()->back();
            }

            // Vérifier si des localités utilisent ce type
            $countLocalites = $typeLocalite->localites()->count();

            if ($countLocalites > 0) {
                Log::channel('sifec')->warning("Impossible de supprimer le type de localité " . $id . " car il est utilisé par " . $countLocalites . " localité(s)");
                toastr()->error("Impossible de supprimer ce type de localité car il est utilisé par des localités");
                return redirect()->back();
            }

            TypeLocalite::where('code_type_localite', $id)->update(['supprimer' => 1]);
            toastr()->success("Suppression effectuée avec succès");
            return redirect()->route("typelocalite.index");
        } catch (Exception $e) {
            Log::channel('sifec')->error("Erreur lors de la suppression du type de localité " . $id . ": " . $e->getMessage());
            Log::channel('sifec')->error("Stack trace: " . $e->getTraceAsString());
            toastr()->error($e->getMessage());
            return redirect()->back();
        }
    }
}

