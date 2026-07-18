{{-- Styles partagés — référentiel CRUD (professions, religions, causes de décès, etc.) --}}
<style>
.sifec-ref-crud-page { --sl-green:#006B31; --sl-mid:#009E49; --sl-light:#21B931; --sl-gold:#FBDE4A; }
.sifec-ref-crud-page .sl-hero {
    background: linear-gradient(135deg, var(--sl-green) 0%, var(--sl-mid) 52%, var(--sl-light) 100%);
    border-radius: 16px;
    padding: 1.5rem 1.75rem;
    color: #fff;
    box-shadow: 0 14px 40px rgba(0, 107, 49, 0.28);
    position: relative;
    overflow: hidden;
}
.sifec-ref-crud-page .sl-hero::after {
    content: '';
    position: absolute;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: rgba(251, 222, 74, 0.12);
    top: -80px; right: -60px;
    pointer-events: none;
}
.sifec-ref-crud-page .sl-hero h1 { font-size: 1.35rem; font-weight: 700; letter-spacing: 0.02em; margin: 0 0 .35rem; }
.sifec-ref-crud-page .sl-hero p { margin: 0; opacity: .92; font-size: .9rem; max-width: 42rem; }
.sifec-ref-crud-page .sl-hero .btn-light {
    background: rgba(255,255,255,.95);
    border: none;
    color: var(--sl-green);
    font-weight: 600;
    border-radius: 10px;
    padding: .5rem 1rem;
}
.sifec-ref-crud-page .sl-hero .btn-light:hover { background: #fff; color: var(--sl-mid); }
.sifec-ref-crud-page .sl-stat {
    border: none;
    border-radius: 14px;
    box-shadow: 0 4px 22px rgba(0,0,0,.06);
    transition: transform .2s ease, box-shadow .2s ease;
    height: 100%;
}
.sifec-ref-crud-page .sl-stat:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(0,0,0,.08); }
.sifec-ref-crud-page .sl-stat .card-body { padding: 1rem 1.15rem; }
.sifec-ref-crud-page .sl-stat-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
}
.sifec-ref-crud-page .sl-stat-val { font-size: 1.5rem; font-weight: 700; color: #1a1a1a; line-height: 1.1; }
.sifec-ref-crud-page .sl-stat-lbl { font-size: .75rem; text-transform: uppercase; letter-spacing: .06em; color: #6c757d; font-weight: 600; }
.sifec-ref-crud-page .sl-card {
    border: none;
    border-radius: 14px;
    box-shadow: 0 4px 24px rgba(0,0,0,.055);
    overflow: hidden;
}
.sifec-ref-crud-page .sl-card .card-header {
    background: linear-gradient(90deg, rgba(0,107,49,.06) 0%, rgba(33,185,49,.04) 100%);
    border-bottom: 1px solid rgba(0,107,49,.1);
    padding: 1rem 1.25rem;
}
.sifec-ref-crud-page .sl-card .card-header h5 { margin: 0; font-size: 1rem; font-weight: 700; color: var(--sl-green); }
.sifec-ref-crud-page .sl-filter-label { font-size: .72rem; text-transform: uppercase; letter-spacing: .05em; font-weight: 700; color: #6c757d; margin-bottom: .35rem; }
.sifec-ref-crud-page .sl-table-wrap { border-radius: 12px; border: 1px solid rgba(0,0,0,.06); overflow: hidden; }
.sifec-ref-crud-page .sl-table thead th {
    background: linear-gradient(180deg, #f8faf9 0%, #eef5f1 100%);
    color: var(--sl-green);
    font-size: .72rem;
    text-transform: uppercase;
    letter-spacing: .05em;
    font-weight: 700;
    border-bottom: 2px solid rgba(0,158,73,.2);
    padding: .85rem .75rem;
    white-space: nowrap;
}
.sifec-ref-crud-page .sl-table tbody td { padding: .75rem .75rem; vertical-align: middle; border-color: rgba(0,0,0,.05); }
.sifec-ref-crud-page .sl-table tbody tr:hover { background: rgba(0,158,73,.04); }
.sifec-ref-crud-page .sl-num {
    font-size: .8rem; font-weight: 700; color: var(--sl-green);
    background: rgba(0,107,49,.08); padding: .15rem .5rem; border-radius: 8px;
}
.sifec-ref-crud-page .sl-result-pill {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    padding: .35rem .75rem;
    border-radius: 999px;
    background: rgba(0,158,73,.1);
    color: var(--sl-green);
    font-size: .8rem;
    font-weight: 600;
}
.sifec-ref-crud-page .sl-modal-header {
    background: linear-gradient(135deg, var(--sl-green) 0%, var(--sl-mid) 100%);
    border: none;
}
.sifec-ref-crud-page .sl-modal-header .modal-title { font-weight: 700; font-size: 1.05rem; }
.sifec-ref-crud-page .sl-row-num { width: 3rem; text-align: center; }
.sifec-ref-crud-page .sl-table-host { position: relative; min-height: 120px; }
.sifec-ref-crud-page .sl-table-loading-overlay {
    display: flex; align-items: center; justify-content: center; gap: 0.65rem;
    position: absolute; inset: 0; z-index: 6;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(2px);
    font-weight: 600; font-size: 0.95rem; color: var(--sl-green);
    border-radius: 12px;
}
.sifec-ref-crud-page .sl-table-loading-overlay .sifec-spinner { width: 1.25rem; height: 1.25rem; border-width: 3px; }
.sifec-ref-crud-page .sl-empty-icon {
    width: 72px; height: 72px; margin: 0 auto;
    border-radius: 50%;
    background: rgba(0,158,73,.1);
    color: var(--sl-mid);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.75rem;
}
.sifec-ref-crud-page .sl-actions { white-space: nowrap; }
.sifec-ref-crud-page .sl-actions-group { display: inline-flex; align-items: center; gap: .4rem; }
.sifec-ref-crud-page .sl-btn-action {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 2.25rem; height: 2.1rem; padding: 0 .55rem;
    border-radius: 10px; font-size: .85rem; font-weight: 600;
    border: 1.5px solid transparent;
    transition: background .15s ease, border-color .15s ease, color .15s ease, box-shadow .15s ease;
    background: #fff;
    box-shadow: 0 1px 2px rgba(0,0,0,.04);
}
.sifec-ref-crud-page .sl-btn-action:focus { outline: none; box-shadow: 0 0 0 3px rgba(0, 158, 73, .2); }
.sifec-ref-crud-page .sl-btn-action-edit {
    color: var(--sl-green); border-color: rgba(0, 107, 49, .35);
}
.sifec-ref-crud-page .sl-btn-action-edit:hover {
    background: rgba(0, 158, 73, .1); border-color: var(--sl-mid); color: var(--sl-green);
}
.sifec-ref-crud-page .sl-btn-action-label {
    gap: .4rem;
    min-width: auto;
    height: auto;
    padding: .4rem .75rem;
    font-size: .8rem;
    line-height: 1.2;
}
.sifec-ref-crud-page .sl-btn-action-label i { font-size: .85rem; }
.sifec-ref-crud-page .sl-btn-action-delete {
    color: #b02a37; border-color: rgba(176, 42, 55, .3);
}
.sifec-ref-crud-page .sl-btn-action-delete:hover {
    background: rgba(176, 42, 55, .08); border-color: #b02a37; color: #9e1f2b;
}
.swal2-popup.sl-swal-referentiel {
    border-radius: 14px !important;
    padding: 1.5rem 1.35rem 1.35rem !important;
    box-shadow: 0 14px 40px rgba(0, 0, 0, .12) !important;
    border: none !important;
}
.swal2-popup.sl-swal-referentiel .swal2-title {
    color: #006B31 !important;
    font-size: 1.1rem !important;
    font-weight: 700 !important;
    padding: 0 0 .5rem !important;
}
.swal2-popup.sl-swal-referentiel .swal2-html-container { color: #495057 !important; font-size: .95rem !important; }
.swal2-popup.sl-swal-referentiel .swal2-actions { gap: .5rem !important; margin-top: 1.15rem !important; }
</style>
