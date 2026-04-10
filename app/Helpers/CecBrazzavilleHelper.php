<?php

namespace App\Helpers;

use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\Localite;

/**
 * Règle métier : les mairies (CEC) sous la commune de Brazzaville ne gèrent pas le parcours décès dans SIFEC.
 */
final class CecBrazzavilleHelper
{
    /** Commune « Brazzaville » dans le référentiel (ex. sifec.sql). */
    public const CODE_COMMUNE_BRAZZAVILLE = 'LOC_0026';

    public static function mairieCecZoneBrazzavilleSansParcoursDeces(Institution $inst): bool
    {
        $inst->loadMissing(['typeInstitution', 'lieu']);

        if (($inst->typeInstitution?->code_type_institution) !== 'TPINS_0002') {
            return false;
        }

        return self::localiteRattacheeCommuneBrazzaville($inst->lieu);
    }

    public static function localiteRattacheeCommuneBrazzaville(?Localite $loc): bool
    {
        if ($loc === null) {
            return false;
        }

        $current = $loc;
        for ($i = 0; $i < 30 && $current !== null; $i++) {
            if ($current->code_localite === self::CODE_COMMUNE_BRAZZAVILLE
                && $current->code_type_localite === 'TPLOC_0003') {
                return true;
            }

            if ($current->code_type_localite === 'TPLOC_0003'
                && strtoupper(trim((string) $current->lib_localite)) === 'BRAZZAVILLE') {
                return true;
            }

            $current = $current->localiteParent;
        }

        return false;
    }
}
