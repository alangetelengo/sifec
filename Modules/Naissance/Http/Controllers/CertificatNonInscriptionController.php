<?php

namespace Modules\Naissance\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Sifec\Sifec;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Referentiel\Entities\Document;
use Modules\Referentiel\Entities\Localite;
use Modules\Referentiel\Entities\Personne;
use Modules\Referentiel\Entities\Filiation;
use Illuminate\Contracts\Support\Renderable;
use Modules\Referentiel\Entities\Profession;
use Modules\Referentiel\Entities\Departement;
use Modules\Referentiel\Entities\Nationalite;
use Modules\Referentiel\Entities\TypeDocument;
use Modules\Referentiel\Entities\Arrondissement;
use Modules\Referentiel\Entities\LieuSurvenance;
use Modules\Naissance\Entities\MouvementNaissance;
use Modules\Naissance\Entities\Declarationnaissance;
use Modules\Naissance\Entities\CertificatDestruction;
use Modules\Referentiel\Entities\SituationMatrimoniale;

class CertificatNonInscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        // $certificats = Declarationnaissance::where('type_declaration','CERTIFICAT DE NON INSCRIPTION')->get();
       // $certificats = Declarationnaissance::where('type_declaration',"CERTIFICAT DE NON INSCRIPTION")->get();
        $user = Auth::user();
        $certificats = $user->institution()->declarationsNaissances();

        return view('naissance::certificat-non-inscription.index', compact('certificats'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $dateNaissance = $request->date_naissance_enfant;
        $dateNaissanceConvertis = Carbon::create($dateNaissance);
        $date = date("Y-m-d");
        $dateNaissanceNow = Carbon::create($date);
        $ageEnfant = $dateNaissanceConvertis->diffInYears($dateNaissanceNow);
        $nbreJourNaissance = $dateNaissanceConvertis->diffInDays($dateNaissanceNow);

        $title = "";
        $type_declaration = "";

        if($nbreJourNaissance < 30){
            $title = "Créer une déclaration de naissance";
            $type_declaration = "DECLARATION DE NAISSANCE";
        }

        if($nbreJourNaissance > 30){
            $title = "Créer un certificat de non inscription";
            $type_declaration = "CERTIFICAT DE NON INSCRIPTION";
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


        return view('naissance::declaration.create',compact("title","dateNaissance","departements","ageEnfant","countries","arrondissement","typedocuments","instructions","filiations","localites","professions","nationalites","situationMatrimoniales","lieuSurvenances","quartierVillages","type_declaration"));
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
            "lieu_naissance_enfant"=>["required","string"],
            "code_situation_matrimoniale"=>["required"],
            "sexe_enfant"=>["required"],
            "heure_naissance_enfant"=>["required","max:5","min:5"],
            // "lieu_survenance"=>["required","string"],
            "nombre_enfant"=>["required","numeric"]
        ];



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

                $ec = Personne::where("personne_string",$enfantUniqueString)->first();
                if($ec != null){

                    return response()->json([
                        "code"=>"99",
                        "message"=>["enfant_exist"=>"L'enfant existe déjà dans le système"]
                    ]);
                }


                $pere = Personne::where("personne_string",$pereUniqueString)->first();
                $mere = Personne::where("personne_string",$mereUniqueString)->first();
                $declarant = Personne::where("personne_string",$declarantUniqueString)->first();

                if($pere == null){
                    $pere = Sifec::savePersonne($request,"_pere","M",$pereUniqueString);
                }
                if($mere == null){
                    $mere = Sifec::savePersonne($request,"_mere","F",$mereUniqueString);
                }
                if($declarant == null)
                {

                    if($request->filiation != "FIL_0001" && $request->filiation != "FIL_0001")
                    {
                        $declarant = Sifec::savePersonne($request,"_declarant",$request->sexe_declarant,$declarantUniqueString);

                    }
                }

                $enfant = Sifec::savePersonne($request,"_enfant",$request->sexe_enfant,$enfantUniqueString);

                $dn = new Declarationnaissance;
                $codedn = Sifec::genererCodeUniqueReferentiel($dn,"code_declaration_naissance",8,"CDN_");
                $dn->code_declaration_naissance = $codedn;
                $dn->nombre_enfant = $request->nombre_enfant;
                $dn->date_heure_declaration = $request->date_heure_declaration;
                $dn->date_heure_naissance = $request->date_naissance_enfant." ".$request->heure_naissance_enfant.":00" ;
                $dn->type_declarant = "Personne physique";

                if($request->filiation == "FIL_0001")
                {
                    $dn->code_declarant = $pere->code_personne;
                }

                if($request->filiation == "FIL_0002")
                {
                    $dn->code_declarant = $mere->code_personne;
                }

                if($request->filiation != "FIL_0001" && $request->filiation != "FIL_0002")
                {
                    $dn->code_declarant = $declarant->code_personne;
                }

                $dn->code_enfant = $enfant->code_personne;
                $dn->code_pere = $pere->code_personne;
                $dn->code_mere = $mere->code_personne;
                $dn->personne_declaree = "Enfant normal";
                $dn->code_lieu_survenance = "LSURV_0001";
                $dn->code_user_institution  = Auth::user()->affectationActive()->cui;
                $dn->code_filiation = $request->filiation;
                $dn->code_situation_mat = $request->code_situation_matrimoniale;
                $dn->type_declaration = "CERTIFICAT DE NON INSCRIPTION";
                $dn->date_heure_declaration = Carbon::now();
                $dn->numero_certificat = Sifec::genererCodeUniqueReferentiel($dn,"numero_certificat",4,"");
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
                    "message"=>"Le certificat de non inscription est enregistré avec succès"
                ]);

            }catch(Exception $e){
                    DB::rollBack();
                    return response()->json([
                        "code"=>"99",
                        "message"=>["error" =>$e->getMessage()]
                    ]);
            }


    }

    public function savePersonne(Request $request,$sufix,$sexe) : Personne{

        // Informations du déclarant
        $personne = new Personne;
        //code
        $codedeclarant = Sifec::genererCodeUniqueReferentiel($personne,"code_personne",8,"PRS_");

        $personne->code_personne = $codedeclarant;
        $personne->nom = $request->input("nom".$sufix);
        $personne->prenom = $request->input("prenom".$sufix);
        $personne->date_naissance = $request->input("date_naissance".$sufix);
        $personne->lieu_naissance = $request->input("lieu_naissance".$sufix);
        $personne->adresse = $request->input("domicile".$sufix);
        $personne->code_profession = $request->input("profession".$sufix);
        $personne->code_nationalite = $request->input("code_nationalite".$sufix);
        $personne->niveau_instruction = $request->input("niveau_instruction".$sufix);
        $personne->telephone = $request->input("telephone".$sufix);
        $personne->sexe = $sexe;
        $personne->save();

        //Ajouter le document
        if($sufix != "_enfant"){
            if($request->input("code_type_document".$sufix) != null && $request->input("numero_document".$sufix)){
                $dop = new Document;
                $code = Sifec::genererCodeUniqueReferentiel($dop,"code_document",8,"DOC_");
                $dop->code_document = $code;
                $dop->numero_document = $request->input("numero_document".$sufix);
                $dop->code_personne = $personne->code_personne;
                $dop->code_type_document = $request->input("code_type_document".$sufix);
                $dop->save();
            }
        }


        return $personne;
    }

    public function etat($id)
    {
        // $duplicata = Duplicata::find($id);
        $certificat = Declarationnaissance::find($id);

        $dateNaissEnfant = Carbon::create($certificat->enfant->date_naissance);
        $dateNow = Carbon::create(date("Y-m-y"));
        $ageEnfant = $dateNow->diffInYears($dateNaissEnfant);

        if($certificat == null){
            toastr()->error("Certificat indisponible");
            return back();
        }

        DB::beginTransaction();

       try {
            view()->share("tester", "Vincent");
            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML(view('naissance::etats.certificat_non_inscription', compact("certificat","ageEnfant"))->render());
            DB::commit();

            return $html2pdf->output($certificat->code_certif_dest.".pdf");

       } catch (Exception $e) {
            DB::rollBack();
            toastr()->error($e->getMessage());
            return back();
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


    public function update(Request $request, $id)
    {

        $dn = Declarationnaissance::find($id);
        if ($dn == NULL) {
            toastr()->error("Déclaration non retrouvé");
            return back();
        }

        try {
            $numcert = Sifec::genererCodeUniqueReferentiel($dn,"numero_certificat",4,"");
            $admin = Declarationnaissance::find($id)->update(['numero_certificat' => $numcert]);
            if (!$admin) {
                toastr()->error("Erreur de création du certificat");
                return back();
            }

            toastr()->success("Certificat de non inscription générer");
            return back();

        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back();
        }

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
