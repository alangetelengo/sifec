<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\SituationMatrimoniale;

class SituationMatrimonialeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('set foreign_key_checks = 0');
        DB::statement('truncate tr_situation_matrimoniale');

        $donnes = ["Mariage état civil","Pré mariage","Célibataire","Union libre","Divorcé(e)","Veuf(ve)"];

        $data = [];
        for($i = 0; $i < count($donnes); $i++){
            $intCode = $i + 1;
            $count = $intCode;
            $strCode = "SMAT_".str_pad($count,4,"0",STR_PAD_LEFT);
            $data[] = ["code_situation_matrimoniale"=>$strCode,'lib_situation_matrimoniale'=>$donnes[$i]];
        }
        foreach ($data as $d){
            SituationMatrimoniale::create($d);
        }
    }
}
