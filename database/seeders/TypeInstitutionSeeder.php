<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\TypeInstitution;

class TypeInstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('set foreign_key_checks = 0');
        DB::statement('truncate tr_type_institution');



        //$d = ["Mairie","Ambassade","Prison","Pompes funèbres","Formation sanitaire","Autre formation sanitaire (AFS)","Tribunal","Sous-préfecture","Communauté urbaine"];
        $donnes = ["Mairie","Ambassade","Prison","Pompes funèbres","Formation sanitaire","Autre formation sanitaire (AFS)","Tribunal"];
        // $typecentres = ["Centre Principal","Centre Secondaire"];

        // $data = [];
        // for($i = 0; $i < count($d); $i++){
        //     $intCode = $i + 1;
        //     $count = $intCode;
        //     $strCode = "TPINS_".str_pad($count,4,"0",STR_PAD_LEFT);
        //     $tcindex = 0;
        //     TypeInstitution::create([
        //         "lib_categorie"=> $i < 2 ? $typecentres[0] : $typecentres[1],
        //         "lib_type_institution"=>$d[$i],
        //         "code_type_institution"=>$strCode
        //     ]);
        // }
        $data = [
            ["code_type_institution"=>"TPINS_0001",'lib_categorie'=> "Tribunal","lib_type_institution"=>"Tribunal"],
            ["code_type_institution"=>"TPINS_0002",'lib_categorie'=> "Mairie","lib_type_institution"=>"Centre Principal"],
            ["code_type_institution"=>"TPINS_0003",'lib_categorie'=> "Pompes funèbres","lib_type_institution"=>"Centre Principal"],
            ["code_type_institution"=>"TPINS_0004",'lib_categorie'=> "Formation sanitaire","lib_type_institution"=>"Centre Secondaire"],
            ["code_type_institution"=>"TPINS_0005",'lib_categorie'=> "Ambassade","lib_type_institution"=>"Centre Principal"],
            ["code_type_institution"=>"TPINS_0006",'lib_categorie'=> "Cour d'appel","lib_type_institution"=>"Cour d'appel"],
            ["code_type_institution"=>"TPINS_0007",'lib_categorie'=> "Préfecture","lib_type_institution"=>"Préfecture"],
        ];

        foreach ($data as $d){
            TypeInstitution::create($d);
        }


    }
}
