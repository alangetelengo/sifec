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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Deces\Entities\ActeDeces;
use Illuminate\Support\Facades\Validator;
use Modules\Notification\Jobs\SendSmsJob;
use Modules\Referentiel\Entities\Registre;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Log;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Referentiel\Entities\RetraitActe;
use Modules\Referentiel\Entities\ActeRegistre;
use Modules\Referentiel\Entities\FeuilletRegistre;
use Modules\Notification\Jobs\ValidationacteDecesJob;
use Modules\Referentiel\Entities\TypeDeclarationDeces;


class ActeDecesController extends Controller
{

    public function index()
    {

        $declarations = [];
        $inst = Auth::user()->affectationActive()->institution;


        //MAIRIE
        if($inst->typeInstitution->code_type_institution == "TPINS_0002"){

            $mylocalite = $inst->lieu->code_localite;
            $instDD =  $inst->descendants()->map->declarationsDeces()->flatten();
            $ddloc = DeclarationDeces::where("lieu_deces", $mylocalite)->get();
            $declarations = $instDD->merge($ddloc);
        }

        //POMPES FUNEBRES DE BZ
        if($inst->typeInstitution->code_type_institution == "TPINS_0003"){
            $user = Auth::user();
            $fn = $user->AffectationActive()->fonction->code_fonction;


            if($fn == "FONC_0012"){ //si c'est un directeur des pf

                $declarations = $inst->pompeFunebre->declarationDeces();
            }
            else{
                //si c'est un agent simple
                // $declarations = $user->declarationDeces();
                $declarations = DeclarationDeces::all();
            }

        }

        $fonctionuser = InstitutionUser::where("cui",Auth::user()->affectationActive()->cui)->first();

        $registre = Registre::where("cui",Auth::user()->affectationActive()->cui)->where("code_type_registre","TPRG_0004")->first();
        return view('deces::acte.index',compact("declarations","registre","fonctionuser"));
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

    public function generateActe(Request $request)
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

        $dn = DeclarationDeces::find($request->code_declaration_deces);
        $rn = Registre::where("cui",Auth::user()->affectationActive()->cui)->where("code_type_registre","TPRG_0004")->first();

        if($dn == null){

            return response()->json([
                "code"=>"180",
                "message"=>["error"=>"Cette déclaration de décès n'est pas reconnue"]
            ]);
        }

        if($rn == null){
            return response()->json([
                "code"=>"181",
                "message"=>["error"=>"Aucun registre disponible"]
            ]);
        }

        if($rn->statut == 0){
            return response()->json([
                "code"=>"182",
                "message"=>["error"=>"Ce registre est déjà clôturé"]
            ]);
        }

        if($rn->nombre_acte_prevu == $rn->nombre_acte_transcrit){
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

            $acteDeces = new ActeDeces();
            $acteDeces->code_acte_deces = Sifec::genererCodeUniqueReferentiel($acteDeces,"code_acte_deces",8,"AD_");
            $acteDeces->date_emission = now();
            $acteDeces->code_declaration_deces = $request->code_declaration_deces;
            $acteDeces->code_registre = $rn->code_registre;
            $acteDeces->cui = Auth::user()->affectationActive()->cui;
            $acteDeces->approbation_tribunal = 1;
            $acteDeces->sceau_tribunal = Auth::user()->affectationActive()->institution->institutionParent->sceau;
            $acteDeces->save();


            if(($rn->nombre_acte_transcrit + 1) == $rn->nombre_acte_prevu){
                $rn->statut = 0;
            }
            $rn->nombre_acte_transcrit = $rn->nombre_acte_transcrit + 1;

            $rn->save();

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


    public function sendOtp($id){
        // Log::channel("sifec")->error($id);


        $ad = ActeDeces::where("code_declaration_deces",$id)->first();
        if($ad == null){
            return response()->json([
                "code"=>"180",
                "message"=>"Aucun acte trouvé pour ce code $id"
            ]);
        }

        try {
            $otp = substr(time(),2);

            $temp = config("sifec.sms.templates.actions.validation_acte_deces");
            $temp = str_replace(":pompe_funebre",Auth::user()->personne->nomcomplet(),$temp);
            $temp = str_replace(":code_declaration_deces",$id,$temp);
            $temp = str_replace(":code_otp",$otp,$temp);
            // $sifecObjet = new Sifec;
            $ad->otp_approbation_pompes_funebre = $otp;
            $ad->save();

            //contact du directeur de pompes funebres ou officier d'état civil
            $contact = Auth::user()->personne->contacts->last();

            if($contact != null)
            {
                dispatch(new SendSmsJob($contact->telephone,$temp));//directeur tel
                    // dispatch(new ValidationacteDecesJob(Auth::user()->personne->nomComplet(),$ad->count(),$otp,Auth::user()->personne->contacts->last()->email_professionnelle));
                dispatch(new ValidationacteDecesJob(Auth::user()->personne->nomComplet(),$ad->count(),$otp,"alangetelengo87@gmail.com"));

            }



            return response()->json([
                "code"=>"200",
                "message"=>"SMS envoyé avec succès"
            ]);


        } catch (Exception $e) {
            Log::channel("sifec")->error($e->getMessage());
            return response()->json([
                "code"=>"181",
                "message"=>["error"=>$e->getMessage()]
            ]);
        }

    }

    public function validateOtp(Request $request){
        return response()->json($request->all());

        $rules = [
            "otp_approbation_pompes_funebre"=>["required","numeric"],
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
        $otp = $request->otp_approbation_pompes_funebre;

        $ad = ActeDeces::where("code_declaration_deces",$cdn)->first();
        if($ad == null){
            return response()->json([
                "code"=>"182",
                "message"=>"Aucun acte trouvé pour ce code $cdn"
            ]);
        }

        if($otp != $ad->otp_approbation_pompes_funebre){
            return response()->json([
                "code"=>"183",
                "message"=>"Code de validation incorrect"
            ]);
        }

        try {

            $otp = substr(time(),2);

            $temp = config("sifec.sms.templates.actions.acte_deces");
            $temp = str_replace(":declarant",$ad->declaration->declarant->nomcomplet(),$temp);
            $temp = str_replace(":code_acte_deces",$ad->code_acte_deces,$temp);

            // $sifecObjet = new Sifec;
            $ad->otp_approbation_pompes_funebre = $otp;
            $ad->approbation_pompe_funebre = 1;
            $ad->signature_pompe_funebre = Auth::user()->personne->signature;
            $ad->save();

            $contactDeclarant = $ad->declaration->declarant->contacts->last();

            if($contactDeclarant != null){
                $indicatif = $contactDeclarant->indicatif;

                if($indicatif != "+242"){

                    SifecFacade::infobipSms($contactDeclarant->indicatif.$contactDeclarant->telephone, $temp);

                }else{
                    dispatch(new SendSmsJob($contactDeclarant->indicatif.$contactDeclarant->telephone,$temp));
                    // dispatch(new SendSmsJob("+242066835332",$temp));
                }

                // dispatch(new SendSmsJob($ad->declaration->declarant->telephone,$temp));
            }

            return response()->json([
                "code"=>"200",
                "message"=>"Acte de décès validé avec succès"
            ]);


        } catch (Exception $e) {
            return response()->json([
                "code"=>"183",
                "message"=>["error"=>$e->getMessage()]
            ]);
        }

    }

    public function sendOtpBulk(Request $request){
        $codes = $request->codes;
        $ad = ActeDeces::whereIn("code_declaration_deces",$codes)->get();
        if($ad->count() == 0){
            return response()->json([
                "code"=>"180",
                "message"=>"Aucun acte trouvé"
            ]);
        }

        try {

            $otp = substr(time(),2);

            $temp = config("sifec.sms.templates.actions.validation_multiples_acte_deces");
            $temp = str_replace(":pompe_funebre",Auth::user()->personne->nom,$temp);
            $temp = str_replace(":nombre",$ad->count(),$temp);
            $temp = str_replace(":code_otp",$otp,$temp);

            ActeDeces::whereIn("code_declaration_deces",$codes)->update(["otp_approbation_pompe_funebre"=>$otp]);

            $contact = Auth::user()->personne->contacts->first();
            if($contact != null){
                $indicatif = $contact->indicatif;


                SifecFacade::sendSms($indicatif.$contact->telephone, $temp);
                dispatch(new SendSmsJob($indicatif.$contact->telephone,$temp));
                dispatch(new ValidationacteDecesJob(Auth::user()->personne->nomComplet(),$ad->count(),$otp,$contact->email_professionnelle));
            }

            return response()->json([
                "code"=>"200",
                "message"=>"SMS envoyé avec succès"
            ]);
            // $otp = substr(time(),2);

            // $temp = config("sifec.sms.templates.actions.validation_acte_deces");
            // $temp = config("sifec.sms.templates.actions.validation_multiples_acte_deces");
            // $temp = str_replace(":pompe_funebre",Auth::user()->personne->nomcomplet(),$temp);
            // $temp = str_replace(":nombre",$ad->count(),$temp);
            // $temp = str_replace(":code_otp",$otp,$temp);

            // //insertion du cote otp dans les colonnes de la table
            // ActeDeces::whereIn("code_declaration_deces",$codes)->update(["otp_approbation_pompe_funebre"=>$otp]);

            // //contact du directeur de pompes funebres ou officier d'état civil
            // $contact = Auth::user()->personne->contacts->last();

            // if($contact != null)
            // {
            //     dispatch(new SendSmsJob($contact->telephone,$temp));//directeur tel
            // }

            // return response()->json([
            //     "code"=>"200",
            //     "message"=>"SMS envoyé avec succès"
            // ]);


        } catch (Exception $e) {
            Log::channel("sifec")->error($e->getMessage());
            return response()->json([
                "code"=>"181",
                "message"=>["error" =>$e->getMessage()]
            ]);
        }

    }

    public function validateOtpBulk(Request $request){
        $rules = [
            "otp_approbation_pompe_funebre"=>["required","numeric"],
            "codes"=>["required"]
        ];

        $validator = Validator::make($request->all(),$rules);


        if($validator->fails())
        {
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

        $ad = ActeDeces::whereIn("code_declaration_deces",$codes)->get();


        if($ad->count() == 0)
        {
            return response()->json([
                 "code"=>"182",
                 "message"=>["error"=>"Aucun acte trouvé"]
            ]);
        }

        if($otp != $ad->last()->otp_approbation_pompe_funebre){
            return response()->json([
                "code"=>"183",
                "message"=>["error"=>"Code de validation incorrect ou expiré"]
            ]);
        }

        DB::beginTransaction();
        try {

            // ActeDeces::where("otp_approbation_pompe_funebre",$otp)->update([
            //     "approbation_pompe_funebre"=>1,
            //     "signature_pompe_funebre"=>Auth::user()->personne->signature
            // ]);

            ActeDeces::whereIn("code_declaration_deces",$codes)->update([
                "approbation_pompe_funebre"=> Auth::user()->affectationActive()->cui,
                "signature_pompe_funebre"=> Auth::user()->personne->signature
            ]);

            DB::commit();

            // foreach($ad as $a){
            //     $temp = config("sifec.sms.templates.actions.acte_deces");
            //     $temp = str_replace(":declarant",$a->declaration->declarant->nomcomplet(),$temp);
            //     $temp = str_replace(":code_acte_deces",$a->code_acte_deces,$temp);
            //     $temp = str_replace(":defunt",$a->declaration->defunt->nomcomplet(),$temp);
            //    dispatch(new SendSmsJob($a->declaration->declarant->telephone,$temp));

            //    $contact = $a->declaration->declarant->contacts->first();


            //    if($contact != null){
            //        dispatch(new SendSmsJob($a->declaration->declarant->telephone(),$temp));
            //        dispatch(new ValidationacteDecesJob(Auth::user()->personne->nomComplet(),$ad->count(),$otp,$contact->email_professionnelle));
            //     }

            // }

            foreach($ad as $a){
                $temp = config("sifec.sms.templates.actions.acte_deces");
                $temp = str_replace(":declarant",$a->declaration->declarant->nomcomplet(),$temp);
                $temp = str_replace(":code_acte_deces",$a->code_acte_deces,$temp);
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
                dispatch(new ValidationacteDecesJob(Auth::user()->personne->nomComplet(),$ad->count(),$otp,$contactDeclarant->email_professionnelle));
                // dispatch(new ValidationacteNaissanceJob(Auth::user()->personne->nomComplet(),$an->count(),$otp,"alangetelengo87@gmail.com"));

            }


            // $sifecObjet = new Sifec;
            return response()->json([
                "code"=>"200",
                "message"=>"Actes des décès validés avec succès"
            ]);


        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "code"=>"183",
                "message"=>["error"=>$e->getMessage()]
            ]);
        }

    }


    public function generateActeBulk(Request $request)
    {
        $codes = $request->codes;
        $dd = DeclarationDeces::whereIn("code_declaration_deces",$codes)->get();

        $rd = Auth::user()->affectationActive()->institution->registres()->where("statut",1)
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

        DB::beginTransaction();
        try{

            foreach($dd as $d){
                $acteDeces = new ActeDeces();
                $acteDeces->code_acte_deces = Sifec::genererCodeUniqueReferentiel($acteDeces,"code_acte_deces",8,"AD_");
                $acteDeces->date_emission = now();
                $acteDeces->code_declaration_deces = $d->code_declaration_deces;
                $acteDeces->code_registre = $rd->code_registre;
                $acteDeces->cui = Auth::user()->affectationActive()->cui;
                $acteDeces->approbation_tribunal = 1;
                $acteDeces->sceau_tribunal = Auth::user()->affectationActive()->institution->institutionParent->sceau;
                $acteDeces->save();

                if(($rd->nombre_acte_transcrit + 1) == $rd->nombre_acte_prevu){
                    $rd->statut = 0;
                }

                $position = $rd->nombre_acte_transcrit + 1;
                $rd->nombre_acte_transcrit = $position;
                $rd->save();

                $feuillet = new FeuilletRegistre;
                $feuillet->code_feuillet_registre = Sifec::genererCodeUniqueReferentiel($feuillet, "code_feuillet_registre", 4, "FRE_");
                $feuillet->code_acte = $acteDeces->code_acte_deces;
                $feuillet->numero_acte =  SifecFacade::generate_acte_number($rd, $position);
                $feuillet->save();
            }

            DB::commit();

            return response()->json([
                "code"=>"200",
                "message"=>["reponse"=>"Actes des Décès générés avec succès"]
            ]);


        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                "code"=>"201",
                "message"=>["error"=> $e->getMessage()]
            ]);
        }
    }


    public function etatlistedeces(){
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
         $dn = DeclarationDeces::whereIn("code_declaration_deces",$codes)->get();

         if($dn->count() == 0){

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


}
