<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTCopieTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('t_copie', function (Blueprint $table) {
            $table->id();
            $table->string("numero_acte",16);
            $table->string("new_nom",175)->nullable();
            $table->string("new_prenom",175)->nullable();
            $table->date("new_date_naissance")->nullable();
            $table->string("reference_document",55)->nullable();
            $table->date("date_document")->nullable();
            $table->text("libelle_document");
            $table->string("lieu_delivrance_document",100)->nullable();
            $table->string("signature_officier",175)->nullable();
            $table->string("nom_officier",175)->nullable();
            $table->string("cui", 16)->nullable();
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
        Schema::dropIfExists('t_copie');
    }
}
