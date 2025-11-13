<?php

namespace Modules\Rectification\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MouvementRectification extends Model
{
    protected $table = 't_mouvement_rectification';
    protected $primaryKey = 'code_mouvement_rectification';
    public $incrementing = false;
    protected $guarded = [];

   

}
