<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrLocaliteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_localite', function (Blueprint $table) {
            $table->primary("code_localite");
            $table->string('code_localite',16);
            $table->string('lib_localite',150);
            $table->string('code_type_localite',16);
            $table->boolean("pompes_funebres")->default(false);
            $table->string('code_localite_parent',16)->nullable();
            $table->boolean('supprimer')->default(false);
            $table->timestamps();
            

            $table->foreign('code_type_localite')->references('code_type_localite')->on('tr_type_localite')->onDelete('CASCADE')->onUpdate("CASCADE");
            $table->foreign('code_localite_parent')->references('code_localite')->on('tr_localite')->onDelete('CASCADE')->onUpdate("CASCADE");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_localite');
    }
}
