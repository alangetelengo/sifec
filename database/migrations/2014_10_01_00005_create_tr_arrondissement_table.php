<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrArrondissementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_arrondissement', function (Blueprint $table) {
            $table->string("code_arrondissement",16);
            $table->primary("code_arrondissement");
            $table->string("lib_arrondissement");
            $table->string("longitude_arrondissement",16)->nullable();
            $table->string("latitude_arrondissement",16)->nullable();
            $table->string("code_commune",16);
            $table->boolean("supprimer")->default(false);
            $table->foreign("code_commune")->references("code_commune")->on("tr_commune")->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('tr_arrondissement');
    }
}
