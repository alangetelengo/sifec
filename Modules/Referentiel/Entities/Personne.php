<?php

namespace Modules\Referentiel\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
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
    //ode_personne 	nom 	prenom 	sexe 	date_naissance 	lieu_naissance 	code_localite 	photo 	telephone 	telephone_parent 	adresse 	niveau_instruction 	code_nationalite 	code_profession 	signature 	personne_string 	type_adoption 	statut_personne 	type_date_naissance
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
        $nom = trim((string) ($this->nom ?? ''));
        $prenom = trim((string) ($this->prenom ?? ''));
        // Placeholders / tirets Unicode souvent saisis quand le prénom est inconnu
        if ($prenom === '' || in_array($prenom, ['—', '–', '-', 'N/A', 'n/a', 'XXXX', 'XXXXXXXXXXXXXXXX'], true)) {
            return $nom;
        }

        return trim($nom.' '.$prenom);
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

    /**
     * E-mails du premier enregistrement `t_contact_personne` (ex. déclarant).
     *
     * @return array<string, string> Libellé => adresse (ex. « Professionnel » => « …@… »)
     */
    public function emailsDepuisPremierContact(): array
    {
        $c = $this->contacts()->orderBy('id')->first();
        if (! $c) {
            return [];
        }
        $out = [];
        $pro = trim((string) $c->email_professionnelle);
        $perso = trim((string) $c->email_personnelle);
        if ($perso !== '') {
            $out['Personnel'] = $perso;
        }
        if ($pro !== '') {
            $out['Professionnel'] = $pro;
        }

        return $out;
    }

    /**
     * E-mails valides (pro + perso par contact) fusionnés sur toutes les fiches contact actives, tri par id, sans doublon.
     * À utiliser pour l’OTP officier lorsque plusieurs lignes existent dans t_contact_personne.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<int, ContactPersonne>|null  $contacts  Déjà chargées (ex. orderBy id) pour éviter une requête en double
     * @return list<string>
     */
    public function adressesEmailPourNotificationAgregees($contacts = null): array
    {
        $contacts = $contacts ?? $this->contacts()->orderBy('id')->get();
        $emails = [];
        foreach ($contacts as $contact) {
            foreach ($contact->adressesEmailPourNotification() as $email) {
                $emails[$email] = true;
            }
        }

        return array_keys($emails);
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
