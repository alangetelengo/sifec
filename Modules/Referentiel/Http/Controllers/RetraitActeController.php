<?php

namespace Modules\Referentiel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Referentiel\Entities\Personne;
use Illuminate\Contracts\Support\Renderable;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Mariage\Entities\DeclarationMariage;
use Modules\Naissance\Entities\Declarationnaissance;

class RetraitActeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('referentiel::retrait-acte.index');
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
    public function searchActeRetire(Request $request)
    {

        $request->validate([
            "nom_enfant"=> ["required","string"],
            "sexe_enfant"=> ["required","string"],
            "annee_naissance_enfant"=> ["required"],
        ]);

        $nom = $request->nom_enfant;
        $prenom = $request->prenom_enfant;
        $sexe = $request->sexe_enfant;
        $annee = $request->annee_naissance_enfant;
        $resultatRecherche = [];
        //$personnes->declarations->acte

        //recherche de la personne
        $personnes = Personne::where("nom",'LIKE',"%{$nom}%")->where("prenom",'LIKE',"%{$prenom}%")->where("sexe",$sexe)->whereYear("date_naissance",$annee)->get();
        //vérification personnes
      

        if(count($personnes ) == 0){
            toastr()->error("Aucune information trouvée !");
            return back()->withInput();
        }
        //recherche de la declaration de chaque personne
        foreach ($personnes as $personne) {

            //declaration de naissance
            $dn = Declarationnaissance::where("code_enfant",$personne->code_personne)->first();

            if($dn == null){
                toastr()->error("Aucune déclaration de naissance trouvée avec ces informations !");
                return back()->withInput();
            }else{
                //vérification de l'acte de naissance
                $acte = ActeNaissance::where('code_declaration_naissance', $dn->code_declaration_naissance)
                    ->with(['retrait', 'declaration.enfant', 'declaration.pere', 'declaration.mere'])
                    ->first();


                if($acte->signature_mairie == null){

                    toastr()->error("Acte de naissance en cours de production !");
                    return back()->withInput();
                }else{
                    // $resultatRecherche =
                    // dd($acte);
                    return view('referentiel::retrait-acte.index',compact("acte"));
                }
            }
        }
        // if($personne == null){
        //     toastr()->error("Aucune personne trouvée avec ces informations !");
        //     return back()->withInput();
        // }


        // if($dm == null){
        //     toastr()->error("Il n'y a aucun formulaire type enregistré portant cette identité !");
        //     return back()->withInput();
        // }
        // if($dn == null){
        //     toastr()->error("Il n'y a aucune déclaration de naissance  de l'enfant !");
        //     return back()->withInput();
        // }


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
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
