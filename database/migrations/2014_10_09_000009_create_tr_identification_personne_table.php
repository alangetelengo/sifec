<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrIdentificationPersonneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_identification_personne', function (Blueprint $table) {
            $table->primary("code_personne");
            $table->string('code_personne',16);
            $table->string('nom',75);
            $table->string('prenom',75)->nullable();
            $table->enum('sexe',['M','F']);
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->string('code_localite',16)->nullable();
            $table->longText("photo",255)->nullable(); //base64
            $table->string("telephone",15)->nullable();
            $table->string("telephone_parent",15)->nullable();
            $table->string("adresse")->nullable();
            $table->enum('niveau_instruction',['PRIMAIRE','SECONDAIRE NIVEAU I','SECONDAIRE NIVEAU II','SUPERIEUR','NON DECLARE'])->nullable()->default("NON DECLARE");
            $table->string('code_nationalite',16)->nullable();
            $table->string('code_profession',16)->nullable();
            $table->string("signature",175)->nullable();
            $table->string('personne_string')->nullable()->unique();
            $table->string("type_adoption",30)->nullable();
            $table->enum("statut_personne",["VIVANT","DECEDE"])->default("VIVANT");
            $table->enum("type_date_naissance",["EXACTE","ESTIME"])->default("EXACTE");
            $table->timestamps();


            $table->foreign('code_localite')->references('code_localite')->on('tr_localite')->onDelete('cascade')->onUpdate("cascade");
            $table->foreign('code_nationalite')->references('code_nationalite')->on('tr_nationalite')->onDelete('cascade');
            $table->foreign('code_profession')->references('code_profession')->on('tr_profession')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_identification_personne');
    }
}
