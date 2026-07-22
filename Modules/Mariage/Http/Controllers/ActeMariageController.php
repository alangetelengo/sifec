<?php

namespace Modules\Mariage\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Modules\Mariage\Entities\ActeMariage;
use Modules\Mariage\Entities\DeclarationMariage;
use Modules\Mariage\Services\ActeMariageService;
use Modules\Mariage\Services\MouvementMariageService;
use Modules\Mariage\Services\OtpService;
use Modules\Referentiel\Entities\Registre;
use Spipu\Html2Pdf\Html2Pdf;

class ActeMariageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $affectation = $user->affectationActive();
        $institution = $affectation->institution;

        // Registre réellement utilisable pour ce centre (même logique que la génération),
        // afin que le modal n'affiche pas un registre d'un autre CEC ou déjà complet.
        $registre = $this->resolveRegistreMariageDisponible($affectation);

        // $declarations = Auth::user()->affectationActive()->institution->declarationsMariages()->where("cec_approuver","OUI");

        $declarations = DeclarationMariage::where(function ($query) use ($institution) {
            $query->where('code_institution_destinataire', $institution->code_institution)
                ->orWhere('code_institution', $institution->code_institution);
        })
            ->where('cec_approuver', 'OUI')
            ->where(function ($query) {
                $query->where('type_declaration', '!=', 'DISPENSE')
                    ->orWhere(function ($subQuery) {
                        $subQuery->where('type_declaration', 'DISPENSE')
                            ->where('tribunal_approuver', 'OUI')
                            ->where(function ($requisitionQuery) {
                                $requisitionQuery->whereHas('requisition', function ($reqQuery) {
                                    $reqQuery->where('statut', 'envoyée');
                                })
                                    ->orWhereHas('jugement', function ($jugQuery) {
                                        $jugQuery->where('statut', 'envoyée');
                                    });
                            });
                    });
            })
            ->get();

        return view('mariage::acte.index', compact('declarations', 'registre'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {

        return view('mariage::acte.index');
    }

    /**
     * Store a newly created resource in storage.
     *
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
        $affectation = $user->affectationActive();
        $declaration = DeclarationMariage::findOrFail($request->code_declaration_mariage);

        // Prérequis : la déclaration doit être signée électroniquement par le centre d'état civil
        // ET confirmée. On exige les deux pour éviter qu'un dossier signé mais dont la confirmation
        // a échoué (état incohérent) puisse tout de même donner lieu à un acte.
        if (! filled($declaration->sig_cec_proof_id)) {
            return response()->json([
                'code' => '403',
                'message' => "La déclaration doit d'abord être signée électroniquement lors de la confirmation du dossier avant de générer l'acte.",
            ], 403);
        }

        if ($declaration->cec_approuver !== 'OUI') {
            return response()->json([
                'code' => '403',
                'message' => "Le dossier doit être confirmé par le centre d'état civil avant de générer l'acte.",
            ], 403);
        }

        // Vérifier si un acte existe déjà pour cette déclaration
        $acteExistant = $service->obtenirActeParDeclaration($declaration->code_declaration_mariage);
        if ($acteExistant) {
            return response()->json([
                'code' => '409',
                'message' => 'Un acte de mariage existe déjà pour cette déclaration (Code: '.$acteExistant->code_acte_mariage.')',
            ], 409);
        }

        // Protection supplémentaire : vérifier si une génération est en cours
        $cacheKey = 'generation_acte_'.$declaration->code_declaration_mariage;
        if (cache()->has($cacheKey)) {
            return response()->json([
                'code' => '429',
                'message' => "Une génération d'acte est déjà en cours pour cette déclaration. Veuillez patienter.",
            ], 429);
        }

        // Marquer qu'une génération est en cours (expire après 30 secondes)
        cache()->put($cacheKey, true, 30);

        if (! Gate::allows('module.acteMariage.generate')) {
            // Libérer le verrou avant de sortir.
            cache()->forget($cacheKey);

            return response()->json([
                'code' => '403',
                'message' => "Vous n'êtes pas autorisé à générer un acte",
            ], 403);
        }

        // Résolution robuste du registre : d'abord par CUI de l'agent, sinon par le CEC de
        // l'affectation, avec statut actif et feuillets restants (cf. module Naissance).
        $registre = $this->resolveRegistreMariageDisponible($affectation);

        if (! $registre) {
            // Libérer le verrou avant de sortir.
            cache()->forget($cacheKey);

            return response()->json([
                'code' => '400',
                'message' => $this->messageRegistreMariageIndisponible($affectation),
            ], 400);
        }
        DB::beginTransaction();
        try {
            $acte = $service->genererActe($declaration, $registre, $user);

            $mouvementService = app(MouvementMariageService::class);
            $mouvementService->ajouterEvenementActe($user, $acte, 'attente_approbation', null, $acte);

            DB::commit();

            // Libérer le verrou de génération
            cache()->forget($cacheKey);

            return response()->json([
                'code' => '200',
                'message' => 'Acte de mariage généré avec succès',
                'data' => [
                    'code_acte_mariage' => $acte->code_acte_mariage,
                    'niupp' => $acte->code_acte_mariage,
                ],
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            // Libérer le verrou de génération en cas d'erreur
            cache()->forget($cacheKey);

            return response()->json([
                'code' => '500',
                'message' => "Erreur lors de la génération de l'acte: ".$e->getMessage(),
            ], 500);
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
        return view('mariage::acte.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('mariage::acte.edit');
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

    /**
     * Envoyer OTP pour validation d'un acte
     */
    public function sendOtp(Request $request, OtpService $otpService)
    {
        $rules = [
            'code_declaration_mariage' => ['required', 'string'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'code' => '180',
                'message' => 'Code de déclaration requis',
            ]);
        }

        if (! Gate::allows('module.acteMariage.signature')) {
            return response()->json([
                'code' => '181',
                'message' => "Vous n'êtes pas autorisé à valider un acte de mariage",
            ]);
        }

        try {
            $acte = ActeMariage::where('code_declaration_mariage', $request->code_declaration_mariage)->first();
            if (! $acte) {
                return response()->json([
                    'code' => '182',
                    'message' => 'Aucun acte trouvé pour ce code',
                ]);
            }

            $otp = $otpService->envoyerOtpValidationActes(Auth::user(), [$acte]);

            return response()->json([
                'code' => '200',
                'message' => 'SMS envoyé avec succès',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'code' => '183',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function validateOtp(Request $request, OtpService $otpService)
    {
        $rules = [
            'otp_approbation_mairie' => ['required', 'numeric'],
            'code_declaration_mariage' => ['required', 'string'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'code' => '180',
                'message' => 'Données requises manquantes',
            ]);
        }

        if (! Gate::allows('module.acteMariage.signature')) {
            return response()->json([
                'code' => '181',
                'message' => "Vous n'êtes pas autorisé à valider un acte de mariage",
            ]);
        }

        try {
            [$success, $message] = $otpService->validerOtpActes(
                [$request->code_declaration_mariage], $request->otp_approbation_mairie,
                $request->ip(),
                $request->userAgent()
            );

            if (! $success) {
                return response()->json([
                    'code' => '183',
                    'message' => $message,
                ]);
            }

            return response()->json([
                'code' => '200',
                'message' => 'Acte de mariage validé avec succès',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'code' => '183',
                'message' => $e->getMessage(),
            ]);
        }
    }

    // Send OTP for bulk validation
    public function sendOtpBulk(Request $request, OtpService $otpService)
    {
        $codes = $request->codes;

        if (empty($codes)) {
            return response()->json([
                'code' => '180',
                'message' => 'Aucune déclaration sélectionnée',
            ]);
        }

        $actes = ActeMariage::whereIn('code_declaration_mariage', $codes)->get();
        if ($actes->count() == 0) {
            return response()->json([
                'code' => '180',
                'message' => 'Aucun acte trouvé',
            ]);
        }

        if (! Gate::allows('module.acteMariage.signature')) {
            return response()->json([
                'code' => '181',
                'message' => "Vous n'êtes pas autorisé à valider des actes de mariage",
            ]);
        }

        try {
            $otp = $otpService->envoyerOtpValidationActes(Auth::user(), $actes);

            return response()->json([
                'code' => '200',
                'message' => 'SMS envoyé avec succès',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'code' => '181',
                'message' => $e->getMessage(),
            ]);
        }
    }

    // Validate multiple actes
    public function validateOtpBulk(Request $request, OtpService $otpService)
    {
        $rules = [
            'otp_approbation_mairie' => ['required', 'numeric'],
            'codes' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'code' => '180',
                'message' => 'Données requises manquantes',
            ]);
        }

        if (! Gate::allows('module.acteMariage.signature')) {
            return response()->json([
                'code' => '181',
                'message' => "Vous n'êtes pas autorisé à valider des actes de mariage",
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

            if (! $success) {
                return response()->json([
                    'code' => '183',
                    'message' => $message,
                ]);
            }

            return response()->json([
                'code' => '200',
                'message' => 'Acte(s) de mariage validé(s) avec succès',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'code' => '183',
                'message' => $e->getMessage(),
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
            $acteMariageService = new ActeMariageService;
            $acte = $acteMariageService->rechercherActe($id);

            if ($acte !== null) {
                $optionMariage = $acte->declaration->optionMariage;

                if ($optionMariage->code_option_mariage == 'OMRG_0002') {
                    // Monogamie - problème
                    return response()->json([
                        'code' => '99',
                        'message' => [
                            'optionMariage' => "Il semble que l'époux soit déjà marié avec l'option <strong>Monogamie</strong>, au cas où il serait divorcé, alors veuillez présenter le jugement du divorce ou bien l'acte de décès de son épouse",
                        ],
                        'acte' => [
                            'code_acte' => $acte->code_acte_mariage,
                            'date_emission' => $acte->date_emission,
                            'option_mariage' => $optionMariage->lib_option_mariage,
                        ],
                    ]);
                } else {
                    // Polygamie - OK
                    return response()->json([
                        'code' => '200',
                        'message' => [
                            'optionMariage' => "L'époux est déjà marié avec l'option ".$optionMariage->lib_option_mariage.'. Le processus peut continuer.',
                        ],
                        'acte' => [
                            'code_acte' => $acte->code_acte_mariage,
                            'date_emission' => $acte->date_emission,
                            'option_mariage' => $optionMariage->lib_option_mariage,
                            'epouse' => [
                                'nom' => $acte->declaration->epouse->nom ?? '',
                                'prenom' => $acte->declaration->epouse->prenom ?? '',
                                'nom_complet' => $acte->declaration->epouse->nomcomplet() ?? '',
                            ],
                            'date_celebration' => $acte->declaration->date_prevue_mariage ?? $acte->declaration->date_celebration_mariage ?? '',
                            'etat_civil' => [
                                'nom_institution' => $acte->institution->lib_institution ?? '',
                            ],
                        ],
                    ]);
                }
            } else {
                // Aucun acte trouvé
                return response()->json([
                    'code' => '404',
                    'message' => [
                        'optionMariage' => 'Aucun acte de mariage trouvé avec ce numéro.',
                    ],
                ]);
            }
        } catch (Exception $e) {
            \Log::error('Erreur lors de la recherche d\'acte de mariage: '.$e->getMessage(), [
                'numero_acte' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'code' => '500',
                'message' => [
                    'optionMariage' => "Une erreur s'est produite lors de la vérification de l'acte de mariage.",
                ],
            ], 500);
        }
    }

    public function repertoire()
    {
        return redirect()->route('reporting.faits.repertoire.alphabetique', ['type_fait' => 'mariage']);
    }

    public function repertoireetat()
    {
        return redirect()->route('reporting.faits.repertoire.alphabetique', array_filter([
            'type_fait' => 'mariage',
            'dated' => request('dated'),
            'datef' => request('datef'),
        ], fn ($v) => $v !== null && $v !== ''));
    }

    /**
     * Affiche l'acte de mariage pour impression
     */
    public function printActe($id)
    {
        $acte = ActeMariage::where('code_declaration_mariage', $id)->orWhere('code_acte_mariage', $id)->first();

        // Pas besoin de redirection ici, la vue gère le cas où $acte est null
        return view('mariage::acte.acte', compact('acte'));
    }

    /**
     * Affiche la copie de l'acte de mariage dans une page SIFEC (visualiseur intégré),
     * comme le bouton « Voir l'acte » et conformément au module Naissance.
     */
    public function printCopie($id)
    {
        $acte = ActeMariage::where('code_declaration_mariage', $id)->orWhere('code_acte_mariage', $id)->first();

        return view('mariage::acte.copie', compact('acte'));
    }

    /**
     * Affiche l'extrait de l'acte de mariage dans une page SIFEC (visualiseur intégré),
     * comme le bouton « Voir l'acte » et conformément au module Naissance.
     */
    public function printExtrait($id)
    {
        $acte = ActeMariage::where('code_declaration_mariage', $id)->orWhere('code_acte_mariage', $id)->first();

        return view('mariage::acte.extrait', compact('acte'));
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
                'observation' => 'nullable|string',
            ]);

            $declaration = DeclarationMariage::where('code_declaration_mariage', $request->code_declaration_mariage)->first();

            if (! $declaration || ! $declaration->acte) {
                return response()->json([
                    'code' => '400',
                    'message' => 'Acte non trouvé',
                ], 400);
            }

            // Logique d'annulation (à adapter selon vos besoins)
            $declaration->acte->update([
                'annule' => true,
                'motif_annulation' => $request->motif,
                'observation_annulation' => $request->observation,
                'date_annulation' => now(),
            ]);

            return response()->json([
                'code' => '200',
                'message' => [
                    'reponse' => 'Acte annulé avec succès',
                ],
            ]);

        } catch (Exception $e) {
            return response()->json([
                'code' => '500',
                'message' => 'Erreur lors de l\'annulation de l\'acte: '.$e->getMessage(),
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
                'observation' => 'nullable|string',
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
                        'date_annulation' => now(),
                    ]);
                    $annules++;
                } else {
                    $erreurs[] = "Acte non trouvé pour le code: $code";
                }
            }

            return response()->json([
                'code' => '200',
                'message' => [
                    'reponse' => "$annules acte(s) annulé(s) avec succès",
                ],
            ]);

        } catch (Exception $e) {
            return response()->json([
                'code' => '500',
                'message' => 'Erreur lors de l\'annulation des actes: '.$e->getMessage(),
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
                'telephoneinteresse' => 'required|string',
            ]);

            $acte = ActeMariage::where('code_acte_mariage', $request->code_acte_mariage)->first();

            if (! $acte) {
                return response()->json([
                    'code' => '400',
                    'message' => 'Acte non trouvé',
                ], 400);
            }

            // Prérequis : l'acte doit être validé/signé par l'officier avant tout retrait.
            // On se base sur approbation_mairie (renseignée aussi bien par la signature GUOT que
            // par l'ancien flux OTP) afin de ne pas rendre irrétirables les actes déjà validés en
            // production avant la bascule vers la signature électronique.
            if (! filled($acte->approbation_mairie)) {
                return response()->json([
                    'code' => '403',
                    'message' => "L'acte doit être signé électroniquement par l'officier d'état civil avant d'être retiré.",
                ], 403);
            }

            // Logique de retrait (à adapter selon vos besoins)
            $acte->update([
                'retire' => true,
                'nom_interesse' => $request->nominteresse,
                'prenom_interesse' => $request->prenominteresse,
                'telephone_interesse' => $request->telephoneinteresse,
                'date_retrait' => now(),
            ]);

            return response()->json([
                'code' => '200',
                'message' => [
                    'reponse' => 'Retrait enregistré avec succès',
                ],
            ]);

        } catch (Exception $e) {
            return response()->json([
                'code' => '500',
                'message' => 'Erreur lors de l\'enregistrement du retrait: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Affiche la copie d'un acte de mariage
     */
    public function copie($id)
    {
        $acte = ActeMariage::where('code_declaration_mariage', $id)->orWhere('code_acte_mariage', $id)->first();

        if (! $acte) {
            abort(404, 'Acte non trouvé');
        }

        $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('mariage::etats.copie', compact('acte'))->render());

        return $html2pdf->output('CopieActeMariage.pdf');
    }

    /**
     * Affiche l'extrait d'un acte de mariage
     */
    public function displayExtrait($id)
    {
        $acte = ActeMariage::where('code_declaration_mariage', $id)->orWhere('code_acte_mariage', $id)->first();

        if (! $acte) {
            abort(404, 'Acte non trouvé');
        }

        $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('mariage::etats.extrait', compact('acte'))->render());

        return $html2pdf->output('ExtraitActeMariage.pdf');
    }

    /**
     * Registre de mariage utilisable pour générer un acte : actif (statut = 1) et disposant
     * encore de feuillets. Recherche d'abord sur le CUI de l'agent, sinon sur le CEC de
     * l'affectation (alignée sur le module Naissance).
     */
    private function resolveRegistreMariageDisponible($affectation): ?Registre
    {
        if ($affectation === null) {
            return null;
        }

        $base = Registre::query()
            ->where('code_type_registre', 'TPRG_0002')
            ->where('statut', 1)
            ->whereColumn('nombre_acte_transcrit', '<', 'nombre_acte_prevu')
            ->orderByDesc('updated_at');

        $byCui = (clone $base)->where('cui', $affectation->cui)->first();
        if ($byCui !== null) {
            return $byCui;
        }

        $codeInstitution = $affectation->code_institution ?? $affectation->institution?->code_institution;
        if (! filled($codeInstitution)) {
            return null;
        }

        return (clone $base)
            ->whereHas('institutionUser', function ($q) use ($codeInstitution) {
                $q->where('code_institution', $codeInstitution);
            })
            ->first();
    }

    /**
     * Message de diagnostic lorsqu'aucun registre de mariage n'est utilisable.
     */
    private function messageRegistreMariageIndisponible($affectation): string
    {
        if ($affectation === null) {
            return 'Aucune affectation active : impossible de déterminer le registre.';
        }

        $candidats = Registre::query()
            ->where('code_type_registre', 'TPRG_0002')
            ->where(function ($q) use ($affectation) {
                $q->where('cui', $affectation->cui);
                $codeInstitution = $affectation->code_institution ?? $affectation->institution?->code_institution;
                if (filled($codeInstitution)) {
                    $q->orWhereHas('institutionUser', function ($q2) use ($codeInstitution) {
                        $q2->where('code_institution', $codeInstitution);
                    });
                }
            })
            ->orderByDesc('updated_at')
            ->get();

        if ($candidats->isEmpty()) {
            return 'Aucun registre de mariage trouvé pour ce centre. Créez un registre puis faites-le parapher par le tribunal.';
        }

        $enAttente = $candidats->first(function (Registre $r) {
            return (int) $r->statut === 0;
        });

        $plein = $candidats->first(function (Registre $r) {
            return (int) $r->statut === 1
                && ((int) $r->nombre_acte_prevu - (int) $r->nombre_acte_transcrit) <= 0;
        });
        if ($plein !== null) {
            return 'Le registre de mariage est plein. Ajoutez des feuillets ou ouvrez un nouveau registre.';
        }

        if ($enAttente !== null) {
            return 'Le registre de mariage est en attente de paraphe/activation. Impossible de générer un acte tant qu\'il n\'est pas actif.';
        }

        return 'Aucun registre de mariage actif et disponible pour générer un acte.';
    }
}
