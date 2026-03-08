<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTUserArrondissementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('t_user_arrondissement', function (Blueprint $table) {
            $table->primary(["code_arrondissement","cui"]);
            $table->string("cui",16);
            $table->string("code_arrondissement",16);
            $table->foreign("cui")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_arrondissement")->references("code_arrondissement")->on("tr_arrondissement")->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('t_user_arrondissement');
    }
}
