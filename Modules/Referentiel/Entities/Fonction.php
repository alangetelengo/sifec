<?php

namespace Modules\Referentiel\Entities;

use App\Models\User;
use App\Models\InstitutionUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Authentification\Entities\Fonctionnalite;

class Fonction extends Model
{
    use HasFactory;
    protected $table = "tr_fonction";
    protected $primaryKey = "code_fonction";
    public $incrementing = false;
    protected $guarded = [];

    /**
     * Get all of the users for the Fonction
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usersInstitutions(): HasMany
    {
        return $this->hasMany(InstitutionUser::class, 'code_fonction', 'code_fonction');
    }

    public function users(){
        return $this->usersInstitutions->map->user->flatten();
    }

    //
    public function responsability(){
        return $this->users()->map->personne->flatten();
    }

    /**
     * The fonctionnalites that belong to the Fonction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function fonctionnalites(): BelongsToMany
    {
        return $this->belongsToMany(Fonctionnalite::class, 'tr_ff', 'code_fonction', 'code_fonctionnalite')->withTimestamps();
    }

}
