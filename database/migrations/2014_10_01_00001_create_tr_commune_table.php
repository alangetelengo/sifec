<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrCommuneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_commune', function (Blueprint $table) {
            $table->string("code_commune",16);
            $table->primary("code_commune");
            $table->string("lib_commune");
            $table->string("sigle")->nullable();
            $table->string("longitude_commune",16)->nullable();
            $table->string("latitude_commune",16)->nullable();
            $table->string("code_departement",16);
            // $table->boolean("supprimer")->default(false);

            $table->foreign("code_departement")->references("code_departement")->on("tr_departement")->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('tr_commune');
    }
}
