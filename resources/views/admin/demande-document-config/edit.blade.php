@extends('layout.app')

@section('titre')
    Administration — Validité des documents (demandes)
@endsection

@section('styles')
@include('referentiel::partials.sifec-ref-crud-styles')
<style>
.sifec-ref-crud-page .sl-config-info {
    border-left: 4px solid rgba(0, 107, 49, 0.45);
    background: linear-gradient(90deg, rgba(0, 107, 49, 0.06) 0%, rgba(255, 255, 255, 0.9) 100%);
    border-radius: 0 12px 12px 0;
    padding: 1rem 1.15rem;
}
.sifec-ref-crud-page .sl-config-info ul {
    margin: 0.5rem 0 0 1.1rem;
    padding: 0;
    color: #495057;
    font-size: 0.92rem;
}
.sifec-ref-crud-page .sl-field-number {
    max-width: 12rem;
}
</style>
@endsection

@section('corps')
@php
    $mois = (int) old('validite_document_mois', $config->validite_document_mois);
@endphp
<div class="sifec-ref-crud-page container-fluid px-0">
    <div class="sl-hero mb-4">
        <div class="row align-items-center g-3 position-relative" style="z-index: 1;">
            <div class="col-lg">
                <h1><i class="fas fa-calendar-check me-2 opacity-90"></i>Validité des documents délivrés</h1>
                <p class="mb-0">Paramètre global : durée de vie des copies et extraits après signature (demandes portail / centre).</p>
            </div>
            <div class="col-lg-auto">
                <a href="{{ route('admin.tarifs.index') }}" class="btn btn-light">
                    <i class="fas fa-tags me-1"></i> Tarifs
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-4">
            <div class="card sl-stat">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="sl-stat-icon text-white" style="background: linear-gradient(135deg, #006B31, #009E49);">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div>
                        <div class="sl-stat-lbl">Validité actuelle</div>
                        <div class="sl-stat-val">{{ $mois }}&nbsp;<span class="fs-6 fw-semibold text-muted">mois</span></div>
                        <div class="small text-muted">Paramètre actif en base</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card sl-stat">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="sl-stat-icon text-white" style="background: linear-gradient(135deg, #2781d5, #5a9fd4);">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <div class="sl-stat-lbl">Plage autorisée</div>
                        <div class="sl-stat-val small fw-normal text-dark pt-1">1 à 120 mois</div>
                        <div class="small text-muted">Contrôle serveur</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card sl-card">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <h5 class="mb-0"><i class="fas fa-sliders-h me-2"></i>Réglage</h5>
            @can('module.admin.demande_document.parametres.modifier')
                <span class="badge rounded-pill" style="background: rgba(0, 107, 49, 0.12); color: #006B31;">
                    <i class="fas fa-pen me-1"></i> Modifiable
                </span>
            @else
                <span class="badge rounded-pill bg-secondary">Lecture seule</span>
            @endcan
        </div>
        <div class="card-body p-3 p-md-4">
            <div class="sl-config-info mb-4">
                <div class="fw-semibold text-dark mb-1">
                    <i class="fas fa-info-circle me-1" style="color: #006B31;"></i> À quoi sert ce paramètre ?
                </div>
                <p class="text-muted mb-2 mb-md-0 small">
                    Durée pendant laquelle un document signé (copie ou extrait) reste valable après signature.
                    Passé ce délai, le statut peut passer à « Expirée » et le centre peut enclencher un nouveau cycle (génération PDF + signature).
                </p>
                <ul>
                    <li>S'applique aux nouvelles signatures et aux renouvellements selon votre workflow.</li>
                    <li>Modification immédiate pour les prochains enregistrements (comportement historique inchangé pour les dossiers déjà signés).</li>
                </ul>
            </div>

            <form method="POST" action="{{ route('admin.demande-document-config.update') }}" id="demandeDocumentConfigForm">
                @csrf
                @method('PUT')

                <div class="row align-items-end g-3">
                    <div class="col-md-auto">
                        <label for="validite_document_mois" class="form-label fw-bold">Durée de validité</label>
                        <div class="input-group border rounded-3 overflow-hidden bg-white shadow-sm sl-field-number">
                            <span class="input-group-text bg-white border-0 text-muted"><i class="fas fa-hashtag"></i></span>
                            <input type="number" name="validite_document_mois" id="validite_document_mois"
                                   class="form-control border-0 @error('validite_document_mois') is-invalid @enderror"
                                   min="1" max="120" required
                                   value="{{ $mois }}"
                                   @cannot('module.admin.demande_document.parametres.modifier') readonly disabled @endcannot>
                            <span class="input-group-text bg-light border-0 fw-semibold text-muted">mois</span>
                        </div>
                        @error('validite_document_mois')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted mt-1 d-block">Valeur entre 1 et 120&nbsp;mois.</small>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top">
                    @can('module.admin.demande_document.parametres.modifier')
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-semibold">
                            <i class="fas fa-check me-1"></i> Enregistrer
                        </button>
                    @else
                        <div class="alert alert-warning mb-0 py-2 px-3 rounded-3">
                            <i class="fas fa-lock me-1"></i> Vous n'avez pas la permission de modifier ce paramètre.
                        </div>
                    @endcan
                    <a href="{{ route('admin.tarifs.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-arrow-left me-1"></i> Retour aux tarifs
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#demandeDocumentConfigForm').on('submit', function () {
        var btn = $(this).find('button[type="submit"]')[0];
        if (btn && typeof sifecBtnLoading === 'function') {
            sifecBtnLoading(btn, 'Enregistrement…');
        }
    });
</script>
@endsection
