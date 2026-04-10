<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Modules\Referentiel\Entities\Institution;

/**
 * Rattachement des centres d'état civil (mairies) au ressort d'un TGI
 * via la hiérarchie parent → enfants déjà modélisée dans tr_institution.
 *
 * Ex. INS_0006 (TGI Brazzaville) englobe INS_0023 (TI), INS_0047 (Mairie Makelekele), etc.
 */
class RessortJudiciaireService
{
    /** Type institution « Mairie » (centre d'état civil) */
    public const CODE_TYPE_MAIRIE = 'TPINS_0002';

    /**
     * TGI de référence pour la démo / Brazzaville (données institutions.sql).
     */
    public const CODE_TGI_BRAZZAVILLE = 'INS_0006';

    /**
     * @return Collection<int, Institution> Mairies descendantes du TGI (sans inclure le TGI lui-même si non-mairie).
     */
    public function centresEtatCivilDuRessortTgi(?string $codeTgi = null): Collection
    {
        $codeTgi = $codeTgi ?? self::CODE_TGI_BRAZZAVILLE;
        $tgi = Institution::query()->whereKey($codeTgi)->first();
        if ($tgi === null) {
            return collect();
        }

        return $tgi->descendants()
            ->filter(fn (Institution $i) => $i->code_institution !== $codeTgi)
            ->filter(fn (Institution $i) => $i->code_type_institution === self::CODE_TYPE_MAIRIE)
            ->values();
    }

    /**
     * @return list<string>
     */
    public function codesInstitutionsCecDuRessort(?string $codeTgi = null): array
    {
        return $this->centresEtatCivilDuRessortTgi($codeTgi)->pluck('code_institution')->all();
    }

    /**
     * Indique si une institution donnée est une mairie du ressort du TGI.
     */
    public function institutionEstCecDuRessort(string $codeInstitution, ?string $codeTgi = null): bool
    {
        return in_array($codeInstitution, $this->codesInstitutionsCecDuRessort($codeTgi), true);
    }
}
