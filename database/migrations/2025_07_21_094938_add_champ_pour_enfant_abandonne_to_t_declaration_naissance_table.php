<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChampPourEnfantAbandonneToTDeclarationNaissanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_declaration_naissance', function (Blueprint $table) {
            $table->string("lieu_placement",150)->nullable()->comment("qui permet de renseigner la structure au quel l’enfant trouvé ou abandonné a été placé")->after("piece_mere");
            $table->string("piece_extrait_main_courante",175)->nullable()->comment("qui permet de renseigner la structure au quel l’enfant trouvé ou abandonné a été placé")->after("lieu_placement");
            $table->string("num_jugement_placement_provisoir",20)->nullable()->comment("qui permet de renseigner la structure au quel l’enfant trouvé ou abandonné a été placé")->after("extrait_main_courante");
            $table->string("num_fiche_placement",20)->nullable()->comment("qui permet de renseigner la structure au quel l’enfant trouvé ou abandonné a été placé")->after("extrait_main_courante");
        });
    }

    /**
     * Reverse the migrations.
     *Ajouter les champs (lieu_placement,num_extrait_main_courante,num_fiche_placement) dans table declaration qui permet de renseigner la structure au quel l’enfant abandonné a été placé
     * @return void
     */
    public function down()
    {
        Schema::table('t_declaration_naissance', function (Blueprint $table) {
            //
        });
    }
}
