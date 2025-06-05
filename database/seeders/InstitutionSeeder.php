<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\Institution;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('TRUNCATE tr_institution');

        $data = [
            ["code_institution"=>"INS_0001", 'lib_institution'=> "TRIBUNAL DE GRANDE INSTANCE DE BRAZZAVILLE", "code_institution_parent"=>"INS_0006", "code_type_institution"=>"TPINS_0001", "code_commune"=>"COM_0001","sceau"=>"sceau/7xb1mXoT6LXLckelP3OCutx3izCWOPkru4pTn5VK.png"],
            ["code_institution"=>"INS_0002", 'lib_institution'=> "TRIBUNAL D'INSTANCE DE BACONGO MAKELEKELE",  "code_institution_parent"=>"INS_0006", "code_type_institution"=>"TPINS_0001","code_arrondissement"=>"ARR_0001","sceau"=>"sceau/MsnzwpiGhqEoJmFCsYjfqhwETUJbtDYdpqjVYH55.png"],
            ["code_institution"=>"INS_0003", 'lib_institution'=> "MAIRIE DE MAKELEKELE",  "code_institution_parent"=>"INS_0002","code_type_institution"=>"TPINS_0002",  "code_arrondissement"=>"ARR_0001"],
            ["code_institution"=>"INS_0004", 'lib_institution'=> "POMPES FUNEBRES MUNICIPALES",  "code_institution_parent"=>"INS_0001","code_type_institution"=>"TPINS_0003", "code_arrondissement"=>"ARR_0002"],
            ["code_institution"=>"INS_0005", 'lib_institution'=> "HÔPITAL DE REFERENCE DE MAKELEKELE",  "code_institution_parent"=>'INS_0003', "code_pompe_funebre"=>"INS_0004","code_type_institution"=>"TPINS_0004", "code_arrondissement"=>"ARR_0001"],
            ["code_institution"=>"INS_0006", 'lib_institution'=> "COUR D'APPEL DE BRAZZAVILLE","code_type_institution"=>"TPINS_0006", "code_commune"=>"COM_0001"],
            ["code_institution"=>"INS_0007", 'lib_institution'=> "CENTRE D'HYGIENE DE BRAZZAVILLE","code_type_institution"=>"TPINS_0004","code_pompe_funebre"=>"INS_0004", "code_arrondissement"=>"ARR_0001"],
            ["code_institution"=>"INS_0008", 'lib_institution'=> "PREFECTURE DU BRAZZAVILLE","code_type_institution"=>"TPINS_0007", "code_commune"=>"COM_0001"],
            ["code_institution"=>"INS_0009", 'lib_institution'=> "MAIRIE CENTRALE", "code_institution_parent"=>"INS_0001", "code_type_institution"=>"TPINS_0002", "code_commune"=>"COM_0001"]
        ];

        foreach ($data as $d){
            Institution::create($d);
        }
    }
}
