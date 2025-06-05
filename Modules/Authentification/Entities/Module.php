<?php

namespace Modules\Authentification\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Authentification\Entities\Fonctionnalite;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "tr_module";
    protected $primaryKey = "code_module";
    public $incrementing = false;

    /**
     * Get all of the fonctionnalites for the Module
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fonctionnalites(): HasMany
    {
        return $this->hasMany(Fonctionnalite::class, 'code_module', 'code_module');
    }
}
