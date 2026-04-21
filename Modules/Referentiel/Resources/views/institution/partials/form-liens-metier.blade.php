@php
    use Illuminate\Database\Eloquent\Collection as EloquentCollection;
    use Modules\Referentiel\Entities\TypeLienInstitution;
    $liensSrc = $institution->relationLoaded('liensSortants')
        ? $institution->getRelation('liensSortants')
        : new EloquentCollection([]);
    $selDeces = $liensSrc
        ->where('code_type_lien', TypeLienInstitution::CODE_PARTENAIRE_DECES_POMPE)
        ->pluck('code_institution_cible')
        ->all();
    $selNaissance = $liensSrc
        ->where('code_type_lien', TypeLienInstitution::CODE_FORMATION_CEC_NAISSANCE)
        ->pluck('code_institution_cible')
        ->all();
    $selTribunal = $liensSrc
        ->where('code_type_lien', TypeLienInstitution::CODE_TRIBUNAL_RESSORT)
        ->pluck('code_institution_cible')
        ->all();
@endphp

<input type="hidden" name="_gestion_liens_institution" value="1">

<div class="col-12 mt-2">
    <div class="card border-secondary">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-link me-2 text-secondary"></i>Liens métier entre institutions</h6>
            <small class="text-muted">Source = cette institution ; cibles = institutions partenaires. La colonne historique <code>code_pompe_funebre</code> est synchronisée automatiquement (compatibilité).</small>
        </div>
        <div class="card-body row">
            <div class="mb-3 col-md-12">
                <label class="form-label fw-bold">CEC partenaires — décès (pompes funèbres, etc.)</label>
                <select name="liens_cec_deces[]" class="form-select" multiple size="8">
                    @foreach ($cecPourLiens as $cec)
                        <option value="{{ $cec->code_institution }}" {{ in_array($cec->code_institution, old('liens_cec_deces', $selDeces), true) ? 'selected' : '' }}>
                            {{ $cec->lib_institution }} @if($cec->typeInstitution) — {{ $cec->typeInstitution->lib_type_institution }} @endif
                        </option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Ctrl + clic pour plusieurs choix. Ces centres recevront les dossiers décès émis par cette institution (type TPLIEN_0001).</small>
            </div>

            <div class="mb-3 col-md-12">
                <label class="form-label fw-bold">CEC destinataires — naissances (formations sanitaires)</label>
                <select name="liens_cec_naissance[]" class="form-select" multiple size="8">
                    @foreach ($cecPourLiens as $cec)
                        <option value="{{ $cec->code_institution }}" {{ in_array($cec->code_institution, old('liens_cec_naissance', $selNaissance), true) ? 'selected' : '' }}>
                            {{ $cec->lib_institution }} @if($cec->typeInstitution) — {{ $cec->typeInstitution->lib_type_institution }} @endif
                        </option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Type TPLIEN_0003. Le premier choix alimente aussi la colonne historique pour les formations sanitaires.</small>
            </div>

            <div class="mb-3 col-md-12">
                <label class="form-label fw-bold">Tribunaux de ressort</label>
                <select name="liens_tribunal_ressort[]" class="form-select" multiple size="6">
                    @foreach ($tribunauxPourLiens as $tr)
                        <option value="{{ $tr->code_institution }}" {{ in_array($tr->code_institution, old('liens_tribunal_ressort', $selTribunal), true) ? 'selected' : '' }}>
                            {{ $tr->lib_institution }} @if($tr->typeInstitution) — {{ $tr->typeInstitution->lib_type_institution }} @endif
                        </option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Type TPLIEN_0002 — référentiel ; exploitation métier à brancher selon vos parcours (réquisitions, jugements).</small>
            </div>
        </div>
    </div>
</div>
