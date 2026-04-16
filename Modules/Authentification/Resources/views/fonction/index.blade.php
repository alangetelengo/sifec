@extends('layout.app')
@section('titre')
  Fonctions
@endsection
@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style>
    .modal-fonction-sifec {
        --mf-ink: #1a2e26;
        --mf-ink-muted: #5c6d66;
        --mf-green: #0f5132;
        --mf-green-mid: #1b6f4a;
        --mf-green-soft: #e8f0eb;
        --mf-line: #e2e8e4;
        --mf-paper: #ffffff;
        --mf-shadow: 0 18px 48px rgba(26, 46, 38, 0.12);
        --mf-radius: 14px;
    }

    .modal-fonction-sifec .modal-dialog {
        max-width: 420px;
    }

    .modal-fonction-sifec .modal-content {
        border: none;
        border-radius: var(--mf-radius);
        box-shadow: var(--mf-shadow);
        overflow: hidden;
    }

    .modal-fonction-sifec .modal-header {
        background: linear-gradient(135deg, var(--mf-green-soft) 0%, #f0f4f1 100%);
        border-bottom: 1px solid var(--mf-line);
        padding: 1rem 1.25rem;
    }

    .modal-fonction-sifec .modal-title {
        font-weight: 600;
        font-size: 1.05rem;
        color: var(--mf-ink);
        letter-spacing: -0.02em;
    }

    .modal-fonction-sifec .modal-header .btn-close {
        opacity: 0.55;
        transition: opacity 0.15s ease;
    }

    .modal-fonction-sifec .modal-header .btn-close:hover {
        opacity: 1;
    }

    .modal-fonction-sifec .modal-body {
        padding: 1.35rem 1.35rem 1.15rem;
        background: var(--mf-paper);
    }

    .modal-fonction-sifec .modal-body .form-label {
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--mf-ink);
        margin-bottom: 0.4rem;
    }

    .modal-fonction-sifec .modal-body .form-control {
        border-radius: 10px;
        border-color: #cfd8d3;
        padding: 0.55rem 0.85rem;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .modal-fonction-sifec .modal-body .form-control:focus {
        border-color: var(--mf-green-mid);
        box-shadow: 0 0 0 3px rgba(27, 111, 74, 0.22);
    }

    .modal-fonction-sifec .modal-body .form-control::placeholder {
        color: #94a3a8;
    }

    .modal-fonction-sifec .modal-footer {
        border-top: 1px solid var(--mf-line);
        padding: 1rem 1.35rem;
        gap: 0.5rem;
        background: #fafcfb;
    }

    .modal-fonction-sifec .modal-footer .btn {
        border-radius: 10px;
        font-weight: 600;
        padding: 0.45rem 1.15rem;
        min-height: 2.35rem;
    }

    .modal-fonction-sifec .modal-footer .btn-secondary {
        border-color: #c5d0c8;
        color: var(--mf-ink-muted);
        background: #fff;
    }

    .modal-fonction-sifec .modal-footer .btn-secondary:hover {
        background: #f3f6f4;
        border-color: #b8c4bc;
        color: var(--mf-ink);
    }

    .modal-fonction-sifec .modal-footer .btn-primary {
        background: var(--mf-green-mid);
        border-color: var(--mf-green-mid);
        color: #fff;
        min-width: 7rem;
    }

    .modal-fonction-sifec .modal-footer .btn-primary:hover {
        background: var(--mf-green);
        border-color: var(--mf-green);
        color: #fff;
    }

    .modal-fonction-sifec .modal-footer .btn-warning {
        min-width: 7rem;
        font-weight: 600;
        border-radius: 10px;
    }

    .modal-fonction-sifec .modal-footer .btn-primary.sifec-btn-loading,
    .modal-fonction-sifec .modal-footer .btn-warning.sifec-btn-loading {
        pointer-events: none;
        opacity: 0.92;
    }

    @include('authentification::partials.sifec-swal-delete-styles')

    /* ── Page liste fonctions ───────────────────────────────────────────── */
    .page-fonctions-sifec {
        --pf-ink: #1a2e26;
        --pf-muted: #5c6d66;
        --pf-green: #0f5132;
        --pf-green-mid: #1b6f4a;
        --pf-green-soft: #e8f0eb;
        --pf-line: #e2e8e4;
        --pf-paper: #ffffff;
        --pf-shadow: 0 1px 3px rgba(26, 46, 38, 0.06);
        --pf-shadow-lg: 0 12px 40px rgba(26, 46, 38, 0.08);
        --pf-radius: 14px;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        margin: -0.35rem -0.5rem 0;
        padding: 0.5rem 0.5rem 2rem;
        min-height: calc(100vh - 100px);
        background: linear-gradient(180deg, #fafaf8 0%, #eef1ee 100%);
    }

    .page-fonctions-sifec .pf-card {
        border: 1px solid var(--pf-line);
        border-radius: var(--pf-radius);
        box-shadow: var(--pf-shadow-lg);
        overflow: hidden;
        background: var(--pf-paper);
    }

    .page-fonctions-sifec .pf-card .card-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem 1rem;
        background: linear-gradient(135deg, var(--pf-green-soft) 0%, #f4f7f5 100%);
        border-bottom: 1px solid var(--pf-line);
        padding: 1.1rem 1.35rem;
    }

    .page-fonctions-sifec .pf-card .card-header h4 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--pf-ink);
        letter-spacing: -0.02em;
    }

    .page-fonctions-sifec .pf-header-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.65rem;
    }

    .page-fonctions-sifec .pf-count {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--pf-green-mid);
        background: rgba(27, 111, 74, 0.1);
        border: 1px solid rgba(27, 111, 74, 0.2);
        padding: 0.28rem 0.65rem;
        border-radius: 999px;
    }

    .page-fonctions-sifec .pf-btn-add {
        background: var(--pf-green-mid);
        border-color: var(--pf-green-mid);
        color: #fff;
        font-weight: 600;
        border-radius: 10px;
        padding: 0.45rem 1rem;
        box-shadow: 0 2px 8px rgba(27, 111, 74, 0.25);
    }

    .page-fonctions-sifec .pf-btn-add:hover {
        background: var(--pf-green);
        border-color: var(--pf-green);
        color: #fff;
    }

    .page-fonctions-sifec .pf-card .card-body {
        padding: 1.15rem 1.25rem 1.35rem;
    }

    .page-fonctions-sifec .table-responsive {
        border-radius: 10px;
        border: 1px solid var(--pf-line);
        overflow: auto;
        background: #fafcfb;
    }

    .page-fonctions-sifec table.dataTable {
        margin: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0;
    }

    .page-fonctions-sifec table.dataTable thead th {
        background: #f0f4f1;
        color: var(--pf-ink);
        font-weight: 600;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 2px solid #d5ded8 !important;
        padding: 0.75rem 0.85rem !important;
        white-space: nowrap;
    }

    .page-fonctions-sifec table.dataTable tbody td {
        padding: 0.7rem 0.85rem !important;
        vertical-align: middle;
        border-color: #eef1ee !important;
        font-size: 0.92rem;
        color: #2d3d35;
    }

    .page-fonctions-sifec table.dataTable tbody tr:hover td {
        background: rgba(232, 240, 235, 0.45) !important;
    }

    .page-fonctions-sifec .pf-col-name {
        font-weight: 600;
        color: var(--pf-ink);
    }

    .page-fonctions-sifec .pf-perms {
        max-width: 28rem;
        font-size: 0.85rem;
        color: var(--pf-muted);
        line-height: 1.45;
    }

    .page-fonctions-sifec .pf-actions {
        white-space: nowrap;
    }

    .page-fonctions-sifec .pf-actions .btn {
        border-radius: 8px;
        width: 2rem;
        height: 2rem;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.25rem;
        box-shadow: 0 1px 4px rgba(26, 46, 38, 0.08);
    }

    .page-fonctions-sifec .pf-actions .btn:last-child {
        margin-right: 0;
    }

    .page-fonctions-sifec .pf-actions .btn-primary {
        background: var(--pf-green-mid);
        border-color: var(--pf-green-mid);
    }

    .page-fonctions-sifec .pf-actions .btn-primary:hover {
        background: var(--pf-green);
        border-color: var(--pf-green);
    }

    .page-fonctions-sifec .pf-actions .btn-info {
        background: #0d6e8c;
        border-color: #0d6e8c;
    }

    .page-fonctions-sifec .pf-actions .btn-info:hover {
        background: #0a5a73;
        border-color: #0a5a73;
        color: #fff;
    }

    .page-fonctions-sifec #example_wrapper .dataTables_length,
    .page-fonctions-sifec #example_wrapper .dataTables_filter {
        margin-bottom: 0.85rem;
    }

    .page-fonctions-sifec #example_wrapper .dataTables_filter input {
        border-radius: 8px;
        border: 1px solid #cfd8d3;
        padding: 0.35rem 0.65rem;
    }

    .page-fonctions-sifec #example_wrapper .dataTables_filter input:focus {
        border-color: var(--pf-green-mid);
        box-shadow: 0 0 0 2px rgba(27, 111, 74, 0.2);
        outline: none;
    }

    .page-fonctions-sifec #example_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--pf-green-mid) !important;
        border-color: var(--pf-green-mid) !important;
        color: #fff !important;
    }
</style>
@endsection
@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
    <div class="page-fonctions-sifec">
    <div class="row">
        <div class="col-xl-12">
            <div class="card pf-card">
                <div class="card-header">
                    <h4>Liste des fonctions</h4>
                    <div class="pf-header-meta">
                        <span class="pf-count">{{ $fonctions->count() }} fonction(s)</span>
                        <button type="button" class="btn btn-sm pf-btn-add" data-bs-toggle="modal" data-bs-target="#modalAjoutFonction">
                            <i class="fas fa-plus me-1" aria-hidden="true"></i>Ajouter
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="display" style="min-width: 845px">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Nom</th>
                                    <th>Permissions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fonctions as $i => $fonction)
                                @php
                                    $permsList = $fonction->fonctionnalites->pluck('lib_fonctionnalite')->unique()->implode(', ');
                                @endphp
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="pf-col-name">{{ $fonction->lib_fonction }}</td>
                                    <td>
                                        <div class="pf-perms text-truncate" title="{{ e($permsList) }}">{{ $permsList }}</div>
                                    </td>
                                    <td class="pf-actions">
                                        <button type="button"
                                            class="btn btn-primary shadow btn-xs sharp"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEdit{{ $fonction->code_fonction }}"
                                            title="Modifier">
                                            <i class="fas fa-pencil-alt" aria-hidden="true"></i>
                                        </button>

                                        <form style="display:inline-block"
                                            action="{{ route('fonction.destroy', $fonction->code_fonction) }}"
                                            method="POST"
                                            class="form-delete-fonction">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger shadow btn-xs sharp" type="submit" title="Supprimer">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </form>

                                        <a href="{{ route('fonction.assigner', $fonction->code_fonction) }}"
                                            class="btn btn-info shadow btn-xs sharp"
                                            title="Assigner des permissions">
                                            <i class="fas fa-user-shield" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>N°</th>
                                    <th>Nom</th>
                                    <th>Permissions</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    {{-- ── Modal Ajout ─────────────────────────────────────────────────────── --}}
    <div class="modal fade modal-fonction-sifec" id="modalAjoutFonction"
        data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="labelAjoutFonction" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="labelAjoutFonction">Nouvelle fonction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form class="js-fonction-modal-form" action="{{ route('fonction.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-0">
                            <label class="form-label" for="inputLibFonctionAjout">Libellé <span class="text-danger">*</span></label>
                            <input id="inputLibFonctionAjout" type="text" name="lib_fonction" required
                                class="form-control @error('lib_fonction') is-invalid @enderror"
                                value="{{ old('lib_fonction') }}"
                                placeholder="Nom de la fonction"
                                autocomplete="off">
                            @error('lib_fonction')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-sm btn-primary">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Modals Édition (un par fonction, hors du tableau) ───────────────── --}}
    @foreach ($fonctions as $fonction)
    <div class="modal fade modal-fonction-sifec" id="modalEdit{{ $fonction->code_fonction }}"
        data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="labelEdit{{ $fonction->code_fonction }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="labelEdit{{ $fonction->code_fonction }}">
                        Modification — {{ $fonction->lib_fonction }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form class="js-fonction-modal-form" action="{{ route('fonction.update', $fonction->code_fonction) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-0">
                            <label class="form-label" for="inputLibFonctionEdit{{ $fonction->code_fonction }}">Libellé <span class="text-danger">*</span></label>
                            <input id="inputLibFonctionEdit{{ $fonction->code_fonction }}" type="text" name="lib_fonction" required
                                class="form-control"
                                value="{{ $fonction->lib_fonction }}"
                                autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-sm btn-warning">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
</div>
</div>
@endsection
@section('scripts')
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

    <script>
        function resetFonctionModalSubmit(btn) {
            if (!btn) return;
            btn.removeAttribute('data-sifec-submitting');
            btn.disabled = false;
            btn.removeAttribute('aria-busy');
            btn.classList.remove('sifec-btn-loading');
            var html = btn.getAttribute('data-sifec-html');
            if (html) {
                btn.innerHTML = html;
            }
        }

        $(document).on('submit', 'form.js-fonction-modal-form', function () {
            var form = this;
            var btn = form.querySelector('button[type="submit"]');
            if (!btn || btn.getAttribute('data-sifec-submitting') === '1') {
                return;
            }
            btn.setAttribute('data-sifec-submitting', '1');
            if (!btn.getAttribute('data-sifec-html')) {
                btn.setAttribute('data-sifec-html', btn.innerHTML);
            }
            // Ne pas désactiver le bouton dans le même tick que submit : certains navigateurs n’envoient pas le POST.
            setTimeout(function () {
                btn.disabled = true;
                btn.setAttribute('aria-busy', 'true');
                btn.classList.add('sifec-btn-loading');
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1" aria-hidden="true"></i>Enregistrement…';
            }, 0);
        });

        $(document).on('hidden.bs.modal', '.modal-fonction-sifec', function () {
            var form = this.querySelector('form.js-fonction-modal-form');
            if (!form) return;
            resetFonctionModalSubmit(form.querySelector('button[type="submit"]'));
        });

        // Confirmation avant suppression (SweetAlert) puis envoi natif du formulaire DELETE
        $(document).on('submit', '.form-delete-fonction', function (e) {
            e.preventDefault();
            var form = this;
            Swal.fire({
                title: 'Confirmer la suppression',
                html: 'Cette fonction sera <strong>supprimée définitivement</strong>.<br><span style="font-size:0.9em;opacity:.9">Cette action est irréversible.</span>',
                type: 'warning',
                showCancelButton: true,
                focusCancel: true,
                reverseButtons: true,
                buttonsStyling: false,
                customClass: 'sifec-swal-delete',
                confirmButtonText: 'Supprimer',
                cancelButtonText: 'Annuler',
                confirmButtonAriaLabel: 'Confirmer la suppression',
                cancelButtonAriaLabel: 'Annuler'
            }).then(function (result) {
                // SweetAlert2 v7 (public/sweetalert2.all.min.js) : confirm → result.value === true. v8+ : result.isConfirmed.
                if (result && (result.value === true || result.isConfirmed === true)) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
