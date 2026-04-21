<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandeDocumentConfig extends Model
{
    public const CONFIG_ID = 1;

    protected $table = 't_demande_document_config';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $guarded = [];

    /**
     * Ligne unique de configuration (créée par migration).
     */
    public static function instance(): self
    {
        $defaut = (int) config('sifec.demande_document.validite_mois_par_defaut', 3);

        return static::firstOrCreate(
            ['id' => self::CONFIG_ID],
            ['validite_document_mois' => max(1, min(120, $defaut))]
        );
    }

    /**
     * Durée de validité des documents signés (copie / extrait), en mois — paramétrable par l'admin.
     */
    public static function validiteEnMois(): int
    {
        $v = (int) static::instance()->validite_document_mois;

        return $v >= 1 && $v <= 120 ? $v : 3;
    }
}
