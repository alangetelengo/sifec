<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Referentiel\Entities\Commune;
use Modules\Referentiel\Entities\District;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quartier extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    protected $table = "tr_quartier";
    protected $primaryKey = "code_quartier";
    public $incrementing = false;


    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'code_district', 'code_district');
    }

    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class, 'code_commune', 'code_commune');
    }

    public function communauteUrbaine(): BelongsTo
    {
        return $this->belongsTo(CommunauteUrbaine::class, 'code_communaute_urbaine', 'code_communaute_urbaine');
    }

    public function arrondissement(): BelongsTo
    {
        return $this->belongsTo(Arrondissement::class, 'code_arrondissement ', 'code_arrondissement ');
    }

    

}
