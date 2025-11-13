<?php

namespace App\Helpers;

use Illuminate\Support\Collection;

class LocaliteHelper
{
    /**
     * Récupère toutes les localités d'un type donné (par code_type_localite)
     * @param string $codeTypeLocalite
     * @return \Illuminate\Support\Collection
     */
    public static function getLocalitesByType($codeTypeLocalite)
    {
        return \Modules\Referentiel\Entities\Localite::where('code_type_localite', $codeTypeLocalite)->get();
    }

    /**
     * Retourne un tableau clé/valeur (code_localite => lib_localite) pour un type de localité donné
     * @param string $codeTypeLocalite
     * @return array
     */
    public static function getLocalitesDropdown($codeTypeLocalite)
    {
        return \Modules\Referentiel\Entities\Localite::where('code_type_localite', $codeTypeLocalite)
            ->pluck('lib_localite', 'code_localite')
            ->toArray();
    }

    /**
     * Récupère tous les départements (code_type_localite = 'TPLOC_0001')
     * @return \Illuminate\Support\Collection
     */
    public static function getDepartements()
    {
        return \Modules\Referentiel\Entities\Localite::where('code_type_localite', 'TPLOC_0001')->get();
    }

    /**
     * Retourne un tableau clé/valeur (code_localite => lib_localite) pour les départements
     * @return array
     */
    public static function getDepartementsDropdown()
    {
        return \Modules\Referentiel\Entities\Localite::where('code_type_localite', 'TPLOC_0001')
            ->pluck('lib_localite', 'code_localite')
            ->toArray();
    }

    /**
     * Remonte la hiérarchie pour trouver la localité d'un type donné (par code_type_localite)
     * @param string $code_localite
     * @param string $codeTypeLocalite (ex: 'TPLOC_0003' pour COMMUNE)
     * @return Localite|null
     */
    public static function getLocaliteByType($code_localite, $codeTypeLocalite)
    {
        $localite = \Modules\Referentiel\Entities\Localite::find($code_localite);
        while ($localite) {
            if ($localite->code_type_localite === $codeTypeLocalite) {
                return $localite;
            }
            $localite = $localite->localiteParent;
        }
        return null;
    }
}
