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


            $table->string("cui",16)->nullable();
            $table->string("code_institution",16)->nullable()->comment("centre état civil où vient le certificat");

            $table->enum("type_requisition",["requisition aux fins de reconstitution de l\'acte","requisition aux fins de declaration tardive","requisition aux fins de transcription de l\'acte","requisition aux fins de rectification de l\'acte","requisition aux fins de rectification de l\'acte"])->nullable();
            $table->enum("statut_document",["Envoye","En cours de traitement"])->default("En cours de traitement");
            $table->string("send_to",16)->nullable();

            $table->foreign("cui")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("send_to")->references("code_institution")->on("tr_institution")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_institution")->references("code_institution")->on("tr_institution")->onDelete("cascade")->onUpdate("cascade");

            $table->timestamps();
            $table->softDeletes();
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
