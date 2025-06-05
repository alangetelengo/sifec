<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profession extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "tr_profession";
    protected $primaryKey = "code_profession";
    public $incrementing = false;


    /**
     * Get all of the personnes for the Profession
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function personnes(): HasMany
    {
        return $this->hasMany(Personne::class, 'code_profession', 'code_profession');
    }
}
