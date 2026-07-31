<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\Fonction;

class FonctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('TRUNCATE tr_fonction');

        $donnes = [
            'Sous préfet',
            "Officier d'état civil",
            "Officier d'état civil délégué",
            'Agent mairie',
            'Agent pompes funèbres',
            'Agent formation sanitaire',
            "Agent centre d'hygiène",
            'Agent tribunal',
            'Président du tribunal',
            'Procureur général',
            'Super administrateur',
            'Directeur pompes funèbres',
            'DGAT',
            'Agent mairie centrale',
            'DEC',
            'Chef de service gestion des malades', // FONC_0016
            "Agent bureau d'enregistrement de décès",
            'Procureur de la République',
            'Agent ambassade',
            'Agent sanitaire (naissance)',
            'Consule',
            'Gouverneur',
            'Ministre',
            "Chef de service état civil", // FONC_0024
        ];

        $data = [];
        for ($i = 0; $i < count($donnes); $i++) {
            $intCode = $i + 1;
            $count = $intCode;
            $strCode = 'FONC_'.str_pad($count, 4, '0', STR_PAD_LEFT);
            $data[] = ['code_fonction' => $strCode, 'lib_fonction' => $donnes[$i]];
        }

        foreach ($data as $d) {
            Fonction::create($d);
        }
    }
}
