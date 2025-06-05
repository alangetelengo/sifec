<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypeRegistre extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "tr_type_registre";
    protected $primaryKey = "code_type_registre";
    public $incrementing = false;


    public function registres(): HasMany
    {
        return $this->hasMany(Registre::class, 'code_type_registre', 'code_type_registre');
    }

}
