<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLibTechniqueToTrRubriqueTable extends Migration
{
    /**
     * Run the migrations.
     * Ajoute lib_technique (utilisé par la vue rectification create) si la colonne n'existe pas.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('tr_rubrique', 'lib_technique')) {
            Schema::table('tr_rubrique', function (Blueprint $table) {
                $table->string('lib_technique', 50)->nullable()->after('lib_rubrique')
                    ->comment('Clé technique pour le traitement: nom, prenom, sexe, date_naissance, lieu_naissance, nationalite, etc');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('tr_rubrique', 'lib_technique')) {
            Schema::table('tr_rubrique', function (Blueprint $table) {
                $table->dropColumn('lib_technique');
            });
        }
    }
}
