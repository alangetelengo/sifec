<?php

namespace Modules\Deces\Http\Controllers;

use Exception;
use App\Sifec\Sifec;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Deces\Entities\ActeDeces;
use Modules\Referentiel\Entities\Registre;
use Illuminate\Contracts\Support\Renderable;
use Modules\Deces\Entities\DeclarationDeces;

class RequisitionTranscriptionDecesController extends Controller
{
    public function index()
    {
        // $typeDeclaration = "CERTIFICAT DE TRANSCRIPTION";
        $typeDeclaration = "CERTIFICAT DE NON INSCRIPTION DE DECES";
        $requisitions = DeclarationDeces::where(["type_declaration"=>$typeDeclaration])->get();
        $registre = Registre::where(["statut"=>1,"code_type_registre"=>"TPRG_0004"])->first();

        return view('deces::requisition-transcription.index', compact('requisitions','registre'));
    }

    public function etat($id)
    {
        $requisition = DeclarationDeces::find($id);

        if($requisition == null){
            toastr()->error("Requisition indisponible");
            return back();
        }

        view()->share("tester", "Vincent");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('deces::etats.requisitions.requisition_transcription', compact("requisition"))->render());

        return $html2pdf->output($requisition->code_declaration_deces.".pdf");

    }

    public function generateRequisition(Request $request, $id)
    {
        $certificat = DeclarationDeces::find($id);

        if($certificat == null){
            toastr()->error("Certificat indisponible");
            return back();
        }
        if( $certificat->top_requisition == 1){
            toastr()->error("Cette réquisition existe déjà pour ce certificat de destruction");
            return back();
        }


        try{
            DB::beginTransaction();

            $certificat->top_requisition = 1;
            $certificat->numero_req = Sifec::genererCodeUniqueReferentiel($certificat,"numero_req",4,"");
            $certificat->save();

            DB::commit();
            toastr()->success("La réquisition a été créee avec succès");
            return back();

        }catch(Exception $e){
            DB::rollBack();
            toastr()->error($e->getMessage());
            return back();

        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('deces::create');
    }

    public function generateActe(Request $request)
    {

        $dn = DeclarationDeces::find($request->code_declaration_deces);
        $rn = Registre::where("cui",Auth::user()->affectationActive()->cui)->where("code_type_registre","TPRG_0001")->first();

        if($dn == null){

            return response()->json([
                "code"=>"180",
                "message"=>["error"=>"Cette déclaration de naisance n'est pas reconnue"]
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

        DB::beginTransaction();
        try{

            $acteDeces = new ActeDeces();
            $acteDeces->niupp = Sifec::genererCodeUniqueReferentiel($acteDeces,"niupp",8,"AN_");
            $acteDeces->date_emission = now();
            $acteDeces->code_declaration_deces = $request->code_declaration_deces;
            $acteDeces->code_registre = $rn->code_registre;
            $acteDeces->cui = Auth::user()->affectationActive()->cui;
            $acteDeces->save();

            if(($rn->nombre_acte_transcrit + 1) == $rn->nombre_acte_prevu){
                $rn->statut = 0;
            }
            $rn->nombre_acte_transcrit = $rn->nombre_acte_transcrit + 1;

            $rn->save();

            DB::commit();

            toastr()->success("Acte deces généré avec succès");

            return response()->json([
                "code"=>"200",
                "message"=>["reponse"=>"Acte deces généré avec succès"]
            ]);


        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                "code"=>"201",
                "message"=>["error"=> $e->getMessage()]
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
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('deces::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('deces::edit');
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
}
