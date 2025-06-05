<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RetraitActe extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "t_retrait_acte";
    protected $primaryKey = "code_retrait_acte";
    public $incrementing = false;

}
