<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToTDeclarationNaissanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_declaration_naissance', function (Blueprint $table) {
            $table->string("cec_approuve_par")->nullable()->after("cec_approuver");
            $table->enum("tribunal_approuver",["NON","OUI"])->default("NON")->after("cec_approuve_par");
            $table->string("tribunal_approuve_par")->nullable()->after("tribunal_approuver");

            $table->timestamp("cec_approuve_le")->nullable()->after("tribunal_approuve_par");
            $table->timestamp("tribunal_approuve_le")->nullable()->after("cec_approuve_le");

            $table->foreign("cec_approuve_par")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("tribunal_approuve_par")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");
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
            //
        });
    }
}
