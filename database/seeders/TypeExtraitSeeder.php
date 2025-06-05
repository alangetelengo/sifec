<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\TypeExtrait;

class TypeExtraitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('set foreign_key_checks = 0');
        DB::statement('truncate tr_type_extrait');

        $donnes = ["Extrait d'acte de naissance", "Extrait d'acte de mariage", "Extrait d'acte de décès"];
        $data = [];
        for($i = 0; $i < count($donnes); $i++){
            $intCode = $i + 1;
            $count = $intCode;
            $strCode = "TEX_".str_pad($count,4,"0",STR_PAD_LEFT);
            $data[] = ["code_type_extrait"=>$strCode,'lib_type_extrait'=>$donnes[$i]];
        }

        foreach ($data as $d){
            TypeExtrait::create($d);
        }
    }
}
