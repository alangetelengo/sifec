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
        Schema::table('t_jugement', function (Blueprint $table) {
            $table->string('numero_ancien_acte', 50)->nullable()
                ->after('code_type_jugement')
                ->comment('Numéro/NIUPP de l\'ancien acte à annuler ou adopter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_jugement', function (Blueprint $table) {
            $table->dropColumn('numero_ancien_acte');
        });
    }
};
