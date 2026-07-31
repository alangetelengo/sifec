@extends('layout.app')

@section('titre')
    SIGNELEC — Signataires & enrôlements
@endsection

@section('styles')
@include('referentiel::partials.sifec-ref-crud-styles')
@endsection

@section('corps')
<div class="sifec-ref-crud-page container-fluid px-0">
    <div class="sl-hero mb-4">
        <div class="row align-items-center g-3 position-relative" style="z-index:1">
            <div class="col-lg">
                <h1><i class="fas fa-user-check me-2 opacity-90"></i>Signataires &amp; enrôlements</h1>
                <p>Responsables à enrôler ({{ $signataireDescription }}) — les agents de saisie sont exclus.</p>
            </div>
            <div class="col-lg-auto d-flex flex-wrap gap-2">
                <a href="{{ route('admin.signelec.dashboard') }}" class="btn btn-outline-light">
                    <i class="fas fa-tachometer-alt me-1"></i> Tableau de bord
                </a>
                <a href="{{ route('admin.signelec.parametres') }}" class="btn btn-outline-light">
                    <i class="fas fa-sliders-h me-1"></i> Paramètres
                </a>
                <a href="{{ route('utilisateur.create') }}" class="btn btn-light">
                    <i class="fas fa-user-plus me-1"></i> Nouvel utilisateur
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3"><div class="card sl-stat"><div class="card-body py-3"><div class="small text-muted">Responsables actifs</div><div class="fs-4 fw-bold">{{ $compteurs['total'] }}</div></div></div></div>
        <div class="col-md-3"><div class="card sl-stat"><div class="card-body py-3"><div class="small text-muted">Enrôlés</div><div class="fs-4 fw-bold text-success">{{ $compteurs['enroles'] }}</div></div></div></div>
        <div class="col-md-3"><div class="card sl-stat"><div class="card-body py-3"><div class="small text-muted">À enrôler</div><div class="fs-4 fw-bold text-secondary">{{ $compteurs['non_enroles'] }}</div></div></div></div>
        <div class="col-md-3"><div class="card sl-stat"><div class="card-body py-3"><div class="small text-muted">Expire &lt; 30 j</div><div class="fs-4 fw-bold text-warning">{{ $compteurs['expire_bientot'] }}</div></div></div></div>
    </div>

    <div class="card sl-card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.signelec.signataires') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Recherche</label>
                    <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Nom, e-mail, CUI, actor_id…">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Institution</label>
                    <select name="code_institution" class="form-control">
                        <option value="">Toutes</option>
                        @foreach($institutions as $inst)
                            <option value="{{ $inst->code_institution }}" {{ $codeInstitution === $inst->code_institution ? 'selected' : '' }}>
                                {{ $inst->lib_institution }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Statut PKI</label>
                    <select name="statut_pki" class="form-control">
                        <option value="all" {{ $statut === 'all' ? 'selected' : '' }}>Tous</option>
                        <option value="enrole" {{ $statut === 'enrole' ? 'selected' : '' }}>Enrôlés</option>
                        <option value="non_enrole" {{ $statut === 'non_enrole' ? 'selected' : '' }}>Non enrôlés</option>
                        <option value="expire_bientot" {{ $statut === 'expire_bientot' ? 'selected' : '' }}>Expire bientôt</option>
                        <option value="expire" {{ $statut === 'expire' ? 'selected' : '' }}>Expirés</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-success flex-grow-1"><i class="fas fa-filter me-1"></i>Filtrer</button>
                    <a href="{{ route('admin.signelec.signataires') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card sl-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Signataire</th>
                        <th>Fonction</th>
                        <th>Institution</th>
                        <th>Actor ID</th>
                        <th>Statut PKI</th>
                        <th class="sl-actions text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($signataires as $aff)
                        @php
                            $personne = $aff->user?->personne;
                            $nom = $personne ? trim(($personne->nom ?? '').' '.($personne->prenom ?? '')) : ($aff->user?->email ?? '—');
                            $guotId = $aff->guot_user_id ?? null;
                            $notAfter = $aff->guot_user_cert_not_after ?? null;
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $nom }}</div>
                                <div class="small text-muted">{{ $aff->user?->email ?? '—' }}</div>
                                <code class="small">{{ $aff->cui }}</code>
                            </td>
                            <td class="small">{{ $aff->fonction?->lib_fonction ?? '—' }}</td>
                            <td class="small">{{ $aff->institution?->lib_institution ?? '—' }}</td>
                            <td>
                                @if($guotId)
                                    <code class="small">{{ \Illuminate\Support\Str::limit($guotId, 22) }}</code>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                @include('partials.guot.badge-statut-certificat', [
                                    'actorId' => $guotId,
                                    'notAfter' => $notAfter,
                                ])
                            </td>
                            <td class="sl-actions text-center">
                                @if($aff->user?->code_user)
                                    <div class="sl-actions-group justify-content-center">
                                        <a href="{{ route('utilisateur.profile', $aff->user->code_user) }}"
                                           class="sl-btn-action sl-btn-action-edit sl-btn-action-label"
                                           title="Ouvrir le profil et l’enrôlement GUOT">
                                            <i class="fas fa-id-card"></i>
                                            <span>Profil</span>
                                        </a>
                                    </div>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Aucun signataire trouvé pour ce filtre.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($signataires->total() > 0)
            <div class="card-footer sl-pagination d-flex flex-wrap align-items-center justify-content-between gap-2">
                <span class="small text-muted mb-0">
                    Affichage de <strong>{{ $signataires->firstItem() }}</strong>
                    à <strong>{{ $signataires->lastItem() }}</strong>
                    sur <strong>{{ $signataires->total() }}</strong>
                </span>
                @if($signataires->hasPages())
                    <div class="sl-pagination-links">{{ $signataires->links('pagination::bootstrap-4') }}</div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
