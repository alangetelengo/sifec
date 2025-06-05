<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\Regime;

class RegimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('set foreign_key_checks = 0');
        DB::statement('truncate tr_regime');

        $donnes = ["Régime de la communauté réduite aux acquêts(RCA)","Régime de la séparation de biens(RSB)","Régime de la communauté conventionnelle(RCC)"];

        $data = [];
        for($i = 0; $i < count($donnes); $i++){
            $intCode = $i + 1;
            $count = $intCode;
            $strCode = "RGIM_".str_pad($count,4,"0",STR_PAD_LEFT);
            $data[] = ["code_regime"=>$strCode,'lib_regime'=>trim($donnes[$i]) ];
        }

        foreach ($data as $d){
            Regime::create($d);
        }
    }
}
