<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class RestructureTActeNaissancePrimaryUuid extends Migration
{
    public function up(): void
    {
        Schema::table('t_acte_naissance', function (Blueprint $table) {
            $table->uuid('code_acte_naissance')->nullable()->after('niupp');
        });

        DB::table('t_acte_naissance')->orderBy('niupp')->chunk(200, function ($rows) {
            foreach ($rows as $row) {
                DB::table('t_acte_naissance')->where('niupp', $row->niupp)->update([
                    'code_acte_naissance' => (string) Str::uuid(),
                ]);
            }
        });

        DB::statement('ALTER TABLE t_acte_naissance DROP PRIMARY KEY');
        DB::statement('ALTER TABLE t_acte_naissance MODIFY niupp VARCHAR(50) NULL');
        DB::statement('ALTER TABLE t_acte_naissance MODIFY code_acte_naissance CHAR(36) NOT NULL');

        Schema::table('t_acte_naissance', function (Blueprint $table) {
            $table->primary('code_acte_naissance');
            $table->unique('niupp');
        });
    }

    public function down(): void
    {
        Schema::table('t_acte_naissance', function (Blueprint $table) {
            $table->dropUnique(['niupp']);
            $table->dropPrimary(['code_acte_naissance']);
        });

        DB::statement("UPDATE t_acte_naissance SET niupp = CONCAT('MIG_', REPLACE(code_acte_naissance, '-', '')) WHERE niupp IS NULL");

        DB::statement('ALTER TABLE t_acte_naissance MODIFY niupp VARCHAR(50) NOT NULL');

        Schema::table('t_acte_naissance', function (Blueprint $table) {
            $table->dropColumn('code_acte_naissance');
        });

        Schema::table('t_acte_naissance', function (Blueprint $table) {
            $table->primary('niupp');
        });
    }
}
