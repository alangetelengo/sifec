<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocaliteSeeder extends Seeder
{
    /**
     * Charge les localités depuis database/data/tr_localite_seed.sql
     * (extrait de tr_localite.sql). Retombe sur database/data/localites.sql si absent.
     */
    public function run(): void
    {
        $primary = database_path('data/tr_localite_seed.sql');
        $fallback = database_path('data/localites.sql');
        $sqlFile = is_file($primary) ? $primary : $fallback;

        if (! is_file($sqlFile)) {
            $this->command?->warn("Aucun fichier SQL de localités trouvé ({$primary} ou {$fallback}). Seeder ignoré.");

            return;
        }

        $sql = file_get_contents($sqlFile);
        $statements = preg_split('/;\s*\R/m', $sql) ?: [];

        foreach ($statements as $statement) {
            $stmt = trim($statement);
            if ($stmt === '' || str_starts_with($stmt, '--')) {
                continue;
            }
            DB::unprepared($stmt);
        }

        $total = DB::table('tr_localite')->count();
        $this->command?->info("tr_localite : {$total} ligne(s) (fichier : ".basename($sqlFile).').');
    }
}
