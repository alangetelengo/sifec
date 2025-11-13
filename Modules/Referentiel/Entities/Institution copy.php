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
        return $this->belongsTo(Institution::class, 'code_pompe_funebre', 'code_institution');
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



    /**
     * Récupère les déclarations de naissance envoyées par les formations sanitaires
     * qui ont été validées par le centre d'état civil (cec_approuver = 'OUI')
     */
    public function getDeclarationsFormationSanitaireApprouvees()
    {
        $formationsSanitairesCodes = $this->descendants()
            ->filter(function($institution) {
                return $institution->typeInstitution &&
                    $institution->typeInstitution->code_type_categorie_ins === 'TCINS_0003';
            })
            ->pluck('code_institution')
            ->toArray();

        return Declarationnaissance::with(['enfant', 'declarant', 'pere', 'mere', 'mouvements', 'acte'])
            ->whereIn('code_institution', $formationsSanitairesCodes)
            ->where('type_declaration', 'DECLARATION DE NAISSANCE')
            ->where('cec_approuver', 'OUI')
            ->where('code_institution_destinataire', $this->code_institution)
            ->get();
    }



        /**
     * Récupère les déclarations de naissance du centre d’état civil courant
     * qui ont une réquisition ou un jugement envoyé par le tribunal,
     * approuvées par le tribunal (tribunal_approuver = 'OUI'),
     * et dont le mouvement 'MOUV_0011' (transfert au centre d'état civil) est présent.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getDeclarationsWithRequisitionsOrJugements()
    {
        return Declarationnaissance::with([
                'enfant',
                'declarant',
                'pere',
                'mere',
                'mouvements',
                'acte',
                'requisition',
                'jugement'
            ])
            ->where('code_institution', $this->code_institution)
            ->where('tribunal_approuver', 'OUI')
            ->where(function($query) {
                $query->whereHas('requisition')
                    ->orWhereHas('jugement');
            })
            ->whereHas('mouvements', function($query) {
                $query->where('code_mouvement', 'MOUV_0011');
            })
            ->get();
    }



    /**
     * Récupère les déclarations de naissance créées par le centre d'état civil
     * qui ont été validées (cec_approuver = 'OUI')
     */
    public function getDeclarationsCentreEtatCivilApprouvees()
    {
        return Declarationnaissance::with(['enfant', 'declarant', 'pere', 'mere', 'mouvements', 'acte'])
            ->where('code_institution', $this->code_institution)
            ->where('type_declaration', 'DECLARATION DE NAISSANCE')
            ->where('cec_approuver', 'OUI')
            ->get();
    }

    /**
     * Récupère les certificats de non inscription envoyés au tribunal
     * (avec mouvements MOUV_0009, MOUV_0010, MOUV_0011)
     */
    public function getCertificatsEnvoyesTribunal()
    {
        return Declarationnaissance::with(['enfant', 'declarant', 'pere', 'mere', 'mouvements', 'acte'])
            ->where('code_institution', $this->code_institution)
            // ->where('type_declaration', 'CERTIFICAT DE NON INSCRIPTION')
            ->whereHas('mouvements', function($query) {
                $query->whereIn('code_mouvement', ['MOUV_0009', 'MOUV_0010', 'MOUV_0011']);
            })
            ->get();
    }

    /**
     * Récupère les statistiques des documents
     */
    public function getStatistiquesDocuments()
    {
        return [
            'documents_a_controler' => $this->getDocumentsAControler()->count(),
            'actes_gestion' => $this->getActesGestion()->count(),
            'declarations_formation_sanitaire' => $this->getDeclarationsFormationSanitaireAControler()->count(),
            'certificats_centre_etat_civil' => $this->getDeclarationsWithRequisitionsOrJugements()->count(),
        ];
    }

    /**
     * Dossiers à contrôler - Naissance
     */
    public function getDocumentsAControlerNaissance()
    {
        $documentsAControler = collect();
        $documentsAControler = $documentsAControler->merge($this->getDeclarationsFormationSanitaireAControler());
        return $documentsAControler->sortByDesc('date_heure_declaration');
    }

    /**
     * Dossiers à contrôler - Décès
     */
    public function getDocumentsAControlerDeces()
    {
        // À adapter selon la structure de la déclaration de décès
        return $this->institutionsUsers->map->declarationDeces->flatten()
            ->filter(function($deces) {
                return isset($deces->cec_approuver) && $deces->cec_approuver === 'NON';
            })->sortByDesc('date_heure_declaration');
    }

    /**
     * Dossiers à contrôler - Mariage
     */
    public function getDocumentsAControlerMariage()
    {
        // À adapter selon la structure de la déclaration de mariage
        return $this->institutionsUsers->map->declarationMariages->flatten()
            ->filter(function($mariage) {
                return isset($mariage->cec_approuver) && $mariage->cec_approuver === 'NON';
            })->sortByDesc('date_heure_declaration');
    }

    /**
     * Actes à gérer - Naissance
     */
    public function getActesGestionNaissance()
    {
        $actesGestion = collect();
        $actesGestion = $actesGestion->merge($this->getDeclarationsFormationSanitaireApprouvees());
        $actesGestion = $actesGestion->merge($this->getDeclarationsWithRequisitionsOrJugements());
        $actesGestion = $actesGestion->merge($this->getDeclarationsCentreEtatCivilApprouvees());
        return $actesGestion->sortByDesc('date_heure_declaration');
    }

    /**
     * Actes à gérer - Décès
     */
    public function getActesGestionDeces()
    {
        // À adapter selon la structure de la déclaration de décès
        return $this->institutionsUsers->map->declarationDeces->flatten()
            ->filter(function($deces) {
                return isset($deces->cec_approuver) && $deces->cec_approuver === 'OUI';
            })->sortByDesc('date_heure_declaration');
    }

    /**
     * Actes à gérer - Mariage
     */
    public function getActesGestionMariage()
    {
        // À adapter selon la structure de la déclaration de mariage
        return $this->institutionsUsers->map->declarationMariages->flatten()
            ->filter(function($mariage) {
                return isset($mariage->cec_approuver) && $mariage->cec_approuver === 'OUI';
            })->sortByDesc('date_heure_declaration');
    }

    // Les méthodes génériques existantes peuvent être conservées pour compatibilité, ou rediriger vers la version spécifique selon le module.

    /**
     * Récupère les documents à contrôler selon le module
     * @param string|null $module ("naissance", "deces", "mariage")
     */
    public function getDocumentsAControler($module = null)
    {
        if ($module === 'deces') {
            return $this->getDocumentsAControlerDeces();
        } elseif ($module === 'mariage') {
            return $this->getDocumentsAControlerMariage();
        } else { // défaut naissance
            return $this->getDocumentsAControlerNaissance();
        }
    }

    /**
     * Récupère les actes à gérer selon le module
     * @param string|null $module ("naissance", "deces", "mariage")
     */
    public function getActesGestion($module = null)
    {
        if ($module === 'deces') {
            return $this->getActesGestionDeces();
        } elseif ($module === 'mariage') {
            return $this->getActesGestionMariage();
        } else { // défaut naissance
            return $this->getActesGestionNaissance();
        }
    }

    /**
     * Déclarations formation sanitaire à contrôler - Naissance
     */
    public function getDeclarationsFormationSanitaireAControlerNaissance()
    {
        return \Modules\Naissance\Entities\Declarationnaissance::with(['enfant', 'declarant', 'pere', 'mere', 'mouvements'])
            ->where('type_declaration', 'DECLARATION DE NAISSANCE')
            ->where('cec_approuver', 'NON')
            ->where('code_institution_destinataire', $this->code_institution)
            ->get();
    }

    /**
     * Déclarations formation sanitaire à contrôler - Décès
     */
    public function getDeclarationsFormationSanitaireAControlerDeces()
    {
        return \Modules\Deces\Entities\DeclarationDeces::with(['defunt', 'declarant', 'mouvements'])
            ->where('cec_approuver', 'NON')
            ->where('code_institution_destinataire', $this->code_institution)
            ->get();
    }

    /**
     * Déclarations formation sanitaire à contrôler - Mariage
     */
    public function getDeclarationsFormationSanitaireAControlerMariage()
    {
        return \Modules\Mariage\Entities\DeclarationMariage::with(['epoux', 'epouse', 'mouvements'])
            ->where('cec_approuver', 'NON')
            ->where('code_institution_destinataire', $this->code_institution)
            ->get();
    }

    /**
     * Méthode générique pour récupérer les déclarations formation sanitaire à contrôler selon le module
     * @param string|null $module ("naissance", "deces", "mariage")
     */
    public function getDeclarationsFormationSanitaireAControler($module = null)
    {
        if ($module === 'deces') {
            return $this->getDeclarationsFormationSanitaireAControlerDeces();
        } elseif ($module === 'mariage') {
            return $this->getDeclarationsFormationSanitaireAControlerMariage();
        } else {
            return $this->getDeclarationsFormationSanitaireAControlerNaissance();
        }
    }
}
