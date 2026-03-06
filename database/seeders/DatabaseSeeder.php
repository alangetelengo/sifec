<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Ordre d'exécution respectant les dépendances de clés étrangères :
 *  1. Référentiels indépendants (types, listes de valeurs)
 *  2. Référentiels géographiques (dépt > communes > arrondissements > localités)
 *  3. Référentiels institutionnels (catégories > types > institutions)
 *  4. Modules / fonctionnalités / fonctions
 *  5. Comptes utilisateurs et affectations
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // ── Listes de valeurs indépendantes ──────────────────────────────
            TypeDocumentSeeder::class,
            RegimeSeeder::class,
            NationaliteSeeder::class,
            LieuSurvenanceSeeder::class,
            FiliationSeeder::class,
            SituationMatrimonialeSeeder::class,
            TypeRegistreSeeder::class,
            CauseDecesSeeder::class,
            ProfessionSeeder::class,
            TypeExtraitSeeder::class,
            ReligionSeeder::class,
            OptionMariageSeeder::class,
            TypeLocaliteSeeder::class,
            TypeJugementSeeder::class,
            TypeRequisitionSeeder::class,

            // ── Géographie ────────────────────────────────────────────────────
            DepartementSeeder::class,
            LocaliteSeeder::class,

            // ── Institutions ─────────────────────────────────────────────────
            TypeCategorieInstitutionSeeder::class,
            TypeInstitutionSeeder::class,
            InstitutionSeeder::class,

            // ── Modules & habilitations ──────────────────────────────────────
            ModuleSeeder::class,
            FonctionnaliteSeeder::class,
            FonctionSeeder::class,

            // ── Mouvements ───────────────────────────────────────────────────
            TrMouvementSeeder::class,

            // ── Comptes utilisateurs (optionnel — décommenter si nécessaire) ─
            PersonneSeeder::class,
            UserSeederTableSeeder::class,
            // InstitutionUserSeeder::class,

            // ── Appareils autorisés ───────────────────────────────────────────
            AppareilSeeder::class,
        ]);
    }
}
