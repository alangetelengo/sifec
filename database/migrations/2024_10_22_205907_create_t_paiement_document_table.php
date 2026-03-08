<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTPaiementDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('t_paiement_document', function (Blueprint $table) {
            $table->string("code_paiement_document",16);
            $table->primary("code_paiement_document");
            $table->string("code_demande_document",16);
            $table->double("prix");
            $table->enum("canal_paiement",["MOMO","AIRTEL","OTHER"])->nullable();
            $table->string("numero_paiement",15)->nullable();
            $table->enum('statut_payment',["success","failed","pending"])->default("pending");

            $table->string("cui",16)->nullable();

            $table->foreign("code_demande_document")->references("code_demande_document")->on("t_demande_document")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("cui")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");

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
        Schema::dropIfExists('t_paiement_document');
    }
}
