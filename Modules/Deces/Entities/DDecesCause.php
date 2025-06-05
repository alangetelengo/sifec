<?php

namespace Modules\Deces\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Referentiel\Entities\CauseDeces;

class DDecesCause extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected $guarded = [];
    protected $table = "t_ddecescause";
    public $incrementing = false;

    /**
     * Get the declaration that owns the DDecesCause
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function declaration(): BelongsTo
    {
        return $this->belongsTo(DeclarationDeces::class, 'code_declaration_deces', 'code_declaration_deces');
    }

    /**
     * Get the cause that owns the DDecesCause
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function causeDeces(): BelongsTo
    {
        return $this->belongsTo(CauseDeces::class, 'code_cause_deces', 'code_cause_deces');
    }
}
