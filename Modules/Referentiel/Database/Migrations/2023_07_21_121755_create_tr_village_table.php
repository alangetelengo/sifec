<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrVillageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_village', function (Blueprint $table) {
            $table->string("code_village", 16);
            $table->primary("code_village");
            $table->string("lib_village", 80);
            $table->string("code_district", 16)->nullable();
            $table->string("code_communaute_rurale", 16)->nullable();
            $table->string("longitude_village",16)->nullable();
            $table->string("latitude_village",16)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("code_district")->references("code_district")->on("tr_district")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_communaute_rurale")->references("code_communaute_rurale")->on("tr_communaute_rurale")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_village');
    }
}
