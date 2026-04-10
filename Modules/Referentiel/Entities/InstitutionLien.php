<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstitutionLien extends Model
{
    protected $table = 'tr_institution_lien';

    protected $fillable = [
        'code_institution_source',
        'code_institution_cible',
        'code_type_lien',
        'date_debut',
        'date_fin',
        'commentaire',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    public function institutionSource(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'code_institution_source', 'code_institution');
    }

    public function institutionCible(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'code_institution_cible', 'code_institution');
    }

    public function typeLien(): BelongsTo
    {
        return $this->belongsTo(TypeLienInstitution::class, 'code_type_lien', 'code_type_lien');
    }
}
