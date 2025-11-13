<?php

namespace Modules\Mariage\Services;

use Exception;
use App\Sifec\Sifec;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Mariage\Entities\MouvementMariage;
use Modules\Mariage\Entities\DeclarationMariage;
use Modules\Tribunal\Services\MouvementService as TribunalMouvementService;

class MouvementMariageService
{
    /**
     * Service de mouvement du tribunal (centralisé)
     */
    private $tribunalMouvementService;

    public function __construct()
    {
        $this->tribunalMouvementService = app(TribunalMouvementService::class);
    }

    /**
     * Envoyer une déclaration de mariage vers une institution (tribunal)
     *
     * @param DeclarationMariage $declaration
     * @param string $typeMouvement Type de mouvement (ex: 'dispense_mariage')
     * @param mixed $tribunal Institution tribunal destinataire
     * @param mixed $user Utilisateur effectuant l'action
     * @param string $statut Statut du mouvement
     * @param string|null $observation Observation optionnelle
     * @return array [bool, string] [succès, message]
     */
    public function envoyerDeclaration($declaration, $typeMouvement, $tribunal, $user,$statut, $observation = null)
    {
        try {
            // Mapping des types de déclaration vers les codes mouvements
            $mapping = $this->obtenirMappingMouvements();

            if (!isset($mapping[$typeMouvement])) {
                throw new Exception('Type de mouvement inconnu pour les déclarations de mariage');
            }

            // Récupération du code de mouvement depuis le mapping
            $codeMouvement = $mapping[$typeMouvement]['code'];

            // Récupération du mouvement référentiel
            $trmouvement = DB::table('tr_mouvement')->where('code_mouvement', $codeMouvement)->first();
            if (!$trmouvement) {
                throw new Exception("Mouvement référentiel {$codeMouvement} introuvable");
            }

            // Récupérer le code de l'institution tribunal
            $codeInstitutionTribunal = is_object($tribunal) ? $tribunal->code_institution : $tribunal;

           //creation de mouvement dans la table MouvementMariage
           $mouvement = new MouvementMariage();
           $mouvement->code_mouvement_mariage = Sifec::genererCodeUniqueReferentiel($mouvement, "code_mouvement_mariage", 4, "MDM_");
           $mouvement->code_declaration_mariage = $declaration->code_declaration_mariage;
           $mouvement->code_mouvement = $trmouvement->code_mouvement;
           $mouvement->lib_mouvement = $trmouvement->lib_mouvement;
           $mouvement->cui = $user->affectationActive()->cui;
           $mouvement->observation = $observation;
           $mouvement->statut = $statut;
           $mouvement->code_institution_destinataire = $codeInstitutionTribunal;
           $mouvement->save();

            // Mettre à jour la déclaration
            $declaration->code_institution_destinataire = $codeInstitutionTribunal;
            $declaration->cec_approuver = "OUI";
            $declaration->cec_approuve_par = $user->affectationActive()->cui;
            $declaration->cec_approuve_le = date("Y-m-d H:i:s");
            $declaration->epoux_approuver = "OUI";
            $declaration->epouse_approuver = "OUI";
            $declaration->save();

            return [true, "Déclaration envoyée avec succès au tribunal"];

        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de l\'envoi de la déclaration de mariage', [
                'code_declaration' => $declaration->code_declaration_mariage,
                'type_mouvement' => $typeMouvement,
                'error' => $e->getMessage()
            ]);
            return [false, $e->getMessage()];
        }
    }

    /**
     * Créer un mouvement initial pour une nouvelle déclaration
     *
     * @param DeclarationMariage $declaration
     * @param $user
     * @param string $statut
     * @return MouvementMariage
     */
    public function creerMouvementInitial(DeclarationMariage $declaration, $user, $statut = "En cours")
    {
        try {
            $mouvement = new MouvementMariage;
            $mouvement->code_mouvement_mariage = Sifec::genererCodeUniqueReferentiel($mouvement, "code_mouvement_mariage", 4, "MDM_");
            $mouvement->statut = $statut;
            $mouvement->code_declaration_mariage = $declaration->code_declaration_mariage;
            $mouvement->cui = $user->affectationActive()->cui;

            // Mouvement initial : déclaration créée
            $mouvement->code_mouvement = 'MOUV_2007';
            $mouvement->lib_mouvement = 'Formulaire type enregistré';
            $mouvement->save();

            Log::channel('sifec')->info('Mouvement initial créé pour déclaration de mariage', [
                'code_declaration' => $declaration->code_declaration_mariage,
                'code_mouvement_mariage' => $mouvement->code_mouvement_mariage,
                'user' => $user->id
            ]);

            return $mouvement;

        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de la création du mouvement initial', [
                'code_declaration' => $declaration->code_declaration_mariage,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }



    /**
     * Publier les bans de mariage
     *
     * @param DeclarationMariage $declaration
     * @param $user
     * @param string|null $observation
     * @return array [bool, string]
     */
    public function publierBanMariage(DeclarationMariage $declaration, $user, $observation = null)
    {
        return $this->envoyerDeclaration($declaration, 'publication_ban', null, $user, 'Publié', $observation);
    }

    /**
     * Célébrer le mariage
     *
     * @param DeclarationMariage $declaration
     * @param $user
     * @param string|null $observation
     * @return array [bool, string]
     */
    public function celebrerMariage(DeclarationMariage $declaration, $user, $observation = null)
    {
        return $this->envoyerDeclaration($declaration, 'celebration', null, $user, 'Célébré', $observation);
    }

    /**
     * Confirmer une déclaration de mariage
     *
     * @param $affectation
     * @param DeclarationMariage $declaration
     * @param string $statut
     * @param string|null $observation
     * @return array [bool, string]
     */
    public function confirmerDeclaration($affectation, $declaration, $statut, $observation = null)
    {
        DB::beginTransaction();
        try {
            $user = $affectation;

              //si user est tribunal, on ajoute un mouvement de confirmation
              if($user->institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins == "TCINS_0002"){
                $codeMouvement = 'MOUV_1019'; // Code du mouvement pour confirmation du dossier par le tribunal
                $observation = 'Dossier confirmé et prêt pour l\'importation du dossier';
                $declaration->tribunal_approuver = "OUI";
                $declaration->tribunal_approuve_par = $affectation->cui;
                $declaration->tribunal_approuve_le = date("Y-m-d H:i:s");
                $declaration->save();
            }else{
                $codeMouvement = 'MOUV_0019'; // Code du mouvement pour confirmation du dossier par le centre d'état civil
                $declaration->cec_approuver = "OUI";
                $declaration->cec_approuve_par = $affectation->cui;
                $declaration->epoux_approuver = "OUI";
                $declaration->epouse_approuver = "OUI";
                $declaration->save();
            }


            $mouvementRef = DB::table('tr_mouvement')->where('code_mouvement', $codeMouvement)->first();
            if (!$mouvementRef) {
                throw new Exception('Mouvement référentiel de confirmation introuvable.');
            }

            $mouvement = new MouvementMariage();
            $mouvement->code_mouvement_mariage = Sifec::genererCodeUniqueReferentiel($mouvement, "code_mouvement_mariage", 4, "MDM_");
            $mouvement->code_declaration_mariage = $declaration->code_declaration_mariage;
            $mouvement->code_mouvement = $mouvementRef->code_mouvement;
            $mouvement->lib_mouvement = $mouvementRef->lib_mouvement;
            $mouvement->statut = $statut;
            $mouvement->cui = $affectation->cui;
            $mouvement->observation = $observation ?? 'Dossier confirmé et prêt pour la génération de l\'acte';
            $mouvement->save();

            // Notification du centre d'état civil de la validation du dossier
            if ($declaration->institution) {
                \Modules\Notification\Services\NotificationService::notifierAgentsInstitution(
                    $declaration->institution,
                    new \Modules\Notification\Notifications\FormulaireTypeValideNotification(
                        $declaration->code_declaration_mariage,
                        $observation ?? 'Dossier confirmé et prêt pour la génération de l\'acte',
                        $declaration->type_declaration
                    )
                );
            }

            DB::commit();
            return [true, 'Déclaration confirmée avec succès'];

        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('Erreur lors de la confirmation de la déclaration de mariage', [
                'code_declaration' => $declaration->code_declaration_mariage,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [false, $e->getMessage()];
        }
    }

    /**
     * Renvoyer une déclaration au centre d'état civil
     *
     * @param $affectation
     * @param DeclarationMariage $declaration
     * @param string|null $observation
     * @return array [bool, string]
     */
    public function renvoyerAuCentre($affectation, $declaration, $observation = null)
    {
        DB::beginTransaction();
        try {
            $user = $affectation->user;
            $codeMouvement = 'MOUV_0004';
            $nouveauStatut = 'Renvoyée';

            $mouvementRef = DB::table('tr_mouvement')->where('code_mouvement', $codeMouvement)->first();
            if (!$mouvementRef) {
                throw new Exception('Mouvement référentiel introuvable.');
            }

            // Création du mouvement dans la table MouvementMariage
            $mouvement = new MouvementMariage();
            $mouvement->code_mouvement_mariage = Sifec::genererCodeUniqueReferentiel($mouvement, "code_mouvement_mariage", 4, "MDM_");
            $mouvement->code_declaration_mariage = $declaration->code_declaration_mariage;
            $mouvement->code_mouvement = $mouvementRef->code_mouvement;
            $mouvement->lib_mouvement = $mouvementRef->lib_mouvement;
            $mouvement->statut = $nouveauStatut;
            $mouvement->cui = $user->cui;
            $mouvement->observation = $observation;
            $mouvement->code_institution_destinataire = NULL;
            $mouvement->save();

            // Mettre à jour le champ destinataire sur la déclaration (pour le renvoi)
            $declaration->code_institution_destinataire = NULL;
            $declaration->cec_approuver = "NON";
            $declaration->save();

            DB::commit();
            return [true, 'Déclaration renvoyée avec succès'];

        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('Erreur lors du renvoi de la déclaration de mariage', [
                'code_declaration' => $declaration->code_declaration_mariage,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [false, $e->getMessage()];
        }
    }

    /**
     * Rejeter une déclaration de mariage
     *
     * @param DeclarationMariage $declaration
     * @param $user
     * @param string $motifRejet
     * @return array [bool, string]
     */
    public function rejeterDeclaration(DeclarationMariage $declaration, $user, $motifRejet)
    {
        return $this->envoyerDeclaration($declaration, 'rejet', null, $user, 'Rejeté', $motifRejet);
    }

    /**
     * Obtenir l'historique des mouvements d'une déclaration
     *
     * @param DeclarationMariage $declaration
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function obtenirHistoriqueMouvements(DeclarationMariage $declaration)
    {
        return MouvementMariage::where('code_declaration_mariage', $declaration->code_declaration_mariage)
                              ->orderBy('created_at', 'desc')
                              ->get();
    }

    /**
     * Obtenir le dernier mouvement d'une déclaration
     *
     * @param DeclarationMariage $declaration
     * @return MouvementMariage|null
     */
    public function obtenirDernierMouvement(DeclarationMariage $declaration)
    {
        return MouvementMariage::where('code_declaration_mariage', $declaration->code_declaration_mariage)
                              ->latest('created_at')
                              ->first();
    }

    /**
     * Vérifier si une déclaration peut être envoyée (statut approprié)
     *
     * @param DeclarationMariage $declaration
     * @return bool
     */
    public function peutEtreEnvoyee(DeclarationMariage $declaration)
    {
        // Une déclaration peut être envoyée au tribunal si elle n'est pas approuvée par le centre
        return $declaration->cec_approuver === "NON";
    }

    /**
     * Mettre à jour le statut d'une déclaration selon le mouvement
     *
     * @param DeclarationMariage $declaration
     * @param string $typeMouvement
     * @return void
     */
    public function mettreAJourStatutDeclaration(DeclarationMariage $declaration, $typeMouvement)
    {
        switch ($typeMouvement) {
            case 'envoi_tribunal':
                $declaration->cec_approuver = "NON";
                $declaration->tribunal_approuver = "EN_ATTENTE";
                break;
            case 'confirmation':
                $declaration->tribunal_approuver = "OUI";
                $declaration->cec_approuver = "OUI";
                break;
            case 'rejet':
                $declaration->tribunal_approuver = "NON";
                $declaration->cec_approuver = "NON";
                break;
            case 'renvoi_centre':
                $declaration->tribunal_approuver = "OUI";
                break;
        }

        $declaration->save();
    }

    /**
     * Obtenir le mapping des types de mouvements vers les codes
     *
     * @return array
     */
    private function obtenirMappingMouvements()
    {
        return [
            // Mouvements de déclaration
            'dispense_mariage' => [
                'code' => 'MOUV_2008',
                'libelle' => "Formulaire type envoyé au tribunal"
            ],
            'confirmation' => [
                'code' => 'MOUV_0009',
                'libelle' => "Déclaration de mariage confirmée"
            ],
            'renvoi_centre' => [
                'code' => 'MOUV_0004',
                'libelle' => "Déclaration de mariage renvoyée au centre"
            ],
            'publication_ban' => [
                'code' => 'MOUV_2009',
                'libelle' => "Publication de ban de mariage effectuée"
            ],
            'celebration' => [
                'code' => 'MOUV_2010',
                'libelle' => "Célébration de mariage effectuée"
            ],
            'acte_genere' => [
                'code' => 'MOUV_0005',
                'libelle' => "Acte généré et envoyé à la signature"
            ],
            'acte_valide_retire' => [
                'code' => 'MOUV_0212',
                'libelle' => "Acte de mariage signé et rétiré"
            ],
            // Mouvements d'actes
            'attente_transcription' => [
                'code' => 'MOUV_0013',
                'libelle' => "En attente de transcription de l'acte"
            ],
            'attente_approbation' => [
                'code' => 'MOUV_0014',
                'libelle' => "Acte produit et en attente d'approbation de l'officier d'état civil"
            ],
            'non_retiré' => [
                'code' => 'MOUV_0015',
                'libelle' => "Acte produit non rétiré"
            ],
            'retiré' => [
                'code' => 'MOUV_0016',
                'libelle' => "Acte rétiré"
            ],
            'annulé' => [
                'code' => 'MOUV_0017',
                'libelle' => "Acte annulé"
            ],
            'rectifié' => [
                'code' => 'MOUV_0023',
                'libelle' => "Acte rectifié"
            ],
        ];
    }



    /**
     * Obtenir les statistiques des mouvements de mariage
     *
     * @param array $filtres
     * @return array
     */
    public function obtenirStatistiquesMouvements($filtres = [])
    {
        $query = MouvementMariage::query();

        // Appliquer les filtres si fournis
        if (isset($filtres['date_debut'])) {
            $query->whereDate('created_at', '>=', $filtres['date_debut']);
        }
        if (isset($filtres['date_fin'])) {
            $query->whereDate('created_at', '<=', $filtres['date_fin']);
        }
        if (isset($filtres['cui'])) {
            $query->where('cui', $filtres['cui']);
        }

        return [
            'total_mouvements' => $query->count(),
            'mouvements_par_type' => $query->groupBy('code_mouvement')
                                          ->selectRaw('code_mouvement, lib_mouvement, count(*) as total')
                                          ->get(),
            'mouvements_par_statut' => $query->groupBy('statut')
                                            ->selectRaw('statut, count(*) as total')
                                            ->get()
        ];
    }

    /**
     * Ajoute un événement d'acte dans MouvementMariage selon le type d'événement demandé.
     *
     * @param $user
     * @param $declaration
     * @param string $evenement (valeurs possibles : attente_transcription, attente_approbation, non_retiré, retiré, annulé, rectifié)
     * @param string|null $observation
     * @param $acte
     * @return array [bool, string]
     */
    public function ajouterEvenementActe($user, $declaration, string $evenement, $observation = null, $acte = null)
    {
        // Utiliser le mapping centralisé
        $mapping = $this->obtenirMappingMouvements();

        if (!isset($mapping[$evenement])) {
            return [false, 'Type d\'événement inconnu'];
        }

        $codeMouvement = $mapping[$evenement]['code'];
        $libMouvement = $mapping[$evenement]['libelle'];

        // Contrôle d'unicité : ne pas dupliquer le même mouvement pour la même déclaration
        if ($codeMouvement !== 'MOUV_0016') { // si déjà retiré
            $existe = MouvementMariage::where('code_declaration_mariage', $declaration->code_declaration_mariage)
                ->where('code_mouvement', $codeMouvement)
                ->exists();
            if ($existe) {
                Log::channel('sifec')->info('[ajouterEvenementActe] Mouvement déjà existant', [
                    'declaration' => $declaration->code_declaration_mariage,
                    'code_mouvement' => $codeMouvement,
                ]);
                return [false, 'Mouvement déjà existant'];
            }
        }

        DB::beginTransaction();
        try {
            $mouvement = new MouvementMariage();
            $mouvement->code_mouvement_mariage = Sifec::genererCodeUniqueReferentiel($mouvement, "code_mouvement_mariage", 4, "MDM_");
            $mouvement->code_declaration_mariage = $declaration->code_declaration_mariage;
            $mouvement->code_mouvement = $codeMouvement;
            $mouvement->lib_mouvement = $libMouvement;
            $mouvement->cui = $user->cui;
            $mouvement->observation = $observation;
            $mouvement->statut = 'Actif';
            $mouvement->save();

            // Envoyer une notification pour l'événement attente_approbation
            if ($evenement === 'attente_approbation') {
                $actePourNotification = $declaration;
                if ($actePourNotification) {
                    $codeInstitutionCentre = $user->affectationActive()->institution->code_institution;
                    \Modules\Notification\Services\NotificationService::notifierAgentsInstitution(
                        $codeInstitutionCentre,
                        new \Modules\Notification\Notifications\ActeMariageAValiderNotification(
                            $actePourNotification->code_acte_mariage,
                            "Acte de mariage généré et en attente de la signature de l'officier d'état civil"
                        )
                    );
                }
            }

            DB::commit();
            return [true, 'Mouvement ajouté : ' . $libMouvement];
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error($e->getMessage());
            return [false, $e->getMessage()];
        }
    }
}
