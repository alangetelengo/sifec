@extends('layout.app')

@section('titre')
Modifier Tarif {{ $tarif->code_tarification }}
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
                    <h4><i class="fas fa-edit text-primary me-2"></i> Modifier le Tarif {{ $tarif->code_tarification }}</h4>
                    <a href="{{ route('admin.tarifs.index') }}">
                        <button type="button" class="btn btn-warning float-end text-white">
                            <i class="fa fa-list"></i> Retour à la liste
                        </button>
                    </a>
                </div>

                <div class="card-body">
                    {{-- Info du tarif --}}
                    <div class="alert alert-info mb-4">
                        <strong><i class="fas fa-info-circle"></i> Informations du tarif:</strong><br>
                        <ul class="mb-0 mt-2">
                            <li>Type de document: <strong>{{ $tarif->typeDocumentDemande->lib_type_document_demande }}</strong></li>
                            <li>Portée: 
                                @if($tarif->code_institution)
                                    <strong>Institution spécifique</strong> ({{ $tarif->institution->lib_institution ?? $tarif->code_institution }})
                                @else
                                    <strong>National</strong>
                                @endif
                            </li>
                        </ul>
                    </div>

                    <form id="form-tarif-edit" action="{{ route('admin.tarifs.update', $tarif->code_tarification) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Prix (FCFA) <span class="text-danger">*</span></label>
                                <input type="number" name="prix" class="form-control" 
                                       value="{{ old('prix', $tarif->prix) }}" min="0" step="100" required 
                                       placeholder="Ex: 5000">
                                @error('prix')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Statut</label>
                                <div class="form-check form-switch" style="padding-top: 8px;">
                                    <input class="form-check-input" type="checkbox" name="actif" value="1"
                                           id="actif" {{ old('actif', $tarif->actif) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="actif">
                                        Tarif actif
                                    </label>
                                </div>
                                @error('actif')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Date début validité</label>
                                <input type="date" name="date_debut_validite" class="form-control" 
                                       value="{{ old('date_debut_validite', $tarif->date_debut_validite?->format('Y-m-d')) }}">
                                @error('date_debut_validite')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Date fin validité</label>
                                <input type="date" name="date_fin_validite" class="form-control" 
                                       value="{{ old('date_fin_validite', $tarif->date_fin_validite?->format('Y-m-d')) }}">
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
                                          placeholder="Ex: Tarif établi selon la loi n° XX-2026 du XX/XX/2026">{{ old('commentaire', $tarif->commentaire) }}</textarea>
                                @error('commentaire')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="border-top pt-3 mt-3">
                            <button type="submit" class="btn btn-success btn-lg" id="btn-tarif-edit-submit">
                                <i class="fas fa-save"></i> Enregistrer les Modifications
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
    document.getElementById('form-tarif-edit')?.addEventListener('submit', function () {
        var btn = document.getElementById('btn-tarif-edit-submit');
        if (btn && typeof window.sifecBtnLoading === 'function') {
            window.sifecBtnLoading(btn, 'Enregistrement…');
        }
    });
})();
</script>
@endsection
