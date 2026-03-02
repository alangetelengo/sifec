<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateLibRubriqueLengthInTrRubriqueTable extends Migration
{
    /**
     * Run the migrations.
     * Votre table a lib_rubrique et entite_rubrique en varchar(15).
     * Le seeder insère "Date de naissance" (18 car.) et "Lieu de naissance" (18 car.),
     * d'où l'erreur "Data too long". On augmente les tailles.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE tr_rubrique MODIFY lib_rubrique VARCHAR(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT \'Exple: nom, prenom, sexe, date de naissance, nationalite, etc\'');
        DB::statement('ALTER TABLE tr_rubrique MODIFY entite_rubrique VARCHAR(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT \'Exple: enfant, père, mère, époux, épouse, defunt, etc\'');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE tr_rubrique MODIFY lib_rubrique VARCHAR(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL');
        DB::statement('ALTER TABLE tr_rubrique MODIFY entite_rubrique VARCHAR(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL');
    }
}
