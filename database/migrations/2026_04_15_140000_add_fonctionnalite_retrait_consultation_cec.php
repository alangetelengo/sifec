<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ajoute la fonctionnalité FNC_0049 (retrait depuis consultation CEC) pour les bases déjà peuplées.
     */
    public function up(): void
    {
        if (! DB::table('tr_fonctionnalite')->where('code_fonctionnalite', 'FNC_0049')->exists()) {
            DB::table('tr_fonctionnalite')->insert([
                'code_fonctionnalite' => 'FNC_0049',
                'lib_fonctionnalite' => 'Enregistrer un retrait depuis la consultation actes retirés (CEC)',
                'lib_technique' => 'module.acteNaissance.retrait.depuisConsultationCEC',
                'description_fonctionnalite' => "Permet d'enregistrer le retrait d'un acte de naissance depuis l'écran Consultation des actes retirés (guichet CEC).",
                'code_fonctionnalite_parent' => 'FNC_0002',
                'code_module' => 'MOD_0002',
                'etat_fonctionnalite' => 'Activé',
                'supprimer' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $fonctionsCec = ['FONC_0002', 'FONC_0003', 'FONC_0004', 'FONC_0015', 'FONC_0016', 'FONC_0014'];
        foreach ($fonctionsCec as $codeFonction) {
            if (! DB::table('tr_fonction')->where('code_fonction', $codeFonction)->exists()) {
                continue;
            }
            if (! DB::table('tr_ff')->where('code_fonction', $codeFonction)->where('code_fonctionnalite', 'FNC_0049')->exists()) {
                DB::table('tr_ff')->insert([
                    'code_fonction' => $codeFonction,
                    'code_fonctionnalite' => 'FNC_0049',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('tr_ff')->where('code_fonctionnalite', 'FNC_0049')->delete();
        DB::table('tr_fonctionnalite')->where('code_fonctionnalite', 'FNC_0049')->delete();
    }
};
