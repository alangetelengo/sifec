<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCodeJugementAndCodeRequisitionFromTDeclarationMariageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_declaration_mariage', function (Blueprint $table) {
            $table->dropColumn(['code_jugement', 'code_requisition']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_declaration_mariage', function (Blueprint $table) {
            $table->string('code_jugement', 16)->nullable();
            $table->string('code_requisition', 16)->nullable();
        });
    }
}
