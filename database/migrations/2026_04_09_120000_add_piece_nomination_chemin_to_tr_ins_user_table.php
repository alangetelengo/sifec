<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPieceNominationCheminToTrInsUserTable extends Migration
{
    /**
     * Note de service / nomination justifiant la fonction et le centre d’affectation.
     */
    public function up()
    {
        Schema::table('tr_ins_user', function (Blueprint $table) {
            $table->string('piece_nomination_chemin', 512)->nullable()->after('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('tr_ins_user', function (Blueprint $table) {
            $table->dropColumn('piece_nomination_chemin');
        });
    }
}
