<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTRectificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_rectification', function (Blueprint $table) {
            $table->string("code_rectification",16);
            $table->primary("code_rectification");
            $table->string("cui",16)->nullable()->comment("CUI de l'utilisateur qui a fait la rectification");
            $table->string("code_institution",16)->nullable()->comment("Centre état civil où vient la rectification");
            $table->string("code_institution_destinataire",16)->nullable()->comment("Centre état civil où va la rectification");
            $table->string("numero_rectification",30)->nullable()->comment("Numéro de la rectification");
            $table->string("code_type_acte",16)->nullable();
            $table->string("code_requisition",16)->nullable();

            $table->string("nom_prenom_requerant",150)->nullable();
            $table->string("adresse_requerant",100)->nullable();
            $table->string("telephone_requerant",20)->nullable();
            $table->date("date_rectification")->nullable();
            $table->string("code_filiation",16)->nullable()->comment("Code de la filiation du requérant, exple: père, mère, époux, épouse, etc.");
            $table->string("numero_acte",30)->nullable()->comment("Numero de l'acte à rectifier");
            $table->enum("statut",["En cours de traitement","Envoyé au tribunal","Validé","Annulé"])->default("En cours")->comment("Statut de la rectification");


            $table->foreign("code_type_acte")->references("code_type_acte")->on("tr_type_acte")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_requisition")->references("code_requisition")->on("t_requisition")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_filiation")->references("code_filiation")->on("tr_filiation")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("cui")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_institution")->references("code_institution")->on("tr_institution")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_institution_destinataire")->references("code_institution")->on("tr_institution")->onDelete("cascade")->onUpdate("cascade");
            $table->softDeletes();

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
        Schema::dropIfExists('t_rectification');
    }
}
