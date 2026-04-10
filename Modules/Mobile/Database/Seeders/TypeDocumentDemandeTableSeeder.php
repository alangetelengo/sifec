<?php

namespace Modules\Mobile\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Modules\Mobile\Entities\TypeDocumentDemande;

class TypeDocumentDemandeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('set foreign_key_checks = 0');
        DB::statement("truncate tr_type_document_demande");

        $donnees = ["Copie","Extrait"];
        $data = [];
        for($i = 0; $i < count($donnees); $i++){
            $count = $i+1;
            $strCode = "TDD_".str_pad($count,4,"0",STR_PAD_LEFT);
            $data[] = ["code_type_document_demande"=>$strCode, "lib_type_document_demande"=>$donnees[$i]];
        }
        foreach($data as $d){
            TypeDocumentDemande::create($d);
        }
    }
}
