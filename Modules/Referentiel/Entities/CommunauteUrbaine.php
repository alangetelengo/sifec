<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Referentiel\Entities\Institution;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommunauteUrbaine extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "tr_communaute_urbaine";
    protected $primaryKey = "code_communaute_urbaine";
    public $incrementing = false;


    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'code_district', 'code_district');
    }



    public function quartiers(): HasMany
    {
        return $this->hasMany(Quartier::class, 'code_communaute_urbaine', 'code_communaute_urbaine');
    }
    
    public function institutions(): HasMany
    {
        return $this->hasMany(Institution::class, 'code_communaute_urbaine', 'code_communaute_urbaine');
    }
}
