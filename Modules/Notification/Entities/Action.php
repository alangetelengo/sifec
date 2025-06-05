<?php

namespace Modules\Notification\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Action extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "t_action";
    protected $primaryKey = "code_action";
    public $incrementing = false;


}
