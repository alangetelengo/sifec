<style>
    /*
     * Charte dashboard — tons légers (verts adoucis, mariage en ardoise, sans jaune)
     *   Vert doux   : #5a9d78 / #8fc4a8
     *   Mariage     : ardoise / indigo léger (#7e8fa3)
     *   Rouge décès : #DC241F (atténué dans .fait-deces)
     *   Bleu actes  : #5a9bc9
     */

    /* ===== RESET & BASE ===== */
    .db-wrap { padding: 0 2px; }

    /* ===== HEADER — vert adouci ===== */
    .db-header {
        background: linear-gradient(135deg, #4a7c62 0%, #6a9e7e 48%, #8ab89a 100%);
        border-radius: 16px;
        padding: 22px 28px;
        margin-bottom: 22px;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(74, 124, 98, 0.22);
    }
    .db-header::before, .db-header::after {
        content:''; position:absolute; border-radius:50%; pointer-events:none;
    }
    .db-header::before { width:220px;height:220px; background:rgba(255,255,255,0.08); top:-70px; right:-50px; }
    .db-header::after  { width:140px;height:140px; background:rgba(255,255,255,0.05); bottom:-50px; right:160px; }
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
    .db-header .btn-hd:hover { background:rgba(255,255,255,0.28); color:#fff; transform:translateY(-1px); }

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

    /* Naissance — vert très léger (en-tête type “carte pastel”) */
    .fait-naissance .fait-head {
        background: linear-gradient(135deg, #ecf6f0 0%, #d8ebe1 45%, #c5dfd0 100%);
        border-bottom: 1px solid rgba(74, 124, 98, 0.12);
    }
    .fait-naissance .fait-head .fait-title { color: #2d5a40; }
    .fait-naissance .fait-head .fait-icon {
        background: rgba(255, 255, 255, 0.75);
        color: #3d7a56;
    }
    .fait-naissance .fait-head > span[style] {
        background: rgba(45, 90, 64, 0.1) !important;
        color: #2d5a40 !important;
    }
    .fait-naissance { border-left: 4px solid #9bc4ad; }

    /* Mariage — ardoise / bleu-gris (pas de jaune) */
    .fait-mariage .fait-head {
        background: linear-gradient(135deg, #eef2f7 0%, #e0e7ef 45%, #d2dce8 100%);
        border-bottom: 1px solid rgba(100, 119, 140, 0.15);
    }
    .fait-mariage .fait-head .fait-title { color: #3d4f5f; }
    .fait-mariage .fait-head .fait-icon {
        background: rgba(255, 255, 255, 0.75);
        color: #5a6d82;
    }
    .fait-mariage .fait-head > span[style] {
        background: rgba(61, 79, 95, 0.1) !important;
        color: #3d4f5f !important;
    }
    .fait-mariage { border-left: 4px solid #a8b8c9; }

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
    .fait-deces .fait-head > span[style] {
        background: rgba(107, 24, 21, 0.1) !important;
        color: #6b1815 !important;
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

    /* Couleurs des valeurs — tons atténués */
    .fait-naissance .fait-metric .fm-decl { color:#4a8f68; }
    .fait-naissance .fait-metric .fm-acte  { color:#5a9bc9; }
    .fait-mariage   .fait-metric .fm-decl  { color:#5c6d82; }
    .fait-mariage   .fait-metric .fm-acte  { color:#5a9bc9; }
    .fait-deces     .fait-metric .fm-decl  { color:#c44742; }
    .fait-deces     .fait-metric .fm-acte  { color:#5a9bc9; }

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
        border-bottom:2px solid #8bc4a8;
    }
    .recap-tbl tbody tr:hover { background:#f4f4f4; }
    .recap-tbl tbody td {
        padding:10px 12px; font-size:.82rem; border-bottom:1px solid #f1f1f1;
        color:#444; vertical-align:middle;
    }
    .recap-tbl tfoot td {
        padding:10px 12px; font-size:.85rem; font-weight:700;
        background:#f4f4f4; border-top:2px solid #8bc4a8; color:#222;
    }
    .day-pill {
        background:#e9ecef; color:#444; border-radius:5px;
        padding:2px 9px; font-weight:700; font-size:.72rem;
    }
    .n-cell { font-weight:700; text-align:center; }
    .n-nais { color:#4a8f68; }
    .n-mar  { color:#5c6d82; }
    .n-dec  { color:#c44742; }
    .n-an   { color:#5a9bc9; }
    .n-am   { color:#5a9bc9; }
    .n-ad   { color:#5a9bc9; }

    /* ===== MODULES RAPIDES ===== */
    .mod-card {
        background:#fff; border:1px solid #e9ecef; border-radius:11px; padding:14px 10px;
        text-align:center; text-decoration:none; color:#444;
        transition:all .18s; display:flex; flex-direction:column; align-items:center; gap:6px;
    }
    .mod-card:hover {
        border-color:#7aab8f; background:rgba(122, 171, 143, 0.08);
        transform:translateY(-2px); box-shadow:0 5px 18px rgba(74, 124, 98, 0.12);
        text-decoration:none;
    }
    .mod-card .mc-ico { width:40px;height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; }
    .mod-card .mc-lbl { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.4px; color:#444 !important; display:block; }
    .mod-card:hover .mc-lbl { color:#4a7c62 !important; }

    /* Scroll mobile */
    .tbl-scroll { overflow-x:auto; -webkit-overflow-scrolling:touch; }
</style>
