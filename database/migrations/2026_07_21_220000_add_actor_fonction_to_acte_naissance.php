<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fonction institutionnelle du signataire de l'acte (tr_fonction.lib_fonction).
 */
class AddActorFonctionToActeNaissance extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('t_acte_naissance', 'actor_fonction')) {
            Schema::table('t_acte_naissance', function (Blueprint $table) {
                $table->string('actor_fonction', 150)->nullable()->after('actor_nom');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('t_acte_naissance', 'actor_fonction')) {
            Schema::table('t_acte_naissance', function (Blueprint $table) {
                $table->dropColumn('actor_fonction');
            });
        }
    }
}
