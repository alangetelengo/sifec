<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrTypeInstitutionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_type_institution', function (Blueprint $table) {
            $table->primary("code_type_institution");
            $table->string('code_type_institution',16);
            $table->string('lib_categorie',150);
            $table->string('lib_type_institution',150);
            $table->string('code_type_categorie_ins',16);
            $table->enum("lib_type_cec", ['CEC PRINCIPAL','CEC SECONDAIRE','AUCUN'])->nullable();
            $table->timestamps();
            $table->softDeletes();



            $table->foreign("code_type_categorie_ins")->references("code_type_categorie_ins")->on("tr_type_categorie_ins")->onDelete("cascade")->onUpdate("cascade");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_type_institution');
    }
}
