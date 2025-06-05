<?php

namespace Database\Seeders;

use App\Sifec\Sifec;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\Filiation;

class FiliationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('TRUNCATE tr_filiation');


        $donnes = ["PERE","MERE","TANTE PATERNELLE","TANTE MATERNELLE","FRERE",
        "SOEUR","COUSIN (E)","AUTRE","ONCLE PATERNEL"];

        $data = [];
        for($i = 0; $i < count($donnes); $i++){
            $intCode = $i + 1;
            $count = $intCode;
            $strCode = "FIL_".str_pad($count,4,"0",STR_PAD_LEFT);
            $data[] = ["code_filiation"=>$strCode,'lib_filiation'=>$donnes[$i]];
        }

        foreach ($data as $d){
            Filiation::create($d);
        }
    }
}
