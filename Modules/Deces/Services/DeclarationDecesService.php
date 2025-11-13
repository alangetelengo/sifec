<?php

namespace Modules\Deces\Services;

use Exception;
use Carbon\Carbon;
use App\Sifec\Sifec;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Deces\Entities\DDecesCause;
use Modules\Referentiel\Entities\Personne;
use Modules\Deces\Entities\DeclarationDeces;

class DeclarationDecesService
{
    /**
     * Créer une déclaration de décès avec validation et transaction.
     */
    public function enregistrer($request, $user)
    {
        // Validation des données
        $validation = $this->validerDonneesDeces($request);
        if ($validation !== true) {
            return $validation;
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

            // Gestion des personnes (défunt, déclarant, parents, conjoint)
            $personnes = $this->gererPersonnes($request, $uniqueStrings);

            // Création de la déclaration
            $declaration = $this->creerDeclaration($request, $user, $personnes);

            // Traitement des causes de décès
            $this->traiterCausesDeces($request, $declaration);

            DB::commit();
            return $declaration;

        } catch (Exception $e) {
            DB::rollBack();
            Log::channel("sifec")->error("Erreur lors de l'enregistrement de la déclaration de décès: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Valide les données de la déclaration de décès
     */
    private function validerDonneesDeces($request)
    {
        // Validation de la date de décès (ne peut pas être dans le futur)
        $dateDeces = Carbon::create($request->date_deces);
        if ($dateDeces->isFuture()) {
            return response()->json([
                "code" => "99",
                "message" => "La date de décès ne peut pas être dans le futur"
            ]);
        }

        // Validation de l'âge du défunt (doit être au moins 0 ans)
        $dateNaissanceDefunt = Carbon::create($request->date_naissance_defunt);
        $ageDefunt = $dateNaissanceDefunt->diffInYears($dateDeces);

        if ($ageDefunt < 0) {
            return response()->json([
                "code" => "99",
                "message" => "La date de naissance du défunt ne peut pas être postérieure à la date de décès"
            ]);
        }

        return true;
    }

    /**
     * Vérifie les doublons dans le système
     */
    private function verifierDoublons($request)
    {
        // Vérification du défunt
        $defuntUniqueString = Sifec::uniqueString($request, "_defunt", $request->sexe_defunt);
        $defuntExistant = Personne::where("personne_string", $defuntUniqueString)->first();

        if ($defuntExistant) {
            // Vérifier si une déclaration existe déjà pour ce défunt
            $declarationExistante = DeclarationDeces::where('code_defunt', $defuntExistant->code_personne)->first();

            if ($declarationExistante) {
                return response()->json([
                    "code" => "99",
                    "message" => "Une déclaration de décès existe déjà pour ce défunt dans le système"
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
            'date_heure_deces' => $request->date_deces . " " . $request->heure_deces . ":00",
            'type_declaration' => $request->type_declaration ?? 'DECLARATION DE DECES'
        ];

        // Ajouter le lieu de décès si spécifié
        if ($request->lieu_deces) {
            $critereDoublon['lieu_deces'] = $request->lieu_deces;
        }

        $declarationSimilaire = DeclarationDeces::where($critereDoublon)->first();

        if ($declarationSimilaire) {
            return response()->json([
                "code" => "99",
                "message" => "Une déclaration similaire existe déjà pour cette date et lieu de décès"
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
            'defunt' => Sifec::uniqueString($request, "_defunt", $request->sexe_defunt),
            'declarant' => Sifec::uniqueString($request, "_declarant", $request->sexe_declarant),
            'pere' => Sifec::uniqueString($request, "_pere", "M"),
            'mere' => Sifec::uniqueString($request, "_mere", "F"),
            'conjoint' => Sifec::uniqueString($request, "_conjoint", $request->sexe_conjoint)
        ];
    }

    /**
     * Gère la création/récupération des personnes
     */
    private function gererPersonnes($request, $uniqueStrings)
    {
        $personnes = [];

        // Gestion du défunt
        $personnes['defunt'] = Personne::where("personne_string", $uniqueStrings['defunt'])->first();
        if (!$personnes['defunt']) {
            $personnes['defunt'] = Sifec::savePersonne($request, "_defunt", $request->sexe_defunt, $uniqueStrings['defunt']);
        }

        // Gestion du déclarant
        $personnes['declarant'] = Personne::where("personne_string", $uniqueStrings['declarant'])->first();
        if (!$personnes['declarant']) {
            $personnes['declarant'] = Sifec::savePersonne($request, "_declarant", $request->sexe_declarant, $uniqueStrings['declarant']);
        }

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

        // Gestion du conjoint (optionnel)
        $personnes['conjoint'] = null;
        if ($request->nom_conjoint != null) {
            $personnes['conjoint'] = Personne::where("personne_string", $uniqueStrings['conjoint'])->first();
            if (!$personnes['conjoint']) {
                $personnes['conjoint'] = Sifec::savePersonne($request, "_conjoint", $request->sexe_conjoint, $uniqueStrings['conjoint']);
            }
        }

        return $personnes;
    }

    /**
     * Crée la déclaration de décès
     */
    private function creerDeclaration($request, $user, $personnes)
    {
            $ddeces = new DeclarationDeces;
        $codeddeces = Sifec::genererCodeUniqueReferentiel($ddeces, "code_declaration_deces", 8, "CDD_");

        // Configuration de base
            $ddeces->code_declaration_deces = $codeddeces;
        $ddeces->date_heure_declaration = now();
        $ddeces->date_heure_deces = $request->date_deces . " " . $request->heure_deces . ":00";
            $ddeces->code_lieu_survenance = $request->lieu_survenance_code;
        $ddeces->lieu_deces = $request->lieu_deces;
            $ddeces->domicile_defunt = $request->domicile_defunt;
            $ddeces->type_declarant = "Personne physique";
        $ddeces->type_declaration = $request->type_declaration ?? 'DECLARATION DE DECES';
        $ddeces->code_religion = $request->code_religion_defunt;
        $ddeces->code_situation_matrimoniale = $request->code_situation_matrimoniale_defunt;
        $ddeces->code_filiation = $request->filiation;

        // Attribution des personnes
        $ddeces->code_defunt = $personnes['defunt']->code_personne;
        $ddeces->code_declarant = $personnes['declarant']->code_personne;
        $ddeces->code_pere = $personnes['pere']->code_personne;
        $ddeces->code_mere = $personnes['mere']->code_personne;

        if ($personnes['conjoint']) {
            $ddeces->code_conjoint = $personnes['conjoint']->code_personne;
        }

        // Informations institutionnelles
        $ddeces->code_user_institution = $user->affectationActive()->cui;
            $ddeces->code_institution = $user->affectationActive()->code_institution;

        // Informations matrimoniales
        $ddeces->date_mariage = $request->date_mariage;
        $ddeces->code_regime = $request->code_regime;
        $ddeces->cec_mariage = $request->cec_mariage;
        $ddeces->cec_naissance = $request->cec_naissance;
        $ddeces->num_acte_mariage = $request->num_acte_mariage;
        $ddeces->num_acte_naissance = $request->num_acte_naissance;

            $ddeces->save();

        return $ddeces;
    }

    /**
     * Traite les causes de décès
     */
    private function traiterCausesDeces($request, $declaration)
    {
            $causes = $request->code_cause_deces;
        if ($causes != null) {
            foreach ($causes as $cause) {
                    DDecesCause::create([
                    'code_declaration_deces' => $declaration->code_declaration_deces,
                        'code_cause_deces' => $cause
                    ]);
                }
            }

        // Génération du numéro de certificat si nécessaire
        if ($declaration->type_declaration != "DECLARATION DE DECES" && $declaration->type_declaration != "DECLARATION TARDIVE") {
            $declaration->numero_certificat = Sifec::genererCodeUniqueReferentiel($declaration, "numero_certificat", 4, "");
            $declaration->save();
        }
    }

    public function update($request, $id, $user)
    {
        $declaration = DeclarationDeces::find($id);
        if (!$declaration) {
            throw new Exception('Déclaration de décès non trouvée');
        }
        $pere = Personne::find($request->input('code_pere'));
        $mere = Personne::find($request->input('code_mere'));
        $defunt = Personne::find($request->input('code_defunt'));
        $declarant = Personne::find($request->input('code_declarant'));
        if (!$pere || !$mere || !$defunt || !$declarant) {
            throw new Exception('Personne liée non trouvée');
        }
        DB::beginTransaction();
        try {
            $pere = Sifec::updatePersonne($request, '_pere', 'M', $pere->code_personne);
            $mere = Sifec::updatePersonne($request, '_mere', 'F', $mere->code_personne);
            $defunt = Sifec::updatePersonne($request, '_defunt', $request->input('sexe_defunt'), $defunt->code_personne);
            $declarant = Sifec::updatePersonne($request, '_declarant', $request->input('sexe_declarant'), $declarant->code_personne);

            $declaration->code_defunt = $defunt->code_personne;
            $declaration->code_declarant = $declarant->code_personne;
            $declaration->code_pere = $pere->code_personne;
            $declaration->code_mere = $mere->code_personne;
            $declaration->date_deces = $request->input('date_deces');
            $declaration->lieu_deces = $request->input('lieu_deces');
            $declaration->code_user_institution = $user->cui;
            $declaration->code_institution = $user->affectationActive()->code_institution;
            // ... autres champs à mettre à jour selon le modèle
            $declaration->save();

            DB::commit();
            return $declaration;
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error($e->getMessage());
            throw $e;
        }
    }
}
