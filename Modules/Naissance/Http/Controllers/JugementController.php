<?php

namespace Modules\Naissance\Http\Controllers;

use Exception;
use App\Sifec\Sifec;
use App\Models\Jugement;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Renderable;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Referentiel\Entities\Institution;
use Modules\Naissance\Entities\MouvementNaissance;
use Modules\Naissance\Entities\Declarationnaissance;

class JugementController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        $jugements = [];
        $cecsDestinations = [];
        $institution = Auth::user()->institution();

        //CAS MAIRIE
        if($institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins == "TCINS_0001"){//si c'est un cec
            //recuperer la liste des jugements venant du tribunal
            $jugements = Jugement::where("statut_document","Envoye")->where("send_to",$institution->code_institution)->get();
        }
        //CAS TRIBUNAL
        if($institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins == "TCINS_0002"){//si c'est un tribunal
            //recuperer les jugements crées par le tribunal
            $jugementsT = $institution->tousJugements();
            //recuperer les jugement crées par cec lors d'enregistrement de certificat de non inscription de l'age de l'enfant > 90 jours sans déclarer
            $jugementsCec = $institution->descendants()->where("code_type_institution","TPINS_0002")->map->jugements->flatten()->where("cui",null);

            $jugements = $jugementsT->merge($jugementsCec);
            //recuperer la liste des cec sous tutel du tribunal
            $cecsDestinations = Auth::user()->affectationActive()->institution->descendants()->where("code_type_institution","TPINS_0002");
        }

        return view('naissance::jugement.index', compact("jugements","cecsDestinations"));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($type)
    {
        $type_jugement = "JUGEMENT ".strtoupper($type);

        return view('naissance::jugement.create',compact("type_jugement"));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            "num_jugement"=> ["required"],
            "cui"=> ["required"],
            "date_jugement"=> ["required"],
            "document_jugement"=> ["required"],
        ]);

        if($request->numero_ancien_acte != ""){
            //Vérification de ce numero dans la table jugement
            $oldJugement = Jugement::where("numero_ancien_acte", $request->numero_ancien_acte)->first();
            if($oldJugement != null){
                if($request->type_jugement == "JUGEMENT D'HOMOLOGATION" || $request->type_jugement == "JUGEMENT D'ANNULATION D'ACTE" || $request->type_jugement == "JUGEMENT D'ADOPTION"){
                    toastr()->error("Vous ne pouver enregistrer un autre jugement pour ce même numéro d'acte");
                    return back()->withInput();
                }
            }
        }

        try {

            $jugement = new Jugement;
            $jugement->code_jugement = Sifec::genererCodeUniqueReferentiel($jugement, 'code_jugement', 4, "CJUG_");
            $jugement->num_jugement = $request->num_jugement;
            $jugement->cui = $request->cui;
            $jugement->type_jugement = $request->type_jugement;
            $jugement->date_jugement = $request->date_jugement;
            $document_jugement = $request->document_jugement->store("document");
            $jugement->document_jugement = $document_jugement;
            $jugement->numero_ancien_acte = $request->numero_ancien_acte;
            $jugement->save();

            toastr()->success("Document enregistré avec succès");
            return redirect()->back();

        } catch (Exception $e) {
            Log::channel("sifec")->error($e->getMessage());
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $jugement = Jugement::find($id);
        if($jugement == null){
            toastr()->error("Impossible de Charger cette page");
            return back();
        }

        return view('naissance::jugement.show', compact("jugement"));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $jugmt = Jugement::find($id);
        if($jugmt == null){
            toastr()->error("Impossible de charger cette page");
            return false;
        }

        return view('naissance::jugement.edit',compact("jugmt"));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            "num_jugement"=> ["required"],
            "cui"=> ["required"],
            "date_jugement"=> ["required"],
            "document_jugement"=> ["required"],
        ]);

        $jugement = Jugement::find($id);
        if($jugement == null){
            toastr()->error("Impossible de charger cette page");
            return false;
        }

        try {


            $jugement->num_jugement = $request->num_jugement;
            $jugement->cui = $request->cui;
            $jugement->date_jugement = $request->date_jugement;
            $document_jugement = $request->document_jugement->store("document");
            $jugement->document_jugement = $document_jugement;
            $jugement->save();

            toastr()->success("Document enregistré avec succès");
            return redirect()->back();

        } catch (Exception $e) {
            Log::channel("sifec")->error($e->getMessage());
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    //envoyer le jugement au cec concerné
    public function send(Request $request)
    {


        $jugement = Jugement::find($request->code_jugement);

        if($jugement == null){
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

            $jugement->statut_document = "Envoye";
            $jugement->send_to = $request->send_to;
            $jugement->save();

            if($jugement->type_jugement == "JUGEMENT D'AUTORISATION"){


                $mouvement = new MouvementNaissance;
                $mouvement->code_mouvement_naissance = Sifec::genererCodeUniqueReferentiel($mouvement,"code_mouvement_naissance",4,"MDN_");

                $lastMouvement = $jugement->declarationNaissance->mouvements->last();
                $dn = Declarationnaissance::find($jugement->declarationNaissance->code_declaration_naissance);

                if($lastMouvement->statut == "En cours"){
                    $mouvement->statut = "Envoyée";
                    $mouvement->code_declaration_naissance = $jugement->declarationNaissance->code_declaration_naissance;
                    $mouvement->cui = Auth::user()->affectationActive()->cui;
                    $mouvement->save();

                    $dn->approuver = "OUI";
                    $dn->save();

                }
            }
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
