<?php

namespace Database\Seeders;

use App\Models\InstitutionUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Affectations utilisateur ↔ institution ↔ fonction (tr_ins_user).
 * Les droits applicatifs viennent de tr_ff pour la fonction indiquée (FonctionFonctionnaliteSeeder).
 */
class InstitutionUserSeeder extends Seeder
{
    public function run(): void
    {
        $path = __DIR__.'/Data/sifec_comptes_institutions.php';
        if (! is_file($path)) {
            $this->command?->error('Fichier manquant : database/seeders/Data/sifec_comptes_institutions.php');

            return;
        }

        /** @var array<int, array<string, mixed>> $comptes */
        $comptes = require $path;

        $now = now();

        foreach ($comptes as $row) {
            InstitutionUser::query()->updateOrInsert(
                [
                    'cui' => $row['cui'],
                    'code_institution' => $row['code_institution'],
                    'code_user' => $row['code_user'],
                ],
                [
                    'code_fonction' => $row['code_fonction'],
                    'active' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
