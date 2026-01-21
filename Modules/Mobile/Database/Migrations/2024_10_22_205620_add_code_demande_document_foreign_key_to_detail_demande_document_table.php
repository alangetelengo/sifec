<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodeDemandeDocumentForeignKeyToDetailDemandeDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_demande_document', function (Blueprint $table) {
            $table->foreign("code_demande_document")
                ->references("code_demande_document")
                ->on("t_demande_document")
                ->onDelete("cascade")
                ->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detail_demande_document', function (Blueprint $table) {
            $table->dropForeign(['code_demande_document']);
        });
    }
}

