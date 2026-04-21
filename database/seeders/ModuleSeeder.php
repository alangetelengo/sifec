<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Authentification\Entities\Module;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('TRUNCATE tr_module');

        $data = [
            ['code_module' => 'MOD_0001', 'lib_module' => "Gestion d'accès au Système", 'description_module' => "Module qui permet à un utilisateur d'accéder au système", 'etat_module' => 'Activé'],
            ['code_module' => 'MOD_0002', 'lib_module' => 'Gestion des naissances', 'description_module' => "Permet à un utilisateur d'accéder aux données des naissance dans le système", 'etat_module' => 'Activé'],
            ['code_module' => 'MOD_0003', 'lib_module' => 'Gestion des décès', 'description_module' => "Permet à un utilisateur d'accéder aux données des décès dans le système", 'etat_module' => 'Activé'],
            ['code_module' => 'MOD_0004', 'lib_module' => 'Gestion des mariages', 'description_module' => "Permet à un utilisateur d'accéder aux données des mariages dans le système", 'etat_module' => 'Activé'],
            ['code_module' => 'MOD_0005', 'lib_module' => 'Gestion des divorces', 'description_module' => "Permet à un utilisateur d'accéder aux données des divorces dans le système", 'etat_module' => 'Activé'],
            ['code_module' => 'MOD_0006', 'lib_module' => 'Demandes de documents', 'description_module' => "Module de gestion des demandes d'expédition de documents (copies et extraits) pour tous les types d'actes", 'etat_module' => 'Activé'],
        ];

        foreach ($data as $d) {
            Module::create($d);
        }
    }
}
