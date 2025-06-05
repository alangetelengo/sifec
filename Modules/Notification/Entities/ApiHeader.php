<?php

namespace Modules\Notification\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApiHeader extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "t_api_headers";
    protected $primaryKey = "code_api_headers";
    public $incrementing = false;


    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'code_providers', 'code_providers');
    }
}
