<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Mobile\Entities\DemandeDocument;

class LieuSurvenance extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "tr_lieu_survenance";
    protected $primaryKey = "code_lieu_survenance";
    public $incrementing = false;



    public function demandeDocuments(): HasMany
    {
        return $this->hasMany(DemandeDocument::class, 'code_lieu_survenance', 'code_lieu_survenance');
    }
}
