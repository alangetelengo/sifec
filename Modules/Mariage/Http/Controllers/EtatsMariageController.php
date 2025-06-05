<?php

namespace Modules\Mariage\Http\Controllers;

use App\Sifec\Sifec;
use Exception;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Modules\Mariage\Entities\ActeMariage;
use Modules\Referentiel\Entities\Registre;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Carbon;
use Modules\Mariage\Entities\Declarationmariage;

class EtatsMariageController extends Controller
{


   public function declaration($id)
   {
        $dm = Declarationmariage::find($id);

        if($dm == null){
            toastr()->error("Impossible de charger cette page");
            return back();
        }
        $mention = "";
        $dateDeclaration = Carbon::create($dm->date_declaration_mariage);
        $dateMariage = Carbon::create($dm->date_prevue_mariage);
        $diffJours = $dateMariage->diffInDays($dateDeclaration);

        if($diffJours < 60 || $dm->lieu_ceremonie_mariage == "Hors centre d'état civil" ){
            $mention = "Cette déclaration est soumise à une réquisition";
        }

        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');

        $html2pdf->writeHTML(view('mariage::etats.declarationMariage', compact("dm","mention"))->render());
        return $html2pdf->output($dm->code_declaration_naissance.".pdf");
    }

   public function reqDelaiLieu()
   {
        $actes = "";

        view()->share("tester", "Vincent");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('mariage::etats.requisitiondelailieu', compact("actes"))->render());

        return $html2pdf->output("Requisition.pdf");
   }

   public function reqDelai()
   {
        $actes = "";

        view()->share("tester", "Vincent");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('mariage::etats.requisitiondelai', compact("actes"))->render());

        return $html2pdf->output("Requisition.pdf");
   }

   public function reqLieu()
   {
        $actes = "";

        view()->share("tester", "Vincent");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('mariage::etats.requisitionlieu', compact("actes"))->render());

        return $html2pdf->output("Requisition.pdf");
   }

   public function certifCoutume()
   {
        $actes = "";

        view()->share("tester", "Vincent");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('mariage::etats.CertificatCoutume', compact("actes"))->render());

        return $html2pdf->output("certificat.pdf");
   }

   public function publication()
   {
        $actes = "";

        view()->share("tester", "Vincent");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('mariage::etats.Publication', compact("actes"))->render());

        return $html2pdf->output("publication.pdf");
   }

   public function attestationdote()
   {
        $actes = "";

        view()->share("tester", "Vincent");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('mariage::etats.Attestationdote', compact("actes"))->render());

        return $html2pdf->output("attestation.pdf");
   }

   public function generateActe(Request $request)
   {
        $regle = [
            "code_declaration_mariage"=> ["required","string","unique:t_acte_mariage"]
        ];

        $validator = Validator::make($request->all(),$regle);

        if($validator->failed()){
            return response()->json([
                "code"=>"180",
                "message"=>["error"=>$validator->errors()]
            ]);
        }
        $dm = Declarationmariage::find($request->code_declaration_mariage);
        $rn = Registre::where("statut",1)->where("code_type_registre","TPRG_0002")->first();

        if($dm == null){

            return response()->json([
                "code"=>"180",
                "message"=>["error"=>"Cette déclaration de mariage n'est pas reconnue"]
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

        if( ! Gate::allows("module.acteMariage.generate")){

            return response()->json([
                "code"=>"180",
                "message"=>["error"=>"Vous n'êtes pas autorisé à effectuer cette opération"]
            ]);
        }

        DB::beginTransaction();

        try {
            $acte = new ActeMariage;
            $acte->code_acte_mariage = Sifec::genererCodeUniqueReferentiel($acte,'code_acte_mariage',8,"AM_");
            $acte->date_emission = now();
            $acte->code_declaration_mariage = $request->code_declaration_mariage;
            $acte->code_registre = $rn->code_registre;
            $acte->cui = Auth::user()->affectationActive()->cui;
            $acte->approbation_tribunal = 1;
            $acte->sceau_tribunal = Auth::user()->affectationActive()->institution->institutionParent->sceau;
            $acte->save();

            if(($rn->nombre_acte_transcrit + 1) == $rn->nombre_acte_prevu){
                $rn->statut = 0;
            }
            $rn->nombre_acte_transcrit = $rn->nombre_acte_transcrit + 1;

            $rn->save();

            DB::commit();

            return response()->json([
                "code"=>"200",
                "message"=>["reponse"=>"Acte de mariage généré avec succès"]
            ]);


        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                "code"=>"201",
                "message"=>["error"=> $e->getMessage()]
            ]);
        }

   }

   public function displayActe($id)
   {
        $acte = ActeMariage::where("code_declaration_mariage",$id)->first();

        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('P', 'A3', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('mariage::etats.ActeMariageEtat', compact("acte"))->render());

        return $html2pdf->output("ActeMariage.pdf");
   }

   public function requisition()
   {
       $requisitions = Declarationmariage::where("type_declaration","DISPENSE")->get();
        return view('mariage::requisition.index',compact("requisitions"));
   }

   public function generateRequisition(Request $request, $id)
    {
        $dm = Declarationmariage::find($id);

        if($dm == null){
            toastr()->error("Cette déclaration n'existe pas !");
            return back();
        }

        if($dm == null){
            toastr()->error("Déclaration indisponible");
            return back();
        }
        if( $dm->top_requisition == 1){
            toastr()->error("Cette réquisition existe déjà pour cette déclaration de mariage");
            return back();
        }

        try{
            DB::beginTransaction();

            $titreRequisition = "";
            $dateDeclaration = Carbon::create($dm->date_declaration_mariage);
            $dateMariage = Carbon::create($dm->date_prevue_mariage);
            $diffJours = $dateMariage->diffInDays($dateDeclaration);

            if($diffJours < 60 ){
                $titreRequisition = "REQUISITION AUX FINS DE DISPENSE DE PUBLICATION DE BANS ET DE DELAI DE CELEBRATION DU MARIAGE";
            }
            if($dm->lieu_ceremonie_mariage == "Hors centre d'état civil" ){
                $titreRequisition = "REQUISITION AUX FINS DE DISPENSE DE PUBLICATION DE BANS ET DE LIEU DE CELEBRATION DU MARIAGE";
            }
            if($diffJours < 60 && $dm->lieu_ceremonie_mariage == "Hors centre d'état civil" ){
                $titreRequisition = "REQUISITION AUX FINS DE DISPENSE DE PUBLICATION DE BANS,DE DELAI ET DU LIEU DE CELEBRATION DU MARIAGE";
            }

            $dm->top_requisition = 1;
            $dm->numero_dispense = Sifec::genererCodeUniqueReferentiel($dm,"numero_dispense",4,"");
            $dm->titre_requisition = $titreRequisition;
            $dm->save();

            DB::commit();
            toastr()->success("La réquisition a été créee avec succès");
            return back();

        }catch(Exception $e){
            DB::rollBack();
            toastr()->error($e->getMessage());
            return back();

        }

    }

    public function displayRequisition($id)
    {

        $requisition = Declarationmariage::where("code_declaration_mariage",$id)->first();


        if($requisition->titre_requisition == "REQUISITION AUX FINS DE DISPENSE DE PUBLICATION DE BANS ET DE DELAI DE CELEBRATION DU MARIAGE"){
            view()->share("tester", "Alange");
            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML(view('mariage::etats.requisitiondelai', compact("requisition"))->render());
            return $html2pdf->output("requisition.pdf");
        }

        if($requisition->titre_requisition == "REQUISITION AUX FINS DE DISPENSE DE PUBLICATION DE BANS ET DE LIEU DE CELEBRATION DU MARIAGE"){
            view()->share("tester", "Alange");
            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML(view('mariage::etats.requisitionlieu', compact("requisition"))->render());
            return $html2pdf->output("requisition.pdf");
        }

        if($requisition->titre_requisition == "REQUISITION AUX FINS DE DISPENSE DE PUBLICATION DE BANS,DE DELAI ET DU LIEU DE CELEBRATION DU MARIAGE"){
            view()->share("tester", "Alange");
            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML(view('mariage::etats.requisitiondelailieu', compact("requisition"))->render());
            return $html2pdf->output("requisition.pdf");
        }


    }


    public function livretFamilles()
    {
        $acteMariages = ActeMariage::all();

        return view('mariage::livret-famille.index', compact("acteMariages"));
    }

    public function livretFamille($id)
    {
        $am = ActeMariage::find($id);

        return view('mariage::livret-famille.maquette', compact("am"));
    }


}
