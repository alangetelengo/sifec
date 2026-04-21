<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ListSifecUsers extends Command
{
    protected $signature = 'sifec:list-users {search? : Rechercher par email, nom ou code}';

    protected $description = 'Lister les utilisateurs SIFEC';

    public function handle()
    {
        $search = $this->argument('search');

        $query = DB::table('tr_user')
            ->select('code_user', 'email', 'pseudo', 'code_personne');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhere('code_user', 'like', "%{$search}%")
                    ->orWhere('pseudo', 'like', "%{$search}%");
            });
        }

        $users = $query->take(20)->get();

        if ($users->isEmpty()) {
            $this->error('Aucun utilisateur trouvé');

            return 1;
        }

        $this->info("Utilisateurs trouvés:\n");

        foreach ($users as $user) {
            $this->line("Code: {$user->code_user}");
            $this->line('Pseudo: '.($user->pseudo ?? 'N/A'));
            $this->line('Email: '.($user->email ?? 'N/A'));
            $this->line('---');
        }

        return 0;
    }
}
