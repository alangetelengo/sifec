<?php

namespace Modules\Naissance\Http\Controllers;

use App\Models\Jugement;
use App\Models\Requisition;
use Exception;
use Carbon\Carbon;
use App\Sifec\Sifec;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Modules\Referentiel\Entities\Document;
use Modules\Referentiel\Entities\Localite;
use Modules\Referentiel\Entities\Personne;
use Modules\Referentiel\Entities\Filiation;
use Illuminate\Contracts\Support\Renderable;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Referentiel\Entities\Profession;
use Modules\Referentiel\Entities\Departement;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\Nationalite;
use Modules\Referentiel\Entities\TypeDocument;
use Modules\Referentiel\Entities\Arrondissement;
use Modules\Referentiel\Entities\LieuSurvenance;
use Modules\Naissance\Entities\MouvementNaissance;
use Modules\Naissance\Entities\Declarationnaissance;
use Modules\Referentiel\Entities\SituationMatrimoniale;

class NaissanceController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $declarations = [];
        $title = "Liste des déclarations de naissance";
        $button = "Créer déclaration";


        // if($user->affectationActive()->fonction->code_fonction == "FONC_0022") //si c'est un agent de maternité
        // {
        //     $title = "Liste des fiches de maternité";
        //     $button = "Créer une fiche de maternité";
        //     // $declarations = Declarationnaissance::where("code_user_institution",$user->affectationActive()->cui)->where('statut_enfant','Vivant')->get();
        //     $declarations = Declarationnaissance::where("code_user_institution",$user->affectationActive()->cui)->get();
        // }else{
        //     $declarations = $user->institution()->declarationsNaissances();
        // }
        $declarations = $user->institution()->declarationsNaissances();

        return view('naissance::declaration.index',compact("declarations","title","button"));
    }

    public function etat($id)
    {

        $typeDCertifatD = "CERTIFICAT DE DESTRUCTION DE L'ACTE";
        $typeDPaternite = "DECLARATION DE PATERNITE";
        $typeDCertifatN = "CERTIFICAT DE NON INSCRIPTION";
        $typeDNais = "DECLARATION DE NAISSANCE";
        $typeJSup = "JUGEMENT SUPPLETIF";
        $typeJHomo = "JUGEMENT D'HOMOLOGATION";
        $typeFMNais = "FICHE DE MATERNITE";
        $typeFTA = "FICHE DE TRANSCRIPTION DE L'ACTE";


        $declarationsD = Declarationnaissance::where("code_declaration_naissance",$id)->where("type_declaration",$typeDCertifatD)->first();
        $declarationP = Declarationnaissance::where("code_declaration_naissance",$id)->where("type_declaration",$typeDPaternite)->first();
        $declarationsN = Declarationnaissance::where("code_declaration_naissance",$id)->where("type_declaration",$typeDCertifatN)->first();
        $declarations = Declarationnaissance::where("code_declaration_naissance",$id)->where("type_declaration",$typeDNais)->first();
        $jugementSuppletif = Declarationnaissance::where("code_declaration_naissance",$id)->where("type_declaration",$typeJSup)->first();
        $jugementHomologation = Declarationnaissance::where("code_declaration_naissance",$id)->where("type_declaration",$typeJHomo)->first();
        $ficheMat = Declarationnaissance::where("code_declaration_naissance",$id)->where("type_declaration",$typeFMNais)->first();
        $ficheTransActe = Declarationnaissance::where("code_declaration_naissance",$id)->where("type_declaration",$typeFTA)->first();

        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');

        $dummy = "XXXXXXXXXXXXXXXX";

        if($declarations != null){

            $html2pdf->writeHTML(view('naissance::etats.declaration', compact("dummy"),["dn"=>$declarations])->render());
           return $html2pdf->output($declarations->code_declaration_naissance.".pdf");
        }

        if($declarationsN != null){

            $dateNaissEnfant = Carbon::create($declarationsN->enfant->date_naissance);
            $dateNow = Carbon::create(date("Y-m-y"));
            $ageEnfant = $dateNow->diffInYears($dateNaissEnfant);

            $html2pdf->writeHTML(view('naissance::etats.certificat_non_inscription', compact("dummy","ageEnfant"),["certificat"=>$declarationsN])->render());
            return $html2pdf->output($declarationsN->code_declaration_naissance.".pdf");
        }
        if($jugementHomologation != null){

            $html2pdf->writeHTML(view('naissance::etats.declaration', compact("dummy"),["dn"=>$jugementHomologation])->render());
            return $html2pdf->output($jugementHomologation->code_declaration_naissance.".pdf");
        }
        if($jugementSuppletif != null){
            // dd($jugementSuppletif);
            $html2pdf->writeHTML(view('naissance::etats.declaration', compact("dummy"),["dn"=>$jugementSuppletif])->render());
            return $html2pdf->output($jugementSuppletif->code_declaration_naissance.".pdf");
        }
        if($declarationP != null){
            $html2pdf->writeHTML(view('naissance::etats.certificat_de_transcription', compact("dummy"),["certificat"=>$declarationP])->render());
            return $html2pdf->output($declarationP->code_declaration_naissance.".pdf");
        }
        if($declarationsD != null){
            $html2pdf->writeHTML(view('naissance::etats.certificat_destruction', compact("dummy"),["certificat"=>$declarationsD])->render());
            return $html2pdf->output($declarationsD->code_declaration_naissance.".pdf");
        }
        if($ficheMat != null){
            $html2pdf->writeHTML(view('naissance::etats.declaration', compact("dummy"),["dn"=>$ficheMat])->render());
            return $html2pdf->output($ficheMat->code_declaration_naissance.".pdf");
        }
        if($ficheTransActe != null){
            $html2pdf->writeHTML(view('naissance::etats.declaration', compact("dummy"),["dn"=>$ficheTransActe])->render());
            return $html2pdf->output($ficheTransActe->code_declaration_naissance.".pdf");
        }
    }

    // Recherche d'une personne
    public function recherchePersonne(Request $request)
    {
        if($request->ajax()){

        if($request->nom==null)
        {
            $nom="";
        }else{
            $nom=$request->nom;
        }

        if($request->prenom==null)
        {
            $prenom="";
        }else{
            $prenom=$request->prenom;
        }

        if($request->telephone==null)
        {
            $telephone="";
        }else{
            $telephone=$request->telephone;
        }


        if(empty($request->statut))
        {
            $personnes =   DB::select('SELECT *, c.telephone as phone FROM `tr_identification_personne` t
                                       LEFT JOIN t_document d  ON t.code_personne=d.code_personne
                                       LEFT JOIN t_adresse_personne a on t.code_personne=a.code_personne
                                       LEFT JOIN t_contact_personne c on t.code_personne=c.code_personne

                                       WHERE   t.sexe like "%'.$request->sexe.'%"
                                               and t.nom like "%'.$request->nom.'%"
                                               and t.prenom like "%'.$request->prenom.'%"
                                               and c.telephone like "%'.$telephone.'%"
           ');

        }else
        {
            $personnes =   DB::select('SELECT *, c.telephone as phone FROM `tr_identification_personne` t
                                        LEFT JOIN t_document d  ON t.code_personne=d.code_personne
                                        LEFT JOIN t_adresse_personne a on t.code_personne=a.code_personne
                                        LEFT JOIN t_contact_personne c on t.code_personne=c.code_personne
                                        WHERE   t.sexe like "%'.$request->sexe.'%"
                                                and t.nom like "%'.$nom.'%"
                                                and t.prenom like "%'.$prenom.'%"
                                                and t.statut_personne = "'.$request->statut.'"
                                                and c.telephone like "%'.$telephone.'%"
           ');
        }

        return response()->json([
               "personnes" => $personnes
            ]);

       }
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $user = Auth::user();
        $title = "Créer une déclaration de naissance";
        $type_declaration = "DECLARATION DE NAISSANCE";

        if($user->affectationActive()->fonction->code_fonction == "FONC_0022") //si c'est un agent de maternité
        {
            $title = "Créer une fiche de maternité";
            $type_declaration = "FICHE DE MATERNITE";
        }

        if($user->affectationActive()->fonction->code_fonction == "FONC_0014") //si c'est un agent de la mairie centrale
        {
            $title = "Créer une fiche de transcription de l'acte";
            $type_declaration = "FICHE DE TRANSCRIPTION DE L'ACTE";
        }

        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite','TPLOC_0002')->Orwhere('code_type_localite','TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $lieuSurvenances = LieuSurvenance::all();
        $filiations = Filiation::all();
        $typedocuments = TypeDocument::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $arrondissement = Localite::where('code_type_localite','TPLOC_0004')->Orwhere('code_type_localite','TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite','TPLOC_0007')->Orwhere('code_type_localite','TPLOC_0008')->get();
        $countries = collect(json_decode(file_get_contents(public_path("codes_pays.json"))));
        $departements = Departement::all();
        return view('naissance::declaration.create',compact("title","departements","countries","arrondissement","typedocuments","instructions","filiations","localites","professions","nationalites","situationMatrimoniales","lieuSurvenances","quartierVillages","type_declaration"));
    }

    public function affaireSociale()
    {

        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite','TPLOC_0002')->Orwhere('code_type_localite','TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $lieuSurvenances = LieuSurvenance::all();
        $filiations = Filiation::all();
        $typedocuments = TypeDocument::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $arrondissement = Localite::where('code_type_localite','TPLOC_0004')->Orwhere('code_type_localite','TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite','TPLOC_0007')->Orwhere('code_type_localite','TPLOC_0008')->get();
        $countries = collect(json_decode(file_get_contents(public_path("codes_pays.json"))));
        $departements = Departement::all();

        $dummy = "XXXXXXXXXXXXXXXX";

        return view('naissance::enfant-abandonne.create ',compact("dummy","departements","countries","arrondissement","quartierVillages","typedocuments","instructions","filiations","localites","professions","nationalites","situationMatrimoniales","lieuSurvenances"));

    }

    public function enfantTrouve()
    {

        $instructions = Sifec::niveauInstructions();
        $localites = Localite::all();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $lieuSurvenances = LieuSurvenance::all();
        $filiations = Filiation::all();
        $typedocuments = TypeDocument::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $arrondissement = Arrondissement::all();
        $countries = collect(json_decode(file_get_contents(public_path("codes_pays.json"))));
        $departements = Departement::all();

        $dummy = "XXXXXXXXXXXXXXXX";

        return view('naissance::enfant-trouver.create',compact("dummy","departements","countries","arrondissement","typedocuments","instructions","filiations","localites","professions","nationalites","situationMatrimoniales","lieuSurvenances"));

    }

    public function affaireSocialeStore(Request $request)
    {

        DB::beginTransaction();

        try{

            $enfantUniqueString = Sifec::uniqueString($request,"_enfant",$request->sexe_enfant);
            $pereUniqueString = Sifec::uniqueString($request,"_pere","M");
            $mereUniqueString = Sifec::uniqueString($request,"_mere","F");

            $ec = Personne::where("personne_string",$enfantUniqueString)->first();

            $declarantUniqueString = Sifec::uniqueString($request,"_declarant",$request->sexe_declarant);

            $ec = Personne::where("personne_string",$enfantUniqueString)->first();

            // Log::channel('sifec')->info(["objet"=>$request->all()]);
            // dd("ok");

            if($ec != null){
                return response()->json([
                    "code"=>"99",
                    "message"=>["enfant_exist"=>"La déclaration de cet enfant existe déjà dans le système"]
                ]);
            }

            $declarant = Personne::where("personne_string",$declarantUniqueString)->first();
            $pere = Personne::where("personne_string",$pereUniqueString)->first();
            $mere = Personne::where("personne_string",$mereUniqueString)->first();

            if($declarant == null)
            {
                $declarant = Sifec::savePersonne($request,"_declarant",$request->sexe_declarant,$declarantUniqueString);
            }

            if($pere == null){
                $pere = Sifec::savePersonne($request,"_pere","M",$pereUniqueString);
            }

            if($mere == null){
                $mere = Sifec::savePersonne($request,"_mere","F",$mereUniqueString);
            }

            $enfant = Sifec::savePersonne($request,"_enfant",$request->sexe_enfant,$enfantUniqueString);


            $dn = new Declarationnaissance;
            $codedn = Sifec::genererCodeUniqueReferentiel($dn,"code_declaration_naissance",8,"CDN_");
            $dn->code_declaration_naissance = $codedn;
            $dn->nombre_enfant = 0;
            $dn->date_heure_naissance = $request->date_naissance_enfant." ".$request->heure_naissance_enfant.":00" ;
            $dn->date_heure_declaration = date("Y-m-d H:i");

            $dn->type_declarant = "Personne morale";
            $dn->code_declarant = $declarant->code_personne;
            $dn->code_enfant = $enfant->code_personne;
            $dn->code_pere = $pere->code_personne;
            $dn->code_mere = $mere->code_personne;
            if(Auth::user()->affectationActive()->institution->typeInstitution->code_type_institution == "TPINS_0002")//cas de mairie ou Enfant trouvé
            {
                $dn->personne_declaree = "Enfant trouvé";
                $dn->code_lieu_survenance = "LSURV_0007";
            }else{
                $dn->personne_declaree = "Enfant abandonné";
                $dn->code_lieu_survenance = "LSURV_0001";
            }

            $dn->code_user_institution  = Auth::user()->affectationActive()->cui;
            $dn->code_institution = Auth::user()->affectationActive()->code_institution;
            $dn->type_declaration = $request->type_declaration;

            $dn->formation_sanitaire_naissance = $request->formation_sanitaire_naissance;
            $dn->save();

            $transaction = new MouvementNaissance();
            $transaction->code_mouvement_naissance = Sifec::genererCodeUniqueReferentiel($transaction,"code_mouvement_naissance",4,"MDN_");
            $transaction->statut = "En cours";
            $transaction->code_declaration_naissance = $dn->code_declaration_naissance;
            $transaction->cui = Auth::user()->affectationActive()->cui;
            $transaction->save();

            DB::commit();

            return response()->json([
                "code"=>"200",
                "message"=>"La déclaration enregistrée avec succès"
            ]);

        }catch(Exception $e){
                DB::rollBack();
                Log::channel("sifec")->error($e->getMessage());
                return response()->json([
                    "code"=>"99",
                    "message"=>["error" =>$e->getMessage()]
                ]);
        }
    }
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {


        $rules = [
            "nom_enfant"=>["required"],
            "date_naissance_enfant"=>["required","date"],
            "code_situation_matrimoniale"=>["required"],
            "sexe_enfant"=>["required"],
            "heure_naissance_enfant"=>["required","max:5","min:5"],
            "nombre_enfant"=>["required","numeric"]
        ];

        $dateNaissEnfant = Carbon::create($request->date_naissance_enfant);
        $date = Carbon::create(date("Y-m-y"));
        $ageEnfant = $date->diffInYears($dateNaissEnfant);

        // Log::channel("sifec")->info(["objet"=>$request->all()]);
        // dd("ok");

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json([
                "code"=>"150",
                "message"=>$validator->errors()
            ]);
        }
        // le pere doit avoir au moins 14 ans de plus que l'enfant
        // la mere doit avoir au moins 12 ans de plus que l'enfant

        $dateNaissancePere = Carbon::create($request->date_naissance_pere);
        $dateNaissanceEnfant = Carbon::create($request->date_naissance_enfant);
        $dateNaissanceMere = Carbon::create($request->date_naissance_mere);
        $differenceAgeEnfantPere = $dateNaissancePere->diffInYears($dateNaissanceEnfant);
        $differenceAgeEnfantMere = $dateNaissanceMere->diffInYears($dateNaissanceEnfant);


        if($differenceAgeEnfantPere < 14){
            return response()->json([
                "code"=>"99",
                "message"=>["age_pere"=>"La différence d'age entre père et enfant doit être supérieure ou égale à 14 ans"]
            ]);
        }

        if($differenceAgeEnfantMere < 12){
            return response()->json([
                "code"=>"99",
                "message"=>["age_mere"=>"La différence d'age entre mère et enfant doit être supérieure ou égale à 12 ans"]
            ]);
        }

        DB::beginTransaction();
        try{
            $pereUniqueString = Sifec::uniqueString($request,"_pere","M");
            $mereUniqueString = Sifec::uniqueString($request,"_mere","F");
            $enfantUniqueString = Sifec::uniqueString($request,"_enfant",$request->sexe_enfant);
            $ec = Personne::where("personne_string",$enfantUniqueString)->first();



            $declarantUniqueString = Sifec::uniqueString($request,"_declarant",$request->sexe_declarant);



            if($ec != null){
                return response()->json([
                    "code"=>"99",
                    "message"=>["enfant_exist"=>"La déclaration de cet enfant existe déjà dans le système"]
                ]);
            }

            $pere = Personne::where("personne_string",$pereUniqueString)->first();
            $mere = Personne::where("personne_string",$mereUniqueString)->first();
            $declarant = Personne::where("personne_string",$declarantUniqueString)->first();

            //insertion de pere,mere,declarant et enfant
            if($pere == null){
                $pere = Sifec::savePersonne($request,"_pere","M",$pereUniqueString);
                // // Log::channel("sifec")->info(["enfantUniqueString"=>$enfantUniqueString]);
            }


            if($mere == null){
                $mere = Sifec::savePersonne($request,"_mere","F",$mereUniqueString);
            }
            if($declarant == null)
            {

                if($request->filiation != "FIL_0001" && $request->filiation != "FIL_0002")
                {
                    $declarant = Sifec::savePersonne($request,"_declarant",$request->sexe_declarant,$declarantUniqueString);
                }
                if($declarantUniqueString != $enfantUniqueString)
                {
                    $enfant = Sifec::savePersonne($request,"_enfant",$request->sexe_enfant,$enfantUniqueString);
                }
                else
                {
                    $enfant = $declarant;
                }
            }

            $dn = new Declarationnaissance;
            $codedn = Sifec::genererCodeUniqueReferentiel($dn,"code_declaration_naissance",8,"CDN_");
            $dn->code_declaration_naissance = $codedn;
            $dn->nombre_enfant = $request->nombre_enfant;
            if ($request->date_heure_declaration != '') {
                $dn->date_heure_declaration = $request->date_heure_declaration.' 00:00';
            }else{
                $dn->date_heure_declaration = date("Y-m-d H:i");
            }
            $dn->date_heure_naissance = $request->date_naissance_enfant." ".$request->heure_naissance_enfant.":00" ;
            $dn->type_declarant = "Personne physique";
            if($request->filiation == "FIL_0001"){
                $dn->code_declarant = $pere->code_personne;
            }
            if($request->filiation == "FIL_0002"){
                $dn->code_declarant = $mere->code_personne;
            }
            if($request->filiation != "FIL_0001" && $request->filiation != "FIL_0002"){
                $dn->code_declarant = $declarant->code_personne;
            }

            $dn->code_enfant = $enfant->code_personne;
            $dn->code_pere = $pere->code_personne;
            $dn->code_mere = $mere->code_personne;
            $dn->personne_declaree = "Enfant normal";
            if(Auth::user()->affectationActive()->institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins == "TCINS_0003")
            {
                $dn->code_lieu_survenance = "LSURV_0001";
            }else{
                $dn->code_lieu_survenance = $request->lieu_survenance;
            }
            $dn->code_user_institution  = Auth::user()->affectationActive()->cui;
            $dn->code_filiation = $request->filiation;
            $dn->code_situation_mat = $request->code_situation_matrimoniale;

            $dn->type_declaration = $request->type_declaration;
            // if ($request->type_declaration != 'DECLARATION DE NAISSANCE' && $request->type_declaration != "FICHE DE MATERNITE" && $request->type_declaration != "DECLARATION DE PATERNITE") {
            if ($request->type_declaration != 'DECLARATION DE NAISSANCE' && $request->type_declaration != "FICHE DE MATERNITE") {
                $dn->numero_certificat = Sifec::genererCodeUniqueReferentiel($dn,"numero_certificat",4,"");
            }
            $dn->formation_sanitaire_naissance = $request->formation_sanitaire_naissance;
            $dn->code_jugement = $request->code_jugement;
            $dn->code_institution = Auth::user()->affectationActive()->code_institution;
            $dn->save();

            $transaction = new MouvementNaissance();
            $transaction->code_mouvement_naissance = Sifec::genererCodeUniqueReferentiel($transaction,"code_mouvement_naissance",4,"MDN_");
            $transaction->statut = "En cours";
            $transaction->code_declaration_naissance = $dn->code_declaration_naissance;
            $transaction->cui = Auth::user()->affectationActive()->cui;
            $transaction->save();

            if($dn->type_declaration == "CERTIFICAT DE DESTRUCTION DE L'ACTE"){
                $requisition = new Requisition;
                $requisition->code_requisition = Sifec::genererCodeUniqueReferentiel($requisition, 'code_requisition', 4, "CREQ_");
                $requisition->code_institution = Auth::user()->affectationActive()->code_institution;
                $requisition->save();

                //update le code_requisition dans la dn
                $dn->code_requisition = $requisition->code_requisition;
                $dn->save();
            }
            if($dn->type_declaration == "CERTIFICAT DE NON INSCRIPTION"){

                  //calculer l'age de l'enfant
                    $dateNaissance = $request->date_naissance_enfant;
                    $dateNaissanceConvertis = Carbon::create($dateNaissance);
                    $date = date("Y-m-d");
                    $dateNaissanceNow = Carbon::create($date);
                    $ageEnfant = $dateNaissanceConvertis->diffInDays($dateNaissanceNow);

                 //cas de l'age > 3 mois un jugement est requis conformément à l'article 80 du code de la famille
                 if($ageEnfant > 90){
                    $jugement = new Jugement;
                    $jugement->code_jugement = Sifec::genererCodeUniqueReferentiel($jugement, 'code_jugement', 4, "CJUG_");
                    $jugement->type_jugement = "JUGEMENT D'AUTORISATION";
                    $jugement->code_institution = Auth::user()->affectationActive()->code_institution;
                    $jugement->save();

                    //update le code_jugement dans la dn
                    $dn->code_jugement = $jugement->code_jugement;
                    $dn->save();

                 }else{
                    $requisition = new Requisition;
                    $requisition->code_requisition = Sifec::genererCodeUniqueReferentiel($requisition, 'code_requisition', 4, "CREQ_");
                    $requisition->code_institution = Auth::user()->affectationActive()->code_institution;
                    $requisition->save();

                    //update le code_requisition dans la dn
                    $dn->code_requisition = $requisition->code_requisition;
                    $dn->save();
                 }


            }

            DB::commit();

            return response()->json([
                "code"=>"200",
                "message"=>"La déclaration enregistrée avec succès"
            ]);

        }catch(Exception $e){
            DB::rollBack();
            Log::channel("sifec")->error($e->getMessage());
            return response()->json([
                "code"=>"90",
                "message"=>["error" =>$e->getMessage()]
            ]);
        }

    }


    public function show($id)
    {
        return view('naissance::declaration.show');
    }

    public function edit($id)
    {
        $dn = Declarationnaissance::find($id);

        if($dn==null)
        {
            toastr()->error("Impossible de charger cette page");
            return back();
        }
        //recuperer la fonction de user pour la gestion de fiche de maternité
        //si la modification est faite lors de la declaration,
        //le document change de type(en declaration de naissance)
        $fnUser = Auth::user()->affectationActive()->fonction->code_fonction;
        $typeDeclaration = "";

        $fnUser == "FONC_0006" ? $typeDeclaration = "DECLARATION DE NAISSANCE" : $typeDeclaration = $dn->type_declaration; //agent formation sanitaire

       $title = " MODIFICATION ".$typeDeclaration;

       $date = Carbon::create(date("Y-m-y"));
       $dateNaissEnfant = Carbon::create($dn->enfant->date_naissance);
       $ageEnfant = $date->diffInYears($dateNaissEnfant);

       $instructions = Sifec::niveauInstructions();
       $localites = Localite::where('code_type_localite','TPLOC_0002')->Orwhere('code_type_localite','TPLOC_0003')->get();
       $professions = Profession::all();
       $nationalites = Nationalite::all();
       $lieuSurvenances = LieuSurvenance::all();
       $filiations = Filiation::all();
       $typedocuments = TypeDocument::all();
       $situationMatrimoniales = SituationMatrimoniale::all();
       $arrondissement = Localite::where('code_type_localite','TPLOC_0004')->Orwhere('code_type_localite','TPLOC_0005')->get();
       $quartierVillages = Localite::where('code_type_localite','TPLOC_0007')->Orwhere('code_type_localite','TPLOC_0008')->get();
       $countries = collect(json_decode(file_get_contents(public_path("codes_pays.json"))));
       $departements = Departement::all();


       return view('naissance::declaration.edit',compact("ageEnfant","dn","title","departements","countries","arrondissement","typedocuments","instructions","filiations","localites","professions","nationalites","situationMatrimoniales","lieuSurvenances","quartierVillages","typeDeclaration"));

    }


    public function update(Request $request, $id)
    {

        $dn = Declarationnaissance::find($id);
        // Log::channel("sifec")->info(["update"=> $request->all()]);
        // dd("ok");
       if($dn == null){
        return response()->json([
            "code"=>"150",
            "message"=>"Impossible de charger cette page"
            ]);
        }
        $pere = Personne::find($request->code_pere);
        $mere = Personne::find($request->code_mere);
        $enfant = Personne::find($request->code_enfant);

        //gestion de cas d'adoption
        $typeAdoption = $request->type_adoption;


       if($pere == null){
        return response()->json([
            "code"=>"150",
            "message"=>"Le père n'existe pas"
            ]);
       }
       if($mere == null){
        return response()->json([
            "code"=>"150",
            "message"=>"Le mère n'existe pas"
            ]);
       }

        DB::beginTransaction();

        try{

            $pere = Sifec::updatePersonne($request,"_pere","M",$pere->code_personne);
            $mere = Sifec::updatePersonne($request,"_mere","F",$mere->code_personne);

            //cas de l'adoption
            if($typeAdoption != ""){

                $enfantUniqueString = "";
                $adoptant = "";
                // $declarant = "";
                //cas d'adoption
                $enfantUniqueString = Sifec::uniqueString($request,"_enfant",$request->sexe_enfant,$typeAdoption);
                $enfant = Sifec::updatePersonne($request,"_enfant",$request->sexe_enfant,$enfant->code_personne,$enfantUniqueString);

                //creation de string unique de l'adoptant
                $adoptantUniqueString = Sifec::uniqueString($request,"_adoptant",$request->sexe_adoptant);

                $adoptant = Personne::where("personne_string",$adoptantUniqueString)->first();

                //creation de l'adoptant
                if($adoptant == ""){
                    $adoptant = Sifec::savePersonne($request,"_adoptant",$request->sexe_adoptant,$adoptantUniqueString);
                }

                //fin cas d'adoption
            }else{

                $declarant = Personne::find($request->code_declarant);
                // if($declarant == null){
                // return response()->json([
                //     "code"=>"150",
                //     "message"=>"Le déclarant n'existe pas"
                //     ]);
                // }


                $declarant = Sifec::updatePersonne($request,"_declarant",$request->sexe_declarant,$declarant->code_personne);
                $enfant = Sifec::updatePersonne($request,"_enfant",$request->sexe_enfant,$enfant->code_personne);
            }
            // Log::channel("sifec")->info(['enfant'=>$enfant]);
            // dd("ok");

            $lieuSurvenance = Auth::user()->affectationActive()->institution->TypeInstitution->typeCategorieInstitution->code_type_categorie_ins == "TCINS_0003" ? "LSURV_0001" : $request->lieu_survenance;
            $dn->nombre_enfant = $request->nombre_enfant;

            if ($request->date_heure_declaration != '') {
                $dn->date_heure_declaration = $request->date_heure_declaration.' 00:00';
            }else{
                $dn->date_heure_declaration = date("Y-m-d H:i");
            }
            $dn->date_heure_naissance = $request->date_naissance_enfant." ".$request->heure_naissance_enfant ;
            $dn->type_declarant = "Personne physique";
            if($request->filiation == "FIL_0001" && $typeAdoption== ""){
                $dn->code_declarant = $pere->code_personne;
            }
            if($request->filiation == "FIL_0002" && $typeAdoption== ""){
                $dn->code_declarant = $mere->code_personne;
            }
            if($request->filiation != "FIL_0001" && $request->filiation != "FIL_0002" && $typeAdoption== ""){
                $dn->code_declarant = $declarant->code_personne;
            }
            $dn->code_enfant = $enfant->code_personne;
            $dn->code_pere = $pere->code_personne;
            $dn->code_mere = $mere->code_personne;
            $dn->personne_declaree = "Enfant normal";
            if($request->type_declaration == 'DECLARATION DE NAISSANCE' || $request->type_declaration == 'FICHE DE MATERNITE'){
                $dn->code_lieu_survenance = "LSURV_0001";
            }
            // $dn->code_user_institution  = Auth::user()->affectationActive()->cui;
            $dn->code_filiation = $request->filiation;
            $dn->code_situation_mat = $request->code_situation_matrimoniale;
            $dn->type_declaration = $request->type_declaration;
            if ($request->type_declaration != 'DECLARATION DE NAISSANCE' && $request->type_declaration != "FICHE DE MATERNITE" && $request->type_declaration != "DECLARATION DE PATERNITE") {
                $dn->numero_certificat = Sifec::genererCodeUniqueReferentiel($dn,"numero_certificat",4,"");
            }
            $dn->formation_sanitaire_naissance = $request->formation_sanitaire_naissance;

            if($typeAdoption != ""){
                //cas d'adoption partielle
                $dn->code_jugement = $request->code_jugement;
                // $dn->date_jugement = $request->date_jugement;
                // $dn->code_tribunal_jugement  = $request->tribunal_jugement;
                $dn->code_adoptant = $adoptant->code_personne;
                $dn->type_adoption = $typeAdoption;
            }
            $dn->save();


           DB::commit();

            return response()->json([
                "code"=>"200",
                "message"=>"Document modifié avec succès"
            ]);

        }catch(Exception $e){
            DB::rollBack();
            Log::channel("sifec")->error($e->getMessage());

            return response()->json([
                "code"=>"150",
                "message"=>$e->getMessage()
            ]);
        }

    }

    public function mouvement(Request $request)
    {
        $rules = [
            "code_declaration_naissance"=>["required"]
            // ,
            // "motif_renvoi"=>["required"],
            // "observation"=>["required"]
        ];
        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json([
                "code"=>"180",
                "message"=>"Veuillez renseigner le motif et l'observation !"
            ]);
        }

        DB::beginTransaction();
        try{
            $dn = Declarationnaissance::find($request->code_declaration_naissance);
            $typeDeclaration = $dn->type_declaration;

            $lastMouvement = $dn->mouvements->last();

            $transaction = new MouvementNaissance();
            $transaction->code_mouvement_naissance = Sifec::genererCodeUniqueReferentiel($transaction,"code_mouvement_naissance",4,"MDN_");

            if($lastMouvement->statut == "En cours"){
                $transaction->statut = "Envoyée";
                $dn->approuver = "OUI";
            }
            if($lastMouvement->statut == "Envoyée"){
                $transaction->statut = "Renvoyée";
                $dn->approuver = "NON";
            }
            if($lastMouvement->statut == "Renvoyée"){
                $transaction->statut = "Envoyée";
                $dn->approuver = "OUI";
            }

            if($typeDeclaration == "CERTIFICAT DE NON INSCRIPTION" || $typeDeclaration == "CERTIFICAT DE DESTRUCTION DE L'ACTE"){
                if($lastMouvement->statut == "En cours"){
                    $transaction->statut = "Envoye au tribunal";
                }
            }

            $transaction->code_declaration_naissance = $request->code_declaration_naissance;
            $transaction->cui = Auth::user()->affectationActive()->cui;
            $transaction->motif_renvoi = $request->motif_renvoi;
            $transaction->observation = trim($request->observation);
            $transaction->save();

             $dn->save();

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
        $mvtn = MouvementNaissance::find($id);


        if($mvtn =="" || $mvtn == null){
            return response()->json([
                "code"=>"183",
                "message"=>["Aucune donnée trouvée"]
            ]);
        }

        DB::beginTransaction();
        try {

            $mvtn->motif_renvoi = $request->motif_renvoi;
            $mvtn->observation = trim($request->observation);
            $mvtn->save();

            //update (lu et approuve) du déclarant
            $dn = Declarationnaissance::find($mvtn->code_declaration_naissance);
            $dn->approuver = "NON";
            $dn->save();
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
        $mvtn = MouvementNaissance::find($id);


        if($mvtn =="" || $mvtn == null){
            return response()->json([
                "code"=>"183",
                "message"=>["Aucune donnée trouvée"]
            ]);
        }

        try {
            $mvtn->delete();
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

    public function statParSexe()
    {

        $declarations = Auth::user()->institution()->declarationsNaissances();
        $liste = [];
        foreach ($declarations as $key) {
            $liste[] = $key->code_declaration_naissance;
        }
        $array = implode("','",$liste);

        $datas = DB::select("SELECT tr_identification_personne.sexe, COUNT(*) AS TOTAL
        FROM t_declaration_naissance
        JOIN tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_naissance.code_enfant
        -- AND code_declaration_naissance IN ('".$array."')
        AND MONTH(t_declaration_naissance.date_heure_declaration) = MONTH(CURDATE())
        GROUP BY tr_identification_personne.sexe");

        return view('naissance::statistiques.declarationSexe', compact('datas'));
    }

    public function statParSexeEtat()
    {
        $declarations = Auth::user()->institution()->declarationsNaissances();
        $liste = [];
        foreach ($declarations as $key) {
            $liste[] = $key->code_declaration_naissance;
        }
        $array = implode("','",$liste);

        $datas = DB::select("SELECT tr_identification_personne.sexe, COUNT(*) AS TOTAL
        FROM t_declaration_naissance
        JOIN tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_naissance.code_enfant
        -- AND code_declaration_naissance IN ('".$array."')
        AND MONTH(t_declaration_naissance.date_heure_declaration) = MONTH(CURDATE())
        GROUP BY tr_identification_personne.sexe");

        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('naissance::etats.statistiques.declarationParsexe', compact("datas"))->render());

        return $html2pdf->output("declarationParsexe.pdf");
    }

    public function statParSexeActe()
    {

        $declarations = Auth::user()->affectations()->get();
        $liste = [];
        foreach ($declarations as $key) {
            $liste[] = $key->code_institution;
        }
        $array = implode("','",$liste);

        $datas = DB::select("SELECT sexe, COUNT(*) AS TOTAL
        FROM t_acte_naissance
        JOIN t_declaration_naissance ON t_declaration_naissance.code_declaration_naissance = t_acte_naissance.code_declaration_naissance
        JOIN tr_identification_personne tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_naissance.code_enfant
        JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
        JOIN tr_institution ON tr_institution.code_institution = tr_ins_user.code_institution
        WHERE  tr_institution.code_institution IN ('".$array."')
        AND MONTH(t_acte_naissance.date_emission) = MONTH(CURDATE())
        GROUP BY tr_identification_personne.sexe");

        return view('naissance::statistiques.acteNaissanceSexe', compact('datas'));
    }

    public function statParSexeActeEtat(){
        $declarations = Auth::user()->affectations()->get();
        $liste = [];
        foreach ($declarations as $key) {
            $liste[] = $key->code_institution;
        }
        $array = implode("','",$liste);

        $datas = DB::select("SELECT sexe, COUNT(*) AS TOTAL
        FROM t_acte_naissance
        JOIN t_declaration_naissance ON t_declaration_naissance.code_declaration_naissance = t_acte_naissance.code_declaration_naissance
        JOIN tr_identification_personne tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_naissance.code_enfant
        JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
        JOIN tr_institution ON tr_institution.code_institution = tr_ins_user.code_institution
        WHERE t_acte_naissance.supprimer = 0
        AND tr_institution.code_institution IN ('".$array."')
        AND MONTH(t_acte_naissance.date_emission) = MONTH(CURDATE())
        GROUP BY tr_identification_personne.sexe");

        view()->share("tester", "Vincent");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('naissance::etats.statistiques.acteNaissanceSexeEtat', compact("datas"))->render());

        return $html2pdf->output("declarationParsexe.pdf");
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

    public function searchLocalite($id)
    {
        $qv = Sifec::communesDistricts($id);
        // return $qv->flatten();
        return response()->json([
            "localites" => $qv->flatten()
        ]);
    }


    public function searchArrondissement()
    {
        $id =request('id');
        $arrond = Sifec::ArrondissementComUrb($id);
        return response()->json($arrond->flatten());
    }

    public function searchQuartier()
    {
        $id = request('id');
        $quartiers = Sifec::Quartier($id);
        return response()->json($quartiers->flatten());
    }

    public function searchInstitution()
    {
        $id = request('id');

        $institutions = Sifec::institutions($id,"TPINS_0002");
        //cas de owando
        // $institutions = Institution::where("code_localite",$id)->get();
        Log::channel("sifec")->info($institutions);
        return response()->json($institutions);
    }


    public function tardive()
    {
        $title = "Créer une déclaration tardive de naissance";

        $type_declaration = "DECLARATION TARDIVE DE NAISSANCE";
        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite','TPLOC_0002')->Orwhere('code_type_localite','TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $lieuSurvenances = LieuSurvenance::all();
        $filiations = Filiation::all();
        $typedocuments = TypeDocument::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $arrondissement = Localite::where('code_type_localite','TPLOC_0004')->Orwhere('code_type_localite','TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite','TPLOC_0007')->Orwhere('code_type_localite','TPLOC_0008')->get();
        $countries = collect(json_decode(file_get_contents(public_path("codes_pays.json"))));
        $departements = Departement::all();

        return view('naissance::declaration.create',compact("title","departements","countries","arrondissement","typedocuments","instructions","filiations","localites","professions","nationalites","situationMatrimoniales","lieuSurvenances","quartierVillages","type_declaration"));

    }
    public function paternite()
    {
        $title = "Créer une déclaration de paternité";

        $type_declaration = "DECLARATION DE PATERNITE";
        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite','TPLOC_0002')->Orwhere('code_type_localite','TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $lieuSurvenances = LieuSurvenance::all();
        $filiations = Filiation::all();
        $typedocuments = TypeDocument::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $arrondissement = Localite::where('code_type_localite','TPLOC_0004')->Orwhere('code_type_localite','TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite','TPLOC_0007')->Orwhere('code_type_localite','TPLOC_0008')->get();
        $countries = collect(json_decode(file_get_contents(public_path("codes_pays.json"))));
        $departements = Departement::all();

        return view('naissance::declaration.create',compact("title","departements","countries","arrondissement","typedocuments","instructions","filiations","localites","professions","nationalites","situationMatrimoniales","lieuSurvenances","quartierVillages","type_declaration"));

    }


    public function joindreDocument($id)
    {
        $dn = Declarationnaissance::find($id);
        if($dn ==null){
            toastr()->error("Aucun document trouvé");
            return back();
        }
       return view('naissance::declaration.add_document', compact("dn"));
    }

    public function storeScanner(Request $request)
    {

        $file = $request->com_asprise_scannerjs_images[0];

        //Recherche du document
        $fdoc = Document::where("numero_document", $request->numero_document)->first();

        if($fdoc == null){
            return response()->json([
                "code"=> "180",
                "message"=> "Ce document n'existe pas"
            ]);
        }


        DB::beginTransaction();
        try{

            $chemin_document = $file->store("document");

            $fdoc->image_document = $chemin_document;
            $fdoc->save();

            DB::commit();
            return response()->json(1);

        }catch(Exception $e){
            DB::rollBack();
            Log::channel("sifec")->error($e->getMessage());
            return response()->json($e->getMessage());


        }
    }

    public function storeImporter(Request $request)
    {
        // return $request->all();

        // verification des champs avec ajax
        $validator = \Validator::make($request->all(),[
            // 'file' => 'required|mimes:doc,docx,pdf,zip,rar|max:2048',
            'file' => 'required|mimes:pdf|max:2048',
            'code_type_document'=>'required|string',
            'codeparent'=>'required|string',
            'numero_document'=>'required|string'
        ]);


        // Message des champs vide avec ajax
        if(!$validator->passes()){
            return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }

        $fdoc = Document::where("numero_document", $request->numero_document)->first();

        if ($fdoc == null) {
            return response()->json(['code'=>1,'msg'=>'Le système ne retrouve pas ce document']);
        }

        DB::beginTransaction();
        try{

            $chemin_document = $request->file->store("document");

            $fdoc->image_document = $chemin_document;
            $fdoc->save();

            DB::commit();
            return response()->json(['code'=>2,"codeparent"=>$request->codeparent, 'msg'=>'Enregistement de la pièce effectué avec succès']);

        }catch(Exception $e){
            DB::rollBack();
            //-------- redirection avec ajax
            return response()->json(['code'=>1, 'msg'=> $e->getMessage()]);
        }
    }

    // Retrouver le document de la personne par son code
    public function getDocument($id)
    {
        $documents = Document::where("code_personne",$id)->get();
        $out = "";
        $count = 1;
        foreach($documents as $document){
            $out .= "
                <tr style='width: 100px;'>
                <td>".$count ++. "</td>
                <td>".($document->numero_document)."</td>
                <td>".$document->typeDocument->lib_type_document."</td>
                <td>
                    <div class='btn-group btn-group-xs'>
                    <a href='".route("declarationNaissance.show.document",$document->code_document)."' class='btn btn-success shadow btn-xs sharp me-1' title='Voir la pièce'><i class='fas fa-eye'></i></a>
                        <form action='".route("declarationNaissance.destroy.document",$document->code_document)."' method='post'>

                            <button class='btn btn-danger shadow btn-xs sharp' type='submit'><i class='fa fa-trash'></i></button>
                        </form>
                    </div>
                </td>
                </tr>
           ";
        }

        return $out;
    }


    public function showDocument($id)
    {
        $doc = Document::find($id);

        if($doc == null){
            toastr()->error("Document indisponible");
            return back();
        }

        if(Document::find($id)->image_document == ""){

            toastr()->warning("Image de la pièce est indisponible dans le système.<br> Veuillez Scanner la pièce","Gestion des documents");
            return back();
        }
        return Storage::download(Document::find($id)->image_document, Document::find($id)->typeDocument->lib_type_document);
    }


    public function deleteDocument($id)
    {
        return $id;
    }

    //enfant a adopter
    public function adoption(Request $request,$id)
    {

        $request->validate([
            "code_jugement"=> ["required"],
            "numero_acte_naissance"=> ["required"]
        ]);

        $dn = Declarationnaissance::find($id);

        if($dn == null){
            toastr()->error("Document indisponible");
            return back();
        }
        //enfant déjà adopté
        if($dn->code_adoptant != null || $dn->code_adoptant != ""){
            toastr()->error("Cet enfant a déjà été adopté, veuiller contacter l'administrateur");
            return back();
        }

        $title = "Adoption d'un enfant";
        $dateNaissanceConvertis = Carbon::create($dn->enfant->date_naissance);
        $date = date("Y-m-d");
        $dateNaissanceNow = Carbon::create($date);
        $ageEnfant = $dateNaissanceConvertis->diffInYears($dateNaissanceNow);
        $tgis = Institution::where("code_type_institution","TPINS_0001")->get();

        $typeDeclaration = "DECLARATION DE NAISSANCE";
        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite','TPLOC_0002')->Orwhere('code_type_localite','TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $lieuSurvenances = LieuSurvenance::all();
        $filiations = Filiation::all();
        $typedocuments = TypeDocument::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $arrondissement = Localite::where('code_type_localite','TPLOC_0004')->Orwhere('code_type_localite','TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite','TPLOC_0007')->Orwhere('code_type_localite','TPLOC_0008')->get();
        $countries = collect(json_decode(file_get_contents(public_path("codes_pays.json"))));
        $departements = Departement::all();

        return view('naissance::adoption.adopter',compact("dn","tgis","title","departements","ageEnfant","countries","arrondissement","typedocuments","instructions","filiations","localites","professions","nationalites","situationMatrimoniales","lieuSurvenances","quartierVillages","typeDeclaration"));

    }

    //enregistrement adoption pleniere d'un enfant
    public function storeAdoptionPleniere(Request $request)
    {

        $rules = [
            "nom_enfant"=>["required"],
            "date_naissance_enfant"=>["required","date"],
            "code_situation_matrimoniale"=>["required"],
            "sexe_enfant"=>["required"],
            "heure_naissance_enfant"=>["required","max:5","min:5"],
            "nombre_enfant"=>["required","numeric"],
            "code_jugement"=>["required"]
        ];

        $dateNaissEnfant = Carbon::create($request->date_naissance_enfant);
        $date = Carbon::create(date("Y-m-y"));
        $ageEnfant = $date->diffInYears($dateNaissEnfant);

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json([
                "code"=>"150",
                "message"=>$validator->errors()
            ]);
        }
        // le pere doit avoir au moins 14 ans de plus que l'enfant
        // la mere doit avoir au moins 12 ans de plus que l'enfant
        $dateNaissancePere = Carbon::create($request->date_naissance_pere);
        $dateNaissanceEnfant = Carbon::create($request->date_naissance_enfant);
        $dateNaissanceMere = Carbon::create($request->date_naissance_mere);
        $differenceAgeEnfantPere = $dateNaissancePere->diffInYears($dateNaissanceEnfant);
        $differenceAgeEnfantMere = $dateNaissanceMere->diffInYears($dateNaissanceEnfant);


        if($differenceAgeEnfantPere < 14){
            return response()->json([
                "code"=>"99",
                "message"=>["age_pere"=>"La différence d'age entre père et enfant doit être supérieure ou égale à 14 ans"]
            ]);
        }

        if($differenceAgeEnfantMere < 12){
            return response()->json([
                "code"=>"99",
                "message"=>["age_mere"=>"La différence d'age entre mère et enfant doit être supérieure ou égale à 12 ans"]
            ]);
        }

        // $pereUniqueString = Sifec::uniqueString($request,"_pere","M");
        // $pere = Sifec::savePersonne($request,"_pere","M",$pereUniqueString);
        DB::beginTransaction();

        try{

            //gestion de cas d'adoption
            $typeAdoption = $request->type_adoption;
            $pereUniqueString = Sifec::uniqueString($request,"_pere","M");
            $mereUniqueString = Sifec::uniqueString($request,"_mere","F");
            $enfantUniqueString = Sifec::uniqueString($request,"_enfant",$request->sexe_enfant,$typeAdoption);

            $ec = Personne::where("personne_string",$enfantUniqueString)->first();

            $adoptantUniqueString = Sifec::uniqueString($request,"_adoptant",$request->sexe_adoptant);

            if($ec != null){
                return response()->json([
                    "code"=>"99",
                    "message"=>["enfant_exist"=>"La déclaration de cet enfant existe déjà dans le système"]
                ]);
            }

            $pere = Personne::where("personne_string",$pereUniqueString)->first();
            $mere = Personne::where("personne_string",$mereUniqueString)->first();

            //insertion de pere,mere,adoptant et enfant
            if($pere == null){
                $pere = Sifec::savePersonne($request,"_pere","M",$pereUniqueString);
            }
            if($mere == null){
                $mere = Sifec::savePersonne($request,"_mere","F",$mereUniqueString);
            }

            $adoptant = Personne::where("personne_string",$adoptantUniqueString)->first();
            $enfant = Sifec::savePersonne($request,"_enfant",$request->sexe_enfant,$enfantUniqueString);

            if($adoptant == null)
            {
                $adoptant = Sifec::savePersonne($request,"_adoptant",$request->sexe_adoptant,$adoptantUniqueString);
            }

            // Log::channel("sifec")->info($request->all());
            // dd("ok ok");
            $dn = new Declarationnaissance;
            $codedn = Sifec::genererCodeUniqueReferentiel($dn,"code_declaration_naissance",8,"CDN_");
            $dn->code_declaration_naissance = $codedn;
            $dn->nombre_enfant = $request->nombre_enfant;
            if ($request->date_heure_declaration != '') {
                $dn->date_heure_declaration = $request->date_heure_declaration.' 00:00';
            }else{
                $dn->date_heure_declaration = date("Y-m-d H:i");
            }
            $dn->date_heure_naissance = $request->date_naissance_enfant." ".$request->heure_naissance_enfant.":00" ;
            $dn->type_declarant = "Personne physique";
            $dn->code_adoptant = $adoptant->code_personne;
            $dn->code_enfant = $enfant->code_personne;
            $dn->code_pere = $pere->code_personne;
            $dn->code_mere = $mere->code_personne;
            $dn->personne_declaree = "Enfant normal";
            if(Auth::user()->affectationActive()->institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins == "TCINS_0003")
            {
                $dn->code_lieu_survenance = "LSURV_0001";
            }else{
                $dn->code_lieu_survenance = $request->lieu_survenance;
            }
            $dn->code_user_institution  = Auth::user()->affectationActive()->cui;
            $dn->code_filiation = $request->filiation;
            $dn->code_situation_mat = $request->code_situation_matrimoniale;
            $dn->type_declaration = $request->type_declaration;
            if ($request->type_declaration != 'DECLARATION DE NAISSANCE' && $request->type_declaration != "FICHE DE MATERNITE" && $request->type_declaration != "DECLARATION DE PATERNITE") {
                $dn->numero_certificat = Sifec::genererCodeUniqueReferentiel($dn,"numero_certificat",4,"");
            }
            $dn->formation_sanitaire_naissance = $request->formation_sanitaire_naissance;
            $dn->numero_ancien_acte = $request->niupp;
            $dn->code_jugement = $request->code_jugement;
            $dn->save();

            $transaction = new MouvementNaissance();
            $transaction->code_mouvement_naissance = Sifec::genererCodeUniqueReferentiel($transaction,"code_mouvement_naissance",4,"MDN_");
            $transaction->statut = "En cours";
            $transaction->code_declaration_naissance = $dn->code_declaration_naissance;
            $transaction->cui = Auth::user()->affectationActive()->cui;
            $transaction->save();

            //cas d'adoption plenierre de l'enfant, on fais la mise a jour de l'ancien acte
            $updtaeOldActe = ActeNaissance::find($request->niupp);
            $updtaeOldActe->statut = 1; //annulation de l'acte
            $updtaeOldActe->save();

            DB::commit();

            return response()->json([
                "code"=>"200",
                "message"=>"La déclaration enregistrée avec succès"
            ]);

            }catch(Exception $e){
                DB::rollBack();
                Log::channel("sifec")->error($e->getMessage());
                return response()->json([
                    "code"=>"99",
                    "message"=>["error" =>$e->getMessage()]
                ]);
            }

    }


    //on va creer un certificat de non inscription à partir du jugement venant du tribunal de ressort
    public function createDeclarationJugement($code_jugement)
    {

        //rechercher le jugement
        $jugement = Jugement::find($code_jugement);
        if($jugement == null){
            toastr()->error("Impossible de charger cette page");
            return back();
        }

        $user = Auth::user();
        $title = "";
        $type_declaration = "";
        if($jugement->type_jugement == "JUGEMENT SUPPLETIF"){
            $title = "Créer un certificat de non inscription/jugement supplétif";
            $type_declaration = $jugement->type_jugement;
        }
        if($jugement->type_jugement == "JUGEMENT D'HOMOLOGATION"){
            $title = "Créer déclaration/jugement d'homologation ou de paternité";
            $type_declaration = $jugement->type_jugement;
        }
        if($jugement->type_jugement == "JUGEMENT D'ANNULATION D'ACTE"){
            $title = "Créer déclaration/jugement d'annulation de l'acte";
            $type_declaration = $jugement->type_jugement;

            //vérification de l'acte
            $an = ActeNaissance::find($jugement->numero_ancien_acte);
            return view('naissance::acte.annuler',compact("jugement","an"));
        }
        if($jugement->type_jugement == "JUGEMENT D'ADOPTION"){
            $title = "";
            $type_declaration = $jugement->type_jugement;

            //vérification de l'acte
            $an = ActeNaissance::find($jugement->numero_ancien_acte);
            return view('naissance::adoption.indentification_acte',compact("jugement","an"));
        }



        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite','TPLOC_0002')->Orwhere('code_type_localite','TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $lieuSurvenances = LieuSurvenance::all();
        $filiations = Filiation::all();
        $typedocuments = TypeDocument::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $arrondissement = Localite::where('code_type_localite','TPLOC_0004')->Orwhere('code_type_localite','TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite','TPLOC_0007')->Orwhere('code_type_localite','TPLOC_0008')->get();
        $countries = collect(json_decode(file_get_contents(public_path("codes_pays.json"))));
        $departements = Departement::all();

        $tgis = Institution::where("code_type_institution","TPINS_0001")->get();

        return view('naissance::jugementsupletif.create',compact("tgis","jugement","type_declaration","title","departements","countries","arrondissement","typedocuments","instructions","filiations","localites","professions","nationalites","situationMatrimoniales","lieuSurvenances","quartierVillages"));
    }

    public function jugementhomologation()
    {
        $user = Auth::user();
        $title = "Créer déclaration/jugement d'homologation ou de paternité";
        $type_declaration = "JUGEMENT D'HOMOLOGATION";

        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite','TPLOC_0002')->Orwhere('code_type_localite','TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $lieuSurvenances = LieuSurvenance::all();
        $filiations = Filiation::all();
        $typedocuments = TypeDocument::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $arrondissement = Localite::where('code_type_localite','TPLOC_0004')->Orwhere('code_type_localite','TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite','TPLOC_0007')->Orwhere('code_type_localite','TPLOC_0008')->get();
        $countries = collect(json_decode(file_get_contents(public_path("codes_pays.json"))));
        $departements = Departement::all();

        $tgis = Institution::where("code_type_institution","TPINS_0001")->get();

        // return view('naissance::jugementsupletif.create',compact("tgis","title","departements","countries","arrondissement","typedocuments","instructions","filiations","localites","professions","nationalites","situationMatrimoniales","lieuSurvenances","quartierVillages","type_declaration"));

        return view('naissance::jugementhomologation.create',compact("tgis","title","departements","countries","arrondissement","typedocuments","instructions","filiations","localites","professions","nationalites","situationMatrimoniales","lieuSurvenances","quartierVillages","type_declaration"));
    }



    public function storeJugementHomologation(Request $request)
    {

        $rules = [
            "nom_enfant"=>["required"],
            "date_naissance_enfant"=>["required","date"],
            "code_situation_matrimoniale"=>["required"],
            "sexe_enfant"=>["required"],
            "heure_naissance_enfant"=>["required","max:5","min:5"],
            "nombre_enfant"=>["required","numeric"]
        ];

        $dateNaissEnfant = Carbon::create($request->date_naissance_enfant);
        $date = Carbon::create(date("Y-m-y"));
        $ageEnfant = $date->diffInYears($dateNaissEnfant);


        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json([
                "code"=>"150",
                "message"=>$validator->errors()
            ]);
        }
        // le pere doit avoir au moins 14 ans de plus que l'enfant
        // la mere doit avoir au moins 12 ans de plus que l'enfant

        $dateNaissancePere = Carbon::create($request->date_naissance_pere);
        $dateNaissanceEnfant = Carbon::create($request->date_naissance_enfant);
        $dateNaissanceMere = Carbon::create($request->date_naissance_mere);
        $differenceAgeEnfantPere = $dateNaissancePere->diffInYears($dateNaissanceEnfant);
        $differenceAgeEnfantMere = $dateNaissanceMere->diffInYears($dateNaissanceEnfant);


        if($differenceAgeEnfantPere < 14){
            return response()->json([
                "code"=>"99",
                "message"=>["age_pere"=>"La différence d'age entre père et enfant doit être supérieure ou égale à 14 ans"]
            ]);
        }

        if($differenceAgeEnfantMere < 12){
            return response()->json([
                "code"=>"99",
                "message"=>["age_mere"=>"La différence d'age entre mère et enfant doit être supérieure ou égale à 12 ans"]
            ]);
        }

        DB::beginTransaction();
        try{

            //recuperation de l'ancienne declaration
            $dnOld = ActeNaissance::find($request->numero_ancien_acte);
            if($dnOld == NULL){

                $pereUniqueString = Sifec::uniqueString($request,"_pere","M");
                $mereUniqueString = Sifec::uniqueString($request,"_mere","F");
                $enfantUniqueString = Sifec::uniqueString($request,"_enfant",$request->sexe_enfant);
                $ec = Personne::where("personne_string",$enfantUniqueString)->first();

                $declarantUniqueString = Sifec::uniqueString($request,"_declarant",$request->sexe_declarant);

                if($ec != null){
                    return response()->json([
                        "code"=>"99",
                        "message"=>["enfant_exist"=>"La déclaration de cet enfant existe déjà dans le système"]
                    ]);
                }

                $pere = Personne::where("personne_string",$pereUniqueString)->first();
                $mere = Personne::where("personne_string",$mereUniqueString)->first();
                $declarant = Personne::where("personne_string",$declarantUniqueString)->first();


                Log::channel("sifec")->info("aucun acte trouvé");
                dd("ok");
            }


            //création du nouveau pere
            $newPereUniqueString = Sifec::uniqueString($request,"_pere","M");
            $newPpere = Personne::where("personne_string",$newPereUniqueString)->first();

            //insertion du nouveau new pere
            if($newPpere == null){
                $newPpere = Sifec::savePersonne($request,"_pere","M",$newPereUniqueString);
            }

            //recuperation des infos de la mere
            $oldActe = ActeNaissance::find($request->numero_ancien_acte);
            $mere = $oldActe->declaration->mere;
            $declarant = $newPpere;
            $pere = $newPpere;
            $enfant = $oldActe->declaration->enfant;

            // if($declarant == null)
            // {

            //     if($request->filiation != "FIL_0001" && $request->filiation != "FIL_0002")
            //     {
            //         $declarant = Sifec::savePersonne($request,"_declarant",$request->sexe_declarant,$declarantUniqueString);
            //     }
            //     if($declarantUniqueString != $enfantUniqueString)
            //     {
            //         $enfant = Sifec::savePersonne($request,"_enfant",$request->sexe_enfant,$enfantUniqueString);
            //     }
            //     else
            //     {
            //         $enfant = $declarant;
            //     }
            // }


            $dn = new Declarationnaissance;
            $codedn = Sifec::genererCodeUniqueReferentiel($dn,"code_declaration_naissance",8,"CDN_");
            $dn->code_declaration_naissance = $codedn;
            $dn->nombre_enfant = $request->nombre_enfant;
            if ($request->date_heure_declaration != '') {
                $dn->date_heure_declaration = $request->date_heure_declaration.' 00:00';
            }else{
                $dn->date_heure_declaration = date("Y-m-d H:i");
            }
            $dn->date_heure_naissance = $request->date_naissance_enfant." ".$request->heure_naissance_enfant.":00" ;
            $dn->type_declarant = "Personne physique";
            if($request->filiation == "FIL_0001"){
                $dn->code_declarant = $pere->code_personne;
            }
            if($request->filiation == "FIL_0002"){
                $dn->code_declarant = $mere->code_personne;
            }
            if($request->filiation != "FIL_0001" && $request->filiation != "FIL_0002"){
                $dn->code_declarant = $declarant->code_personne;
            }

            $dn->code_enfant = $enfant->code_personne;
            $dn->code_pere = $pere->code_personne;
            $dn->code_mere = $mere->code_personne;
            $dn->personne_declaree = "Enfant normal";
            if(Auth::user()->affectationActive()->institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins == "TCINS_0003")
            {
                $dn->code_lieu_survenance = "LSURV_0001";
            }else{
                $dn->code_lieu_survenance = $request->lieu_survenance;
            }
            $dn->code_user_institution  = Auth::user()->affectationActive()->cui;
            $dn->code_filiation = $request->filiation;
            $dn->code_situation_mat = $request->code_situation_matrimoniale;
            $dn->type_declaration = $request->type_declaration;
            $dn->formation_sanitaire_naissance = $request->formation_sanitaire_naissance;

            $dn->num_jugement = $request->num_jugement;
            $dn->date_jugement = $request->date_jugement;
            $dn->code_tribunal_jugement  = $request->tribunal;
            $dn->numero_ancien_acte  = $request->numero_ancien_acte;
            $dn->save();

            $transaction = new MouvementNaissance();
            $transaction->code_mouvement_naissance = Sifec::genererCodeUniqueReferentiel($transaction,"code_mouvement_naissance",4,"MDN_");
            $transaction->statut = "En cours";
            $transaction->code_declaration_naissance = $dn->code_declaration_naissance;
            $transaction->cui = Auth::user()->affectationActive()->cui;
            $transaction->save();

            //mise à jour du statut de l'ancienne déclaration de naissance
            $oldDn = Declarationnaissance::find($oldActe->declaration->code_declaration_naissance);
            $oldDn->statut  = 1;
            $oldDn->num_jugement = $request->num_jugement;
            $oldDn->date_jugement = $request->date_jugement;
            $oldDn->code_tribunal_jugement  = $request->tribunal;
            $oldDn->save();

            DB::commit();

            return response()->json([
                "code"=>"200",
                "message"=>"Le document enregistré avec succès"
            ]);

            }catch(Exception $e){
                DB::rollBack();
                Log::channel("sifec")->error($e->getMessage());
                return response()->json([
                    "code"=>"99",
                    "message"=>["error" =>$e->getMessage()]
                ]);
            }

    }


}
