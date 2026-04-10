<?php

namespace Modules\Mariage\Http\Controllers;

use Exception;
use App\Sifec\Sifec;
use App\Sifec\SifecFacade;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Mariage\Services\OtpService;
use Illuminate\Support\Facades\Validator;
use Modules\Mariage\Entities\ActeMariage;
use Modules\Referentiel\Entities\Registre;
use App\Mail\ValidationActeMariageMailable;
use Illuminate\Contracts\Support\Renderable;
use Modules\Mariage\Entities\DeclarationMariage;
use Modules\Mariage\Services\ActeMariageService;
use Modules\Referentiel\Entities\FeuilletRegistre;
use Modules\Notification\Services\NotificationService;
use Modules\Notification\Jobs\ValidationActeMariageJob;


class ActeMariageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $affectation = $user->affectationActive();
        $institution = $affectation->institution;

        $registre = Registre::where("statut",1)->where("code_type_registre","TPRG_0002")->first();
        // $registre = Registre::where("code_type_registre","TPRG_0002")->first();

        // $declarations = Auth::user()->affectationActive()->institution->declarationsMariages()->where("cec_approuver","OUI");

        $declarations = DeclarationMariage::where(function($query) use ($institution) {
            $query->where("code_institution_destinataire", $institution->code_institution)
                  ->orWhere("code_institution", $institution->code_institution);
        })
        ->where("cec_approuver", "OUI")
        ->where(function($query) {
            $query->where("type_declaration", "!=", "DISPENSE")
                  ->orWhere(function($subQuery) {
                      $subQuery->where("type_declaration", "DISPENSE")
                               ->where("tribunal_approuver", "OUI")
                               ->where(function($requisitionQuery) {
                                   $requisitionQuery->whereHas('requisition', function($reqQuery) {
                                                       $reqQuery->where('statut', 'envoyée');
                                                   })
                                                   ->orWhereHas('jugement', function($jugQuery) {
                                                       $jugQuery->where('statut', 'envoyée');
                                                   });
                               });
                  });
        })
        ->get();

        return view('mariage::acte.index',compact("declarations","registre"));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {

         return view('mariage::acte.index');
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
     * Générer un acte de mariage
     */
    public function generateActe(Request $request, ActeMariageService $service)
    {
        $user = Auth::user();
        $declaration = DeclarationMariage::findOrFail($request->code_declaration_mariage);

        // Vérifier si un acte existe déjà pour cette déclaration
        $acteExistant = $service->obtenirActeParDeclaration($declaration->code_declaration_mariage);
        if ($acteExistant) {
            return response()->json([
                "code" => "409",
                "message" => "Un acte de mariage existe déjà pour cette déclaration (Code: " . $acteExistant->code_acte_mariage . ")"
            ], 409);
        }

        // Protection supplémentaire : vérifier si une génération est en cours
        $cacheKey = "generation_acte_" . $declaration->code_declaration_mariage;
        if (cache()->has($cacheKey)) {
            return response()->json([
                "code" => "429",
                "message" => "Une génération d'acte est déjà en cours pour cette déclaration. Veuillez patienter."
            ], 429);
        }

        // Marquer qu'une génération est en cours (expire après 30 secondes)
        cache()->put($cacheKey, true, 30);

        $registre = $user->affectationActive()->registres()->where("code_type_registre","TPRG_0002")->where("statut",1)->first();

        if (!Gate::allows("module.acteMariage.generate")) {
            return response()->json([
                "code" => "403",
                "message" => "Vous n'êtes pas autorisé à générer un acte"
            ], 403);
        }
        if (!$registre || $registre->statut == 0 || ($registre->nombre_acte_prevu - $registre->nombre_acte_transcrit) == 0) {
            return response()->json([
                "code" => "400",
                "message" => "Registre non disponible ou complet"
            ], 400);
        }
        DB::beginTransaction();
        try {
            $acte = $service->genererActe($declaration, $registre, $user);

            $mouvementService = app(\Modules\Mariage\Services\MouvementMariageService::class);
            $mouvementService->ajouterEvenementActe($user, $acte, 'attente_approbation', null, $acte);

            DB::commit();
            
            // Libérer le verrou de génération
            cache()->forget($cacheKey);
            
            return response()->json([
                "code" => "200",
                "message" => "Acte de mariage généré avec succès",
                "data" => [
                    "code_acte_mariage" => $acte->code_acte_mariage,
                    "niupp" => $acte->code_acte_mariage
                ]
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            
            // Libérer le verrou de génération en cas d'erreur
            cache()->forget($cacheKey);
            
            return response()->json([
                "code" => "500",
                "message" => "Erreur lors de la génération de l'acte: " . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('mariage::acte.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
         return view('mariage::acte.edit');
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

    public function mariageApprouver($id)
    {
        $am = ActeMariage::find($id);

        if($am == null){
            toastr()->error("Vous ne pouvez pas approuver cet acte de mariage");
            return back();
        }

        if( Gate::allows("module.acteMariage.signature")){
            try {
                $otp = substr(time(),2);

                $am->approbation_mairie = Auth::user()->affectationActive()->cui;
                $am->signature_maire = Auth::user()->personne->signature;
                $am->otp_approbation_mairie = $otp;
                $am->save();

                toastr()->success("Acte approuvé avec succès");

                return back();

            } catch (Exception $e) {
                toastr()->error($e->getMessage());
                return back();
            }
        }
    }

    /**
     * Envoyer OTP pour validation d'un acte
     */
    public function sendOtp(Request $request, OtpService $otpService)
    {
        $rules = [
            "code_declaration_mariage" => ["required", "string"]
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                "code" => "180",
                "message" => "Code de déclaration requis"
            ]);
        }

        if (!Gate::allows("module.acteMariage.signature")) {
            return response()->json([
                "code" => "181",
                "message" => "Vous n'êtes pas autorisé à valider un acte de mariage"
            ]);
        }

        try {
            $acte = ActeMariage::where("code_declaration_mariage", $request->code_declaration_mariage)->first();
            if (!$acte) {
                return response()->json([
                    "code" => "182",
                    "message" => "Aucun acte trouvé pour ce code"
                ]);
            }

            $otp = $otpService->envoyerOtpValidationActes(Auth::user(), [$acte]);

            return response()->json([
                "code" => "200",
                "message" => "SMS envoyé avec succès"
            ]);

        } catch (Exception $e) {
            return response()->json([
                "code" => "183",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function validateOtp(Request $request, OtpService $otpService)
    {
        $rules = [
            "otp_approbation_mairie" => ["required", "numeric"],
            "code_declaration_mariage" => ["required", "string"]
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                "code" => "180",
                "message" => "Données requises manquantes"
            ]);
        }

        if (!Gate::allows("module.acteMariage.signature")) {
            return response()->json([
                "code" => "181",
                "message" => "Vous n'êtes pas autorisé à valider un acte de mariage"
            ]);
        }

        try {
            [$success, $message] = $otpService->validerOtpActes(
                [$request->code_declaration_mariage], $request->otp_approbation_mairie,
                $request->ip(),
                $request->userAgent()
            );

            if (!$success) {
                return response()->json([
                    "code" => "183",
                    "message" => $message
                ]);
            }

            return response()->json([
                "code" => "200",
                "message" => "Acte de mariage validé avec succès"
            ]);

        } catch (Exception $e) {
            return response()->json([
                "code" => "183",
                "message" => $e->getMessage()
            ]);
        }
    }


     // Send OTP for bulk validation
     public function sendOtpBulk(Request $request, OtpService $otpService){
        $codes = $request->codes;

        if (empty($codes)) {
            return response()->json([
                "code" => "180",
                "message" => "Aucune déclaration sélectionnée"
            ]);
        }

        $actes = ActeMariage::whereIn("code_declaration_mariage", $codes)->get();
        if ($actes->count() == 0) {
            return response()->json([
                "code" => "180",
                "message" => "Aucun acte trouvé"
            ]);
        }

        if (!Gate::allows("module.acteMariage.signature")) {
            return response()->json([
                "code" => "181",
                "message" => "Vous n'êtes pas autorisé à valider des actes de mariage"
            ]);
        }

        try {
            $otp = $otpService->envoyerOtpValidationActes(Auth::user(), $actes);

            return response()->json([
                "code" => "200",
                "message" => "SMS envoyé avec succès"
            ]);

        } catch (Exception $e) {
            return response()->json([
                "code" => "181",
                "message" => $e->getMessage()
            ]);
        }
    }



    // Validate multiple actes
    public function validateOtpBulk(Request $request, OtpService $otpService){
        $rules = [
            "otp_approbation_mairie" => ["required", "numeric"],
            "codes" => ["required"]
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                "code" => "180",
                "message" => "Données requises manquantes"
            ]);
        }

        if (!Gate::allows("module.acteMariage.signature")) {
            return response()->json([
                "code" => "181",
                "message" => "Vous n'êtes pas autorisé à valider des actes de mariage"
            ]);
        }

        $codes = $request->codes;
        $otp = $request->otp_approbation_mairie;

        try {
            [$success, $message] = $otpService->validerOtpActes(
                $codes, $otp,
                $request->ip(),
                $request->userAgent()
            );

            if (!$success) {
                return response()->json([
                    "code" => "183",
                    "message" => $message
                ]);
            }

            return response()->json([
                "code" => "200",
                "message" => "Acte(s) de mariage validé(s) avec succès"
            ]);

        } catch (Exception $e) {
            return response()->json([
                "code" => "183",
                "message" => $e->getMessage()
            ]);
        }
    }



     // générer bulk actes
    //  public function generateActeBulk(Request $request)
    //  {
    //      try {
    //          $codes = $request->codes;

    //          if (empty($codes)) {
    //              return response()->json([
    //                  "code" => "180",
    //                  "message" => ["error" => "Aucune déclaration sélectionnée"],
    //                  "flashAlert" => [
    //                      "type" => "error",
    //                      "message" => "Veuillez sélectionner au moins une déclaration"
    //                  ]
    //              ]);
    //          }

    //          $acteMariageService = new ActeMariageService();
    //          [$success, $message, $actes] = $acteMariageService->genererActesEnLot($codes, Auth::user());

    //          return response()->json([
    //              "code" => "200",
    //              "message" => ["reponse" => $message],
    //              "flashAlert" => [
    //                  "type" => "success",
    //                  "message" => $message
    //              ],
    //              "data" => [
    //                  "nombre_actes" => count($actes)
    //              ]
    //          ]);

    //      } catch (Exception $e) {
    //          return response()->json([
    //              "code" => "201",
    //              "message" => ["error" => $e->getMessage()],
    //              "flashAlert" => [
    //                  "type" => "error",
    //                  "message" => $e->getMessage()
    //              ]
    //          ]);
    //      }
    //  }

     public function searchActe($id)
     {
        try {
            $acteMariageService = new ActeMariageService();
            $acte = $acteMariageService->rechercherActe($id);

            if($acte !== null){
                $optionMariage = $acte->declaration->optionMariage;

                if($optionMariage->code_option_mariage == "OMRG_0002"){
                    // Monogamie - problème
                    return response()->json([
                        "code" => "99",
                        "message" => [
                            "optionMariage" => "Il semble que l'époux soit déjà marié avec l'option <strong>Monogamie</strong>, au cas où il serait divorcé, alors veuillez présenter le jugement du divorce ou bien l'acte de décès de son épouse"
                        ],
                        "acte" => [
                            "code_acte" => $acte->code_acte_mariage,
                            "date_emission" => $acte->date_emission,
                            "option_mariage" => $optionMariage->lib_option_mariage
                        ]
                    ]);
                } else {
                    // Polygamie - OK
                    return response()->json([
                        "code" => "200",
                        "message" => [
                            "optionMariage" => "L'époux est déjà marié avec l'option " . $optionMariage->lib_option_mariage . ". Le processus peut continuer."
                        ],
                        "acte" => [
                            "code_acte" => $acte->code_acte_mariage,
                            "date_emission" => $acte->date_emission,
                            "option_mariage" => $optionMariage->lib_option_mariage,
                            "epouse" => [
                                "nom" => $acte->declaration->epouse->nom ?? '',
                                "prenom" => $acte->declaration->epouse->prenom ?? '',
                                "nom_complet" => $acte->declaration->epouse->nomcomplet() ?? ''
                            ],
                            "date_celebration" => $acte->declaration->date_prevue_mariage ?? $acte->declaration->date_celebration_mariage ?? '',
                            "etat_civil" => [
                                "nom_institution" => $acte->institution->lib_institution ?? ''
                            ]
                        ]
                    ]);
                }
            } else {
                // Aucun acte trouvé
                return response()->json([
                    "code" => "404",
                    "message" => [
                        "optionMariage" => "Aucun acte de mariage trouvé avec ce numéro."
                    ]
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la recherche d\'acte de mariage: ' . $e->getMessage(), [
                'numero_acte' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                "code" => "500",
                "message" => [
                    "optionMariage" => "Une erreur s'est produite lors de la vérification de l'acte de mariage."
                ]
            ], 500);
        }
     }


     public function repertoire()
     {
        return view('mariage::acte.repertoire');
     }


    public function repertoireetat()
    {
        $dated = request('dated');
        $datef = request('datef');

        if ($dated == null && $datef == null) {
            $actes = ActeMariage::where('cui', Auth::user()->affectationActive()->cui)->get();
        }

        if ($dated != null && $datef == null) {
            $actes = ActeMariage::where('cui', Auth::user()->affectationActive()->cui)->whereBetween('date_emission', [$dated, $dated])->get();
        }

        if ($dated == null && $datef != null) {
            $actes = ActeMariage::where('cui', Auth::user()->affectationActive()->cui)->whereBetween('date_emission', [$datef, $datef])->get();
        }

        if ($dated != null && $datef != null) {
            $actes = ActeMariage::where('cui', Auth::user()->affectationActive()->cui)->whereBetween('date_emission', [$dated, $datef])->get();
        }

        if ($actes == null) {
            toastr()->warning('Aucune donnée trouvée');
            return back();
        }

        view()->share("tester", "Vincent");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('mariage::etats.repertoire', compact("actes","dated", "datef"))->render());

        return $html2pdf->output("repertoireAlpha.pdf");
    }



    /**
     * Affiche l'acte de mariage pour impression
     */
    public function printActe($id)
    {
        $acte = ActeMariage::where("code_declaration_mariage", $id)->orWhere("code_acte_mariage", $id)->first();

        // Pas besoin de redirection ici, la vue gère le cas où $acte est null
        return view('mariage::acte.acte', compact("acte"));
    }

    /**
     * Annule un acte de mariage
     */
    public function annuler(Request $request)
    {
        try {
            $request->validate([
                'code_declaration_mariage' => 'required|string',
                'motif' => 'required|string',
                'observation' => 'nullable|string'
            ]);

            $declaration = DeclarationMariage::where('code_declaration_mariage', $request->code_declaration_mariage)->first();

            if (!$declaration || !$declaration->acte) {
                return response()->json([
                    'code' => '400',
                    'message' => 'Acte non trouvé'
                ], 400);
            }

            // Logique d'annulation (à adapter selon vos besoins)
            $declaration->acte->update([
                'annule' => true,
                'motif_annulation' => $request->motif,
                'observation_annulation' => $request->observation,
                'date_annulation' => now()
            ]);

            return response()->json([
                'code' => '200',
                'message' => [
                    'reponse' => 'Acte annulé avec succès'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'code' => '500',
                'message' => 'Erreur lors de l\'annulation de l\'acte: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Annule plusieurs actes de mariage
     */
    public function annulerBulk(Request $request)
    {
        try {
            $request->validate([
                'codes' => 'required|array',
                'motif' => 'required|string',
                'observation' => 'nullable|string'
            ]);

            $codes = $request->codes;
            $annules = 0;
            $erreurs = [];

            foreach ($codes as $code) {
                $declaration = DeclarationMariage::where('code_declaration_mariage', $code)->first();

                if ($declaration && $declaration->acte) {
                    $declaration->acte->update([
                        'annule' => true,
                        'motif_annulation' => $request->motif,
                        'observation_annulation' => $request->observation,
                        'date_annulation' => now()
                    ]);
                    $annules++;
                } else {
                    $erreurs[] = "Acte non trouvé pour le code: $code";
                }
            }

            return response()->json([
                'code' => '200',
                'message' => [
                    'reponse' => "$annules acte(s) annulé(s) avec succès"
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'code' => '500',
                'message' => 'Erreur lors de l\'annulation des actes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enregistre le retrait d'un acte de mariage
     */
    public function retrait(Request $request)
    {
        try {
            $request->validate([
                'code_acte_mariage' => 'required|string',
                'nominteresse' => 'required|string',
                'prenominteresse' => 'nullable|string',
                'telephoneinteresse' => 'required|string'
            ]);

            $acte = ActeMariage::where('code_acte_mariage', $request->code_acte_mariage)->first();

            if (!$acte) {
                return response()->json([
                    'code' => '400',
                    'message' => 'Acte non trouvé'
                ], 400);
            }

            // Logique de retrait (à adapter selon vos besoins)
            $acte->update([
                'retire' => true,
                'nom_interesse' => $request->nominteresse,
                'prenom_interesse' => $request->prenominteresse,
                'telephone_interesse' => $request->telephoneinteresse,
                'date_retrait' => now()
            ]);

            return response()->json([
                'code' => '200',
                'message' => [
                    'reponse' => 'Retrait enregistré avec succès'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'code' => '500',
                'message' => 'Erreur lors de l\'enregistrement du retrait: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche la copie d'un acte de mariage
     */
    public function copie($id)
    {
        $acte = ActeMariage::where("code_declaration_mariage", $id)->orWhere("code_acte_mariage", $id)->first();

        if (!$acte) {
            abort(404, 'Acte non trouvé');
        }

        return view('mariage::etats.copie', compact("acte"));
    }

    /**
     * Affiche l'extrait d'un acte de mariage
     */
    public function displayExtrait($id)
    {
        $acte = ActeMariage::where("code_declaration_mariage", $id)->orWhere("code_acte_mariage", $id)->first();

        if (!$acte) {
            abort(404, 'Acte non trouvé');
        }

        return view('mariage::etats.extrait', compact("acte"));
    }
}
