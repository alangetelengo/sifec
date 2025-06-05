<?php

namespace Modules\Referentiel\Http\Controllers;

use Exception;
use App\Sifec\Sifec;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\Referentiel\Entities\TypeInstitution;

class TypeInstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $typeInstitutions = TypeInstitution::where("supprimer",0)->get();
        return view('referentiel::type-institution.index',compact("typeInstitutions"));
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'lib_type_institution'=> ['required','string'],
            'lib_categorie'=> ['required','string']
        ]);

        try {
            $typeInstitution = new TypeInstitution();
            $typeInstitution->code_type_institution = Sifec::genererCodeUniqueReferentiel($typeInstitution,"code_type_institution",4,"TPINS_");
            $typeInstitution->lib_type_institution = $request->lib_type_institution;
            $typeInstitution->lib_categorie = $request->lib_categorie;
            $typeInstitution->save();
            toastr()->success('Type d\'institution ajouté avec succès');
            return redirect()->route('typeInstitution.index');

        } catch (Exception $e) {

            toastr()->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function update(Request $request, $id)
    {
        $typeInstitution = TypeInstitution::where('code_type_institution',$id)->first();

        if ($typeInstitution == null){
            toastr()->error("Impossible de charger cette page");
            return redirect()->back();
        }

        $request->validate([
            'lib_type_institution'=> ['required','string'],
            'lib_categorie'=> ['required','string']
        ]);

        try {
            $typeInstitution->lib_categorie = $request->lib_categorie;
            $typeInstitution->lib_type_institution = $request->lib_type_institution;
            $typeInstitution->save();
            toastr()->success('Type d\'institution modifié avec succès');
            return redirect()->route('typeInstitution.index');

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
        $typeInstitution = TypeInstitution::where('code_type_institution',$id)->first();

        if ($typeInstitution == null){
            toastr()->error("Impossible de charger cette page");
            return redirect()->back();
        }

        try {
            TypeInstitution::where('code_type_institution',$id)->update(['supprimer' => 1]);
            toastr()->success("Suppression effectuée avec succès");
            return redirect()->route("typeInstitution.index");
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
