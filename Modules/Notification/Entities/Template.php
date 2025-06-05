<?php

namespace Modules\Notification\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Template extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "t_sms_templates";
    protected $primaryKey = "code_template";
    public $incrementing = false;


    public function action(): BelongsTo
    {
        return $this->belongsTo(Action::class, 'code_action', 'code_action');
    }
}
