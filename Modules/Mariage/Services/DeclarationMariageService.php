<?php

namespace Modules\Mariage\Services;

use Exception;
use Carbon\Carbon;
use App\Sifec\Sifec;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Referentiel\Entities\Personne;
use Modules\Referentiel\Entities\Localite;
use Modules\Mariage\Entities\DeclarationMariage;
use Modules\Mariage\Entities\MouvementMariage;
use Modules\Mariage\Services\MouvementMariageService;

class DeclarationMariageService
{
    /**
     * Créer une déclaration de mariage avec validation et transaction.
     *
     * @param $request
     * @param $user
     * @return array [bool, string|DeclarationMariage]|JsonResponse
     */
    public function enregistrer($request, $user)
    {
        // Validation des âges
        $validationAges = $this->validerAges($request);
        if ($validationAges !== true) {
            return $validationAges;
        }

        $maxRetries = 3;
        $retryCount = 0;

        while ($retryCount < $maxRetries) {
            DB::beginTransaction();
            try {
                // Vérification des doublons à l'intérieur de la transaction pour éviter les doublons lors des retries
                $verificationDoublons = $this->verifierDoublons($request);
                if ($verificationDoublons !== true) {
                    DB::rollBack();
                    return $verificationDoublons;
                }

                // Création ou récupération des personnes
                $personnes = $this->gererPersonnes($request);

                // Détermination du type de déclaration
                $typeDeclaration = $this->determinerTypeDeclaration($request);

                // Création de la déclaration
                $declaration = $this->creerDeclaration($request, $personnes, $typeDeclaration, $user);

                // Création du mouvement initial
                $mouvementService = new MouvementMariageService();
                $mouvementService->creerMouvementInitial($declaration, $user);

                // Traitement des enfants si présents
                if ($request->enfants != null && $request->enfants != 0 && count($request->enfants) > 0) {
                    $this->traiterEnfants($request, $declaration);
                }

                DB::commit();

                Log::channel('sifec')->info('Déclaration de mariage enregistrée', [
                    'code_declaration' => $declaration->code_declaration_mariage,
                    'type' => $typeDeclaration,
                    'user' => $user->id
                ]);

                return $declaration;
                break; // Succès, sortir de la boucle

            } catch (Exception $e) {
                DB::rollBack();

                // Vérifier si c'est un deadlock
                if (strpos($e->getMessage(), 'Deadlock found') !== false || strpos($e->getMessage(), '1213') !== false) {
                    $retryCount++;
                    Log::channel('sifec')->warning('Deadlock détecté, tentative de retry', [
                        'retry_count' => $retryCount,
                        'max_retries' => $maxRetries,
                        'error' => $e->getMessage()
                    ]);

                    if ($retryCount < $maxRetries) {
                        // Attendre un peu avant de réessayer (backoff exponentiel)
                        usleep(pow(2, $retryCount) * 100000); // 100ms, 200ms, 400ms
                        continue;
                    }
                }

                Log::channel('sifec')->error('Erreur lors de l\'enregistrement de la déclaration de mariage', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'retry_count' => $retryCount
                ]);
                throw $e;
            }
        }

        // Si on arrive ici, tous les retries ont échoué
        throw new Exception('Impossible d\'enregistrer la déclaration après ' . $maxRetries . ' tentatives');
    }

    /**
     * Valider les âges des futurs époux
     */
    private function validerAges($request)
    {
        $dateNaissanceEpoux = Carbon::create($request->date_naissance_epoux);
        $dateNaissanceEpouse = Carbon::create($request->date_naissance_epouse);
        $dateMariage = Carbon::create($request->date_ceremonie_mariage ?? $request->date_declaration_mariage);

        $ageEpoux = $dateNaissanceEpoux->diffInYears($dateMariage);
        $ageEpouse = $dateNaissanceEpouse->diffInYears($dateMariage);

        if ($ageEpoux < 18) {
            return response()->json([
                "code" => "99",
                "message" => "L'âge du futur marié doit être supérieur ou égal à 18 ans"
            ]);
        }

        if ($ageEpouse < 17) {
            return response()->json([
                "code" => "99",
                "message" => "L'âge de la future mariée doit être supérieur ou égal à 17 ans"
            ]);
        }

        return true;
    }

    /**
     * Vérifie les doublons dans le système
     */
    private function verifierDoublons($request)
    {
        // Génération des chaînes uniques pour les époux
        $epouxUniqueString = Sifec::uniqueString($request, "_epoux", "M");
        $epouseUniqueString = Sifec::uniqueString($request, "_epouse", "F");

        // Utiliser des verrous pour éviter les conditions de course
        $epoux = Personne::where("personne_string", $epouxUniqueString)->lockForUpdate()->first();
        $epouse = Personne::where("personne_string", $epouseUniqueString)->lockForUpdate()->first();

        if ($epoux && $epouse) {
            // Vérifier si une déclaration existe déjà pour ce couple avec un verrou
            $declarationExistante = DeclarationMariage::where("code_epoux", $epoux->code_personne)
                ->where('code_epouse', $epouse->code_personne)
                ->lockForUpdate()
                ->first();

            if ($declarationExistante) {
                return response()->json([
                    "code" => "99",
                    "message" => "Une déclaration de mariage existe déjà pour ce couple dans le système"
                ]);
            }
        }

        // Vérification des doublons de déclaration basée sur les critères métier
        $doublonDeclaration = $this->verifierDoublonDeclaration($request);
        if ($doublonDeclaration !== true) {
            return $doublonDeclaration;
        }

        return true;
    }

    /**
     * Vérifie les doublons de déclaration selon les critères métier
     */
    private function verifierDoublonDeclaration($request)
    {
        // Vérifier s'il existe déjà une déclaration avec les mêmes informations clés
        $critereDoublon = [
            'date_prevue_mariage' => $request->date_ceremonie_mariage,
            'type_declaration' => $this->determinerTypeDeclaration($request)
        ];

        // Ajouter le lieu de cérémonie si spécifié
        if ($request->lieu_ceremonie_mariage) {
            $critereDoublon['lieu_ceremonie_mariage'] = $request->lieu_ceremonie_mariage;
        }

        // Utiliser un verrou pour éviter les conditions de course
        $declarationSimilaire = DeclarationMariage::where($critereDoublon)->lockForUpdate()->first();

        if ($declarationSimilaire) {
            return response()->json([
                "code" => "99",
                "message" => "Une déclaration similaire existe déjà pour cette date et lieu de mariage"
            ]);
        }

        return true;
    }

    /**
     * Gérer la création ou récupération des personnes
     */
    private function gererPersonnes($request)
    {
        // Vérification d'abord si des codes de personnes existants sont fournis
        $epoux = null;
        $epouse = null;
        $tEpoux1 = null;
        $tEpoux2 = null;
        $tEpouse1 = null;
        $tEpouse2 = null;

        // Vérifier les codes de personnes existants pour les témoins
        if ($request->has('code_personne_t_epoux_1') && $request->code_personne_t_epoux_1) {
            $tEpoux1 = Personne::find($request->code_personne_t_epoux_1);
        }
        if ($request->has('code_personne_t_epoux_2') && $request->code_personne_t_epoux_2) {
            $tEpoux2 = Personne::find($request->code_personne_t_epoux_2);
        }
        if ($request->has('code_personne_t_epouse_1') && $request->code_personne_t_epouse_1) {
            $tEpouse1 = Personne::find($request->code_personne_t_epouse_1);
        }
        if ($request->has('code_personne_t_epouse_2') && $request->code_personne_t_epouse_2) {
            $tEpouse2 = Personne::find($request->code_personne_t_epouse_2);
        }

        // Si les témoins existants ne sont pas trouvés, utiliser la logique normale
        if (!$tEpoux1 || !$tEpoux2 || !$tEpouse1 || !$tEpouse2) {
            // Génération des uniqueString
            $epouxUniqueString = Sifec::uniqueString($request, "_epoux", "M");
            $epouseUniqueString = Sifec::uniqueString($request, "_epouse", "F");
            $tEpoux1UniqueString = Sifec::uniqueString($request, "_t_epoux_1", "M");
            $tEpoux2UniqueString = Sifec::uniqueString($request, "_t_epoux_2", "F");
            $tEpouse1UniqueString = Sifec::uniqueString($request, "_t_epouse_1", "M");
            $tEpouse2UniqueString = Sifec::uniqueString($request, "_t_epouse_2", "F");

            // Recherche des personnes existantes par personne_string avec verrous
            if (!$epoux) {
                $epoux = Personne::where("personne_string", $epouxUniqueString)->lockForUpdate()->first();
            }
            if (!$epouse) {
                $epouse = Personne::where("personne_string", $epouseUniqueString)->lockForUpdate()->first();
            }
            if (!$tEpoux1) {
                $tEpoux1 = Personne::where("personne_string", $tEpoux1UniqueString)->lockForUpdate()->first();
            }
            if (!$tEpoux2) {
                $tEpoux2 = Personne::where("personne_string", $tEpoux2UniqueString)->lockForUpdate()->first();
            }
            if (!$tEpouse1) {
                $tEpouse1 = Personne::where("personne_string", $tEpouse1UniqueString)->lockForUpdate()->first();
            }
            if (!$tEpouse2) {
                $tEpouse2 = Personne::where("personne_string", $tEpouse2UniqueString)->lockForUpdate()->first();
            }

            // Création des personnes si elles n'existent pas
            if (!$epoux) {
                $epoux = Sifec::savePersonne($request, "_epoux", "M", $epouxUniqueString);
            }
            if (!$epouse) {
                $epouse = Sifec::savePersonne($request, "_epouse", "F", $epouseUniqueString);
            }
            if (!$tEpoux1) {
                $tEpoux1 = Sifec::savePersonne($request, "_t_epoux_1", "M", $tEpoux1UniqueString);
            }
            if (!$tEpoux2) {
                $tEpoux2 = Sifec::savePersonne($request, "_t_epoux_2", "M", $tEpoux2UniqueString);
            }
            if (!$tEpouse1) {
                $tEpouse1 = Sifec::savePersonne($request, "_t_epouse_1", "M", $tEpouse1UniqueString);
            }
            if (!$tEpouse2) {
                $tEpouse2 = Sifec::savePersonne($request, "_t_epouse_2", "M", $tEpouse2UniqueString);
            }
        }

        return [
            'epoux' => $epoux,
            'epouse' => $epouse,
            'temoins' => [
                'epoux1' => $tEpoux1,
                'epoux2' => $tEpoux2,
                'epouse1' => $tEpouse1,
                'epouse2' => $tEpouse2
            ]
        ];
    }


    /**
     * Déterminer le type de déclaration selon les règles métier
     */
    public function determinerTypeDeclaration($request)
    {
        // Vérifier d'abord le type de mariage sélectionné par l'utilisateur
        if (isset($request->type_mariage)) {
            switch ($request->type_mariage) {
                case 'PROCURATION':
                    return 'PROCURATION';
                case 'posthume':
                    return 'POSTHUME';
                case 'NORMAL':
                default:
                    // Pour les mariages normaux, vérifier les conditions de dispense
                    return $this->determinerTypeDeclarationNormal($request);
            }
        }

        // Fallback : détermination automatique basée sur les dates
        return $this->determinerTypeDeclarationNormal($request);
    }

    /**
     * Déterminer le type de déclaration pour un mariage normal.
     *
     * DECLARATION DE MARIAGE : cérémonie >= 60 jours après la déclaration ET lieu au centre d'état civil.
     * DISPENSE : lieu hors centre d'état civil OU cérémonie < 60 jours après la déclaration.
     */
    private function determinerTypeDeclarationNormal($request)
    {
        if (! isset($request->date_declaration_mariage) || ! isset($request->date_ceremonie_mariage)) {
            return 'DECLARATION DE MARIAGE';
        }

        try {
            $dateDeclaration = Carbon::create($request->date_declaration_mariage);
            $dateMariage = Carbon::create($request->date_ceremonie_mariage);
            $diffJours = $dateDeclaration->diffInDays($dateMariage);

            $lieuCentre = ($request->lieu_ceremonie_mariage ?? '') === "Centre d'état civil";
            $delaiSuffisant = $diffJours >= 60;

            if ($lieuCentre && $delaiSuffisant) {
                return 'DECLARATION DE MARIAGE';
            }

            return 'DISPENSE';

        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de la détermination du type de déclaration', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            // En cas d'erreur, retourner le type par défaut
            return "DECLARATION DE MARIAGE";
        }
    }

    /**
     * Obtenir les détails de la détermination du type de déclaration
     */
    public function obtenirDetailsDeterminationType($request)
    {
        $details = [
            'type_mariage_selectionne' => $request->type_mariage ?? 'Non spécifié',
            'type_declaration_determine' => $this->determinerTypeDeclaration($request),
            'conditions_verifiees' => [],
            'raison_determination' => ''
        ];

        // Si c'est un mariage normal, analyser les conditions
        if (($request->type_mariage ?? 'NORMAL') === 'NORMAL') {
            if (isset($request->date_declaration_mariage) && isset($request->date_ceremonie_mariage)) {
                try {
                    $dateDeclaration = Carbon::create($request->date_declaration_mariage);
                    $dateMariage = Carbon::create($request->date_ceremonie_mariage);
                    $diffJours = $dateDeclaration->diffInDays($dateMariage);

                    $lieuCentre = ($request->lieu_ceremonie_mariage ?? '') === "Centre d'état civil";
                    $delaiSuffisant = $diffJours >= 60;

                    $conditions = [
                        'ecart_jours' => $diffJours,
                        'delai_au_moins_60_jours' => $delaiSuffisant,
                        'lieu_centre_etat_civil' => $lieuCentre,
                        'lieu_hors_centre' => ! $lieuCentre && ($request->lieu_ceremonie_mariage ?? '') === "Hors centre d'état civil",
                    ];

                    $details['conditions_verifiees'] = $conditions;

                    if ($conditions['lieu_hors_centre']) {
                        $details['raison_determination'] = "Cérémonie hors centre d'état civil";
                    } elseif (! $delaiSuffisant) {
                        $details['raison_determination'] = "Date de cérémonie < 60 jours à compter de la déclaration ({$diffJours} jours)";
                    } elseif ($lieuCentre && $delaiSuffisant) {
                        $details['raison_determination'] = "Cérémonie >= 60 jours et lieu au centre d'état civil — déclaration normale";
                    } else {
                        $details['raison_determination'] = 'Conditions de déclaration normale non remplies — dispense';
                    }
                } catch (Exception $e) {
                    $details['raison_determination'] = "Erreur lors de l'analyse des dates: " . $e->getMessage();
                }
            } else {
                $details['raison_determination'] = "Dates manquantes - Déclaration normale par défaut";
            }
        } else {
            $details['raison_determination'] = "Type de mariage spécifique sélectionné: " . $request->type_mariage;
        }

        return $details;
    }

    /**
     * Créer la déclaration de mariage
     */
    private function creerDeclaration($request, $personnes, $typeDeclaration, $user)
    {
        // Construction de l'adresse de cérémonie
        $qv = $request->lib_quartier_ceremonie ?? $request->lib_village_ceremonie ??
              $request->new_quartier_ceremonie ?? $request->new_village_ceremonie;
        $adresse = $request->domicile_numero_ceremonie . ", " . $request->domicile_ceremonie .
                  " " . $request->domicile_nomvoie_ceremonie . " " . $qv;

        $dm = new DeclarationMariage;
        $dm->code_declaration_mariage = Sifec::genererCodeUniqueReferentiel($dm, "code_declaration_mariage", 8, "CDM_");

        // Données principales
        $dm->date_declaration_mariage = $request->date_declaration_mariage ?? date("Y-m-d");
        $dm->date_prevue_mariage = $request->date_ceremonie_mariage;
        $dm->lieu_ceremonie_mariage = $request->lieu_ceremonie_mariage;
        $dm->type_declaration = $typeDeclaration;
        $dm->numero_dispense = $typeDeclaration == "DISPENSE" ? Sifec::genererCodeUniqueReferentiel($dm, "numero_dispense", 4, "") : "";
        $dm->adresse_celebration_mariage = $adresse;

        // Personnes
        $dm->code_epoux = $personnes['epoux']->code_personne;
        $dm->code_epouse = $personnes['epouse']->code_personne;
        $dm->code_temoin_homme_epoux = $personnes['temoins']['epoux1']->code_personne;
        $dm->code_temoin_femme_epoux = $personnes['temoins']['epoux2']->code_personne;
        $dm->code_temoin_homme_epouse = $personnes['temoins']['epouse1']->code_personne;
        $dm->code_temoin_femme_epouse = $personnes['temoins']['epouse2']->code_personne;

        // Autorisations ambassade
        $dm->autorisation_ambassade_epoux = $request->autorisation_ambassade_epoux;
        $dm->autorisation_ambassade_epouse = $request->autorisation_ambassade_epouse;
        $dm->date_autorisation_ambassade_epoux = $request->date_autorisation_ambassade_epoux;
        $dm->date_autorisation_ambassade_epouse = $request->date_autorisation_ambassade_epouse;

        // Certificats et documents
        $dm->cec_naissance_epoux = $request->cec_naissance_epoux ?? $request->new_cec_naissance_epoux;
        $dm->cec_naissance_epouse = $request->cec_naissance_epouse ?? $request->new_cec_naissance_epouse;
        $dm->certificat_residence_epoux = $request->certificat_residence_epoux;
        $dm->certificat_residence_epouse = $request->certificat_residence_epouse;
        $dm->date_emission_certificat_residence_epoux = $request->date_emission_certificat_residence_epoux;
        $dm->date_emission_certificat_residence_epouse = $request->date_emission_certificat_residence_epouse;

        // Informations familiales
        $dm->pere_epoux = $request->nom_pere_epoux;
        $dm->mere_epoux = $request->nom_mere_epoux;
        $dm->pere_epouse = $request->nom_pere_epouse;
        $dm->mere_epouse = $request->nom_mere_epouse;

        // Professions
        $dm->code_profession_epoux = $request->code_profession_epoux;
        $dm->code_profession_epouse = $request->code_profession_epouse;
        $dm->code_profession_temoin_h_epoux = $request->code_profession_t_epoux_1;
        $dm->code_profession_temoin_f_epoux = $request->code_profession_t_epoux_2;
        $dm->code_profession_temoin_h_epouse = $request->code_profession_t_epouse_1;
        $dm->code_profession_temoin_f_epouse = $request->code_profession_t_epouse_2;

        // Filiation
        $dm->code_filiation_chef_famille = $request->filiation;
        $dm->chef_famille = $request->chef_famille;

        // Actes de naissance
        $dm->date_emission_acte_naissance_epoux = $request->date_emission_acte_naissance_epoux;
        $dm->date_emission_acte_naissance_epouse = $request->date_emission_acte_naissance_epouse;
        $dm->numero_acte_naissance_epoux = $request->num_acte_naissance_epoux;
        $dm->numero_acte_naissance_epouse = $request->num_acte_naissance_epouse;

        // Jugements et actes précédents
        $dm->numero_jugement_divorce_epoux = $request->numero_jugement_divorce_epoux;
        $dm->numero_jugement_divorce_epouse = $request->numero_jugement_divorce_epouse;
        $dm->numero_acte_mariage_epoux = $request->numero_acte_mariage_epoux;
        $dm->numero_acte_mariage_epouse = $request->numero_acte_mariage_epouse;
        $dm->numero_acte_deces_epoux = $request->numero_acte_deces_epoux;
        $dm->numero_acte_deces_epouse = $request->numero_acte_deces_epouse;

        // Examens prénuptiaux et informations complémentaires
        $dm->examens_prenuptiaux = $request->examens_prenuptiaux;
        $dm->date_pre_mariage_epoux = $request->date_pre_mariage_epoux;
        $dm->date_pre_mariage_epouse = $request->date_pre_mariage_epouse;
        $dm->parent_paternel_epoux = $request->parent_paternel_epoux;
        $dm->parent_maternel_epoux = $request->parent_maternel_epoux;
        $dm->parent_paternel_epouse = $request->parent_paternel_epouse;
        $dm->parent_maternel_epouse = $request->parent_maternel_epouse;
        $dm->montant_dot = 50000;
        $dm->type_mariage = $request->type_mariage;

        // Mandants
        $dm->nom_prenom_mandant_epoux = $request->nom_prenom_mandant_epoux;
        $dm->nom_prenom_mandant_epouse = $request->nom_prenom_mandant_epouse;

        // Options de mariage
        $dm->code_option_mariage = $request->option_mariage;
        $dm->code_regime = $request->regime_mariage;
        $dm->code_situation_mat_epoux = $request->sit_matrimoniale_epoux;
        $dm->code_situation_mat_epouse = $request->sit_matrimoniale_epouse;

        // Nombre d'enfants
        if ($request->enfants != null && $request->enfants != 0) {
            $dm->nbre_enfant = count($request->enfants);
        }

        $dm->cui = $user->affectationActive()->cui;
        $dm->code_institution = $user->affectationActive()->code_institution;
        $dm->save();

        return $dm;
    }


    /**
     * Traiter les enfants de la déclaration
     */
    private function traiterEnfants($request, $declaration)
    {
        foreach (collect($request->enfants) as $enfantData) {
            $enfantString = Sifec::uniqueStringEnfant($enfantData, $enfantData['sexe']);

            // Création de l'enfant
            $enfant = new Personne;
            $enfant->code_personne = Sifec::genererCodeUniqueReferentiel($enfant, "code_personne", 8, "PRS_");
            $enfant->nom = $enfantData["nom"];
            $enfant->prenom = $enfantData["prenom"];
            $enfant->date_naissance = $enfantData["date_naissance"];
            $enfant->lieu_naissance = Localite::find($enfantData["lieu_naissance"])->lib_localite;
            $enfant->code_localite = $enfantData["lieu_naissance"];
            $enfant->code_profession = "PROF_0010";
            $enfant->code_nationalite = "NAT_0001";
            $enfant->niveau_instruction = "NON DECLARE";
            $enfant->sexe = $enfantData['sexe'];
            $enfant->statut_personne = "VIVANT";
            $enfant->type_date_naissance = "EXACTE";
            $enfant->save();

            // Création du livret famille (logique à compléter selon besoins)
            // Cette partie pourrait être déplacée dans un service dédié aux livrets
        }
    }
}
