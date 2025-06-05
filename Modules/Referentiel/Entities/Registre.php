<?php

namespace Modules\Referentiel\Entities;

use App\Models\InstitutionUser;
use Illuminate\Database\Eloquent\Model;
use Modules\Naissance\Entities\ActeNaissance;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Deces\Entities\ActeDeces;

class Registre extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "tr_registre";
    protected $primaryKey = "code_registre";
    public $incrementing = false;


    public function typeRegistre(): BelongsTo
    {
        return $this->belongsTo(TypeRegistre::class, 'code_type_registre', 'code_type_registre');
    }

    public function acteNaissances(): HasMany
    {
        return $this->hasMany(ActeNaissance::class, 'code_registre', 'code_registre');
    }

    public function acteDeces(): HasMany
    {
        return $this->hasMany(ActeDeces::class, 'code_registre', 'code_registre');
    }

    /**
     * Get the institutionUser that owns the Registre
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function institutionUser(): BelongsTo
    {
        return $this->belongsTo(InstitutionUser::class, 'cui', 'cui');
    }

    public function getcode(){
        $id = $this->identifiant_registre;
        $code = $this->code_registre;
        $cr = substr($code,4);
        // $s = "R.A.N_INS_000915022023203954";

        $idr = substr($id,0,6).$cr;
        // $idr = substr($id,0,6).$cr;
        return $idr;
    }


    // public function feuillets(): HasMany
    // {
    //     return $this->hasMany(FeuilletRegistre::class, 'code_registre', 'code_registre');
    // }

    public function numeroActe()
    {
        $id = $this->identifiant_registre;
        $code = $this->code_registre;
        $cr = substr($code,4);
        // $s = "2023_R.A.N_";

        $idr = substr($id,0,6).$cr;
        return $cr;
    }

    //signature du tribunal pour parapher et coter
    public function signataire(): BelongsTo
    {
        return $this->belongsTo(InstitutionUser::class, 'approbation_tribunal', 'cui');
    }

    /**
     * signature du cec pour clôturer le registre
     */
    public function signataireClose(): BelongsTo
    {
        return $this->belongsTo(InstitutionUser::class, 'cloture_cec', 'cui');
    }

    //le numéro du registre revisé pour les sms notifications

    public function numeroOrdreRegistre()
    {
        $code =  substr($this->identifiant_registre,0,5);
        $ordre = substr($this->code_registre,4,8);
        $numero = $code.$ordre;
        return $numero;
    }


    public function validateur()
    {
        return $this->institutionUser->institution->institutionParent->institutionsUsers->flatten()->where("code_fonction","FONC_0009")->first()->user->personne;

    }
}
