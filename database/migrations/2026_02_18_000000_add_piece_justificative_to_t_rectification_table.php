<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPieceJustificativeToTRectificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_rectification', function (Blueprint $table) {
            $table->string('piece_justificative', 255)->nullable()->after('numero_acte')
                ->comment('Chemin du fichier pièce justificative (PDF, image)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_rectification', function (Blueprint $table) {
            $table->dropColumn('piece_justificative');
        });
    }
}
