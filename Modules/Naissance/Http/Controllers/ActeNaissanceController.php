<?php

namespace Modules\Naissance\Http\Controllers;

use App\Sifec\Sifec;
use App\Sifec\SifecFacade;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Mariage\Entities\DeclarationMariage;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Naissance\Entities\Declarationnaissance;
use Modules\Naissance\Entities\MouvementNaissance;
use Modules\Naissance\Exceptions\ActeNaissanceOtpLockedException;
use Modules\Naissance\Http\Requests\GenerateActeRequest;
use Modules\Naissance\Services\ActeNaissanceAnnulationSignatureService;
use Modules\Naissance\Services\ActeNaissanceGuotValidationService;
use Modules\Naissance\Services\ActeNaissanceService;
use Modules\Naissance\Services\ActeNaissanceSignatureFinalizer;
use Modules\Naissance\Services\CecNaissanceActeDashboardService;
use Modules\Naissance\Services\MouvementService;
use Modules\Naissance\Services\OtpService;
use Modules\Notification\Jobs\DeclarantActeDisponibleInformationJob;
use Modules\Notification\Notifications\ActeAValiderNotification;
use Modules\Notification\Notifications\DeclarationEnvoyeeCentreNotification;
use Modules\Notification\Services\NotificationService;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\Registre;
use Modules\Referentiel\Entities\RetraitActe;
use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Component\HttpFoundation\Response;

class ActeNaissanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $affectation = $user->affectationActive();
        $institution = $affectation->institution;

        $dashboard = app(CecNaissanceActeDashboardService::class);

        // Aperçu : 20 lignes max au chargement initial
        $documentsAControler = $dashboard->documentsAControlerPreview($institution, 20);
        $actesGestion = $dashboard->actesGestionPreview($institution, 20);

        $registre = $this->resolveRegistreNaissanceDisponible($affectation);

        // Liste fixe des types autorisés (filtres) — pas de distinct() sur toute la table
        $typesDeclaration = collect(Declarationnaissance::TYPES_AUTORISES)->sort()->values();

        return view(
            'naissance::acte.index', compact(
                'documentsAControler',
                'actesGestion',
                'registre',
                'typesDeclaration'
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
                ],
            ]);

            // Recherche : dossiers du CEC (créés par ou reçus par l'institution) — filtres en SQL
            $query = Declarationnaissance::with([
                'enfant', 'declarant', 'pere', 'mere', 'mouvements', 'acte', 'requisition', 'jugement',
            ])
                ->where(function ($q) use ($institution) {
                    $q->where('code_institution_destinataire', $institution->code_institution)
                        ->orWhere('code_institution', $institution->code_institution);
                });

            $countInitial = (clone $query)->count();

            if ($request->filled('numero_declaration') && strlen(trim((string) $request->numero_declaration)) > 0) {
                $search = trim((string) $request->numero_declaration);
                $like = '%'.addcslashes($search, '%_\\').'%';
                $query->where('code_declaration_naissance', 'like', $like);
            }

            if ($request->filled('type_declaration') && strlen(trim((string) $request->type_declaration)) > 0) {
                $query->where('type_declaration', $request->type_declaration);
            }

            if ($request->filled('date_debut')) {
                $query->whereRaw(
                    'COALESCE(DATE(date_heure_declaration), DATE(created_at)) >= ?',
                    [$request->date_debut]
                );
            }
            if ($request->filled('date_fin')) {
                $query->whereRaw(
                    'COALESCE(DATE(date_heure_declaration), DATE(created_at)) <= ?',
                    [$request->date_fin]
                );
            }

            if ($request->filled('sexe')) {
                $query->whereHas('enfant', fn ($q) => $q->where('sexe', $request->sexe));
            }

            if ($request->filled('statut')) {
                $statut = $request->statut;
                if ($statut === 'dossier_recu') {
                    $query->whereHas('mouvements', fn ($q) => $q->whereIn('code_mouvement', [
                        'MOUV_0001', 'MOUV_0035', 'MOUV_0011', 'MOUV_0024', 'MOUV_0033',
                    ]));
                } elseif ($statut === 'confirme') {
                    $query->whereHas('mouvements', fn ($q) => $q->where('code_mouvement', 'MOUV_0019'));
                } elseif ($statut === 'en_attente') {
                    $query->whereHas('mouvements', fn ($q) => $q->where('code_mouvement', 'MOUV_0004'));
                }
            }

            $hasFilters = $request->filled('numero_declaration')
                || $request->filled('type_declaration')
                || $request->filled('date_debut')
                || $request->filled('date_fin')
                || $request->filled('sexe')
                || $request->filled('statut');

            // Sans filtre: aperçu limité à 20. Avec filtres: autoriser un volume plus large.
            $maxResults = $hasFilters ? 500 : 20;
            $countResultat = (clone $query)->count();
            $documents = (clone $query)
                ->orderByDesc('date_heure_declaration')
                ->limit($maxResults)
                ->get()
                ->values();

            if ($countResultat > $maxResults) {
                Log::channel('sifec')->warning('=== RECHERCHE DOCUMENTS - LIMITE ATTEINTE ===', [
                    'user_id' => $user->code_user ?? null,
                    'count_total' => $countResultat,
                    'count_affiché' => $maxResults,
                    'message' => "Plus de {$maxResults} résultats trouvés. Affinez vos critères de recherche pour voir tous les résultats.",
                ]);
            }

            // Logger les résultats de la recherche
            Log::channel('sifec')->info('=== RÉSULTATS RECHERCHE DOCUMENTS À CONTRÔLER ===', [
                'user_id' => $user->code_user ?? null,
                'institution' => $institution->code_institution ?? null,
                'count_initial' => $countInitial,
                'count_resultat' => $countResultat,
                'count_affiché' => $documents->count(),
                'filtres_appliques' => $request->only(['numero_declaration', 'date_debut', 'date_fin', 'sexe', 'type_declaration', 'statut']),
            ]);

            return response()->json([
                'code' => '200',
                'data' => view('naissance::acte.partials.table-documents', compact('documents'))->render(),
                'count' => $countResultat,
                'count_affiché' => $documents->count(),
                'limite_atteinte' => $countResultat > $maxResults,
            ]);
        } catch (Exception $e) {
            Log::channel('sifec')->error('=== ERREUR RECHERCHE DOCUMENTS À CONTRÔLER ===', [
                'user_id' => Auth::user()->code_user ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'criteres' => $request->only(['numero_declaration', 'date_debut', 'date_fin', 'sexe', 'type_declaration', 'statut']),
            ]);

            return response()->json([
                'code' => '500',
                'message' => 'Erreur lors de la recherche des documents',
                'error' => $e->getMessage(),
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
                ],
            ]);

            // Utiliser la même méthode que getActesGestion pour garantir la cohérence
            $actesGestion = $institution->getActesGestion('naissance');
            $countInitial = $actesGestion->count();

            // Filtre par numéro de déclaration
            if ($request->filled('numero_declaration')) {
                $actesGestion = $actesGestion->filter(function ($acte) use ($request) {
                    return stripos($acte->code_declaration_naissance, $request->numero_declaration) !== false;
                });
            }

            // Filtre par NIUPP (numéro d'acte)
            if ($request->filled('niupp')) {
                $actesGestion = $actesGestion->filter(function ($acte) use ($request) {
                    return $acte->acte && stripos($acte->acte->niupp, $request->niupp) !== false;
                });
            }

            // Filtre par période (date de déclaration de naissance)
            if ($request->filled('date_debut')) {
                $actesGestion = $actesGestion->filter(function ($acte) use ($request) {
                    // Utiliser date_heure_declaration (date de déclaration) pour filtrer par date de création du document
                    $dateDeclaration = $acte->date_heure_declaration ?? $acte->created_at;
                    if ($dateDeclaration) {
                        return date('Y-m-d', strtotime($dateDeclaration)) >= $request->date_debut;
                    }

                    return false;
                });
            }
            if ($request->filled('date_fin')) {
                $actesGestion = $actesGestion->filter(function ($acte) use ($request) {
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
                $actesGestion = $actesGestion->filter(function ($acte) use ($request) {
                    return $acte->enfant && $acte->enfant->sexe === $request->sexe;
                });
            }

            // Filtre par statut
            if ($request->filled('statut')) {
                $statut = $request->statut;
                $actesGestion = $actesGestion->filter(function ($acte) use ($statut) {
                    $codesMouvements = $acte->mouvements ? $acte->mouvements->pluck('code_mouvement')->toArray() : [];
                    $dernierMouvement = $acte->mouvements ? $acte->mouvements->sortByDesc('created_at')->first() : null;

                    if ($statut === 'en_attente_generation') {
                        return ! $acte->acte;
                    } elseif ($statut === 'en_attente_validation') {
                        return $acte->acte && (! $acte->acte->approbation_mairie || $acte->acte->approbation_mairie === '');
                    } elseif ($statut === 'valide_non_retire') {
                        return $acte->acte && $acte->acte->approbation_mairie &&
                               (! $dernierMouvement || ($dernierMouvement->code_mouvement !== 'MOUV_0016' && $dernierMouvement->code_mouvement !== 'MOUV_0017'));
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
                    'message' => "Plus de {$maxResults} résultats trouvés. Affinez vos critères de recherche pour voir tous les résultats.",
                ]);
            }

            // Logger les résultats de la recherche
            Log::channel('sifec')->info('=== RÉSULTATS RECHERCHE ACTES À GÉRER ===', [
                'user_id' => $user->code_user ?? null,
                'institution' => $institution->code_institution ?? null,
                'count_initial' => $countInitial,
                'count_resultat' => $countResultat,
                'count_affiché' => $actes->count(),
                'filtres_appliques' => $request->only(['numero_declaration', 'niupp', 'date_debut', 'date_fin', 'sexe', 'statut']),
            ]);

            return response()->json([
                'code' => '200',
                'data' => view('naissance::acte.partials.table-actes', compact('actes'))->render(),
                'count' => $countResultat,
                'count_affiché' => $actes->count(),
                'limite_atteinte' => $countResultat > $maxResults,
            ]);
        } catch (Exception $e) {
            Log::channel('sifec')->error('=== ERREUR RECHERCHE ACTES À GÉRER ===', [
                'user_id' => Auth::user()->code_user ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'criteres' => $request->only(['numero_declaration', 'niupp', 'date_debut', 'date_fin', 'sexe', 'statut']),
            ]);

            return response()->json([
                'code' => '500',
                'message' => 'Erreur lors de la recherche des actes',
                'error' => $e->getMessage(),
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
                'declaration.jugement.institution',
                'declaration.institutionUser.institution.institutionParent',
                'declaration.institutionUser.institution.lieu.localiteParent',
                'declaration.requisition.typeRequisition',
                'declaration.requisition.institution',
                'declaration.requisitionParCode.institution',
                'declaration.jugementParCode.institution',
                'declaration.institution.institutionParent',
                'institutionUser.institution',
                'institutionUser.institution.institutionParent.lieu.localiteParent',
                'registre',
                'signataire.user.personne',
            ])->where(function ($q) use ($id) {
                $q->where('code_declaration_naissance', $id)
                    ->orWhere('niupp', $id)
                    ->orWhere('code_acte_naissance', $id);
            })->first();

            if ($acte == null) {
                Log::channel('sifec')->warning('[ActeNaissance][PDF] Acte introuvable', [
                    'identifiant_url' => $id,
                ]);

                return $this->actePdfStreamErrorResponse(
                    'Acte introuvable.',
                    404
                );
            }

            // Vérifier que la déclaration existe
            if (! $acte->declaration) {
                Log::channel('sifec')->error('[ActeNaissance][PDF] Déclaration manquante', [
                    'code_acte_naissance' => $acte->code_acte_naissance,
                    'identifiant_url' => $id,
                ]);
                throw new Exception("Données incomplètes pour générer l'acte. Déclaration manquante.");
            }

            // Aperçu avant signature : niupp NULL autorisé (cf. migration t_acte_naissance, niupp nullable + unique).
            if (! $acte->niupp) {
                Log::channel('sifec')->info('[ActeNaissance][PDF] Aperçu acte sans NIUPP (en attente signature officier)', [
                    'code_declaration_naissance' => $acte->code_declaration_naissance,
                    'code_acte_naissance' => $acte->code_acte_naissance,
                    'cui' => $acte->cui,
                ]);
            }

            $dummy = 'XXXXXXXXXXXXXXXX';

            // Recherche de l'acte annulé (si existe)
            $acteannuler = Declarationnaissance::where('numero_ancien_acte', $acte->niupp)->first();

            // Recherche de déclaration de décès (si existe, et cohérente avec la date de naissance)
            $declarationDeces = DeclarationDeces::pourMentionActeNaissance(
                $acte->niupp,
                optional($acte->declaration)->date_heure_naissance
            );

            // Recherche de mariage (si existe)
            $mariage = null;
            $mariageEpoux = DeclarationMariage::where('numero_acte_naissance_epoux', $acte->niupp)->first();
            if ($mariageEpoux) {
                $mariage = $mariageEpoux;
            } else {
                $mariageEpouse = DeclarationMariage::where('numero_acte_naissance_epouse', $acte->niupp)->first();
                if ($mariageEpouse) {
                    $mariage = $mariageEpouse;
                }
            }

            // Compter les mentions réellement affichées dans la marge (etats/acte.blade.php) : pas d’incrément pour un jugement sans bloc marge ni pour acte annulé (non rendu dans la marge).
            $nombreMentions = 0;
            if ($mariage != null) {
                $nombreMentions++;
            }
            if ($declarationDeces != null) {
                $nombreMentions++;
            }
            $jugement = $acte->declaration->jugement;
            if ($jugement !== null) {
                if (filled($acte->declaration->code_adoptant) || filled(optional($acte->declaration->adoptant)->code_personne)) {
                    $nombreMentions++;
                }
                $typeJg = (string) ($jugement->type_jugement ?? '');
                if (in_array($typeJg, ['JUGEMENT SUPPLETIF', "JUGEMENT D'HOMOLOGATION"], true)) {
                    $nombreMentions++;
                }
            }
            // Charger les rectifications si nécessaire pour le comptage
            if (! $acte->relationLoaded('rectifications')) {
                $acte->load('rectifications');
            }
            if ($acte->rectifications && $acte->rectifications->count() > 0) {
                $nombreMentions += $acte->rectifications->count();
            }

            view()->share('tester', 'Alange');
            // Marges [L,T,R,B] mm — bas légèrement réduit pour gagner de la hauteur utile sur A4.
            $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', [5, 5, 5, 5]);
            $html2pdf->setDefaultFont('Arial');
            // Acte : une grande TD (colonne texte) contient tout le corps ; le test « TD sur une page » provoque souvent un saut page 2 inutile (cf. exemple 11 Html2Pdf).
            $html2pdf->setTestTdInOnePage(false);

            $verificationUrl = $acte->niupp
                ? URL::signedRoute('verification.acte', ['niupp' => $acte->niupp])
                : '';
            $qrCode = $verificationUrl;

            // Rendre la vue avec gestion d'erreur
            $htmlContent = view('naissance::etats.acte', compact('acte', 'dummy', 'acteannuler', 'declarationDeces', 'mariage', 'qrCode', 'nombreMentions'))->render();

            if (empty($htmlContent)) {
                throw new Exception("Le contenu HTML de l'acte est vide.");
            }

            $html2pdf->writeHTML($htmlContent);

            $pdfBinary = $html2pdf->output($acte->code_acte_naissance.'.pdf');

            return $this->pdfInlineResponse($pdfBinary, $acte->code_acte_naissance.'.pdf');

        } catch (Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            Log::channel('sifec')->error('[ActeNaissance][PDF] Échec génération', [
                'identifiant_url' => $id,
                'message' => $e->getMessage(),
                'exception' => $e::class,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Si c'est une requête AJAX ou PDF, renvoyer une réponse JSON ou une erreur HTTP
            if (request()->expectsJson() || request()->wantsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Erreur lors de la génération du PDF: '.$e->getMessage(),
                ], 500);
            }

            return $this->actePdfStreamErrorResponse(
                'Erreur lors de la génération du PDF : '.$e->getMessage(),
                500
            );
        }
    }

    /**
     * Réponse binaire PDF avec en-têtes corrects (évite « Invalid PDF structure » côté PDF.js).
     */
    private function pdfInlineResponse(string $pdfBinary, string $filename): Response
    {
        $safeName = preg_replace('/[^a-zA-Z0-9._\-]/', '_', $filename) ?: 'acte.pdf';
        $safeName = trim($safeName) !== '' ? trim($safeName) : 'acte.pdf';

        return response($pdfBinary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$safeName.'"',
            'Cache-Control' => 'private, must-revalidate',
        ]);
    }

    /**
     * Erreur pour la route /generate consommée par PDF.js (pas de redirect HTML).
     */
    private function actePdfStreamErrorResponse(string $message, int $status): Response
    {
        return response($message, $status)->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function displayCopie($id)
    {
        try {
            $acte = ActeNaissance::with(Declarationnaissance::eagerLoadDeclarationTribunalMentionDepuisActeNaissance())
                ->where('code_declaration_naissance', $id)
                ->first();
            $dummy = 'XXXXXXXXXXXXXXXX';

            if ($acte == null) {
                Log::channel('sifec')->warning('[ActeNaissance][PDF][Copie] Acte introuvable', [
                    'code_declaration_naissance' => $id,
                ]);
                flash()->error('Vous ne pouvez pas généré un acte de naissance');

                return back();
            }

            if (! $acte->niupp) {
                Log::channel('sifec')->warning('[ActeNaissance][PDF][Copie] Copie demandée sans NIUPP (signature officier requise)', [
                    'code_declaration_naissance' => $acte->code_declaration_naissance,
                    'code_acte_naissance' => $acte->code_acte_naissance,
                ]);
                flash()->error('La copie d’acte n’est disponible qu’après signature par l’officier d’état civil.');

                return back();
            }

            $declarationDeces = DeclarationDeces::pourMentionActeNaissance(
                $acte->niupp,
                optional($acte->declaration)->date_heure_naissance
            );

            $mariage = null;
            if (DeclarationMariage::where('numero_acte_naissance_epoux', $acte->niupp)->first() != null) {
                $mariage = DeclarationMariage::where('numero_acte_naissance_epoux', $acte->niupp)->first();
            }
            if (DeclarationMariage::where('numero_acte_naissance_epouse', $acte->niupp)->first() != null) {
                $mariage = DeclarationMariage::where('numero_acte_naissance_epouse', $acte->niupp)->first();
            }

            view()->share('tester', [], 'Alange');
            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->setDefaultFont('Arial');
            // Copie hors demande : pas de QR de délivrance (signature = officier en fonction via Demande document)
            $html2pdf->writeHTML(view('naissance::etats.copieActeNaissance', compact('acte', 'dummy', 'declarationDeces', 'mariage'))->render());

            $pdfBinary = $html2pdf->output($acte->code_acte_naissance.'.pdf');

            return $this->pdfInlineResponse($pdfBinary, $acte->code_acte_naissance.'.pdf');
        } catch (Exception $e) {
            Log::channel('sifec')->error('[ActeNaissance][PDF][Copie] Échec génération', [
                'identifiant_url' => $id,
                'message' => $e->getMessage(),
                'exception' => $e::class,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            flash()->error("Une erreur est survenue lors de la génération de la copie d'acte de naissance: ".$e->getMessage());

            return back();
        }
    }

    // generation de l'acte single
    public function generateActe(GenerateActeRequest $request, ActeNaissanceService $service, MouvementService $mouvementService)
    {
        $user = Auth::user();
        $affectation = $user->affectationActive();
        $declaration = Declarationnaissance::findOrFail($request->code_declaration_naissance);
        $registre = $this->resolveRegistreNaissanceDisponible($affectation);

        if (! Gate::allows('module.acteNaissance.generate')) {
            return response()->json([
                'code' => '403',
                'message' => "Vous n'êtes pas autorisé à générer un acte",
            ], 403);
        }

        if ($registre === null) {
            return response()->json([
                'code' => '400',
                'message' => $this->messageRegistreNaissanceIndisponible($affectation),
            ], 400);
        }

        if (! filled($declaration->sig_cec_proof_id)) {
            return response()->json([
                'code' => '400',
                'message' => "La déclaration de naissance doit être signée électroniquement par un responsable du centre d'état civil avant la génération de l'acte.",
            ], 400);
        }

        DB::beginTransaction();
        try {
            $service->genererActe($declaration, $registre, $user);
            $mouvementService->ajouterEvenementActe($user, $declaration, 'attente_approbation');
            // Lien vers l’acte : code déclaration (le NIUPP n’existe qu’après signature)
            $codeInstitutionCentre = $affectation->institution->code_institution;
            NotificationService::notifierAgentsInstitution(
                $codeInstitutionCentre,
                new ActeAValiderNotification(
                    $declaration->code_declaration_naissance,
                    "Acte de naissance généré et en attente de la signature de l'officier d'état civil"
                )
            );

            DB::commit();

            return response()->json([
                'code' => '200',
                'message' => ['Acte naissance généré avec succès'],
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->info("Erreur lors de la validation ou de l'enregistrement du mouvement : ".$e->getMessage());

            return response()->json([
                'code' => '500',
                'message' => ["Erreur lors de la génération ou de l'enregistrement du mouvement : ".$e->getMessage()],
            ]);
        }
    }

    // générer bulk actes
    public function generateActeBulk(Request $request, ActeNaissanceService $service)
    {
        $codes = $request->codes;
        $user = Auth::user();
        $affectation = $user->affectationActive();
        $rn = $this->resolveRegistreNaissanceDisponible($affectation);

        if (! Gate::allows('module.acteNaissance.generate')) {
            return response()->json([
                'code' => '181',
                'message' => ['error' => 'Vous n\'êtes pas autorisé à générer un acte'],
            ]);
        }

        if ($rn === null) {
            return response()->json([
                'code' => '182',
                'message' => ['error' => $this->messageRegistreNaissanceIndisponible($affectation)],
            ]);
        }

        $regResteplace = $rn->nombre_acte_prevu - $rn->nombre_acte_transcrit;

        if ($regResteplace == 0) {
            return response()->json([
                'code' => '184',
                'message' => ['error' => 'Registre plein.Veuillez ajouter des feuillets pour continuer !'],
            ]);
        }

        $dn = Declarationnaissance::whereIn('code_declaration_naissance', $codes)->get();

        if ($dn->count() == 0) {
            return response()->json([
                'code' => '180',
                'message' => ['error' => 'Aucune déclaration à générer'],
            ]);
        }

        // Prérequis : déclaration signée électroniquement par le CEC.
        $nbNonSignees = $dn->filter(fn ($d) => ! filled($d->sig_cec_proof_id))->count();
        if ($nbNonSignees > 0) {
            $dn = $dn->filter(fn ($d) => filled($d->sig_cec_proof_id))->values();
        }

        if ($dn->count() === 0) {
            return response()->json([
                'code' => '400',
                'message' => ['error' => "Aucune déclaration signée : la signature électronique du centre d'état civil est requise avant la génération de l'acte."],
            ]);
        }

        // Limiter le nombre d'actes à générer si le registre n'a pas assez de place
        $nbReportesRegistre = 0;
        if ($regResteplace < $dn->count()) {
            $nbReportesRegistre = $dn->count() - $regResteplace;
            $dn = $dn->take($regResteplace);
        }

        DB::beginTransaction();
        try {
            $mouvementService = new MouvementService;
            foreach ($dn as $d) {
                $service->genererActe($d, $rn, $user);
                $mouvementService->ajouterEvenementActe($user, $d, 'attente_approbation');
                $codeInstitutionCentre = $user->affectationActive()->institution->code_institution;
                NotificationService::notifierAgentsInstitution(
                    $codeInstitutionCentre,
                    new ActeAValiderNotification(
                        $d->code_declaration_naissance,
                        "Acte de naissance généré et en attente de la signature de l'officier d'état civil"
                    )
                );
            }
            DB::commit();

            $nbGeneres = $dn->count();
            $message = $nbGeneres.' acte(s) de naissance généré(s) avec succès.';
            $avertissements = [];
            if ($nbNonSignees > 0) {
                $avertissements[] = $nbNonSignees." déclaration(s) ignorée(s) faute de signature électronique du centre d'état civil";
            }
            if ($nbReportesRegistre > 0) {
                $avertissements[] = $nbReportesRegistre.' déclaration(s) reportée(s) faute de place dans le registre';
            }
            if ($avertissements !== []) {
                $message .= ' ('.implode(' ; ', $avertissements).').';
            }

            return response()->json([
                'code' => '200',
                'message' => ['reponse' => $message],
                'details' => [
                    'generes' => $nbGeneres,
                    'ignores_non_signes' => $nbNonSignees,
                    'reportes_registre' => $nbReportesRegistre,
                ],
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error($e->getMessage());

            return response()->json([
                'code' => '201',
                'message' => ['error' => $e->getMessage()],
            ]);
        }
    }

    public function searchActe()
    {

        $nom = request('nom') ? '%'.request('nom').'%' : '';
        $prenom = request('prenom') ? '%'.request('prenom').'%' : '';
        $lieu = request('lieu') ? '%'.request('lieu').'%' : '';
        $personnes = DB::select('SELECT dn.code_declaration_naissance, ip.nom,ip.prenom,ip.lieu_naissance,ti.lib_institution,an.niupp FROM tr_identification_personne ip JOIN t_declaration_naissance dn ON ip.code_personne = dn.code_enfant JOIN t_acte_naissance an ON dn.code_declaration_naissance = an.code_declaration_naissance JOIN tr_ins_user iu ON an.cui = iu.cui JOIN tr_institution ti ON iu.code_institution = ti.code_institution WHERE ip.nom LIKE ? OR ip.prenom LIKE ? OR ip.lieu_naissance LIKE ?', [$nom, $prenom, $lieu]);

        return response()->json([
            'personnes' => $personnes,
        ]);
    }

    public function displayDuplicata($id)
    {

        $acte = ActeNaissance::where('code_declaration_naissance', $id)->first();
        $dummy = 'XXXXXXXXXXXXXXXX';

        if ($acte == null) {
            flash()->error('Vous ne pouvez pas généré un acte de naissance');

            return back();
        }

        if (! $acte->niupp) {
            flash()->error('Le duplicata n’est disponible qu’après signature par l’officier d’état civil.');

            return back();
        }

        view()->share('tester', [], 'Alange');
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('naissance::etats.duplicata', compact('acte', 'dummy'))->render());

        return $html2pdf->output($acte->code_acte_naissance.'.pdf');
    }

    /**
     * @deprecated Remplacé par signature/prepare + signature/finalize (.p12).
     */
    public function sendOtp(Request $request, OtpService $otpService)
    {
        return response()->json([
            'code' => '410',
            'message' => 'La validation par OTP n\'est plus disponible. Utilisez la signature électronique (.p12).',
        ]);
    }

    /**
     * @deprecated Remplacé par signature/prepare + signature/finalize (.p12).
     */
    public function validateOtp(Request $request, OtpService $otpService)
    {
        return response()->json([
            'code' => '410',
            'message' => 'La validation par OTP n\'est plus disponible. Utilisez la signature électronique (.p12).',
        ]);
    }

    /**
     * Prépare la signature électronique (.p12) — empreintes PDF.
     */
    public function prepareSignature(Request $request, ActeNaissanceGuotValidationService $guotValidation)
    {
        if (! Gate::allows('module.acteNaissance.signature')) {
            return response()->json([
                'code' => '181',
                'message' => "Vous n'êtes pas autorisé à signer un acte de naissance",
            ]);
        }

        $codes = $request->input('codes', []);
        if (! is_array($codes) || $codes === []) {
            $single = (string) $request->input('code_declaration_naissance', '');
            $codes = $single !== '' ? [$single] : [];
        }
        $codes = array_values(array_unique(array_filter(array_map('strval', $codes))));
        if ($codes === []) {
            return response()->json([
                'code' => '180',
                'message' => 'Aucun acte sélectionné',
            ]);
        }

        $result = $guotValidation->prepare(Auth::user(), $codes);

        return response()->json([
            'code' => $result['ok'] ? '200' : '183',
            'message' => $result['message'],
            'token' => $result['token'] ?? null,
            'expected_serial' => $result['expected_serial'] ?? null,
            'items' => $result['items'] ?? [],
        ]);
    }

    /**
     * Finalise la signature électronique après signature locale .p12.
     */
    public function finalizeSignature(Request $request, ActeNaissanceGuotValidationService $guotValidation)
    {
        if (! Gate::allows('module.acteNaissance.signature')) {
            return response()->json([
                'code' => '181',
                'message' => "Vous n'êtes pas autorisé à signer un acte de naissance",
            ]);
        }

        $token = (string) $request->input('token', '');
        $signatures = $request->input('signatures', []);
        if (! is_array($signatures)) {
            $signatures = [];
        }

        $result = $guotValidation->finalize(
            Auth::user(),
            $token,
            $signatures,
            $request->ip(),
            $request->userAgent()
        );

        return response()->json([
            'code' => $result['ok'] ? '200' : '183',
            'message' => $result['message'],
            'signed' => $result['signed'],
        ]);
    }

    /**
     * Prépare l'annulation électronique (.p12) — empreinte de la demande d'annulation.
     */
    public function prepareAnnulationSignature(Request $request, ActeNaissanceAnnulationSignatureService $annulationSignature)
    {
        if (! Gate::allows('module.acteNaissance.signature')) {
            return response()->json([
                'code' => '181',
                'message' => "Vous n'êtes pas autorisé à annuler un acte de naissance",
            ]);
        }

        $codes = $request->input('codes', []);
        if (! is_array($codes) || $codes === []) {
            $single = (string) $request->input('code_declaration_naissance', '');
            $codes = $single !== '' ? [$single] : [];
        }
        $codes = array_values(array_unique(array_filter(array_map('strval', $codes))));

        $motif = trim((string) $request->input('motif', ''));
        $observation = (string) $request->input('observation', '');

        if ($codes === []) {
            return response()->json(['code' => '180', 'message' => 'Aucun acte sélectionné']);
        }

        $result = $annulationSignature->prepare(Auth::user(), $codes, $motif, $observation);

        return response()->json([
            'code' => $result['ok'] ? '200' : '183',
            'message' => $result['message'],
            'token' => $result['token'] ?? null,
            'expected_serial' => $result['expected_serial'] ?? null,
            'items' => $result['items'] ?? [],
        ]);
    }

    /**
     * Finalise l'annulation après signature locale .p12.
     */
    public function finalizeAnnulationSignature(Request $request, ActeNaissanceAnnulationSignatureService $annulationSignature)
    {
        if (! Gate::allows('module.acteNaissance.signature')) {
            return response()->json([
                'code' => '181',
                'message' => "Vous n'êtes pas autorisé à annuler un acte de naissance",
            ]);
        }

        $token = (string) $request->input('token', '');
        $signatures = $request->input('signatures', []);
        if (! is_array($signatures)) {
            $signatures = [];
        }

        $result = $annulationSignature->finalize(
            Auth::user(),
            $token,
            $signatures,
            $request->ip(),
            $request->userAgent()
        );

        return response()->json([
            'code' => $result['ok'] ? '200' : '183',
            'message' => $result['message'],
            'cancelled' => $result['cancelled'],
        ]);
    }

    /**
     * @deprecated Remplacé par prepareSignature + finalizeSignature (.p12).
     */
    public function signGuot(Request $request, ActeNaissanceGuotValidationService $guotValidation)
    {
        return $this->prepareSignature($request, $guotValidation);
    }

    /**
     * @deprecated Remplacé par prepareSignature + finalizeSignature (.p12).
     */
    public function signGuotBulk(Request $request, ActeNaissanceGuotValidationService $guotValidation)
    {
        return $this->prepareSignature($request, $guotValidation);
    }

    /**
     * @deprecated Remplacé par signature/prepare + signature/finalize (.p12).
     */
    public function sendOtpBulk(Request $request, OtpService $otpService)
    {
        return response()->json([
            'code' => '410',
            'message' => 'La validation par OTP n\'est plus disponible. Utilisez la signature électronique (.p12).',
        ]);
    }

    /**
     * @deprecated Remplacé par signature/prepare + signature/finalize (.p12).
     */
    public function validateOtpBulk(Request $request, OtpService $otpService, MouvementService $mouvementService)
    {
        return response()->json([
            'code' => '410',
            'message' => 'La validation par OTP n\'est plus disponible. Utilisez la signature électronique (.p12).',
        ]);
    }

    /**
     * @deprecated Remplacé par annulation/prepare + annulation/finalize (.p12).
     */
    public function sendOtpAnnulation(Request $request, OtpService $otpService)
    {
        return response()->json([
            'code' => '410',
            'message' => 'L\'annulation par OTP n\'est plus disponible. Utilisez la signature électronique (.p12).',
        ]);
    }

    /**
     * @deprecated Remplacé par annulation/prepare + annulation/finalize (.p12).
     */
    public function validateOtpAnnulation(Request $request, OtpService $otpService, MouvementService $mouvementService)
    {
        return response()->json([
            'code' => '410',
            'message' => 'L\'annulation par OTP n\'est plus disponible. Utilisez la signature électronique (.p12).',
        ]);
    }

    /**
     * @deprecated Remplacé par annulation/prepare + annulation/finalize (.p12).
     */
    public function sendOtpAnnulationBulk(Request $request, OtpService $otpService)
    {
        return response()->json([
            'code' => '410',
            'message' => 'L\'annulation par OTP n\'est plus disponible. Utilisez la signature électronique (.p12).',
        ]);
    }

    /**
     * @deprecated Remplacé par annulation/prepare + annulation/finalize (.p12).
     */
    public function validateOtpAnnulationBulk(Request $request, OtpService $otpService)
    {
        return response()->json([
            'code' => '410',
            'message' => 'L\'annulation par OTP n\'est plus disponible. Utilisez la signature électronique (.p12).',
        ]);
    }

    public function repertoire()
    {
        return redirect()->route('reporting.faits.repertoire.alphabetique', ['type_fait' => 'naissance']);
    }

    public function repertoireAlphabetique(Request $request)
    {
        return redirect()->route('reporting.faits.repertoire.alphabetique', array_filter([
            'type_fait' => 'naissance',
            'dated' => $request->input('dated'),
            'datef' => $request->input('datef'),
        ], fn ($v) => $v !== null && $v !== ''));
    }

    public function retraitActe(Request $request)
    {
        $peutDepuisConsultationCec = Gate::allows('module.acteNaissance.retrait.depuisConsultationCEC');
        $peutDepuisProduction = Gate::allows('module.acteNaissance.generate');
        $peutSigner = Gate::allows('module.acteNaissance.signature');
        if (! $peutDepuisConsultationCec && ! $peutDepuisProduction && ! $peutSigner) {
            return response()->json([
                'code' => '403',
                'message' => ['error' => 'Vous n\'avez pas l\'autorisation d\'enregistrer le retrait de cet acte.'],
            ], 403);
        }

        $retire_par = $request->nominteresse.' '.$request->prenominteresse;
        $acte = ActeNaissance::findByIdentifierOrFail($request->niupp);
        if (! $acte->niupp) {
            return response()->json([
                'code' => '201',
                'message' => ['error' => 'Retrait impossible : l’acte n’a pas encore été signé (NIUPP non attribué).'],
            ]);
        }
        $observations = trim($request->observations) ?? 'Acte rétiré';

        DB::beginTransaction();
        try {
            $retrait = new RetraitActe;
            $retrait->code_retrait_acte = Sifec::genererCodeUniqueReferentiel($retrait, 'code_retrait_acte', 8, 'RET_');
            $retrait->code_acte = $acte->niupp;
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
            $mouvementService = new MouvementService;
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
                'message' => ['reponse' => 'Le retrait de l\'acte de naissance enregistré avec succès'],
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error($e->getMessage());

            return response()->json([
                'code' => '201',
                'message' => ['error' => $e->getMessage()],
            ]);
        }
    }

    public function displayExtrait($id)
    {
        $acte = ActeNaissance::with(array_merge(
            Declarationnaissance::eagerLoadDeclarationTribunalMentionDepuisActeNaissance(),
            [
                'declaration.enfant',
                'declaration.institutionUser.institution.institutionParent',
                'institutionUser.institution.lieu.localiteParent',
            ]
        ))->where('code_declaration_naissance', $id)->first();
        $dummy = 'XXXXXXXXXXXXXXXX';
        $numExtrait = substr(time(), 2);

        if ($acte == null) {
            Log::channel('sifec')->warning('[ActeNaissance][PDF][Extrait] Acte introuvable', [
                'code_declaration_naissance' => $id,
            ]);
            flash()->error("Vous ne pouvez pas généré un extrait d'acte de naissance");

            return back();
        }

        if (! $acte->niupp) {
            Log::channel('sifec')->warning('[ActeNaissance][PDF][Extrait] Extrait demandé sans NIUPP', [
                'code_declaration_naissance' => $acte->code_declaration_naissance,
                'code_acte_naissance' => $acte->code_acte_naissance,
            ]);
            flash()->error('L’extrait n’est disponible qu’après signature par l’officier d’état civil.');

            return back();
        }

        try {
            view()->share('tester', [], 'extrait');
            // $html2pdf = new Html2Pdf('L', 'A5', 'fr');
            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->setDefaultFont('Arial');

            // Extrait hors demande : pas de QR de délivrance (cf. Demande document)
            $html2pdf->writeHTML(view('naissance::etats.extrait', compact('acte', 'dummy', 'numExtrait'))->render());

            $pdfBinary = $html2pdf->output($acte->code_acte_naissance.'.pdf');

            return $this->pdfInlineResponse($pdfBinary, $acte->code_acte_naissance.'.pdf');
        } catch (Exception $e) {
            Log::channel('sifec')->error('[ActeNaissance][PDF][Extrait] Échec génération', [
                'code_declaration_naissance' => $id,
                'message' => $e->getMessage(),
                'exception' => $e::class,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            flash()->error("Erreur lors de la génération de l'extrait : ".$e->getMessage());

            return back();
        }
    }

    public function findActe(Request $request)
    {
        $acte = ActeNaissance::findByIdentifier($request->niupp);

        if ($acte == null) {
            return response()->json([
                'code' => '180',
                'message' => 'Aucun acte trouvé pour ce numéro',
            ]);
        }

        if ($acte->deleted_at != null) {
            return response()->json([
                'code' => '180',
                'message' => 'Cet acte a été annulé',
            ]);
        }

        $nom = $acte->declaration->enfant->nom;
        $prenom = $acte->declaration->enfant->prenom;
        $sexe = $acte->declaration->enfant->sexe == 'M' ? 'Masculin' : 'Féminin';
        $dateNaissance = date('d-m-Y', strtotime($acte->declaration->date_heure_naissance));
        $lieuNaissance = $acte->declaration->enfant->lieu_naissance;
        $cec = $acte->institutionUser->institution->lib_institution;
        $cdn = $acte->code_declaration_naissance;
        $codeAdoptant = $acte->declaration->code_adoptant; // déjà adopter ou pas
        $button = '';
        if ($codeAdoptant != '') {
            $button = 'disabled'; // pour désactiver le lien de l'adoption
        }

        return response()->json([
            'code' => '200',
            'nomPrenom' => $nom.' '.$prenom,
            'dateNaissance' => $dateNaissance,
            'sexe' => $sexe,
            'lieuNaissance' => $lieuNaissance,
            'cec' => $cec,
            'cdn' => $cdn,
            'statutEnfant' => $codeAdoptant == '' ? 'Adopter' : 'Enfant déjà adopté',
            'statutLien' => $button,
        ]);
    }

    public function printActe($id)
    {

        $acte = ActeNaissance::where('code_declaration_naissance', $id)->orWhere('niupp', $id)->orWhere('code_acte_naissance', $id)->first();

        // Pas besoin de redirection ici, la vue gère le cas où $acte est null
        return view('naissance::acte.acte', compact('acte'));
    }

    public function printCopie($id)
    {
        $acte = ActeNaissance::where('code_declaration_naissance', $id)->first();
        if ($acte === null) {
            Log::channel('sifec')->warning('[ActeNaissance][Copie] Page copie : déclaration / acte introuvable', [
                'code_declaration_naissance' => $id,
            ]);
        }

        return view('naissance::acte.copie', compact('acte'));
        // return view('naissance::acte.acte',compact("acte"));
    }

    public function printExtrait($id)
    {
        $acte = ActeNaissance::where('code_declaration_naissance', $id)->first();
        if ($acte === null) {
            Log::channel('sifec')->warning('[ActeNaissance][Extrait] Page extrait : déclaration / acte introuvable', [
                'code_declaration_naissance' => $id,
            ]);
        }

        return view('naissance::acte.extrait', compact('acte'));
    }

    /**
     * Valide l'annulation d'un acte et crée un nouvel acte portant la mention "ANNULÉ"
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function validerAnnulation(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $acte = ActeNaissance::findByIdentifier($id);
            
            if ($acte == null) {
                flash()->error('Acte de naissance indisponible');
                return back();
            }

            if ($acte->deleted_at != null) {
                flash()->error('Cet acte est déjà annulé');
                return back();
            }

            $declaration = Declarationnaissance::where('code_declaration_naissance', $acte->code_declaration_naissance)->first();
            if ($declaration == null) {
                flash()->error('Déclaration indisponible');
                return back();
            }

            // Vérifier qu'un registre est disponible
            $registre = $this->resolveRegistreNaissanceDisponible($affectation);
                
            if (!$registre) {
                flash()->error($this->messageRegistreNaissanceIndisponible($affectation));
                return back();
            }

            if (($registre->nombre_acte_prevu - $registre->nombre_acte_transcrit) <= 0) {
                flash()->error('Le registre est plein. Veuillez ajouter des feuillets avant de continuer.');
                return back();
            }

            // Enregistrer le code du jugement
            $declaration->code_jugement = $request->code_jugement;
            $declaration->save();

            // Récupérer le jugement pour les informations de mention
            $jugement = $declaration->jugement;
            if (!$jugement) {
                flash()->error('Jugement introuvable pour cette déclaration');
                return back();
            }

            // 1. Annuler l'ancien acte (soft delete)
            $acte->deleted_at = Carbon::now();
            $acte->motif_annulation = $request->motif ?? 'Jugement d\'annulation';
            $acte->statut = 1;
            $acte->save();

            // 2. Créer un nouvel acte portant la mention "ANNULÉ"
            $acteNaissanceService = app(ActeNaissanceService::class);
            $nouvelActe = $acteNaissanceService->genererActeAnnule(
                $declaration, 
                $acte, 
                $registre, 
                $user,
                $jugement
            );

            // 3. Créer un mouvement d'annulation
            if ($acte && $declaration) {
                $mouvementService = new MouvementService;
                $mouvementService->envoyerDeclaration($user, $declaration, 'MOUV_0014', [], 'annulé');
            }

            // 4. Ajouter un mouvement pour la création du nouvel acte
            $mouvementService = new MouvementService;
            $mouvementService->ajouterEvenementActe($user, $declaration, 'attente_approbation');

            flash()->success('Acte annulé et nouvel acte créé avec mention "ANNULÉ". Le nouvel acte doit être signé par l\'officier d\'état civil.');
            DB::commit();

            return redirect()->route('acteNaissance.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('Erreur validerAnnulation : '.$e->getMessage());
            flash()->error('Erreur lors de l\'annulation : '.$e->getMessage());

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

            $acte = ActeNaissance::findByIdentifier($id);
            if ($acte == null) {
                flash()->error('Acte de naissance indisponible');

                return back();
            }

            if ($acte->deleted_at != null) {
                flash()->warning('Acte déjà annuler');

                return back();
            }

            $created = new Carbon($acte->created_at);
            // $now = Carbon::now();
            // $difference = ($created->diff($now)->days < 1)
            //     ? 'today'
            //     : $created->diffForHumans($now);
            $DeferenceInDays = Carbon::parse(Carbon::now())->diffInMonths($created);

            $tgis = Institution::where('code_type_institution', [], 'TPINS_0001')->get();

            return view('naissance::acte.acterectification', compact('acte', 'tgis', 'DeferenceInDays'));
        } catch (Exception $e) {
            flash()->error($e->getMessage());

            return back();
        }
    }

    /**
     * @deprecated Remplacé par annulation/prepare + annulation/finalize (.p12).
     */
    public function annulerActe(Request $request, MouvementService $mouvementService)
    {
        return response()->json([
            'code' => '410',
            'message' => 'L\'annulation sans signature électronique n\'est plus disponible. Utilisez la signature (.p12).',
        ]);
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
            $statut = 'Confirmée';

            [$ok, $result] = $mouvementService->confirmerDeclarationNaissance(
                $affectation,
                $declaration,
                $statut,
                $observation
            );

            if (! $ok) {
                DB::rollBack();
                throw new Exception($result ?: 'Erreur lors de la confirmation du dossier');
            }

            DB::commit();

            return response()->json([
                'code' => '200',
                'message' => ['Dossier confirmé avec succès'],
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('Erreur confirmation dossier : '.$e->getMessage());

            return response()->json([
                'code' => '500',
                'message' => ['Erreur lors de la confirmation du dossier: '.$e->getMessage()],
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
            $statut = 'Confirmée';

            $declarations = Declarationnaissance::whereIn('code_declaration_naissance', $codes)->get();

            if ($declarations->count() === 0) {
                return response()->json([
                    'code' => '400',
                    'message' => ['Aucun dossier à confirmer'],
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
                    $erreurs[] = $declaration->code_declaration_naissance.' : '.$result;
                }
            }

            if ($confirmes === 0) {
                return response()->json([
                    'code' => '400',
                    'message' => ["Aucun dossier n'a pu être confirmé", ...$erreurs],
                ]);
            }

            $msg = [$confirmes.' dossier(s) confirmé(s) avec succès'];
            if (count($erreurs)) {
                $msg[] = 'Erreurs sur certains dossiers : '.implode(' | ', $erreurs);
            }

            return response()->json([
                'code' => '200',
                'message' => $msg,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => '500',
                'message' => ['Erreur lors de la confirmation des dossiers: '.$e->getMessage()],
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

            if (! $ok) {
                DB::rollBack();
                throw new Exception($result ?: 'Erreur lors du renvoi du dossier');
            }

            // Notification centralisée via le module Notification (après commit)
            NotificationService::notifierAgentsInstitution(
                $declaration->institution,
                new DeclarationEnvoyeeCentreNotification(
                    $declaration,
                    $declaration->institution,
                    'renvoyée',
                    $observation ?? 'Une déclaration de naissance a été renvoyée à votre institution.',
                ),
                'FONC_0006'
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
                'message' => ['Dossier renvoyé avec succès'],
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('Erreur renvoi dossier : '.$e->getMessage());

            return response()->json([
                'code' => '500',
                'message' => ['Erreur lors du renvoi du dossier: '.$e->getMessage()],
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
                    'message' => ['Aucun dossier à renvoyer'],
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
                        new DeclarationEnvoyeeCentreNotification(
                            $declaration,
                            $declaration->institution,
                            'renvoyée',
                            $motif,
                            $observation
                        )
                    );
                } else {
                    $erreurs[] = $declaration->code_declaration_naissance.' : '.$result;
                }
            }

            if ($renvoyes === 0) {
                return response()->json([
                    'code' => '400',
                    'message' => ["Aucun dossier n'a pu être renvoyé", ...$erreurs],
                ]);
            }

            $msg = [$renvoyes.' dossier(s) renvoyé(s) avec succès'];
            if (count($erreurs)) {
                $msg[] = 'Erreurs sur certains dossiers : '.implode(' | ', $erreurs);
            }

            return response()->json([
                'code' => '200',
                'message' => $msg,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => '500',
                'message' => ['Erreur lors du renvoi des dossiers: '.$e->getMessage()],
            ]);
        }
    }

    /**
     * @deprecated Remplacé par annulation/prepare + annulation/finalize (.p12).
     */
    public function annulerActesBulk(Request $request)
    {
        return response()->json([
            'code' => '410',
            'message' => 'L\'annulation sans signature électronique n\'est plus disponible. Utilisez la signature (.p12).',
        ]);
    }

    /**
     * Registre de naissance utilisable pour générer un acte :
     * paraphé (approbation tribunal), statut actif, places restantes.
     * Recherche d’abord sur le CUI de l’agent, sinon sur le CEC de l’affectation.
     */
    private function resolveRegistreNaissanceDisponible($affectation): ?Registre
    {
        if ($affectation === null) {
            return null;
        }

        $base = Registre::query()
            ->where('code_type_registre', 'TPRG_0001')
            ->where('statut', 1)
            ->whereNotNull('approbation_tribunal')
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

    private function messageRegistreNaissanceIndisponible($affectation): string
    {
        if ($affectation === null) {
            return 'Aucune affectation active : impossible de déterminer le registre.';
        }

        $candidats = Registre::query()
            ->where('code_type_registre', 'TPRG_0001')
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
            return 'Aucun registre de naissance trouvé pour ce centre. Créez un registre puis faites-le parapher par le tribunal.';
        }

        $enAttente = $candidats->first(function (Registre $r) {
            return (int) $r->statut === 0 && ! filled($r->approbation_tribunal);
        });
        if ($enAttente !== null) {
            return 'Le registre est en attente de paraphe du tribunal. Impossible de générer un acte tant qu’il n’est pas activé.';
        }

        $plein = $candidats->first(function (Registre $r) {
            return filled($r->approbation_tribunal)
                && ((int) $r->nombre_acte_prevu - (int) $r->nombre_acte_transcrit) <= 0;
        });
        if ($plein !== null) {
            return 'Le registre est plein. Ajoutez des feuillets pour continuer.';
        }

        $cloture = $candidats->first(function (Registre $r) {
            return (int) $r->statut === 0 && filled($r->approbation_tribunal);
        });
        if ($cloture !== null) {
            return 'Le registre est clôturé ou inactif. Ouvrez un nouveau registre de naissance.';
        }

        return 'Aucun registre de naissance actif et paraphé disponible pour générer un acte.';
    }
}
