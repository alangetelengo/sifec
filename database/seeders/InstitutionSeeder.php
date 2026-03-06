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

        $sql = file_get_contents($sqlFile);

        foreach (explode(";\n", $sql) as $statement) {
            $stmt = trim($statement);
            if ($stmt !== '' && !str_starts_with($stmt, '--')) {
                DB::unprepared($stmt);
            }
        }

        $total = DB::table('tr_institution')->count();
        $this->command->info("tr_institution : {$total} institutions insérées.");
    }
}
