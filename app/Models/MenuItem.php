<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    protected $table = 'tr_menu_item';

    protected $primaryKey = 'code_menu_item';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_group' => 'boolean',
            'visibility_hide_fonctions' => 'array',
            'visibility_show_only_fonctions' => 'array',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'code_parent', 'code_menu_item');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'code_parent', 'code_menu_item')->orderBy('sort_order');
    }
}
