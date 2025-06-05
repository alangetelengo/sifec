<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\Nationalite;

class NationaliteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0");
        DB::statement("truncate tr_nationalite");

        $donnes = ["CONGOLAIS(E)","GABONAIS(E)","CAMEROUNAIS(E)","SENEGALAIS(E)","ESPAGNOL(E)","MAROCAIN(E)","LYBANAIS(E)","IVOIRIEN(E)","NON DECLARE"];

        $data = [];
        for($i = 0; $i < count($donnes); $i++){
            $intCode = $i + 1;
            $count = $intCode;
            $strCode = "NAT_".str_pad($count,4,"0",STR_PAD_LEFT);
            $data[] = ["code_nationalite"=>$strCode,'lib_nationalite'=>$donnes[$i]];
        }


        foreach($data as $d){
            Nationalite::create($d);
        }
    }
}
