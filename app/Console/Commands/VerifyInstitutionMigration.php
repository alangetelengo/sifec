<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class VerifyInstitutionMigration extends Command
{
    protected $signature = 'institution:verify-migration';
    protected $description = 'Vérifier que la migration peut être exécutée';

    public function handle()
    {
        $withoutLocalite = DB::table('tr_institution')
            ->whereNull('code_localite')
            ->where(function($query) {
                $query->whereNotNull('code_commune')
                    ->orWhereNotNull('code_district')
                    ->orWhereNotNull('code_arrondissement')
                    ->orWhereNotNull('code_communaute_urbaine');
            })
            ->count();
        
        if ($withoutLocalite === 0) {
            $this->info("✅ Toutes les institutions ont un code_localite. La migration de suppression peut être exécutée.");
            return 0;
        } else {
            $this->error("❌ {$withoutLocalite} institutions n'ont pas de code_localite mais utilisent les champs obsolètes.");
            return 1;
        }
    }
}

