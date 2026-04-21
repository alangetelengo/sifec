@extends('layout.app')

@section('titre')
Gestion des tarifs
@endsection

@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ asset('tpl/wizard/assets/node_modules/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
@include('referentiel::partials.sifec-ref-crud-styles')
<style>
    @include('authentification::partials.sifec-swal-delete-styles')
    .sifec-ref-crud-page .sl-code-tarif {
        font-size: .75rem;
        background: rgba(0, 107, 49, .08);
        color: var(--sl-green);
        padding: .2rem .45rem;
        border-radius: 6px;
        font-weight: 600;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    }
    .sifec-ref-crud-page .sl-prix-tarif {
        font-weight: 700;
        color: #1a1a1a;
        font-size: .95rem;
        white-space: nowrap;
    }
    .sifec-ref-crud-page .sl-badge-portee {
        font-size: .72rem;
        font-weight: 600;
        padding: .35rem .65rem;
        border-radius: 999px;
    }
    .sifec-ref-crud-page .sl-badge-portee-national {
        background: rgba(0, 158, 73, .12);
        color: var(--sl-green);
    }
    .sifec-ref-crud-page .sl-badge-portee-mairie {
        background: rgba(39, 129, 213, .12);
        color: #1a5a8a;
    }
    .sifec-ref-crud-page .sl-btn-action-toggle {
        color: #856404;
        border-color: rgba(212, 176, 42, .45);
    }
    .sifec-ref-crud-page .sl-btn-action-toggle:hover {
        background: rgba(255, 193, 7, .14);
        border-color: #c9a227;
        color: #6c4a00;
    }
    .sifec-ref-crud-page .sl-validite-lines {
        font-size: .8rem;
        color: #495057;
        line-height: 1.45;
    }
    .sifec-ref-crud-page .sl-validite-lines i { color: var(--sl-mid); width: 1rem; }
</style>
@endsection

@section('corps')
<div class="sifec-ref-crud-page container-fluid px-0">
    <div class="sl-hero mb-4">
        <div class="row align-items-center g-3 position-relative" style="z-index: 1;">
            <div class="col-lg">
                <h1><i class="fas fa-tags me-2 opacity-90"></i>Gestion des tarifs</h1>
                <p class="mb-0">Tarifs nationaux et tarifs par mairie pour les copies et extraits d’actes (demandes de documents).</p>
            </div>
            <div class="col-lg-auto d-flex flex-wrap gap-2 justify-content-lg-end">
                @can('module.admin.demande_document.parametres')
                    <a href="{{ route('admin.demande-document-config.edit') }}" class="btn btn-outline-light">
                        <i class="fas fa-calendar-alt me-1"></i> Validité documents
                    </a>
                @endcan
                <a href="{{ route('admin.tarifs.create') }}" class="btn btn-light">
                    <i class="fas fa-plus-circle me-1"></i> Nouveau tarif
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card sl-stat">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="sl-stat-icon text-white" style="background: linear-gradient(135deg, #006B31, #009E49);">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div>
                        <div class="sl-stat-lbl">Tarifs enregistrés</div>
                        <div class="sl-stat-val">{{ $stats['total'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card sl-stat">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="sl-stat-icon text-white" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="sl-stat-lbl">Actifs</div>
                        <div class="sl-stat-val">{{ $stats['actifs'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card sl-stat">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="sl-stat-icon text-white" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                        <i class="fas fa-globe-africa"></i>
                    </div>
                    <div>
                        <div class="sl-stat-lbl">Nationaux</div>
                        <div class="sl-stat-val">{{ $stats['nationaux'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card sl-stat">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="sl-stat-icon text-white" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                        <i class="fas fa-building"></i>
                    </div>
                    <div>
                        <div class="sl-stat-lbl">Par mairie</div>
                        <div class="sl-stat-val">{{ $stats['specifiques'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card sl-card">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Liste des tarifs</h5>
            <span class="badge rounded-pill" style="background: rgba(0, 107, 49, 0.12); color: #006B31;">
                <i class="fas fa-bookmark me-1"></i> Administration
            </span>
        </div>

        <div class="card-body border-top">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <div class="sl-filter-label">Type de document</div>
                    <select name="type_document" class="form-select form-control">
                        <option value="">Tous</option>
                        @foreach($typesDocuments as $type)
                            <option value="{{ $type->code_type_document_demande }}"
                                    {{ request('type_document') == $type->code_type_document_demande ? 'selected' : '' }}>
                                {{ $type->lib_type_document_demande }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="sl-filter-label">Portée</div>
                    <select name="type_tarif" class="form-select form-control">
                        <option value="">Tous</option>
                        <option value="national" {{ request('type_tarif') == 'national' ? 'selected' : '' }}>National</option>
                        <option value="specifique" {{ request('type_tarif') == 'specifique' ? 'selected' : '' }}>Spécifique (mairie)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="sl-filter-label">Statut</div>
                    <select name="actif" class="form-select form-control">
                        <option value="">Tous</option>
                        <option value="1" {{ request('actif') === '1' ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ request('actif') === '0' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2 flex-wrap">
                    <button type="submit" class="btn btn-primary flex-grow-1 flex-md-grow-0">
                        <i class="fas fa-search me-1"></i> Filtrer
                    </button>
                    <a href="{{ route('admin.tarifs.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>

        <div class="card-body pt-0">
            <div class="sl-table-wrap">
                <div class="table-responsive sl-table-host">
                    <table id="table-tarifs-admin" class="display sl-table" style="min-width: 920px">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Type document</th>
                                <th class="text-end">Prix (FCFA)</th>
                                <th>Portée</th>
                                <th>Validité</th>
                                <th>Statut</th>
                                <th class="sl-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tarifs as $tarif)
                                <tr>
                                    <td><span class="sl-code-tarif">{{ $tarif->code_tarification }}</span></td>
                                    <td>{{ $tarif->typeDocumentDemande->lib_type_document_demande }}</td>
                                    <td class="text-end">
                                        <span class="sl-prix-tarif">{{ number_format($tarif->prix, 0, ',', ' ') }}</span>
                                    </td>
                                    <td>
                                        @if($tarif->code_institution)
                                            <span class="sl-badge-portee sl-badge-portee-mairie">
                                                <i class="fas fa-building me-1"></i>{{ \Illuminate\Support\Str::limit($tarif->institution->lib_institution ?? $tarif->code_institution, 42) }}
                                            </span>
                                        @else
                                            <span class="sl-badge-portee sl-badge-portee-national">
                                                <i class="fas fa-globe me-1"></i> National
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="sl-validite-lines">
                                            <div>
                                                <i class="fas fa-calendar-day me-1"></i>
                                                Du {{ $tarif->date_debut_validite ? $tarif->date_debut_validite->format('d/m/Y') : '—' }}
                                            </div>
                                            <div>
                                                <i class="fas fa-calendar-times me-1"></i>
                                                Au {{ $tarif->date_fin_validite ? $tarif->date_fin_validite->format('d/m/Y') : '—' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($tarif->actif)
                                            <span class="badge badge-success">Actif</span>
                                        @else
                                            <span class="badge badge-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="sl-actions">
                                        <div class="sl-actions-group">
                                            <a href="{{ route('admin.tarifs.edit', $tarif->code_tarification) }}"
                                               class="sl-btn-action sl-btn-action-edit" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.tarifs.toggle', $tarif->code_tarification) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                        class="sl-btn-action sl-btn-action-toggle"
                                                        title="{{ $tarif->actif ? 'Désactiver' : 'Activer' }}">
                                                    <i class="fas fa-{{ $tarif->actif ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.tarifs.destroy', $tarif->code_tarification) }}"
                                                  method="POST" class="d-inline form-tarif-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="sl-btn-action sl-btn-action-delete btn-tarif-delete" title="Supprimer">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="sl-empty-icon mb-3"><i class="fas fa-tags"></i></div>
                                        <p class="text-muted mb-2">Aucun tarif ne correspond aux critères.</p>
                                        <a href="{{ route('admin.tarifs.create') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus-circle me-1"></i> Créer un tarif
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-muted small">
                    @if($tarifs->total() > 0)
                        Affichage de {{ $tarifs->firstItem() }} à {{ $tarifs->lastItem() }} sur {{ $tarifs->total() }}
                    @endif
                </div>
                {{ $tarifs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('tpl/wizard/assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
<script>
$(document).ready(function() {
    @if($tarifs->isNotEmpty())
    $('#table-tarifs-admin').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json'
        },
        paging: false,
        info: false,
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: [6] }
        ],
        dom: '<"row mb-2"<"col-sm-12"f>>rt'
    });
    @endif

    $(document).on('click', '.btn-tarif-delete', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var form = this.closest('form');
        if (!form || !form.action) {
            return;
        }
        Swal.fire({
            title: 'Supprimer ce tarif ?',
            text: 'Cette action est irréversible.',
            icon: 'warning',
            showCancelButton: true,
            customClass: { popup: 'sifec-swal-delete' },
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler',
            buttonsStyling: true
        }).then(function(result) {
            var ok = result && (result.isConfirmed === true || result.value === true);
            if (ok) {
                form.submit();
            }
        });
    });
});
</script>
@endsection
