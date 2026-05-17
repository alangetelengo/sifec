<?php

namespace Modules\Naissance\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Naissance\Entities\Declarationnaissance;

class ActeNaissanceService
{
    public function genererActe(Declarationnaissance $declaration, $registre, $user)
    {
        DB::beginTransaction();
        try {
            $acteNaissance = new ActeNaissance();
            $acteNaissance->code_acte_naissance = (string) Str::uuid();
            $acteNaissance->niupp = null;
            $acteNaissance->date_emission = now();
            $acteNaissance->code_declaration_naissance = $declaration->code_declaration_naissance;
            $acteNaissance->code_registre = $registre->code_registre;
            $acteNaissance->cui = $user->affectationActive()->cui;
            $acteNaissance->code_institution = $user->affectationActive()->code_institution;
            $acteNaissance->approbation_tribunal = 1;
            $acteNaissance->sceau_tribunal = $user->affectationActive()->institution->institutionParent->sceau ?? null;
            $acteNaissance->save();

            DB::commit();

            return $acteNaissance;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Génère un nouvel acte portant la mention "ANNULÉ" suite à un jugement d'annulation
     * 
     * @param Declarationnaissance $declaration La déclaration liée au nouvel acte
     * @param ActeNaissance $ancienActe L'acte qui a été annulé
     * @param $registre Le registre dans lequel inscrire le nouvel acte
     * @param $user L'utilisateur qui crée l'acte
     * @param $jugement Le jugement d'annulation
     * @return ActeNaissance Le nouvel acte créé avec mention d'annulation
     */
    public function genererActeAnnule(Declarationnaissance $declaration, ActeNaissance $ancienActe, $registre, $user, $jugement)
    {
        DB::beginTransaction();
        try {
            // Créer le nouvel acte basé sur les mêmes données que l'ancien
            $nouvelActe = new ActeNaissance();
            $nouvelActe->code_acte_naissance = (string) Str::uuid();
            $nouvelActe->niupp = null; // Sera attribué lors de la signature
            $nouvelActe->date_emission = now();
            $nouvelActe->code_declaration_naissance = $declaration->code_declaration_naissance;
            $nouvelActe->code_registre = $registre->code_registre;
            $nouvelActe->cui = $user->affectationActive()->cui;
            $nouvelActe->code_institution = $user->affectationActive()->code_institution;
            $nouvelActe->approbation_tribunal = 1;
            $nouvelActe->sceau_tribunal = $user->affectationActive()->institution->institutionParent->sceau ?? null;
            
            // Marquer que cet acte est une annulation
            $nouvelActe->est_acte_annulation = true;
            $nouvelActe->code_acte_annule = $ancienActe->code_acte_naissance;
            $nouvelActe->niupp_acte_annule = $ancienActe->niupp;
            
            $nouvelActe->save();

            DB::commit();

            return $nouvelActe;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
