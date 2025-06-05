<?php

namespace Modules\Naissance\Http\Controllers;

use Exception;
use App\Sifec\Sifec;
use App\Models\Jugement;
use App\Models\Requisition;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use Illuminate\Support\Carbon;
use App\Models\InstitutionUser;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Referentiel\Entities\Registre;
use Illuminate\Contracts\Support\Renderable;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Referentiel\Entities\ActeRegistre;
use Modules\Naissance\Entities\MouvementNaissance;
use Modules\Naissance\Entities\Declarationnaissance;

class RequisitionController extends Controller
{
    public function index()
    {

        $requisitions = [];
        // $cecsDestinations = [];
        $institution = Auth::user()->institution();

        // $cecsDestinations = Auth::user()->affectationActive()->institution->descendants()->where("code_type_institution","TPINS_0002");
        $requisitions =  Auth::user()->institution()->descendants()->map->requisitions->flatten();

        return view('naissance::requisition.index', compact("requisitions"));

    }

    public function create($id)
    {

        $type_requisition = $id;
        return view('naissance::requisition.create',compact("type_requisition"));
    }

    public function edit($id)
    {
        $req = Requisition::find($id);
        if($req == null){
            toastr()->error("Impossible de charger cette page");
            return false;
        }

        return view('naissance::requisition.create',compact("req"));
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            "num_requisition"=> ["required"],
            "cui"=> ["required"],
            "date_requisition"=> ["required"],
            "document_requisition"=> ["required"],
        ]);

        $requisition = Requisition::find($id);
        if($requisition == null){
            toastr()->error("Impossible de charger cette page");
            return false;
        }

        $typeReq = "";

        if($requisition->declarationNaissance !=null && $requisition->declarationNaissance->type_declaration == "CERTIFICAT DE NON INSCRIPTION"){

            $typeReq = "requisition aux fins de déclaration tardive";
        }
        if($requisition->declarationNaissance !=null && $requisition->declarationNaissance->type_declaration == "CERTIFICAT DE DESTRUCTION DE L'ACTE"){
            $typeReq = "requisition aux fins de reconstitution de l'acte";
        }
        if($requisition->declarationNaissance !=null && $requisition->declarationNaissance->type_declaration == "FICHE DE TRANSCRIPTION"){
            $typeReq = "requisition aux fins de transcription de l'acte";
        }

        if($requisition->rectification != null){
            $typeReq = "requisition aux fins de rectification de l'acte";
        }
        // dd($typeReq);

        try {

            $requisition->num_requisition = $request->num_requisition;
            $requisition->cui = $request->cui;
            $requisition->type_requisition = $typeReq;
            $requisition->date_requisition = $request->date_requisition;
            $document_requisition = $request->document_requisition->store("requisition");
            $requisition->document_requisition = $document_requisition;
            $requisition->save();

            //si la requisition concerne une déclaration
            if($requisition->declarationNaissance != null){
                $dn = Declarationnaissance::where("code_requisition",$id)->first();
                $dn->numero_req = $request->num_requisition;
                $dn->save();
            }


            toastr()->success("Document enregistré avec succès");
            return redirect()->route("requisition.index");

        } catch (Exception $e) {
            Log::channel("sifec")->error($e->getMessage());
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

      //envoyer la requisition au cec concerné
      public function send(Request $request)
      {
        //Log::channel("sifec")->info(["send"=>$request->all()]);
        dd($request->all());
        // array (
        //     'code_requisition' => 'CREQ_0001',
        //     'send_to' => 'INS_0047',
        //   ),
          $requisition = Requisition::find($request->code_requisition);
          $dn = Declarationnaissance::find($request->cdn);

          if($requisition == null){
              toastr()->error("Impossible de Charger cette page");
              return back();
          }

          $rule = [
              "send_to" => ["required","string"]
          ];

          $validator = Validator::make($request->all(),$rule);

          if($validator->fails()){
              return response()->json([
                  "code"=>"180",
                  "message"=>"Veuiller selectionner un centre d'état civil"
              ]);
          }

          DB::beginTransaction();
          try {

              $requisition->statut_document = "Envoyée";
              $requisition->send_to = $request->send_to;
              $requisition->save();

              $lastMouvement = $dn->mouvements->last();

            $transaction = new MouvementNaissance;
            $transaction->code_mouvement_naissance = Sifec::genererCodeUniqueReferentiel($transaction,"code_mouvement_naissance",4,"MDN_");

            // if($lastMouvement->statut == "En cours"){
            //     $transaction->statut = "Envoyée";
            // }
            if($lastMouvement->statut == "Envoye au tribunal"){
                $transaction->statut = "Envoyée";
            }
            if($lastMouvement->statut == "Renvoyée"){
                $transaction->statut = "Envoye au tribunal";
            }
            $transaction->code_declaration_naissance = $request->cdn;
            $transaction->cui = Auth::user()->affectationActive()->cui;
            $transaction->observation = "Réquisition créee avec succès";
            $transaction->save();

            DB::commit();
              return response()->json([
                  "code"=>"200",
                  "message"=>"Ce document a été envoyé avec succès"
              ]);

          } catch (Exception $e) {
            DB::rollBack();
              return response()->json([
                  "code"=>"183",
                  "message"=>["error"=>$e->getMessage()]
              ]);
          }
      }


      public function show($id)
      {
            $requisition = Requisition::find($id);
            if($requisition == null){
                toastr()->error("Impossible de charger cette page");
                return false;
            }

            return view("naissance::requisition.show", compact("requisition"));

      }

}
