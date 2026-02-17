<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\Departement;

class CartesController extends Controller
{
    /*  debut nationale - comptage des ACTES établis (t_acte_*), pas des déclarations */
    public function cumuleNationale(){
        $cumuleNationale = DB::select("SELECT
        (SELECT COUNT(*) FROM t_acte_naissance WHERE deleted_at IS NULL) AS TOTALNAISSANCE,
        (SELECT COUNT(*) FROM t_acte_deces WHERE deleted_at IS NULL) AS TOTALDECES,
        (SELECT COUNT(*) FROM t_acte_mariage WHERE deleted_at IS NULL) AS TOTALMARIAGE,
        0 AS TOTALDIVORCE");
       return $cumuleNationale;
    }

    public function cumuleNationaleYear(){
       $cumuleNationaleYear = DB::select("SELECT
       (SELECT COUNT(*) FROM t_acte_naissance WHERE deleted_at IS NULL AND YEAR(created_at) = YEAR(CURDATE())) AS TOTALNAISSANCE,
       (SELECT COUNT(*) FROM t_acte_deces WHERE deleted_at IS NULL AND YEAR(created_at) = YEAR(CURDATE())) AS TOTALDECES,
       (SELECT COUNT(*) FROM t_acte_mariage WHERE deleted_at IS NULL AND YEAR(created_at) = YEAR(CURDATE())) AS TOTALMARIAGE,
       0 AS TOTALDIVORCE");
       return $cumuleNationaleYear;
    }

     public function cumuleNationaleMonth(){
        $cumuleNationaleMonth = DB::select("SELECT
        (SELECT COUNT(*) FROM t_acte_naissance WHERE deleted_at IS NULL AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())) AS TOTALNAISSANCE,
        (SELECT COUNT(*) FROM t_acte_deces WHERE deleted_at IS NULL AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())) AS TOTALDECES,
        (SELECT COUNT(*) FROM t_acte_mariage WHERE deleted_at IS NULL AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())) AS TOTALMARIAGE,
        0 AS TOTALDIVORCE");
        return $cumuleNationaleMonth;
    }

    public function cumuleNationaleWeek(){
       $cumuleNationaleWeekend = DB::select("SELECT
       (SELECT COUNT(*) FROM t_acte_naissance WHERE deleted_at IS NULL AND WEEK(created_at) = WEEK(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())) AS TOTALNAISSANCE,
       (SELECT COUNT(*) FROM t_acte_deces WHERE deleted_at IS NULL AND WEEK(created_at) = WEEK(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())) AS TOTALDECES,
       (SELECT COUNT(*) FROM t_acte_mariage WHERE deleted_at IS NULL AND WEEK(created_at) = WEEK(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())) AS TOTALMARIAGE,
       0 AS TOTALDIVORCE");
       return $cumuleNationaleWeekend;
    }

    public function cumuleNationaleDate(){
        $cumuleNationaleDate = DB::select("SELECT
        (SELECT COUNT(*) FROM t_acte_naissance WHERE deleted_at IS NULL AND DATE(created_at) = DATE(CURDATE())) AS TOTALNAISSANCE,
        (SELECT COUNT(*) FROM t_acte_deces WHERE deleted_at IS NULL AND DATE(created_at) = DATE(CURDATE())) AS TOTALDECES,
        (SELECT COUNT(*) FROM t_acte_mariage WHERE deleted_at IS NULL AND DATE(created_at) = DATE(CURDATE())) AS TOTALMARIAGE,
        0 AS TOTALDIVORCE");
        return $cumuleNationaleDate;
    }
    /* Fin cumule nationale */


     /*  Debut departement  */
     public function departementGet(Request $request){
        $departement = Departement::find($request->id);
        return response()->json(['details'=>$departement]);
    }

    /**
     * Retourne les code_localite des nœuds département (type TPLOC_0001) correspondant au code_departement.
     * Plusieurs nœuds peuvent représenter le même département (ex. DPT_0001 et LOC_BZ pour Brazzaville).
     */
    private function getRootLocaliteCodesByDepartement(string $codeDepartement): array
    {
        $dep = Departement::find($codeDepartement);
        if (!$dep) {
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

    public function cumuleDepartement(Request $request){
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


    public function cumuleDepartementYear(Request $request){
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

    public function cumuleDepartementMonth(Request $request){
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


    public function cumuleDepartementWeek(Request $request){
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


    public function cumuleDepartementDate(Request $request){
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

}
