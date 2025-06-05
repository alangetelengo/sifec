<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\Commune;

class CommuneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('set foreign_key_checks = 0');
        DB::statement('truncate tr_commune');

        $data = [
            ["code_commune" => "COM_0001","lib_commune" => "BRAZZAVILLE","code_departement" => "DPT_0001"]
        ];


        foreach ($data as $d){
            Commune::create($d);
        }
    }
}
