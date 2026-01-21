@extends('layout.app')
@section('titre')
   Gestion des Institutions
@endsection
@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endsection
@section('corps')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0"><i class="fas fa-building me-2"></i>Gestion des Institutions</h4>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCEC">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter une institution
                </button>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Liste des institutions</h5>
                </div>
                <div class="card-body">
                    <!-- Formulaire de filtre -->
                    <form id="form-search-institutions">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Libellé de l'institution</label>
                                <input type="text" class="form-control" name="lib_institution" id="filter-lib-institution" placeholder="Rechercher...">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Type d'institution</label>
                                <select name="code_type_institution" id="filter-code-type-institution" class="form-control">
                                    <option value="">Tous les types</option>
                                    @foreach ($typeInstitutions as $type)
                                        <option value="{{ $type->code_type_institution }}">{{ $type->lib_type_institution }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Localité</label>
                                <select name="code_localite" id="filter-code-localite" class="form-control">
                                    <option value="">Toutes les localités</option>
                                    @foreach ($localites as $localite)
                                        <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i> Rechercher
                                </button>
                                <button type="button" class="btn btn-secondary" id="btn-reset-filters-institutions">
                                    <i class="fas fa-redo me-1"></i> Réinitialiser
                                </button>
                                <span id="count-results" class="ms-3 text-muted"></span>
                            </div>
                        </div>
                    </form>

                    <!-- Tableau des institutions -->
                    <div class="table-responsive mt-4">
                        <table id="table-institutions" class="display table table-hover" style="min-width: 845px">
                            <thead class="table-light">
                                <tr>
                                    <th>N°</th>
                                    <th>Institution</th>
                                    <th>Institution parent</th>
                                    <th>Type institution</th>
                                    <th>Lieu</th>
                                    <th>Sceau</th>
                                    <th>Statut</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-institutions">
                                @php
                                    $institutionsCount = $institutions ? $institutions->count() : 0;
                                @endphp
                                @if($institutionsCount > 0)
                                    @include('referentiel::institution.partials.table-institutions', ['institutions' => $institutions])
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Aucune institution trouvée (Total: {{ $institutionsCount }})</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th>N°</th>
                                    <th>Institution</th>
                                    <th>Institution parent</th>
                                    <th>Type institution</th>
                                    <th>Lieu</th>
                                    <th>Sceau</th>
                                    <th>Statut</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ajout -->
    <div class="modal fade" id="modalCEC" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addInstitutionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addInstitutionModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Ajouter une institution
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route("institution.store") }}" method="POST" enctype="multipart/form-data" id="addInstitutionForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <!-- 1. Libellé de l'institution -->
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Libellé de l'institution <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg @error('lib_institution') is-invalid @enderror" 
                                       value="{{ old("lib_institution") }}" 
                                       placeholder="Ex: Cour d'Appel de Brazzaville..." 
                                       required name="lib_institution">
                                @error('lib_institution')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Saisissez le nom complet de l'institution
                                </small>
                            </div>

                            <!-- 2. Type d'institution -->
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Type d'institution <span class="text-danger">*</span></label>
                                <select id="codetypeinstitution" name="code_type_institution" required class="form-control form-control-lg @error('code_type_institution') is-invalid @enderror">
                                    <option value="">Choisissez un type</option>
                                    @foreach ($typeInstitutions as $item)
                                        <option value="{{ $item->code_type_institution }}" {{ old('code_type_institution') == $item->code_type_institution ? 'selected' : '' }}>
                                            {{ $item->lib_type_institution }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('code_type_institution')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Sélectionnez le type d'institution
                                </small>
                            </div>

                            <!-- 3. Rattachement à une institution parent -->
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Rattacher à une institution parent ?</label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rattacher" id="rattacherOui" value="OUI">
                                        <label class="form-check-label fw-bold" for="rattacherOui">
                                            <i class="fas fa-check-circle me-1 text-success"></i>OUI
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rattacher" id="rattacherNon" value="NON" checked>
                                        <label class="form-check-label fw-bold" for="rattacherNon">
                                            <i class="fas fa-times-circle me-1 text-danger"></i>NON
                                        </label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Indiquez si cette institution dépend d'une autre institution
                                </small>
                            </div>

                            <!-- 4. Institution parent (conditionnel) -->
                            <div class="mb-3 col-md-12 institutionRattache d-none">
                                <label class="form-label fw-bold">Institution parent</label>
                                <select id="codeinstitutionparent" name="code_institution_parent" class="form-control form-control-lg" disabled>
                                    <option value="">Sélectionnez d'abord un type d'institution</option>
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Sélectionnez l'institution parent si applicable
                                </small>
                            </div>

                            <!-- 5. Type de localité (si pas de parent) -->
                            <div class="mb-3 col-md-12 typeLocalite d-none">
                                <label class="form-label fw-bold">Type de localité <span class="text-danger">*</span></label>
                                <select id="codeTypeLocalite" name="code_type_localite" class="form-control form-control-lg" disabled>
                                    <option value="">Choisissez un type</option>
                                    @foreach ($typeLocalites as $item)
                                        <option value="{{ $item->code_type_localite }}">{{ $item->lib_type_localite }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Sélectionnez le type de localité pour filtrer les localités disponibles
                                </small>
                            </div>

                            <!-- 6. Localité -->
                            <div class="mb-3 col-md-12 localite d-none">
                                <label class="form-label fw-bold">Localité <span class="text-danger">*</span></label>
                                <select id="codelocalites" name="code_localite" class="form-control form-control-lg" disabled>
                                    <option value="">Sélectionnez d'abord un type de localité</option>
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Sélectionnez la localité où se trouve l'institution
                                </small>
                            </div>

                            <!-- 7. Sceau -->
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Sceau</label>
                                <input type="file" class="form-control form-control-lg" name="sceau" accept="image/*">
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Uploadez l'image du sceau de l'institution (format image)
                                </small>
                            </div>

                            <!-- 8. Statut -->
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Statut <span class="text-danger">*</span></label>
                                <select name="statut" class="form-control form-control-lg @error('statut') is-invalid @enderror" required>
                                    <option value="1" {{ old('statut', 1) == 1 ? 'selected' : '' }}>Actif</option>
                                    <option value="0" {{ old('statut') == 0 ? 'selected' : '' }}>Inactif</option>
                                </select>
                                @error('statut')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Définissez le statut de l'institution
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Annuler
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Enregistrer
                        </button>
                    </div>
                </form>
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
@endsection
@section('scripts')
<script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script>
    $(document).ready(function() {
        // Variable pour stocker l'instance DataTables
        var tableInstitutions = null;

        // Fonction pour rechercher les institutions côté serveur
        function searchInstitutionsServer() {
            var formData = $('#form-search-institutions').serialize();
            formData += '&_token={{ csrf_token() }}';

            $.ajax({
                url: "{{ route('institution.filter') }}",
                type: 'POST',
                data: formData,
                beforeSend: function() {
                    $('#tbody-institutions').html('<tr><td colspan="8" class="text-center"><i class="fas fa-spinner fa-spin"></i> Chargement...</td></tr>');
                    $('#count-results').text('');
                },
                success: function(response) {
                    try {
                        if (response.success && response.html) {
                            // Détruire DataTables complètement avant de modifier le contenu
                            if ($.fn.DataTable.isDataTable('#table-institutions')) {
                                try {
                                    tableInstitutions.destroy();
                                } catch(e) {
                                    console.log('Erreur lors de la destruction de DataTables:', e);
                            }
                                tableInstitutions = null;
                            }
                            // Vider complètement le tbody et le remplacer par les nouvelles données
                            $('#tbody-institutions').empty().html(response.html);

                            // Afficher le nombre de résultats
                            var countText = response.count + ' résultat(s) trouvé(s)';
                            if (response.limite_atteinte) {
                                countText += ' (limite de 500 atteinte, affinez vos critères)';
                            }
                            $('#count-results').text(countText);

                            // Réinitialiser DataTables avec les nouvelles données (même si vide)
                            setTimeout(function() {
                                    try {
                                    // Vérifier si la table a des données (plus d'une ligne ou pas de classe text-center)
                                    var rows = $('#tbody-institutions tr');
                                    var hasData = rows.length > 0 && rows.first().find('td.text-center').length === 0;

                                    if (hasData && rows.length > 0) {
                                        tableInstitutions = $('#table-institutions').DataTable({
                                            "language": {
                                                "search": "Rechercher:",
                                                "lengthMenu": "Afficher _MENU_ éléments",
                                                "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                                                "infoEmpty": "Affichage de 0 à 0 sur 0 éléments",
                                                "infoFiltered": "(filtré sur _MAX_ éléments au total)",
                                                "loadingRecords": "Chargement...",
                                                "zeroRecords": "Aucun élément correspondant trouvé",
                                                "emptyTable": "Aucune donnée disponible dans le tableau",
                                                "paginate": {
                                                    "first": "Premier",
                                                    "last": "Dernier",
                                                    "next": "Suivant",
                                                    "previous": "Précédent"
                                                }
                                            },
                                            "paging": false,
                                            "searching": true,
                                            "info": false,
                                            "ordering": true,
                                            "destroy": true
                                        });
                                    } else {
                                        // Si pas de données réelles, ne pas initialiser DataTables pour éviter les erreurs
                                        console.log('Table vide ou message d\'information seulement, DataTables non initialisé');
                                    }
                                } catch(e) {
                                    console.error('Erreur lors de l\'initialisation de DataTables:', e);
                                }
                            }, 100);
                        } else {
                            flashAlert("Erreur", "error", response.message || "Une erreur est survenue lors de la recherche");
                        }
                    } catch(e) {
                        console.error('Erreur lors du traitement de la réponse:', e);
                        flashAlert("Erreur", "error", "Erreur lors du traitement de la réponse");
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

        // Fonction de confirmation de suppression avec SweetAlert2
        function confirmDelete(code, libelle) {
            var formId = 'deleteForm' + code;
            var form = document.getElementById(formId);

            if (!form) {
                console.error('Formulaire non trouvé:', formId);
                Swal.fire({
                    title: 'Erreur',
                    text: 'Formulaire de suppression non trouvé: ' + formId,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            Swal.fire({
                title: 'Êtes-vous sûr ?',
                html: 'Voulez-vous vraiment supprimer l\'institution <strong>' + libelle + '</strong> ?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#CE1126',
                cancelButtonColor: '#009639',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-danger me-2',
                    cancelButton: 'btn btn-success'
                }
            }).then((result) => {
                console.log('Résultat de la confirmation:', result);
                if (result.value === true || result.isConfirmed === true) {
                    console.log('Soumission du formulaire:', formId);
                    console.log('Formulaire trouvé:', form);
                    if (form) {
                        form.submit();
                    } else {
                        console.error('Formulaire non trouvé pour soumission');
                        Swal.fire({
                            title: 'Erreur',
                            text: 'Impossible de trouver le formulaire de suppression',
                            type: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                } else {
                    console.log('Suppression annulée');
                }
            });
        }

        // Gestion de la suppression avec confirmation
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var button = $(this);
            var code = button.data('code');
            var libelle = button.data('libelle');

            if (code && libelle) {
                confirmDelete(code, libelle);
            } else {
                Swal.fire({
                    title: 'Erreur',
                    text: 'Données manquantes pour la suppression',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });

        // Scripts pour le modal d'ajout
        $('input[name="rattacher"]').change(function(){
            var selectedValue = $(this).val();
            if(selectedValue == "OUI"){
                $(".institutionRattache").removeClass("d-none");
                $("#codeinstitutionparent").prop('disabled', false).prop('required', true);
                $(".typeLocalite").addClass("d-none");
                $(".localite").addClass("d-none");
                $("#codeTypeLocalite").prop('disabled', true).prop('required', false);
                $("#codelocalites").prop('disabled', true).prop('required', false);
            }
            if(selectedValue == "NON"){
                $(".institutionRattache").addClass("d-none");
                $("#codeinstitutionparent").prop('disabled', true).prop('required', false).val('');
                $(".typeLocalite").removeClass("d-none");
                $("#codeTypeLocalite").prop('disabled', false).prop('required', true);
            }
        });

        $("#codeinstitutionparent").on("change", function(){
            if($(this).val() != ''){
                $(".typeLocalite").removeClass("d-none");
                $(".localite").removeClass("d-none");
                $("#codeTypeLocalite").prop('disabled', false).prop('required', true);
                $("#codelocalites").prop('disabled', false).prop('required', true);
            }
        });

        $("#codetypeinstitution").on("change", function(){
            var typeInstitution = $(this).val();
            if(typeInstitution != "" && typeInstitution != null){
                // Charger les institutions parents selon le type
                loadAvailableParentsByType('#codeinstitutionparent', typeInstitution);
            } else {
                $('#codeinstitutionparent').html('<option value="">Sélectionnez d\'abord un type d\'institution</option>');
            }
        });

        $("#codeTypeLocalite").on("change", function(){
            $(".localite").removeClass("d-none");
            var codetypeLoc = $(this).val();
            if(codetypeLoc != "" && codetypeLoc != null){
                getLocalite(codetypeLoc);
            }
        });

        // Fonction pour charger les parents disponibles par type
        function loadAvailableParentsByType(selectElement, codeTypeInstitution) {
            if (!codeTypeInstitution) {
                $(selectElement).html('<option value="">Sélectionnez d\'abord un type d\'institution</option>');
                return;
            }

            var url = "{{ route('institution.available.parents.by.type', ':type') }}".replace(':type', codeTypeInstitution);
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
                            options += '<option value="' + parent.code_institution + '">' + parent.lib_institution + typeLabel + '</option>';
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

        // Réinitialiser le formulaire d'ajout lors de l'ouverture du modal
        $('#modalCEC').on('show.bs.modal', function() {
            $('#addInstitutionForm')[0].reset();
            $(".institutionRattache").addClass("d-none");
            $(".typeLocalite").addClass("d-none");
            $(".localite").addClass("d-none");
            $("#codeinstitutionparent").prop('disabled', true).prop('required', false).html('<option value="">Sélectionnez d\'abord un type d\'institution</option>');
            $("#codeTypeLocalite").prop('disabled', true).prop('required', false);
            $("#codelocalites").prop('disabled', true).prop('required', false).html('<option value="">Sélectionnez d\'abord un type de localité</option>');
            $('#rattacherNon').prop('checked', true);
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

        function getInstitution(id){
            // Utiliser la nouvelle route pour charger les parents par type
            loadAvailableParentsByType('#codeinstitutionparent', id);
        }

        function getLocalite(id){
            var out = "<option selected disabled>Choisissez</option>";
            $.get("{{ route('institution.get.localite') }}", { id:id }, function(data){
                if(data.length > 0){
                    for(var i=0; i < data.length; i++){
                        out += "<option value="+data[i].code_localite+" >"+data[i].lib_localite+"</option>";
                    }
                }
                $("#codelocalites").html(out);
            });
        }
    });
</script>
@endsection
