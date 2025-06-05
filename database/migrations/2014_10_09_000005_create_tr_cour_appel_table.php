<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrCourAppelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_cour_appel', function (Blueprint $table) {
            $table->string("code_cour_appel",16);
            $table->primary("code_cour_appel");
            $table->string("lib_cour_appel",75);
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
        Schema::dropIfExists('tr_cour_appel');
    }
}
