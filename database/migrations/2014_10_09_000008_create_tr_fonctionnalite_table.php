<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrFonctionnaliteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_fonctionnalite', function (Blueprint $table) {
            $table->primary("code_fonctionnalite");
            $table->string("code_fonctionnalite",16);
            $table->string("lib_fonctionnalite")->nullable()->unique();
            $table->string("lib_technique",100)->nullable()->unique();
            $table->text("description_fonctionnalite")->nullable();
            $table->string("code_fonctionnalite_parent",16)->nullable();
            $table->string("code_module",16);
            $table->enum("etat_fonctionnalite",["Activé","Désactivé"])->default("Désactivé");
            $table->boolean("supprimer")->default(false);
            $table->timestamps();

            $table->foreign("code_fonctionnalite_parent")->references("code_fonctionnalite")->on("tr_fonctionnalite")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_module")->references("code_module")->on("tr_module")->onDelete("CASCADE")->onUpdate("CASCADE");

        });

        Schema::create("tr_ff", function (Blueprint $table) {
            $table->primary(["code_fonction","code_fonctionnalite"]);
            $table->string("code_fonction",16);
            $table->string("code_fonctionnalite",16);
            $table->timestamps();

            $table->foreign("code_fonction")->references("code_fonction")->on("tr_fonction")->onDelete("CASCADE")->onUpdate("CASCADE");
            $table->foreign("code_fonctionnalite")->references("code_fonctionnalite")->on("tr_fonctionnalite")->onDelete("CASCADE")->onUpdate("CASCADE");
        });




    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_ff');
        Schema::dropIfExists('tr_fonctionnalite');
    }
}
