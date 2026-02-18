<?php

namespace Modules\Rectification\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Rectification\Entities\Rubrique;

/**
 * Seeder basé sur les commentaires de la migration create_tr_rubrique_table :
 * - lib_rubrique : exple nom, prenom, sexe, date de naissance, nationalite, etc
 * - entite_rubrique : exple enfant, père, mère, époux, épouse, defunt, etc
 * - lib_technique : clé pour le traitement (nom, prenom, sexe, date_naissance, lieu_naissance, nationalite)
 * - code_type_acte : TAC_0001 Naissance, TAC_0002 Mariage, TAC_0003 Décès
 */
class RubriqueSeederTableSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('tr_rubrique')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $rubriques = [
            // ---- Acte de naissance (TAC_0001) : enfant ----
            ['code_rubrique' => 'RUB_0001', 'lib_rubrique' => 'Nom', 'lib_technique' => 'nom', 'entite_rubrique' => 'enfant', 'code_type_acte' => 'TAC_0001'],
            ['code_rubrique' => 'RUB_0002', 'lib_rubrique' => 'Prénom', 'lib_technique' => 'prenom', 'entite_rubrique' => 'enfant', 'code_type_acte' => 'TAC_0001'],
            ['code_rubrique' => 'RUB_0003', 'lib_rubrique' => 'Sexe', 'lib_technique' => 'sexe', 'entite_rubrique' => 'enfant', 'code_type_acte' => 'TAC_0001'],
            ['code_rubrique' => 'RUB_0004', 'lib_rubrique' => 'Date de naissance', 'lib_technique' => 'date_naissance', 'entite_rubrique' => 'enfant', 'code_type_acte' => 'TAC_0001'],
            ['code_rubrique' => 'RUB_0005', 'lib_rubrique' => 'Lieu de naissance', 'lib_technique' => 'lieu_naissance', 'entite_rubrique' => 'enfant', 'code_type_acte' => 'TAC_0001'],
            ['code_rubrique' => 'RUB_0006', 'lib_rubrique' => 'Nationalité', 'lib_technique' => 'nationalite', 'entite_rubrique' => 'enfant', 'code_type_acte' => 'TAC_0001'],
            // ---- Acte de naissance (TAC_0001) : père ----
            ['code_rubrique' => 'RUB_0007', 'lib_rubrique' => 'Nom', 'lib_technique' => 'nom', 'entite_rubrique' => 'père', 'code_type_acte' => 'TAC_0001'],
            ['code_rubrique' => 'RUB_0008', 'lib_rubrique' => 'Prénom', 'lib_technique' => 'prenom', 'entite_rubrique' => 'père', 'code_type_acte' => 'TAC_0001'],
            ['code_rubrique' => 'RUB_0009', 'lib_rubrique' => 'Sexe', 'lib_technique' => 'sexe', 'entite_rubrique' => 'père', 'code_type_acte' => 'TAC_0001'],
            ['code_rubrique' => 'RUB_0010', 'lib_rubrique' => 'Date de naissance', 'lib_technique' => 'date_naissance', 'entite_rubrique' => 'père', 'code_type_acte' => 'TAC_0001'],
            ['code_rubrique' => 'RUB_0011', 'lib_rubrique' => 'Lieu de naissance', 'lib_technique' => 'lieu_naissance', 'entite_rubrique' => 'père', 'code_type_acte' => 'TAC_0001'],
            ['code_rubrique' => 'RUB_0012', 'lib_rubrique' => 'Nationalité', 'lib_technique' => 'nationalite', 'entite_rubrique' => 'père', 'code_type_acte' => 'TAC_0001'],
            // ---- Acte de naissance (TAC_0001) : mère ----
            ['code_rubrique' => 'RUB_0013', 'lib_rubrique' => 'Nom', 'lib_technique' => 'nom', 'entite_rubrique' => 'mère', 'code_type_acte' => 'TAC_0001'],
            ['code_rubrique' => 'RUB_0014', 'lib_rubrique' => 'Prénom', 'lib_technique' => 'prenom', 'entite_rubrique' => 'mère', 'code_type_acte' => 'TAC_0001'],
            ['code_rubrique' => 'RUB_0015', 'lib_rubrique' => 'Sexe', 'lib_technique' => 'sexe', 'entite_rubrique' => 'mère', 'code_type_acte' => 'TAC_0001'],
            ['code_rubrique' => 'RUB_0016', 'lib_rubrique' => 'Date de naissance', 'lib_technique' => 'date_naissance', 'entite_rubrique' => 'mère', 'code_type_acte' => 'TAC_0001'],
            ['code_rubrique' => 'RUB_0017', 'lib_rubrique' => 'Lieu de naissance', 'lib_technique' => 'lieu_naissance', 'entite_rubrique' => 'mère', 'code_type_acte' => 'TAC_0001'],
            ['code_rubrique' => 'RUB_0018', 'lib_rubrique' => 'Nationalité', 'lib_technique' => 'nationalite', 'entite_rubrique' => 'mère', 'code_type_acte' => 'TAC_0001'],
            // ---- Acte de mariage (TAC_0002) : époux ----
            ['code_rubrique' => 'RUB_0019', 'lib_rubrique' => 'Nom', 'lib_technique' => 'nom', 'entite_rubrique' => 'époux', 'code_type_acte' => 'TAC_0002'],
            ['code_rubrique' => 'RUB_0020', 'lib_rubrique' => 'Prénom', 'lib_technique' => 'prenom', 'entite_rubrique' => 'époux', 'code_type_acte' => 'TAC_0002'],
            ['code_rubrique' => 'RUB_0021', 'lib_rubrique' => 'Sexe', 'lib_technique' => 'sexe', 'entite_rubrique' => 'époux', 'code_type_acte' => 'TAC_0002'],
            ['code_rubrique' => 'RUB_0022', 'lib_rubrique' => 'Date de naissance', 'lib_technique' => 'date_naissance', 'entite_rubrique' => 'époux', 'code_type_acte' => 'TAC_0002'],
            ['code_rubrique' => 'RUB_0023', 'lib_rubrique' => 'Lieu de naissance', 'lib_technique' => 'lieu_naissance', 'entite_rubrique' => 'époux', 'code_type_acte' => 'TAC_0002'],
            ['code_rubrique' => 'RUB_0024', 'lib_rubrique' => 'Nationalité', 'lib_technique' => 'nationalite', 'entite_rubrique' => 'époux', 'code_type_acte' => 'TAC_0002'],
            // ---- Acte de mariage (TAC_0002) : épouse ----
            ['code_rubrique' => 'RUB_0025', 'lib_rubrique' => 'Nom', 'lib_technique' => 'nom', 'entite_rubrique' => 'épouse', 'code_type_acte' => 'TAC_0002'],
            ['code_rubrique' => 'RUB_0026', 'lib_rubrique' => 'Prénom', 'lib_technique' => 'prenom', 'entite_rubrique' => 'épouse', 'code_type_acte' => 'TAC_0002'],
            ['code_rubrique' => 'RUB_0027', 'lib_rubrique' => 'Sexe', 'lib_technique' => 'sexe', 'entite_rubrique' => 'épouse', 'code_type_acte' => 'TAC_0002'],
            ['code_rubrique' => 'RUB_0028', 'lib_rubrique' => 'Date de naissance', 'lib_technique' => 'date_naissance', 'entite_rubrique' => 'épouse', 'code_type_acte' => 'TAC_0002'],
            ['code_rubrique' => 'RUB_0029', 'lib_rubrique' => 'Lieu de naissance', 'lib_technique' => 'lieu_naissance', 'entite_rubrique' => 'épouse', 'code_type_acte' => 'TAC_0002'],
            ['code_rubrique' => 'RUB_0030', 'lib_rubrique' => 'Nationalité', 'lib_technique' => 'nationalite', 'entite_rubrique' => 'épouse', 'code_type_acte' => 'TAC_0002'],
            // ---- Acte de décès (TAC_0003) : defunt ----
            ['code_rubrique' => 'RUB_0031', 'lib_rubrique' => 'Nom', 'lib_technique' => 'nom', 'entite_rubrique' => 'defunt', 'code_type_acte' => 'TAC_0003'],
            ['code_rubrique' => 'RUB_0032', 'lib_rubrique' => 'Prénom', 'lib_technique' => 'prenom', 'entite_rubrique' => 'defunt', 'code_type_acte' => 'TAC_0003'],
            ['code_rubrique' => 'RUB_0033', 'lib_rubrique' => 'Sexe', 'lib_technique' => 'sexe', 'entite_rubrique' => 'defunt', 'code_type_acte' => 'TAC_0003'],
            ['code_rubrique' => 'RUB_0034', 'lib_rubrique' => 'Date de naissance', 'lib_technique' => 'date_naissance', 'entite_rubrique' => 'defunt', 'code_type_acte' => 'TAC_0003'],
            ['code_rubrique' => 'RUB_0035', 'lib_rubrique' => 'Lieu de naissance', 'lib_technique' => 'lieu_naissance', 'entite_rubrique' => 'defunt', 'code_type_acte' => 'TAC_0003'],
            ['code_rubrique' => 'RUB_0036', 'lib_rubrique' => 'Nationalité', 'lib_technique' => 'nationalite', 'entite_rubrique' => 'defunt', 'code_type_acte' => 'TAC_0003'],
        ];

        foreach ($rubriques as $rubrique) {
            Rubrique::create($rubrique);
        }
    }
}
