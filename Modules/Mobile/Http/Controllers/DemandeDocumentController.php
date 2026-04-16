<?php

namespace Modules\Mobile\Http\Controllers;

use App\Sifec\Sifec;
use App\Technodev\TechnoDev;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Mobile\Entities\DemandeDocument;
use Modules\Mobile\Entities\DetailDemandeDocument;
use Modules\Mobile\Entities\Tarificatrion;
use Modules\Naissance\Entities\Declarationnaissance;
use Modules\Referentiel\Entities\Personne;

class DemandeDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        return view('mobile::index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('mobile::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'nom_demandeur' => ['required', 'string'],
                'sexe_demander' => ['required', 'string'],
                'telephone_demander' => ['required', 'string'],
                'email_demandeur' => ['required', 'email'],
                'nom_sujet' => ['required', 'string'],
                'sexe_sujet' => ['required', 'string'],
                'date_naissance_sujet' => ['required'],
                'lieu_naissance_sujet' => ['required', 'string'],
                // "numero_acte_demande" => ["required"],
                // "code_filiation" => ["required"],
                'code_type_document_demande' => ['required'],
                'code_type_acte' => ['required'],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'code' => '180',
                    'message' => 'Il y a des erreurs à corriger',
                    'errors' => $validator->errors(),
                ]);
            }

            /*  return response()->json([
                     "code"=>"170",
                     "message"=>"Test okey ",
                     "datas"=>$request->all()
                 ]); */

            // $sujet = Personne::where("nom",$request->nom_sujet)
            // ->where("sexe",$request->sexe_sujet)
            // ->where("date_naissance", $request->date_naissance_sujet)
            // ->where("lieu_naissance" , $request->lieu_naissance_sujet)
            // ->orWhere("prenom", $request->prenom_sujet)->first();

            if ($request->nom_sujet == null) {

                $sujet = Personne::where('nom', $request->nom_sujet)
                    ->where('sexe', $request->sexe_sujet == 'Masculin' ? 'M' : 'F')
                    ->where('date_naissance', date('Y-m-d', strtotime($request->date_naissance_sujet)))
                    ->where('lieu_naissance', $request->lieu_naissance_sujet)->first();

            } else {

                $sujet = Personne::where('nom', $request->nom_sujet)
                    ->Where('prenom', $request->prenom_sujet)
                    ->where('sexe', $request->sexe_sujet == 'Masculin' ? 'M' : 'F')
                    ->where('date_naissance', date('Y-m-d', strtotime($request->date_naissance_sujet)))
                    ->where('lieu_naissance', $request->lieu_naissance_sujet)->first();

            }

            // return response()->json([
            //     "code"=> "181",
            //     "message"=> ["reponse"=>"Le sujet n'a aucun acte demandé"],
            //     "sujet"=>$sujet
            // ]);

            if ($sujet == null) {
                return response()->json([
                    'code' => '181',
                    'message' => ['reponse' => "Le sujet n'a aucun acte demandé"],
                ]);
            }

            // AU CAS OU CEST NAISSANCE
            if ($request->code_type_acte == 'TAC_0001') {
                // declaration naissance
                $declarationNaissance = Declarationnaissance::where('code_enfant', $sujet->code_personne)->first();

                if ($declarationNaissance == null) {
                    return response()->json([
                        'code' => '182',
                        'message' => ['reponse' => 'Aucune déclaration de naissance pour ce sujet'],
                        'sujet' => $sujet,
                    ]);
                }

                // trouver l'acte
                $acte = $declarationNaissance->acte;

                if ($acte == null) {
                    return response()->json([
                        'code' => '183',
                        'message' => ['reponse' => 'Aucun acte de naissance pour ce sujet'],
                    ]);
                }

                // Enregistrer
                return $this->saveDemandeDocument($request, $acte, 'code_declaration_naissance');
            }

            // AU CAS DECES

            if ($request->code_type_acte == 'TAC_0002') {
                // declaration naissance
                $declarationDeces = DeclarationDeces::where('code_defunt', $sujet->code_personne)->first();

                if ($declarationDeces == null) {
                    return response()->json([
                        'code' => '182',
                        'message' => ['reponse' => 'Aucune déclaration de décès pour ce sujet'],
                    ]);
                }
                // trouver l'acte
                $acte = $declarationDeces->acte;

                if ($acte == null) {
                    return response()->json([
                        'code' => '183',
                        'message' => ['reponse' => 'Aucun acte de décès pour ce sujet'],
                    ]);
                }

                // Enregistrer
                return $this->saveDemandeDocument($request, $acte, 'code_declaration_deces');
            }
        } catch (Exception $e) {
            return response()->json([
                'code' => '183',
                'message' => ['error' => $e->getMessage()],
            ]);
        }
    }

    public function statutverification(Request $request)
    {
        try {
            $rules = [
                'code_demande_document' => ['required', 'string'],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'code' => '180',
                    'message' => 'Il y a une erreur à corriger',
                    'errors' => $validator->errors(),
                ]);
            }

            $statut = DemandeDocument::where('code_demande_document', $request->code_demande_document)->first();

            if ($statut == null) {
                return response()->json([
                    'code' => '181',
                    'message' => ['reponse' => 'Aucun statut trouvé pour ce code, merci vérifier le code'],
                ]);
            } else {
                return response()->json([
                    'code' => '200',
                    'message' => ['reponse' => 'statut trouvé'],
                    'document' => $statut,
                ]);
            }

        } catch (Exception $e) {
            return response()->json([
                'code' => '183',
                'message' => ['error' => $e->getMessage()],
            ]);
        }
    }

    public function otpverification(Request $request)
    {
        try {
            $rules = [
                'code_otp' => ['required', 'string'],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'code' => '180',
                    'message' => 'Il y a une erreur à corriger',
                    'errors' => $validator->errors(),
                ]);
            }

            $document = DetailDemandeDocument::where('code_otp', $request->code_otp)
                ->where('statut_lien', 'actif')->first();

            if ($document == null) {
                return response()->json([
                    'code' => '181',
                    'message' => ['reponse' => 'Aucun document trouvé pour ce code, merci vérifier le code'],
                ]);
            } else {
                return response()->json([
                    'code' => '200',
                    'message' => ['reponse' => 'Document trouvé'],
                    'document' => $document,
                ]);
            }

        } catch (Exception $e) {
            return response()->json([
                'code' => '183',
                'message' => ['error' => $e->getMessage()],
            ]);
        }
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('mobile::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('mobile::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function paiementDocument(Request $request)
    {

        $codeDemandeDocument = DemandeDocument::find($request->code_demande_document);

        if ($codeDemandeDocument == null) {
            return response()->json([
                'code' => '182',
                'message' => ['error' => "Il n'y a aucun document trouvé"],
            ]);
        }

        $tarification = Tarificatrion::where('code_type_acte', $codeDemandeDocument->code_type_acte)->where('code_type_document_demande', $codeDemandeDocument->code_type_document_demande)->first();

        if ($tarification == null) {
            return response()->json([
                'code' => '183',
                'message' => ['error' => "Il n'y a aucune tarification trouvée"],
            ]);
        }

        $uui4 = (string) Str::uuid();
        $payData = [
            'amount' => $tarification->prix,
            'invoice_code' => uniqid(),
            'number' => $request->numeroPayeur,
            'uui4' => $uui4,
            'payer_message' => 'Paiement document',
            'payee_message' => 'Commande copie acte de naissance',
            'commande_id' => $codeDemandeDocument->code_demande_document,
        ];

        $rep = new TechnoDev;

        $retour = $rep->transact($payData);

        if ($retour['code'] == '200') {
            return response()->json([
                'code' => '200',
                'step' => '2',
                'message' => $retour['msg'],
            ]);
        }
    }

    public function saveDemandeDocument(Request $request, Model $model, string $field)
    {

        try {
            // code...
            DB::beginTransaction();
            // paiement du document

            // save demande du document
            $demande = new DemandeDocument;
            $demande->code_demande_document = Sifec::genererCodeUniqueReferentiel($demande, 'code_demande_document', 4, 'DD_');
            // demandeur
            $demande->nom_demandeur = $request->nom_demandeur;
            $demande->prenom_demander = $request->prenom_demandeur;
            $demande->sexe_demander = $request->sexe_demander == 'Masculin' ? 'M' : 'F';
            $demande->telephone_demander = $request->telephone_demander;
            $demande->email_demandeur = $request->email_demandeur;
            // sujet
            $demande->nom_sujet = $request->nom_sujet;
            $demande->prenom_sujet = $request->prenom_sujet;
            $demande->sexe_sujet = $request->sexe_sujet == 'Masculin' ? 'M' : 'F';
            $demande->date_naissance_sujet = date('Y-m-d', strtotime($request->date_naissance_sujet));
            $demande->lieu_naissance_sujet = $request->lieu_naissance_sujet;
            $demande->numero_acte_demande = $request->numero_acte_demande == null ? $model->$field : $model->$field;
            $demande->code_lieu_survenance = $request->code_lieu_survenance;
            $demande->code_filiation = $request->code_filiation;
            $demande->code_type_document_demande = $request->code_type_document_demande;
            $demande->code_type_acte = $request->code_type_acte;
            $demande->save();
            DB::commit();

            return response()->json([
                'code' => '200',
                'message' => ['reponse' => 'Demande du document envoyée avec succès'],
                'object' => $demande,
                'step' => '1',
                'next_link' => route('demande.paiement.document'),
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response([
                'code' => '182',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
