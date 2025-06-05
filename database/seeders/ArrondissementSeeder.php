<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\Arrondissement;

class ArrondissementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('set foreign_key_checks = 0');
        DB::statement('truncate tr_arrondissement');

        $donnees = ["MAKELEKELE","BACONGO","POTO-POTO","MOUNGALI","OUENZE","TALANGAÏ","M'FILOU","MADIBOU","DJIRI"];

        $data = [];

        for($i = 0; $i < count($donnees); $i++){
            $count = $i + 1;
            $str_code = "ARR_".str_pad($count,4,"0", STR_PAD_LEFT);
            $data[] = ["code_commune"=>"COM_0001","code_arrondissement"=>$str_code,"lib_arrondissement"=>$donnees[$i]];
        }

        foreach ($data as $d){
            Arrondissement::create($d);
        }
    }
}
