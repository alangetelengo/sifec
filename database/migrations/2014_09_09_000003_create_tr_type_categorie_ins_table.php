<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrTypeCategorieInsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_type_categorie_ins', function (Blueprint $table) {
            $table->string("code_type_categorie_ins",16);
            $table->primary("code_type_categorie_ins");
            $table->string("lib_type_categorie_institution");
            $table->string("image_illustrative", 175)->nullable();
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
        Schema::dropIfExists('tr_type_categorie_ins');
    }
}
