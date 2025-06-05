<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Localisation extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $table="t_localisation";
    protected $primaryKey="code_localisation";
    public $incrementing = false;


    public function departement(): BelongsTo
    {
        return $this->belongsTo(User::class, 'code_departement', 'code_departement');
    }

}
