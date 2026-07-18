<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuotSignelecConfig extends Model
{
    public const CONFIG_ID = 1;

    protected $table = 't_guot_signelec_config';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $guarded = [];

    protected $casts = [
        'signataire_fonctions' => 'array',
    ];

    /**
     * Ligne unique de configuration (créée par migration).
     */
    public static function instance(): self
    {
        $defaults = config('sifec.guot.signataire_fonctions', ['FONC_0002']);

        return static::firstOrCreate(
            ['id' => self::CONFIG_ID],
            ['signataire_fonctions' => array_values($defaults)]
        );
    }

    /**
     * @return list<string>
     */
    public static function signataireFonctions(): array
    {
        $codes = static::instance()->signataire_fonctions;

        if (! is_array($codes) || $codes === []) {
            $codes = config('sifec.guot.signataire_fonctions', ['FONC_0002']);
        }

        return array_values(array_unique(array_filter(array_map('strval', $codes))));
    }
}
