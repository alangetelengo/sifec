<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\TypeRegistre;

class TypeRegistreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = ["NAISSANCE","MARIAGE","DIVORCE","DECES"];

        DB::statement('set foreign_key_checks = 0');
        DB::statement("truncate tr_type_registre");

        $donnees = [];
        for ($i = 0; $i < count($data); $i++) {
            $intCode = $i + 1;
            $count = $intCode;
            $strCode = "TPRG_".str_pad($count,4,"0",STR_PAD_LEFT);
            $donnees[] = ["code_type_registre"=>$strCode, "lib_type_registre"=>$data[$i]];


        }

        foreach ($donnees as $d) {
            TypeRegistre::create($d);
        }
    }
}
