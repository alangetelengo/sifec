<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Referentiel\Entities\Personne;
use App\Sifec\Sifec;

class FixDuplicatePersonnes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sifec:fix-duplicate-personnes {--dry-run : Afficher les actions sans les exécuter}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corriger les codes personne en doublon et tester la nouvelle génération de codes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔍 Recherche des codes personne en doublon...');

        // Vérifier les doublons existants
        $duplicates = DB::select("
            SELECT code_personne, COUNT(*) as count
            FROM tr_identification_personne
            GROUP BY code_personne
            HAVING COUNT(*) > 1
        ");

        if (empty($duplicates)) {
            $this->info('✅ Aucun doublon trouvé dans la base de données.');
        } else {
            $this->warn("⚠️  {count($duplicates)} codes en doublon trouvés :");

            foreach ($duplicates as $duplicate) {
                $this->line("   - Code: {$duplicate->code_personne} ({$duplicate->count} occurrences)");
            }

            if (!$this->option('dry-run')) {
                $this->info('🔧 Correction des doublons...');
                $this->fixDuplicates($duplicates);
            } else {
                $this->info('Mode dry-run : aucune correction appliquée.');
            }
        }

        // Tester la génération de nouveaux codes
        $this->info('🧪 Test de la génération de codes uniques...');
        $this->testCodeGeneration();

        return 0;
    }

    /**
     * Corriger les doublons en assignant de nouveaux codes
     */
    private function fixDuplicates($duplicates)
    {
        DB::beginTransaction();

        try {
            foreach ($duplicates as $duplicate) {
                $this->info("Correction du code: {$duplicate->code_personne}");

                // Récupérer toutes les personnes avec ce code
                $personnes = Personne::where('code_personne', $duplicate->code_personne)
                    ->orderBy('created_at')
                    ->get();

                // Garder la première personne avec le code original
                $first = true;
                foreach ($personnes as $personne) {
                    if ($first) {
                        $first = false;
                        $this->line("   - Gardé: {$personne->code_personne} (original)");
                        continue;
                    }

                    // Générer un nouveau code pour les autres
                    $newCode = Sifec::genererCodeUniqueReferentiel(new Personne(), "code_personne", 8, "PRS_");

                    // Mettre à jour toutes les références
                    $this->updatePersonneReferences($personne->code_personne, $newCode);

                    // Mettre à jour la personne
                    $personne->code_personne = $newCode;
                    $personne->save();

                    $this->line("   - Mis à jour: {$duplicate->code_personne} → {$newCode}");
                }
            }

            DB::commit();
            $this->info('✅ Doublons corrigés avec succès.');

        } catch (Exception $e) {
            DB::rollBack();
            $this->error('❌ Erreur lors de la correction des doublons: ' . $e->getMessage());
            Log::channel('sifec')->error('Erreur correction doublons', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Mettre à jour les références à un code personne dans les autres tables
     */
    private function updatePersonneReferences($oldCode, $newCode)
    {
        // Mettre à jour les tables qui référencent code_personne
        $tables = [
            'tr_document' => 'code_personne',
            't_declaration_mariage' => ['code_epoux', 'code_epouse', 'code_temoin_homme_epoux', 'code_temoin_femme_epoux', 'code_temoin_homme_epouse', 'code_temoin_femme_epouse'],
            't_declaration_naissance' => ['code_enfant', 'code_declarant', 'code_pere', 'code_mere'],
            't_declaration_deces' => ['code_defunt', 'code_declarant'],
            // Ajouter d'autres tables selon le schéma
        ];

        foreach ($tables as $table => $columns) {
            if (is_array($columns)) {
                foreach ($columns as $column) {
                    DB::table($table)
                        ->where($column, $oldCode)
                        ->update([$column => $newCode]);
                }
            } else {
                DB::table($table)
                    ->where($columns, $oldCode)
                    ->update([$columns => $newCode]);
            }
        }
    }

    /**
     * Tester la génération de codes uniques
     */
    private function testCodeGeneration()
    {
        $codes = [];

        DB::beginTransaction();
        try {
            for ($i = 0; $i < 5; $i++) {
                $personne = new Personne();
                $code = Sifec::genererCodeUniqueReferentiel($personne, "code_personne", 8, "PRS_");

                if (in_array($code, $codes)) {
                    $this->error("❌ Code dupliqué généré: {$code}");
                    DB::rollBack();
                    return;
                }

                // Créer une personne temporaire pour simuler l'insertion
                $testPersonne = new Personne();
                $testPersonne->code_personne = $code;
                $testPersonne->nom = "TEST_" . $i;
                $testPersonne->prenom = "Test";
                $testPersonne->sexe = "M";
                $testPersonne->statut_personne = "VIVANT";
                $testPersonne->type_date_naissance = "EXACTE";
                $testPersonne->personne_string = "TEST" . $i . time();
                $testPersonne->save();

                $codes[] = $code;
                $this->line("   ✓ Code généré et testé: {$code}");
            }

            $this->info('✅ Test de génération réussi - tous les codes sont uniques.');

        } catch (Exception $e) {
            $this->error("❌ Erreur lors du test: " . $e->getMessage());
        } finally {
            // Toujours rollback pour ne pas polluer la base
            DB::rollBack();
        }
    }
}
