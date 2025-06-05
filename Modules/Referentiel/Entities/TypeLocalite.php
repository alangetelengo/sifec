<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypeLocalite extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "tr_type_localite";
    protected $primaryKey = "code_type_localite";
    public $incrementing = false;


    public function localites(): HasMany
    {
        return $this->hasMany(Localite::class, 'code_type_localite', 'code_type_localite');
    }
}
