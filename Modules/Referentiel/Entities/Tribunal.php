<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tribunal extends Model
{
    use HasFactory;

    protected $table = "tr_tribunal";
    protected $primaryKey = "code_tribunal";
    public $incrementing = false;


    public function institutions(): HasMany
    {
        return $this->hasMany(Institution::class, 'code_tribunal', 'code_tribunal');
    }


    public function courAppel(): BelongsTo
    {
        return $this->belongsTo(CourAppel::class, 'code_cour_appel', 'code_cour_appel');
    }

}
