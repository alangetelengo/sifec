<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('t_declaration_deces', function (Blueprint $table) {
            if (! Schema::hasColumn('t_declaration_deces', 'type_declaration_origine')) {
                $table->string('type_declaration_origine', 120)->nullable()->after('type_declaration');
            }
            if (! Schema::hasColumn('t_declaration_deces', 'contexte_affichage')) {
                $table->string('contexte_affichage', 30)->nullable()->after('type_declaration_origine');
            }
        });
    }

    public function down(): void
    {
        Schema::table('t_declaration_deces', function (Blueprint $table) {
            if (Schema::hasColumn('t_declaration_deces', 'contexte_affichage')) {
                $table->dropColumn('contexte_affichage');
            }
            if (Schema::hasColumn('t_declaration_deces', 'type_declaration_origine')) {
                $table->dropColumn('type_declaration_origine');
            }
        });
    }
};
