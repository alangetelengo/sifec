@extends('layout.app')

@section('titre')
    Mise à jour des données — {{ $user->personne->nom ?? '' }} {{ $user->personne->prenom ?? '' }}
@endsection

@php
    $actionLabels = \App\Models\UserAuditTrail::getAvailableActions();
    $auditPillClass = function ($action) {
        if (strpos($action, '2fa_') === 0 || strpos($action, 'recovery_') === 0) {
            return 'pu-pill pu-pill--twofa';
        }
        if ($action === 'profile_update' || $action === 'password_change') {
            return 'pu-pill pu-pill--profile';
        }
        if ($action === 'login_failed') {
            return 'pu-pill pu-pill--danger';
        }
        if ($action === 'login' || $action === 'logout') {
            return 'pu-pill pu-pill--session';
        }
        if (strpos($action, 'permission_') === 0) {
            return 'pu-pill pu-pill--perm';
        }
        if (strpos($action, 'account_') === 0) {
            return 'pu-pill pu-pill--status';
        }

        return 'pu-pill pu-pill--neutral';
    };
@endphp

@section('styles')
<style>
    .page-pu-sifec {
        --pu-ink: #1a2e26;
        --pu-ink-muted: #5c6d66;
        --pu-green: #0f5132;
        --pu-green-soft: #e8f0eb;
        --pu-green-mid: #1b6f4a;
        --pu-cream: #fafaf8;
        --pu-paper: #ffffff;
        --pu-line: #e2e8e4;
        --pu-gold: #9a7b2c;
        --pu-gold-soft: #f5f0e6;
        --pu-danger: #9b2c2c;
        --pu-danger-soft: #fce8e8;
        --pu-shadow: 0 1px 3px rgba(26, 46, 38, 0.06);
        --pu-shadow-lg: 0 12px 40px rgba(26, 46, 38, 0.08);
        --pu-radius: 14px;
        --pu-radius-sm: 10px;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        margin: -0.35rem -0.5rem 0;
        padding: 0.5rem 0.5rem 2rem;
        min-height: calc(100vh - 100px);
        background: linear-gradient(180deg, var(--pu-cream) 0%, #eef1ee 100%);
    }

    .page-pu-sifec .pu-breadcrumb {
        font-size: 0.875rem;
        margin-bottom: 1rem;
        background: var(--pu-paper);
        border: 1px solid var(--pu-line);
        border-radius: var(--pu-radius-sm);
        padding: 0.65rem 1.15rem;
        box-shadow: var(--pu-shadow);
    }
    .page-pu-sifec .pu-breadcrumb .breadcrumb { margin-bottom: 0; }
    .page-pu-sifec .pu-breadcrumb .breadcrumb-item { color: #475569 !important; }
    .page-pu-sifec .pu-breadcrumb .breadcrumb-item a {
        color: var(--pu-green-mid) !important;
        font-weight: 600;
        text-decoration: none;
    }
    .page-pu-sifec .pu-breadcrumb .breadcrumb-item a:hover {
        color: var(--pu-green) !important;
        text-decoration: underline;
    }
    .page-pu-sifec .pu-breadcrumb .breadcrumb-item.active {
        color: var(--pu-ink) !important;
        font-weight: 700;
    }
    .page-pu-sifec .pu-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
        color: #94a3b8 !important;
    }

    .page-pu-sifec .pu-shell {
        position: relative;
        background: linear-gradient(180deg, var(--pu-paper) 0%, #fbfcfb 100%);
        border-radius: var(--pu-radius);
        padding: 1.75rem 1.75rem 2rem;
        box-shadow: var(--pu-shadow-lg);
        border: 1px solid var(--pu-line);
        overflow: hidden;
        max-width: 1100px;
        margin: 0 auto;
    }
    .page-pu-sifec .pu-shell::before {
        content: '';
        position: absolute;
        inset: -18% -8% auto auto;
        width: 360px;
        height: 360px;
        background: radial-gradient(circle, rgba(15, 81, 50, 0.05) 0%, transparent 72%);
        pointer-events: none;
    }

    .page-pu-sifec .pu-header {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1.25rem;
        margin-bottom: 1.35rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid var(--pu-line);
        position: relative;
        z-index: 1;
    }
    .page-pu-sifec .pu-header h1 {
        font-size: 1.4rem;
        font-weight: 600;
        letter-spacing: -0.02em;
        color: var(--pu-ink);
        margin: 0 0 0.4rem;
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }
    .page-pu-sifec .pu-header-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--pu-green-soft) 0%, #d8e8df 100%);
        color: var(--pu-green);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(15, 81, 50, 0.12);
    }
    .page-pu-sifec .pu-sub {
        font-size: 0.875rem;
        color: var(--pu-ink-muted);
        margin: 0;
        max-width: 40rem;
        line-height: 1.55;
    }
    .page-pu-sifec .pu-sub::before {
        content: '';
        display: block;
        width: 36px;
        height: 3px;
        background: linear-gradient(90deg, var(--pu-gold), rgba(154, 123, 44, 0.2));
        border-radius: 2px;
        margin-bottom: 0.55rem;
    }

    .page-pu-sifec .btn-pu-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 1.2rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #fff !important;
        background: linear-gradient(135deg, var(--pu-green-mid) 0%, var(--pu-green) 100%);
        border: none;
        border-radius: var(--pu-radius-sm);
        box-shadow: 0 4px 14px rgba(15, 81, 50, 0.22);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        text-decoration: none !important;
    }
    .page-pu-sifec .btn-pu-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(15, 81, 50, 0.28);
        color: #fff !important;
    }

    .page-pu-sifec .pu-info {
        background: linear-gradient(135deg, var(--pu-green-soft) 0%, #f0f6f2 100%);
        border: 1px solid rgba(15, 81, 50, 0.12);
        border-radius: var(--pu-radius-sm);
        padding: 0.9rem 1.15rem;
        font-size: 0.8125rem;
        color: var(--pu-ink-muted);
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
    }
    .page-pu-sifec .pu-info i {
        color: var(--pu-green-mid);
        margin-top: 0.12rem;
        flex-shrink: 0;
    }
    .page-pu-sifec .pu-info code {
        font-size: 0.78rem;
        background: rgba(255, 255, 255, 0.85);
        padding: 0.12rem 0.4rem;
        border-radius: 4px;
        border: 1px solid rgba(15, 81, 50, 0.1);
        color: var(--pu-green);
    }

    .page-pu-sifec .pu-section {
        position: relative;
        z-index: 1;
        margin-bottom: 1.75rem;
    }
    .page-pu-sifec .pu-section-title {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--pu-green);
        margin-bottom: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .page-pu-sifec .pu-section-hint {
        font-size: 0.78rem;
        color: var(--pu-ink-muted);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }
    .page-pu-sifec .pu-section-card {
        background: var(--pu-paper);
        border: 1px solid var(--pu-line);
        border-radius: var(--pu-radius-sm);
        padding: 1.2rem 1.25rem 1.1rem;
        box-shadow: var(--pu-shadow);
    }

    .page-pu-sifec .pu-locked {
        background: linear-gradient(180deg, #f8faf9 0%, #f1f4f2 100%);
        border: 1px dashed rgba(15, 81, 50, 0.2);
        border-radius: var(--pu-radius-sm);
        padding: 1rem 1.15rem 0.5rem;
        margin-bottom: 1.25rem;
    }
    .page-pu-sifec .pu-locked-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--pu-ink-muted);
        margin-bottom: 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .page-pu-sifec .pu-locked-label i { color: #64748b; }

    .page-pu-sifec .form-label {
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: var(--pu-ink-muted);
        margin-bottom: 0.35rem;
    }
    .page-pu-sifec .form-control,
    .page-pu-sifec .form-select {
        border-radius: 8px;
        border-color: var(--pu-line);
        font-size: 0.9rem;
    }
    .page-pu-sifec .form-control:focus,
    .page-pu-sifec .form-select:focus {
        border-color: var(--pu-green-mid);
        box-shadow: 0 0 0 3px rgba(27, 111, 74, 0.12);
    }
    .page-pu-sifec .form-control[readonly],
    .page-pu-sifec .form-control.bg-light {
        background: #fff !important;
        border-style: dashed;
        color: var(--pu-ink-muted);
    }

    .page-pu-sifec .pu-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        margin-top: 1.75rem;
        padding-top: 1.35rem;
        border-top: 1px solid var(--pu-line);
        position: relative;
        z-index: 1;
    }
    .page-pu-sifec .btn-pu-outline {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.55rem 1.15rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--pu-danger) !important;
        background: var(--pu-paper);
        border: 1px solid rgba(155, 44, 44, 0.35);
        border-radius: var(--pu-radius-sm);
        text-decoration: none !important;
        transition: background 0.15s, border-color 0.15s;
    }
    .page-pu-sifec .btn-pu-outline:hover {
        background: var(--pu-danger-soft);
        border-color: var(--pu-danger);
        color: var(--pu-danger) !important;
    }
    .page-pu-sifec .btn-pu-submit {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.35rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(135deg, var(--pu-green-mid), var(--pu-green));
        border: none;
        border-radius: var(--pu-radius-sm);
        box-shadow: 0 4px 14px rgba(15, 81, 50, 0.22);
    }
    .page-pu-sifec .btn-pu-submit:hover {
        color: #fff;
        filter: brightness(1.05);
    }

    .page-pu-sifec .pu-history-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--pu-ink);
        margin-bottom: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .page-pu-sifec .pu-history-title i { color: var(--pu-green-mid); }
    .page-pu-sifec .pu-history-hint {
        font-size: 0.8rem;
        color: var(--pu-ink-muted);
        line-height: 1.5;
        margin-bottom: 1rem;
    }
    .page-pu-sifec .pu-history-hint code {
        font-size: 0.75rem;
        background: var(--pu-green-soft);
        padding: 0.1rem 0.35rem;
        border-radius: 4px;
        color: var(--pu-green);
    }

    .page-pu-sifec .pu-empty {
        background: var(--pu-paper);
        border: 1px solid var(--pu-line);
        border-radius: var(--pu-radius-sm);
        padding: 1.5rem 1.25rem;
        text-align: center;
        color: var(--pu-ink-muted);
        font-size: 0.875rem;
    }
    .page-pu-sifec .pu-empty i {
        display: block;
        font-size: 2rem;
        opacity: 0.35;
        margin-bottom: 0.5rem;
    }

    .page-pu-sifec .table-pu-wrap {
        border: 1px solid var(--pu-line);
        border-radius: var(--pu-radius-sm);
        overflow: hidden;
        background: var(--pu-paper);
        box-shadow: var(--pu-shadow);
    }
    .page-pu-sifec .table-pu {
        margin-bottom: 0;
        font-size: 0.875rem;
    }
    .page-pu-sifec .table-pu thead th {
        background: linear-gradient(180deg, #f4f7f5 0%, #eef2ef 100%);
        color: var(--pu-ink);
        font-weight: 600;
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        border-bottom: 2px solid var(--pu-line);
        padding: 0.8rem 0.85rem;
        white-space: nowrap;
    }
    .page-pu-sifec .table-pu tbody td {
        padding: 0.75rem 0.85rem;
        border-color: #f0f2f0;
        vertical-align: middle;
    }
    .page-pu-sifec .table-pu tbody tr:hover td {
        background: #fafcfb;
    }
    .page-pu-sifec .pu-time {
        font-variant-numeric: tabular-nums;
        font-size: 0.8125rem;
        font-weight: 500;
    }
    .page-pu-sifec .pu-desc {
        font-size: 0.8125rem;
        line-height: 1.45;
        max-width: 320px;
    }

    .page-pu-sifec .pu-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.35em 0.7em;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.02em;
        border-radius: 999px;
        white-space: nowrap;
    }
    .page-pu-sifec .pu-pill--session { background: #e8eef6; color: #334155; }
    .page-pu-sifec .pu-pill--profile { background: var(--pu-green-soft); color: var(--pu-green); }
    .page-pu-sifec .pu-pill--twofa { background: var(--pu-gold-soft); color: #6b5420; }
    .page-pu-sifec .pu-pill--perm { background: #ede9fe; color: #5b21b6; }
    .page-pu-sifec .pu-pill--status { background: #dcfce7; color: #166534; }
    .page-pu-sifec .pu-pill--danger { background: var(--pu-danger-soft); color: var(--pu-danger); }
    .page-pu-sifec .pu-pill--neutral {
        background: #f1f2f3;
        color: var(--pu-ink-muted);
        border: 1px solid var(--pu-line);
    }

    .page-pu-sifec .btn-pu-json {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        border: 1px solid rgba(27, 111, 74, 0.35);
        color: var(--pu-green-mid);
        background: #fff;
    }
    .page-pu-sifec .btn-pu-json:hover {
        background: var(--pu-green-soft);
        color: var(--pu-green);
    }

    .page-pu-sifec .pu-json-row td {
        background: #f6f8f7 !important;
        border-top: none !important;
        padding: 0 !important;
    }
    .page-pu-sifec .pu-json-inner {
        padding: 1rem 1.1rem;
        border-top: 1px dashed var(--pu-line);
    }
    .page-pu-sifec .pu-json-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--pu-ink-muted);
        margin-bottom: 0.4rem;
    }
    .page-pu-sifec .pu-json-pre {
        margin: 0 0 0.85rem;
        padding: 0.7rem 0.95rem;
        font-size: 0.72rem;
        line-height: 1.45;
        background: var(--pu-paper);
        border: 1px solid var(--pu-line);
        border-radius: 8px;
        max-height: 200px;
        overflow: auto;
        font-family: ui-monospace, Consolas, monospace;
        color: #1e293b;
    }
    .page-pu-sifec .pu-json-pre:last-child { margin-bottom: 0; }

    .page-pu-sifec .btn-pu-submit.sifec-btn-loading {
        pointer-events: none;
        opacity: 0.92;
    }

    .page-pu-sifec hr.pu-divider {
        margin: 2.25rem 0;
        border: 0;
        border-top: 1px solid var(--pu-line);
        opacity: 1;
    }

    .page-pu-sifec .pu-nomination-zone {
        background: linear-gradient(180deg, #fafbf9 0%, #f4f6f4 100%);
        border: 1px dashed rgba(15, 81, 50, 0.22);
        border-radius: var(--pu-radius-sm);
        padding: 1rem 1.1rem;
        margin-bottom: 1rem;
    }
    .page-pu-sifec .pu-nomination-hint {
        display: none;
        font-size: 0.8rem;
        color: #5c4a1a;
        margin-top: 0.65rem;
        padding: 0.65rem 0.85rem;
        background: var(--pu-gold-soft);
        border: 1px solid rgba(154, 123, 44, 0.35);
        border-radius: 8px;
        line-height: 1.45;
    }
    .page-pu-sifec .pu-nomination-hint.is-visible {
        display: block;
    }
    .page-pu-sifec .pu-file-current {
        font-size: 0.8125rem;
        margin-bottom: 0.65rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem;
    }
    .page-pu-sifec .pu-file-current a {
        font-weight: 600;
        color: var(--pu-green-mid) !important;
        text-decoration: none;
    }
    .page-pu-sifec .pu-file-current a:hover {
        text-decoration: underline;
        color: var(--pu-green) !important;
    }
</style>
@endsection

@section('corps')
<div class="container-fluid page-pu-sifec">
    <nav class="pu-breadcrumb" aria-label="Fil d'Ariane">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('utilisateur.index') }}">Utilisateurs</a></li>
            <li class="breadcrumb-item"><a href="{{ route('utilisateur.profile', $user->code_user) }}">Profil</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mise à jour des données</li>
        </ol>
    </nav>

    <div class="pu-shell">
        <header class="pu-header">
            <div>
                <h1>
                    <span class="pu-header-icon" aria-hidden="true"><i class="fas fa-user-edit"></i></span>
                    Mise à jour des données
                </h1>
                <p class="pu-sub">
                    {{ $user->personne->nom ?? '' }} {{ $user->personne->prenom ?? '' }}
                    <span class="text-muted">·</span>
                    <span class="font-monospace small">{{ $user->code_user }}</span>
                </p>
            </div>
            <a href="{{ route('utilisateur.profile', $user->code_user) }}" class="btn-pu-primary">
                <i class="fas fa-arrow-left"></i>
                Retour au profil
            </a>
        </header>

        <div class="pu-info" role="note">
            <i class="fas fa-info-circle"></i>
            <span>
                Les identités civiles issues du registre ne sont pas modifiables ici. Toute modification est consignée dans <code>tr_user_audit_trail</code>.
            </span>
        </div>

        <form id="pu-profile-update-form" method="POST" action="{{ route('utilisateur.profile.mise-a-jour.update', $user->code_user) }}"
              enctype="multipart/form-data"
              data-fonction-init="{{ e($codeFonctionActuel ?? '') }}"
              data-institution-init="{{ e($codeInstitutionActuel ?? '') }}">
            @csrf
            @method('PUT')

            <div class="pu-section">
                <div class="pu-section-title">Identité civile</div>
                <div class="pu-section-hint"><i class="fas fa-lock"></i> Lecture seule</div>
                <div class="pu-locked">
                    <div class="pu-locked-label"><i class="fas fa-id-card"></i> Données verrouillées</div>
                    <input type="hidden" name="nom" value="{{ $user->personne->nom ?? '' }}">
                    <input type="hidden" name="sexe" value="{{ $user->personne->sexe ?? '' }}">
                    <input type="hidden" name="date_naissance" value="{{ $user->personne->date_naissance ? \Carbon\Carbon::parse($user->personne->date_naissance)->format('Y-m-d') : '' }}">
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="pu-ro-nom">Nom(s)</label>
                            <input id="pu-ro-nom" type="text" class="form-control bg-light" value="{{ $user->personne->nom ?? '' }}" readonly>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="pu-ro-prenom">Prénom(s)</label>
                            <input id="pu-ro-prenom" type="text" class="form-control bg-light" value="{{ $user->personne->prenom ?? '' }}" readonly>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="pu-ro-sexe">Sexe</label>
                            <input id="pu-ro-sexe" type="text" class="form-control bg-light"
                                   value="{{ ($user->personne->sexe ?? '') === 'F' ? 'Féminin' : 'Masculin' }}" readonly>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="pu-ro-naiss">Date de naissance</label>
                            <input id="pu-ro-naiss" type="text" class="form-control bg-light"
                                   value="{{ $user->personne->date_naissance ? \Carbon\Carbon::parse($user->personne->date_naissance)->format('d/m/Y') : '' }}" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pu-section">
                <div class="pu-section-title">Coordonnées &amp; nationalité</div>
                <div class="pu-section-card">
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="pu-nat">Nationalité <span class="text-danger">*</span></label>
                            <select id="pu-nat" name="code_nationalite" class="form-select @error('code_nationalite') is-invalid @enderror" required>
                                <option value="" disabled {{ old('code_nationalite', $user->personne->code_nationalite ?? '') ? '' : 'selected' }}>Sélectionner</option>
                                @foreach ($nationalites as $nat)
                                    <option value="{{ $nat->code_nationalite }}"
                                        {{ old('code_nationalite', $user->personne->code_nationalite ?? '') == $nat->code_nationalite ? 'selected' : '' }}>
                                        {{ $nat->lib_nationalite }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_nationalite')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3 col-md-8">
                            <label class="form-label" for="pu-adr">Adresse / domicile <span class="text-danger">*</span></label>
                            <input id="pu-adr" type="text" name="adresse" class="form-control @error('adresse') is-invalid @enderror"
                                   value="{{ old('adresse', $user->personne->adresse ?? '') }}">
                            @error('adresse')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-0 col-md-6">
                            <label class="form-label" for="pu-tel">Téléphone</label>
                            <input id="pu-tel" type="text" name="telephone" class="form-control @error('telephone') is-invalid @enderror"
                                   value="{{ old('telephone', $user->personne->telephone ?? '') }}">
                            @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="pu-section">
                <div class="pu-section-title">Document d’identité <span class="fw-normal text-muted text-uppercase" style="letter-spacing:0.04em;font-size:0.65rem;">facultatif</span></div>
                <div class="pu-section-card">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="pu-doc-type">Type de pièce</label>
                            <select id="pu-doc-type" name="code_type_document" class="form-select @error('code_type_document') is-invalid @enderror">
                                <option value="" selected>— Aucun —</option>
                                @foreach ($typeDocuments as $item)
                                    <option value="{{ $item->code_type_document }}" {{ old('code_type_document') == $item->code_type_document ? 'selected' : '' }}>
                                        {{ $item->lib_type_document }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_type_document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-0 col-md-6">
                            <label class="form-label" for="pu-doc-num">Numéro de la pièce</label>
                            <input id="pu-doc-num" type="text" name="numero_document" class="form-control @error('numero_document') is-invalid @enderror"
                                   value="{{ old('numero_document') }}">
                            @error('numero_document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="pu-section">
                <div class="pu-section-title">Affectation &amp; compte</div>
                <div class="pu-section-card">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="pu-fn">Fonction <span class="text-danger">*</span></label>
                            <select id="pu-fn" name="code_fonction" class="form-select @error('code_fonction') is-invalid @enderror" required>
                                <option value="" disabled {{ old('code_fonction', $user->affectationActive()?->fonction?->code_fonction) ? '' : 'selected' }}>Sélectionner</option>
                                @foreach ($fonctions as $fn)
                                    <option value="{{ $fn->code_fonction }}"
                                        {{ old('code_fonction', $user->affectationActive()?->fonction?->code_fonction) == $fn->code_fonction ? 'selected' : '' }}>
                                        {{ $fn->lib_fonction }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_fonction')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="pu-ins">Centre / institution <span class="text-danger">*</span></label>
                            <select id="pu-ins" name="code_institution" class="form-select @error('code_institution') is-invalid @enderror" required>
                                <option value="" disabled {{ old('code_institution', $user->affectationActive()?->institution?->code_institution) ? '' : 'selected' }}>Sélectionner</option>
                                @foreach ($institutions as $ins)
                                    <option value="{{ $ins->code_institution }}"
                                        {{ old('code_institution', $user->affectationActive()?->institution?->code_institution) == $ins->code_institution ? 'selected' : '' }}>
                                        {{ $ins->lib_institution }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_institution')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <div class="pu-nomination-zone">
                                <label class="form-label" for="pu-nomination">
                                    Note de service ou acte de nomination
                                    <span id="pu-nomination-star" class="text-danger" style="display: none;">*</span>
                                </label>
                                <p class="small text-muted mb-2 mb-md-2" style="margin-top: -0.15rem;">
                                    Justificatif (PDF ou image) prouvant la fonction et le centre d’affectation renseignés ci-dessus.
                                </p>
                                @if(!empty($pieceNominationChemin))
                                    <div class="pu-file-current">
                                        <i class="fas fa-paperclip text-muted"></i>
                                        <span>Justificatif enregistré :</span>
                                        <a href="{{ asset('app/'.$pieceNominationChemin) }}" target="_blank" rel="noopener noreferrer">
                                            <i class="fas fa-external-link-alt me-1"></i>Ouvrir le fichier
                                        </a>
                                    </div>
                                @endif
                                <input type="file" name="piece_nomination" id="pu-nomination"
                                       class="form-control @error('piece_nomination') is-invalid @enderror"
                                       accept=".pdf,.jpg,.jpeg,.png,image/jpeg,image/png,application/pdf">
                                <div id="pu-nomination-hint" class="pu-nomination-hint" role="status">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    Vous avez modifié la <strong>fonction</strong> ou le <strong>centre / institution</strong> : le téléversement d’une note de service ou d’un acte de nomination est <strong>obligatoire</strong> (PDF, JPG ou PNG, max. 5&nbsp;Mo).
                                </div>
                                @error('piece_nomination')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="pu-mail">Adresse e-mail <span class="text-danger">*</span></label>
                            <input id="pu-mail" type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label" for="pu-niv">Niveau d’instruction</label>
                            <select id="pu-niv" name="niveau_instruction" class="form-select">
                                <option value="">—</option>
                                @foreach ($niveauInstructions as $ni)
                                    <option value="{{ $ni }}"
                                        {{ old('niveau_instruction', $user->personne->niveau_instruction ?? '') == $ni ? 'selected' : '' }}>
                                        {{ $ni }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-0 col-md-3">
                            <label class="form-label" for="pu-active">Statut du compte <span class="text-danger">*</span></label>
                            <select id="pu-active" name="active" class="form-select @error('active') is-invalid @enderror">
                                <option value="1" {{ old('active', $user->status ? '1' : '0') == '1' ? 'selected' : '' }}>Actif</option>
                                <option value="0" {{ old('active', $user->status ? '1' : '0') == '0' ? 'selected' : '' }}>Inactif</option>
                            </select>
                            @error('active')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="pu-actions">
                <a href="{{ route('utilisateur.profile', $user->code_user) }}" class="btn-pu-outline">
                    <i class="fas fa-times"></i> Annuler
                </a>
                <button type="submit" class="btn-pu-submit" id="puProfileSubmitBtn">
                    <i class="fas fa-save"></i> Enregistrer les modifications
                </button>
            </div>
        </form>

        <hr class="pu-divider">

        <section class="pu-section mb-0">
            <h2 class="pu-history-title"><i class="fas fa-history"></i> Historique d’audit (cet utilisateur)</h2>
            <p class="pu-history-hint">
                Source : <code>tr_user_audit_trail</code>. Les mouvements sur les actes (naissance, décès, etc.) sont tracés ailleurs (<code>t_mouvement_*</code>, <code>t_mouvement_dossier</code>…).
            </p>

            @if($auditHistory->isEmpty())
                <div class="pu-empty">
                    <i class="fas fa-inbox"></i>
                    Aucune entrée d’audit pour cet utilisateur pour l’instant.
                </div>
            @else
                <div class="table-pu-wrap">
                    <div class="table-responsive">
                        <table class="table table-pu align-middle">
                            <thead>
                                <tr>
                                    <th>Date / heure</th>
                                    <th>Action</th>
                                    <th>Description</th>
                                    <th class="text-end">Détail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($auditHistory as $row)
                                    <tr>
                                        <td class="pu-time text-nowrap">{{ $row->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td><span class="{{ $auditPillClass($row->action) }}">{{ $actionLabels[$row->action] ?? $row->action }}</span></td>
                                        <td><span class="pu-desc">{{ $row->description ?? '—' }}</span></td>
                                        <td class="text-end">
                                            @if($row->old_values || $row->new_values)
                                                <button type="button" class="btn btn-pu-json"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#audit-{{ $row->id }}"
                                                        aria-expanded="false"
                                                        aria-controls="audit-{{ $row->id }}">
                                                    <i class="fas fa-code me-1"></i> JSON
                                                </button>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($row->old_values || $row->new_values)
                                        <tr class="collapse pu-json-row" id="audit-{{ $row->id }}">
                                            <td colspan="4">
                                                <div class="pu-json-inner">
                                                    @if($row->old_values)
                                                        <div class="pu-json-label">Avant</div>
                                                        <pre class="pu-json-pre">{{ json_encode($row->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                    @endif
                                                    @if($row->new_values)
                                                        <div class="pu-json-label">Après</div>
                                                        <pre class="pu-json-pre">{{ json_encode($row->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
    var form = document.getElementById('pu-profile-update-form');
    if (!form) return;
    var fn0 = form.getAttribute('data-fonction-init') || '';
    var ins0 = form.getAttribute('data-institution-init') || '';
    var selFn = document.getElementById('pu-fn');
    var selIns = document.getElementById('pu-ins');
    var hint = document.getElementById('pu-nomination-hint');
    var star = document.getElementById('pu-nomination-star');
    function sync() {
        var changed = (selFn && selFn.value !== fn0) || (selIns && selIns.value !== ins0);
        if (hint) {
            hint.classList.toggle('is-visible', changed);
        }
        if (star) {
            star.style.display = changed ? 'inline' : 'none';
        }
    }
    if (selFn) selFn.addEventListener('change', sync);
    if (selIns) selIns.addEventListener('change', sync);
    sync();
})();

(function () {
    var form = document.getElementById('pu-profile-update-form');
    var btn = document.getElementById('puProfileSubmitBtn');
    if (!form || !btn) return;
    form.addEventListener('submit', function () {
        if (btn.getAttribute('data-sifec-submitting') === '1') return;
        btn.setAttribute('data-sifec-submitting', '1');
        if (!btn.getAttribute('data-sifec-html')) {
            btn.setAttribute('data-sifec-html', btn.innerHTML);
        }
        btn.disabled = true;
        btn.setAttribute('aria-busy', 'true');
        btn.classList.add('sifec-btn-loading');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2" aria-hidden="true"></i>Enregistrement en cours…';
    });
})();
</script>
@endsection
