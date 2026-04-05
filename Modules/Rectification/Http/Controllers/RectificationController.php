<?php

namespace Modules\Rectification\Http\Controllers;

use App\Models\Requisition;
use Exception;
use App\Sifec\Sifec;
use App\Sifec\SifecFacade;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Deces\Entities\ActeDeces;
use Modules\Deces\Entities\DeclarationDeces;
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
use Modules\Rectification\Services\MouvementService;
use Modules\Notification\Services\NotificationService;
use Modules\Notification\Notifications\RectificationEnvoyeeTribunalNotification;
use Modules\Referentiel\Entities\Mouvement;

class RectificationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $rectifications = Rectification::orderBy('created_at', 'desc')->get();

        // dd($rectifications);
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

            // Contrôle des données
            $rules = [
                "numero_acte" => ["required", "string"],
                "type_acte" => ["required", "string"],
                "old_value" => ["nullable", "string"],
                "nouvelle_valeur" => ["required", "string"],
                "rubrique" => ["required", "string"],
                "nom_requerant" => ["required", "string"],
                "prenom_requerant" => ["required", "string"],
                "telephone_requerant" => ["required", "string"],
                "filiation_requerant" => ["nullable", "string"],
                "piece_justificative" => ["nullable", "file", "mimes:pdf,jpg,jpeg,png", "max:5120"],
            ];
            $validator = Validator::make($request->all(),$rules);
            if($validator->fails()){
                return response()->json([
                    "code"=>"400",
                    "message"=>"Veuillez remplir tous les champs obligatoires"
                ]);
            }

            // Récupération des données
            $rubrique = trim((string) $request->input('rubrique', ''));
            $typeActe = trim((string) $request->input('type_acte', ''));
            $numeroActe = trim((string) $request->input('numero_acte', ''));
            $oldValue = trim((string) $request->input('old_value', ''));
            $newValue = trim((string) $request->input('nouvelle_valeur', ''));

            // Vérification du format de la rubrique
            $rubriqueParts = explode('-', $rubrique);
            if (count($rubriqueParts) < 3) {
                return response()->json([
                    'code' => '400',
                    'message' => 'Format de rubrique invalide'
                ]);
            }

            // Si old_value est vide, utiliser "-" pour indiquer qu'il n'y avait pas de valeur précédente
            if ($oldValue === '') {
                $oldValue = '-';
            }
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

            $champ_id = $rubriqueParts[1]; // lib_technique : nom, prenom, sexe, date_naissance, etc.

            // Vérification que la nouvelle valeur est différente de l'ancienne valeur (si ancienne valeur existe)
            if ($oldValue !== '-' && $oldValue === $newValue) {
                return response()->json([
                    'code' => '400',
                    'message' => "La nouvelle valeur doit être différente de l'ancienne valeur"
                ]);
            }

            // Empêcher la rectification si le titulaire de l'acte est décédé (contrôle côté serveur)
            $acte = SifecFacade::rechercherActe($typeActe, $numeroActe);
            if ($acte instanceof ActeNaissance) {
                if (DeclarationDeces::where('num_acte_naissance', $numeroActe)->exists()) {
                    return response()->json([
                        'code' => '403',
                        'message' => 'La rectification n\'est pas autorisée : le titulaire de cet acte de naissance est décédé.',
                    ]);
                }
            }
            if ($acte instanceof ActeDeces) {
                return response()->json([
                    'code' => '403',
                    'message' => 'La rectification d\'un acte de décès n\'est pas autorisée.',
                ]);
            }

            // Extraction du code_rubrique depuis la rubrique
            $codeRubrique = $rubriqueParts[0];

            DB::beginTransaction();
        try {

            // Vérification de l'existence de la rectification (support TPA_* et TAC_*)
            $rectificationExist = Rectification::where("numero_acte", $numeroActe)
                ->where(function($query) use ($typeActe) {
                    // Accepter les deux formats de codes
                    if (in_array($typeActe, ['TAC_0001'], true)) {
                        $query->whereIn('code_type_acte', ['TPA_0001', 'TAC_0001']);
                    } elseif (in_array($typeActe, ['TAC_0002'], true)) {
                        $query->whereIn('code_type_acte', ['TPA_0002', 'TAC_0002']);
                    } elseif (in_array($typeActe, ['TAC_0003'], true)) {
                        $query->whereIn('code_type_acte', ['TPA_0003', 'TAC_0003']);
                    } else {
                        $query->where('code_type_acte', $typeActe);
                    }
                })
                ->first();

            //si la rectification existe déjà, on ajoute simplement les détails
            if($rectificationExist != null){
                // Vérification de l'existence de la rubrique dans les détails
                $detailExist = DetailRectification::where("code_rubrique", $codeRubrique)
                    ->where("code_rectification", $rectificationExist->code_rectification)
                    ->first();

                // Si la rubrique existe déjà dans les détails, mettre à jour la valeur au lieu de créer un doublon
                if ($detailExist != null) {
                    // Mettre à jour la valeur existante
                    $detailExist->ancienne_valeur = $oldValue;
                    if ($champ_id === "date_naissance") {
                        $newValue = date("Y-m-d", strtotime($newValue));
                    }
                    $detailExist->nouvelle_valeur = $newValue;
                    $detailExist->save();
                    
                    DB::commit();
                    return response()->json([
                        'code' => '200',
                        'message' => 'Rectification mise à jour avec succès',
                        'data' => [
                            'code_rectification' => $rectificationExist->code_rectification,
                            'details' => [
                                'code_detail_rectification' => $detailExist->code_detail_rectification,
                                'code_rubrique' => $detailExist->code_rubrique,
                                'ancienne_valeur' => $detailExist->ancienne_valeur,
                                'nouvelle_valeur' => $detailExist->nouvelle_valeur
                            ]
                        ]
                    ]);
                }

                // Insertion des détails
                $detailsRectification = new DetailRectification;
                $detailsRectification->code_detail_rectification = Sifec::genererCodeUniqueReferentiel($detailsRectification, "code_detail_rectification", 8, "DRE_");
                $detailsRectification->code_rectification = $rectificationExist->code_rectification;
                $detailsRectification->code_rubrique = $codeRubrique;
                $detailsRectification->ancienne_valeur = $oldValue;
                if ($champ_id === "date_naissance") {
                    $newValue = date("Y-m-d", strtotime($newValue));
                }
                $detailsRectification->nouvelle_valeur = $newValue;
                $detailsRectification->save();

                if ($request->hasFile('piece_justificative') && $request->file('piece_justificative')->isValid()) {
                    $uploadPath = public_path('app/rectification');
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    $file = $request->file('piece_justificative');
                    $filename = 'rectif_' . $rectificationExist->code_rectification . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move($uploadPath, $filename);
                    $rectificationExist->piece_justificative = 'app/rectification/' . $filename;
                    $rectificationExist->save();
                }

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
                if ($request->hasFile('piece_justificative') && $request->file('piece_justificative')->isValid()) {
                    $uploadPath = public_path('app/rectification');
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    $file = $request->file('piece_justificative');
                    $filename = 'rectif_' . $rectification->code_rectification . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move($uploadPath, $filename);
                    $rectification->piece_justificative = 'app/rectification/' . $filename;
                }
                $rectification->save();

                // Enregistrement du mouvement de création
                $trmouvement = Mouvement::where('code_mouvement', 'MOUV_2004')->first();
                if ($trmouvement) {
                    $d = auth()->user();
                    app(MouvementService::class)->enregistrerMouvementRectification($rectification, $trmouvement,  Auth::user(), 'Fiche de rectification créée');
                }

                // Insertion des détails
                $detailsRectification = new DetailRectification;
                $detailsRectification->code_detail_rectification = Sifec::genererCodeUniqueReferentiel($detailsRectification, "code_detail_rectification", 8, "DRE_");
                $detailsRectification->code_rectification = $rectification->code_rectification;
                $detailsRectification->code_rubrique = $codeRubrique;
                $detailsRectification->ancienne_valeur = $oldValue;
                
                if ($champ_id === "date_naissance") {
                    $newValue = date("Y-m-d", strtotime($newValue));
                }
                
                $detailsRectification->nouvelle_valeur = $newValue;
                $detailsRectification->save();

                //creation de la réquisition pour la rectification
                // $requisition = new Requisition;
                // $requisition->code_requisition = Sifec::genererCodeUniqueReferentiel($requisition, "code_requisition", 4, "CREQ_");
                // $requisition->code_institution =  $rectification->code_institution;
                // $requisition->type_requisition = "requisition aux fins de rectification de l'acte";
                // $requisition->save();


                //update le code_requisition dans la rectification
                // $rectification->code_requisition = $requisition->code_requisition;
                // $rectification->save();

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

        // Chemin local pour l'image (évite "Unauthorized path host" avec Html2Pdf/TCPDF)
        $armoirie_path = public_path('tpl/armoirie_congo.png');
        if (!is_file($armoirie_path)) {
            $armoirie_path = '';
        }

        try {

            view()->share("tester", "Alange");
            $html2pdf = new Html2Pdf('L', 'A4', 'fr');
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML(view('rectification::etats.fiche-rectification', compact("acte", "rectification", "detailsRectification", "armoirie_path"))->render());

            return $html2pdf->output($id.".pdf");

        } catch (Exception $e) {
            Log::channel('sifec')->error("Erreur lors de la génération du PDF de la fiche de rectification : ".$e->getMessage());
            toastr()->error("Erreur lors de la génération du PDF de la fiche de rectification");
            return back();
        }
    }

    public function oldValue(Request $request)
    {
        $rubrique = trim((string) $request->input('rubrique', ''));
        $typeActe = trim((string) $request->input('type_acte', ''));
        $numeroActe = trim((string) $request->input('numero_acte', ''));

        if ($rubrique === '' || $typeActe === '' || $numeroActe === '') {
            return '';
        }

        $acte = null;
        $declaration = null;

        // Récupération de l'acte et de la déclaration (support TPA_* et TAC_*)
        if (in_array($typeActe, ['TPA_0001', 'TAC_0001'], true)) {
            $acte = ActeNaissance::findByIdentifier($numeroActe);
            if ($acte) {
                $declaration = $acte->declaration;
            }
        } elseif (in_array($typeActe, ['TPA_0002', 'TAC_0002'], true)) {
            $acte = ActeMariage::where('code_acte_mariage', $numeroActe)->first();
            if ($acte) {
                $declaration = $acte->declaration;
            }
        } elseif (in_array($typeActe, ['TPA_0003', 'TAC_0003'], true)) {
            $acte = ActeDeces::where('code_acte_deces', $numeroActe)->first();
            if ($acte) {
                $declaration = $acte->declaration;
            }
        }

        if (!$acte || !$declaration) {
            return '';
        }

        // Parsing de la rubrique : format "code_rubrique-lib_technique-entite_rubrique"
        $parts = explode('-', $rubrique);
        if (count($parts) < 3) {
            return '';
        }

        $entite = $parts[2]; // enfant, père, mère, époux, épouse, defunt
        $libTechnique = $parts[1]; // nom, prenom, sexe, date_naissance, lieu_naissance, nationalite

        // Mapping des entités vers les colonnes de la déclaration selon le type d'acte
        $champDec = null;
        if (in_array($typeActe, ['TPA_0001', 'TAC_0001'], true)) {
            // Naissance : enfant, père, mère
            $mapping = ['enfant' => 'code_enfant', 'père' => 'code_pere', 'mère' => 'code_mere'];
            $champDec = $mapping[$entite] ?? null;
        } elseif (in_array($typeActe, ['TPA_0002', 'TAC_0002'], true)) {
            // Mariage : époux, épouse
            $mapping = ['époux' => 'code_epoux', 'épouse' => 'code_epouse'];
            $champDec = $mapping[$entite] ?? null;
        } elseif (in_array($typeActe, ['TPA_0003', 'TAC_0003'], true)) {
            // Décès : defunt, père, mère
            $mapping = ['defunt' => 'code_defunt', 'père' => 'code_pere', 'mère' => 'code_mere'];
            $champDec = $mapping[$entite] ?? null;
        }

        if (!$champDec || !$declaration->$champDec) {
            return '';
        }

        $personne = Personne::find($declaration->$champDec);
        if (!$personne) {
            return '';
        }

        // Récupération de la valeur selon lib_technique
        $ancienneValeur = $personne->$libTechnique ?? '';

        // Formatage selon le type de champ
        if ($libTechnique === 'sexe') {
            if ($ancienneValeur === 'M') {
                $ancienneValeur = 'Masculin';
            } elseif ($ancienneValeur === 'F') {
                $ancienneValeur = 'Féminin';
            }
        } elseif ($libTechnique === 'date_naissance' && $ancienneValeur) {
            $ancienneValeur = date('d-m-Y', strtotime($ancienneValeur));
        }

        return $ancienneValeur;
    }

    public function getActe(Request $request)
    {
        $typeActe = trim((string) $request->input('type_acte', ''));
        $numeroActe = trim((string) $request->input('numero_acte', ''));

        if ($numeroActe === '' || $typeActe === '') {
            return response()->json([
                'code' => '400',
                'message' => 'Veuillez renseigner le numéro de l\'acte et le type d\'acte.',
            ]);
        }

        $acte = SifecFacade::rechercherActe($typeActe, $numeroActe);

        if ($acte === null) {
            return response()->json([
                'code' => '180',
                'message' => 'Aucun acte trouvé pour ce numéro.',
            ]);
        }

        // Empêcher la rectification si le titulaire de l'acte est décédé
        if ($acte instanceof ActeNaissance) {
            if (DeclarationDeces::where('num_acte_naissance', $numeroActe)->exists()) {
                return response()->json([
                    'code' => '403',
                    'message' => 'La rectification n\'est pas autorisée : le titulaire de cet acte de naissance est décédé.',
                ]);
            }
        }
        // Pour un acte de décès, le sujet est le défunt : pas de rectification autorisée
        if ($acte instanceof ActeDeces) {
            return response()->json([
                'code' => '403',
                'message' => 'La rectification d\'un acte de décès n\'est pas autorisée.',
            ]);
        }

        return response()->json(['code' => '200', 'acte' => $acte]);
    }


    public function getDetails(Request $request)
    {
        $numeroActe = trim((string) $request->input('numero_acte', ''));
        $typeActe = trim((string) $request->input('type_acte', ''));

        if ($numeroActe === '' || $typeActe === '') {
            return response()->json([
                'code' => '400',
                'message' => "Veuillez renseigner le numéro de l'acte et le type d'acte.",
            ]);
        }

        $acte = SifecFacade::rechercherActe($typeActe, $numeroActe);

        if ($acte === null) {
            return response()->json([
                'code' => '180',
                'message' => 'Aucun acte trouvé pour ce numéro.',
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

    public function send($id, MouvementService $mouvementService)
    {
        //récupération de la fiche de rectification
        $rectification = Rectification::find($id);

        // dd($rectification->institution->institutionParent);

        //vérification de l'existence de la fiche
        if($rectification == null){
            return response()->json([
                "code"=>"404",
                "message"=>"Fiche de rectification introuvable"
            ]);
        }

        // Récupération dynamique du mouvement référentiel
        $trmouvement = Mouvement::where('code_mouvement', 'MOUV_2001')->first();
        if (!$trmouvement) {
            Log::channel('sifec')->error('Mouvement référentiel MOUV_2001 introuvable pour la rectification');
        } else {
            $user = auth()->user();
            $mouvementService->enregistrerMouvementRectification($rectification, $trmouvement, $user, 'Fiche envoyée au tribunal');
        }

        // Notification aux agents du tribunal
        $tribunalInstitution = $rectification->institution->institutionParent;
        NotificationService::notifierAgentsInstitution(
            $tribunalInstitution,
            new RectificationEnvoyeeTribunalNotification($rectification)
        );

        return response()->json([
            "code"=>"200",
            "message"=>"Fiche de rectification envoyée avec succès"
        ]);
    }


}
