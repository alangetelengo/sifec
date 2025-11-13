<?php

namespace Modules\Deces\Http\Controllers;

use Exception;
use App\Sifec\Sifec;
use App\Sifec\SifecFacade;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use App\Models\InstitutionUser;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Deces\Entities\ActeDeces;
use Illuminate\Support\Facades\Validator;
use Modules\Notification\Jobs\SendSmsJob;
use Modules\Deces\Entities\MouvementDeces;
use Modules\Referentiel\Entities\Registre;
use Modules\Deces\Services\OtpDecesService;
use Illuminate\Contracts\Support\Renderable;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Deces\Services\ActeDecesService;
use Modules\Deces\Services\MouvementService;
use Modules\Referentiel\Entities\RetraitActe;
use Modules\Referentiel\Entities\ActeRegistre;
use Modules\Referentiel\Entities\FeuilletRegistre;
use Modules\Notification\Jobs\ValidationacteDecesJob;
use Modules\Notification\Services\NotificationService;
use Modules\Referentiel\Entities\TypeDeclarationDeces;
use Modules\Notification\Notifications\ActeDecesAValiderNotification;


class ActeDecesController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $affectation = $user->affectationActive();
        $institution = $affectation->institution;

        // Documents à contrôler (cec_approuver = 'NON')
        $documentsAControler = DeclarationDeces::where(function($query) use ($institution) {
                                    $query->where("code_institution_destinataire", $institution->code_institution)
                                          ->orWhere("code_institution", $institution->code_institution);
                                })
                                ->where("cec_approuver", "NON")
                                ->where(function($query) {
                                    $query->where("type_declaration", "!=", "CERTIFICAT DE NON INSCRIPTION")
                                          ->orWhere(function($subQuery) {
                                              $subQuery->where("type_declaration", "CERTIFICAT DE NON INSCRIPTION")
                                                       ->where("tribunal_approuver", "OUI")
                                                       ->where(function($requisitionQuery) {
                                                           $requisitionQuery->whereHas('requisition', function($reqQuery) {
                                                                               $reqQuery->where('statut', 'envoyée');
                                                                           })
                                                                           ->orWhereHas('jugement', function($jugQuery) {
                                                                               $jugQuery->where('statut', 'envoyée');
                                                                           });
                                                       });
                                          });
                                })
                                ->get();

        // Gestion des actes (cec_approuver = 'OUI')
        $actesGestion = DeclarationDeces::where(function($query) use ($institution) {
                                    $query->where("code_institution_destinataire", $institution->code_institution)
                                          ->orWhere("code_institution", $institution->code_institution);
                                })
                                ->where("cec_approuver", "OUI")->get();

        $registre = \Modules\Referentiel\Entities\Registre::where("cui", $affectation->cui)
            ->where("statut", 1)
            ->where("code_type_registre", "TPRG_0004")
            ->first();

        // Statistiques documents (comme dans naissance)
        $statistiquesDocuments = method_exists($institution, 'getStatistiquesDocuments')
            ? $institution->getStatistiquesDocuments('deces')
            : [];

        return view(
            'deces::acte.index', compact(
                "documentsAControler",
                "actesGestion",
                "registre",
                "statistiquesDocuments"
            )
        );
    }

    public function displayActe($id)
    {

        $acte = ActeDeces::where("code_declaration_deces",$id)->first();
        $codefonction = $acte->institutionUser->fonction->code_fonction;
        $nomcomplet = "";
        $libfonction = "";

        //agent mairie
        if($codefonction == "FONC_0004" || $codefonction == "FONC_0017" || $codefonction == "FONC_0018"){
            $f = $acte->institutionUser->where("code_fonction","FONC_0002")->first();
            $nomcomplet = $f->user->personne->nomcomplet();
            $libfonction = $f->fonction->lib_fonction;
        }
        //agent pompe funebre
        if($codefonction == "FONC_0005"){

            $f = $acte->institutionUser->where("code_fonction","FONC_0012")->first();
            $nomcomplet = $f->user->personne->nomcomplet();
            $libfonction = $f->fonction->lib_fonction;
        }

        if($acte == null){
            toastr()->error("Vous ne pouvez pas généré un acte de décès");
            return back();
        }

        DB::beginTransaction();

       try {
        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('deces::etats.acte', compact("acte","nomcomplet","libfonction"))->render());
        DB::commit();

        return $html2pdf->output($acte->code_acte_deces.".pdf");

       } catch (Exception $e) {
            DB::rollBack();
            toastr()->error($e->getMessage());
            return back();
       }
    }



    public function displayCopie($id)
    {
        $acte = ActeDeces::where("code_declaration_deces",$id)->first();

        if($acte == null){
            toastr()->error("Vous ne pouvez pas généré un acte de décès");
            return back();
        }

        DB::beginTransaction();

       try {
        view()->share("tester", "Vincent");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('deces::etats.acte_deces_copie', compact("acte"))->render());
        DB::commit();

        return $html2pdf->output($acte->code_acte_deces.".pdf");

       } catch (Exception $e) {
            DB::rollBack();
            toastr()->error($e->getMessage());
            return back();
       }
    }


    public function displayDuplicata($id)
    {
        $acte = ActeDeces::where("code_acte_deces",$id)->first();

        if($acte == null){
            toastr()->error("Vous ne pouvez pas généré un acte de décès");
            return back();
        }

        DB::beginTransaction();

       try {
        view()->share("tester", "Vincent");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('deces::etats.duplicata_deces', compact("acte"))->render());
        DB::commit();

        return $html2pdf->output($acte->code_acte_deces.".pdf");

       } catch (Exception $e) {
            DB::rollBack();
            toastr()->error($e->getMessage());
            return back();
       }
    }

    public function searchActe()
    {
        $nom = request('nom') ?  "%".request('nom')."%"  : "";
        $prenom = request('prenom') ?  "%".request('prenom')."%" : "";
        $lieu = request('lieu') ? "%".request('lieu')."%":"";
        $personnes = DB::select("SELECT dc.code_declaration_deces, ip.nom,ip.prenom,dc.lieu_deces,ti.lib_institution,ad.code_acte_deces FROM tr_identification_personne ip JOIN t_declaration_deces dc ON ip.code_personne = dc.code_defunt JOIN t_acte_deces ad ON dc.code_declaration_deces = ad.code_declaration_deces JOIN tr_ins_user iu ON ad.cui = iu.cui JOIN tr_institution ti ON iu.code_institution = ti.code_institution WHERE ip.nom LIKE ? OR ip.prenom LIKE ? OR dc.lieu_deces LIKE ?",[$nom,$prenom,$lieu]);
        // dd($personnes);
        return response()->json([
           "personnes" => $personnes
        ]);
    }


    public function decesApprouver($id)
    {
        $acte = ActeDeces::find($id);

        if($acte==null){
            toastr()->error("Vous ne pouvez pas signé cet acte de décès");
            return back();
        }

        if ( Gate::allows("module.acteDeces.signature")) {

           try{
                $acte->approbation_pompe_funebre = 1;
                $acte->signature_pompe_funebre =  Auth::user()->personne->signature;
                $acte->save();


                $otp = substr(time(),2);

                $temp = config("sifec.sms.templates.actions.acte_deces");
                $temp = str_replace(":declarant",$acte->declaration->declarant->nomcomplet(),$temp);
                $temp = str_replace(":code_acte_deces",$acte->code_acte_deces,$temp);
                toastr()->success("Acte signé avec succès");

                dispatch(new SendSmsJob($acte->declaration->declarant->telephone,$temp));

                return back();

            }catch(Exception $e){
                toastr()->error($e->getMessage());
                return back();
            }

        }


        toastr()->error("Vous n'avez pas la permission de signer cet acte de décès");
        return back();

    }


    public function apitest(){
        $param = request("id");

        $actedeces = ActeDeces::find($param);
        if($actedeces != NULL){
            return [
                "code"=>"200",
                "message"=>"Acte Valide",
                "defunt"=>$actedeces->declaration->defunt->nom." ".$actedeces->declaration->defunt->prenom,
                "declarant"=>$actedeces->declaration->declarant->nom." ".$actedeces->declaration->declarant->prenom,
                "date_deces"=>$actedeces->declaration->date_heure_deces,
                "codeacte"=>$actedeces
            ];
        }else{
            return response()->json([
                "code"=>"201",
                "message"=>"Acte Invalide",
                "donnees"=> null,
                "codeacte"=>$actedeces
            ]);
        }
    }


    public function sendOtp(Request $request, OtpDecesService $otpService)
    {
        $code = $request->code_declaration_deces;
        $ad = ActeDeces::where("code_declaration_deces", $code)->get();
        $user = Auth::user();
        if ($ad->count() == 0) {
            return response()->json([
                "code"=>"180",
                "message"=>"Aucun acte trouvé"
            ]);
        }
        try {
            $otpService->envoyerOtpValidationActes($user, $ad);
            return response()->json([
                "code"=>"200",
                "message"=>"SMS envoyé avec succès"
            ]);
        } catch (Exception $e) {
            return response()->json([
                "code"=>"181",
                "message"=>["error" =>$e->getMessage()]
            ]);
        }
    }

    public function sendOtpBulk(Request $request, OtpDecesService $otpService)
    {
        $codes = $request->codes;
        $ad = ActeDeces::whereIn("code_declaration_deces", $codes)->get();
        $user = Auth::user();
        if ($ad->count() == 0) {
            return response()->json([
                "code"=>"180",
                "message"=>"Aucun acte trouvé"
            ]);
        }
        try {
            $otpService->envoyerOtpValidationActes($user, $ad);
            return response()->json([
                "code"=>"200",
                "message"=>"SMS envoyé avec succès"
            ]);
        } catch (Exception $e) {
            return response()->json([
                "code"=>"181",
                "message"=>["error" =>$e->getMessage()]
            ]);
        }
    }

    public function validateOtp(Request $request, OtpDecesService $otpService)
    {
        $rules = [
            "otp_approbation_pompe_funebre"=>["required","numeric"],
            "code_declaration_deces"=>["required","string"]
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json([
                "code"=>"180",
                "message"=>"Aucun acte trouvé pour ce code"
            ]);
        }
        if(!Gate::allows("module.acteDeces.signature")){
            return response()->json([
                "code"=>"181",
                "message"=>["error" => "Vous n'êtes pas autorisé à valider un acte de décès"]
            ]);
        }
        $cdn = $request->code_declaration_deces;
        $otp = $request->otp_approbation_pompe_funebre;
        [$ok, $result] = $otpService->validerOtpActes([$cdn], $otp);
        if (!$ok) {
            return response()->json([
                "code"=>"183",
                "message"=>["error" => $result]
            ]);
        }
        // Notification après validation OTP (single)
        foreach ($result as $ad) {
            $numeroActe = $ad->code_acte_deces;
            $codeInstitution = $ad->declaration->institution->code_institution;
            NotificationService::notifierAgentsInstitution(
                $codeInstitution,
                new ActeDecesAValiderNotification(
                    $numeroActe,
                    "Acte de décès produit avec succès"
                )
            );
        }
        return response()->json([
            "code"=>"200",
            "message"=>"Acte de décès validé avec succès"
        ]);
    }

    public function validateOtpBulk(Request $request, OtpDecesService $otpService)
    {
        $rules = [
            "otp_approbation_pompe_funebre"=>["required","numeric"],
            "codes"=>["required"]
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()) {
            return response()->json([
                "code"=>"180",
                "message"=>"Aucun acte trouvé pour ce code"
            ]);
        }
        if(!Gate::allows("module.acteDeces.signature")){
            return response()->json([
                "code"=>"181",
                "message"=>"Vous n'êtes pas autorisé à valider un acte de deces"
            ]);
        }
        $codes = $request->codes;
        $otp = $request->otp_approbation_pompe_funebre;
        [$ok, $result] = $otpService->validerOtpActes($codes, $otp);
        if (!$ok) {
            return response()->json([
                "code"=>"183",
                "message"=>["error" => $result]
            ]);
        }
        // Notification après validation OTP (bulk)
        foreach ($result as $ad) {
            $numeroActe = $ad->code_acte_deces;
            $codeInstitution = $ad->declaration->institution->code_institution;
            NotificationService::notifierAgentsInstitution(
                $codeInstitution,
                new ActeDecesAValiderNotification(
                    $numeroActe,
                    "Acte de décès produit avec succès"
                )
            );
        }
        return response()->json([
            "code"=>"200",
            "message"=>["Actes des décès validés avec succès"]
        ]);
    }


    public function generateActe(Request $request, ActeDecesService $service, MouvementService $mouvementService)
    {

        $regle = [
            "code_declaration_deces"=>["required","string","unique:t_acte_deces"]
        ];

        $validator = Validator::make($request->all(),$regle);

        if($validator->fails()){
            return response()->json([
                "code"=>"180",
                "message"=>["error"=>$validator->errors()]
            ]);
        }

        $user = Auth::user();
        $dd = DeclarationDeces::find($request->code_declaration_deces);
        $rd = Registre::where("cui",$user->affectationActive()->cui)->where("code_type_registre","TPRG_0004")->first();

        if($dd == null){
            return response()->json([
                "code"=>"180",
                "message"=>["error"=>"Cette déclaration de décès n'est pas reconnue"]
            ]);
        }

        if($rd == null){
            return response()->json([
                "code"=>"181",
                "message"=>["error"=>"Aucun registre disponible"]
            ]);
        }

        if($rd->statut == 0){
            return response()->json([
                "code"=>"182",
                "message"=>["error"=>"Ce registre est déjà clôturé"]
            ]);
        }

        if($rd->nombre_acte_prevu == $rd->nombre_acte_transcrit){
            return response()->json([
                "code"=>"183",
                "message"=>["error"=>"Ce registre a déjà atteint le nombre d'actes prévu"]
            ]);
        }

        if( ! Gate::allows("module.acteDeces.generate")){
            return response()->json([
                "code"=>"180",
                "message"=>["error"=>"Vous n'êtes pas autorisé à effectuer cette opération"]
            ]);
        }

        DB::beginTransaction();
        try{
            $service->genererActe($dd, $rd, $user);

            $mouvementService->ajouterEvenementActe($user, $dd, 'attente_approbation');
            // Notifier les officiers de l'état civil de la disponibilité de l'acte à valider
            $codeInstitutionCentre = $user->affectationActive()->institution->code_institution;
            $numeroActe = $dd->acte ? $dd->acte->code_acte_deces : null;
            if($numeroActe) {
                // Notification centralisée via le module Notification
                NotificationService::notifierAgentsInstitution(
                $codeInstitutionCentre,
                new \Modules\Notification\Notifications\ActeDecesAValiderNotification(
                    $numeroActe,
                    "Acte de décès généré et en attente de la signature de l'officier d'état civil"
                ));
            }

            DB::commit();
            return response()->json([
                "code"=>"200",
                "message"=>["reponse"=>"Acte décès généré avec succès"]
            ]);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                "code"=>"201",
                "message"=>["error"=> $e->getMessage()]
            ]);
        }
    }

    public function generateActeBulk(Request $request, ActeDecesService $service)
    {
        $codes = $request->codes;
        $user = Auth::user();
        $dd = DeclarationDeces::whereIn("code_declaration_deces",$codes)->get();
        $rd = $user->affectationActive()->institution->registres()->where("statut",1)
                ->where("code_type_registre","TPRG_0004")
                ->first();

        if($dd->count() == 0)
        {
            return response()->json([
                "code"=>"180",
                "message"=>["error"=>"Aucune déclaration à générer"]
            ]);
        }

        if( ! Gate::allows("module.acteDeces.generate")){
            return response()->json([
                "code"=>"181",
                "message"=>["error"=>"Vous n'êtes pas autorisé à générer un acte"]
            ]);
        }

        if($rd == null){
            return response()->json([
                "code"=>"182",
                "message"=>["error"=>"Aucun registre disponible"]
            ]);
        }

        if($rd->statut == 0){
            return response()->json([
                "code"=>"183",
                "message"=>["error"=>"Ce registre est déjà clôturé"]
            ]);
        }

        if($rd->nombre_acte_prevu == $rd->nombre_acte_transcrit){
            return response()->json([
                "code"=>"184",
                "message"=>["error"=>"Ce registre a déjà atteint le nombre d'actes prévu"]
            ]);
        }

        // Limiter le nombre d'actes à générer si le registre n'a pas assez de place
        $regResteplace = $rd->nombre_acte_prevu - $rd->nombre_acte_transcrit;
        if ($regResteplace < count($codes)) {
            $dd = DeclarationDeces::whereIn('code_declaration_deces', $codes)->take($regResteplace)->get();
        }

         DB::beginTransaction();
        try {
            $mouvementService = new MouvementService();
            foreach ($dd as $d) {
                $service->genererActe($d, $rd, $user);
                $mouvementService->ajouterEvenementActe($user, $d, 'attente_approbation');
                // Notifier les officiers de l'état civil pour chaque acte généré
                $codeInstitutionCentre = $user->affectationActive()->institution->code_institution;
                $numeroActe = $d->acte ? $d->acte->code_acte_deces : null;
                if($numeroActe) {

                    // Notification centralisée via le module Notification
                    NotificationService::notifierAgentsInstitution(
                    $codeInstitutionCentre,
                    new \Modules\Notification\Notifications\ActeDecesAValiderNotification(
                        $numeroActe,
                        "Acte de décès généré et en attente de la signature de l'officier d'état civil"
                    ));
                }
            }
            DB::commit();
            return response()->json([
                'code' => '200',
                'message' => ['reponse' => 'Actes des décès générés avec succès']
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error($e->getMessage());
            return response()->json([
                'code' => '201',
                'message' => ['error' => $e->getMessage()]
            ]);
        }
    }


    public function etatlistedeces()
    {
        $dated = request('dated');
        $datef = request('datef');

        $listes = "";

        if ($dated == null && $datef == null) {
            $listes = DB::select("SELECT * FROM t_acte_deces
            JOIN t_declaration_deces ON t_declaration_deces.code_declaration_deces = t_acte_deces.code_declaration_deces
            JOIN tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_deces.code_defunt
            JOIN tr_lieu_survenance ON tr_lieu_survenance.code_lieu_survenance = t_declaration_deces.code_lieu_survenance
            ORDER BY tr_identification_personne.nom ASC
            ");
        }elseif ($dated != null && $datef == null) {
            $listes = DB::select("SELECT * FROM t_acte_deces
            JOIN t_declaration_deces ON t_declaration_deces.code_declaration_deces = t_acte_deces.code_declaration_deces
            JOIN tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_deces.code_defunt
            JOIN tr_lieu_survenance ON tr_lieu_survenance.code_lieu_survenance = t_declaration_deces.code_lieu_survenance
             WHERE date(t_declaration_deces.date_heure_deces) BETWEEN '".$dated."' AND '".$dated."'
             ORDER BY tr_identification_personne.nom ASC");
        }elseif ($dated == null && $datef != null) {
            $listes = DB::select("SELECT * FROM t_acte_deces
            JOIN t_declaration_deces ON t_declaration_deces.code_declaration_deces = t_acte_deces.code_declaration_deces
            JOIN tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_deces.code_defunt
            JOIN tr_lieu_survenance ON tr_lieu_survenance.code_lieu_survenance = t_declaration_deces.code_lieu_survenance
            WHERE date(t_declaration_deces.date_heure_deces) BETWEEN '".$datef."' AND '".$datef."'
            ORDER BY tr_identification_personne.nom ASC");
        }elseif ($dated != null && $datef != null) {
            $listes = DB::select("SELECT * FROM t_acte_deces
            JOIN t_declaration_deces ON t_declaration_deces.code_declaration_deces = t_acte_deces.code_declaration_deces
            JOIN tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_deces.code_defunt
            JOIN tr_lieu_survenance ON tr_lieu_survenance.code_lieu_survenance = t_declaration_deces.code_lieu_survenance
            WHERE date(t_declaration_deces.date_heure_deces) BETWEEN '".$dated."' AND '".$datef."'
            ORDER BY tr_identification_personne.nom ASC");
        }
        // dd($listes);

        if ($listes != null) {
            view()->share("tester", "Vincent");
            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML(view('deces::etats.listedeces', compact("listes","dated","datef"))->render());

            return $html2pdf->output("listedeces.pdf");
        } else {
            toastr()->warning('Aucune donnée trouvée');
            return redirect()->back();
        }

    }

    public function listedece()
    {
        return view('deces::statistiques.listedeces');
    }



     // poser le visa du Sécrétaire général de la mairie sur les actes
     public function viserActeBulk(Request $request)
     {
         $codes = $request->codes;
         $dd = DeclarationDeces::whereIn("code_declaration_deces",$codes)->get();

         if($dd->count() == 0){

             return response()->json([
                 "code"=>"180",
                 "message"=>["error"=>"Aucune déclaration à générer"]
             ]);
         }

         if( ! Gate::allows("module.acteDeces.viser")){

             return response()->json([
                 "code"=>"181",
                 "message"=>["error"=>"Vous n'êtes pas autorisé à viser un acte"]
             ]);
         }

         DB::beginTransaction();
         try {

             ActeDeces::whereIn("code_declaration_deces",$codes)->update([
                 "approbation_secretaire_general"=> Auth::user()->affectationActive()->cui,
                 "visa_secretaire_general"=> Auth::user()->personne->signature
             ]);

             DB::commit();

               return response()->json([
                 "code"=>"200",
                 "message"=>"Acte(s) des decès visé(s) avec succès"
             ]);


         } catch (Exception $e) {
             DB::rollBack();
             return response()->json([
                 "code"=>"183",
                 "message"=>["error"=>$e->getMessage()]
             ]);
         }

     }

    public function retraitActe(Request $request)
    {
        $retire_par = $request->nominteresse." ".$request->prenominteresse;
        $acte = ActeDeces::find($request->codeactedeces);

        // if(! Gate::allows("module.acteDeces.retrait")){

        //     return response()->json([
        //         "code"=>"181",
        //         "message"=>["error"=>"Vous n'êtes pas autorisé à effectuer cette opération"]
        //         ]);
        // }

        DB::beginTransaction();
        try {
            $retrait = new RetraitActe;
            $retrait->code_retrait_acte = Sifec::genererCodeUniqueReferentiel($retrait, "code_retrait_acte", 8, "RET_");
            $retrait->code_acte = $acte->codeactedeces;
            $retrait->retirer_par = $retire_par;
            $retrait->telephone = $request->telephoneinteresse;
            $retrait->save();

            $acte->retirer = 1;
            $acte->save();

            DB::commit();

            return response()->json([
                "code"=>"200",
                "message"=> "Le retrait de l'acte de décès enregistré avec succès"
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "code"=> "201",
                "message"=> ["error"=> $e->getMessage()]
            ]);
        }
    }

    /**
     * Annule un acte de décès (single)
     */
    public function annulerActe(Request $request)
    {
        $request->validate([
            'code_declaration_deces' => 'required|string',
            'motif' => 'required|string',
        ]);
        $code = $request->input('code_declaration_deces');
        $motif = $request->input('motif');
        $observation = $request->input('observation');
        $acte = \Modules\Deces\Entities\ActeDeces::where('code_declaration_deces', $code)->first();
        if (!$acte) {
            return response()->json([
                'code' => '404',
                'message' => 'Aucun acte trouvé pour ce code.'
            ]);
        }
        // Statut d'annulation (à adapter selon la structure)
        $acte->statut = 'annule';
        $acte->motif_annulation = $motif;
        $acte->observation_annulation = $observation;
        $acte->save();
        return response()->json([
            'code' => '200',
            'message' => 'Acte annulé avec succès.'
        ]);
    }

    /**
     * Annule plusieurs actes de décès (bulk)
     */
    public function annulerActeBulk(Request $request)
    {
        $request->validate([
            'codes' => 'required|array',
            'motif' => 'required|string',
        ]);
        $codes = $request->input('codes');
        $motif = $request->input('motif');
        $observation = $request->input('observation');
        $updated = 0;
        foreach ($codes as $code) {
            $acte = \Modules\Deces\Entities\ActeDeces::where('code_declaration_deces', $code)->first();
            if ($acte) {
                $acte->statut = 'annule';
                $acte->motif_annulation = $motif;
                $acte->observation_annulation = $observation;
                $acte->save();
                $updated++;
            }
        }
        return response()->json([
            'code' => '200',
            'message' => $updated.' acte(s) annulé(s) avec succès.'
        ]);
    }

     /**
     * Confirmer un dossier individuel (acte)
     */
    public function confirmerDossier(Request $request, MouvementService $mouvementService)
    {

        try {
            DB::beginTransaction();
            $declaration = Declarationdeces::findOrFail($request->code_declaration_deces);
            $user = Auth::user();
            $affectation = $user->affectationActive();
            $observation = $request->observation;
            $statut= "Confirmée";

            [$ok, $result] = $mouvementService->confirmerDeclarationDeces(
                $affectation,
                $declaration,
                $statut,
                $observation
            );

            if (!$ok) {
                DB::rollBack();
                Log::channel('sifec')->info($result);
                throw new Exception($result ?: "Erreur lors de la confirmation du dossier");
            }

            DB::commit();
            return response()->json([
                'code' => '200',
                'message' => ['Dossier confirmé avec succès']
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('Erreur confirmation dossier : ' . $e->getMessage());
            return response()->json([
                'code' => '500',
                'message' => ["Erreur lors de la confirmation du dossier: " . $e->getMessage()]
            ]);
        }
    }

    /**
     * Confirmer plusieurs dossiers en bulk (actes)
     */
    public function confirmerDossiersBulk(Request $request, MouvementService $mouvementService)
    {
        try {
            $codes = $request->codes;
            $observation = $request->observation;
            $user = Auth::user();
            $affectation = $user->affectationActive();
            $statut= "Confirmée";

            $declarations = Declarationdeces::whereIn('code_declaration_deces', $codes)->get();

            if ($declarations->count() === 0) {
                return response()->json([
                    'code' => '400',
                    'message' => ['Aucun dossier à confirmer']
                ]);
            }

            $confirmes = 0;
            $erreurs = [];

            foreach ($declarations as $declaration) {
                [$ok, $result] = $mouvementService->confirmerDeclarationDeces(
                    $affectation,
                    $declaration,
                    $statut,
                    $observation
                );
                if ($ok) {
                    $confirmes++;
                } else {
                    $erreurs[] = $declaration->code_declaration_naissance . ' : ' . $result;
                }
            }

            if ($confirmes === 0) {
                return response()->json([
                    'code' => '400',
                    'message' => ["Aucun dossier n'a pu être confirmé", ...$erreurs]
                ]);
            }

            $msg = [$confirmes . ' dossier(s) confirmé(s) avec succès'];
            if (count($erreurs)) {
                $msg[] = 'Erreurs sur certains dossiers : ' . implode(' | ', $erreurs);
            }

            return response()->json([
                'code' => '200',
                'message' => $msg
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => '500',
                'message' => ["Erreur lors de la confirmation des dossiers: " . $e->getMessage()]
            ]);
        }
    }

        /**
     * Renvoyer un dossier individuel (acte)
     */
    public function renvoyerDossier(Request $request, MouvementService $mouvementService)
    {
        try {
            DB::beginTransaction();
            $declaration = Declarationdeces::findOrFail($request->code_declaration_deces);
            $user = Auth::user();
            $affectation = $user->affectationActive();
            $motif = $request->motif_renvoi;
            $observation = $request->observation;

            [$ok, $result] = $mouvementService->renvoyerDeclarationDeces(
                $affectation,
                $declaration,
                $motif,
                $observation
            );

            if (!$ok) {
                DB::rollBack();
                Log::channel('sifec')->info($result);
                throw new Exception($result ?: "Erreur lors du renvoi du dossier");
            }

              // Notification centralisée via le module Notification (après commit)
              NotificationService::notifierAgentsInstitution(
                $declaration->institution,
                new \Modules\Notification\Notifications\DeclarationEnvoyeeCentreNotification(
                    $declaration,
                    $declaration->institution,
                    'renvoyée',
                     $observation ?? 'Une déclaration de décès a été renvoyée à votre institution.',
                ),
                "FONC_0006"
            );

            DB::commit();

            // // Notification centralisée via le module Notification (après commit)
            // NotificationService::notifierAgentsInstitution(
            //     $declaration->institution,
            //     new \Modules\Notification\Notifications\DeclarationEnvoyeeCentreNotification(
            //         $declaration,
            //         $declaration->institution,
            //         'renvoyée',
            //          $observation ?? 'Une déclaration de naissance a été renvoyée à votre institution.',

            //     ),
            //     "FONC_0006"
            // );

            return response()->json([
                'code' => '200',
                'message' => ['Dossier renvoyé avec succès']
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('Erreur renvoi dossier : ' . $e->getMessage());
            return response()->json([
                'code' => '500',
                'message' => ["Erreur lors du renvoi du dossier: " . $e->getMessage()]
            ]);
        }

    }

    /**
     * Renvoyer plusieurs dossiers en bulk (actes)
     */
    public function renvoyerDossiersBulk(Request $request, MouvementService $mouvementService)
    {
        try {
            $codes = $request->codes;
            $motif = $request->motif_renvoi;
            $observation = $request->observation;
            $user = Auth::user();
            $affectation = $user->affectationActive();

            $declarations = Declarationdeces::whereIn('code_declaration_deces', $codes)->get();

            if ($declarations->count() === 0) {
                return response()->json([
                    'code' => '400',
                    'message' => ['Aucun dossier à renvoyer']
                ]);
            }

            $renvoyes = 0;
            $erreurs = [];

            foreach ($declarations as $declaration) {
                [$ok, $result] = $mouvementService->renvoyerDeclarationDeces(
                    $affectation,
                    $declaration,
                    $motif,
                    $observation
                );
                if ($ok) {
                    $renvoyes++;

                     // Notification centralisée via le module Notification
                    NotificationService::notifierAgentsInstitution(
                        $declaration->institution,
                        new \Modules\Notification\Notifications\DeclarationEnvoyeeCentreNotification(
                            $declaration,
                            $declaration->institution,
                            'renvoyée',
                            $motif,
                            $observation
                        )
                    );
                } else {
                    $erreurs[] = $declaration->code_declaration_deces . ' : ' . $result;
                }
            }

            if ($renvoyes === 0) {
                return response()->json([
                    'code' => '400',
                    'message' => ["Aucun dossier n'a pu être renvoyé", ...$erreurs]
                ]);
            }

            $msg = [$renvoyes . ' dossier(s) renvoyé(s) avec succès'];
            if (count($erreurs)) {
                $msg[] = 'Erreurs sur certains dossiers : ' . implode(' | ', $erreurs);
            }

            return response()->json([
                'code' => '200',
                'message' => $msg
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => '500',
                'message' => ["Erreur lors du renvoi des dossiers: " . $e->getMessage()]
            ]);
        }
    }

    public function annulerActesBulk(Request $request)
    {
        try {
            $codes = $request->codes;
            $motif = $request->motif;
            $observation = $request->observation;
            $user = Auth::user();
            $affectation = $user->affectationActive();

            $declarations = Declarationdeces::whereIn('code_declaration_deces', $codes)
                ->whereHas('acte', function($query) {
                    $query->whereNull('deleted_at');
                })
                ->get();

            if ($declarations->count() !== count($codes)) {
                return response()->json([
                    'code' => '400',
                    'message' => 'Certains actes ne peuvent pas être annulés'
                ]);
            }

            DB::beginTransaction();

            foreach ($declarations as $declaration) {
                $acte = $declaration->acte;
                if ($acte && !$acte->deleted_at) {
                    $acte->motif_annulation = $motif;
                    $acte->observation_annulation = $observation;
                    $acte->deleted_at = now();
                    $acte->save();

                    // Historique mouvement
                    $mouvement = new MouvementDeces();
                    $mouvement->code_mouvement_deces = Sifec::genererCodeUniqueReferentiel($mouvement, "code_mouvement_deces", 8, "MOUV_");
                    $mouvement->code_declaration_deces = $declaration->code_declaration_deces;
                    $mouvement->motif_renvoi = 'Annulation d\'acte: ' . $motif;
                    $mouvement->observation = $observation;
                    $mouvement->cui = $affectation->cui;
                    $mouvement->save();
                }
            }

            DB::commit();

            return response()->json([
                'code' => '200',
                'message' => 'Actes annulés avec succès'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'code' => '500',
                'message' => 'Erreur lors de l\'annulation des actes: ' . $e->getMessage()
            ]);
        }
    }

    public function printActe($id)
    {
        $acte = ActeDeces::where("code_declaration_deces", $id)->orWhere("code_acte_deces", $id)->first();

        // Log pour déboguer
        Log::channel("sifec")->info("printActe called with id: $id, acte found: " . ($acte ? 'yes' : 'no'));

        // Pas besoin de redirection ici, la vue gère le cas où $acte est null
        return view('deces::acte.acte', compact("acte"));
    }

}
