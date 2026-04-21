@extends('layout.app')
@section('titre')
    Nouvelle institution
@endsection
@section('styles')
@include('referentiel::partials.sifec-ref-crud-styles')
@endsection
@section('corps')
<div class="sifec-ref-crud-page container-fluid px-0">
    <div class="sl-hero mb-4">
        <div class="row align-items-center g-3 position-relative" style="z-index:1">
            <div class="col-lg">
                <h1><i class="fas fa-plus-circle me-2 opacity-90"></i>Nouvelle institution</h1>
                <p>Même formulaire que la modification : rattachement, localité, sceau et liens métier (CEC, tribunaux).</p>
            </div>
            <div class="col-lg-auto">
                <a href="{{ route('institution.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <div class="card sl-card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-building me-2"></i>Saisie</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('institution.store') }}" method="POST" enctype="multipart/form-data" id="createInstitutionForm">
                @csrf
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label class="form-label fw-bold">Libellé de l'institution <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg @error('lib_institution') is-invalid @enderror"
                               value="{{ old('lib_institution') }}"
                               name="lib_institution" required placeholder="Ex: Cour d'Appel de Brazzaville…">
                        @error('lib_institution')
                            <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-12">
                        <label class="form-label fw-bold">Type d'institution <span class="text-danger">*</span></label>
                        <select name="code_type_institution" required class="form-control form-control-lg @error('code_type_institution') is-invalid @enderror" id="create-code-type-institution">
                            <option value="">— Choisir un type —</option>
                            @foreach ($typeInstitutions as $item)
                                <option value="{{ $item->code_type_institution }}" {{ old('code_type_institution') == $item->code_type_institution ? 'selected' : '' }}>
                                    {{ $item->lib_type_institution }}
                                </option>
                            @endforeach
                        </select>
                        @error('code_type_institution')
                            <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-12">
                        <label class="form-label fw-bold">Rattacher à une institution parent ?</label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="newrattacher" id="createRattacherOui" value="OUI" {{ old('newrattacher') == 'OUI' ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="createRattacherOui">
                                    <i class="fas fa-check-circle me-1 text-success"></i>OUI
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="newrattacher" id="createRattacherNon" value="NON" {{ old('newrattacher', 'NON') == 'NON' ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="createRattacherNon">
                                    <i class="fas fa-times-circle me-1 text-danger"></i>NON
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 col-md-12" id="createInstitutionParentBlock" style="{{ old('newrattacher') == 'OUI' ? '' : 'display:none;' }}">
                        <label class="form-label fw-bold">Institution parent</label>
                        <select name="code_institution_parent" class="form-control form-control-lg" id="create-code-institution-parent">
                            <option value="">Aucune</option>
                            @foreach ($availableParents as $item)
                                <option value="{{ $item->code_institution }}" {{ old('code_institution_parent') == $item->code_institution ? 'selected' : '' }}>
                                    {{ $item->lib_institution }} @if($item->typeInstitution) ({{ $item->typeInstitution->lib_type_institution }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-12">
                        <label class="form-label fw-bold">Localité <span class="text-danger">*</span></label>
                        <select name="code_localite" required class="form-control form-control-lg @error('code_localite') is-invalid @enderror">
                            <option value="">— Choisir une localité —</option>
                            @foreach ($localites as $item)
                                <option value="{{ $item->code_localite }}" {{ old('code_localite') == $item->code_localite ? 'selected' : '' }}>
                                    {{ $item->lib_localite }}
                                </option>
                            @endforeach
                        </select>
                        @error('code_localite')
                            <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-12">
                        <label class="form-label fw-bold">Sceau</label>
                        <input type="file" class="form-control form-control-lg" name="sceau" accept="image/*">
                        <small class="form-text text-muted"><i class="fas fa-info-circle me-1"></i>Image du sceau (facultatif à la création).</small>
                    </div>

                    <div class="mb-3 col-md-12">
                        <label class="form-label fw-bold">Statut <span class="text-danger">*</span></label>
                        <select name="statut" class="form-control form-control-lg @error('statut') is-invalid @enderror" required>
                            <option value="1" {{ (string) old('statut', '1') === '1' ? 'selected' : '' }}>Actif</option>
                            <option value="0" {{ (string) old('statut', '1') === '0' ? 'selected' : '' }}>Inactif</option>
                        </select>
                        @error('statut')
                            <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    @include('referentiel::institution.partials.form-liens-metier', [
                        'institution' => $institution,
                        'cecPourLiens' => $cecPourLiens,
                        'tribunauxPourLiens' => $tribunauxPourLiens,
                    ])
                </div>
                <div class="d-flex gap-2 mt-3 flex-wrap">
                    <button type="submit" class="btn btn-success btn-lg rounded-pill px-4 fw-semibold">
                        <i class="fas fa-check me-1"></i>Enregistrer
                    </button>
                    <a href="{{ route('institution.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                        <i class="fas fa-times me-1"></i>Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        function toggleCreateParentBlock() {
            var isOui = $('input[name="newrattacher"]:checked').val() === 'OUI';
            if (isOui) {
                $('#createInstitutionParentBlock').show();
                $('#create-code-institution-parent').prop('disabled', false);
            } else {
                $('#createInstitutionParentBlock').hide();
                $('#create-code-institution-parent').prop('disabled', true).val('');
            }
        }
        $('input[name="newrattacher"]').on('change', toggleCreateParentBlock);
        toggleCreateParentBlock();
    });

    $('#createInstitutionForm').on('submit', function () {
        var btn = $(this).find('button[type="submit"]')[0];
        if (typeof sifecBtnLoading === 'function') sifecBtnLoading(btn, 'Enregistrement…');
    });
</script>
@endsection
