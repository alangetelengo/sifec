<?php

namespace Modules\Notification\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provider extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "tr_sms_providers";
    protected $primaryKey = "code_providers";
    public $incrementing = false;


    public function apiHeaders(): HasMany
    {
        return $this->hasMany(ApiHeader::class, 'code_providers', 'code_providers');
    }


    public function apiParams(): HasMany
    {
        return $this->hasMany(ApiParam::class, 'code_providers', 'code_providers');
    }
}
