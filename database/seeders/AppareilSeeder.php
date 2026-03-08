<?php

namespace Database\Seeders;

use App\Models\Appareil;
use Illuminate\Database\Seeder;

class AppareilSeeder extends Seeder
{
    public function run(): void
    {
        $appareils = [
            [
                'code_appareil'       => 'APP_0001',
                'nom_appareil'        => 'ALANGE-PC',
                'adresse_mac'         => '90-E8-68-13-C3-61',
                'type_appareil'       => 'ordinateur',
                'code_institution'    => null,
                'enregistre_par'      => null,
                'statut'              => true,
                'date_enregistrement' => now(),
            ],
        ];

        foreach ($appareils as $data) {
            Appareil::updateOrCreate(
                ['adresse_mac' => $data['adresse_mac']],
                $data
            );
        }

        $this->command->info('AppareilSeeder : ' . count($appareils) . ' appareil(s) inséré(s).');
    }
}
