<?php

namespace Modules\Naissance\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Sifec\Sifec;
use App\Sifec\SifecFacade;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Modules\Notification\Jobs\SendSmsJob;
use Modules\Naissance\Services\OtpService;
use Modules\Referentiel\Entities\Registre;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\RetraitActe;
use Modules\Mariage\Entities\DeclarationMariage;
use Modules\Naissance\Services\MouvementService;
use Modules\Naissance\Entities\MouvementNaissance;
use Modules\Naissance\Entities\Declarationnaissance;
use Modules\Naissance\Services\ActeNaissanceService;
use Modules\Naissance\Http\Requests\GenerateActeRequest;
use Modules\Notification\Jobs\ValidationacteNaissanceJob;
use Illuminate\Support\Facades\URL;

use Modules\Notification\Services\NotificationService;


class ActeNaissanceController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $affectation = $user->affectationActive();
        $institution = $affectation->institution;

        // Documents à contrôler (cec_approuver = 'NON') - Afficher les 20 derniers par défaut
        $documentsAControler = $institution->getDocumentsAControler("naissance")
            ->take(20)
            ->values();

        // Gestion des actes (cec_approuver = 'OUI') - Afficher les 20 derniers par défaut
        $actesGestion = $institution->getActesGestion("naissance")
            ->take(20)
            ->values();

        $registre = Registre::where("cui",$affectation->cui)->where("statut",1)->where("code_type_registre","TPRG_0001")->first();

        // Récupérer les types de déclaration uniques pour les filtres
        $typesDeclaration = \Modules\Naissance\Entities\Declarationnaissance::distinct()
            ->pluck('type_declaration')
            ->filter()
            ->sort()
            ->values();

        return view(
            'naissance::acte.index', compact(
            "documentsAControler",
            "actesGestion",
            "registre",
            "typesDeclaration"
        ));
    }

    /**
     * Filtrer les documents à contrôler côté serveur
     */
    public function filterDocuments(Request $request)
    {
        try {
            $user = Auth::user();
            $affectation = $user->affectationActive();
            $institution = $affectation->institution;

            // Logger les critères de recherche
            Log::channel('sifec')->info('=== RECHERCHE DOCUMENTS À CONTRÔLER ===', [
                'user_id' => $user->code_user ?? null,
                'institution' => $institution->code_institution ?? null,
                'criteres' => [
                    'numero_declaration' => $request->input('numero_declaration'),
                    'date_debut' => $request->input('date_debut'),
                    'date_fin' => $request->input('date_fin'),
                    'sexe' => $request->input('sexe'),
                    'type_declaration' => $request->input('type_declaration'),
                    'statut' => $request->input('statut'),
                ]
            ]);

            // Recherche : tous les dossiers du CEC (créés par ou reçus par l'institution), validés ou non
            $documentsAControler = Declarationnaissance::with([
                'enfant', 'declarant', 'pere', 'mere', 'mouvements', 'acte', 'requisition', 'jugement'
            ])
                ->where(function ($q) use ($institution) {
                    $q->where('code_institution_destinataire', $institution->code_institution)
                        ->orWhere('code_institution', $institution->code_institution);
                })
                ->orderByDesc('date_heure_declaration')
                ->get();
            $countInitial = $documentsAControler->count();

            // Filtre par numéro de déclaration
            if ($request->filled('numero_declaration') && strlen(trim($request->numero_declaration)) > 0) {
                $search = trim($request->numero_declaration);
                $documentsAControler = $documentsAControler->filter(
                    fn($doc) => stripos($doc->code_declaration_naissance ?? '', $search) !== false
                );
            }

            // Filtre par type de déclaration
            if ($request->filled('type_declaration') && strlen(trim($request->type_declaration)) > 0) {
                $documentsAControler = $documentsAControler->filter(
                    fn($doc) => $doc->type_declaration === $request->type_declaration
                );
            }

            // Filtre par période (date de déclaration)
        if ($request->filled('date_debut')) {
            $documentsAControler = $documentsAControler->filter(function($doc) use ($request) {
                // Utiliser date_heure_declaration (date de déclaration) pour filtrer par date de création du document
                $dateDeclaration = $doc->date_heure_declaration ?? $doc->created_at;
                if ($dateDeclaration) {
                    return date('Y-m-d', strtotime($dateDeclaration)) >= $request->date_debut;
                }
                return false;
            });
        }
        if ($request->filled('date_fin')) {
            $documentsAControler = $documentsAControler->filter(function($doc) use ($request) {
                // Utiliser date_heure_declaration (date de déclaration) pour filtrer par date de création du document
                $dateDeclaration = $doc->date_heure_declaration ?? $doc->created_at;
                if ($dateDeclaration) {
                    return date('Y-m-d', strtotime($dateDeclaration)) <= $request->date_fin;
                }
                return false;
            });
        }

        // Filtre par sexe
        if ($request->filled('sexe')) {
            $documentsAControler = $documentsAControler->filter(
                fn($doc) => $doc->enfant && $doc->enfant->sexe === $request->sexe
            );
        }

        // Filtre par statut (basé sur les mouvements)
        if ($request->filled('statut')) {
            $statut = $request->statut;
            $documentsAControler = $documentsAControler->filter(function($doc) use ($statut) {
                $codesMouvements = $doc->mouvements ? $doc->mouvements->pluck('code_mouvement')->toArray() : [];
                if ($statut === 'dossier_recu') {
                    return in_array('MOUV_0001', $codesMouvements) ||
                           in_array('MOUV_0011', $codesMouvements) ||
                           in_array('MOUV_0024', $codesMouvements);
                } elseif ($statut === 'confirme') {
                    return in_array('MOUV_0019', $codesMouvements);
                } elseif ($statut === 'en_attente') {
                    return in_array('MOUV_0004', $codesMouvements);
                }
                return true;
            });
        }

        $documents = $documentsAControler->values();

        // Les relations sont déjà chargées via getDocumentsAControler (avec 'with')
        // Il n'est pas nécessaire de les recharger

        $countResultat = $documents->count();

        // Limiter les résultats à 500 maximum pour éviter les problèmes de performance
        // Si l'utilisateur a besoin de voir plus, il peut affiner ses critères de recherche
        $maxResults = 500;
        if ($countResultat > $maxResults) {
            $documents = $documents->take($maxResults);
            Log::channel('sifec')->warning('=== RECHERCHE DOCUMENTS - LIMITE ATTEINTE ===', [
                'user_id' => $user->code_user ?? null,
                'count_total' => $countResultat,
                'count_affiché' => $maxResults,
                'message' => "Plus de {$maxResults} résultats trouvés. Affinez vos critères de recherche pour voir tous les résultats."
            ]);
        }

            // Logger les résultats de la recherche
            Log::channel('sifec')->info('=== RÉSULTATS RECHERCHE DOCUMENTS À CONTRÔLER ===', [
                'user_id' => $user->code_user ?? null,
                'institution' => $institution->code_institution ?? null,
                'count_initial' => $countInitial,
                'count_resultat' => $countResultat,
                'count_affiché' => $documents->count(),
                'filtres_appliques' => $request->only(['numero_declaration', 'date_debut', 'date_fin', 'sexe', 'type_declaration', 'statut'])
            ]);

            return response()->json([
                'code' => '200',
                'data' => view('naissance::acte.partials.table-documents', compact('documents'))->render(),
                'count' => $countResultat,
                'count_affiché' => $documents->count(),
                'limite_atteinte' => $countResultat > $maxResults
            ]);
        } catch (\Exception $e) {
            Log::channel('sifec')->error('=== ERREUR RECHERCHE DOCUMENTS À CONTRÔLER ===', [
                'user_id' => Auth::user()->code_user ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'criteres' => $request->only(['numero_declaration', 'date_debut', 'date_fin', 'sexe', 'type_declaration', 'statut'])
            ]);

            return response()->json([
                'code' => '500',
                'message' => 'Erreur lors de la recherche des documents',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Filtrer les actes à gérer côté serveur
     */
    public function filterActes(Request $request)
    {
        try {
            $user = Auth::user();
            $affectation = $user->affectationActive();
            $institution = $affectation->institution;

            // Logger les critères de recherche
            Log::channel('sifec')->info('=== RECHERCHE ACTES À GÉRER ===', [
                'user_id' => $user->code_user ?? null,
                'institution' => $institution->code_institution ?? null,
                'criteres' => [
                    'numero_declaration' => $request->input('numero_declaration'),
                    'niupp' => $request->input('niupp'),
                    'date_debut' => $request->input('date_debut'),
                    'date_fin' => $request->input('date_fin'),
                    'sexe' => $request->input('sexe'),
                    'statut' => $request->input('statut'),
                ]
            ]);

            // Utiliser la même méthode que getActesGestion pour garantir la cohérence
            $actesGestion = $institution->getActesGestion("naissance");
            $countInitial = $actesGestion->count();

        // Filtre par numéro de déclaration
        if ($request->filled('numero_declaration')) {
            $actesGestion = $actesGestion->filter(function($acte) use ($request) {
                return stripos($acte->code_declaration_naissance, $request->numero_declaration) !== false;
            });
        }

        // Filtre par NIUPP (numéro d'acte)
        if ($request->filled('niupp')) {
            $actesGestion = $actesGestion->filter(function($acte) use ($request) {
                return $acte->acte && stripos($acte->acte->niupp, $request->niupp) !== false;
            });
        }

        // Filtre par période (date de déclaration de naissance)
        if ($request->filled('date_debut')) {
            $actesGestion = $actesGestion->filter(function($acte) use ($request) {
                // Utiliser date_heure_declaration (date de déclaration) pour filtrer par date de création du document
                $dateDeclaration = $acte->date_heure_declaration ?? $acte->created_at;
                if ($dateDeclaration) {
                    return date('Y-m-d', strtotime($dateDeclaration)) >= $request->date_debut;
                }
                return false;
            });
        }
        if ($request->filled('date_fin')) {
            $actesGestion = $actesGestion->filter(function($acte) use ($request) {
                // Utiliser date_heure_declaration (date de déclaration) pour filtrer par date de création du document
                $dateDeclaration = $acte->date_heure_declaration ?? $acte->created_at;
                if ($dateDeclaration) {
                    return date('Y-m-d', strtotime($dateDeclaration)) <= $request->date_fin;
                }
                return false;
            });
        }

        // Filtre par sexe
        if ($request->filled('sexe')) {
            $actesGestion = $actesGestion->filter(function($acte) use ($request) {
                return $acte->enfant && $acte->enfant->sexe === $request->sexe;
            });
        }

        // Filtre par statut
        if ($request->filled('statut')) {
            $statut = $request->statut;
            $actesGestion = $actesGestion->filter(function($acte) use ($statut) {
                $codesMouvements = $acte->mouvements ? $acte->mouvements->pluck('code_mouvement')->toArray() : [];
                $dernierMouvement = $acte->mouvements ? $acte->mouvements->sortByDesc('created_at')->first() : null;

                if ($statut === 'en_attente_generation') {
                    return !$acte->acte;
                } elseif ($statut === 'en_attente_validation') {
                    return $acte->acte && (!$acte->acte->approbation_mairie || $acte->acte->approbation_mairie === '');
                } elseif ($statut === 'valide_non_retire') {
                    return $acte->acte && $acte->acte->approbation_mairie &&
                           (!$dernierMouvement || ($dernierMouvement->code_mouvement !== 'MOUV_0016' && $dernierMouvement->code_mouvement !== 'MOUV_0017'));
                } elseif ($statut === 'retire') {
                    return $dernierMouvement && $dernierMouvement->code_mouvement === 'MOUV_0016';
                } elseif ($statut === 'annule') {
                    return $dernierMouvement && $dernierMouvement->code_mouvement === 'MOUV_0017';
                }
                return true;
            });
        }

        $actes = $actesGestion->values();

        $countResultat = $actes->count();

        // Limiter les résultats à 500 maximum pour éviter les problèmes de performance
        // Si l'utilisateur a besoin de voir plus, il peut affiner ses critères de recherche
        $maxResults = 500;
        if ($countResultat > $maxResults) {
            $actes = $actes->take($maxResults);
            Log::channel('sifec')->warning('=== RECHERCHE ACTES - LIMITE ATTEINTE ===', [
                'user_id' => $user->code_user ?? null,
                'count_total' => $countResultat,
                'count_affiché' => $maxResults,
                'message' => "Plus de {$maxResults} résultats trouvés. Affinez vos critères de recherche pour voir tous les résultats."
            ]);
        }

            // Logger les résultats de la recherche
            Log::channel('sifec')->info('=== RÉSULTATS RECHERCHE ACTES À GÉRER ===', [
                'user_id' => $user->code_user ?? null,
                'institution' => $institution->code_institution ?? null,
                'count_initial' => $countInitial,
                'count_resultat' => $countResultat,
                'count_affiché' => $actes->count(),
                'filtres_appliques' => $request->only(['numero_declaration', 'niupp', 'date_debut', 'date_fin', 'sexe', 'statut'])
            ]);

            return response()->json([
                'code' => '200',
                'data' => view('naissance::acte.partials.table-actes', compact('actes'))->render(),
                'count' => $countResultat,
                'count_affiché' => $actes->count(),
                'limite_atteinte' => $countResultat > $maxResults
            ]);
        } catch (\Exception $e) {
            Log::channel('sifec')->error('=== ERREUR RECHERCHE ACTES À GÉRER ===', [
                'user_id' => Auth::user()->code_user ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'criteres' => $request->only(['numero_declaration', 'niupp', 'date_debut', 'date_fin', 'sexe', 'statut'])
            ]);

            return response()->json([
                'code' => '500',
                'message' => 'Erreur lors de la recherche des actes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function displayActe($id)
    {
        try {
            // Charger l'acte avec ses relations nécessaires
            $acte = ActeNaissance::with([
                'declaration.enfant',
                'declaration.pere.nationalite',
                'declaration.pere.profession',
                'declaration.mere.nationalite',
                'declaration.mere.profession',
                'declaration.declarant',
                'declaration.adoptant',
                'declaration.jugement.institutionUser.institution.institutionParent',
                'declaration.institutionUser.institution.institutionParent',
                'declaration.institutionUser.institution.lieu.localiteParent',
                'declaration.requisition.typeRequisition',
                'declaration.institution.institutionParent',
                'institutionUser.institution',
                'institutionUser.institution.institutionParent.lieu.localiteParent'
            ])->where("code_declaration_naissance", $id)->first();

            if($acte == null){
                Log::channel('sifec')->error("Acte de naissance introuvable pour code_declaration_naissance: {$id}");
                toastr()->error("Vous ne pouvez pas générer un acte de naissance. Acte introuvable.");
                return back();
            }

            // Vérifier que la déclaration existe
            if(!$acte->declaration) {
                Log::channel('sifec')->error("Déclaration manquante pour acte: {$acte->code_acte_naissance}");
                throw new Exception("Données incomplètes pour générer l'acte. Déclaration manquante.");
            }

            $dummy = "XXXXXXXXXXXXXXXX";

            // Recherche de l'acte annulé (si existe)
            $acteannuler = Declarationnaissance::where("numero_ancien_acte", $acte->niupp)->first();

            // Recherche de déclaration de décès (si existe)
            $declarationDeces = DeclarationDeces::where("num_acte_naissance", $acte->niupp)->first();

            // Recherche de mariage (si existe)
            $mariage = null;
            $mariageEpoux = DeclarationMariage::where('numero_acte_naissance_epoux', $acte->niupp)->first();
            if($mariageEpoux) {
                $mariage = $mariageEpoux;
            } else {
                $mariageEpouse = DeclarationMariage::where('numero_acte_naissance_epouse', $acte->niupp)->first();
                if($mariageEpouse) {
                    $mariage = $mariageEpouse;
                }
            }

            // Compter le nombre total de mentions
            $nombreMentions = 0;
            if ($mariage != null) {
                $nombreMentions++;
            }
            if ($declarationDeces != null) {
                $nombreMentions++;
            }
            if ($acte->declaration->jugement != null) {
                $nombreMentions++;
            }
            if ($acteannuler != null) {
                $nombreMentions++;
            }
            // Charger les rectifications si nécessaire pour le comptage
            if (!$acte->relationLoaded('rectifications')) {
                $acte->load('rectifications');
            }
            if ($acte->rectifications && $acte->rectifications->count() > 0) {
                $nombreMentions += $acte->rectifications->count();
            }

            DB::beginTransaction();

            view()->share("tester", "Alange");
            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->setDefaultFont('Arial');

            $verificationUrl = URL::signedRoute('verification.acte', ['niupp' => $acte->niupp]);
            $qrCode = $verificationUrl;

            // Rendre la vue avec gestion d'erreur
            $htmlContent = view('naissance::etats.acte', compact("acte","dummy","acteannuler","declarationDeces","mariage","qrCode","nombreMentions"))->render();

            if(empty($htmlContent)) {
                throw new Exception("Le contenu HTML de l'acte est vide.");
            }

            $html2pdf->writeHTML($htmlContent);
            DB::commit();

            return $html2pdf->output($acte->code_acte_naissance.".pdf");

        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error("Erreur génération PDF acte de naissance ID: {$id} - Message: " . $e->getMessage());
            Log::channel('sifec')->error("Stack trace: " . $e->getTraceAsString());

            // Si c'est une requête AJAX ou PDF, renvoyer une réponse JSON ou une erreur HTTP
            if (request()->expectsJson() || request()->wantsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => "Erreur lors de la génération du PDF: " . $e->getMessage()
                ], 500);
            }

            // Sinon, renvoyer une réponse HTML d'erreur pour le PDF Viewer
            return response("Erreur lors de la génération du PDF: " . $e->getMessage(), 500)
                ->header('Content-Type', 'text/plain');
        }
    }

    public function displayCopie($id)
    {
        try {
            $acte = ActeNaissance::where("code_declaration_naissance",$id)->first();
            $dummy = "XXXXXXXXXXXXXXXX";

            if($acte == null){
                toastr()->error("Vous ne pouvez pas généré un acte de naissance");
                return back();
            }

            $declarationDeces = DeclarationDeces::where("num_acte_naissance", $acte->niupp)->first();

            $mariage = null;
            if (DeclarationMariage::where('numero_acte_naissance_epoux',$acte->niupp)->first() != null) {
                $mariage = DeclarationMariage::where('numero_acte_naissance_epoux',$acte->niupp)->first();
            }
            if (DeclarationMariage::where('numero_acte_naissance_epouse',$acte->niupp)->first() != null) {
                $mariage = DeclarationMariage::where('numero_acte_naissance_epouse',$acte->niupp)->first();
            }

            view()->share("tester", "Alange");
            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->setDefaultFont('Arial');
            $verificationUrl = URL::signedRoute('verification.acte', ['niupp' => $acte->niupp]);
            $qrCode = $verificationUrl;
            $html2pdf->writeHTML(view('naissance::etats.copieActeNaissance', compact("acte","dummy", "declarationDeces","mariage","qrCode"))->render());

            return $html2pdf->output($acte->code_acte_naissance.".pdf");
        } catch (Exception $e) {
            toastr()->error("Une erreur est survenue lors de la génération de la copie d'acte de naissance: " . $e->getMessage());
            return back();
        }
    }

    //generation de l'acte single
    public function generateActe(GenerateActeRequest $request, ActeNaissanceService $service, MouvementService $mouvementService)
    {
        $user = Auth::user();
        $declaration = Declarationnaissance::findOrFail($request->code_declaration_naissance);
        $registre = $user->affectationActive()->registres()->where("code_type_registre","TPRG_0001")->where("statut",1)->first();

        if (!Gate::allows("module.acteNaissance.generate")) {
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
            $service->genererActe($declaration, $registre, $user);
            $mouvementService->ajouterEvenementActe($user, $declaration, 'attente_approbation');
            // Notifier les officiers de l'état civil de la disponibilité de l'acte à valider
            $codeInstitutionCentre = $user->affectationActive()->institution->code_institution;
            $numeroActe = $declaration->acte ? $declaration->acte->niupp : null;
            if($numeroActe) {

                  // Notification centralisée via le module Notification
                  NotificationService::notifierAgentsInstitution(
                    $codeInstitutionCentre,
                    new \Modules\Notification\Notifications\ActeAValiderNotification(
                        $numeroActe,
                        "Acte de naissance généré et en attente de la signature de l'officier d'état civil"
                    ));
            }

            DB::commit();
            return response()->json([
                "code" => "200",
                "message" => ["Acte naissance généré avec succès"]
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel("sifec")->info("Erreur lors de la validation ou de l'enregistrement du mouvement : ".$e->getMessage());
            return response()->json([
                "code"=>"500",
                "message"=>["Erreur lors de la génération ou de l'enregistrement du mouvement : ".$e->getMessage()]
            ]);
        }
    }

      // générer bulk actes
      public function generateActeBulk(Request $request, ActeNaissanceService $service)
      {
          $codes = $request->codes;
          $user = Auth::user();

          $rn = $user->affectationActive()->registres()->where("code_type_registre","TPRG_0001")->where("statut",1)->first();



          if (!Gate::allows('module.acteNaissance.generate')) {
              return response()->json([
                  'code' => '181',
                  'message' => ['error' => 'Vous n\'êtes pas autorisé à générer un acte']
              ]);
          }

          if ($rn == null) {
              return response()->json([
                  'code' => '182',
                  'message' => ['error' => 'Aucun registre disponible']
              ]);
          }

          if ($rn->statut == 0) {
              return response()->json([
                  'code' => '183',
                  'message' => ['error' => 'Ce registre est déjà clôturé']
              ]);
          }

          $regResteplace = $rn->nombre_acte_prevu - $rn->nombre_acte_transcrit;

          if ($regResteplace == 0) {
              return response()->json([
                  'code' => '184',
                  'message' => ['error' => 'Registre plein.Veuillez ajouter des feuillets pour continuer !']
              ]);
          }

          $dn = Declarationnaissance::whereIn('code_declaration_naissance', $codes)->get();

          if ($dn->count() == 0) {
              return response()->json([
                  'code' => '180',
                  'message' => ['error' => 'Aucune déclaration à générer']
              ]);
          }

          // Limiter le nombre d'actes à générer si le registre n'a pas assez de place
          if ($regResteplace < count($codes)) {
              $dn = Declarationnaissance::whereIn('code_declaration_naissance', $codes)->take($regResteplace)->get();
          }

          DB::beginTransaction();
          try {
              $mouvementService = new MouvementService();
              foreach ($dn as $d) {
                  $service->genererActe($d, $rn, $user);
                  $mouvementService->ajouterEvenementActe($user, $d, 'attente_approbation');
                  // Notifier les officiers de l'état civil pour chaque acte généré
                  $codeInstitutionCentre = $user->affectationActive()->institution->code_institution;
                  $numeroActe = $d->acte ? $d->acte->niupp : null;
                  if($numeroActe) {

                      // Notification centralisée via le module Notification
                      NotificationService::notifierAgentsInstitution(
                      $codeInstitutionCentre,
                      new \Modules\Notification\Notifications\ActeAValiderNotification(
                          $numeroActe,
                          "Acte de naissance généré et en attente de la signature de l'officier d'état civil"
                      ));
                  }
              }
              DB::commit();
              return response()->json([
                  'code' => '200',
                  'message' => ['reponse' => 'Actes des naissances générés avec succès']
              ]);
          } catch (Exception $e) {
              DB::rollBack();
              Log::channel('sifec')->error($e->getMessage());
              return response()->json([
                  'code' => '201',
                  'message' => ['error' => $e->getMessage()]
              ]);
          }
      }


    public function naissanceApprouver($id)
    {

        $acte = ActeNaissance::find($id);

        if($acte==null){
            toastr()->error("Vous ne pouvez pas approuver cet acte de naissance");
            return back();
        }

        if ( Gate::allows("module.acteNaissance.signature")) {

           try{
                $acte->approbation_mairie = 1;
                $acte->signature_mairie =  Auth::user()->personne->signature;
                $acte->save();

                $otp = substr(time(),2);

                $temp = config("sifec.sms.templates.actions.acte_naissance");
                $temp = str_replace(":declarant",$acte->declaration->declarant->nomcomplet(),$temp);
                $temp = str_replace(":code_acte_naissance",$acte->niupp,$temp);
                toastr()->success("Acte approuvé avec succès");

                dispatch(new SendSmsJob($acte->declaration->declarant->telephone,$temp));

                return back();

            }catch(Exception $e){
                toastr()->error($e->getMessage());
                return back();
            }

        }
        // if ( Gate::allows("module.acteNaissance.sceau")) {

        //    try{
        //         $acte->approbation_tribunal = 1;
        //         $acte->sceau = $acte->institutionUser->institution->institutionParent->sceau;
        //         $acte->save();
        //         toastr()->success("Acte approuvé avec succès");
        //         return back();

        //     }catch(Exception $e){
        //         toastr()->error($e->getMessage());
        //         return back();
        //     }
        // }
        toastr()->error("Vous n'avez pas la permission d'approuver cet acte de naissance");
        return back();

    }

    public function searchActe()
    {

        $nom = request('nom') ?  "%".request('nom')."%"  : "";
        $prenom = request('prenom') ?  "%".request('prenom')."%" : "";
        $lieu = request('lieu') ? "%".request('lieu')."%":"";
        $personnes = DB::select("SELECT dn.code_declaration_naissance, ip.nom,ip.prenom,ip.lieu_naissance,ti.lib_institution,an.niupp FROM tr_identification_personne ip JOIN t_declaration_naissance dn ON ip.code_personne = dn.code_enfant JOIN t_acte_naissance an ON dn.code_declaration_naissance = an.code_declaration_naissance JOIN tr_ins_user iu ON an.cui = iu.cui JOIN tr_institution ti ON iu.code_institution = ti.code_institution WHERE ip.nom LIKE ? OR ip.prenom LIKE ? OR ip.lieu_naissance LIKE ?",[$nom,$prenom,$lieu]);

        return response()->json([
           "personnes" => $personnes
        ]);
    }

    public function displayDuplicata($id)
    {

        $acte = ActeNaissance::where("code_declaration_naissance",$id)->first();
        $dummy = "XXXXXXXXXXXXXXXX";

        if($acte == null){
            toastr()->error("Vous ne pouvez pas généré un acte de naissance");
            return back();
        }

        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('naissance::etats.duplicata', compact("acte","dummy"))->render());

        return $html2pdf->output($acte->code_acte_naissance.".pdf");
    }

    public function sendOtp(Request $request, OtpService $otpService)
    {
        $code = $request->code_declaration_naissance;
        $an = ActeNaissance::where("code_declaration_naissance", $code)->first();
        $user = Auth::user();
        if (!$an) {
            return response()->json([
                "code"=>"180",
                "message"=>"Aucun acte trouvé"
            ]);
        }

        try {
            $otpService->envoyerOtpValidationActes($user, [$an]);
            return response()->json([
                "code"=>"200",
                "message"=>"SMS envoyé avec succès"
            ]);
        } catch (Exception $e) {
            return response()->json([
                "code"=>"181",
                "message"=>["error" =>$e->getMessage()]
            ]);
        }
    }

    public function validateOtp(Request $request, OtpService $otpService)
    {
        $rules = [
            "otp_approbation_mairie" => ["required", "numeric"],
            "code_declaration_naissance" => ["required", "string"]
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                "code" => "180",
                "message" => "Aucun acte trouvé pour ce code"
            ]);
        }

        if (!Gate::allows("module.acteNaissance.signature")) {
            return response()->json([
                "code" => "181",
                "message" => ["error" => "Vous n'êtes pas autorisé à valider un acte de naissance"]
            ]);
        }

        $code = $request->code_declaration_naissance;
        $otp  = $request->otp_approbation_mairie;

        [$ok, $result] = $otpService->validerOtpActes(
            [$code], $otp,
            $request->ip(),
            $request->userAgent()
        );

        if (!$ok) {
            Log::channel("sifec")->info($result);
            return response()->json([
                "code" => "183",
                "message" => ["error" => $result]
            ]);
        }

        return response()->json([
            "code" => "200",
            "message" => "Acte de naissance validé avec succès"
        ]);
    }

    public function sendOtpBulk(Request $request, OtpService $otpService)
    {
        $codes = $request->codes;
        $an = ActeNaissance::whereIn("code_declaration_naissance", $codes)->get();
        $user = Auth::user();
        if ($an->count() == 0) {
            return response()->json([
                "code"=>"180",
                "message"=>"Aucun acte trouvé"
            ]);
        }
        try {
            $otpService->envoyerOtpValidationActes($user, $an);
            return response()->json([
                "code"=>"200",
                "message"=>"SMS envoyé avec succès"
            ]);
        } catch (Exception $e) {
            return response()->json([
                "code"=>"181",
                "message"=>["error" =>$e->getMessage()]
            ]);
        }
    }


    public function validateOtpBulk(Request $request, OtpService $otpService, MouvementService $mouvementService)
    {
        $codes = $request->codes;
        $otp = $request->otp_approbation_mairie;
        if(!Gate::allows("module.acteNaissance.signature")){
            return response()->json([
                "code"=>"181",
                "message"=>["error" =>"Vous n'êtes pas autorisé à valider un acte de naissance"]
            ]);
        }
        // Vérification contact déclarant pour chaque acte
        $actes = ActeNaissance::whereIn("code_declaration_naissance", $codes)->get();
        foreach($actes as $an) {
            $contactDeclarant = $an->declaration->declarant->contacts->first();
            if($contactDeclarant == null){
                return response()->json([
                    "code"=>"184",
                    "message"=>["Aucun contact trouvé pour le déclarant de l'acte ".$an->niupp]
                ]);
            }
        }
        DB::beginTransaction();
        try {
            [$ok, $result] = $otpService->validerOtpActes(
                $codes, $otp,
                $request->ip(),
                $request->userAgent()
            );
            if (!$ok) {
                DB::rollBack();
                Log::channel("sifec")->info($result);
                return response()->json([
                    "code"=>"183",
                    "message"=>[ $result]
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel("sifec")->info($e->getMessage());
            return response()->json([
                "code"=>"500",
                "message"=>["Erreur lors de la validation ou de l'enregistrement du mouvement : ".$e->getMessage()]
            ]);
        }

        return response()->json([
            "code"=>"200",
            "message"=>["Actes des naissances validés avec succès"]
        ]);
    }






    public function repertoire()
    {

        $dated = request('dated');
        $datef = request('datef');


        if ($dated == null && $datef == null) {
            $actes = DB::select("SELECT *
            FROM t_acte_naissance
            JOIN t_declaration_naissance ON t_declaration_naissance.code_declaration_naissance = t_acte_naissance.code_declaration_naissance
            JOIN tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_naissance.code_enfant

            WHERE t_acte_naissance.cui = '".Auth::user()->affectationActive()->cui."'
            ORDER BY tr_identification_personne.nom");
        }

        return view('naissance::acte.repertoire', compact('actes', 'dated', 'datef'));
    }



    public function repertoireAlphabetique(Request $request)
    {

        //
        $dated = $request->dated;
        $datef = $request->datef;
        $user = Auth::user();
        // if ($dated == null && $datef == null) {
        //     $actes = DB::select("SELECT *
        //     FROM t_acte_naissance
        //     JOIN t_declaration_naissance ON t_declaration_naissance.code_declaration_naissance = t_acte_naissance.code_declaration_naissance
        //     JOIN tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_naissance.code_enfant
        //     WHERE t_acte_naissance.cui = '".Auth::user()->affectationActive()->cui."'
        //     ORDER BY tr_identification_personne.nom");
        // }

        // if ($dated != null && $datef == null) {
        //     $actes = DB::select("SELECT *
        //     FROM t_acte_naissance
        //     JOIN t_declaration_naissance ON t_declaration_naissance.code_declaration_naissance = t_acte_naissance.code_declaration_naissance
        //     JOIN tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_naissance.code_enfant
        //     WHERE t_acte_naissance.cui = '".Auth::user()->affectationActive()->cui."'
        //     AND date(t_declaration_naissance.date_heure_naissance) BETWEEN '".$dated."' AND '".$dated."'
        //     ORDER BY tr_identification_personne.nom");
        // }

        // if ($dated == null && $datef != null) {
        //     $actes = DB::select("SELECT *
        //     FROM t_acte_naissance
        //     JOIN t_declaration_naissance ON t_declaration_naissance.code_declaration_naissance = t_acte_naissance.code_declaration_naissance
        //     JOIN tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_naissance.code_enfant
        //     WHERE t_acte_naissance.cui = '".Auth::user()->affectationActive()->cui."'
        //     AND date(t_declaration_naissance.date_heure_naissance) BETWEEN '".$datef."' AND '".$datef."'
        //     ORDER BY tr_identification_personne.nom");
        // }



        if ($dated != null && $datef != null) {
            $actes = DB::select("SELECT p.nom,p.prenom,p.sexe,p.date_naissance,an.niupp FROM tr_identification_personne p
                JOIN t_declaration_naissance dn ON dn.code_enfant = p.code_personne
                JOIN t_acte_naissance an ON an.code_declaration_naissance = dn.code_declaration_naissance
                JOIN tr_ins_user iuser ON an.cui=iuser.cui
                JOIN tr_institution ins ON iuser.code_institution = ins.code_institution
                WHERE date(an.date_emission) BETWEEN '".$dated."' AND '".$datef."' AND ins.code_institution = '".Auth::user()->affectationActive()->code_institution."'
                ORDER BY p.nom,p.prenom,p.sexe,p.date_naissance,an.niupp"
            );

        }
        // dd("impossible");
        // dd($request->all());
        if ($actes == null) {
            toastr()->warning('Aucune donnée trouvée');
            return back();
        }

        view()->share("tester", "Vincent");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('naissance::etats.repertoire', compact("actes","dated", "datef"))->render());

        return  $html2pdf->output("repertoireAlpha.pdf");

    }


    public function retraitActe(Request $request)
    {
        // Log::channel("sifec")->info($request->all());
        // dd($request->all());



        $retire_par = $request->nominteresse." ".$request->prenominteresse;
        $acte = ActeNaissance::findOrFail($request->niupp);
        $observations = trim($request->observations) ?? "Acte rétiré";

        DB::beginTransaction();
        try {
            $retrait = new RetraitActe;
            $retrait->code_retrait_acte = Sifec::genererCodeUniqueReferentiel($retrait, "code_retrait_acte", 8, "RET_");
            $retrait->code_acte = $request->niupp;
            $retrait->retirer_par = $retire_par;
            $retrait->telephone = $request->telephoneinteresse;
            $retrait->piece_identite = $request->piece_identite;
            $retrait->numero_piece_identite = $request->numero_piece_identite;
            $retrait->observations = $observations;
            $retrait->cui = Auth::user()->affectationActive()->cui;
            $retrait->save();

            $acte->retirer = 1;
            $acte->save();

            // Enregistrement du mouvement dans la transaction
            $declaration = $acte->declaration;
            $user = Auth::user();
            $mouvementService = new MouvementService();
            $result = $mouvementService->ajouterEvenementActe(
                $user,
                $declaration,
                'retiré',
                $observations
            );
            // Log::channel('sifec')->info('[retraitActe] Résultat ajout mouvement', ['result' => $result]);

            DB::commit();

            return response()->json([
                'code' => '200',
                'message' => ['reponse' => 'Le retrait de l\'acte de naissance enregistré avec succès']
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::channel("sifec")->error($e->getMessage());
            return response()->json([
                "code"=> "201",
                "message"=> ["error"=> $e->getMessage()]
            ]);
        }
    }

    public function displayExtrait($id)
    {

        $acte = ActeNaissance::where("code_declaration_naissance",$id)->first();
        $dummy = "XXXXXXXXXXXXXXXX";
        $numExtrait = substr(time(),2);

        if($acte == null){
            toastr()->error("Vous ne pouvez pas généré un extrait d'acte de naissance");
            return back();
        }

            view()->share("tester", "extrait");
            $html2pdf = new Html2Pdf('L', 'A5', 'fr');
            $html2pdf->setDefaultFont('Arial');

            //contrôle de contexte
            $code_institution = Auth::user()->affectationActive()->institution->code_institution;

            //contexte Congo
            $html2pdf->writeHTML(view('naissance::etats.extrait', compact("acte","dummy", "numExtrait"))->render());

            return $html2pdf->output($acte->code_acte_naissance.".pdf");
    }

    public function findActe(Request $request)
    {
        $acte = ActeNaissance::find($request->niupp);

        if($acte == null){
            return response()->json([
                "code" => "180",
                "message"=>"Aucun acte trouvé pour ce numéro"
            ]);
        }

        if($acte->deleted_at != null){
            return response()->json([
                "code" => "180",
                "message"=>"Cet acte a été annulé"
            ]);
        }

        $nom = $acte->declaration->enfant->nom;
        $prenom = $acte->declaration->enfant->prenom;
        $sexe = $acte->declaration->enfant->sexe=="M" ? "Masculin" : "Féminin";
        $dateNaissance = date("d-m-Y", strtotime($acte->declaration->date_heure_naissance));
        $lieuNaissance = $acte->declaration->enfant->lieu_naissance;
        $cec = $acte->institutionUser->institution->lib_institution;
        $cdn = $acte->code_declaration_naissance;
        $codeAdoptant = $acte->declaration->code_adoptant; //déjà adopter ou pas
        $button = "";
        if($codeAdoptant != ""){
            $button = "disabled"; //pour désactiver le lien de l'adoption
        }



        return response()->json([
            "code" => "200",
            "nomPrenom" => $nom." ".$prenom,
            "dateNaissance" => $dateNaissance,
            "sexe" => $sexe,
            "lieuNaissance" => $lieuNaissance,
            "cec" => $cec,
            "cdn" => $cdn,
            "statutEnfant" => $codeAdoptant == "" ? "Adopter" : "Enfant déjà adopté",
            "statutLien" => $button
        ]);
    }

    public function printActe($id)
    {

        $acte = ActeNaissance::where("code_declaration_naissance",$id)->orWhere("niupp",$id)->first();

        // Pas besoin de redirection ici, la vue gère le cas où $acte est null
        return view('naissance::acte.acte',compact("acte"));
    }
    public function printCopie($id)
    {
        $acte = ActeNaissance::where("code_declaration_naissance",$id)->first();
        return view('naissance::acte.copie',compact("acte"));
        // return view('naissance::acte.acte',compact("acte"));
    }
    public function printExtrait($id)
    {
        $acte = ActeNaissance::where("code_declaration_naissance",$id)->first();
        return view('naissance::acte.extrait',compact("acte"));
    }


    /**
     * Valide la suppression d'un acte de naissance
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validerAnnulation(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $acte = ActeNaissance::find($id);
            if ($acte == null) {
                toastr()->error("Acte de naissance indisponible");
                return back();
            }

            $declaration = DeclarationNaissance::where("code_declaration_naissance",$acte->code_declaration_naissance)->first();
            if ($declaration == null) {
                toastr()->error("Déclaration indisponible");
                return back();
            }

            $declaration->code_jugement = $request->code_jugement;
            $declaration->save();

            $acte->deleted_at = Carbon::now();
            $acte->motif_annulation = $request->motif;
            $acte->statut = 1;
            $acte->save();

            if ($acte && $declaration) {
                $user = Auth::user();
                $mouvementService = new MouvementService();
                $mouvementService->envoyerDeclaration($user, $declaration, 'MOUV_0014', "annulé");
            }

            toastr()->success("Acte annulé");
            DB::commit();
            return redirect()->route('acteNaissance.index');
        } catch (Exception $e) {
            DB::rollBack();
            toastr()->error($e->getMessage());
            return back();
        }
    }

    // public function rectification()
    // {
    //     return view('naissance::acte.rectification');
    // }

    public function rectificationacte()
    {

        $id = request('id');

        try {

            $acte = ActeNaissance::find($id);
            if ($acte == null) {
                toastr()->error("Acte de naissance indisponible");
                return back();
            }

            if ($acte->deleted_at != null) {
                toastr()->warning("Acte déjà annuler");
                return back();
            }

            $created = new Carbon($acte->created_at);
            // $now = Carbon::now();
            // $difference = ($created->diff($now)->days < 1)
            //     ? 'today'
            //     : $created->diffForHumans($now);
            $DeferenceInDays = Carbon::parse(Carbon::now())->diffInMonths($created);

            $tgis = Institution::where("code_type_institution","TPINS_0001")->get();
            return view('naissance::acte.acterectification', compact('acte','tgis','DeferenceInDays'));
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back();
        }
    }

    /**
     * Annuler un acte de naissance
     */
    public function annulerActe(Request $request, MouvementService $mouvementService)
    {

        try {
            DB::beginTransaction();
            $declaration = Declarationnaissance::findOrFail($request->code_declaration_naissance);
            $acte = $declaration->acte;

            if (!$acte) {
                return response()->json([
                    'code' => '404',
                    'message' => 'Aucun acte trouvé pour cette déclaration'
                ]);
            }

            if ($acte->deleted_at) {
                return response()->json([
                    'code' => '400',
                    'message' => 'Cet acte a déjà été annulé'
                ]);
            }

            // Annuler l'acte
            $acte->deleted_at = Carbon::now();
            $acte->motif_annulation = $request->motif;
            $acte->statut = 1;
            $acte->save();

            // Créer un mouvement d'annulation
            if ($acte && $declaration) {
                $user = Auth::user();
                $mouvementService->envoyerDeclaration($user, $declaration, 'MOUV_0014', "annulé");
            }

            DB::commit();

            return response()->json([
                'code' => '200',
                'message' => 'Acte annulé avec succès'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('Erreur annulation acte : ' . $e->getMessage());
            return response()->json([
                'code' => '500',
                'message' => 'Erreur lors de l\'annulation de l\'acte: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Confirmer un dossier individuel (acte)
     */
    public function confirmerDossier(Request $request, MouvementService $mouvementService)
    {
        try {
            DB::beginTransaction();
            $declaration = Declarationnaissance::findOrFail($request->code_declaration_naissance);
            $user = Auth::user();
            $affectation = $user->affectationActive();
            $observation = $request->observation;
            $statut= "Confirmée";

            [$ok, $result] = $mouvementService->confirmerDeclarationNaissance(
                $affectation,
                $declaration,
                $statut,
                $observation
            );

            if (!$ok) {
                DB::rollBack();
                throw new Exception($result ?: "Erreur lors de la confirmation du dossier");
            }

            DB::commit();
            return response()->json([
                'code' => '200',
                'message' => ['Dossier confirmé avec succès']
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('Erreur confirmation dossier : ' . $e->getMessage());
            return response()->json([
                'code' => '500',
                'message' => ["Erreur lors de la confirmation du dossier: " . $e->getMessage()]
            ]);
        }
    }

    /**
     * Confirmer plusieurs dossiers en bulk (actes)
     */
    public function confirmerDossiersBulk(Request $request, MouvementService $mouvementService)
    {
        try {
            $codes = $request->codes;
            $observation = $request->observation;
            $user = Auth::user();
            $affectation = $user->affectationActive();
            $statut= "Confirmée";

            $declarations = Declarationnaissance::whereIn('code_declaration_naissance', $codes)->get();

            if ($declarations->count() === 0) {
                return response()->json([
                    'code' => '400',
                    'message' => ['Aucun dossier à confirmer']
                ]);
            }

            $confirmes = 0;
            $erreurs = [];

            foreach ($declarations as $declaration) {
                [$ok, $result] = $mouvementService->confirmerDeclarationNaissance(
                    $affectation,
                    $declaration,
                    $statut,
                    $observation
                );
                if ($ok) {
                    $confirmes++;
                } else {
                    $erreurs[] = $declaration->code_declaration_naissance . ' : ' . $result;
                }
            }

            if ($confirmes === 0) {
                return response()->json([
                    'code' => '400',
                    'message' => ["Aucun dossier n'a pu être confirmé", ...$erreurs]
                ]);
            }

            $msg = [$confirmes . ' dossier(s) confirmé(s) avec succès'];
            if (count($erreurs)) {
                $msg[] = 'Erreurs sur certains dossiers : ' . implode(' | ', $erreurs);
            }

            return response()->json([
                'code' => '200',
                'message' => $msg
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => '500',
                'message' => ["Erreur lors de la confirmation des dossiers: " . $e->getMessage()]
            ]);
        }
    }

        /**
     * Renvoyer un dossier individuel (acte)
     */
    public function renvoyerDossier(Request $request, MouvementService $mouvementService)
    {
        try {
            DB::beginTransaction();
            $declaration = Declarationnaissance::findOrFail($request->code_declaration_naissance);
            $user = Auth::user();
            $affectation = $user->affectationActive();
            $motif = $request->motif_renvoi;
            $observation = $request->observation;

            [$ok, $result] = $mouvementService->renvoyerDeclarationNaissance(
                $affectation,
                $declaration,
                $motif,
                $observation
            );

            if (!$ok) {
                DB::rollBack();
                throw new Exception($result ?: "Erreur lors du renvoi du dossier");
            }

              // Notification centralisée via le module Notification (après commit)
              NotificationService::notifierAgentsInstitution(
                $declaration->institution,
                new \Modules\Notification\Notifications\DeclarationEnvoyeeCentreNotification(
                    $declaration,
                    $declaration->institution,
                    'renvoyée',
                     $observation ?? 'Une déclaration de naissance a été renvoyée à votre institution.',
                ),
                "FONC_0006"
            );

            DB::commit();

            // // Notification centralisée via le module Notification (après commit)
            // NotificationService::notifierAgentsInstitution(
            //     $declaration->institution,
            //     new \Modules\Notification\Notifications\DeclarationEnvoyeeCentreNotification(
            //         $declaration,
            //         $declaration->institution,
            //         'renvoyée',
            //          $observation ?? 'Une déclaration de naissance a été renvoyée à votre institution.',

            //     ),
            //     "FONC_0006"
            // );

            return response()->json([
                'code' => '200',
                'message' => ['Dossier renvoyé avec succès']
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('Erreur renvoi dossier : ' . $e->getMessage());
            return response()->json([
                'code' => '500',
                'message' => ["Erreur lors du renvoi du dossier: " . $e->getMessage()]
            ]);
        }

    }

    /**
     * Renvoyer plusieurs dossiers en bulk (actes)
     */
    public function renvoyerDossiersBulk(Request $request, MouvementService $mouvementService)
    {
        try {
            $codes = $request->codes;
            $motif = $request->motif_renvoi;
            $observation = $request->observation;
            $user = Auth::user();
            $affectation = $user->affectationActive();

            $declarations = Declarationnaissance::whereIn('code_declaration_naissance', $codes)->get();

            if ($declarations->count() === 0) {
                return response()->json([
                    'code' => '400',
                    'message' => ['Aucun dossier à renvoyer']
                ]);
            }

            $renvoyes = 0;
            $erreurs = [];

            foreach ($declarations as $declaration) {
                [$ok, $result] = $mouvementService->renvoyerDeclarationNaissance(
                    $affectation,
                    $declaration,
                    $motif,
                    $observation
                );
                if ($ok) {
                    $renvoyes++;

                     // Notification centralisée via le module Notification
                    NotificationService::notifierAgentsInstitution(
                        $declaration->institution,
                        new \Modules\Notification\Notifications\DeclarationEnvoyeeCentreNotification(
                            $declaration,
                            $declaration->institution,
                            'renvoyée',
                            $motif,
                            $observation
                        )
                    );
                } else {
                    $erreurs[] = $declaration->code_declaration_naissance . ' : ' . $result;
                }
            }

            if ($renvoyes === 0) {
                return response()->json([
                    'code' => '400',
                    'message' => ["Aucun dossier n'a pu être renvoyé", ...$erreurs]
                ]);
            }

            $msg = [$renvoyes . ' dossier(s) renvoyé(s) avec succès'];
            if (count($erreurs)) {
                $msg[] = 'Erreurs sur certains dossiers : ' . implode(' | ', $erreurs);
            }

            return response()->json([
                'code' => '200',
                'message' => $msg
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => '500',
                'message' => ["Erreur lors du renvoi des dossiers: " . $e->getMessage()]
            ]);
        }
    }

    public function annulerActesBulk(Request $request)
    {
        try {
            $codes = $request->codes;
            $motif = $request->motif;
            $observation = $request->observation;
            $user = Auth::user();
            $affectation = $user->affectationActive();

            $declarations = Declarationnaissance::whereIn('code_declaration_naissance', $codes)
                ->whereHas('acte', function($query) {
                    $query->whereNull('deleted_at');
                })
                ->get();

            if ($declarations->count() !== count($codes)) {
                return response()->json([
                    'code' => '400',
                    'message' => 'Certains actes ne peuvent pas être annulés'
                ]);
            }

            DB::beginTransaction();

            foreach ($declarations as $declaration) {
                $acte = $declaration->acte;
                if ($acte && !$acte->deleted_at) {
                    $acte->motif_annulation = $motif;
                    $acte->observation_annulation = $observation;
                    $acte->deleted_at = now();
                    $acte->save();

                    // Historique mouvement
                    $mouvement = new MouvementNaissance();
                    $mouvement->code_mouvement_naissance = Sifec::genererCodeUniqueReferentiel($mouvement, "code_mouvement_naissance", 8, "MOUV_");
                    $mouvement->code_declaration_naissance = $declaration->code_declaration_naissance;
                    $mouvement->motif_renvoi = 'Annulation d\'acte: ' . $motif;
                    $mouvement->observation = $observation;
                    $mouvement->cui = $affectation->cui;
                    $mouvement->save();
                }
            }

            DB::commit();

            return response()->json([
                'code' => '200',
                'message' => 'Actes annulés avec succès'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'code' => '500',
                'message' => 'Erreur lors de l\'annulation des actes: ' . $e->getMessage()
            ]);
        }
    }
}
