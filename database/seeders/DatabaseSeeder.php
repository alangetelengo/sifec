<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Mobile\Database\Seeders\TypeActeTableSeeder;
use Modules\Mobile\Database\Seeders\TypeDocumentDemandeTableSeeder;
use Modules\Rectification\Database\Seeders\RubriqueSeederTableSeeder;

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
            TypeJugementSeeder::class,
            TypeRequisitionSeeder::class,

            // ── Géographie (tr_type_localite → tr_localite ; voir data/tr_localite_seed.sql) ──
            TypeLocaliteSeeder::class,
            DepartementSeeder::class,
            LocaliteSeeder::class,

            // ── Institutions ─────────────────────────────────────────────────
            TypeCategorieInstitutionSeeder::class,
            TypeInstitutionSeeder::class,
            InstitutionSeeder::class,

            // ── Types d'actes ─────────────────────────────────────────────────
            TypeActeTableSeeder::class,
            RubriqueSeederTableSeeder::class,
            TypeDocumentDemandeTableSeeder::class,
            // ── Modules & habilitations ──────────────────────────────────────
            ModuleSeeder::class,
            FonctionnaliteSeeder::class,
            FonctionSeeder::class,
            FonctionFonctionnaliteSeeder::class,

            // ── Menu latéral (tr_menu_item) — après migrations dédiées ────
            MenuItemSeeder::class,

            // ── Mouvements ───────────────────────────────────────────────────
            TrMouvementSeeder::class,
            TrMouvementCertificatsNaissanceDecesSeeder::class,

            // ── Comptes : personnes + users + affectations (voir Data/sifec_comptes_institutions.php) ─
            // PersonneSeeder vide tr_ins_user, tr_user, tr_identification_personne puis réinsère les personnes.
            PersonneSeeder::class,
            UserSeederTableSeeder::class,
            InstitutionUserSeeder::class,

            // Centre d’hygiène Brazzaville : lien PF (constatation décès → institution de ressort)
            CentreHygieneBrazzavilleDecesSeeder::class,

            // ── Appareils autorisés ───────────────────────────────────────────
            AppareilSeeder::class,

            // Démo organisation (Makelekele, TGI, PF) — mdp 123456 ; décommenter si besoin
            // DemoSifecOrganisationSeeder::class,
        ]);
    }
}
