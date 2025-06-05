<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Referentiel\Entities\Document;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypeDocument extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "tr_type_document";
    protected $primaryKey = "code_type_document";
    public $incrementing = false;

    /**
     * Get all of the documents for the TypeDocument
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'code_type_document', 'code_type_document');
    }

}
