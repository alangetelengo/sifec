<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactPersonne extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [];

    protected $table = "t_contact_personne";
     protected $primaryKey="id";
     public $incrementing = true;

 
    public function personne(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'code_personne', 'code_personne');
    }

    /**
     * E-mails valides pour notifier le déclarant (professionnel en priorité, puis personnel — sans doublon).
     *
     * @return list<string>
     */
    public function adressesEmailPourNotification(): array
    {
        $out = [];
        foreach ([$this->email_professionnelle, $this->email_personnelle] as $e) {
            $e = trim((string) $e);
            if ($e !== '' && filter_var($e, FILTER_VALIDATE_EMAIL)) {
                $out[$e] = true;
            }
        }

        return array_keys($out);
    }
}
