<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Referentiel\Entities\District;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Village extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "tr_village";
    protected $primaryKey = "code_village";
    public $incrementing = false;


    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'code_district', 'code_district');
    }

    public function communauteUrbaine(): BelongsTo
    {
        return $this->belongsTo(CommunauteUrbaine::class, 'code_communaute_urbaine', 'code_communaute_urbaine');
    }
}
