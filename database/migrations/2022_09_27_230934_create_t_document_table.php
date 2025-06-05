<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_document', function (Blueprint $table) {
            $table->primary("code_document");
            $table->string("code_document",16);
            $table->string("numero_document",75)->nullable();
            $table->string("code_type_document")->nullable();
            $table->string("code_personne",16)->nullable();
            $table->longText("image_document",255)->nullable(); //base64
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("code_type_document")->references("code_type_document")->on("tr_type_document")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_personne")->references("code_personne")->on("tr_identification_personne")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_document');
    }
}
