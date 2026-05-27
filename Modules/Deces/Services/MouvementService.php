<?php
namespace Modules\Deces\Services;

use Exception;
use App\Sifec\Sifec;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Deces\Entities\MouvementDeces;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Deces\Services\DecesDestinataireEnvoiService;

class MouvementService
{
    /**
     * Envoie une déclaration de décès à l'institution destinataire selon la logique métier (centre d'état civil, tribunal, etc.)
     *
     * @param $user
     * @param $declaration
     * @param string $typeMouvement Code du mouvement à utiliser (ex: MOUV_0002, MOUV_0006...)
     * @param string $statut
     * @param string|null $observation
     * @return array [bool, string]
     */
    public function envoyerDeclaration($user, $declaration, $typeMouvement, $statut, $observation = null)
    {
        // Mapping des types de déclaration vers les codes mouvements
        $mapping = [
            'declaration_deces' => [
                'code' => 'MOUV_0002',
                'libelle' => "Déclaration de décès envoyée"
            ],
            'declaration_tardive' => [
                'code' => 'MOUV_0002',
                'libelle' => "Déclaration tardive envoyée"
            ],
            'certificat_constatation_deces' => [
                'code' => 'MOUV_2006',
                'libelle' => "Certificat de constatation de décès envoyé"
            ],
            'certificat_non_inscription' => [
                'code' => 'MOUV_0006',
                'libelle' => "Certificat de non inscription envoyé"
            ],
            'certificat_destruction' => [
                'code' => 'MOUV_0006',
                'libelle' => "Certificat de destruction envoyé"
            ],
            'fiche_transcription' => [
                'code' => 'MOUV_0006',
                'libelle' => "Fiche de transcription envoyée"
            ],
        ];

        if (!isset($mapping[$typeMouvement])) {
            return [false, 'Type de déclaration inconnu'];
        }

        $codeMouvement = $mapping[$typeMouvement]['code'];
        $libMouvement = $mapping[$typeMouvement]['libelle'];



        $mouvementRef = DB::table('tr_mouvement')->where('code_mouvement', $codeMouvement)->first();
        if (!$mouvementRef) {
            throw new Exception('Mouvement référentiel introuvable.');
        }

        $destinataire = null;
        if ($typeMouvement === 'certificat_constatation_deces') {
            $declaration->loadMissing(['institution.pompeFunebre', 'institution.institutionParent']);
            $resolu = app(DecesDestinataireEnvoiService::class)->resolveCodeInstitutionDestinataire($declaration);
            if ($resolu !== null) {
                $destinataire = $resolu;
            }
        }

        if ($destinataire === null) {
            if ($declaration->institution->pompeFunebre) {
                $destinataire = $declaration->institution->pompeFunebre->code_institution;
            } else {
                if ($declaration->type_declaration == 'DECLARATION TARDIVE') {
                    $destinataire = $declaration->institution->code_institution;
                } else {
                    $destinataire = $declaration->institution->institutionParent->code_institution;
                }
            }
        }

        // Autoriser plusieurs envois/renvois tant que la déclaration n'est pas approuvée (cec_approuver != 'OUI')
        if (isset($declaration->cec_approuver) && $declaration->cec_approuver === 'OUI') {
            return [false, "Cette déclaration a déjà été approuvée, impossible de l'envoyer ou renvoyer."];
        }
        DB::beginTransaction();
        try {
            $mouvement = new MouvementDeces();
            $mouvement->code_mouvement_deces = Sifec::genererCodeUniqueReferentiel($mouvement, "code_mouvement_deces", 4, "MDC_");
            $mouvement->code_declaration_deces = $declaration->code_declaration_deces;
            $mouvement->code_mouvement = $mouvementRef->code_mouvement;
            $mouvement->lib_mouvement = $libMouvement;
            $mouvement->cui = $user->affectationActive()->cui;
            $mouvement->observation = $observation;
            $mouvement->statut = $statut;
            $mouvement->code_institution_destinataire = $destinataire;
            $mouvement->save();
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
     * Confirme une déclaration de décès (individuelle ou en lot)
     * @param $user
     * @param $declaration
     * @param $observation string|null
     * @return array [bool, string]
     */
    public function confirmerDeclarationDeces($user, $declaration, $statut, $motif = null, $observation = null)
    {
        DB::beginTransaction();
        try {
            if($user->institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins == "TCINS_0002"){
                $codeMouvement = 'MOUV_1019'; // Code du mouvement pour confirmation du dossier par le tribunal
                $observation = 'Dossier de décès confirmé et prêt pour l\'importation du dossier';
                $declaration->tribunal_approuver = "OUI";
                $declaration->tribunal_approuve_par = $user->cui;
                $declaration->save();
            }else{
                $codeMouvement = 'MOUV_0019'; // Code du mouvement pour confirmation du dossier par le centre d'état civil / PF
                $declaration->cec_approuver = "OUI";
                $declaration->cec_approuve_par = $user->cui;
                $declaration->cec_approuve_le = now();

                $etaitConstatation = ($declaration->type_declaration === 'CERTIFICAT DE CONSTATATION DE DECES');
                $etaitCertificatFs = ($declaration->type_declaration === 'DECLARATION DE DECES'
                    && empty($declaration->type_declaration_origine));

                if ($etaitConstatation) {
                    $declaration->type_declaration_origine = 'CERTIFICAT DE CONSTATATION DE DECES';
                    $declaration->type_declaration = 'DECLARATION DE DECES';
                    $declaration->contexte_affichage = 'pompe_funebre';
                } elseif ($etaitCertificatFs) {
                    $declaration->type_declaration_origine = 'CERTIFICAT DE DECES';
                    $declaration->contexte_affichage = 'pompe_funebre';
                }

                $declaration->save();

                if ($etaitConstatation || $etaitCertificatFs) {
                    $refTransform = DB::table('tr_mouvement')->where('code_mouvement', 'MOUV_2012')->first();
                    if (! $refTransform) {
                        throw new Exception('Mouvement référentiel MOUV_2012 introuvable (certificat transformé en déclaration de décès).');
                    }
                    $mvtTransform = new MouvementDeces();
                    $mvtTransform->code_mouvement_deces = Sifec::genererCodeUniqueReferentiel($mvtTransform, 'code_mouvement_deces', 4, 'MDC_');
                    $mvtTransform->code_declaration_deces = $declaration->code_declaration_deces;
                    $mvtTransform->code_mouvement = $refTransform->code_mouvement;
                    $mvtTransform->lib_mouvement = $refTransform->lib_mouvement;
                    $mvtTransform->statut = $statut;
                    $mvtTransform->cui = $user->cui;
                    $mvtTransform->motif_renvoi = $motif;
                    $mvtTransform->observation = $observation ?? 'Certificat enregistré comme déclaration de décès par la pompe funèbre.';
                    $mvtTransform->save();
                }
            }
            $mouvementRef = DB::table('tr_mouvement')->where('code_mouvement', $codeMouvement)->first();
            if (!$mouvementRef) {
                throw new Exception('Mouvement référentiel de confirmation introuvable.');
            }
            $mouvement = new MouvementDeces();
            $mouvement->code_mouvement_deces = Sifec::genererCodeUniqueReferentiel($mouvement,"code_mouvement_deces",4, "MDC_");
            $mouvement->code_declaration_deces = $declaration->code_declaration_deces;
            $mouvement->code_mouvement = $mouvementRef->code_mouvement;
            $mouvement->lib_mouvement = $mouvementRef->lib_mouvement;
            $mouvement->statut = $statut;
            $mouvement->cui = $user->cui;
            $mouvement->motif_renvoi = $motif;
            $mouvement->observation = $observation ?? 'Dossier confirmé et prêt pour la génération de l\'acte';
            $mouvement->save();
            DB::commit();
            return [true, 'Dossier de décès confirmé'];
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error($e->getMessage());
            return [false, $e->getMessage()];
        }
    }

    public function renvoyerDeclarationDeces($user, $declaration, $motif = null, $observation = null)
    {
        DB::beginTransaction();
        try {
            $codeMouvement = 'MOUV_0004';
            $nouveauStatut = 'Renvoyée';
            $mouvementRef = DB::table('tr_mouvement')->where('code_mouvement', $codeMouvement)->first();
            if (!$mouvementRef) {
                throw new Exception('Mouvement référentiel introuvable.');
            }
            $mouvement = new MouvementDeces();
            $mouvement->code_mouvement_deces = Sifec::genererCodeUniqueReferentiel($mouvement,"code_mouvement_deces",4, "MDC_");
            $mouvement->code_declaration_deces = $declaration->code_declaration_deces;
            $mouvement->code_mouvement = $mouvementRef->code_mouvement;
            $mouvement->lib_mouvement = $mouvementRef->lib_mouvement;
            $mouvement->statut = $nouveauStatut;
            $mouvement->cui = $user->cui;
            $mouvement->motif_renvoi = $motif;
            $mouvement->observation = $observation;
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
     * Ajoute un événement d'acte dans MouvementDeces selon le type d'événement demandé.
     *
     * @param $user
     * @param $declaration
     * @param string $evenement (valeurs possibles : attente_transcription, attente_approbation, non_retiré, retiré, annulé, rectifié)
     * @param string|null $observation
     * @return array [bool, string]
     */
    public function ajouterEvenementActe($user, $declaration, string $evenement, $observation=null)
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
            $existe = MouvementDeces::where('code_declaration_deces', $declaration->code_declaration_deces)
                ->where('code_mouvement', $codeMouvement)
                ->exists();
            if ($existe) {
                Log::channel('sifec')->info('[ajouterEvenementActe] Mouvement déjà existant', [
                    'declaration' => $declaration->code_declaration_deces,
                    'code_mouvement' => $codeMouvement,
                ]);
                return [false, 'Mouvement déjà existant'];
            }
        }
        DB::beginTransaction();
        try {
            $mouvement = new MouvementDeces();
            $mouvement->code_mouvement_deces = Sifec::genererCodeUniqueReferentiel($mouvement, "code_mouvement_deces", 4, "MDC_");
            $mouvement->code_declaration_deces = $declaration->code_declaration_deces;
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

      //ajouter les événement d'enregistrement de declaration selon les type de declaration(declaration de naissance,certificat de non inscription,certificat de destruction,jugement d'homologation,jugement d'adoption,jugement supplétif,fiche de transcription)
      public function ajouterEvenementDeclaration($user, $declaration, string $evenement, $observation = null)
      {
          // Mapping des types de déclaration vers les codes mouvements
          $mapping = [
              'declaration_deces' => [
                  'code' => 'MOUV_0032',
                  'libelle' => "Déclaration de décès enregistrée"
              ],
              'declaration_tardive' => [
                  'code' => 'MOUV_0032',
                  'libelle' => "Déclaration tardive enregistrée"
              ],
              'certificat_constatation_deces' => [
                  'code' => 'MOUV_2005',
                  'libelle' => "Certificat de constatation de décès enregistré"
              ],
              'certificat_non_inscription' => [
                  'code' => 'MOUV_0026',
                  'libelle' => "Certificat de non inscription enregistré"
              ],
              'certificat_destruction' => [
                  'code' => 'MOUV_0027',
                  'libelle' => "Certificat de destruction enregistré"
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
          $existe = MouvementDeces::where('code_declaration_deces', $declaration->code_declaration_deces)
              ->where('code_mouvement', $codeMouvement)
              ->exists();
          if ($existe) {
              Log::channel('sifec')->info('[ajouterEvenementDeclaration] Mouvement déjà existant', [
                  'declaration' => $declaration->code_declaration_deces,
                  'code_mouvement' => $codeMouvement,
              ]);
              return [false, 'Mouvement déjà existant'];
          }

          DB::beginTransaction();
          try {
              $mouvement = new MouvementDeces();
              $mouvement->code_mouvement_deces = Sifec::genererCodeUniqueReferentiel($mouvement, "code_mouvement_deces", 4, "MDC_");
              $mouvement->code_declaration_deces = $declaration->code_declaration_deces;
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

      public function envoyerCertificatDeces($user, $certificat, $typeMouvement, $statut, $observation = null, $tribunal)
      {

         // Mapping des types de déclaration vers les codes mouvements
         $mapping = [
            'certificat_non_inscription' => [
                'code' => 'MOUV_0006',
                'libelle' => "Certificat de non inscription envoyé au tribunal"
            ],
            'certificat_destruction' => [
                'code' => 'MOUV_0006',
                'libelle' => "Certificat de destruction envoyé"
            ],
            'fiche_transcription' => [
                'code' => 'MOUV_0006',
                'libelle' => "Fiche de transcription envoyée"
            ],
        ];

        if (!isset($mapping[$typeMouvement])) {
            return [false, 'Type de déclaration inconnu'];
        }

        $codeMouvement = $mapping[$typeMouvement]['code'];
        $libMouvement = $mapping[$typeMouvement]['libelle'];



        $mouvementRef = DB::table('tr_mouvement')->where('code_mouvement', $codeMouvement)->first();
        if (!$mouvementRef) {
            throw new Exception('Mouvement référentiel introuvable.');
        }

        // Vérifier si le certificat peut encore être envoyé
        // Permettre l'envoi si le dernier mouvement est un renvoi (MOUV_0004)
        $dernierMouvement = $certificat->mouvements()->orderBy('created_at', 'desc')->first();
        if (isset($certificat->cec_approuver) && $certificat->cec_approuver === 'OUI') {
            if (!$dernierMouvement || $dernierMouvement->code_mouvement !== 'MOUV_0004') {
                return [false, "Ce certificat a déjà été envoyé au tribunal."];
            }
        }

        DB::beginTransaction();
        try {
            $mouvement = new MouvementDeces();
            $mouvement->code_mouvement_deces = Sifec::genererCodeUniqueReferentiel($mouvement, "code_mouvement_deces", 4, "MDC_");
            $mouvement->code_declaration_deces = $certificat->code_declaration_deces;
            $mouvement->code_mouvement = $mouvementRef->code_mouvement;
            $mouvement->lib_mouvement = $libMouvement;
            $mouvement->cui = $user->affectationActive()->cui;
            $mouvement->observation = $observation;
            $mouvement->statut = $statut;
            $mouvement->code_institution_destinataire = $tribunal;
            $mouvement->save();

            $certificat->code_institution_destinataire = $tribunal;
            $certificat->declarant_approuver = "OUI";
            $certificat->save();
            DB::commit();
            return [true, "Certificat de non inscription envoyé au tribunal"];
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error($e->getMessage());
            return [false, $e->getMessage()];
        }

      }

}
