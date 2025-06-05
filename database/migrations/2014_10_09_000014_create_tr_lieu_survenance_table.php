<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrLieuSurvenanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_lieu_survenance', function (Blueprint $table) {
            $table->primary("code_lieu_survenance");
            $table->string("code_lieu_survenance",16);
            $table->string("lib_lieu_survenance", 50)->unique();
            $table->boolean('supprimer')->default(false);
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
        Schema::dropIfExists('tr_lieu_survenance');
    }
}
