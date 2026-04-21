<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remplace la liaison tarification par CUI (tr_ins_user) par code_institution (tr_institution).
     */
    public function up(): void
    {
        Schema::table('t_tarification', function (Blueprint $table) {
            $table->dropForeign(['cui']);
        });

        Schema::table('t_tarification', function (Blueprint $table) {
            $table->string('code_institution', 16)->nullable()->after('prix');
        });

        DB::statement(
            'UPDATE t_tarification t
            INNER JOIN tr_ins_user i ON t.cui = i.cui
            SET t.code_institution = i.code_institution
            WHERE t.cui IS NOT NULL'
        );

        Schema::table('t_tarification', function (Blueprint $table) {
            $table->dropColumn('cui');
        });

        Schema::table('t_tarification', function (Blueprint $table) {
            $table->foreign('code_institution')
                ->references('code_institution')
                ->on('tr_institution')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('t_tarification', function (Blueprint $table) {
            $table->dropForeign(['code_institution']);
        });

        Schema::table('t_tarification', function (Blueprint $table) {
            $table->string('cui', 16)->nullable()->after('code_type_document_demande');
        });

        $rows = DB::table('t_tarification')->whereNotNull('code_institution')->get(['code_tarification', 'code_institution']);
        foreach ($rows as $row) {
            $cui = DB::table('tr_ins_user')
                ->where('code_institution', $row->code_institution)
                ->where('active', true)
                ->value('cui');
            if ($cui !== null) {
                DB::table('t_tarification')
                    ->where('code_tarification', $row->code_tarification)
                    ->update(['cui' => $cui]);
            }
        }

        Schema::table('t_tarification', function (Blueprint $table) {
            $table->dropColumn('code_institution');
        });

        Schema::table('t_tarification', function (Blueprint $table) {
            $table->foreign('cui')
                ->references('cui')
                ->on('tr_ins_user')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }
};
