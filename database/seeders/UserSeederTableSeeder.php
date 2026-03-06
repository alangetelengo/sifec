<?php

namespace Database\Seeders;

use App\Models\User;
use App\Sifec\Sifec;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Crée le compte administrateur système initial.
 * Identifiants de test : pseudo 066835335 / mot de passe : 123456
 * À modifier impérativement en production.
 */
class UserSeederTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('TRUNCATE tr_user');

        $user = new User();
        $user->code_user    = 'USR_00000001';
        $user->code_personne = 'PRS_00000001';
        $user->pseudo       = '066835335';
        $user->email        = 'alange@acsi.cg';
        $user->password     = Hash::make('123456');
        $user->status       = 1;
        $user->save();
    }
}
