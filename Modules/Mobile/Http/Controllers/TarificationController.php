<?php

namespace Modules\Mobile\Http\Controllers;

use App\Sifec\Sifec;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Mobile\Entities\Tarificatrion;
use Illuminate\Contracts\Support\Renderable;

class TarificationController extends Controller
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
            "code_type_acte" => ["required","string"],
            "code_type_document_demande" => ["required","string"],
            "cui" => ["required","string"],
            "prix" => ["cui"]
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json([
                "code"=>"180",
                "message"=>"Il y a des erreurs à corriger",
                "errors"=>$validator->errors()
            ]);
        }

        try {
            DB::transaction();

            $tarification = new Tarificatrion();
            $tarification->code_tarification = Sifec::genererCodeUniqueReferentiel($tarification,"code_tarification",4,"TAR_");
            $tarification->code_type_acte = $request->code_type_acte;
            $tarification->code_type_document_demande = $request->code_type_document_demande;
            $tarification->prix = $request->prix;
            $tarification->cui = Auth::user()->affectationActive()->cui;
            $tarification->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "code"=>"181",
                "message"=> $e->getMessage(),
            ]);
        }

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
