<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BanController extends Controller
{
    /**
     * Expression SQL du département (alignée sur la grille journal BAN).
     */
    protected function departementSqlExpression(): string
    {
        return "COALESCE(
            CASE WHEN l0.code_type_localite = 'TPLOC_0001' THEN l0.lib_localite END,
            CASE WHEN l1.code_type_localite = 'TPLOC_0001' THEN l1.lib_localite END,
            CASE WHEN l2.code_type_localite = 'TPLOC_0001' THEN l2.lib_localite END,
            CASE WHEN l3.code_type_localite = 'TPLOC_0001' THEN l3.lib_localite END,
            ''
        )";
    }

    /**
     * Requête de base : déclarations de mariage sans acte publiée.
     */
    protected function baseJournalSansActeQuery()
    {
        return DB::table('t_declaration_mariage as d')
            ->leftJoin('t_acte_mariage as a', 'a.code_declaration_mariage', '=', 'd.code_declaration_mariage')
            ->leftJoin('tr_identification_personne as epoux', 'epoux.code_personne', '=', 'd.code_epoux')
            ->leftJoin('tr_identification_personne as epouse', 'epouse.code_personne', '=', 'd.code_epouse')
            ->leftJoin('tr_institution as ins', 'ins.code_institution', '=', 'd.code_institution')
            ->leftJoin('tr_localite as l0', 'l0.code_localite', '=', 'ins.code_localite')
            ->leftJoin('tr_localite as l1', 'l1.code_localite', '=', 'l0.code_localite_parent')
            ->leftJoin('tr_localite as l2', 'l2.code_localite', '=', 'l1.code_localite_parent')
            ->leftJoin('tr_localite as l3', 'l3.code_localite', '=', 'l2.code_localite_parent')
            ->whereNull('a.code_acte_mariage')
            ->whereNull('d.deleted_at');
    }

    protected function selectJournalColumns(): array
    {
        $depExpr = $this->departementSqlExpression();

        return [
            'd.code_declaration_mariage as NumeroDeclaration',
            DB::raw("TRIM(CONCAT(COALESCE(epoux.nom, ''), ' ', COALESCE(epoux.prenom, ''))) as NomEpoux"),
            DB::raw("TRIM(CONCAT(COALESCE(epouse.nom, ''), ' ', COALESCE(epouse.prenom, ''))) as NomEpouse"),
            'd.date_prevue_mariage as DateCelebration',
            'd.lieu_ceremonie_mariage as LieuCelebration',
            DB::raw("{$depExpr} as Departement"),
            'ins.lib_institution as Institution',
        ];
    }

    /**
     * Filtres serveur (public ou authentifié).
     */
    protected function applyJournalFilters($query, Request $request): void
    {
        if ($request->filled('institution')) {
            $term = $request->input('institution');
            $query->where('ins.lib_institution', 'like', '%'.addcslashes($term, '%_\\').'%');
        }

        if ($request->filled('departement')) {
            $term = $request->input('departement');
            $depExpr = $this->departementSqlExpression();
            $query->whereRaw("({$depExpr}) LIKE ?", ['%'.$term.'%']);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('d.date_prevue_mariage', '>=', $request->date('date_debut'));
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('d.date_prevue_mariage', '<=', $request->date('date_fin'));
        }
    }

    /**
     * Départements (référentiel) : localités de type département.
     *
     * @return list<string>
     */
    protected function referentielDepartementLibs(): array
    {
        return DB::table('tr_localite')
            ->where('code_type_localite', 'TPLOC_0001')
            ->orderBy('lib_localite')
            ->limit(500)
            ->pluck('lib_localite')
            ->map(static fn ($v) => trim((string) $v))
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    /**
     * Centres / structures (même périmètre que listeCec) pour alimenter les filtres même sans ligne BAN.
     *
     * @return list<string>
     */
    protected function referentielInstitutionLibs(): array
    {
        $codeInstitutionMairie = 'TPINS_0002';
        $codeInstitutionAmbassade = 'TPINS_0005';

        $sql = '(select i.lib_institution as lib from tr_institution i inner join tr_type_institution ti on ti.code_type_institution = i.code_type_institution where (i.code_type_institution = ? or i.code_type_institution = ?)) '
            ."union (select concat('COMMUNAUTE URBAINE - ', lib_communaute_urbaine) as lib from tr_communaute_urbaine) "
            .'union (select concat(\'DISTRICT - \', lib_district) as lib from tr_district)';

        $rows = DB::select($sql, [$codeInstitutionAmbassade, $codeInstitutionMairie]);

        return collect($rows)
            ->pluck('lib')
            ->map(static fn ($v) => trim((string) $v))
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    /**
     * Jointure institution → hiérarchie de localités (même logique que le journal BAN).
     */
    protected function institutionWithDepartementJoin()
    {
        return DB::table('tr_institution as i')
            ->leftJoin('tr_localite as l0', 'l0.code_localite', '=', 'i.code_localite')
            ->leftJoin('tr_localite as l1', 'l1.code_localite', '=', 'l0.code_localite_parent')
            ->leftJoin('tr_localite as l2', 'l2.code_localite', '=', 'l1.code_localite_parent')
            ->leftJoin('tr_localite as l3', 'l3.code_localite', '=', 'l2.code_localite_parent');
    }

    /**
     * Codes département (tr_departement ou nœud TPLOC_0001) à partir du libellé affiché dans les facettes.
     *
     * @return list<string>
     */
    protected function resolveDepartementCodesFromLib(string $libDepartement): array
    {
        $norm = mb_strtolower(trim($libDepartement));
        if ($norm === '') {
            return [];
        }

        $fromDep = DB::table('tr_departement')
            ->whereRaw('LOWER(TRIM(lib_departement)) = ?', [$norm])
            ->pluck('code_departement')
            ->all();

        if (count($fromDep) > 0) {
            return $fromDep;
        }

        return DB::table('tr_localite')
            ->where('code_type_localite', 'TPLOC_0001')
            ->whereNull('deleted_at')
            ->whereRaw('LOWER(TRIM(lib_localite)) = ?', [$norm])
            ->pluck('code_localite')
            ->all();
    }

    /**
     * Institutions du référentiel (mairies, ambassades, CU, districts) rattachées au département choisi.
     *
     * @return list<string>
     */
    protected function referentielInstitutionLibsForDepartement(string $libDepartement): array
    {
        $codeInstitutionMairie = 'TPINS_0002';
        $codeInstitutionAmbassade = 'TPINS_0005';
        $depExpr = $this->departementSqlExpression();
        $norm = mb_strtolower(trim($libDepartement));

        $fromInst = $this->institutionWithDepartementJoin()
            ->whereIn('i.code_type_institution', [$codeInstitutionAmbassade, $codeInstitutionMairie])
            ->whereNull('i.deleted_at')
            ->whereRaw("LOWER(TRIM({$depExpr})) = ?", [$norm])
            ->orderBy('i.lib_institution')
            ->limit(500)
            ->pluck('i.lib_institution');

        $codesDep = $this->resolveDepartementCodesFromLib($libDepartement);
        $fromCu = collect();
        $fromDist = collect();
        if (count($codesDep) > 0) {
            $fromDist = DB::table('tr_district')
                ->whereNull('deleted_at')
                ->whereIn('code_departement', $codesDep)
                ->orderBy('lib_district')
                ->limit(200)
                ->pluck(DB::raw("CONCAT('DISTRICT - ', lib_district)"));
            $fromCu = DB::table('tr_communaute_urbaine as cu')
                ->join('tr_district as d', 'd.code_district', '=', 'cu.code_district')
                ->whereNull('cu.deleted_at')
                ->whereNull('d.deleted_at')
                ->whereIn('d.code_departement', $codesDep)
                ->orderBy('cu.lib_communaute_urbaine')
                ->limit(200)
                ->pluck(DB::raw("CONCAT('COMMUNAUTE URBAINE - ', cu.lib_communaute_urbaine)"));
        }

        return collect($fromInst)
            ->merge($fromCu)
            ->merge($fromDist)
            ->map(static fn ($v) => trim((string) $v))
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->take(500)
            ->all();
    }

    /**
     * Institutions présentes dans le journal BAN pour un département donné.
     *
     * @return list<string>
     */
    protected function instFromBanForDepartement(string $libDepartement): array
    {
        $depExpr = $this->departementSqlExpression();
        $norm = mb_strtolower(trim($libDepartement));
        $base = $this->baseJournalSansActeQuery();

        return (clone $base)
            ->whereRaw("LOWER(TRIM({$depExpr})) = ?", [$norm])
            ->whereNotNull('ins.lib_institution')
            ->where('ins.lib_institution', '!=', '')
            ->orderBy('ins.lib_institution')
            ->limit(500)
            ->pluck('ins.lib_institution')
            ->map(static fn ($v) => trim((string) $v))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @param  list<string>  $a
     * @param  list<string>  $b
     * @return list<string>
     */
    protected function mergeInstitutionFacetLists(array $a, array $b): array
    {
        return collect($a)
            ->merge($b)
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->take(500)
            ->all();
    }

    /**
     * Facettes complètes (sans filtre département sur les institutions), mise en cache.
     *
     * @return array{departements: array<int, string>, institutions: array<int, string>}
     */
    protected function buildJournalFacetsUnfiltered(): array
    {
        $depExpr = $this->departementSqlExpression();
        $base = $this->baseJournalSansActeQuery();

        $depFromBan = (clone $base)
            ->selectRaw("{$depExpr} as __v")
            ->whereRaw("({$depExpr}) <> ''")
            ->orderBy('__v')
            ->limit(500)
            ->pluck('__v')
            ->map(static fn ($v) => trim((string) $v))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $instFromBan = (clone $base)
            ->whereNotNull('ins.lib_institution')
            ->where('ins.lib_institution', '!=', '')
            ->orderBy('ins.lib_institution')
            ->limit(500)
            ->pluck('ins.lib_institution')
            ->map(static fn ($v) => trim((string) $v))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $departements = collect($depFromBan)
            ->merge($this->referentielDepartementLibs())
            ->unique()
            ->sort()
            ->values()
            ->take(500)
            ->all();

        $institutions = collect($instFromBan)
            ->merge($this->referentielInstitutionLibs())
            ->unique()
            ->sort()
            ->values()
            ->take(500)
            ->all();

        return [
            'departements' => $departements,
            'institutions' => $institutions,
        ];
    }

    /**
     * Listes pour filtres : valeurs présentes dans le journal BAN + référentiel.
     * Si $facetDepartementLib est renseigné, la liste des institutions est restreinte à ce département.
     *
     * @return array{departements: array<int, string>, institutions: array<int, string>}
     */
    protected function journalFacets(?string $facetDepartementLib = null): array
    {
        $cached = Cache::remember('api_v1_ban_mariage_public_facets_v4', 300, function () {
            return $this->buildJournalFacetsUnfiltered();
        });

        $facetDepartementLib = $facetDepartementLib !== null ? trim($facetDepartementLib) : '';
        if ($facetDepartementLib === '') {
            return $cached;
        }

        return [
            'departements' => $cached['departements'],
            'institutions' => $this->mergeInstitutionFacetLists(
                $this->instFromBanForDepartement($facetDepartementLib),
                $this->referentielInstitutionLibsForDepartement($facetDepartementLib),
            ),
        ];
    }

    /**
     * Retourne les declarations de mariage qui n'ont pas encore d'acte.
     * (Agents : auth API + scope + can:module.acteMariage)
     */
    public function journalMariagesSansActe(Request $request): JsonResponse
    {
        $query = $this->baseJournalSansActeQuery();
        $this->applyJournalFilters($query, $request);

        $rows = $query
            ->orderByDesc('d.date_prevue_mariage')
            ->select($this->selectJournalColumns())
            ->get();

        return response()->json([
            'data' => $rows,
            'total' => $rows->count(),
        ]);
    }

    /**
     * Journal BAN — consultation publique (identités conformes publication légale).
     * Pagination, filtres, facettes, limitation de débit (middleware throttle).
     */
    public function journalMariagesSansActePublic(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:50'],
            'departement' => ['sometimes', 'nullable', 'string', 'max:200'],
            'institution' => ['sometimes', 'nullable', 'string', 'max:255'],
            'facet_departement' => ['sometimes', 'nullable', 'string', 'max:200'],
            'date_debut' => ['sometimes', 'nullable', 'date'],
            'date_fin' => ['sometimes', 'nullable', 'date', 'after_or_equal:date_debut'],
            'include_facets' => ['sometimes', 'boolean'],
        ]);

        $perPage = (int) ($validated['per_page'] ?? 20);
        $perPage = min(50, max(1, $perPage));
        $page = max(1, (int) ($validated['page'] ?? 1));

        $query = $this->baseJournalSansActeQuery();
        $this->applyJournalFilters($query, $request);

        $total = (clone $query)->distinct('d.code_declaration_mariage')->count('d.code_declaration_mariage');

        $rows = (clone $query)
            ->orderByDesc('d.date_prevue_mariage')
            ->select($this->selectJournalColumns())
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        $payload = [
            'data' => $rows,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => max(1, (int) ceil($total / $perPage)),
        ];

        if (filter_var($request->query('include_facets', false), FILTER_VALIDATE_BOOLEAN)) {
            $facetLib = trim((string) ($validated['facet_departement'] ?? ''));
            $payload['facets'] = $this->journalFacets($facetLib !== '' ? $facetLib : null);
        }

        return response()->json($payload);
    }
}
