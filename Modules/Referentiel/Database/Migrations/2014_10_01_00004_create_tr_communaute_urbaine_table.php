<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrCommunauteUrbaineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_communaute_urbaine', function (Blueprint $table) {
            $table->string("code_communaute_urbaine",16);
            $table->primary("code_communaute_urbaine");
            $table->string("lib_communaute_urbaine");
            $table->string("longitude_communaute_urbaine",16)->nullable();
            $table->string("latitude_communaute_urbaine",16)->nullable();
            $table->string("code_district",16)->nullable();
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
        Schema::dropIfExists('tr_communaute_urbaine');
    }
}
