<?php

namespace Modules\Referentiel\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Referentiel\Entities\Commune;
use Modules\Referentiel\Entities\District;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Mariage\Entities\DeclarationMariage;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Naissance\Entities\Declarationnaissance;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Personne extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "tr_identification_personne";
    protected $primaryKey = "code_personne";
    public $incrementing = false;


    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'code_personne', 'code_personne');
    }

    public function localite(): BelongsTo
    {
        return $this->belongsTo(Localite::class, 'code_localite', 'code_localite');
    }

    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class, 'code_commune', 'code_commune');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'code_district', 'code_district');
    }

    /**
     * Get the nationalite that owns the Personne
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nationalite(): BelongsTo
    {
        return $this->belongsTo(Nationalite::class, 'code_nationalite', 'code_nationalite');
    }

    public function document(): HasOne
    {
        return $this->hasOne(Document::class, 'code_personne', 'code_personne');
    }


    public function profession(): BelongsTo
    {
        return $this->belongsTo(Profession::class, 'code_profession', 'code_profession');
    }

    public function nomcomplet(){
        return $this->nom ." ".$this->prenom;
    }


    public function declarationNaissance(): HasOne
    {
        return $this->hasOne(Declarationnaissance::class, 'code_enfant', 'code_personne');
    }

    public function declarationMariageEpoux(): HasOne
    {
        return $this->hasOne(DeclarationMariage::class, 'code_epoux', 'code_personne');
    }
    public function declarationMariageEpouse(): HasOne
    {
        return $this->hasOne(DeclarationMariage::class, 'code_epouse', 'code_personne');
    }

    public function declarationDeces(): HasOne
    {
        return $this->hasOne(DeclarationDeces::class, 'code_defunt', 'code_personne');
    }

    public function adresses():HasMany{
        return $this->hasMany(AdressePersonne::class, "code_personne", "code_personne");
    }


    public function contacts(): HasMany
    {
        return $this->hasMany(ContactPersonne::class, 'code_personne', 'code_personne');
    }

    public function telephone()
    {
        return $this->contacts->first()->indicatif.$this->contacts->first()->telephone;
    }

   
    public function dernierAdresse()
    {
        if ($this->adresses->last()->quartier != null) {
            $qv = $this->adresses->last()->quartier->lib_quartier;
        }elseif($this->adresses->last()->village != null){
            $qv = $this->adresses->last()->village->lib_village;
        }else{
            $qv = '';
        }

        // $qv = $this->adresses->last()->quartier->lib_quartier ?? $this->adresses->last()->village->lib_village;
        $localite_exterieure = $this->adresses->last()->lib_ville." ".$this->adresses->last()->lib_pays;
        if($qv != ""){
            return $this->adresses->last()->numero_rue.",".$this->adresses->last()->type_voie." ".$this->adresses->last()->nom_voie." ".$qv;
        }else{
            return $this->adresses->last()->numero_rue.",".$this->adresses->last()->type_voie." ".$this->adresses->last()->nom_voie." ".$localite_exterieure;

        }

    }
}
