<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaiementDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paiement_documents', function (Blueprint $table) {
            $table->string("code_paiement_document",16);
            $table->primary("code_paiement_document");
            $table->enum("type_document",["ACTE","DUPLICATA","COPIE","EXTRAIT"]);
            // $table->enum("channel",["AM","MOMO","OTHER"]);
            $table->string("montant",30)->nullable();
            $table->date("date_paiement")->nullable();
            $table->string("cui",16);
            $table->boolean("etat")->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("cui")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paiement_documents');
    }
}
