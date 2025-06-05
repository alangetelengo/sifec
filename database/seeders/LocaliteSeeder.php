<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\Localite;

class LocaliteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('set foreign_key_checks = 0');
        DB::statement('truncate tr_localite');

        $data = [
            ["code_localite"=>"LOC_0001",'lib_localite'=> "BRAZZAVILLE","code_type_localite"=>'TPLOC_0001',"code_localite_parent"=>NULL],
            ["code_localite"=>"LOC_0002",'lib_localite'=> "POINTE-NOIRE","code_type_localite"=>'TPLOC_0001',"code_localite_parent"=>NULL],
            ["code_localite"=>"LOC_0003",'lib_localite'=> "LIKOUALA","code_type_localite"=>'TPLOC_0001',"code_localite_parent"=>NULL],
            ["code_localite"=>"LOC_0004",'lib_localite'=> "SANGHA","code_type_localite"=>'TPLOC_0001',"code_localite_parent"=>NULL],
            ["code_localite"=>"LOC_0005",'lib_localite'=> "CUVETTE-OUEST","code_type_localite"=>'TPLOC_0001',"code_localite_parent"=>NULL],
            ["code_localite"=>"LOC_0006",'lib_localite'=> "CUVETTE","code_type_localite"=>'TPLOC_0001',"code_localite_parent"=>NULL],
            ["code_localite"=>"LOC_0007",'lib_localite'=> "PLATEAUX","code_type_localite"=>'TPLOC_0001',"code_localite_parent"=>NULL],
            ["code_localite"=>"LOC_0008",'lib_localite'=> "POOL","code_type_localite"=>'TPLOC_0001',"code_localite_parent"=>NULL],
            ["code_localite"=>"LOC_0009",'lib_localite'=> "LEKOUMOU","code_type_localite"=>'TPLOC_0001',"code_localite_parent"=>NULL],
            ["code_localite"=>"LOC_0010",'lib_localite'=> "BOUENZA","code_type_localite"=>'TPLOC_0001',"code_localite_parent"=>NULL],
            ["code_localite"=>"LOC_0011",'lib_localite'=> "NIARI","code_type_localite"=>'TPLOC_0001',"code_localite_parent"=>NULL],
            ["code_localite"=>"LOC_0012",'lib_localite'=> "KOUILOU","code_type_localite"=>'TPLOC_0001',"code_localite_parent"=>NULL],
            ["code_localite"=>"LOC_0013",'lib_localite'=> "BRAZZAVILLE","code_type_localite"=>'TPLOC_0003',"code_localite_parent"=>"LOC_0001"]
        ];
        // $donnees = [];
        // for($i = 0; $i < count($data); $i++){
        //     $count = $i + 1;
        //     $strCode = "LOC_".str_pad($count,4,"0",STR_PAD_LEFT);
        //     $donnees[] = ["code_localite"=> $strCode,'lib_localite'=> $data[$i],'code_type_localite'=>$data[$i],"code_parent"=>$data[$i]];

        // }

        foreach ($data as $d){
            Localite::create($d);
        }
    }
}
