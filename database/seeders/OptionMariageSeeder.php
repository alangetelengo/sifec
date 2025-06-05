<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\OptionMariage;

class OptionMariageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('set foreign_key_checks = 0');
        DB::statement('truncate tr_option_mariage');

        $donnes = ["Monogamie","Polygamie"];

        $data = [];
        for($i = 0; $i < count($donnes); $i++){
            $intCode = $i + 1;
            $count = $intCode;
            $strCode = "OPM_".str_pad($count,4,"0",STR_PAD_LEFT);
            $data[] = ["code_option_mariage"=>$strCode,'lib_option_mariage'=>$donnes[$i]];
        }

        foreach ($data as $d){
            OptionMariage::create($d);
        }
    }
}
