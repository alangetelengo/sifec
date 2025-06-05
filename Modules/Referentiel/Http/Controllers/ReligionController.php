<?php

namespace Modules\Referentiel\Http\Controllers;

use App\Sifec\Sifec;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Referentiel\Entities\Religion;
use Illuminate\Contracts\Support\Renderable;

class ReligionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $religions = Religion::where('supprimer',0)->get();
        return view('referentiel::religion.index',compact('religions'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'lib_religion' => ["required","string", "min:2"]
        ]);

        try {
            $religion = new Religion();
            $religion->code_religion = Sifec::genererCodeUniqueReferentiel($religion,"code_religion",4,"RELI_");
            $religion->lib_religion = $request->lib_religion;
            $religion->save();

            toastr()->success("Réligion enregistrée avec succès");
            return redirect()->route("religion.index");
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
        $religion = Religion::where('code_religion',$id)->first();

        if ($religion == null){
            toastr()->error("Impossible de charger cette page");
            return redirect()->back();
        }

        $request->validate([
            'lib_religion' => ["required","string", "min:2"]
        ]);

        try {
            $religion->lib_religion = $request->lib_religion;
            $religion->save();

            toastr()->success("Réligion modifiée avec succès");
            return redirect()->route("religion.index");
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
        $religion = Religion::where('code_religion',$id)->first();

        if ($religion == null){
            toastr()->error("Impossible de charger cette page");
            return redirect()->back();
        }

        $religion = Religion::where("code_religion",$id)->update(['supprimer'=>1]);
        toastr()->success("Suppression a été effectuée avec succès");
        return redirect()->route("religion.index");
    }
}
