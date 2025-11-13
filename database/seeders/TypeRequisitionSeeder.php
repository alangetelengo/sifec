<?php

namespace Database\Seeders;

use App\Models\TypeRequisition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeRequisitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [
                "requisition aux fins d'inscription à la déclaration tardive",
                "requisition aux fins de reconstitution de l'acte",
                "requisition aux fins de transcription de l'acte",
                "requisition aux fins de rectification de l'acte",
                "dispense aux fins de lieu de célébration du mariage",
                "dispense aux fins de délai de célébration du mariage"
            ];

        DB::statement('set foreign_key_checks = 0');
        TypeRequisition::truncate();

        $donnees = [];
        for ($i = 0; $i < count($data); $i++) {
            $intCode = $i + 1;
            $count = $intCode;
            $strCode = "TPRG_".str_pad($count,4,"0",STR_PAD_LEFT);
            $donnees[] = ["code_type_requisition"=>$strCode, "lib_type_requisition"=>$data[$i]];


        }

        foreach ($donnees as $d) {
            TypeRequisition::create($d);
        }
    }
}
