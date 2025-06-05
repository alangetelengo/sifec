<?php

namespace Modules\Mobile\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailDemandeDocument extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "detail_demande_document";
    protected $primaryKey = "code_detail_demande_document";
    public $incrementing = false;

    /**
     * Get the demandeDocument that owns the DetailDemandeDocument
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function demandeDocument(): BelongsTo
    {
        return $this->belongsTo(DemandeDocument::class, 'code_demande_document', 'code_demande_document');
    }


}
