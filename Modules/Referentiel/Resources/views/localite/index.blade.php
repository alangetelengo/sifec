@extends('layout.app')
@section('titre')
   Gestion des Localités
@endsection
@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endsection
@section('corps')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Gestion des Localités</h4>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLocaliteModal">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter une localité
                </button>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Liste des localités</h5>
                </div>
                        <div class="card-body">
                    <!-- Formulaire de filtre -->



                    <form id="form-search-localites">
                                                                            <div class="row">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Libellé de la localité</label>
                                <input type="text" class="form-control" name="lib_localite" id="filter-lib-localite" placeholder="Rechercher...">
                                                                                </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Type de localité</label>
                                <select name="code_type_localite" id="filter-code-type-localite" class="form-control">
                                    <option value="">Tous les types</option>
                                    @foreach ($typeLocalites as $type)
                                        <option value="{{ $type->code_type_localite }}">{{ $type->lib_type_localite }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i> Rechercher
                                </button>
                                <button type="button" class="btn btn-secondary" id="btn-reset-filters-localites">
                                    <i class="fas fa-redo me-1"></i> Réinitialiser
                                </button>
                                <span id="count-results" class="ms-3 text-muted"></span>
                                                                        </div>
                                                                        </div>
                                                                    </form>

                    <!-- Tableau des localités -->
                    <div class="table-responsive">
                        <table id="table-localites" class="display table table-hover" style="min-width: 845px">
                            <thead class="table-light">
                                <tr>
                                    <th>N°</th>
                                    <th>Localité</th>
                                    <th>Type</th>
                                    <th>Parent</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-localites">
                                @php
                                    $localitesCount = $localites ? $localites->count() : 0;
                                @endphp
                                @if($localitesCount > 0)
                                    @foreach ($localites as $item)
                                    <tr>
                                        <td><span class="badge badge-primary">{{ $loop->iteration }}</span></td>
                                        <td><strong>{{ $item->lib_localite }}</strong></td>
                                        <td>{{ $item->typelocalite ? $item->typelocalite->lib_type_localite : 'N/A' }}</td>
                                        <td>
                                            @if($item->localiteParent)
                                                <span class="text-muted"><i class="fas fa-level-up-alt me-1"></i>{{ $item->localiteParent->lib_localite }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-primary shadow btn-xs sharp" data-bs-toggle="modal" data-bs-target="#editLocaliteModal{{ $item->code_localite }}" title="Modifier">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>
                                                <form action="{{ route('localite.destroy', $item->code_localite) }}" method="post" class="d-inline" id="deleteForm{{ $item->code_localite }}">
                                                            @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger shadow btn-xs sharp btn-delete" type="button" data-code="{{ $item->code_localite }}" data-libelle="{{ $item->lib_localite }}" title="Supprimer">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Aucune localité trouvée (Total: {{ $localitesCount }})</p>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                    </tbody>
                            <tfoot class="table-light">
                                        <tr>
                                    <th>N°</th>
                                            <th>Localité</th>
                                    <th>Type</th>
                                    <th>Parent</th>
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
    <div class="modal fade" id="addLocaliteModal" tabindex="-1" aria-labelledby="addLocaliteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="addLocaliteModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Ajouter une localité
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                <form method="POST" action="{{ route('localite.store') }}" id="addLocaliteForm">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                            <!-- 1. Type de localité (en premier) -->
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Type de localité <span class="text-danger">*</span></label>
                                <select name="code_type_localite" id="add_code_type_localite" class="form-control form-control-lg @error('code_type_localite') is-invalid @enderror" required>
                                    <option value="">Choisissez un type</option>
                                    @foreach ($typeLocalites as $type)
                                        <option value="{{ $type->code_type_localite }}" {{ old('code_type_localite') == $type->code_type_localite ? 'selected' : '' }}>
                                            {{ $type->lib_type_localite }}
                                        </option>
                                        @endforeach
                                    </select>
                                @error("code_type_localite")
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Sélectionnez d'abord le type de localité que vous souhaitez enregistrer
                                </small>
                            </div>

                            <!-- 2. Localité parent (se charge selon le type sélectionné) -->
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Localité parent <span id="add_parent_required" class="text-danger" style="display:none;">*</span></label>
                                <select name="code_localite_parent" id="add_code_localite_parent" class="form-control form-control-lg" disabled>
                                    <option value="">Sélectionnez d'abord un type de localité</option>
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i><span id="add_parent_help">Sélectionnez d'abord le type de localité pour voir les parents disponibles</span>
                                </small>
                            </div>

                            <!-- 3. Libellé de la localité (en dernier) -->
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Libellé de la localité <span class="text-danger">*</span></label>
                                <input type="text" name="lib_localite" class="form-control form-control-lg @error('lib_localite') is-invalid @enderror"
                                       value="{{ old("lib_localite") }}" placeholder="Ex: Brazzaville, Pointe-Noire..." required>
                                @error("lib_localite")
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Saisissez le nom de la localité à enregistrer
                                </small>
                                </div>

                            <!-- 4. Pompes funèbres -->
                            <div class="mb-3 col-md-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="pompes_funebres" id="add_pompes_funebres" value="1" {{ old('pompes_funebres') ? 'checked' : '' }} disabled>
                                    <label class="form-check-label fw-bold" for="add_pompes_funebres">
                                        Pompes funèbres
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Disponible uniquement pour Commune ou Arrondissement
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

    <!-- Modals Modification -->
    @foreach ($localites as $item)
    <div class="modal fade" id="editLocaliteModal{{ $item->code_localite }}" tabindex="-1" aria-labelledby="editLocaliteModalLabel{{ $item->code_localite }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLocaliteModalLabel{{ $item->code_localite }}">
                        <i class="fas fa-edit me-2"></i>Modifier {{ $item->lib_localite }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('localite.update', $item->code_localite) }}" method="POST" id="editLocaliteForm{{ $item->code_localite }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Libellé de la localité <span class="text-danger">*</span></label>
                                <input class="form-control form-control-lg @error('lib_localite') is-invalid @enderror"
                                       name="lib_localite" type="text" value="{{ $item->lib_localite }}" required>
                                @error("lib_localite")
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label fw-bold">Type de localité <span class="text-danger">*</span></label>
                                <select name="code_type_localite" id="edit_code_type_localite{{ $item->code_localite }}" class="form-control form-control-lg @error('code_type_localite') is-invalid @enderror" required>
                                    @foreach ($typeLocalites as $type)
                                        <option value="{{ $type->code_type_localite }}" {{ $type->code_type_localite == $item->code_type_localite ? 'selected' : '' }}>
                                            {{ $type->lib_type_localite }}
                                        </option>
                                        @endforeach
                                    </select>
                                @error("code_type_localite")
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label fw-bold">Localité parent <span id="edit_parent_required{{ $item->code_localite }}" class="text-danger" style="display:none;">*</span></label>
                                <select name="code_localite_parent" id="edit_code_localite_parent{{ $item->code_localite }}" class="form-control form-control-lg" data-current-id="{{ $item->code_localite }}" data-current-type="{{ $item->code_type_localite }}" data-current-parent="{{ $item->code_localite_parent }}">
                                    <option value="">Chargement...</option>
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Les parents disponibles dépendent du type de localité
                                </small>
                            </div>
                            <div class="mb-3 col-md-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="pompes_funebres" id="edit_pompes_funebres{{ $item->code_localite }}" value="1" {{ $item->pompes_funebres ? 'checked' : '' }}
                                           @if(!in_array($item->code_type_localite, ['TPLOC_0003', 'TPLOC_0004'])) disabled @endif>
                                    <label class="form-check-label fw-bold" for="edit_pompes_funebres{{ $item->code_localite }}">
                                        Pompes funèbres
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Disponible uniquement pour Commune ou Arrondissement
                                </small>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Annuler
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-1"></i>Modifier
                        </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
@section('scripts')
      <!-- Datatable -->
      <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>
      <script>
          // Fonction de confirmation de suppression avec flashAlert (SweetAlert2)
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
                  html: 'Voulez-vous vraiment supprimer la localité <strong>' + libelle + '</strong> ?',
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
                  if (result.value === true || result.isConfirmed === true) {
                      form.submit();
                  }
              });
          }

          // Variable pour stocker l'instance DataTables
          var tableLocalites = null;

          // Fonction pour rechercher les localités côté serveur
          function searchLocalitesServer() {
              var formData = $('#form-search-localites').serialize();
              formData += '&_token={{ csrf_token() }}';

              $.ajax({
                  url: "{{ route('localite.filter') }}",
                  type: 'POST',
                  data: formData,
                  beforeSend: function() {
                      $('#tbody-localites').html('<tr><td colspan="5" class="text-center"><i class="fa fa-spinner fa-spin"></i> Chargement...</td></tr>');
                      $('#count-results').text('');
                  },
                  success: function(response) {
                      try {
                          if (response.code === '200') {
                              // Détruire DataTables complètement avant de modifier le contenu
                              if ($.fn.DataTable.isDataTable('#table-localites')) {
                                  try {
                                      tableLocalites.destroy();
                                  } catch(e) {
                                      console.log('Erreur lors de la destruction de DataTables:', e);
                                  }
                                  tableLocalites = null;
                              }
                              // Vider complètement le tbody et le remplacer par les nouvelles données
                              $('#tbody-localites').empty().html(response.data);

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
                                      var rows = $('#tbody-localites tr');
                                      var hasData = rows.length > 0 && rows.first().find('td.text-center').length === 0;

                                      if (hasData && rows.length > 0) {
                                          tableLocalites = $('#table-localites').DataTable({
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
                      var errorMessage = "Erreur lors de la recherche des localités";
                      if (xhr.responseJSON && xhr.responseJSON.message) {
                          errorMessage = xhr.responseJSON.message;
                      } else if (xhr.responseJSON && xhr.responseJSON.error) {
                          errorMessage = xhr.responseJSON.error;
                      }
                      flashAlert("Erreur", "error", errorMessage);
                  }
              });
          }

          // Fonction pour charger les parents disponibles selon le type de localité
          function loadAvailableParents(selectElement, codeTypeLocalite, currentValue = null, excludeId = null) {
              if (!codeTypeLocalite) {
                  $(selectElement).html('<option value="">Sélectionnez d\'abord un type de localité</option>');
                  return;
              }

              var url = "{{ route('localite.available.parents.by.type', ':type') }}".replace(':type', codeTypeLocalite);
              if (excludeId) {
                  url = "{{ route('localite.available.parents', ':id') }}".replace(':id', excludeId) + '?type=' + codeTypeLocalite;
              }

              $(selectElement).html('<option value="">Chargement...</option>');

              $.ajax({
                  url: url,
                  method: 'GET',
                  dataType: 'json',
                  success: function(data) {
                      var options = '';

                      // Seul le Département peut être racine (pas de parent)
                      if (codeTypeLocalite === 'TPLOC_0001') {
                          options = '<option value="">Aucune (Département - localité racine)</option>';
                      } else {
                          // Pour tous les autres types, un parent est obligatoire
                          options = '<option value="">-- Sélectionnez un parent --</option>';
                      }

                      $.each(data, function(index, parent) {
                          var typeLabel = parent.typelocalite ? ' (' + parent.typelocalite.lib_type_localite + ')' : '';
                          var selected = (currentValue && currentValue === parent.code_localite) ? 'selected' : '';
                          options += '<option value="' + parent.code_localite + '" ' + selected + '>' + parent.lib_localite + typeLabel + '</option>';
                      });

                      $(selectElement).html(options);
                  },
                  error: function(xhr, status, error) {
                      console.error('Erreur lors du chargement des parents disponibles:', error);
                      $(selectElement).html('<option value="">Erreur lors du chargement</option>');
                  }
              });
          }

          // Fonction pour gérer l'activation/désactivation du champ pompes_funebres
          function togglePompesFunebres(codeTypeLocalite, checkboxId) {
              var checkbox = $('#' + checkboxId);
              if (codeTypeLocalite === 'TPLOC_0003' || codeTypeLocalite === 'TPLOC_0004') {
                  checkbox.prop('disabled', false);
              } else {
                  checkbox.prop('disabled', true);
                  checkbox.prop('checked', false);
              }
          }

          // Event listener pour les boutons de suppression (délégation d'événements)
          $(document).ready(function() {
              // Charger les parents disponibles lors du changement de type (modal ajout)
              $('#add_code_type_localite').on('change', function() {
                  var codeTypeLocalite = $(this).val();
                  var selectParent = $('#add_code_localite_parent');
                  var parentRequired = $('#add_parent_required');
                  var parentHelp = $('#add_parent_help');

                  if (codeTypeLocalite) {
                      // Activer le champ parent et charger les parents disponibles
                      selectParent.prop('disabled', false);

                      // Pour le Département, le parent n'est pas requis
                      if (codeTypeLocalite === 'TPLOC_0001') {
                          selectParent.prop('required', false);
                          parentRequired.hide();
                          parentHelp.text('Le Département est une localité racine (pas de parent)');
                      } else {
                          selectParent.prop('required', true);
                          parentRequired.show();
                          parentHelp.text('Sélectionnez un parent selon la hiérarchie');
                      }

                      loadAvailableParents(selectParent, codeTypeLocalite);
                      togglePompesFunebres(codeTypeLocalite, 'add_pompes_funebres');
                  } else {
                      // Désactiver le champ parent et réinitialiser
                      selectParent.prop('disabled', true);
                      selectParent.prop('required', false);
                      selectParent.html('<option value="">Sélectionnez d\'abord un type de localité</option>');
                      parentRequired.hide();
                      parentHelp.text('Sélectionnez d\'abord le type de localité pour voir les parents disponibles');
                      $('#add_pompes_funebres').prop('disabled', true).prop('checked', false);
                  }
              });

              // Initialiser le champ parent et pompes_funebres au chargement (modal ajout)
              var initialType = $('#add_code_type_localite').val();
              if (initialType) {
                  $('#add_code_type_localite').trigger('change');
              } else {
                  $('#add_code_localite_parent').prop('disabled', true);
                  $('#add_code_localite_parent').html('<option value="">Sélectionnez d\'abord un type de localité</option>');
                  $('#add_pompes_funebres').prop('disabled', true);
              }

              // Réinitialiser le formulaire lors de l'ouverture du modal
              $('#addLocaliteModal').on('show.bs.modal', function() {
                  // Réinitialiser tous les champs
                  $('#addLocaliteForm')[0].reset();
                  $('#add_code_type_localite').val('').trigger('change');
                  $('#add_code_localite_parent').prop('disabled', true);
                  $('#add_code_localite_parent').html('<option value="">Sélectionnez d\'abord un type de localité</option>');
                  $('#add_pompes_funebres').prop('disabled', true).prop('checked', false);
                  $('#add_parent_required').hide();
                  $('#add_parent_help').text('Sélectionnez d\'abord le type de localité pour voir les parents disponibles');
              });
              // TEMPORAIREMENT : Ne pas initialiser DataTables pour voir si le tableau s'affiche
              console.log('=== DEBUG TABLEAU ===');
              console.log('Table trouvée:', $('#table-localites').length > 0);
              console.log('Nombre de lignes dans le tbody:', $('#tbody-localites tr').length);
              console.log('Tableau visible:', $('#table-localites').is(':visible'));
              console.log('Hauteur du tableau:', $('#table-localites').height());

              // Initialisation des DataTables sans pagination (comme dans ActeNaissanceController)
              // DÉSACTIVÉ TEMPORAIREMENT POUR TEST
              /*
              if ($('#table-localites').length) {
                  var rowCount = $('#tbody-localites tr').length;

                  if (rowCount > 0) {
                      // Vérifier si ce n'est pas juste le message "Aucune localité trouvée"
                      var firstRow = $('#tbody-localites tr').first();
                      var isEmptyMessage = firstRow.find('td.text-center').length > 0 || firstRow.find('i.fa-inbox').length > 0;

                      if (!isEmptyMessage) {
                          try {
                              if (!$.fn.DataTable.isDataTable('#table-localites')) {
                                  tableLocalites = $('#table-localites').DataTable({
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
                                          },
                                          "aria": {
                                              "sortAscending": ": activer pour trier la colonne par ordre croissant",
                                              "sortDescending": ": activer pour trier la colonne par ordre décroissant"
                                          }
                                      },
                                      "paging": false,
                                      "searching": true,
                                      "info": false,
                                      "ordering": true
                                  });
                                  console.log('DataTables initialisé avec succès');
                              }
                          } catch(e) {
                              console.error('Erreur lors de l\'initialisation de DataTables:', e);
                          }
                      }
                  }
              }
              */

              // Soumission du formulaire de recherche
              $('#form-search-localites').on('submit', function(e) {
                  e.preventDefault();
                  searchLocalitesServer();
              });

              // Réinitialiser les filtres
              $('#btn-reset-filters-localites').on('click', function() {
                  $('#form-search-localites')[0].reset();
                  // Recharger les données initiales (20 derniers)
                  location.reload();
              });

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
                          type: 'error',
                          confirmButtonText: 'OK'
                      });
                  }
              });

              // Charger les parents disponibles lors de la modification
              @foreach ($localites as $item)
              (function() {
                  var modalId = '#editLocaliteModal{{ $item->code_localite }}';
                  var selectParent = $('#edit_code_localite_parent{{ $item->code_localite }}');
                  var selectType = $('#edit_code_type_localite{{ $item->code_localite }}');
                  var currentId = '{{ $item->code_localite }}';
                  var currentType = '{{ $item->code_type_localite }}';
                  var currentParent = '{{ $item->code_localite_parent }}';

                  // Charger les parents disponibles lors de l'ouverture du modal
                  $(modalId).on('show.bs.modal', function() {
                      loadEditAvailableParents(selectParent, currentType, currentId, currentParent);
                      toggleEditPompesFunebres(currentType, 'edit_pompes_funebres{{ $item->code_localite }}');
                  });

                  // Charger les parents disponibles lors du changement de type
                  selectType.on('change', function() {
                      var newType = $(this).val();
                      loadEditAvailableParents(selectParent, newType, currentId, currentParent);
                      toggleEditPompesFunebres(newType, 'edit_pompes_funebres{{ $item->code_localite }}');
                  });
              })();
              @endforeach

              // Fonction pour charger les parents disponibles dans le modal de modification
              function loadEditAvailableParents(selectElement, codeTypeLocalite, excludeId, currentParentValue) {
                  if (!codeTypeLocalite) {
                      $(selectElement).html('<option value="">Sélectionnez d\'abord un type de localité</option>');
                      return;
                  }

                  // Utiliser la route avec exclusion si un ID est fourni
                  var url;
                  if (excludeId) {
                      url = "{{ route('localite.available.parents', ':id') }}".replace(':id', excludeId) + '?type=' + codeTypeLocalite;
                  } else {
                      url = "{{ route('localite.available.parents.by.type', ':type') }}".replace(':type', codeTypeLocalite);
                  }

                  $(selectElement).html('<option value="">Chargement...</option>');

                  $.ajax({
                      url: url,
                      method: 'GET',
                      dataType: 'json',
                      success: function(data) {
                          var options = '';

                          // Seul le Département peut être racine (pas de parent)
                          if (codeTypeLocalite === 'TPLOC_0001') {
                              options = '<option value="">Aucune (Département - localité racine)</option>';
                          } else {
                              options = '<option value="">-- Sélectionnez un parent --</option>';
                          }

                          $.each(data, function(index, parent) {
                              var typeLabel = parent.typelocalite ? ' (' + parent.typelocalite.lib_type_localite + ')' : '';
                              var selected = (currentParentValue && currentParentValue === parent.code_localite) ? 'selected' : '';
                              options += '<option value="' + parent.code_localite + '" ' + selected + '>' + parent.lib_localite + typeLabel + '</option>';
                          });

                          $(selectElement).html(options);

                          // Si le parent actuel n'est pas dans la liste (peut arriver si le type change), le sélectionner quand même
                          if (currentParentValue && !$(selectElement).find('option[value="' + currentParentValue + '"]').length) {
                              $(selectElement).append('<option value="' + currentParentValue + '" selected>Parent actuel (non disponible avec ce type)</option>');
                          }
                      },
                      error: function(xhr, status, error) {
                          console.error('Erreur lors du chargement des parents disponibles:', error);
                          $(selectElement).html('<option value="">Erreur lors du chargement</option>');
                      }
                  });
              }

              // Fonction pour gérer l'activation/désactivation du champ pompes_funebres dans le modal de modification
              function toggleEditPompesFunebres(codeTypeLocalite, checkboxId) {
                  var checkbox = $('#' + checkboxId);
                  if (codeTypeLocalite === 'TPLOC_0003' || codeTypeLocalite === 'TPLOC_0004') {
                      checkbox.prop('disabled', false);
                  } else {
                      checkbox.prop('disabled', true);
                      if (!checkbox.prop('checked')) {
                          checkbox.prop('checked', false);
                      }
                  }
              }
          });
      </script>
@endsection
