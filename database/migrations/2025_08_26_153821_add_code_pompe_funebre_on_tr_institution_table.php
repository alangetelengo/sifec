<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodePompeFunebreOnTrInstitutionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tr_institution', function (Blueprint $table) {
            $table->string('code_pompe_funebre',16)->nullable()->after('code_institution_parent');
            $table->foreign('code_pompe_funebre')->references('code_institution')->on('tr_institution')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tr_institution', function (Blueprint $table) {
            $table->dropForeign(['code_pompe_funebre']);
            $table->dropColumn('code_pompe_funebre');
        });
    }
}
