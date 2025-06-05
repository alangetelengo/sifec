<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTFeuilletRegistreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_feuillet_registre', function (Blueprint $table) {
            $table->string("code_feuillet_registre",16);
            $table->primary("code_feuillet_registre");
            $table->string("code_acte",30);
            $table->string("numero_acte",30);
            // $table->foreign("code_registre")->references("code_registre")->on("tr_registre")->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('t_feuillet_registre');
    }
}
