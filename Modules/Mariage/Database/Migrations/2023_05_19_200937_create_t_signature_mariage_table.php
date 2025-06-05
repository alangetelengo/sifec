<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTSignatureMariageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_signature_mariage', function (Blueprint $table) {
            $table->primary("code_signature_mariage");
            $table->string('code_signature_mariage',16);
            $table->string("code_declaration_mariage",16);
            $table->longText('signature_epoux')->nullable();
            $table->longText('signature_epouse')->nullable();
            $table->longText('signature_temoin_premier_epoux')->nullable();
            $table->longText('signature_temoin_deuxieme_epoux')->nullable();
            $table->longText('signature_temoin_premier_epouse')->nullable();
            $table->longText('signature_temoin_deuxieme_epouse')->nullable();
            $table->boolean("etat")->default(false);
            $table->foreign('code_declaration_mariage')->references("code_declaration_mariage")->on('t_declaration_mariage')->onDelete("cascade")->onUpdate("cascade");

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
        Schema::dropIfExists('t_signature_mariage');
    }
}
