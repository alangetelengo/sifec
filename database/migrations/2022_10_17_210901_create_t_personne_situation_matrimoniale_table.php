<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTPersonneSituationMatrimonialeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_personne_sitMat', function (Blueprint $table) {
            $table->string("code_personne_sitMat",16);
            $table->primary("code_personne_sitMat");
            $table->string("code_personne",16)->nullable();
            $table->string("code_situation_matrimoniale",16)->nullable();
            //$table->enum("atat_personne",[""])-
            $table->boolean("supprimer")->default(false);


            $table->foreign("code_personne")->references("code_personne")->on("tr_identification_personne")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_situation_matrimoniale")->references("code_situation_matrimoniale")->on("tr_situation_matrimoniale")->onDelete("cascade")->onUpdate("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_personne_sitMat');
    }
}
