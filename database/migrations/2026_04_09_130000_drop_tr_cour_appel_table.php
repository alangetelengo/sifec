<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Les cours d’appel ne sont plus modélisées en référentiel séparé ; la hiérarchie passe par tr_institution.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('tr_cour_appel');
    }

    public function down(): void
    {
        Schema::create('tr_cour_appel', function (Blueprint $table) {
            $table->string('code_cour_appel', 16);
            $table->primary('code_cour_appel');
            $table->string('lib_cour_appel', 75);
            $table->boolean('supprimer')->default(false);
            $table->timestamps();
        });
    }
};
