<?php

namespace Modules\Mariage\Services;

use Exception;
use App\Sifec\Sifec;
use App\Sifec\SifecFacade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Modules\Mariage\Entities\Signature;
use Modules\Mariage\Entities\ActeMariage;
use Modules\Referentiel\Entities\Registre;
use Modules\Mariage\Entities\MouvementMariage;
use Modules\Mariage\Entities\DeclarationMariage;
use Modules\Referentiel\Entities\FeuilletRegistre;

class ActeMariageService
{


    /**
     * Obtenir un acte par code de déclaration
     */
    public function obtenirActeParDeclaration($codeDeclaration)
    {
        return ActeMariage::where("code_declaration_mariage", $codeDeclaration)->first();
    }

    /**
     * Rechercher un acte avec ses relations
     */
    public function rechercherActe($codeActe)
    {
        return ActeMariage::with([
            "declaration.optionMariage",
            "declaration.epoux",
            "declaration.epouse",
            "declaration.institution",
            "declaration.institution.institutionParent"
        ])->where("code_acte_mariage", $codeActe)->first();
    }

    /**
     * Générer un acte de mariage
     */
    public function genererActe(DeclarationMariage $declaration, $registre, $user)
    {
        DB::beginTransaction();
        try {
            $acteMariage = new ActeMariage();
            $codeActe = Sifec::genererCodeUniqueReferentiel($acteMariage, "code_acte_mariage", 8, "AM_");
            $acteMariage->code_acte_mariage = $codeActe;
            $acteMariage->date_emission = now();
            $acteMariage->code_declaration_mariage = $declaration->code_declaration_mariage;
            $acteMariage->code_registre = $registre->code_registre;
            $acteMariage->cui = $user->affectationActive()->cui;
            $acteMariage->code_institution = $user->affectationActive()->code_institution;
            $acteMariage->approbation_tribunal = 1;
            $acteMariage->sceau_tribunal = $user->affectationActive()->institution->institutionParent->sceau ?? null;
            $acteMariage->save();

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
            $feuillet->code_acte = $acteMariage->code_acte_mariage;
            $feuillet->numero_acte = SifecFacade::generate_acte_number($registre, $position);
            $feuillet->save();

            // Création de la signature
            $signature = new Signature;
            $signature->code_signature_mariage = Sifec::genererCodeUniqueReferentiel($signature, "code_signature_mariage", 4, "CSM_");
            $signature->code_declaration_mariage = $acteMariage->code_declaration_mariage;
            $signature->save();

            DB::commit();
            return $acteMariage;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}
