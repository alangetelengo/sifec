<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class BanController extends Controller
{
    /**
     * Retourne les declarations de mariage qui n'ont pas encore d'acte.
     */
    public function journalMariagesSansActe(): JsonResponse
    {
        $rows = DB::table('t_declaration_mariage as d')
            ->leftJoin('t_acte_mariage as a', 'a.code_declaration_mariage', '=', 'd.code_declaration_mariage')
            ->leftJoin('tr_identification_personne as epoux', 'epoux.code_personne', '=', 'd.code_epoux')
            ->leftJoin('tr_identification_personne as epouse', 'epouse.code_personne', '=', 'd.code_epouse')
            ->leftJoin('tr_institution as ins', 'ins.code_institution', '=', 'd.code_institution')
            ->leftJoin('tr_localite as l0', 'l0.code_localite', '=', 'ins.code_localite')
            ->leftJoin('tr_localite as l1', 'l1.code_localite', '=', 'l0.code_localite_parent')
            ->leftJoin('tr_localite as l2', 'l2.code_localite', '=', 'l1.code_localite_parent')
            ->leftJoin('tr_localite as l3', 'l3.code_localite', '=', 'l2.code_localite_parent')
            ->whereNull('a.code_acte_mariage')
            ->whereNull('d.deleted_at')
            ->orderByDesc('d.date_prevue_mariage')
            ->select([
                'd.code_declaration_mariage as NumeroDeclaration',
                DB::raw("TRIM(CONCAT(COALESCE(epoux.nom, ''), ' ', COALESCE(epoux.prenom, ''))) as NomEpoux"),
                DB::raw("TRIM(CONCAT(COALESCE(epouse.nom, ''), ' ', COALESCE(epouse.prenom, ''))) as NomEpouse"),
                'd.date_prevue_mariage as DateCelebration',
                'd.lieu_ceremonie_mariage as LieuCelebration',
                DB::raw("COALESCE(
                    CASE WHEN l0.code_type_localite = 'TPLOC_0001' THEN l0.lib_localite END,
                    CASE WHEN l1.code_type_localite = 'TPLOC_0001' THEN l1.lib_localite END,
                    CASE WHEN l2.code_type_localite = 'TPLOC_0001' THEN l2.lib_localite END,
                    CASE WHEN l3.code_type_localite = 'TPLOC_0001' THEN l3.lib_localite END,
                    ''
                ) as Departement"),
                'ins.lib_institution as Institution',
            ])
            ->get();

        return response()->json([
            'data' => $rows,
            'total' => $rows->count(),
        ]);
    }
}
