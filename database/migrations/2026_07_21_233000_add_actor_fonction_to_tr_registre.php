<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fonction institutionnelle du magistrat parapheur (tr_fonction.lib_fonction).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('tr_registre', 'actor_fonction')) {
            Schema::table('tr_registre', function (Blueprint $table) {
                $table->string('actor_fonction', 150)->nullable()->after('actor_nom');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('tr_registre', 'actor_fonction')) {
            Schema::table('tr_registre', function (Blueprint $table) {
                $table->dropColumn('actor_fonction');
            });
        }
    }
};
