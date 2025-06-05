<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTMouvementMariageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_mouvement_mariage', function (Blueprint $table) {
            $table->primary("code_mouvement_mariage");
            $table->string('code_mouvement_mariage',16);
            $table->enum("statut",["En cours","Validée","Envoyée","Renvoyée"])->default("En cours");
            $table->string("code_declaration_mariage",16);
            $table->string('cui',16)->nullable();
            $table->foreign('code_declaration_mariage')->references("code_declaration_mariage")->on('t_declaration_mariage')->onDelete("cascade")->onUpdate("cascade");
            $table->foreign('cui')->references("cui")->on('tr_ins_user')->onDelete("cascade")->onUpdate("cascade");

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
        Schema::dropIfExists('t_mouvement_mariage');
    }
}
