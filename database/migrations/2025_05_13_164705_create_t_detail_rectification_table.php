<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTDetailRectificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_detail_rectification', function (Blueprint $table) {
            $table->string("code_detail_rectification",16);
            $table->primary("code_detail_rectification");
            $table->string("code_rectification",16)->nullable();
            $table->string("code_rubrique",16)->nullable();
            $table->string("ancienne_valeur",150)->nullable();
            $table->string("nouvelle_valeur",150)->nullable();

            $table->foreign("code_rectification")->references("code_rectification")->on("t_rectification")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_rubrique")->references("code_rubrique")->on("tr_rubrique")->onDelete("cascade")->onUpdate("cascade");
            $table->softDeletes();
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
        Schema::dropIfExists('t_detail_rectification');
    }
}
