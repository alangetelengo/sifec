<?php

namespace Modules\Mobile\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Mobile\Entities\TypeActe;
use Illuminate\Database\Eloquent\Model;

class TypeActeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('set foreign_key_checks = 0');
        DB::statement("truncate tr_type_acte");

        $donnees = ["NAISSANCE","MARIAGE","DIVORCE","DECES"];
        $data = [];

        for($i = 0; $i < count($donnees); $i++){
            $count = $i +1;
            $strCode = "TAC_".str_pad($count,4,"0",STR_PAD_LEFT);
            $data[] = ["code_type_acte"=>$strCode,"lib_type_acte"=>$donnees[$i]];
        }

       foreach($data as $d){
            TypeActe::create($d);
       }
    }
}
