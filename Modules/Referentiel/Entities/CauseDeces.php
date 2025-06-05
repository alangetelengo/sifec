<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Deces\Entities\DeclarationDeces;

class CauseDeces extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "tr_cause_deces";
    protected $primaryKey = "code_cause_deces";
    public $incrementing = false;


    public function declarationDeces(): HasMany
    {
        return $this->hasMany(DeclarationDeces::class, 'code_cause_deces', 'code_cause_deces');
    }

}
