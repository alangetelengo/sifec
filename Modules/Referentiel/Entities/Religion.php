<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Religion extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "tr_religion";
    protected $primaryKey = "code_religion";
    public $incrementing = false;
}
