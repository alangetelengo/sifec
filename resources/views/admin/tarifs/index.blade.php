@extends('layout.app')

@section('titre')
Gestion des tarifs
@endsection

@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
@endsection

@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-tags text-primary me-2"></i> Gestion des Tarifs</h4>
                    <div class="float-end">
                        @can('module.admin.demande_document.parametres')
                            <a href="{{ route('admin.demande-document-config.edit') }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-calendar-alt"></i> Validité documents (demandes)
                            </a>
                        @endcan
                        <a href="{{ route('admin.tarifs.create') }}">
                            <button type="button" class="btn btn-info text-white">
                                <i class="fa fa-plus-circle"></i> Nouveau Tarif
                            </button>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Filtres --}}
                    <form method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Type de document</label>
                                <select name="type_document" class="form-control">
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
                                <label class="form-label">Type de tarif</label>
                                <select name="type_tarif" class="form-control">
                                    <option value="">Tous</option>
                                    <option value="national" {{ request('type_tarif') == 'national' ? 'selected' : '' }}>National</option>
                                    <option value="specifique" {{ request('type_tarif') == 'specifique' ? 'selected' : '' }}>Spécifique</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Statut</label>
                                <select name="actif" class="form-control">
                                    <option value="">Tous</option>
                                    <option value="1" {{ request('actif') === '1' ? 'selected' : '' }}>Actif</option>
                                    <option value="0" {{ request('actif') === '0' ? 'selected' : '' }}>Inactif</option>
                                </select>
                            </div>

                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-filter"></i> Filtrer
                                </button>
                                <a href="{{ route('admin.tarifs.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> Réinitialiser
                                </a>
                            </div>
                        </div>
                    </form>

                    {{-- Table --}}
                    <div class="table-responsive">
                        <table id="example" class="display" style="min-width: 845px">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Type Document</th>
                                    <th>Prix (FCFA)</th>
                                    <th>Portée</th>
                                    <th>Validité</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tarifs as $tarif)
                                    <tr>
                                        <td><strong>{{ $tarif->code_tarification }}</strong></td>
                                        <td>{{ $tarif->typeDocumentDemande->lib_type_document_demande }}</td>
                                        <td class="text-end">
                                            <strong style="font-size: 16px; color: #28a745;">
                                                {{ number_format($tarif->prix, 0, ',', ' ') }}
                                            </strong>
                                        </td>
                                        <td>
                                            @if($tarif->code_institution)
                                                <span class="badge badge-info">
                                                    <i class="fas fa-building"></i> {{ $tarif->institution->lib_institution ?? $tarif->code_institution }}
                                                </span>
                                            @else
                                                <span class="badge badge-primary">
                                                    <i class="fas fa-globe"></i> National
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>
                                                Du: {{ $tarif->date_debut_validite ? $tarif->date_debut_validite->format('d/m/Y') : 'Indéfini' }}<br>
                                                Au: {{ $tarif->date_fin_validite ? $tarif->date_fin_validite->format('d/m/Y') : 'Indéfini' }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($tarif->actif)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle"></i> Actif
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-times-circle"></i> Inactif
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('admin.tarifs.edit', $tarif->code_tarification) }}" 
                                                   class="btn btn-sm btn-primary" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <form action="{{ route('admin.tarifs.toggle', $tarif->code_tarification) }}" 
                                                      method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-{{ $tarif->actif ? 'warning' : 'success' }}" 
                                                            title="{{ $tarif->actif ? 'Désactiver' : 'Activer' }}">
                                                        <i class="fas fa-{{ $tarif->actif ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('admin.tarifs.destroy', $tarif->code_tarification) }}" 
                                                      method="POST" style="display: inline;" class="form-delete">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle"></i> Aucun tarif configuré. 
                                                <a href="{{ route('admin.tarifs.create') }}">Créer le premier tarif</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-3">
                        {{ $tarifs->links() }}
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
<script src="{{ asset('tpl/wizard/assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Confirmation avant suppression
    $('.form-delete').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        
        Swal.fire({
            title: 'Supprimer ce tarif?',
            text: "Cette action est irréversible",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endsection
