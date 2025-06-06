<?php

namespace Modules\Rectification\Http\Controllers;

use App\Models\Requisition;
use Exception;
use App\Sifec\Sifec;
use App\Sifec\SifecFacade;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Tag\Svg\Rect;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Deces\Entities\ActeDeces;
use Modules\Mobile\Entities\TypeActe;
use Illuminate\Support\Facades\Validator;
use Modules\Mariage\Entities\ActeMariage;
use Modules\Referentiel\Entities\Personne;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;
use Modules\Rectification\Entities\Rubrique;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Rectification\Entities\Rectification;
use Modules\Rectification\Entities\DetailRectification;
use Modules\Referentiel\Entities\Filiation;
use Modules\Referentiel\Entities\Localite;

class RectificationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $rectifications = Rectification::orderBy('created_at', 'desc')->paginate(10);
        return view('rectification::index', compact('rectifications'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
         //récupération des type Actes
        $typesActe = TypeActe::all();
        //récupération de la liste de rubri rubriques
        $rubriques = Rubrique::all();
        //récupération de la liste des filiations
        $filiations = Filiation::all();
        //récupération de la liste des localites de type "TPLOC_0001"
        $localites =  Localite::where("code_type_localite", "TPLOC_0001")->get();

        return view('rectification::create', compact('typesActe', 'rubriques', 'filiations','localites'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

            //controle des donneés
            $rules = [
                "numero_acte" => ["required","string"],
                "type_acte" => ["required","string"],
                "old_value" => ["required"],
                "nouvelle_valeur" => ["required"],
                "rubrique" => ["required"],
                "nom_requerant" => ["required","string"],
                "filiation_requerant" => ["required","string"],
                "telephone_requerant" => ["required","string"]
            ];
            $validator = Validator::make($request->all(),$rules);
            if($validator->fails()){
                return response()->json([
                    "code"=>"400",
                    "message"=>"Veuillez remplir tous les champs obligatoires"
                ]);
            }

            //récupération des donnees
            $rubrique = $request->rubrique;
            $typeActe = $request->type_acte;
            $numeroActe = $request->numero_acte;
            $oldValue = $request->old_value;
            $newValue = $request->nouvelle_valeur;
            $nomRequerant = $request->nom_requerant;
            $prenomRequerant = $request->prenom_requerant;
            $telephoneRequerant = $request->telephone_requerant;
            $filiationRequerant = $request->filiation_requerant;
            $communeDistrictRequerant = $request->commune_district_requerant;
            $arrondRequerant = $request->arrond_requerant;
            $quartierRequerant = $request->quartier_requerant;
            $domicileTypeVoieRequerant = $request->domicile_type_voie_requerant;
            $domicileNumeroRequerant = $request->domicile_numero_requerant;
            $domicileNomVoieRequerant = $request->domicile_nom_voie_requerant;

            //recuperation de l'adresse du requerant structure(numero,typeVoie,nomVoie,quartier,arrondissement,communeDistrict)
            $adresseRequerant = $domicileNumeroRequerant." ".$domicileTypeVoieRequerant." ".$domicileNomVoieRequerant." ".$quartierRequerant." ".$arrondRequerant." ".($communeDistrictRequerant);

            $champ_id = explode("-", $rubrique)[1]; //nom technique exple:nom


            //vérification l'égalité de l'ancienne valeur et de la nouvelle valeur
            if($oldValue == $newValue){
                return response()->json([
                    "code"=>"400",
                    "message"=>"L'ancienne valeur et la nouvelle valeur doivent être différentes"
                ]);
            }
            DB::beginTransaction();
        try {

            //vérification de l'existence de la rectification
            $rectificationExist = Rectification::where("numero_acte", $numeroActe)
                ->where("code_type_acte", $typeActe)
                ->first();

            //si la rectification existe déjà, on ajoute simplement les détails
            if($rectificationExist != null){
                //vérification de l'existence de la rubrique dans les détails
                $detailExist = DetailRectification::where("code_rubrique", explode("-", $rubrique)[0])
                    ->where("nouvelle_valeur", $newValue)
                    ->where("code_rectification", $rectificationExist->code_rectification)
                    ->first();

                //si la nouvelle valeur existe déjà pour la rubrique, on retourne une erreur
                if($detailExist != null){
                    return response()->json([
                        "code"=>"400",
                        "message"=>"Cette nouvelle valeur a déjà été enregistrée pour cette rubrique"
                    ]);
                }

                //insertion des détails
                $detailsRectification = new DetailRectification;
                $detailsRectification->code_detail_rectification = Sifec::genererCodeUniqueReferentiel($detailsRectification, "code_detail_rectification",8,"DRE_");
                $detailsRectification->code_rectification = $rectificationExist->code_rectification;
                $detailsRectification->code_rubrique = explode("-", $rubrique)[0];
                $detailsRectification->ancienne_valeur = $oldValue;
                $detailsRectification->nouvelle_valeur = $newValue;
                $detailsRectification->save();

                DB::commit();
                if($champ_id == "date_naissance") $detailsRectification->nouvelle_valeur = date("d-m-Y", strtotime($detailsRectification->nouvelle_valeur));
                return response()->json([
                    "code"=>"200",
                    "message"=>"Détail de la fiche de rectification enregistré avec succès",
                    "data"=>[
                        "code_rectification"=>$rectificationExist->code_rectification,
                        "numero_rectification"=>$rectificationExist->numero_rectification,
                        "numero_acte"=>$rectificationExist->numero_acte,
                        "code_type_acte"=>$rectificationExist->code_type_acte,
                        //parcourir le tableau des détails et retourner les informations
                        "details"=>[
                            "code_detail_rectification"=>$detailsRectification->code_detail_rectification,
                            "code_rubrique"=>$detailsRectification->code_rubrique,
                            "ancienne_valeur"=>$detailsRectification->ancienne_valeur,
                            "nouvelle_valeur"=>$detailsRectification->nouvelle_valeur
                        ]

                    ]
                ]);
            }else //si la rectification n'existe pas, on crée une nouvelle rectification avec les détails
            {

                $rectification = new Rectification;
                $rectification->code_rectification = Sifec::genererCodeUniqueReferentiel($rectification,"code_rectification", 8,"REC_");
                $rectification->code_type_acte = $typeActe;
                $rectification->numero_rectification = Sifec::genererCodeUniqueReferentiel($rectification, "numero_rectification", 4, "");
                $rectification->numero_acte = $numeroActe;
                $rectification->code_institution = Auth::user()->affectationActive()->code_institution;
                $rectification->nom_prenom_requerant = $nomRequerant." ".$prenomRequerant;
                $rectification->adresse_requerant = $adresseRequerant;
                $rectification->telephone_requerant = $telephoneRequerant;
                $rectification->cui = Auth::user()->affectationActive()->cui;
                $rectification->code_filiation = $filiationRequerant;
                $rectification->save();

                //insertion des détails
                $detailsRectification = new DetailRectification;
                $detailsRectification->code_detail_rectification = Sifec::genererCodeUniqueReferentiel($detailsRectification, "code_detail_rectification",8,"DRE_");
                $detailsRectification->code_rectification = $rectification->code_rectification;
                $detailsRectification->code_rubrique = explode("-", $rubrique)[0];
                $detailsRectification->ancienne_valeur = $oldValue;

                if($champ_id == "date_naissance") $newValue = date("Y-m-d", strtotime($newValue));

                $detailsRectification->nouvelle_valeur = $newValue;
                $detailsRectification->save();

                //creation de la réquisition pour la rectification
                $requisition = new Requisition;
                $requisition->code_requisition = Sifec::genererCodeUniqueReferentiel($requisition, "code_requisition", 4, "CREQ_");
                $requisition->code_institution =  $rectification->code_institution;
                $requisition->type_requisition = "requisition aux fins de rectification de l'acte";
                $requisition->save();


                //update le code_requisition dans la rectification
                $rectification->code_requisition = $requisition->code_requisition;
                $rectification->save();

                DB::commit();

                if($champ_id == "date_naissance") $detailsRectification->nouvelle_valeur = date("d-m-Y", strtotime($detailsRectification->nouvelle_valeur));
                 return response()->json([
                    "code"=>"200",
                    "message"=>"Fiche de rectification enregistrée avec succès",
                    "data"=>[
                        "code_rectification"=>$rectification->code_rectification,
                        "numero_rectification"=>$rectification->numero_rectification,
                        "numero_acte"=>$rectification->numero_acte,
                        "code_type_acte"=>$rectification->code_type_acte,
                        "details"=>[
                            "code_detail_rectification"=>$detailsRectification->code_detail_rectification,
                            "code_rubrique"=>$detailsRectification->code_rubrique,
                            "ancienne_valeur"=>$detailsRectification->ancienne_valeur,
                            "nouvelle_valeur"=>$detailsRectification->nouvelle_valeur
                        ]
                    ]
                ]);
            }


        } catch (Exception $e) {
            DB::rollBack();
            //enregistrement de l'erreur dans le log
            Log::channel('sifec')->error("Erreur lors de l'enregistrement de la fiche de rectification : ".$e->getMessage());
            return response()->json([
                "code"=>"500",
                "message"=>"Erreur lors de l'enregistrement de la fiche de rectification"
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
        return view('rectification::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('rectification::edit');
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
        //récupération du detail de la rectification à supprimer
        $detailRectification = DetailRectification::find($id);

        //suppression des détails
        $detailRectification->delete();

        return response()->json([
            "code"=>"200",
            "message"=>"Rubrique de la rectification supprimée avec succès"
        ]);
    }

    public function ficheRectification($id)
    {

        //récupération de la dernière rectifications
        $rectification = Rectification::where("numero_acte", $id)
            ->latest()
            ->first();

            // Vérification de l'existence de la rectification
            if (!$rectification) {
                toastr()->error("Aucune fiche de rectification trouvée pour cet acte");
                return back();
            }
        //vérification de l'existence de l'acte en passant par le numero et le type d'acte
        $acte = SifecFacade::getActe($id);

        // vérification de l'existence de l'acte
        if($acte == null){
            return response()->json([
                "code"=>"180",
                "message"=>"Aucun acte trouvé pour ce numéro"
            ]);
        }

        //récupération de la liste des détails
        $detailsRectification = DetailRectification::where("code_rectification", $rectification->code_rectification)->get();

        try {

            view()->share("tester", "Vincent");
            $html2pdf = new Html2Pdf('L', 'A4', 'fr');
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML(view('rectification::etats.fiche-rectification', compact("acte", "rectification", "detailsRectification"))->render());

            return $html2pdf->output($id.".pdf");

        } catch (Exception $e) {
            Log::channel('sifec')->error("Erreur lors de la génération du PDF de la fiche de rectification : ".$e->getMessage());
            toastr()->error("Erreur lors de la génération du PDF de la fiche de rectification");
            return back();
        }
    }

    public function oldValue(Request $request)
    {
        $rubrique = $request->rubrique;
        $typeActe = $request->type_acte;
        $numeroActe = $request->numero_acte;
        $declaration = null;


        //recuperation de la declaration
        if($typeActe == "TPA_0001"){
            $declaration = ActeNaissance::find($numeroActe)->declaration;
        }
        if($typeActe == "TPA_0002"){

            $declaration = ActeMariage::find($numeroActe)->declaration;

        }
        if($typeActe == "TPA_0003"){
            $declaration = ActeDeces::find($numeroActe)->declaration;
        }

        //recupération champs côté identité
        $codeDec = explode("-", $rubrique)[2]; //entite exple: enfant
        $champ_id = explode("-", $rubrique)[1]; //nom technique exple:nom

        //création du champ de liaison
        $champ_dec = 'code_'.$codeDec;

        //appelle de l'ancienne valeur
        $ancienneValeur = Personne::find($declaration->$champ_dec)->$champ_id;
        if($ancienneValeur == "M") $ancienneValeur = "Masculin";
        if($ancienneValeur == "F") $ancienneValeur = "Féminin";
        if($champ_id == "date_naissance") $ancienneValeur = date("d-m-Y", strtotime($ancienneValeur));
        return $ancienneValeur;

    }

    public function getActe(Request $request)
    {
        //vérification de l'acte
        $typeActe = $request->type_acte;
        $numeroActe = $request->numero_acte;

          //vérification de l'existence de l'acte en passant par le numero et le type d'acte
        $acte = SifecFacade::rechercherActe($typeActe, $numeroActe);

        // vérification de l'existence de l'acte
        if($acte == null){
            return response()->json([
                "code"=>"180",
                "message"=>"Aucun acte trouvé pour ce numéro"
            ]);
        }
        return response()->json(["code"=> "200","acte"=>$acte]);
    }


    public function getDetails(Request $request)
    {
        $numeroActe = $request->numero_acte;
        $typeActe = $request->type_acte;

        //si numeroActe ou typeActe n'existe pas, on retourne une erreur
        if($numeroActe == null || $typeActe == null){
            return response()->json([
                "code"=>"400",
                "message"=>"Veuillez renseigner le numéro de l'acte et le type d'acte"
            ]);
        }
        //vérification de l'existence de l'acte en passant par le numero et le type d'acte
        $acte = SifecFacade::rechercherActe($typeActe, $numeroActe);

        // vérification de l'existence de l'acte
        if($acte == null){
            return response()->json([
                "code"=>"180",
                "message"=>"Aucun acte trouvé pour ce numéro"
            ]);
        }

        //récupération de la fiche de rectification
        $rectification = Rectification::where("numero_acte", $numeroActe)->first();


        //vérification de l'existence de la fiche
        if($rectification == null){
            return response()->json([
                "code"=>"404",
                "message"=>"Fiche de rectification introuvable"
            ]);
        }
        //récupération des détails de la rectification de l'acte (code_detail_rectification,lib_rubrique,ancienne_valeur,nouvelle_valeur
        $detailsRectification = DetailRectification::where("code_rectification", $rectification->code_rectification)
            ->join("tr_rubrique", "t_detail_rectification.code_rubrique", "=", "tr_rubrique.code_rubrique")
            ->select("t_detail_rectification.code_detail_rectification", "tr_rubrique.lib_rubrique", "t_detail_rectification.ancienne_valeur", "t_detail_rectification.nouvelle_valeur")
            ->get();

        //vérification de l'existence des détails
        if($detailsRectification == null){
            return response()->json([
                "code"=>"404",
                "message"=>"Aucun détail de rectification trouvé pour cette fiche"
            ]);
        }


        return response()->json([
            "code"=>"200",
            "message"=>"Détails de la fiche de rectification récupérés avec succès",
            "data"=>$detailsRectification
        ]);


    }

    public function send($id)
    {
        //récupération de la fiche de rectification
        $rectification = Rectification::find($id);

        //vérification de l'existence de la fiche
        if($rectification == null){
            return response()->json([
                "code"=>"404",
                "message"=>"Fiche de rectification introuvable"
            ]);
        }

        //mise à jour du statut de la fiche
        $rectification->statut = "Envoyé au tribunal";
        $rectification->save();

        return response()->json([
            "code"=>"200",
            "message"=>"Fiche de rectification envoyée avec succès"
        ]);
    }


}
