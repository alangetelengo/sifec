@extends('layout.app')

@section('titre')
    Consultation — actes retirés
@endsection

@section('styles')
@endsection

@section('corps')
<div class="page-sifec-index">
    <div class="an-shell">
        <header class="an-hero an-hero--sifec-green">
            <div class="an-hero-text">
                <h1>
                    <span class="an-hero-icon" aria-hidden="true"><i class="fas fa-file-signature"></i></span>
                    Consultation des actes retirés
                </h1>
                <p>
                    Vérifiez si un acte a bien été retiré au guichet : choisissez le type d’acte, puis renseignez l’identité
                    telle qu’elle figure sur l’acte de naissance.
                </p>
            </div>
            <div class="an-toolbar">
                <a href="{{ route('dashboard.index') }}" class="an-hero-ghost">
                    <i class="fas fa-arrow-left"></i> Accueil
                </a>
            </div>
        </header>

        <div class="an-body">
            <div class="an-hint an-hint--step1 mb-3" role="note">
                <span class="an-hint__icon" aria-hidden="true"><i class="fas fa-info"></i></span>
                <div>
                    <strong>Naissance.</strong> La recherche est disponible pour l’acte de naissance.
                    Les onglets <em>Mariage</em> et <em>Décès</em> seront proposés dès que le module correspondra à cette consultation.
                </div>
            </div>

            <ul class="nav nav-pills an-tabs mb-3" id="retraitTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-naissance-btn" data-bs-toggle="tab" data-bs-target="#tab-naissance" type="button" role="tab" aria-controls="tab-naissance" aria-selected="true">
                        <i class="fas fa-baby me-1"></i> Acte de naissance
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-mariage-btn" data-bs-toggle="tab" data-bs-target="#tab-mariage" type="button" role="tab" aria-controls="tab-mariage" aria-selected="false">
                        <i class="fas fa-ring me-1"></i> Acte de mariage
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-deces-btn" data-bs-toggle="tab" data-bs-target="#tab-deces" type="button" role="tab" aria-controls="tab-deces" aria-selected="false">
                        <i class="fas fa-cross me-1"></i> Acte de décès
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab-naissance" role="tabpanel" aria-labelledby="tab-naissance-btn">
                    <div class="card an-filter-card shadow-none">
                        <div class="card-header">
                            <h2 class="card-title mb-0">
                                <i class="fas fa-search me-2 text-secondary"></i> Rechercher un acte de naissance
                            </h2>
                            <p class="text-secondary small mb-0 mt-1">Les champs marqués d’une astérisque (*) sont obligatoires.</p>
                        </div>
                        <div class="card-body">
                            <form id="form-retrait-naissance" action="{{ route('retrait.search.acte') }}" method="POST">
                                @csrf
                                <div class="row g-2 g-md-3">
                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="nom_enfant">Nom(s) de l’enfant <span class="text-danger">*</span></label>
                                        <input type="text" required class="form-control @error('nom_enfant') is-invalid @enderror" id="nom_enfant" name="nom_enfant" value="{{ old('nom_enfant') }}" placeholder="Ex. MBOULOU" autocomplete="family-name" onkeyup="verif_lettre(this); this.value = this.value.toUpperCase()">
                                        @error('nom_enfant')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="prenom_enfant">Prénom(s) de l’enfant</label>
                                        <input type="text" class="form-control" id="prenom_enfant" name="prenom_enfant" value="{{ old('prenom_enfant') }}" placeholder="Ex. Chancelvie" style="text-transform: capitalize" onkeyup="verif_lettre(this);">
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <label class="form-label" for="sexe_enfant">Sexe <span class="text-danger">*</span></label>
                                        <select id="sexe_enfant" name="sexe_enfant" required class="form-control @error('sexe_enfant') is-invalid @enderror">
                                            <option value="" disabled {{ old('sexe_enfant', '') === '' ? 'selected' : '' }}>Sélectionner</option>
                                            <option value="M" {{ old('sexe_enfant') === 'M' ? 'selected' : '' }}>Masculin</option>
                                            <option value="F" {{ old('sexe_enfant') === 'F' ? 'selected' : '' }}>Féminin</option>
                                        </select>
                                        @error('sexe_enfant')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <label class="form-label" for="annee_naissance_enfant">Année de naissance <span class="text-danger">*</span></label>
                                        <select id="annee_naissance_enfant" name="annee_naissance_enfant" required class="form-control @error('annee_naissance_enfant') is-invalid @enderror">
                                            <option value="" disabled {{ old('annee_naissance_enfant', '') === '' ? 'selected' : '' }}>Sélectionner</option>
                                            @php
                                                $yMax = (int) date('Y') + 1;
                                                $yMin = 1970;
                                            @endphp
                                            @for ($y = $yMax; $y >= $yMin; $y--)
                                                <option value="{{ $y }}" {{ (string) old('annee_naissance_enfant') === (string) $y ? 'selected' : '' }}>{{ $y }}</option>
                                            @endfor
                                        </select>
                                        @error('annee_naissance_enfant')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap gap-2 mt-3 pt-2 border-top" style="border-color: var(--si-line, #e2e8e4) !important;">
                                    <button type="submit" id="btn-retrait-submit" class="btn btn-sm btn-primary an-btn-search text-white">
                                        <span class="btn-retrait-submit__idle"><i class="fas fa-check me-1"></i> Valider la recherche</span>
                                    </button>
                                    <a href="{{ route('retrait.index') }}" class="btn btn-sm btn-outline-secondary an-btn-reset">
                                        <i class="fas fa-undo me-1"></i> Réinitialiser
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-mariage" role="tabpanel" aria-labelledby="tab-mariage-btn">
                    <div class="cni-callout cni-callout--neutral mb-0">
                        <span class="cni-callout__icon" aria-hidden="true"><i class="fas fa-hourglass-half"></i></span>
                        <div class="cni-callout__body">
                            <strong>Acte de mariage</strong>
                            <span class="cni-callout__sub">La consultation des retraits pour les actes de mariage n’est pas encore disponible depuis cet écran.</span>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-deces" role="tabpanel" aria-labelledby="tab-deces-btn">
                    <div class="cni-callout cni-callout--neutral mb-0">
                        <span class="cni-callout__icon" aria-hidden="true"><i class="fas fa-hourglass-half"></i></span>
                        <div class="cni-callout__body">
                            <strong>Acte de décès</strong>
                            <span class="cni-callout__sub">La consultation des retraits pour les actes de décès n’est pas encore disponible depuis cet écran.</span>
                        </div>
                    </div>
                </div>
            </div>

            @isset($acte)
                <div class="mt-4">
                    @include('referentiel::retrait-acte.resultat')
                </div>
                @can('module.acteNaissance.retrait.depuisConsultationCEC')
                    @if(! $acte->retrait && $acte->signature_mairie)
                        @include('naissance::acte.partials.modal-retrait-acte')
                    @endif
                @endcan
            @endisset
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
    var form = document.getElementById('form-retrait-naissance');
    var btn = document.getElementById('btn-retrait-submit');
    if (!form || !btn) return;

    form.addEventListener('submit', function () {
        if (btn.disabled) return;
        /* Désactiver après le tick courant pour ne pas bloquer l’envoi du formulaire (Firefox, etc.). */
        window.setTimeout(function () {
            btn.disabled = true;
            btn.setAttribute('aria-busy', 'true');
            btn.innerHTML =
                '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>' +
                'Recherche en cours…';
        }, 0);
    });
})();
</script>
@isset($acte)
    @can('module.acteNaissance.retrait.depuisConsultationCEC')
        @if(! $acte->retrait && $acte->signature_mairie)
            @include('naissance::acte.partials.retrait-acte-form-scripts', ['acte' => $acte])
        @endif
    @endcan
@endisset
@endsection
