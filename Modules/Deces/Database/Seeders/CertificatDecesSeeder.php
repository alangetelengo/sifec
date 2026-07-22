<?php

namespace Modules\Deces\Database\Seeders;

use App\Models\User;
use App\Sifec\Sifec;
use Carbon\Carbon;
use Database\Seeders\Concerns\PurgesDemoFaitData;
use Illuminate\Database\Seeder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Deces\Services\DeclarationDecesService;
use Modules\Deces\Services\MouvementService;
use Modules\Referentiel\Entities\Personne;

/**
 * Certificats de décès (formation sanitaire) et certificats de constatation
 * (centre d'hygiène), enregistrés en brouillon (sans envoi au PF).
 *
 * @see agentfs@sifec.cg (INS_0094) — type DECLARATION DE DECES — MOUV_0032
 * @see centre.hygiene@sifec.cg (INS_0198) — CERTIFICAT DE CONSTATATION DE DECES — MOUV_2005
 */
class CertificatDecesSeeder extends Seeder
{
    use PurgesDemoFaitData;
    private const TOTAL_CERTIFICATS_FS = 5;

    private const TOTAL_CERTIFICATS_CONSTATATION = 5;

    private const UNIQUE_RATIO = 0.80;

    private const CODE_LIEU_DECES_BRAZZAVILLE = 'LOC_0026';

    private int $personCounter = 0;

    public function run(): void
    {
        $this->resetTables();

        $userFs = $this->resolveUser('agentfs@sifec.cg');
        $userHygiene = $this->resolveUser('centre.hygiene@sifec.cg');

        if ($userFs === null || $userHygiene === null) {
            return;
        }

        $religion = DB::table('tr_religion')->first();
        $causesDeces = DB::table('tr_cause_deces')->get();
        $filiation = DB::table('tr_filiation')->first();

        if (! $religion || $causesDeces->isEmpty() || ! $filiation) {
            $this->command?->warn('Référentiels religion / causes / filiation manquants. Exécutez les seeders référentiels.');

            return;
        }

        $causesDecesCodes = $causesDeces->pluck('code_cause_deces')->toArray();
        $namesData = $this->buildNamesData();
        $sharedFamilies = $this->buildSharedFamilies(
            $namesData['surnames'],
            $namesData['maleNames'],
            $namesData['femaleNames'],
            $namesData['streetNames'],
            $namesData['localiteCodes'],
            $namesData['lieuxNaissance']
        );

        /** @var DeclarationDecesService $service */
        $service = app(DeclarationDecesService::class);
        /** @var MouvementService $mouvementService */
        $mouvementService = app(MouvementService::class);

        $rangesMensuels = $this->buildMonthlyRanges();
        if (empty($rangesMensuels)) {
            $this->command?->warn('Impossible de construire la période mensuelle de génération.');

            return;
        }

        $createdFs = $this->seedBatch(
            $service,
            $mouvementService,
            $userFs,
            self::TOTAL_CERTIFICATS_FS,
            'DECLARATION DE DECES',
            'declaration_deces',
            'MOUV_0002',
            self::CODE_LIEU_DECES_BRAZZAVILLE,
            0,
            $namesData,
            $sharedFamilies,
            $religion,
            $filiation,
            $causesDecesCodes,
            $rangesMensuels
        );

        $createdConstatation = $this->seedBatch(
            $service,
            $mouvementService,
            $userHygiene,
            self::TOTAL_CERTIFICATS_CONSTATATION,
            'CERTIFICAT DE CONSTATATION DE DECES',
            'certificat_constatation_deces',
            'MOUV_2006',
            self::CODE_LIEU_DECES_BRAZZAVILLE,
            50000,
            $namesData,
            $sharedFamilies,
            $religion,
            $filiation,
            $causesDecesCodes,
            $rangesMensuels
        );

        $this->command?->info("Certificats FS (brouillon, MOUV_0032) : {$createdFs}");
        $this->command?->info('Certificats constatation (brouillon, MOUV_2005) : '.$createdConstatation);
    }

    private function resolveUser(string $email): ?User
    {
        $user = User::where('email', $email)
            ->with(['affectations' => fn ($q) => $q->where('active', 1)])
            ->first();

        if (! $user) {
            $this->command?->warn("Utilisateur {$email} introuvable (seeder abandonné).");

            return null;
        }

        if (! $user->affectationActive()) {
            $this->command?->warn("Affectation active introuvable pour {$email} (seeder abandonné).");

            return null;
        }

        return $user;
    }

    /**
     * @return array<int, array{debut: Carbon, fin: Carbon, cursor: int}>
     */
    private function buildMonthlyRanges(): array
    {
        $now = Carbon::now();
        $rangesMensuels = [];

        for ($mois = 1; $mois <= (int) $now->month; $mois++) {
            $debutMois = Carbon::create((int) $now->year, $mois, 1)->startOfDay();
            $finMois = $mois === (int) $now->month
                ? $now->copy()->startOfDay()
                : $debutMois->copy()->endOfMonth()->startOfDay();

            if ($finMois->lt($debutMois)) {
                continue;
            }

            $rangesMensuels[] = [
                'debut' => $debutMois,
                'fin' => $finMois,
                'cursor' => 0,
            ];
        }

        return $rangesMensuels;
    }

    private function seedBatch(
        DeclarationDecesService $service,
        MouvementService $mouvementService,
        User $user,
        int $total,
        string $typeDeclaration,
        string $typeEvenementEnregistrement,
        string $codeMouvementEnvoiAttendu,
        string $lieuDecesCode,
        int $seedOffset,
        array $namesData,
        array $sharedFamilies,
        object $religion,
        object $filiation,
        array $causesDecesCodes,
        array &$rangesMensuels
    ): int {
        $now = Carbon::now();
        $totalUnique = (int) round($total * self::UNIQUE_RATIO);
        $created = 0;

        $basePayload = [
            'code_situation_matrimoniale_defunt' => 'SMAT_0003',
            'sexe_defunt' => 'M',
            'statut_personne_defunt' => 'DECEDE',
            'type_date_naissance_defunt' => 'EXACTE',
            'statut_personne_declarant' => 'VIVANT',
            'type_date_naissance_mere' => 'EXACTE',
            'statut_personne_mere' => 'VIVANT',
            'type_date_naissance_pere' => 'EXACTE',
            'statut_personne_pere' => 'VIVANT',
            'code_type_document_pere' => 'TDOC_0018',
            'code_type_document_mere' => 'TDOC_0018',
            'code_type_document_declarant' => 'TDOC_0018',
            'code_nationalite_pere' => 'NAT_0001',
            'code_nationalite_mere' => 'NAT_0001',
            'code_nationalite_declarant' => 'NAT_0001',
            'code_nationalite_defunt' => 'NAT_0001',
            'niveau_instruction_pere' => 'SECONDAIRE NIVEAU II',
            'niveau_instruction_mere' => 'SECONDAIRE NIVEAU II',
            'niveau_instruction_declarant' => 'SECONDAIRE NIVEAU II',
            'profession_pere' => 'PROF_0010',
            'profession_mere' => 'PROF_0011',
            'profession_declarant' => 'PROF_0010',
            'code_profession_pere' => 'PROF_0010',
            'code_profession_mere' => 'PROF_0011',
            'code_profession_declarant' => 'PROF_0010',
            'profession_defunt' => 'PROF_0010',
            'code_pays_pere' => '+242',
            'code_pays_mere' => '+242',
            'code_pays_declarant' => '+242',
            'code_pays_defunt' => '+242',
            'domicile_pays_pere' => 'Congo',
            'domicile_pays_mere' => 'Congo',
            'domicile_pays_declarant' => 'Congo',
            'domicile_pays_defunt' => 'Congo',
            'domicile_arrondissement_pere' => null,
            'domicile_quartier_pere' => null,
            'domicile_arrondissement_mere' => null,
            'domicile_quartier_mere' => null,
            'domicile_arrondissement_declarant' => null,
            'domicile_quartier_declarant' => null,
            'domicile_arrondissement_defunt' => null,
            'domicile_quartier_defunt' => null,
            'type_declaration' => $typeDeclaration,
            'type_declarant' => 'Personne physique',
            'lieu_survenance_code' => 'LSURV_0001',
            'code_religion_defunt' => $religion->code_religion,
            'filiation' => $filiation->code_filiation,
            'cec_naissance' => 'MAIRIE DE L\'ARRONDISSEMENT 1 MAKELEKELE',
            'nom_medecin' => 'DR SEED MEDECIN',
        ];

        for ($i = 0; $i < $total; $i++) {
            $seed = $seedOffset + $i;
            $isSharedFamily = $i >= $totalUnique;

            if ($isSharedFamily) {
                $familyData = $sharedFamilies[$i % count($sharedFamilies)];
            } else {
                $familyData = $this->createFamilyData(
                    $seed,
                    $namesData['surnames'],
                    $namesData['maleNames'],
                    $namesData['femaleNames'],
                    $namesData['streetNames'],
                    $namesData['localiteCodes'],
                    $namesData['lieuxNaissance']
                );
            }

            $payload = array_merge($basePayload, $familyData);
            $payload['sexe_defunt'] = ($seed % 2 === 0) ? 'M' : 'F';

            $nbCauses = rand(1, min(3, count($causesDecesCodes)));
            $causesDisponibles = $causesDecesCodes;
            $causesSelectionnees = [];
            for ($j = 0; $j < $nbCauses; $j++) {
                $indexAleatoire = array_rand($causesDisponibles);
                $causesSelectionnees[] = $causesDisponibles[$indexAleatoire];
                unset($causesDisponibles[$indexAleatoire]);
                $causesDisponibles = array_values($causesDisponibles);
                if (empty($causesDisponibles)) {
                    break;
                }
            }
            $payload['code_cause_deces'] = $causesSelectionnees;

            $indexMois = $i % count($rangesMensuels);
            $debutMois = $rangesMensuels[$indexMois]['debut'];
            $finMois = $rangesMensuels[$indexMois]['fin'];
            $joursDansMois = $debutMois->diffInDays($finMois) + 1;
            $offsetJour = $rangesMensuels[$indexMois]['cursor'] % $joursDansMois;
            $rangesMensuels[$indexMois]['cursor']++;
            $dateDeces = $debutMois->copy()->addDays($offsetJour);
            $heureDeces = Carbon::createFromTime(8, 0)->addMinutes($seed % 1440)->format('H:i');

            $ageDefunt = rand(18, 90);
            $dateNaissanceDefunt = $dateDeces->copy()->subYears($ageDefunt)->subDays(rand(0, 365));
            $payload['date_naissance_defunt'] = $dateNaissanceDefunt->format('Y-m-d');

            $maxDelaiJours = min(30, $dateDeces->diffInDays($now->copy()->startOfDay()));
            $joursDelai = $maxDelaiJours > 0 ? rand(0, $maxDelaiJours) : 0;
            $dateDeclaration = $dateDeces->copy()->addDays($joursDelai)->addMinutes(rand(0, 1439));
            if ($dateDeclaration->gt($now)) {
                $dateDeclaration = $now->copy();
            }

            $payload['date_deces'] = $dateDeces->format('Y-m-d');
            $payload['heure_deces'] = $heureDeces;
            $payload['date_heure_declaration'] = $dateDeclaration->format('Y-m-d H:i');
            $payload['lieu_deces'] = $lieuDecesCode;
            $payload['domicile_defunt'] = $payload['domicile_typevoie_pere'].' '.$payload['domicile_numero_pere'].', '.$payload['domicile_nomvoie_pere'];

            $request = new Request($payload);

            $uniqueDefunt = Sifec::uniqueString($request, '_defunt', $request->sexe_defunt);
            $defunt = Personne::where('personne_string', $uniqueDefunt)->first();
            if ($defunt && $defunt->declarationDeces) {
                continue;
            }

            $declaration = $service->enregistrer($request, $user);
            if ($declaration instanceof JsonResponse || ! $declaration) {
                continue;
            }

            $declaration->refresh();
            $declaration->date_heure_declaration = $dateDeclaration;
            $declaration->date_heure_deces = $dateDeces->format('Y-m-d').' '.$heureDeces.':00';
            $declaration->lieu_deces = $lieuDecesCode;
            $declaration->created_at = $dateDeclaration;
            $declaration->updated_at = $dateDeclaration;
            $declaration->save();

            [$okMouvement, $messageMouvement] = $mouvementService->ajouterEvenementDeclaration(
                $user,
                $declaration,
                $typeEvenementEnregistrement,
                'Certificat enregistré via seeder'
            );

            if (! $okMouvement) {
                $this->command?->warn("Mouvement enregistrement impossible pour {$declaration->code_declaration_deces} : {$messageMouvement}");
                continue;
            }

            $declaration->refresh();
            $created++;

            if ($created % 5 === 0) {
                $this->command?->info("[{$typeDeclaration}] {$created}/{$total} certificats traités");
            }
        }

        return $created;
    }

    /**
     * @return array{surnames: array, maleNames: array, femaleNames: array, streetNames: array, localiteCodes: array, lieuxNaissance: array}
     */
    private function buildNamesData(): array
    {
        return [
            'surnames' => array_values(array_unique(array_map(fn ($value) => mb_strtoupper($value, 'UTF-8'), [
                'ELENGA', 'TCHETEMBO', 'BISSIKA', 'MBONGO', 'FOUTOU', 'LIKIBI', 'MANIANGUI', 'BOSSOUBA',
                'TATY', 'LOUDIMA', 'LIMANDOU', 'MASSALA', 'NZILA', 'NDZOMA', 'OPALA', 'NIANGA',
                'NGODILA', 'TONGO', 'TANGUISSA', 'NZOMBA', 'MAPASSA', 'NFILA', 'MOKIBA', 'DONGUI',
                'MABIALA', 'NGANGA', 'NGOMA', 'MBOUKOU', 'MASSAMBA', 'BISSILA', 'OUAMBA', 'KIBAMBA',
                'DJOMBO', 'TCHITINGOU',
            ]))),
            'maleNames' => array_values(array_unique(array_map(fn ($value) => mb_strtoupper($value, 'UTF-8'), [
                'PRINCE', 'ARNAUD', 'SYLVAIN', 'FAREL', 'DISTEL', 'ARCHANGE', 'STEVE', 'PAVEL',
                'NICODEM', 'ADAM', 'ERIC', 'CONSTANT', 'MICHEL', 'ARMAND', 'RIGOBERT', 'ALPHONSE',
                'GERVAIS', 'PATRICK', 'ROMARIC', 'EMILE', 'BLAISE', 'MARCEL', 'CHRISTIAN',
            ]))),
            'femaleNames' => array_values(array_unique(array_map(fn ($value) => mb_strtoupper($value, 'UTF-8'), [
                'STALLA', 'PAMELA', 'NADEGE', 'PAULE', 'LAURIE', 'SANDRINE', 'FLORE', 'MIRIAME',
                'NINELLE', 'RACHELLE', 'PIERLINE', 'JEANNE', 'MARIE', 'PAULINE', 'SYLVIE', 'ERCINA',
                'RUTH', 'REBBECA', 'ANNE', 'MERLINE', 'PRISCA', 'CLARISSE', 'MIREILLE', 'JOSIANE',
            ]))),
            'streetNames' => array_values(array_unique(array_map(fn ($value) => mb_strtoupper($value, 'UTF-8'), [
                'MASSA', 'NGO', 'DJAMBALA', 'MARIEN NGOUABI', '31 JUILLET', 'DE LA PAIX', 'MOE POATY',
                'LIRANGA', 'BOUNDJI', 'DE LA LIBERTE', 'MPOUYA', 'DU CONGO', 'MALONGA', 'BAKONGO',
            ]))),
            'localiteCodes' => ['LOC_0001', 'LOC_0016', 'LOC_0013', 'LOC_0008'],
            'lieuxNaissance' => ['BRAZZAVILLE', 'POINTE-NOIRE', 'OUESSO', 'DOLISIE', 'OYO'],
        ];
    }

    private function createFamilyData(
        int $seed,
        array $surnames,
        array $maleNames,
        array $femaleNames,
        array $streetNames,
        array $localiteCodes,
        array $lieuxNaissance
    ): array {
        $street = $streetNames[$seed % count($streetNames)];
        $typeVoie = 'Rue';
        $numeroVoie = (string) (10 + ($seed % 90));

        $defuntNames = $this->makeNomPrenom(($seed % 2 === 0) ? 'M' : 'F', $surnames, $maleNames, $femaleNames, $seed);
        $declarantNames = $this->makeNomPrenom('M', $surnames, $maleNames, $femaleNames, $seed * 3 + 1000);
        $fatherNames = $this->makeNomPrenom('M', $surnames, $maleNames, $femaleNames, $seed * 5 + 2000);
        $motherNames = $this->makeNomPrenom('F', $surnames, $maleNames, $femaleNames, $seed * 7 + 3000);

        $maxDaysDefunt = Carbon::create(2006, 12, 31)->diffInDays(Carbon::create(1934, 1, 1));
        mt_srand($seed);
        $defuntBirth = Carbon::create(1934, 1, 1)->addDays($seed % ($maxDaysDefunt + 1));

        $maxDaysDeclarant = Carbon::create(2006, 12, 31)->diffInDays(Carbon::create(1975, 1, 1));
        mt_srand($seed * 3 + 5000);
        $declarantBirth = Carbon::create(1975, 1, 1)->addDays(($seed * 3) % ($maxDaysDeclarant + 1));

        mt_srand($seed * 11 + 10000);
        $fatherBirth = $defuntBirth->copy()->subYears(mt_rand(20, 40))->subDays(mt_rand(0, 365));

        mt_srand($seed * 13 + 15000);
        $motherBirth = $defuntBirth->copy()->subYears(mt_rand(16, 35))->subDays(mt_rand(0, 365));

        $lieuNaissanceDefunt = $lieuxNaissance[$seed % count($lieuxNaissance)];
        $lieuNaissanceDeclarant = $lieuxNaissance[($seed + 1) % count($lieuxNaissance)];
        $lieuNaissancePere = $lieuxNaissance[($seed + 2) % count($lieuxNaissance)];
        $lieuNaissanceMere = $lieuxNaissance[($seed + 3) % count($lieuxNaissance)];

        return [
            'nom_defunt' => $defuntNames['nom'],
            'prenom_defunt' => $defuntNames['prenom'],
            'date_naissance_defunt' => $defuntBirth->format('Y-m-d'),
            'lieu_naissance_defunt' => $lieuNaissanceDefunt,
            'code_localite_defunt' => $localiteCodes[$seed % count($localiteCodes)],
            'code_profession_defunt' => 'PROF_0010',
            'niveau_instruction_defunt' => 'SECONDAIRE NIVEAU II',
            'nom_declarant' => $declarantNames['nom'],
            'prenom_declarant' => $declarantNames['prenom'],
            'sexe_declarant' => 'M',
            'date_naissance_declarant' => $declarantBirth->format('Y-m-d'),
            'lieu_naissance_declarant' => $lieuNaissanceDeclarant,
            'code_localite_declarant' => $localiteCodes[($seed + 1) % count($localiteCodes)],
            'telephone_declarant' => sprintf('066%06d', ($seed * 17) % 1000000),
            'numero_document_declarant' => sprintf('CG-DECL-%06d', $seed + 1),
            'email_declarant' => $this->buildEmail($declarantNames['prenom'], $declarantNames['nom'], $seed),
            'nom_pere' => $fatherNames['nom'],
            'prenom_pere' => $fatherNames['prenom'],
            'date_naissance_pere' => $fatherBirth->format('Y-m-d'),
            'lieu_naissance_pere' => $lieuNaissancePere,
            'code_localite_pere' => $localiteCodes[($seed + 2) % count($localiteCodes)],
            'telephone_pere' => sprintf('066%06d', ($seed * 19) % 1000000),
            'numero_document_pere' => sprintf('CG-PERE-%06d', $seed + 1),
            'email_pere' => $this->buildEmail($fatherNames['prenom'], $fatherNames['nom'], $seed + 1000),
            'nom_mere' => $motherNames['nom'],
            'prenom_mere' => $motherNames['prenom'],
            'date_naissance_mere' => $motherBirth->format('Y-m-d'),
            'lieu_naissance_mere' => $lieuNaissanceMere,
            'code_localite_mere' => $localiteCodes[($seed + 3) % count($localiteCodes)],
            'telephone_mere' => sprintf('065%06d', ($seed * 23) % 1000000),
            'numero_document_mere' => sprintf('CG-MERE-%06d', $seed + 1),
            'email_mere' => $this->buildEmail($motherNames['prenom'], $motherNames['nom'], $seed + 2000),
            'domicile_typevoie_pere' => $typeVoie,
            'domicile_numero_pere' => $numeroVoie,
            'domicile_nomvoie_pere' => $street,
            'domicile_typevoie_mere' => $typeVoie,
            'domicile_numero_mere' => $numeroVoie,
            'domicile_nomvoie_mere' => $street,
            'domicile_typevoie_declarant' => $typeVoie,
            'domicile_numero_declarant' => $numeroVoie,
            'domicile_nomvoie_declarant' => $street,
            'domicile_typevoie_defunt' => $typeVoie,
            'domicile_numero_defunt' => $numeroVoie,
            'domicile_nomvoie_defunt' => $street,
        ];
    }

    private function buildSharedFamilies(
        array $surnames,
        array $maleNames,
        array $femaleNames,
        array $streetNames,
        array $localiteCodes,
        array $lieuxNaissance
    ): array {
        $families = [];
        $baseSeed = 10000;
        for ($i = 0; $i < 5; $i++) {
            $families[] = $this->createFamilyData(
                $baseSeed + $i,
                $surnames,
                $maleNames,
                $femaleNames,
                $streetNames,
                $localiteCodes,
                $lieuxNaissance
            );
        }

        return $families;
    }

    private function makeNomPrenom(string $gender, array $surnames, array $maleNames, array $femaleNames, ?int $uniqueSeed = null): array
    {
        if ($uniqueSeed === null) {
            $this->personCounter++;
            $uniqueSeed = $this->personCounter;
        }

        mt_srand($uniqueSeed * 1000 + ($gender === 'M' ? 1 : 2));

        $patterns = [
            ['noms' => 2, 'prenoms' => 2],
            ['noms' => 2, 'prenoms' => 1],
            ['noms' => 3, 'prenoms' => 1],
            ['noms' => 1, 'prenoms' => 2],
            ['noms' => 1, 'prenoms' => 1],
        ];

        $pattern = $patterns[$uniqueSeed % count($patterns)];
        $surnameCount = max(1, min($pattern['noms'], count($surnames)));
        $surnameParts = $this->pickUniqueElementsSeeded($surnames, $surnameCount, $uniqueSeed * 7);
        $nom = $this->formatParts($surnameParts);

        $firstNamePool = $gender === 'M' ? $maleNames : $femaleNames;
        $prenomCount = min($pattern['prenoms'], count($firstNamePool));
        $prenom = '';
        if ($prenomCount > 0) {
            $prenomParts = $this->pickUniqueElementsSeeded($firstNamePool, $prenomCount, $uniqueSeed * 11);
            $prenom = $this->formatParts($prenomParts);
        }

        return ['nom' => $nom, 'prenom' => $prenom];
    }

    private function pickUniqueElementsSeeded(array $source, int $count, int $seed): array
    {
        $count = min($count, count($source));
        if ($count === 1) {
            mt_srand($seed);

            return [$source[mt_rand(0, count($source) - 1)]];
        }

        mt_srand($seed);
        $availableKeys = range(0, count($source) - 1);
        $selectedKeys = [];

        for ($i = 0; $i < $count; $i++) {
            $randomIndex = mt_rand(0, count($availableKeys) - 1);
            $selectedKeys[] = $availableKeys[$randomIndex];
            unset($availableKeys[$randomIndex]);
            $availableKeys = array_values($availableKeys);
        }

        $parts = [];
        foreach ($selectedKeys as $key) {
            $parts[] = $source[$key];
        }

        return $parts;
    }

    private function formatParts(array $parts): string
    {
        $upperParts = array_map(fn ($value) => mb_strtoupper(trim($value), 'UTF-8'), $parts);

        return implode(' ', array_unique($upperParts));
    }

    private function buildEmail(string $firstname, string $lastname, int $seed): string
    {
        $slugLast = $this->slug(str_replace(' ', '', $lastname));
        $slugFirst = $this->slug(str_replace(' ', '', $firstname));
        $localPart = $slugFirst !== '' ? $slugFirst : $slugLast;
        if ($localPart === '') {
            $localPart = 'civil';
        }
        $domainPart = $slugLast !== '' ? $slugLast : 'cg';

        return sprintf('%s.%s%03d@cg.cg', $localPart, $domainPart, $seed % 1000);
    }

    private function slug(string $value): string
    {
        $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
        $value = preg_replace('/[^a-zA-Z0-9]/', '', $value ?? '');

        return strtolower($value ?? '');
    }

    private function resetTables(): void
    {
        $this->purgeDecesDemoData();
    }
}
