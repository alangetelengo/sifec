<?php

namespace Modules\Notification\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Canal extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "t_canal";
    protected $primaryKey = "code_canal";
    public $incrementing = false;
}
