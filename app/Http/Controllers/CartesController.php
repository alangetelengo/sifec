<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Naissance\Entities\Declarationnaissance;
use Modules\Referentiel\Entities\Departement;

class CartesController extends Controller
{
    /*  debut nationale - comptage des ACTES établis (t_acte_*), pas des déclarations */
    public function cumuleNationale()
    {
        $cumuleNationale = DB::select('SELECT
        (SELECT COUNT(*) FROM t_acte_naissance WHERE deleted_at IS NULL) AS TOTALNAISSANCE,
        (SELECT COUNT(*) FROM t_acte_deces WHERE deleted_at IS NULL) AS TOTALDECES,
        (SELECT COUNT(*) FROM t_acte_mariage WHERE deleted_at IS NULL) AS TOTALMARIAGE,
        0 AS TOTALDIVORCE');

        return $cumuleNationale;
    }

    public function cumuleNationaleYear()
    {
        $cumuleNationaleYear = DB::select('SELECT
       (SELECT COUNT(*) FROM t_acte_naissance WHERE deleted_at IS NULL AND YEAR(created_at) = YEAR(CURDATE())) AS TOTALNAISSANCE,
       (SELECT COUNT(*) FROM t_acte_deces WHERE deleted_at IS NULL AND YEAR(created_at) = YEAR(CURDATE())) AS TOTALDECES,
       (SELECT COUNT(*) FROM t_acte_mariage WHERE deleted_at IS NULL AND YEAR(created_at) = YEAR(CURDATE())) AS TOTALMARIAGE,
       0 AS TOTALDIVORCE');

        return $cumuleNationaleYear;
    }

    public function cumuleNationaleMonth()
    {
        $cumuleNationaleMonth = DB::select('SELECT
        (SELECT COUNT(*) FROM t_acte_naissance WHERE deleted_at IS NULL AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())) AS TOTALNAISSANCE,
        (SELECT COUNT(*) FROM t_acte_deces WHERE deleted_at IS NULL AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())) AS TOTALDECES,
        (SELECT COUNT(*) FROM t_acte_mariage WHERE deleted_at IS NULL AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())) AS TOTALMARIAGE,
        0 AS TOTALDIVORCE');

        return $cumuleNationaleMonth;
    }

    public function cumuleNationaleWeek()
    {
        $cumuleNationaleWeekend = DB::select('SELECT
       (SELECT COUNT(*) FROM t_acte_naissance WHERE deleted_at IS NULL AND WEEK(created_at) = WEEK(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())) AS TOTALNAISSANCE,
       (SELECT COUNT(*) FROM t_acte_deces WHERE deleted_at IS NULL AND WEEK(created_at) = WEEK(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())) AS TOTALDECES,
       (SELECT COUNT(*) FROM t_acte_mariage WHERE deleted_at IS NULL AND WEEK(created_at) = WEEK(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())) AS TOTALMARIAGE,
       0 AS TOTALDIVORCE');

        return $cumuleNationaleWeekend;
    }

    public function cumuleNationaleDate()
    {
        $cumuleNationaleDate = DB::select('SELECT
        (SELECT COUNT(*) FROM t_acte_naissance WHERE deleted_at IS NULL AND DATE(created_at) = DATE(CURDATE())) AS TOTALNAISSANCE,
        (SELECT COUNT(*) FROM t_acte_deces WHERE deleted_at IS NULL AND DATE(created_at) = DATE(CURDATE())) AS TOTALDECES,
        (SELECT COUNT(*) FROM t_acte_mariage WHERE deleted_at IS NULL AND DATE(created_at) = DATE(CURDATE())) AS TOTALMARIAGE,
        0 AS TOTALDIVORCE');

        return $cumuleNationaleDate;
    }
    /* Fin cumule nationale */

    /*  Debut departement */
    public function departementGet(Request $request)
    {
        $departement = Departement::find($request->id);

        return response()->json(['details' => $departement]);
    }

    /**
     * Retourne les code_localite des nœuds département (type TPLOC_0001) correspondant au code_departement.
     * Plusieurs nœuds peuvent représenter le même département (ex. DPT_0001 et LOC_BZ pour Brazzaville).
     */
    private function getRootLocaliteCodesByDepartement(string $codeDepartement): array
    {
        $dep = Departement::find($codeDepartement);
        if (! $dep) {
            return [];
        }
        $libNorm = strtolower(trim($dep->lib_departement ?? ''));
        $codes = DB::table('tr_localite')
            ->where('code_type_localite', 'TPLOC_0001')
            ->where(function ($q) use ($codeDepartement, $libNorm) {
                $q->where('code_localite', $codeDepartement)
                    ->orWhereRaw('LOWER(TRIM(lib_localite)) = ?', [$libNorm]);
            })
            ->pluck('code_localite')
            ->unique()
            ->values()
            ->all();

        return $codes ?: [$codeDepartement];
    }

    /**
     * Retourne les code_institution dont la localité appartient au département (code_departement).
     * Hiérarchie tr_localite : chaque ligne a code_localite et code_localite_parent (remontée vers le département).
     * La CTE remonte de chaque nœud vers son parent jusqu'au nœud type TPLOC_0001 (département).
     */
    private function getInstitutionCodesByDepartement(string $codeDepartement): array
    {
        $rootCodes = $this->getRootLocaliteCodesByDepartement($codeDepartement);
        if (empty($rootCodes)) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($rootCodes), '?'));
        $sql = "
        WITH RECURSIVE anc AS (
            SELECT code_localite AS node, code_localite AS root, code_type_localite
            FROM tr_localite
            UNION ALL
            SELECT anc.node, parent.code_localite, parent.code_type_localite
            FROM anc
            JOIN tr_localite cur ON cur.code_localite = anc.root AND cur.code_localite_parent IS NOT NULL
            JOIN tr_localite parent ON parent.code_localite = cur.code_localite_parent
        ),
        dep_localites AS (
            SELECT node FROM anc WHERE code_type_localite = 'TPLOC_0001' AND root IN ($placeholders)
        )
        SELECT inst.code_institution
        FROM tr_institution inst
        WHERE inst.code_localite IN (SELECT node FROM dep_localites)
        ";
        $rows = DB::select($sql, $rootCodes);

        return array_values(array_unique(array_column($rows, 'code_institution')));
    }

    public function cumuleDepartement(Request $request)
    {
        $codesInstitution = $this->getInstitutionCodesByDepartement($request->id);
        if (empty($codesInstitution)) {
            return [['TOTALNAISSANCE' => 0, 'TOTALDECES' => 0, 'TOTALMARIAGE' => 0, 'TOTALDIVORCE' => 0]];
        }
        $placeholders = implode(',', array_fill(0, count($codesInstitution), '?'));

        $sql = "
        SELECT
            (SELECT COUNT(*) FROM t_acte_naissance an
             LEFT JOIN t_declaration_naissance dn ON an.code_declaration_naissance = dn.code_declaration_naissance
             LEFT JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution
             WHERE an.deleted_at IS NULL
             AND (an.code_institution IN ($placeholders) OR (an.code_institution IS NULL AND iu.code_institution IN ($placeholders)))) AS TOTALNAISSANCE,
            (SELECT COUNT(*) FROM t_acte_deces ad
             LEFT JOIN t_declaration_deces dd ON ad.code_declaration_deces = dd.code_declaration_deces
             LEFT JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution
             WHERE ad.deleted_at IS NULL
             AND (ad.code_institution IN ($placeholders) OR (ad.code_institution IS NULL AND iu.code_institution IN ($placeholders)))) AS TOTALDECES,
            (SELECT COUNT(*) FROM t_acte_mariage am
             LEFT JOIN t_declaration_mariage dm ON am.code_declaration_mariage = dm.code_declaration_mariage
             WHERE am.deleted_at IS NULL
             AND (am.code_institution IN ($placeholders) OR (am.code_institution IS NULL AND dm.code_institution IN ($placeholders)))) AS TOTALMARIAGE,
            0 AS TOTALDIVORCE
        ";
        $params = array_merge($codesInstitution, $codesInstitution, $codesInstitution, $codesInstitution, $codesInstitution, $codesInstitution);
        $cumuleDepartement = DB::select($sql, $params);

        return $cumuleDepartement;
    }

    public function cumuleDepartementYear(Request $request)
    {
        $codesInstitution = $this->getInstitutionCodesByDepartement($request->id);
        if (empty($codesInstitution)) {
            return [['TOTALNAISSANCE' => 0, 'TOTALDECES' => 0, 'TOTALMARIAGE' => 0, 'TOTALDIVORCE' => 0]];
        }
        $placeholders = implode(',', array_fill(0, count($codesInstitution), '?'));
        $sql = "
        SELECT
            (SELECT COUNT(*) FROM t_acte_naissance an
             LEFT JOIN t_declaration_naissance dn ON an.code_declaration_naissance = dn.code_declaration_naissance
             LEFT JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution
             WHERE an.deleted_at IS NULL AND YEAR(an.created_at) = YEAR(CURDATE())
             AND (an.code_institution IN ($placeholders) OR (an.code_institution IS NULL AND iu.code_institution IN ($placeholders)))) AS TOTALNAISSANCE,
            (SELECT COUNT(*) FROM t_acte_deces ad
             LEFT JOIN t_declaration_deces dd ON ad.code_declaration_deces = dd.code_declaration_deces
             LEFT JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution
             WHERE ad.deleted_at IS NULL AND YEAR(ad.created_at) = YEAR(CURDATE())
             AND (ad.code_institution IN ($placeholders) OR (ad.code_institution IS NULL AND iu.code_institution IN ($placeholders)))) AS TOTALDECES,
            (SELECT COUNT(*) FROM t_acte_mariage am
             LEFT JOIN t_declaration_mariage dm ON am.code_declaration_mariage = dm.code_declaration_mariage
             WHERE am.deleted_at IS NULL AND YEAR(am.created_at) = YEAR(CURDATE())
             AND (am.code_institution IN ($placeholders) OR (am.code_institution IS NULL AND dm.code_institution IN ($placeholders)))) AS TOTALMARIAGE,
            0 AS TOTALDIVORCE
        ";
        $params = array_merge($codesInstitution, $codesInstitution, $codesInstitution, $codesInstitution, $codesInstitution, $codesInstitution);

        return DB::select($sql, $params);
    }

    public function cumuleDepartementMonth(Request $request)
    {
        $codesInstitution = $this->getInstitutionCodesByDepartement($request->id);
        if (empty($codesInstitution)) {
            return [['TOTALNAISSANCE' => 0, 'TOTALDECES' => 0, 'TOTALMARIAGE' => 0, 'TOTALDIVORCE' => 0]];
        }
        $placeholders = implode(',', array_fill(0, count($codesInstitution), '?'));
        $sql = "
        SELECT
            (SELECT COUNT(*) FROM t_acte_naissance an
             LEFT JOIN t_declaration_naissance dn ON an.code_declaration_naissance = dn.code_declaration_naissance
             LEFT JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution
             WHERE an.deleted_at IS NULL AND MONTH(an.created_at) = MONTH(CURDATE()) AND YEAR(an.created_at) = YEAR(CURDATE())
             AND (an.code_institution IN ($placeholders) OR (an.code_institution IS NULL AND iu.code_institution IN ($placeholders)))) AS TOTALNAISSANCE,
            (SELECT COUNT(*) FROM t_acte_deces ad
             LEFT JOIN t_declaration_deces dd ON ad.code_declaration_deces = dd.code_declaration_deces
             LEFT JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution
             WHERE ad.deleted_at IS NULL AND MONTH(ad.created_at) = MONTH(CURDATE()) AND YEAR(ad.created_at) = YEAR(CURDATE())
             AND (ad.code_institution IN ($placeholders) OR (ad.code_institution IS NULL AND iu.code_institution IN ($placeholders)))) AS TOTALDECES,
            (SELECT COUNT(*) FROM t_acte_mariage am
             LEFT JOIN t_declaration_mariage dm ON am.code_declaration_mariage = dm.code_declaration_mariage
             WHERE am.deleted_at IS NULL AND MONTH(am.created_at) = MONTH(CURDATE()) AND YEAR(am.created_at) = YEAR(CURDATE())
             AND (am.code_institution IN ($placeholders) OR (am.code_institution IS NULL AND dm.code_institution IN ($placeholders)))) AS TOTALMARIAGE,
            0 AS TOTALDIVORCE
        ";
        $params = array_merge($codesInstitution, $codesInstitution, $codesInstitution, $codesInstitution, $codesInstitution, $codesInstitution);

        return DB::select($sql, $params);
    }

    public function cumuleDepartementWeek(Request $request)
    {
        $codesInstitution = $this->getInstitutionCodesByDepartement($request->id);
        if (empty($codesInstitution)) {
            return [['TOTALNAISSANCE' => 0, 'TOTALDECES' => 0, 'TOTALMARIAGE' => 0, 'TOTALDIVORCE' => 0]];
        }
        $placeholders = implode(',', array_fill(0, count($codesInstitution), '?'));
        $sql = "
        SELECT
            (SELECT COUNT(*) FROM t_acte_naissance an
             LEFT JOIN t_declaration_naissance dn ON an.code_declaration_naissance = dn.code_declaration_naissance
             LEFT JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution
             WHERE an.deleted_at IS NULL AND WEEK(an.created_at) = WEEK(CURDATE()) AND YEAR(an.created_at) = YEAR(CURDATE())
             AND (an.code_institution IN ($placeholders) OR (an.code_institution IS NULL AND iu.code_institution IN ($placeholders)))) AS TOTALNAISSANCE,
            (SELECT COUNT(*) FROM t_acte_deces ad
             LEFT JOIN t_declaration_deces dd ON ad.code_declaration_deces = dd.code_declaration_deces
             LEFT JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution
             WHERE ad.deleted_at IS NULL AND WEEK(ad.created_at) = WEEK(CURDATE()) AND YEAR(ad.created_at) = YEAR(CURDATE())
             AND (ad.code_institution IN ($placeholders) OR (ad.code_institution IS NULL AND iu.code_institution IN ($placeholders)))) AS TOTALDECES,
            (SELECT COUNT(*) FROM t_acte_mariage am
             LEFT JOIN t_declaration_mariage dm ON am.code_declaration_mariage = dm.code_declaration_mariage
             WHERE am.deleted_at IS NULL AND WEEK(am.created_at) = WEEK(CURDATE()) AND YEAR(am.created_at) = YEAR(CURDATE())
             AND (am.code_institution IN ($placeholders) OR (am.code_institution IS NULL AND dm.code_institution IN ($placeholders)))) AS TOTALMARIAGE,
            0 AS TOTALDIVORCE
        ";
        $params = array_merge($codesInstitution, $codesInstitution, $codesInstitution, $codesInstitution, $codesInstitution, $codesInstitution);

        return DB::select($sql, $params);
    }

    public function cumuleDepartementDate(Request $request)
    {
        $codesInstitution = $this->getInstitutionCodesByDepartement($request->id);
        if (empty($codesInstitution)) {
            return [['TOTALNAISSANCE' => 0, 'TOTALDECES' => 0, 'TOTALMARIAGE' => 0, 'TOTALDIVORCE' => 0]];
        }
        $placeholders = implode(',', array_fill(0, count($codesInstitution), '?'));
        $sql = "
        SELECT
            (SELECT COUNT(*) FROM t_acte_naissance an
             LEFT JOIN t_declaration_naissance dn ON an.code_declaration_naissance = dn.code_declaration_naissance
             LEFT JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution
             WHERE an.deleted_at IS NULL AND DATE(an.created_at) = DATE(CURDATE())
             AND (an.code_institution IN ($placeholders) OR (an.code_institution IS NULL AND iu.code_institution IN ($placeholders)))) AS TOTALNAISSANCE,
            (SELECT COUNT(*) FROM t_acte_deces ad
             LEFT JOIN t_declaration_deces dd ON ad.code_declaration_deces = dd.code_declaration_deces
             LEFT JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution
             WHERE ad.deleted_at IS NULL AND DATE(ad.created_at) = DATE(CURDATE())
             AND (ad.code_institution IN ($placeholders) OR (ad.code_institution IS NULL AND iu.code_institution IN ($placeholders)))) AS TOTALDECES,
            (SELECT COUNT(*) FROM t_acte_mariage am
             LEFT JOIN t_declaration_mariage dm ON am.code_declaration_mariage = dm.code_declaration_mariage
             WHERE am.deleted_at IS NULL AND DATE(am.created_at) = DATE(CURDATE())
             AND (am.code_institution IN ($placeholders) OR (am.code_institution IS NULL AND dm.code_institution IN ($placeholders)))) AS TOTALMARIAGE,
            0 AS TOTALDIVORCE
        ";
        $params = array_merge($codesInstitution, $codesInstitution, $codesInstitution, $codesInstitution, $codesInstitution, $codesInstitution);

        return DB::select($sql, $params);
    }

    /* Fin cumule departement */

    /**
     * @return array{debut: string, fin: string}
     */
    private function validatePeriode(Request $request): array
    {
        $v = $request->validate([
            'debut' => 'required|date',
            'fin' => 'required|date|after_or_equal:debut',
        ]);

        return [
            'debut' => Carbon::parse($v['debut'])->format('Y-m-d'),
            'fin' => Carbon::parse($v['fin'])->format('Y-m-d'),
        ];
    }

    /**
     * Comptages nationaux (actes) avec condition SQL identique sur created_at (tables sans alias).
     *
     * @param  array<int, string>  $bindings
     */
    private function nationalActeRow(string $extraSql, array $bindings): object
    {
        $sql = "
        SELECT
            (SELECT COUNT(*) FROM t_acte_naissance WHERE deleted_at IS NULL {$extraSql}) AS TOTALNAISSANCE,
            (SELECT COUNT(*) FROM t_acte_deces WHERE deleted_at IS NULL {$extraSql}) AS TOTALDECES,
            (SELECT COUNT(*) FROM t_acte_mariage WHERE deleted_at IS NULL {$extraSql}) AS TOTALMARIAGE,
            0 AS TOTALDIVORCE
        ";

        $tripleBindings = array_merge($bindings, $bindings, $bindings);
        $row = DB::selectOne($sql, $tripleBindings);
        if (! $row) {
            return (object) [
                'TOTALNAISSANCE' => 0,
                'TOTALDECES' => 0,
                'TOTALMARIAGE' => 0,
                'TOTALDIVORCE' => 0,
            ];
        }

        return $row;
    }

    /**
     * Synthèse nationale sur une période : mêmes « situations » que l’écran carte, croisées avec [debut, fin].
     */
    public function syntheseNationalePeriode(Request $request)
    {
        $p = $this->validatePeriode($request);
        $d1 = $p['debut'];
        $d2 = $p['fin'];

        $between = 'AND DATE(created_at) BETWEEN ? AND ?';
        $b2 = [$d1, $d2];

        return response()->json([
            'debut' => $d1,
            'fin' => $d2,
            'cumule' => $this->nationalActeRow($between, $b2),
            'annee' => $this->nationalActeRow(
                $between.' AND YEAR(created_at) = YEAR(CURDATE())',
                $b2
            ),
            'mois' => $this->nationalActeRow(
                $between.' AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())',
                $b2
            ),
            'semaine' => $this->nationalActeRow(
                $between.' AND WEEK(created_at) = WEEK(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())',
                $b2
            ),
            'jour' => $this->nationalActeRow(
                $between.' AND DATE(created_at) = DATE(CURDATE())',
                $b2
            ),
        ]);
    }

    /**
     * Comptages par département (actes) avec filtres sur an.created_at / ad.created_at / am.created_at.
     *
     * @param  array<int, string>  $codesInstitution
     * @param  array<int, mixed>  $params  ordre : 6× codes puis d1,d2 pour chaque sous-requête
     */
    private function departementActeRow(
        array $codesInstitution,
        string $tailN,
        string $tailD,
        string $tailM,
        array $params
    ): object {
        $placeholders = implode(',', array_fill(0, count($codesInstitution), '?'));
        $sql = "
        SELECT
            (SELECT COUNT(*) FROM t_acte_naissance an
             LEFT JOIN t_declaration_naissance dn ON an.code_declaration_naissance = dn.code_declaration_naissance
             LEFT JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution
             WHERE an.deleted_at IS NULL
             AND (an.code_institution IN ($placeholders) OR (an.code_institution IS NULL AND iu.code_institution IN ($placeholders)))
             {$tailN}) AS TOTALNAISSANCE,
            (SELECT COUNT(*) FROM t_acte_deces ad
             LEFT JOIN t_declaration_deces dd ON ad.code_declaration_deces = dd.code_declaration_deces
             LEFT JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution
             WHERE ad.deleted_at IS NULL
             AND (ad.code_institution IN ($placeholders) OR (ad.code_institution IS NULL AND iu.code_institution IN ($placeholders)))
             {$tailD}) AS TOTALDECES,
            (SELECT COUNT(*) FROM t_acte_mariage am
             LEFT JOIN t_declaration_mariage dm ON am.code_declaration_mariage = dm.code_declaration_mariage
             WHERE am.deleted_at IS NULL
             AND (am.code_institution IN ($placeholders) OR (am.code_institution IS NULL AND dm.code_institution IN ($placeholders)))
             {$tailM}) AS TOTALMARIAGE,
            0 AS TOTALDIVORCE
        ";

        $row = DB::selectOne($sql, $params);
        if (! $row) {
            return (object) [
                'TOTALNAISSANCE' => 0,
                'TOTALDECES' => 0,
                'TOTALMARIAGE' => 0,
                'TOTALDIVORCE' => 0,
            ];
        }

        return $row;
    }

    private function departementParamsWithDates(array $codesInstitution, string $d1, string $d2): array
    {
        $six = array_merge(
            $codesInstitution,
            $codesInstitution,
            $codesInstitution,
            $codesInstitution,
            $codesInstitution,
            $codesInstitution
        );

        return array_merge($six, [$d1, $d2], [$d1, $d2], [$d1, $d2]);
    }

    public function syntheseDepartementPeriode(Request $request)
    {
        $v = $request->validate([
            'id' => 'required|string',
            'debut' => 'required|date',
            'fin' => 'required|date|after_or_equal:debut',
        ]);
        $d1 = Carbon::parse($v['debut'])->format('Y-m-d');
        $d2 = Carbon::parse($v['fin'])->format('Y-m-d');
        $codes = $this->getInstitutionCodesByDepartement($v['id']);
        if ($codes === []) {
            $zero = (object) [
                'TOTALNAISSANCE' => 0,
                'TOTALDECES' => 0,
                'TOTALMARIAGE' => 0,
                'TOTALDIVORCE' => 0,
            ];

            return response()->json([
                'debut' => $d1,
                'fin' => $d2,
                'cumule' => $zero,
                'annee' => $zero,
                'mois' => $zero,
                'semaine' => $zero,
                'jour' => $zero,
            ]);
        }

        $tBetween = 'AND DATE(an.created_at) BETWEEN ? AND ?';
        $tBetweenD = 'AND DATE(ad.created_at) BETWEEN ? AND ?';
        $tBetweenM = 'AND DATE(am.created_at) BETWEEN ? AND ?';
        $params = $this->departementParamsWithDates($codes, $d1, $d2);

        return response()->json([
            'debut' => $d1,
            'fin' => $d2,
            'cumule' => $this->departementActeRow($codes, $tBetween, $tBetweenD, $tBetweenM, $params),
            'annee' => $this->departementActeRow(
                $codes,
                $tBetween.' AND YEAR(an.created_at) = YEAR(CURDATE())',
                $tBetweenD.' AND YEAR(ad.created_at) = YEAR(CURDATE())',
                $tBetweenM.' AND YEAR(am.created_at) = YEAR(CURDATE())',
                $params
            ),
            'mois' => $this->departementActeRow(
                $codes,
                $tBetween.' AND MONTH(an.created_at) = MONTH(CURDATE()) AND YEAR(an.created_at) = YEAR(CURDATE())',
                $tBetweenD.' AND MONTH(ad.created_at) = MONTH(CURDATE()) AND YEAR(ad.created_at) = YEAR(CURDATE())',
                $tBetweenM.' AND MONTH(am.created_at) = MONTH(CURDATE()) AND YEAR(am.created_at) = YEAR(CURDATE())',
                $params
            ),
            'semaine' => $this->departementActeRow(
                $codes,
                $tBetween.' AND WEEK(an.created_at) = WEEK(CURDATE()) AND YEAR(an.created_at) = YEAR(CURDATE())',
                $tBetweenD.' AND WEEK(ad.created_at) = WEEK(CURDATE()) AND YEAR(ad.created_at) = YEAR(CURDATE())',
                $tBetweenM.' AND WEEK(am.created_at) = WEEK(CURDATE()) AND YEAR(am.created_at) = YEAR(CURDATE())',
                $params
            ),
            'jour' => $this->departementActeRow(
                $codes,
                $tBetween.' AND DATE(an.created_at) = DATE(CURDATE())',
                $tBetweenD.' AND DATE(ad.created_at) = DATE(CURDATE())',
                $tBetweenM.' AND DATE(am.created_at) = DATE(CURDATE())',
                $params
            ),
        ]);
    }

    /**
     * Série journalière des actes sur le territoire national (somme des départements = filtre date uniquement).
     */
    public function serieNationaleJournaliere(Request $request)
    {
        $p = $this->validatePeriode($request);
        $start = Carbon::parse($p['debut'])->startOfDay();
        $end = Carbon::parse($p['fin'])->endOfDay();
        if ($start->diffInDays($end) > 366) {
            return response()->json(['message' => 'La période ne peut excéder 366 jours.'], 422);
        }

        $nais = DB::table('t_acte_naissance')
            ->whereNull('deleted_at')
            ->whereBetween(DB::raw('DATE(created_at)'), [$p['debut'], $p['fin']])
            ->selectRaw('DATE(created_at) as jour, COUNT(*) as c')
            ->groupBy('jour')
            ->pluck('c', 'jour');

        $deces = DB::table('t_acte_deces')
            ->whereNull('deleted_at')
            ->whereBetween(DB::raw('DATE(created_at)'), [$p['debut'], $p['fin']])
            ->selectRaw('DATE(created_at) as jour, COUNT(*) as c')
            ->groupBy('jour')
            ->pluck('c', 'jour');

        $mariages = DB::table('t_acte_mariage')
            ->whereNull('deleted_at')
            ->whereBetween(DB::raw('DATE(created_at)'), [$p['debut'], $p['fin']])
            ->selectRaw('DATE(created_at) as jour, COUNT(*) as c')
            ->groupBy('jour')
            ->pluck('c', 'jour');

        $serie = [];
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $key = $d->format('Y-m-d');
            $serie[] = [
                'jour' => $key,
                'naissance' => (int) ($nais[$key] ?? 0),
                'deces' => (int) ($deces[$key] ?? 0),
                'mariage' => (int) ($mariages[$key] ?? 0),
            ];
        }

        return response()->json(['debut' => $p['debut'], 'fin' => $p['fin'], 'points' => $serie]);
    }

    /**
     * Certificats de transcription (hors territoire) sur la période.
     */
    public function transcriptionsHorsTerritoire(Request $request)
    {
        $p = $this->validatePeriode($request);
        $d1 = $p['debut'].' 00:00:00';
        $d2 = $p['fin'].' 23:59:59';

        $baseN = Declarationnaissance::query()->where('type_declaration', 'CERTIFICAT DE TRANSCRIPTION');
        $baseD = DeclarationDeces::query()->where('type_declaration', 'CERTIFICAT DE TRANSCRIPTION');

        $naisPeriode = (clone $baseN)->whereBetween('created_at', [$d1, $d2])->count();
        $naisAnnee = (clone $baseN)->whereBetween('created_at', [$d1, $d2])
            ->whereYear('created_at', now()->year)
            ->count();
        $naisMois = (clone $baseN)->whereBetween('created_at', [$d1, $d2])
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        $decesDateExpr = 'DATE(COALESCE(date_heure_declaration, created_at))';

        $decesPeriode = (clone $baseD)
            ->whereRaw("{$decesDateExpr} BETWEEN ? AND ?", [$p['debut'], $p['fin']])
            ->count();

        $decesAnnee = (clone $baseD)
            ->whereRaw("{$decesDateExpr} BETWEEN ? AND ?", [$p['debut'], $p['fin']])
            ->whereYear(DB::raw('COALESCE(date_heure_declaration, created_at)'), now()->year)
            ->count();

        $decesMois = (clone $baseD)
            ->whereRaw("{$decesDateExpr} BETWEEN ? AND ?", [$p['debut'], $p['fin']])
            ->whereYear(DB::raw('COALESCE(date_heure_declaration, created_at)'), now()->year)
            ->whereMonth(DB::raw('COALESCE(date_heure_declaration, created_at)'), now()->month)
            ->count();

        return response()->json([
            'debut' => $p['debut'],
            'fin' => $p['fin'],
            'naissance' => ['cumul' => $naisPeriode, 'annee' => $naisAnnee, 'mois' => $naisMois],
            'deces' => ['cumul' => $decesPeriode, 'annee' => $decesAnnee, 'mois' => $decesMois],
        ]);
    }

    /**
     * Données agrégées pour export PDF (même logique que les endpoints JSON).
     *
     * @return array<string, mixed>
     */
    private function buildCartePdfPayload(string $debut, string $fin, string $departementId): array
    {
        $natReq = Request::create('/cartes/periode/synthese-nationale', 'GET', ['debut' => $debut, 'fin' => $fin]);
        $nat = json_decode($this->syntheseNationalePeriode($natReq)->getContent(), true) ?: [];

        $depReq = Request::create('/cartes/periode/synthese-departement', 'POST', [
            'id' => $departementId,
            'debut' => $debut,
            'fin' => $fin,
        ]);
        $dep = json_decode($this->syntheseDepartementPeriode($depReq)->getContent(), true) ?: [];

        $trReq = Request::create('/cartes/periode/transcriptions', 'GET', ['debut' => $debut, 'fin' => $fin]);
        $trans = json_decode($this->transcriptionsHorsTerritoire($trReq)->getContent(), true) ?: [];

        $depLib = Departement::find($departementId)?->lib_departement ?? $departementId;

        return [
            'debut' => $debut,
            'fin' => $fin,
            'departement_id' => $departementId,
            'departement_lib' => $depLib,
            'national' => $nat,
            'departement' => $dep,
            'transcriptions' => $trans,
        ];
    }

    public function exportCartePdf(Request $request)
    {
        $v = $request->validate([
            'debut' => 'required|date',
            'fin' => 'required|date|after_or_equal:debut',
            'departement_id' => 'required|string',
        ]);
        $debut = Carbon::parse($v['debut'])->format('Y-m-d');
        $fin = Carbon::parse($v['fin'])->format('Y-m-d');
        $payload = $this->buildCartePdfPayload($debut, $fin, $v['departement_id']);

        $pdf = DomPDF::loadView('admin.dashboard.carte-pdf', $payload);
        $pdf->setPaper('A4', 'portrait');

        $fname = 'carte-du-congo_'.$debut.'_'.$fin.'.pdf';

        return $pdf->download($fname);
    }
}
