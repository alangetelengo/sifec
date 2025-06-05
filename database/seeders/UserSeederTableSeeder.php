<?php

namespace Database\Seeders;

use App\Models\User;
use App\Sifec\Sifec;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement("SET FOREIGN_KEY_CHECKS=0");
        DB::statement("TRUNCATE tr_user");

        $user = new User;
        $user->code_personne = "PRS_00000001";
        $user->pseudo = "066835332";
        $user->password = Hash::make("123456");
        $user->email = "alange@gmail.com";
        $user->code_user = Sifec::genererCodeUniqueReferentiel($user,"code_user",8,"USR_");
        $user->save();
    }
}
