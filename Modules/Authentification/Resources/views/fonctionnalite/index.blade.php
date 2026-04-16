@extends('layout.app')
@section('titre')
Liste des fonctionnalités
@endsection
@section('styles')
<link href="{{ asset('tpl/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css')}}" rel="stylesheet">
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<style>
@include('authentification::partials.sifec-swal-delete-styles')
    .page-fonctionnalites-sifec {
        --pfc-ink: #1a2e26;
        --pfc-muted: #5c6d66;
        --pfc-green: #0f5132;
        --pfc-green-mid: #1b6f4a;
        --pfc-green-soft: #e8f0eb;
        --pfc-line: #e2e8e4;
        --pfc-paper: #ffffff;
        --pfc-shadow-lg: 0 12px 40px rgba(26, 46, 38, 0.08);
        --pfc-radius: 14px;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        margin: -0.35rem -0.5rem 0;
        padding: 0.5rem 0.5rem 2rem;
        min-height: calc(100vh - 100px);
        background: linear-gradient(180deg, #fafaf8 0%, #eef1ee 100%);
    }

    .page-fonctionnalites-sifec .pfc-card {
        border: 1px solid var(--pfc-line);
        border-radius: var(--pfc-radius);
        box-shadow: var(--pfc-shadow-lg);
        overflow: hidden;
        background: var(--pfc-paper);
    }

    .page-fonctionnalites-sifec .pfc-card > .card-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem 1rem;
        background: linear-gradient(135deg, var(--pfc-green-soft) 0%, #f4f7f5 100%);
        border-bottom: 1px solid var(--pfc-line);
        padding: 1.1rem 1.35rem;
    }

    .page-fonctionnalites-sifec .pfc-card > .card-header h4 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--pfc-ink);
        letter-spacing: -0.02em;
    }

    .page-fonctionnalites-sifec .pfc-header-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.65rem;
    }

    .page-fonctionnalites-sifec .pfc-count {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--pfc-green-mid);
        background: rgba(27, 111, 74, 0.1);
        border: 1px solid rgba(27, 111, 74, 0.2);
        padding: 0.28rem 0.65rem;
        border-radius: 999px;
    }

    .page-fonctionnalites-sifec .pfc-btn-add {
        background: var(--pfc-green-mid);
        border-color: var(--pfc-green-mid);
        color: #fff;
        font-weight: 600;
        border-radius: 10px;
        padding: 0.45rem 1rem;
        box-shadow: 0 2px 8px rgba(27, 111, 74, 0.25);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .page-fonctionnalites-sifec .pfc-btn-add:hover {
        background: var(--pfc-green);
        border-color: var(--pfc-green);
        color: #fff;
    }

    .page-fonctionnalites-sifec .pfc-card .card-body {
        padding: 1.15rem 1.25rem 1.35rem;
    }

    .page-fonctionnalites-sifec .table-responsive {
        border-radius: 10px;
        border: 1px solid var(--pfc-line);
        overflow: auto;
        background: #fafcfb;
    }

    .page-fonctionnalites-sifec table.dataTable {
        margin: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0;
    }

    .page-fonctionnalites-sifec table.dataTable thead th {
        background: #f0f4f1;
        color: var(--pfc-ink);
        font-weight: 600;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 2px solid #d5ded8 !important;
        padding: 0.75rem 0.85rem !important;
        white-space: nowrap;
    }

    .page-fonctionnalites-sifec table.dataTable tbody td {
        padding: 0.7rem 0.85rem !important;
        vertical-align: middle;
        border-color: #eef1ee !important;
        font-size: 0.92rem;
        color: #2d3d35;
    }

    .page-fonctionnalites-sifec table.dataTable tbody tr:hover td {
        background: rgba(232, 240, 235, 0.45) !important;
    }

    .page-fonctionnalites-sifec .pfc-col-lib {
        font-weight: 600;
        color: var(--pfc-ink);
    }

    .page-fonctionnalites-sifec .pfc-desc {
        max-width: 14rem;
        font-size: 0.85rem;
        color: var(--pfc-muted);
        line-height: 1.4;
    }

    .page-fonctionnalites-sifec .pfc-actions {
        white-space: nowrap;
    }

    .page-fonctionnalites-sifec .pfc-actions .btn {
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

    .page-fonctionnalites-sifec .pfc-actions .btn-primary {
        background: var(--pfc-green-mid);
        border-color: var(--pfc-green-mid);
    }

    .page-fonctionnalites-sifec .pfc-actions .btn-primary:hover {
        background: var(--pfc-green);
        border-color: var(--pfc-green);
        color: #fff;
    }

    .page-fonctionnalites-sifec .pfc-actions .btn-info {
        background: #0d6e8c;
        border-color: #0d6e8c;
    }

    .page-fonctionnalites-sifec .pfc-actions .btn-info:hover {
        background: #0a5a73;
        border-color: #0a5a73;
        color: #fff;
    }

    .page-fonctionnalites-sifec #example_wrapper .dataTables_length,
    .page-fonctionnalites-sifec #example_wrapper .dataTables_filter {
        margin-bottom: 0.85rem;
    }

    .page-fonctionnalites-sifec #example_wrapper .dataTables_filter input {
        border-radius: 8px;
        border: 1px solid #cfd8d3;
        padding: 0.35rem 0.65rem;
    }

    .page-fonctionnalites-sifec #example_wrapper .dataTables_filter input:focus {
        border-color: var(--pfc-green-mid);
        box-shadow: 0 0 0 2px rgba(27, 111, 74, 0.2);
        outline: none;
    }

    .page-fonctionnalites-sifec #example_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--pfc-green-mid) !important;
        border-color: var(--pfc-green-mid) !important;
        color: #fff !important;
    }
</style>
@endsection

@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
<div class="page-fonctionnalites-sifec">
    <div class="row">
        <div class="col-xl-12">
            <div class="card pfc-card">
                <div class="card-header">
                    <h4>Liste des fonctionnalités</h4>
                    <div class="pfc-header-meta">
                        <span class="pfc-count">{{ $fonctionnalites->count() }} fonctionnalité(s)</span>
                        <a href="{{ route('fonctionnalite.create') }}" class="btn btn-sm pfc-btn-add">
                            <i class="fas fa-plus me-1" aria-hidden="true"></i>Créer une fonctionnalité
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="display" style="min-width: 845px">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Libellé</th>
                                    <th>Description</th>
                                    <th>Module</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @forelse ($fonctionnalites as $item)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td class="pfc-col-lib">{{ $item->lib_fonctionnalite }}</td>
                                    <td>
                                        <div class="pfc-desc text-truncate" title="{{ e($item->description_fonctionnalite) }}">{{ $item->description_fonctionnalite }}</div>
                                    </td>
                                    <td>{{ $item->module->lib_module }}</td>
                                    <td>
                                        @if($item->etat_fonctionnalite == 'Activé')
                                            <span class="badge light badge-success" style="font-size: 13px;font-weight:600;">{{ $item->etat_fonctionnalite }}</span>
                                        @elseif($item->etat_fonctionnalite == 'Désactivé')
                                            <span class="badge light badge-danger" style="font-size: 13px;font-weight:600;">{{ $item->etat_fonctionnalite }}</span>
                                        @else
                                            <span class="badge light badge-secondary">{{ $item->etat_fonctionnalite }}</span>
                                        @endif
                                    </td>
                                    <td class="pfc-actions">
                                        <a href="{{ route('fonctionnalite.edit', $item->code_fonctionnalite) }}"
                                            class="btn btn-info shadow btn-xs sharp"
                                            title="Modifier"><i class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                                        <form action="{{ route('fonctionnalite.destroy', $item->code_fonctionnalite) }}" method="post" class="d-inline-block form-delete-fonctionnalite">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger shadow btn-xs sharp" type="submit" title="Supprimer"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Aucune fonctionnalité enregistrée.</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Libellé</th>
                                    <th>Description</th>
                                    <th>Module</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
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
<script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>
<script>
    $(document).on('submit', '.form-delete-fonctionnalite', function (e) {
        e.preventDefault();
        var form = this;
        Swal.fire({
            title: 'Confirmer la suppression',
            html: 'Cette fonctionnalité sera <strong>supprimée définitivement</strong>.<br><span style="font-size:0.9em;opacity:.9">Cette action est irréversible.</span>',
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
</script>
@endsection
