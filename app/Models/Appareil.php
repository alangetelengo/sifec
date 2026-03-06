<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Referentiel\Entities\Institution;

class Appareil extends Model
{
    use SoftDeletes;

    protected $table = 'tr_appareils';
    protected $primaryKey = 'code_appareil';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'code_appareil',
        'adresse_mac',
        'nom_appareil',
        'type_appareil',
        'code_institution',
        'enregistre_par',
        'statut',
        'date_enregistrement',
    ];

    protected $casts = [
        'statut' => 'boolean',
        'date_enregistrement' => 'datetime',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class, 'code_institution', 'code_institution');
    }

    public function enregistrePar()
    {
        return $this->belongsTo(\App\Models\InstitutionUser::class, 'enregistre_par', 'cui');
    }

    /**
     * Vérifie si une adresse MAC est autorisée (appareil actif dans le système).
     */
    public static function estAutorise(string $adresseMac): bool
    {
        return static::where('adresse_mac', $adresseMac)
                     ->where('statut', true)
                     ->whereNull('deleted_at')
                     ->exists();
    }

    /**
     * Retourne l'appareil correspondant à une adresse MAC, ou null.
     */
    public static function trouverParMac(string $adresseMac): ?self
    {
        return static::where('adresse_mac', $adresseMac)
                     ->where('statut', true)
                     ->first();
    }
}
