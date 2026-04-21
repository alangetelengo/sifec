@extends('layout.app')

@section('titre')
Nouveau Tarif
@endsection

@section('styles')
<link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
@endsection

@section('corps')
<div class="page-sifec-form">
<div class="an-shell">
<div class="an-body">
    <div class="row">
        <div class="col-xl-12">
            <div class="card wizard-content">
                <div class="card-header">
                    <h4><i class="fas fa-plus-circle text-primary me-2"></i> Créer un Nouveau Tarif</h4>
                    <a href="{{ route('admin.tarifs.index') }}">
                        <button type="button" class="btn btn-warning float-end text-white">
                            <i class="fa fa-list"></i> Retour à la liste
                        </button>
                    </a>
                </div>

                <div class="card-body">
                    <form id="form-tarif-create" action="{{ route('admin.tarifs.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Type de document <span class="text-danger">*</span></label>
                                <select name="code_type_document_demande" class="form-control" required>
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($typesDocuments as $type)
                                        <option value="{{ $type->code_type_document_demande }}"
                                                {{ old('code_type_document_demande') == $type->code_type_document_demande ? 'selected' : '' }}>
                                            {{ $type->lib_type_document_demande }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('code_type_document_demande')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Prix (FCFA) <span class="text-danger">*</span></label>
                                <input type="number" name="prix" class="form-control"
                                       value="{{ old('prix') }}" min="0" step="100" required
                                       placeholder="Ex: 5000">
                                @error('prix')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Mairie (laisser vide pour tarif national)</label>
                                <select name="code_institution" class="form-control">
                                    <option value="">-- Tarif national (toutes les mairies sans tarif spécifique) --</option>
                                    @foreach($institutions as $inst)
                                        <option value="{{ $inst->code_institution }}"
                                                {{ old('code_institution') == $inst->code_institution ? 'selected' : '' }}>
                                            {{ $inst->lib_institution }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('code_institution')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Date début validité</label>
                                <input type="date" name="date_debut_validite" class="form-control"
                                       value="{{ old('date_debut_validite', now()->format('Y-m-d')) }}">
                                @error('date_debut_validite')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Date fin validité</label>
                                <input type="date" name="date_fin_validite" class="form-control"
                                       value="{{ old('date_fin_validite') }}">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> Laisser vide pour une validité indéfinie
                                </small>
                                @error('date_fin_validite')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Commentaire / Référence légale</label>
                                <textarea name="commentaire" class="form-control" rows="3"
                                          placeholder="Ex: Tarif établi selon la loi n° XX-2026 du XX/XX/2026">{{ old('commentaire') }}</textarea>
                                @error('commentaire')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="border-top pt-3 mt-3">
                            <button type="submit" class="btn btn-success btn-lg" id="btn-tarif-create-submit">
                                <i class="fas fa-save"></i> Enregistrer le Tarif
                            </button>
                            <a href="{{ route('admin.tarifs.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection

@section('scripts')
<script>
(function () {
    document.getElementById('form-tarif-create')?.addEventListener('submit', function () {
        var btn = document.getElementById('btn-tarif-create-submit');
        if (btn && typeof window.sifecBtnLoading === 'function') {
            window.sifecBtnLoading(btn, 'Enregistrement…');
        }
    });
})();
</script>
@endsection
