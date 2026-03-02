<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContexteAffichageToTDeclarationNaissanceTable extends Migration
{
    /**
     * Run the migrations.
     * Contexte d'affichage pour certificat/declaration : formation_sanitaire | centre_etat_civil
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_declaration_naissance', function (Blueprint $table) {
            $table->string('contexte_affichage', 30)->nullable()->after('type_declaration')
                ->comment("Contexte pour l'en-tête/titre: formation_sanitaire | centre_etat_civil (certificat et déclaration de naissance uniquement)");
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
            $table->dropColumn('contexte_affichage');
        });
    }
}
