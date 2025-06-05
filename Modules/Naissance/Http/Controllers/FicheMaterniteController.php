<?php

namespace Modules\Naissance\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Sifec\Sifec;
use App\Sifec\SifecFacade;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Notification\Jobs\SendSmsJob;
use Modules\Referentiel\Entities\Localite;
use Modules\Referentiel\Entities\Personne;
use Modules\Referentiel\Entities\Filiation;
use Illuminate\Contracts\Support\Renderable;
use Modules\Referentiel\Entities\Profession;
use Modules\Referentiel\Entities\Departement;
use Modules\Referentiel\Entities\Nationalite;
use Modules\Referentiel\Entities\TypeDocument;
use Modules\Referentiel\Entities\LieuSurvenance;
use Modules\Naissance\Entities\MouvementNaissance;
use Modules\Naissance\Entities\Declarationnaissance;
use Modules\Referentiel\Entities\SituationMatrimoniale;

class FicheMaterniteController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $declarations = [];
        $title = "Liste des déclarations de naissance";
        $button = "Créer déclaration";


        if($user->affectationActive()->fonction->code_fonction == "FONC_0020") //si c'est un agent de maternité
        {
            $title = "Liste des fiches de maternité";
            $button = "Créer une fiche de maternité";
            // $declarations = Declarationnaissance::where("code_user_institution",$user->affectationActive()->cui)->where('statut_enfant','Vivant')->get();
            $declarations = Declarationnaissance::where("code_user_institution",$user->affectationActive()->cui)->get();
        }

        return view('naissance::fiche-maternite.index', compact("declarations","title","button"));
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
        $dummy = "XXXXXXXXXXXXXXXX";

        if($user->affectationActive()->fonction->code_fonction == "FONC_0020") //si c'est un agent de maternité
        {
            $title = "Créer une fiche de maternité";
            $type_declaration = "FICHE DE MATERNITE";
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

        return view('naissance::fiche-maternite.create',compact("title","dummy","departements","countries","arrondissement","typedocuments","instructions","filiations","localites","professions","nationalites","situationMatrimoniales","lieuSurvenances","quartierVillages","type_declaration"));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // Log::channel("sifec")->info(["objet"=>$request->all()]);
        // dd("opala");
        $rules = [
            "date_naissance_enfant"=>["required","date"],
            "poids_enfant"=>["required"],
            "sexe_enfant"=>["required"],
            "heure_naissance_enfant"=>["required","max:5","min:5"],
            "taille_enfant"=>["required"],
            "pc_enfant"=>["required"],
            "taille_enfant"=>["required"]
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json([
                "code"=>"150",
                "message"=>$validator->errors()
            ]);
        }

        // Log::channel("sifec")->info($request->all());
        // dd("ok");
        // le pere doit avoir au moins 14 ans de plus que l'enfant
        // la mere doit avoir au moins 12 ans de plus que l'enfant
        $dateNaissanceEnfant = Carbon::create($request->date_naissance_enfant);
        $dateNaissanceMere = Carbon::create($request->date_naissance_mere);
        $differenceAgeEnfantMere = $dateNaissanceMere->diffInYears($dateNaissanceEnfant);

        if($differenceAgeEnfantMere < 12){
            return response()->json([
                "code"=>"99",
                "message"=>["age_mere"=>"La différence d'age entre mère et enfant doit être supérieure ou égale à 12 ans"]
            ]);
        }

        DB::beginTransaction();
        try {

            $pereUniqueString = Sifec::uniqueString($request,"_pere","M");

            $pc = Personne::where("personne_string",$pereUniqueString)->first();
            $mereUniqueString = Sifec::uniqueString($request,"_mere","F");
            $mc = Personne::where("personne_string",$mereUniqueString)->first();

            $poids = $request->poids_enfant;
            $tailleEnfant = $request->taille_enfant;
            $pcEnfant = $request->pc_enfant;
            $enfantUniqueString = Sifec::uniqueString($request,"_enfant",$request->sexe_enfant,"",$poids,$tailleEnfant,$pcEnfant);


            $ec = Personne::where("personne_string",$enfantUniqueString)->first();
            $declarantUniqueString = Sifec::uniqueString($request,"_declarant",$request->sexe_declarant);
            $declc = Personne::where("personne_string",$declarantUniqueString)->first();
            $ec = Personne::where("personne_string",$enfantUniqueString)->first();

            if($ec != null){

                return response()->json([
                    "code"=>"99",
                    "message"=>["enfant_exist"=>"La déclaration de cet enfant existe déjà dans le système"]
                ]);
            }


            $pere = Personne::where("personne_string",$pereUniqueString)->first();
            $mere = Personne::where("personne_string",$mereUniqueString)->first();
            $declarant = Personne::where("personne_string",$declarantUniqueString)->first();
            $enfant = Personne::where("personne_string",$enfantUniqueString)->first();

            if($pere == null){
                $pere = Sifec::savePersonne($request,"_pere","M",$pereUniqueString);
            }

            if($mere == null){
                $mere = Sifec::savePersonne($request,"_mere","F",$mereUniqueString);
            }

            if($declarant == null)
            {
                $declarant = Sifec::savePersonne($request,"_declarant",$request->sexe_declarant,$declarantUniqueString);
            }

            if($enfant == null)
            {
                $enfant = Sifec::savePersonne($request,"_enfant",$request->sexe_enfant,$enfantUniqueString);
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
            $dn->code_declarant = $declarant->code_personne;
            $dn->code_enfant = $enfant->code_personne;
            $dn->code_pere = $pere->code_personne;
            $dn->code_mere = $mere->code_personne;
            $dn->personne_declaree = "Enfant normal";
            $dn->code_lieu_survenance = $request->lieu_survenance;
            $dn->code_user_institution  = Auth::user()->affectationActive()->cui;
            $dn->code_filiation = $request->filiation;
            $dn->code_situation_mat = $request->code_situation_matrimoniale;
            $dn->type_declaration = $request->type_declaration;

            $dn->formation_sanitaire_naissance = $request->formation_sanitaire_naissance;
            $dn->code_institution = Auth::user()->affectationActive()->code_institution;
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
            "message"=>"La fiche de maternité enregistrée avec succès"
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
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('naissance::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('naissance::edit');
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

    //envois de sms notification à la mere ou au parent tuteur
    public function sendNotification($id)
    {
        $fm = Declarationnaissance::find($id);

        if($fm == null){
            return response()->json([
                "code"=>"180",
                "message"=>"Aucune fiche de maternité trouvée pour ce code $id"
            ]);
        }

        try {

            $temp = config("sifec.sms.templates.actions.notification_fiche_maternite");
            $temp = str_replace(":mere",$fm->mere->nomcomplet(),$temp);
            $temp = str_replace(":formation_sanitaire",$fm->institutionUser->institution->lib_institution,$temp);
            $temp = str_replace(":date_naissance",date('d-m-Y', strtotime($fm->enfant->date_naissance)),$temp);

            // dispatch(new SendSmsJob("+242".$fm->mere->telephone_parent,$temp));
            SifecFacade::sendSms("+242".$fm->mere->telephone_parent,$temp);

            return response()->json([
                "code"=>"200",
                "message"=>"La notification a été envoyée au :".$fm->mere->telephone_parent
            ]);

        } catch (Exception $e) {
            Log::channel('sifec')->error($e->getMessage());
            return response()->json([
                "code"=>"180",
                "message"=> $e->getMessage()
            ]);
        }
    }
}
