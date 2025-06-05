<?php

namespace Modules\Mobile\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class MobileDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            // TypeActeTableSeeder::class,
            TypeDocumentDemandeTableSeeder::class
        ]);
    }
}
