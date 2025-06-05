<?php

namespace Modules\Referentiel\Entities;

use App\Models\InstitutionUser;
use Illuminate\Database\Eloquent\Model;
use Modules\Deces\Entities\DeclarationDeces;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Arrondissement extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "tr_arrondissement";
    protected $primaryKey = "code_arrondissement";
    public $incrementing = false;


    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class, 'code_commune', 'code_commune');
    }


    public function quartiers(): HasMany
    {
        return $this->hasMany(Quartier::class, 'code_arrondissement', 'code_arrondissement');
    }
    
    public function institutions(): HasMany
    {
        return $this->hasMany(Institution::class, 'code_arrondissement', 'code_arrondissement');
    }

    /**
     * Get all of the adresses for the Arrondissement
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adresses(): HasMany
    {
        return $this->hasMany(AdressePersonne::class, 'code_arrondissement', 'code_arrondissement');
    }


    public function responsables(): BelongsToMany
    {
        return $this->belongsToMany(InstitutionUser::class, 't_user_arrondissement', 'code_arrondissement', 'cui');
    }


}
