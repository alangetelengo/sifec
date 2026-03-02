<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodeJugementToTDeclarationNaissanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_declaration_naissance', function (Blueprint $table) {
            $table->string('code_requisition', 16)->nullable()->after('code_jugement');
            $table->foreign("code_requisition")->references("code_requisition")->on("t_requisition")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_declaration_naissance', function (Blueprint $table) {
            $table->dropColumn('code_requisition');
        });
    }
}
