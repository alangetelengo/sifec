<?php

namespace Modules\Rectification\Services;

use Exception;
use App\Sifec\Sifec;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Rectification\Entities\MouvementRectification;

class MouvementService
{
    /**
     * Enregistre un mouvement pour une fiche de rectification
     * La clé primaire du mouvement (code_mouvement_rectification)
     * est TOUJOURS générée automatiquement ici, jamais passée en paramètre.
     *
     * @param $rectification (objet Rectification)
     * @param $trmouvement (objet référentiel mouvement)
     * @param $user (utilisateur connecté)
     * @param string|null $observation
     * @return array [bool succès, string message]
     */
    public function enregistrerMouvementRectification($rectification, $trmouvement, $user, $observation = null)
    {
        try {
            DB::beginTransaction();

            $mouvement = new MouvementRectification();
            $mouvement->code_mouvement_rectification = Sifec::genererCodeUniqueReferentiel($mouvement, "code_mouvement_rectification", 4, "MRF_");
            $mouvement->code_rectification = $rectification->code_rectification;
            $mouvement->code_mouvement = $trmouvement->code_mouvement;
            $mouvement->lib_mouvement = $trmouvement->lib_mouvement;
            $mouvement->cui = $user->affectationActive()->cui;
            $mouvement->observation = $observation;
            $mouvement->code_institution_destinataire = $rectification->institution->institutionParent->code_institution;
            $mouvement->save();

            //mise à jour code_institution_destinataire de la rectification
            $rectification->code_institution_destinataire = $rectification->institution->institutionParent->code_institution;
            $rectification->statut = "Envoyé au tribunal";
            $rectification->save();


            DB::commit();
            return [true, "Mouvement de rectification enregistré avec succès."];
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('Erreur MouvementService Rectification : ' . $e->getMessage());
            return [false, $e->getMessage()];
        }
    }
}
