<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTMouvementNaissanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_mouvement_naissance', function (Blueprint $table) {
            $table->primary("code_mouvement_naissance");
            $table->string('code_mouvement_naissance',16);
            $table->enum("statut",["En cours","Validée","Envoyée","Renvoyée","Envoye au tribunal"])->default("En cours");
            $table->string("code_declaration_naissance",16);
            $table->string('cui',16)->nullable(); //utilisateur qui a effectué le mouvement
            $table->string('motif_renvoi')->nullable();
            $table->text('observation')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('code_declaration_naissance')->references("code_declaration_naissance")->on('t_declaration_naissance')->onUpdate("cascade")->onDelete("cascade");
            $table->foreign('cui')->references("cui")->on('tr_ins_user')->onUpdate("cascade")->onDelete("cascade");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_mouvement_naissance');
    }
}
