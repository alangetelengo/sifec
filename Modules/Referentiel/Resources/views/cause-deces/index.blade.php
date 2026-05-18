@extends('layout.app')
@section('titre')
    Référentiel — Causes de décès
@endsection
@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@include('referentiel::partials.sifec-ref-crud-styles')
@endsection
@section('corps')
@php
    $causeDecesCount = $causeDeces ? $causeDeces->count() : 0;
@endphp
<div class="sifec-ref-crud-page container-fluid px-0">
    <div class="sl-hero mb-4">
        <div class="row align-items-center g-3 position-relative" style="z-index:1">
            <div class="col-lg">
                <h1><i class="fas fa-cross me-2 opacity-90"></i>Référentiel des causes de décès</h1>
                <p>Libellés utilisés dans les déclarations et actes de décès. Recherche jusqu’à 500 résultats par requête.</p>
            </div>
            <div class="col-lg-auto">
                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addCauseDecesModal">
                    <i class="fas fa-plus-circle me-1"></i> Nouvelle cause
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
                        <div class="sl-stat-lbl">Affichage initial</div>
                        <div class="sl-stat-val">{{ $causeDecesCount }}</div>
                        <div class="small text-muted">Dernières entrées</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card sl-stat">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="sl-stat-icon text-white" style="background:linear-gradient(135deg,#2781d5,#5a9fd4);">
                        <i class="fas fa-database"></i>
                    </div>
                    <div>
                        <div class="sl-stat-lbl">Référentiel</div>
                        <div class="sl-stat-val small fw-normal text-dark pt-1">Données partagées</div>
                        <div class="small text-muted">Même base pour tout SIFEC</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card sl-card">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <h5 class="mb-0"><i class="fas fa-table me-2"></i>Résultats</h5>
            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addCauseDecesModal" style="border-radius:10px;">
                <i class="fas fa-plus me-1"></i> Ajouter
            </button>
        </div>
        <div class="card-body p-0 p-md-3">
            <form id="form-search-cause-deces" class="row g-3 align-items-end px-3 px-md-0 pt-3 pt-md-0">
                <div class="col-md-6 col-lg-5">
                    <label class="sl-filter-label d-block" for="filter-lib-cause-deces">Libellé</label>
                    <div class="input-group border rounded-3 overflow-hidden bg-white shadow-sm">
                        <span class="input-group-text bg-white border-0 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control border-0 ps-0" name="lib_cause_deces" id="filter-lib-cause-deces" placeholder="Ex. Maladie, accident…" autocomplete="off">
                    </div>
                </div>
                <div class="col-12 col-md-auto d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-success px-4" style="border-radius:10px;font-weight:600;">
                        <i class="fas fa-search me-1"></i> Rechercher
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="btn-reset-filters-cause-deces" style="border-radius:10px;">
                        <i class="fas fa-rotate-left me-1"></i> Réinitialiser
                    </button>
                    <span id="count-results" class="sl-result-pill ms-md-2 d-none d-md-inline-flex"></span>
                </div>
            </form>
            <div class="mt-2 d-md-none px-3">
                <span id="count-results-mobile" class="sl-result-pill"></span>
            </div>

            <div class="sl-table-host mx-md-0 px-3 px-md-0 pb-3 pb-md-0">
                <div id="cause-deces-table-loading" class="sl-table-loading-overlay d-none" aria-live="polite" aria-busy="false" hidden>
                    <span class="sifec-spinner" role="status"></span>
                    <span>Recherche en cours…</span>
                </div>
                <div class="table-responsive sl-table-wrap mt-3">
                    <table id="table-cause-deces" class="table table-hover sl-table mb-0 align-middle" style="min-width:720px">
                        <thead>
                            <tr>
                                <th class="sl-row-num">#</th>
                                <th>Libellé</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-cause-deces">
                            @include('referentiel::cause-deces.partials.table-cause-deces', ['causeDeces' => $causeDeces])
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addCauseDecesModal" tabindex="-1" aria-labelledby="addCauseDecesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden;">
            <div class="modal-header sl-modal-header text-white border-0 py-3">
                <h5 class="modal-title" id="addCauseDecesModalLabel"><i class="fas fa-plus-circle me-2"></i>Nouvelle cause de décès</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form method="POST" action="{{ route('causedeces.store') }}" id="addCauseDecesForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Libellé <span class="text-danger">*</span></label>
                        <input type="text" name="lib_cause_deces" class="form-control form-control-lg @error('lib_cause_deces') is-invalid @enderror"
                               value="{{ old('lib_cause_deces') }}" placeholder="Ex. Maladie, accident…" required>
                        @error('lib_cause_deces')
                            <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted"><i class="fas fa-info-circle me-1"></i>Nom affiché dans les formulaires de décès.</small>
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

<div class="modal fade" id="editCauseDecesModal" tabindex="-1" aria-labelledby="editCauseDecesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden;">
            <div class="modal-header sl-modal-header text-white border-0 py-3">
                <h5 class="modal-title" id="editCauseDecesModalLabel"><i class="fas fa-pen-to-square me-2"></i><span id="editCauseDecesModalTitle">Modifier une cause de décès</span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="#" method="POST" id="editCauseDecesForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Libellé <span class="text-danger">*</span></label>
                        <input
                            class="form-control form-control-lg @error('lib_cause_deces') is-invalid @enderror"
                            name="lib_cause_deces"
                            id="edit-lib-cause-deces"
                            type="text"
                            value=""
                            required
                        >
                        @error('lib_cause_deces')
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
@endsection
@section('scripts')
<script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>
<script>
    function confirmDeleteCauseDeces(code, libelle) {
        var form = document.getElementById('deleteForm' + code);
        if (!form) {
            Swal.fire({ title: 'Erreur', text: 'Formulaire de suppression introuvable.', icon: 'error', confirmButtonText: 'OK', customClass: { popup: 'sl-swal-referentiel' } });
            return;
        }
        var libEsc = (typeof sifecHtmlForSwalStrong === 'function')
            ? sifecHtmlForSwalStrong(libelle)
            : String(libelle).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        Swal.fire({
            title: 'Supprimer cette cause de décès ?',
            html: 'La ligne <strong>' + libEsc + '</strong> sera retirée de la liste (suppression logique).',
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

    var tableCauseDeces = null;

    function setCauseDecesTableLoading(show) {
        var $el = $('#cause-deces-table-loading');
        if (show) { $el.removeClass('d-none').removeAttr('hidden').attr('aria-busy', 'true'); }
        else { $el.addClass('d-none').attr('aria-busy', 'false').attr('hidden', 'hidden'); }
    }

    function searchCauseDecesServer() {
        $.ajax({
            url: "{{ route('causedeces.filter') }}",
            type: 'POST',
            data: $('#form-search-cause-deces').serialize() + '&_token={{ csrf_token() }}',
            beforeSend: function () {
                setCauseDecesTableLoading(true);
                $('#count-results').text('').addClass('d-none');
                $('#count-results-mobile').text('');
            },
            complete: function () { setCauseDecesTableLoading(false); },
            success: function (response) {
                if (response.code !== '200') {
                    flashAlert('Erreur', 'error', response.message || 'Une erreur est survenue.');
                    return;
                }
                if ($.fn.DataTable.isDataTable('#table-cause-deces')) {
                    try { tableCauseDeces.destroy(); } catch (e) {}
                    tableCauseDeces = null;
                }
                $('#tbody-cause-deces').empty().html(response.data);
                var countText = response.count + ' résultat(s)';
                if (response.limite_atteinte) countText += ' — limite 500, affinez la recherche';
                $('#count-results').text(countText).removeClass('d-none');
                $('#count-results-mobile').text(countText);

                setTimeout(function () {
                    try {
                        var rows = $('#tbody-cause-deces tr');
                        var firstTd = rows.first().find('td').first();
                        var isEmpty = rows.length === 0 || firstTd.hasClass('sl-empty') || firstTd.attr('colspan') === '3';
                        if (!isEmpty && rows.length > 0) {
                            tableCauseDeces = $('#table-cause-deces').DataTable({
                                language: { search: 'Filtrer le tableau :', lengthMenu: 'Afficher _MENU_', zeroRecords: 'Aucune ligne', emptyTable: '—', info: '', infoEmpty: '', infoFiltered: '' },
                                paging: false, searching: true, info: false, ordering: true, destroy: true
                            });
                        } else tableCauseDeces = null;
                    } catch (e) { console.error(e); }
                }, 100);
            },
            error: function (xhr) {
                var msg = 'Erreur lors de la recherche des causes de décès.';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                else if (xhr.responseJSON && xhr.responseJSON.error) msg = xhr.responseJSON.error;
                flashAlert('Erreur', 'error', msg);
            }
        });
    }

    $(document).ready(function () {
        $('#count-results').text('{{ $causeDecesCount }} ligne(s) — aperçu').removeClass('d-none');
        $('#count-results-mobile').text('{{ $causeDecesCount }} ligne(s)');

        var initRows = $('#tbody-cause-deces tr');
        if (initRows.length && !initRows.first().find('td.sl-empty').length) {
            try {
                if (!$.fn.DataTable.isDataTable('#table-cause-deces')) {
                    tableCauseDeces = $('#table-cause-deces').DataTable({
                        language: { search: 'Filtrer le tableau :', lengthMenu: 'Afficher _MENU_', zeroRecords: 'Aucune ligne', emptyTable: '—', info: '', infoEmpty: '', infoFiltered: '' },
                        paging: false, searching: true, info: false, ordering: true
                    });
                }
            } catch (e) {}
        }

        $('#form-search-cause-deces').on('submit', function (e) { e.preventDefault(); searchCauseDecesServer(); });
        $('#btn-reset-filters-cause-deces').on('click', function () {
            $('#form-search-cause-deces')[0].reset();
            location.reload();
        });

        $(document).on('click', '.btn-delete', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var code = $(this).data('code');
            var libelle = this.getAttribute('data-libelle');
            if (code && libelle) confirmDeleteCauseDeces(code, libelle);
        });
        $(document).on('click', '.btn-edit', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var libelle = this.getAttribute('data-libelle') || '';
            var updateUrl = this.getAttribute('data-update-url') || '';
            if (!updateUrl) {
                flashAlert('Erreur', 'error', 'Impossible d’ouvrir le formulaire de modification.');
                return;
            }

            $('#editCauseDecesForm').attr('action', updateUrl);
            $('#edit-lib-cause-deces').val(libelle);
            $('#editCauseDecesModalTitle').text('Modifier — ' + libelle);

            var modalEl = document.getElementById('editCauseDecesModal');
            if (!modalEl) return;
            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        });

        $('#addCauseDecesModal').on('show.bs.modal', function () { $('#addCauseDecesForm')[0].reset(); });
        $('#editCauseDecesModal').on('hidden.bs.modal', function () {
            $('#editCauseDecesForm').attr('action', '#');
            $('#edit-lib-cause-deces').val('');
            $('#editCauseDecesModalTitle').text('Modifier une cause de décès');
        });
    });

    $('#addCauseDecesForm').on('submit', function () {
        var btn = $(this).find('button[type="submit"]')[0];
        if (typeof sifecBtnLoading === 'function') sifecBtnLoading(btn, 'Enregistrement…');
    });
    $(document).on('submit', '#editCauseDecesForm', function () {
        var btn = $(this).find('button[type="submit"]')[0];
        if (typeof sifecBtnLoading === 'function') sifecBtnLoading(btn, 'Enregistrement…');
    });
</script>
@endsection
