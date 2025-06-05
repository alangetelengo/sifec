<?php

namespace Modules\EtatCivilRDC\Http\Controllers;

use App\Sifec\Sifec;
use App\Sifec\SifecFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Renderable;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\Localite;

class EtatCivilRDCController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $anneEncours = date("Y");
        $listeAnnees = DB::select('select DISTINCT(YEAR(created_at)) as annee from t_demande_document ORDER BY annee DESC');
        return view('etatcivilrdc::dashboard-rdc.index', compact("listeAnnees","anneEncours"));
    }

    // public function detailDashboard()
    // {
    //     return view('etatcivilrdc::dashboard-rdc.detail');
    // }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('etatcivilrdc::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('etatcivilrdc::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('etatcivilrdc::edit');
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

    public function getRectteCec($annee)
    {
        return   $listeRecettesParCec = SifecFacade::listeRecettesParCec($annee);
    }

    public function getRectteMois($annee)
    {
        return   $listeRecettesParMois = SifecFacade::listeRecettesParMois($annee);
    }

    //MISE A JOUR recetteAnnuelle avec gestion des périodes
    public function recetteAnnuelle()
    {
        $periode = request("periode");
        $codeFonction = request("codeFonction");



        // total de recette annuelle
        $sumtotal = SifecFacade::recetteAnnuelle($periode,$codeFonction);
        $topRecettes = SifecFacade::topRecettes($periode, $codeFonction);

        // return response()->json([
        //     "tabTopRecettes" => $topRecettes
        // ]);
        // return $topRecettes;


        $listeRecettesParMois = SifecFacade::listeRecettesParMois($periode, $codeFonction);
        $listeRecettesParCec = SifecFacade::listeRecettesParCec($periode, $codeFonction);

        $mt= 0;
        $tabTopRecettes = [];
        $tablisteRecettesParMois = [];
        $tablisteRecettesParCec = [];


        if($codeFonction != "FONC_0023"){
            //top 3 de recette
            foreach ($topRecettes as $item) {
                $tabTopRecettes[] = array(
                'libInstitution' => $item->lib_institution,
                'Prix' => number_format($item->total,2,",","."),
                'auth' => number_format(0,2,",",".")
                );
            }
             //Liste des recettes par cec d'une annee
            foreach ($listeRecettesParCec as $item) {
                $tablisteRecettesParCec[] = array(
                'institution' => $item->lib_institution,
                'total' => $item->total
                );
            }
        }

        //total montant annuelle
        foreach($sumtotal as $item){
            $mt = $item->total;
        }
        //Liste des recettes par mois d'une annee
        foreach ($listeRecettesParMois as $item) {
            $tablisteRecettesParMois[] = array(
             'lemois' => $item->mois,
             'Tmontant' => $item->total
            );
         }


         if($codeFonction == "FONC_0023"){
            return  response()->json([

                "tabTopRecettes" => $topRecettes,
                "mt" => number_format($mt,2,",","."),
                "tablisteRecettesParMois"=>$tablisteRecettesParMois,
                "tablisteRecettesParCec"=>$listeRecettesParCec
            ]);
         }
         return  response()->json([

            "tabTopRecettes" => $tabTopRecettes,
            "mt" => number_format($mt,2,",","."),
            "tablisteRecettesParMois"=>$tablisteRecettesParMois,
            "tablisteRecettesParCec"=>$tablisteRecettesParCec
        ]);


    }


    //récupération de la vue par defaut :: dashboard des faits
    public function faitStatIndex()
    {
        $anneEncours = date("Y");
        $listeAnnees = DB::select('select DISTINCT(YEAR(created_at)) as annee from t_demande_document ORDER BY annee DESC');
        return view('etatcivilrdc::dashboard-rdc.faitStat', compact("listeAnnees","anneEncours"));
    }

    //METHODE POUR LA GESTION DS STATISTIQUES RELATIVES AUX FAITS ::: CAS DES NAISSANCES
    public function faitsStat(Request $request)
    {
        $mt= 0;
        $tabTopRecettes = [];
        $tablisteRecettesParMois = [];
        $tablisteRecettesParCec = [];
        $tabNaissancesParMois = [];
        $tabCommunesGouverneur = [];

        //return("ok controlleur");
        $sumtotal = SifecFacade::effectifNaissances($request->periode,$request->codeFonction);

        // return $sumtotal;

        $topRecettes = SifecFacade::topRecettes($request->periode, $request->codeFonction);
        //$sumtotal = SifecFacade::recetteAnnuelle($request->periode,$request->codeFonction);
        $naissanceParMois = SifecFacade::effectifNaissancesParMois($request->periode, $request->codeFonction);
        // dd($naissanceParMois);

        $listeRecettesParMois = SifecFacade::naissanceParCec($request->periode, $request->codeFonction);
        $listeRecettesParCec = SifecFacade::naissanceParSexe($request->periode, $request->codeFonction);
        //Récupération de la liste des communes d'une province :: cas du gouverneur
        $listeCommunes = [];
        //Cas gouverneur
        if($request->codeFonction == "FONC_0022"){
            $code_loc_gouv =  Auth::user()->affectationActive()->institution->lieu->localiteParent->code_localite;
            $listeCommunes = DB::select('select lib_localite from tr_localite where code_localite_parent = ?', [$code_loc_gouv]);
        }
        //Cas ministre
        if($request->codeFonction == "FONC_0023"){
            $listeCommunes = DB::select('select lib_localite from tr_localite where (code_type_localite = "TPLOC_0001" AND created_at > "2024-09-01") or (lib_localite = "KINSHASA" AND code_type_localite="TPLOC_0001")');
        }


        // return $listeRecettesParMois;

        //top 3 de recette
        foreach ($naissanceParMois as $item) {
            $tabNaissancesParMois[] = array(
            'mois' => $item->mois,
            'effectif' => $item->total,
            //'auth' => number_format(0,2,",",".")
            );
        }

        //récupération liste des communes de la province
        foreach ($listeCommunes as $item) {
            $tabCommunesGouverneur[] = array(
            'localite' => $item->lib_localite
            );
        }

        //total montant annuelle
        foreach($sumtotal as $item){
            $mt = $item->total;
        }
        //naissances par cec :: commune
        //1. Récupération liste exhaustive des communes
        foreach ($listeRecettesParMois as $item) {
            $tablisteRecettesParMois[] = array(
            'lemois' => $item->lib_localite,
            'Tmontant' => $item->total
            );
        }

        //Liste des recettes par cec d'une annee
        foreach ($listeRecettesParCec as $item) {
            if($item->sexe == "F"){
                $sexe = "Féminin";
            }else{
                $sexe = "Masculin";
            }
            $tablisteRecettesParCec[] = array(
            'institution' => $sexe,
            'total' => $item->total
            );
        }
        //dd($listeCommunes);
        return  response()->json([
            "tabTopRecettes" => $tabTopRecettes,
            "tabNaissancesParMois" => $tabNaissancesParMois,
            "mt" => number_format($mt,2,",","."),
            "tablisteRecettesParMois"=>$tablisteRecettesParMois,
            "tablisteRecettesParCec"=>$tablisteRecettesParCec,
            "tabCommunesGouverneur"=>$tabCommunesGouverneur
        ]);

    }

    public function statCarte()
    {
        $codeFonction = request("codefonction");
        $codeProvince = request("codeProvince");


        // if($codeProvince == "HAUT-KATANGA"){
        //     return $codeProvince;
        // }

        $stats = SifecFacade::statCarte($codeFonction,$codeProvince);

        // $tabRecettes = [];

        // //recuperation des stats carte
        // foreach ($stats as $item) {
        //     $tabRecettes[] = array(
        //      'libInstitution' => $item->lib_institution,
        //      'Prix' => "$ ".number_format($item->total,2,",","."),
        //      'auth' => number_format(0,2,",",".")
        //     );
        // }

        return  response()->json([
            // "tabRecettes" => $tabRecettes
            "tabRecettes" => $stats
        ]);
    }


    public function getlocaCommuneProvince()
    {
        $libProvince = request("id");
        $idProvince = Localite::where("lib_localite",$libProvince)->first();

        $communes = Localite::where("code_localite_parent",$idProvince->code_localite)->get();
        return $communes;
    }

    public function getArrondissementCommune()
    {
        //date par defaut
        $anneeEncours = date("Y");
        $idCommune = request("id");
        $arrondissements = Localite::where("code_localite_parent",$idCommune)->get();

        //recuperation du codeLocalite de l'institution mere
        $idInstitutionParent = Institution::where("code_localite",$idCommune)->first()->code_institution;

        $tablisteRecettesParArrondissement = [];


        //recuperation des recettes des mairies/communes de chaque arrondissement
        $recettes = DB::select('select SUM(prix) as total,i.lib_institution,i.code_localite from t_demande_document dd, tr_ins_user iu, tr_institution i where dd.cui = iu.cui AND iu.code_institution = i.code_institution AND i.code_institution_parent = ? AND YEAR(dd.created_at) = ? GROUP BY i.lib_institution,i.code_localite',[$idInstitutionParent,$anneeEncours]);

          //Liste des recettes par cec d'une annee
          foreach ($recettes as $item) {
            $tablisteRecettesParArrondissement[] = array(
             'institution' => $item->lib_institution,
             'codeLocalite' => $item->code_localite,
             'total' => $item->total
            );
         }
          return response()->json([
                "arrondissements"=> $arrondissements,
                "tablisteRecettesParArrondissement" => $tablisteRecettesParArrondissement

          ]);
    }
}
