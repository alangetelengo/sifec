<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrQuartierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_quartier', function (Blueprint $table) {
            $table->string("code_quartier", 16);
            $table->primary("code_quartier");
            $table->string("lib_quartier", 80);
            $table->string("code_district", 16)->nullable();
            $table->string("code_commune", 16)->nullable();
            $table->string("code_arrondissement", 16)->nullable();
            $table->string("code_communaute_urbaine", 16)->nullable();
            $table->string("longitude_quartier",16)->nullable();
            $table->string("latitude_quartier",16)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("code_district")->references("code_district")->on("tr_district")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_commune")->references("code_commune")->on("tr_commune")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_arrondissement")->references("code_arrondissement")->on("tr_arrondissement")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_communaute_urbaine")->references("code_communaute_urbaine")->on("tr_communaute_urbaine")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_quartier');
    }
}
