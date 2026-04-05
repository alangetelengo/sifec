<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder des institutions — données extraites de la BD de référence (sifec.sql).
 * Les données sont dans database/data/institutions.sql (195 institutions).
 * Catégories : Cours d'appel, TGI, TI, Mairies, Formations sanitaires,
 *              Ambassades, Pompes funèbres, Centres d'hygiène.
 */
class InstitutionSeeder extends Seeder
{
    public function run(): void
    {
        $sqlFile = database_path('data/institutions.sql');

        if (!file_exists($sqlFile)) {
            $this->command->warn("Fichier introuvable : {$sqlFile}. Seeder ignoré.");
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('TRUNCATE tr_institution');

        $sql = file_get_contents($sqlFile);

        // Normaliser les fins de ligne Windows → Unix
        $sql = str_replace("\r\n", "\n", $sql);

        foreach (explode(";\n", $sql) as $statement) {
            // Supprimer les lignes de commentaires (-- ...) du fragment
            $lines = array_filter(
                explode("\n", $statement),
                fn($line) => !str_starts_with(trim($line), '--')
            );
            $stmt = trim(implode("\n", $lines));

            if ($stmt === '') {
                continue;
            }

            $upper = strtoupper($stmt);

            // Ignorer DDL et instructions FK
            if (
                str_starts_with($upper, 'CREATE')                 ||
                str_starts_with($upper, 'DROP')                   ||
                str_starts_with($upper, 'ALTER')                  ||
                str_starts_with($upper, 'SET FOREIGN_KEY_CHECKS')
            ) {
                continue;
            }

            // N'exécuter que les INSERT et TRUNCATE
            if (str_starts_with($upper, 'INSERT') || str_starts_with($upper, 'TRUNCATE')) {
                DB::unprepared($stmt);
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $total = DB::table('tr_institution')->count();
        $this->command->info("tr_institution : {$total} institutions insérées.");
    }
}
