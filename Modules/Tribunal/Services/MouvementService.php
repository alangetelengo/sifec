<?php

namespace Modules\Tribunal\Services;

use Exception;
use App\Sifec\Sifec;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Deces\Entities\MouvementDeces;
use Modules\Mariage\Entities\MouvementMariage;
use Modules\Naissance\Entities\MouvementNaissance;

class MouvementService
{
    /**
     * Enregistre un mouvement pour une déclaration (naissance, mariage, décès)
     * La clé primaire du mouvement (code_mouvement_naissance, code_mouvement_mariage, code_mouvement_deces)
     * est TOUJOURS générée automatiquement ici, jamais passée en paramètre.
     *
     * @param $declaration (objet déclaration)
     * @param string $codeMouvement
     * @param string $module ('naissance', 'mariage', 'deces')
     * @param $user (utilisateur connecté)
     * @param string|null $observation
     * @return array [bool succès, string message]
     */
    public function enregistrerMouvementDossier($declaration, $module, $trmouvement, $user, $observation = null)
    {
        try {
            DB::beginTransaction();
            // La clé primaire du mouvement est générée automatiquement selon le module
            if ($module === 'naissance') {
                $mouvement = new MouvementNaissance();
                $mouvement->code_mouvement_naissance = Sifec::genererCodeUniqueReferentiel($mouvement,"code_mouvement_naissance",4, "MDN_");
                $mouvement->code_declaration_naissance = $declaration->code_declaration_naissance;
                //update cec_approuver de OUI à NON
                $declaration->cec_approuver = "NON";
                $declaration->save();
            } elseif ($module === 'mariage') {
                $mouvement = new MouvementMariage();
                $mouvement->code_mouvement_mariage = Sifec::genererCodeUniqueReferentiel($mouvement,"code_mouvement_mariage",4, "MDM_");
                $mouvement->code_declaration_mariage = $declaration->code_declaration_mariage;
                //update cec_approuver de OUI à NON
                // $declaration->cec_approuver = "NON";
                $declaration->save();
            } elseif ($module === 'deces') {
                $mouvement = new MouvementDeces();
                $mouvement->code_mouvement_deces = Sifec::genererCodeUniqueReferentiel($mouvement,"code_mouvement_deces",4, "MDD_");
                $mouvement->code_declaration_deces = $declaration->code_declaration_deces;
                //update cec_approuver de OUI à NON
                $declaration->cec_approuver = "NON";
                $declaration->save();
            } else {
                return [false, "Module inconnu pour l'enregistrement du mouvement."];
            }
            $mouvement->code_mouvement = $trmouvement->code_mouvement;
            $mouvement->lib_mouvement = $trmouvement->lib_mouvement;
            $mouvement->cui = $user->affectationActive()->cui;
            $mouvement->observation = $observation;
            $mouvement->save();



            DB::commit();
            return [true, "Mouvement enregistré avec succès."];
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('Erreur MouvementService Tribunal : ' . $e->getMessage());
            return [false, $e->getMessage()];
        }
    }

    //confirmer le document c'est update cec_approuver de NON à OUI
    public function confirmerDocument($user, $declaration, $statut, $observation = null)
    {
        DB::beginTransaction();
        try {
            $codeMouvement = 'MOUV_1019'; // Confirmation par le tribunal
            $mouvementRef = DB::table('tr_mouvement')->where('code_mouvement', $codeMouvement)->first();
            if (!$mouvementRef) {
                throw new Exception('Mouvement référentiel de confirmation introuvable.');
            }

            // Choix du modèle selon le type de déclaration
            if ($declaration instanceof \Modules\Naissance\Entities\Declarationnaissance) {
                $mouvement = new \Modules\Naissance\Entities\MouvementNaissance();
                $mouvement->code_declaration_naissance = $declaration->code_declaration_naissance;
                $mouvement->code_mouvement_naissance = \App\Sifec\Sifec::genererCodeUniqueReferentiel($mouvement, "code_mouvement_naissance", 4, "MDN_");
            } elseif ($declaration instanceof \Modules\Deces\Entities\DeclarationDeces) {
                $mouvement = new \Modules\Deces\Entities\MouvementDeces();
                $mouvement->code_declaration_deces = $declaration->code_declaration_deces;
                $mouvement->code_mouvement_deces = \App\Sifec\Sifec::genererCodeUniqueReferentiel($mouvement, "code_mouvement_deces", 4, "MDC_");
            } elseif ($declaration instanceof \Modules\Mariage\Entities\DeclarationMariage) {
                $mouvement = new \Modules\Mariage\Entities\MouvementMariage();
                $mouvement->code_declaration_mariage = $declaration->code_declaration_mariage;
                $mouvement->code_mouvement_mariage = \App\Sifec\Sifec::genererCodeUniqueReferentiel($mouvement, "code_mouvement_mariage", 4, "MDM_");
            } else {
                throw new Exception('Type de déclaration inconnu pour la confirmation.');
            }

            $mouvement->code_mouvement = $mouvementRef->code_mouvement;
            $mouvement->lib_mouvement = $mouvementRef->lib_mouvement;
            $mouvement->statut = $statut;
            $mouvement->cui = $user->cui;
            $mouvement->observation = $observation ?? 'Dossier confirmé et prêt pour l\'importation du dossier';
            $mouvement->save();

            $declaration->cec_approuver = "OUI";
            $declaration->save();

            DB::commit();
            return [true, 'Dossier confirmé'];
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error($e->getMessage());
            return [false, $e->getMessage()];
        }
    }
}
