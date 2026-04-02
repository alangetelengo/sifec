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
}
