@extends('layout.app')
@section('titre')
    Gestion des Utilisateurs
@endsection
@section('styles')
<style>
    /* ── Page : ambiance institutionnelle sobre ───────────────────────── */
    .page-users-sifec {
        --u-ink: #1a2e26;
        --u-ink-muted: #5c6d66;
        --u-green: #0f5132;
        --u-green-soft: #e8f0eb;
        --u-green-mid: #1b6f4a;
        --u-cream: #fafaf8;
        --u-paper: #ffffff;
        --u-line: #e2e8e4;
        --u-gold: #9a7b2c;
        --u-gold-soft: #f5f0e6;
        --u-danger: #9b2c2c;
        --u-danger-soft: #fce8e8;
        --u-shadow: 0 1px 3px rgba(26, 46, 38, 0.06);
        --u-shadow-lg: 0 12px 40px rgba(26, 46, 38, 0.08);
        --u-radius: 14px;
        --u-radius-sm: 10px;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }

    .page-users-sifec .users-shell {
        position: relative;
        background: linear-gradient(180deg, var(--u-cream) 0%, #f0f3f1 100%);
        border-radius: var(--u-radius);
        padding: 2rem 2rem 2.25rem;
        box-shadow: var(--u-shadow-lg);
        border: 1px solid var(--u-line);
        overflow: hidden;
    }

    /* Filigrane décoratif discret (remplace un filigrane visuel lourd) */
    .page-users-sifec .users-shell::before {
        content: '';
        position: absolute;
        inset: -20% -10% auto auto;
        width: 420px;
        height: 420px;
        background: radial-gradient(circle, rgba(15, 81, 50, 0.04) 0%, transparent 70%);
        pointer-events: none;
    }

    .page-users-sifec .users-header {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1.25rem;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--u-line);
        position: relative;
        z-index: 1;
    }

    .page-users-sifec .users-header h1 {
        font-size: 1.5rem;
        font-weight: 600;
        letter-spacing: -0.02em;
        color: var(--u-ink);
        margin: 0 0 0.35rem;
    }

    .page-users-sifec .users-header .users-sub {
        font-size: 0.875rem;
        color: var(--u-ink-muted);
        margin: 0;
        max-width: 36rem;
        line-height: 1.5;
    }

    .page-users-sifec .users-header .users-sub::before {
        content: '';
        display: block;
        width: 36px;
        height: 3px;
        background: linear-gradient(90deg, var(--u-gold), rgba(154, 123, 44, 0.2));
        border-radius: 2px;
        margin-bottom: 0.65rem;
    }

    .page-users-sifec .btn-users-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #fff !important;
        background: linear-gradient(135deg, var(--u-green-mid) 0%, var(--u-green) 100%);
        border: none;
        border-radius: var(--u-radius-sm);
        box-shadow: 0 4px 14px rgba(15, 81, 50, 0.25);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        text-decoration: none !important;
    }

    .page-users-sifec .btn-users-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(15, 81, 50, 0.3);
        color: #fff !important;
    }

    /* Cartes KPI */
    .page-users-sifec .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.75rem;
        position: relative;
        z-index: 1;
    }

    @media (max-width: 1199px) {
        .page-users-sifec .kpi-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 575px) {
        .page-users-sifec .kpi-grid { grid-template-columns: 1fr; }
    }

    .page-users-sifec .kpi-card {
        background: var(--u-paper);
        border: 1px solid var(--u-line);
        border-radius: var(--u-radius-sm);
        padding: 1.15rem 1.25rem;
        box-shadow: var(--u-shadow);
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .page-users-sifec .kpi-card:hover {
        border-color: rgba(15, 81, 50, 0.15);
        box-shadow: 0 8px 24px rgba(26, 46, 38, 0.07);
    }

    .page-users-sifec .kpi-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        flex-shrink: 0;
    }

    .page-users-sifec .kpi-card--total .kpi-icon {
        background: var(--u-green-soft);
        color: var(--u-green);
    }
    .page-users-sifec .kpi-card--active .kpi-icon {
        background: #e6f4ea;
        color: #1e7e4a;
    }
    .page-users-sifec .kpi-card--inactive .kpi-icon {
        background: var(--u-danger-soft);
        color: var(--u-danger);
    }
    .page-users-sifec .kpi-card--2fa .kpi-icon {
        background: var(--u-gold-soft);
        color: var(--u-gold);
    }

    .page-users-sifec .kpi-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--u-ink-muted);
        margin-bottom: 0.2rem;
    }

    .page-users-sifec .kpi-value {
        font-size: 1.75rem;
        font-weight: 700;
        letter-spacing: -0.03em;
        color: var(--u-ink);
        line-height: 1.1;
    }

    /* Filtres */
    .page-users-sifec .toolbar-filters {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 0.75rem;
        position: relative;
        z-index: 1;
    }

    .page-users-sifec .btn-filter-toggle {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.45rem 1rem;
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--u-ink);
        background: var(--u-paper);
        border: 1px solid var(--u-line);
        border-radius: 999px;
        box-shadow: var(--u-shadow);
        transition: border-color 0.2s, background 0.2s;
    }

    .page-users-sifec .btn-filter-toggle:hover {
        border-color: rgba(15, 81, 50, 0.25);
        background: var(--u-green-soft);
    }

    .page-users-sifec .filters-panel {
        background: var(--u-paper);
        border: 1px solid var(--u-line);
        border-radius: var(--u-radius-sm);
        padding: 1.25rem 1.35rem;
        margin-bottom: 1.25rem;
        box-shadow: var(--u-shadow);
    }

    .page-users-sifec .filters-panel .form-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--u-ink-muted);
    }

    .page-users-sifec .filters-panel .form-control {
        border-radius: 8px;
        border-color: var(--u-line);
        font-size: 0.875rem;
    }

    .page-users-sifec .filters-panel .form-control:focus {
        border-color: var(--u-green-mid);
        box-shadow: 0 0 0 3px rgba(27, 111, 74, 0.12);
    }

    /* Bulk */
    .bulk-panel {
        background: linear-gradient(135deg, #1e2934 0%, #2d3a47 100%);
        border-radius: var(--u-radius-sm);
        padding: 14px 20px;
        display: none;
        margin-bottom: 16px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
        box-shadow: var(--u-shadow-lg);
    }
    .bulk-panel.visible { display: flex; }
    .bulk-count {
        color: #e8c76a;
        font-weight: 600;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .bulk-info-text { color: rgba(255, 255, 255, 0.55); font-size: 0.8rem; }

    .user-checkbox, .select-all-checkbox {
        width: 17px;
        height: 17px;
        cursor: pointer;
        accent-color: var(--u-green-mid);
    }
    .select-all-checkbox { accent-color: #e8c76a; }

    tr.row-selected td {
        background-color: rgba(27, 111, 74, 0.06) !important;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        color: #fff;
        flex-shrink: 0;
        object-fit: cover;
        border: 2px solid var(--u-paper);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    /* Tableau premium */
    .page-users-sifec .table-users-wrap {
        border: 1px solid var(--u-line);
        border-radius: var(--u-radius-sm);
        overflow: hidden;
        background: var(--u-paper);
        box-shadow: var(--u-shadow);
        position: relative;
        z-index: 1;
    }

    .page-users-sifec .table-users {
        margin-bottom: 0;
        font-size: 0.875rem;
    }

    .page-users-sifec .table-users thead th {
        background: linear-gradient(180deg, #f4f7f5 0%, #eef2ef 100%);
        color: var(--u-ink);
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        border-bottom: 2px solid var(--u-line);
        padding: 0.9rem 0.85rem;
        vertical-align: middle;
        white-space: nowrap;
    }

    .page-users-sifec .table-users tbody td {
        padding: 0.85rem 0.85rem;
        border-color: #f0f2f0;
        vertical-align: middle;
    }

    .page-users-sifec .table-users tbody tr:hover td {
        background: #fafcfb;
    }

    .page-users-sifec .agent-name {
        font-weight: 600;
        color: var(--u-ink);
        font-size: 0.875rem;
    }

    .page-users-sifec .agent-meta {
        font-size: 0.75rem;
        color: var(--u-ink-muted);
    }

    .page-users-sifec .cell-muted {
        color: var(--u-ink-muted);
        font-size: 0.8125rem;
    }

    .page-users-sifec .badge-pill-status {
        display: inline-flex;
        align-items: center;
        padding: 0.35em 0.75em;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.02em;
        border-radius: 999px;
    }

    .page-users-sifec .badge-pill-status--ok {
        background: #e6f4ea;
        color: #146c2e;
    }

    .page-users-sifec .badge-pill-status--off {
        background: var(--u-danger-soft);
        color: var(--u-danger);
    }

    .page-users-sifec .badge-pill-2fa-on {
        background: var(--u-green-soft);
        color: var(--u-green);
        font-size: 0.7rem;
        padding: 0.35em 0.65em;
        border-radius: 999px;
        font-weight: 600;
    }

    .page-users-sifec .badge-pill-2fa-off {
        background: #f1f2f3;
        color: var(--u-ink-muted);
        font-size: 0.7rem;
        padding: 0.35em 0.65em;
        border-radius: 999px;
        border: 1px solid var(--u-line);
        font-weight: 500;
    }

    /* Actions : contraste élevé (les liens héritent souvent d’une couleur claire du layout) */
    .page-users-sifec .action-group {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        padding: 5px 6px;
        background: #fff;
        border-radius: 10px;
        border: 1px solid #c8d4cc;
        box-shadow: 0 1px 2px rgba(26, 46, 38, 0.06);
    }

    .page-users-sifec a.action-btn,
    .page-users-sifec button.action-btn {
        width: 36px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        border-radius: 8px;
        background: #f0f4f1;
        text-decoration: none !important;
        cursor: pointer;
        transition: background 0.15s, color 0.15s, box-shadow 0.15s, transform 0.12s;
    }

    .page-users-sifec .action-btn i {
        font-size: 0.95rem;
        line-height: 1;
    }

    /* Couleurs d’icônes explicites (état normal + !important pour écraser le thème global) */
    .page-users-sifec a.action-btn:not(.action-btn--warn):not(.action-btn--info):not(.action-btn--toggle):not(.action-btn--danger),
    .page-users-sifec button.action-btn:not(.action-btn--warn):not(.action-btn--info):not(.action-btn--toggle):not(.action-btn--danger) {
        color: #0a5c3c !important;
    }

    .page-users-sifec .action-btn--warn {
        color: #8b6914 !important;
    }

    .page-users-sifec .action-btn--info {
        color: #0b5a96 !important;
    }

    .page-users-sifec .action-btn--toggle {
        color: #374151 !important;
    }

    .page-users-sifec .action-btn--danger {
        color: #b42318 !important;
    }

    .page-users-sifec .action-btn:hover {
        background: #fff !important;
        box-shadow: 0 2px 8px rgba(26, 46, 38, 0.12);
        transform: translateY(-1px);
    }

    .page-users-sifec a.action-btn:not(.action-btn--warn):not(.action-btn--info):not(.action-btn--toggle):not(.action-btn--danger):hover,
    .page-users-sifec button.action-btn:not(.action-btn--warn):not(.action-btn--info):not(.action-btn--toggle):not(.action-btn--danger):hover {
        color: #06402a !important;
    }

    .page-users-sifec .action-btn--warn:hover {
        color: #5c4500 !important;
        background: #fdf6e5 !important;
    }

    .page-users-sifec .action-btn--info:hover {
        color: #063d66 !important;
        background: #e8f2fa !important;
    }

    .page-users-sifec .action-btn--toggle:hover {
        color: #111827 !important;
        background: #f3f4f6 !important;
    }

    .page-users-sifec .action-btn--danger:hover {
        background: #fde8e6 !important;
        color: #8f1f15 !important;
    }

    .page-users-sifec .table-foot {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1.25rem;
        padding-top: 1rem;
        border-top: 1px solid var(--u-line);
        font-size: 0.8125rem;
        color: var(--u-ink-muted);
    }

    .page-users-sifec .table-foot select.form-control-sm {
        border-radius: 8px;
        border-color: var(--u-line);
    }

    /* Modal raffiné */
    .modal-users-delete .modal-content {
        border: none;
        border-radius: var(--u-radius);
        overflow: hidden;
        box-shadow: var(--u-shadow-lg);
    }

    .modal-users-delete .modal-header {
        background: linear-gradient(135deg, #7f1d1d 0%, #9b2c2c 100%);
        border: none;
        padding: 1.1rem 1.35rem;
    }

    .modal-users-delete .modal-title {
        font-weight: 600;
        font-size: 1rem;
    }

    .modal-users-delete .modal-body {
        padding: 1.35rem;
    }

    .modal-users-delete .modal-footer {
        border-top: 1px solid var(--u-line);
        padding: 1rem 1.35rem;
    }

    .page-users-sifec .btn-users-primary.sifec-btn-loading,
    .page-users-sifec .btn-danger.sifec-btn-loading {
        pointer-events: none;
        opacity: 0.92;
    }
</style>
@endsection

@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
<div class="row page-users-sifec">
    <div class="col-12">
        <div class="users-shell">
            <header class="users-header">
                <div>
                    <h1>Gestion des utilisateurs</h1>
                    <p class="users-sub">Administration des comptes agents, affectations aux centres d’état civil et sécurisation (2FA).</p>
                </div>
                <a href="{{ route('utilisateur.create') }}" class="btn-users-primary">
                    <i class="fa fa-user-plus"></i>
                    <span>Nouvel utilisateur</span>
                </a>
            </header>

            {{-- KPIs --}}
            <div class="kpi-grid">
                <div class="kpi-card kpi-card--total">
                    <div class="kpi-icon"><i class="fas fa-users"></i></div>
                    <div>
                        <div class="kpi-label">Total utilisateurs</div>
                        <div class="kpi-value">{{ $users->total() }}</div>
                    </div>
                </div>
                <div class="kpi-card kpi-card--active">
                    <div class="kpi-icon"><i class="fas fa-user-check"></i></div>
                    <div>
                        <div class="kpi-label">Actifs</div>
                        <div class="kpi-value">{{ $allUsers->where('status', 1)->count() }}</div>
                    </div>
                </div>
                <div class="kpi-card kpi-card--inactive">
                    <div class="kpi-icon"><i class="fas fa-user-slash"></i></div>
                    <div>
                        <div class="kpi-label">Inactifs</div>
                        <div class="kpi-value">{{ $allUsers->where('status', 0)->count() }}</div>
                    </div>
                </div>
                <div class="kpi-card kpi-card--2fa">
                    <div class="kpi-icon"><i class="fas fa-shield-alt"></i></div>
                    <div>
                        <div class="kpi-label">2FA activée</div>
                        <div class="kpi-value">{{ $allUsers->where('google2fa_enabled', true)->count() }}</div>
                    </div>
                </div>
            </div>

            <div class="toolbar-filters">
                <button class="btn btn-filter-toggle" type="button"
                        data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                    <i class="fas fa-sliders-h"></i>
                    <span>Filtres et recherche</span>
                    @if(request()->hasAny(['status','institution','fonction','search']))
                        <span class="badge rounded-pill" style="background: var(--u-gold-soft); color: var(--u-gold); font-weight:600;">actifs</span>
                    @endif
                </button>
            </div>

            @php $filtersOpen = request()->hasAny(['status','institution','fonction','search']); @endphp
            <div class="collapse {{ $filtersOpen ? 'show' : '' }}" id="filtersCollapse">
                <div class="filters-panel">
                    <form method="GET" action="{{ route('utilisateur.index') }}" id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Statut</label>
                                <select class="form-control" name="status" id="statusFilter">
                                    <option value="">Tous les statuts</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Actif</option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactif</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Institution</label>
                                <select class="form-control" name="institution" id="institutionFilter">
                                    <option value="">Toutes les institutions</option>
                                    @foreach($allUsers->map(fn($u) => $u->affectationActive()?->institution?->lib_institution)->filter()->unique() as $institution)
                                        <option value="{{ $institution }}" {{ request('institution') == $institution ? 'selected' : '' }}>{{ $institution }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Fonction</label>
                                <select class="form-control" name="fonction" id="fonctionFilter">
                                    <option value="">Toutes les fonctions</option>
                                    @foreach($allUsers->map(fn($u) => $u->affectationActive()?->fonction?->lib_fonction)->filter()->unique() as $fonction)
                                        <option value="{{ $fonction }}" {{ request('fonction') == $fonction ? 'selected' : '' }}>{{ $fonction }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Recherche</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" id="searchInput"
                                           placeholder="Nom, e-mail…" value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-users-primary px-3" id="filterSearchBtn"><i class="fas fa-search"></i></button>
                                    <a href="{{ route('utilisateur.index') }}" class="btn btn-outline-secondary px-3" title="Réinitialiser">
                                        <i class="fas fa-redo"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bulk-panel" id="bulkPanel">
                <div class="bulk-count">
                    <i class="fas fa-check-square"></i>
                    <span id="selectedCount">0</span> utilisateur(s) sélectionné(s)
                </div>
                <div class="d-flex gap-2 flex-wrap align-items-center ms-auto">
                    <span class="bulk-info-text">Action groupée :</span>
                    <button type="button" class="btn btn-sm btn-success rounded-pill px-3" onclick="confirmBulk('enable')">
                        <i class="fas fa-shield-alt me-1"></i> Activer la 2FA
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3" onclick="confirmBulk('disable')">
                        <i class="fas fa-shield-virus me-1"></i> Désactiver la 2FA
                    </button>
                    <button type="button" class="btn btn-sm btn-link text-white-50 text-decoration-none" onclick="clearSelection()">
                        <i class="fas fa-times me-1"></i> Annuler
                    </button>
                </div>
            </div>

            <div class="table-users-wrap table-responsive">
                <table class="table table-users table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="44" class="text-center">
                                <input type="checkbox" class="select-all-checkbox" id="selectAll" title="Tout sélectionner">
                            </th>
                            <th width="48" class="text-center">#</th>
                            <th>Agent</th>
                            <th>Identifiant</th>
                            <th>Centre d’état civil</th>
                            <th>Fonction</th>
                            <th class="text-center">Statut</th>
                            <th class="text-center">2FA</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php $i = ($users->currentPage() - 1) * $users->perPage() + 1; @endphp
                    @foreach ($users as $user)
                        <tr class="user-row" data-id="{{ $user->code_user }}">
                            <td class="text-center">
                                <input type="checkbox" class="user-checkbox" name="user_ids[]"
                                       value="{{ $user->code_user }}" onchange="updateBulkPanel()">
                            </td>
                            <td class="text-center cell-muted"><strong>{{ $i++ }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($user->personne && $user->personne->signature)
                                        @php
                                            $signaturePath = $user->personne->signature;
                                            if (strpos($signaturePath, 'signature/') === 0) {
                                                $signaturePath = str_replace('signature/', 'signatures/', $signaturePath);
                                            }
                                            if (strpos($signaturePath, 'signatures/') === 0 || strpos($signaturePath, 'storage/') === 0) {
                                                $imageUrl = asset($signaturePath);
                                            } else {
                                                $imageUrl = asset('storage/'.$signaturePath);
                                            }
                                        @endphp
                                        <img src="{{ $imageUrl }}" alt="" class="user-avatar"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="user-avatar" style="display:none;background:{{ ($user->personne->sexe ?? '') == 'F' ? '#9b2c4a' : '#1b6f4a' }};">
                                            <i class="fas fa-{{ ($user->personne->sexe ?? '') == 'F' ? 'female' : 'male' }}"></i>
                                        </div>
                                    @else
                                        <div class="user-avatar" style="background:{{ ($user->personne->sexe ?? '') == 'F' ? '#9b2c4a' : '#1b6f4a' }};">
                                            <i class="fas fa-{{ ($user->personne->sexe ?? '') == 'F' ? 'female' : 'male' }}"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="agent-name">{{ $user->personne->nom ?? 'N/A' }} {{ $user->personne->prenom ?? 'N/A' }}</div>
                                        @if($user->personne && $user->personne->telephone)
                                            <div class="agent-meta"><i class="fas fa-phone-alt me-1"></i>{{ $user->personne->telephone }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="cell-muted">{{ $user->email }}</td>
                            <td class="cell-muted">
                                @php $lib_ins = $user->affectationActive()?->institution?->lib_institution; @endphp
                                {{ $lib_ins ?? '—' }}
                            </td>
                            <td class="cell-muted">
                                @php $lib_fn = $user->affectationActive()?->fonction?->lib_fonction; @endphp
                                {{ $lib_fn ?? '—' }}
                            </td>
                            <td class="text-center">
                                @if($user->status == 1)
                                    <span class="badge-pill-status badge-pill-status--ok">Actif</span>
                                @else
                                    <span class="badge-pill-status badge-pill-status--off">Inactif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($user->hasTwoFactorEnabled())
                                    <span class="badge-pill-2fa-on"><i class="fas fa-shield-alt me-1"></i>Oui</span>
                                @else
                                    <span class="badge-pill-2fa-off"><i class="fas fa-shield-alt me-1"></i>Non</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="action-group justify-content-center">
                                    <a href="{{ route('utilisateur.profile',$user->code_user) }}"
                                       class="action-btn" title="Profil"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('utilisateur.edit',$user->code_user) }}"
                                       class="action-btn action-btn--warn" title="Modifier"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('utilisateur.assigner.permission',$user->code_user) }}"
                                       class="action-btn action-btn--info" title="Permissions"><i class="fas fa-key"></i></a>
                                    <button type="button"
                                            class="action-btn action-btn--toggle"
                                            onclick="toggleUserStatus('{{ $user->code_user }}', {{ $user->status }})"
                                            title="{{ $user->status ? 'Désactiver' : 'Activer' }}">
                                        <i class="fas fa-{{ $user->status ? 'pause' : 'play' }}"></i>
                                    </button>
                                    <button type="button"
                                            class="action-btn action-btn--danger"
                                            onclick="deleteUser('{{ $user->code_user }}', '{{ $user->personne->nom }} {{ $user->personne->prenom }}')"
                                            title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="table-foot">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <span>
                        Affichage de <strong>{{ $users->firstItem() ?? 0 }}</strong> à <strong>{{ $users->lastItem() ?? 0 }}</strong>
                        sur <strong>{{ $users->total() }}</strong> utilisateurs
                    </span>
                    <form method="GET" action="{{ route('utilisateur.index') }}" id="perPageForm" class="d-flex align-items-center gap-2 ms-md-2">
                        @foreach(request()->except(['page', 'per_page']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <label class="mb-0">Lignes par page</label>
                        <select name="per_page" class="form-control form-control-sm" style="width:auto; min-width:4.5rem;" onchange="this.form.submit()">
                            <option value="10"  {{ request('per_page') == 10  ? 'selected' : '' }}>10</option>
                            <option value="15"  {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                            <option value="25"  {{ request('per_page') == 25  ? 'selected' : '' }}>25</option>
                            <option value="50"  {{ request('per_page') == 50  ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </form>
                </div>
                <div>{{ $users->links('pagination::bootstrap-4') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-users-delete" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">Supprimer définitivement l’utilisateur <strong id="userName"></strong> ?</p>
                <p class="text-danger mb-0"><small>Cette action est irréversible.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form id="deleteForm" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="deleteUserConfirmBtn">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<form id="toggleStatusForm" method="POST" action="" style="display:none;">
    @csrf
    @method('PATCH')
</form>
</div>
</div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#statusFilter, #institutionFilter, #fonctionFilter').on('change', function() {
        $('#filterForm').submit();
    });
});

function updateBulkPanel() {
    var checked = document.querySelectorAll('.user-checkbox:checked');
    var count   = checked.length;
    document.getElementById('selectedCount').textContent = count;

    var panel = document.getElementById('bulkPanel');
    if (count > 0) { panel.classList.add('visible'); }
    else           { panel.classList.remove('visible'); }

    document.querySelectorAll('.user-row').forEach(function(row) {
        var cb = row.querySelector('.user-checkbox');
        if (cb && cb.checked) { row.classList.add('row-selected'); }
        else                  { row.classList.remove('row-selected'); }
    });

    var total     = document.querySelectorAll('.user-checkbox').length;
    var selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.checked       = (count === total && total > 0);
        selectAll.indeterminate = (count > 0 && count < total);
    }
}

document.getElementById('selectAll').addEventListener('change', function() {
    var checked = this.checked;
    document.querySelectorAll('.user-checkbox').forEach(function(cb) { cb.checked = checked; });
    updateBulkPanel();
});

function clearSelection() {
    document.querySelectorAll('.user-checkbox').forEach(function(cb) { cb.checked = false; });
    document.getElementById('selectAll').checked       = false;
    document.getElementById('selectAll').indeterminate = false;
    document.getElementById('bulkPanel').classList.remove('visible');
    document.querySelectorAll('.user-row').forEach(function(r) { r.classList.remove('row-selected'); });
}

function executeBulk2FA(action, userIds) {
    $('.btn-bulk-enable, .btn-bulk-disable').prop('disabled', true).css('opacity','0.6');
    $.ajax({
        url: '{{ route("utilisateur.bulk-2fa") }}',
        method: 'POST',
        data: { action: action, user_ids: userIds },
        traditional: false,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: 'Opération réussie', html: response.message, icon: 'success',
                    confirmButtonColor: '#1b6f4a',
                    confirmButtonText: 'OK'
                }).then(function() { window.location.reload(); });
            } else {
                Swal.fire({ title: 'Erreur', html: response.message || 'Une erreur est survenue.', icon: 'error', confirmButtonColor: '#9b2c2c' });
                $('.btn-bulk-enable, .btn-bulk-disable').prop('disabled', false).css('opacity','1');
            }
        },
        error: function(xhr) {
            var msg;
            if (xhr.status === 419) { msg = 'Session expirée. Rechargez la page et réessayez.'; }
            else if (xhr.status === 422) {
                try { var j = $.parseJSON(xhr.responseText); msg = j.message || 'Données invalides.'; } catch(e) { msg = 'Données invalides (422).'; }
            } else {
                try { var j2 = $.parseJSON(xhr.responseText); msg = j2.message || 'Erreur serveur (' + xhr.status + ').'; } catch(e) { msg = 'Erreur serveur (' + xhr.status + ').'; }
            }
            Swal.fire({ title: 'Erreur', html: msg, icon: 'error', confirmButtonColor: '#9b2c2c' });
            $('.btn-bulk-enable, .btn-bulk-disable').prop('disabled', false).css('opacity','1');
        }
    });
}

function confirmBulk(action) {
    var userIds = $.map($('.user-checkbox:checked'), function(cb) { return $(cb).val(); });
    if (userIds.length === 0) return;

    var label    = action === 'enable'
        ? '<span style="color:#1b6f4a;font-weight:700;">ACTIVER</span> la double authentification'
        : '<span style="color:#9b2c2c;font-weight:700;">DÉSACTIVER</span> la double authentification';
    var extraMsg = action === 'enable'
        ? '<br><br><small style="color:#6b5a1a;background:#f5f0e6;padding:8px 12px;border-radius:8px;display:block;margin-top:8px;">Chaque utilisateur recevra son QR code et ses codes de récupération par e-mail.</small>'
        : '<br><br><small style="color:#721c24;background:#fce8e8;padding:8px 12px;border-radius:8px;display:block;margin-top:8px;">La 2FA sera immédiatement désactivée pour les utilisateurs sélectionnés.</small>';

    Swal.fire({
        title: 'Confirmer l\'action',
        html: 'Vous allez ' + label + ' pour <strong>' + userIds.length + ' utilisateur(s)</strong>.' + extraMsg,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: action === 'enable' ? 'Activer la 2FA' : 'Désactiver la 2FA',
        cancelButtonText: 'Annuler',
        confirmButtonColor: action === 'enable' ? '#1b6f4a' : '#9b2c2c',
        cancelButtonColor: '#6c757d',
        reverseButtons: true,
    }).then(function(result) {
        var confirmed = (result === true) || (result && result.value === true) || (result && result.isConfirmed === true);
        if (!confirmed) return;
        executeBulk2FA(action, userIds);
    });
}

function toggleUserStatus(userId, currentStatus) {
    var action = currentStatus ? 'désactiver' : 'activer';
    var label  = currentStatus ? 'Désactiver' : 'Activer';
    var color  = currentStatus ? '#9b2c2c' : '#1b6f4a';

    Swal.fire({
        title: 'Confirmer l\'action',
        html: 'Êtes-vous sûr de vouloir <strong style="color:' + color + ';">' + action + '</strong> ce compte utilisateur ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: label,
        cancelButtonText: 'Annuler',
        confirmButtonColor: color,
        cancelButtonColor: '#6c757d',
        reverseButtons: true,
    }).then(function(result) {
        var confirmed = (result === true) || (result && result.value === true) || (result && result.isConfirmed === true);
        if (!confirmed) return;
        var form = document.getElementById('toggleStatusForm');
        form.action = '/utilisateur/' + userId + '/toggle-status';
        form.submit();
    });
}

function deleteUser(userId, userName) {
    $('#userName').text(userName);
    $('#deleteForm').attr('action', '/utilisateur/' + userId);
    var delBtn = document.getElementById('deleteUserConfirmBtn');
    if (delBtn) {
        delBtn.removeAttribute('data-sifec-submitting');
        delBtn.disabled = false;
        delBtn.removeAttribute('aria-busy');
        delBtn.classList.remove('sifec-btn-loading');
        if (delBtn.getAttribute('data-sifec-html')) {
            delBtn.innerHTML = delBtn.getAttribute('data-sifec-html');
        }
    }
    $('#deleteModal').modal('show');
}

(function () {
    var filterForm = document.getElementById('filterForm');
    var filterBtn = document.getElementById('filterSearchBtn');
    if (filterForm && filterBtn) {
        filterForm.addEventListener('submit', function () {
            if (filterBtn.getAttribute('data-sifec-submitting') === '1') return;
            filterBtn.setAttribute('data-sifec-submitting', '1');
            if (!filterBtn.getAttribute('data-sifec-html')) {
                filterBtn.setAttribute('data-sifec-html', filterBtn.innerHTML);
            }
            filterBtn.disabled = true;
            filterBtn.setAttribute('aria-busy', 'true');
            filterBtn.classList.add('sifec-btn-loading');
            filterBtn.innerHTML = '<i class="fas fa-spinner fa-spin" aria-hidden="true"></i>';
        });
    }

    var perPageForm = document.getElementById('perPageForm');
    if (perPageForm) {
        perPageForm.addEventListener('submit', function () {
            var sel = perPageForm.querySelector('select[name="per_page"]');
            if (!sel || sel.getAttribute('data-sifec-loading') === '1') return;
            sel.setAttribute('data-sifec-loading', '1');
            sel.disabled = true;
        });
    }

    var deleteForm = document.getElementById('deleteForm');
    var deleteBtn = document.getElementById('deleteUserConfirmBtn');
    if (deleteForm && deleteBtn) {
        deleteForm.addEventListener('submit', function () {
            if (deleteBtn.getAttribute('data-sifec-submitting') === '1') return;
            deleteBtn.setAttribute('data-sifec-submitting', '1');
            if (!deleteBtn.getAttribute('data-sifec-html')) {
                deleteBtn.setAttribute('data-sifec-html', deleteBtn.innerHTML);
            }
            deleteBtn.disabled = true;
            deleteBtn.setAttribute('aria-busy', 'true');
            deleteBtn.classList.add('sifec-btn-loading');
            deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1" aria-hidden="true"></i>Suppression…';
        });
    }
})();
</script>
@endsection
