<?php

namespace Modules\Referentiel\Http\Controllers;

use App\Sifec\Sifec;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Referentiel\Entities\Localite;
use Modules\Referentiel\Entities\TypeLocalite;

class LocaliteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        // Récupérer uniquement les localités non supprimées (SoftDeletes) avec leurs relations
        // Limiter à 20 résultats par défaut pour améliorer les performances
        $localites = Localite::with(['typelocalite', 'localiteParent'])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        // Charger toutes les localités racines pour le filtre de hiérarchie
        $localitesRacines = Localite::with('typelocalite')
            ->whereNull('code_localite_parent')
            ->orderBy('lib_localite')
            ->get();

        $typeLocalites = TypeLocalite::query()->orderBy('lib_type_localite')->get();

        return view('referentiel::localite.index', compact('localites', 'localitesRacines', 'typeLocalites'));
    }

    /**
     * Filtrer les localités côté serveur
     */
    public function filterLocalites(Request $request)
    {
        try {
            $query = Localite::with(['typelocalite', 'localiteParent']);

            if ($request->filled('lib_localite') && strlen(trim($request->lib_localite)) > 0) {
                $query->where('lib_localite', 'LIKE', '%'.strtoupper(trim($request->lib_localite)).'%');
            }

            if ($request->filled('code_type_localite') && strlen(trim($request->code_type_localite)) > 0) {
                $query->where('code_type_localite', $request->code_type_localite);
            }

            $maxResults = 500;
            $localites = (clone $query)
                ->orderBy('created_at', 'desc')
                ->limit($maxResults + 1)
                ->get();

            $limiteAtteinte = $localites->count() > $maxResults;
            if ($limiteAtteinte) {
                $localites = $localites->take($maxResults);
                $countResultat = $query->count();
                Log::channel('sifec')->warning('Recherche localités : limite affichage', [
                    'total' => $countResultat,
                    'max' => $maxResults,
                    'filtres' => $request->only(['lib_localite', 'code_type_localite']),
                ]);
            } else {
                $countResultat = $localites->count();
            }

            return response()->json([
                'code' => '200',
                'data' => view('referentiel::localite.partials.table-localites', compact('localites'))->render(),
                'count' => $countResultat,
                'count_affiché' => $localites->count(),
                'limite_atteinte' => $limiteAtteinte,
            ]);
        } catch (Exception $e) {
            Log::channel('sifec')->error('=== ERREUR RECHERCHE LOCALITÉS ===', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'criteres' => $request->only(['lib_localite', 'code_type_localite', 'code_localite_parent', 'pompes_funebres']),
            ]);

            return response()->json([
                'code' => '500',
                'message' => 'Erreur lors de la recherche des localités',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Enfants directs d'une localité, filtrés par types (?types=TPLOC_0003,TPLOC_0002).
     */
    public function children(Request $request, string $code)
    {
        $typesParam = (string) $request->query('types', '');
        $types = array_values(array_filter(array_map('trim', explode(',', $typesParam))));
        if ($types === []) {
            return response()->json([]);
        }

        $localites = Localite::query()
            ->where('code_localite_parent', $code)
            ->whereIn('code_type_localite', $types)
            ->orderBy('lib_localite')
            ->get();

        return response()->json($localites);
    }

    public function store(Request $request)
    {
        $request->validate([
            'lib_localite' => ['required', 'string', 'max:150'],
            'code_type_localite' => ['required', 'string'],
            'code_localite_parent' => ['nullable', 'string'],
            'pompes_funebres' => ['nullable', 'boolean'],
        ]);

        try {
            // Valider la hiérarchie
            $validation = $this->validateHierarchy($request->code_type_localite, $request->code_localite_parent);
            if (! $validation['valid']) {
                flash()->error($validation['message']);

                return redirect()->back()->withInput();
            }

            // Vérifier que pompes_funebres n'est activé que pour Commune ou Arrondissement
            if ($request->has('pompes_funebres') && $request->pompes_funebres) {
                if (! in_array($request->code_type_localite, ['TPLOC_0003', 'TPLOC_0004'])) {
                    flash()->error('Les pompes funèbres ne peuvent être activées que pour une Commune ou un Arrondissement');

                    return redirect()->back()->withInput();
                }
            }

            $localite = new Localite;
            $localite->code_localite = Sifec::genererCodeUniqueReferentiel($localite, 'code_localite', 4, [], 'LOC_');
            $localite->lib_localite = strtoupper($request->lib_localite);
            $localite->code_type_localite = $request->code_type_localite;
            $localite->code_localite_parent = $request->code_localite_parent ?: null;
            $localite->pompes_funebres = ($request->has('pompes_funebres') && in_array($request->code_type_localite, ['TPLOC_0003', 'TPLOC_0004'])) ? true : false;
            $localite->save();

            flash()->success("$localite->lib_localite créée avec succès");

            return redirect()->route('localite.index');

        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de la création de localité: '.$e->getMessage());
            flash()->error($e->getMessage());

            return redirect()->back()->withInput();
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
        $localite = Localite::find($id);

        if ($localite == null) {
            flash()->error('Impossible de charger cette page');

            return redirect()->back();
        }

        $request->validate([
            'code_type_localite' => ['required', 'string'],
            'lib_localite' => ['required', 'string', 'max:150'],
            'code_localite_parent' => ['nullable', 'string'],
            'pompes_funebres' => ['nullable', 'boolean'],
        ]);

        try {
            // Valider la hiérarchie
            $validation = $this->validateHierarchy($request->code_type_localite, $request->code_localite_parent);
            if (! $validation['valid']) {
                flash()->error($validation['message']);

                return redirect()->back()->withInput();
            }

            // Vérifier que la localité parent n'est pas elle-même ou un de ses descendants
            if ($request->code_localite_parent) {
                // descendants() inclut la localité elle-même
                $descendants = $localite->descendants()->pluck('code_localite')->toArray();
                if (in_array($request->code_localite_parent, $descendants)) {
                    flash()->error('Une localité ne peut pas être son propre parent ou avoir un de ses descendants comme parent');

                    return redirect()->back()->withInput();
                }
            }

            // Vérifier que pompes_funebres n'est activé que pour Commune ou Arrondissement
            $pompesFunebres = false;
            if ($request->has('pompes_funebres') && $request->pompes_funebres) {
                if (! in_array($request->code_type_localite, ['TPLOC_0003', 'TPLOC_0004'])) {
                    flash()->error('Les pompes funèbres ne peuvent être activées que pour une Commune ou un Arrondissement');

                    return redirect()->back()->withInput();
                }
                $pompesFunebres = true;
            }

            $localite->lib_localite = strtoupper($request->lib_localite);
            $localite->code_type_localite = $request->code_type_localite;
            $localite->code_localite_parent = $request->code_localite_parent ?: null;
            $localite->pompes_funebres = $pompesFunebres;
            $localite->save();

            flash()->success('Localité modifiée avec succès');

            return redirect()->route('localite.index');

        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de la modification de localité: '.$e->getMessage());
            flash()->error($e->getMessage());

            return redirect()->back()->withInput();
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
            Log::channel('sifec')->info('Tentative de suppression de la localité: '.$id);

            $localite = Localite::find($id);

            if ($localite == null) {
                Log::channel('sifec')->error('Localité non trouvée: '.$id);
                flash()->error('Impossible de charger cette page');

                return redirect()->back();
            }

            // Vérifier si la localité a des enfants directs
            $countEnfants = $localite->localitesEnfants()->count();
            if ($countEnfants > 0) {
                Log::channel('sifec')->warning('Impossible de supprimer la localité '.$id.' car elle a '.$countEnfants.' localité(s) enfant(s)');
                flash()->error('Impossible de supprimer cette localité car elle a des localités enfants');

                return redirect()->back();
            }

            // Vérifier si des institutions utilisent cette localité
            $countInstitutions = $localite->institutions()->count();
            if ($countInstitutions > 0) {
                Log::channel('sifec')->warning('Impossible de supprimer la localité '.$id.' car elle est utilisée par '.$countInstitutions.' institution(s)');
                flash()->error('Impossible de supprimer cette localité car elle est utilisée par des institutions');

                return redirect()->back();
            }

            // Vérifier si des personnes utilisent cette localité
            $countPersonnes = $localite->personnes()->count();
            if ($countPersonnes > 0) {
                Log::channel('sifec')->warning('Impossible de supprimer la localité '.$id.' car elle est utilisée par '.$countPersonnes.' personne(s)');
                flash()->error('Impossible de supprimer cette localité car elle est utilisée par des personnes');

                return redirect()->back();
            }

            // Suppression logique avec SoftDeletes
            $localite->delete();
            Log::channel('sifec')->info('Localité supprimée avec succès: '.$id);
            flash()->success('Suppression effectuée avec succès');

            return redirect()->route('localite.index');
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de la suppression de la localité '.$id.': '.$e->getMessage());
            Log::channel('sifec')->error('Stack trace: '.$e->getTraceAsString());
            flash()->error($e->getMessage());

            return redirect()->back();
        }
    }

    /**
     * Récupérer les types de parents autorisés pour un type de localité donné
     */
    private function getAuthorizedParentTypes($codeTypeLocalite)
    {
        // Mapping des types de localités vers leurs parents autorisés
        $hierarchy = [
            'TPLOC_0001' => [], // DEPARTEMENT - pas de parent
            'TPLOC_0002' => ['TPLOC_0001'], // DISTRICT - parent: DEPARTEMENT
            'TPLOC_0003' => ['TPLOC_0001'], // COMMUNE - parent: DEPARTEMENT
            'TPLOC_0004' => ['TPLOC_0003'], // ARRONDISSEMENT - parent: COMMUNE
            'TPLOC_0005' => ['TPLOC_0002'], // COMMUNAUTE URBAINE - parent: DISTRICT
            'TPLOC_0006' => ['TPLOC_0002'], // COMMUNAUTE RURALE - parent: DISTRICT
            'TPLOC_0007' => ['TPLOC_0003', 'TPLOC_0005', 'TPLOC_0006'], // QUARTIER - parent: COMMUNE, COMMUNAUTE URBAINE, COMMUNAUTE RURALE
            'TPLOC_0008' => ['TPLOC_0005'], // VILLAGE - parent: COMMUNAUTE URBAINE
        ];

        return $hierarchy[$codeTypeLocalite] ?? [];
    }

    /**
     * Valider la hiérarchie selon les règles métier
     */
    private function validateHierarchy($codeTypeLocalite, $codeLocaliteParent = null)
    {
        // Si pas de parent, vérifier que le type peut être racine
        if (! $codeLocaliteParent) {
            $authorizedTypes = $this->getAuthorizedParentTypes($codeTypeLocalite);
            // Seul le DEPARTEMENT peut être racine (pas de parent)
            if ($codeTypeLocalite !== 'TPLOC_0001') {
                return [
                    'valid' => false,
                    'message' => 'Ce type de localité doit avoir un parent. Seul le Département peut être une localité racine.',
                ];
            }

            return ['valid' => true];
        }

        // Vérifier que le parent existe
        $parent = Localite::find($codeLocaliteParent);
        if (! $parent) {
            return [
                'valid' => false,
                'message' => 'La localité parent sélectionnée n\'existe pas.',
            ];
        }

        // Vérifier que le type de parent est autorisé
        $authorizedTypes = $this->getAuthorizedParentTypes($codeTypeLocalite);
        if (! in_array($parent->code_type_localite, $authorizedTypes)) {
            $parentType = $parent->typelocalite ? $parent->typelocalite->lib_type_localite : 'inconnu';
            $currentType = TypeLocalite::find($codeTypeLocalite);
            $currentTypeName = $currentType ? $currentType->lib_type_localite : 'inconnu';

            return [
                'valid' => false,
                'message' => "Un(e) {$currentTypeName} ne peut pas avoir un(e) {$parentType} comme parent.",
            ];
        }

        return ['valid' => true];
    }

    /**
     * Récupérer les localités disponibles comme parent selon le type de localité
     */
    public function getAvailableParents(Request $request, $id = null)
    {
        $codeTypeLocalite = $request->input('type');
        $query = Localite::with('typelocalite');

        // Si un type de localité est fourni, filtrer selon la hiérarchie
        if ($codeTypeLocalite) {
            $authorizedTypes = $this->getAuthorizedParentTypes($codeTypeLocalite);
            if (! empty($authorizedTypes)) {
                $query->whereIn('code_type_localite', $authorizedTypes);
            } else {
                // Si aucun type autorisé, retourner un tableau vide
                return response()->json([]);
            }
        }

        // Exclure la localité courante et ses descendants
        if ($id) {
            $localite = Localite::find($id);
            if ($localite) {
                $descendants = $localite->descendants()->pluck('code_localite')->toArray();
                $query->whereNotIn('code_localite', $descendants);
            }
        }

        $localites = $query->orderBy('lib_localite')->get();

        return response()->json($localites);
    }

    /**
     * Récupérer les parents disponibles selon le type de localité (pour AJAX)
     */
    public function getAvailableParentsByType($codeTypeLocalite)
    {
        $authorizedTypes = $this->getAuthorizedParentTypes($codeTypeLocalite);

        if (empty($authorizedTypes)) {
            return response()->json([]);
        }

        $localites = Localite::with('typelocalite')
            ->whereIn('code_type_localite', $authorizedTypes)
            ->orderBy('lib_localite')
            ->get();

        return response()->json($localites);
    }
}
