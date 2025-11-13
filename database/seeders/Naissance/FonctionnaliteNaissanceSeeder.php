<?php

namespace Database\Seeders\Naissance;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Constants\FonctionnaliteCodes;
use App\Constants\ModuleCodes;
use Carbon\Carbon;

class FonctionnaliteNaissanceSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $fonctionnalites = [
            [
                'code_fonctionnalite' => FonctionnaliteCodes::GESTION_ACTE_NAISSANCE,
                'lib_fonctionnalite' => 'Gestion des actes de naissance',
                'lib_technique' => 'module.acteNaissance',
                'description_fonctionnalite' => "Permet à un utilisateur d'accéder aux données de naissance dans le système",
                'code_fonctionnalite_parent' => null,
                'code_module' => ModuleCodes::NAISSANCE,
                'etat_fonctionnalite' => 'Activé',
                'supprimer' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code_fonctionnalite' => FonctionnaliteCodes::DECLARATION_NAISSANCE_CREATE,
                'lib_fonctionnalite' => 'Créer une déclaration de naissance',
                'lib_technique' => 'module.ActeNaissance.declarationNaissance.create',
                'description_fonctionnalite' => "Permet de créer une déclaration de naissance",
                'code_fonctionnalite_parent' => FonctionnaliteCodes::GESTION_ACTE_NAISSANCE,
                'code_module' => ModuleCodes::NAISSANCE,
                'etat_fonctionnalite' => 'Activé',
                'supprimer' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Ajoutez ici d'autres fonctionnalités du module Naissance
        ];

        foreach ($fonctionnalites as $fonctionnalite) {
            DB::table('tr_fonctionnalite')->updateOrInsert(
                ['code_fonctionnalite' => $fonctionnalite['code_fonctionnalite']],
                $fonctionnalite
            );
        }
    }
}
