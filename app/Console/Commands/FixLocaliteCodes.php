<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Referentiel\Entities\Localite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixLocaliteCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'localite:fix-codes {--dry-run : Afficher les changements sans les appliquer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corriger les codes de localité pour respecter l\'ordre séquentiel et éviter les doublons';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('🔍 Mode DRY-RUN : Aucun changement ne sera appliqué');
        }

        $this->info('🔍 Inspection des codes de localité...');
        
        // Trouver le maximum numérique réel
        $maxCode = Localite::selectRaw('MAX(CAST(SUBSTRING(code_localite, 5) AS UNSIGNED)) as max_num')
            ->first();
        
        $maxNum = $maxCode->max_num ?? 0;
        $this->info("📊 Code maximum numérique trouvé : LOC_{$maxNum}");
        
        // Trouver les doublons
        $duplicates = Localite::selectRaw('code_localite, COUNT(*) as count')
            ->groupBy('code_localite')
            ->having('count', '>', 1)
            ->get();
        
        if ($duplicates->count() > 0) {
            $this->warn("⚠️  {$duplicates->count()} code(s) dupliqué(s) trouvé(s) :");
            foreach ($duplicates as $dup) {
                $this->line("   - {$dup->code_localite} (apparaît {$dup->count} fois)");
            }
        } else {
            $this->info('✅ Aucun doublon trouvé');
        }
        
        // Trouver les codes qui ne respectent pas l'ordre
        $this->info("\n🔍 Vérification de l'ordre des codes...");
        $allCodes = Localite::selectRaw('code_localite, CAST(SUBSTRING(code_localite, 5) AS UNSIGNED) as num')
            ->orderByRaw('CAST(SUBSTRING(code_localite, 5) AS UNSIGNED) ASC')
            ->get();
        
        $outOfOrder = [];
        $expectedNum = 1;
        
        foreach ($allCodes as $localite) {
            if ($localite->num != $expectedNum) {
                $outOfOrder[] = [
                    'code' => $localite->code_localite,
                    'current_num' => $localite->num,
                    'expected_num' => $expectedNum
                ];
            }
            $expectedNum++;
        }
        
        if (count($outOfOrder) > 0) {
            $this->warn("⚠️  " . count($outOfOrder) . " code(s) ne respectent pas l'ordre séquentiel");
            if ($this->confirm('Voulez-vous voir la liste complète ?', false)) {
                foreach ($outOfOrder as $item) {
                    $this->line("   - {$item['code']} (numéro: {$item['current_num']}, attendu: {$item['expected_num']})");
                }
            }
        } else {
            $this->info('✅ Tous les codes respectent l\'ordre séquentiel');
        }
        
        // Proposer de corriger
        if (!$dryRun && (count($duplicates) > 0 || count($outOfOrder) > 0)) {
            if ($this->confirm("\n❓ Voulez-vous corriger les codes ?", false)) {
                $this->fixCodes($duplicates, $outOfOrder, $maxNum);
            }
        }
        
        return 0;
    }
    
    private function fixCodes($duplicates, $outOfOrder, $maxNum)
    {
        $this->info("\n🔧 Correction des codes...");
        
        DB::beginTransaction();
        try {
            $newMax = $maxNum;
            
            // Corriger les doublons
            foreach ($duplicates as $dup) {
                $localites = Localite::where('code_localite', $dup->code_localite)->get();
                $first = true;
                
                foreach ($localites as $localite) {
                    if ($first) {
                        $first = false;
                        continue; // Garder le premier tel quel
                    }
                    
                    $newMax++;
                    $newCode = 'LOC_' . str_pad($newMax, 4, '0', STR_PAD_LEFT);
                    
                    // Vérifier que le nouveau code n'existe pas
                    while (Localite::where('code_localite', $newCode)->exists()) {
                        $newMax++;
                        $newCode = 'LOC_' . str_pad($newMax, 4, '0', STR_PAD_LEFT);
                    }
                    
                    $this->line("   ✓ {$localite->code_localite} → {$newCode} ({$localite->lib_localite})");
                    $localite->code_localite = $newCode;
                    $localite->save();
                }
            }
            
            // Corriger les codes hors ordre (optionnel, peut être long)
            if (count($outOfOrder) > 0 && $this->confirm('Voulez-vous réorganiser tous les codes pour qu\'ils soient séquentiels ?', false)) {
                $this->warn('⚠️  Cette opération peut prendre du temps...');
                $newMax = 0;
                
                $allLocalites = Localite::orderBy('created_at', 'ASC')->get();
                foreach ($allLocalites as $localite) {
                    $newMax++;
                    $newCode = 'LOC_' . str_pad($newMax, 4, '0', STR_PAD_LEFT);
                    
                    if ($localite->code_localite != $newCode) {
                        // Vérifier que le nouveau code n'existe pas
                        if (!Localite::where('code_localite', $newCode)->exists()) {
                            $oldCode = $localite->code_localite;
                            $localite->code_localite = $newCode;
                            $localite->save();
                            $this->line("   ✓ {$oldCode} → {$newCode} ({$localite->lib_localite})");
                        }
                    }
                }
            }
            
            DB::commit();
            $this->info("\n✅ Correction terminée avec succès !");
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ Erreur lors de la correction : " . $e->getMessage());
            Log::channel('sifec')->error('Erreur correction codes localité', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}

