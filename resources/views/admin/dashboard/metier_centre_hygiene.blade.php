@extends('layout.app')
@section('titre')
    Tableau de bord — Centre d'hygiène
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
    .fs-stat-val.de { color:#DC241F; }
    .fs-hint { font-size:.72rem; color:#888; font-weight:400; display:block; margin-top:2px; }
    .fs-st-pill { font-size:.68rem; padding:3px 9px; border-radius:12px; font-weight:600; white-space:nowrap; }
    .fs-st-brouillon { background:#fff3cd; color:#856404; }
    .fs-st-transmis { background:#cce5ff; color:#004085; }
    .fs-st-valide { background:#d4edda; color:#155724; }
    .fs-actions .mod-card .mc-ico.deces { background:rgba(220,36,31,0.12); color:#DC241F; }
    .fs-actions .mod-card .mc-ico.compte { background:rgba(90,155,201,0.14); color:#5a9bc9; }
    .fs-btn-deces-tout {
        background: linear-gradient(135deg, #e05550, #c41e1a);
        color: #fff !important;
        border: none;
    }
    .fs-btn-deces-tout:hover { color: #fff !important; filter: brightness(1.06); }
    .fs-recap-deces .recap-tbl thead th { border-bottom-color: #DC241F; }
    .fs-col-module { display: flex; flex-direction: column; gap: 1rem; }
    .fs-col-module .fait-card,
    .fs-col-module .sec-card { margin-bottom: 0; }
    .hy-routing-alert { border-left: 4px solid #5a9bc9; background: #f0f7fc; }
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
                    <i class="fa fa-hand-holding-medical me-2" style="opacity:.9;"></i>
                    {{ $libInstitution }}
                </div>
                <div class="hd-sub mt-2">
                    <span class="hd-badge"><i class="fa fa-notes-medical me-1"></i>{{ $roleHygieneLibelle ?? "Centre d'hygiène" }}</span>
                    <span class="hd-badge"><i class="fa fa-file-signature me-1"></i>Constatation de décès</span>
                    @if($routingDecesParcoursBrazzaville)
                        <span class="hd-badge"><i class="fa fa-dove me-1"></i>Parcours pompes funèbres (zone Brazzaville)</span>
                    @else
                        <span class="hd-badge"><i class="fa fa-landmark me-1"></i>Parcours centre d’état civil (hors Brazzaville)</span>
                    @endif
                    @if($cec)
                        <span class="hd-badge"><i class="fa fa-landmark me-1"></i>Rattachement : {{ $cec->lib_institution }}</span>
                    @endif
                </div>
                <div class="hd-sub" style="margin-top:6px;">
                    <span style="opacity:.85;"><i class="fa fa-user me-1"></i>{{ $nomAgent }}</span>
                    <span style="opacity:.65;">· {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</span>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    @if(auth()->user()->canVoirCarteOuStatistiquesDashboard())
                    <a href="{{ route('dashboard.carteducongo') }}" class="btn-hd"><i class="fa fa-map"></i> Carte</a>
                    <a href="{{ route('dashboard.statgenredep') }}" class="btn-hd"><i class="fa fa-chart-bar"></i> Statistiques</a>
                    @endif
                    <a href="{{ route('notifications.index') }}" class="btn-hd"><i class="fa fa-bell"></i> Notifications</a>
                </div>
            </div>
        </div>
    </div>

    <div class="sec-card fs-actions mb-3">
        <div class="sc-head">
            <h5 class="sc-title mb-0"><i class="fa fa-bolt" style="color:#7e8fa3;"></i> Actions rapides</h5>
        </div>
        <div class="sc-body">
            <div class="row g-3">
                @can('module.acteDeces.CCDeces.create')
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('centreHygiene.create') }}" class="mod-card h-100">
                        <span class="mc-ico deces"><i class="fa fa-plus-circle"></i></span>
                        <span class="mc-lbl">Nouvelle constatation</span>
                    </a>
                </div>
                @endcan
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('centreHygiene.index') }}" class="mod-card h-100">
                        <span class="mc-ico deces"><i class="fa fa-list"></i></span>
                        <span class="mc-lbl">Liste des constatations</span>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('utilisateur.profile', $user->code_user) }}" class="mod-card h-100">
                        <span class="mc-ico compte"><i class="fa fa-id-card"></i></span>
                        <span class="mc-lbl">Mon profil</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 align-items-start">
        <div class="col-xl-7 col-lg-7">
            <div class="fs-col-module">
                <div class="fait-card fait-deces">
                    <div class="fait-head">
                        <div class="fait-title">
                            <div class="fait-icon"><i class="fa fa-cross"></i></div>
                            Certificats de constatation de décès
                        </div>
                    </div>
                    <div class="fait-body p-0">
                        <p class="px-3 pt-3 mb-2 small text-muted" style="line-height:1.45;">
                            Périmètre : type « <strong>CERTIFICAT DE CONSTATATION DE DECES</strong> » saisi par votre centre.
                            Les certificats / déclarations de décès issus du médecin à l’hôpital restent sur le tableau de bord <strong>formation sanitaire</strong>.
                        </p>
                        <table class="fs-stat-tbl mb-0">
                            <thead>
                                <tr>
                                    <th>Indicateur</th>
                                    <th class="text-end">Valeur</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fs-stat-lbl">Constatations enregistrées <span class="fs-hint">À cette structure</span></td>
                                    <td class="fs-stat-val de">{{ $kpi_constatation['constatations_saisies'] }}</td>
                                </tr>
                                <tr>
                                    <td class="fs-stat-lbl">Transmis <span class="fs-hint">Au moins un mouvement « Envoyée »</span></td>
                                    <td class="fs-stat-val de">{{ $kpi_constatation['envoyes'] }}</td>
                                </tr>
                                <tr>
                                    <td class="fs-stat-lbl">Validés par le CEC</td>
                                    <td class="fs-stat-val de">{{ $kpi_constatation['valides'] }}</td>
                                </tr>
                                <tr>
                                    <td class="fs-stat-lbl">En attente côté CEC</td>
                                    <td class="fs-stat-val de">{{ $kpi_constatation['en_attente'] }}</td>
                                </tr>
                                <tr>
                                    <td class="fs-stat-lbl">Actes d’état civil produits</td>
                                    <td class="fs-stat-val de">{{ $kpi_constatation['actes_produits'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-5 col-lg-5">
            <div class="sec-card fs-recap-deces h-100">
                <div class="sc-head">
                    <h5 class="sc-title mb-0"><i class="fa fa-clock" style="color:#DC241F;"></i> Dernières constatations</h5>
                    <a href="{{ route('centreHygiene.index') }}" class="btn btn-sm fw-semibold px-3 fs-btn-deces-tout">Voir tout</a>
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
                            @forelse($recent_constatations as $dd)
                                @php
                                    $st = $dd->cec_approuver === 'OUI'
                                        ? ['Validé CEC', 'fs-st-valide']
                                        : ($dd->mouvements->where('statut', 'Envoyée')->isNotEmpty()
                                            ? ['Transmis', 'fs-st-transmis']
                                            : ['À transmettre', 'fs-st-brouillon']);
                                @endphp
                                <tr>
                                    <td>
                                        <a href="{{ route('centreHygiene.show', $dd->code_declaration_deces) }}">{{ \Illuminate\Support\Str::limit($dd->code_declaration_deces, 14) }}</a>
                                    </td>
                                    <td>{{ $dd->date_heure_declaration ? \Carbon\Carbon::parse($dd->date_heure_declaration)->format('d/m/Y H:i') : '—' }}</td>
                                    <td>{{ $dd->defunt ? $dd->defunt->nomcomplet() : '—' }}</td>
                                    <td><span class="fs-st-pill {{ $st[1] }}">{{ $st[0] }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">Aucune constatation pour cette structure.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
