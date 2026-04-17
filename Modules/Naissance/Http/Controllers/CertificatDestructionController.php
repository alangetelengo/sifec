<?php

namespace Modules\Naissance\Http\Controllers;

use App\Sifec\Sifec;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Modules\Naissance\Entities\Declarationnaissance;
use Modules\Naissance\Entities\MouvementNaissance;
use Modules\Naissance\Services\MouvementService;
use Modules\Notification\Notifications\DeclarationEnvoyeeCentreNotification;
use Modules\Notification\Services\NotificationService;
use Modules\Referentiel\Entities\Departement;
use Modules\Referentiel\Entities\Document;
use Modules\Referentiel\Entities\Filiation;
use Modules\Referentiel\Entities\LieuSurvenance;
use Modules\Referentiel\Entities\Localite;
use Modules\Referentiel\Entities\Nationalite;
use Modules\Referentiel\Entities\Personne;
use Modules\Referentiel\Entities\Profession;
use Modules\Referentiel\Entities\SituationMatrimoniale;
use Modules\Referentiel\Entities\TypeDocument;
use Spipu\Html2Pdf\Html2Pdf;

class CertificatDestructionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $affectationActive = (is_object($user) && method_exists($user, 'affectationActive')) ? $user->affectationActive() : null;

        $certificats = collect();
        $institution = $affectationActive ? $affectationActive->institution : null;
        if (! $institution || $institution->code_type_institution !== 'TPINS_0002') {
            flash()->error("Accès réservé aux agents du centre d'état civil.");

            return back();
        }
        $certificats = Declarationnaissance::where('type_declaration', "CERTIFICAT DE DESTRUCTION DE L'ACTE")
            ->where('code_user_institution', $affectationActive ? $affectationActive->cui : null)
            ->get();

        return view('naissance::certificat-destruction.index', compact('certificats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {

        $title = "Créer un certificat de destruction de l'acte de naissance";

        $type_declaration = "CERTIFICAT DE DESTRUCTION DE L'ACTE";
        $ageEnfant = 0;
        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite', 'TPLOC_0002')->Orwhere('code_type_localite', 'TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $lieuSurvenances = LieuSurvenance::all();
        $filiations = Filiation::all();
        $typedocuments = TypeDocument::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $communes = Localite::where('code_type_localite', 'TPLOC_0003')->Orwhere('code_type_localite', 'TPLOC_0002')->get();
        $arrondissements = Localite::where('code_type_localite', 'TPLOC_0004')->Orwhere('code_type_localite', 'TPLOC_0005')->get();
        $quartiers = Localite::where('code_type_localite', 'TPLOC_0007')->Orwhere('code_type_localite', 'TPLOC_0008')->get();
        $countries = collect(json_decode(file_get_contents(public_path('codes_pays.json'))));
        $departements = Departement::all();

        return view('naissance::declaration.create', compact('title', 'departements', 'countries', 'communes', 'arrondissements',
            'typedocuments', 'instructions', 'filiations', 'localites', 'professions',
            'nationalites', 'situationMatrimoniales', 'lieuSurvenances', 'quartiers',
            'type_declaration', 'ageEnfant'));

    }

    // public function store(Request $request)
    // {

    //     $rules = [
    //         "nom_enfant"=>["required"],
    //         "date_naissance_enfant"=>["required","date"],
    //         "lieu_naissance_enfant"=>["required","string"],
    //         "code_situation_matrimoniale"=>["required"],
    //         "sexe_enfant"=>["required"],
    //         "heure_naissance_enfant"=>["required","max:5","min:5"],
    //         "nombre_enfant"=>["required","numeric"]
    //     ];
    //     // Log::channel('sifec')->info($request->all());
    //     // return response()->json(["donnee"=>$request->all()]);

    //     $validator = Validator::make($request->all(),$rules);

    //     if($validator->fails()){
    //         return response()->json([
    //             "code"=>"150",
    //             "message"=>$validator->errors()
    //         ]);
    //     }

    //     // le pere doit avoir au moins 14 ans de plus que l'enfant
    //     // la mere doit avoir au moins 12 ans de plus que l'enfant

    //     $dateNaissancePere = Carbon::create($request->date_naissance_pere);
    //     $dateNaissanceEnfant = Carbon::create($request->date_naissance_enfant);
    //     $dateNaissanceMere = Carbon::create($request->date_naissance_mere);
    //     $differenceAgeEnfantPere = $dateNaissancePere->diffInYears($dateNaissanceEnfant);
    //     $differenceAgeEnfantMere = $dateNaissanceMere->diffInYears($dateNaissanceEnfant);

    //     if($differenceAgeEnfantPere < 14){
    //         return response()->json([
    //             "code"=>"99",
    //             "message"=>["age_pere"=>"La différence d'age entre père et enfant doit être supérieure ou égale à 14 ans"]
    //         ]);
    //     }

    //     if($differenceAgeEnfantMere < 12){
    //         return response()->json([
    //             "code"=>"99",
    //             "message"=>["age_mere"=>"La différence d'age entre mère et enfant doit être supérieure ou égale à 12 ans"]
    //         ]);
    //     }

    //     // $pereUniqueString = Sifec::uniqueString($request,"_pere","M");
    //     // $pere = Sifec::savePersonne($request,"_pere","M",$pereUniqueString);

    //     // Log::channel('sifec')->info($request->code_localite_pere);
    //     // return $request->code_localite_pere;

    //     DB::beginTransaction();

    //     try{

    //         $pereUniqueString = Sifec::uniqueString($request,"_pere","M");
    //         $pc = Personne::where("personne_string",$pereUniqueString)->first();

    //         $mereUniqueString = Sifec::uniqueString($request,"_mere","F");
    //         $mc = Personne::where("personne_string",$mereUniqueString)->first();

    //         $enfantUniqueString = Sifec::uniqueString($request,"_enfant",$request->sexe_enfant);
    //         $ec = Personne::where("personne_string",$enfantUniqueString)->first();

    //         $declarantUniqueString = Sifec::uniqueString($request,"_declarant",$request->sexe_declarant);
    //         $declc = Personne::where("personne_string",$declarantUniqueString)->first();

    //         // Log::channel('sifec')->info($request->all());

    //         // dd($request->all());

    //         $ec = Personne::where("personne_string",$enfantUniqueString)->first();
    //         if($ec != null){

    //             return response()->json([
    //                 "code"=>"99",
    //                 "message"=>["enfant_exist"=>"La déclaration de cet enfant existe déjà dans le système"]
    //             ]);
    //         }

    //         $pere = Personne::where("personne_string",$pereUniqueString)->first();
    //         $mere = Personne::where("personne_string",$mereUniqueString)->first();
    //         $declarant = Personne::where("personne_string",$declarantUniqueString)->first();

    //         if($pere == null){
    //             $pere = Sifec::savePersonne($request,"_pere","M",$pereUniqueString);

    //         }
    //         if($mere == null){
    //             $mere = Sifec::savePersonne($request,"_mere","F",$mereUniqueString);
    //         }
    //         if($declarant == null)
    //         {

    //             if($request->filiation != "FIL_0001" && $request->filiation != "FIL_0001")
    //             {
    //                 $declarant = Sifec::savePersonne($request,"_declarant",$request->sexe_declarant,$declarantUniqueString);
    //             }
    //         }

    //         $enfant = Sifec::savePersonne($request,"_enfant",$request->sexe_enfant,$enfantUniqueString);

    //         $dn = new Declarationnaissance;
    //         $codedn = Sifec::genererCodeUniqueReferentiel($dn,"code_declaration_naissance",8,"CDN_");
    //         $dn->code_declaration_naissance = $codedn;
    //         $dn->nombre_enfant = $request->nombre_enfant;
    //         if ($request->date_heure_declaration != '') {
    //             $dn->date_heure_declaration = $request->date_heure_declaration.' 00:00';
    //         }
    //         $dn->date_heure_naissance = $request->date_naissance_enfant." ".$request->heure_naissance_enfant.":00" ;
    //         $dn->type_declarant = "Personne physique";
    //         if($request->filiation == "FIL_0001"){
    //             $dn->code_declarant = $pere->code_personne;
    //         }
    //         if($request->filiation == "FIL_0002"){
    //             $dn->code_declarant = $mere->code_personne;
    //         }
    //         if($request->filiation != "FIL_0001" && $request->filiation != "FIL_0002"){
    //             $dn->code_declarant = $declarant->code_personne;
    //         }
    //         $dn->code_enfant = $enfant->code_personne;
    //         $dn->code_pere = $pere->code_personne;
    //         $dn->code_mere = $mere->code_personne;
    //         $dn->personne_declaree = "Enfant normal";
    //         // $dn->code_lieu_survenance = "LSURV_0001";
    //         $dn->code_lieu_survenance = $request->code_lieu_survenance;
    //         $dn->code_user_institution  = Auth::user()->affectationActive()->cui;
    //         $dn->code_filiation = $request->filiation;
    //         $dn->code_situation_mat = $request->code_situation_matrimoniale;
    //         $dn->type_declaration = $request->type_declaration;
    //         if ($request->type_declaration != 'DECLARATION DE NAISSANCE') {
    //             $dn->numero_certificat = Sifec::genererCodeUniqueReferentiel($dn,"numero_certificat",4,"");
    //         }
    //         $dn->formation_sanitaire_naissance = $request->formation_sanitaire_naissance; //ajout alange
    //         $dn->save();

    //         $transaction = new MouvementNaissance();
    //         $transaction->code_mouvement_naissance = Sifec::genererCodeUniqueReferentiel($transaction,"code_mouvement_naissance",4,"MDN_");
    //         $transaction->statut = "En cours";
    //         $transaction->code_declaration_naissance = $dn->code_declaration_naissance;
    //         $transaction->cui = Auth::user()->affectationActive()->cui;
    //         $transaction->save();

    //         DB::commit();

    //         return response()->json([
    //             "code"=>"200",
    //             "message"=>"La déclaration enregistrée avec succès"
    //         ]);

    //         }catch(Exception $e){
    //             DB::rollBack();
    //             Log::channel("sifec")->error($e->getMessage());
    //             return response()->json([
    //                 "code"=>"99",
    //                 "message"=>["error" =>$e->getMessage()]
    //             ]);
    //         }

    // }

    // public function savePersonne(Request $request,$sufix,$sexe) : Personne
    // {
    //     // Informations du déclarant

    //     $personne = new Personne;
    //     //code

    //     $codedeclarant = Sifec::genererCodeUniqueReferentiel($personne,"code_personne",8,"PRS_");

    //     $personne->code_personne = $codedeclarant;
    //     $personne->nom = $request->input("nom".$sufix);
    //     $personne->prenom = $request->input("prenom".$sufix);
    //     $personne->date_naissance = $request->input("date_naissance".$sufix);
    //     $personne->lieu_naissance = $request->input("lieu_naissance".$sufix);
    //     $personne->adresse = $request->input("domicile".$sufix);
    //     $personne->code_profession = $request->input("profession".$sufix);
    //     $personne->code_nationalite = $request->input("code_nationalite".$sufix);
    //     $personne->niveau_instruction = $request->input("niveau_instruction".$sufix);
    //     $personne->telephone = $request->input("telephone".$sufix);
    //     $personne->sexe = $sexe;

    //     //on verifie si la personne existe deja
    //     $persExistance = Personne::where(["nom"=>$personne->nom,"prenom"=>$personne->prenom,"telephone"=>$personne->telephone,"sexe"=>$personne->sexe])->get();

    //     $i=0;
    //     if($sufix != "_enfant")
    //     {

    //         foreach ($persExistance as $pers)
    //         {
    //             $personne=$pers;

    //           //  $personne->code_personne=$pers['code_personne'];
    //             $i++;
    //         }
    //     }

    //     //si la personne n'existe pas on enregistre
    //     if($i==0)
    //     {
    //         $personne->save();
    //     }

    //     //Ajouter le document
    //     if($sufix != "_enfant")
    //     {
    //         if($request->input("code_type_document".$sufix) != null && $request->input("numero_document".$sufix))
    //         {
    //             $dop = new Document;
    //             $code = Sifec::genererCodeUniqueReferentiel($dop,"code_document",8,"DOC_");
    //             $dop->code_document = $code;
    //             $dop->numero_document = $request->input("numero_document".$sufix);
    //             $dop->code_personne = $personne->code_personne;
    //             $dop->code_type_document = $request->input("code_type_document".$sufix);
    //             $dop->save();
    //         }
    //     }

    //     return $personne;
    // }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {

        $certificat = Declarationnaissance::with(Declarationnaissance::relationsPourEagerLoadCertificatDetail())->find($id);
        if ($certificat == null) {
            flash()->error('Certificat indisponible');

            return back();
        }

        return view('naissance::certificat-destruction.show', compact('certificat'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('naissance::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function etat($id)
    {
        $certificat = Declarationnaissance::find($id);

        if ($certificat == null) {
            flash()->error('Certificat indisponible');

            return back();
        }

        view()->share('tester', [], 'Vincent');
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $verificationUrl = URL::signedRoute('verification.declaration', ['code' => $certificat->code_declaration_naissance]);
        $qrCode = $verificationUrl;
        $html2pdf->writeHTML(view('naissance::etats.certificat_destruction', compact('certificat', 'qrCode'))->render());

        return $html2pdf->output($certificat->code_declaration_naissance.'.pdf');

    }

    public function envoyerAuTribunal(Request $request, MouvementService $mouvementService, NotificationService $notificationService)
    {
        $certificat = Declarationnaissance::findOrFail($request->code_declaration_naissance);
        $user = Auth::user();
        $tribunal = $certificat->institution->institutionParent;
        $codeMouvement = 'MOUV_0006'; // Code mouvement pour envoyer un certificat
        $statut = 'Envoyée';
        $observation = $request->observation ?? null;

        DB::beginTransaction();
        try {
            // Utilise la méthode générique pour l'envoi
            [$success, $message] = $mouvementService->envoyerDeclaration(
                $user,
                $certificat,
                $codeMouvement,
                $statut,
                $observation
            );
            if (! $success) {
                DB::rollBack();

                return response()->json([
                    'code' => '90',
                    'message' => $message,
                ]);
            }

            // Utilisation du NotificationService pour notifier tous les agents du tribunal
            try {
                $notificationService->notifierAgentsInstitution(
                    $tribunal->code_institution,
                    new DeclarationEnvoyeeCentreNotification(
                        $certificat,
                        $tribunal,
                        'envoyée',
                        'Un certificat de destruction de l\'acte de naissance a été envoyé à votre institution.'
                    )
                );
            } catch (Exception $e) {
                DB::rollBack();
                Log::channel('sifec')->info($e->getMessage());

                return response()->json([
                    'code' => '90',
                    'message' => 'Erreur lors de la notification aux agents du tribunal : '.$e->getMessage(),
                ]);
            }

            DB::commit();

            return response()->json([
                'code' => '200',
                'message' => 'Certificat envoyé au tribunal et notification envoyée aux agents du tribunal.',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->info($e->getMessage());

            return response()->json([
                'code' => '90',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
