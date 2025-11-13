<?php

namespace Database\Seeders;

use App\Models\TypeJugement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeJugementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [
                "jugement supplétif",
                "jugement d'homologation",
                "jugement d'annulation de l'acte",
                "jugement d'adoption",
                "jugement d'autorisation"
            ];

        DB::statement('set foreign_key_checks = 0');
        TypeJugement::truncate();

        $donnees = [];
        for ($i = 0; $i < count($data); $i++) {
            $intCode = $i + 1;
            $count = $intCode;
            $strCode = "TPRG_".str_pad($count,4,"0",STR_PAD_LEFT);
            $donnees[] = ["code_type_jugement"=>$strCode, "lib_type_jugement"=>$data[$i]];
        }

        foreach ($donnees as $d) {
            TypeJugement::create($d);
        }
    }
}
