<style>
    .page-fonctionnalite-form-sifec {
        --sff-ink: #1a2e26;
        --sff-muted: #5c6d66;
        --sff-green: #0f5132;
        --sff-green-mid: #1b6f4a;
        --sff-green-soft: #e8f0eb;
        --sff-line: #e2e8e4;
        --sff-paper: #ffffff;
        --sff-shadow-lg: 0 12px 40px rgba(26, 46, 38, 0.08);
        --sff-radius: 14px;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        margin: -0.35rem -0.5rem 0;
        padding: 0.5rem 0.5rem 2rem;
        min-height: calc(100vh - 100px);
        background: linear-gradient(180deg, #fafaf8 0%, #eef1ee 100%);
    }

    .page-fonctionnalite-form-sifec .sff-card {
        border: 1px solid var(--sff-line);
        border-radius: var(--sff-radius);
        box-shadow: var(--sff-shadow-lg);
        overflow: hidden;
        background: var(--sff-paper);
    }

    .page-fonctionnalite-form-sifec .sff-card .card-header {
        background: linear-gradient(135deg, var(--sff-green-soft) 0%, #f4f7f5 100%);
        border-bottom: 1px solid var(--sff-line);
        padding: 1.1rem 1.35rem;
    }

    .page-fonctionnalite-form-sifec .sff-card .card-header h4 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--sff-ink);
        letter-spacing: -0.02em;
    }

    .page-fonctionnalite-form-sifec .sff-card .card-body {
        padding: 1.35rem 1.35rem 1.5rem;
    }

    .page-fonctionnalite-form-sifec .form-label {
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--sff-ink);
    }

    .page-fonctionnalite-form-sifec .form-control,
    .page-fonctionnalite-form-sifec select.form-control {
        border-radius: 10px;
        border-color: #cfd8d3;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .page-fonctionnalite-form-sifec .form-control:focus,
    .page-fonctionnalite-form-sifec select.form-control:focus {
        border-color: var(--sff-green-mid);
        box-shadow: 0 0 0 3px rgba(27, 111, 74, 0.22);
    }

    .page-fonctionnalite-form-sifec .sff-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.65rem;
        align-items: center;
        margin-top: 0.5rem;
        padding-top: 1rem;
        border-top: 1px solid var(--sff-line);
    }

    .page-fonctionnalite-form-sifec .sff-btn-back {
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

    .page-fonctionnalite-form-sifec .sff-btn-back:hover {
        background: #991b1b;
        border-color: #7f1d1d;
        color: #fff !important;
    }

    .page-fonctionnalite-form-sifec .sff-btn-back:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(185, 28, 28, 0.4);
    }

    .page-fonctionnalite-form-sifec .sff-btn-submit {
        border-radius: 10px;
        font-weight: 600;
        padding: 0.45rem 1.35rem;
        background: var(--sff-green-mid);
        border-color: var(--sff-green-mid);
        color: #fff;
        box-shadow: 0 2px 8px rgba(27, 111, 74, 0.25);
    }

    .page-fonctionnalite-form-sifec .sff-btn-submit:hover {
        background: var(--sff-green);
        border-color: var(--sff-green);
        color: #fff;
    }

    .page-fonctionnalite-form-sifec .sff-btn-submit.sifec-btn-loading {
        pointer-events: none;
        opacity: 0.92;
    }
</style>
