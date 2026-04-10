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
use Modules\Mariage\Services\ActeMariageService;
use Modules\Referentiel\Entities\Registre;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Carbon;
use Modules\Mariage\Entities\DeclarationMariage;

class EtatsMariageController extends Controller
{


   public function declaration($id)
   {
        $dm = DeclarationMariage::find($id);

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
        return $html2pdf->output($dm->code_declaration_mariage.".pdf");
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
            "code_declaration_mariage" => ["required", "string", "unique:t_acte_mariage"]
        ];

        $validator = Validator::make($request->all(), $regle);

        if ($validator->fails()) {
            return response()->json([
                "code" => "180",
                "message" => $validator->errors()
            ]);
        }

        try {
            $acteMariageService = new ActeMariageService();

            // Validation de la déclaration
            $declaration = $acteMariageService->validerDeclarationPourActe($request->code_declaration_mariage);

            // Récupération du registre actif
            $registre = $acteMariageService->obtenirRegistreActif();

            // Génération de l'acte
            $acte = $acteMariageService->genererActe($declaration, $registre, Auth::user());

            return response()->json([
                "code" => "200",
                "message" => "Acte de mariage généré avec succès",
                "data" => [
                    "code_acte" => $acte->code_acte_mariage
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                "code" => "201",
                "message" => $e->getMessage()
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
       $requisitions = DeclarationMariage::where("type_declaration","DISPENSE")->get();
        return view('mariage::requisition.index',compact("requisitions"));
   }

   public function generateRequisition(Request $request, $id)
    {
        $dm = DeclarationMariage::find($id);

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

        $requisition = DeclarationMariage::where("code_declaration_mariage",$id)->first();


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
