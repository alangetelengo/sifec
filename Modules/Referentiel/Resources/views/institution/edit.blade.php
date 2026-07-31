@extends('layout.app')
@section('titre')
   Modifier l'institution
@endsection
@section('corps')
<div class="page-sifec-form">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Modifier l'institution</h4>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('institution.create') }}" class="btn btn-outline-primary">
                        <i class="fas fa-plus me-1"></i>Nouvelle institution
                    </a>
                    <a href="{{ route('institution.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Retour à la liste
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-building me-2"></i>{{ $institution->lib_institution }}</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success border-0">
                            <i class="fas fa-check-circle me-1"></i>{{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger border-0">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ session('error') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger border-0">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif
                    <form action="{{ route('institution.update', $institution->code_institution) }}" method="POST" enctype="multipart/form-data" id="editInstitutionForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <!-- 1. Libellé de l'institution -->
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Libellé de l'institution <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg @error('lib_institution') is-invalid @enderror"
                                       value="{{ old('lib_institution', $institution->lib_institution) }}"
                                       name="lib_institution" required placeholder="Ex: Cour d'Appel de Brazzaville...">
                                @error('lib_institution')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- 2. Type d'institution -->
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Type d'institution <span class="text-danger">*</span></label>
                                <select name="code_type_institution" required class="form-control form-control-lg @error('code_type_institution') is-invalid @enderror" id="edit-code-type-institution">
                                    @foreach ($typeInstitutions as $item)
                                        <option value="{{ $item->code_type_institution }}" {{ old('code_type_institution', $institution->code_type_institution) == $item->code_type_institution ? 'selected' : '' }}>
                                            {{ $item->lib_type_institution }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('code_type_institution')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- 3. Rattachement à une institution parent -->
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Rattacher à une institution parent ?</label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="newrattacher" id="newrattacherOui" value="OUI" {{ old('newrattacher', $institution->code_institution_parent ? 'OUI' : 'NON') == 'OUI' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="newrattacherOui">
                                            <i class="fas fa-check-circle me-1 text-success"></i>OUI
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="newrattacher" id="newrattacherNon" value="NON" {{ old('newrattacher', $institution->code_institution_parent ? 'OUI' : 'NON') == 'NON' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="newrattacherNon">
                                            <i class="fas fa-times-circle me-1 text-danger"></i>NON
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. Institution parent (conditionnel) -->
                            <div class="mb-3 col-md-12" id="editInstitutionParentBlock" style="{{ $institution->code_institution_parent ? '' : 'display:none;' }}">
                                <label class="form-label fw-bold">Institution parent</label>
                                <select name="code_institution_parent" class="form-control form-control-lg" id="edit-code-institution-parent">
                                    <option value="">Aucune</option>
                                    @foreach ($availableParents as $item)
                                        <option value="{{ $item->code_institution }}" {{ old('code_institution_parent', $institution->code_institution_parent) == $item->code_institution ? 'selected' : '' }}>
                                            {{ $item->lib_institution }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- 5. Localité -->
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Localité <span class="text-danger">*</span></label>
                                <select name="code_localite" required class="form-control form-control-lg @error('code_localite') is-invalid @enderror">
                                    @foreach ($localites as $item)
                                        <option value="{{ $item->code_localite }}" {{ old('code_localite', $institution->code_localite) == $item->code_localite ? 'selected' : '' }}>
                                            {{ $item->lib_localite }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('code_localite')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- 6. Sceau -->
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Sceau</label>
                                @if($institution->sceau)
                                    <div class="mb-2">
                                        <img src="{{ asset('app/'.$institution->sceau) }}" alt="Sceau actuel" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                                        <p class="text-muted small mb-0">Sceau actuel</p>
                                    </div>
                                @endif
                                <input type="file" class="form-control form-control-lg" name="sceau" accept="image/*">
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Laissez vide pour conserver l'image actuelle
                                </small>
                            </div>

                            @include('partials.guot.cachet-institution', [
                                'institution' => $institution,
                                'editable' => true,
                            ])

                            <!-- 7. Statut -->
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Statut <span class="text-danger">*</span></label>
                                <select name="statut" class="form-control form-control-lg @error('statut') is-invalid @enderror" required>
                                    <option value="1" {{ old('statut', $institution->statut) == 1 ? 'selected' : '' }}>Actif</option>
                                    <option value="0" {{ old('statut', $institution->statut) == 0 ? 'selected' : '' }}>Inactif</option>
                                </select>
                                @error('statut')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            @include('referentiel::institution.partials.form-liens-metier', [
                                'institution' => $institution,
                                'cecPourLiens' => $cecPourLiens,
                                'tribunauxPourLiens' => $tribunauxPourLiens,
                            ])
                        </div>
                        <div class="d-flex gap-2 mt-3 flex-wrap">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-1"></i>Enregistrer les modifications
                            </button>
                            <a href="{{ route('institution.index') }}" class="btn btn-lg text-white border-0" style="background-color: #CE1126; display: inline-flex; align-items: center;">
                                <i class="fas fa-times me-1"></i>Annuler
                            </a>
                        </div>
                    </form>

                    {{-- Formulaires cachet hors du form principal (évite les <form> imbriqués) --}}
                    <form id="guot-institution-enroll-form" action="{{ route('institution.guot.enroll', $institution->code_institution) }}" method="post" class="d-none">
                        @csrf
                    </form>
                    <form id="guot-institution-sync-form" action="{{ route('institution.guot.sync', $institution->code_institution) }}" method="post" class="d-none">
                        @csrf
                    </form>
                    <form id="guot-institution-revoke-form" action="{{ route('institution.guot.revoke', $institution->code_institution) }}" method="post" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        function toggleEditParentBlock() {
            var isOui = $('input[name="newrattacher"]:checked').val() === 'OUI';
            if (isOui) {
                $('#editInstitutionParentBlock').show();
                $('#edit-code-institution-parent').prop('disabled', false);
            } else {
                $('#editInstitutionParentBlock').hide();
                $('#edit-code-institution-parent').prop('disabled', true).val('');
            }
        }
        $('input[name="newrattacher"]').on('change', toggleEditParentBlock);
        toggleEditParentBlock();
    });

    $('#editInstitutionForm').on('submit', function () {
        var btn = $(this).find('button[type="submit"]')[0];
        if (typeof sifecBtnLoading === 'function') sifecBtnLoading(btn, 'Enregistrement…');
    });
</script>
@endsection
