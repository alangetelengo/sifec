<style>
    .page-menu-item-form-sifec {
        --smi-ink: #1a2e26;
        --smi-muted: #5c6d66;
        --smi-green: #0f5132;
        --smi-green-mid: #1b6f4a;
        --smi-green-soft: #e8f0eb;
        --smi-line: #e2e8e4;
        --smi-paper: #ffffff;
        --smi-shadow-lg: 0 12px 40px rgba(26, 46, 38, 0.08);
        --smi-radius: 14px;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        margin: -0.35rem -0.5rem 0;
        padding: 0.5rem 0.5rem 2rem;
        min-height: calc(100vh - 100px);
        background: linear-gradient(180deg, #fafaf8 0%, #eef1ee 100%);
    }

    .page-menu-item-form-sifec .smi-breadcrumb {
        font-size: 0.875rem;
        margin-bottom: 1rem;
        background: var(--smi-paper);
        border: 1px solid var(--smi-line);
        border-radius: 10px;
        padding: 0.65rem 1.15rem;
        box-shadow: 0 1px 3px rgba(26, 46, 38, 0.06);
    }

    .page-menu-item-form-sifec .smi-breadcrumb .breadcrumb { margin-bottom: 0; }
    .page-menu-item-form-sifec .smi-breadcrumb .breadcrumb-item a {
        color: var(--smi-green-mid) !important;
        font-weight: 600;
        text-decoration: none;
    }

    .page-menu-item-form-sifec .sff-card {
        border: 1px solid var(--smi-line);
        border-radius: var(--smi-radius);
        box-shadow: var(--smi-shadow-lg);
        overflow: hidden;
        background: var(--smi-paper);
    }

    .page-menu-item-form-sifec .sff-card .card-header {
        background: linear-gradient(135deg, var(--smi-green-soft) 0%, #f4f7f5 100%);
        border-bottom: 1px solid var(--smi-line);
        padding: 1.1rem 1.35rem;
    }

    .page-menu-item-form-sifec .sff-card .card-header h4 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--smi-ink);
        letter-spacing: -0.02em;
    }

    .page-menu-item-form-sifec .sff-card .card-header .smi-header-meta {
        margin: 0.35rem 0 0;
        font-size: 0.875rem;
        color: var(--smi-muted);
    }

    .page-menu-item-form-sifec .sff-card .card-body {
        padding: 1.35rem 1.35rem 1.5rem;
    }

    .page-menu-item-form-sifec .form-label {
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--smi-ink);
    }

    .page-menu-item-form-sifec .form-control,
    .page-menu-item-form-sifec select.form-control {
        border-radius: 10px;
        border-color: #cfd8d3;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .page-menu-item-form-sifec .form-control:focus,
    .page-menu-item-form-sifec select.form-control:focus {
        border-color: var(--smi-green-mid);
        box-shadow: 0 0 0 3px rgba(27, 111, 74, 0.22);
    }

    .page-menu-item-form-sifec .sff-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.65rem;
        align-items: center;
        margin-top: 0.5rem;
        padding-top: 1rem;
        border-top: 1px solid var(--smi-line);
    }

    .page-menu-item-form-sifec .sff-btn-back {
        border-radius: 10px;
        font-weight: 600;
        padding: 0.45rem 1.15rem;
        border: 1px solid #991b1b;
        background: #b91c1c;
        color: #fff !important;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 2px 8px rgba(185, 28, 28, 0.3);
        transition: background 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .page-menu-item-form-sifec .sff-btn-back:hover {
        background: #991b1b;
        border-color: #7f1d1d;
        color: #fff !important;
    }

    .page-menu-item-form-sifec .sff-btn-back:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(185, 28, 28, 0.4);
    }

    .page-menu-item-form-sifec .sff-btn-submit {
        border-radius: 10px;
        font-weight: 600;
        padding: 0.45rem 1.35rem;
        background: var(--smi-green-mid);
        border-color: var(--smi-green-mid);
        color: #fff;
        box-shadow: 0 2px 8px rgba(27, 111, 74, 0.25);
    }

    .page-menu-item-form-sifec .sff-btn-submit:hover {
        background: var(--smi-green);
        border-color: var(--smi-green);
        color: #fff;
    }

    .page-menu-item-form-sifec .sff-btn-submit.sifec-btn-loading {
        pointer-events: none;
        opacity: 0.92;
    }

    .page-menu-item-form-sifec .smi-panel {
        background: #fff;
        border: 1px solid var(--smi-line);
        border-radius: 12px;
        padding: 1.1rem 1.2rem 1.15rem;
        box-shadow: 0 1px 3px rgba(26, 46, 38, 0.05);
    }

    .page-menu-item-form-sifec .smi-panel-title {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--smi-muted);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }
</style>
