<?php

namespace Modules\Naissance\Services;

use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Modules\Naissance\Entities\CompteurNiuppNaissance;
use Modules\Naissance\Entities\Declarationnaissance;

class NiuppNaissanceCounter
{
    /**
     * Prochain ordre séquentiel (par CEC, année et mois de naissance de l'enfant), verrouillé en transaction.
     */
    public function allocateNextOrdre(string $codeInstitution, Declarationnaissance $dn): int
    {
        $birth = Carbon::parse($dn->enfant->date_naissance);
        $annee = (int) $birth->year;
        $mois = (int) $birth->format('n');

        return (int) DB::transaction(function () use ($codeInstitution, $annee, $mois) {
            $row = CompteurNiuppNaissance::query()
                ->where('code_institution', $codeInstitution)
                ->where('annee', $annee)
                ->where('mois', $mois)
                ->lockForUpdate()
                ->first();

            if ($row) {
                $row->increment('dernier_ordre');

                return $row->fresh()->dernier_ordre;
            }

            try {
                CompteurNiuppNaissance::query()->create([
                    'code_institution' => $codeInstitution,
                    'annee' => $annee,
                    'mois' => $mois,
                    'dernier_ordre' => 1,
                ]);

                return 1;
            } catch (QueryException $e) {
                if (($e->errorInfo[1] ?? null) !== 1062) {
                    throw $e;
                }
                $row = CompteurNiuppNaissance::query()
                    ->where('code_institution', $codeInstitution)
                    ->where('annee', $annee)
                    ->where('mois', $mois)
                    ->lockForUpdate()
                    ->firstOrFail();
                $row->increment('dernier_ordre');

                return $row->fresh()->dernier_ordre;
            }
        });
    }
}
