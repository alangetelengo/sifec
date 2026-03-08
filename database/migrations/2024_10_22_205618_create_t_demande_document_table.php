<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTDemandeDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('t_demande_document', function (Blueprint $table) {
            $table->string("code_demande_document",16);
            $table->primary("code_demande_document");
            //dmandeur
            $table->string("nom_demandeur",75);
            $table->string("prenom_demander",75)->nullable();
            $table->enum("sexe_demander",["M","F"]);
            $table->string("telephone_demander",13);
            $table->string("email_demandeur",70)->nullable();
            $table->string("code_type_document_demande",16)->nullable();
            $table->enum("statut",["En traitement","Réjeté","Traité","Livré"])->default("En traitement");
            $table->foreign("code_type_document_demande")->references("code_type_document_demande")->on("tr_type_document_demande")->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('t_demande_document');
    }
}
