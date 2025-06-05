<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Referentiel\Entities\Quartier;
use Modules\Referentiel\Entities\Institution;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commune extends Model
{
    use HasFactory;


    protected $guarded=[];
    protected $table="tr_commune";
    protected $primaryKey="code_commune";
    public $incrementing = false;


    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class, 'code_departement', 'code_departement');
    }


    public function arrondissements(): HasMany
    {
        return $this->hasMany(Arrondissement::class, 'code_commune', 'code_commune');
    }

    public function institutions(): HasMany
    {
        return $this->hasMany(Institution::class, 'code_commune', 'code_commune');
    }

    public function quartiers(): HasMany
    {
        return $this->hasMany(Quartier::class, 'code_commune', 'code_commune');
    }



}
