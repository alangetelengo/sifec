<?php

namespace Modules\Referentiel\Http\Controllers;

use App\Sifec\Sifec;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Referentiel\Entities\CauseDeces;

class CausedecesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $causeDeces = CauseDeces::where('supprimer',0)->get();
        return view('referentiel::cause-deces.index',compact('causeDeces'));
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
            'lib_cause_deces'=> ['required','string','min:3']
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
            'lib_cause_deces'=> ['required','string']
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

            CauseDeces::where('code_cause_deces',$id)->update(['supprimer' => 1]);
            toastr()->success("Suppression effectuée avec succès");
            return redirect()->route("causedeces.index");
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
