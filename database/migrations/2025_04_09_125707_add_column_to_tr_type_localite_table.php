<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToTrTypeLocaliteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tr_type_localite', function (Blueprint $table) {
            $table->string("type_cec",4)->nullable()->comment("Codification officiel du centre d'état civil")->after("lib_type_localite");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tr_type_localite', function (Blueprint $table) {
            //
        });
    }
}
