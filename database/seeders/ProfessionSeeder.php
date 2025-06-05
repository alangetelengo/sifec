<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\Profession;

class ProfessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('TRUNCATE tr_profession');

        $donnes = ["Comptable","Informaticien","Commerçant(e)","Juge","Medécin","Docteur","Journaliste","Plombier","Mécanicien(e)","Non déclaré"];

        $data = [];
        for($i = 0; $i < count($donnes); $i++){
            $intCode = $i + 1;
            $count = $intCode;
            $strCode = "PROF_".str_pad($count,4,"0",STR_PAD_LEFT);
            $data[] = ["code_profession"=>$strCode,'lib_profession'=>$donnes[$i]];
        }

        foreach ($data as $d){
            Profession::create($d);
        }
    }
}
