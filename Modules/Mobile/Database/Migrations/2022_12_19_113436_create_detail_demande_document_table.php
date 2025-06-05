<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailDemandeDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_demande_document', function (Blueprint $table) {
            $table->string("code_detail_demande_document",16);

            $table->primary("code_detail_demande_document");
            $table->string("code_demande_document",16);
            $table->string("code_otp",8)->nullable();
            $table->string("lien_telechargement",100)->nullable();
            $table->dateTime("date_creation_lien")->default(now());
            $table->smallInteger("duree_validite")->default(31);
            $table->enum("statut_lien",["actif","expiré"])->default("actif");
            $table->integer("nombre_telechargement")->default(1);
            $table->timestamps();

            $table->foreign("code_demande_document")
            ->references("code_demande_document")
            ->on("t_demande_document")
            ->onDelete("cascade")
            ->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_demande_document');
    }
}
