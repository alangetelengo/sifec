<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTDetailLivretTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_detail_livret', function (Blueprint $table) {
            $table->string("code_detail_livret", 16);
            $table->primary("code_detail_livret");
            $table->string("code_livret_famille", 16);
            $table->string("code_enfant", 16);
            $table->string("code_type_extrait",16);
            $table->string("numero_extrait", 10)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("code_livret_famille")->references("code_livret_famille")->on("t_livret_famille")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_enfant")->references("code_personne")->on("tr_identification_personne")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_type_extrait")->references("code_type_extrait")->on("tr_type_extrait")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_detail_livret');
    }
}
