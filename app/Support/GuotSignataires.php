<?php

namespace App\Support;

use App\Models\GuotSignelecConfig;
use Illuminate\Support\Facades\Schema;
use Modules\Referentiel\Entities\Fonction;

/**
 * Fonctions métier éligibles à l’enrôlement / signature électronique GUOT.
 *
 * Les agents de saisie (ex. FONC_0004) ne sont pas signataires :
 * seuls les responsables choisis par l’admin portent le certificat.
 */
final class GuotSignataires
{
    /**
     * Codes FONC_* configurés comme signataires GUOT (DB, sinon config).
     *
     * @return list<string>
     */
    public static function codes(): array
    {
        if (Schema::hasTable('t_guot_signelec_config')) {
            return GuotSignelecConfig::signataireFonctions();
        }

        $codes = config('sifec.guot.signataire_fonctions', ['FONC_0002']);

        return array_values(array_unique(array_filter(
            array_map('strval', is_array($codes) ? $codes : [])
        )));
    }

    public static function isSignataire(?string $codeFonction): bool
    {
        if ($codeFonction === null || $codeFonction === '') {
            return false;
        }

        return in_array($codeFonction, self::codes(), true);
    }

    /**
     * Description pour l’UI : libellés des fonctions cochées, sinon texte de config.
     */
    public static function description(): string
    {
        $codes = self::codes();
        if ($codes === []) {
            return 'Aucune fonction éligible (à paramétrer dans SIGNELEC).';
        }

        if (Schema::hasTable('tr_fonction')) {
            $labels = Fonction::query()
                ->whereIn('code_fonction', $codes)
                ->orderBy('lib_fonction')
                ->pluck('lib_fonction')
                ->filter()
                ->values()
                ->all();

            if ($labels !== []) {
                return implode(', ', $labels);
            }
        }

        return (string) config(
            'sifec.guot.signataire_description',
            'Officier d’état civil (et responsables assimilés).'
        );
    }
}
