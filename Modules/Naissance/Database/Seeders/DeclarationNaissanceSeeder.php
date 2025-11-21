<?php

namespace Modules\Naissance\Database\Seeders;

use App\Models\User;
use App\Sifec\Sifec;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Naissance\Entities\MouvementNaissance;
use Modules\Naissance\Services\DeclarationNaissanceService;
use Modules\Referentiel\Entities\Personne;
use Modules\Referentiel\Entities\Localite;

class DeclarationNaissanceSeeder extends Seeder
{
    private const TOTAL_DECLARATIONS = 3000;
    private const UNIQUE_RATIO = 0.80; // 80 % de familles uniques

    private int $personCounter = 0;

    public function run(): void
    {
        $this->resetTables();

        $user = User::where('email', 'sandrine@gmail.com')
            ->with(['affectations' => fn ($q) => $q->where('active', 1)])
            ->first();

        if (!$user) {
            $this->command?->warn("Utilisateur sandrine@gmail.com introuvable (seeder abandonné).");
            return;
        }

        $affectation = $user->affectationActive();
        if (!$affectation) {
            $this->command?->warn("Affectation active introuvable pour sandrine@gmail.com (seeder abandonné).");
            return;
        }

        $institutionLocaliteLib = 'BRAZZAVILLE';
        $institutionLocaliteCode = 'LOC_0026';
        $institution = $affectation->institution;
        $localite = null;

        if ($institution && $institution->lalocalite) {
            $localite = $institution->lalocalite;
        }

        if (!$localite) {
            $localite = Localite::find('LOC_0026');
        }

        if ($localite) {
            $birthLocalite = $localite;
            while ($birthLocalite && !in_array($birthLocalite->code_type_localite, ['TPLOC_0002', 'TPLOC_0003'])) {
                $birthLocalite = $birthLocalite->localiteParent;
            }

            if (!$birthLocalite) {
                $birthLocalite = Localite::find('LOC_0026');
            }

            if ($birthLocalite) {
                $institutionLocaliteLib = mb_strtoupper($birthLocalite->lib_localite ?? 'BRAZZAVILLE', 'UTF-8');
                $institutionLocaliteCode = $birthLocalite->code_localite ?? 'LOC_0026';
            }
        }

        $surnames = array_values(array_unique(array_map(fn ($value) => mb_strtoupper($value, 'UTF-8'), [
            'ELENGA', 'TCHETEMBO', 'BISSIKA', 'MBONGO', 'FOUTOU', 'LIKIBI', 'MANIANGUI', 'BOSSOUBA',
            'TATY', 'LOUDIMA', 'LIMANDOU', 'MASSALA', 'NZILA', 'NDZOMA', 'OPALA', 'NIANGA',
            'NGODILA', 'TONGO', 'TANGUISSA', 'NZOMBA', 'MAPASSA', 'NFILA', 'MOKIBA', 'DONGUI',
            'MABIALA', 'NGANGA', 'NGOMA', 'MBOUKOU', 'MASSAMBA', 'BISSILA', 'OUAMBA', 'KIBAMBA',
            'DJOMBO', 'TCHITINGOU'
        ])));

        $maleNames = array_values(array_unique(array_map(fn ($value) => mb_strtoupper($value, 'UTF-8'), [
            'PRINCE', 'ARNAUD', 'SYLVAIN', 'FAREL', 'DISTEL', 'ARCHANGE', 'STEVE', 'PAVEL',
            'NICODEM', 'ADAM', 'ERIC', 'CONSTANT', 'MICHEL',
            'ARMAND', 'RIGOBERT', 'ALPHONSE', 'GERVAIS', 'PATRICK', 'ROMARIC', 'EMILE', 'BLAISE',
            'MARCEL', 'CHRISTIAN'
        ])));

        $femaleNames = array_values(array_unique(array_map(fn ($value) => mb_strtoupper($value, 'UTF-8'), [
            'STALLA', 'PAMELA', 'NADEGE', 'PAULE', 'LAURIE', 'SANDRINE', 'FLORE', 'MIRIAME',
            'NINELLE', 'RACHELLE', 'PIERLINE', 'JEANNE', 'MARIE', 'PAULINE', 'SYLVIE', 'ERCINA',
            'RUTH', 'REBBECA', 'ANNE',
            'MERLINE', 'PRISCA', 'CLARISSE', 'MIREILLE', 'JOSIANE', 'MERVEILLE', 'FRANCINE'
        ])));

        $childNames = array_values(array_unique(array_map(fn ($value) => mb_strtoupper($value, 'UTF-8'), [
            'GABRIEL', 'GABRIELLE', 'ANGE', 'BERTRICK', 'VICTOIRE', 'VINQUEUR', 'FRESNEL', 'HERCINA',
            'JEANNICE', 'ORNELLA', 'PRINCESSE', 'SISI', 'DORCAS',
            'BRICE', 'EMMANUEL', 'JOEL', 'KADER', 'MIKAEL', 'CEDRIC', 'BRUNO', 'WILFRID', 'SEBASTIEN',
            'FIRMIN'
        ])));

        $streetNames = array_values(array_unique(array_map(fn ($value) => mb_strtoupper($value, 'UTF-8'), [
            'RUE MASSA', 'RUE NGO', 'RUE DJAMBALA', 'AVENUE MARIEN NGOUABI', 'AVENUE 31 JUILLET',
            'AVENUE DE LA PAIX', 'AVENUE MOE POATY', 'RUE LIRANGA', 'RUE BOUNDJI',
            'AVENUE DE LA LIBERTE', 'RUE MPOUYA', 'AVENUE DU CONGO', 'AVENUE MALONGA', 'RUE BAKONGO'
        ])));

        $localiteCodes = ['LOC_0001', 'LOC_0016', 'LOC_0013', 'LOC_0008'];
        $lieuxNaissance = ['BRAZZAVILLE', 'POINTE-NOIRE', 'OUESSO', 'DOLISIE', 'OYO'];

        $totalUnique = (int) round(self::TOTAL_DECLARATIONS * self::UNIQUE_RATIO);

        $basePayload = [
            'code_situation_matrimoniale' => 'SMAT_0001',
            'sexe_enfant' => 'M',
            'nombre_enfant' => '1',
            'statut_personne_enfant' => 'VIVANT',

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
            'niveau_instruction_pere' => 'SECONDAIRE NIVEAU II',
            'niveau_instruction_mere' => 'SECONDAIRE NIVEAU II',
            'niveau_instruction_declarant' => 'SECONDAIRE NIVEAU II',
            'profession_pere' => 'PROF_0010',
            'profession_mere' => 'PROF_0011',
            'profession_declarant' => 'PROF_0010',
            'code_pays_pere' => '+242',
            'code_pays_mere' => '+242',
            'code_pays_declarant' => '+242',

            'domicile_pays_pere' => 'Congo',
            'domicile_pays_mere' => 'Congo',
            'domicile_pays_declarant' => 'Congo',
            'domicile_pays_enfant' => 'Congo',
            'domicile_arrondissement_pere' => null,
            'domicile_quartier_pere' => null,
            'domicile_arrondissement_mere' => null,
            'domicile_quartier_mere' => null,
            'domicile_arrondissement_declarant' => null,
            'domicile_quartier_declarant' => null,
            'domicile_arrondissement_enfant' => null,
            'domicile_quartier_enfant' => null,

            'type_declaration' => 'DECLARATION DE NAISSANCE',
            'type_declarant' => 'Personne physique',
            'personne_declaree' => 'Enfant normal',

            'lieu_survenance' => 'LSURV_0001',
            'lieu_placement' => null,
            'num_fiche_placement' => null,
            'num_jugement_placement_provisoir' => null,
            'piece_extrait_main_courante' => null,
        ];

        $sharedFamilies = $this->buildSharedFamilies(
            $surnames,
            $maleNames,
            $femaleNames,
            $streetNames,
            $localiteCodes,
            $lieuxNaissance
        );

        /** @var DeclarationNaissanceService $service */
        $service = app(DeclarationNaissanceService::class);

        $refMouvement = DB::table('tr_mouvement')->where('code_mouvement', 'MOUV_0024')->first();
        if (!$refMouvement) {
            $this->command?->warn("Référentiel mouvement MOUV_0024 introuvable (mouvement non créé).");
            return;
        }

        $created = 0;

        for ($i = 0; $i < self::TOTAL_DECLARATIONS; $i++) {
            $isSharedFamily = $i >= $totalUnique;

            if ($isSharedFamily) {
                $familyData = $sharedFamilies[$i % count($sharedFamilies)];
            } else {
                $familyData = $this->createFamilyData(
                    $i,
                    $surnames,
                    $maleNames,
                    $femaleNames,
                    $streetNames,
                    $localiteCodes,
                    $lieuxNaissance
                );
            }

            $payload = array_merge($basePayload, $familyData);

            $dateNaissanceEnfant = Carbon::create(2025, 1, 1)->addDays($i);
            $heureNaissance = Carbon::createFromTime(7, 30)->addMinutes($i % 1440)->format('H:i');
            $joursDelai = rand(0, 29);
            $minutesDelai = rand(0, 1439);
            $dateDeclaration = $dateNaissanceEnfant->copy()->addDays($joursDelai)->addMinutes($minutesDelai);

            $payload['date_naissance_enfant'] = $dateNaissanceEnfant->format('Y-m-d');
            $payload['heure_naissance_enfant'] = $heureNaissance;
            $payload['date_heure_declaration'] = $dateDeclaration->format('Y-m-d H:i');
            $payload['prenom_enfant'] = mb_strtoupper($childNames[$i % count($childNames)], 'UTF-8');
            $payload['nom_enfant'] = $payload['nom_pere'];
            $payload['lieu_naissance_enfant'] = $institutionLocaliteLib;
            $payload['domicile_numero_enfant'] = $payload['domicile_numero_pere'];
            $payload['domicile_nomvoie_enfant'] = $payload['domicile_nomvoie_pere'];
            $payload['domicile_typevoie_enfant'] = $payload['domicile_typevoie_pere'];
            $payload['code_localite_enfant'] = $institutionLocaliteCode;

            $request = new Request($payload);

            $uniqueEnfant = Sifec::uniqueString($request, '_enfant', $request->sexe_enfant);
            $enfant = Personne::where('personne_string', $uniqueEnfant)->first();
            if ($enfant && $enfant->declarationNaissance) {
                continue;
            }

            $declaration = $service->enregistrer($request, $user);
            if ($declaration instanceof JsonResponse || !$declaration) {
                continue;
            }

            $mouvement = new MouvementNaissance();
            $mouvement->code_mouvement_naissance = Sifec::genererCodeUniqueReferentiel($mouvement, 'code_mouvement_naissance', 4, 'MDN_');
            $mouvement->code_declaration_naissance = $declaration->code_declaration_naissance;
            $mouvement->code_mouvement = $refMouvement->code_mouvement;
            $mouvement->lib_mouvement = $refMouvement->lib_mouvement;
            $mouvement->statut = 'En cours';
            $mouvement->cui = $affectation->cui;
            $mouvement->save();

            $created++;

            if ($created % 100 === 0) {
                $this->command?->info("$created déclarations enregistrées");
            }
        }

        $this->command?->info("Total déclarations créées: $created");
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
        $typeVoie = str_starts_with($street, 'AVENUE') ? 'Avenue' : 'Rue';
        $numeroVoie = (string) (10 + ($seed % 90));

        $fatherNames = $this->makeNomPrenom('M', $surnames, $maleNames, $femaleNames);
        $motherNames = $this->makeNomPrenom('F', $surnames, $maleNames, $femaleNames);

        $fatherBirth = Carbon::create(1975, 1, 1)->addDays($seed % 8000);
        $motherBirth = Carbon::create(1978, 1, 1)->addDays(($seed * 7) % 8000);

        $lieuNaissancePere = $lieuxNaissance[$seed % count($lieuxNaissance)];
        $lieuNaissanceMere = $lieuxNaissance[($seed + 2) % count($lieuxNaissance)];
        $localitePere = $localiteCodes[$seed % count($localiteCodes)];
        $localiteMere = $localiteCodes[($seed + 1) % count($localiteCodes)];

        $telephonePere = sprintf('066%06d', ($seed * 17) % 1000000);
        $telephoneMere = sprintf('065%06d', ($seed * 29) % 1000000);

        $documentPere = sprintf('CG-PERE-%06d', $seed + 1);
        $documentMere = sprintf('CG-MERE-%06d', $seed + 1);

        $emailPere = $this->buildEmail($fatherNames['prenom'], $fatherNames['nom'], $seed);
        $emailMere = $this->buildEmail($motherNames['prenom'], $motherNames['nom'], $seed + 2000);

        return [
            'nom_pere' => $fatherNames['nom'],
            'prenom_pere' => $fatherNames['prenom'],
            'date_naissance_pere' => $fatherBirth->format('Y-m-d'),
            'lieu_naissance_pere' => $lieuNaissancePere,
            'code_localite_pere' => $localitePere,
            'telephone_pere' => $telephonePere,
            'numero_document_pere' => $documentPere,
            'email_pere' => $emailPere,

            'nom_mere' => $motherNames['nom'],
            'prenom_mere' => $motherNames['prenom'],
            'date_naissance_mere' => $motherBirth->format('Y-m-d'),
            'lieu_naissance_mere' => $lieuNaissanceMere,
            'code_localite_mere' => $localiteMere,
            'telephone_mere' => $telephoneMere,
            'numero_document_mere' => $documentMere,
            'email_mere' => $emailMere,

            'nom_declarant' => $fatherNames['nom'],
            'prenom_declarant' => $fatherNames['prenom'],
            'date_naissance_declarant' => $fatherBirth->format('Y-m-d'),
            'lieu_naissance_declarant' => $lieuNaissancePere,
            'code_localite_declarant' => $localitePere,
            'telephone_declarant' => $telephonePere,
            'numero_document_declarant' => $documentPere,
            'email_declarant' => $emailPere,
            'filiation' => 'FIL_0001',

            'domicile_typevoie_pere' => $typeVoie,
            'domicile_numero_pere' => $numeroVoie,
            'domicile_nomvoie_pere' => $street,

            'domicile_typevoie_mere' => $typeVoie,
            'domicile_numero_mere' => $numeroVoie,
            'domicile_nomvoie_mere' => $street,

            'domicile_typevoie_declarant' => $typeVoie,
            'domicile_numero_declarant' => $numeroVoie,
            'domicile_nomvoie_declarant' => $street,

            'code_localite_enfant' => $localitePere,
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

    private function makeNomPrenom(string $gender, array $surnames, array $maleNames, array $femaleNames): array
    {
        $this->personCounter++;
        $mod = $this->personCounter % 100;

        $patterns = [
            ['noms' => 2, 'prenoms' => 2],
            ['noms' => 2, 'prenoms' => 1],
            ['noms' => 3, 'prenoms' => 1],
            ['noms' => 3, 'prenoms' => 0],
            ['noms' => 1, 'prenoms' => 2],
            ['noms' => 2, 'prenoms' => 1],
            ['noms' => 1, 'prenoms' => 3],
            ['noms' => 1, 'prenoms' => 1],
            ['noms' => 2, 'prenoms' => 0],
        ];

        $pattern = $patterns[$mod % count($patterns)];

        $surnameCount = max(1, min($pattern['noms'], count($surnames)));
        $surnameParts = $this->pickUniqueElements($surnames, $surnameCount);
        $nom = $this->formatParts($surnameParts);

        $firstNamePool = $gender === 'M' ? $maleNames : $femaleNames;
        $prenomCount = min($pattern['prenoms'], count($firstNamePool));

        $prenom = '';
        if ($prenomCount > 0) {
            $prenomParts = $this->pickUniqueElements($firstNamePool, $prenomCount);
            $prenom = $this->formatParts($prenomParts);
        } else {
            $prenom = $gender === 'M' ? '—' : '—';
        }

        return [
            'nom' => $nom,
            'prenom' => $prenom,
        ];
    }

    private function pickUniqueElements(array $source, int $count): array
    {
        $count = min($count, count($source));
        if ($count === 1) {
            return [$source[array_rand($source)]];
        }

        $keys = array_rand($source, $count);
        if (!is_array($keys)) {
            $keys = [$keys];
        }

        $parts = [];
        foreach ($keys as $key) {
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
        $personCodes = DB::table('t_declaration_naissance')
            ->select('code_enfant', 'code_pere', 'code_mere', 'code_declarant', 'code_adoptant')
            ->get()
            ->flatMap(function ($row) {
                return collect([
                    $row->code_enfant,
                    $row->code_pere,
                    $row->code_mere,
                    $row->code_declarant,
                    $row->code_adoptant,
                ]);
            })
            ->filter()
            ->unique()
            ->values();

        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        DB::table('t_acte_naissance')->truncate();
        DB::table('t_mouvement_naissance')->truncate();
        DB::table('t_declaration_naissance')->truncate();

        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        if ($personCodes->isNotEmpty()) {
            Personne::whereIn('code_personne', $personCodes)->delete();
        }
    }
}
