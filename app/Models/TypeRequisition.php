<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeRequisition extends Model
{
    use HasFactory;
    protected $table = "tr_type_requisition";
    // protected $fillable = ["code_type_requisition","lib_type_requisition"];
    protected $guarded = [];
    protected $primaryKey = "code_type_requisition";
    public $incrementing = false;

}
