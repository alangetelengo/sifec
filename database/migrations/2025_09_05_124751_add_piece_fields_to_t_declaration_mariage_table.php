<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPieceFieldsToTDeclarationMariageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_declaration_mariage', function (Blueprint $table) {
            $table->string('piece_epoux', 255)->nullable()->comment('Chemin vers la pièce d\'identité de l\'époux');
            $table->string('piece_epouse', 255)->nullable()->comment('Chemin vers la pièce d\'identité de l\'épouse');
            $table->string('piece_temoins', 255)->nullable()->comment('Chemin vers les pièces d\'identité des témoins');
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
            $table->dropColumn(['piece_epoux', 'piece_epouse', 'piece_temoins']);
        });
    }
}
