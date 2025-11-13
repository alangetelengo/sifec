<?php
namespace Modules\Naissance\Services;

use Exception;
use App\Sifec\Sifec;
use App\Models\Requisition;
use App\Models\MouvementDossier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Modules\Referentiel\Entities\Mouvement;
use Modules\Naissance\Entities\MouvementNaissance;
use Modules\Naissance\Entities\Declarationnaissance;

class MouvementService
{


    /**
     * Envoie une déclaration à l'institution destinataire selon la logique métier (centre d'état civil, tribunal, etc.)
     *
     * @param $user
     * @param $declaration
     * @param string $typeMouvement Code du mouvement à utiliser (ex: MOUV_0001, MOUV_0006...)
     * @param string $statut
     * @param string|null $observation
     * @return array [bool, string]
     */
    public function envoyerDeclaration($user, $declaration, $typeMouvement, $statut, $observation = null)
    {
        $mouvementRef = DB::table('tr_mouvement')->where('code_mouvement', $typeMouvement)->first();
        if (!$mouvementRef) {
            throw new Exception('Mouvement référentiel introuvable.');
        }

        $destinataire = null;
        $typeDeclaration = $declaration->type_declaration;
        $typeCategorieInstitution = $declaration->institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins;

        //la declaration provient de la Formation sanitaire
        if($typeCategorieInstitution == "TCINS_0003")
        {
            //le destinataire c'est un centre d'état civil
            $destinataire = $declaration->institution->institutionParent->code_institution;
        }
        //la declaration provient d'un centre d'état civil ou de l'ambassade
        if($typeCategorieInstitution == "TCINS_0001" || $typeCategorieInstitution == "TCINS_0004")
        {
            //recherche du type de declaration du document si c'est un certificat ou une fiche de transcription,destinataire tribunal sinon destinataire centre d'état civil
            if($typeDeclaration == "CERTIFICAT DE NON INSCRIPTION" || $typeDeclaration == "FICHE DE TRANSCRIPTION" || $typeDeclaration == "CERTIFICAT DE DESTRUCTION DE L'ACTE"){
                $destinataire = $declaration->institution->institutionParent->code_institution;
            }else{
                $destinataire = $declaration->institution->code_institution;

            }
        }

        // Autoriser plusieurs envois/renvois tant que la déclaration n'est pas approuvée (cec_approuver != 'OUI')
        if (isset($declaration->cec_approuver) && $declaration->cec_approuver === 'OUI') {
            return [false, "Cette déclaration a déjà été approuvée, impossible de l'envoyer ou renvoyer."];
        }

        DB::beginTransaction();
        try {
            // Création du mouvement dans la table MouvementNaissance
            $mouvement = new MouvementNaissance();
            $mouvement->code_mouvement_naissance = Sifec::genererCodeUniqueReferentiel($mouvement, "code_mouvement_naissance", 4, "MDN_");
            $mouvement->code_declaration_naissance = $declaration->code_declaration_naissance;
            $mouvement->code_mouvement = $mouvementRef->code_mouvement;
            $mouvement->lib_mouvement = $mouvementRef->lib_mouvement;
            $mouvement->cui = $user->affectationActive()->cui;
            $mouvement->observation = $observation;
            $mouvement->statut = $statut;
            $mouvement->code_institution_destinataire =  $destinataire;
            $mouvement->save();

            // Mettre à jour la déclaration
            $declaration->code_institution_destinataire = $destinataire;
            $declaration->declarant_approuver = "OUI";
            $declaration->save();
            DB::commit();
            return [true, "Déclaration envoyée à l'institution destinataire"];
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error($e->getMessage());
            return [false, $e->getMessage()];
        }
    }

    /**
     * Confirme une déclaration de naissance (individuelle ou en lot)
     * @param $user
     * @param $declaration
     * @param $observation string|null
     * @return array [bool, string]
     */
    public function confirmerDeclarationNaissance($user, $declaration, $statut, $motif = null, $observation = null)
    {
        DB::beginTransaction();
        try {
            //si user est tribunal, on ajoute un mouvement de confirmation
            if($user->institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins == "TCINS_0002"){
                $codeMouvement = 'MOUV_1019'; // Code du mouvement pour confirmation du dossier par le tribunal
                $observation = 'Dossier confirmé et prêt pour l\'importation du dossier';
                $declaration->tribunal_approuver = "OUI";
                $declaration->tribunal_approuve_par = $user->cui;
                $declaration->save();
            }else{
                $codeMouvement = 'MOUV_0019'; // Code du mouvement pour confirmation du dossier par le centre d'état civil
                $declaration->cec_approuver = "OUI";
                $declaration->cec_approuve_par = $user->cui;
                $declaration->save();
            }
            $mouvementRef = DB::table('tr_mouvement')->where('code_mouvement', $codeMouvement)->first();
            if (!$mouvementRef) {
                throw new Exception('Mouvement référentiel de confirmation introuvable.');
            }
            $mouvement = new MouvementNaissance();
            $mouvement->code_mouvement_naissance = Sifec::genererCodeUniqueReferentiel($mouvement,"code_mouvement_naissance",4, "MDN_");
            $mouvement->code_declaration_naissance = $declaration->code_declaration_naissance;
            $mouvement->code_mouvement = $mouvementRef->code_mouvement;
            $mouvement->lib_mouvement = $mouvementRef->lib_mouvement;
            $mouvement->statut = $statut;
            $mouvement->cui = $user->cui;
            $mouvement->motif_renvoi = $motif;
            $mouvement->observation = $observation ?? 'Dossier confirmé et prêt pour la génération de l\'acte';
            $mouvement->save();

            DB::commit();
            return [true, 'Dossier confirmé'];
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error($e->getMessage());
            return [false, $e->getMessage()];
        }
    }

    public function renvoyerDeclarationNaissance($user, $declaration, $motif = null, $observation = null)
    {
        DB::beginTransaction();
        try {
            $codeMouvement = 'MOUV_0004';
            $nouveauStatut = 'Renvoyée';

            $mouvementRef = DB::table('tr_mouvement')->where('code_mouvement', $codeMouvement)->first();
            if (!$mouvementRef) {
                throw new Exception('Mouvement référentiel introuvable.');
            }

            // Création du mouvement dans la table MouvementNaissance
            $mouvement = new MouvementNaissance();
            $mouvement->code_mouvement_naissance = Sifec::genererCodeUniqueReferentiel($mouvement,"code_mouvement_naissance",4, "MDN_");
            $mouvement->code_declaration_naissance = $declaration->code_declaration_naissance;
            $mouvement->code_mouvement = $mouvementRef->code_mouvement;
            $mouvement->lib_mouvement = $mouvementRef->lib_mouvement;
            $mouvement->statut = $nouveauStatut;
            $mouvement->cui = $user->cui;
            $mouvement->motif_renvoi = $motif;
            $mouvement->observation = $observation;
            // $mouvement->code_institution_destinataire = $declaration->code_institution;
            $mouvement->code_institution_destinataire = NULL;
            $mouvement->save();

            // Mettre à jour le champ destinataire sur la déclaration (pour le renvoi)
            $declaration->code_institution_destinataire = NULL;
            $declaration->declarant_approuver = "NON";
            $declaration->save();


            DB::commit();
            return [true, $nouveauStatut];

        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error($e->getMessage());
            return [false, $e->getMessage()];
        }
    }

    /**
     * Ajoute un événement d'acte dans MouvementNaissance selon le type d'événement demandé.
     *
     * @param $user
     * @param $declaration
     * @param string $evenement (valeurs possibles : attente_transcription, attente_approbation, non_retiré, retiré, annulé, rectifié)
     * @param string|null $observation
     * @return array [bool, string]
     */
    public function ajouterEvenementActe($user, $declaration, string $evenement,   $observation=null)
    {

        // Mapping des événements vers les codes mouvements
        $mapping = [
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

        if (!isset($mapping[$evenement])) {
            return [false, 'Type d\'événement inconnu'];
        }

        $codeMouvement = $mapping[$evenement]['code'];
        $libMouvement = $mapping[$evenement]['libelle'];

        // Contrôle d'unicité : ne pas dupliquer le même mouvement pour la même déclaration
        if ($codeMouvement !== 'MOUV_0016') { // Autoriser plusieurs retraits
            $existe = MouvementNaissance::where('code_declaration_naissance', $declaration->code_declaration_naissance)
                ->where('code_mouvement', $codeMouvement)
                ->exists();
            if ($existe) {
                Log::channel('sifec')->info('[ajouterEvenementActe] Mouvement déjà existant', [
                    'declaration' => $declaration->code_declaration_naissance,
                    'code_mouvement' => $codeMouvement,
                ]);
                return [false, 'Mouvement déjà existant'];
            }
        }

        DB::beginTransaction();
        try {
            $mouvement = new MouvementNaissance();
            $mouvement->code_mouvement_naissance = Sifec::genererCodeUniqueReferentiel($mouvement, "code_mouvement_naissance", 4, "MDN_");
            $mouvement->code_declaration_naissance = $declaration->code_declaration_naissance;
            $mouvement->code_mouvement = $codeMouvement;
            $mouvement->lib_mouvement = $libMouvement;
            $mouvement->cui = $user->cui;
            $mouvement->observation = $observation;
            $mouvement->statut = 'Actif';
            $mouvement->save();

            DB::commit();
            return [true, 'Mouvement ajouté : ' . $libMouvement];
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error($e->getMessage());
            return [false, $e->getMessage()];
        }
    }

    //ajouter les événement d'enregistrement de declaration selon les type de declaration(declaration de naissance,fiche de maternité,certificat de non inscription,certificat de destruction,jugement d'homologation,jugement d'adoption,jugement supplétif,fiche de transcription)
    public function ajouterEvenementDeclaration($user, $declaration, string $evenement, $observation = null)
    {
        // Mapping des types de déclaration vers les codes mouvements
        $mapping = [
            'declaration_naissance' => [
                'code' => 'MOUV_0024',
                'libelle' => "Déclaration de naissance enregistrée"
            ],
            'fiche_maternite' => [
                'code' => 'MOUV_0025',
                'libelle' => "Fiche de maternité enregistrée"
            ],
            'certificat_non_inscription' => [
                'code' => 'MOUV_0026',
                'libelle' => "Certificat de non inscription enregistré"
            ],
            'certificat_destruction' => [
                'code' => 'MOUV_0027',
                'libelle' => "Certificat de destruction enregistré"
            ],
            'jugement_homologation' => [
                'code' => 'MOUV_0028',
                'libelle' => "Jugement d'homologation enregistré"
            ],
            'jugement_adoption' => [
                'code' => 'MOUV_0029',
                'libelle' => "Jugement d'adoption enregistré"
            ],
            'jugement_suppletif' => [
                'code' => 'MOUV_0030',
                'libelle' => "Jugement supplétif enregistré"
            ],
            'fiche_transcription' => [
                'code' => 'MOUV_0031',
                'libelle' => "Fiche de transcription enregistrée"
            ],
            'import_requisition' => [
                'code' => 'MOUV_1001',
                'libelle' => "Réquisition importée par le tribunal"
            ],
            'import_jugement' => [
                'code' => 'MOUV_1002',
                'libelle' => "Jugement importé par le tribunal"
            ],
        ];

        if (!isset($mapping[$evenement])) {
            return [false, 'Type de déclaration inconnu'];
        }

        $codeMouvement = $mapping[$evenement]['code'];
        $libMouvement = $mapping[$evenement]['libelle'];

        // Contrôle d'unicité : ne pas dupliquer le même mouvement pour la même déclaration
        $existe = MouvementNaissance::where('code_declaration_naissance', $declaration->code_declaration_naissance)
            ->where('code_mouvement', $codeMouvement)
            ->exists();
        if ($existe) {
            Log::channel('sifec')->info('[ajouterEvenementDeclaration] Mouvement déjà existant', [
                'declaration' => $declaration->code_declaration_naissance,
                'code_mouvement' => $codeMouvement,
            ]);
            return [false, 'Mouvement déjà existant'];
        }

        DB::beginTransaction();
        try {
            $mouvement = new MouvementNaissance();
            $mouvement->code_mouvement_naissance = Sifec::genererCodeUniqueReferentiel($mouvement, "code_mouvement_naissance", 4, "MDN_");
            $mouvement->code_declaration_naissance = $declaration->code_declaration_naissance;
            $mouvement->code_mouvement = $codeMouvement;
            $mouvement->lib_mouvement = $libMouvement;
            $mouvement->cui = $user->affectationActive()->cui;
            $mouvement->observation = $observation;
            $mouvement->statut = 'En cours';
            $mouvement->save();

            DB::commit();
            return [true, 'Mouvement ajouté : ' . $libMouvement];
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error($e->getMessage());
            return [false, $e->getMessage()];
        }
    }


}
