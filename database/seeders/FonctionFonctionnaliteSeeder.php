<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Authentification\Entities\Fonctionnalite;
use Modules\Referentiel\Entities\Fonction;

/**
 * Remplit tr_ff : liaison fonction métier (tr_fonction) ↔ fonctionnalités (tr_fonctionnalite).
 *
 * À exécuter après ModuleSeeder, FonctionnaliteSeeder et FonctionSeeder.
 */
class FonctionFonctionnaliteSeeder extends Seeder
{
    public function run(): void
    {
        $mapPath = __DIR__.'/Data/fonction_fonctionnalite_map.php';
        if (! is_file($mapPath)) {
            $this->command?->error('Fichier manquant : database/seeders/Data/fonction_fonctionnalite_map.php');

            return;
        }

        /** @var array<string, array<string>|null> $map */
        $map = require $mapPath;

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('tr_ff')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $allCodes = Fonctionnalite::query()->orderBy('code_fonctionnalite')->pluck('code_fonctionnalite')->all();

        foreach ($map as $codeFonction => $codesFnc) {
            $fonction = Fonction::query()->find($codeFonction);
            if (! $fonction) {
                $this->command?->warn("Fonction absente en base, ignorée : {$codeFonction}");

                continue;
            }

            if ($codeFonction === 'FONC_0011' || $codesFnc === null) {
                $fonction->fonctionnalites()->sync($allCodes);

                continue;
            }

            $codesFnc = array_values(array_unique(array_filter($codesFnc)));
            $valid = array_values(array_intersect($codesFnc, $allCodes));
            if (count($valid) !== count($codesFnc)) {
                $invalid = array_diff($codesFnc, $valid);
                if ($invalid !== []) {
                    $this->command?->warn("FONC {$codeFonction} : codes FNC inconnus ignorés — ".implode(', ', $invalid));
                }
            }

            $fonction->fonctionnalites()->sync($valid);
        }

        $this->command?->info('tr_ff : fonctionnalités attachées aux fonctions (FonctionFonctionnaliteSeeder).');
    }
}
