<?php

namespace Modules\Mariage\Http\Controllers;

use App\Mail\ValidationActeMariageMailable;
use Exception;
use App\Sifec\Sifec;
use App\Sifec\SifecFacade;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Mariage\Entities\ActeMariage;
use Modules\Notification\Jobs\SendSmsJob;
use Modules\Referentiel\Entities\Registre;
use Illuminate\Contracts\Support\Renderable;
use Modules\Mariage\Entities\Declarationmariage;
use Modules\Notification\Jobs\ValidationActeMariageJob;
use Modules\Referentiel\Entities\FeuilletRegistre;
use Illuminate\Support\Facades\Validator;


class ActeMariageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        // $registre = Registre::where("statut",1)->where("code_type_registre","TPRG_0002")->first();
        $registre = Registre::where("code_type_registre","TPRG_0002")->first();

		$declarations = Auth::user()->affectationActive()->institution->declarationsMariages();

        return view('mariage::acte.index',compact("declarations","registre"));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {

         return view('mariage::acte.index');
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
        return view('mariage::acte.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
         return view('mariage::acte.edit');
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

    public function mariageApprouver($id)
    {
        $am = ActeMariage::find($id);

        if($am == null){
            toastr()->error("Vous ne pouvez pas approuver cet acte de mariage");
            return back();
        }

        if( Gate::allows("module.acteMariage.signature")){
            try {
                $otp = substr(time(),2);

                $am->approbation_mairie = Auth::user()->affectationActive()->cui;
                $am->signature_maire = Auth::user()->personne->signature;
                $am->otp_approbation_mairie = $otp;
                $am->save();

                toastr()->success("Acte approuvé avec succès");

                return back();

            } catch (Exception $e) {
                toastr()->error($e->getMessage());
                return back();
            }
        }
    }

    public function validateOtp(Request $request)
    {

        $rules = [
            "otp_approbation_mairie"=>["required","numeric"],
            "code_declaration_mariage"=>["required","string"]
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json([
                "code"=>"180",
                "message"=>"Aucun acte trouvé pour ce code"
            ]);
        }

        if(!Gate::allows("module.acteMariage.signature")){
            return response()->json([
                "code"=>"181",
                "message"=>["error"=>"Vous n'êtes pas autorisé à valider un acte de mariage"]
            ]);
        }

        $cdm = $request->code_declaration_mariage;
        $otp = $request->otp_approbation_mairie;

        $am = ActeMariage::where("code_declaration_mariage",$cdm)->first();
        if($am == null){
            return response()->json([
                "code"=>"182",
                "message"=>"Aucun acte trouvé pour ce code $cdm"
            ]);
        }

        if($otp != $am->otp_approbation_mairie){
            return response()->json([
                "code"=>"183",
                "message"=>"Code otp incorrect"
            ]);
        }

        try {

            $am->otp_approbation_mairie = 1;
            $am->approbation_mairie = Auth::user()->affectationActive()->cui;
            $am->signature = Auth::user()->personne->signature;
            $am->save();

            $otp = substr(time(),2);

            $temp = config("sifec.sms.templates.actions.acte_mariage");
            // $temp = str_replace(":declarant",$am->declaration->declarant->nomcomplet(),$temp);
            $temp = str_replace(":declarant",$am->declaration->epoux->nomcomplet(),$temp);
            $temp = str_replace(":code_acte_mariage",$am->code_acte_mariage,$temp);

            // $sifecObjet = new Sifec;
            $am->otp_approbation_mairie = $otp;
            $am->save();


            $contact = $am->declaration->epoux->contacts->first();

            if($contact != null){
                $indicatif = $contact->indicatif;

                // if($indicatif != "+242"){
                //     SifecFacade::infobipSms($contact->indicatif.$contact->telephone, $temp);
                // }else{
                //     dispatch(new SendSmsJob($contact->indicatif.$contact->telephone,$temp));
                //     // dispatch(new SendSmsJob("+242066835332",$temp));
                // }
                SifecFacade::infobipSms($contact->indicatif.$contact->telephone, $temp);
                dispatch(new SendSmsJob($contact->indicatif.$contact->telephone,$temp));

                // dispatch(new SendSmsJob($contact->telephone,$temp));
                dispatch(new SendSmsJob($am->declaration->epoux->telephone,$temp));

            }


            return response()->json([
                "code"=>"200",
                "message"=>"Acte de mariage validé avec succès"
            ]);


        } catch (Exception $e) {
            return response()->json([
                "code"=>"183",
                "message"=>["error" =>$e->getMessage()]
            ]);
        }

    }


     // Send OTP for bulk validation
     public function sendOtpBulk(Request $request){
        $codes = $request->codes;
        // return response()->json($codes);
        $am = ActeMariage::whereIn("code_declaration_mariage",$codes)->get();
        if($am->count() == 0){
            return response()->json([
                "code"=>"180",
                "message"=>"Aucun acte trouvé"
            ]);
        }

        try {
            $otp = substr(time(),2);

            $temp = config("sifec.sms.templates.actions.validation_multiples_acte_mariages");
            $temp = str_replace(":maire",Auth::user()->personne->nom,$temp);
            $temp = str_replace(":nombre",$am->count(),$temp);
            $temp = str_replace(":code_otp",$otp,$temp);


            ActeMariage::whereIn("code_declaration_mariage",$codes)->update(["otp_approbation_mairie"=>$otp]);

            $contact = Auth::user()->personne->contacts->first();

            if($contact != null){
                $indicatif = $contact->indicatif;

                // if($indicatif != "+242"){
                //     SifecFacade::infobipSms($contact->indicatif.$contact->telephone, $temp);
                // }else{
                //     dispatch(new SendSmsJob($contact->indicatif.$contact->telephone,$temp));
                //     // dispatch(new SendSmsJob("+242066835332",$temp));
                // }
                SifecFacade::infobipSms($contact->indicatif.$contact->telephone, $temp);
                dispatch(new SendSmsJob($contact->indicatif.$contact->telephone,$temp));

                dispatch(new ValidationActeMariageJob(Auth::user()->personne->nomComplet(),$am->count(),$otp,"alangetelengo87@gmail.com"));
                // dispatch(new ValidationActeMariageJob(Auth::user()->personne->nomComplet(), $am->count(), $otp, "alangetelengo87@gmail.com"));
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
        $rules = [
            "otp_approbation_mairie"=>["required","numeric"],
            "codes"=>["required"]
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json([
                "code"=>"180",
                "message"=>"Aucun acte trouvé pour ce code"
            ]);
        }

        if(!Gate::allows("module.acteMariage.signature")){
            return response()->json([
                "code"=>"181",
                "message"=>["error" =>"Vous n'êtes pas autorisé à valider un acte de mariage"]
            ]);
        }

        $codes = $request->codes;
        $otp = $request->otp_approbation_mairie;

        $am = ActeMariage::whereIn("code_declaration_mariage",$codes)->get();
        if($am->count() == 0){
            return response()->json([
                "code"=>"182",
                "message"=>"Aucun acte trouvé"
            ]);
        }

        if($otp != $am->last()->otp_approbation_mairie){
            return response()->json([
                "code"=>"183",
                "message"=>["error" =>"Code otp incorrect"]
            ]);
        }

        DB::beginTransaction();
        try {

            ActeMariage::whereIn("code_declaration_mariage",$codes)->update([
                "approbation_mairie"=> Auth::user()->affectationActive()->cui,
                "signature_maire"=> Auth::user()->personne->signature
            ]);

            DB::commit();


            foreach($am as $a){
                $temp = config("sifec.sms.templates.actions.acte_mariage");
                // $temp = str_replace(":declarant",$a->declaration->declarant->nomcomplet(),$temp);
                $temp = str_replace(":declarant",$a->declaration->epoux->nomcomplet(),$temp);
                $temp = str_replace(":code_acte_mariage",$a->code_acte_mariage,$temp);
                // dispatch(new SendSmsJob($a->declaration->declarant->telephone,$temp));
                dispatch(new SendSmsJob($a->declaration->epoux->telephone,$temp));
                // $contact = $a->declaration->declarant->contacts->first();
                $contact = $a->declaration->epoux->contacts->first();


                if($contact != null){
                    // dispatch(new SendSmsJob($a->declaration->declarant->telephone(),$temp));
                    dispatch(new SendSmsJob($a->declaration->epoux->telephone(),$temp));
                    dispatch(new ValidationActeMariageJob(Auth::user()->personne->nomComplet(),$am->count(),$otp,$contact->email_professionnelle));
                }

            }

            // $sifecObjet = new Sifec;
            return response()->json([
                "code"=>"200",
                "message"=>"Acte(s) de mariage validé(s) avec succès"
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
         $dm = Declarationmariage::whereIn("code_declaration_mariage",$codes)->get();
         $rn = Registre::where("statut",1)->where("code_type_registre","TPRG_0002")->first();

         if($dm->count() == 0){

             return response()->json([
                 "code"=>"180",
                 "message"=>["error"=>"Aucune déclaration à générer"]
             ]);
         }

         if(! Gate::allows("module.acteMariage.generate")){

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

         if($rn->nombre_acte_prevu == $rn->nombre_acte_transcrit){
             return response()->json([
                 "code"=>"184",
                 "message"=>["error"=>"Ce registre a déjà atteint le nombre d'actes prévu"]
             ]);
         }

         DB::beginTransaction();
         try{

             foreach($dm as $d){
                 $acteMariage = new ActeMariage;
                 $acteMariage->code_acte_mariage = Sifec::genererCodeUniqueReferentiel($acteMariage,"code_acte_mariage",8,"AM_");
                 $acteMariage->date_emission = now();
                 $acteMariage->code_declaration_mariage = $d->code_declaration_mariage;
                 $acteMariage->code_registre = $rn->code_registre;
                 $acteMariage->cui = Auth::user()->affectationActive()->cui;
                 $acteMariage->approbation_tribunal = 1;
                 $acteMariage->sceau_tribunal = Auth::user()->affectationActive()->institution->institutionParent->sceau;
                 $acteMariage->save();

                 if(($rn->nombre_acte_transcrit + 1) == $rn->nombre_acte_prevu){
                     $rn->statut = 0;
                 }

                 $position = $rn->nombre_acte_transcrit + 1;
                 $rn->nombre_acte_transcrit = $position;

                 $rn->save();

                 $feuillet = new FeuilletRegistre;
                 $feuillet->code_feuillet_registre = Sifec::genererCodeUniqueReferentiel($feuillet, "code_feuillet_registre", 4, "FRE_");
                 $feuillet->code_acte = $acteMariage->code_acte_mariage;
                 $feuillet->numero_acte =  SifecFacade::generate_acte_number($rn, $position);
                 $feuillet->save();
             }

             DB::commit();

             return response()->json([
                 "code"=>"200",
                 "message"=>["reponse"=>"Actes des mariages générés avec succès"]
             ]);


         }catch(Exception $e){
             DB::rollBack();
             return response()->json([
                 "code"=>"201",
                 "message"=>["error"=> $e->getMessage()]
             ]);
         }
     }

     public function searchActe($id)
     {
        $acte = ActeMariage::with(["declaration.optionMariage"])->where("code_acte_mariage",$id)->first();
        if($acte !== null){
            if($acte->declaration->optionMariage->code_option_mariage == "OPM_0001"){
                return response()->json([
                    "code"=>"99",
                    "message"=>["optionMariage"=>"Il semble que l'époux soit déjà marié avec l'option <strong>Monogamie</strong>, au cas où il serait divorcé, alors veuillez présenter le jugement du divorce ou bien l'acte de décès de son épouse"]
                ]);
            }
            // return response()->json([
            //     "code"=>"200",
            //     "message"=>["optionMariage"=>$acte->declaration->optionMariage->code_option_mariage]
            // ]);
        }
     }


     public function repertoire()
     {
        return view('mariage::acte.repertoire');
     }


    public function repertoireetat()
    {
        $dated = request('dated');
        $datef = request('datef');

        if ($dated == null && $datef == null) {
            $actes = ActeMariage::where('cui', Auth::user()->affectationActive()->cui)->get();
        }

        if ($dated != null && $datef == null) {
            $actes = ActeMariage::where('cui', Auth::user()->affectationActive()->cui)->whereBetween('date_emission', [$dated, $dated])->get();
        }

        if ($dated == null && $datef != null) {
            $actes = ActeMariage::where('cui', Auth::user()->affectationActive()->cui)->whereBetween('date_emission', [$datef, $datef])->get();
        }

        if ($dated != null && $datef != null) {
            $actes = ActeMariage::where('cui', Auth::user()->affectationActive()->cui)->whereBetween('date_emission', [$dated, $datef])->get();
        }

        if ($actes == null) {
            toastr()->warning('Aucune donnée trouvée');
            return back();
        }

        view()->share("tester", "Vincent");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('mariage::etats.repertoire', compact("actes","dated", "datef"))->render());

        return $html2pdf->output("repertoireAlpha.pdf");
    }


     // poser le visa du Sécrétaire général de la mairie sur les actes
     public function viserActeBulk(Request $request)
     {
         $codes = $request->codes;
         $dn = Declarationmariage::whereIn("code_declaration_mariage",$codes)->get();

         if($dn->count() == 0){

             return response()->json([
                 "code"=>"180",
                 "message"=>["error"=>"Aucun document à générer"]
             ]);
         }

         if( ! Gate::allows("module.acteMariage.viser")){

             return response()->json([
                 "code"=>"181",
                 "message"=>["error"=>"Vous n'êtes pas autorisé à viser un acte"]
             ]);
         }

         DB::beginTransaction();
         try {

             ActeMariage::whereIn("code_declaration_mariage",$codes)->update([
                 "approbation_secretaire_general"=> Auth::user()->affectationActive()->cui,
                 "visa_secretaire_general"=> Auth::user()->personne->signature
             ]);

             DB::commit();

               return response()->json([
                 "code"=>"200",
                 "message"=>"Acte(s) de mariage visés avec succès"
             ]);


         } catch (Exception $e) {
             DB::rollBack();
             return response()->json([
                 "code"=>"183",
                 "message"=>["error"=>$e->getMessage()]
             ]);
         }

     }

}
