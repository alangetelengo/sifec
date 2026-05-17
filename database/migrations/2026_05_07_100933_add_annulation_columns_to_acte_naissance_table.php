<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('t_acte_naissance', function (Blueprint $table) {
            // Colonnes pour gérer les actes annulés
            $table->boolean('est_acte_annulation')->default(false)->after('retirer')->comment('Indique si cet acte est un acte d\'annulation');
            $table->string('code_acte_annule', 100)->nullable()->after('est_acte_annulation')->comment('Code de l\'acte qui a été annulé');
            $table->string('niupp_acte_annule', 100)->nullable()->after('code_acte_annule')->comment('NIUPP de l\'acte qui a été annulé');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_acte_naissance', function (Blueprint $table) {
            $table->dropColumn(['est_acte_annulation', 'code_acte_annule', 'niupp_acte_annule']);
        });
    }
};
