<?php

namespace Modules\Naissance\Services;

use Exception;
use App\Sifec\Sifec;
use App\Sifec\SifecFacade;
use Illuminate\Support\Facades\DB;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Referentiel\Entities\FeuilletRegistre;
use Modules\Naissance\Entities\Declarationnaissance;
use Modules\Naissance\Services\MouvementService;

class ActeNaissanceService
{
    public function genererActe(Declarationnaissance $declaration, $registre, $user)
    {
        DB::beginTransaction();
        try {
            $niupp = Sifec::genererNiupp($declaration->code_declaration_naissance);
            $acteNaissance = new ActeNaissance();
            $acteNaissance->niupp = $niupp;
            $acteNaissance->date_emission = now();
            $acteNaissance->code_declaration_naissance = $declaration->code_declaration_naissance;
            $acteNaissance->code_registre = $registre->code_registre;
            $acteNaissance->cui = $user->affectationActive()->cui;
            $acteNaissance->code_institution = $user->affectationActive()->code_institution;
            $acteNaissance->approbation_tribunal = 1;
            $acteNaissance->sceau_tribunal = $user->affectationActive()->institution->institutionParent->sceau ?? null;
            $acteNaissance->save();


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
            $feuillet->code_acte = $acteNaissance->niupp;
            $feuillet->numero_acte =  SifecFacade::generate_acte_number($registre, $position);
            $feuillet->save();

            DB::commit();
            return $acteNaissance;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
