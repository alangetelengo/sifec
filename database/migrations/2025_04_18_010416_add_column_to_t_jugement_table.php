<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToTJugementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_jugement', function (Blueprint $table) {
            $table->string("code_institution",16)->nullable()->comment("centre état civil où vient le certificat de non inscription,cas de age du nouveau nee > 90 jours sans declararer")->after("cui");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_jugement', function (Blueprint $table) {
            //
        });
    }
}
