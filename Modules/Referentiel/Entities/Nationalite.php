<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nationalite extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $table = "tr_nationalite";
    protected $primaryKey = "code_nationalite";
    public $incrementing = false;

    public function personnes(): HasMany
    {
        return $this->hasMany(Personne::class, 'code_nationalite', 'code_nationalite');
    }
}
