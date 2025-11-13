<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignCodeInstitutionDestinataireToTDeclarationDeces extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_declaration_deces', function (Blueprint $table) {
            $table->foreign('code_institution_destinataire')
            ->references('code_institution')
            ->on('tr_institution')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_declaration_deces', function (Blueprint $table) {
            $table->dropForeign(['code_institution_destinataire']);
        });
    }
}
