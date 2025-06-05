<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrLocalisationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_localisation', function (Blueprint $table) {
            $table->string("code_localisation",16);
            $table->primary("code_localisation");
            $table->string("lib_localisation");
            $table->string("code_departement",16);
            $table->boolean("supprimer")->default(false);

            $table->foreign("code_departement")->references("code_departement")->on("tr_departement")->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('tr_localisation');
    }
}
