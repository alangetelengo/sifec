<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeCategorieInstitution extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "tr_type_categorie_ins";
    protected $primaryKey = "code_type_categorie_ins";
    public $incrementing = false;


    public function typeInstitutions(): HasMany
    {
        return $this->hasMany(TypeInstitution::class, 'code_type_categorie_ins', 'code_type_categorie_ins');
    }
    
}
