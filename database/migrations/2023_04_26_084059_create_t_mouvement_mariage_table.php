<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTMouvementMariageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_mouvement_mariage', function (Blueprint $table) {
            $table->primary("code_mouvement_mariage");
            $table->string('code_mouvement_mariage',16);
            $table->string('code_mouvement',16);
            $table->string('lib_mouvement');
            $table->string("code_declaration_mariage",16);
            $table->string('cui',16)->nullable()->comment("utilisateur qui a effectué le mouvement");
            $table->string("code_institution_destinataire", 16)->nullable()->comment("pour renvoyer à l'institution d'origine");

            $table->string('motif_renvoi')->nullable();
            $table->text('observation')->nullable();
            $table->enum('statut', ['En cours', 'Envoyée', 'Renvoyée','Actif','Importé','Confirmée'])->default('En cours');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('code_mouvement')->references("code_mouvement")->on('tr_mouvement')->onUpdate("cascade")->onDelete("cascade");
            $table->foreign('code_declaration_mariage')->references("code_declaration_mariage")->on('t_declaration_mariage')->onUpdate("cascade")->onDelete("cascade");
            $table->foreign('cui')->references("cui")->on('tr_ins_user')->onUpdate("cascade")->onDelete("cascade");
            $table->foreign("code_institution_destinataire")->references("code_institution")->on("tr_institution")->onDelete("cascade")->onUpdate("cascade");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_mouvement_mariage');
    }
}
