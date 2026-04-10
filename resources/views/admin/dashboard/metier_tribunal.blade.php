@extends('layout.app')
@section('titre')
    Tableau de bord — Juridiction
@endsection

@section('styles')
@include('admin.dashboard._sifec_dashboard_theme')
<style>
    .tr-actions .mod-card .mc-ico.docs { background:rgba(251,222,74,0.2); color:#856404; }
    .tr-actions .mod-card .mc-ico.rect { background:rgba(220,36,31,0.1); color:#DC241F; }
    .tr-actions .mod-card .mc-ico.stats { background:rgba(0,158,73,0.12); color:#009E49; }
    .tr-actions .mod-card .mc-ico.req { background:rgba(39,129,213,0.12); color:#2781d5; }
</style>
@endsection

@section('corps')
@php
    $nomAgent = $user->personne ? $user->personne->nomcomplet() : ($user->email ?? '');
@endphp

<div class="db-wrap">
    <div class="db-header">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-3 mb-lg-0">
                <div class="hd-title">
                    <i class="fa fa-balance-scale me-2" style="opacity:.9;"></i>
                    {{ $libInstitution }}
                </div>
                <div class="hd-sub mt-2">
                    <span class="hd-badge"><i class="fa fa-gavel me-1"></i>{{ $roleBadge }}</span>
                    <span class="hd-badge"><i class="fa fa-user-tie me-1"></i>{{ $fonctionLib }}</span>
                </div>
                <div class="hd-sub" style="margin-top:6px;">
                    <span style="opacity:.85;"><i class="fa fa-user me-1"></i>{{ $nomAgent }}</span>
                    <span style="opacity:.65;">· {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</span>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    <a href="{{ route('notifications.index') }}" class="btn-hd"><i class="fa fa-bell"></i> Notifications</a>
                </div>
            </div>
        </div>
    </div>

    <div class="sec-card tr-actions mb-3">
        <div class="sc-head">
            <h5 class="sc-title mb-0"><i class="fa fa-bolt" style="color:#FBDE4A;"></i> Accès rapide — Tribunal</h5>
        </div>
        <div class="sc-body">
            <div class="row g-3">
                @can('module.menus.tribunal')
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('tribunal.document.index') }}" class="mod-card h-100">
                        <span class="mc-ico docs"><i class="fa fa-folder-open"></i></span>
                        <span class="mc-lbl">Dossiers &amp; documents</span>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('tribunal.document.rectification') }}" class="mod-card h-100">
                        <span class="mc-ico rect"><i class="fa fa-edit"></i></span>
                        <span class="mc-lbl">Rectifications</span>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('tribunal.document.historique') }}" class="mod-card h-100">
                        <span class="mc-ico stats"><i class="fa fa-history"></i></span>
                        <span class="mc-lbl">Historique</span>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('tribunal.document.envoyes') }}" class="mod-card h-100">
                        <span class="mc-ico stats"><i class="fa fa-paper-plane"></i></span>
                        <span class="mc-lbl">Envoyés au CEC</span>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('tribunal.document.stats') }}" class="mod-card h-100">
                        <span class="mc-ico stats"><i class="fa fa-chart-bar"></i></span>
                        <span class="mc-lbl">Statistiques tribunal</span>
                    </a>
                </div>
                @endcan
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('documents.requisitions') }}" class="mod-card h-100">
                        <span class="mc-ico req"><i class="fa fa-file-signature"></i></span>
                        <span class="mc-lbl">Réquisitions</span>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('documents.jugements') }}" class="mod-card h-100">
                        <span class="mc-ico req"><i class="fa fa-book"></i></span>
                        <span class="mc-lbl">Jugements</span>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('dashboard.carteducongo') }}" class="mod-card h-100">
                        <span class="mc-ico" style="background:rgba(33,185,49,0.1);color:#21B931;"><i class="fa fa-map-marked-alt"></i></span>
                        <span class="mc-lbl">Carte</span>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('dashboard.statgenredep') }}" class="mod-card h-100">
                        <span class="mc-ico" style="background:rgba(220,36,31,0.08);color:#DC241F;"><i class="fa fa-chart-pie"></i></span>
                        <span class="mc-lbl">Statistiques nationales</span>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('utilisateur.profile', $user->code_user) }}" class="mod-card h-100">
                        <span class="mc-ico" style="background:rgba(39,129,213,0.12);color:#2781d5;"><i class="fa fa-id-card"></i></span>
                        <span class="mc-lbl">Mon profil</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="sec-card">
        <div class="sc-head">
            <h5 class="sc-title mb-0"><i class="fa fa-info-circle" style="color:#2781d5;"></i> Espace juridiction</h5>
        </div>
        <div class="sc-body">
            <p class="mb-0 text-muted" style="font-size:.95rem;">
                Ce tableau de bord regroupe les raccourcis vers le traitement des dossiers transmis par les centres d’état civil et la formation sanitaire.
                Utilisez <strong>Dossiers &amp; documents</strong> pour la file active, puis <strong>Envoyés au CEC</strong> pour le suivi des retours.
            </p>
        </div>
    </div>
</div>
@endsection
