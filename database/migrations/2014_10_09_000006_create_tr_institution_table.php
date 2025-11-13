<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrInstitutionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_institution', function (Blueprint $table) {
            $table->string('code_institution',16);
            $table->primary("code_institution");
            $table->string('lib_institution',255);
            $table->string('longitude_institution',25)->nullable();
            $table->string('latitude_institution',25)->nullable();
            $table->string('code_institution_parent',16)->nullable();
            $table->string('code_pompe_funebre',16)->nullable();
            $table->string('code_type_institution',16);
            $table->string("code_commune",16)->nullable();
            $table->string("code_district",16)->nullable();
            $table->string("code_arrondissement",16)->nullable();
            $table->string("code_communaute_urbaine",16)->nullable();
            $table->string('code_type_cec',16)->nullable();
            $table->string("code_localite",16)->nullable();
            $table->boolean("statut")->default(true);
            $table->string("sceau",175)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('code_institution_parent')->references('code_institution')->on('tr_institution')->onDelete('cascade')->onUpdate("cascade");
            $table->foreign('code_pompe_funebre')->references('code_institution')->on('tr_institution')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('code_type_institution')->references('code_type_institution')->on('tr_type_institution')->onDelete('cascade')->onUpdate("cascade");
            $table->foreign("code_commune")->references("code_commune")->on("tr_commune")->onDelete('cascade')->onUpdate("cascade");
            $table->foreign("code_district")->references("code_district")->on("tr_district")->onDelete('cascade')->onUpdate("cascade");
            $table->foreign('code_arrondissement')->references('code_arrondissement')->on('tr_arrondissement')->onDelete('cascade')->onUpdate("cascade");
            $table->foreign('code_communaute_urbaine')->references('code_communaute_urbaine')->on('tr_communaute_urbaine')->onDelete('cascade')->onUpdate("cascade");
            $table->foreign('code_type_cec')->references('code_type_cec')->on('tr_type_cec')->onDelete('cascade')->onUpdate("cascade");
            $table->foreign('code_localite')->references('code_localite')->on('tr_localite')->onDelete('cascade')->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_institution');
    }
}
