<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Referentiel\Entities\Religion;

class ReligionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $donnes = ['CHRISTIANISME', 'ISLAMIQUE', 'AUTRE'];

        $data = [];
        for ($i = 0; $i < count($donnes); $i++) {
            $intCode = $i + 1;
            $count = $intCode;
            $strCode = 'RELI_'.str_pad($count, 4, '0', STR_PAD_LEFT);
            $data[] = ['code_religion' => $strCode, 'lib_religion' => $donnes[$i]];
        }

        foreach ($data as $d) {
            Religion::updateOrCreate(
                ['code_religion' => $d['code_religion']],
                ['lib_religion' => $d['lib_religion']]
            );
        }
    }
}
