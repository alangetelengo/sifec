<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrTribunalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_tribunal', function (Blueprint $table) {
            $table->string("code_tribunal",16);
            $table->primary("code_tribunal");
            $table->string("lib_tribunal",75);
            $table->string("code_cour_appel",16);
            $table->boolean('statut')->default(true);
            $table->string("sceau", 175)->nullable();
            $table->boolean("supprimer")->default(false);

            $table->foreign("code_cour_appel")->references("code_cour_appel")->on("tr_cour_appel")->onDelete("cascade")->onUpdate("cascade");

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
        Schema::dropIfExists('tr_tribunal');
    }
}
