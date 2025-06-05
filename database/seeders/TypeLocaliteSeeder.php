<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\TypeLocalite;

class TypeLocaliteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('set foreign_key_checks = 0');
        DB::statement('truncate tr_type_localite');

        $donnes = ["DEPARTEMENT","DISTRICT","COMMUNE","ARRONDISSEMENT","COMMUNAUTE URBAINE",
        "COMMUNAUTE RURALE","QUARTIER","VILLAGE"];

        $data = [];
        for($i = 0; $i < count($donnes); $i++){
            $intCode = $i + 1;
            $count = $intCode;
            $strCode = "TPLOC_".str_pad($count,4,"0",STR_PAD_LEFT);
            $data[] = ["code_type_localite"=>$strCode,'lib_type_localite'=>$donnes[$i]];
        }

        foreach ($data as $d){
            TypeLocalite::create($d);
        }
    }
}
