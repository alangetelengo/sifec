<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrSituationMatrimonialeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_situation_matrimoniale', function (Blueprint $table) {
            $table->primary("code_situation_matrimoniale");
            $table->string("code_situation_matrimoniale",16);
            $table->string("lib_situation_matrimoniale")->unique();
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
        Schema::dropIfExists('tr_situation_matrimoniale');
    }
}
