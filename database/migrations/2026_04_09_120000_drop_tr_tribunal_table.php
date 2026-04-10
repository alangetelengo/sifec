<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Les tribunaux sont gérés comme institutions (tr_institution), pas via tr_tribunal.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('tr_tribunal');
    }

    public function down(): void
    {
        // Recréation minimale pour rollback (alignée sur 2014_10_09_000005_create_tr_tribunal_table)
        Schema::create('tr_tribunal', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->string('code_tribunal', 16);
            $table->primary('code_tribunal');
            $table->string('lib_tribunal', 75);
            $table->string('code_cour_appel', 16);
            $table->boolean('statut')->default(true);
            $table->string('sceau', 175)->nullable();
            $table->boolean('supprimer')->default(false);
            $table->timestamps();
            $table->foreign('code_cour_appel')->references('code_cour_appel')->on('tr_cour_appel')->onDelete('cascade')->onUpdate('cascade');
        });
    }
};
