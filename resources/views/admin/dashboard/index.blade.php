@extends('layout.app')
@section('titre')
    Tableau de Bord
@endsection

@section('styles')
<style>
    /*
     * Charte de couleurs SIFEC — drapeau République du Congo
     *   Vert primaire  : #009E49  (sidebar active: #21B931)
     *   Jaune secondaire: #FBDE4A
     *   Rouge danger   : #DC241F
     *   Bleu info      : #2781d5
     *   Fond body      : #f1f1f1
     */

    /* ===== RESET & BASE ===== */
    .db-wrap { padding: 0 2px; }

    /* ===== HEADER — vert primaire ===== */
    .db-header {
        background: linear-gradient(135deg, #006B31 0%, #009E49 55%, #21B931 100%);
        border-radius: 16px;
        padding: 22px 28px;
        margin-bottom: 22px;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 28px rgba(0,158,73,0.32);
    }
    .db-header::before, .db-header::after {
        content:''; position:absolute; border-radius:50%; pointer-events:none;
    }
    .db-header::before { width:220px;height:220px; background:rgba(255,255,255,0.06); top:-70px; right:-50px; }
    .db-header::after  { width:140px;height:140px; background:rgba(251,222,74,0.08); bottom:-50px; right:160px; }
    .db-header .hd-title { font-size:1.35rem; font-weight:700; letter-spacing:.3px; }
    .db-header .hd-sub   { font-size:.82rem; opacity:.85; margin-top:4px; display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
    .db-header .hd-badge {
        background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.3);
        border-radius:20px; padding:2px 12px; font-size:.75rem; font-weight:500;
    }
    .db-header .btn-hd {
        background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.28);
        color:#fff; border-radius:7px; padding:5px 12px; font-size:.75rem; font-weight:500;
        text-decoration:none; transition:all .18s; display:inline-flex; align-items:center; gap:5px;
    }
    .db-header .btn-hd:hover { background:rgba(251,222,74,0.25); color:#fff; transform:translateY(-1px); }

    /* ===== CARDS FAITS D'ÉTAT CIVIL ===== */
    .fait-card {
        border:none; border-radius:16px; overflow:hidden;
        box-shadow:0 3px 18px rgba(0,0,0,0.09);
        margin-bottom:20px; transition:transform .22s, box-shadow .22s;
    }
    .fait-card:hover { transform:translateY(-3px); box-shadow:0 12px 35px rgba(0,0,0,0.13); }

    .fait-card .fait-head {
        padding:16px 20px 14px;
        display:flex; align-items:center; justify-content:space-between;
    }
    .fait-card .fait-head .fait-title {
        font-size:1rem; font-weight:700; color:#fff; display:flex; align-items:center; gap:8px;
    }
    .fait-card .fait-head .fait-icon {
        width:40px;height:40px; background:rgba(255,255,255,0.22);
        border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.15rem; color:#fff;
    }
    .fait-card .fait-body { background:#fff; padding:16px 20px 18px; }

    /* Naissance — VERT primaire */
    .fait-naissance .fait-head { background:linear-gradient(135deg, #006B31, #21B931); }
    .fait-naissance { border-left: 4px solid #009E49; }

    /* Mariage — JAUNE secondaire */
    .fait-mariage .fait-head   { background:linear-gradient(135deg, #c8960a, #FBDE4A); }
    .fait-mariage .fait-head .fait-title { color:#4a3200; }
    .fait-mariage .fait-head .fait-icon  { background:rgba(0,0,0,0.12); color:#4a3200; }
    .fait-mariage .fait-head span        { color:#4a3200 !important; }
    .fait-mariage { border-left: 4px solid #FBDE4A; }

    /* Décès — ROUGE danger */
    .fait-deces .fait-head     { background:linear-gradient(135deg, #9b1915, #DC241F); }
    .fait-deces { border-left: 4px solid #DC241F; }

    /* Métriques */
    .fait-metrics { display:flex; gap:10px; }
    .fait-metric {
        flex:1; background:#f8f9fa; border-radius:10px; padding:12px 10px; text-align:center;
        border:1px solid #e9ecef; position:relative; overflow:hidden;
    }
    .fait-metric .fm-val {
        font-size:1.7rem; font-weight:800; line-height:1;
        margin-bottom:3px;
    }
    .fait-metric .fm-lbl { font-size:.7rem; font-weight:600; text-transform:uppercase; letter-spacing:.4px; color:#6e6e6e; }
    .fait-metric .fm-sub { font-size:.68rem; color:#aaa; margin-top:3px; }

    /* Couleurs des valeurs — charte SIFEC */
    .fait-naissance .fait-metric .fm-decl { color:#009E49; }
    .fait-naissance .fait-metric .fm-acte  { color:#2781d5; }
    .fait-mariage   .fait-metric .fm-decl  { color:#c8960a; }
    .fait-mariage   .fait-metric .fm-acte  { color:#2781d5; }
    .fait-deces     .fait-metric .fm-decl  { color:#DC241F; }
    .fait-deces     .fait-metric .fm-acte  { color:#2781d5; }

    /* ===== SECTION CARD GÉNÉRIQUE ===== */
    .sec-card {
        background:#fff; border:none; border-radius:14px;
        box-shadow:0 2px 14px rgba(0,0,0,0.07); margin-bottom:20px; overflow:hidden;
    }
    .sec-card .sc-head {
        padding:13px 20px; border-bottom:2px solid #f1f1f1;
        display:flex; align-items:center; justify-content:space-between;
    }
    .sec-card .sc-title {
        font-size:.9rem; font-weight:700; color:#333;
        display:flex; align-items:center; gap:7px; margin:0;
    }
    .sec-card .sc-body { padding:18px 20px; }

    /* ===== LÉGENDE CHART ===== */
    .chart-leg { display:flex; gap:14px; flex-wrap:wrap; }
    .chart-leg span { display:flex; align-items:center; gap:5px; font-size:.75rem; color:#6e6e6e; }
    .chart-leg .dot { width:9px;height:9px;border-radius:50%; display:inline-block; }

    /* ===== TABLEAU RÉCAP ===== */
    .recap-tbl { width:100%; border-collapse:separate; border-spacing:0; }
    .recap-tbl thead th {
        font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px;
        color:#6e6e6e; background:#f4f4f4; padding:9px 12px;
        border-bottom:2px solid #009E49;
    }
    .recap-tbl tbody tr:hover { background:#f4f4f4; }
    .recap-tbl tbody td {
        padding:10px 12px; font-size:.82rem; border-bottom:1px solid #f1f1f1;
        color:#444; vertical-align:middle;
    }
    .recap-tbl tfoot td {
        padding:10px 12px; font-size:.85rem; font-weight:700;
        background:#f4f4f4; border-top:2px solid #009E49; color:#222;
    }
    .day-pill {
        background:#e9ecef; color:#444; border-radius:5px;
        padding:2px 9px; font-weight:700; font-size:.72rem;
    }
    .n-cell { font-weight:700; text-align:center; }
    .n-nais { color:#009E49; }
    .n-mar  { color:#c8960a; }
    .n-dec  { color:#DC241F; }
    .n-an   { color:#2781d5; }
    .n-am   { color:#2781d5; }
    .n-ad   { color:#2781d5; }

    /* ===== MODULES RAPIDES ===== */
    .mod-card {
        background:#fff; border:1px solid #e9ecef; border-radius:11px; padding:14px 10px;
        text-align:center; text-decoration:none; color:#444;
        transition:all .18s; display:flex; flex-direction:column; align-items:center; gap:6px;
    }
    .mod-card:hover {
        border-color:#009E49; background:rgba(0,158,73,0.06);
        transform:translateY(-2px); box-shadow:0 5px 18px rgba(0,158,73,0.15);
        text-decoration:none;
    }
    .mod-card .mc-ico { width:40px;height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; }
    .mod-card .mc-lbl { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.4px; color:#444 !important; display:block; }
    .mod-card:hover .mc-lbl { color:#009E49 !important; }

    /* Scroll mobile */
    .tbl-scroll { overflow-x:auto; -webkit-overflow-scrolling:touch; }
</style>
@endsection

@section('corps')
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
                <i class="fa fa-landmark me-2" style="opacity:.85;"></i>
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
                    @can('module.menus.naissance')
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
                        <a href="{{ route('institution.index') }}" class="mod-card">
                            <div class="mc-ico" style="background:rgba(39,129,213,0.1);color:#2781d5;"><i class="fa fa-database"></i></div>
                            <span class="mc-lbl">Référentiel</span>
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
