<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RaisonRevocation extends Model
{
    use SoftDeletes;

    protected $table = 'tr_raison_revocation';

    protected $primaryKey = 'code_raison_revocation';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected $casts = [
        'actif' => 'boolean',
        'ordre' => 'integer',
    ];

    /**
     * Raisons actives pour les listes déroulantes.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, self>
     */
    public static function actives()
    {
        return static::query()
            ->where('actif', true)
            ->orderBy('ordre')
            ->orderBy('lib_raison_revocation')
            ->get();
    }
}
