<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TarifsNationauxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tarifs = [
            [
                'code_tarification' => 'TARIF_NAT_001',
                'code_type_document_demande' => 'TDD_0001', // Copie
                'code_institution' => null, // Tarif national
                'prix' => 5000.00,
                'date_debut_validite' => '2026-01-01',
                'date_fin_validite' => null,
                'actif' => 1,
                'commentaire' => 'Tarif national pour copie intégrale d\'acte (naissance, mariage, décès) - Établi selon la loi n° XX-2026 du XX/XX/2026',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code_tarification' => 'TARIF_NAT_002',
                'code_type_document_demande' => 'TDD_0002', // Extrait
                'code_institution' => null, // Tarif national
                'prix' => 3000.00,
                'date_debut_validite' => '2026-01-01',
                'date_fin_validite' => null,
                'actif' => 1,
                'commentaire' => 'Tarif national pour extrait d\'acte (naissance, mariage, décès) - Établi selon la loi n° XX-2026 du XX/XX/2026',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($tarifs as $tarif) {
            // Vérifier si le tarif n'existe pas déjà
            $exists = DB::table('t_tarification')
                ->where('code_tarification', $tarif['code_tarification'])
                ->exists();

            if (! $exists) {
                DB::table('t_tarification')->insert($tarif);
                $this->command->info("✓ Tarif {$tarif['code_tarification']} créé : {$tarif['prix']} FCFA pour {$tarif['code_type_document_demande']}");
            } else {
                $this->command->warn("⚠ Tarif {$tarif['code_tarification']} existe déjà");
            }
        }

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('Tarifs nationaux initialisés avec succès!');
        $this->command->info('========================================');
    }
}
