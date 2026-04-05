<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactPersonneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('t_contact_personne', function (Blueprint $table) {
            $table->id();
            $table->string("indicatif", 5)->nullable();
            $table->string("telephone", 12)->nullable();
            $table->string("email_personnelle", 100)->nullable();
            $table->string("email_professionnelle", 100)->nullable();
            $table->string("code_personne", 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign("code_personne")->references("code_personne")->on("tr_identification_personne")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_contact_personne');
    }
}
