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
     * Si une personne existe déjà, met à jour uniquement les informations modifiables
     */
    private function gererPersonnes($request, $uniqueStrings)
    {
        $personnes = [];

        // Gestion du défunt
        $personnes['defunt'] = Personne::where("personne_string", $uniqueStrings['defunt'])->first();
        if (!$personnes['defunt']) {
            $personnes['defunt'] = Sifec::savePersonne($request, "_defunt", $request->sexe_defunt, $uniqueStrings['defunt']);
        } else {
            // Personne existe déjà : mettre à jour uniquement les informations modifiables
            $this->mettreAJourInformationsModifiables($request, $personnes['defunt'], "_defunt");
        }

        // Gestion du déclarant : priorité au code_declarant envoyé par le formulaire (père, mère ou autre personne)
        $personnes['declarant'] = null;
        if ($request->filled('code_declarant')) {
            $personnes['declarant'] = Personne::find($request->input('code_declarant'));
        }
        if (!$personnes['declarant']) {
            $personnes['declarant'] = Personne::where("personne_string", $uniqueStrings['declarant'])->first();
        }
        if (!$personnes['declarant']) {
            $personnes['declarant'] = Sifec::savePersonne($request, "_declarant", $request->sexe_declarant, $uniqueStrings['declarant']);
        } else {
            $this->mettreAJourInformationsModifiables($request, $personnes['declarant'], "_declarant");
        }

        // Gestion du père
        $personnes['pere'] = Personne::where("personne_string", $uniqueStrings['pere'])->first();
        if (!$personnes['pere']) {
            $personnes['pere'] = Sifec::savePersonne($request, "_pere", "M", $uniqueStrings['pere']);
        } else {
            // Personne existe déjà : mettre à jour uniquement les informations modifiables (téléphone, email, adresse, etc.)
            $this->mettreAJourInformationsModifiables($request, $personnes['pere'], "_pere");
        }

        // Gestion de la mère
        $personnes['mere'] = Personne::where("personne_string", $uniqueStrings['mere'])->first();
        if (!$personnes['mere']) {
            $personnes['mere'] = Sifec::savePersonne($request, "_mere", "F", $uniqueStrings['mere']);
        } else {
            // Personne existe déjà : mettre à jour uniquement les informations modifiables (téléphone, email, adresse, etc.)
            $this->mettreAJourInformationsModifiables($request, $personnes['mere'], "_mere");
        }

        // Gestion du conjoint (optionnel)
        $personnes['conjoint'] = null;
        if ($request->nom_conjoint != null) {
            $personnes['conjoint'] = Personne::where("personne_string", $uniqueStrings['conjoint'])->first();
            if (!$personnes['conjoint']) {
                $personnes['conjoint'] = Sifec::savePersonne($request, "_conjoint", $request->sexe_conjoint, $uniqueStrings['conjoint']);
            } else {
                // Personne existe déjà : mettre à jour uniquement les informations modifiables
                $this->mettreAJourInformationsModifiables($request, $personnes['conjoint'], "_conjoint");
            }
        }

        return $personnes;
    }

    /**
     * Met à jour uniquement les informations modifiables d'une personne existante
     * Les informations d'identité (nom, prénom, date de naissance, lieu de naissance) ne sont PAS modifiées
     *
     * Informations modifiables :
     * - Pour le défunt : profession, niveau d'instruction, situation matrimoniale, religion, adresse
     * - Pour le père/mère : téléphone (modifiable à tout moment), email
     */
    private function mettreAJourInformationsModifiables($request, $personne, $suffixe)
    {
        // Informations modifiables pour le défunt : profession, niveau d'instruction, situation matrimoniale, religion, adresse
        if ($suffixe === "_defunt") {
            // Mettre à jour la profession si fournie
            if ($request->has("code_profession_defunt")) {
                $personne->code_profession = $request->input("code_profession_defunt") ?? $personne->code_profession;
            }

            // Mettre à jour le niveau d'instruction si fourni
            if ($request->has("niveau_instruction_defunt")) {
                $personne->niveau_instruction = $request->input("niveau_instruction_defunt") ?? $personne->niveau_instruction;
            }

            // Mettre à jour l'adresse du défunt si fournie (peut changer avec le temps)
            // Note: updateAdresse crée une nouvelle adresse, ce qui est normal pour garder l'historique
            if ($request->has("domicile_ville_defunt") || $request->has("domicile_numero_defunt") || $request->has("domicile_nomvoie_defunt")) {
                try {
                    $sifec = new Sifec();
                    $sifec->updateAdresse($request, $suffixe, $personne->personne_string);
                } catch (\Exception $e) {
                    Log::channel("sifec")->warning("Erreur lors de la mise à jour de l'adresse du défunt pour {$personne->code_personne}: " . $e->getMessage());
                }
            }

            // Note: La situation matrimoniale (code_situation_matrimoniale_defunt) et la religion (code_religion_defunt)
            // sont stockées dans la déclaration (t_declaration_deces), pas dans Personne.
            // Elles sont déjà gérées dans creerDeclaration() et seront toujours mises à jour avec les nouvelles valeurs du formulaire.
        }

        // Pour le père, la mère, le déclarant et le conjoint : téléphone sur Personne (modifiable à tout moment)
        if (in_array($suffixe, ['_pere', '_mere', '_declarant', '_conjoint'], true)) {
            if ($request->has("telephone" . $suffixe)) {
                $personne->telephone = $request->input("telephone" . $suffixe);
            }
        }

        $personne->save();

        // Mettre à jour le contact (téléphone, email) - principalement pour père/mère
        $contact = \Modules\Referentiel\Entities\ContactPersonne::where('code_personne', $personne->code_personne)->first();
        if ($contact) {
            if (in_array($suffixe, ['_pere', '_mere', '_declarant', '_conjoint'], true) && $request->has("telephone" . $suffixe)) {
                $contact->telephone = $request->input("telephone" . $suffixe);
            }
            if ($request->has("email" . $suffixe)) {
                $contact->email_personnelle = $request->input("email" . $suffixe);
            }
            if ($request->has("email_professionnel" . $suffixe)) {
                $contact->email_professionnelle = $request->input("email_professionnel" . $suffixe) ?: null;
            }
            if ($request->has("code_pays" . $suffixe)) {
                $contact->indicatif = $request->input("code_pays" . $suffixe);
            }
            $contact->save();
        } elseif (in_array($suffixe, ['_pere', '_mere', '_declarant', '_conjoint'], true)
            && ($request->has("telephone" . $suffixe) || $request->has("email" . $suffixe) || $request->has("email_professionnel" . $suffixe))) {
            $contact = new \Modules\Referentiel\Entities\ContactPersonne();
            $contact->code_personne = $personne->code_personne;
            if ($request->has("telephone" . $suffixe)) {
                $contact->telephone = $request->input("telephone" . $suffixe);
            }
            if ($request->has("email" . $suffixe)) {
                $contact->email_personnelle = $request->input("email" . $suffixe);
            }
            if ($request->has("email_professionnel" . $suffixe)) {
                $contact->email_professionnelle = $request->input("email_professionnel" . $suffixe) ?: null;
            }
            if ($request->has("code_pays" . $suffixe)) {
                $contact->indicatif = $request->input("code_pays" . $suffixe);
            }
            $contact->save();
        }

        return $personne;
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
            $causes = $request->input('code_cause_deces');
        // Vérifier que les causes existent et forment un tableau non vide
        if ($causes != null && is_array($causes) && !empty($causes)) {
            // Filtrer les causes vides
            $causesValides = array_filter($causes, function($cause) {
                return !empty($cause);
            });

            if (!empty($causesValides)) {
                // La première cause devient la cause principale dans t_declaration_deces
                $premiereCause = reset($causesValides);
                $declaration->code_cause_deces = $premiereCause;
                $declaration->save();

                // Insérer toutes les causes dans t_ddecescause (relation many-to-many)
                foreach ($causesValides as $cause) {
                    DDecesCause::create([
                        'code_declaration_deces' => $declaration->code_declaration_deces,
                        'code_cause_deces' => $cause
                    ]);
                }
            }
        }

        // Génération du numéro de certificat si nécessaire
        if ($declaration->type_declaration != "DECLARATION DE DECES" && $declaration->type_declaration != "DECLARATION TARDIVE") {
            $declaration->numero_certificat = Sifec::genererCodeUniqueReferentiel($declaration, "numero_certificat", 4, "");
            $declaration->save();
        }
    }

    /**
     * Traite les causes de décès lors d'une mise à jour
     * Supprime les anciennes causes et insère les nouvelles
     */
    private function traiterCausesDecesUpdate($request, $declaration)
    {
        $causes = $request->input('code_cause_deces');

        // Supprimer les anciennes causes
        DDecesCause::where('code_declaration_deces', $declaration->code_declaration_deces)->delete();

        // Vérifier que les nouvelles causes existent et forment un tableau non vide
        if ($causes != null && is_array($causes) && !empty($causes)) {
            // Filtrer les causes vides
            $causesValides = array_filter($causes, function($cause) {
                return !empty($cause);
            });

            if (!empty($causesValides)) {
                // La première cause devient la cause principale dans t_declaration_deces
                $premiereCause = reset($causesValides);
                $declaration->code_cause_deces = $premiereCause;
                $declaration->save();

                // Insérer toutes les causes dans t_ddecescause (relation many-to-many)
                foreach ($causesValides as $cause) {
                    DDecesCause::create([
                        'code_declaration_deces' => $declaration->code_declaration_deces,
                        'code_cause_deces' => $cause
                    ]);
                }
            } else {
                // Si aucune cause valide, mettre code_cause_deces à NULL
                $declaration->code_cause_deces = null;
                $declaration->save();
            }
        } else {
            // Si aucune cause fournie, mettre code_cause_deces à NULL
            $declaration->code_cause_deces = null;
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

            // Mettre à jour les causes de décès
            $this->traiterCausesDecesUpdate($request, $declaration);

            DB::commit();
            return $declaration;
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error($e->getMessage());
            throw $e;
        }
    }
}
