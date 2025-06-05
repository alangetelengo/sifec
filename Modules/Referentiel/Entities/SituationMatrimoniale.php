<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SituationMatrimoniale extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "tr_situation_matrimoniale";
    protected $primaryKey = 'code_situation_matrimoniale';
    public $incrementing = false;
}
