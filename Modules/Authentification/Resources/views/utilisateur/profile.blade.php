@extends('layout.app')

@section('titre')
    Profil — {{ $user->personne ? $user->personne->nom.' '.$user->personne->prenom : $user->email }}
@endsection

@section('styles')
<style>
    :root {
        --sifec-profile-green-900: #064e2b;
        --sifec-profile-green-700: #0d5c36;
        --sifec-profile-green-600: #006B31;
        --sifec-profile-green-500: #009E49;
        --sifec-profile-green-400: #21B931;
        --sifec-profile-surface: #f8fafc;
        --sifec-profile-border: #e2e8f0;
        --sifec-profile-muted: #64748b;
        --sifec-profile-text: #0f172a;
        --sifec-profile-radius: 14px;
        --sifec-profile-shadow: 0 1px 3px rgba(15, 23, 42, 0.06), 0 8px 24px rgba(15, 23, 42, 0.06);
    }

    .sifec-profile-page {
        background: var(--sifec-profile-surface);
        margin: -0.5rem -0.75rem 0;
        padding: 1rem 0.75rem 2.5rem;
        min-height: calc(100vh - 120px);
    }

    /* Liens profil : toute la page est dans .sifec-profile-page ; la grille des cartes
       (Identité, e-mails…) est hors de .sifec-profile-content — sans ces règles, le thème
       peut hériter une couleur de lien illisible sur fond blanc. */
    .sifec-profile-page a.sifec-profile-link,
    .sifec-profile-page a.sifec-profile-link .sifec-profile-email-value {
        color: var(--sifec-profile-green-600) !important;
        font-weight: 500;
        text-decoration: none;
    }
    .sifec-profile-page a.sifec-profile-link:hover,
    .sifec-profile-page a.sifec-profile-link:hover .sifec-profile-email-value {
        color: var(--sifec-profile-green-500) !important;
        text-decoration: underline;
    }

    /* Fil d’Ariane : contraste fort (lisible sur fond clair) */
    .sifec-profile-page .sifec-profile-breadcrumb {
        font-size: 0.875rem;
        margin-bottom: 1rem;
        background: #fff;
        border: 1px solid var(--sifec-profile-border);
        border-radius: 10px;
        padding: 0.65rem 1.15rem;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
    }
    .sifec-profile-page .sifec-profile-breadcrumb .breadcrumb {
        margin-bottom: 0;
    }
    .sifec-profile-page .sifec-profile-breadcrumb .breadcrumb-item {
        color: #475569 !important;
    }
    .sifec-profile-page .sifec-profile-breadcrumb .breadcrumb-item a {
        color: #047857 !important;
        font-weight: 600;
        text-decoration: none;
    }
    .sifec-profile-page .sifec-profile-breadcrumb .breadcrumb-item a:hover {
        color: #059669 !important;
        text-decoration: underline;
    }
    .sifec-profile-page .sifec-profile-breadcrumb .breadcrumb-item.active {
        color: #0f172a !important;
        font-weight: 700;
    }
    .sifec-profile-page .sifec-profile-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
        color: #94a3b8 !important;
        font-weight: 400;
    }

    /* En-tête profil : même dégradé + halos + cercles que le menu .deznav-sifec-gradient */
    .sifec-profile-hero {
        border-radius: var(--sifec-profile-radius);
        color: #fff;
        padding: 1.75rem 1.75rem 1.5rem;
        position: relative;
        overflow: hidden;
    }
    .sifec-profile-hero--sifec-menu {
        background-color: #009E49 !important;
        background-image: linear-gradient(135deg, #006B31 0%, #009E49 55%, #21B931 100%) !important;
        box-shadow: 0 4px 28px rgba(0, 158, 73, 0.28);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .sifec-profile-hero--sifec-menu .sifec-profile-hero-mesh {
        position: absolute;
        inset: 0;
        pointer-events: none;
        z-index: 0;
        overflow: hidden;
    }
    .sifec-profile-hero--sifec-menu .sifec-profile-hero-orb {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
    }
    .sifec-profile-hero--sifec-menu .sifec-profile-hero-orb--light {
        width: 220px;
        height: 220px;
        background: rgba(255, 255, 255, 0.06);
        top: -70px;
        right: -50px;
        z-index: 1;
    }
    .sifec-profile-hero--sifec-menu .sifec-profile-hero-orb--jaune {
        width: 140px;
        height: 140px;
        background: rgba(251, 222, 74, 0.08);
        bottom: 18%;
        right: -42px;
        z-index: 1;
    }
    .sifec-profile-hero--sifec-menu .sifec-profile-hero-blob {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        z-index: 2;
        box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.05) inset;
    }
    .sifec-profile-hero--sifec-menu .sifec-profile-hero-blob--1 {
        width: 240px;
        height: 240px;
        top: 3%;
        right: -75px;
        background: rgba(46, 184, 92, 0.44);
    }
    .sifec-profile-hero--sifec-menu .sifec-profile-hero-blob--2 {
        width: 200px;
        height: 200px;
        top: 14%;
        right: -40px;
        background: rgba(54, 198, 102, 0.36);
    }
    .sifec-profile-hero--sifec-menu .sifec-profile-hero-blob--3 {
        width: 175px;
        height: 175px;
        top: 26%;
        right: -15px;
        background: rgba(46, 184, 92, 0.3);
    }
    .sifec-profile-hero--sifec-menu .sifec-profile-hero-inner {
        position: relative;
        z-index: 2;
    }
    @media only screen and (max-width: 1400px) {
        .sifec-profile-hero--sifec-menu .sifec-profile-hero-orb--light {
            width: 190px;
            height: 190px;
            top: -55px;
            right: -45px;
        }
        .sifec-profile-hero--sifec-menu .sifec-profile-hero-orb--jaune {
            width: 120px;
            height: 120px;
            right: -36px;
        }
        .sifec-profile-hero--sifec-menu .sifec-profile-hero-blob--1 {
            width: 200px;
            height: 200px;
            right: -65px;
        }
        .sifec-profile-hero--sifec-menu .sifec-profile-hero-blob--2 {
            width: 170px;
            height: 170px;
            right: -35px;
        }
        .sifec-profile-hero--sifec-menu .sifec-profile-hero-blob--3 {
            width: 145px;
            height: 145px;
            right: -10px;
        }
    }

    .sifec-profile-avatar {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        border: 3px solid rgba(255,255,255,0.45);
        overflow: hidden;
        background: rgba(255,255,255,0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 14px rgba(0,0,0,0.15);
    }
    .sifec-profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .sifec-profile-avatar .initials {
        font-size: 1.75rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        color: #fff;
    }

    .sifec-profile-name {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        line-height: 1.2;
        margin: 0 0 0.5rem;
        color: #fff;
    }
    .sifec-profile-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: rgba(255,255,255,0.18);
        border: 1px solid rgba(255,255,255,0.28);
        color: #fff;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.28rem 0.75rem;
        border-radius: 999px;
    }
    .sifec-profile-meta {
        font-size: 0.875rem;
        opacity: 0.92;
        margin-top: 0.85rem;
    }
    .sifec-profile-meta i {
        width: 1.1rem;
        opacity: 0.85;
    }

    .sifec-profile-actions {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    @media (min-width: 992px) {
        .sifec-profile-actions {
            min-width: 11rem;
        }
    }
    .sifec-profile-actions .btn {
        font-size: 0.8125rem;
        font-weight: 600;
        border-radius: 8px;
        justify-content: center;
    }

    /* Cartes : ne pas forcer height:100% ici — avec une .row en align-items stretch,
       la 1re carte d’une colonne étirée occuperait toute la hauteur et repousserait
       les cartes suivantes (ex. Affectation) hors du viewport (#main-wrapper overflow:hidden). */
    .sifec-profile-card {
        background: #fff;
        border-radius: var(--sifec-profile-radius);
        box-shadow: var(--sifec-profile-shadow);
        border: 1px solid var(--sifec-profile-border);
        overflow: hidden;
    }
    .sifec-profile-card--fill {
        height: 100%;
    }
    .sifec-profile-card-h {
        font-size: 0.8125rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--sifec-profile-muted);
        padding: 0.9rem 1.15rem;
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid var(--sifec-profile-border);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .sifec-profile-card-h i {
        color: var(--sifec-profile-green-600);
    }

    .sifec-profile-dl {
        margin: 0;
        padding: 0.35rem 0;
    }
    .sifec-profile-dl > div {
        display: grid;
        grid-template-columns: minmax(0, 38%) minmax(0, 1fr);
        gap: 0.5rem 1rem;
        padding: 0.65rem 1.15rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
        align-items: start;
    }
    .sifec-profile-dl > div:last-child {
        border-bottom: none;
    }
    .sifec-profile-dl dt {
        margin: 0;
        color: var(--sifec-profile-muted);
        font-weight: 600;
        font-size: 0.8125rem;
    }
    .sifec-profile-dl dd {
        margin: 0;
        min-width: 0;
        color: var(--sifec-profile-text);
        font-weight: 500;
        word-break: break-word;
        overflow-wrap: anywhere;
    }

    /* Métriques */
    .sifec-profile-metric {
        background: #fff;
        border-radius: var(--sifec-profile-radius);
        border: 1px solid var(--sifec-profile-border);
        box-shadow: var(--sifec-profile-shadow);
        padding: 1.1rem 1.15rem;
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
        height: 100%;
    }
    .sifec-profile-metric-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .sifec-profile-metric-value {
        font-size: 1.35rem;
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -0.02em;
        color: var(--sifec-profile-text);
    }
    .sifec-profile-metric-label {
        font-size: 0.75rem;
        color: var(--sifec-profile-muted);
        font-weight: 600;
        margin-top: 0.2rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .sifec-profile-metric--green .sifec-profile-metric-icon {
        background: #ecfdf5;
        color: var(--sifec-profile-green-600);
    }
    .sifec-profile-metric--green .sifec-profile-metric-value {
        color: var(--sifec-profile-green-700);
    }
    .sifec-profile-metric--amber .sifec-profile-metric-icon {
        background: #fffbeb;
        color: #b45309;
    }
    .sifec-profile-metric--amber .sifec-profile-metric-value {
        color: #92400e;
        font-size: 1.05rem;
    }
    .sifec-profile-metric--slate .sifec-profile-metric-icon {
        background: #f1f5f9;
        color: #475569;
    }

    /* Onglets */
    .sifec-profile-tabs.nav {
        border-bottom: 1px solid var(--sifec-profile-border);
        gap: 0.15rem;
        flex-wrap: wrap;
        padding: 0 0.25rem;
    }
    .sifec-profile-tabs .nav-link {
        color: var(--sifec-profile-muted) !important;
        border: none;
        border-bottom: 2px solid transparent;
        padding: 0.65rem 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 0;
        background: transparent;
    }
    .sifec-profile-tabs .nav-link:hover {
        color: var(--sifec-profile-green-600) !important;
        border-bottom-color: #bbf7d0;
    }
    .sifec-profile-tabs .nav-link.active {
        color: var(--sifec-profile-green-600) !important;
        border-bottom-color: var(--sifec-profile-green-500);
        background: transparent;
    }

    /* Badges */
    .sifec-profile-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.28rem 0.65rem;
        border-radius: 999px;
    }
    .sifec-profile-badge--ok {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    .sifec-profile-badge--off {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    .sifec-profile-badge--neutral {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    /* Sécurité tuiles */
    .sifec-profile-sec-tile {
        border: 1px solid var(--sifec-profile-border);
        border-radius: var(--sifec-profile-radius);
        padding: 1.35rem 1.15rem;
        text-align: center;
        background: #fff;
        height: 100%;
        transition: box-shadow 0.2s, border-color 0.2s;
    }
    .sifec-profile-sec-tile:hover {
        box-shadow: var(--sifec-profile-shadow);
        border-color: #cbd5e1;
    }
    .sifec-profile-sec-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        margin: 0 auto 1rem;
    }

    /* Activité timeline */
    .sifec-profile-timeline {
        position: relative;
        padding-left: 0.5rem;
    }
    .sifec-profile-timeline::before {
        content: '';
        position: absolute;
        left: 17px;
        top: 8px;
        bottom: 8px;
        width: 2px;
        background: linear-gradient(180deg, var(--sifec-profile-green-400), #e2e8f0);
        border-radius: 2px;
    }
    .sifec-profile-tl-item {
        position: relative;
        padding: 0.65rem 0 0.65rem 2.75rem;
    }
    .sifec-profile-tl-dot {
        position: absolute;
        left: 0;
        top: 0.85rem;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        border: 2px solid #fff;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    }

    /* Zone signature */
    .sifec-profile-sig-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        background: #fafbfc;
        transition: border-color 0.2s, background 0.2s;
    }
    .sifec-profile-sig-zone:hover {
        border-color: var(--sifec-profile-green-500);
        background: #f0fdf4;
    }
    .sifec-profile-sig-preview {
        max-height: 110px;
        max-width: 100%;
        object-fit: contain;
    }
</style>
@endsection

@section('corps')
@php
    $p = $user->personne;
    $aff = $user->affectationActive();
    $nomComplet = $p ? trim($p->nom.' '.$p->prenom) : 'Utilisateur';
    $initials = $p
        ? mb_strtoupper(mb_substr($p->nom, 0, 1).mb_substr($p->prenom, 0, 1))
        : mb_strtoupper(mb_substr($user->email ?? '?', 0, 2));
@endphp

<div class="sifec-profile-page">
    <div class="container-fluid px-2 px-lg-3" style="max-width: 1320px; margin: 0 auto;">

        <nav class="sifec-profile-breadcrumb" aria-label="Fil d'Ariane">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ url('/home') }}">Accueil</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('utilisateur.index') }}">Utilisateurs</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Profil</li>
            </ol>
        </nav>

        <div class="sifec-profile-hero sifec-profile-hero--sifec-menu mb-4">
            <div class="sifec-profile-hero-mesh" aria-hidden="true">
                <span class="sifec-profile-hero-orb sifec-profile-hero-orb--light"></span>
                <span class="sifec-profile-hero-orb sifec-profile-hero-orb--jaune"></span>
                <span class="sifec-profile-hero-blob sifec-profile-hero-blob--1"></span>
                <span class="sifec-profile-hero-blob sifec-profile-hero-blob--2"></span>
                <span class="sifec-profile-hero-blob sifec-profile-hero-blob--3"></span>
            </div>
            <div class="sifec-profile-hero-inner">
                <div class="row align-items-center g-4">
                    <div class="col-auto">
                        <div class="sifec-profile-avatar">
                            @if($p && $p->signature)
                                <img src="{{ asset('app/'.$p->signature) }}" alt="Photo / signature">
                            @else
                                <span class="initials">{{ $initials }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col">
                        <h1 class="sifec-profile-name">{{ $nomComplet }}</h1>
                        <span class="sifec-profile-chip">
                            <i class="fas fa-id-badge"></i>
                            {{ $aff?->fonction?->lib_fonction ?? 'Fonction non définie' }}
                        </span>
                        <div class="sifec-profile-meta">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="fas fa-envelope"></i>
                                <span>{{ $user->email }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-building"></i>
                                <span>{{ $aff?->institution?->lib_institution ?? 'Non affecté' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-auto">
                        <div class="sifec-profile-actions">
                            <a href="{{ route('utilisateur.edit', $user->code_user) }}" class="btn btn-light text-dark">
                                <i class="fas fa-user-edit me-2"></i>Modifier le profil
                            </a>
                            <a href="{{ route('utilisateur.assigner.permission', $user->code_user) }}" class="btn btn-outline-light">
                                <i class="fas fa-key me-2"></i>Permissions
                            </a>
                            @can('module.menus.administration')
                            <a href="{{ route('two-factor.index') }}" class="btn btn-outline-light">
                                <i class="fas fa-shield-alt me-2"></i>Administration 2FA
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="sifec-profile-content row g-3 g-lg-4 mb-4">
            <div class="col-6 col-xl-3">
                <div class="sifec-profile-metric sifec-profile-metric--slate">
                    <div class="sifec-profile-metric-icon"><i class="fas fa-calendar-alt"></i></div>
                    <div>
                        <div class="sifec-profile-metric-value">{{ $user->created_at->diffInDays(now()) }}</div>
                        <div class="sifec-profile-metric-label">Ancienneté (jours)</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="sifec-profile-metric {{ $user->hasTwoFactorEnabled() ? 'sifec-profile-metric--green' : 'sifec-profile-metric--amber' }}">
                    <div class="sifec-profile-metric-icon"><i class="fas fa-shield-alt"></i></div>
                    <div>
                        <div class="sifec-profile-metric-value">{{ $user->hasTwoFactorEnabled() ? 'Oui' : 'Non' }}</div>
                        <div class="sifec-profile-metric-label">Double authentification</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="sifec-profile-metric {{ $user->status ? 'sifec-profile-metric--green' : 'sifec-profile-metric--amber' }}">
                    <div class="sifec-profile-metric-icon"><i class="fas fa-user-check"></i></div>
                    <div>
                        <div class="sifec-profile-metric-value">{{ $user->status ? 'Actif' : 'Inactif' }}</div>
                        <div class="sifec-profile-metric-label">Statut du compte</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="sifec-profile-metric sifec-profile-metric--green">
                    <div class="sifec-profile-metric-icon"><i class="fas fa-user-plus"></i></div>
                    <div>
                        <div class="sifec-profile-metric-value" style="font-size:1.05rem;">{{ $user->created_at->format('d/m/Y') }}</div>
                        <div class="sifec-profile-metric-label">Compte créé</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 g-lg-4 align-items-lg-start">
            <div class="col-lg-4">
                @if($p)
                <div class="sifec-profile-card mb-3">
                    <div class="sifec-profile-card-h"><i class="fas fa-user"></i> Identité</div>
                    <dl class="sifec-profile-dl">
                        <div>
                            <dt>Nom complet</dt>
                            <dd>{{ $nomComplet }}</dd>
                        </div>
                        @if($p->date_naissance)
                        <div>
                            <dt>Date de naissance</dt>
                            <dd>{{ \Carbon\Carbon::parse($p->date_naissance)->format('d/m/Y') }}</dd>
                        </div>
                        @endif
                        @if($p->sexe)
                        <div>
                            <dt>Sexe</dt>
                            <dd>{{ $p->sexe === 'F' ? 'Féminin' : 'Masculin' }}</dd>
                        </div>
                        @endif
                        @if($p->telephone)
                        <div>
                            <dt>Téléphone</dt>
                            <dd>{{ $p->telephone }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
                @php
                    $contactAgent = $p?->contacts?->first();
                    $normEmail = static function ($v) {
                        $s = preg_replace('/\s+/u', ' ', trim((string) ($v ?? '')));

                        return $s;
                    };
                    $emailProAgent = $contactAgent ? $normEmail($contactAgent->email_professionnelle) : '';
                    $emailPersoAgent = $contactAgent ? $normEmail($contactAgent->email_personnelle) : '';
                @endphp
                @if($contactAgent)
                <div class="sifec-profile-card mb-3">
                    <div class="sifec-profile-card-h"><i class="fas fa-at"></i> E-mails de contact (agent)</div>
                    <dl class="sifec-profile-dl">
                        @if($emailProAgent !== '')
                        <div>
                            <dt>E-mail professionnel</dt>
                            <dd>
                                <a href="mailto:{{ $emailProAgent }}" class="sifec-profile-link">
                                    <span class="sifec-profile-email-value">{{ $emailProAgent }}</span>
                                </a>
                            </dd>
                        </div>
                        @endif
                        @if($emailPersoAgent !== '')
                        <div>
                            <dt>E-mail personnel</dt>
                            <dd>
                                <a href="mailto:{{ $emailPersoAgent }}" class="sifec-profile-link">
                                    <span class="sifec-profile-email-value">{{ $emailPersoAgent }}</span>
                                </a>
                            </dd>
                        </div>
                        @endif
                        @if($emailProAgent === '' && $emailPersoAgent === '')
                        <div>
                            <dt>E-mails</dt>
                            <dd class="text-muted">Non renseignés sur la fiche contact</dd>
                        </div>
                        @endif
                    </dl>
                </div>
                @else
                <div class="sifec-profile-card mb-3">
                    <div class="sifec-profile-card-h"><i class="fas fa-at"></i> E-mails de contact (agent)</div>
                    <p class="small text-muted mb-0 px-3 pb-3">Aucune fiche <code>t_contact_personne</code> pour cette personne — les e-mails professionnel / personnel et le SMS OTP ne pourront pas être utilisés tant que le contact n’est pas créé.</p>
                </div>
                @endif
                @endif

                <div class="sifec-profile-card mb-3">
                    <div class="sifec-profile-card-h"><i class="fas fa-briefcase"></i> Affectation</div>
                    <dl class="sifec-profile-dl">
                        <div>
                            <dt>Institution</dt>
                            <dd>{{ $aff?->institution?->lib_institution ?? 'Non affecté' }}</dd>
                        </div>
                        <div>
                            <dt>Fonction</dt>
                            <dd>{{ $aff?->fonction?->lib_fonction ?? 'Non définie' }}</dd>
                        </div>
                        <div>
                            <dt>Type d’institution</dt>
                            <dd>{{ $aff?->institution?->typeInstitution?->lib_type_institution ?? '—' }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="sifec-profile-card">
                    <div class="sifec-profile-card-h"><i class="fas fa-signature"></i> Signature de l’agent</div>
                    <div class="p-3">
                        <div class="sifec-profile-sig-zone mb-3">
                            @if($p && $p->signature)
                                <img src="{{ asset('app/'.$p->signature) }}"
                                     alt="Signature"
                                     id="sig-preview"
                                     class="sifec-profile-sig-preview">
                                <div class="small text-success fw-semibold mt-2"><i class="fas fa-check-circle me-1"></i>Signature enregistrée</div>
                            @else
                                <div id="sig-placeholder">
                                    <i class="fas fa-pen-fancy fa-2x text-muted mb-2 d-block opacity-50"></i>
                                    <span class="text-muted small">Aucune signature — importez une image PNG ou JPG</span>
                                </div>
                                <img src="" alt="" id="sig-preview" class="sifec-profile-sig-preview d-none">
                            @endif
                        </div>

                        <form action="{{ route('utilisateur.signature', $user->code_user) }}"
                              method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <label class="form-label small fw-semibold text-muted mb-1" for="sig-input">
                                {{ ($p && $p->signature) ? 'Remplacer la signature' : 'Ajouter une signature' }}
                            </label>
                            <input type="file"
                                   class="form-control form-control-sm @error('signature') is-invalid @enderror"
                                   name="signature"
                                   id="sig-input"
                                   accept="image/png,image/jpeg">
                            <div class="form-text small">PNG ou JPG — 2&nbsp;Mo max</div>
                            @error('signature')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <button type="submit" class="btn btn-sm w-100 text-white mt-2 fw-semibold" style="background: linear-gradient(135deg, #006B31, #009E49); border: none;">
                                <i class="fas fa-save me-1"></i>Enregistrer
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="sifec-profile-card sifec-profile-card--fill">
                    <div class="p-0">
                        <ul class="nav sifec-profile-tabs px-2 pt-2" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="tab-ov-btn" data-bs-toggle="tab" data-bs-target="#tab-overview" type="button" role="tab" aria-controls="tab-overview" aria-selected="true">
                                    <i class="fas fa-layer-group me-1"></i>Résumé
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-sec-btn" data-bs-toggle="tab" data-bs-target="#tab-security" type="button" role="tab">
                                    <i class="fas fa-lock me-1"></i>Sécurité
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-act-btn" data-bs-toggle="tab" data-bs-target="#tab-activity" type="button" role="tab">
                                    <i class="fas fa-stream me-1"></i>Activité
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content p-3 p-md-4">
                            <div class="tab-pane fade show active" id="tab-overview" role="tabpanel" aria-labelledby="tab-ov-btn">
                                <div class="row g-3 align-items-stretch">
                                    <div class="col-md-6 d-flex">
                                        <div class="sifec-profile-card sifec-profile-card--fill border-0 shadow-none flex-grow-1" style="border: 1px solid var(--sifec-profile-border) !important;">
                                            <div class="sifec-profile-card-h">Compte</div>
                                            <dl class="sifec-profile-dl">
                                                <div>
                                                    <dt>Adresse e-mail</dt>
                                                    <dd>{{ $user->email }}</dd>
                                                </div>
                                                <div>
                                                    <dt>Statut</dt>
                                                    <dd>
                                                        <span class="sifec-profile-badge {{ $user->status ? 'sifec-profile-badge--ok' : 'sifec-profile-badge--off' }}">
                                                            {{ $user->status ? 'Actif' : 'Inactif' }}
                                                        </span>
                                                    </dd>
                                                </div>
                                                <div>
                                                    <dt>2FA</dt>
                                                    <dd>
                                                        <span class="sifec-profile-badge {{ $user->hasTwoFactorEnabled() ? 'sifec-profile-badge--ok' : 'sifec-profile-badge--neutral' }}">
                                                            {{ $user->hasTwoFactorEnabled() ? 'Activée' : 'Non activée' }}
                                                        </span>
                                                    </dd>
                                                </div>
                                                <div>
                                                    <dt>Création</dt>
                                                    <dd>{{ $user->created_at->format('d/m/Y à H:i') }}</dd>
                                                </div>
                                                <div>
                                                    <dt>Dernière mise à jour</dt>
                                                    <dd>{{ $user->updated_at->format('d/m/Y à H:i') }}</dd>
                                                </div>
                                            </dl>
                                        </div>
                                    </div>
                                    <div class="col-md-6 d-flex">
                                        <div class="sifec-profile-card sifec-profile-card--fill border-0 shadow-none flex-grow-1" style="border: 1px solid var(--sifec-profile-border) !important;">
                                            <div class="sifec-profile-card-h">Affectation actuelle</div>
                                            <dl class="sifec-profile-dl">
                                                <div>
                                                    <dt>Institution</dt>
                                                    <dd>{{ $aff?->institution?->lib_institution ?? '—' }}</dd>
                                                </div>
                                                <div>
                                                    <dt>Fonction</dt>
                                                    <dd>{{ $aff?->fonction?->lib_fonction ?? '—' }}</dd>
                                                </div>
                                                <div>
                                                    <dt>Type</dt>
                                                    <dd>{{ $aff?->institution?->typeInstitution?->lib_type_institution ?? '—' }}</dd>
                                                </div>
                                            </dl>
                                            <div class="px-3 pb-3">
                                                @can('module.users')
                                                    <a href="{{ route('utilisateur.profile.mise-a-jour', $user->code_user) }}" class="btn btn-primary w-100">
                                                        <i class="fas fa-edit me-1"></i>Mettre à jour les données
                                                    </a>
                                                @else
                                                    <p class="text-muted small mb-0">La mise à jour des affectations et du compte est réservée aux utilisateurs disposant du droit <strong>Gestion des utilisateurs</strong>.</p>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab-security" role="tabpanel">
                                <div class="row g-3">
                                    @can('module.menus.administration')
                                    <div class="col-md-6">
                                        <div class="sifec-profile-sec-tile">
                                            <div class="sifec-profile-sec-icon" style="background: {{ $user->hasTwoFactorEnabled() ? '#ecfdf5' : '#fff7ed' }}; color: {{ $user->hasTwoFactorEnabled() ? '#047857' : '#c2410c' }};">
                                                <i class="fas fa-shield-alt"></i>
                                            </div>
                                            <h3 class="h6 fw-bold mb-2">Double authentification</h3>
                                            <p class="text-muted small mb-3">Configuration réservée aux administrateurs (menu Administration).</p>
                                            <a href="{{ route('two-factor.index') }}" class="btn btn-sm {{ $user->hasTwoFactorEnabled() ? 'btn-outline-success' : 'btn-success' }} w-100">
                                                <i class="fas fa-cog me-1"></i>{{ $user->hasTwoFactorEnabled() ? 'Gérer la 2FA' : 'Configurer la 2FA' }}
                                            </a>
                                        </div>
                                    </div>
                                    @else
                                    <div class="col-md-6">
                                        <div class="sifec-profile-sec-tile text-start">
                                            <div class="sifec-profile-sec-icon mx-0 mb-3" style="background: #f1f5f9; color: #475569;">
                                                <i class="fas fa-shield-alt"></i>
                                            </div>
                                            <h3 class="h6 fw-bold mb-2">Double authentification</h3>
                                            <p class="text-muted small mb-0">
                                                L’activation ou la désactivation de la 2FA est effectuée par un <strong>administrateur SIFEC</strong>.
                                                État actuel du compte affiché&nbsp;: <strong>{{ $user->hasTwoFactorEnabled() ? 'activée' : 'non activée' }}</strong>.
                                            </p>
                                        </div>
                                    </div>
                                    @endcan
                                    <div class="col-md-6">
                                        <div class="sifec-profile-sec-tile">
                                            <div class="sifec-profile-sec-icon" style="background: #fffbeb; color: #b45309;">
                                                <i class="fas fa-key"></i>
                                            </div>
                                            <h3 class="h6 fw-bold mb-2">Mot de passe</h3>
                                            <p class="text-muted small mb-3">Modifiez votre mot de passe régulièrement.</p>
                                            <a href="{{ route('utilisateur.change-password', $user->code_user) }}" class="btn btn-sm btn-success w-100">
                                                <i class="fas fa-unlock-alt me-1"></i>Changer le mot de passe
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab-activity" role="tabpanel">
                                <div class="sifec-profile-timeline">
                                    <div class="sifec-profile-tl-item">
                                        <div class="sifec-profile-tl-dot" style="background:#ecfdf5;color:#047857;">
                                            <i class="fas fa-user-plus"></i>
                                        </div>
                                        <div class="fw-semibold small">Compte créé</div>
                                        <div class="text-muted small">{{ $user->created_at->format('d/m/Y à H:i') }}</div>
                                    </div>
                                    <div class="sifec-profile-tl-item">
                                        <div class="sifec-profile-tl-dot" style="background:#eff6ff;color:#1d4ed8;">
                                            <i class="fas fa-sync-alt"></i>
                                        </div>
                                        <div class="fw-semibold small">Dernière mise à jour du profil</div>
                                        <div class="text-muted small">{{ $user->updated_at->format('d/m/Y à H:i') }}</div>
                                    </div>
                                    @if($user->hasTwoFactorEnabled())
                                    <div class="sifec-profile-tl-item">
                                        <div class="sifec-profile-tl-dot" style="background:#ecfeff;color:#0e7490;">
                                            <i class="fas fa-shield-alt"></i>
                                        </div>
                                        <div class="fw-semibold small">2FA activée</div>
                                        <div class="text-muted small">
                                            {{ $user->two_factor_verified_at ? $user->two_factor_verified_at->format('d/m/Y à H:i') : 'Récemment' }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
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
    var input = document.getElementById('sig-input');
    if (!input) return;
    input.addEventListener('change', function () {
        var file = this.files[0];
        if (!file) return;
        var preview = document.getElementById('sig-preview');
        var placeholder = document.getElementById('sig-placeholder');
        var reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            if (placeholder) placeholder.classList.add('d-none');
        };
        reader.readAsDataURL(file);
    });
})();
</script>
@endsection
