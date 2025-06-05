<?php

namespace Modules\Referentiel\Http\Controllers;

use Exception;
use App\Sifec\Sifec;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Referentiel\Entities\Localite;
use Illuminate\Contracts\Support\Renderable;
use Modules\Referentiel\Entities\TypeLocalite;

class SubArrondissementComUrbaineController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $quartiersVillages = Localite::whereIn("code_type_localite",["TPLOC_0007","TPLOC_0008"])->orderBy('code_localite', 'DESC')->get();
        $arrondissementsComUrb = Localite::whereIn("code_type_localite",["TPLOC_0004","TPLOC_0005"])->get();
        $typeLocalites = TypeLocalite::whereIn("code_type_localite",["TPLOC_0007","TPLOC_0008"])->get();

        // dd($arrondissementsComUrb);
        return view('referentiel::quartier-village.index', compact("quartiersVillages","arrondissementsComUrb","typeLocalites"));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('referentiel::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'lib_localite'=> ['required','string'],
            'code_localite_parent'=> ['required','string']
        ]);

        try {
            $localite = new Localite();
            $localite->code_localite = Sifec::genererCodeUniqueReferentiel($localite,"code_localite",4,"LOC_");
            $localite->lib_localite = strtoupper($request->lib_localite);
            $localite->code_type_localite = $request->code_type_localite;
            $localite->code_localite_parent = $request->code_localite_parent;
            $localite->save();
            toastr()->success("$localite->lib_localite crée avec succès");
            return redirect()->route('quartierVillage.index');

        } catch (Exception $e) {

            toastr()->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('referentiel::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('referentiel::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $localite = Localite::find($id);
            // dd($request->all());


            if($localite == null){
                toastr()->error("Impossible de charger cette page");
                return back();
            }

            try {
                $localite->lib_localite = strtoupper($request->lib_localite);
                $localite->code_type_localite = $request->code_type_localite;
                $localite->code_localite_parent = $request->code_localite_parent;
                $localite->save();
                toastr()->success("$localite->lib_localite modifié avec succès");
                return redirect()->route('quartierVillage.index');

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
        $quartiervillage = Localite::find($id);

        if($quartiervillage == null){
            toastr()->error("Impossible de charger cette page");
            return back();
        }
        $quartiervillage->delete();
        toastr()->success("Elément supprimé avec succès");
        return back();
    }
}
