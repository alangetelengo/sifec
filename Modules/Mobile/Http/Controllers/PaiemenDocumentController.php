<?php

namespace Modules\Mobile\Http\Controllers;

use App\Sifec\Sifec;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Renderable;
use Modules\Mobile\Entities\PaiementDocument;

class PaiemenDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('mobile::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('mobile::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $rules = [
            "code_demande_document" => ["required","string"],
            "prix" => ["required"],
            "canal_paiement" => ["required"],
            "numero_paiement" => ["required"],
            "statut_paiement" => ["required"]
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->failed()){
            return response()->json([
                "code"=>"180",
                "message" => "Il y a des erreurs à corriger",
                "errors" => $validator->errors()
            ]);
        }

        try {
            DB::transaction();

            $paiementDocument = new PaiementDocument();
            $paiementDocument->code_paiement_document = Sifec::genererCodeUniqueReferentiel($paiementDocument,"code_paiement_document",4,"PDC_");
            $paiementDocument->code_demande_document = $request->code_demande_document;
            $paiementDocument->prix = $request->prix;
            $paiementDocument->canal_paiement = $request->canal_paiement;
            $paiementDocument->numero_paiement = $request->numero_paiement;
            $paiementDocument->statut_paiement = $request->statut_paiement;
            $paiementDocument->save();

            DB::commit();

        } catch (Exception $e) {
            return response()->json([
                "code" => "181",
                "message" => $e->getMessage()
            ]);
        }
        //
        // $total = (int) ($commande->commande_owner_pays_delivery + $commande->commande_owner_pays_pickup);
        // $uui4 = Uuid::generate(4)->string;
        // $payData = ["amount"=>"50","invoice_code"=>$commande->invoice_number,"number"=>$request->pay_telephone,"uui4"=>$uui4,"payer_message"=>"Paiement commande","payee_message"=>"Commande Nokinoki","commande_id"=>$commande->id];
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('mobile::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('mobile::edit');
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
}
