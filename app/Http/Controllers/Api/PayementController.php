<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DemandePortailParticulier;
use App\Sifec\Sifec;
use App\Sifec\SifecFacade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PayementController extends Controller
{

    public function paiement(Request $request)
    {
        //return $request->code_demande;
        //récupération typeDocument
        //$typeActe = $request->type_acte;
        //récupération quantité
        //$exemplaire = $request->nombre_exemplaire;
        //récupération du montant à payer
        //$amount = $request->montant;
        //récupération du mode de paiement
        $moyenPaiement = $request->moyen_paiement;


        //paiement par mtn mobile money
        if($moyenPaiement == "mtn"){
            //récuépration des informations du paiement
            $montant = $request->montant;
            $numero = $request->numero_momo;
            $codeDemande = $request->code_demande;
            $paiementDemande = DemandePortailParticulier::find($codeDemande);
            //recuperer la clé de transaction de paiement
            $transid = SifecFacade::paiement($numero,"10");
            // Log::channel("sifec")->info(["transid"=>$keyTransaction]);
            // dd($keyTransaction);
            return view('paiementMtnAttente',compact("paiementDemande","transid"));
        }

    }

    public function statutPaiementMomo(Request $request)
    {
        //requete de verification du statut de paiement
        $statutPaiement = SifecFacade::statutPaiement($request->trans_id);
        $statut = $statutPaiement->status;
       return $statut;

    }


    public function rdcpaiement(Request $request)
    {
        $rules = [
            "phone"=>"required|digits:10|numeric",
            "amount"=>"required|numeric",
            "code_paiement_document"=>"required",
            // "code_paiement_document"=>"required|exists:paiement_documents,code_paiement_document",
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
           return response()->json([
            "success"=>false,
            "message"=>collect($validator->errors())->flatten()
           ],400);
        }

        $phone = $request->phone;
        "243".substr($phone,1);

        SifecFacade::transact($phone,"1",);

    }

}
