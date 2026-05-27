<?php

namespace Modules\Deces\Database\Seeders;

use App\Models\User;
use App\Sifec\Sifec;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Deces\Entities\MouvementDeces;
use Modules\Deces\Services\DeclarationDecesService;
use Modules\Deces\Services\ActeDecesService;
use Modules\Deces\Services\MouvementService;
use Modules\Referentiel\Entities\Personne;
use Modules\Referentiel\Entities\Localite;
use Exception;
use Illuminate\Support\Facades\Log;

class DeclarationDecesSeeder extends Seeder
{
    private const TOTAL_DECLARATIONS = 30;
    private const UNIQUE_RATIO = 0.80; // 80 % de familles uniques

    private int $personCounter = 0;

    public function run(): void
    {
        $this->resetTables();

        $user = User::where('email', 'sandrine@gmail.com')
            ->with(['affectations'=> fn ($q) => $q->where('active', 1)])
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

        $streetNames = array_values(array_unique(array_map(fn ($value) => mb_strtoupper($value, 'UTF-8'), [
            'MASSA', 'NGO', 'DJAMBALA', 'MARIEN NGOUABI', '31 JUILLET',
            'DE LA PAIX', 'MOE POATY', 'LIRANGA', 'BOUNDJI',
            'DE LA LIBERTE', 'MPOUYA', 'DU CONGO', 'MALONGA', 'BAKONGO'
        ])));

        $localiteCodes = ['LOC_0001', 'LOC_0016', 'LOC_0013', 'LOC_0008'];
        $lieuxNaissance = ['BRAZZAVILLE', 'POINTE-NOIRE', 'OUESSO', 'DOLISIE', 'OYO'];

        $totalUnique = (int) round(self::TOTAL_DECLARATIONS * self::UNIQUE_RATIO);

        // Récupérer les codes référentiels disponibles
        $religion = DB::table('tr_religion')->where('supprimer', 0)->first();
        $causesDeces = DB::table('tr_cause_deces')->get(); // Récupérer toutes les causes disponibles
        $filiation = DB::table('tr_filiation')->first();

        if (!$religion) {
            $this->command?->warn("Aucune religion trouvée dans la base de données. Veuillez exécuter ReligionSeeder.");
            return;
        }

        if ($causesDeces->isEmpty()) {
            $this->command?->warn("Aucune cause de décès trouvée dans la base de données. Veuillez exécuter CauseDecesSeeder.");
            return;
        }

        if (!$filiation) {
            $this->command?->warn("Aucune filiation trouvée dans la base de données. Veuillez exécuter FiliationSeeder.");
            return;
        }

        // Convertir en tableau de codes pour faciliter l'utilisation
        $causesDecesCodes = $causesDeces->pluck('code_cause_deces')->toArray();

        $basePayload = [
            'code_situation_matrimoniale_defunt'=> 'SMAT_0003',
            'sexe_defunt'=> 'M',
            'statut_personne_defunt'=> 'DECEDE',
            'type_date_naissance_defunt'=> 'EXACTE',

            'statut_personne_declarant'=> 'VIVANT',
            'type_date_naissance_mere'=> 'EXACTE',
            'statut_personne_mere'=> 'VIVANT',
            'type_date_naissance_pere'=> 'EXACTE',
            'statut_personne_pere'=> 'VIVANT',

            'code_type_document_pere'=> 'TDOC_0018',
            'code_type_document_mere'=> 'TDOC_0018',
            'code_type_document_declarant'=> 'TDOC_0018',
            'code_nationalite_pere'=> 'NAT_0001',
            'code_nationalite_mere'=> 'NAT_0001',
            'code_nationalite_declarant'=> 'NAT_0001',
            'code_nationalite_defunt'=> 'NAT_0001',
            'niveau_instruction_pere'=> 'SECONDAIRE NIVEAU II',
            'niveau_instruction_mere'=> 'SECONDAIRE NIVEAU II',
            'niveau_instruction_declarant'=> 'SECONDAIRE NIVEAU II',
            'profession_pere'=> 'PROF_0010',
            'profession_mere'=> 'PROF_0011',
            'profession_declarant'=> 'PROF_0010',
            'code_pays_pere'=> '+242',
            'code_pays_mere'=> '+242',
            'code_pays_declarant'=> '+242',
            'code_pays_defunt'=> '+242',

            'domicile_pays_pere'=> 'Congo',
            'domicile_pays_mere'=> 'Congo',
            'domicile_pays_declarant'=> 'Congo',
            'domicile_pays_defunt'=> 'Congo',
            'domicile_arrondissement_pere'=> null,
            'domicile_quartier_pere'=> null,
            'domicile_arrondissement_mere'=> null,
            'domicile_quartier_mere'=> null,
            'domicile_arrondissement_declarant'=> null,
            'domicile_quartier_declarant'=> null,
            'domicile_arrondissement_defunt'=> null,
            'domicile_quartier_defunt'=> null,

            'type_declaration'=> 'DECLARATION DE DECES',
            'type_declarant'=> 'Personne physique',

            'lieu_survenance_code'=> 'LSURV_0001',
            'code_religion_defunt'=> $religion->code_religion, // Récupéré dynamiquement
            // code_cause_deces sera défini dans la boucle pour varier les causes
            'filiation'=> $filiation->code_filiation, // Récupéré dynamiquement
            'cec_naissance'=> 'MAIRIE DE L\'ARRONDISSEMENT 1 MAKELEKELE',
        ];

        $sharedFamilies = $this->buildSharedFamilies(
            $surnames,
            $maleNames,
            $femaleNames,
            $streetNames,
            $localiteCodes,
            $lieuxNaissance
        );

        /** @var DeclarationDecesService $service */
        $service = app(DeclarationDecesService::class);
        /** @var MouvementService $mouvementService */
        $mouvementService = app(MouvementService::class);
        /** @var ActeDecesService $acteService */
        $acteService = app(ActeDecesService::class);

        $refMouvement = DB::table('tr_mouvement')->where('code_mouvement', 'MOUV_0032')->first();
        if (!$refMouvement) {
            $this->command?->warn("Référentiel mouvement MOUV_0032 introuvable (mouvement non créé).");
            return;
        }

        // Récupérer l'utilisateur du centre d'état civil pour l'approbation et la génération des actes
        $centreEtatCivilUser = User::where('email', 'agentpfbz@gmail.com')
            ->with(['affectations'=> fn ($q) => $q->where('active', 1)])
            ->first();

        $affectationCentre = null;
        $registre = null;
        if ($centreEtatCivilUser && $centreEtatCivilUser->affectationActive()) {
            $affectationCentre = $centreEtatCivilUser->affectationActive();
            $registre = $affectationCentre->registres()
                ->where('code_type_registre', 'TPRG_0004')
                ->where('statut', 1)
                ->first();
        }

        $created = 0;
        $actesGeneres = 0;

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

            // ÉCRASER la date de naissance du défunt pour garantir l'unicité
            // Même pour les familles partagées, chaque défunt doit avoir une date de naissance unique
            // Cette date sera calculée plus bas en fonction de $i

            // Assigner au moins une cause de décès (parfois plusieurs pour plus de réalisme)
            $nbCauses = rand(1, min(3, count($causesDecesCodes))); // Entre 1 et 3 causes
            $causesSelectionnees = [];
            $causesDisponibles = $causesDecesCodes;
            for ($j = 0; $j < $nbCauses; $j++) {
                $indexAleatoire = array_rand($causesDisponibles);
                $causesSelectionnees[] = $causesDisponibles[$indexAleatoire];
                unset($causesDisponibles[$indexAleatoire]);
                $causesDisponibles = array_values($causesDisponibles); // Réindexer
                if (empty($causesDisponibles)) {
                    break; // Plus de causes disponibles
                }
            }
            $payload['code_cause_deces'] = $causesSelectionnees; // Toujours au moins une cause

            // Générer une date de décès répartie entre 2024 et 2025 (dans le passé)
            $annee = ($i % 2 === 0) ? 2024 : 2025;
            $aujourdhui = Carbon::now();

            if ($annee === 2024) {
                // Pour 2024 : du 1er janvier au 31 décembre 2024
                $debutAnnee = Carbon::create(2024, 1, 1);
                $finAnnee = Carbon::create(2024, 12, 31);
                $dateMax = $finAnnee;
                $joursDisponibles = 365;
            } else {
                // Pour 2025 : du 1er janvier 2025 à aujourd'hui (ou 31 décembre si on est déjà en 2026)
                $debutAnnee = Carbon::create(2025, 1, 1);
                $finAnnee = Carbon::create(2025, 12, 31);
                $dateMax = $finAnnee->isBefore($aujourdhui) ? $finAnnee : $aujourdhui;
                $joursDisponibles = $debutAnnee->diffInDays($dateMax);
            }

            // Générer une date de décès unique pour chaque déclaration (comme dans NaissanceSeeder)
            $joursAleatoires = $i % ($joursDisponibles + 1);
            $dateDeces = $debutAnnee->copy()->addDays((int)$joursAleatoires);

            // Générer une heure de décès unique (comme dans NaissanceSeeder avec addMinutes)
            $heureDeces = Carbon::createFromTime(0, 0)->addMinutes($i % 1440)->format('H:i');

            // Générer une date de naissance pour le défunt (au moins 18 ans avant le décès)
            $ageMin = 18;
            $ageMax = 90;
            $ageDefunt = rand($ageMin, $ageMax);
            $dateNaissanceDefunt = $dateDeces->copy()->subYears($ageDefunt)->subDays(rand(0, 365));

            // Générer une date de déclaration (après le décès, max 30 jours)
            $joursDelai = rand(0, 29);
            $minutesDelai = rand(0, 1439);
            $dateDeclaration = $dateDeces->copy()->addDays($joursDelai)->addMinutes($minutesDelai);

            $payload['date_deces'] = $dateDeces->format('Y-m-d');
            $payload['heure_deces'] = $heureDeces;
            $payload['date_heure_declaration'] = $dateDeclaration->format('Y-m-d H:i');
            $payload['date_naissance_defunt'] = $dateNaissanceDefunt->format('Y-m-d');
            $payload['lieu_deces'] = $institutionLocaliteLib;
            $payload['domicile_defunt'] = $payload['domicile_typevoie_pere'] . ''. $payload['domicile_numero_pere'] . ', '. $payload['domicile_nomvoie_pere'];

            $request = new Request($payload);

            // Vérifier les doublons AVANT de créer la déclaration (comme dans NaissanceSeeder)
            $uniqueDefunt = Sifec::uniqueString($request, '_defunt', $request->sexe_defunt);
            $defunt = Personne::where('personne_string', $uniqueDefunt)->first();
            if ($defunt && $defunt->declarationDeces) {
                continue;
            }

            $declaration = $service->enregistrer($request, $user);
            if ($declaration instanceof JsonResponse || !$declaration) {
                continue;
            }

            // Vérifier que les causes de décès ont bien été insérées
            $declaration->refresh();

            // Vérifier que code_cause_deces est renseigné dans la déclaration principale
            if (empty($declaration->code_cause_deces)) {
                $this->command?->warn("code_cause_deces NULL pour {$declaration->code_declaration_deces}. Mise à jour...");
                if (!empty($causesSelectionnees)) {
                    $declaration->code_cause_deces = $causesSelectionnees[0]; // Première cause comme cause principale
                    $declaration->save();
                }
            }

            // Vérifier que les causes sont dans t_ddecescause
            $causesInserees = DB::table('t_ddecescause')
                ->where('code_declaration_deces', $declaration->code_declaration_deces)
                ->count();

            if ($causesInserees === 0) {
                $this->command?->warn("Aucune cause de décès dans t_ddecescause pour {$declaration->code_declaration_deces}. Insertion manuelle...");
                // Insérer toutes les causes sélectionnées
                if (!empty($causesSelectionnees)) {
                    $causesAInserer = [];
                    foreach ($causesSelectionnees as $cause) {
                        $causesAInserer[] = [
                            'code_declaration_deces'=> $declaration->code_declaration_deces,
                            'code_cause_deces'=> $cause,
                            'created_at'=> now(),
                            'updated_at'=> now(),
                        ];
                    }
                    DB::table('t_ddecescause')->insert($causesAInserer);
                    $this->command?->info("{$declaration->code_declaration_deces} : " . count($causesAInserer) . " cause(s) insérée(s) manuellement dans t_ddecescause.");
                }
            } else {
                $this->command?->info("{$declaration->code_declaration_deces} : {$causesInserees} cause(s) déjà présente(s) dans t_ddecescause.");
            }

            $mouvement = new MouvementDeces();
            $mouvement->code_mouvement_deces = Sifec::genererCodeUniqueReferentiel($mouvement, 'code_mouvement_deces', 4, 'MDC_');
            $mouvement->code_declaration_deces = $declaration->code_declaration_deces;
            $mouvement->code_mouvement = $refMouvement->code_mouvement;
            $mouvement->lib_mouvement = $refMouvement->lib_mouvement;
            $mouvement->statut = 'En cours';
            $mouvement->cui = $affectation->cui;
            $mouvement->save();

            $created++;

            // Envoyer la déclaration au centre d'état civil et générer l'acte
            if ($affectationCentre && $registre) {
                try {
                    // 1. Envoyer la déclaration au centre d'état civil (MOUV_0002)
                    if (!$declaration->mouvements()->where('code_mouvement', 'MOUV_0002')->exists()) {
                        [$ok, $message] = $mouvementService->envoyerDeclaration(
                            $user,
                            $declaration,
                            'declaration_deces',
                            'Envoyée',
                            'Envoi automatique via seeder'
                        );

                        if (!$ok) {
                            $this->command?->warn("Envoi impossible pour {$declaration->code_declaration_deces} : {$message}");
                        } else {
                            $declaration->refresh();
                        }
                    }

                    // 2. Approuver la déclaration
                    $declaration->cec_approuver = 'OUI';
                    $declaration->cec_approuve_par = $affectationCentre->cui ?? 'CUI_00000004';
                    $declaration->cec_approuve_le = now();
                    $declaration->declarant_approuver = 'OUI';
                    $declaration->code_institution_destinataire = $affectationCentre->code_institution ?? 'INS_0047';
                    $declaration->save();

                    // 3. Confirmer la déclaration (MOUV_0019)
                    if (!$declaration->mouvements()->where('code_mouvement', 'MOUV_0019')->exists()) {
                        [$ok, $message] = $mouvementService->confirmerDeclarationDeces(
                            $affectationCentre,
                            $declaration,
                            'Confirmée',
                            null,
                            'Confirmation automatique via seeder'
                        );

                        if (!$ok) {
                            $this->command?->warn("Confirmation impossible pour {$declaration->code_declaration_deces} : {$message}");
                        }
                    }

                    // 4. Vérifier que le registre n'est pas saturé
                    $registre->refresh();
                    if ($registre->statut == 0 || ($registre->nombre_acte_prevu - $registre->nombre_acte_transcrit) <= 0) {
                        $this->command?->warn("Registre saturé, arrêt de la génération des actes.");
                        break;
                    }

                    // 5. Générer l'acte de décès
                    DB::beginTransaction();
                    try {
                        $acteService->genererActe($declaration, $registre, $centreEtatCivilUser);
                        $mouvementService->ajouterEvenementActe(
                            $centreEtatCivilUser,
                            $declaration,
                            'attente_approbation',
                            'Acte généré automatiquement via seeder'
                        );
                        DB::commit();
                        $actesGeneres++;
                    } catch (Exception $e) {
                        DB::rollBack();
                        Log::channel("sifec")->error("Échec génération acte {$declaration->code_declaration_deces} : {$e->getMessage()}");
                        $this->command?->warn("Échec génération acte {$declaration->code_declaration_deces} : {$e->getMessage()}");
                    }

                    $registre->refresh();
                } catch (Exception $e) {
                    Log::channel("sifec")->error("Erreur lors du traitement de la déclaration {$declaration->code_declaration_deces} : {$e->getMessage()}");
                    $this->command?->warn("Erreur lors du traitement de la déclaration {$declaration->code_declaration_deces} : {$e->getMessage()}");
                }
            } else {
                $this->command?->warn("Centre d'état civil ou registre introuvable, les actes ne seront pas générés.");
            }

            if ($created % 100 === 0) {
                $this->command?->info("$created déclarations enregistrées, $actesGeneres actes générés");
            }
        }

        $this->command?->info("Total déclarations créées: $created");
        $this->command?->info("Total actes générés: $actesGeneres");
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
        $typeVoie = str_starts_with($street, '') ? '': 'Rue';
        $numeroVoie = (string) (10 + ($seed % 90));

        // Utiliser des seeds différents pour chaque personne pour éviter les doublons
        $defuntNames = $this->makeNomPrenom(($seed % 2 === 0) ? 'M': 'F', $surnames, $maleNames, $femaleNames, $seed);
        $declarantNames = $this->makeNomPrenom('M', $surnames, $maleNames, $femaleNames, $seed * 3 + 1000);
        $fatherNames = $this->makeNomPrenom('M', $surnames, $maleNames, $femaleNames, $seed * 5 + 2000);
        $motherNames = $this->makeNomPrenom('F', $surnames, $maleNames, $femaleNames, $seed * 7 + 3000);

        // Le défunt doit avoir au moins 18 ans (né au plus tard en 2006 pour décès en 2024)
        // Le défunt doit avoir au maximum 90 ans (né au plus tôt en 1934 pour décès en 2024)
        $maxDaysDefunt = Carbon::create(2006, 12, 31)->diffInDays(Carbon::create(1934, 1, 1));
        mt_srand($seed);
        $defuntBirth = Carbon::create(1934, 1, 1)->addDays($seed % ($maxDaysDefunt + 1));

        // Le déclarant doit avoir au moins 18 ans en 2024
        $maxDaysDeclarant = Carbon::create(2006, 12, 31)->diffInDays(Carbon::create(1975, 1, 1));
        mt_srand($seed * 3 + 5000);
        $declarantBirth = Carbon::create(1975, 1, 1)->addDays(($seed * 3) % ($maxDaysDeclarant + 1));

        // Le père doit avoir au moins 18 ans de plus que le défunt
        mt_srand($seed * 11 + 10000);
        $fatherBirth = $defuntBirth->copy()->subYears(mt_rand(20, 40))->subDays(mt_rand(0, 365));

        // La mère doit avoir au moins 14 ans de plus que le défunt
        mt_srand($seed * 13 + 15000);
        $motherBirth = $defuntBirth->copy()->subYears(mt_rand(16, 35))->subDays(mt_rand(0, 365));

        $lieuNaissanceDefunt = $lieuxNaissance[$seed % count($lieuxNaissance)];
        $lieuNaissanceDeclarant = $lieuxNaissance[($seed + 1) % count($lieuxNaissance)];
        $lieuNaissancePere = $lieuxNaissance[($seed + 2) % count($lieuxNaissance)];
        $lieuNaissanceMere = $lieuxNaissance[($seed + 3) % count($lieuxNaissance)];
        $localiteDefunt = $localiteCodes[$seed % count($localiteCodes)];
        $localiteDeclarant = $localiteCodes[($seed + 1) % count($localiteCodes)];
        $localitePere = $localiteCodes[($seed + 2) % count($localiteCodes)];
        $localiteMere = $localiteCodes[($seed + 3) % count($localiteCodes)];

        $telephoneDeclarant = sprintf('066%06d', ($seed * 17) % 1000000);
        $telephonePere = sprintf('066%06d', ($seed * 19) % 1000000);
        $telephoneMere = sprintf('065%06d', ($seed * 23) % 1000000);

        $documentDeclarant = sprintf('CG-DECL-%06d', $seed + 1);
        $documentPere = sprintf('CG-PERE-%06d', $seed + 1);
        $documentMere = sprintf('CG-MERE-%06d', $seed + 1);

        $emailDeclarant = $this->buildEmail($declarantNames['prenom'], $declarantNames['nom'], $seed);
        $emailPere = $this->buildEmail($fatherNames['prenom'], $fatherNames['nom'], $seed + 1000);
        $emailMere = $this->buildEmail($motherNames['prenom'], $motherNames['nom'], $seed + 2000);

        return [
            'nom_defunt'=> $defuntNames['nom'],
            'prenom_defunt'=> $defuntNames['prenom'],
            'sexe_defunt'=> ($seed % 2 === 0) ? 'M': 'F',
            'date_naissance_defunt'=> $defuntBirth->format('Y-m-d'),
            'lieu_naissance_defunt'=> $lieuNaissanceDefunt,
            'code_localite_defunt'=> $localiteDefunt,
            'code_profession_defunt'=> 'PROF_0010',
            'niveau_instruction_defunt'=> 'SECONDAIRE NIVEAU II',

            'nom_declarant'=> $declarantNames['nom'],
            'prenom_declarant'=> $declarantNames['prenom'],
            'sexe_declarant'=> 'M',
            'date_naissance_declarant'=> $declarantBirth->format('Y-m-d'),
            'lieu_naissance_declarant'=> $lieuNaissanceDeclarant,
            'code_localite_declarant'=> $localiteDeclarant,
            'telephone_declarant'=> $telephoneDeclarant,
            'numero_document_declarant'=> $documentDeclarant,
            'email_declarant'=> $emailDeclarant,

            'nom_pere'=> $fatherNames['nom'],
            'prenom_pere'=> $fatherNames['prenom'],
            'date_naissance_pere'=> $fatherBirth->format('Y-m-d'),
            'lieu_naissance_pere'=> $lieuNaissancePere,
            'code_localite_pere'=> $localitePere,
            'telephone_pere'=> $telephonePere,
            'numero_document_pere'=> $documentPere,
            'email_pere'=> $emailPere,

            'nom_mere'=> $motherNames['nom'],
            'prenom_mere'=> $motherNames['prenom'],
            'date_naissance_mere'=> $motherBirth->format('Y-m-d'),
            'lieu_naissance_mere'=> $lieuNaissanceMere,
            'code_localite_mere'=> $localiteMere,
            'telephone_mere'=> $telephoneMere,
            'numero_document_mere'=> $documentMere,
            'email_mere'=> $emailMere,

            'domicile_typevoie_pere'=> $typeVoie,
            'domicile_numero_pere'=> $numeroVoie,
            'domicile_nomvoie_pere'=> $street,

            'domicile_typevoie_mere'=> $typeVoie,
            'domicile_numero_mere'=> $numeroVoie,
            'domicile_nomvoie_mere'=> $street,

            'domicile_typevoie_declarant'=> $typeVoie,
            'domicile_numero_declarant'=> $numeroVoie,
            'domicile_nomvoie_declarant'=> $street,

            'domicile_typevoie_defunt'=> $typeVoie,
            'domicile_numero_defunt'=> $numeroVoie,
            'domicile_nomvoie_defunt'=> $street,
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

    private function makeNomPrenom(string $gender, array $surnames, array $maleNames, array $femaleNames, int $uniqueSeed = null): array
    {
        // Utiliser un seed unique pour éviter les doublons
        if ($uniqueSeed === null) {
            $this->personCounter++;
            $uniqueSeed = $this->personCounter;
        }

        // Utiliser mt_srand pour avoir une séquence reproductible mais variée
        mt_srand($uniqueSeed * 1000 + ($gender === 'M'? 1 : 2));

        $patterns = [
            ['noms'=> 2, 'prenoms'=> 2],
            ['noms'=> 2, 'prenoms'=> 1],
            ['noms'=> 3, 'prenoms'=> 1],
            ['noms'=> 3, 'prenoms'=> 0],
            ['noms'=> 1, 'prenoms'=> 2],
            ['noms'=> 2, 'prenoms'=> 1],
            ['noms'=> 1, 'prenoms'=> 3],
            ['noms'=> 1, 'prenoms'=> 1],
            ['noms'=> 2, 'prenoms'=> 0],
        ];

        $pattern = $patterns[$uniqueSeed % count($patterns)];

        $surnameCount = max(1, min($pattern['noms'], count($surnames)));
        $surnameParts = $this->pickUniqueElementsSeeded($surnames, $surnameCount, $uniqueSeed * 7);
        $nom = $this->formatParts($surnameParts);

        $firstNamePool = $gender === 'M'? $maleNames : $femaleNames;
        $prenomCount = min($pattern['prenoms'], count($firstNamePool));

        $prenom = '';
        if ($prenomCount > 0) {
            $prenomParts = $this->pickUniqueElementsSeeded($firstNamePool, $prenomCount, $uniqueSeed * 11);
            $prenom = $this->formatParts($prenomParts);
        } else {
            $prenom = ''; // Champ vide au lieu de '—'
        }

        return [
            'nom'=> $nom,
            'prenom'=> $prenom,
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
        return implode('', array_unique($upperParts));
    }

    private function buildEmail(string $firstname, string $lastname, int $seed): string
    {
        $slugLast = $this->slug(str_replace('', '', $lastname));
        $slugFirst = $this->slug(str_replace('', '', $firstname));

        $localPart = $slugFirst !== ''? $slugFirst : $slugLast;
        if ($localPart === '') {
            $localPart = 'civil';
        }

        $domainPart = $slugLast !== ''? $slugLast : 'cg';

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
        $personCodes = DB::table('t_declaration_deces')
            ->select('code_defunt', 'code_pere', 'code_mere', 'code_declarant', 'code_conjoint')
            ->get()
            ->flatMap(function ($row) {
                return collect([
                    $row->code_defunt,
                    $row->code_pere,
                    $row->code_mere,
                    $row->code_declarant,
                    $row->code_conjoint,
                ]);
            })
            ->filter()
            ->unique()
            ->values();

        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        DB::table('t_acte_deces')->truncate();
        DB::table('t_mouvement_deces')->truncate();
        DB::table('t_ddecescause')->truncate();
        DB::table('t_declaration_deces')->truncate();

        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        if ($personCodes->isNotEmpty()) {
            Personne::whereIn('code_personne', $personCodes)->delete();
        }
    }
}

