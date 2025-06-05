<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\TypeDocument;

class TypeDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('set foreign_key_checks = 0');
        DB::statement('truncate tr_type_document');

        $donnes = ["Carte nationale d'identité","Passport","Permis de conduire","Carte d'étudiant","Carte scolaire","Carte consulaire"];

        $data = [];
        for($i = 0; $i < count($donnes); $i++){
            $intCode = $i + 1;
            $count = $intCode;
            $strCode = "TDOC_".str_pad($count,4,"0",STR_PAD_LEFT);
            $data[] = ["code_type_document"=>$strCode,'lib_type_document'=>$donnes[$i]];
        }

        foreach ($data as $d){
            TypeDocument::create($d);
        }
    }
}
