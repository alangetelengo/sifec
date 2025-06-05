<?php

namespace Modules\Referentiel\Http\Controllers;

use Exception;
use App\Sifec\Sifec;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\Referentiel\Entities\Profession;

class ProfessionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $professions = Profession::where('supprimer',0)->get();
        return view('referentiel::profession.index',compact('professions'));
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'lib_profession' => ["required","string", "min:2"]
        ]);

        try {
            $profession = new Profession();
            $profession->code_profession = Sifec::genererCodeUniqueReferentiel($profession,"code_profession",4,"PROF_");
            $profession->lib_profession = $request->lib_profession;
            $profession->save();

            toastr()->success("Profession enregistrée avec succès");
            return redirect()->route("profession.index");
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
        $profession = Profession::where('code_profession',$id)->first();

        if ($profession == null){
            toastr()->error("Impossible de charger cette page");
            return redirect()->back();
        }

        $request->validate([
            'lib_profession' => ["required","string", "min:2"]
        ]);

        try {
            $profession->lib_profession = $request->lib_profession;
            $profession->save();

            toastr()->success("Profession modifiée avec succès");
            return redirect()->route("profession.index");
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
        $profession = Profession::where('code_profession',$id)->first();

        if ($profession == null){
            toastr()->error("Impossible de charger cette page");
            return redirect()->back();
        }

        $profession = Profession::where("code_profession",$id)->update(['supprimer'=>1]);
        toastr()->success("Suppression a été effectuée avec succès");
        return redirect()->route("profession.index");
    }
}
