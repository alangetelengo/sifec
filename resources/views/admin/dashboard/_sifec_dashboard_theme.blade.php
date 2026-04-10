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

    /* Décès — rouge clair + motifs (même esprit que le bloc naissance) */
    .fait-deces .fait-head {
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(circle at 88% 12%, rgba(255,255,255,0.55) 0%, transparent 42%),
            radial-gradient(circle at 10% 85%, rgba(255,255,255,0.38) 0%, transparent 40%),
            radial-gradient(circle at 50% 40%, rgba(220,36,31,0.12) 0%, transparent 50%),
            linear-gradient(135deg, #fcecea 0%, #f5cac7 42%, #eeb4b0 100%);
        border-bottom: 1px solid rgba(220,36,31,0.12);
    }
    .fait-deces .fait-head .fait-title { color: #6b1815; }
    .fait-deces .fait-head .fait-icon {
        background: rgba(255,255,255,0.55);
        color: #b8221e;
    }
    .fait-deces { border-left: 4px solid #e59894; }

    /* Métriques */
    .fait-metrics { display:flex; gap:10px; flex-wrap: wrap; }
    .fait-metric {
        flex:1; min-width: 88px; background:#f8f9fa; border-radius:10px; padding:12px 10px; text-align:center;
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
