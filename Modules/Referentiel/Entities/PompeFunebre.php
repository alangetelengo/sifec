&&²<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Referentiel\Entities\Institution;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Deces\Entities\DeclarationDeces;

class PompeFunebre extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];
    protected $table = 'tr_pompes_funebres';
    protected $primaryKey = "code_pompes_funebres";
    public $incrementing = false;


    public function institutions(): HasMany
    {
        return $this->hasMany(Institution::class, 'code_pompe_funebre', 'code_pompes_funebres');
    }


    public function declarationDeces()
    {
        return $this->institutions->map->declarationsDeces()->flatten();
    }





}
