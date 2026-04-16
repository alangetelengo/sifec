@extends('layout.app')
@section('titre')
    Liste des modules
@endsection
@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style>
    .page-modules-sifec {
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

    .page-modules-sifec .pf-card {
        border: 1px solid var(--pf-line);
        border-radius: var(--pf-radius);
        box-shadow: var(--pf-shadow-lg);
        overflow: hidden;
        background: var(--pf-paper);
    }

    .page-modules-sifec .pf-card .card-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem 1rem;
        background: linear-gradient(135deg, var(--pf-green-soft) 0%, #f4f7f5 100%);
        border-bottom: 1px solid var(--pf-line);
        padding: 1.1rem 1.35rem;
    }

    .page-modules-sifec .pf-card .card-header h4 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--pf-ink);
        letter-spacing: -0.02em;
    }

    .page-modules-sifec .pf-header-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.65rem;
    }

    .page-modules-sifec .pf-count {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--pf-green-mid);
        background: rgba(27, 111, 74, 0.1);
        border: 1px solid rgba(27, 111, 74, 0.2);
        padding: 0.28rem 0.65rem;
        border-radius: 999px;
    }

    .page-modules-sifec .pf-btn-add {
        background: var(--pf-green-mid);
        border-color: var(--pf-green-mid);
        color: #fff;
        font-weight: 600;
        border-radius: 10px;
        padding: 0.45rem 1rem;
        box-shadow: 0 2px 8px rgba(27, 111, 74, 0.25);
    }

    .page-modules-sifec .pf-btn-add:hover {
        background: var(--pf-green);
        border-color: var(--pf-green);
        color: #fff;
    }

    .page-modules-sifec .pf-card .card-body {
        padding: 1.15rem 1.25rem 1.35rem;
    }

    .page-modules-sifec .table-responsive {
        border-radius: 10px;
        border: 1px solid var(--pf-line);
        overflow: auto;
        background: #fafcfb;
    }

    .page-modules-sifec table.dataTable {
        margin: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0;
    }

    .page-modules-sifec table.dataTable thead th {
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

    .page-modules-sifec table.dataTable tbody td {
        padding: 0.7rem 0.85rem !important;
        vertical-align: middle;
        border-color: #eef1ee !important;
        font-size: 0.92rem;
        color: #2d3d35;
    }

    .page-modules-sifec table.dataTable tbody tr:hover td {
        background: rgba(232, 240, 235, 0.45) !important;
    }

    .page-modules-sifec .pf-col-name {
        font-weight: 600;
        color: var(--pf-ink);
    }

    .page-modules-sifec .pf-desc {
        max-width: 22rem;
        font-size: 0.85rem;
        color: var(--pf-muted);
        line-height: 1.45;
    }

    .page-modules-sifec .pf-actions {
        white-space: nowrap;
    }

    .page-modules-sifec .pf-actions .btn {
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

    .page-modules-sifec .pf-actions .btn:last-child {
        margin-right: 0;
    }

    .page-modules-sifec .pf-actions .btn-info {
        background: #0d6e8c;
        border-color: #0d6e8c;
    }

    .page-modules-sifec .pf-actions .btn-info:hover {
        background: #0a5a73;
        border-color: #0a5a73;
        color: #fff;
    }

    .page-modules-sifec .pf-link-fonc {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--pf-green-mid);
        text-decoration: none;
    }

    .page-modules-sifec .pf-link-fonc:hover {
        color: var(--pf-green);
        text-decoration: underline;
    }

    .page-modules-sifec #dt-modules-sifec_wrapper .dataTables_length,
    .page-modules-sifec #dt-modules-sifec_wrapper .dataTables_filter {
        margin-bottom: 0.85rem;
    }

    .page-modules-sifec #dt-modules-sifec_wrapper .dataTables_filter input {
        border-radius: 8px;
        border: 1px solid #cfd8d3;
        padding: 0.35rem 0.65rem;
    }

    .page-modules-sifec #dt-modules-sifec_wrapper .dataTables_filter input:focus {
        border-color: var(--pf-green-mid);
        box-shadow: 0 0 0 2px rgba(27, 111, 74, 0.2);
        outline: none;
    }

    .page-modules-sifec #dt-modules-sifec_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--pf-green-mid) !important;
        border-color: var(--pf-green-mid) !important;
        color: #fff !important;
    }

    @include('authentification::partials.sifec-swal-delete-styles')
</style>
@endsection

@section('corps')
<div class="page-sifec-index">
    <div class="an-shell">
        <div class="an-body">
            <div class="page-modules-sifec">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card pf-card">
                            <div class="card-header">
                                <h4>Liste des modules</h4>
                                <div class="pf-header-meta">
                                    <span class="pf-count">{{ $modules->count() }} module(s)</span>
                                    <a href="{{ route('module.create') }}" class="btn btn-sm pf-btn-add">
                                        <i class="fas fa-plus me-1" aria-hidden="true"></i>Créer un module
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="dt-modules-sifec" class="display" style="min-width: 845px">
                                        <thead>
                                            <tr>
                                                <th>N°</th>
                                                <th>Libellé</th>
                                                <th>Description</th>
                                                <th>Fonctionnalités</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($modules as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td class="pf-col-name">{{ $item->lib_module }}</td>
                                                    <td>
                                                        <div class="pf-desc text-truncate" title="{{ e($item->description_module) }}">{{ $item->description_module }}</div>
                                                    </td>
                                                    <td>
                                                        <a href="#"
                                                            class="show-module pf-link-fonc"
                                                            data-lib="{{ e($item->lib_module) }}"
                                                            data-code="{{ e($item->code_module) }}">
                                                            Voir ({{ $item->fonctionnalites->count() }})
                                                        </a>
                                                    </td>
                                                    <td>
                                                        @if ($item->etat_module === 'Activé')
                                                            <span class="badge light badge-success" style="font-size: 13px; font-weight: 600;">{{ $item->etat_module }}</span>
                                                        @elseif ($item->etat_module === 'Désactivé')
                                                            <span class="badge light badge-danger" style="font-size: 13px; font-weight: 600;">{{ $item->etat_module }}</span>
                                                        @else
                                                            <span class="badge light badge-secondary" style="font-size: 13px; font-weight: 600;">{{ $item->etat_module }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="pf-actions">
                                                        <a href="{{ route('module.edit', $item->code_module) }}"
                                                            class="btn btn-info shadow btn-xs sharp"
                                                            title="Modifier">
                                                            <i class="fas fa-pencil-alt" aria-hidden="true"></i>
                                                        </a>
                                                        <form style="display: inline-block"
                                                            action="{{ route('module.destroy', $item->code_module) }}"
                                                            method="post"
                                                            class="form-delete-module">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-danger shadow btn-xs sharp" type="submit" title="Supprimer">
                                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted py-4">Aucun module enregistré.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>N°</th>
                                                <th>Libellé</th>
                                                <th>Description</th>
                                                <th>Fonctionnalités</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modal-fonctionnalites" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="labelModalFoncModule" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="labelModalFoncModule"><span class="module-title"></span></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                            </div>
                            <div class="modal-body">
                                <div id="fonctionnalites" class="w-100"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script>
    function escapeHtml(s) {
        if (s === null || s === undefined) {
            return '';
        }
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    $(function () {
        if ($.fn.DataTable && $('#dt-modules-sifec').length) {
            try {
                $('#dt-modules-sifec').DataTable({
                    pageLength: 25,
                    order: [[1, 'asc']],
                    language: {
                        search: 'Filtrer&nbsp;:',
                        lengthMenu: 'Afficher _MENU_ lignes',
                        info: 'Lignes _START_ à _END_ sur _TOTAL_',
                        infoEmpty: 'Aucune ligne',
                        zeroRecords: 'Aucun résultat',
                        paginate: {
                            first: '«',
                            last: '»',
                            next: 'Suivant',
                            previous: 'Précédent'
                        }
                    }
                });
            } catch (err) {
                console.warn('DataTables (modules)', err);
            }
        }

        $(document).on('click', 'a.show-module', function (e) {
            e.preventDefault();
            var me = $(this);
            var codeModule = me.data('code');
            var libModule = me.data('lib');
            var modalEl = document.getElementById('modal-fonctionnalites');

            $('span.module-title').text(libModule);
            getFonctionnalites(codeModule);

            if (modalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            }
        });

        $(document).on('submit', '.form-delete-module', function (e) {
            e.preventDefault();
            var form = this;
            Swal.fire({
                title: 'Confirmer la suppression',
                html: 'Ce module sera marqué comme <strong>supprimé</strong> dans le système.<br><span style="font-size:0.9em;opacity:.9">Vérifiez qu’aucune fonctionnalité active ne dépend encore de ce module.</span>',
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
                if (result && (result.value === true || result.isConfirmed === true)) {
                    form.submit();
                }
            });
        });
    });

    function getFonctionnalites(codeModule) {
        var route = "{{ route('module.fonctionnalites', ':id') }}";
        route = route.replace(':id', encodeURIComponent(codeModule));

        $('#fonctionnalites').html('<p class="text-muted mb-0"><i class="fas fa-spinner fa-spin me-2" aria-hidden="true"></i>Chargement…</p>');

        $.get(route, function (data) {
            var rows = '';
            var int = 0;
            var list = (data && data.fonctionnalites) ? data.fonctionnalites : [];

            if (list.length > 0) {
                for (var i = 0; i < list.length; i++) {
                    int++;
                    rows += '<tr>' +
                        '<td>' + int + '</td>' +
                        '<td>' + escapeHtml(list[i].lib_fonctionnalite) + '</td>' +
                        '<td>' + escapeHtml(list[i].description_fonctionnalite) + '</td>' +
                        '<td>' + escapeHtml(list[i].etat_fonctionnalite) + '</td>' +
                        '</tr>';
                }
            }

            var table = '<div class="table-responsive">' +
                '<table class="table table-bordered table-hover mb-0">' +
                '<thead class="table-light">' +
                '<tr>' +
                '<th>#</th>' +
                '<th>Libellé</th>' +
                '<th>Description</th>' +
                '<th>État</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody>' +
                (rows || '<tr><td colspan="4" class="text-center text-muted py-3">Aucune fonctionnalité pour ce module.</td></tr>') +
                '</tbody>' +
                '</table>' +
                '</div>';

            $('#fonctionnalites').html(table);
        }).fail(function () {
            $('#fonctionnalites').html('<div class="alert alert-danger mb-0">Impossible de charger les fonctionnalités.</div>');
        });
    }
</script>
@endsection
