<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTJugementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_jugement', function (Blueprint $table) {
            $table->string("code_jugement",16);
            $table->primary("code_jugement");
            $table->string('num_jugement',30)->nullable();
            $table->date('date_jugement')->nullable();
            $table->string("numero_ancien_acte",20)->nullable();
            $table->string("document_jugement", 175)->nullable();

            $table->string("code_temoin1",16)->nullable();
            $table->string("code_temoin2",16)->nullable();
            $table->string("code_temoin3",16)->nullable();

            $table->string("cui",16);
            $table->enum("type_jugement",["JUGEMENT SUPPLETIF","JUGEMENT D\'HOMOLOGATION","JUGEMENT D\'ANNULATION D\'ACTE","JUGEMENT D\'ADOPTION"])->nullable();
            $table->enum("statut_document",["Envoye","En cours de traitement"])->default("En cours de traitement");
            $table->string("send_to",16)->nullable();

            $table->foreign("cui")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("send_to")->references("code_institution")->on("tr_institution")->onDelete("cascade")->onUpdate("cascade");

            $table->foreign("code_temoin1")->references("code_personne")->on("tr_identification_personne")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_temoin2")->references("code_personne")->on("tr_identification_personne")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_temoin3")->references("code_personne")->on("tr_identification_personne")->onDelete("cascade")->onUpdate("cascade");

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
        Schema::dropIfExists('t_jugement');
    }
}
