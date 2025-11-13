<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTMouvementDossierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_mouvement_dossier', function (Blueprint $table) {
            $table->primary("code_mouvement");
            $table->string('code_mouvement',16);
            $table->string('code_dossier', 32)->comment("Peut être code_declaration_naissance ou code_declaration_deces");
            $table->string('module', 20)->comment('naissance ou deces ou autre');
            $table->string('cui', 16)->nullable()->comment("l’auteur représente l’utilisateur ou l’entité qui a effectué l’action (le mouvement)."); // Nom ou identifiant de l'utilisateur
            $table->text('observation')->nullable();
            $table->timestamp('date_mouvement')->useCurrent();
            $table->timestamps();

            $table->foreign('code_mouvement')->references("code_mouvement")->on('tr_mouvement')->onUpdate("cascade")->onDelete("cascade");
            $table->foreign('cui')->references("cui")->on('tr_ins_user')->onUpdate("cascade")->onDelete("cascade");

            // Optionnel : index pour accélérer les recherches
            $table->index(['code_dossier', 'module']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_mouvement_dossier');
    }
}
