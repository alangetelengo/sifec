<?php

namespace Modules\Referentiel\Entities;

use App\Models\MouvementDossier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mouvement extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table="tr_mouvement";
    protected $primaryKey="code_mouvement";
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'code_mouvement',
        'lib_mouvement',
        'description',
    ];

    public function mouvementsDossier()
    {
        return $this->hasMany(MouvementDossier::class, 'code_mouvement', 'code_mouvement');
    }
}
