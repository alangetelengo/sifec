@extends('layout.app')
@section('titre')
    Référentiel — Types d’institution
@endsection
@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@include('referentiel::partials.sifec-ref-crud-styles')
@endsection
@section('corps')
@php
    $typeInstitutionsCount = $typeInstitutions ? $typeInstitutions->count() : 0;
@endphp
<div class="sifec-ref-crud-page container-fluid px-0">
    <div class="sl-hero mb-4">
        <div class="row align-items-center g-3 position-relative" style="z-index:1">
            <div class="col-lg">
                <h1><i class="fas fa-building me-2 opacity-90"></i>Types d’institution</h1>
                <p>Libellés rattachés à une catégorie. Recherche et filtre ci-dessous (aperçu : 20 derniers enregistrés).</p>
            </div>
            <div class="col-lg-auto">
                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addTypeInstitutionModal">
                    <i class="fas fa-plus-circle me-1"></i> Nouveau type
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
                        <div class="sl-stat-lbl">Aperçu liste</div>
                        <div class="sl-stat-val">{{ $typeInstitutionsCount }}</div>
                        <div class="small text-muted">Dernières entrées</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card sl-stat">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="sl-stat-icon text-white" style="background:linear-gradient(135deg,#2781d5,#5a9fd4);">
                        <i class="fas fa-filter"></i>
                    </div>
                    <div>
                        <div class="sl-stat-lbl">Recherche</div>
                        <div class="sl-stat-val small fw-normal text-dark pt-1">Filtre serveur</div>
                        <div class="small text-muted">Libellé et catégorie</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card sl-card">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <h5 class="mb-0"><i class="fas fa-table me-2"></i>Résultats</h5>
            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addTypeInstitutionModal" style="border-radius:10px;">
                <i class="fas fa-plus me-1"></i> Ajouter
            </button>
        </div>
        <div class="card-body p-0 p-md-3">
            <form id="form-search-type-institutions" class="row g-3 align-items-end px-3 px-md-0 pt-3 pt-md-0">
                <div class="col-md-5 col-lg-4">
                    <label class="sl-filter-label d-block" for="filter-lib-type-institution">Type d’institution</label>
                    <div class="input-group border rounded-3 overflow-hidden bg-white shadow-sm">
                        <span class="input-group-text bg-white border-0 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control border-0 ps-0" name="lib_type_institution" id="filter-lib-type-institution" placeholder="Rechercher…" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-5 col-lg-4">
                    <label class="sl-filter-label d-block" for="filter-code-type-categorie-ins">Catégorie</label>
                    <select name="code_type_categorie_ins" id="filter-code-type-categorie-ins" class="form-select border rounded-3 shadow-sm">
                        <option value="">Toutes les catégories</option>
                        @foreach ($typeCategorieInstitutions as $categorie)
                            <option value="{{ $categorie->code_type_categorie_ins }}">{{ $categorie->lib_type_categorie_institution }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-auto d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-success px-4" style="border-radius:10px;font-weight:600;">
                        <i class="fas fa-search me-1"></i> Rechercher
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="btn-reset-filters-type-institutions" style="border-radius:10px;">
                        <i class="fas fa-rotate-left me-1"></i> Réinitialiser
                    </button>
                    <span id="count-results" class="sl-result-pill ms-md-2 d-none d-md-inline-flex"></span>
                </div>
            </form>
            <div class="mt-2 d-md-none px-3">
                <span id="count-results-mobile" class="sl-result-pill"></span>
            </div>

            <div class="sl-table-host mx-md-0 px-3 px-md-0 pb-3 pb-md-0">
                <div id="type-institutions-table-loading" class="sl-table-loading-overlay d-none" aria-live="polite" aria-busy="false" hidden>
                    <span class="sifec-spinner" role="status"></span>
                    <span>Recherche en cours…</span>
                </div>
                <div class="table-responsive sl-table-wrap mt-3">
                    <table id="table-type-institutions" class="table table-hover sl-table mb-0 align-middle" style="min-width:640px">
                        <thead>
                            <tr>
                                <th class="sl-row-num">#</th>
                                <th>Type d’institution</th>
                                <th>Catégorie</th>
                                <th class="text-end sl-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-type-institutions">
                            @include('referentiel::type-institution.partials.table-type-institutions', ['typeInstitutions' => $typeInstitutions])
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal ajout --}}
<div class="modal fade" id="addTypeInstitutionModal" tabindex="-1" aria-labelledby="addTypeInstitutionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden;">
            <div class="modal-header sl-modal-header text-white border-0 py-3">
                <h5 class="modal-title" id="addTypeInstitutionModalLabel"><i class="fas fa-plus-circle me-2"></i>Nouveau type d’institution</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form method="POST" action="{{ route('typeInstitution.store') }}" id="addTypeInstitutionForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Libellé <span class="text-danger">*</span></label>
                        <input type="text" name="lib_type_institution" class="form-control form-control-lg @error('lib_type_institution') is-invalid @enderror"
                               value="{{ old('lib_type_institution') }}" placeholder="Ex. CEC principal, Tribunal…" required>
                        @error('lib_type_institution')
                            <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">Catégorie <span class="text-danger">*</span></label>
                        <select name="code_type_categorie_ins" class="form-select form-select-lg @error('code_type_categorie_ins') is-invalid @enderror" required>
                            <option value="">— Sélectionner —</option>
                            @foreach($typeCategorieInstitutions as $categorie)
                                <option value="{{ $categorie->code_type_categorie_ins }}" {{ old('code_type_categorie_ins') == $categorie->code_type_categorie_ins ? 'selected' : '' }}>
                                    {{ $categorie->lib_type_categorie_institution }}
                                </option>
                            @endforeach
                        </select>
                        @error('code_type_categorie_ins')
                            <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
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

@foreach ($typeInstitutionsForModals as $item)
    <div class="modal fade" id="editTypeInstitutionModal{{ $item->code_type_institution }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden;">
                <div class="modal-header sl-modal-header text-white border-0 py-3">
                    <h5 class="modal-title"><i class="fas fa-pen-to-square me-2"></i>{{ $item->lib_type_institution }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form action="{{ route('typeInstitution.update', $item->code_type_institution) }}" method="POST" id="editTypeInstitutionForm{{ $item->code_type_institution }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Libellé <span class="text-danger">*</span></label>
                            <input class="form-control form-control-lg @error('lib_type_institution') is-invalid @enderror" name="lib_type_institution" type="text" value="{{ $item->lib_type_institution }}" required>
                            @error('lib_type_institution')
                                <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">Catégorie <span class="text-danger">*</span></label>
                            <select name="code_type_categorie_ins" class="form-select form-select-lg @error('code_type_categorie_ins') is-invalid @enderror" required>
                                <option value="">— Sélectionner —</option>
                                @foreach($typeCategorieInstitutions as $categorie)
                                    <option value="{{ $categorie->code_type_categorie_ins }}" {{ $item->code_type_categorie_ins == $categorie->code_type_categorie_ins ? 'selected' : '' }}>
                                        {{ $categorie->lib_type_categorie_institution }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_type_categorie_ins')
                                <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
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
    function confirmDeleteTypeInstitution(code, libelle) {
        var form = document.getElementById('deleteForm' + code);
        if (!form) {
            Swal.fire({ title: 'Erreur', text: 'Formulaire de suppression introuvable.', icon: 'error', confirmButtonText: 'OK', customClass: { popup: 'sl-swal-referentiel' } });
            return;
        }
        var libEsc = (typeof sifecHtmlForSwalStrong === 'function')
            ? sifecHtmlForSwalStrong(libelle)
            : String(libelle).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        Swal.fire({
            title: 'Supprimer ce type ?',
            html: 'Le type <strong>' + libEsc + '</strong> sera retiré s’il n’est référencé par aucune institution.',
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

    var tableTypeInstitutions = null;

    function setTypeInstitutionsTableLoading(show) {
        var $el = $('#type-institutions-table-loading');
        if (show) { $el.removeClass('d-none').removeAttr('hidden').attr('aria-busy', 'true'); }
        else { $el.addClass('d-none').attr('aria-busy', 'false').attr('hidden', 'hidden'); }
    }

    function searchTypeInstitutionsServer() {
        $.ajax({
            url: "{{ route('typeInstitution.filter') }}",
            type: 'POST',
            data: $('#form-search-type-institutions').serialize() + '&_token={{ csrf_token() }}',
            dataType: 'json',
            beforeSend: function () {
                setTypeInstitutionsTableLoading(true);
                $('#count-results').text('').addClass('d-none');
                $('#count-results-mobile').text('');
            },
            complete: function () { setTypeInstitutionsTableLoading(false); },
            success: function (response) {
                if (!response || response.success !== true) {
                    flashAlert('Erreur', 'error', (response && response.message) ? response.message : 'Erreur lors de la recherche.');
                    return;
                }
                if ($.fn.DataTable.isDataTable('#table-type-institutions')) {
                    try { tableTypeInstitutions.destroy(); } catch (e) {}
                    tableTypeInstitutions = null;
                }
                var html = (response.html !== undefined && response.html !== null) ? String(response.html) : '';
                $('#tbody-type-institutions').html(html);

                var count = (typeof response.count === 'number') ? response.count : 0;
                var countText = count + ' résultat(s)';
                if (response.limite_atteinte) countText += ' — limite 500, affinez la recherche';
                $('#count-results').text(countText).removeClass('d-none');
                $('#count-results-mobile').text(countText);

                setTimeout(function () {
                    try {
                        var rows = $('#tbody-type-institutions tr');
                        var firstTd = rows.first().find('td').first();
                        var isEmpty = rows.length === 0 || firstTd.attr('colspan') === '4';
                        if (!isEmpty && rows.length > 0) {
                            tableTypeInstitutions = $('#table-type-institutions').DataTable({
                                language: { search: 'Filtrer le tableau :', lengthMenu: 'Afficher _MENU_', zeroRecords: 'Aucune ligne', emptyTable: '—', info: '', infoEmpty: '', infoFiltered: '' },
                                paging: false, searching: true, info: false, ordering: true, destroy: true
                            });
                        }
                    } catch (e) { console.error(e); }
                }, 120);
            },
            error: function (xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Erreur lors de la recherche.';
                flashAlert('Erreur', 'error', msg);
            }
        });
    }

    $(document).ready(function () {
        var initCountText = '{{ $typeInstitutionsCount }} ligne(s) — aperçu';
        $('#count-results').text(initCountText).removeClass('d-none');
        $('#count-results-mobile').text(initCountText);

        var initRows = $('#tbody-type-institutions tr');
        if (initRows.length && initRows.first().find('td').first().attr('colspan') !== '4') {
            try {
                tableTypeInstitutions = $('#table-type-institutions').DataTable({
                    language: { search: 'Filtrer le tableau :', lengthMenu: 'Afficher _MENU_', zeroRecords: 'Aucune ligne', emptyTable: '—', info: '', infoEmpty: '', infoFiltered: '' },
                    paging: false, searching: true, info: false, ordering: true
                });
            } catch (e) {}
        }

        $('#form-search-type-institutions').on('submit', function (e) {
            e.preventDefault();
            searchTypeInstitutionsServer();
        });

        $('#btn-reset-filters-type-institutions').on('click', function () {
            $('#form-search-type-institutions')[0].reset();
            location.reload();
        });

        $(document).on('click', '.btn-delete', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var code = $(this).data('code');
            var libelle = this.getAttribute('data-libelle');
            if (code && libelle) confirmDeleteTypeInstitution(code, libelle);
        });

        $('#addTypeInstitutionModal').on('show.bs.modal', function () {
            var f = document.getElementById('addTypeInstitutionForm');
            if (f) f.reset();
        });
    });

    $('#addTypeInstitutionForm').on('submit', function () {
        var btn = $(this).find('button[type="submit"]')[0];
        if (typeof sifecBtnLoading === 'function') sifecBtnLoading(btn, 'Enregistrement…');
    });
    $(document).on('submit', 'form[id^="editTypeInstitutionForm"]', function () {
        var btn = $(this).find('button[type="submit"]')[0];
        if (typeof sifecBtnLoading === 'function') sifecBtnLoading(btn, 'Enregistrement…');
    });
</script>
@endsection
