<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Referentiel\Entities\Localite;
use App\Sifec\Sifec;
use Illuminate\Support\Facades\DB;

class TestLocaliteCodeGeneration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'localite:test-code-generation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tester la génération de code pour les localités';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🧪 Test de génération de code pour les localités...');
        $this->newLine();
        
        // Vérifier le maximum actuel
        $this->info('📊 Vérification du maximum actuel...');
        $max = Localite::selectRaw('MAX(CAST(SUBSTRING(code_localite, 5) AS UNSIGNED)) as max_num')
            ->first();
        $maxNum = $max->max_num ?? 0;
        $this->line("   Maximum numérique actuel : {$maxNum}");
        $this->line("   Code maximum actuel : LOC_{$maxNum}");
        
        // Vérifier si LOC_4282 existe (avec et sans soft deletes)
        $this->newLine();
        $this->info('🔍 Vérification du code LOC_4282...');
        $exists4282 = Localite::where('code_localite', 'LOC_4282')->exists();
        $exists4282WithTrashed = Localite::withTrashed()->where('code_localite', 'LOC_4282')->exists();
        if ($exists4282) {
            $this->warn('   ⚠️  LOC_4282 existe déjà dans la base de données (actif)');
            $localite4282 = Localite::where('code_localite', 'LOC_4282')->first();
            $this->line("   Libellé : {$localite4282->lib_localite}");
        } elseif ($exists4282WithTrashed) {
            $this->warn('   ⚠️  LOC_4282 existe mais est supprimé (soft delete)');
            $localite4282 = Localite::withTrashed()->where('code_localite', 'LOC_4282')->first();
            $this->line("   Libellé : {$localite4282->lib_localite}");
            $this->line("   Supprimé le : {$localite4282->deleted_at}");
        } else {
            $this->info('   ✅ LOC_4282 n\'existe pas encore');
        }
        
        // Vérifier ce que la requête MAX retourne
        $this->newLine();
        $this->info('🔍 Vérification de la requête MAX...');
        $maxQuery = Localite::selectRaw('MAX(CAST(SUBSTRING(code_localite, 5) AS UNSIGNED)) as max_num')->first();
        $this->line("   MAX retourné : " . ($maxQuery->max_num ?? 'NULL'));
        
        // Vérifier les codes autour de 4282
        $this->newLine();
        $this->info('🔍 Vérification des codes autour de 4282...');
        for ($i = 4280; $i <= 4285; $i++) {
            $code = 'LOC_' . str_pad($i, 4, '0', STR_PAD_LEFT);
            $exists = Localite::where('code_localite', $code)->exists();
            $existsTrashed = Localite::withTrashed()->where('code_localite', $code)->exists();
            if ($exists) {
                $this->line("   {$code} : ✅ Existe (actif)");
            } elseif ($existsTrashed) {
                $this->line("   {$code} : ⚠️  Existe (supprimé)");
            } else {
                $this->line("   {$code} : ❌ N'existe pas");
            }
        }
        
        // Tester la génération de code
        $this->newLine();
        $this->info('🧪 Test de génération de codes...');
        
        DB::beginTransaction();
        try {
            $codes = [];
            for ($i = 1; $i <= 5; $i++) {
                $localite = new Localite();
                $code = Sifec::genererCodeUniqueReferentiel($localite, 'code_localite', 4, 'LOC_');
                $codes[] = $code;
                $this->line("   Code généré {$i} : {$code}");
            }
            
            // Vérifier l'unicité
            $uniqueCodes = array_unique($codes);
            if (count($codes) === count($uniqueCodes)) {
                $this->info('   ✅ Tous les codes générés sont uniques');
            } else {
                $this->error('   ❌ Des codes dupliqués ont été générés !');
                $duplicates = array_diff_assoc($codes, $uniqueCodes);
                foreach ($duplicates as $dup) {
                    $this->error("      - {$dup} est dupliqué");
                }
            }
            
            // Vérifier que les codes n'existent pas déjà
            $this->newLine();
            $this->info('🔍 Vérification de l\'existence des codes générés...');
            $allExist = true;
            foreach ($codes as $code) {
                $exists = Localite::where('code_localite', $code)->exists();
                if ($exists) {
                    $this->warn("   ⚠️  {$code} existe déjà dans la base de données");
                    $allExist = false;
                } else {
                    $this->line("   ✅ {$code} n'existe pas (OK)");
                }
            }
            
            if ($allExist) {
                $this->info('   ✅ Aucun des codes générés n\'existe déjà');
            }
            
            // Afficher le prochain code attendu
            $this->newLine();
            $this->info('📈 Prochain code attendu...');
            $nextExpected = 'LOC_' . str_pad($maxNum + 1, 4, '0', STR_PAD_LEFT);
            $this->line("   Prochain code attendu : {$nextExpected}");
            
            if (in_array($nextExpected, $codes)) {
                $this->info('   ✅ Le code généré correspond au prochain attendu');
            } else {
                $this->warn('   ⚠️  Le code généré ne correspond pas au prochain attendu');
            }
            
            DB::rollBack();
            $this->newLine();
            $this->info('✅ Test terminé (transaction annulée, aucune donnée enregistrée)');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Erreur lors du test : ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
        
        return 0;
    }
}

