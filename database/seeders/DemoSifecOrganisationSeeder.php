<?php

namespace Database\Seeders;

use App\Models\InstitutionUser;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Referentiel\Entities\Personne;

/**
 * Comptes de démonstration (codes USR_DEMO*, PRS_DEMO*, CUI_DEMO*).
 *
 * Le seed standard charge déjà personnes + users + affectations via
 * Data/sifec_comptes_institutions.php (USR_00000001 …, institutions + fonctions alignées sur tr_ff).
 * N’utilisez ce seeder que pour des logins « demo_* » supplémentaires ou une réinjection ciblée.
 *
 * Exécution : php artisan db:seed --class=DemoSifecOrganisationSeeder
 *
 * Mots de passe : 123456 (à changer en production).
 */
class DemoSifecOrganisationSeeder extends Seeder
{
    /** Références institutions (database/data/institutions.sql) */
    public const INS_HOPITAL_MAKELEKELE = 'INS_0094';

    public const INS_MAIRIE_ARR1_MAKELEKELE = 'INS_0047';

    public const INS_TI_BACONGO_MAKELEKELE = 'INS_0023';

    public const INS_TGI_BRAZZAVILLE = 'INS_0006';

    public const INS_PF_MUNICIPALES_BRAZZAVILLE = 'INS_0192';

    private const PASSWORD = '123456';

    private const LOCALITE_BRAZZAVILLE_COMMUNE = 'LOC_0026';

    private const NATIONALITE_CONGO = 'NAT_0001';

    public function run(): void
    {
        $comptes = [
            [
                'email' => 'agentfs@sifec.cg',
                'pseudo' => 'demo_agfs',
                'code_user' => 'USR_DEMO0001',
                'code_personne' => 'PRS_DEMO0001',
                'cui' => 'CUI_DEMO0001',
                'code_institution' => self::INS_HOPITAL_MAKELEKELE,
                'code_fonction' => 'FONC_0006',
                'nom' => 'MBOUMBA',
                'prenom' => 'Christelle',
                'sexe' => 'F',
                'telephone' => '066112233',
            ],
            [
                'email' => 'agentcec@sifec.cg',
                'pseudo' => 'demo_acec',
                'code_user' => 'USR_DEMO0002',
                'code_personne' => 'PRS_DEMO0002',
                'cui' => 'CUI_DEMO0002',
                'code_institution' => self::INS_MAIRIE_ARR1_MAKELEKELE,
                'code_fonction' => 'FONC_0004',
                'nom' => 'KIMBOU',
                'prenom' => 'Yves Fabrice',
                'sexe' => 'M',
                'telephone' => '066223344',
            ],
            [
                'email' => 'cscec@sifec.cg',
                'pseudo' => 'democscec',
                'code_user' => 'USR_DEMO0003',
                'code_personne' => 'PRS_DEMO0003',
                'cui' => 'CUI_DEMO0003',
                'code_institution' => self::INS_MAIRIE_ARR1_MAKELEKELE,
                'code_fonction' => 'FONC_0016',
                'nom' => 'NGOMA',
                'prenom' => 'Sylvie Prisca',
                'sexe' => 'F',
                'telephone' => '066334455',
            ],
            [
                'email' => 'officiercec@sifec.cg',
                'pseudo' => 'demo_offcec',
                'code_user' => 'USR_DEMO0004',
                'code_personne' => 'PRS_DEMO0004',
                'cui' => 'CUI_DEMO0004',
                'code_institution' => self::INS_MAIRIE_ARR1_MAKELEKELE,
                'code_fonction' => 'FONC_0002',
                'nom' => 'LOUBAKI',
                'prenom' => 'Marc Aurèle',
                'sexe' => 'M',
                'telephone' => '066445566',
            ],
            [
                'email' => 'agentpf@sifec.cg',
                'pseudo' => 'demo_apf',
                'code_user' => 'USR_DEMO0005',
                'code_personne' => 'PRS_DEMO0005',
                'cui' => 'CUI_DEMO0005',
                'code_institution' => self::INS_PF_MUNICIPALES_BRAZZAVILLE,
                'code_fonction' => 'FONC_0005',
                'nom' => 'BANZOUZI',
                'prenom' => 'Ghislain',
                'sexe' => 'M',
                'telephone' => '066556677',
            ],
            [
                'email' => 'cspf@sifec.cg',
                'pseudo' => 'demo_cspf',
                'code_user' => 'USR_DEMO0006',
                'code_personne' => 'PRS_DEMO0006',
                'cui' => 'CUI_DEMO0006',
                'code_institution' => self::INS_PF_MUNICIPALES_BRAZZAVILLE,
                'code_fonction' => 'FONC_0016',
                'nom' => 'MAKAYA',
                'prenom' => 'Josiane',
                'sexe' => 'F',
                'telephone' => '066667788',
            ],
            [
                'email' => 'directeurpf@sifec.cg',
                'pseudo' => 'demo_dpf',
                'code_user' => 'USR_DEMO0007',
                'code_personne' => 'PRS_DEMO0007',
                'cui' => 'CUI_DEMO0007',
                'code_institution' => self::INS_PF_MUNICIPALES_BRAZZAVILLE,
                'code_fonction' => 'FONC_0012',
                'nom' => 'OTSIENO',
                'prenom' => 'Brice Dimitri',
                'sexe' => 'M',
                'telephone' => '066778899',
            ],
            [
                'email' => 'president.ti@sifec.cg',
                'pseudo' => 'demo_prti',
                'code_user' => 'USR_DEMO0008',
                'code_personne' => 'PRS_DEMO0008',
                'cui' => 'CUI_DEMO0008',
                'code_institution' => self::INS_TI_BACONGO_MAKELEKELE,
                'code_fonction' => 'FONC_0009',
                'nom' => 'MOUKOKO',
                'prenom' => 'Henriette',
                'sexe' => 'F',
                'telephone' => '066889900',
            ],
            [
                'email' => 'procureur.tgi@sifec.cg',
                'pseudo' => 'demo_proc',
                'code_user' => 'USR_DEMO0009',
                'code_personne' => 'PRS_DEMO0009',
                'cui' => 'CUI_DEMO0009',
                'code_institution' => self::INS_TGI_BRAZZAVILLE,
                'code_fonction' => 'FONC_0018',
                'nom' => 'ONDO',
                'prenom' => 'Blaise Fabrice',
                'sexe' => 'M',
                'telephone' => '066990011',
            ],
        ];

        foreach ($comptes as $row) {
            $this->creerOuMettreAJourPersonne($row);
            $user = $this->creerOuMettreAJourUser($row);
            $this->creerOuMettreAJourAffectation($row);
        }

        $this->command?->info('Démo SIFEC : '.count($comptes).' comptes (personnes + users + affectations). Droits : tr_ff selon code_fonction.');
    }

    private function creerOuMettreAJourPersonne(array $row): void
    {
        $dateNaissance = '1982-06-15';
        $str = strtoupper(preg_replace('/\s+/', '', $row['nom'].$row['prenom'].$dateNaissance.'BRAZZAVILLE'.$row['sexe']));

        Personne::query()->updateOrInsert(
            ['code_personne' => $row['code_personne']],
            [
                'nom' => $row['nom'],
                'prenom' => $row['prenom'],
                'sexe' => $row['sexe'],
                'date_naissance' => $dateNaissance,
                'lieu_naissance' => 'Brazzaville',
                'code_localite' => self::LOCALITE_BRAZZAVILLE_COMMUNE,
                'telephone' => $row['telephone'],
                'adresse' => 'Brazzaville, République du Congo',
                'niveau_instruction' => 'SUPERIEUR',
                'code_nationalite' => self::NATIONALITE_CONGO,
                'personne_string' => $str,
                'statut_personne' => 'VIVANT',
                'type_date_naissance' => 'EXACTE',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    private function creerOuMettreAJourUser(array $row): User
    {
        User::query()->updateOrInsert(
            ['code_user' => $row['code_user']],
            [
                'code_personne' => $row['code_personne'],
                'pseudo' => $row['pseudo'],
                'email' => $row['email'],
                'password' => Hash::make(self::PASSWORD),
                'status' => 1,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return User::query()->whereKey($row['code_user'])->firstOrFail();
    }

    private function creerOuMettreAJourAffectation(array $row): void
    {
        InstitutionUser::query()->updateOrInsert(
            [
                'cui' => $row['cui'],
                'code_institution' => $row['code_institution'],
                'code_user' => $row['code_user'],
            ],
            [
                'code_fonction' => $row['code_fonction'],
                'active' => 1,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}
