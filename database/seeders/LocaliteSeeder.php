<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocaliteSeeder extends Seeder
{
    public function run(): void
    {
        $sqlFile = database_path('data/localites.sql');

        if (!file_exists($sqlFile)) {
            $this->command->warn("Fichier introuvable : {$sqlFile}. Seeder ignoré.");
            return;
        }

        $sql = file_get_contents($sqlFile);

        // Exécuter chaque instruction SQL séparément
        foreach (explode(";\n", $sql) as $statement) {
            $stmt = trim($statement);
            if ($stmt !== '' && !str_starts_with($stmt, '--')) {
                DB::unprepared($stmt);
            }
        }

        $total = DB::table('tr_localite')->count();
        $this->command->info("tr_localite : {$total} localités insérées.");
    }
}
