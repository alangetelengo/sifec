<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTRequisitionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_requisition', function (Blueprint $table) {
            $table->string("code_requisition",16);
            $table->primary("code_requisition");
            $table->string('num_requisition',30)->nullable();
            $table->date('date_requisition')->nullable();
            $table->string("document_requisition", 175)->nullable();
            $table->string('code_declaration',16)->nullable();

            $table->string("cui", 16)->nullable();
            $table->string("code_type_requisition",16)->nullable();
            $table->string("code_institution", 16)->nullable();
            $table->enum('statut', ['importée', 'envoyée'])->default('importée');


            $table->timestamps();
            $table->softDeletes();

            $table->foreign("cui")
            ->references("cui")
            ->on("tr_ins_user")
            ->onDelete("cascade")
            ->onUpdate("cascade");
            $table->foreign("code_type_requisition")
            ->references("code_type_requisition")
            ->on("tr_type_requisition")
            ->onDelete("cascade")
            ->onUpdate("cascade");
            $table->foreign("code_institution")
            ->references("code_institution")
            ->on("tr_institution")
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
        Schema::dropIfExists('t_requisition');
    }
}
