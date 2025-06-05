<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\Departement;

class DepartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('set foreign_key_checks = 0');
        DB::statement('truncate tr_departement');

        $donnes = ["BRAZZAVILLE", "POINTE-NOIRE", "LIKOUALA", "SANGHA", "CUVETTE-OUEST", "CUVETTE","PLATEAUX","POOL", "LEKOUMOU","BOUENZA","NIARI","KOUILOU"];
        $data = [];
        for($i = 0; $i < count($donnes); $i++){
            $intCode = $i + 1;
            $count = $intCode;
            $strCode = "DPT_".str_pad($count,4,"0",STR_PAD_LEFT);
            $data[] = ["code_departement"=>$strCode,'lib_departement'=>$donnes[$i]];
        }

        foreach ($data as $d){
            Departement::create($d);
        }
    }
}
