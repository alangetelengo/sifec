<?php

namespace Modules\Naissance\Http\Controllers;

use Exception;
use Carbon\Carbon;
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
use Illuminate\Support\Facades\Validator;
use Modules\Notification\Jobs\SendSmsJob;
use Modules\Referentiel\Entities\Registre;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\RetraitActe;
use Modules\Mariage\Entities\DeclarationMariage;
use Modules\Referentiel\Entities\FeuilletRegistre;
use Modules\Naissance\Entities\Declarationnaissance;
use Modules\Notification\Jobs\ValidationacteNaissanceJob;

class ActeNaissanceController extends Controller
{

    public function index()
    {
        $declarations = [];
        if(Auth::user()->affectationActive()->institution->code_institution == "INS_0046"){
            $declarations = Auth::user()->affectationActive()->institution->declarationsNaissances();

        }else{
            $declarations = Auth::user()->institution()->descendants()->map->declarationsNaissances()->flatten();
        }

        $fonctionuser = InstitutionUser::where("cui",Auth::user()->affectationActive()->cui)->first();
        $registre = Registre::where("statut",1)->where("code_type_registre","TPRG_0001")->first();

        // dd($declarations);
        return view('naissance::acte.index',compact("declarations","registre",'fonctionuser'));
    }

    public function displayActe($id)
    {
        $acte = ActeNaissance::where("code_declaration_naissance",$id)->first();
        $dummy = "XXXXXXXXXXXXXXXX";

        if($acte == null){
            toastr()->error("Vous ne pouvez pas généré un acte de naissance");
            return back();
        }
        $acteannuler = Declarationnaissance::where("numero_ancien_acte",$acte->niupp)->first();

        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');


        $html2pdf->writeHTML(view('naissance::etats.acte', compact("acte","dummy","acteannuler"))->render());


        return $html2pdf->output($acte->code_acte_naissance.".pdf");
    }

    public function displayCopie($id)
    {


        $acte = ActeNaissance::where("code_declaration_naissance",$id)->first();
        $dummy = "XXXXXXXXXXXXXXXX";


        if($acte == null){
            toastr()->error("Vous ne pouvez pas généré un acte de naissance");
            return back();
        }

        $declarationDeces = DeclarationDeces::where("num_acte_naissance", $acte->niupp)->first();

        $mariage = null;
        if (DeclarationMariage::where('numero_acte_naissance_epoux',$acte->niupp)->first() != null) {
            $mariage = DeclarationMariage::where('numero_acte_naissance_epoux',$acte->niupp)->first();
        }
        if (DeclarationMariage::where('numero_acte_naissance_epouse',$acte->niupp)->first() != null) {
            $mariage = DeclarationMariage::where('numero_acte_naissance_epouse',$acte->niupp)->first();
        }

        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');


            //contexte Congo
            $html2pdf->writeHTML(view('naissance::etats.copieActeNaissance', compact("acte","dummy", "declarationDeces","mariage"))->render());


        return $html2pdf->output($acte->code_acte_naissance.".pdf");
    }

    public function generateActe(Request $request)
    {


        $request->validate([
            "code_declaration_naissance"=> ["required:string"]
        ]);
        // dd($request->all());


        $dn = Declarationnaissance::find($request->code_declaration_naissance);

        if($dn == null){
            toastr()->error("Ce document n'existe pas");
            return back();
        }

        $rn = Auth::user()->institution()->registres()->where("code_type_registre","TPRG_0001")->where("statut",1)->first();

        //Vérifier si le user a le droit pour effectuer cette action
        if(! Gate::allows("module.acteNaissance.generate")){
            toastr()->error("Vous n'êtes pas autorisé à générer un acte");
            return back();
        }

        if($rn == null){
            toastr()->error("Aucun registre disponible");
            return back();
        }

        if($rn->statut == 0){
            toastr()->error("Ce registre est déjà clôturé");
            return back();
        }

        $regResteplace = $rn->nombre_acte_prevu - $rn->nombre_acte_transcrit;

        // if($rn->nombre_acte_prevu == $rn->nombre_acte_transcrit){
        if($regResteplace == 0){
            toastr()->error("Ce registre a déjà atteint le nombre d'actes prévu");
            return back();
        }

        DB::beginTransaction();
        try{

            $niupp = Sifec::genererNiupp($request->code_declaration_naissance);
            $acteNaissance = new ActeNaissance();
            $acteNaissance->niupp = $niupp;
            $acteNaissance->date_emission = now();
            $acteNaissance->code_declaration_naissance = $request->code_declaration_naissance;
            $acteNaissance->code_registre = $rn->code_registre;
            $acteNaissance->cui = Auth::user()->affectationActive()->cui;
            $acteNaissance->approbation_tribunal = 1;
            $acteNaissance->sceau_tribunal = Auth::user()->affectationActive()->institution->institutionParent->sceau;
            $acteNaissance->save();


            if(($rn->nombre_acte_transcrit + 1) == $rn->nombre_acte_prevu){
                $rn->statut = 0;
            }
            $position = $rn->nombre_acte_transcrit + 1;
            $rn->nombre_acte_transcrit = $position;
            $rn->save();

            $feuillet = new FeuilletRegistre;
            $feuillet->code_feuillet_registre = Sifec::genererCodeUniqueReferentiel($feuillet, "code_feuillet_registre", 4, "FRE_");
            $feuillet->code_acte = $acteNaissance->niupp;
            $feuillet->numero_acte =  SifecFacade::generate_acte_number($rn, $position);
            $feuillet->save();

            DB::commit();

            return response()->json([
                "code"=>"200",
                "message"=>["reponse"=>"Acte naissance généré avec succès"]
            ]);
            toastr()->success("Acte naissance généré avec succès, l'acte de naissance numéro : $request->numero_ancien_acte a été annulé");
            return redirect()->route("acteNaissance.index");

        }catch(Exception $e){
            DB::rollBack();
            Log::channel('sifec')->info($e->getMessage());
            toastr()->error($e->getMessage());
            return back();
        }
    }

    public function naissanceApprouver($id)
    {

        $acte = ActeNaissance::find($id);

        if($acte==null){
            toastr()->error("Vous ne pouvez pas approuver cet acte de naissance");
            return back();
        }

        if ( Gate::allows("module.acteNaissance.signature")) {

           try{
                $acte->approbation_mairie = 1;
                $acte->signature_mairie =  Auth::user()->personne->signature;
                $acte->save();

                $otp = substr(time(),2);

                $temp = config("sifec.sms.templates.actions.acte_naissance");
                $temp = str_replace(":declarant",$acte->declaration->declarant->nomcomplet(),$temp);
                $temp = str_replace(":code_acte_naissance",$acte->niupp,$temp);
                toastr()->success("Acte approuvé avec succès");

                dispatch(new SendSmsJob($acte->declaration->declarant->telephone,$temp));

                return back();

            }catch(Exception $e){
                toastr()->error($e->getMessage());
                return back();
            }

        }
        // if ( Gate::allows("module.acteNaissance.sceau")) {

        //    try{
        //         $acte->approbation_tribunal = 1;
        //         $acte->sceau = $acte->institutionUser->institution->institutionParent->sceau;
        //         $acte->save();
        //         toastr()->success("Acte approuvé avec succès");
        //         return back();

        //     }catch(Exception $e){
        //         toastr()->error($e->getMessage());
        //         return back();
        //     }
        // }
        toastr()->error("Vous n'avez pas la permission d'approuver cet acte de naissance");
        return back();

    }

    public function searchActe()
    {

        $nom = request('nom') ?  "%".request('nom')."%"  : "";
        $prenom = request('prenom') ?  "%".request('prenom')."%" : "";
        $lieu = request('lieu') ? "%".request('lieu')."%":"";
        $personnes = DB::select("SELECT dn.code_declaration_naissance, ip.nom,ip.prenom,ip.lieu_naissance,ti.lib_institution,an.niupp FROM tr_identification_personne ip JOIN t_declaration_naissance dn ON ip.code_personne = dn.code_enfant JOIN t_acte_naissance an ON dn.code_declaration_naissance = an.code_declaration_naissance JOIN tr_ins_user iu ON an.cui = iu.cui JOIN tr_institution ti ON iu.code_institution = ti.code_institution WHERE ip.nom LIKE ? OR ip.prenom LIKE ? OR ip.lieu_naissance LIKE ?",[$nom,$prenom,$lieu]);

        return response()->json([
           "personnes" => $personnes
        ]);
    }

    public function displayDuplicata($id)
    {

        $acte = ActeNaissance::where("code_declaration_naissance",$id)->first();
        $dummy = "XXXXXXXXXXXXXXXX";

        if($acte == null){
            toastr()->error("Vous ne pouvez pas généré un acte de naissance");
            return back();
        }

        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('naissance::etats.duplicata', compact("acte","dummy"))->render());

        return $html2pdf->output($acte->code_acte_naissance.".pdf");
    }

    public function sendOtp(Request $request){

        $an = ActeNaissance::where("code_declaration_naissance",$request->codeDn)->first();
        if($an == null){
            return response()->json([
                "code"=>"180",
                "message"=>"Aucun acte trouvé pour ce code $request->codeDn"
            ]);
        }

        try {
            $otp = substr(time(),2);


            // $temp = config("sifec.sms.templates.actions.validation_acte_naissance");
            // $temp = str_replace(":maire",Auth::user()->personne->nom,$temp);
            // $temp = str_replace(":nombre","1",$temp);
            // $temp = str_replace(":code_otp",$otp,$temp);


            // $temp = config("sifec.sms.templates.actions.validation_multiples_acte_naissances");
            $temp = config("sifec.sms.templates.actions.validation_acte_naissance");
            $temp = str_replace(":maire",Auth::user()->personne->nom,$temp);
            $temp = str_replace(":nombre",1,$temp);
            $temp = str_replace(":code_otp",$otp,$temp);

            $an->otp_approbation_mairie = $otp;
            $an->save();

            $contact = Auth::user()->personne->contacts->first();

            // Log::channel("sifec")->info(["contact"=>$contact]);
            // dd("ok");

            if($contact != null){
                $indicatif = $contact->indicatif;

                // if($indicatif != "+242"){
                //     SifecFacade::infobipSms($contact->indicatif.$contact->telephone, $temp);
                // }else{
                //     dispatch(new SendSmsJob($contact->indicatif.$contact->telephone,$temp));
                //     // dispatch(new SendSmsJob("+242066835332",$temp));
                // }

                SifecFacade::sendSms($contact->indicatif.$contact->telephone, $temp);
                dispatch(new SendSmsJob($contact->indicatif.$contact->telephone,$temp));
                dispatch(new ValidationacteNaissanceJob(Auth::user()->personne->nomComplet(),1,$otp,$contact->email_professionnelle));
            }

            return response()->json([
                "code"=>"200",
                "message"=>"SMS envoyé avec succès"
            ]);


        } catch (Exception $e) {
            return response()->json([
                "code"=>"181",
                "message"=> ["error" =>$e->getMessage()]
            ]);
        }

    }

    public function validateOtp(Request $request)
    {

        $rules = [
            "otp_approbation_mairie"=>["required","numeric"],
            "code_declaration_naissance"=>["required","string"]
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json([
                "code"=>"180",
                "message"=>"Aucun acte trouvé pour ce code"
            ]);
        }

        if(!Gate::allows("module.acteNaissance.signature")){
            return response()->json([
                "code"=>"181",
                "message"=>["error"=>"Vous n'êtes pas autorisé à valider un acte de naissance"]
            ]);
        }

        $cdn = $request->code_declaration_naissance;
        $otp = $request->otp_approbation_mairie;

        $an = ActeNaissance::where("code_declaration_naissance",$cdn)->first();
        if($an == null){
            return response()->json([
                "code"=>"182",
                "message"=>"Aucun acte trouvé pour ce code $cdn"
            ]);
        }

        if($otp != $an->otp_approbation_mairie){
            return response()->json([
                "code"=>"183",
                "message"=>["error"=>"Code de validation incorrect"]
            ]);
        }

        DB::beginTransaction();
        try {

                $an->approbation_mairie = Auth::user()->affectationActive()->cui;
                $an->signature_mairie = Auth::user()->personne->signature;
                $an->save();

              // verification de l'existence d'un ancien acte de naissance issu de ce codeDn
                $oldDn = Declarationnaissance::find($cdn);
                $oldAn = ActeNaissance::find($oldDn->numero_ancien_acte);
                if($oldAn != null){

                    // Log::channel("sifec")->info(["OldActe"=>$oldAn]);

                    //annulation de l'ancien acte de naissance
                    $oldAn->deleted_at = Carbon::now();
                    $oldAn->motif_annulation = "déclaration postérieure à la naissance";
                    $oldAn->statut = 1;
                    $oldAn->save();
                }

            DB::commit();

            $otp = substr(time(),2);

            $temp = config("sifec.sms.templates.actions.acte_naissance");
            $temp = str_replace(":declarant",$an->declaration->declarant->nomcomplet(),$temp);
            $temp = str_replace(":code_acte_naissance",$an->niupp,$temp);
            $temp = str_replace(":libCec",$an->institutionUser->institution->lib_institution,$temp);

            // Send sms au déclarant
            // $contact = $an->declaration->declarant->contacts->first();
            // if($contact != null){
            //     dispatch(new SendSmsJob($contact->telephone,$temp));
            // }

            // dispatch(new SendSmsJob($an->declaration->declarant->telephone,$temp));

            $contactDeclarant = $an->declaration->declarant->contacts->first();
            if($contactDeclarant != null){
                $indicatif = $contactDeclarant->indicatif;

                // if($indicatif != "+242"){

                //     SifecFacade::infobipSms($contactDeclarant->indicatif.$contactDeclarant->telephone, $temp);
                // }else{
                //     dispatch(new SendSmsJob($contactDeclarant->indicatif.$contactDeclarant->telephone,$temp));
                //     // dispatch(new SendSmsJob("+242066835332",$temp));
                // }
                // SifecFacade::infobipSms($contactDeclarant->indicatif.$contactDeclarant->telephone, $temp);
                SifecFacade::sendSms($contactDeclarant->indicatif.$contactDeclarant->telephone, $temp);
                dispatch(new SendSmsJob($contactDeclarant->indicatif.$contactDeclarant->telephone,$temp));
            }
            dispatch(new ValidationacteNaissanceJob(Auth::user()->personne->nomComplet(),$an->count(),$otp,$contactDeclarant->email_professionnelle));
            // dispatch(new ValidationacteNaissanceJob(Auth::user()->personne->nomComplet(),$an->count(),$otp,"alangetelengo87@gmail.com"));


            if($oldAn != null){
                return response()->json([
                    "code"=>"200",
                    "message"=>"Acte de naissance validé avec succès, l'acte de naissance numéro : $oldAn->niupp a été annulé"
                ]);
            }
            return response()->json([
                "code"=>"200",
                "message"=>"Acte de naissance validé avec succès"
            ]);


        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "code"=>"183",
                "message"=>["error" =>$e->getMessage()]
            ]);
        }

    }

    // Send OTP for bulk validation
    public function sendOtpBulk(Request $request){
        $codes = $request->codes;

        $an = ActeNaissance::whereIn("code_declaration_naissance",$codes)->get();
        if($an->count() == 0){
            return response()->json([
                "code"=>"180",
                "message"=>"Aucun acte trouvé"
            ]);
        }

        try {
            $otp = substr(time(),2);

            $temp = config("sifec.sms.templates.actions.validation_multiples_acte_naissances");
            $temp = str_replace(":maire",Auth::user()->personne->nom,$temp);
            $temp = str_replace(":nombre",$an->count(),$temp);
            $temp = str_replace(":code_otp",$otp,$temp);

            ActeNaissance::whereIn("code_declaration_naissance",$codes)->update(["otp_approbation_mairie"=>$otp]);

            $contact = Auth::user()->personne->contacts->first();
            if($contact != null){
                $indicatif = $contact->indicatif;

                // if($indicatif != "+242"){
                //     SifecFacade::infobipSms($contact->indicatif.$contact->telephone, $temp);
                // }else{
                //     dispatch(new SendSmsJob($contact->indicatif.$contact->telephone,$temp));
                //     // dispatch(new SendSmsJob("+242066835332",$temp));
                // }
                SifecFacade::sendSms($contact->indicatif.$contact->telephone, $temp);
                dispatch(new SendSmsJob($contact->indicatif.$contact->telephone,$temp));
                dispatch(new ValidationacteNaissanceJob(Auth::user()->personne->nomComplet(),$an->count(),$otp,$contact->email_professionnelle));
            }

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

    // Validate multiple actes
    public function validateOtpBulk(Request $request){
        // return $request->all();
        // Log::channel("sifec")->info(["codes"=>$request->all()]);
        // dd("ok alange");
        $rules = [
            "otp_approbation_mairie"=>["required","numeric"],
            "codes"=>["required"]
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json([
                "code"=>"180",
                "message"=>"Aucun acte trouvé pour ce numéro"
            ]);
        }

        if(!Gate::allows("module.acteNaissance.signature")){
            return response()->json([
                "code"=>"181",
                "message"=>["error" =>"Vous n'êtes pas autorisé à valider un acte de naissance"]
            ]);
        }

        $codes = $request->codes;
        $otp = $request->otp_approbation_mairie;

        $an = ActeNaissance::whereIn("code_declaration_naissance",$codes)->get();
        if($an->count() == 0){
            return response()->json([
                "code"=>"182",
                "message"=>"Aucun acte trouvé"
            ]);
        }

        if($otp != $an->last()->otp_approbation_mairie){
            return response()->json([
                "code"=>"183",
                "message"=>["error" =>"Code de validation incorrect ou expiré"]
            ]);
        }

        DB::beginTransaction();
        try {

            ActeNaissance::whereIn("code_declaration_naissance",$codes)->update([
                "approbation_mairie"=> Auth::user()->affectationActive()->cui,
                "signature_mairie"=> Auth::user()->personne->signature
            ]);

            DB::commit();


            foreach($an as $a){
                $temp = config("sifec.sms.templates.actions.acte_naissance");
                $temp = str_replace(":declarant",$a->declaration->declarant->nomcomplet(),$temp);
                $temp = str_replace(":code_acte_naissance",$a->niupp,$temp);
                $temp = str_replace(":libCec",$a->institutionUser->institution->lib_institution,$temp);


                $contactDeclarant = $a->declaration->declarant->contacts->first();
                if($contactDeclarant != null){
                    $indicatif = $contactDeclarant->indicatif;

                    // if($indicatif != "+242"){

                    //     SifecFacade::infobipSms($contactDeclarant->indicatif.$contactDeclarant->telephone, $temp);
                    // }else{
                    //     dispatch(new SendSmsJob($contactDeclarant->indicatif.$contactDeclarant->telephone,$temp));
                    //     // dispatch(new SendSmsJob("+242066835332",$temp));
                    // }
                    // SifecFacade::infobipSms($contactDeclarant->indicatif.$contactDeclarant->telephone, $temp);
                    SifecFacade::sendSms($contactDeclarant->indicatif.$contactDeclarant->telephone, $temp);
                    dispatch(new SendSmsJob($contactDeclarant->indicatif.$contactDeclarant->telephone,$temp));
                }
                dispatch(new ValidationacteNaissanceJob(Auth::user()->personne->nomComplet(),$an->count(),$otp,$contactDeclarant->email_professionnelle));
                // dispatch(new ValidationacteNaissanceJob(Auth::user()->personne->nomComplet(),$an->count(),$otp,"alangetelengo87@gmail.com"));

            }

            // $sifecObjet = new Sifec;
            return response()->json([
                "code"=>"200",
                "message"=>"Actes des naissances validés avec succès"
            ]);


        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "code"=>"183",
                "message"=>["error"=>$e->getMessage()]
            ]);
        }

    }
    // générer bulk actes
    public function generateActeBulk(Request $request)
    {
        $codes = $request->codes;
        //institution concernee
        $institution = Auth::user()->institution()->code_institution;



        // $rn = Auth::user()->institution()->registres()->where("code_type_registre","TPRG_0001")->where("statut",1)->first();
        //On recupere un registre de l'année en cours
        $rn = DB::table('tr_registre')
                            ->join("tr_ins_user","tr_registre.cui","tr_ins_user.cui")
                            ->where("tr_registre.code_type_registre","TPRG_0001")
                            ->where("tr_registre.statut",1)
                            ->where("tr_ins_user.code_institution","=",$institution)
                            ->whereYear('tr_registre.updated_at', '=', date('Y'))
                            ->select("tr_registre.*")
                            ->first();


        if(! Gate::allows("module.acteNaissance.generate")){
            return response()->json([
                "code"=>"181",
                "message"=>["error"=>"Vous n'êtes pas autorisé à générer un acte"]
            ]);
        }

        if($rn == null){
            return response()->json([
                "code"=>"182",
                "message"=>["error"=>"Aucun registre disponible"]
            ]);
        }

        if($rn->statut == 0){
            return response()->json([
                "code"=>"183",
                "message"=>["error"=>"Ce registre est déjà clôturé"]
            ]);
        }

        $regResteplace = $rn->nombre_acte_prevu - $rn->nombre_acte_transcrit;

        if($regResteplace == 0){
            return response()->json([
                "code"=>"184",
                "message"=>["error"=>"Registre plein.Veuillez ajouter des feuillets pour continuer !"]
            ]);
        }

        //recuperer toutes les dn par rapport à leurs codes envoyés
        $dn = Declarationnaissance::whereIn("code_declaration_naissance",$codes)->get();


        if($dn->count() == 0){
            return response()->json([
                "code"=>"180",
                "message"=>["error"=>"Aucune déclaration à générer"]
            ]);
        }


        //vérification de place disponible avant de générer
        if($regResteplace < count($codes)) // (1-0) < 2 => 1<2 --juste
        {
            $dn = Declarationnaissance::whereIn("code_declaration_naissance",$codes)->take($regResteplace)->get();
        }

        DB::beginTransaction();
        try{

            foreach($dn as $d){

                $niupp = Sifec::genererNiupp($d->code_declaration_naissance);
                $acteNaissance = new ActeNaissance();
                $acteNaissance->niupp = $niupp;
                $acteNaissance->date_emission = now();
                $acteNaissance->code_declaration_naissance = $d->code_declaration_naissance;
                $acteNaissance->code_registre = $rn->code_registre;
                $acteNaissance->cui = Auth::user()->affectationActive()->cui;
                $acteNaissance->approbation_tribunal = 1;
                $acteNaissance->sceau_tribunal = Auth::user()->affectationActive()->institution->institutionParent->sceau;
                $acteNaissance->save();

                //mise à jour le registre
                $mrn = Registre::find($rn->code_registre);
                $position = $mrn->nombre_acte_transcrit + 1;
                $mrn->nombre_acte_transcrit = $position;
                $mrn->save();

                //creer un numero d'ordre dans le registre
                $feuillet = new FeuilletRegistre;
                $feuillet->code_feuillet_registre = Sifec::genererCodeUniqueReferentiel($feuillet, "code_feuillet_registre", 4, "FRE_");
                $feuillet->code_acte = $acteNaissance->niupp;
                $feuillet->numero_acte =  SifecFacade::generate_acte_number($mrn, $position);
                $feuillet->save();
            }

            DB::commit();

            return response()->json([
                "code"=>"200",
                "message"=>["reponse"=>"Actes des naissances générés avec succès"]
            ]);

        }catch(Exception $e){
            DB::rollBack();
            Log::channel("sifec")->error($e->getMessage());
            return response()->json([
                "code"=>"201",
                "message"=>["error"=> $e->getMessage()]
            ]);
        }
    }

    public function displayActeMaquette2($id)
    {

        $acte = ActeNaissance::where("code_declaration_naissance",$id)->first();
        $dummy = "XXXXXXXXXXXXXXXX";


        if($acte == null){
            toastr()->error("Vous ne pouvez pas généré un acte de naissance");
            return back();
        }

        $declarationDeces = DeclarationDeces::where("num_acte_naissance", $acte->niupp)->first();

        $mariage = null;
        if (DeclarationMariage::where('numero_acte_naissance_epoux',$acte->niupp)->first() != null) {
            $mariage = DeclarationMariage::where('numero_acte_naissance_epoux',$acte->niupp)->first();
        }
        if (DeclarationMariage::where('numero_acte_naissance_epouse',$acte->niupp)->first() != null) {
            $mariage = DeclarationMariage::where('numero_acte_naissance_epouse',$acte->niupp)->first();
        }


        view()->share("tester", "Vincent");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('naissance::etats.acteMaquette2', compact("acte","dummy", "declarationDeces","mariage"))->render());

        return $html2pdf->output($acte->code_acte_naissance.".pdf");
    }

    public function displayCopieMaquette2($id)
    {

        $acte = ActeNaissance::where("code_declaration_naissance",$id)->first();
        $dummy = "XXXXXXXXXXXXXXXX";


        if($acte == null){
            toastr()->error("Vous ne pouvez pas généré un acte de naissance");
            return back();
        }

        $declarationDeces = DeclarationDeces::where("num_acte_naissance", $acte->niupp)->first();

        $mariage = null;
        if (DeclarationMariage::where('numero_acte_naissance_epoux',$acte->niupp)->first() != null) {
            $mariage = DeclarationMariage::where('numero_acte_naissance_epoux',$acte->niupp)->first();
        }
        if (DeclarationMariage::where('numero_acte_naissance_epouse',$acte->niupp)->first() != null) {
            $mariage = DeclarationMariage::where('numero_acte_naissance_epouse',$acte->niupp)->first();
        }


        view()->share("tester", "Vincent");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('naissance::etats.copieAncienActe', compact("acte","dummy", "declarationDeces","mariage"))->render());

        return $html2pdf->output($acte->code_acte_naissance.".pdf");
    }

    public function displaySouche($id)
    {

        $acte = ActeNaissance::where("code_declaration_naissance",$id)->first();
        $dummy = "XXXXXXXXXXXXXXXX";


        if($acte == null){
            toastr()->error("Vous ne pouvez pas généré un acte de naissance");
            return back();
        }

        $declarationDeces = DeclarationDeces::where("num_acte_naissance", $acte->niupp)->first();

        $mariage = null;
        if (DeclarationMariage::where('numero_acte_naissance_epoux',$acte->niupp)->first() != null) {
            $mariage = DeclarationMariage::where('numero_acte_naissance_epoux',$acte->niupp)->first();
        }
        if (DeclarationMariage::where('numero_acte_naissance_epouse',$acte->niupp)->first() != null) {
            $mariage = DeclarationMariage::where('numero_acte_naissance_epouse',$acte->niupp)->first();
        }


        view()->share("tester", "Vincent");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('naissance::etats.souche', compact("acte","dummy", "declarationDeces","mariage"))->render());

        return $html2pdf->output($acte->code_acte_naissance.".pdf");
    }


    public function repertoire()
    {

        $dated = request('dated');
        $datef = request('datef');


        if ($dated == null && $datef == null) {
            $actes = DB::select("SELECT *
            FROM t_acte_naissance
            JOIN t_declaration_naissance ON t_declaration_naissance.code_declaration_naissance = t_acte_naissance.code_declaration_naissance
            JOIN tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_naissance.code_enfant

            WHERE t_acte_naissance.cui = '".Auth::user()->affectationActive()->cui."'
            ORDER BY tr_identification_personne.nom");
        }

        return view('naissance::acte.repertoire', compact('actes', 'dated', 'datef'));
    }



    public function repertoireAlphabetique(Request $request)
    {

        //
        $dated = $request->dated;
        $datef = $request->datef;
        $user = Auth::user()->affectationActive();
        // if ($dated == null && $datef == null) {
        //     $actes = DB::select("SELECT *
        //     FROM t_acte_naissance
        //     JOIN t_declaration_naissance ON t_declaration_naissance.code_declaration_naissance = t_acte_naissance.code_declaration_naissance
        //     JOIN tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_naissance.code_enfant
        //     WHERE t_acte_naissance.cui = '".Auth::user()->affectationActive()->cui."'
        //     ORDER BY tr_identification_personne.nom");
        // }

        // if ($dated != null && $datef == null) {
        //     $actes = DB::select("SELECT *
        //     FROM t_acte_naissance
        //     JOIN t_declaration_naissance ON t_declaration_naissance.code_declaration_naissance = t_acte_naissance.code_declaration_naissance
        //     JOIN tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_naissance.code_enfant
        //     WHERE t_acte_naissance.cui = '".Auth::user()->affectationActive()->cui."'
        //     AND date(t_declaration_naissance.date_heure_naissance) BETWEEN '".$dated."' AND '".$dated."'
        //     ORDER BY tr_identification_personne.nom");
        // }

        // if ($dated == null && $datef != null) {
        //     $actes = DB::select("SELECT *
        //     FROM t_acte_naissance
        //     JOIN t_declaration_naissance ON t_declaration_naissance.code_declaration_naissance = t_acte_naissance.code_declaration_naissance
        //     JOIN tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_naissance.code_enfant
        //     WHERE t_acte_naissance.cui = '".Auth::user()->affectationActive()->cui."'
        //     AND date(t_declaration_naissance.date_heure_naissance) BETWEEN '".$datef."' AND '".$datef."'
        //     ORDER BY tr_identification_personne.nom");
        // }



        if ($dated != null && $datef != null) {
            $actes = DB::select("SELECT p.nom,p.prenom,p.sexe,p.date_naissance,an.niupp FROM tr_identification_personne p
                JOIN t_declaration_naissance dn ON dn.code_enfant = p.code_personne
                JOIN t_acte_naissance an ON an.code_declaration_naissance = dn.code_declaration_naissance
                JOIN tr_ins_user iuser ON an.cui=iuser.cui
                JOIN tr_institution ins ON iuser.code_institution = ins.code_institution
                WHERE date(an.date_emission) BETWEEN '".$dated."' AND '".$datef."' AND ins.code_institution = '".$user->code_institution."'
                ORDER BY p.nom,p.prenom,p.sexe,p.date_naissance,an.niupp"
            );

        }
        // dd("impossible");
        // dd($request->all());
        if ($actes == null) {
            toastr()->warning('Aucune donnée trouvée');
            return back();
        }

        view()->share("tester", "Vincent");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('naissance::etats.repertoire', compact("actes","dated", "datef"))->render());

        return  $html2pdf->output("repertoireAlpha.pdf");

    }


    public function retraitActe(Request $request)
    {

        $retire_par = $request->nominteresse." ".$request->prenominteresse;
        $acte = ActeNaissance::find($request->niupp);

        // Log::channel("sifec")->info(["OBJET"=>$request->all()]);
        // dd("ok");
        // if(! Gate::allows("module.acteNaissance.retrait")){

        //     return response()->json([
        //         "code"=>"181",
        //         "message"=>["error"=>"Vous n'êtes pas autorisé à effectuer cette opération"]
        //         ]);
        // }

        DB::beginTransaction();
        try {
            $retrait = new RetraitActe;
            $retrait->code_retrait_acte = Sifec::genererCodeUniqueReferentiel($retrait, "code_retrait_acte", 8, "RET_");
            $retrait->code_acte = $request->niupp;
            $retrait->retirer_par = $retire_par;
            $retrait->telephone = $request->telephoneinteresse;
            $retrait->cui = Auth::user()->affectationActive()->cui;
            $retrait->save();

            $acte->retirer = 1;
            $acte->save();

            DB::commit();

            return response()->json([
                "code"=>"200",
                "message"=> "Le retrait de l'acte de naissance enregistré avec succès"
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::channel("sifec")->error($e->getMessage());
            return response()->json([
                "code"=> "201",
                "message"=> ["error"=> $e->getMessage()]
            ]);
        }
    }

    public function displayExtrait($id)
    {

        $acte = ActeNaissance::where("code_declaration_naissance",$id)->first();
        $dummy = "XXXXXXXXXXXXXXXX";
        $numExtrait = substr(time(),2);

        if($acte == null){
            toastr()->error("Vous ne pouvez pas généré un extrait d'acte de naissance");
            return back();
        }

            view()->share("tester", "extrait");
            $html2pdf = new Html2Pdf('L', 'A5', 'fr');
            $html2pdf->setDefaultFont('Arial');

            //contrôle de contexte
            $code_institution = Auth::user()->affectationActive()->institution->code_institution;

            //contexte Congo
            $html2pdf->writeHTML(view('naissance::etats.extrait', compact("acte","dummy", "numExtrait"))->render());

            return $html2pdf->output($acte->code_acte_naissance.".pdf");
    }

    public function findActe(Request $request)
    {
        $acte = ActeNaissance::find($request->niupp);

        if($acte == null){
            return response()->json([
                "code" => "180",
                "message"=>"Aucun acte trouvé pour ce numéro"
            ]);
        }

        if($acte->deleted_at != null){
            return response()->json([
                "code" => "180",
                "message"=>"Cet acte a été annulé"
            ]);
        }

        $nom = $acte->declaration->enfant->nom;
        $prenom = $acte->declaration->enfant->prenom;
        $sexe = $acte->declaration->enfant->sexe=="M" ? "Masculin" : "Féminin";
        $dateNaissance = date("d-m-Y", strtotime($acte->declaration->date_heure_naissance));
        $lieuNaissance = $acte->declaration->enfant->lieu_naissance;
        $cec = $acte->institutionUser->institution->lib_institution;
        $cdn = $acte->code_declaration_naissance;
        $codeAdoptant = $acte->declaration->code_adoptant; //déjà adopter ou pas
        $button = "";
        if($codeAdoptant != ""){
            $button = "disabled"; //pour désactiver le lien de l'adoption
        }



        return response()->json([
            "code" => "200",
            "nomPrenom" => $nom." ".$prenom,
            "dateNaissance" => $dateNaissance,
            "sexe" => $sexe,
            "lieuNaissance" => $lieuNaissance,
            "cec" => $cec,
            "cdn" => $cdn,
            "statutEnfant" => $codeAdoptant == "" ? "Adopter" : "Enfant déjà adopté",
            "statutLien" => $button
        ]);
    }

    public function printActe($id)
    {
        $acte = ActeNaissance::where("code_declaration_naissance",$id)->first();
        return view('naissance::acte.acte',compact("acte"));
    }
    public function printCopie($id)
    {
        $acte = ActeNaissance::where("code_declaration_naissance",$id)->first();
        return view('naissance::acte.copie',compact("acte"));
        // return view('naissance::acte.acte',compact("acte"));
    }
    public function printExtrait($id)
    {
        $acte = ActeNaissance::where("code_declaration_naissance",$id)->first();
        return view('naissance::acte.extrait',compact("acte"));
    }

    // public function destroy($id)
    // {
    //     $an = ActeNaissance::find($id);

    //     if($an == null){
    //         return response()->json([
    //             "code" => "180",
    //             "message"=>"Aucun acte trouvé pour ce numéro"
    //         ]);
    //     }

    //     try {
    //         $an->delete();

    //         return response()->json([
    //             "code" => "200",
    //            "message"=>"Acte annulé avec succès"
    //         ]);

    //     } catch (Exception $e) {
    //         log::channel("sifec")->error($e->getMessage());
    //         return response()->json([
    //             "code" => "180",
    //             "message"=>["error" =>$e->getMessage()]
    //         ]);
    //     }
    // }


    // public function suppression()
    // {
    //     return view('naissance::acte.annuler');
    // }

    /**
     * Gère la suppression d'un acte de naissance
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    // public function suppressionacte()
    // {
    //     $id = request('id');

    //     try {

    //         $acte = ActeNaissance::find($id);
    //         if ($acte == null) {
    //             toastr()->error("Acte de naissance indisponible");
    //             return back();
    //         }

    //         if ($acte->deleted_at != null) {
    //             toastr()->warning("Acte déjà annuler");
    //             return back();
    //         }

    //         $tgis = Institution::where("code_type_institution","TPINS_0001")->get();
    //         return view('naissance::acte.actesuppression', compact('acte','tgis'));
    //     } catch (Exception $e) {
    //         toastr()->error($e->getMessage());
    //         return back();
    //     }
    // }

    /**
     * Valide la suppression d'un acte de naissance
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validerAnnulation(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $acte = ActeNaissance::find($id);
            if ($acte == null) {
                toastr()->error("Acte de naissance indisponible");
                return back();
            }

            $declaration = DeclarationNaissance::where("code_declaration_naissance",$acte->code_declaration_naissance)->first();
            if ($declaration == null) {
                toastr()->error("Déclaration indisponible");
                return back();
            }

            $declaration->code_jugement = $request->code_jugement;
            $declaration->save();

            $acte->deleted_at = Carbon::now();
            $acte->motif_annulation = $request->motif;
            $acte->statut = 1;
            $acte->save();
            toastr()->success("Acte annulé");
            DB::commit();
            return redirect()->route('acteNaissance.index');
        } catch (Exception $e) {
            DB::rollBack();
            toastr()->error($e->getMessage());
            return back();
        }
    }

    // public function rectification()
    // {
    //     return view('naissance::acte.rectification');
    // }

    public function rectificationacte()
    {

        $id = request('id');

        try {

            $acte = ActeNaissance::find($id);
            if ($acte == null) {
                toastr()->error("Acte de naissance indisponible");
                return back();
            }

            if ($acte->deleted_at != null) {
                toastr()->warning("Acte déjà annuler");
                return back();
            }

            $created = new Carbon($acte->created_at);
            // $now = Carbon::now();
            // $difference = ($created->diff($now)->days < 1)
            //     ? 'today'
            //     : $created->diffForHumans($now);
            $DeferenceInDays = Carbon::parse(Carbon::now())->diffInMonths($created);

            $tgis = Institution::where("code_type_institution","TPINS_0001")->get();
            return view('naissance::acte.acterectification', compact('acte','tgis','DeferenceInDays'));
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back();
        }
    }
}
