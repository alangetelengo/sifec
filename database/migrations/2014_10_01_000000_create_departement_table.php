<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_departement', function (Blueprint $table) {
            $table->string("code_departement",16);
            $table->primary("code_departement");
            $table->string("lib_departement",45);
            $table->string("longitude_departement",16)->nullable();
            $table->string("latitude_departement",16)->nullable();
            $table->boolean("supprimer")->default(false);
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
        Schema::dropIfExists('departement');
    }
}
