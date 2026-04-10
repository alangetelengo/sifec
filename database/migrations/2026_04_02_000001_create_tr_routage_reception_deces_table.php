<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Priorise ou force l'institution destinataire pour une localité de lieu de décès
 * (plusieurs CEC, repli explicite, etc.).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tr_routage_reception_deces', function (Blueprint $table) {
            $table->id();
            $table->string('code_localite', 16);
            $table->string('code_institution', 16);
            $table->unsignedSmallInteger('priorite')->default(100);
            $table->timestamps();

            $table->unique(['code_localite', 'code_institution'], 'tr_rrd_localite_institution_unique');
            $table->foreign('code_localite')->references('code_localite')->on('tr_localite')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('code_institution')->references('code_institution')->on('tr_institution')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tr_routage_reception_deces');
    }
};
