<?php

namespace Modules\Deces\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Sifec\Sifec;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Modules\Deces\Entities\ActeDeces;
use Modules\Deces\Entities\DDecesCause;
use Modules\Referentiel\Entities\Regime;
use Illuminate\Support\Facades\Validator;
use Modules\Deces\Entities\MouvementDeces;
use Modules\Deces\Entities\PersonneSitMat;
use Modules\Referentiel\Entities\Localite;
use Modules\Referentiel\Entities\Personne;
use Modules\Referentiel\Entities\Religion;
use Modules\Referentiel\Entities\Filiation;
use Illuminate\Contracts\Support\Renderable;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Referentiel\Entities\CauseDeces;
use Modules\Referentiel\Entities\Profession;
use Modules\Referentiel\Entities\Departement;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\Nationalite;
use Modules\Referentiel\Entities\TypeDocument;
use Modules\Referentiel\Entities\Arrondissement;
use Modules\Referentiel\Entities\LieuSurvenance;
use Modules\Referentiel\Entities\SituationMatrimoniale;

class DecesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
         $instructions = Sifec::niveauInstructions();
         $declarations = Auth::user()->institution()->declarationsDeces();

        return view('deces::declaration.index',compact('declarations','instructions'));
    }


    public function displayCertifatNonInscription($id)
    {
        $acte = DeclarationDeces::where("code_declaration_deces",$id)->first();
        if($acte == null){
            toastr()->error("Vous ne pouvez pas généré un certificat de non incription de décès");
            return back();
        }

        DB::beginTransaction();

       try {
        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('deces::etats.certificats.certificat_non_inscription', compact("acte"))->render());
        DB::commit();

        return $html2pdf->output($acte->code_declaration_deces.".pdf");

       } catch (Exception $e) {
            DB::rollBack();
            toastr()->error($e->getMessage());
            return back();
       }
    }

    public function certificatConstatation()
    {
        $constatationdeces = DeclarationDeces::all();
        return view('deces::certificat-constatation-deces.index',compact('constatationdeces'));
    }

    public function etat($id)
    {
        $ddc = DeclarationDeces::where("code_declaration_deces",$id)->first();

        $dat1 = Carbon::create($ddc->created_at);
        $dateDeces = Carbon::create($ddc->date_heure_deces);
        $diffJour = $dateDeces->diffInDays($dat1);



        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');

        $html2pdf->writeHTML(view('deces::etats.declaration', compact("ddc","diffJour"))->render());
        return $html2pdf->output($ddc->code_declaration_deces.".pdf");

    }

    public function create()
    {
        $title = "Créer une déclaration de decès";

        $type_declaration = "DECLARATION DE DECES";
        $cecMariage = Institution::where("code_type_institution","TPINS_0002")->get();
        $instructions = Sifec::niveauInstructions();
        // $localites = Localite::where('code_type_localite','TPLOC_0002')->Orwhere('code_type_localite','TPLOC_0003')->get();
        $localites = Localite::where('code_type_localite','TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $religions = Religion::all();
        $lieusurvenances = LieuSurvenance::all();
        $filiations= Filiation::all();
        $regimes= Regime::all();
        $causesDeces= CauseDeces::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $typedocuments = TypeDocument::all();
        $arrondissement = Localite::where('code_type_localite','TPLOC_0004')->Orwhere('code_type_localite','TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite','TPLOC_0007')->Orwhere('code_type_localite','TPLOC_0008')->get();        $countries = collect( json_decode(file_get_contents(public_path("codes_pays.json"))));
        $departements = Departement::all();

        return view('deces::declaration.create',compact("title","type_declaration","quartierVillages","cecMariage","departements", "countries","arrondissement","instructions","typedocuments","causesDeces","regimes","localites","professions","nationalites","situationMatrimoniales","religions","lieusurvenances","filiations"));


    }
    //creer une autorisation de transfert de dépouille
    public function createTransfertDepouille()
    {
        $title = "Créer une autorisation de transfert de dépouille";

        $type_declaration = "AUTORISATION DE TRANSFERT DE DEPOUILLE";
        $cecMariage = Institution::where("code_type_institution","TPINS_0002")->get();
        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite','TPLOC_0002')->Orwhere('code_type_localite','TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $religions = Religion::all();
        $lieusurvenances = LieuSurvenance::all();
        $filiations= Filiation::all();
        $regimes= Regime::all();
        $causesDeces= CauseDeces::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $typedocuments = TypeDocument::all();
        $arrondissement = Localite::where('code_type_localite','TPLOC_0004')->Orwhere('code_type_localite','TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite','TPLOC_0007')->Orwhere('code_type_localite','TPLOC_0008')->get();        $countries = collect( json_decode(file_get_contents(public_path("codes_pays.json"))));
        $departements = Departement::all();

        return view('deces::declaration.create',compact("title","type_declaration","quartierVillages","cecMariage","departements", "countries","arrondissement","instructions","typedocuments","causesDeces","regimes","localites","professions","nationalites","situationMatrimoniales","religions","lieusurvenances","filiations"));
    }

    public function certificatNonIscription()
    {
        $title = "Créer un certificat de non inscription de décès";

        $type_declaration = "CERTIFICAT DE NON INSCRIPTION DE DECES";
        $cecMariage = Institution::where("code_type_institution","TPINS_0002")->get();
        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite','TPLOC_0002')->Orwhere('code_type_localite','TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $religions = Religion::all();
        $lieusurvenances = LieuSurvenance::all();
        $filiations= Filiation::all();
        $regimes= Regime::all();
        $causesDeces= CauseDeces::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $typedocuments = TypeDocument::all();
        $arrondissement = Localite::where('code_type_localite','TPLOC_0004')->Orwhere('code_type_localite','TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite','TPLOC_0007')->Orwhere('code_type_localite','TPLOC_0008')->get();        $countries = collect( json_decode(file_get_contents(public_path("codes_pays.json"))));
        $departements = Departement::all();

        return view('deces::declaration.create',compact("title","type_declaration","quartierVillages","cecMariage","departements", "countries","arrondissement","instructions","typedocuments","causesDeces","regimes","localites","professions","nationalites","situationMatrimoniales","religions","lieusurvenances","filiations"));
    }

    public function declarationTardive()
    {
        $title = "Créer une déclaration tardive de décès";
        $type_declaration = "DECLARATION TARDIVE";
        $datedeces = request("date_deces");

        $cecMariage = Institution::where("code_type_institution","TPINS_0002")->get();
        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite','TPLOC_0002')->Orwhere('code_type_localite','TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $religions = Religion::all();
        $lieusurvenances = LieuSurvenance::all();
        $filiations= Filiation::all();
        $regimes= Regime::all();
        $causesDeces= CauseDeces::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $typedocuments = TypeDocument::all();
        $arrondissement = Localite::where('code_type_localite','TPLOC_0004')->Orwhere('code_type_localite','TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite','TPLOC_0007')->Orwhere('code_type_localite','TPLOC_0008')->get();        $countries = collect( json_decode(file_get_contents(public_path("codes_pays.json"))));
        $departements = Departement::all();

        return view('deces::declaration.create',compact("type_declaration","cecMariage","title","datedeces","departements","arrondissement","quartierVillages","countries","instructions","typedocuments","causesDeces","regimes","localites","professions","nationalites","situationMatrimoniales","religions","lieusurvenances","filiations"));


    }

    public function store(Request $request)
    {
        // Log::channel("sifec")->info($request->all());
        // dd("ok");

        $dateNaissancePere = Carbon::create($request->date_naissance_pere);
        $dateNaissanceEnfant = Carbon::create($request->date_naissance_defunt);
        $dateNaissanceMere = Carbon::create($request->date_naissance_mere);
        $differenceAgeEnfantPere = $dateNaissancePere->diffInYears($dateNaissanceEnfant);
        $differenceAgeEnfantMere = $dateNaissanceMere->diffInYears($dateNaissanceEnfant);

        if($differenceAgeEnfantPere < 14){
            return response()->json([

                "message"=>"La différence d'age entre père et enfant doit être supérieure ou égale à 14 ans"
            ]);
        }

        if($differenceAgeEnfantMere < 12){
            return response()->json([

                "message"=>"La différence d'age entre mère et enfant doit être supérieure ou égale à 12 ans"
            ]);
        }

        DB::beginTransaction();
        try{

                //Traitements d'enregistrement du defunt
                $defuntUniqueString = Sifec::uniqueString($request,"_defunt",$request->sexe_defunt);
                $defunt = Personne::where("personne_string",$defuntUniqueString)->first();
                if($defunt==null)
                {
                  $defunt = sifec::savePersonne($request,"_defunt",$request->sexe_defunt,$defuntUniqueString);
                }
                else
                {
                      $defunt->statut_personne = "DECEDE";
                      $defunt->save();
                }
                //Traitement d'enregistrement du pere
                $pereUniqueString = Sifec::uniqueString($request,"_pere","M");
                $pere = Personne::where("personne_string",$pereUniqueString)->first();
                if($pere==null)
                 {
                   $pere = sifec::savePersonne($request,"_pere","M",$pereUniqueString);
                 }



                //Traitement d'enregistrement de la mere
                $mereUniqueString = Sifec::uniqueString($request,"_mere","F");
                $mere = Personne::where("personne_string",$mereUniqueString)->first();
                if($mere==null)
                {
                 $mere = sifec::savePersonne($request,"_mere","F",$mereUniqueString);
                }

                //Traitementt du conjoint
                $codeconjoint="";

                $conjointUniqueString = Sifec::uniqueString($request,"_conjoint",$request->sexe_conjoint);
                $conjoint = Personne::where("personne_string",$conjointUniqueString)->first();

                if(($conjoint==null))
                {
                  if($request->nom_conjoint!=null)
                  {
                    $conjoint = sifec::savePersonne($request,"_conjoint",$request->sexe_conjoint,$conjointUniqueString);
                    $codeconjoint=$conjoint->code_personne;
                  }
                }
                else
                {
                    $codeconjoint=$conjoint->code_personne;
                }

                //Traitement du declarant
                $declarantUniqueString = Sifec::uniqueString($request,"_declarant",$request->sexe_declarant);
                $declarant = Personne::where("personne_string",$declarantUniqueString)->first();
                 if($declarant==null)
                  {
                    $declarant = sifec::savePersonne($request,"_declarant",$request->sexe_declarant,$declarantUniqueString);
                  }


                // déclaration de décès
                $ddeces = new DeclarationDeces;
                $codeddeces = Sifec::genererCodeUniqueReferentiel($ddeces,"code_declaration_deces",8,"CDD_");

                $ddeces->code_declaration_deces = $codeddeces;
                $ddeces->date_heure_declaration=now();
                $ddeces->date_heure_deces = $request->date_deces." ".$request->heure_deces.":00" ;
                $ddeces->code_lieu_survenance = $request->lieu_survenance_code;
                $ddeces->date_mariage = $request->date_mariage;
                $ddeces->code_regime  = $request->code_regime;
                $ddeces->domicile_defunt = $request->domicile_defunt;
                $ddeces->cec_mariage= $request->cec_mariage;
                $ddeces->cec_naissance= $request->cec_naissance;
                $ddeces->lieu_deces= $request->lieu_deces;
                $ddeces->num_acte_mariage=$request->num_acte_mariage;
                $ddeces->num_acte_naissance=$request->num_acte_naissance;
                $ddeces->type_declarant = "Personne physique";
                $ddeces->type_declaration = $request->type_declaration;
                $ddeces->code_religion =$request->code_religion_defunt;
                $ddeces->code_pere = $pere->code_personne;
                $ddeces->code_mere = $mere->code_personne;
                $ddeces->code_user_institution  = Auth::user()->affectationActive()->cui;

                if ($codeconjoint != "") {
                    $ddeces->code_conjoint = $codeconjoint;
                }
                $ddeces->code_filiation = $request->filiation;
                $ddeces->code_declarant = $declarant->code_personne;
                $ddeces->code_defunt = $defunt->code_personne;
                $ddeces->code_situation_matrimoniale = $request->code_situation_matrimoniale_defunt;

                $deces_existant = DeclarationDeces::where("code_defunt",$defunt->code_personne)->first();

                if($deces_existant==null)
                {
                    $ddeces->save();

                    $causes = $request->code_cause_deces;
                    if($causes !=null){
                        foreach($causes as $cause){
                            DDecesCause::create([
                                'code_declaration_deces' => $ddeces->code_declaration_deces,
                                'code_cause_deces' => $cause
                            ]);
                        }

                    }

                    $transaction = new MouvementDeces;
                    $transaction->code_mouvement_deces = Sifec::genererCodeUniqueReferentiel($transaction,"code_mouvement_deces",4,"MDD_");
                    $transaction->statut = "En cours";
                    $transaction->code_declaration_deces = $ddeces->code_declaration_deces;
                    $transaction->cui = Auth::user()->affectationActive()->cui;
                    $transaction->save();

                }else{
                    return response()->json([
                        'success' => false,
                        'message' => 'Document déjà enregistré',
                    ]);
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Document enregistré avec succès',
                ]);

        }catch(Exception $e){
                DB::rollBack();
                Log::channel("sifec")->info($e->getMessage());
                return response()->json([
                    "message"=>$e->getMessage()
                ]);
        }

    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('deces::declaration.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $dd = DeclarationDeces::find($id);
        $cecMariage = Institution::where("code_type_institution","TPINS_0002")->get();
        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite','TPLOC_0002')->Orwhere('code_type_localite','TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $religions = Religion::all();
        $lieusurvenances = LieuSurvenance::all();
        $filiations= Filiation::all();
        $regimes= Regime::all();
        $causesDeces= CauseDeces::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $typedocuments = TypeDocument::all();
        $arrondissement = Localite::where('code_type_localite','TPLOC_0004')->Orwhere('code_type_localite','TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite','TPLOC_0007')->Orwhere('code_type_localite','TPLOC_0008')->get();        $countries = collect( json_decode(file_get_contents(public_path("codes_pays.json"))));
        $departements = Departement::all();

        // return view('deces::declaration.edit',compact("departements", "causesDeces","regimes","typedocuments","arrondissement","countries","localites","instructions","professions","nationalites","situationMatrimoniales","religions","lieusurvenances","filiations","declaration"));


        return view('deces::declaration.edit',compact("dd","quartierVillages","cecMariage","departements", "countries","arrondissement","instructions","typedocuments","causesDeces","regimes","localites","professions","nationalites","situationMatrimoniales","religions","lieusurvenances","filiations"));

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        // return response()->json([
        //     "code"=>"200",
        //     "message"=>"Déclaration modifié avec succès",
        //     "data"=>$request->all()
        // ]);

        $ddc = DeclarationDeces::find($id);
        // Log::channel("sifec")->info($request->all());



       if($ddc==null){
        toastr()->error("Impossible de charger cette page");
        return back();
       }

        DB::beginTransaction();

        try{

           $pere = Sifec::updatePersonne($request,"_pere","M",$ddc->code_pere);

           $mere = Sifec::updatePersonne($request,"_mere","F",$ddc->code_mere);

           $declarant = Sifec::updatePersonne($request,"_declarant",$request->sexe_declarant,$ddc->code_declarant);

           $defunt = Sifec::updatePersonne($request,"_defunt",$request->sexe_defunt, $ddc->code_defunt);


        //    $code_ins = DB::table("tr_user")
        //    ->join('tr_ins_user','tr_user.code_user',"=",'tr_ins_user.code_user')
        //    ->select("tr_ins_user.cui")
        //    ->where('tr_user.code_user', auth()->user()->code_user)
        //    ->first();

            // $ddc->date_heure_declaration=now();
            $ddc->date_heure_deces = $request->date_deces." ".$request->heure_deces ;
            $ddc->code_lieu_survenance = $request->lieu_survenance_code;
            $ddc->date_mariage = $request->date_mariage;
            $ddc->code_regime  = $request->code_regime;
            $ddc->domicile_defunt = $request->domicile_defunt;
            $ddc->cec_mariage= $request->cec_mariage;
            $ddc->cec_naissance= $request->cec_naissance;
            $ddc->lieu_deces= $request->lieu_deces;
            $ddc->num_acte_mariage=$request->num_acte_mariage;
            $ddc->num_acte_naissance=$request->num_acte_naissance;
            $ddc->type_declarant = "Personne physique";
            $ddc->type_declaration = "DECLARATION DE DECES";
            $ddc->code_religion =$request->code_religion_defunt;
            $ddc->code_pere = $pere->code_personne;
            $ddc->code_mere = $mere->code_personne;
            // $ddc->code_user_institution  = Auth::user()->affectationActive()->cui;

            // if ($codeconjoint != "") {
            //     $ddc->code_conjoint = $codeconjoint;
            // }
            $ddc->code_filiation = $request->filiation;
            $ddc->code_declarant = $declarant->code_personne;
            $ddc->code_defunt = $defunt->code_personne;
            $ddc->code_situation_matrimoniale = $request->code_situation_matrimoniale_defunt;
            $ddc->save();

            DB::commit();

            return response()->json([
                "code"=>"200",
                "message"=>"Déclaration modifié avec succès",
                "data"=>$request->all()
            ]);


            }catch(Exception $e){
                    DB::rollBack();
                    return response()->json([
                        "code"=>"200",
                        "message"=>$e->getMessage()
                    ]);
            }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        $declaradeces = DeclarationDeces::find($request->code_declaration);
        $declaradeces->delete();

        return response()->json([
            'success' => true,
            'message' => 'Déclaration suprimée avec succès !',
        ]);
    }

    public function verification()
    {
        $certificats = DeclarationDeces::where('type_declaration','DECLARATION TARDIVE')->get();
        return view('deces::declaration.verification', compact('certificats'));
    }

    public function statParCause()
    {

        $declarations = Auth::user()->institution()->declarationsDeces();

        $liste = [];
        foreach ($declarations as $key) {
            $liste[] = $key->code_declaration_deces;
        }
        $array = implode("','",$liste); // liste des codes déclarati


        $datas = DB::select("SELECT tr_arrondissement.lib_arrondissement AS arrondissement, tr_cause_deces.lib_cause_deces, COUNT(tr_cause_deces.lib_cause_deces) AS TOTAL
        FROM t_declaration_deces
        JOIN tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_deces.code_defunt
        JOIN t_ddecescause ON t_ddecescause.code_declaration_deces = t_declaration_deces.code_declaration_deces
        JOIN tr_cause_deces ON tr_cause_deces.code_cause_deces = t_ddecescause.code_cause_deces

        JOIN t_adresse_personne ON t_adresse_personne.code_personne = tr_identification_personne.code_personne
        JOIN tr_arrondissement ON tr_arrondissement.code_arrondissement= t_adresse_personne.code_arrondissement

        WHERE  MONTH(t_declaration_deces.date_heure_deces) = MONTH(CURDATE())
        AND t_declaration_deces.code_declaration_deces IN ('".$array."')
        GROUP BY tr_cause_deces.lib_cause_deces,tr_arrondissement.lib_arrondissement");


        //ORDER BY tr_arrondissement.lib_arrondissement
        ///  dd($datas);
        return view('deces::statistiques.declarationCausesDeces', compact('datas'));
    }

    public function statParCauseEtat(){
        $declarations = Auth::user()->institution()->declarationsDeces();

        $liste = [];
        foreach ($declarations as $key) {
            $liste[] = $key->code_declaration_deces;
        }
        $array = implode("','",$liste);

        $datas = DB::select("SELECT tr_arrondissement.lib_arrondissement AS arrondissement, tr_cause_deces.lib_cause_deces, COUNT(tr_cause_deces.lib_cause_deces) AS TOTAL
        FROM t_declaration_deces
        JOIN tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_deces.code_defunt
        JOIN t_ddecescause ON t_ddecescause.code_declaration_deces = t_declaration_deces.code_declaration_deces
        JOIN tr_cause_deces ON tr_cause_deces.code_cause_deces = t_ddecescause.code_cause_deces

        JOIN t_adresse_personne ON t_adresse_personne.code_personne = tr_identification_personne.code_personne
        JOIN tr_arrondissement ON tr_arrondissement.code_arrondissement= t_adresse_personne.code_arrondissement

        WHERE  MONTH(t_declaration_deces.date_heure_deces) = MONTH(CURDATE())
        AND t_declaration_deces.code_declaration_deces IN ('".$array."')
        GROUP BY tr_cause_deces.lib_cause_deces,tr_arrondissement.lib_arrondissement");

        view()->share("tester", "Vincent");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('deces::etats.statistiques.declarationCauseEtat', compact("datas"))->render());

        return $html2pdf->output("statParCauses.pdf");
    }

    public function statParTrancheAge()
    {
        $mesdeclarations = Auth::user()->institution()->declarationsDeces();
        $liste = [];
        foreach ($mesdeclarations as $key) {
            $liste[] = $key->code_declaration_deces;
        }
        $tab = implode("','",$liste);

        $donnees = DB::select("SELECT tr_identification_personne.date_naissance FROM t_declaration_deces
        JOIN tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_deces.code_defunt
        WHERE MONTH(t_declaration_deces.date_heure_deces) = MONTH(CURDATE())
        -- AND t_declaration_deces.code_declaration_deces IN ('".$tab."')
        ");
        $moin18 = [];
        $dix829 = [];
        $trent65 = [];
        $pruss65 = [];
        $age = 0;

        foreach ($donnees as $key) {
            $diff = date_diff(date_create(date('Y-m-d', strtotime($key->date_naissance))),date_create(date('Y-m-d')));
            $age = (int)$diff->y;
            if ($age < 18) {
                $moin18[] = $age;
            }
            if ($age >= 18 && $age < 30) {
                $dix829[] = $age;
            }
            if ($age >= 30 && $age < 66) {
                $trent65[] = $age;
            }
            if ($age >= 66) {
                $pruss65[] = $age;
            }
        }

        $moinsde18 = count($moin18);
        $de18a29 = count($dix829);
        $de30a65 = count($trent65);
        $plusde65 = count($pruss65);

        $total = $moinsde18 + $de18a29 + $de30a65 + $plusde65;

        return view('deces::statistiques.tranchesage',compact('moinsde18','de18a29','de30a65','plusde65','total'));
    }
    public function statParTrancheAgeEtat()
    {
        $mesdeclarations = Auth::user()->institution()->declarationsDeces();
        $liste = [];
        foreach ($mesdeclarations as $key) {
            $liste[] = $key->code_declaration_deces;
        }
        $tab = implode("','",$liste);

        $donnees = DB::select("SELECT tr_identification_personne.date_naissance FROM t_declaration_deces
        JOIN tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_deces.code_defunt
        WHERE MONTH(t_declaration_deces.date_heure_deces) = MONTH(CURDATE())
        -- AND t_declaration_deces.code_declaration_deces IN ('".$tab."')
        ");
        $moin18 = [];
        $dix829 = [];
        $trent65 = [];
        $pruss65 = [];
        $age = 0;

        foreach ($donnees as $key) {
            $diff = date_diff(date_create(date('Y-m-d', strtotime($key->date_naissance))),date_create(date('Y-m-d')));
            $age = (int)$diff->y;
            if ($age < 18) {
                $moin18[] = $age;
            }
            if ($age >= 18 && $age < 30) {
                $dix829[] = $age;
            }
            if ($age >= 30 && $age < 66) {
                $trent65[] = $age;
            }
            if ($age >= 66) {
                $pruss65[] = $age;
            }
        }

        $moinsde18 = count($moin18);
        $de18a29 = count($dix829);
        $de30a65 = count($trent65);
        $plusde65 = count($pruss65);

        $tout = (int)$moinsde18 + (int)$de18a29 + (int)$de30a65 + (int)$plusde65;

        view()->share("tester", "Vincent");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('deces::etats.statistiques.trancheAgeEtat', compact('moinsde18','de18a29','de30a65','plusde65','tout'))->render());

        return $html2pdf->output("statParAge.pdf");
    }

    public function mouvement(Request $request)
    {

        $rules = [
            "code_declaration_deces"=>["required"]
        ];
        $validator = Validator::make($request->all(),$rules);
        $dd = DeclarationDeces::find($request->code_declaration_deces);

        if($validator->fails()){
            return response()->json([
                "code"=>"180",
                "message"=>"Aucune déclaration trouvée pour ce code"
            ]);
        }

        DB::beginTransaction();
        try{

            $lastMouvement = $dd->mouvements->last();

            $transaction = new MouvementDeces;
            $transaction->code_mouvement_deces = Sifec::genererCodeUniqueReferentiel($transaction,"code_mouvement_deces",4,"MDD_");

            if($lastMouvement->statut == "En cours"){
                $transaction->statut = "Envoyée";
                $dd->approuver = "OUI";
            }
            if($lastMouvement->statut == "Envoyée"){
                $transaction->statut = "Renvoyée";
                $dd->approuver = "NON";
            }
            if($lastMouvement->statut == "Renvoyée"){
                $transaction->statut = "Envoyée";
                $dd->approuver = "OUI";
            }

            $transaction->code_declaration_deces = $dd->code_declaration_deces;
            $transaction->cui = Auth::user()->affectationActive()->cui;
            $transaction->motif_renvoi = $request->motif_renvoi;
            $transaction->observation = trim($request->observation);
            $transaction->save();

            $dd->save();

            DB::commit();
            return response()->json([
                "code"=>"200",
                "message"=>"Cette déclaration a été $transaction->statut avec succès"
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "code"=>"183",
                "message"=>["error"=>$e->getMessage()]
            ]);
        }
    }

    public function mouvementEdit(Request $request, $id)
    {
        $mvtd = MouvementDeces::find($id);

        if($mvtd =="" || $mvtd == null){
            return response()->json([
                "code"=>"183",
                "message"=>["Aucune donnée trouvée"]
            ]);
        }

        DB::beginTransaction();
        try {

            $mvtd->motif_renvoi = $request->motif_renvoi;
            $mvtd->observation = trim($request->observation);
            $mvtd->save();

             //update (lu et approuve) du déclarant
             $dd = DeclarationDeces::find($mvtd->code_declaration_deces);
             $dd->approuver = "NON";
             $dd->save();

             DB::commit();

            return response()->json([
                "code"=>"200",
                "message"=>"Modification effectuée avec succès"
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "code"=>"183",
                "message"=>["error"=>$e->getMessage()]
            ]);
        }

    }

    public function mouvementDelete($id)
    {
        $mvtd = MouvementDeces::find($id);


        if($mvtd =="" || $mvtd == null){
            return response()->json([
                "code"=>"183",
                "message"=>["Aucune donnée trouvée"]
            ]);
        }

        try {
            $mvtd->delete();
            return response()->json([
                "code"=>"200",
                "message"=>"Opération effectuée avec succès"
            ]);

        }  catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "code"=>"183",
                "message"=>["error"=>$e->getMessage()]
            ]);
        }
    }

    public function rechercheDefunt(Request $request)
    {
        $personne = Sifec::rechercherPersonne($request->numero_acte_naissance);

        if($personne ==null){
            return response()->json([
                "code"=>"99",
                "message"=>"Aucun numéro d'acte trouvé"
            ]);
        }
        // $lieuNaiss = Localite::where("lib_localite",$personne->declaration->enfant->lieu_naissance)->where("code_type_localite","TPLOC_0002")->orWhere("code_type_localite","TPLOC_0003")->first();
        $lieuNaiss = Localite::where("lib_localite",$personne->declaration->enfant->lieu_naissance)->first();


        return response()->json([
            "code"=>"200",
            "nom"=>$personne->declaration->enfant->nom,
            "prenom"=>$personne->declaration->enfant->prenom,
            "sexe"=>$personne->declaration->enfant->sexe,
            "date_naissance"=>$personne->declaration->enfant->date_naissance,
            "niveau_instruction"=>$personne->declaration->enfant->niveau_instruction,
            // "lieu_naissance"=>'LOC_0026',
            "lieu_naissance"=> $personne->declaration->enfant->code_localite,
            // "dateEmisAN"=>$date_emis_acte_nais,
            "dateEmisAN"=>$personne->declaration->enfant->date_naissance,
            "cec_naissance"=>$personne->institutionUser->institution->lib_institution,
            "code_nationalite"=>$personne->declaration->enfant->nationalite->code_nationalite,
            "code_profession"=>$personne->declaration->enfant->profession->code_profession,
            "pere"=>$personne->declaration->pere->nomcomplet(),
            "mere"=>$personne->declaration->mere->nomcomplet()
        ]);
    }

    public function autorisationtransfert(){
        $instructions = Sifec::niveauInstructions();
        $declarations = Auth::user()->institution()->declarationsDeces();
        return view('deces::declaration.autorisationtransfert',compact('declarations','instructions'));
    }

    public function autorisationtransfertetat($id){
        $ddc = DeclarationDeces::find($id);
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('deces::etats.transfertetatdepouille', compact('ddc'))->render());

        return $html2pdf->output("Autorisation.pdf");
    }

}
