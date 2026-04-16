<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Authentification\Entities\Fonctionnalite;

class FonctionnaliteSeeder extends Seeder
{
    /**
     * Insère tr_fonctionnalite depuis database/seeders/Data/fonctionnalites_definitions.php.
     */
    public function run(): void
    {
        $path = __DIR__.'/Data/fonctionnalites_definitions.php';
        if (! is_file($path)) {
            $this->command?->error('Fichier manquant : '.$path);

            return;
        }

        /** @var array<int, array<string, mixed>> $rows */
        $rows = require $path;

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('TRUNCATE tr_fonctionnalite');

        foreach ($rows as $row) {
            $parent = $row['code_fonctionnalite_parent'] ?? null;
            if ($parent === '') {
                $parent = null;
            }

            Fonctionnalite::create([
                'code_fonctionnalite' => $row['code_fonctionnalite'],
                'lib_fonctionnalite' => $row['lib_fonctionnalite'],
                'lib_technique' => $row['lib_technique'],
                'description_fonctionnalite' => $row['description_fonctionnalite'] ?? null,
                'code_module' => $row['code_module'],
                'etat_fonctionnalite' => $row['etat_fonctionnalite'] ?? 'Activé',
                'code_fonctionnalite_parent' => $parent,
            ]);
        }
    }
}
