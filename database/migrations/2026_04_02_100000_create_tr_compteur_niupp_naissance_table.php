<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrCompteurNiuppNaissanceTable extends Migration
{
    public function up(): void
    {
        Schema::create('tr_compteur_niupp_naissance', function (Blueprint $table) {
            $table->id();
            $table->string('code_institution', 16);
            $table->unsignedSmallInteger('annee');
            $table->unsignedTinyInteger('mois');
            $table->unsignedInteger('dernier_ordre')->default(0);
            $table->timestamps();

            $table->unique(['code_institution', 'annee', 'mois'], 'uniq_cec_annee_mois_niupp');
            $table->foreign('code_institution')
                ->references('code_institution')
                ->on('tr_institution')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tr_compteur_niupp_naissance');
    }
}
