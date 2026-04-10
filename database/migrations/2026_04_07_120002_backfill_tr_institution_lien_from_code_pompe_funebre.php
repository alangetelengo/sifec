<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BackfillTrInstitutionLienFromCodePompeFunebre extends Migration
{
    private const CODE_TYPE = 'TPLIEN_0001';

    public function up(): void
    {
        if (!Schema::hasTable('tr_institution_lien')) {
            return;
        }

        $rows = DB::table('tr_institution')
            ->whereNotNull('code_pompe_funebre')
            ->whereColumn('code_institution', '!=', 'code_pompe_funebre')
            ->get(['code_institution', 'code_pompe_funebre']);

        $now = now();

        foreach ($rows as $row) {
            $exists = DB::table('tr_institution_lien')
                ->where('code_institution_source', $row->code_institution)
                ->where('code_institution_cible', $row->code_pompe_funebre)
                ->where('code_type_lien', self::CODE_TYPE)
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('tr_institution_lien')->insert([
                'code_institution_source' => $row->code_institution,
                'code_institution_cible' => $row->code_pompe_funebre,
                'code_type_lien' => self::CODE_TYPE,
                'date_debut' => null,
                'date_fin' => null,
                'commentaire' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('tr_institution_lien')) {
            return;
        }

        DB::table('tr_institution_lien')
            ->where('code_type_lien', self::CODE_TYPE)
            ->delete();
    }
}
