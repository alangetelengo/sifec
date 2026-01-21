<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Referentiel\Entities\Institution;
use Illuminate\Support\Facades\DB;

class CheckInstitutionDataBeforeMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'institution:check-data-before-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifier les données existantes avant de supprimer les champs obsolètes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔍 Vérification des données existantes dans tr_institution...');
        $this->newLine();

        // Compter le total d'institutions
        $total = Institution::count();
        $this->line("📊 Total d'institutions : {$total}");
        $this->newLine();

        // Vérifier les institutions qui utilisent les champs obsolètes
        $this->info('🔍 Vérification des champs obsolètes...');
        
        $withCommune = DB::table('tr_institution')
            ->whereNotNull('code_commune')
            ->where('code_commune', '!=', '')
            ->count();
        
        $withDistrict = DB::table('tr_institution')
            ->whereNotNull('code_district')
            ->where('code_district', '!=', '')
            ->count();
        
        $withArrondissement = DB::table('tr_institution')
            ->whereNotNull('code_arrondissement')
            ->where('code_arrondissement', '!=', '')
            ->count();
        
        $withCommunauteUrbaine = DB::table('tr_institution')
            ->whereNotNull('code_communaute_urbaine')
            ->where('code_communaute_urbaine', '!=', '')
            ->count();

        $this->line("   - Institutions avec code_commune : {$withCommune}");
        $this->line("   - Institutions avec code_district : {$withDistrict}");
        $this->line("   - Institutions avec code_arrondissement : {$withArrondissement}");
        $this->line("   - Institutions avec code_communaute_urbaine : {$withCommunauteUrbaine}");
        $this->newLine();

        // Vérifier les institutions qui utilisent le nouveau système
        $withLocalite = DB::table('tr_institution')
            ->whereNotNull('code_localite')
            ->where('code_localite', '!=', '')
            ->count();
        
        $this->info('✅ Vérification du nouveau système...');
        $this->line("   - Institutions avec code_localite : {$withLocalite}");
        $this->newLine();

        // Vérifier les institutions qui n'ont ni ancien ni nouveau système
        $withoutAny = DB::table('tr_institution')
            ->whereNull('code_localite')
            ->where(function($query) {
                $query->whereNull('code_commune')
                    ->whereNull('code_district')
                    ->whereNull('code_arrondissement')
                    ->whereNull('code_communaute_urbaine');
            })
            ->count();
        
        if ($withoutAny > 0) {
            $this->warn("   ⚠️  Institutions sans localisation : {$withoutAny}");
        }
        $this->newLine();

        // Vérifier les institutions qui ont à la fois ancien et nouveau système
        $withBoth = DB::table('tr_institution')
            ->whereNotNull('code_localite')
            ->where(function($query) {
                $query->whereNotNull('code_commune')
                    ->orWhereNotNull('code_district')
                    ->orWhereNotNull('code_arrondissement')
                    ->orWhereNotNull('code_communaute_urbaine');
            })
            ->count();
        
        if ($withBoth > 0) {
            $this->info("   ℹ️  Institutions avec les deux systèmes : {$withBoth}");
        }
        $this->newLine();

        // Afficher quelques exemples d'institutions avec champs obsolètes
        if ($withCommune > 0 || $withDistrict > 0 || $withArrondissement > 0 || $withCommunauteUrbaine > 0) {
            $this->warn('⚠️  Institutions utilisant les champs obsolètes :');
            $this->newLine();
            
            $examples = DB::table('tr_institution')
                ->select('code_institution', 'lib_institution', 'code_commune', 'code_district', 'code_arrondissement', 'code_communaute_urbaine', 'code_localite')
                ->where(function($query) {
                    $query->whereNotNull('code_commune')
                        ->orWhereNotNull('code_district')
                        ->orWhereNotNull('code_arrondissement')
                        ->orWhereNotNull('code_communaute_urbaine');
                })
                ->limit(10)
                ->get();
            
            $headers = ['Code', 'Libellé', 'Commune', 'District', 'Arrondissement', 'Comm. Urbaine', 'Localité'];
            $rows = [];
            
            foreach ($examples as $inst) {
                $rows[] = [
                    $inst->code_institution,
                    $inst->lib_institution,
                    $inst->code_commune ?? '-',
                    $inst->code_district ?? '-',
                    $inst->code_arrondissement ?? '-',
                    $inst->code_communaute_urbaine ?? '-',
                    $inst->code_localite ?? '-'
                ];
            }
            
            $this->table($headers, $rows);
            $this->newLine();
        }

        // Résumé et recommandations
        $this->info('📋 Résumé et Recommandations :');
        $this->newLine();
        
        if ($withCommune > 0 || $withDistrict > 0 || $withArrondissement > 0 || $withCommunauteUrbaine > 0) {
            $this->warn('⚠️  ATTENTION : Des institutions utilisent encore les champs obsolètes !');
            $this->line('   Avant d\'exécuter la migration, vous devez :');
            $this->line('   1. Migrer les données des champs obsolètes vers code_localite');
            $this->line('   2. Vérifier que toutes les institutions ont un code_localite valide');
            $this->line('   3. Tester que tout fonctionne correctement');
            $this->newLine();
            
            if ($this->confirm('Voulez-vous voir un script de migration des données ?', false)) {
                $this->showMigrationScript();
            }
        } else {
            $this->info('✅ Aucune institution n\'utilise les champs obsolètes.');
            $this->line('   La migration peut être exécutée en toute sécurité.');
        }

        return 0;
    }

    private function showMigrationScript()
    {
        $this->newLine();
        $this->info('📝 Script de migration suggéré :');
        $this->line('');
        $this->line('// Migrer code_commune vers code_localite');
        $this->line('DB::table(\'tr_institution\')');
        $this->line('    ->whereNotNull(\'code_commune\')');
        $this->line('    ->whereNull(\'code_localite\')');
        $this->line('    ->update([\'code_localite\' => DB::raw(\'code_commune\')]);');
        $this->line('');
        $this->line('// Répéter pour district, arrondissement, communaute_urbaine');
        $this->line('// selon la logique métier de votre application');
        $this->newLine();
    }
}

