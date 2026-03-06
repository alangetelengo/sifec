<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeCategorieInstitutionSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('TRUNCATE tr_type_categorie_ins');

        DB::table('tr_type_categorie_ins')->insert([
            ['code_type_categorie_ins' => 'TCINS_0001', 'lib_type_categorie_institution' => "centre d'état civil",   'image_illustrative' => 'img-bkg-accueil/cec.jpg'],
            ['code_type_categorie_ins' => 'TCINS_0002', 'lib_type_categorie_institution' => 'Tribunal',              'image_illustrative' => 'img-bkg-accueil/tribunal.jpg'],
            ['code_type_categorie_ins' => 'TCINS_0003', 'lib_type_categorie_institution' => 'Formation sanitaire',   'image_illustrative' => 'img-bkg-accueil/fs.jpg'],
            ['code_type_categorie_ins' => 'TCINS_0004', 'lib_type_categorie_institution' => 'Ambassade',             'image_illustrative' => 'img-bkg-accueil/cec.jpg'],
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
