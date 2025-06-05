<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTarificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_tarification', function (Blueprint $table) {
            $table->string("code_tarification",16);
            $table->primary("code_tarification");
            // $table->string("code_type_acte",16);
            $table->string("code_type_document_demande",16);
            $table->string("cui",16);
            $table->double("prix");

            // $table->foreign("code_type_acte")->references("code_type_acte")->on("tr_type_acte")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_type_document_demande")->references("code_type_document_demande")->on("tr_type_document_demande")->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('t_tarification');
    }
}
