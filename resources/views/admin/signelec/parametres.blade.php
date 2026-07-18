@extends('layout.app')

@section('titre')
    SIGNELEC — Paramètres
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
.sifec-ref-crud-page .sl-fonction-check {
    border: 1px solid rgba(0, 107, 49, 0.12);
    border-radius: 10px;
    padding: 0.65rem 0.85rem;
    background: #fff;
    transition: border-color .15s ease, background .15s ease;
}
.sifec-ref-crud-page .sl-fonction-check:hover {
    border-color: rgba(0, 158, 73, 0.35);
    background: rgba(0, 158, 73, 0.04);
}
.sifec-ref-crud-page .sl-fonction-check .form-check-input:checked {
    background-color: #009E49;
    border-color: #009E49;
}
</style>
@endsection

@section('corps')
<div class="sifec-ref-crud-page container-fluid px-0">
    <div class="sl-hero mb-4">
        <div class="row align-items-center g-3 position-relative" style="z-index:1">
            <div class="col-lg">
                <h1><i class="fas fa-sliders-h me-2 opacity-90"></i>Paramètres SIGNELEC</h1>
                <p>Définir les fonctions (postes) éligibles à l’enrôlement PKI — responsables uniquement.</p>
            </div>
            <div class="col-lg-auto d-flex flex-wrap gap-2">
                <a href="{{ route('admin.signelec.dashboard') }}" class="btn btn-outline-light">
                    <i class="fas fa-tachometer-alt me-1"></i> Tableau de bord
                </a>
                <a href="{{ route('admin.signelec.signataires') }}" class="btn btn-light">
                    <i class="fas fa-user-check me-1"></i> Signataires
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm">{{ session('error') }}</div>
    @endif

    <div class="card sl-card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>Fonctions éligibles à l’enrôlement</h5>
        </div>
        <div class="card-body p-3 p-md-4">
            <div class="sl-config-info mb-4">
                <div class="fw-semibold text-dark mb-1">
                    <i class="fas fa-info-circle me-1" style="color: #006B31;"></i> Règle métier
                </div>
                <p class="text-muted mb-0 small">
                    Cochez les postes qui peuvent recevoir un certificat Signum (signature électronique).
                    Les agents de saisie ne doivent pas être sélectionnés. Ce paramètre filtre la liste
                    « Signataires &amp; enrôlements » et l’option d’enrôlement à la création d’utilisateur.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.signelec.parametres.update') }}" id="signelecParametresForm">
                @csrf
                @method('PUT')

                <div class="row g-2 mb-3">
                    @forelse($fonctions as $fn)
                        <div class="col-md-6 col-xl-4">
                            <label class="sl-fonction-check form-check d-flex align-items-start gap-2 mb-0 h-100">
                                <input type="checkbox"
                                       class="form-check-input mt-1"
                                       name="signataire_fonctions[]"
                                       value="{{ $fn->code_fonction }}"
                                       {{ in_array($fn->code_fonction, $selected, true) ? 'checked' : '' }}>
                                <span>
                                    <span class="d-block fw-semibold text-dark">{{ $fn->lib_fonction }}</span>
                                    <code class="small text-muted">{{ $fn->code_fonction }}</code>
                                </span>
                            </label>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-muted mb-0">Aucune fonction disponible dans le référentiel.</p>
                        </div>
                    @endforelse
                </div>

                @error('signataire_fonctions')
                    <div class="alert alert-danger py-2">{{ $message }}</div>
                @enderror
                @error('signataire_fonctions.*')
                    <div class="alert alert-danger py-2">{{ $message }}</div>
                @enderror

                <div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-semibold">
                        <i class="fas fa-check me-1"></i> Enregistrer
                    </button>
                    <a href="{{ route('admin.signelec.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#signelecParametresForm').on('submit', function () {
        var btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Enregistrement…');
    });
</script>
@endsection
