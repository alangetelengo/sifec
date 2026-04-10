<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeLienInstitution extends Model
{
    protected $table = 'tr_type_lien_institution';
    protected $primaryKey = 'code_type_lien';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'code_type_lien',
        'lib_type_lien',
        'description',
    ];

    /** Partenaire décès : source (pompe / émetteur) → cible (CEC). */
    public const CODE_PARTENAIRE_DECES_POMPE = 'TPLIEN_0001';

    public const CODE_TRIBUNAL_RESSORT = 'TPLIEN_0002';

    public const CODE_FORMATION_CEC_NAISSANCE = 'TPLIEN_0003';

    public function institutionLiens(): HasMany
    {
        return $this->hasMany(InstitutionLien::class, 'code_type_lien', 'code_type_lien');
    }
}
