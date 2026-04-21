@extends('layout.app')
@section('titre')
   Gestion des Institutions
@endsection
@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@include('referentiel::partials.sifec-ref-crud-styles')
@endsection
@section('corps')
@php
    $institutionsCount = $institutions ? $institutions->count() : 0;
@endphp
<div class="sifec-ref-crud-page container-fluid px-0">
    <div class="sl-hero mb-4">
        <div class="row align-items-center g-3 position-relative" style="z-index:1">
            <div class="col-lg">
                <h1><i class="fas fa-university me-2 opacity-90"></i>Institutions</h1>
                <p>Gestion des centres et structures du référentiel. Recherche côté serveur (jusqu’à 500 résultats).</p>
            </div>
            <div class="col-lg-auto">
                <a href="{{ route('institution.create') }}" class="btn btn-light">
                    <i class="fas fa-plus-circle me-1"></i> Nouvelle institution
                </a>
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
                        <div class="sl-stat-val">{{ $institutionsCount }}</div>
                        <div class="small text-muted">20 derniers enregistrements</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card sl-card">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <h5 class="mb-0"><i class="fas fa-table me-2"></i>Liste des institutions</h5>
            <a href="{{ route('institution.create') }}" class="btn btn-sm btn-success" style="border-radius:10px;">
                <i class="fas fa-plus me-1"></i> Ajouter
            </a>
        </div>
        <div class="card-body p-0 p-md-3">
            <form id="form-search-institutions" class="row g-3 align-items-end px-3 px-md-0 pt-3 pt-md-0">
                <div class="col-md-4 col-lg-3">
                    <label class="sl-filter-label d-block" for="filter-lib-institution">Libellé</label>
                    <div class="input-group border rounded-3 overflow-hidden bg-white shadow-sm">
                        <span class="input-group-text bg-white border-0 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control border-0 ps-0" name="lib_institution" id="filter-lib-institution" placeholder="Rechercher…" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <label class="sl-filter-label d-block" for="filter-code-type-institution">Type</label>
                    <select name="code_type_institution" id="filter-code-type-institution" class="form-select border rounded-3 shadow-sm">
                        <option value="">Tous les types</option>
                        @foreach ($typeInstitutions as $type)
                            <option value="{{ $type->code_type_institution }}">{{ $type->lib_type_institution }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-lg-3">
                    <label class="sl-filter-label d-block" for="filter-code-localite">Localité</label>
                    <select name="code_localite" id="filter-code-localite" class="form-select border rounded-3 shadow-sm">
                        <option value="">Toutes les localités</option>
                        @foreach ($localites as $localite)
                            <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-success px-4" style="border-radius:10px;font-weight:600;">
                        <i class="fas fa-search me-1"></i> Rechercher
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="btn-reset-filters-institutions" style="border-radius:10px;">
                        <i class="fas fa-rotate-left me-1"></i> Réinitialiser
                    </button>
                    <span id="count-results" class="sl-result-pill"></span>
                </div>
            </form>

            <div class="sl-table-host mx-md-0 px-3 px-md-0 pb-3 pb-md-0 mt-3">
                <div id="institutions-table-loading" class="sl-table-loading-overlay d-none" aria-live="polite" aria-busy="false" hidden>
                    <span class="sifec-spinner" role="status"></span>
                    <span>Recherche en cours…</span>
                </div>
                <div class="table-responsive sl-table-wrap">
                    <table id="table-institutions" class="table table-hover sl-table mb-0 align-middle" style="min-width:960px">
                        <thead>
                            <tr>
                                <th class="sl-row-num">#</th>
                                <th>Institution</th>
                                <th>Parent</th>
                                <th>Type</th>
                                <th>Lieu</th>
                                <th>Sceau</th>
                                <th>Statut</th>
                                <th class="text-end sl-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-institutions">
                            @if($institutionsCount > 0)
                                @include('referentiel::institution.partials.table-institutions', ['institutions' => $institutions])
                            @else
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="sl-empty-icon mx-auto mb-2"><i class="fas fa-inbox"></i></div>
                                        <p class="text-muted mb-0">Aucune institution dans l’aperçu.</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals de modification (dynamiques) -->
    @foreach ($institutions as $institution)
        @if($institution->code_type_institution != "TPINS_0004" && $institution->code_type_institution != "TPINS_0001")
            <div class="modal fade" id="editInstitutionModal{{ $institution->code_institution }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="editInstitutionModalLabel{{ $institution->code_institution }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-info text-white">
                            <h5 class="modal-title" id="editInstitutionModalLabel{{ $institution->code_institution }}">
                                <i class="fas fa-edit me-2"></i>Modifier {{ $institution->lib_institution }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('institution.update',$institution->code_institution) }}" method="POST" enctype="multipart/form-data" id="editInstitutionForm{{ $institution->code_institution }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="row">
                                    <!-- 1. Libellé de l'institution -->
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label fw-bold">Libellé de l'institution <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-lg @error('lib_institution') is-invalid @enderror" 
                                               value="{{ $institution->lib_institution }}" 
                                               name="lib_institution" required>
                                        @error('lib_institution')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- 2. Type d'institution -->
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label fw-bold">Type d'institution <span class="text-danger">*</span></label>
                                        <select name="code_type_institution" required class="form-control form-control-lg @error('code_type_institution') is-invalid @enderror">
                                            @foreach ($typeInstitutions as $item)
                                                <option value="{{ $item->code_type_institution }}" {{$item->code_type_institution == $institution->code_type_institution ? "selected":""}}>
                                                    {{ $item->lib_type_institution }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('code_type_institution')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- 3. Rattachement à une institution parent -->
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label fw-bold">Rattacher à une institution parent ?</label>
                                        <div class="d-flex gap-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="newrattacher" id="newrattacherOui{{ $institution->code_institution }}" 
                                                       value="OUI" {{ $institution->code_institution_parent != null ? "checked" : "" }}>
                                                <label class="form-check-label fw-bold" for="newrattacherOui{{ $institution->code_institution }}">
                                                    <i class="fas fa-check-circle me-1 text-success"></i>OUI
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="newrattacher" id="newrattacherNon{{ $institution->code_institution }}" 
                                                       value="NON" {{ $institution->code_institution_parent == null ? "checked" : "" }}>
                                                <label class="form-check-label fw-bold" for="newrattacherNon{{ $institution->code_institution }}">
                                                    <i class="fas fa-times-circle me-1 text-danger"></i>NON
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 4. Institution parent (conditionnel) -->
                                    @if($institution->code_institution_parent != null)
                                        <div class="mb-3 col-md-12" id="editInstitutionParent{{ $institution->code_institution }}">
                                            <label class="form-label fw-bold">Institution parent</label>
                                            <select name="code_institution_parent" class="form-control form-control-lg oldparent">
                                                <option value="">Aucune</option>
                                                @foreach ($institutions as $item)
                                                    @if($item->code_institution != $institution->code_institution)
                                                        <option value="{{ $item->code_institution }}" {{$item->code_institution == $institution->code_institution_parent ? "selected":""}}>
                                                            {{ $item->lib_institution }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        <div class="mb-3 col-md-12 institutionRattache d-none" id="editInstitutionParent{{ $institution->code_institution }}">
                                            <label class="form-label fw-bold">Institution parent</label>
                                            <select name="code_institution_parent" class="form-control form-control-lg newparent">
                                                <option value="">Aucune</option>
                                                @foreach ($institutions as $item)
                                                    @if($item->code_institution != $institution->code_institution)
                                                        <option value="{{ $item->code_institution }}">{{ $item->lib_institution }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <!-- 5. Localité -->
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label fw-bold">Localité <span class="text-danger">*</span></label>
                                        <select name="code_localite" required class="form-control form-control-lg @error('code_localite') is-invalid @enderror">
                                            @foreach ($localites as $item)
                                                <option value="{{ $item->code_localite }}" 
                                                        {{ $institution->lieu != null ? $item->code_localite == $institution->lieu->code_localite ? "selected" : "" : "" }}>
                                                    {{ $item->lib_localite }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('code_localite')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- 6. Sceau -->
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label fw-bold">Sceau</label>
                                        @if($institution->sceau != null)
                                            <div class="mb-2">
                                                <img src='{{ asset("app/".$institution->sceau) }}' alt="Sceau actuel" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                                                <p class="text-muted small mb-0">Sceau actuel</p>
                                            </div>
                                        @endif
                                        <input type="file" class="form-control form-control-lg" name="sceau" accept="image/*">
                                        <small class="form-text text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Laissez vide pour conserver l'image actuelle
                                        </small>
                                    </div>

                                    <!-- 7. Statut -->
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label fw-bold">Statut <span class="text-danger">*</span></label>
                                        <select name="statut" class="form-control form-control-lg @error('statut') is-invalid @enderror" required>
                                            <option value="1" {{"1"==$institution->statut ? "selected":""}}>Actif</option>
                                            <option value="0" {{"0"==$institution->statut ? "selected":""}}>Inactif</option>
                                        </select>
                                        @error('statut')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i>Annuler
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>
@endsection
@section('scripts')
<script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script>
    $(document).ready(function() {
        var tableInstitutions = null;

        function setInstitutionsTableLoading(show) {
            var $el = $('#institutions-table-loading');
            if (show) { $el.removeClass('d-none').removeAttr('hidden').attr('aria-busy', 'true'); }
            else { $el.addClass('d-none').attr('aria-busy', 'false').attr('hidden', 'hidden'); }
        }

        $('#count-results').text('{{ $institutionsCount }} institution(s) — aperçu');

        (function initInstitutionsDataTableIfNeeded() {
            var initRows = $('#tbody-institutions tr');
            if (initRows.length && initRows.first().find('td').first().attr('colspan') !== '8') {
                try {
                    tableInstitutions = $('#table-institutions').DataTable({
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
                } catch (e) {}
            }
        })();

        function searchInstitutionsServer() {
            var formData = $('#form-search-institutions').serialize();
            formData += '&_token={{ csrf_token() }}';

            $.ajax({
                url: "{{ route('institution.filter') }}",
                type: 'POST',
                data: formData,
                dataType: 'json',
                beforeSend: function() {
                    setInstitutionsTableLoading(true);
                    $('#count-results').text('');
                },
                complete: function() {
                    setInstitutionsTableLoading(false);
                },
                success: function(response) {
                    var $tbody = $('#tbody-institutions');
                    if (!$tbody.length) {
                        if (typeof flashAlert === 'function') flashAlert("Erreur", "error", "Tableau introuvable");
                        return;
                    }
                    try {
                        if (typeof response === 'string') {
                            try { response = JSON.parse(response); } catch (parseErr) {
                                if (typeof flashAlert === 'function') flashAlert("Erreur", "error", "Réponse serveur invalide");
                                $tbody.html('<tr><td colspan="8" class="text-center text-danger">Réponse serveur invalide</td></tr>');
                                return;
                            }
                        }
                        if (!response || response.success !== true) {
                            if (typeof flashAlert === 'function') flashAlert("Erreur", "error", (response && response.message) ? response.message : "Une erreur est survenue lors de la recherche");
                            return;
                        }
                        var html = (response.html != null && response.html !== undefined) ? String(response.html) : '';
                        var count = (typeof response.count === 'number') ? response.count : 0;

                        if ($.fn.DataTable.isDataTable('#table-institutions')) {
                            try { tableInstitutions.destroy(); } catch (e) { }
                            tableInstitutions = null;
                        }

                        $tbody.empty();
                        try {
                            var tbodyEl = $tbody.get(0);
                            if (tbodyEl && tbodyEl.insertAdjacentHTML) {
                                tbodyEl.insertAdjacentHTML('beforeend', html);
                            } else {
                                $tbody.html(html);
                            }
                        } catch (injErr) {
                            console.error('Injection HTML:', injErr);
                            try { $tbody.html(html); } catch (e2) {
                                $tbody.html('<tr><td colspan="8" class="text-center text-danger">Impossible d\'afficher les résultats</td></tr>');
                            }
                        }

                        var countText = count + ' résultat(s) trouvé(s)';
                        if (response.limite_atteinte) countText += ' (limite de 500 atteinte, affinez vos critères)';
                        $('#count-results').text(countText);

                        setTimeout(function() {
                            try {
                                var rows = $tbody.find('tr');
                                var firstTd = rows.first().find('td').first();
                                var isEmpty = rows.length === 0 || firstTd.attr('colspan') === '8';
                                if (!isEmpty && rows.length > 0) {
                                    tableInstitutions = $('#table-institutions').DataTable({
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
                                }
                            } catch (dtErr) {
                                console.error('DataTables:', dtErr);
                            }
                        }, 150);
                    } catch (e) {
                        console.error('Traitement réponse:', e);
                        if (typeof flashAlert === 'function') flashAlert("Erreur", "error", "Erreur lors du traitement de la réponse");
                        $tbody.html('<tr><td colspan="8" class="text-center text-danger">Erreur lors du traitement des résultats. Réessayez.</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erreur AJAX:', error);
                    var errorMessage = "Erreur lors de la recherche des institutions";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    flashAlert("Erreur", "error", errorMessage);
                }
            });
        }

        // Soumission du formulaire de recherche
        $('#form-search-institutions').on('submit', function(e) {
            e.preventDefault();
            searchInstitutionsServer();
        });

        // Réinitialiser les filtres
        $('#btn-reset-filters-institutions').on('click', function() {
            $('#form-search-institutions')[0].reset();
            location.reload();
        });

        function confirmDelete(code, libelle) {
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
                title: 'Supprimer cette institution ?',
                html: 'L’institution <strong>' + libEsc + '</strong> sera retirée de la liste (suppression logique).',
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

        // Gestion de la suppression avec confirmation
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var button = $(this);
            var code = button.data('code');
            var libelle = button[0] && button[0].getAttribute ? button[0].getAttribute('data-libelle') : button.data('libelle');

            if (code && libelle) {
                confirmDelete(code, libelle);
            } else {
                Swal.fire({
                    title: 'Erreur',
                    text: 'Données manquantes pour la suppression.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    customClass: { popup: 'sl-swal-referentiel' }
                });
            }
        });

        // Fonction pour charger les parents disponibles (excluant l'institution et ses descendants)
        function loadEditAvailableParents(selectElement, institutionId, currentParentValue) {
            if (!institutionId) {
                $(selectElement).html('<option value="">Erreur: ID manquant</option>');
                return;
            }

            var url = "{{ route('institution.available.parents', ':id') }}".replace(':id', institutionId);
            $(selectElement).html('<option value="">Chargement...</option>');
            $(selectElement).prop('disabled', true);

            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    var options = '<option value="">Aucune</option>';
                    if (data && data.length > 0) {
                        $.each(data, function(index, parent) {
                            var typeLabel = parent.type_institution ? ' (' + parent.type_institution.lib_type_institution + ')' : '';
                            var selected = (currentParentValue && currentParentValue === parent.code_institution) ? 'selected' : '';
                            options += '<option value="' + parent.code_institution + '" ' + selected + '>' + parent.lib_institution + typeLabel + '</option>';
                        });
                    }
                    $(selectElement).html(options);
                    $(selectElement).prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    console.error('Erreur lors du chargement des parents disponibles:', error);
                    $(selectElement).html('<option value="">Erreur lors du chargement</option>');
                    $(selectElement).prop('disabled', false);
                }
            });
        }

        // Scripts pour les modals de modification
        @foreach ($institutions as $institution)
            @if($institution->code_type_institution != "TPINS_0004" && $institution->code_type_institution != "TPINS_0001")
                (function() {
                    var modalId = '#editInstitutionModal{{ $institution->code_institution }}';
                    var institutionId = '{{ $institution->code_institution }}';
                    var currentParent = '{{ $institution->code_institution_parent }}';
                    var parentSelect = modalId + ' select[name="code_institution_parent"]';

                    // Charger les parents disponibles lors de l'ouverture du modal
                    $(modalId).on('show.bs.modal', function() {
                        if ($(modalId + ' input[name="newrattacher"]:checked').val() === 'OUI') {
                            loadEditAvailableParents($(parentSelect), institutionId, currentParent);
                        }
                    });

                    $(modalId + ' input[name="newrattacher"]').change(function(){
                        var selectedValue = $(this).val();
                        var parentDiv = $('#editInstitutionParent{{ $institution->code_institution }}');
                        
                        if(selectedValue == "OUI"){
                            parentDiv.removeClass("d-none");
                            parentDiv.find('select').prop('disabled', false);
                            // Charger les parents disponibles
                            loadEditAvailableParents($(parentSelect), institutionId, currentParent);
                        }
                        if(selectedValue == "NON"){
                            parentDiv.addClass("d-none");
                            parentDiv.find('select').prop('disabled', true).val('');
                        }
                    });
                })();
            @endif
        @endforeach
    });

    $(document).on('submit', 'form[id^="editInstitutionForm"]', function () {
        var btn = $(this).find('button[type="submit"]')[0];
        if (typeof sifecBtnLoading === 'function') sifecBtnLoading(btn, 'Enregistrement…');
    });
</script>
@endsection
