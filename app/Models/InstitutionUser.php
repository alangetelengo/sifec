<?php

namespace App\Models;

use App\Models\User;
use App\Models\Jugement;
use App\Models\Requisition;
use Modules\Deces\Entities\ActeDeces;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Modules\Mobile\Entities\Tarificatrion;
use Modules\Referentiel\Entities\Fonction;
use Modules\Referentiel\Entities\Localite;
use Modules\Referentiel\Entities\Registre;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Deces\Entities\UserPompeFunebre;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Referentiel\Entities\Institution;
use Modules\Mariage\Entities\DeclarationMariage;
use Modules\Referentiel\Entities\Arrondissement;
use Modules\Naissance\Entities\MouvementNaissance;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Naissance\Entities\Declarationnaissance;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class InstitutionUser extends Model
{
    use HasFactory,Notifiable;

    protected $guarded = [];
    protected $table="tr_ins_user";
    protected $primaryKey="cui";
    public $incrementing = false;

    protected $casts = [
        'active' => 'boolean',
    ];



    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'code_institution', 'code_institution');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'code_user', 'code_user');
    }

    public function fonction(): BelongsTo
    {
        return $this->belongsTo(Fonction::class, 'code_fonction', 'code_fonction');
    }

    public function declarationNaissances(): HasMany
    {
        return $this->hasMany(Declarationnaissance::class, 'code_user_institution', 'cui');
    }

    public function declarationDeces(): HasMany
    {
        return $this->hasMany(DeclarationDeces::class, 'code_user_institution', 'cui');
    }


    public function mouvementNaissances(): HasMany
    {
        return $this->hasMany(MouvementNaissance::class, 'cui', 'cui');
    }


    public function acteNaissances(): HasMany
    {
        return $this->hasMany(ActeNaissance::class, 'cui', 'cui');
    }

    public function acteDeces(): HasMany
    {
        return $this->hasMany(ActeDeces::class, 'cui', 'cui');
    }

    public function registres(): HasMany
    {
        return $this->hasMany(Registre::class, 'cui', 'cui');
    }


    public function tarifications(): HasMany
    {
        return $this->hasMany(Tarificatrion::class, 'code_institution', 'code_institution');
    }


    public function arrondissements(): BelongsToMany
    {
        return $this->belongsToMany(Arrondissement::class, 't_user_arrondissement', 'cui', 'code_arrondissement');
    }


    public function declarationMariages(): HasMany
    {
        return $this->hasMany(DeclarationMariage::class, 'cui', 'cui');
    }

    public function userPompeFunebres(): HasMany
    {
        return $this->hasMany(UserPompeFunebre::class, 'cui', 'cui');
    }

    //associer user pompe funebre traitant les declarations de deces de chaque localite
    public function associate($localites)
    {
        $attached = 0;
        if($localites->count() > 0){

            foreach($localites as $loc){
                $userpf = UserPompeFunebre::where("cui",$this->cui)->where("code_localite",$loc->code_localite)->first();
                if($userpf == null){
                    UserPompeFunebre::create([
                        "cui"=>$this->cui,
                        "code_localite"=>$loc->code_localite
                    ]);
                    $attached+= 1;
                }
            }
        }
        return ["attached"=>$attached];
    }

    //admin Maire
    public function responsable($codeInst)
    {
        return $this->where("code_fonction","FONC_0002")->where("code_institution",$codeInst)->first()->user->personne->nomcomplet();
    }


    public function jugements(): HasMany
    {
        return $this->hasMany(Jugement::class, 'cui', 'cui');
    }

    public function requisitions(): HasMany
    {
        return $this->hasMany(Requisition::class, 'cui', 'cui');
    }
}
