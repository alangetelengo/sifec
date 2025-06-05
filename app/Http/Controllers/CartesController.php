<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\Departement;

class CartesController extends Controller
{
    /*  debut nationale  */
    public function cumuleNationale(){
        $cumuleNationale = DB::select("SELECT COUNT(*) AS TOTALNAISSANCE,
        (SELECT COUNT(*) FROM t_declaration_deces WHERE type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION')) AS TOTALDECES,
        (SELECT COUNT(*) FROM t_declaration_mariage) AS TOTALMARIAGE
        FROM t_declaration_naissance WHERE type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') ");
       return $cumuleNationale;
    }

    public function cumuleNationaleYear(){
       $cumuleNationaleYear = DB::select("SELECT COUNT(*) AS TOTALNAISSANCE,
       (SELECT COUNT(*) FROM t_declaration_deces WHERE type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION') AND  YEAR(t_declaration_deces.created_at) = YEAR(CURDATE())) AS TOTALDECES,
       (SELECT COUNT(*) FROM t_declaration_mariage WHERE YEAR(t_declaration_mariage.created_at) = YEAR(CURDATE())) AS TOTALMARIAGE
       FROM t_declaration_naissance WHERE type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') AND YEAR(t_declaration_naissance.created_at) = YEAR(CURDATE())");
       return $cumuleNationaleYear;
    }

     public function cumuleNationaleMonth(){
        $cumuleNationaleMonth = DB::select("SELECT COUNT(*) AS TOTALNAISSANCE,
        (SELECT COUNT(*) FROM t_declaration_deces WHERE type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION') AND MONTH(t_declaration_deces.created_at) = MONTH(CURDATE()) AND YEAR(t_declaration_deces.created_at) = YEAR(CURDATE())) AS TOTALDECES,
        (SELECT COUNT(*) FROM t_declaration_mariage WHERE MONTH(t_declaration_mariage.created_at) = MONTH(CURDATE()) AND YEAR(t_declaration_mariage.created_at) = YEAR(CURDATE())) AS TOTALMARIAGE
        FROM t_declaration_naissance WHERE type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') AND MONTH(t_declaration_naissance.created_at) = MONTH(CURDATE()) AND YEAR(t_declaration_naissance.created_at) = YEAR(CURDATE())");
        return $cumuleNationaleMonth;
    }

    public function cumuleNationaleWeek(){
       $cumuleNationaleWeekend = DB::select("SELECT COUNT(*) AS TOTALNAISSANCE,
       (SELECT COUNT(*) FROM t_declaration_deces WHERE type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION') AND  WEEK(t_declaration_deces.created_at) = WEEK(CURDATE()) AND YEAR(t_declaration_deces.created_at) = YEAR(CURDATE())) AS TOTALDECES,
       (SELECT COUNT(*) FROM t_declaration_mariage WHERE WEEK(t_declaration_mariage.created_at) = WEEK(CURDATE()) AND YEAR(t_declaration_mariage.created_at) = YEAR(CURDATE())) AS TOTALMARIAGE
       FROM t_declaration_naissance WHERE type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') AND WEEK(t_declaration_naissance.created_at) = WEEK(CURDATE()) AND YEAR(t_declaration_naissance.created_at) = YEAR(CURDATE())");
       return $cumuleNationaleWeekend;
    }

    public function cumuleNationaleDate(){
        $cumuleNationaleDate = DB::select("SELECT COUNT(*) AS TOTALNAISSANCE,
        (SELECT COUNT(*) FROM t_declaration_deces WHERE type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION') AND DATE(t_declaration_deces.created_at) = DATE(CURDATE())) AS TOTALDECES,
        (SELECT COUNT(*) FROM t_declaration_mariage WHERE DATE(t_declaration_mariage.created_at) = DATE(CURDATE())) AS TOTALMARIAGE
        FROM t_declaration_naissance WHERE type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') AND DATE(t_declaration_naissance.created_at) = DATE(CURDATE())");
        return $cumuleNationaleDate;
    }
    /* Fin cumule nationale */


     /*  Debut departement  */
     public function departementGet(Request $request){
        $departement = Departement::find($request->id);
        return response()->json(['details'=>$departement]);
    }

     public function cumuleDepartement(Request $request){


        $cumuleDepartement = DB::select("SELECT COUNT(*) AS TOTALNAISSANCE,
        (SELECT COUNT(*) FROM t_declaration_deces dd
        JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution
        JOIN tr_institution inst ON iu.code_institution = inst.code_institution
        JOIN tr_arrondissement arr ON inst.code_arrondissement = arr.code_arrondissement
        JOIN tr_commune com ON com.code_commune = arr.code_commune
        JOIN tr_departement dep ON com.code_departement = dep.code_departement
        WHERE dd.type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION') AND dep.code_departement = ?) AS TOTALDECES,

        (SELECT COUNT(*) FROM t_declaration_mariage dm
         JOIN tr_ins_user iu ON iu.cui = dm.cui
         JOIN tr_institution inst ON iu.code_institution = inst.code_institution
         JOIN tr_arrondissement arr ON inst.code_arrondissement = arr.code_arrondissement
         JOIN tr_commune com ON com.code_commune = arr.code_commune
         JOIN tr_departement dep ON com.code_departement = dep.code_departement
        WHERE dep.code_departement = ?) AS TOTALMARIAGE

        FROM t_declaration_naissance dn JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_arrondissement arr ON inst.code_arrondissement = arr.code_arrondissement JOIN tr_commune com ON com.code_commune = arr.code_commune JOIN tr_departement dep ON com.code_departement = dep.code_departement
        WHERE dn.type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') AND dep.code_departement = ?

        UNION ALL

        SELECT COUNT(*) AS TOTALNAISSANCE,
                (SELECT COUNT(*) FROM t_declaration_deces dd JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_communaute_urbaine comur ON inst.code_communaute_urbaine = comur.code_communaute_urbaine JOIN tr_district dist ON dist.code_district = comur.code_district JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dd.type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION') AND dep.code_departement = ?) AS TOTALDECES,

                (SELECT COUNT(*) FROM t_declaration_mariage dm
                JOIN tr_ins_user iu ON iu.cui = dm.cui
                JOIN tr_institution inst ON iu.code_institution = inst.code_institution
                JOIN tr_communaute_urbaine comur ON inst.code_communaute_urbaine = comur.code_communaute_urbaine
                JOIN tr_district dist ON dist.code_district = comur.code_district
                JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dep.code_departement = ?) AS TOTALMARIAGE

                FROM t_declaration_naissance dn JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_communaute_urbaine comur ON inst.code_communaute_urbaine = comur.code_communaute_urbaine JOIN tr_district dist ON dist.code_district = comur.code_district JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dn.type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') AND dep.code_departement = ?

        UNION ALL

        SELECT COUNT(*) AS TOTALNAISSANCE,
                (SELECT COUNT(*) FROM t_declaration_deces dd JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_commune com ON inst.code_commune = com.code_commune JOIN tr_departement dep ON com.code_departement = dep.code_departement
                WHERE dd.type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION') AND dep.code_departement = ?) AS TOTALDECES,

                (SELECT COUNT(*) FROM t_declaration_mariage dm
                JOIN tr_ins_user iu ON iu.cui = dm.cui
                JOIN tr_institution inst ON iu.code_institution = inst.code_institution
                JOIN tr_commune com ON inst.code_commune = com.code_commune
                JOIN tr_departement dep ON com.code_departement = dep.code_departement
                WHERE dep.code_departement = ?) AS TOTALMARIAGE

                FROM t_declaration_naissance dn JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_commune com ON inst.code_commune = com.code_commune JOIN tr_departement dep ON com.code_departement = dep.code_departement
                WHERE dn.type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') AND dep.code_departement = ?

        UNION ALL

        SELECT COUNT(*) AS TOTALNAISSANCE,
                (SELECT COUNT(*) FROM t_declaration_deces dd JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_district dist ON inst.code_district = dist.code_district JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dd.type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION') AND dep.code_departement = ?) AS TOTALDECES,

                (SELECT COUNT(*) FROM t_declaration_mariage dm
                JOIN tr_ins_user iu ON iu.cui = dm.cui
                JOIN tr_institution inst ON iu.code_institution = inst.code_institution
                JOIN tr_district dist ON inst.code_district = dist.code_district
                JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dep.code_departement = ?) AS TOTALMARIAGE

                FROM t_declaration_naissance dn JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_district dist ON inst.code_district = dist.code_district JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dn.type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') AND dep.code_departement = ?",[$request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id]);

        return $cumuleDepartement;
    }


    public function cumuleDepartementYear(Request $request){
        $cumuleDepartementYear = DB::select("SELECT COUNT(*) AS TOTALNAISSANCE,
        (SELECT COUNT(*) FROM t_declaration_deces dd JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_arrondissement arr ON inst.code_arrondissement = arr.code_arrondissement JOIN tr_commune com ON com.code_commune = arr.code_commune JOIN tr_departement dep ON com.code_departement = dep.code_departement
        WHERE dd.type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION') AND YEAR(dd.created_at) = YEAR(CURDATE()) AND dep.code_departement = ?) AS TOTALDECES,

        (SELECT COUNT(*) FROM t_declaration_mariage dm
         JOIN tr_ins_user iu ON iu.cui = dm.cui
         JOIN tr_institution inst ON iu.code_institution = inst.code_institution
         JOIN tr_arrondissement arr ON inst.code_arrondissement = arr.code_arrondissement
         JOIN tr_commune com ON com.code_commune = arr.code_commune
         JOIN tr_departement dep ON com.code_departement = dep.code_departement
        WHERE dep.code_departement = ? AND YEAR(dm.created_at) = YEAR(CURDATE())) AS TOTALMARIAGE

        FROM t_declaration_naissance dn JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_arrondissement arr ON inst.code_arrondissement = arr.code_arrondissement JOIN tr_commune com ON com.code_commune = arr.code_commune JOIN tr_departement dep ON com.code_departement = dep.code_departement
        WHERE dn.type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') AND YEAR(dn.created_at) = YEAR(CURDATE()) AND dep.code_departement = ?

        UNION ALL

        SELECT COUNT(*) AS TOTALNAISSANCE,
                (SELECT COUNT(*) FROM t_declaration_deces dd JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_communaute_urbaine comur ON inst.code_communaute_urbaine = comur.code_communaute_urbaine JOIN tr_district dist ON dist.code_district = comur.code_district JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dd.type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION') AND YEAR(dd.created_at) = YEAR(CURDATE()) AND dep.code_departement = ?) AS TOTALDECES,

                (SELECT COUNT(*) FROM t_declaration_mariage dm
                JOIN tr_ins_user iu ON iu.cui = dm.cui
                JOIN tr_institution inst ON iu.code_institution = inst.code_institution
                JOIN tr_communaute_urbaine comur ON inst.code_communaute_urbaine = comur.code_communaute_urbaine
                JOIN tr_district dist ON dist.code_district = comur.code_district
                JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dep.code_departement = ? AND YEAR(dm.created_at) = YEAR(CURDATE())) AS TOTALMARIAGE

                FROM t_declaration_naissance dn JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_communaute_urbaine comur ON inst.code_communaute_urbaine = comur.code_communaute_urbaine JOIN tr_district dist ON dist.code_district = comur.code_district JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dn.type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') AND YEAR(dn.created_at) = YEAR(CURDATE()) AND dep.code_departement = ?

        UNION ALL

        SELECT COUNT(*) AS TOTALNAISSANCE,
                (SELECT COUNT(*) FROM t_declaration_deces dd JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_commune com ON inst.code_commune = com.code_commune JOIN tr_departement dep ON com.code_departement = dep.code_departement
                WHERE dd.type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION') AND YEAR(dd.created_at) = YEAR(CURDATE()) AND dep.code_departement = ?) AS TOTALDECES,

                (SELECT COUNT(*) FROM t_declaration_mariage dm
                JOIN tr_ins_user iu ON iu.cui = dm.cui
                JOIN tr_institution inst ON iu.code_institution = inst.code_institution
                JOIN tr_commune com ON inst.code_commune = com.code_commune
                JOIN tr_departement dep ON com.code_departement = dep.code_departement
                WHERE dep.code_departement = ? AND YEAR(dm.created_at) = YEAR(CURDATE())) AS TOTALMARIAGE

                FROM t_declaration_naissance dn JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_commune com ON inst.code_commune = com.code_commune JOIN tr_departement dep ON com.code_departement = dep.code_departement
                WHERE dn.type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') AND YEAR(dn.created_at) = YEAR(CURDATE()) AND dep.code_departement = ?

        UNION ALL

        SELECT COUNT(*) AS TOTALNAISSANCE,
                (SELECT COUNT(*) FROM t_declaration_deces dd JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_district dist ON inst.code_district = dist.code_district JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dd.type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION') AND YEAR(dd.created_at) = YEAR(CURDATE()) AND dep.code_departement = ?) AS TOTALDECES,

                (SELECT COUNT(*) FROM t_declaration_mariage dm
                JOIN tr_ins_user iu ON iu.cui = dm.cui
                JOIN tr_institution inst ON iu.code_institution = inst.code_institution
                JOIN tr_district dist ON inst.code_district = dist.code_district
                JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dep.code_departement = ? AND YEAR(dm.created_at) = YEAR(CURDATE())) AS TOTALMARIAGE

                FROM t_declaration_naissance dn JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_district dist ON inst.code_district = dist.code_district JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dn.type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') AND YEAR(dn.created_at) = YEAR(CURDATE()) AND dep.code_departement = ?",[$request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id]);

        return $cumuleDepartementYear;
    }

    public function cumuleDepartementMonth(Request $request){
        $cumuleDepartementMonth = DB::select("SELECT COUNT(*) AS TOTALNAISSANCE,
        (SELECT COUNT(*) FROM t_declaration_deces dd JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_arrondissement arr ON inst.code_arrondissement = arr.code_arrondissement JOIN tr_commune com ON com.code_commune = arr.code_commune JOIN tr_departement dep ON com.code_departement = dep.code_departement
        WHERE dd.type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION') AND MONTH(dd.created_at) = MONTH(CURDATE()) AND YEAR(dd.created_at) = YEAR(CURDATE()) AND dep.code_departement = ?) AS TOTALDECES,

        (SELECT COUNT(*) FROM t_declaration_mariage dm
         JOIN tr_ins_user iu ON iu.cui = dm.cui
         JOIN tr_institution inst ON iu.code_institution = inst.code_institution
         JOIN tr_arrondissement arr ON inst.code_arrondissement = arr.code_arrondissement
         JOIN tr_commune com ON com.code_commune = arr.code_commune
         JOIN tr_departement dep ON com.code_departement = dep.code_departement
        WHERE dep.code_departement = ? AND MONTH(dm.created_at) = MONTH(CURDATE()) AND YEAR(dm.created_at) = YEAR(CURDATE())) AS TOTALMARIAGE

        FROM t_declaration_naissance dn JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_arrondissement arr ON inst.code_arrondissement = arr.code_arrondissement JOIN tr_commune com ON com.code_commune = arr.code_commune JOIN tr_departement dep ON com.code_departement = dep.code_departement
        WHERE dn.type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') AND MONTH(dn.created_at) = MONTH(CURDATE()) AND YEAR(dn.created_at) = YEAR(CURDATE()) AND dep.code_departement = ?

        UNION ALL

        SELECT COUNT(*) AS TOTALNAISSANCE,
                (SELECT COUNT(*) FROM t_declaration_deces dd JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_communaute_urbaine comur ON inst.code_communaute_urbaine = comur.code_communaute_urbaine JOIN tr_district dist ON dist.code_district = comur.code_district JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dd.type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION') AND MONTH(dd.created_at) = MONTH(CURDATE()) AND YEAR(dd.created_at) = YEAR(CURDATE()) AND dep.code_departement = ?) AS TOTALDECES,

                (SELECT COUNT(*) FROM t_declaration_mariage dm
                JOIN tr_ins_user iu ON iu.cui = dm.cui
                JOIN tr_institution inst ON iu.code_institution = inst.code_institution
                JOIN tr_communaute_urbaine comur ON inst.code_communaute_urbaine = comur.code_communaute_urbaine
                JOIN tr_district dist ON dist.code_district = comur.code_district
                JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dep.code_departement = ? AND MONTH(dm.created_at) = MONTH(CURDATE()) AND YEAR(dm.created_at) = YEAR(CURDATE())) AS TOTALMARIAGE

                FROM t_declaration_naissance dn JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_communaute_urbaine comur ON inst.code_communaute_urbaine = comur.code_communaute_urbaine JOIN tr_district dist ON dist.code_district = comur.code_district JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dn.type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') AND MONTH(dn.created_at) = MONTH(CURDATE()) AND YEAR(dn.created_at) = YEAR(CURDATE()) AND dep.code_departement = ?

        UNION ALL

        SELECT COUNT(*) AS TOTALNAISSANCE,
                (SELECT COUNT(*) FROM t_declaration_deces dd JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_commune com ON inst.code_commune = com.code_commune JOIN tr_departement dep ON com.code_departement = dep.code_departement
                WHERE dd.type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION') AND MONTH(dd.created_at) = MONTH(CURDATE()) AND YEAR(dd.created_at) = YEAR(CURDATE()) AND dep.code_departement = ?) AS TOTALDECES,

                (SELECT COUNT(*) FROM t_declaration_mariage dm
                JOIN tr_ins_user iu ON iu.cui = dm.cui
                JOIN tr_institution inst ON iu.code_institution = inst.code_institution
                JOIN tr_commune com ON inst.code_commune = com.code_commune
                JOIN tr_departement dep ON com.code_departement = dep.code_departement
                WHERE dep.code_departement = ? AND MONTH(dm.created_at) = MONTH(CURDATE()) AND YEAR(dm.created_at) = YEAR(CURDATE())) AS TOTALMARIAGE

                FROM t_declaration_naissance dn JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_commune com ON inst.code_commune = com.code_commune JOIN tr_departement dep ON com.code_departement = dep.code_departement
                WHERE dn.type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') AND MONTH(dn.created_at) = MONTH(CURDATE()) AND YEAR(dn.created_at) = YEAR(CURDATE()) AND dep.code_departement = ?

        UNION ALL

        SELECT COUNT(*) AS TOTALNAISSANCE,
                (SELECT COUNT(*) FROM t_declaration_deces dd JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_district dist ON inst.code_district = dist.code_district JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dd.type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION') AND MONTH(dd.created_at) = MONTH(CURDATE()) AND YEAR(dd.created_at) = YEAR(CURDATE()) AND dep.code_departement = ?) AS TOTALDECES,

                (SELECT COUNT(*) FROM t_declaration_mariage dm
                JOIN tr_ins_user iu ON iu.cui = dm.cui
                JOIN tr_institution inst ON iu.code_institution = inst.code_institution
                JOIN tr_district dist ON inst.code_district = dist.code_district
                JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dep.code_departement = ? AND MONTH(dm.created_at) = MONTH(CURDATE()) AND YEAR(dm.created_at) = YEAR(CURDATE())) AS TOTALMARIAGE

                FROM t_declaration_naissance dn JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_district dist ON inst.code_district = dist.code_district JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dn.type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') AND MONTH(dn.created_at) = MONTH(CURDATE()) AND YEAR(dn.created_at) = YEAR(CURDATE()) AND dep.code_departement = ?",[$request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id]);

        return $cumuleDepartementMonth;
    }


    public function cumuleDepartementWeek(Request $request){
        $cumuleDepartementWeek = DB::select("SELECT COUNT(*) AS TOTALNAISSANCE,
        (SELECT COUNT(*) FROM t_declaration_deces dd JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_arrondissement arr ON inst.code_arrondissement = arr.code_arrondissement JOIN tr_commune com ON com.code_commune = arr.code_commune JOIN tr_departement dep ON com.code_departement = dep.code_departement
        WHERE dd.type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION') AND WEEK(dd.created_at) = WEEK(CURDATE()) AND YEAR(dd.created_at) = YEAR(CURDATE()) AND dep.code_departement = ?) AS TOTALDECES,

        (SELECT COUNT(*) FROM t_declaration_mariage dm
         JOIN tr_ins_user iu ON iu.cui = dm.cui
         JOIN tr_institution inst ON iu.code_institution = inst.code_institution
         JOIN tr_arrondissement arr ON inst.code_arrondissement = arr.code_arrondissement
         JOIN tr_commune com ON com.code_commune = arr.code_commune
         JOIN tr_departement dep ON com.code_departement = dep.code_departement
        WHERE dep.code_departement = ? AND WEEK(dm.created_at) = WEEK(CURDATE()) AND YEAR(dm.created_at) = YEAR(CURDATE())) AS TOTALMARIAGE

        FROM t_declaration_naissance dn JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_arrondissement arr ON inst.code_arrondissement = arr.code_arrondissement JOIN tr_commune com ON com.code_commune = arr.code_commune JOIN tr_departement dep ON com.code_departement = dep.code_departement
        WHERE dn.type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') AND WEEK(dn.created_at) = WEEK(CURDATE()) AND YEAR(dn.created_at) = YEAR(CURDATE()) AND dep.code_departement = ?

        UNION ALL

        SELECT COUNT(*) AS TOTALNAISSANCE,
                (SELECT COUNT(*) FROM t_declaration_deces dd JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_communaute_urbaine comur ON inst.code_communaute_urbaine = comur.code_communaute_urbaine JOIN tr_district dist ON dist.code_district = comur.code_district JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dd.type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION') AND WEEK(dd.created_at) = WEEK(CURDATE()) AND YEAR(dd.created_at) = YEAR(CURDATE()) AND dep.code_departement = ?) AS TOTALDECES,

                (SELECT COUNT(*) FROM t_declaration_mariage dm
                JOIN tr_ins_user iu ON iu.cui = dm.cui
                JOIN tr_institution inst ON iu.code_institution = inst.code_institution
                JOIN tr_communaute_urbaine comur ON inst.code_communaute_urbaine = comur.code_communaute_urbaine
                JOIN tr_district dist ON dist.code_district = comur.code_district
                JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dep.code_departement = ? AND WEEK(dm.created_at) = WEEK(CURDATE()) AND YEAR(dm.created_at) = YEAR(CURDATE())) AS TOTALMARIAGE

                FROM t_declaration_naissance dn JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_communaute_urbaine comur ON inst.code_communaute_urbaine = comur.code_communaute_urbaine JOIN tr_district dist ON dist.code_district = comur.code_district JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dn.type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') AND WEEK(dn.created_at) = WEEK(CURDATE()) AND YEAR(dn.created_at) = YEAR(CURDATE()) AND dep.code_departement = ?

        UNION ALL

        SELECT COUNT(*) AS TOTALNAISSANCE,
                (SELECT COUNT(*) FROM t_declaration_deces dd JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_commune com ON inst.code_commune = com.code_commune JOIN tr_departement dep ON com.code_departement = dep.code_departement
                WHERE dd.type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION') AND WEEK(dd.created_at) = WEEK(CURDATE()) AND YEAR(dd.created_at) = YEAR(CURDATE()) AND dep.code_departement = ?) AS TOTALDECES,

                (SELECT COUNT(*) FROM t_declaration_mariage dm
                JOIN tr_ins_user iu ON iu.cui = dm.cui
                JOIN tr_institution inst ON iu.code_institution = inst.code_institution
                JOIN tr_commune com ON inst.code_commune = com.code_commune
                JOIN tr_departement dep ON com.code_departement = dep.code_departement
                WHERE dep.code_departement = ? AND WEEK(dm.created_at) = WEEK(CURDATE()) AND YEAR(dm.created_at) = YEAR(CURDATE())) AS TOTALMARIAGE

                FROM t_declaration_naissance dn JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_commune com ON inst.code_commune = com.code_commune JOIN tr_departement dep ON com.code_departement = dep.code_departement
                WHERE dn.type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') AND WEEK(dn.created_at) = WEEK(CURDATE()) AND YEAR(dn.created_at) = YEAR(CURDATE()) AND dep.code_departement = ?

        UNION ALL

        SELECT COUNT(*) AS TOTALNAISSANCE,
                (SELECT COUNT(*) FROM t_declaration_deces dd JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_district dist ON inst.code_district = dist.code_district JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dd.type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION') AND WEEK(dd.created_at) = WEEK(CURDATE()) AND YEAR(dd.created_at) = YEAR(CURDATE()) AND dep.code_departement = ?) AS TOTALDECES,

                (SELECT COUNT(*) FROM t_declaration_mariage dm
                JOIN tr_ins_user iu ON iu.cui = dm.cui
                JOIN tr_institution inst ON iu.code_institution = inst.code_institution
                JOIN tr_district dist ON inst.code_district = dist.code_district
                JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dep.code_departement = ? AND WEEK(dm.created_at) = WEEK(CURDATE()) AND YEAR(dm.created_at) = YEAR(CURDATE())) AS TOTALMARIAGE

                FROM t_declaration_naissance dn JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_district dist ON inst.code_district = dist.code_district JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dn.type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') AND WEEK(dn.created_at) = WEEK(CURDATE()) AND YEAR(dn.created_at) = YEAR(CURDATE()) AND dep.code_departement = ?",[$request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id]);

        return $cumuleDepartementWeek;
    }


    public function cumuleDepartementDate(Request $request){
        $cumuleDepartementDate = DB::select("SELECT COUNT(*) AS TOTALNAISSANCE,
        (SELECT COUNT(*) FROM t_declaration_deces dd JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_arrondissement arr ON inst.code_arrondissement = arr.code_arrondissement JOIN tr_commune com ON com.code_commune = arr.code_commune JOIN tr_departement dep ON com.code_departement = dep.code_departement
        WHERE dd.type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION') AND DATE(dd.created_at) = DATE(CURDATE()) AND dep.code_departement = ?) AS TOTALDECES,

        (SELECT COUNT(*) FROM t_declaration_mariage dm
         JOIN tr_ins_user iu ON iu.cui = dm.cui
         JOIN tr_institution inst ON iu.code_institution = inst.code_institution
         JOIN tr_arrondissement arr ON inst.code_arrondissement = arr.code_arrondissement
         JOIN tr_commune com ON com.code_commune = arr.code_commune
         JOIN tr_departement dep ON com.code_departement = dep.code_departement
        WHERE dep.code_departement = ? AND DATE(dm.created_at) = DATE(CURDATE())) AS TOTALMARIAGE

        FROM t_declaration_naissance dn JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_arrondissement arr ON inst.code_arrondissement = arr.code_arrondissement JOIN tr_commune com ON com.code_commune = arr.code_commune JOIN tr_departement dep ON com.code_departement = dep.code_departement
        WHERE dn.type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') AND DATE(dn.created_at) = DATE(CURDATE()) AND dep.code_departement = ?

        UNION ALL

        SELECT COUNT(*) AS TOTALNAISSANCE,
                (SELECT COUNT(*) FROM t_declaration_deces dd JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_communaute_urbaine comur ON inst.code_communaute_urbaine = comur.code_communaute_urbaine JOIN tr_district dist ON dist.code_district = comur.code_district JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dd.type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION') AND DATE(dd.created_at) = DATE(CURDATE()) AND dep.code_departement = ?) AS TOTALDECES,

                (SELECT COUNT(*) FROM t_declaration_mariage dm
                JOIN tr_ins_user iu ON iu.cui = dm.cui
                JOIN tr_institution inst ON iu.code_institution = inst.code_institution
                JOIN tr_communaute_urbaine comur ON inst.code_communaute_urbaine = comur.code_communaute_urbaine
                JOIN tr_district dist ON dist.code_district = comur.code_district
                JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dep.code_departement = ? AND DATE(dm.created_at) = DATE(CURDATE())) AS TOTALMARIAGE

                FROM t_declaration_naissance dn JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_communaute_urbaine comur ON inst.code_communaute_urbaine = comur.code_communaute_urbaine JOIN tr_district dist ON dist.code_district = comur.code_district JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dn.type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') AND DATE(dn.created_at) = DATE(CURDATE()) AND dep.code_departement = ?

        UNION ALL

        SELECT COUNT(*) AS TOTALNAISSANCE,
                (SELECT COUNT(*) FROM t_declaration_deces dd JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_commune com ON inst.code_commune = com.code_commune JOIN tr_departement dep ON com.code_departement = dep.code_departement
                WHERE dd.type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION') AND DATE(dd.created_at) = DATE(CURDATE()) AND dep.code_departement = ?) AS TOTALDECES,

                (SELECT COUNT(*) FROM t_declaration_mariage dm
                JOIN tr_ins_user iu ON iu.cui = dm.cui
                JOIN tr_institution inst ON iu.code_institution = inst.code_institution
                JOIN tr_commune com ON inst.code_commune = com.code_commune
                JOIN tr_departement dep ON com.code_departement = dep.code_departement
                WHERE dep.code_departement = ? AND DATE(dm.created_at) = DATE(CURDATE())) AS TOTALMARIAGE

                FROM t_declaration_naissance dn JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_commune com ON inst.code_commune = com.code_commune JOIN tr_departement dep ON com.code_departement = dep.code_departement
                WHERE dn.type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') AND DATE(dn.created_at) = DATE(CURDATE()) AND dep.code_departement = ?

        UNION ALL

        SELECT COUNT(*) AS TOTALNAISSANCE,
                (SELECT COUNT(*) FROM t_declaration_deces dd JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_district dist ON inst.code_district = dist.code_district JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dd.type_declaration IN('DECLARATION DE DECES','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION') AND DATE(dd.created_at) = DATE(CURDATE()) AND dep.code_departement = ?) AS TOTALDECES,

                (SELECT COUNT(*) FROM t_declaration_mariage dm
                JOIN tr_ins_user iu ON iu.cui = dm.cui
                JOIN tr_institution inst ON iu.code_institution = inst.code_institution
                JOIN tr_district dist ON inst.code_district = dist.code_district
                JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dep.code_departement = ? AND DATE(dm.created_at) = DATE(CURDATE())) AS TOTALMARIAGE

                FROM t_declaration_naissance dn JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution JOIN tr_institution inst ON iu.code_institution = inst.code_institution JOIN tr_district dist ON inst.code_district = dist.code_district JOIN tr_departement dep ON dist.code_departement = dep.code_departement
                WHERE dn.type_declaration IN('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION') AND DATE(dn.created_at) = DATE(CURDATE()) AND dep.code_departement = ?",[$request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id, $request->id]);

        return $cumuleDepartementDate;
    }

    /* Fin cumule departement */

}
