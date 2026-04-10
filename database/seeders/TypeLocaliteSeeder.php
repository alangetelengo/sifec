<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\TypeLocalite;

class TypeLocaliteSeeder extends Seeder
{
    /**
     * Données alignées sur tr_type_localite.sql (dump référentiel).
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('TRUNCATE TABLE `tr_type_localite`');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $rows = [
            ['code_type_localite' => 'TPLOC_0001', 'lib_type_localite' => 'DEPARTEMENT', 'type_cec' => null, 'created_at' => '2026-03-06 17:55:11', 'updated_at' => '2026-03-06 17:55:11', 'deleted_at' => null],
            ['code_type_localite' => 'TPLOC_0002', 'lib_type_localite' => 'DISTRICT', 'type_cec' => null, 'created_at' => '2026-03-06 17:55:11', 'updated_at' => '2026-03-06 17:55:11', 'deleted_at' => null],
            ['code_type_localite' => 'TPLOC_0003', 'lib_type_localite' => 'COMMUNE', 'type_cec' => null, 'created_at' => '2026-03-06 17:55:11', 'updated_at' => '2026-03-06 17:55:11', 'deleted_at' => null],
            ['code_type_localite' => 'TPLOC_0004', 'lib_type_localite' => 'ARRONDISSEMENT', 'type_cec' => null, 'created_at' => '2026-03-06 17:55:11', 'updated_at' => '2026-03-06 17:55:11', 'deleted_at' => null],
            ['code_type_localite' => 'TPLOC_0005', 'lib_type_localite' => 'COMMUNAUTE URBAINE', 'type_cec' => null, 'created_at' => '2026-03-06 17:55:11', 'updated_at' => '2026-03-06 17:55:11', 'deleted_at' => null],
            ['code_type_localite' => 'TPLOC_0006', 'lib_type_localite' => 'COMMUNAUTE RURALE', 'type_cec' => null, 'created_at' => '2026-03-06 17:55:11', 'updated_at' => '2026-03-06 17:55:11', 'deleted_at' => null],
            ['code_type_localite' => 'TPLOC_0007', 'lib_type_localite' => 'QUARTIER', 'type_cec' => null, 'created_at' => '2026-03-06 17:55:11', 'updated_at' => '2026-03-06 17:55:11', 'deleted_at' => null],
            ['code_type_localite' => 'TPLOC_0008', 'lib_type_localite' => 'VILLAGE', 'type_cec' => null, 'created_at' => '2026-03-06 17:55:11', 'updated_at' => '2026-03-06 17:55:11', 'deleted_at' => null],
            ['code_type_localite' => 'TPLOC_0009', 'lib_type_localite' => 'NON DECLARE', 'type_cec' => null, 'created_at' => '2026-03-06 17:55:11', 'updated_at' => '2026-03-06 17:55:11', 'deleted_at' => null],
        ];

        foreach ($rows as $row) {
            TypeLocalite::create($row);
        }
    }
}
