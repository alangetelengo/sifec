<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTLivretFamilleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_livret_famille', function (Blueprint $table) {
            $table->string("code_livret_famille",16);
            $table->primary("code_livret_famille");
            $table->string("code_declaration_mariage",16);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("code_declaration_mariage")->references("code_declaration_mariage")->on("t_declaration_mariage")->onDelete("cascade")->onUpdate("cascade");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_livret_famille');
    }
}
