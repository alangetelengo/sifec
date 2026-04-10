<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Centre d’hygiène de Brazzaville (INS_0198) : rattachement pour l’envoi des certificats
 * de constatation de décès vers l’institution de ressort.
 *
 * Règle métier (Modules/Deces/Services/MouvementService::envoyerDeclaration) :
 * - si {@see tr_institution.code_pompe_funebre} est renseigné → destinataire = cette pompe funèbre ;
 * - sinon → {@see code_institution_parent} (ex. mairie / CEC selon le jeu institutions.sql).
 *
 * Routage automatique à l’envoi : voir {@see \Modules\Deces\Services\DecesDestinataireEnvoiService}
 * et {@see config/sifec_deces.php} (commune LOC_0026 → PF INS_0192).
 * Ce seeder aligne encore parent/pompe sur l’émetteur INS_0198 pour le repli historique.
 */
class CentreHygieneBrazzavilleDecesSeeder extends Seeder
{
    public const CODE_CENTRE_HYGIENE_BRAZZAVILLE = 'INS_0198';

    public const CODE_PF_MUNICIPALES_BRAZZAVILLE = 'INS_0192';

    public function run(): void
    {
        $hygiene = self::CODE_CENTRE_HYGIENE_BRAZZAVILLE;
        $pf = self::CODE_PF_MUNICIPALES_BRAZZAVILLE;

        if (! DB::table('tr_institution')->where('code_institution', $hygiene)->exists()) {
            $this->command?->warn("Institution {$hygiene} absente : exécutez InstitutionSeeder avant CentreHygieneBrazzavilleDecesSeeder.");

            return;
        }

        if (! DB::table('tr_institution')->where('code_institution', $pf)->exists()) {
            $this->command?->warn("Institution {$pf} absente : lien centre d'hygiène → PF non appliqué.");

            return;
        }

        DB::table('tr_institution')->where('code_institution', $hygiene)->update([
            'code_institution_parent' => $pf,
            'code_pompe_funebre' => $pf,
            'updated_at' => now(),
        ]);

        $this->command?->info(sprintf(
            'Centre d\'hygiène %s : parent + pompe funèbre = %s (PF Brazzaville). Envoi constatation → cette institution.',
            $hygiene,
            $pf
        ));

        $this->command?->info(
            'Comptes agent centre d\'hygiène (FONC_0007) : voir Data/sifec_comptes_institutions.php — '
            .'centre.hygiene@sifec.cg / constatation.deces.brazzaville@sifec.cg — mot de passe seed : 123456'
        );
    }
}
