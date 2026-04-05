<?php

namespace Modules\Reporting\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Reporting\Entities\Copie;
use Illuminate\Contracts\Support\Renderable;
use Modules\Deces\Entities\ActeDeces;
use Modules\Mariage\Entities\ActeMariage;
use Modules\Naissance\Entities\ActeNaissance;

class ReportingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('reporting::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('reporting::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('reporting::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('reporting::edit');
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

    public function genererCopie(Request $request)
    {
        $numActe = ActeNaissance::findByIdentifier($request->numero_acte) ?? ActeDeces::find($request->numero_acte) ?? ActeMariage::find($request->numero_acte);

        return response()->json($request->all());

        if($numActe == null || $numActe == ""){
            return response()->json([
                "code"=>"180",
                "message"=>"Aucun acte trouvé pour ce numéro"
            ]);
        }

        try {

            $copie = new Copie;
            $copie->numero_acte = $request->numero_acte;
            $copie->new_nom = $request->new_nom;
            $copie->new_prenom = $request->new_prenom;
            $copie->new_date_naissance = $request->new_date_naissance;
            $copie->reference_document = $request->reference_document;
            $copie->date_document = $request->date_document;
            $copie->libelle_document = $request->libelle_document;
            $copie->lieu_delivrance_document = $request->lieu_delivrance_document;
            $copie->signature_officier = $request->signature_officier;
            $copie->nom_officier = $request->nom_officier;
            $copie->save();

            return response()->json([
                "code"=>"200",
                "message"=>["reponse"=>"Copie d'acte générée avec succès"]
            ]);

        } catch (Exception $e) {
            Log::channel("sifec")->error($e->getMessage());
            return response()->json([
                "code"=> "201",
                "message"=> $e->getMessage()
            ]);
        }
    }


    public function dashbordRecette()
    {
        return view("reporting::rapports.index");
    }
}
