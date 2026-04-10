<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Référentiel tr_mouvement : certificats naissance/décès et transformation certificat → déclaration (CEC).
 * Idempotent (updateOrInsert) pour bases déjà peuplées sans refaire tout TrMouvementSeeder.
 */
class TrMouvementCertificatsNaissanceDecesSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'code_mouvement' => 'MOUV_0033',
                'lib_mouvement' => 'Certificat de naissance enregistré',
                'description' => 'La formation sanitaire ou l\'établissement enregistre un certificat de naissance',
            ],
            [
                'code_mouvement' => 'MOUV_2005',
                'lib_mouvement' => 'Certificat de constatation de décès enregistré',
                'description' => 'Le certificat de constatation de décès est enregistré dans le système.',
            ],
            [
                'code_mouvement' => 'MOUV_0034',
                'lib_mouvement' => 'Déclaration de naissance générée par le Centre d\'état civil',
                'description' => 'Le centre d\'état civil valide le certificat et enregistre le dossier comme déclaration de naissance.',
            ],
            [
                'code_mouvement' => 'MOUV_0035',
                'lib_mouvement' => 'Certificat de naissance envoyé',
                'description' => 'La formation sanitaire envoie un certificat de naissance au centre d\'état civil',
            ],
        ];

        foreach ($rows as $row) {
            DB::table('tr_mouvement')->updateOrInsert(
                ['code_mouvement' => $row['code_mouvement']],
                [
                    'lib_mouvement' => $row['lib_mouvement'],
                    'description' => $row['description'],
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ]
            );
        }
    }
}
