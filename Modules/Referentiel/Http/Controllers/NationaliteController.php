<?php

namespace Modules\Referentiel\Http\Controllers;

use Exception;
use App\Sifec\Sifec;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\Referentiel\Entities\Nationalite;

class NationaliteController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $nationalites = Nationalite::where('supprimer',0)->get();
        return view('referentiel::nationalite.index', compact("nationalites"));
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
            'lib_nationalite'=> ['required','string','min:3']
        ]);

        try {
            $nationalite = new Nationalite();
            $nationalite->code_nationalite = Sifec::genererCodeUniqueReferentiel($nationalite,"code_nationalite",4,"CDCES_");
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
            'lib_nationalite'=> ['required','string']
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

        Nationalite::where("code_nationalite" ,$id)->update(['supprimer' => 1]);
        toastr()->success('suppression effectuée avec succès');
        return redirect()->route('nationalite.index');
    }
}
