<?php

namespace App\Models;


use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Modules\Referentiel\Entities\Personne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Authentification\Entities\Fonctionnalite;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Deces\Entities\DeclarationDeces;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "tr_user";
    protected $primaryKey = "code_user";
    protected $guarded = [];
    public $incrementing = false;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function affectations(): HasMany
    {
        return $this->hasMany(InstitutionUser::class, 'code_user', 'code_user');
    }

    public function affectationActive(){
        return $this->affectations->where("active",1)->first();
    }

    public function fonction(){
        return $this->affectationActive()->fonction;
    }

    public function signateur($id){
        return $this->fonction()->where("code_fonction",$id)->first();
    }

    public function institution(){
        return $this->affectationActive()->institution;
    }

    public function personne(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'code_personne', 'code_personne');
    }


    public function fonctionnalites(): BelongsToMany
    {
        return $this->belongsToMany(Fonctionnalite::class, 'tr_uf', 'code_user', 'code_fonctionnalite');
    }

    public function toutesfonctionnalites(){
        return $this->fonctionnalites->merge($this->fonction()->fonctionnalites)->flatten();
    }

    public function modules(){
        return $this->toutesfonctionnalites()->map->module->flatten();
    }

    //recuperer les declarations de deces de chaque localite
    public function declarationDeces()
    {
       return $this->affectationActive()->userPompeFunebres->flatten()->map->localite->flatten()->map->declarationDeces()->flatten();
    }


    public function MyLocalites()
    {
       return implode(" , ", $this->affectationActive()->userPompeFunebres->map->localite->flatten()->pluck("lib_localite")->toArray());
    }


}
