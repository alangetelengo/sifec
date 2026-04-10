@extends('layout.app')
@section('titre')
    Tableau de Bord
@endsection

@section('styles')
@include('admin.dashboard._sifec_dashboard_theme')
@endsection

@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
@php
    /* ── Totaux ── */
    $totDeclN = $pr[0]->TOTAL  + $dx[0]->TOTAL  + $tr[0]->TOTAL  + $qt[0]->TOTAL  + $cq[0]->TOTAL  + $sx[0]->TOTAL  + $sp[0]->TOTAL;
    $totDeclD = $prd[0]->TOTAL + $dxd[0]->TOTAL + $trd[0]->TOTAL + $qtd[0]->TOTAL + $cqd[0]->TOTAL + $sxd[0]->TOTAL + $spd[0]->TOTAL;
    $totDeclM = $prm[0]->TOTAL + $dxm[0]->TOTAL + $trm[0]->TOTAL + $qtm[0]->TOTAL + $cqm[0]->TOTAL + $sxm[0]->TOTAL + $spm[0]->TOTAL;
    $totActeN = $pra[0]->TOTAL + $dxa[0]->TOTAL + $tra[0]->TOTAL + $qta[0]->TOTAL + $cqa[0]->TOTAL + $sxa[0]->TOTAL + $spa[0]->TOTAL;
    $totActeD = $prb[0]->TOTAL + $dxb[0]->TOTAL + $trb[0]->TOTAL + $qtb[0]->TOTAL + $cqb[0]->TOTAL + $sxb[0]->TOTAL + $spb[0]->TOTAL;
    $totActeM = $prma[0]->TOTAL+ $dxma[0]->TOTAL+ $trma[0]->TOTAL+ $qtma[0]->TOTAL+ $cqma[0]->TOTAL+ $sxma[0]->TOTAL+ $spma[0]->TOTAL;

    /* ── Séries journalières ── */
    $serieDN = [$pr[0]->TOTAL,  $dx[0]->TOTAL,  $tr[0]->TOTAL,  $qt[0]->TOTAL,  $cq[0]->TOTAL,  $sx[0]->TOTAL,  $sp[0]->TOTAL];
    $serieDD = [$prd[0]->TOTAL, $dxd[0]->TOTAL, $trd[0]->TOTAL, $qtd[0]->TOTAL, $cqd[0]->TOTAL, $sxd[0]->TOTAL, $spd[0]->TOTAL];
    $serieDM = [$prm[0]->TOTAL, $dxm[0]->TOTAL, $trm[0]->TOTAL, $qtm[0]->TOTAL, $cqm[0]->TOTAL, $sxm[0]->TOTAL, $spm[0]->TOTAL];
    $serieAN = [$pra[0]->TOTAL, $dxa[0]->TOTAL, $tra[0]->TOTAL, $qta[0]->TOTAL, $cqa[0]->TOTAL, $sxa[0]->TOTAL, $spa[0]->TOTAL];
    $serieAD = [$prb[0]->TOTAL, $dxb[0]->TOTAL, $trb[0]->TOTAL, $qtb[0]->TOTAL, $cqb[0]->TOTAL, $sxb[0]->TOTAL, $spb[0]->TOTAL];
    $serieAM = [$prma[0]->TOTAL,$dxma[0]->TOTAL,$trma[0]->TOTAL,$qtma[0]->TOTAL,$cqma[0]->TOTAL,$sxma[0]->TOTAL,$spma[0]->TOTAL];

    $joursCourt = ['Lun','Mar','Mer','Jeu','Ven','Sam','Dim'];
    $jours      = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'];

    /* ── Dates ── */
    try {
        $dateLun = \Carbon\Carbon::parse($lun)->locale('fr')->isoFormat('D MMMM');
        $dateDim = \Carbon\Carbon::parse($dim)->locale('fr')->isoFormat('D MMMM YYYY');
    } catch(\Exception $e) {
        $dateLun = date('d/m', strtotime($lun));
        $dateDim = date('d/m/Y', strtotime($dim));
    }

    /*
     * ── Visibilité des sections par type d'institution ──────────────────
     *
     *  TCINS_0001 → CEC / Mairie       : Naissance (décl+actes) + Mariage (décl+actes)
     *  TCINS_0002 → Tribunal           : Vue globale (tout)
     *  TCINS_0003 → Formation sanitaire: Naissance (décl) + Décès (décl) — pas d'actes
     *  TCINS_0004 → Ambassade          : Naissance + Décès
     *  TPINS_0003 → Pompes Funèbres    : Décès (décl+actes) uniquement
     *  null (admin)                    : Vue globale (tout)
     */
    /*
     * $isPF doit être vérifié EN PREMIER car dans la BD,
     * TPINS_0003 (Pompes Funèbres) a code_type_categorie_ins = TCINS_0001
     * (même catégorie que le CEC), ce qui fausse les autres flags.
     */
    $isPF        = $codeTypeInstitution === 'TPINS_0003';
    $isCEC       = !$isPF && ($codeTypeCategorie === 'TCINS_0001');
    $isTribunal  = $codeTypeCategorie === 'TCINS_0002';
    $isHopital   = $codeTypeCategorie === 'TCINS_0003';
    $isAmbassade = $codeTypeCategorie === 'TCINS_0004';

    /* Pompes Funèbres → uniquement Décès (déclarations + actes) */
    $showNaissance      = !$isPF && ($vueGlobale || $isCEC || $isHopital || $isAmbassade);
    $showMariage        = !$isPF && ($vueGlobale || $isCEC);
    $showDeces          = $vueGlobale || $isHopital || $isAmbassade || $isPF;
    $showActeNaissance  = !$isPF && ($vueGlobale || $isCEC);
    $showActeMariage    = !$isPF && ($vueGlobale || $isCEC);
    $showActeDeces      = $vueGlobale || $isPF;

    /* ── Libellé du contexte dans le header ── */
    $contextLabel = match(true) {
        $isPF        => 'Pompes Funèbres',
        $isCEC       => 'Centre d\'État Civil',
        $isTribunal  => 'Tribunal — Vue globale',
        $isHopital   => 'Formation Sanitaire',
        $isAmbassade => 'Ambassade',
        default      => 'Vue globale',
    };
    @endphp

<div class="db-wrap">

{{-- ══════════════════════════════════════════════
     HEADER
══════════════════════════════════════════════ --}}
<div class="db-header">
    <div class="row align-items-center">
        <div class="col-md-7 mb-3 mb-md-0">
            <div class="hd-title">
                <i class="fa {{ $isHopital ? 'fa-hospital' : 'fa-landmark' }} me-2" style="opacity:.85;"></i>
                {{ $libInstitution }}
            </div>
            <div class="hd-sub mt-2">
                <span class="hd-badge">
                    <i class="fa fa-building me-1"></i>{{ $contextLabel }}
                </span>
                <span class="hd-badge">
                    <i class="fa fa-calendar-week me-1"></i>Semaine du {{ $dateLun }} au {{ $dateDim }}
                </span>
            </div>
            <div class="hd-sub" style="margin-top:4px;">
                <span style="opacity:.65;">{{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</span>
            </div>
        </div>
        <div class="col-md-5">
            <div class="d-flex gap-2 flex-wrap justify-content-md-end mt-2 mt-md-0">
                <a href="{{ route('dashboard.carteducongo') }}" class="btn-hd"><i class="fa fa-map"></i> Carte</a>
                <a href="{{ route('dashboard.statgenredep') }}" class="btn-hd"><i class="fa fa-chart-bar"></i> Statistiques</a>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     CARTES — FAITS D'ÉTAT CIVIL (filtrés par institution)
══════════════════════════════════════════════ --}}
@php
    /* Calcul du nb de colonnes Bootstrap selon les sections visibles */
    $nbFaits = (int)$showNaissance + (int)$showMariage + (int)$showDeces;
    $colClass = $nbFaits === 1 ? 'col-xl-6 col-md-8 mx-auto' : ($nbFaits === 2 ? 'col-xl-6' : 'col-xl-4');
@endphp
<div class="row g-3 mb-3 justify-content-center">

    {{-- NAISSANCE --}}
    @if($showNaissance)
    <div class="{{ $colClass }} col-md-12">
        <div class="fait-card fait-naissance h-100">
            <div class="fait-head">
                <div class="fait-title">
                    <div class="fait-icon"><i class="fa fa-baby"></i></div>
                    Naissance
                </div>
                <span style="background:rgba(255,255,255,0.2);border-radius:20px;padding:2px 12px;font-size:.72rem;color:#fff;font-weight:600;">
                    {{ $totDeclN + $totActeN }} opérations
                        </span>
            </div>
            <div class="fait-body">
                <div class="fait-metrics">
                    <div class="fait-metric">
                        <div class="fm-val fm-decl">{{ $totDeclN }}</div>
                        <div class="fm-lbl">Déclarations</div>
                        <div class="fm-sub">enregistrées</div>
                    </div>
                    @if($showActeNaissance)
                    <div class="fait-metric">
                        <div class="fm-val fm-acte">{{ $totActeN }}</div>
                        <div class="fm-lbl">Actes</div>
                        <div class="fm-sub">produits</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- MARIAGE --}}
    @if($showMariage)
    <div class="{{ $colClass }} col-md-12">
        <div class="fait-card fait-mariage h-100">
            <div class="fait-head">
                <div class="fait-title">
                    <div class="fait-icon"><i class="fa fa-ring"></i></div>
                    Mariage
                </div>
                <span style="background:rgba(255,255,255,0.2);border-radius:20px;padding:2px 12px;font-size:.72rem;color:#fff;font-weight:600;">
                    {{ $totDeclM + $totActeM }} opérations
                </span>
            </div>
            <div class="fait-body">
                <div class="fait-metrics">
                    <div class="fait-metric">
                        <div class="fm-val fm-decl">{{ $totDeclM }}</div>
                        <div class="fm-lbl">Déclarations</div>
                        <div class="fm-sub">enregistrées</div>
                    </div>
                    @if($showActeMariage)
                    <div class="fait-metric">
                        <div class="fm-val fm-acte">{{ $totActeM }}</div>
                        <div class="fm-lbl">Actes</div>
                        <div class="fm-sub">produits</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- DÉCÈS --}}
    @if($showDeces)
    <div class="{{ $colClass }} col-md-12">
        <div class="fait-card fait-deces h-100">
            <div class="fait-head">
                <div class="fait-title">
                    <div class="fait-icon"><i class="fa fa-cross"></i></div>
                    Décès
                </div>
                <span style="background:rgba(255,255,255,0.2);border-radius:20px;padding:2px 12px;font-size:.72rem;color:#fff;font-weight:600;">
                    {{ $totDeclD + $totActeD }} opérations
                </span>
            </div>
            <div class="fait-body">
                <div class="fait-metrics">
                    <div class="fait-metric">
                        <div class="fm-val fm-decl">{{ $totDeclD }}</div>
                        <div class="fm-lbl">Déclarations</div>
                        <div class="fm-sub">enregistrées</div>
                    </div>
                    @if($showActeDeces)
                    <div class="fait-metric">
                        <div class="fm-val fm-acte">{{ $totActeD }}</div>
                        <div class="fm-lbl">Actes</div>
                        <div class="fm-sub">produits</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

{{-- ══════════════════════════════════════════════
     GRAPHIQUES
══════════════════════════════════════════════ --}}
<div class="row g-3 mb-3">

    {{-- Barres groupées : Déclarations --}}
    <div class="{{ ($showActeNaissance || $showActeDeces || $showActeMariage) ? 'col-xl-8' : 'col-12' }}">
        <div class="sec-card h-100">
            <div class="sc-head">
                <h5 class="sc-title">
                    <i class="fa fa-chart-bar" style="color:#009E49;"></i>
                    Déclarations — évolution hebdomadaire
                </h5>
                <div class="chart-leg">
                    @if($showNaissance)<span><span class="dot" style="background:#009E49;"></span>Naissance</span>@endif
                    @if($showMariage)<span><span class="dot" style="background:#FBDE4A;"></span>Mariage</span>@endif
                    @if($showDeces)<span><span class="dot" style="background:#DC241F;"></span>Décès</span>@endif
                </div>
            </div>
            <div class="sc-body">
                <canvas id="chartDeclarations" height="200"></canvas>
            </div>
        </div>
    </div>

    {{-- Donut : répartition — uniquement si au moins 2 faits visibles --}}
    @if($showActeNaissance || $showActeDeces || $showActeMariage)
    <div class="col-xl-4">
        <div class="sec-card h-100">
            <div class="sc-head">
                <h5 class="sc-title">
                    <i class="fa fa-chart-pie" style="color:#DC241F;"></i>
                    Bilan : Décl. → Actes
                </h5>
            </div>
            <div class="sc-body d-flex justify-content-center align-items-center" style="min-height:220px;">
                <div style="max-width:280px;width:100%;">
                    <canvas id="chartBilan"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

{{-- Graphique Actes (uniquement si l'institution produit des actes) --}}
@if($showActeNaissance || $showActeDeces || $showActeMariage)
<div class="row g-3 mb-3">
    <div class="col-12">
        <div class="sec-card">
            <div class="sc-head">
                <h5 class="sc-title">
                    <i class="fa fa-chart-line" style="color:#2781d5;"></i>
                    Actes produits — évolution sur la semaine
                </h5>
                <div class="chart-leg">
                    @if($showActeNaissance)<span><span class="dot" style="background:#2781d5;"></span>Actes Naissance</span>@endif
                    @if($showActeMariage)<span><span class="dot" style="background:#2781d5;"></span>Actes Mariage</span>@endif
                    @if($showActeDeces)<span><span class="dot" style="background:#2781d5;"></span>Actes Décès</span>@endif
                </div>
            </div>
            <div class="sc-body">
                <canvas id="chartActes" height="110"></canvas>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════════
     TABLEAU RÉCAPITULATIF
══════════════════════════════════════════════ --}}
<div class="row g-3 mb-3">
    <div class="col-12">
        <div class="sec-card">
            <div class="sc-head">
                <h5 class="sc-title">
                    <i class="fa fa-table" style="color:#c8960a;"></i>
                    Récapitulatif jour par jour — Semaine du {{ $dateLun }} au {{ $dateDim }}
                </h5>
            </div>
            <div class="tbl-scroll">
                <table class="recap-tbl">
                    <thead>
                        <tr>
                            <th>Jour</th>
                            @if($showNaissance)
                            <th class="text-center" style="color:#009E49;"><i class="fa fa-baby me-1"></i>Décl. Naissance</th>
                            @endif
                            @if($showActeNaissance)
                            <th class="text-center" style="color:#2781d5;"><i class="fa fa-file-alt me-1"></i>Actes Naissance</th>
                            @endif
                            @if($showMariage)
                            <th class="text-center" style="color:#c8960a;"><i class="fa fa-ring me-1"></i>Décl. Mariage</th>
                            @endif
                            @if($showActeMariage)
                            <th class="text-center" style="color:#2781d5;"><i class="fa fa-file-alt me-1"></i>Actes Mariage</th>
                            @endif
                            @if($showDeces)
                            <th class="text-center" style="color:#DC241F;"><i class="fa fa-cross me-1"></i>Décl. Décès</th>
                            @endif
                            @if($showActeDeces)
                            <th class="text-center" style="color:#2781d5;"><i class="fa fa-file-alt me-1"></i>Actes Décès</th>
                            @endif
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jours as $i => $jour)
                        @php
                            $dn = $showNaissance     ? $serieDN[$i] : 0;
                            $an = $showActeNaissance ? $serieAN[$i] : 0;
                            $dm = $showMariage       ? $serieDM[$i] : 0;
                            $am = $showActeMariage   ? $serieAM[$i] : 0;
                            $dd = $showDeces         ? $serieDD[$i] : 0;
                            $ad = $showActeDeces     ? $serieAD[$i] : 0;
                            $tot = $dn + $an + $dm + $am + $dd + $ad;
                            $maxTot = max(max(array_map(function($j) use($serieDN,$serieAN,$serieDM,$serieAM,$serieDD,$serieAD,$showNaissance,$showActeNaissance,$showMariage,$showActeMariage,$showDeces,$showActeDeces){
                                return ($showNaissance?$serieDN[$j]:0) + ($showActeNaissance?$serieAN[$j]:0)
                                     + ($showMariage?$serieDM[$j]:0) + ($showActeMariage?$serieAM[$j]:0)
                                     + ($showDeces?$serieDD[$j]:0) + ($showActeDeces?$serieAD[$j]:0);
                            }, range(0,6))), 1);
                            $pct = $maxTot > 0 ? round($tot / $maxTot * 100) : 0;
                        @endphp
                        <tr>
                            <td>
                                <span class="day-pill">{{ $joursCourt[$i] }}</span>
                                <span class="ms-2 text-muted" style="font-size:.72rem;">
                                    {{ \Carbon\Carbon::parse($lun)->addDays($i)->format('d/m') }}
                                </span>
                            </td>
                            @if($showNaissance)<td class="n-cell n-nais">{{ $dn }}</td>@endif
                            @if($showActeNaissance)<td class="n-cell n-an">{{ $an }}</td>@endif
                            @if($showMariage)<td class="n-cell n-mar">{{ $dm }}</td>@endif
                            @if($showActeMariage)<td class="n-cell n-am">{{ $am }}</td>@endif
                            @if($showDeces)<td class="n-cell n-dec">{{ $dd }}</td>@endif
                            @if($showActeDeces)<td class="n-cell n-ad">{{ $ad }}</td>@endif
                            <td class="text-center">
                                <span class="fw-bold">{{ $tot }}</span>
                                @if($tot > 0)
                                <div class="progress mt-1" style="height:3px;">
                                    <div class="progress-bar" style="width:{{ $pct }}%;background:linear-gradient(90deg,#009E49,#FBDE4A,#DC241F);"></div>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><span class="day-pill" style="background:#1e293b;color:#fff;">TOTAL</span></td>
                            @if($showNaissance)<td class="n-cell n-nais text-center">{{ $totDeclN }}</td>@endif
                            @if($showActeNaissance)<td class="n-cell n-an text-center">{{ $totActeN }}</td>@endif
                            @if($showMariage)<td class="n-cell n-mar text-center">{{ $totDeclM }}</td>@endif
                            @if($showActeMariage)<td class="n-cell n-am text-center">{{ $totActeM }}</td>@endif
                            @if($showDeces)<td class="n-cell n-dec text-center">{{ $totDeclD }}</td>@endif
                            @if($showActeDeces)<td class="n-cell n-ad text-center">{{ $totActeD }}</td>@endif
                            @php $grandTotal = ($showNaissance?$totDeclN:0)+($showActeNaissance?$totActeN:0)+($showMariage?$totDeclM:0)+($showActeMariage?$totActeM:0)+($showDeces?$totDeclD:0)+($showActeDeces?$totActeD:0); @endphp
                            <td class="text-center" style="font-size:1rem;color:#006B31;font-weight:800;">{{ $grandTotal }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
                    </div>
                </div>
            </div>

{{-- ══════════════════════════════════════════════
     ACCÈS RAPIDE AUX MODULES
══════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="sec-card">
            <div class="sc-head">
                <h5 class="sc-title">
                    <i class="fa fa-th" style="color:#009E49;"></i>
                    Accès rapide
                </h5>
            </div>
            <div class="sc-body">
                <div class="row g-2">
                    @can('module.menus.naissance')
                    <div class="col-xl-2 col-md-3 col-4">
                        <a href="{{ route('declarationNaissance.index') }}" class="mod-card">
                            <div class="mc-ico" style="background:rgba(0,158,73,0.12);color:#009E49;"><i class="fa fa-baby"></i></div>
                            <span class="mc-lbl">Naissance</span>
                        </a>
                    </div>
                    <div class="col-xl-2 col-md-3 col-4">
                        <a href="{{ route('acteNaissance.index') }}" class="mod-card">
                            <div class="mc-ico" style="background:rgba(39,129,213,0.12);color:#2781d5;"><i class="fa fa-file-medical"></i></div>
                            <span class="mc-lbl">Actes Naissance</span>
                        </a>
                    </div>
                    @endcan
                    @can('module.menus.mariage')
                    <div class="col-xl-2 col-md-3 col-4">
                        <a href="{{ route('declarationMariage.index') }}" class="mod-card">
                            <div class="mc-ico" style="background:rgba(251,222,74,0.25);color:#a07800;"><i class="fa fa-ring"></i></div>
                            <span class="mc-lbl">Mariage</span>
                        </a>
                    </div>
                    @endcan
                    @can('module.menus.deces')
                    <div class="col-xl-2 col-md-3 col-4">
                        <a href="{{ route('declarationDeces.index') }}" class="mod-card">
                            <div class="mc-ico" style="background:rgba(220,36,31,0.1);color:#DC241F;"><i class="fa fa-cross"></i></div>
                            <span class="mc-lbl">Décès</span>
                        </a>
                    </div>
                    @endcan
                    @can('module.menus.tribunal')
                    <div class="col-xl-2 col-md-3 col-4">
                        <a href="{{ route('tribunal.document.index') }}" class="mod-card">
                            <div class="mc-ico" style="background:rgba(251,222,74,0.2);color:#856404;"><i class="fa fa-gavel"></i></div>
                            <span class="mc-lbl">Tribunal</span>
                        </a>
                    </div>
                    @endcan
                    @can('module.menus.referentiel')
                    <div class="col-xl-2 col-md-3 col-4">
                        <a href="{{ route('localite.index') }}" class="mod-card">
                            <div class="mc-ico" style="background:rgba(33,185,49,0.12);color:#006B31;"><i class="fa fa-map-marked-alt"></i></div>
                            <span class="mc-lbl">Localités</span>
                        </a>
                    </div>
                    <div class="col-xl-2 col-md-3 col-4">
                        <a href="{{ route('typelocalite.index') }}" class="mod-card">
                            <div class="mc-ico" style="background:rgba(0,158,73,0.12);color:#009E49;"><i class="fa fa-layer-group"></i></div>
                            <span class="mc-lbl">Types localité</span>
                        </a>
                    </div>
                    <div class="col-xl-2 col-md-3 col-4">
                        <a href="{{ route('institution.index') }}" class="mod-card">
                            <div class="mc-ico" style="background:rgba(39,129,213,0.1);color:#2781d5;"><i class="fa fa-university"></i></div>
                            <span class="mc-lbl">Institutions</span>
                        </a>
                    </div>
                    @endcan
                    @can('module.menus.administration')
                    <div class="col-xl-2 col-md-3 col-4">
                        <a href="{{ route('utilisateur.index') }}" class="mod-card">
                            <div class="mc-ico" style="background:rgba(0,107,49,0.1);color:#006B31;"><i class="fa fa-users-cog"></i></div>
                            <span class="mc-lbl">Administration</span>
                        </a>
                    </div>
                    @endcan
                    <div class="col-xl-2 col-md-3 col-4">
                        <a href="{{ route('dashboard.carteducongo') }}" class="mod-card">
                            <div class="mc-ico" style="background:rgba(33,185,49,0.1);color:#21B931;"><i class="fa fa-map-marked-alt"></i></div>
                            <span class="mc-lbl">Carte</span>
                        </a>
                    </div>
                    <div class="col-xl-2 col-md-3 col-4">
                        <a href="{{ route('dashboard.statgenredep') }}" class="mod-card">
                            <div class="mc-ico" style="background:rgba(220,36,31,0.08);color:#DC241F;"><i class="fa fa-chart-pie"></i></div>
                            <span class="mc-lbl">Statistiques</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>{{-- /db-wrap --}}
</div>
</div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function(){
    /* ── Données ── */
    var jours = @json($joursCourt);
    var DN    = @json($serieDN);
    var DD    = @json($serieDD);
    var DM    = @json($serieDM);
    var AN    = @json($serieAN);
    var AD    = @json($serieAD);
    var AM    = @json($serieAM);
    var totDN = {{ $totDeclN }}, totDD = {{ $totDeclD }}, totDM = {{ $totDeclM }};
    var totAN = {{ $totActeN }}, totAD = {{ $totActeD }}, totAM = {{ $totActeM }};

    Chart.defaults.font.family = "'Segoe UI',system-ui,-apple-system,sans-serif";
    Chart.defaults.font.size   = 11;
    Chart.defaults.color       = '#6e6e6e';

    var tooltip = {
        backgroundColor:'#006B31', titleColor:'#fff',
        bodyColor:'#d1fae5', padding:11, cornerRadius:8,
        callbacks:{ label: function(c){ return '  '+c.dataset.label+' : '+c.parsed.y; } }
    };

    /* ── Flags de visibilité depuis PHP ── */
    var showN  = {{ $showNaissance     ? 'true' : 'false' }};
    var showM  = {{ $showMariage       ? 'true' : 'false' }};
    var showD  = {{ $showDeces         ? 'true' : 'false' }};
    var showAN = {{ $showActeNaissance ? 'true' : 'false' }};
    var showAM = {{ $showActeMariage   ? 'true' : 'false' }};
    var showAD = {{ $showActeDeces     ? 'true' : 'false' }};

    var scaleOpts = {
        x:{ grid:{display:false}, ticks:{font:{weight:'600'}} },
        y:{ beginAtZero:true, grid:{color:'rgba(0,0,0,.05)'}, ticks:{precision:0} }
    };

    /* ══════════════════════════════════════════
       1. Barres groupées — Déclarations filtrées
    ═════════════════════════════════════════ */
    var elDecl = document.getElementById('chartDeclarations');
    if(elDecl){
        var declDatasets = [];
        if(showN) declDatasets.push({ label:'Décl. Naissance', data:DN, backgroundColor:'rgba(0,158,73,.72)',   borderColor:'#009E49', borderWidth:1.5, borderRadius:6, borderSkipped:false });
        if(showM) declDatasets.push({ label:'Décl. Mariage',   data:DM, backgroundColor:'rgba(251,222,74,.80)', borderColor:'#c8960a', borderWidth:1.5, borderRadius:6, borderSkipped:false });
        if(showD) declDatasets.push({ label:'Décl. Décès',     data:DD, backgroundColor:'rgba(220,36,31,.70)',  borderColor:'#DC241F', borderWidth:1.5, borderRadius:6, borderSkipped:false });
        new Chart(elDecl,{
            type:'bar',
            data:{ labels:jours, datasets:declDatasets },
            options:{ responsive:true, maintainAspectRatio:true,
                plugins:{ legend:{display:false}, tooltip:tooltip },
                scales: scaleOpts,
                animation:{ duration:900, easing:'easeOutQuart' }
                }
            });
        }

    /* ══════════════════════════════════════════
       2. Bilan horizontal — Décl vs Actes (filtré)
    ═════════════════════════════════════════ */
    var elBilan = document.getElementById('chartBilan');
    if(elBilan){
        var bilanLabels = [], bilanDecl = [], bilanActes = [];
        var bilanBgDecl = [], bilanBdDecl = [], bilanBgActe = [], bilanBdActe = [];
        if(showN){ bilanLabels.push('Naissance'); bilanDecl.push(totDN); bilanActes.push(showAN?totAN:0); bilanBgDecl.push('rgba(0,158,73,.72)');   bilanBdDecl.push('#009E49'); bilanBgActe.push('rgba(39,129,213,.6)');  bilanBdActe.push('#2781d5'); }
        if(showM){ bilanLabels.push('Mariage');   bilanDecl.push(totDM); bilanActes.push(showAM?totAM:0); bilanBgDecl.push('rgba(251,222,74,.80)'); bilanBdDecl.push('#c8960a'); bilanBgActe.push('rgba(39,129,213,.45)'); bilanBdActe.push('#2781d5'); }
        if(showD){ bilanLabels.push('Décès');     bilanDecl.push(totDD); bilanActes.push(showAD?totAD:0); bilanBgDecl.push('rgba(220,36,31,.70)');  bilanBdDecl.push('#DC241F'); bilanBgActe.push('rgba(39,129,213,.35)'); bilanBdActe.push('#2781d5'); }
        var bilanDS = [{ label:'Déclarations', data:bilanDecl, backgroundColor:bilanBgDecl, borderColor:bilanBdDecl, borderWidth:1.5, borderRadius:5 }];
        if(showAN||showAM||showAD) bilanDS.push({ label:'Actes produits', data:bilanActes, backgroundColor:bilanBgActe, borderColor:bilanBdActe, borderWidth:1.5, borderRadius:5 });
        new Chart(elBilan,{
            type:'bar',
            data:{ labels:bilanLabels, datasets:bilanDS },
            options:{ indexAxis:'y', responsive:true,
                plugins:{ legend:{ position:'bottom', labels:{font:{size:11},padding:10,usePointStyle:true,pointStyle:'circle'} },
                    tooltip:{ backgroundColor:'#006B31', titleColor:'#fff', bodyColor:'#d1fae5', padding:10, cornerRadius:8,
                        callbacks:{ label:function(c){ return '  '+c.dataset.label+' : '+c.parsed.x; } } }
                },
                scales:{ x:{beginAtZero:true,grid:{color:'rgba(0,0,0,.05)'},ticks:{precision:0}}, y:{grid:{display:false},ticks:{font:{weight:'700'}}} },
                animation:{ duration:900, easing:'easeOutQuart' }
                }
            });
        }

    /* ══════════════════════════════════════════
       3. Lignes — Actes produits (filtré)
    ═════════════════════════════════════════ */
    var elActes = document.getElementById('chartActes');
    if(elActes){
        var actesDS = [];
        if(showAN) actesDS.push({ label:'Actes Naissance', data:AN, borderColor:'#009E49', backgroundColor:'rgba(0,158,73,.07)',   fill:true, tension:.4, pointBackgroundColor:'#009E49', pointBorderColor:'#fff', pointBorderWidth:2, pointRadius:5, pointHoverRadius:7, borderWidth:2.5 });
        if(showAM) actesDS.push({ label:'Actes Mariage',   data:AM, borderColor:'#c8960a', backgroundColor:'rgba(251,222,74,.10)', fill:true, tension:.4, pointBackgroundColor:'#c8960a', pointBorderColor:'#fff', pointBorderWidth:2, pointRadius:5, pointHoverRadius:7, borderWidth:2.5, borderDash:[5,3] });
        if(showAD) actesDS.push({ label:'Actes Décès',     data:AD, borderColor:'#DC241F', backgroundColor:'rgba(220,36,31,.06)',  fill:true, tension:.4, pointBackgroundColor:'#DC241F', pointBorderColor:'#fff', pointBorderWidth:2, pointRadius:5, pointHoverRadius:7, borderWidth:2.5 });
        new Chart(elActes,{
            type:'line',
            data:{ labels:jours, datasets:actesDS },
            options:{ responsive:true, maintainAspectRatio:true,
                plugins:{ legend:{display:false},
                    tooltip:{ backgroundColor:'#1e293b', titleColor:'#f1f5f9', bodyColor:'#cbd5e1', padding:11, cornerRadius:8, mode:'index', intersect:false }
                },
                scales: scaleOpts,
                animation:{ duration:1100, easing:'easeOutQuart' },
                interaction:{ mode:'nearest', axis:'x', intersect:false }
            }
        });
    }

})();
</script>
@endsection
