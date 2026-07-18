@extends('layout.app')

@section('titre')
    SIGNELEC — Tableau de bord
@endsection

@section('styles')
@include('referentiel::partials.sifec-ref-crud-styles')
@endsection

@section('corps')
<div class="sifec-ref-crud-page container-fluid px-0">
    <div class="sl-hero mb-4">
        <div class="row align-items-center g-3 position-relative" style="z-index:1">
            <div class="col-lg">
                <h1><i class="fas fa-certificate me-2 opacity-90"></i>Signature électronique (SIGNELEC)</h1>
                <p>Pilotage GUOT : cachets institutionnels (CEC), enrôlements signataires et documents signés (L1 / L2 / L3).</p>
            </div>
            <div class="col-lg-auto d-flex flex-wrap gap-2">
                <a href="{{ route('admin.signelec.institutions') }}" class="btn btn-light">
                    <i class="fas fa-building me-1"></i> Institutions &amp; cachets
                </a>
                <a href="{{ route('admin.signelec.signataires') }}" class="btn btn-outline-light">
                    <i class="fas fa-user-check me-1"></i> Signataires
                </a>
                <a href="{{ route('admin.signelec.parametres') }}" class="btn btn-outline-light">
                    <i class="fas fa-sliders-h me-1"></i> Paramètres
                </a>
            </div>
        </div>
    </div>

    <div class="alert {{ $stats['trust_configured'] ? 'alert-success' : 'alert-warning' }} border-0 shadow-sm mb-4">
        @if($stats['trust_configured'])
            <i class="fas fa-plug me-1"></i>
            Connexion trust-api configurée
            @if($stats['trust_url'])
                (<code>{{ $stats['trust_url'] }}</code>)
            @endif
        @else
            <i class="fas fa-exclamation-triangle me-1"></i>
            <strong>trust-api non configuré</strong> — renseignez <code>PKI_TRUST_API_URL</code> et <code>PKI_API_KEY</code> dès réception des accès GUOT.
            Les écrans ci-dessous servent déjà à la checklist opérationnelle.
        @endif
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card sl-stat">
                <div class="card-body">
                    <div class="text-muted small fw-semibold text-uppercase mb-1">CEC (checklist cachet)</div>
                    <div class="fs-3 fw-bold" style="color:var(--sl-green)">{{ $stats['cec_liees'] }} / {{ $stats['cec_total'] }}</div>
                    <div class="small text-muted">liés Signum · {{ $stats['cec_manquantes'] }} manquant(s)</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card sl-stat">
                <div class="card-body">
                    <div class="text-muted small fw-semibold text-uppercase mb-1">Responsables (PKI)</div>
                    <div class="fs-3 fw-bold" style="color:var(--sl-green)">{{ $stats['signataires_enroles'] }} / {{ $stats['signataires_total'] }}</div>
                    <div class="small text-muted">{{ $stats['signataires_non_enroles'] }} non enrôlé(s)</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card sl-stat">
                <div class="card-body">
                    <div class="text-muted small fw-semibold text-uppercase mb-1">Documents L1 / L2 / L3</div>
                    <div class="fs-5 fw-bold" style="color:var(--sl-green)">
                        {{ $stats['docs_l1'] }} · {{ $stats['docs_l2'] }} · {{ $stats['docs_l3'] }}
                    </div>
                    <div class="small text-muted">preuves payload / doc / cachet</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card sl-stat">
                <div class="card-body">
                    <div class="text-muted small fw-semibold text-uppercase mb-1">Alertes J-30</div>
                    <div class="fs-3 fw-bold text-warning">{{ $stats['cec_expire_bientot'] + $stats['signataires_expire_bientot'] }}</div>
                    <div class="small text-muted">
                        {{ $stats['cec_expire_bientot'] }} institution(s) · {{ $stats['signataires_expire_bientot'] }} signataire(s)
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card sl-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-building me-2" style="color:var(--sl-green)"></i>Institutions &amp; cachets</h5>
                    <a href="{{ route('admin.signelec.institutions') }}" class="btn btn-sm btn-outline-success">Ouvrir</a>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">
                        Checklist des CEC éligibles au <strong>cachet institutionnel Layer 3</strong> (tâche planning 06 / 08).
                    </p>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-1"></i>{{ $stats['cec_liees'] }} CEC avec identifiant Signum</li>
                        <li class="mb-2"><i class="fas fa-minus-circle text-secondary me-1"></i>{{ $stats['cec_manquantes'] }} CEC sans cachet lié</li>
                        <li><i class="fas fa-exclamation-triangle text-warning me-1"></i>{{ $stats['cec_expire_bientot'] }} certificat(s) institution expire bientôt</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card sl-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user-shield me-2" style="color:var(--sl-green)"></i>Signataires &amp; enrôlements</h5>
                    <a href="{{ route('admin.signelec.signataires') }}" class="btn btn-sm btn-outline-success">Ouvrir</a>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">
                        Suivi des <strong>responsables</strong> à enrôler (officier d’état civil, etc.) — pas les agents de saisie.
                    </p>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2"><i class="fas fa-id-badge text-success me-1"></i>{{ $stats['signataires_enroles'] }} responsable(s) enrôlé(s)</li>
                        <li class="mb-2"><i class="fas fa-user-clock text-secondary me-1"></i>{{ $stats['signataires_non_enroles'] }} à enrôler</li>
                        <li><i class="fas fa-exclamation-triangle text-warning me-1"></i>{{ $stats['signataires_expire_bientot'] }} certificat(s) utilisateur expire bientôt</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
