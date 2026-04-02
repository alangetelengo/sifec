<?php

namespace Modules\Naissance\Services;

use Exception;
use Carbon\Carbon;
use App\Sifec\Sifec;
use Illuminate\Support\FacadesDB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Referentiel\Entities\Personne;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Naissance\Entities\MouvementNaissance;
use Modules\Naissance\Entities\Declarationnaissance;

class DeclarationNaissanceService
{
    public function enregistrer($request, $user)
    {
        // Validation des âges parents/enfant
        $validationAges = $this->validerAgesParents($request);
        if ($validationAges !== true) {
            return $validationAges;
        }

        // Vérification des doublons
        $verificationDoublons = $this->verifierDoublons($request);
        if ($verificationDoublons !== true) {
            return $verificationDoublons;
        }

        DB::beginTransaction();
        try {
            // Génération des chaînes uniques pour chaque personne
            $uniqueStrings = $this->genererUniqueStrings($request);

            // Gestion des personnes (père, mère, enfant, déclarant)
            $personnes = $this->gererPersonnes($request, $uniqueStrings);

            // Création de la déclaration
            $declaration = $this->creerDeclaration($request, $user, $personnes);

            DB::commit();
            return $declaration;

        } catch (Exception $e) {
            DB::rollBack();
            Log::channel("sifec")->error("Erreur lors de l'enregistrement de la déclaration: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Valide les âges des parents par rapport à l'enfant.
     * Ne s'applique pas au cas "Enfant trouvé" (père et mère inconnus, déclarant = juge).
     */
    private function validerAgesParents($request)
    {
        $personneDeclaree = $request->personne_declaree ?? '';
        if ($personneDeclaree === 'Enfant trouvé' || $personneDeclaree === 'Enfant abandonné') {
            return true;
        }

        try {
            $dateNaissancePere = Carbon::parse($request->date_naissance_pere);
            $dateNaissanceEnfant = Carbon::parse($request->date_naissance_enfant);
            $dateNaissanceMere = Carbon::parse($request->date_naissance_mere);
        } catch (\Exception $e) {
            Log::channel('sifec')->error('Erreur validation âges (dates invalides)', [
                'message' => $e->getMessage(),
                'request' => $request->only(['date_naissance_pere', 'date_naissance_enfant', 'date_naissance_mere', 'personne_declaree']),
            ]);
            return response()->json([
                "code" => "99",
                "message" => "Date(s) de naissance invalide(s)."
            ]);
        }

        $differenceAgeEnfantPere = $dateNaissancePere->diffInYears($dateNaissanceEnfant);
        $differenceAgeEnfantMere = $dateNaissanceMere->diffInYears($dateNaissanceEnfant);

        if ($differenceAgeEnfantPere < 15) {
            Log::channel('sifec')->warning('Validation déclaration naissance : différence d\'âge père/enfant < 15 ans', [
                'difference' => $differenceAgeEnfantPere,
                'personne_declaree' => $personneDeclaree,
            ]);
            return response()->json([
                "code" => "99",
                "message" => "La différence d'âge entre le père et l'enfant doit être supérieure ou égale à 15 ans"
            ]);
        }

        if ($differenceAgeEnfantMere < 12) {
            Log::channel('sifec')->warning('Validation déclaration naissance : différence d\'âge mère/enfant < 12 ans', [
                'difference' => $differenceAgeEnfantMere,
                'personne_declaree' => $personneDeclaree,
            ]);
            return response()->json([
                "code" => "99",
                "message" => "La différence d'âge entre la mère et l'enfant doit être supérieure ou égale à 12 ans"
            ]);
        }

        return true;
    }

    /**
     * Vérifie les doublons dans le système
     */
    private function verifierDoublons($request)
    {
        // Vérification de l'enfant
        $enfantUniqueString = Sifec::uniqueString($request, "_enfant", $request->sexe_enfant);
        $enfantExistant = Personne::where("personne_string", $enfantUniqueString)->first();

        if ($enfantExistant) {
            // Vérifier si une déclaration existe déjà pour cet enfant
            $declarationExistante = Declarationnaissance::where('code_enfant', $enfantExistant->code_personne)->first();

            if ($declarationExistante) {
                return response()->json([
                    "code" => "99",
                    "message" => "Une déclaration existe déjà pour cet enfant dans le système"
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
            'date_heure_naissance' => $request->date_naissance_enfant . " " . $request->heure_naissance_enfant . ":00",
            'type_declaration' => $request->type_declaration ?? 'DECLARATION DE NAISSANCE'
        ];

        // Ajouter le lieu de survenance si spécifié
        if ($request->lieu_survenance) {
            $critereDoublon['code_lieu_survenance'] = $request->lieu_survenance ?? "LSURV_0001";
        }

        $declarationSimilaire = Declarationnaissance::where($critereDoublon)->first();

        if ($declarationSimilaire) {
            return response()->json([
                "code" => "99",
                "message" => "Une déclaration similaire existe déjà pour cette date et heure de naissance"
            ]);
        }

        return true;
    }

    /**
     * Génère les chaînes uniques pour toutes les personnes
     */
    private function genererUniqueStrings($request)
    {
        return [
            'pere' => Sifec::uniqueString($request, "_pere", "M"),
            'mere' => Sifec::uniqueString($request, "_mere", "F"),
            'enfant' => Sifec::uniqueString($request, "_enfant", $request->sexe_enfant),
            'declarant' => Sifec::uniqueString($request, "_declarant", $request->sexe_declarant)
        ];
    }

    /**
     * Gère la création/récupération des personnes
     */
    private function gererPersonnes($request, $uniqueStrings)
    {
        $personnes = [];

        // Gestion du père
        $personnes['pere'] = Personne::where("personne_string", $uniqueStrings['pere'])->first();
        if (!$personnes['pere']) {
            $personnes['pere'] = Sifec::savePersonne($request, "_pere", "M", $uniqueStrings['pere']);
        }

        // Gestion de la mère
        $personnes['mere'] = Personne::where("personne_string", $uniqueStrings['mere'])->first();
        if (!$personnes['mere']) {
            $personnes['mere'] = Sifec::savePersonne($request, "_mere", "F", $uniqueStrings['mere']);
        }

        // Gestion du déclarant : priorité au code_declarant envoyé par le formulaire (choix explicite)
        $personnes['declarant'] = null;
        if ($request->filled('code_declarant')) {
            $personnes['declarant'] = Personne::find($request->input('code_declarant'));
        }
        if (!$personnes['declarant']) {
            $personnes['declarant'] = Personne::where("personne_string", $uniqueStrings['declarant'])->first();
        }
        if (!$personnes['declarant'] && $request->filiation != "FIL_0001" && $request->filiation != "FIL_0002") {
            $personnes['declarant'] = Sifec::savePersonne($request, "_declarant", $request->sexe_declarant, $uniqueStrings['declarant']);
        }

        // Gestion de l'enfant
        if ($uniqueStrings['declarant'] != $uniqueStrings['enfant']) {
            $personnes['enfant'] = Sifec::savePersonne($request, "_enfant", $request->sexe_enfant, $uniqueStrings['enfant']);
        } else {
            $personnes['enfant'] = $personnes['declarant'] ?? Sifec::savePersonne($request, "_enfant", $request->sexe_enfant, $uniqueStrings['enfant']);
        }

        return $personnes;
    }

    /**
     * Crée la déclaration de naissance
     */
    private function creerDeclaration($request, $user, $personnes)
    {
        $dn = new Declarationnaissance;
        $codedn = Sifec::genererCodeUniqueReferentiel($dn, "code_declaration_naissance", 8, "CDN_");

        // Configuration de base
        $dn->code_declaration_naissance = $codedn;
        $dn->nombre_enfant = $request->input('nombre_enfant');
        $dn->date_heure_declaration = $this->formaterDateDeclaration($request);
        $dn->date_heure_naissance = $request->input('date_naissance_enfant') . " " . $request->input('heure_naissance_enfant') . ":00";

        // Attribution du déclarant selon la filiation
        $dn->code_declarant = $this->determinerDeclarant($request, $personnes);

        // Attribution des parents et enfant
        $dn->code_enfant = $personnes['enfant']->code_personne;
        $dn->code_pere = $personnes['pere']->code_personne;
        $dn->code_mere = $personnes['mere']->code_personne;

        // Configuration des autres champs
        $this->configurerChampsDeclaration($dn, $request, $user);

        $dn->save();

        return $dn;
    }

    /**
     * Formate la date de déclaration
     */
    private function formaterDateDeclaration($request)
    {
        if ($request->input('date_heure_declaration')) {
            try {
                return Carbon::parse($request->input('date_heure_declaration'))->format('Y-m-d H:i');
            } catch (\Exception $e) {
                return date('Y-m-d H:i');
            }
        }
        return date('Y-m-d H:i');
    }

    /**
     * Détermine le déclarant : priorité au code_declarant envoyé par le formulaire (choix explicite),
     * sinon selon la filiation (père, mère ou autre personne).
     */
    private function determinerDeclarant($request, $personnes)
    {
        // Priorité au choix explicite du formulaire (déclarant peut être le père, la mère ou toute autre personne)
        if ($request->filled('code_declarant')) {
            $personneDeclarant = Personne::find($request->input('code_declarant'));
            if ($personneDeclarant) {
                return $personneDeclarant->code_personne;
            }
        }

        $filiation = $request->input('filiation');
        if ($filiation === 'FIL_0001') {
            return $personnes['pere']->code_personne;
        }
        if ($filiation === 'FIL_0002') {
            return $personnes['mere']->code_personne;
        }
        // Pour les autres cas (autre personne), utiliser le déclarant spécifique
        if (!$personnes['declarant']) {
            $declarantUniqueString = Sifec::uniqueString($request, "_declarant", $request->input('sexe_declarant'));
            $personnes['declarant'] = Sifec::savePersonne($request, "_declarant", $request->input('sexe_declarant'), $declarantUniqueString);
        }
        return $personnes['declarant']->code_personne;
    }

    /**
     * Configure les autres champs de la déclaration
     */
    private function configurerChampsDeclaration($dn, $request, $user)
    {
        $dn->personne_declaree = $request->personne_declaree;
        $dn->code_lieu_survenance = $request->input('lieu_survenance') ?? "LSURV_0001";
        $dn->code_user_institution = $user->affectationActive()->cui;
        $dn->code_filiation = $request->input('filiation') == "XXXXXXXXXXXXXXXX" ? "FIL_0008" : $request->input('filiation');
        // Enfant trouvé / abandonné : utiliser SMAT_0007 = "Non renseigné"
        $codeSituationMat = $request->input('code_situation_matrimoniale');
        if (in_array($request->personne_declaree ?? '', ['Enfant trouvé', 'Enfant abandonné'], true)) {
            $dn->code_situation_mat = 'SMAT_0007';
        } else {
            $dn->code_situation_mat = $codeSituationMat;
        }
        $dn->type_declaration = $request->input('type_declaration') ?? 'DECLARATION DE NAISSANCE';
        $dn->formation_sanitaire_naissance = $request->input('formation_sanitaire_naissance');
        $dn->code_institution = $user->affectationActive()->code_institution;
        $dn->type_declarant = $request->type_declarant;

        // Contexte d'affichage (option 2) : uniquement pour certificat et déclaration de naissance
        $typeDecl = $dn->type_declaration;
        if ($typeDecl === 'CERTIFICAT DE NAISSANCE' || $typeDecl === 'DECLARATION DE NAISSANCE') {
            $codeCategorie = optional(optional($user->affectationActive()->institution)->typeInstitution)->typeCategorieInstitution->code_type_categorie_ins ?? null;
            $dn->contexte_affichage = ($codeCategorie === 'TCINS_0003') ? 'formation_sanitaire' : 'centre_etat_civil';
        }

        // Champs spécifiques pour les enfants abandonnés
        $dn->lieu_placement = $request->lieu_placement;
        $dn->num_fiche_placement = $request->num_fiche_placement;
        $dn->num_jugement_placement_provisoir = $request->num_jugement_placement_provisoir;

        // Génération du numéro de certificat si nécessaire
        if ($request->input('type_declaration') != "DECLARATION DE NAISSANCE") {
            $dn->numero_certificat = Sifec::genererCodeUniqueReferentiel($dn, "numero_certificat", 4, "");
        }
    }

    public function update($request, $id, $user)
    {
        $dn =  Declarationnaissance::find($id);
        if (!$dn) {
            throw new Exception('Déclaration non trouvée');
        }
        $pere = Personne::find($request->input('code_pere'));
        $mere = Personne::find($request->input('code_mere'));
        $enfant = Personne::find($request->input('code_enfant'));
        if (!$pere || !$mere) {
            throw new Exception('Père ou mère non trouvé');
        }
        $typeAdoption = $request->input('type_adoption') ?? '';
        DB::beginTransaction();
        try {
            $pere = Sifec::updatePersonne($request, '_pere', 'M', $pere->code_personne);
            $mere = Sifec::updatePersonne($request, '_mere', 'F', $mere->code_personne);
            if ($typeAdoption != '') {
                $enfantUniqueString = Sifec::uniqueString($request, '_enfant', $request->input('sexe_enfant'), $typeAdoption);
                $enfant = Sifec::updatePersonne($request, '_enfant', $request->input('sexe_enfant'), $enfant->code_personne, $enfantUniqueString);
                $adoptantUniqueString = Sifec::uniqueString($request, '_adoptant', $request->input('sexe_adoptant'));
                $adoptant = Personne::where('personne_string', $adoptantUniqueString)->first();
                if (!$adoptant) {
                    $adoptant = Sifec::savePersonne($request, '_adoptant', $request->input('sexe_adoptant'), $adoptantUniqueString);
                }
            } else {
                $declarantUniqueString = Sifec::uniqueString($request, '_declarant', $request->input('sexe_declarant'));
                $declarant = Personne::where('personne_string', $declarantUniqueString)->first();
                if (!$declarant) {
                    $declarant = Sifec::savePersonne($request, '_declarant', $request->input('sexe_declarant'), $declarantUniqueString);
                }
                $enfant = Sifec::updatePersonne($request, '_enfant', $request->input('sexe_enfant'), $enfant->code_personne);
            }
            $dn->nombre_enfant = $request->input('nombre_enfant');
            if ($request->input('date_heure_declaration')) {
                try {
                    $dn->date_heure_declaration = Carbon::parse($request->input('date_heure_declaration'))->format('Y-m-d H:i');
                } catch (\Exception $e) {
                    $dn->date_heure_declaration = date('Y-m-d H:i');
                }
            } else {
                $dn->date_heure_declaration = date('Y-m-d H:i');
            }
            $dn->date_heure_naissance = $request->input('date_naissance_enfant') . " " . $request->input('heure_naissance_enfant') . ':00';
            // Priorité au code_declarant envoyé par le formulaire (déclarant = père, mère ou autre)
            if ($typeAdoption == '' && $request->filled('code_declarant')) {
                $personneDeclarant = Personne::find($request->input('code_declarant'));
                if ($personneDeclarant) {
                    $dn->code_declarant = $personneDeclarant->code_personne;
                }
            }
            if (!$dn->code_declarant && $typeAdoption == '') {
                if (($request->input('filiation')) == 'FIL_0001') {
                    $dn->code_declarant = $pere->code_personne;
                } elseif (($request->input('filiation')) == 'FIL_0002') {
                    $dn->code_declarant = $mere->code_personne;
                } else {
                    $dn->code_declarant = $declarant->code_personne;
                }
            }
            $dn->code_enfant = $enfant->code_personne;
            $dn->code_pere = $pere->code_personne;
            $dn->code_mere = $mere->code_personne;
            // $dn->personne_declaree = 'Enfant normal';
            $dn->code_lieu_survenance = $request->input('lieu_survenance') ?? "LSURV_0001";
            $dn->code_filiation = $request->input('filiation');
            $dn->code_situation_mat = $request->input('code_situation_matrimoniale');
            $dn->type_declaration = $request->input('type_declaration') ?? $dn->type_declaration;
            $dn->formation_sanitaire_naissance = $request->input('formation_sanitaire_naissance');
            if ($typeAdoption != '') {
                $dn->code_jugement = $request->input('code_jugement');
                $dn->code_adoptant = $adoptant->code_personne;
                $dn->type_adoption = $typeAdoption;
            }
            $dn->save();
            DB::commit();
            return $dn;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function adopter($request, $user)
    {
        DB::beginTransaction();
        try {
            $typeAdoption = $request->input('type_adoption') ?? '';
            $pereUniqueString = Sifec::uniqueString($request, '_pere', 'M');
            $mereUniqueString = Sifec::uniqueString($request, '_mere', 'F');
            $enfantUniqueString = Sifec::uniqueString($request, '_enfant', $request->input('sexe_enfant'), $typeAdoption);
            $adoptantUniqueString = Sifec::uniqueString($request, '_adoptant', $request->input('sexe_adoptant'));

            $pere = Personne::where('personne_string', $pereUniqueString)->first();
            $mere = Personne::where('personne_string', $mereUniqueString)->first();
            $enfant = Personne::where('personne_string', $enfantUniqueString)->first();
            $adoptant = Personne::where('personne_string', $adoptantUniqueString)->first();

            // Gestion des parents : réutilisation si existants, création sinon
            if ($pere == null) {
                $pere = Sifec::savePersonne($request, '_pere', 'M', $pereUniqueString);
            }
            if ($mere == null) {
                $mere = Sifec::savePersonne($request, '_mere', 'F', $mereUniqueString);
            }
            if ($adoptant == null) {
                $adoptant = Sifec::savePersonne($request, '_adoptant', $request->input('sexe_adoptant'), $adoptantUniqueString);
            }

            // Gestion de l'enfant : réutilisation si existant, création sinon
            if ($enfant == null) {
                $enfant = Sifec::savePersonne($request, '_enfant', $request->input('sexe_enfant'), $enfantUniqueString);
            }

            $dn = new  Declarationnaissance;
            $codedn = Sifec::genererCodeUniqueReferentiel($dn, 'code_declaration_naissance', 8, 'CDN_');
            $dn->code_declaration_naissance = $codedn;
            $dn->nombre_enfant = $request->input('nombre_enfant');
            if ($request->input('date_heure_declaration')) {
                try {
                    $dn->date_heure_declaration = Carbon::parse($request->input('date_heure_declaration'))->format('Y-m-d H:i');
                } catch (\Exception $e) {
                    $dn->date_heure_declaration = date('Y-m-d H:i');
                }
            } else {
                $dn->date_heure_declaration = date('Y-m-d H:i');
            }
            $dn->date_heure_naissance = $request->input('date_naissance_enfant') . " " . $request->input('heure_naissance_enfant') . ':00';
            $dn->type_declarant = 'Personne physique';
            $dn->code_adoptant = $adoptant->code_personne;
            $dn->code_enfant = $enfant->code_personne;
            $dn->code_pere = $pere->code_personne;
            $dn->code_mere = $mere->code_personne;
            $dn->personne_declaree = 'Enfant normal';
            $dn->code_lieu_survenance = $request->input('lieu_survenance') ?? 'LSURV_0001';
            $dn->code_user_institution = $user->affectationActive()->cui;
            $dn->code_filiation = $request->input('filiation');
            $dn->code_situation_mat = $request->input('code_situation_matrimoniale');
            $dn->type_declaration = $request->input('type_declaration') ?? 'DECLARATION DE NAISSANCE';
            $dn->formation_sanitaire_naissance = $request->input('formation_sanitaire_naissance');
            $dn->numero_ancien_acte = $request->input('niupp');
            $dn->code_jugement = $request->input('code_jugement');
            $dn->code_requisition = $request->input('code_requisition');
            $dn->code_institution = $user->affectationActive()->code_institution;
            $dn->save();

            // Mise à jour de l'ancien acte si besoin
            if (!empty($request->input('niupp'))) {
                $updtaeOldActe = ActeNaissance::findByIdentifier($request->input('niupp'));
                if ($updtaeOldActe) {
                    $updtaeOldActe->statut = 1;
                    $updtaeOldActe->save();
                }
            }

            // Enregistrement du premier mouvement
            $transaction = new  MouvementNaissance();
            $transaction->code_mouvement_naissance = Sifec::genererCodeUniqueReferentiel($transaction, "code_mouvement_naissance", 4, "MDN_");
            $transaction->statut = "En cours";
            $transaction->code_declaration_naissance = $dn->code_declaration_naissance;
            $transaction->cui = $user->affectationActive()->cui;
            $transaction->save();

            DB::commit();
            return $dn;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Création d'une déclaration de naissance à partir d'un jugement (supplétif, homologation, etc.)
     */
    public function creerViaJugement($request, $user)
    {
        DB::beginTransaction();
        try {
            // Génération des uniqueString pour les personnes
            $pereUniqueString = Sifec::uniqueString($request, "_pere", "M");
            $mereUniqueString = Sifec::uniqueString($request, "_mere", "F");
            $enfantUniqueString = Sifec::uniqueString($request, "_enfant", $request->input('sexe_enfant'));
            $declarantUniqueString = Sifec::uniqueString($request, "_declarant", $request->input('sexe_declarant'));

            // Recherche ou création des personnes
            $pere = Personne::where("personne_string", $pereUniqueString)->first();
            if (!$pere) {
                $pere = Sifec::savePersonne($request, "_pere", "M", $pereUniqueString);
            }
            $mere = Personne::where("personne_string", $mereUniqueString)->first();
            if (!$mere) {
                $mere = Sifec::savePersonne($request, "_mere", "F", $mereUniqueString);
            }
            $enfant = Personne::where("personne_string", $enfantUniqueString)->first();
            if (!$enfant) {
                $enfant = Sifec::savePersonne($request, "_enfant", $request->input('sexe_enfant'), $enfantUniqueString);
            }
            $declarant = Personne::where("personne_string", $declarantUniqueString)->first();
            if (!$declarant) {
                $declarant = Sifec::savePersonne($request, "_declarant", $request->input('sexe_declarant'), $declarantUniqueString);
            }

            // Création de la déclaration
            $dn = new Declarationnaissance;
            $codedn = Sifec::genererCodeUniqueReferentiel($dn, "code_declaration_naissance", 8, "CDN_");
            $dn->code_declaration_naissance = $codedn;
            $dn->nombre_enfant = $request->input('nombre_enfant');
            if ($request->input('date_heure_declaration')) {
                try {
                    $dn->date_heure_declaration = Carbon::parse($request->input('date_heure_declaration'))->format('Y-m-d H:i');
                } catch (\Exception $e) {
                    $dn->date_heure_declaration = date('Y-m-d H:i');
                }
            } else {
                $dn->date_heure_declaration = date('Y-m-d H:i');
            }
            $dn->date_heure_naissance = $request->input('date_naissance_enfant') . " " . $request->input('heure_naissance_enfant') . ":00";
            $dn->type_declarant = "Personne physique";
            $dn->code_declarant = $declarant->code_personne;
            $dn->code_enfant = $enfant->code_personne;
            $dn->code_pere = $pere->code_personne;
            $dn->code_mere = $mere->code_personne;
            $dn->personne_declaree = "Enfant normal";
            $dn->code_lieu_survenance = $request->input('lieu_survenance') ?? "LSURV_0001";
            $dn->code_user_institution = $user->affectationActive()->cui;
            $dn->code_filiation = $request->input('filiation');
            $dn->code_situation_mat = $request->input('code_situation_matrimoniale');
            $dn->type_declaration = $request->input('type_declaration') ?? 'JUGEMENT';
            $dn->formation_sanitaire_naissance = $request->input('formation_sanitaire_naissance');
            $dn->code_institution = $user->affectationActive()->code_institution;
            $dn->code_jugement = $request->input('code_jugement');
            $dn->numero_ancien_acte = $request->input('numero_ancien_acte');
            $dn->save();

            // Enregistrement du premier mouvement
            $transaction = new  MouvementNaissance();
            $transaction->code_mouvement_naissance = Sifec::genererCodeUniqueReferentiel($transaction, "code_mouvement_naissance", 4, "MDN_");
            $transaction->statut = "En cours";
            $transaction->code_declaration_naissance = $dn->code_declaration_naissance;
            $transaction->cui = $user->affectationActive()->cui;
            $transaction->save();

            DB::commit();
            return $dn;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
