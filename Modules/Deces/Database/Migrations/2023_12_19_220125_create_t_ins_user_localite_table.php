<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTInsUserLocaliteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('t_ins_user_localite', function (Blueprint $table) {
            $table->increments("id");
            $table->string("cui",16);
            $table->string("code_localite",16);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("cui")->references("cui")->on("tr_ins_user")->cascadeOnUpdate();
            $table->foreign("code_localite")->references("code_localite")->on("tr_localite")->cascadeOnUpdate();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_ins_user_localite');
    }
}
