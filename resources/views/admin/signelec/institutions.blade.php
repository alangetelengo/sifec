@extends('layout.app')

@section('titre')
    SIGNELEC — Institutions & cachets
@endsection

@section('styles')
@include('referentiel::partials.sifec-ref-crud-styles')
@endsection

@section('corps')
<div class="sifec-ref-crud-page container-fluid px-0">
    <div class="sl-hero mb-4">
        <div class="row align-items-center g-3 position-relative" style="z-index:1">
            <div class="col-lg">
                <h1><i class="fas fa-stamp me-2 opacity-90"></i>Institutions &amp; cachets GUOT</h1>
                <p>Checklist des CEC : quels centres auront un cachet institutionnel Signum (Layer 3).</p>
            </div>
            <div class="col-lg-auto d-flex flex-wrap gap-2">
                <a href="{{ route('admin.signelec.dashboard') }}" class="btn btn-outline-light">
                    <i class="fas fa-tachometer-alt me-1"></i> Tableau de bord
                </a>
                <a href="{{ route('institution.index') }}" class="btn btn-light">
                    <i class="fas fa-edit me-1"></i> Référentiel institutions
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3"><div class="card sl-stat"><div class="card-body py-3"><div class="small text-muted">Total CEC</div><div class="fs-4 fw-bold">{{ $compteurs['total'] }}</div></div></div></div>
        <div class="col-md-3"><div class="card sl-stat"><div class="card-body py-3"><div class="small text-muted">Liés Signum</div><div class="fs-4 fw-bold text-success">{{ $compteurs['liees'] }}</div></div></div></div>
        <div class="col-md-3"><div class="card sl-stat"><div class="card-body py-3"><div class="small text-muted">Sans cachet</div><div class="fs-4 fw-bold text-secondary">{{ $compteurs['manquantes'] }}</div></div></div></div>
        <div class="col-md-3"><div class="card sl-stat"><div class="card-body py-3"><div class="small text-muted">Expire &lt; 30 j</div><div class="fs-4 fw-bold text-warning">{{ $compteurs['expire_bientot'] }}</div></div></div></div>
    </div>

    <div class="card sl-card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.signelec.institutions') }}" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-semibold">Recherche</label>
                    <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Libellé, code, ID Signum…">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Statut PKI</label>
                    <select name="statut_pki" class="form-control">
                        <option value="all" {{ $statut === 'all' ? 'selected' : '' }}>Tous</option>
                        <option value="lie" {{ $statut === 'lie' ? 'selected' : '' }}>Liés Signum</option>
                        <option value="manquant" {{ $statut === 'manquant' ? 'selected' : '' }}>Sans cachet</option>
                        <option value="expire_bientot" {{ $statut === 'expire_bientot' ? 'selected' : '' }}>Expire bientôt</option>
                        <option value="expire" {{ $statut === 'expire' ? 'selected' : '' }}>Expirés</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-success flex-grow-1"><i class="fas fa-filter me-1"></i>Filtrer</button>
                    <a href="{{ route('admin.signelec.institutions') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card sl-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Institution (CEC)</th>
                        <th>Localité</th>
                        <th>ID Signum</th>
                        <th>Validité certificat</th>
                        <th>Statut</th>
                        <th class="sl-actions text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($institutions as $institution)
                        @php
                            $guotId = $institution->guot_institution_id ?? null;
                            $notAfter = $institution->guot_institution_cert_not_after ?? null;
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $institution->lib_institution }}</div>
                                <code class="small text-muted">{{ $institution->code_institution }}</code>
                            </td>
                            <td class="small">{{ $institution->lieu?->lib_localite ?? '—' }}</td>
                            <td>
                                @if($guotId)
                                    <code class="small">{{ $guotId }}</code>
                                @else
                                    <span class="text-muted small">Non renseigné</span>
                                @endif
                            </td>
                            <td class="small">
                                @if($notAfter)
                                    {{ \Carbon\Carbon::parse($notAfter)->format('d/m/Y') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @include('partials.guot.badge-statut-certificat', [
                                    'actorId' => $guotId,
                                    'notAfter' => $notAfter,
                                ])
                            </td>
                            <td class="sl-actions text-center">
                                <div class="sl-actions-group justify-content-center">
                                    <a href="{{ route('institution.edit', $institution->code_institution) }}"
                                       class="sl-btn-action sl-btn-action-edit sl-btn-action-label"
                                       title="Modifier l’institution / lier le cachet GUOT">
                                        <i class="fas fa-edit"></i>
                                        <span>Modifier</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Aucun CEC trouvé pour ce filtre.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($institutions->hasPages())
            <div class="card-footer">{{ $institutions->links() }}</div>
        @endif
    </div>
</div>
@endsection
