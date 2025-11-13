<?php

namespace Modules\Deces\Services;

use Modules\Deces\Entities\ActeDeces;
use Modules\Referentiel\Entities\FeuilletRegistre;
use App\Sifec\Sifec;
use App\Sifec\SifecFacade;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Deces\Entities\DeclarationDeces;

class ActeDecesService
{
    /**
     * Génère un acte de décès à partir d'une déclaration et d'un registre.
     *
     * @param $declaration
     * @param $registre
     * @param $user
     * @return ActeDeces
     * @throws Exception
     */
    /**
     * Génère un acte de décès pour une seule déclaration
     */
    public function genererActe(DeclarationDeces $declaration, $registre, $user)
    {
        $acteDeces = new ActeDeces();
        $acteDeces->code_acte_deces = Sifec::genererCodeUniqueReferentiel($acteDeces, "code_acte_deces", 8, "AD_");
        $acteDeces->date_emission = now();
        $acteDeces->code_declaration_deces = $declaration->code_declaration_deces;
        $acteDeces->code_registre = $registre->code_registre;
        $acteDeces->cui = $user->affectationActive()->cui;
        $acteDeces->code_institution = $user->affectationActive()->code_institution;
        $acteDeces->approbation_tribunal = 1;
        $acteDeces->sceau_tribunal = $user->affectationActive()->institution->institutionParent->sceau;
        $acteDeces->save();

        // Mise à jour du registre
        $position = $registre->nombre_acte_transcrit + 1;
        $registre->nombre_acte_transcrit = $position;
        if ($position == $registre->nombre_acte_prevu) {
            $registre->statut = 0;
        }
        $registre->save();

        // Création du feuillet
        $feuillet = new FeuilletRegistre;
        $feuillet->code_feuillet_registre = Sifec::genererCodeUniqueReferentiel($feuillet, "code_feuillet_registre", 4, "FRE_");
        $feuillet->code_acte = $acteDeces->code_acte_deces;
        $feuillet->numero_acte = SifecFacade::generate_acte_number($registre, $position);
        $feuillet->save();

        return $acteDeces;
    }

    /**
     * Génère des actes de décès en masse pour plusieurs déclarations
     */
    public function genererActeBulk($declarations, $registre, $user)
    {
        DB::beginTransaction();
        try {
            $actesGeneres = collect();

            foreach ($declarations as $declaration) {
                $acte = $this->genererActe($declaration, $registre, $user);
                $actesGeneres->push($acte);
            }

            DB::commit();
            return $actesGeneres;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


}
