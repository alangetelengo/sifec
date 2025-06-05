<?php

namespace Modules\Rectification\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Rectification\Entities\Rectification;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailRectification extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = "t_detail_rectification";
    protected $primaryKey = "code_detail_rectification";
    public $incrementing = false;


    public function rectification(): BelongsTo
    {
        return $this->belongsTo(Rectification::class, 'code_rectification', 'code_rectification');
    }

    public function rubrique(): BelongsTo
    {
        return $this->belongsTo(Rubrique::class, 'code_rubrique', 'code_rubrique');
    }


}
