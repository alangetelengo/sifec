<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Referentiel\Entities\TypeCategorieInstitution;

class TypeInstitution extends Model
{
    use HasFactory;
    protected $table = "tr_type_institution";
    protected $primaryKey = "code_type_institution";
    public $incrementing = false;


    public function institutions(): HasMany
    {
        return $this->hasMany(Institution::class, 'code_type_institution', 'code_type_institution');
    }

    public function typeCategorieInstitution(): BelongsTo
    {
        return $this->belongsTo(TypeCategorieInstitution::class, 'code_type_categorie_ins', 'code_type_categorie_ins');
    }
}
