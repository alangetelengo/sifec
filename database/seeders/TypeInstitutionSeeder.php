<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeInstitutionSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('TRUNCATE tr_type_institution');

        DB::table('tr_type_institution')->insert([
            ['code_type_institution' => 'TPINS_0001', 'lib_type_institution' => 'Tribunal de grande instance',    'code_type_categorie_ins' => 'TCINS_0002'],
            ['code_type_institution' => 'TPINS_0002', 'lib_type_institution' => 'Mairie',                         'code_type_categorie_ins' => 'TCINS_0001'],
            ['code_type_institution' => 'TPINS_0003', 'lib_type_institution' => 'Pompes funèbres',                 'code_type_categorie_ins' => 'TCINS_0001'],
            ['code_type_institution' => 'TPINS_0005', 'lib_type_institution' => 'Ambassade',                      'code_type_categorie_ins' => 'TCINS_0001'],
            ['code_type_institution' => 'TPINS_0006', 'lib_type_institution' => "Cour d'appel",                   'code_type_categorie_ins' => 'TCINS_0002'],
            ['code_type_institution' => 'TPINS_0008', 'lib_type_institution' => "Tribunal d'instance",            'code_type_categorie_ins' => 'TCINS_0002'],
            ['code_type_institution' => 'TPINS_0009', 'lib_type_institution' => 'Clinique',                       'code_type_categorie_ins' => 'TCINS_0003'],
            ['code_type_institution' => 'TPINS_0010', 'lib_type_institution' => 'Centre Hospitalier Universitaire','code_type_categorie_ins' => 'TCINS_0003'],
            ['code_type_institution' => 'TPINS_0011', 'lib_type_institution' => 'Hôpital spécialisé',             'code_type_categorie_ins' => 'TCINS_0003'],
            ['code_type_institution' => 'TPINS_0012', 'lib_type_institution' => 'Hôpital général',                'code_type_categorie_ins' => 'TCINS_0003'],
            ['code_type_institution' => 'TPINS_0013', 'lib_type_institution' => 'Polyclinique',                   'code_type_categorie_ins' => 'TCINS_0003'],
            ['code_type_institution' => 'TPINS_0014', 'lib_type_institution' => 'Centre Médical Spécialisé',      'code_type_categorie_ins' => 'TCINS_0003'],
            ['code_type_institution' => 'TPINS_0015', 'lib_type_institution' => 'Hôpital de base',                'code_type_categorie_ins' => 'TCINS_0003'],
            ['code_type_institution' => 'TPINS_0016', 'lib_type_institution' => 'Centre de santé intégré',        'code_type_categorie_ins' => 'TCINS_0003'],
            ['code_type_institution' => 'TPINS_0017', 'lib_type_institution' => 'Hôpital de référence',           'code_type_categorie_ins' => 'TCINS_0003'],
            ['code_type_institution' => 'TPINS_0018', 'lib_type_institution' => 'Hopital Militaire',              'code_type_categorie_ins' => 'TCINS_0003'],
            ['code_type_institution' => 'TPINS_0019', 'lib_type_institution' => "Centre D'hygiène",               'code_type_categorie_ins' => 'TCINS_0001'],
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
