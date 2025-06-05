<?php

namespace Modules\Authentification\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Referentiel\Entities\Fonction;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Fonctionnalite extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "tr_fonctionnalite";
    protected $primaryKey = "code_fonctionnalite";
    public $incrementing = false;


    /**
     * Get the module that owns the Fonctionnalite
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'code_module', 'code_module');
    }

    /**
     * The roles that belong to the Fonctionnalite
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function fonctions(): BelongsToMany
    {
        return $this->belongsToMany(Fonction::class, 'tr_ff', 'code_foncationnalite', 'code_fonction');
    }

    /**
     * Get the parent that owns the Fonctionnalite
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Fonctionnalite::class, 'code_foncationnalite_parent', 'code_foncationnalite');
    }

    /**
     * Get all of the fonctionnalites for the Fonctionnalite
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fonctionnalites(): HasMany
    {
        return $this->hasMany(Fonctionnalite::class, 'code_foncationnalite_parent', 'code_foncationnalite');
    }

}
