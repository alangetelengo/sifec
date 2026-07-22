<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\PurgesDemoFaitData;
use Illuminate\Database\Seeder;
use Modules\Deces\Database\Seeders\CertificatDecesSeeder;
use Modules\Mariage\Database\Seeders\FormulaireTypeMariageSeeder;
use Modules\Naissance\Database\Seeders\DeclarationNaissanceSeeder;

/**
 * Données de démonstration (brouillon) : naissance, décès, mariage.
 *
 * Prérequis : DatabaseSeeder (comptes SIFEC + référentiels).
 *
 * php artisan db:seed --class=Database\\Seeders\\FaitsDatabaseSeeder
 */
class FaitsDatabaseSeeder extends Seeder
{
    use PurgesDemoFaitData;

    public function run(): void
    {
        $this->command?->info('Purge des données de démo (faits, contacts, feuillets, notifications, registres)...');
        $this->purgeAllDemoFaitData();

        $this->call([
            DeclarationNaissanceSeeder::class,
            CertificatDecesSeeder::class,
            FormulaireTypeMariageSeeder::class,
        ]);
    }
}
