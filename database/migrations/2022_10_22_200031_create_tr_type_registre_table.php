<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrTypeRegistreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_type_registre', function (Blueprint $table) {
            $table->string("code_type_registre",16);
            $table->primary("code_type_registre");
            $table->string("lib_type_registre")->unique();
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
        Schema::dropIfExists('tr_type_registre');
    }
}
