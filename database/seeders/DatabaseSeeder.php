<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            DepartementSeeder::class,
            TypeDocumentSeeder::class,
            RegimeSeeder::class,
            NationaliteSeeder::class,
            LieuSurvenanceSeeder::class,
            FonctionSeeder::class,
            FiliationSeeder::class,
            SituationMatrimonialeSeeder::class,
            // PersonneSeeder::class,
            // UserSeederTableSeeder::class,
            TypeRegistreSeeder::class,
            // InstitutionUserSeeder::class,
            CauseDecesSeeder::class,
            ProfessionSeeder::class,
            TypeExtraitSeeder::class,
            ReligionSeeder::class,
            OptionMariageSeeder::class,
            \Database\Seeders\Naissance\FonctionnaliteNaissanceSeeder::class,
        ]);
    }
}
