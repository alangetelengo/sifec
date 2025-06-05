<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\InstitutionUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Authentification\Entities\Fonctionnalite;

class InstitutionUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('TRUNCATE tr_ins_user');
        $fnalites = Fonctionnalite::pluck("code_fonctionnalite");
        $user = User::first();

        $data = [
            ["cui"=>"CUI_00000001",'code_institution'=> "INS_0046","code_user"=>"USR_00000001","code_fonction"=>"FONC_0002","active"=>1]
        ];

        foreach ($data as $d){
            InstitutionUser::create($d);
        }
        //assignation des fonctionnalites à cet user
        $user->fonctionnalites()->sync($fnalites);


    }
}
