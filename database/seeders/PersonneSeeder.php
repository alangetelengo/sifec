<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\Personne;

/**
 * Personnes associées aux comptes SIFEC (voir Data/sifec_comptes_institutions.php).
 */
class PersonneSeeder extends Seeder
{
    private const DATE_NAISSANCE = '1982-06-15';

    private const LIEU_NAISSANCE = 'Brazzaville';

    private const CODE_LOCALITE = 'LOC_0026';

    private const CODE_NATIONALITE = 'NAT_0001';

    public function run(): void
    {
        $path = __DIR__.'/Data/sifec_comptes_institutions.php';
        if (! is_file($path)) {
            $this->command?->error('Fichier manquant : database/seeders/Data/sifec_comptes_institutions.php');

            return;
        }

        /** @var array<int, array<string, mixed>> $comptes */
        $comptes = require $path;

        // Ordre : affectations → utilisateurs → personnes (contraintes FK).
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('TRUNCATE tr_ins_user');
        DB::statement('TRUNCATE tr_user');
        DB::statement('TRUNCATE tr_identification_personne');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $now = now();

        $codeLocalite = $this->codeLocaliteReference();
        $codeNationalite = $this->codeNationaliteReference();

        foreach ($comptes as $row) {
            $dateCompact = str_replace('-', '', self::DATE_NAISSANCE);
            $personneString = strtoupper(preg_replace(
                '/\s+/',
                '',
                $row['nom'].$row['prenom'].$dateCompact.'BRAZZAVILLE'.$row['sexe']
            ));

            Personne::query()->updateOrInsert(
                ['code_personne' => $row['code_personne']],
                [
                    'nom' => $row['nom'],
                    'prenom' => $row['prenom'],
                    'sexe' => $row['sexe'],
                    'date_naissance' => self::DATE_NAISSANCE,
                    'lieu_naissance' => self::LIEU_NAISSANCE,
                    'code_localite' => $codeLocalite,
                    'telephone' => $row['telephone'],
                    'adresse' => 'Brazzaville, République du Congo',
                    'niveau_instruction' => 'SUPERIEUR',
                    'code_nationalite' => $codeNationalite,
                    'personne_string' => $personneString,
                    'statut_personne' => 'VIVANT',
                    'type_date_naissance' => 'EXACTE',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }

    private function codeLocaliteReference(): ?string
    {
        if (DB::table('tr_localite')->where('code_localite', self::CODE_LOCALITE)->exists()) {
            return self::CODE_LOCALITE;
        }

        $fallback = DB::table('tr_localite')->orderBy('code_localite')->value('code_localite');
        if ($fallback === null) {
            $this->command?->warn('Aucune localité en base : code_localite laissé NULL (exécutez LocaliteSeeder pour une affectation précise).');
        }

        return $fallback;
    }

    private function codeNationaliteReference(): ?string
    {
        if (DB::table('tr_nationalite')->where('code_nationalite', self::CODE_NATIONALITE)->exists()) {
            return self::CODE_NATIONALITE;
        }

        $fallback = DB::table('tr_nationalite')->orderBy('code_nationalite')->value('code_nationalite');
        if ($fallback === null) {
            $this->command?->warn('Aucune nationalité en base : code_nationalite laissé NULL (exécutez NationaliteSeeder).');
        }

        return $fallback;
    }
}
