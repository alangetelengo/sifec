@extends('layout.app')
@section('titre')
    Menu latéral (tr_menu_item)
@endsection
@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style>
@include('authentification::partials.sifec-swal-delete-styles')
    .page-menu-item-sifec {
        --pmi-ink: #1a2e26;
        --pmi-muted: #5c6d66;
        --pmi-green: #0f5132;
        --pmi-green-mid: #1b6f4a;
        --pmi-green-soft: #e8f0eb;
        --pmi-line: #e2e8e4;
        --pmi-paper: #ffffff;
        --pmi-shadow-lg: 0 12px 40px rgba(26, 46, 38, 0.08);
        --pmi-radius: 14px;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        margin: -0.35rem -0.5rem 0;
        padding: 0.5rem 0.5rem 2rem;
        min-height: calc(100vh - 100px);
        background: linear-gradient(180deg, #fafaf8 0%, #eef1ee 100%);
    }

    .page-menu-item-sifec .pmi-breadcrumb {
        font-size: 0.875rem;
        margin-bottom: 1rem;
        background: var(--pmi-paper);
        border: 1px solid var(--pmi-line);
        border-radius: 10px;
        padding: 0.65rem 1.15rem;
        box-shadow: 0 1px 3px rgba(26, 46, 38, 0.06);
    }

    .page-menu-item-sifec .pmi-breadcrumb .breadcrumb { margin-bottom: 0; }
    .page-menu-item-sifec .pmi-breadcrumb .breadcrumb-item a {
        color: var(--pmi-green-mid) !important;
        font-weight: 600;
        text-decoration: none;
    }

    .page-menu-item-sifec .pmi-card {
        border: 1px solid var(--pmi-line);
        border-radius: var(--pmi-radius);
        box-shadow: var(--pmi-shadow-lg);
        overflow: hidden;
        background: var(--pmi-paper);
    }

    .page-menu-item-sifec .pmi-card > .card-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem 1rem;
        background: linear-gradient(135deg, var(--pmi-green-soft) 0%, #f4f7f5 100%);
        border-bottom: 1px solid var(--pmi-line);
        padding: 1.1rem 1.35rem;
    }

    .page-menu-item-sifec .pmi-card > .card-header h4 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--pmi-ink);
        letter-spacing: -0.02em;
    }

    .page-menu-item-sifec .pmi-header-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.65rem;
    }

    .page-menu-item-sifec .pmi-count {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--pmi-green-mid);
        background: rgba(27, 111, 74, 0.1);
        border: 1px solid rgba(27, 111, 74, 0.2);
        padding: 0.28rem 0.65rem;
        border-radius: 999px;
    }

    .page-menu-item-sifec .pmi-btn-add {
        background: var(--pmi-green-mid);
        border-color: var(--pmi-green-mid);
        color: #fff;
        font-weight: 600;
        border-radius: 10px;
        padding: 0.45rem 1rem;
        box-shadow: 0 2px 8px rgba(27, 111, 74, 0.25);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .page-menu-item-sifec .pmi-btn-add:hover {
        background: var(--pmi-green);
        border-color: var(--pmi-green);
        color: #fff;
    }

    .page-menu-item-sifec .pmi-card .card-body {
        padding: 1.15rem 1.25rem 1.35rem;
    }

    .page-menu-item-sifec .pmi-info {
        background: linear-gradient(135deg, var(--pmi-green-soft) 0%, #f0f6f2 100%);
        border: 1px solid rgba(15, 81, 50, 0.12);
        border-radius: 10px;
        padding: 0.85rem 1.1rem;
        font-size: 0.8125rem;
        color: var(--pmi-muted);
        margin-bottom: 1rem;
        display: flex;
        align-items: flex-start;
        gap: 0.6rem;
    }

    .page-menu-item-sifec .pmi-info i {
        color: var(--pmi-green-mid);
        margin-top: 0.1rem;
    }

    .page-menu-item-sifec .table-responsive {
        border-radius: 10px;
        border: 1px solid var(--pmi-line);
        overflow: auto;
        background: #fafcfb;
    }

    .page-menu-item-sifec table.dataTable thead th {
        background: #f0f4f1;
        color: var(--pmi-ink);
        font-weight: 600;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 2px solid #d5ded8 !important;
        padding: 0.75rem 0.85rem !important;
        white-space: nowrap;
    }

    .page-menu-item-sifec table.dataTable tbody td {
        padding: 0.7rem 0.85rem !important;
        vertical-align: middle;
        border-color: #eef1ee !important;
        font-size: 0.92rem;
        color: #2d3d35;
    }

    .page-menu-item-sifec table.dataTable tbody tr:hover td {
        background: rgba(232, 240, 235, 0.45) !important;
    }

    .page-menu-item-sifec .pmi-code {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--pmi-green-mid);
    }

    .page-menu-item-sifec .pmi-actions {
        white-space: nowrap;
    }

    .page-menu-item-sifec .pmi-actions .btn {
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

    .page-menu-item-sifec .pmi-actions .btn-primary {
        background: var(--pmi-green-mid);
        border-color: var(--pmi-green-mid);
    }

    .page-menu-item-sifec .pmi-actions .btn-primary:hover {
        background: var(--pmi-green);
        border-color: var(--pmi-green);
        color: #fff;
    }

    .page-menu-item-sifec .pmi-actions .btn-danger {
        background: #9b2c2c;
        border-color: #9b2c2c;
    }

    .page-menu-item-sifec .pmi-actions .btn-danger:hover {
        background: #7a2323;
        border-color: #7a2323;
        color: #fff;
    }

    .page-menu-item-sifec #example_wrapper .dataTables_length,
    .page-menu-item-sifec #example_wrapper .dataTables_filter {
        margin-bottom: 0.85rem;
    }

    .page-menu-item-sifec #example_wrapper .dataTables_filter input {
        border-radius: 8px;
        border: 1px solid #cfd8d3;
        padding: 0.35rem 0.65rem;
    }

    .page-menu-item-sifec #example_wrapper .dataTables_filter input:focus {
        border-color: var(--pmi-green-mid);
        box-shadow: 0 0 0 2px rgba(27, 111, 74, 0.2);
        outline: none;
    }

    .page-menu-item-sifec #example_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--pmi-green-mid) !important;
        border-color: var(--pmi-green-mid) !important;
        color: #fff !important;
    }
</style>
@endsection

@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
<div class="page-menu-item-sifec">
    <nav class="pmi-breadcrumb" aria-label="Fil d'Ariane">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('module.index') }}">Administration</a></li>
            <li class="breadcrumb-item active" aria-current="page">Menu latéral</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-xl-12">
            <div class="card pmi-card">
                <div class="card-header">
                    <h4>Structure du menu latéral</h4>
                    <div class="pmi-header-meta">
                        <span class="pmi-count">{{ $items->count() }} entrée(s)</span>
                        <a href="{{ route('menu-item.create') }}" class="btn btn-sm pmi-btn-add">
                            <i class="fas fa-plus me-1" aria-hidden="true"></i>Nouvelle entrée
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="pmi-info" role="note">
                        <i class="fas fa-shield-alt"></i>
                        <span>
                            Réservé au <strong>super administrateur</strong> (<code>FONC_0011</code>). Les entrées visibles pour les autres profils
                            dépendent du champ <code>permission_gate</code>, aligné sur <code>tr_fonctionnalite.lib_technique</code>.
                        </span>
                    </div>
                    <div class="table-responsive">
                        <table id="example" class="display" style="min-width: 920px">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Libellé</th>
                                    <th>Parent</th>
                                    <th>Ordre</th>
                                    <th>Groupe</th>
                                    <th>Route / chemin</th>
                                    <th>Permission</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $row)
                                    <tr>
                                        <td><span class="pmi-code">{{ $row->code_menu_item }}</span></td>
                                        <td class="fw-semibold">{{ $row->libelle }}</td>
                                        <td>{{ $row->parent?->libelle ?? '—' }}</td>
                                        <td>{{ $row->sort_order }}</td>
                                        <td>
                                            @if($row->is_group)
                                                <span class="badge light badge-info" style="font-size:12px;font-weight:600;">Oui</span>
                                            @else
                                                <span class="badge light badge-secondary" style="font-size:12px;">Non</span>
                                            @endif
                                        </td>
                                        <td class="small">
                                            @if($row->route_name)
                                                <code class="text-dark">{{ $row->route_name }}</code>
                                            @elseif($row->external_path)
                                                <code class="text-dark" title="{{ e($row->external_path) }}">{{ \Illuminate\Support\Str::limit($row->external_path, 28) }}</code>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="small"><code class="text-muted">{{ $row->permission_gate ?? '—' }}</code></td>
                                        <td class="pmi-actions">
                                            <a href="{{ route('menu-item.edit', $row->code_menu_item) }}"
                                               class="btn btn-primary shadow btn-xs sharp"
                                               title="Modifier"><i class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                                            <form action="{{ route('menu-item.destroy', $row->code_menu_item) }}" method="post" class="d-inline-block form-delete-menu-item">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger shadow btn-xs sharp" type="submit" title="Supprimer"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">Aucune entrée.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Code</th>
                                    <th>Libellé</th>
                                    <th>Parent</th>
                                    <th>Ordre</th>
                                    <th>Groupe</th>
                                    <th>Route / chemin</th>
                                    <th>Permission</th>
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
@endsection

@section('scripts')
<script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>
<script>
    $(document).on('submit', '.form-delete-menu-item', function (e) {
        e.preventDefault();
        var form = this;
        Swal.fire({
            title: 'Confirmer la suppression',
            html: 'Cette entrée sera <strong>supprimée</strong>. Les sous-menus doivent être vides ou déplacés avant suppression.',
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
