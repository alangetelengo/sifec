<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTJugementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_jugement', function (Blueprint $table) {
            $table->string("code_jugement",16);
            $table->primary("code_jugement");
            $table->string('num_jugement',30)->nullable();
            $table->date('date_jugement')->nullable();
            $table->string("document_jugement", 175)->nullable();
            $table->string('code_declaration',16)->nullable();

            $table->string("cui", 16)->nullable();
            $table->string("code_type_jugement",16)->nullable();
            $table->string("code_institution", 16)->nullable();
            $table->enum('statut', ['importée', 'envoyée'])->default('importée');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign("cui")
            ->references("cui")
            ->on("tr_ins_user")
            ->onDelete("cascade")
            ->onUpdate("cascade");
            $table->foreign("code_type_jugement")
            ->references("code_type_jugement")
            ->on("tr_type_jugement")
            ->onDelete("cascade")
            ->onUpdate("cascade");
            $table->foreign("code_institution")
            ->references("code_institution")
            ->on("tr_institution")
            ->onDelete("cascade")
            ->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_jugement');
    }
}
