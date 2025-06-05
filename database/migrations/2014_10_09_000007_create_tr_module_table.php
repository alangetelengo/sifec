<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_module', function (Blueprint $table) {
            $table->primary("code_module");
            $table->string("code_module",16);
            $table->string("lib_module",100)->unique();
            $table->text("description_module")->nullable();
            $table->enum("etat_module",["Activé","Désactivé"])->default("Désactivé");
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
        Schema::dropIfExists('tr_module');
    }
}
