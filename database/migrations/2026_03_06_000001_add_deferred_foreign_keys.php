<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ajoute les clés étrangères vers t_jugement et t_requisition sur les tables déclaration.
 * Doit s'exécuter APRÈS :
 *   - 2025_03_19_163333_create_t_jugement_table
 *   - 2025_03_29_105344_create_t_requisition_table
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('t_declaration_deces', function (Blueprint $table) {
            $table->foreign('code_jugement')
                  ->references('code_jugement')
                  ->on('t_jugement')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('code_requisition')
                  ->references('code_requisition')
                  ->on('t_requisition')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        Schema::table('t_declaration_naissance', function (Blueprint $table) {
            $table->foreign('code_jugement')
                  ->references('code_jugement')
                  ->on('t_jugement')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('code_requisition')
                  ->references('code_requisition')
                  ->on('t_requisition')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        Schema::table('t_mouvement_rectification', function (Blueprint $table) {
            $table->foreign('code_rectification')
                  ->references('code_rectification')
                  ->on('t_rectification')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });

        Schema::table('detail_demande_document', function (Blueprint $table) {
            $table->foreign('code_demande_document')
                  ->references('code_demande_document')
                  ->on('t_demande_document')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('t_declaration_deces', function (Blueprint $table) {
            $table->dropForeign(['code_jugement']);
            $table->dropForeign(['code_requisition']);
        });

        Schema::table('t_declaration_naissance', function (Blueprint $table) {
            $table->dropForeign(['code_jugement']);
            $table->dropForeign(['code_requisition']);
        });

        Schema::table('t_mouvement_rectification', function (Blueprint $table) {
            $table->dropForeign(['code_rectification']);
        });

        Schema::table('detail_demande_document', function (Blueprint $table) {
            $table->dropForeign(['code_demande_document']);
        });
    }
};
