<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Deces\Entities\DeclarationDeces;

class Religion extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $table = "tr_religion";
    protected $primaryKey = "code_religion";
    public $incrementing = false;

    /**
     * Get all of the declarationsDeces for the Religion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function declarationsDeces(): HasMany
    {
        return $this->hasMany(DeclarationDeces::class, 'code_religion', 'code_religion');
    }
}
