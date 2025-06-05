<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrDistrictTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_district', function (Blueprint $table) {
            $table->string("code_district",16);
            $table->primary("code_district");
            $table->string("lib_district");
            $table->string("longitude_district",16)->nullable();
            $table->string("latitude_district",16)->nullable();
            $table->string("code_departement",16);
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
        Schema::dropIfExists('tr_district');
    }
}
