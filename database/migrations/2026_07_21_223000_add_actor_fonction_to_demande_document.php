<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActorFonctionToDemandeDocument extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('t_demande_document', 'actor_fonction')) {
            Schema::table('t_demande_document', function (Blueprint $table) {
                $table->string('actor_fonction', 150)->nullable()->after('actor_nom');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('t_demande_document', 'actor_fonction')) {
            Schema::table('t_demande_document', function (Blueprint $table) {
                $table->dropColumn('actor_fonction');
            });
        }
    }
}
