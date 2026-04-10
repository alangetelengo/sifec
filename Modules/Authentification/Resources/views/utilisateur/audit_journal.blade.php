@extends('layout.app')

@section('titre')
    Journal d'audit — utilisateurs
@endsection

@php
    $hasFilters = request()->filled('code_user') || request()->filled('action')
        || request()->filled('date_debut') || request()->filled('date_fin');

    $auditPillClass = function ($action) {
        if (strpos($action, '2fa_') === 0 || strpos($action, 'recovery_') === 0) {
            return 'audit-pill audit-pill--twofa';
        }
        if ($action === 'profile_update' || $action === 'password_change') {
            return 'audit-pill audit-pill--profile';
        }
        if ($action === 'login_failed') {
            return 'audit-pill audit-pill--danger';
        }
        if ($action === 'login' || $action === 'logout') {
            return 'audit-pill audit-pill--session';
        }
        if (strpos($action, 'permission_') === 0) {
            return 'audit-pill audit-pill--perm';
        }
        if (strpos($action, 'account_') === 0) {
            return 'audit-pill audit-pill--status';
        }

        return 'audit-pill audit-pill--neutral';
    };
@endphp

@section('styles')
<style>
    .page-audit-sifec {
        --a-ink: #1a2e26;
        --a-ink-muted: #5c6d66;
        --a-green: #0f5132;
        --a-green-soft: #e8f0eb;
        --a-green-mid: #1b6f4a;
        --a-cream: #fafaf8;
        --a-paper: #ffffff;
        --a-line: #e2e8e4;
        --a-gold: #9a7b2c;
        --a-gold-soft: #f5f0e6;
        --a-danger: #9b2c2c;
        --a-danger-soft: #fce8e8;
        --a-shadow: 0 1px 3px rgba(26, 46, 38, 0.06);
        --a-shadow-lg: 0 12px 40px rgba(26, 46, 38, 0.08);
        --a-radius: 14px;
        --a-radius-sm: 10px;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        margin: -0.35rem -0.5rem 0;
        padding: 0.5rem 0.5rem 2rem;
        min-height: calc(100vh - 100px);
        background: linear-gradient(180deg, var(--a-cream) 0%, #eef1ee 100%);
    }

    .page-audit-sifec .audit-breadcrumb {
        font-size: 0.875rem;
        margin-bottom: 1rem;
        background: var(--a-paper);
        border: 1px solid var(--a-line);
        border-radius: var(--a-radius-sm);
        padding: 0.65rem 1.15rem;
        box-shadow: var(--a-shadow);
    }
    .page-audit-sifec .audit-breadcrumb .breadcrumb { margin-bottom: 0; }
    .page-audit-sifec .audit-breadcrumb .breadcrumb-item { color: #475569 !important; }
    .page-audit-sifec .audit-breadcrumb .breadcrumb-item a {
        color: var(--a-green-mid) !important;
        font-weight: 600;
        text-decoration: none;
    }
    .page-audit-sifec .audit-breadcrumb .breadcrumb-item a:hover {
        color: var(--a-green) !important;
        text-decoration: underline;
    }
    .page-audit-sifec .audit-breadcrumb .breadcrumb-item.active {
        color: var(--a-ink) !important;
        font-weight: 700;
    }
    .page-audit-sifec .audit-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
        color: #94a3b8 !important;
    }

    .page-audit-sifec .audit-shell {
        position: relative;
        background: linear-gradient(180deg, var(--a-paper) 0%, #fbfcfb 100%);
        border-radius: var(--a-radius);
        padding: 1.75rem 1.75rem 2rem;
        box-shadow: var(--a-shadow-lg);
        border: 1px solid var(--a-line);
        overflow: hidden;
    }
    .page-audit-sifec .audit-shell::before {
        content: '';
        position: absolute;
        inset: -18% -8% auto auto;
        width: 380px;
        height: 380px;
        background: radial-gradient(circle, rgba(15, 81, 50, 0.05) 0%, transparent 72%);
        pointer-events: none;
    }

    .page-audit-sifec .audit-header {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1.25rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1.35rem;
        border-bottom: 1px solid var(--a-line);
        position: relative;
        z-index: 1;
    }
    .page-audit-sifec .audit-header h1 {
        font-size: 1.45rem;
        font-weight: 600;
        letter-spacing: -0.02em;
        color: var(--a-ink);
        margin: 0 0 0.4rem;
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }
    .page-audit-sifec .audit-header-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--a-green-soft) 0%, #d8e8df 100%);
        color: var(--a-green);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(15, 81, 50, 0.12);
    }
    .page-audit-sifec .audit-sub {
        font-size: 0.875rem;
        color: var(--a-ink-muted);
        margin: 0;
        max-width: 38rem;
        line-height: 1.55;
    }
    .page-audit-sifec .audit-sub::before {
        content: '';
        display: block;
        width: 36px;
        height: 3px;
        background: linear-gradient(90deg, var(--a-gold), rgba(154, 123, 44, 0.2));
        border-radius: 2px;
        margin-bottom: 0.6rem;
    }

    .page-audit-sifec .btn-audit-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 1.2rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #fff !important;
        background: linear-gradient(135deg, var(--a-green-mid) 0%, var(--a-green) 100%);
        border: none;
        border-radius: var(--a-radius-sm);
        box-shadow: 0 4px 14px rgba(15, 81, 50, 0.22);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        text-decoration: none !important;
    }
    .page-audit-sifec .btn-audit-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(15, 81, 50, 0.28);
        color: #fff !important;
    }

    .page-audit-sifec .audit-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
        position: relative;
        z-index: 1;
    }
    .page-audit-sifec .audit-stat-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: var(--a-paper);
        border: 1px solid var(--a-line);
        border-radius: 999px;
        font-size: 0.8125rem;
        color: var(--a-ink);
        box-shadow: var(--a-shadow);
    }
    .page-audit-sifec .audit-stat-chip strong {
        font-weight: 700;
        color: var(--a-green);
    }
    .page-audit-sifec .audit-stat-chip--filter {
        background: var(--a-gold-soft);
        border-color: rgba(154, 123, 44, 0.35);
        color: #5c4a1a;
    }

    .page-audit-sifec .audit-info {
        background: linear-gradient(135deg, var(--a-green-soft) 0%, #f0f6f2 100%);
        border: 1px solid rgba(15, 81, 50, 0.12);
        border-radius: var(--a-radius-sm);
        padding: 0.9rem 1.15rem;
        font-size: 0.8125rem;
        color: var(--a-ink-muted);
        margin-bottom: 1.25rem;
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
    }
    .page-audit-sifec .audit-info i {
        color: var(--a-green-mid);
        margin-top: 0.15rem;
        flex-shrink: 0;
    }
    .page-audit-sifec .audit-info code {
        font-size: 0.78rem;
        background: rgba(255, 255, 255, 0.85);
        padding: 0.12rem 0.4rem;
        border-radius: 4px;
        border: 1px solid rgba(15, 81, 50, 0.1);
        color: var(--a-green);
    }

    .page-audit-sifec .filters-panel {
        background: var(--a-paper);
        border: 1px solid var(--a-line);
        border-radius: var(--a-radius-sm);
        padding: 1.25rem 1.35rem;
        margin-bottom: 1.35rem;
        box-shadow: var(--a-shadow);
        position: relative;
        z-index: 1;
    }
    .page-audit-sifec .filters-panel .filters-title {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--a-ink-muted);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }
    .page-audit-sifec .filters-panel .form-label {
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--a-ink-muted);
        margin-bottom: 0.35rem;
    }
    .page-audit-sifec .filters-panel .form-control,
    .page-audit-sifec .filters-panel .form-select {
        border-radius: 8px;
        border-color: var(--a-line);
        font-size: 0.875rem;
    }
    .page-audit-sifec .filters-panel .form-control:focus,
    .page-audit-sifec .filters-panel .form-select:focus {
        border-color: var(--a-green-mid);
        box-shadow: 0 0 0 3px rgba(27, 111, 74, 0.12);
    }
    .page-audit-sifec .btn-filter-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        padding: 0.5rem 1rem;
        font-weight: 600;
        font-size: 0.875rem;
        color: #fff;
        background: linear-gradient(135deg, var(--a-green-mid), var(--a-green));
        border: none;
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(15, 81, 50, 0.2);
    }
    .page-audit-sifec .btn-filter-submit:hover {
        color: #fff;
        filter: brightness(1.05);
    }
    .page-audit-sifec .btn-filter-submit.sifec-btn-loading {
        pointer-events: none;
        opacity: 0.92;
    }
    .page-audit-sifec .btn-filter-reset {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        padding: 0;
        border-radius: 8px;
        border: 1px solid var(--a-line);
        background: var(--a-paper);
        color: var(--a-ink-muted);
        transition: background 0.15s, border-color 0.15s;
    }
    .page-audit-sifec .btn-filter-reset:hover {
        background: var(--a-green-soft);
        border-color: rgba(15, 81, 50, 0.2);
        color: var(--a-green);
    }

    .page-audit-sifec .table-audit-wrap {
        border: 1px solid var(--a-line);
        border-radius: var(--a-radius-sm);
        overflow: hidden;
        background: var(--a-paper);
        box-shadow: var(--a-shadow);
        position: relative;
        z-index: 1;
    }
    .page-audit-sifec .table-audit {
        margin-bottom: 0;
        font-size: 0.875rem;
    }
    .page-audit-sifec .table-audit thead th {
        background: linear-gradient(180deg, #f4f7f5 0%, #eef2ef 100%);
        color: var(--a-ink);
        font-weight: 600;
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        border-bottom: 2px solid var(--a-line);
        padding: 0.85rem 0.9rem;
        vertical-align: middle;
        white-space: nowrap;
    }
    .page-audit-sifec .table-audit tbody td {
        padding: 0.8rem 0.9rem;
        border-color: #f0f2f0;
        vertical-align: middle;
    }
    .page-audit-sifec .table-audit tbody tr:hover td {
        background: #fafcfb;
    }
    .page-audit-sifec .audit-time {
        font-variant-numeric: tabular-nums;
        font-size: 0.8125rem;
        color: var(--a-ink);
        font-weight: 500;
    }
    .page-audit-sifec .audit-user-code {
        font-weight: 600;
        color: var(--a-green-mid) !important;
        text-decoration: none;
    }
    .page-audit-sifec .audit-user-code:hover {
        color: var(--a-green) !important;
        text-decoration: underline;
    }
    .page-audit-sifec .audit-user-name {
        font-size: 0.75rem;
        color: var(--a-ink-muted);
        margin-top: 0.15rem;
    }
    .page-audit-sifec .audit-desc {
        font-size: 0.8125rem;
        color: var(--a-ink);
        line-height: 1.45;
        max-width: 280px;
    }
    .page-audit-sifec .audit-ip {
        font-size: 0.78rem;
        font-family: ui-monospace, monospace;
        color: var(--a-ink-muted);
    }

    .page-audit-sifec .audit-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.35em 0.7em;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.02em;
        border-radius: 999px;
        white-space: nowrap;
    }
    .page-audit-sifec .audit-pill--session {
        background: #e8eef6;
        color: #334155;
    }
    .page-audit-sifec .audit-pill--profile {
        background: var(--a-green-soft);
        color: var(--a-green);
    }
    .page-audit-sifec .audit-pill--twofa {
        background: var(--a-gold-soft);
        color: #6b5420;
    }
    .page-audit-sifec .audit-pill--perm {
        background: #ede9fe;
        color: #5b21b6;
    }
    .page-audit-sifec .audit-pill--status {
        background: #dcfce7;
        color: #166534;
    }
    .page-audit-sifec .audit-pill--danger {
        background: var(--a-danger-soft);
        color: var(--a-danger);
    }
    .page-audit-sifec .audit-pill--neutral {
        background: #f1f2f3;
        color: var(--a-ink-muted);
        border: 1px solid var(--a-line);
    }

    .page-audit-sifec .btn-audit-detail {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        border: 1px solid rgba(27, 111, 74, 0.35);
        color: var(--a-green-mid);
        background: #fff;
        transition: background 0.15s, color 0.15s;
    }
    .page-audit-sifec .btn-audit-detail:hover {
        background: var(--a-green-soft);
        color: var(--a-green);
    }

    .page-audit-sifec .audit-json-row td {
        background: #f6f8f7 !important;
        border-top: none !important;
        padding: 0 !important;
    }
    .page-audit-sifec .audit-json-inner {
        padding: 1rem 1.15rem 1.15rem;
        border-top: 1px dashed var(--a-line);
    }
    .page-audit-sifec .audit-json-block {
        margin-bottom: 0.85rem;
    }
    .page-audit-sifec .audit-json-block:last-child { margin-bottom: 0; }
    .page-audit-sifec .audit-json-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--a-ink-muted);
        margin-bottom: 0.4rem;
    }
    .page-audit-sifec .audit-json-pre {
        margin: 0;
        padding: 0.75rem 1rem;
        font-size: 0.72rem;
        line-height: 1.45;
        background: var(--a-paper);
        border: 1px solid var(--a-line);
        border-radius: 8px;
        max-height: 220px;
        overflow: auto;
        font-family: ui-monospace, 'Cascadia Code', Consolas, monospace;
        color: #1e293b;
    }

    .page-audit-sifec .audit-empty {
        text-align: center;
        padding: 3rem 1.5rem;
        color: var(--a-ink-muted);
    }
    .page-audit-sifec .audit-empty i {
        font-size: 2.5rem;
        opacity: 0.35;
        margin-bottom: 0.75rem;
        display: block;
    }

    .page-audit-sifec .audit-pagination {
        display: flex;
        justify-content: center;
        margin-top: 1.5rem;
        position: relative;
        z-index: 1;
    }
    .page-audit-sifec .audit-pagination .pagination {
        margin-bottom: 0;
    }
</style>
@endsection

@section('corps')
<div class="container-fluid page-audit-sifec">
    <nav class="audit-breadcrumb" aria-label="Fil d'Ariane">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('utilisateur.index') }}">Utilisateurs</a></li>
            <li class="breadcrumb-item active" aria-current="page">Journal d'audit</li>
        </ol>
    </nav>

    <div class="audit-shell">
        <header class="audit-header">
            <div>
                <h1>
                    <span class="audit-header-icon" aria-hidden="true"><i class="fas fa-clipboard-list"></i></span>
                    Journal d'audit des comptes
                </h1>
                <p class="audit-sub">
                    Historique des événements de sécurité et de gestion des comptes (connexions, profil, mots de passe, 2FA, permissions).
                </p>
            </div>
            <a href="{{ route('utilisateur.index') }}" class="btn-audit-primary">
                <i class="fas fa-users"></i>
                Liste des utilisateurs
            </a>
        </header>

        <div class="audit-stats">
            <span class="audit-stat-chip">
                <i class="fas fa-database text-muted"></i>
                <span><strong>{{ number_format($rows->total(), 0, ',', ' ') }}</strong> événement(s) au total</span>
            </span>
            @if($rows->total() > 0)
                <span class="audit-stat-chip">
                    <i class="fas fa-stream text-muted"></i>
                    <span>Affichage <strong>{{ $rows->firstItem() }}</strong>–<strong>{{ $rows->lastItem() }}</strong></span>
                </span>
            @endif
            @if($hasFilters)
                <span class="audit-stat-chip audit-stat-chip--filter">
                    <i class="fas fa-filter"></i>
                    Filtres actifs
                </span>
            @endif
        </div>

        <div class="audit-info" role="note">
            <i class="fas fa-info-circle"></i>
            <span>
                Données issues de la table <code>tr_user_audit_trail</code>. Les détails techniques (JSON avant / après) sont disponibles ligne par ligne lorsque l’événement les enregistre.
            </span>
        </div>

        <div class="filters-panel">
            <div class="filters-title">
                <i class="fas fa-sliders-h"></i>
                Affiner les résultats
            </div>
            <form method="GET" action="{{ route('utilisateur.audit-journal') }}" id="auditJournalFilterForm" class="row g-3 align-items-end">
                <div class="col-md-3 col-lg-3">
                    <label class="form-label" for="audit-code-user">Code utilisateur</label>
                    <input type="text" id="audit-code-user" name="code_user" class="form-control"
                           value="{{ request('code_user') }}" placeholder="ex. USR_…" autocomplete="off">
                </div>
                <div class="col-md-3 col-lg-3">
                    <label class="form-label" for="audit-action">Type d'action</label>
                    <select id="audit-action" name="action" class="form-select">
                        <option value="">Tous les types</option>
                        @foreach($actionLabels as $code => $lib)
                            <option value="{{ $code }}" {{ request('action') === $code ? 'selected' : '' }}>{{ $lib }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2 col-lg-2">
                    <label class="form-label" for="audit-date-debut">Du</label>
                    <input type="date" id="audit-date-debut" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                </div>
                <div class="col-6 col-md-2 col-lg-2">
                    <label class="form-label" for="audit-date-fin">Au</label>
                    <input type="date" id="audit-date-fin" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                </div>
                <div class="col-md-2 col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-filter-submit flex-grow-1" id="auditFilterSubmitBtn">
                        <i class="fas fa-search"></i> Appliquer
                    </button>
                    <a href="{{ route('utilisateur.audit-journal') }}" class="btn btn-filter-reset-button flex-grow-1" title="Réinitialiser les filtres">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>

        <div class="table-audit-wrap">
            <div class="table-responsive">
                <table class="table table-audit align-middle">
                    <thead>
                        <tr>
                            <th>Date / heure</th>
                            <th>Utilisateur</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>IP</th>
                            <th class="text-end">Détails</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $row)
                            <tr>
                                <td class="audit-time text-nowrap">{{ $row->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>
                                    @if($row->user)
                                        <a href="{{ route('utilisateur.profile', $row->code_user) }}" class="audit-user-code">
                                            {{ $row->code_user }}
                                        </a>
                                        @if($row->user->personne)
                                            <div class="audit-user-name">{{ $row->user->personne->nom }} {{ $row->user->personne->prenom }}</div>
                                        @endif
                                    @else
                                        <span class="text-muted fw-semibold">{{ $row->code_user }}</span>
                                        <div class="audit-user-name fst-italic">Compte supprimé ou inconnu</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="{{ $auditPillClass($row->action) }}">{{ $actionLabels[$row->action] ?? $row->action }}</span>
                                </td>
                                <td><span class="audit-desc">{{ $row->description ?? '—' }}</span></td>
                                <td class="audit-ip text-nowrap">{{ $row->ip_address ?? '—' }}</td>
                                <td class="text-end">
                                    @if($row->old_values || $row->new_values)
                                        <button type="button" class="btn btn-audit-detail"
                                                data-bs-toggle="collapse" data-bs-target="#audit-row-{{ $row->id }}"
                                                aria-expanded="false" aria-controls="audit-row-{{ $row->id }}">
                                            <i class="fas fa-code me-1"></i> JSON
                                        </button>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                            </tr>
                            @if($row->old_values || $row->new_values)
                                <tr class="collapse audit-json-row" id="audit-row-{{ $row->id }}">
                                    <td colspan="6">
                                        <div class="audit-json-inner">
                                            @if($row->old_values)
                                                <div class="audit-json-block">
                                                    <div class="audit-json-label">Avant</div>
                                                    <pre class="audit-json-pre">{{ json_encode($row->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                </div>
                                            @endif
                                            @if($row->new_values)
                                                <div class="audit-json-block">
                                                    <div class="audit-json-label">Après</div>
                                                    <pre class="audit-json-pre">{{ json_encode($row->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" class="p-0">
                                    <div class="audit-empty">
                                        <i class="fas fa-inbox"></i>
                                        Aucun enregistrement ne correspond aux critères sélectionnés.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($rows->hasPages())
            <div class="audit-pagination">
                {{ $rows->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var f = document.getElementById('auditJournalFilterForm');
    var b = document.getElementById('auditFilterSubmitBtn');
    if (!f || !b) return;
    f.addEventListener('submit', function () {
        if (b.getAttribute('data-sifec-submitting') === '1') return;
        b.setAttribute('data-sifec-submitting', '1');
        if (!b.getAttribute('data-sifec-html')) {
            b.setAttribute('data-sifec-html', b.innerHTML);
        }
        b.disabled = true;
        b.setAttribute('aria-busy', 'true');
        b.classList.add('sifec-btn-loading');
        b.innerHTML = '<i class="fas fa-spinner fa-spin me-2" aria-hidden="true"></i>Chargement…';
    });
});
</script>
@endsection
