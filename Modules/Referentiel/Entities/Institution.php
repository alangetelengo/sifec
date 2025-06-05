<?php

namespace Modules\Referentiel\Entities;

use App\Models\User;
use App\Models\Jugement;
use App\Models\Requisition;
use App\Models\InstitutionUser;
use Illuminate\Database\Eloquent\Model;
use Modules\Rectification\Entities\Rectification;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Naissance\Entities\Declarationnaissance;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;


class Institution extends Model
{
    use HasFactory;
    use HasRecursiveRelationships;
    protected $table = "tr_institution";
    protected $primaryKey = "code_institution";
    public $incrementing = false;


    public function typeInstitution(): BelongsTo
    {
        return $this->belongsTo(TypeInstitution::class, 'code_type_institution', 'code_type_institution');
    }

    public function institutionParent(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'code_institution_parent','code_institution');
    }


    public function lieu(): BelongsTo
    {
        return $this->belongsTo(Localite::class, 'code_localite', 'code_localite');
    }


    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'code_institution', 'code_institution');
    }


    public function getParentKeyName()
    {
        return 'code_institution_parent';
    }

    public function descendants(){
        return $this->descendantsAndSelf()->depthFirst()->get();
    }

    //debut à supprimer
    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class, 'code_commune', 'code_commune');
    }
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'code_district', 'code_district');
    }
    public function arrondissement(): BelongsTo
    {
        return $this->belongsTo(Arrondissement::class, 'code_arrondissement', 'code_arrondissement');
    }
    public function communauteUrbaine(): BelongsTo
    {
        return $this->belongsTo(CommunauteUrbaine::class, 'code_communaute_urbaine', 'code_communaute_urbaine');
    }
    //fin à supprimer



    public function institutionsUsers(): HasMany
    {
        return $this->hasMany(InstitutionUser::class, 'code_institution', 'code_institution');
    }

    public function declarationsNaissances(){
        return $this->institutionsUsers->map->declarationNaissances->flatten();
    }


    public function declarationsDeces(){
        return $this->institutionsUsers->map->declarationDeces->flatten();
    }

    public function pompeFunebre(): BelongsTo
    {
        return $this->belongsTo(PompeFunebre::class, 'code_pompe_funebre', 'code_pompes_funebres');
    }

    //Le nom du responsable du tribunal du ressort
    public function validateur()
    {
        return $this->institutionsUsers->map->fonction->map->responsability()->flatten()->first();
        // return $this->institutionsUsers->map->fonction->map->responsability();//A retravailler pour chercher la fonction qui seule peut faire l'action
    }

    public function telephone()
    {
        return $this->validateur()->contacts->first()->indicatif.$this->validateur()->contacts->first()->telephone;
    }

    public function declarationsMariages(){
        return $this->institutionsUsers->map->declarationMariages->flatten();
    }

    public function nomLocalite()
    {
        $locate = $this->commune ?? $this->district ?? $this->arrondissement ?? $this->communauteUrbaine;

        return $locate->lib_commune ?? $locate->lib_district ?? $locate->lib_arrondissement ?? $locate->lib_communaute_urbaine;
    }


    public function localite() //ville
    {
        return $this->commune ?? $this->district ?? $this->arrondissement ?? $this->communauteUrbaine;
    }

    public function departement()
    {
        return $this->localite()->departement ?? $this->localite()->commune->departement ?? $this->localite()->district->departement;
    }

    public function subDepartement()
    {
        return $this->departement()->communes->first() ?? $this->departement()->districts->first();
    }


    public function lalocalite(): BelongsTo
    {
        return $this->belongsTo(Localite::class, 'code_localite', 'code_localite');
    }


    //recuperer les registres d'une institution
    public function registres()
    {
        return $this->institutionsUsers->flatten()->map->registres->flatten();
    }

    //recuperer les jugements d'une institution (un tribunal)
    public function tousJugements(){
        return $this->institutionsUsers->map->jugements->flatten();
    }
    //recuperer les requisitions du tribunal
    public function tousRequisitions(){
        return $this->institutionsUsers->map->requisitions->flatten();
    }

    //les requisitions du centre d'état civil
    public function requisitions(): HasMany
    {
        return $this->hasMany(Requisition::class, 'code_institution','code_institution');
    }

    //les jugements du centre d'état civil lors d'enregistrement de certificat de non inscription de l'age de l'enfant > 90 jours sans déclarer
    public function jugements(): HasMany
    {
        return $this->hasMany(Jugement::class, 'code_institution','code_institution');
    }

    //recupération des rectifications du centre d'état civil
    public function rectifications(): HasMany
    {
        return $this->hasMany(Rectification::class, 'code_institution','code_institution');
    }
}
