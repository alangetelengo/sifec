<?php

namespace Modules\Mariage\Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Mariage\Services\DeclarationMariageService;
use Modules\Referentiel\Entities\Personne;

/**
 * Formulaires type mariage enregistrés au CEC (MOUV_2007), sans confirmation ni acte.
 *
 * DECLARATION DE MARIAGE : cérémonie >= 60 j après déclaration + centre d'état civil.
 * DISPENSE : hors centre OU cérémonie < 60 j après déclaration.
 *
 * @see agentcec@sifec.cg (INS_0047)
 */
class FormulaireTypeMariageSeeder extends Seeder
{
    private const TOTAL_DECLARATION = 20;

    private const TOTAL_DISPENSE = 5;

    private const CODE_LOCALITE = 'LOC_0026';

    private const LIEU_CEREMONIE_NORMAL = "Centre d'état civil";

    private const LIEU_CEREMONIE_DISPENSE = "Hors centre d'état civil";

    public function run(): void
    {
        $this->resetTables();

        $user = User::where('email', 'agentcec@sifec.cg')
            ->with(['affectations' => fn ($q) => $q->where('active', 1)])
            ->first();

        if ($user === null || $user->affectationActive() === null) {
            $this->command?->warn('Utilisateur agentcec@sifec.cg ou affectation active introuvable.');

            return;
        }

        $optionMariage = DB::table('tr_option_mariage')->value('code_option_mariage');
        $regime = DB::table('tr_regime')->value('code_regime');
        $situationMat = DB::table('tr_situation_matrimoniale')
            ->where('lib_situation_matrimoniale', 'Célibataire')
            ->value('code_situation_matrimoniale')
            ?? DB::table('tr_situation_matrimoniale')->value('code_situation_matrimoniale');
        $filiation = DB::table('tr_filiation')->value('code_filiation');
        $typeDocument = DB::table('tr_type_document')->value('code_type_document') ?? 'TDOC_0018';

        if (! $optionMariage || ! $regime || ! $situationMat || ! $filiation) {
            $this->command?->warn('Référentiels mariage manquants (option, régime, situation, filiation).');

            return;
        }

        /** @var DeclarationMariageService $service */
        $service = app(DeclarationMariageService::class);
        $namesData = $this->buildNamesData();
        $rangesMensuels = $this->buildMonthlyRanges();

        if (empty($rangesMensuels)) {
            $this->command?->warn('Impossible de construire la période mensuelle de génération.');

            return;
        }

        $createdDeclaration = $this->seedBatch(
            $service,
            $user,
            self::TOTAL_DECLARATION,
            false,
            0,
            $namesData,
            $rangesMensuels,
            $optionMariage,
            $regime,
            $situationMat,
            $filiation,
            $typeDocument
        );

        $createdDispense = $this->seedBatch(
            $service,
            $user,
            self::TOTAL_DISPENSE,
            true,
            10000,
            $namesData,
            $rangesMensuels,
            $optionMariage,
            $regime,
            $situationMat,
            $filiation,
            $typeDocument
        );

        $this->command?->info("Formulaires DECLARATION DE MARIAGE (MOUV_2007) : {$createdDeclaration}");
        $this->command?->info("Formulaires DISPENSE (MOUV_2007) : {$createdDispense}");
        $this->command?->info('Institution émettrice : '.$user->affectationActive()->code_institution.' (agentcec@sifec.cg)');
    }

    private function seedBatch(
        DeclarationMariageService $service,
        User $user,
        int $total,
        bool $dispense,
        int $seedOffset,
        array $namesData,
        array $rangesMensuels,
        string $optionMariage,
        string $regime,
        string $situationMat,
        string $filiation,
        string $typeDocument
    ): int {
        $created = 0;
        $now = Carbon::now()->startOfDay();

        for ($i = 0; $i < $total; $i++) {
            $seed = $seedOffset + $i + 1;
            $indexMois = $i % count($rangesMensuels);
            $debutMois = $rangesMensuels[$indexMois]['debut'];
            $finMois = $rangesMensuels[$indexMois]['fin'];
            $joursDansMois = $debutMois->diffInDays($finMois) + 1;
            $offsetJour = ($rangesMensuels[$indexMois]['cursor'] + $seed) % $joursDansMois;
            $rangesMensuels[$indexMois]['cursor']++;

            $dateDeclaration = $debutMois->copy()->addDays($offsetJour);
            if ($dateDeclaration->gt($now)) {
                $dateDeclaration = $now->copy()->subDays($total - $i);
            }

            if ($dispense) {
                // Alterner : hors centre (même avec délai long) / centre avec délai < 60 j
                if ($i % 2 === 0) {
                    $lieuCeremonie = self::LIEU_CEREMONIE_DISPENSE;
                    $joursCeremonie = 75 + ($i % 30);
                } else {
                    $lieuCeremonie = self::LIEU_CEREMONIE_NORMAL;
                    $joursCeremonie = 15 + ($i % 44);
                }
            } else {
                $lieuCeremonie = self::LIEU_CEREMONIE_NORMAL;
                $joursCeremonie = 60 + ($i % 45);
            }

            $dateCeremonie = $dateDeclaration->copy()->addDays($joursCeremonie);

            $payload = $this->buildPayload(
                $seed,
                $namesData,
                $dateDeclaration,
                $dateCeremonie,
                $lieuCeremonie,
                $optionMariage,
                $regime,
                $situationMat,
                $filiation,
                $typeDocument
            );

            $request = new Request($payload);
            $result = $service->enregistrer($request, $user);

            if ($result instanceof JsonResponse) {
                $this->command?->warn("Échec seed #{$seed} : ".json_encode($result->getData(true)));

                continue;
            }

            if (! $result) {
                continue;
            }

            $result->refresh();
            $typeAttendu = $dispense ? 'DISPENSE' : 'DECLARATION DE MARIAGE';
            if ($result->type_declaration !== $typeAttendu) {
                $this->command?->warn(
                    "{$result->code_declaration_mariage} : type {$result->type_declaration} (attendu {$typeAttendu})"
                );
            }

            if (! $result->mouvements()->where('code_mouvement', 'MOUV_2007')->exists()) {
                $this->command?->warn("{$result->code_declaration_mariage} : MOUV_2007 absent");
            }

            $created++;
        }

        return $created;
    }

    private function buildPayload(
        int $seed,
        array $namesData,
        Carbon $dateDeclaration,
        Carbon $dateCeremonie,
        string $lieuCeremonie,
        string $optionMariage,
        string $regime,
        string $situationMat,
        string $filiation,
        string $typeDocument
    ): array {
        $epoux = $this->makePersonNames('M', $seed, $namesData);
        $epouse = $this->makePersonNames('F', $seed + 500, $namesData);
        $tEpoux1 = $this->makePersonNames('M', $seed + 1000, $namesData);
        $tEpoux2 = $this->makePersonNames('F', $seed + 1100, $namesData);
        $tEpouse1 = $this->makePersonNames('M', $seed + 1200, $namesData);
        $tEpouse2 = $this->makePersonNames('F', $seed + 1300, $namesData);

        $birthEpoux = $dateCeremonie->copy()->subYears(28)->subDays($seed % 200)->format('Y-m-d');
        $birthEpouse = $dateCeremonie->copy()->subYears(24)->subDays($seed % 180)->format('Y-m-d');
        $birthWitness = $dateCeremonie->copy()->subYears(35)->subDays($seed % 150)->format('Y-m-d');

        $street = $namesData['streetNames'][$seed % count($namesData['streetNames'])];
        $lieuNaissance = $namesData['lieuxNaissance'][$seed % count($namesData['lieuxNaissance'])];

        $basePerson = [
            'code_localite' => self::CODE_LOCALITE,
            'lieu_naissance' => $lieuNaissance,
            'code_nationalite' => 'NAT_0001',
            'code_profession' => 'PROF_0010',
            'code_type_document' => $typeDocument,
            'numero_document' => sprintf('SEED%08d', $seed),
            'type_date_naissance' => 'EXACTE',
            'statut_personne' => 'VIVANT',
        ];

        return array_merge($basePerson, [
            'type_declaration' => 'DECLARATION DE MARIAGE',
            'type_mariage' => 'NORMAL',
            'examens_prenuptiaux' => 1,
            'option_mariage' => $optionMariage,
            'regime_mariage' => $regime,
            'sit_matrimoniale_epoux' => $situationMat,
            'sit_matrimoniale_epouse' => $situationMat,
            'filiation' => $filiation,
            'chef_famille' => $epoux['nom'].' '.$epoux['prenom'],
            'date_declaration_mariage' => $dateDeclaration->format('Y-m-d'),
            'date_ceremonie_mariage' => $dateCeremonie->format('Y-m-d'),
            'lieu_ceremonie_mariage' => $lieuCeremonie,
            'domicile_numero_ceremonie' => (string) (10 + ($seed % 90)),
            'domicile_ceremonie' => 'Rue',
            'domicile_nomvoie_ceremonie' => $street,
            'lib_quartier_ceremonie' => 'QUARTIER SEED',
            'cec_naissance_epoux' => 'MAIRIE DE L\'ARRONDISSEMENT 1 MAKELEKELE',
            'cec_naissance_epouse' => 'MAIRIE DE L\'ARRONDISSEMENT 1 MAKELEKELE',
            'num_acte_naissance_epoux' => 'AN-SEED-E-'.$seed,
            'num_acte_naissance_epouse' => 'AN-SEED-F-'.$seed,
            'date_emission_acte_naissance_epoux' => $dateDeclaration->copy()->subYears(5)->format('Y-m-d'),
            'date_emission_acte_naissance_epouse' => $dateDeclaration->copy()->subYears(5)->format('Y-m-d'),
            'nom_pere_epoux' => 'PERE '.$epoux['nom'],
            'nom_mere_epoux' => 'MERE '.$epoux['nom'],
            'nom_pere_epouse' => 'PERE '.$epouse['nom'],
            'nom_mere_epouse' => 'MERE '.$epouse['nom'],
            'certificat_residence_epoux' => 'CR-E-'.$seed,
            'certificat_residence_epouse' => 'CR-F-'.$seed,
            'date_emission_certificat_residence_epoux' => $dateDeclaration->format('Y-m-d'),
            'date_emission_certificat_residence_epouse' => $dateDeclaration->format('Y-m-d'),
            'nom_epoux' => $epoux['nom'],
            'prenom_epoux' => $epoux['prenom'],
            'date_naissance_epoux' => $birthEpoux,
            'code_localite_epoux' => self::CODE_LOCALITE,
            'lieu_naissance_epoux' => $lieuNaissance,
            'code_nationalite_epoux' => 'NAT_0001',
            'code_profession_epoux' => 'PROF_0010',
            'code_type_document_epoux' => $typeDocument,
            'numero_document_epoux' => sprintf('SEED-E-%08d', $seed),
            'nom_epouse' => $epouse['nom'],
            'prenom_epouse' => $epouse['prenom'],
            'date_naissance_epouse' => $birthEpouse,
            'code_localite_epouse' => self::CODE_LOCALITE,
            'lieu_naissance_epouse' => $lieuNaissance,
            'code_nationalite_epouse' => 'NAT_0001',
            'code_profession_epouse' => 'PROF_0010',
            'code_type_document_epouse' => $typeDocument,
            'numero_document_epouse' => sprintf('SEED-F-%08d', $seed),
            'nom_t_epoux_1' => $tEpoux1['nom'],
            'prenom_t_epoux_1' => $tEpoux1['prenom'],
            'date_naissance_t_epoux_1' => $birthWitness,
            'code_localite_t_epoux_1' => self::CODE_LOCALITE,
            'lieu_naissance_t_epoux_1' => $lieuNaissance,
            'code_nationalite_t_epoux_1' => 'NAT_0001',
            'code_profession_t_epoux_1' => 'PROF_0010',
            'code_type_document_t_epoux_1' => $typeDocument,
            'numero_document_t_epoux_1' => sprintf('SEED-TE1-%08d', $seed),
            'nom_t_epoux_2' => $tEpoux2['nom'],
            'prenom_t_epoux_2' => $tEpoux2['prenom'],
            'date_naissance_t_epoux_2' => $birthWitness,
            'code_localite_t_epoux_2' => self::CODE_LOCALITE,
            'lieu_naissance_t_epoux_2' => $lieuNaissance,
            'code_nationalite_t_epoux_2' => 'NAT_0001',
            'code_profession_t_epoux_2' => 'PROF_0010',
            'code_type_document_t_epoux_2' => $typeDocument,
            'numero_document_t_epoux_2' => sprintf('SEED-TE2-%08d', $seed),
            'nom_t_epouse_1' => $tEpouse1['nom'],
            'prenom_t_epouse_1' => $tEpouse1['prenom'],
            'date_naissance_t_epouse_1' => $birthWitness,
            'code_localite_t_epouse_1' => self::CODE_LOCALITE,
            'lieu_naissance_t_epouse_1' => $lieuNaissance,
            'code_nationalite_t_epouse_1' => 'NAT_0001',
            'code_profession_t_epouse_1' => 'PROF_0010',
            'code_type_document_t_epouse_1' => $typeDocument,
            'numero_document_t_epouse_1' => sprintf('SEED-TF1-%08d', $seed),
            'nom_t_epouse_2' => $tEpouse2['nom'],
            'prenom_t_epouse_2' => $tEpouse2['prenom'],
            'date_naissance_t_epouse_2' => $birthWitness,
            'code_localite_t_epouse_2' => self::CODE_LOCALITE,
            'lieu_naissance_t_epouse_2' => $lieuNaissance,
            'code_nationalite_t_epouse_2' => 'NAT_0001',
            'code_profession_t_epouse_2' => 'PROF_0010',
            'code_type_document_t_epouse_2' => $typeDocument,
            'numero_document_t_epouse_2' => sprintf('SEED-TF2-%08d', $seed),
            'enfants' => 0,
        ]);
    }

    /**
     * @return array{surnames: array, maleNames: array, femaleNames: array, streetNames: array, lieuxNaissance: array}
     */
    private function buildNamesData(): array
    {
        return [
            'surnames' => ['ELENGA', 'MBONGO', 'MASSALA', 'NGOMA', 'LOUDIMA', 'TCHETEMBO', 'BISSIKA', 'OPALA'],
            'maleNames' => ['PRINCE', 'ARNAUD', 'MICHEL', 'PATRICK', 'EMILE', 'CHRISTIAN', 'MARCEL'],
            'femaleNames' => ['STALLA', 'PAULE', 'MARIE', 'SYLVIE', 'PRISCA', 'JOSIANE', 'MIREILLE'],
            'streetNames' => ['MASSA', 'NGO', 'MARIEN NGOUABI', 'DE LA PAIX', 'MALONGA'],
            'lieuxNaissance' => ['BRAZZAVILLE', 'POINTE-NOIRE', 'DOLISIE'],
        ];
    }

    /**
     * @return array<int, array{debut: Carbon, fin: Carbon, cursor: int}>
     */
    private function buildMonthlyRanges(): array
    {
        $ranges = [];
        $cursor = Carbon::now()->startOfMonth()->subMonths(11);

        for ($m = 0; $m < 12; $m++) {
            $debut = $cursor->copy()->addMonths($m)->startOfMonth();
            $fin = $debut->copy()->endOfMonth();
            $ranges[] = ['debut' => $debut, 'fin' => $fin, 'cursor' => 0];
        }

        return $ranges;
    }

    /**
     * @return array{nom: string, prenom: string}
     */
    private function makePersonNames(string $gender, int $seed, array $namesData): array
    {
        $surnames = $namesData['surnames'];
        $firstNames = $gender === 'M' ? $namesData['maleNames'] : $namesData['femaleNames'];

        return [
            'nom' => $surnames[$seed % count($surnames)],
            'prenom' => $firstNames[$seed % count($firstNames)],
        ];
    }

    private function resetTables(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        $personCodes = DB::table('t_declaration_mariage')
            ->select(
                'code_epoux',
                'code_epouse',
                'code_temoin_homme_epoux',
                'code_temoin_femme_epoux',
                'code_temoin_homme_epouse',
                'code_temoin_femme_epouse'
            )
            ->get()
            ->flatMap(function ($row) {
                return [
                    $row->code_epoux,
                    $row->code_epouse,
                    $row->code_temoin_homme_epoux,
                    $row->code_temoin_femme_epoux,
                    $row->code_temoin_homme_epouse,
                    $row->code_temoin_femme_epouse,
                ];
            })
            ->filter()
            ->unique()
            ->values();

        DB::table('t_mouvement_mariage')->delete();
        DB::table('t_acte_mariage')->delete();
        DB::table('t_declaration_mariage')->delete();

        if ($personCodes->isNotEmpty()) {
            DB::table('t_document')->whereIn('code_personne', $personCodes)->delete();
            Personne::whereIn('code_personne', $personCodes)->delete();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
