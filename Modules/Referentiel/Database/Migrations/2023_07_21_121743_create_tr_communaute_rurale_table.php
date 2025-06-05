<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrCommunauteRuraleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_communaute_rurale', function (Blueprint $table) {
            $table->string("code_communaute_rurale", 16);
            $table->primary("code_communaute_rurale");
            $table->string("lib_communaute_rurale", 70);
            $table->string("code_district", 16)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign("code_district")->references("code_district")->on("tr_district")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_communaute_rurale');
    }
}
