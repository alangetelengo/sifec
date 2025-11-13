<?php

namespace Modules\Deces\Entities;


use App\Models\Jugement;
use App\Models\Requisition;
use App\Models\InstitutionUser;
use Modules\Deces\Entities\ActeDeces;
use Illuminate\Database\Eloquent\Model;
use Modules\Referentiel\Entities\Regime;
use Modules\Deces\Entities\MouvementDeces;
use Modules\Referentiel\Entities\Document;
use Modules\Referentiel\Entities\Localite;
use Modules\Referentiel\Entities\Personne;
use Modules\Referentiel\Entities\Religion;
use Modules\Referentiel\Entities\Filiation;
use Modules\Referentiel\Entities\CauseDeces;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\Arrondissement;
use Modules\Referentiel\Entities\LieuSurvenance;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Referentiel\Entities\SituationMatrimoniale;

class DeclarationDeces extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $table="t_declaration_deces";
    protected $primaryKey="code_declaration_deces";
    public $incrementing = false;

       public function lieuSurvenance(): BelongsTo
       {
           return $this->belongsTo(LieuSurvenance::class, 'code_lieu_survenance', 'code_lieu_survenance');
       }

       public function document(): BelongsTo
       {
           return $this->belongsTo(Document::class, 'code_document', 'code_document');
       }

       public function declarant(): BelongsTo
       {
           return $this->belongsTo(Personne::class, 'code_declarant', 'code_personne');
       }

       public function defunt(): BelongsTo
       {
           return $this->belongsTo(Personne::class, 'code_defunt', 'code_personne');

       }

        public function conjoint(): BelongsTo
        {
            return $this->belongsTo(Personne::class, 'code_conjoint', 'code_personne');
        }

        public function religion(): BelongsTo
        {
            return $this->belongsTo(Religion::class, 'code_religion', 'code_religion');
        }

        public function filiation(): BelongsTo
        {
            return $this->belongsTo(Filiation::class, 'code_filiation', 'code_filiation');
        }

        public function institutionUser(): BelongsTo
        {
            return $this->belongsTo(InstitutionUser::class, 'code_user_institution', 'cui');
        }

        public function localite(): BelongsTo
        {
            return $this->belongsTo(Localite::class, 'code_localite', 'code_localite');
        }

        public function mouvements(): HasMany
        {
            return $this->hasMany(MouvementDeces::class, 'code_declaration_deces', 'code_declaration_deces');
        }


        public function situationMat(): BelongsTo
        {
            return $this->belongsTo(SituationMatrimoniale::class, 'code_situation_matrimoniale', 'code_situation_matrimoniale');
        }

        public function regime(): BelongsTo
        {
            return $this->belongsTo(Regime::class, 'code_regime', 'code_regime');
        }

        public function causesDeces(): BelongsTo
        {
            return $this->belongsTo(CauseDeces::class, 'code_cause_deces', 'code_cause_deces');
        }

        /**
         * Get all of the comments for the DeclarationDeces
         *
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function DDecesCauses(): HasMany
        {
            return $this->hasMany(DDecesCause::class, 'code_declaration_deces', 'code_declaration_deces');
        }

        public function acte(): HasOne
        {
            return $this->hasOne(ActeDeces::class, 'code_declaration_deces', 'code_declaration_deces');
        }

        public function pere(): BelongsTo
        {
            return $this->belongsTo(Personne::class, 'code_pere', 'code_personne');
        }

        public function mere(): BelongsTo
        {
            return $this->belongsTo(Personne::class, 'code_mere', 'code_personne');
        }


        public function arrondissement(): BelongsTo
        {
            return $this->belongsTo(Arrondissement::class, 'code_declaration_deces', 'code_arrondissement');
        }

        //recupere lieu de deces par rapport aux décès enregistres par les services d'hygiène
        public function lieuDeces(): BelongsTo
        {
            return $this->belongsTo(Localite::class, 'lieu_deces', 'code_localite');
        }

        /**
         * Permet de savoir l'institution dont la déclaration a été envoyée
         */
        public function institutionDestinataire(): BelongsTo
        {
            return $this->belongsTo(Institution::class, 'code_institution_destinataire', 'code_institution');
        }
        //pour retracer le jugement venant du tribunal
        public function jugement(): HasOne
        {
            return $this->hasOne(Jugement::class, 'code_declaration', 'code_declaration_deces');
        }

        //pour retracer la réquisition venant du tribunal
        public function requisition(): HasOne
        {
            return $this->hasOne(Requisition::class, 'code_declaration', 'code_declaration_deces');
        }

        //institution appartement le document
        public function institution(): BelongsTo
        {
            return $this->belongsTo(Institution::class, 'code_institution','code_institution');
        }


    }

