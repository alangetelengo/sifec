@extends('layout.app')
@section('titre')
    Tableau de bord — {{ $roleBadge }}
@endsection

@section('styles')
@include('admin.dashboard._sifec_dashboard_theme')
<style>
    .fs-stat-tbl { width:100%; border-collapse:separate; border-spacing:0; }
    .fs-stat-tbl th, .fs-stat-tbl td {
        padding:10px 14px; font-size:.86rem; border-bottom:1px solid #eef0f2; vertical-align:middle;
    }
    .fs-stat-tbl thead th {
        font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.4px;
        color:#5a5a5a; background:#f6f7f9; border-bottom:2px solid #8bc4a8;
    }
    .fs-stat-tbl tbody tr:hover { background:#fafcfb; }
    .fs-stat-lbl { color:#444; font-weight:500; }
    .fs-stat-val { font-weight:800; text-align:right; font-variant-numeric:tabular-nums; }
    .fs-stat-val.na { color:#4a8f68; }
    .fs-stat-val.de { color:#DC241F; }
    .fs-hint { font-size:.72rem; color:#888; font-weight:400; display:block; margin-top:2px; }
    .fs-st-pill { font-size:.68rem; padding:3px 9px; border-radius:12px; font-weight:600; white-space:nowrap; }
    .fs-st-brouillon { background:#fff3cd; color:#856404; }
    .fs-st-transmis { background:#cce5ff; color:#004085; }
    .fs-st-valide { background:#d4edda; color:#155724; }
    .fs-actions .mod-card .mc-ico.naissance { background:rgba(74,143,104,0.14); color:#4a8f68; }
    .fs-actions .mod-card .mc-ico.deces { background:rgba(220,36,31,0.12); color:#DC241F; }
    .fs-actions .mod-card .mc-ico.compte { background:rgba(90,155,201,0.14); color:#5a9bc9; }
    .fs-btn-deces-tout {
        background: linear-gradient(135deg, #e05550, #c41e1a);
        color: #fff !important;
        border: none;
    }
    .fs-btn-deces-tout:hover { color: #fff !important; filter: brightness(1.06); }
    .fs-recap-deces .recap-tbl thead th { border-bottom-color: #DC241F; }
    .fs-stat-val.ma { color: #5c6d82; }
    .fs-stat-tbl-mariage thead th { border-bottom-color: #a8b8c9; }
    .fs-recap-mariage .recap-tbl thead th { border-bottom-color: #a8b8c9; }
    .db-row-kpi-dossiers .sec-card { height: 100%; }
    .db-row-kpi-dossiers .fait-card { height: 100%; }
</style>
@endsection

@section('corps')
@php
    $nomAgent = $user->personne ? $user->personne->nomcomplet() : ($user->email ?? '');
    $noteParcoursDecesBrazzaville = $noteParcoursDecesBrazzaville ?? null;
@endphp

<div class="db-wrap">
    <div class="db-header">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-3 mb-lg-0">
                <div class="hd-title">
                    <i class="{{ $headerIcon }} me-2" style="opacity:.9;"></i>
                    {{ $libInstitution }}
                </div>
                <div class="hd-sub mt-2">
                    <span class="hd-badge"><i class="fa fa-briefcase me-1"></i>{{ $roleBadge }}</span>
                    @if($cec)
                        <span class="hd-badge"><i class="fa fa-sitemap me-1"></i>Rattachement : {{ $cec->lib_institution }}</span>
                    @endif
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

    @if($noteParcoursDecesBrazzaville)
    <div class="alert alert-info border-0 shadow-sm mb-3" style="background:#e8f4fd;border-left:4px solid #5a9bc9 !important;">
        <i class="fa fa-info-circle me-2"></i>{{ $noteParcoursDecesBrazzaville }}
    </div>
    @endif

    <div class="sec-card fs-actions mb-3">
        <div class="sc-head">
            <h5 class="sc-title mb-0"><i class="fa fa-bolt" style="color:#7e8fa3;"></i> Accès rapide</h5>
        </div>
        <div class="sc-body">
            <div class="row g-2">
                @can('module.menus.naissance')
                <div class="col-xl-2 col-md-3 col-6">
                    <a href="{{ route('declarationNaissance.index') }}" class="mod-card h-100">
                        <div class="mc-ico naissance"><i class="fa fa-baby"></i></div>
                        <span class="mc-lbl">Déclarations naissance</span>
                    </a>
                </div>
                <div class="col-xl-2 col-md-3 col-6">
                    <a href="{{ route('acteNaissance.index') }}" class="mod-card h-100">
                        <div class="mc-ico" style="background:rgba(90,155,201,0.14);color:#5a9bc9;"><i class="fa fa-file-medical"></i></div>
                        <span class="mc-lbl">Actes naissance</span>
                    </a>
                </div>
                @endcan
                @can('module.menus.mariage')
                <div class="col-xl-2 col-md-3 col-6">
                    <a href="{{ route('declarationMariage.index') }}" class="mod-card h-100">
                        <div class="mc-ico" style="background:rgba(100,119,140,0.16);color:#5c6d82;"><i class="fa fa-ring"></i></div>
                        <span class="mc-lbl">Mariage</span>
                    </a>
                </div>
                @endcan
                @if($showKpiDeces)
                @can('module.menus.naissance')
                <div class="col-xl-2 col-md-3 col-6">
                    <a href="{{ route('declarationDeces.index') }}" class="mod-card h-100">
                        <div class="mc-ico deces"><i class="fa fa-cross"></i></div>
                        <span class="mc-lbl">Décès</span>
                    </a>
                </div>
                @endcan
                @endif
                @can('module.menus.tribunal')
                <div class="col-xl-2 col-md-3 col-6">
                    <a href="{{ route('tribunal.document.index') }}" class="mod-card h-100">
                        <div class="mc-ico" style="background:rgba(120,144,156,0.18);color:#546e7a;"><i class="fa fa-gavel"></i></div>
                        <span class="mc-lbl">Tribunal</span>
                    </a>
                </div>
                @endcan
                @can('module.menus.referentiel')
                <div class="col-xl-2 col-md-3 col-6">
                    <a href="{{ route('localite.index') }}" class="mod-card h-100">
                        <div class="mc-ico" style="background:rgba(74,124,98,0.12);color:#4a7c62;"><i class="fa fa-map-marked-alt"></i></div>
                        <span class="mc-lbl">Localités</span>
                    </a>
                </div>
                <div class="col-xl-2 col-md-3 col-6">
                    <a href="{{ route('typelocalite.index') }}" class="mod-card h-100">
                        <div class="mc-ico" style="background:rgba(74,143,104,0.12);color:#4a8f68;"><i class="fa fa-layer-group"></i></div>
                        <span class="mc-lbl">Types localité</span>
                    </a>
                </div>
                <div class="col-xl-2 col-md-3 col-6">
                    <a href="{{ route('institution.index') }}" class="mod-card h-100">
                        <div class="mc-ico" style="background:rgba(90,155,201,0.12);color:#5a9bc9;"><i class="fa fa-university"></i></div>
                        <span class="mc-lbl">Institutions</span>
                    </a>
                </div>
                @endcan
                @can('module.menus.administration')
                <div class="col-xl-2 col-md-3 col-6">
                    <a href="{{ route('utilisateur.index') }}" class="mod-card h-100">
                        <div class="mc-ico" style="background:rgba(74,124,98,0.12);color:#4a7c62;"><i class="fa fa-users-cog"></i></div>
                        <span class="mc-lbl">Administration</span>
                    </a>
                </div>
                @endcan
                <div class="col-xl-2 col-md-3 col-6">
                    <a href="{{ route('dashboard.carteducongo') }}" class="mod-card h-100">
                        <div class="mc-ico" style="background:rgba(33,185,49,0.1);color:#21B931;"><i class="fa fa-map-marked-alt"></i></div>
                        <span class="mc-lbl">Carte</span>
                    </a>
                </div>
                <div class="col-xl-2 col-md-3 col-6">
                    <a href="{{ route('dashboard.statgenredep') }}" class="mod-card h-100">
                        <div class="mc-ico" style="background:rgba(220,36,31,0.08);color:#DC241F;"><i class="fa fa-chart-pie"></i></div>
                        <span class="mc-lbl">Statistiques</span>
                    </a>
                </div>
                <div class="col-xl-2 col-md-3 col-6">
                    <a href="{{ route('utilisateur.profile', $user->code_user) }}" class="mod-card h-100">
                        <span class="mc-ico compte"><i class="fa fa-id-card"></i></span>
                        <span class="mc-lbl">Mon profil</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if($showKpiNaissance)
    <div class="row g-3 mb-3 align-items-stretch db-row-kpi-dossiers">
        <div class="col-12 col-xl-8 col-lg-7">
            <div class="fait-card fait-naissance h-100">
                <div class="fait-head">
                    <div class="fait-title">
                        <div class="fait-icon"><i class="fa fa-baby"></i></div>
                        Certificats &amp; naissances
                    </div>
                </div>
                <div class="fait-body p-0">
                    <table class="fs-stat-tbl mb-0">
                        <thead>
                            <tr>
                                <th>Indicateur</th>
                                <th class="text-end">Valeur</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fs-stat-lbl">Enregistrés <span class="fs-hint">Total dossiers (certificats médicaux, déclarations, pièces tribunal / transcription)</span></td>
                                <td class="fs-stat-val na">{{ $kpi_naissance['enregistres'] }}</td>
                            </tr>
                            <tr>
                                <td class="fs-stat-lbl">Certificats de naissance enregistrés</td>
                                <td class="fs-stat-val na">{{ $kpi_naissance['certificats_enregistres'] }}</td>
                            </tr>
                            <tr>
                                <td class="fs-stat-lbl">Déclarations de naissance produites</td>
                                <td class="fs-stat-val na">{{ $kpi_naissance['declarations_produites'] }}</td>
                            </tr>
                            <tr>
                                <td class="fs-stat-lbl">Dossiers tribunal / transcription <span class="fs-hint">CNI, destruction, fiche ou certificat de transcription</span></td>
                                <td class="fs-stat-val na">{{ $kpi_naissance['dossiers_tribunal'] ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td class="fs-stat-lbl">Envoyés au CEC <span class="fs-hint">Au moins un mouvement « Envoyée »</span></td>
                                <td class="fs-stat-val na">{{ $kpi_naissance['envoyes'] }}</td>
                            </tr>
                            <tr>
                                <td class="fs-stat-lbl">Validés par le CEC</td>
                                <td class="fs-stat-val na">{{ $kpi_naissance['valides'] }}</td>
                            </tr>
                            <tr>
                                <td class="fs-stat-lbl">En attente <span class="fs-hint">Non validés par le CEC</span></td>
                                <td class="fs-stat-val na">{{ $kpi_naissance['en_attente'] }}</td>
                            </tr>
                            <tr>
                                <td class="fs-stat-lbl">Actes signés <span class="fs-hint">Après validation OTP par l’officier d’état civil</span></td>
                                <td class="fs-stat-val na">{{ $kpi_naissance['actes_produits'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4 col-lg-5">
            <div class="sec-card h-100">
                <div class="sc-head">
                    <h5 class="sc-title mb-0"><i class="fa fa-clock" style="color:#4a8f68;"></i> Derniers dossiers — Naissance</h5>
                    <a href="{{ route('declarationNaissance.index') }}" class="btn btn-sm btn-success text-white fw-semibold px-3">Voir tout</a>
                </div>
                <div class="sc-body tbl-scroll p-0">
                    <table class="recap-tbl mb-0">
                        <thead>
                            <tr>
                                <th>Réf.</th>
                                <th>Date</th>
                                <th>Enfant</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_naissances as $dn)
                                @php
                                    $st = $dn->cec_approuver === 'OUI'
                                        ? ['Validé CEC', 'fs-st-valide']
                                        : ($dn->mouvements->where('statut', 'Envoyée')->isNotEmpty()
                                            ? ['Transmis', 'fs-st-transmis']
                                            : ['À transmettre', 'fs-st-brouillon']);
                                @endphp
                                <tr>
                                    <td>
                                        <a href="{{ route('declarationNaissance.show', $dn->code_declaration_naissance) }}">{{ \Illuminate\Support\Str::limit($dn->code_declaration_naissance, 14) }}</a>
                                    </td>
                                    <td>{{ $dn->date_heure_declaration ? \Carbon\Carbon::parse($dn->date_heure_declaration)->format('d/m/Y H:i') : '—' }}</td>
                                    <td>{{ $dn->enfant ? $dn->enfant->nomcomplet() : '—' }}</td>
                                    <td><span class="fs-st-pill {{ $st[1] }}">{{ $st[0] }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">Aucun dossier pour ce périmètre.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($showKpiDeces)
    <div class="row g-3 mb-3 align-items-stretch db-row-kpi-dossiers">
        <div class="col-12 col-xl-8 col-lg-7">
            <div class="fait-card fait-deces h-100">
                <div class="fait-head">
                    <div class="fait-title">
                        <div class="fait-icon"><i class="fa fa-cross"></i></div>
                        Certificats &amp; décès
                    </div>
                </div>
                <div class="fait-body p-0">
                    <table class="fs-stat-tbl mb-0">
                        <thead>
                            <tr>
                                <th>Indicateur</th>
                                <th class="text-end">Valeur</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fs-stat-lbl">Enregistrés <span class="fs-hint">Total dossiers (constatations + déclarations)</span></td>
                                <td class="fs-stat-val de">{{ $kpi_deces['enregistres'] }}</td>
                            </tr>
                            <tr>
                                <td class="fs-stat-lbl">Certificats de constatation enregistrés</td>
                                <td class="fs-stat-val de">{{ $kpi_deces['certificats_enregistres'] }}</td>
                            </tr>
                            <tr>
                                <td class="fs-stat-lbl">Déclarations de décès produites</td>
                                <td class="fs-stat-val de">{{ $kpi_deces['declarations_produites'] }}</td>
                            </tr>
                            <tr>
                                <td class="fs-stat-lbl">Envoyés au CEC</td>
                                <td class="fs-stat-val de">{{ $kpi_deces['envoyes'] }}</td>
                            </tr>
                            <tr>
                                <td class="fs-stat-lbl">Validés par le CEC</td>
                                <td class="fs-stat-val de">{{ $kpi_deces['valides'] }}</td>
                            </tr>
                            <tr>
                                <td class="fs-stat-lbl">En attente</td>
                                <td class="fs-stat-val de">{{ $kpi_deces['en_attente'] }}</td>
                            </tr>
                            <tr>
                                <td class="fs-stat-lbl">Actes signés <span class="fs-hint">Après validation OTP (signataire enregistré sur l’acte)</span></td>
                                <td class="fs-stat-val de">{{ $kpi_deces['actes_produits'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4 col-lg-5">
            <div class="sec-card fs-recap-deces h-100">
                <div class="sc-head">
                    <h5 class="sc-title mb-0"><i class="fa fa-clock" style="color:#DC241F;"></i> Derniers dossiers — Décès</h5>
                    <a href="{{ route('declarationDeces.index') }}" class="btn btn-sm fw-semibold px-3 fs-btn-deces-tout">Voir tout</a>
                </div>
                <div class="sc-body tbl-scroll p-0">
                    <table class="recap-tbl mb-0">
                        <thead>
                            <tr>
                                <th>Réf.</th>
                                <th>Date</th>
                                <th>Défunt</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_deces as $dd)
                                @php
                                    $st = $dd->cec_approuver === 'OUI'
                                        ? ['Validé CEC', 'fs-st-valide']
                                        : ($dd->mouvements->where('statut', 'Envoyée')->isNotEmpty()
                                            ? ['Transmis', 'fs-st-transmis']
                                            : ['À transmettre', 'fs-st-brouillon']);
                                @endphp
                                <tr>
                                    <td>
                                        <a href="{{ route('declarationDeces.show', $dd->code_declaration_deces) }}">{{ \Illuminate\Support\Str::limit($dd->code_declaration_deces, 14) }}</a>
                                    </td>
                                    <td>{{ $dd->date_heure_declaration ? \Carbon\Carbon::parse($dd->date_heure_declaration)->format('d/m/Y H:i') : '—' }}</td>
                                    <td>{{ $dd->defunt ? $dd->defunt->nomcomplet() : '—' }}</td>
                                    <td><span class="fs-st-pill {{ $st[1] }}">{{ $st[0] }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">Aucun dossier pour ce périmètre.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    @can('module.menus.mariage')
    <div class="row g-3 mb-3 align-items-stretch db-row-kpi-dossiers">
        <div class="col-12 col-xl-8 col-lg-7">
            <div class="fait-card fait-mariage h-100">
                <div class="fait-head">
                    <div class="fait-title">
                        <div class="fait-icon"><i class="fa fa-ring"></i></div>
                        Formulaires &amp; mariage
                    </div>
                </div>
                <div class="fait-body p-0">
                    <table class="fs-stat-tbl fs-stat-tbl-mariage mb-0">
                        <thead>
                            <tr>
                                <th>Indicateur</th>
                                <th class="text-end">Valeur</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fs-stat-lbl">Enregistrés <span class="fs-hint">Total formulaires (déclaration de mariage + dispense)</span></td>
                                <td class="fs-stat-val ma">{{ $kpi_mariage['enregistres'] }}</td>
                            </tr>
                            <tr>
                                <td class="fs-stat-lbl">Formulaire type « déclaration de mariage »</td>
                                <td class="fs-stat-val ma">{{ $kpi_mariage['formulaires_declaration_mariage'] }}</td>
                            </tr>
                            <tr>
                                <td class="fs-stat-lbl">Formulaire type « dispense »</td>
                                <td class="fs-stat-val ma">{{ $kpi_mariage['formulaires_dispense'] }}</td>
                            </tr>
                            <tr>
                                <td class="fs-stat-lbl">Envoyés au CEC <span class="fs-hint">Au moins un mouvement « Envoyée »</span></td>
                                <td class="fs-stat-val ma">{{ $kpi_mariage['envoyes'] }}</td>
                            </tr>
                            <tr>
                                <td class="fs-stat-lbl">Validés par le CEC</td>
                                <td class="fs-stat-val ma">{{ $kpi_mariage['valides'] }}</td>
                            </tr>
                            <tr>
                                <td class="fs-stat-lbl">En attente <span class="fs-hint">Non validés par le CEC</span></td>
                                <td class="fs-stat-val ma">{{ $kpi_mariage['en_attente'] }}</td>
                            </tr>
                            <tr>
                                <td class="fs-stat-lbl">Actes signés <span class="fs-hint">Après validation OTP par l’officier d’état civil</span></td>
                                <td class="fs-stat-val ma">{{ $kpi_mariage['actes_produits'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4 col-lg-5">
            <div class="sec-card fs-recap-mariage h-100">
                <div class="sc-head">
                    <h5 class="sc-title mb-0"><i class="fa fa-clock" style="color:#5c6d82;"></i> Derniers dossiers — Mariage</h5>
                    <a href="{{ route('declarationMariage.index') }}" class="btn btn-sm fw-semibold px-3" style="background:#dde4ef;border:1px solid #c5d0e0;color:#3d4f5f;">Voir tout</a>
                </div>
                <div class="sc-body tbl-scroll p-0">
                    <table class="recap-tbl mb-0">
                        <thead>
                            <tr>
                                <th>Réf.</th>
                                <th>Date</th>
                                <th>Type formulaire</th>
                                <th>Époux / Épouse</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_mariages as $dm)
                                @php
                                    $st = $dm->cec_approuver === 'OUI'
                                        ? ['Validé CEC', 'fs-st-valide']
                                        : ($dm->mouvements->where('statut', 'Envoyée')->isNotEmpty()
                                            ? ['Transmis', 'fs-st-transmis']
                                            : ['À transmettre', 'fs-st-brouillon']);
                                    $libTypeForm = $dm->type_declaration === 'DISPENSE' ? 'Dispense' : 'Déclaration';
                                    $couple = trim(implode(' & ', array_filter([
                                        $dm->epoux ? $dm->epoux->nomcomplet() : null,
                                        $dm->epouse ? $dm->epouse->nomcomplet() : null,
                                    ])));
                                @endphp
                                <tr>
                                    <td>
                                        <a href="{{ route('declarationMariage.show', $dm->code_declaration_mariage) }}">{{ \Illuminate\Support\Str::limit($dm->code_declaration_mariage, 14) }}</a>
                                    </td>
                                    <td>{{ $dm->date_heure_declaration ? \Carbon\Carbon::parse($dm->date_heure_declaration)->format('d/m/Y H:i') : '—' }}</td>
                                    <td><span class="fs-st-pill fs-st-brouillon" style="font-size:.62rem;">{{ $libTypeForm }}</span></td>
                                    <td>{{ $couple !== '' ? $couple : '—' }}</td>
                                    <td><span class="fs-st-pill {{ $st[1] }}">{{ $st[0] }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">Aucun dossier pour ce périmètre.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>
@endsection
