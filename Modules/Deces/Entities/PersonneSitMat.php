<?php

namespace Modules\Deces\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonneSitMat extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = "personne_situation_matrimo";
    protected $primaryKey = "code_personne";

    /**
     * Get all of the declarationdeces for the PersonneSitMat
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function declarationdeces(): HasMany
    {
        return $this->hasMany(DeclarationDeces::class, 'persone_code', 'code_personne');
    }
}
