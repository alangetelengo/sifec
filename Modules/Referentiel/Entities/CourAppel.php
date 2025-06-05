<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourAppel extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "tr_cour_appel";
    protected $primaryKey = "code_cour_appel";
    public $incrementing = false;


    public function tribunaux(): HasMany
    {
        return $this->hasMany(Tribunal::class, 'code_cour_appel', 'code_cour_appel');
    }


}
