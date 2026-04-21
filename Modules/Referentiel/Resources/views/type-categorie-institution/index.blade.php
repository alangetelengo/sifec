@extends('layout.app')
@section('titre')
    Référentiel — Catégories d’institution
@endsection
@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@include('referentiel::partials.sifec-ref-crud-styles')
@endsection
@section('corps')
@php
    $typeCategorieInstitutionsCount = $typeCategorieInstitutions ? $typeCategorieInstitutions->count() : 0;
@endphp
<div class="sifec-ref-crud-page container-fluid px-0">
    <div class="sl-hero mb-4">
        <div class="row align-items-center g-3 position-relative" style="z-index:1">
            <div class="col-lg">
                <h1><i class="fas fa-layer-group me-2 opacity-90"></i>Catégories d’institution</h1>
                <p>Regroupements utilisés pour classer les types d’institution (CEC, tribunaux, etc.).</p>
            </div>
            <div class="col-lg-auto">
                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addTypeCategorieInstitutionModal">
                    <i class="fas fa-plus-circle me-1"></i> Nouvelle catégorie
                </button>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-4">
            <div class="card sl-stat">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="sl-stat-icon text-white" style="background:linear-gradient(135deg,#006B31,#009E49);">
                        <i class="fas fa-list-ol"></i>
                    </div>
                    <div>
                        <div class="sl-stat-lbl">Enregistrements</div>
                        <div class="sl-stat-val">{{ $typeCategorieInstitutionsCount }}</div>
                        <div class="small text-muted">Catégories actives</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card sl-stat">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="sl-stat-icon text-white" style="background:linear-gradient(135deg,#2781d5,#5a9fd4);">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <div>
                        <div class="sl-stat-lbl">Usage</div>
                        <div class="sl-stat-val small fw-normal text-dark pt-1">Hiérarchie référentiel</div>
                        <div class="small text-muted">Lié aux types d’institution</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card sl-card">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <h5 class="mb-0"><i class="fas fa-table me-2"></i>Liste des catégories</h5>
            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addTypeCategorieInstitutionModal" style="border-radius:10px;">
                <i class="fas fa-plus me-1"></i> Ajouter
            </button>
        </div>
        <div class="card-body p-0 p-md-3">
            <div class="sl-table-host mx-md-0 px-3 px-md-0 pb-3 pb-md-0">
                <div class="table-responsive sl-table-wrap mt-0 mt-md-0">
                    <table id="table-type-categorie-institutions" class="table table-hover sl-table mb-0 align-middle" style="min-width:720px">
                        <thead>
                            <tr>
                                <th class="sl-row-num">#</th>
                                <th>Catégorie</th>
                                <th>Code</th>
                                <th>Image</th>
                                <th>Types associés</th>
                                <th class="text-end sl-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($typeCategorieInstitutions as $item)
                                <tr>
                                    <td class="text-center"><span class="sl-num">{{ $loop->iteration }}</span></td>
                                    <td><strong>{{ $item->lib_type_categorie_institution }}</strong></td>
                                    <td><code class="small">{{ $item->code_type_categorie_ins }}</code></td>
                                    <td>
                                        @if($item->image_illustrative)
                                            <img src="{{ asset('app/'.$item->image_illustrative) }}" alt="" width="48" height="48" class="rounded border object-fit-cover">
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill" style="background:rgba(0,158,73,.12);color:#006B31;">{{ $item->typeInstitutions->count() }} type(s)</span>
                                    </td>
                                    <td class="text-end sl-actions">
                                        <div class="sl-actions-group justify-content-end">
                                            <button type="button" class="sl-btn-action sl-btn-action-edit" data-bs-toggle="modal" data-bs-target="#editTypeCategorieInstitutionModal{{ $item->code_type_categorie_ins }}" title="Modifier">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <form action="{{ route('typeCategorieInstitution.destroy', $item->code_type_categorie_ins) }}" method="post" class="d-inline" id="deleteForm{{ $item->code_type_categorie_ins }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="sl-btn-action sl-btn-action-delete btn-delete" type="button" data-code="{{ $item->code_type_categorie_ins }}" data-libelle="{{ $item->lib_type_categorie_institution }}" title="Supprimer">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="sl-empty-icon mx-auto mb-2"><i class="fas fa-inbox"></i></div>
                                        <p class="text-muted mb-0">Aucune catégorie pour le moment.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal ajout --}}
<div class="modal fade" id="addTypeCategorieInstitutionModal" tabindex="-1" aria-labelledby="addTypeCategorieInstitutionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden;">
            <div class="modal-header sl-modal-header text-white border-0 py-3">
                <h5 class="modal-title" id="addTypeCategorieInstitutionModalLabel"><i class="fas fa-plus-circle me-2"></i>Nouvelle catégorie</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form method="POST" action="{{ route('typeCategorieInstitution.store') }}" enctype="multipart/form-data" id="addTypeCategorieInstitutionForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Libellé <span class="text-danger">*</span></label>
                        <input type="text" name="lib_type_categorie_institution" class="form-control form-control-lg @error('lib_type_categorie_institution') is-invalid @enderror"
                               value="{{ old('lib_type_categorie_institution') }}" placeholder="Ex. Centre d’état civil, Tribunal…" required>
                        @error('lib_type_categorie_institution')
                            <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">Image illustrative</label>
                        <input type="file" name="image_illustrative" class="form-control" accept="image/*">
                        <small class="form-text text-muted">JPEG, PNG, JPG ou GIF — max. 2 Mo.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Annuler</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-semibold"><i class="fas fa-check me-1"></i>Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach ($typeCategorieInstitutions as $item)
    <div class="modal fade" id="editTypeCategorieInstitutionModal{{ $item->code_type_categorie_ins }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden;">
                <div class="modal-header sl-modal-header text-white border-0 py-3">
                    <h5 class="modal-title"><i class="fas fa-pen-to-square me-2"></i>{{ $item->lib_type_categorie_institution }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form action="{{ route('typeCategorieInstitution.update', $item->code_type_categorie_ins) }}" method="POST" enctype="multipart/form-data" id="editTypeCategorieInstitutionForm{{ $item->code_type_categorie_ins }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Libellé <span class="text-danger">*</span></label>
                            <input type="text" name="lib_type_categorie_institution" class="form-control form-control-lg @error('lib_type_categorie_institution') is-invalid @enderror"
                                   value="{{ $item->lib_type_categorie_institution }}" required>
                            @error('lib_type_categorie_institution')
                                <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">Image illustrative</label>
                            @if($item->image_illustrative)
                                <div class="mb-2">
                                    <img src="{{ asset('app/'.$item->image_illustrative) }}" alt="" class="img-thumbnail" style="max-width:140px;">
                                    <p class="text-muted small mb-0">Image actuelle</p>
                                </div>
                            @endif
                            <input type="file" name="image_illustrative" class="form-control" accept="image/*">
                            <small class="form-text text-muted">Laissez vide pour conserver l’image actuelle.</small>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Annuler</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-semibold"><i class="fas fa-check me-1"></i>Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection
@section('scripts')
<script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script>
    function confirmDeleteTypeCategorieInstitution(code, libelle) {
        var form = document.getElementById('deleteForm' + code);
        if (!form) {
            Swal.fire({ title: 'Erreur', text: 'Formulaire de suppression introuvable.', icon: 'error', confirmButtonText: 'OK', customClass: { popup: 'sl-swal-referentiel' } });
            return;
        }
        var libEsc = (typeof sifecHtmlForSwalStrong === 'function')
            ? sifecHtmlForSwalStrong(libelle)
            : String(libelle).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        Swal.fire({
            title: 'Supprimer cette catégorie ?',
            html: 'La ligne <strong>' + libEsc + '</strong> sera retirée si aucun type d’institution ne la référence.',
            icon: 'warning',
            iconColor: '#c9a227',
            showCancelButton: true,
            focusCancel: true,
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler',
            buttonsStyling: false,
            customClass: {
                popup: 'sl-swal-referentiel',
                confirmButton: 'btn btn-danger rounded-pill px-4 fw-semibold shadow-sm',
                cancelButton: 'btn btn-outline-secondary rounded-pill px-3 fw-semibold'
            }
        }).then(function (result) {
            if (result.value === true || result.isConfirmed === true) form.submit();
        });
    }

    $(document).ready(function () {
        var initRows = $('#table-type-categorie-institutions tbody tr');
        if (initRows.length && !initRows.first().find('td[colspan]').length) {
            try {
                $('#table-type-categorie-institutions').DataTable({
                    language: {
                        search: 'Filtrer le tableau :',
                        lengthMenu: 'Afficher _MENU_',
                        zeroRecords: 'Aucune ligne',
                        emptyTable: '—',
                        info: '',
                        infoEmpty: '',
                        infoFiltered: ''
                    },
                    paging: true,
                    pageLength: 25,
                    searching: true,
                    info: true,
                    ordering: true
                });
            } catch (e) {}
        }

        $(document).on('click', '.btn-delete', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var code = $(this).data('code');
            var libelle = this.getAttribute('data-libelle');
            if (code && libelle) confirmDeleteTypeCategorieInstitution(code, libelle);
        });

        $('#addTypeCategorieInstitutionModal').on('show.bs.modal', function () {
            var f = document.getElementById('addTypeCategorieInstitutionForm');
            if (f) f.reset();
        });
    });

    $('#addTypeCategorieInstitutionForm').on('submit', function () {
        var btn = $(this).find('button[type="submit"]')[0];
        if (typeof sifecBtnLoading === 'function') sifecBtnLoading(btn, 'Enregistrement…');
    });
    $(document).on('submit', 'form[id^="editTypeCategorieInstitutionForm"]', function () {
        var btn = $(this).find('button[type="submit"]')[0];
        if (typeof sifecBtnLoading === 'function') sifecBtnLoading(btn, 'Enregistrement…');
    });
</script>
@endsection
