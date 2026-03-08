<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrRubriqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('tr_rubrique', function (Blueprint $table) {
            $table->string("code_rubrique",16);
            $table->primary("code_rubrique");
            $table->string("lib_rubrique",80)->nullable()->comment("Exple: nom, prenom, sexe, date de naissance, nationalite, etc");
            $table->string("lib_technique",50)->nullable()->comment("Clé technique pour le traitement: nom, prenom, sexe, date_naissance, lieu_naissance, nationalite, etc");
            $table->string("entite_rubrique",30)->nullable()->comment("Exple: enfant, père, mère, époux, épouse, defunt, etc");
            $table->string("code_type_acte",16)->nullable();

            $table->foreign("code_type_acte")->references("code_type_acte")->on("tr_type_acte")->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('tr_rubrique');
    }
}
