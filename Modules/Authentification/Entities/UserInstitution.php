<?php

namespace Modules\Authentification\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserInstitution extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "tr_ins_user";
    protected $primaryKey = "cui";
    public $incrementing = false;
    
    
}
