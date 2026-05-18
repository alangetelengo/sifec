@extends('layout.app')
@section('titre')
    Référentiel — Professions
@endsection
@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@include('referentiel::partials.sifec-ref-crud-styles')
@endsection
@section('corps')
@php
    $professionsCount = $professions ? $professions->count() : 0;
@endphp
<div class="sifec-ref-crud-page container-fluid px-0">
    <div class="sl-hero mb-4">
        <div class="row align-items-center g-3 position-relative" style="z-index:1">
            <div class="col-lg">
                <h1><i class="fas fa-briefcase me-2 opacity-90"></i>Référentiel des professions</h1>
                <p>Libellés utilisés dans les dossiers et pièces d’état civil. Recherche jusqu’à 500 résultats par requête.</p>
            </div>
            <div class="col-lg-auto">
                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addProfessionModal">
                    <i class="fas fa-plus-circle me-1"></i> Nouvelle profession
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
                        <div class="sl-stat-val">{{ $professionsCount }}</div>
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
            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addProfessionModal" style="border-radius:10px;">
                <i class="fas fa-plus me-1"></i> Ajouter
            </button>
        </div>
        <div class="card-body p-0 p-md-3">
            <form id="form-search-professions" class="row g-3 align-items-end px-3 px-md-0 pt-3 pt-md-0">
                <div class="col-md-6 col-lg-5">
                    <label class="sl-filter-label d-block" for="filter-lib-profession">Libellé</label>
                    <div class="input-group border rounded-3 overflow-hidden bg-white shadow-sm">
                        <span class="input-group-text bg-white border-0 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control border-0 ps-0" name="lib_profession" id="filter-lib-profession" placeholder="Ex. Médecin, Enseignant…" autocomplete="off">
                    </div>
                </div>
                <div class="col-12 col-md-auto d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-success px-4" style="border-radius:10px;font-weight:600;">
                        <i class="fas fa-search me-1"></i> Rechercher
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="btn-reset-filters-professions" style="border-radius:10px;">
                        <i class="fas fa-rotate-left me-1"></i> Réinitialiser
                    </button>
                    <span id="count-results" class="sl-result-pill ms-md-2 d-none d-md-inline-flex"></span>
                </div>
            </form>
            <div class="mt-2 d-md-none px-3">
                <span id="count-results-mobile" class="sl-result-pill"></span>
            </div>

            <div class="sl-table-host mx-md-0 px-3 px-md-0 pb-3 pb-md-0">
                <div id="professions-table-loading" class="sl-table-loading-overlay d-none" aria-live="polite" aria-busy="false" hidden>
                    <span class="sifec-spinner" role="status"></span>
                    <span>Recherche en cours…</span>
                </div>
                <div class="table-responsive sl-table-wrap mt-3">
                    <table id="table-professions" class="table table-hover sl-table mb-0 align-middle" style="min-width:720px">
                        <thead>
                            <tr>
                                <th class="sl-row-num">#</th>
                                <th>Libellé</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-professions">
                            @include('referentiel::profession.partials.table-professions', ['professions' => $professions])
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajout -->
<div class="modal fade" id="addProfessionModal" tabindex="-1" aria-labelledby="addProfessionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden;">
            <div class="modal-header sl-modal-header text-white border-0 py-3">
                <h5 class="modal-title" id="addProfessionModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Nouvelle profession
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form method="POST" action="{{ route('profession.store') }}" id="addProfessionForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Libellé de la profession <span class="text-danger">*</span></label>
                        <input type="text" name="lib_profession" class="form-control form-control-lg @error('lib_profession') is-invalid @enderror"
                               value="{{ old('lib_profession') }}" placeholder="Ex. Médecin, Enseignant…" required>
                        @error('lib_profession')
                            <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted"><i class="fas fa-info-circle me-1"></i>Nom affiché dans les formulaires et actes.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-semibold">
                        <i class="fas fa-check me-1"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editProfessionModal" tabindex="-1" aria-labelledby="editProfessionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden;">
            <div class="modal-header sl-modal-header text-white border-0 py-3">
                <h5 class="modal-title" id="editProfessionModalLabel">
                    <i class="fas fa-pen-to-square me-2"></i><span id="editProfessionModalTitle">Modifier une profession</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="#" method="POST" id="editProfessionForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Libellé de la profession <span class="text-danger">*</span></label>
                        <input class="form-control form-control-lg @error('lib_profession') is-invalid @enderror"
                               name="lib_profession" id="edit-lib-profession" type="text" value="" required>
                        @error('lib_profession')
                            <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-semibold">
                        <i class="fas fa-check me-1"></i>Mettre à jour
                    </button>
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
    function confirmDeleteProfession(code, libelle) {
        var formId = 'deleteForm' + code;
        var form = document.getElementById(formId);
        if (!form) {
            Swal.fire({
                title: 'Erreur',
                text: 'Formulaire de suppression introuvable.',
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: { popup: 'sl-swal-referentiel' }
            });
            return;
        }
        var libEsc = (typeof sifecHtmlForSwalStrong === 'function')
            ? sifecHtmlForSwalStrong(libelle)
            : String(libelle).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        Swal.fire({
            title: 'Supprimer cette profession ?',
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
            if (result.value === true || result.isConfirmed === true) {
                form.submit();
            }
        });
    }

    var tableProfessions = null;

    function setProfessionsTableLoading(show) {
        var $el = $('#professions-table-loading');
        if (show) {
            $el.removeClass('d-none').removeAttr('hidden').attr('aria-busy', 'true');
        } else {
            $el.addClass('d-none').attr('aria-busy', 'false').attr('hidden', 'hidden');
        }
    }

    function searchProfessionsServer() {
        var formData = $('#form-search-professions').serialize();
        formData += '&_token={{ csrf_token() }}';

        $.ajax({
            url: "{{ route('profession.filter') }}",
            type: 'POST',
            data: formData,
            beforeSend: function () {
                setProfessionsTableLoading(true);
                $('#count-results').text('').addClass('d-none');
                $('#count-results-mobile').text('');
            },
            complete: function () {
                setProfessionsTableLoading(false);
            },
            success: function (response) {
                try {
                    if (response.code === '200') {
                        if ($.fn.DataTable.isDataTable('#table-professions')) {
                            try {
                                tableProfessions.destroy();
                            } catch (e) { /* */ }
                            tableProfessions = null;
                        }
                        $('#tbody-professions').empty().html(response.data);

                        var countText = response.count + ' résultat(s)';
                        if (response.limite_atteinte) {
                            countText += ' — limite 500, affinez la recherche';
                        }
                        $('#count-results').text(countText).removeClass('d-none');
                        $('#count-results-mobile').text(countText);

                        setTimeout(function () {
                            try {
                                var rows = $('#tbody-professions tr');
                                var firstTd = rows.first().find('td').first();
                                var isEmptyState = rows.length === 0 || firstTd.hasClass('sl-empty') || firstTd.attr('colspan') === '3';

                                if (!isEmptyState && rows.length > 0) {
                                    tableProfessions = $('#table-professions').DataTable({
                                        language: {
                                            search: 'Filtrer le tableau :',
                                            lengthMenu: 'Afficher _MENU_',
                                            zeroRecords: 'Aucune ligne',
                                            emptyTable: '—',
                                            info: '',
                                            infoEmpty: '',
                                            infoFiltered: ''
                                        },
                                        paging: false,
                                        searching: true,
                                        info: false,
                                        ordering: true,
                                        destroy: true
                                    });
                                } else {
                                    tableProfessions = null;
                                }
                            } catch (e) {
                                console.error(e);
                            }
                        }, 100);
                    } else {
                        flashAlert('Erreur', 'error', response.message || 'Une erreur est survenue.');
                    }
                } catch (e) {
                    console.error(e);
                    flashAlert('Erreur', 'error', 'Erreur lors du traitement de la réponse.');
                }
            },
            error: function (xhr) {
                var msg = 'Erreur lors de la recherche.';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                else if (xhr.responseJSON && xhr.responseJSON.error) msg = xhr.responseJSON.error;
                flashAlert('Erreur', 'error', msg);
            }
        });
    }

    $(document).ready(function () {
        $('#count-results').text('{{ $professionsCount }} ligne(s) — aperçu').removeClass('d-none');
        $('#count-results-mobile').text('{{ $professionsCount }} ligne(s)');

        var initRows = $('#tbody-professions tr');
        if (initRows.length && !initRows.first().find('td.sl-empty').length) {
            try {
                if (!$.fn.DataTable.isDataTable('#table-professions')) {
                    tableProfessions = $('#table-professions').DataTable({
                        language: {
                            search: 'Filtrer le tableau :',
                            lengthMenu: 'Afficher _MENU_',
                            zeroRecords: 'Aucune ligne',
                            emptyTable: '—',
                            info: '',
                            infoEmpty: '',
                            infoFiltered: ''
                        },
                        paging: false,
                        searching: true,
                        info: false,
                        ordering: true
                    });
                }
            } catch (e) { /* */ }
        }

        $('#form-search-professions').on('submit', function (e) {
            e.preventDefault();
            searchProfessionsServer();
        });

        $('#btn-reset-filters-professions').on('click', function () {
            $('#form-search-professions')[0].reset();
            location.reload();
        });

        $(document).on('click', '.btn-delete', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var code = $(this).data('code');
            var libelle = this.getAttribute('data-libelle');
            if (code && libelle) {
                confirmDeleteProfession(code, libelle);
            }
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

            $('#editProfessionForm').attr('action', updateUrl);
            $('#edit-lib-profession').val(libelle);
            $('#editProfessionModalTitle').text('Modifier — ' + libelle);

            var modalEl = document.getElementById('editProfessionModal');
            if (!modalEl) return;
            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        });

        $('#addProfessionModal').on('show.bs.modal', function () {
            $('#addProfessionForm')[0].reset();
        });
        $('#editProfessionModal').on('hidden.bs.modal', function () {
            $('#editProfessionForm').attr('action', '#');
            $('#edit-lib-profession').val('');
            $('#editProfessionModalTitle').text('Modifier une profession');
        });
    });

    $('#addProfessionForm').on('submit', function () {
        var btn = $(this).find('button[type="submit"]')[0];
        if (typeof sifecBtnLoading === 'function') {
            sifecBtnLoading(btn, 'Enregistrement…');
        }
    });
    $(document).on('submit', '#editProfessionForm', function () {
        var btn = $(this).find('button[type="submit"]')[0];
        if (typeof sifecBtnLoading === 'function') {
            sifecBtnLoading(btn, 'Enregistrement…');
        }
    });
</script>
@endsection
