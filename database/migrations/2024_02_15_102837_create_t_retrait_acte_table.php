<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTRetraitActeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_retrait_acte', function (Blueprint $table) {
            $table->string("code_retrait_acte", 16);
            $table->primary("code_retrait_acte");
            $table->string("code_acte", 16);
            $table->string("retirer_par", 255);
            $table->string("telephone", 20);

            $table->string("cui",16);
            $table->foreign("cui")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");

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
        Schema::dropIfExists('t_retrait_acte');
    }
}
