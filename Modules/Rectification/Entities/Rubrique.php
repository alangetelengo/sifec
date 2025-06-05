<?php

namespace Modules\Rectification\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rubrique extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "tr_rubrique";
    protected $primaryKey = "code_rubrique";
    public $incrementing = false;
}
