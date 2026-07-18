<?php

namespace Modules\Referentiel\Http\Controllers;

use App\Services\GuotInstitutionService;
use App\Services\InstitutionLienSyncService;
use App\Sifec\Sifec;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\Localite;
use Modules\Referentiel\Entities\TypeInstitution;
use Modules\Referentiel\Entities\TypeLienInstitution;
use Modules\Referentiel\Entities\TypeLocalite;
use PkiSdk\TrustException;
use RuntimeException;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        // Récupérer uniquement les institutions non supprimées (SoftDeletes) avec leurs relations
        // Limiter à 20 résultats par défaut pour améliorer les performances
        $institutions = Institution::with(['typeInstitution', 'institutionParent', 'lieu'])
            ->whereNotIn('code_type_institution', ['TPINS_0004', 'TPINS_0001']) // Exclure les types spécifiques
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        // Charger toutes les localités pour le filtre (districts, communes, arrondissements)
        $localites = Localite::whereIn('code_type_localite', ['TPLOC_0002', 'TPLOC_0003', 'TPLOC_0004'])->get();

        // Charger tous les types d'institutions pour le filtre
        $typeInstitutions = TypeInstitution::all();

        // Log pour déboguer
        Log::channel('sifec')->info('=== CHARGEMENT PAGE INSTITUTIONS ===', [
            'count_institutions' => $institutions->count(),
            'count_type_institutions' => $typeInstitutions->count(),
            'premiere_institution' => $institutions->first() ? $institutions->first()->lib_institution : 'Aucune',
        ]);

        return view('referentiel::institution.index', compact('institutions', 'localites', 'typeInstitutions'));
    }

    /**
     * Filtrer les institutions côté serveur
     */
    public function filterInstitutions(Request $request)
    {
        try {
            // Logger les critères de recherche
            Log::channel('sifec')->info('=== RECHERCHE INSTITUTIONS ===', [
                'criteres' => [
                    'lib_institution' => $request->input('lib_institution'),
                    'code_type_institution' => $request->input('code_type_institution'),
                    'code_localite' => $request->input('code_localite'),
                ],
            ]);

            $query = Institution::with(['typeInstitution', 'institutionParent', 'lieu'])
                ->whereNotIn('code_type_institution', ['TPINS_0004', 'TPINS_0001']); // Exclure les types spécifiques

            // Filtre par libellé d'institution
            if ($request->filled('lib_institution') && strlen(trim($request->lib_institution)) > 0) {
                $query->where('lib_institution', 'LIKE', '%'.strtoupper(trim($request->lib_institution)).'%');
            }

            // Filtre par type d'institution
            if ($request->filled('code_type_institution') && strlen(trim($request->code_type_institution)) > 0) {
                $query->where('code_type_institution', $request->code_type_institution);
            }

            // Filtre par localité
            if ($request->filled('code_localite') && strlen(trim($request->code_localite)) > 0) {
                $query->where('code_localite', $request->code_localite);
            }

            // Filtre cachet GUOT (lié / manquant)
            if ($request->filled('statut_guot')) {
                if ($request->statut_guot === 'lie') {
                    $query->whereNotNull('guot_institution_id')->where('guot_institution_id', '!=', '');
                } elseif ($request->statut_guot === 'manquant') {
                    $query->where(function ($q) {
                        $q->whereNull('guot_institution_id')->orWhere('guot_institution_id', '');
                    });
                }
            }

            $countInitial = $query->count();

            // Trier par date de création (plus récentes en premier)
            $institutions = $query->orderBy('created_at', 'desc')->get();

            $countResultat = $institutions->count();

            // Limiter les résultats à 500 maximum pour éviter les problèmes de performance
            $maxResults = 500;
            if ($countResultat > $maxResults) {
                $institutions = $institutions->take($maxResults);
                Log::channel('sifec')->warning('=== RECHERCHE INSTITUTIONS - LIMITE ATTEINTE ===', [
                    'count_total' => $countResultat,
                    'count_affiché' => $maxResults,
                    'message' => "Plus de {$maxResults} résultats trouvés. Affinez vos critères de recherche pour voir tous les résultats.",
                ]);
            }

            // Logger les résultats de la recherche
            Log::channel('sifec')->info('=== RÉSULTATS RECHERCHE INSTITUTIONS ===', [
                'count_initial' => $countInitial,
                'count_resultat' => $countResultat,
                'count_affiché' => $institutions->count(),
                'filtres_appliques' => $request->only(['lib_institution', 'code_type_institution', 'code_localite', 'statut_guot']),
            ]);

            $limiteAtteinte = $countResultat > $maxResults;

            return response()->json([
                'success' => true,
                'html' => view('referentiel::institution.partials.table-institutions', compact('institutions'))->render(),
                'count' => $institutions->count(),
                'limite_atteinte' => $limiteAtteinte,
            ]);
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors du filtrage des institutions: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du filtrage : '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Formulaire de création (même périmètre que la page d’édition : localité, parent, liens métier).
     */
    public function create(): Renderable
    {
        $localites = Localite::whereIn('code_type_localite', ['TPLOC_0002', 'TPLOC_0003', 'TPLOC_0004'])
            ->orderBy('lib_localite')
            ->get();

        $typeInstitutions = TypeInstitution::orderBy('lib_type_institution')->get();

        $availableParents = Institution::with('typeInstitution')
            ->whereNotIn('code_type_institution', ['TPINS_0004', 'TPINS_0001'])
            ->orderBy('lib_institution')
            ->get();

        $institution = new Institution;
        $institution->setRelation('liensSortants', new EloquentCollection([]));

        $cecPourLiens = $this->institutionsCecPourLiens(null);
        $tribunauxPourLiens = $this->institutionsTribunauxPourLiens(null);

        return view('referentiel::institution.create', compact(
            'institution',
            'localites',
            'typeInstitutions',
            'availableParents',
            'cecPourLiens',
            'tribunauxPourLiens'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id  Code de l'institution (code_institution)
     * @return Renderable
     */
    public function edit($id)
    {
        $institution = Institution::with(['typeInstitution', 'institutionParent', 'lieu'])
            ->find($id);

        if ($institution === null) {
            return redirect()->route('institution.index')->with('error', 'Institution introuvable.');
        }

        // Exclure les types TPINS_0004 et TPINS_0001 pour l'édition
        if (in_array($institution->code_type_institution, ['TPINS_0004', 'TPINS_0001'])) {
            return redirect()->route('institution.index')->with('error', "La modification de ce type d'institution n'est pas autorisée.");
        }

        $localites = Localite::whereIn('code_type_localite', ['TPLOC_0002', 'TPLOC_0003', 'TPLOC_0004'])->get();
        $typeInstitutions = TypeInstitution::all();
        $typeLocalites = TypeLocalite::all();
        $tribunaux = Institution::whereIn('code_type_institution', ['TPINS_0008', 'TPINS_0001'])->get();

        // Parents disponibles (exclure l'institution et ses descendants)
        $descendants = $institution->descendants()->pluck('code_institution')->toArray();
        $availableParents = Institution::with('typeInstitution')
            ->whereNotIn('code_type_institution', ['TPINS_0004', 'TPINS_0001'])
            ->where('code_institution', '!=', $institution->code_institution)
            ->whereNotIn('code_institution', $descendants)
            ->orderBy('lib_institution')
            ->get();

        $institution->load(['liensSortants' => function ($q) {
            $q->whereIn('code_type_lien', [
                TypeLienInstitution::CODE_PARTENAIRE_DECES_POMPE,
                TypeLienInstitution::CODE_TRIBUNAL_RESSORT,
                TypeLienInstitution::CODE_FORMATION_CEC_NAISSANCE,
            ]);
        }]);

        $cecPourLiens = $this->institutionsCecPourLiens($institution->code_institution);
        $tribunauxPourLiens = $this->institutionsTribunauxPourLiens($institution->code_institution);

        return view('referentiel::institution.edit', compact(
            'institution',
            'localites',
            'typeInstitutions',
            'tribunaux',
            'typeLocalites',
            'availableParents',
            'cecPourLiens',
            'tribunauxPourLiens'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'lib_institution' => ['required', 'string', 'max:255'],
            'code_type_institution' => ['required', 'string'],
            'code_localite' => ['required', 'string'],
            'code_institution_parent' => ['nullable', 'string'],
            'statut' => ['nullable', 'boolean'],
            'guot_institution_id' => ['nullable', 'string', 'max:128'],
            'guot_institution_verifier_url' => ['nullable', 'url', 'max:255'],
            'liens_cec_deces' => ['nullable', 'array'],
            'liens_cec_deces.*' => ['string', 'max:16'],
            'liens_cec_naissance' => ['nullable', 'array'],
            'liens_cec_naissance.*' => ['string', 'max:16'],
            'liens_tribunal_ressort' => ['nullable', 'array'],
            'liens_tribunal_ressort.*' => ['string', 'max:16'],
        ]);

        try {
            DB::beginTransaction();

            // Valider la hiérarchie (éviter les boucles)
            if ($request->code_institution_parent) {
                $parent = Institution::find($request->code_institution_parent);
                if ($parent) {
                    $descendants = $parent->descendants()->pluck('code_institution')->toArray();
                    // L'institution parent ne peut pas être un descendant de l'institution créée
                    // (mais comme on crée, on vérifie juste que le parent existe et n'est pas lui-même)
                }
            }

            $institution = new Institution;
            $institution->code_institution = Sifec::genererCodeUniqueReferentiel($institution, 'code_institution', 4, 'INST_');
            $institution->lib_institution = strtoupper($request->lib_institution);
            $institution->code_type_institution = $request->code_type_institution;
            $institution->statut = $request->statut ?? 1;
            $institution->code_institution_parent = $request->code_institution_parent ?: null;
            $institution->code_localite = $request->code_localite;
            $institution->guot_institution_id = $request->filled('guot_institution_id')
                ? trim($request->guot_institution_id)
                : null;
            $institution->guot_institution_verifier_url = $request->filled('guot_institution_verifier_url')
                ? trim($request->guot_institution_verifier_url)
                : null;

            if ($request->hasFile('sceau')) {
                $file = $request->file('sceau');
                if ($file->isValid()) {
                    $sceau = $file->store('sceau');
                    $institution->sceau = $sceau;
                } else {
                    DB::rollBack();

                    return redirect()->back()->withInput()->with('error', 'Le fichier du sceau est corrompu ou inaccessible.');
                }
            }

            $institution->save();

            app(InstitutionLienSyncService::class)->syncFromRequest($institution, $request->all(), true);

            DB::commit();

            Log::channel('sifec')->info('Institution créée avec succès', [
                'code_institution' => $institution->code_institution,
                'lib_institution' => $institution->lib_institution,
            ]);

            return redirect()
                ->route('institution.index')
                ->with('success', $institution->lib_institution.' enregistré(e) avec succès.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('Erreur lors de la création d\'institution: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {

        $institution = Institution::find($id);

        if ($institution == null) {
            return redirect()->back()->with('error', 'Institution introuvable.');
        }

        try {
            $request->validate([
                'lib_institution' => ['required', 'string', 'max:255'],
                'code_type_institution' => ['required', 'string'],
                'code_localite' => ['required', 'string'],
                'code_institution_parent' => ['nullable', 'string'],
                'statut' => ['nullable', 'boolean'],
                'guot_institution_id' => ['nullable', 'string', 'max:128'],
                'guot_institution_verifier_url' => ['nullable', 'url', 'max:255'],
                'liens_cec_deces' => ['nullable', 'array'],
                'liens_cec_deces.*' => ['string', 'max:16'],
                'liens_cec_naissance' => ['nullable', 'array'],
                'liens_cec_naissance.*' => ['string', 'max:16'],
                'liens_tribunal_ressort' => ['nullable', 'array'],
                'liens_tribunal_ressort.*' => ['string', 'max:16'],
            ]);

            // Valider la hiérarchie (éviter les boucles)
            if ($request->code_institution_parent && $request->code_institution_parent !== $institution->code_institution_parent) {
                $parent = Institution::find($request->code_institution_parent);
                if ($parent) {
                    $descendants = $institution->descendants()->pluck('code_institution')->toArray();
                    if (in_array($request->code_institution_parent, $descendants)) {
                        return redirect()->back()->withInput()->with(
                            'error',
                            'Une institution ne peut pas être son propre parent ou avoir un de ses descendants comme parent.'
                        );
                    }
                }
            }

            $institution->lib_institution = strtoupper($request->lib_institution);
            $institution->code_type_institution = $request->code_type_institution;
            $institution->statut = $request->statut ?? $institution->statut;
            $institution->code_institution_parent = $request->code_institution_parent ?: null;
            $institution->code_localite = $request->code_localite;
            $institution->guot_institution_id = $request->filled('guot_institution_id')
                ? trim($request->guot_institution_id)
                : null;
            $institution->guot_institution_verifier_url = $request->filled('guot_institution_verifier_url')
                ? trim($request->guot_institution_verifier_url)
                : null;

            if ($request->hasFile('sceau')) {
                $file = $request->file('sceau');
                if ($file->isValid()) {
                    $sceau = $file->store('sceau');
                    $institution->sceau = $sceau;
                } else {
                    return redirect()->back()->withInput()->with('error', 'Le fichier du sceau est corrompu ou inaccessible.');
                }
            }

            $institution->save();

            if ($request->boolean('_gestion_liens_institution')) {
                app(InstitutionLienSyncService::class)->syncFromRequest($institution, $request->all(), true);
            }

            Log::channel('sifec')->info('Institution modifiée avec succès', [
                'code_institution' => $institution->code_institution,
                'lib_institution' => $institution->lib_institution,
            ]);

            return redirect()
                ->route('institution.index')
                ->with('success', $institution->lib_institution.' modifié(e) avec succès.');

        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de la modification d\'institution: '.$e->getMessage(), [
                'code_institution' => $id,
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $institution = Institution::find($id);

            if ($institution == null) {
                return redirect()->back()->with('error', 'Institution introuvable.');
            }

            // Utiliser softDeletes() au lieu de supprimer=1
            $institution->delete();

            Log::channel('sifec')->info('Institution supprimée (soft delete)', [
                'code_institution' => $institution->code_institution,
                'lib_institution' => $institution->lib_institution,
            ]);

            return redirect()->route('institution.index')->with('success', 'Suppression effectuée avec succès.');
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de la suppression d\'institution: '.$e->getMessage(), [
                'code_institution' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Erreur lors de la suppression : '.$e->getMessage());
        }
    }

    public function getInstitution()
    {
        $id = request('id');
        $institutions = Institution::where('code_type_institution', $id)->get();

        return $institutions;
    }

    public function getLocalite()
    {
        $id = request('id');
        $localites = Localite::where('code_type_localite', $id)->get();

        return $localites;
    }

    /**
     * Récupérer les institutions parents disponibles (excluant l'institution et ses descendants)
     *
     * @param  string  $id  Code de l'institution à exclure
     * @return JsonResponse
     */
    public function getAvailableParents($id = null)
    {
        try {
            $query = Institution::whereNotIn('code_type_institution', ['TPINS_0004', 'TPINS_0001'])
                ->with('typeInstitution');

            // Exclure l'institution elle-même et ses descendants si un ID est fourni
            if ($id) {
                $institution = Institution::find($id);
                if ($institution) {
                    // Exclure l'institution elle-même
                    $query->where('code_institution', '!=', $id);

                    // Exclure tous les descendants
                    $descendants = $institution->descendants()->pluck('code_institution')->toArray();
                    if (! empty($descendants)) {
                        $query->whereNotIn('code_institution', $descendants);
                    }
                }
            }

            $parents = $query->orderBy('lib_institution')->get();

            return response()->json($parents);
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de la récupération des parents disponibles: '.$e->getMessage());

            return response()->json(['error' => 'Erreur lors de la récupération des parents'], 500);
        }
    }

    /**
     * Récupérer les institutions parents disponibles par type d'institution
     *
     * @param  string  $codeTypeInstitution  Code du type d'institution
     * @return JsonResponse
     */
    public function getAvailableParentsByType($codeTypeInstitution)
    {
        try {
            $parents = Institution::whereNotIn('code_type_institution', ['TPINS_0004', 'TPINS_0001'])
                ->where('code_type_institution', $codeTypeInstitution)
                ->with('typeInstitution')
                ->orderBy('lib_institution')
                ->get();

            return response()->json($parents);
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de la récupération des parents par type: '.$e->getMessage());

            return response()->json(['error' => 'Erreur lors de la récupération des parents'], 500);
        }
    }

    /**
     * Centres d'état civil utilisables comme cible de lien (hors tribunaux, hors pompe funèbre type).
     */
    private function institutionsCecPourLiens(?string $exclureCode = null)
    {
        $q = Institution::with('typeInstitution')
            ->whereHas('typeInstitution', function ($q) {
                $q->where('code_type_categorie_ins', 'TCINS_0001');
            })
            ->where('code_type_institution', '!=', 'TPINS_0003')
            ->whereNotIn('code_type_institution', ['TPINS_0004', 'TPINS_0001'])
            ->orderBy('lib_institution');

        if ($exclureCode) {
            $q->where('code_institution', '!=', $exclureCode);
        }

        return $q->get();
    }

    /**
     * Enrôlement cachet institutionnel GUOT via trust-api (POST /v1/institutions).
     */
    public function enrollGuot(string $id, GuotInstitutionService $guot)
    {
        $institution = Institution::with('typeInstitution')->find($id);
        if ($institution === null) {
            return redirect()->route('institution.index')->with('error', 'Institution introuvable.');
        }

        try {
            $guot->enroll($institution);

            return redirect()
                ->route('institution.edit', $institution->code_institution)
                ->with('success', 'Cachet institutionnel activé avec succès.');
        } catch (TrustException|RuntimeException|Exception $e) {
            Log::channel('sifec')->error('Enrôlement cachet institution GUOT', [
                'code_institution' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('institution.edit', $institution->code_institution)
                ->with('error', 'Échec d’activation du cachet : '.$e->getMessage());
        }
    }

    /**
     * Resynchronise les métadonnées certificat depuis trust-api.
     */
    public function syncGuot(string $id, GuotInstitutionService $guot)
    {
        $institution = Institution::find($id);
        if ($institution === null) {
            return redirect()->route('institution.index')->with('error', 'Institution introuvable.');
        }

        try {
            $guot->syncFromTrustApi($institution);

            return redirect()
                ->route('institution.edit', $institution->code_institution)
                ->with('success', 'Cachet institutionnel actualisé.');
        } catch (TrustException|RuntimeException|Exception $e) {
            return redirect()
                ->route('institution.edit', $institution->code_institution)
                ->with('error', 'Échec d’actualisation du cachet : '.$e->getMessage());
        }
    }

    private function institutionsTribunauxPourLiens(?string $exclureCode = null)
    {
        $q = Institution::with('typeInstitution')
            ->whereHas('typeInstitution', function ($q) {
                $q->where('code_type_categorie_ins', 'TCINS_0002');
            })
            ->whereNotIn('code_type_institution', ['TPINS_0004', 'TPINS_0001'])
            ->orderBy('lib_institution');

        if ($exclureCode) {
            $q->where('code_institution', '!=', $exclureCode);
        }

        return $q->get();
    }
}
