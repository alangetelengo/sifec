<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTResidencePersonneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_residence_personne', function (Blueprint $table) {
            $table->id();
            $table->string("lib_pays", 50)->nullable();
            $table->string("lib_ville", 175)->nullable();
            $table->string("type_voie", 175)->nullable();
            $table->string("nom_voie", 150)->nullable();
            $table->string("numero_rue", 6)->nullable();
            $table->string("code_quartier_village",16)->nullable();
            $table->string("code_localite", 16)->nullable(); // commune ou district
            $table->string("code_arrondissement_comurbaine", 16)->nullable();
            $table->string("code_personne", 16);

            $table->foreign("code_quartier_village")->references("code_localite")->on("tr_localite")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_localite")->references("code_localite")->on("tr_localite")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_arrondissement_comurbaine")->references("code_localite")->on("tr_localite")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_personne")->references("code_personne")->on("tr_identification_personne")->onDelete("cascade")->onUpdate("cascade");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_residence_personne');
    }
}
