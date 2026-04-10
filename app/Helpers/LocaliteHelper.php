<?php

namespace App\Helpers;

use Illuminate\Support\Collection;
use Modules\Referentiel\Entities\Localite;

class LocaliteHelper
{
    /**
     * Districts (TPLOC_0002) et communes (TPLOC_0003), non supprimés (soft delete).
     * Préférer cette méthode à where()->Orwhere() : même résultat SQL, plus lisible.
     */
    public static function communesEtDistricts(): Collection
    {
        $q = ['TPLOC_0002', 'TPLOC_0003'];

        $rows = Localite::query()
            ->whereIn('code_type_localite', $q)
            ->orderBy('lib_localite')
            ->get();

        if ($rows->isNotEmpty()) {
            return $rows;
        }

        return Localite::onlyTrashed()
            ->whereIn('code_type_localite', $q)
            ->orderBy('lib_localite')
            ->get();
    }

    /**
     * Arrondissements (TPLOC_0004) et communautés urbaines (TPLOC_0005).
     */
    public static function arrondissementsEtCommunautesUrbaines(): Collection
    {
        $q = ['TPLOC_0004', 'TPLOC_0005'];

        $rows = Localite::query()
            ->whereIn('code_type_localite', $q)
            ->orderBy('lib_localite')
            ->get();

        if ($rows->isNotEmpty()) {
            return $rows;
        }

        return Localite::onlyTrashed()
            ->whereIn('code_type_localite', $q)
            ->orderBy('lib_localite')
            ->get();
    }

    /**
     * Quartiers (TPLOC_0007) et villages (TPLOC_0008).
     */
    public static function quartiersEtVillages(): Collection
    {
        $q = ['TPLOC_0007', 'TPLOC_0008'];

        $rows = Localite::query()
            ->whereIn('code_type_localite', $q)
            ->orderBy('lib_localite')
            ->get();

        if ($rows->isNotEmpty()) {
            return $rows;
        }

        return Localite::onlyTrashed()
            ->whereIn('code_type_localite', $q)
            ->orderBy('lib_localite')
            ->get();
    }

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
