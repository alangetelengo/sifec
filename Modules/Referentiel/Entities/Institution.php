<?php

namespace Modules\Referentiel\Entities;

use App\Models\Appareil;
use App\Models\InstitutionUser;
use App\Models\Jugement;
use App\Models\Requisition;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Mariage\Entities\DeclarationMariage;
use Modules\Naissance\Entities\Declarationnaissance;
use Modules\Rectification\Entities\Rectification;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Institution extends Model
{
    use HasFactory;
    use HasRecursiveRelationships;
    use SoftDeletes;

    protected $table = 'tr_institution';

    protected $primaryKey = 'code_institution';

    public $incrementing = false;

    public function typeInstitution(): BelongsTo
    {
        return $this->belongsTo(TypeInstitution::class, 'code_type_institution', 'code_type_institution');
    }

    public function institutionParent(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'code_institution_parent', 'code_institution');
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

    public function descendants()
    {
        return $this->descendantsAndSelf()->depthFirst()->get();
    }

    /**
     * Relation vers les institutions enfants (pour la hiérarchie)
     */
    public function institutionsEnfants(): HasMany
    {
        return $this->hasMany(Institution::class, 'code_institution_parent', 'code_institution');
    }

    public function institutionsUsers(): HasMany
    {
        return $this->hasMany(InstitutionUser::class, 'code_institution', 'code_institution');
    }

    public function declarationsNaissances()
    {
        return $this->institutionsUsers->map->declarationNaissances->flatten();
    }

    public function declarationsDeces()
    {
        return $this->institutionsUsers->map->declarationDeces->flatten();
    }

    /**
     * Retourne un query builder des declarations de deces de l'institution.
     */
    public function declarationsDecesQuery(): Builder
    {
        return DeclarationDeces::query()->whereIn(
            'code_user_institution',
            $this->institutionsUsers()->select('cui')
        );
    }

    /**
     * Colonne historique : pour une pompe funèbre, cible CEC décès ; pour une formation, souvent CEC naissances.
     * Préférer les liens {@see liensSortants()} / {@see getInstitutionsPompeFunebre()} selon le cas.
     */
    public function pompeFunebre(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'code_pompe_funebre', 'code_institution');
    }

    public function liensSortants(): HasMany
    {
        return $this->hasMany(InstitutionLien::class, 'code_institution_source', 'code_institution');
    }

    public function liensEntrants(): HasMany
    {
        return $this->hasMany(InstitutionLien::class, 'code_institution_cible', 'code_institution');
    }

    // Le nom du responsable du tribunal du ressort
    public function validateur()
    {
        return $this->institutionsUsers->map->fonction->map->responsability()->flatten()->first();
        // return $this->institutionsUsers->map->fonction->map->responsability();//A retravailler pour chercher la fonction qui seule peut faire l'action
    }

    public function telephone()
    {
        return $this->validateur()->contacts->first()->indicatif.$this->validateur()->contacts->first()->telephone;
    }

    public function declarationsMariages()
    {
        return $this->institutionsUsers->map->declarationMariages->flatten();
    }

    /**
     * Récupère le nom de la localité (utilise le nouveau système unifié)
     *
     * @deprecated Utiliser lieu()->lib_localite directement
     */
    public function nomLocalite()
    {
        return $this->lieu ? $this->lieu->lib_localite : 'NON DÉFINI';
    }

    /**
     * Alias pour la relation lieu() - utilise le nouveau système unifié
     *
     * @deprecated Utiliser lieu() directement
     */
    public function lalocalite(): BelongsTo
    {
        return $this->lieu();
    }

    /**
     * @deprecated Utiliser lieu() directement avec le nouveau système unifié
     */
    public function localite()
    {
        return $this->lieu;
    }

    /**
     * @deprecated Utiliser lieu()->localiteParent() pour remonter la hiérarchie
     */
    public function departement()
    {
        if (! $this->lieu) {
            return null;
        }
        // Remonter la hiérarchie jusqu'au département
        $current = $this->lieu;
        while ($current && $current->code_type_localite !== 'TPLOC_0001') {
            $current = $current->localiteParent;
        }

        return $current;
    }

    /**
     * @deprecated Utiliser lieu()->localiteParent pour obtenir le sous-département
     */
    public function subDepartement()
    {
        if (! $this->lieu) {
            return null;
        }

        // Obtenir le parent direct (district ou commune)
        return $this->lieu->localiteParent;
    }

    // recuperer les registres d'une institution
    public function registres()
    {
        return $this->institutionsUsers->flatten()->map->registres->flatten();
    }

    // recuperer les jugements d'une institution (un tribunal)
    public function tousJugements()
    {
        return $this->institutionsUsers->map->jugements->flatten();
    }

    // recuperer les requisitions du tribunal
    public function tousRequisitions()
    {
        return $this->institutionsUsers->map->requisitions->flatten();
    }

    // les requisitions du centre d'état civil
    public function requisitions(): HasMany
    {
        return $this->hasMany(Requisition::class, 'code_institution', 'code_institution');
    }

    // les jugements du centre d'état civil lors d'enregistrement de certificat de non inscription de l'age de l'enfant > 90 jours sans déclarer
    public function jugements(): HasMany
    {
        return $this->hasMany(Jugement::class, 'code_institution', 'code_institution');
    }

    // recupération des rectifications du centre d'état civil
    public function rectifications(): HasMany
    {
        return $this->hasMany(Rectification::class, 'code_institution', 'code_institution');
    }

    /**
     * Récupère les déclarations envoyées par les formations sanitaires (ou pompes funèbres pour décès)
     * en attente de confirmation par le centre d'état civil.
     * Exclut les dossiers déjà confirmés (cec_approuver = 'OUI').
     *
     * Le mariage n’est pas concerné : les déclarations de mariage sont créées uniquement au CEC
     * (les dispenses passent par le tribunal via réquisition / jugement, pas par une formation sanitaire).
     */
    public function getDeclarationsFormationSanitaireAControler($module)
    {
        $formationsSanitairesCodes = $this->descendants()
            ->filter(fn ($institution) => $institution->typeInstitution?->code_type_categorie_ins === 'TCINS_0003'
            )
            ->pluck('code_institution')
            ->toArray();

        if ($module === 'naissance') {
            $codesFormationsLiees = InstitutionLien::query()
                ->where('code_institution_cible', $this->code_institution)
                ->where('code_type_lien', TypeLienInstitution::CODE_FORMATION_CEC_NAISSANCE)
                ->pluck('code_institution_source')
                ->all();
            $institutionsEmettrices = array_values(array_unique(array_merge($formationsSanitairesCodes, $codesFormationsLiees)));

            return Declarationnaissance::with(['enfant', 'declarant', 'pere', 'mere', 'mouvements'])
                ->whereIn('code_institution', $institutionsEmettrices)
                // ->where('type_declaration', "DECLARATION DE NAISSANCE")
                ->where('type_declaration', 'CERTIFICAT DE NAISSANCE')
                ->where('declarant_approuver', 'OUI')
                ->where('cec_approuver', 'NON')
                ->where('code_institution_destinataire', $this->code_institution)
                ->whereHas('mouvements', fn ($q) => $q->whereIn('code_mouvement', ['MOUV_0001', 'MOUV_0035', 'MOUV_0011']))
                ->get();
        }

        if ($module === 'deces') {
            $pompesFunebresCodes = $this->getInstitutionsPompeFunebre()->pluck('code_institution')->toArray();
            $institutionsCodes = array_merge($formationsSanitairesCodes, $pompesFunebresCodes);

            return DeclarationDeces::with(['defunt', 'declarant', 'pere', 'mere', 'mouvements'])
                ->whereIn('code_institution', $institutionsCodes)
                ->whereIn('type_declaration', ['DECLARATION DE DECES', 'CERTIFICAT DE CONSTATATION DE DECES'])
                ->where('declarant_approuver', 'OUI')
                ->where('cec_approuver', 'NON')
                ->where('code_institution_destinataire', $this->code_institution)
                ->get();
        }

        return collect();
    }

    /**
     * Récupère les déclarations approuvées émises par les formations sanitaires (et pompes funèbres pour décès),
     * une fois validées par ce CEC (cec_approuver = OUI, destinataire = ce centre).
     *
     * Pour le mariage : toujours une collection vide — pas de flux formation sanitaire ; le CEC crée les dossiers
     * et le tribunal intervient pour les dispenses (voir getDeclarationsWithRequisitionsOrJugements).
     *
     * @param  string  $module  naissance|deces|mariage
     * @return Collection
     */
    public function getDeclarationsFormationSanitaireApprouvees($module)
    {
        if (! in_array($module, ['naissance', 'deces', 'mariage'])) {
            throw new \InvalidArgumentException('Module invalide. Valeurs acceptées : naissance, deces, mariage');
        }

        if ($module === 'mariage') {
            return collect();
        }

        $institutionsCodes = match ($module) {
            'naissance' => $this->descendants()
                ->filter(fn ($institution) => $institution->typeInstitution?->code_type_categorie_ins === 'TCINS_0003'
                )
                ->pluck('code_institution')
                ->merge(
                    InstitutionLien::query()
                        ->where('code_institution_cible', $this->code_institution)
                        ->where('code_type_lien', TypeLienInstitution::CODE_FORMATION_CEC_NAISSANCE)
                        ->pluck('code_institution_source')
                )
                ->unique()
                ->values(),
            'deces' => $this->descendants()
                ->filter(fn ($institution) => $institution->typeInstitution?->code_type_categorie_ins === 'TCINS_0003'
                )
                ->pluck('code_institution')
                ->merge($this->getInstitutionsPompeFunebre()->pluck('code_institution'))
                ->unique()
                ->values(),
        };

        $config = [
            'naissance' => [
                'model' => Declarationnaissance::class,
                'with' => ['enfant', 'declarant', 'pere', 'mere', 'mouvements', 'acte'],
                'types' => ['DECLARATION DE NAISSANCE'],
            ],
            'deces' => [
                'model' => DeclarationDeces::class,
                'with' => ['defunt', 'declarant', 'pere', 'mere', 'mouvements', 'acte'],
                'types' => [
                    'DECLARATION DE DECES',
                    'CERTIFICAT DE CONSTATATION DE DECES',
                ],
            ],
        ];

        $moduleConfig = $config[$module];
        $query = $moduleConfig['model']::with($moduleConfig['with'])
            ->whereIn('code_institution', $institutionsCodes)
            ->whereIn('type_declaration', $moduleConfig['types'])
            ->where([
                'cec_approuver' => 'OUI',
                'code_institution_destinataire' => $this->code_institution,
            ]);

        return $query->get();
    }

    /**
     * Récupère les déclarations de naissance du centre d’état civil courant
     * qui ont une réquisition ou un jugement envoyé par le tribunal,
     * approuvées par le tribunal (tribunal_approuver = 'OUI'),
     * et dont le mouvement 'MOUV_0011' (transfert au centre d'état civil) est présent.
     *
     * @return Collection
     */
    /**
     * Récupère les déclarations avec réquisitions ou jugements approuvés par le tribunal
     * et transférées au centre d'état civil (MOUV_0011)
     *
     * @param  string  $module  naissance|deces|mariage
     * @return Collection
     */
    public function getDeclarationsWithRequisitionsOrJugements($module)
    {
        if (! in_array($module, ['naissance', 'deces', 'mariage'])) {
            throw new \InvalidArgumentException('Module invalide. Valeurs acceptées : naissance, deces, mariage');
        }

        // Configuration spécifique par module (CNI/destruction avec réquisition ou jugement du tribunal)
        $config = [
            'naissance' => [
                'model' => Declarationnaissance::class,
                'with' => ['enfant', 'declarant', 'pere', 'mere', 'mouvements', 'acte', 'requisition', 'jugement'],
                'types' => [
                    'CERTIFICAT DE NON INSCRIPTION',
                    "CERTIFICAT DE DESTRUCTION DE L'ACTE",
                    'FICHE DE TRANSCRIPTION',
                    'CERTIFICAT DE TRANSCRIPTION',
                ],
            ],
            'deces' => [
                'model' => DeclarationDeces::class,
                'with' => ['defunt', 'declarant', 'pere', 'mere', 'mouvements', 'acte', 'requisition', 'jugement'],
                'types' => null,
            ],
            'mariage' => [
                'model' => DeclarationMariage::class,
                'with' => ['epoux', 'epouse', 'acte', 'requisition', 'jugement'],
                'types' => null,
            ],
        ];

        $moduleConfig = $config[$module];
        $query = $moduleConfig['model']::with($moduleConfig['with'])
            ->where([
                'code_institution' => $this->code_institution,
                'tribunal_approuver' => 'OUI',
            ])
            ->where(function ($q) {
                $q->whereHas('requisition')->orWhereHas('jugement');
            })
            ->whereHas('mouvements', fn ($q) => $q->where('code_mouvement', 'MOUV_0011'));

        if (! empty($moduleConfig['types'])) {
            $query->whereIn('type_declaration', $moduleConfig['types']);
        }

        return $query->get();
    }

    /**
     * Récupère les déclarations de naissance, de deces et de mariage créées par le centre d'état civil
     * qui ont été validées (cec_approuver = 'OUI')
     */
    /**
     * Récupère les déclarations directes créées et approuvées par le centre d'état civil
     *
     * @param  string  $module  naissance|deces|mariage
     * @return Collection
     */
    public function getDeclarationsCentreEtatCivilApprouvees($module)
    {
        if (! in_array($module, ['naissance', 'deces', 'mariage'])) {
            throw new \InvalidArgumentException('Module invalide. Valeurs acceptées : naissance, deces, mariage');
        }

        // Configuration spécifique par module
        // Pour naissance : uniquement déclarations directes. CNI et destruction passent par le tribunal
        // et sont gérés par getDeclarationsWithRequisitionsOrJugements.
        $config = [
            'naissance' => [
                'model' => Declarationnaissance::class,
                'with' => ['enfant', 'declarant', 'pere', 'mere', 'mouvements', 'acte'],
                'types' => ['DECLARATION DE NAISSANCE'],
            ],
            'deces' => [
                'model' => DeclarationDeces::class,
                'with' => ['defunt', 'declarant', 'pere', 'mere', 'mouvements', 'acte'],
                'types' => ['DECLARATION DE DECES', 'DECLARATION TARDIVE', 'CERTIFICAT DE NON INSCRIPTION'],
            ],
            'mariage' => [
                'model' => DeclarationMariage::class,
                'with' => ['epoux', 'epouse', 'acte'],
                'types' => ['DECLARATION DE MARIAGE', 'DISPENSE'],
            ],
        ];

        $moduleConfig = $config[$module];

        return $moduleConfig['model']::with($moduleConfig['with'])
            ->where([
                'code_institution' => $this->code_institution,
                'cec_approuver' => 'OUI',
            ])
            ->whereIn('type_declaration', $moduleConfig['types'])
            ->get();
    }

    /**
     * Récupère les statistiques des documents
     */
    public function getStatistiquesDocuments($module)
    {
        $modulesAvecStats = ['naissance', 'deces', 'mariage'];

        return [
            'documents_a_controler' => in_array($module, $modulesAvecStats, true)
                ? $this->getDocumentsAControler($module)->count()
                : 0,
            'actes_gestion' => in_array($module, $modulesAvecStats, true)
                ? $this->getActesGestion($module)->count()
                : 0,
            'declarations_formation_sanitaire' => $module === 'naissance' || $module === 'deces'
                ? $this->getDeclarationsFormationSanitaireAControler($module)->count()
                : 0,
            'certificats_centre_etat_civil' => in_array($module, $modulesAvecStats, true)
                ? $this->getDeclarationsWithRequisitionsOrJugements($module)->count()
                : 0,
        ];
    }

    /**
     * Récupère les documents à contrôler (cec_approuver = NON).
     * Naissance / décès : déclarations reçues des formations sanitaires (ou partenaires décès).
     * Mariage : dossiers saisis au CEC de ce centre, en attente de validation interne (pas de formation sanitaire).
     */
    public function getDocumentsAControler($module)
    {
        if ($module === 'mariage') {
            return DeclarationMariage::with(['epoux', 'epouse', 'mouvements'])
                ->where('code_institution', $this->code_institution)
                ->where('cec_approuver', 'NON')
                ->get()
                ->sortByDesc('date_declaration_mariage')
                ->values();
        }

        $documentsAControler = collect();
        // $documentsAControler->merge($this->getDeclarationsWithRequisitionsOrJugements($module))
        $documentsAControler = $module === 'naissance' || $module === 'deces'
            ? $documentsAControler->merge($this->getDeclarationsFormationSanitaireAControler($module))
            : $documentsAControler;

        return $documentsAControler->sortByDesc('date_heure_declaration');
    }

    /**
     * Récupère tous les documents pour la gestion des actes (cec_approuver = OUI)
     * Combine les déclarations formation sanitaire + certificats centre état civil + déclarations du centre
     */
    /**
     * Récupère tous les actes à gérer pour un module donné
     * Les actes sont classés en trois catégories :
     * 1. Déclarations approuvées des formations sanitaires
     * 2. Déclarations avec réquisitions/jugements approuvés par le tribunal
     * 3. Déclarations directes du centre d'état civil approuvées
     *
     * @param  string  $module  naissance|deces|mariage
     * @return Collection
     */
    /**
     * Récupère tous les actes à gérer pour un module donné en évitant les doublons
     * Les actes sont classés en trois catégories :
     * 1. Déclarations approuvées des formations sanitaires
     * 2. Déclarations avec réquisitions/jugements approuvés par le tribunal
     * 3. Déclarations directes du centre d'état civil approuvées
     *
     * @param  string  $module  naissance|deces|mariage
     * @return Collection
     */
    public function getActesGestion($module)
    {
        if (! in_array($module, ['naissance', 'deces', 'mariage'])) {
            throw new \InvalidArgumentException('Module invalide. Valeurs acceptées : naissance, deces, mariage');
        }

        // Configuration des clés primaires par module
        $primaryKeys = [
            'naissance' => 'code_declaration_naissance',
            'deces' => 'code_declaration_deces',
            'mariage' => 'code_declaration_mariage',
        ];

        $primaryKey = $primaryKeys[$module];
        $actesGestion = collect();
        $processedIds = collect();

        // Fonction pour ajouter des déclarations en évitant les doublons
        $addUniqueDeclarations = function ($declarations) use (&$actesGestion, &$processedIds, $primaryKey) {
            $declarations->each(function ($declaration) use (&$actesGestion, &$processedIds, $primaryKey) {
                $id = $declaration->$primaryKey;
                if (! $processedIds->contains($id)) {
                    $actesGestion->push($declaration);
                    $processedIds->push($id);
                }
            });
        };

        // 1. Déclarations approuvées des formations sanitaires (vide pour le mariage)
        $addUniqueDeclarations($this->getDeclarationsFormationSanitaireApprouvees($module));

        // 2. Déclarations avec réquisitions/jugements approuvés
        $addUniqueDeclarations($this->getDeclarationsWithRequisitionsOrJugements($module));

        // 3. Déclarations directes du centre approuvées
        $addUniqueDeclarations($this->getDeclarationsCentreEtatCivilApprouvees($module));

        if ($module === 'mariage') {
            return $actesGestion->sortByDesc('date_declaration_mariage')->values();
        }

        return $actesGestion->sortByDesc('date_heure_declaration');
    }

    /**
     * Institutions partenaires « pompe funèbre » pour ce CEC : ancienne colonne code_pompe_funebre
     * (cible = cette institution) et liens tr_institution_lien (TPLIEN_0001, source → cible = cette institution).
     */
    public function getInstitutionsPompeFunebre()
    {
        $codesFromLiens = InstitutionLien::query()
            ->where('code_institution_cible', $this->code_institution)
            ->where('code_type_lien', TypeLienInstitution::CODE_PARTENAIRE_DECES_POMPE)
            ->pluck('code_institution_source');

        $fromLiens = static::query()
            ->whereIn('code_institution', $codesFromLiens)
            ->get();

        $fromLegacy = static::query()
            ->where('code_pompe_funebre', $this->code_institution)
            ->get();

        return $fromLiens->merge($fromLegacy)->unique('code_institution')->values();
    }

    // les appareils de l'institution
    public function appareils()
    {
        return $this->hasMany(Appareil::class, 'code_institution', 'code_institution');
    }

    // retourne un appareil de l'institution par son adresse MAC
    public function appareilParAdresseMac(string $adresseMac)
    {
        return $this->appareils->where('adresse_mac', $adresseMac)->first();
    }
}
