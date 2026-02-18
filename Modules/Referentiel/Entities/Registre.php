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
        // $ordre = substr($this->code_registre,4,8);
        $numero = $code.date("Y");
        return $numero;
    }

    

    public function validateur()
    {
        return $this->institutionUser->institution->institutionParent->institutionsUsers->flatten()->where("code_fonction","FONC_0009")->first()->user->personne;

    }

    /**
     * Génère le texte de paraphage du registre (intro de la page 1) selon le type de registre et l'institution.
     * Utilisable dans toutes les vues (naissance, décès, mariage).
     *
     * @param string $contexte 'naissance' | 'deces' | 'mariage' — détermine la formulation du type de registre et "pour le compte"
     * @return string HTML du paragraphe (à afficher avec {!! !!})
     */
    public function getTexteParapheRegistre(string $contexte = 'naissance'): string
    {
        $registre = $this->loadMissing([
            'typeRegistre',
            'institutionUser.institution.typeInstitution.typeCategorieInstitution',
            'institutionUser.institution.institutionParent',
            'signataire.user.personne',
        ]);

        $inst = $registre->institutionUser->institution;
        $typeReg = $registre->typeRegistre;
        $categorie = $inst->typeInstitution->typeCategorieInstitution->lib_type_categorie_institution ?? '';
        $libInstitution = $inst->lib_institution ?? '';
        $parentLib = optional($inst->institutionParent)->lib_institution ?? '';

        $n = (int) $registre->nombre_acte_prevu;
        $libType = $typeReg->lib_type_registre ?? '';

        if ($contexte === 'naissance') {
            $libTypeRegistre = 'registre d\'acte de ' . strtolower($libType);
            $pourLeCompte = 'du ' . strtolower($categorie) . ' de la <strong>' . e($libInstitution) . '</strong>';
            $dateAnnee = $registre->updated_at ? date('Y', strtotime($registre->updated_at)) : '';
            $dateCe = $registre->updated_at ? date('d-m-Y', strtotime($registre->updated_at)) : '';
        } elseif ($contexte === 'deces') {
            $libTypeRegistre = 'registre d\'acte de ' . strtolower($libType);
            if ($inst->typeInstitution && $inst->typeInstitution->code_type_institution === 'TPINS_0003') {
                $pourLeCompte = 'des <strong>' . e($libInstitution) . '</strong>';
            } else {
                $pourLeCompte = 'du ' . strtolower($categorie) . ' de la <strong>' . e($libInstitution) . '</strong>';
            }
            $dateAnnee = $registre->created_at ? date('Y', strtotime($registre->created_at)) : '';
            $dateCe = $registre->updated_at ? date('d-m-Y', strtotime($registre->updated_at)) : '';
        } else {
            $libTypeRegistre = 'registre d\'acte de ' . strtolower($libType);
            $pourLeCompte = 'du ' . strtolower($categorie) . ' de la <strong>' . e($libInstitution) . '</strong>';
            $dateAnnee = $registre->created_at ? date('Y', strtotime($registre->created_at)) : '';
            $dateCe = $registre->updated_at ? date('d-m-Y', strtotime($registre->updated_at)) : '';
        }

        $pdt = '';
        $sexep = '';
        $titre = 'Président';
        if ($registre->signataire && $registre->signataire->user && $registre->signataire->user->personne) {
            $pdt = e($registre->signataire->user->personne->nomcomplet());
            $sexep = $registre->signataire->user->personne->sexe ?? 'M';
            $titre = ($sexep === 'F') ? 'Présidente' : 'Président';
        }

        $s = 'Ce présent registre contenant <strong>' . $n . '</strong> feuillets devant servir de <strong> ' . $libTypeRegistre . '</strong>';
        $s .= ' en <strong>' . $dateAnnee . '</strong> pour le compte ' . $pourLeCompte;
        $s .= ', a été coté et paraphé par nous, <strong>' . $pdt . '</strong>, ' . $titre . '  du <strong> ' . e($parentLib) . ' </strong>';
        $s .= ', ce <strong>' . $dateCe . '</strong>. <br> <br>';
        $s .= "Le registre sera clôturé et arrêté le 31 Décembre par l'officier de l'état-civil.";

        return $s;
    }
}
