<?php

namespace Database\Seeders;

use App\Sifec\Sifec;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Referentiel\Entities\Personne;

class PersonneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0");
        DB::statement("TRUNCATE tr_identification_personne");

        $personne = new Personne();
        $personne->code_personne = Sifec::genererCodeUniqueReferentiel($personne,"code_personne",8,"PRS_");
        $personne->nom = "ELENGA TELENGO OPALA";
        $personne->prenom = "Alange";
        $personne->sexe = "M";
        $personne->telephone = "066835332";
        $personne->adresse = "16,rue laptop Mpila";
        $personne->save();
    }
}
