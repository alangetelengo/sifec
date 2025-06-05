<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\LieuSurvenance;

class LieuSurvenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('set foreign_key_checks = 0');
        DB::statement('truncate tr_lieu_survenance');


        $donnes = ["Formation sanitaire","Centre carcéral","Domicile","Navire","Avion","Etranger"];

        $data = [];
        for($i = 0; $i < count($donnes); $i++){
            $intCode = $i + 1;
            $count = $intCode;
            $strCode = "LSURV_".str_pad($count,4,"0",STR_PAD_LEFT);
            $data[] = ["code_lieu_survenance"=>$strCode,'lib_lieu_survenance'=>$donnes[$i]];
        }


        foreach ($data as $d){
            LieuSurvenance::create($d);
        }
    }
}
