<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('t_mouvement_rectification', function (Blueprint $table) {
            $table->primary("code_mouvement_rectification");
            $table->string('code_mouvement_rectification',16);
            $table->string('code_mouvement',16);
            $table->string('lib_mouvement');
            $table->string("code_rectification",16);
            $table->string('cui',16)->nullable()->comment("utilisateur qui a effectué le mouvement");
            $table->string("code_institution_destinataire", 16)->nullable()->comment("pour renvoyer à l'institution d'origine");

            $table->string('motif_renvoi')->nullable();
            $table->text('observation')->nullable();
            $table->enum('statut', ['En cours', 'Envoyée', 'Renvoyée','Actif','Importé'])->default('En cours');

            $table->timestamps();
            $table->softDeletes();
            $table->foreign('code_mouvement')->references("code_mouvement")->on('tr_mouvement')->onUpdate("cascade")->onDelete("cascade");
            $table->foreign('code_rectification')->references("code_rectification")->on('t_rectification')->onUpdate("cascade")->onDelete("cascade");
            $table->foreign('cui')->references("cui")->on('tr_ins_user')->onUpdate("cascade")->onDelete("cascade");
            $table->foreign("code_institution_destinataire")->references("code_institution")->on("tr_institution")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_mouvement_rectification');
    }
};
