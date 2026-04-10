@extends('layout.app')
@section('titre')
   Gestion des Types d'Institution
@endsection
@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endsection
@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0"><i class="fas fa-building me-2"></i>Gestion des Types d'Institution</h4>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTypeInstitutionModal">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter un type d'institution
                </button>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Liste des types d'institution</h5>
                </div>
                <div class="card-body">
                    <!-- Formulaire de filtre -->
                    <form id="form-search-type-institutions">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Type d'institution</label>
                                <input type="text" class="form-control" name="lib_type_institution" id="filter-lib-type-institution" placeholder="Rechercher...">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Catégorie</label>
                                <select name="code_type_categorie_ins" id="filter-code-type-categorie-ins" class="form-control">
                                    <option value="">Toutes les catégories</option>
                                    @foreach ($typeCategorieInstitutions as $categorie)
                                        <option value="{{ $categorie->code_type_categorie_ins }}">{{ $categorie->lib_type_categorie_institution }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i> Rechercher
                                </button>
                                <button type="button" class="btn btn-secondary" id="btn-reset-filters-type-institutions">
                                    <i class="fas fa-redo me-1"></i> Réinitialiser
                                </button>
                                <span id="count-results" class="ms-3 text-muted"></span>
                            </div>
                        </div>
                    </form>

                    <!-- Tableau des types d'institution -->
                    <div class="table-responsive mt-4">
                        <table id="table-type-institutions" class="display table table-hover" style="min-width: 845px">
                            <thead class="table-light">
                                <tr>
                                    <th>N°</th>
                                    <th>Type d'institution</th>
                                    <th>Catégorie</th>
                                    <th>Type de CEC</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-type-institutions">
                                @php
                                    $typeInstitutionsCount = $typeInstitutions ? $typeInstitutions->count() : 0;
                                @endphp
                                @if($typeInstitutionsCount > 0)
                                    @foreach ($typeInstitutions as $item)
                                    <tr>
                                        <td><span class="badge badge-primary">{{ $loop->iteration }}</span></td>
                                        <td><strong>{{ $item->lib_type_institution }}</strong></td>
                                        <td>{{ $item->typeCategorieInstitution ? $item->typeCategorieInstitution->lib_type_categorie_institution : 'N/A' }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-primary shadow btn-xs sharp" data-bs-toggle="modal" data-bs-target="#editTypeInstitutionModal{{ $item->code_type_institution }}" title="Modifier">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>
                                                <form action="{{ route('typeInstitution.destroy', $item->code_type_institution) }}" method="post" class="d-inline" id="deleteForm{{ $item->code_type_institution }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger shadow btn-xs sharp btn-delete" type="button" data-code="{{ $item->code_type_institution }}" data-libelle="{{ $item->lib_type_institution }}" title="Supprimer">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Aucun type d'institution trouvé (Total: {{ $typeInstitutionsCount }})</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th>N°</th>
                                    <th>Type d'institution</th>
                                    <th>Catégorie</th>
                                    <th>Type de CEC</th>
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
    <div class="modal fade" id="addTypeInstitutionModal" tabindex="-1" aria-labelledby="addTypeInstitutionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTypeInstitutionModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Ajouter un type d'institution
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('typeInstitution.store') }}" id="addTypeInstitutionForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Type d'institution <span class="text-danger">*</span></label>
                                <input type="text" name="lib_type_institution" class="form-control form-control-lg @error('lib_type_institution') is-invalid @enderror"
                                       value="{{ old("lib_type_institution") }}" placeholder="Ex: CEC Principal, Tribunal..." required>
                                @error("lib_type_institution")
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Saisissez le nom du type d'institution
                                </small>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Catégorie <span class="text-danger">*</span></label>
                                <select name="code_type_categorie_ins" class="form-control form-control-lg @error('code_type_categorie_ins') is-invalid @enderror" required>
                                    <option value="">-- Sélectionner une catégorie --</option>
                                    @foreach($typeCategorieInstitutions as $categorie)
                                        <option value="{{ $categorie->code_type_categorie_ins }}" {{ old('code_type_categorie_ins') == $categorie->code_type_categorie_ins ? 'selected' : '' }}>
                                            {{ $categorie->lib_type_categorie_institution }}
                                        </option>
                                    @endforeach
                                </select>
                                @error("code_type_categorie_ins")
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Sélectionnez la catégorie d'institution
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
    @foreach ($typeInstitutions as $item)
    <div class="modal fade" id="editTypeInstitutionModal{{ $item->code_type_institution }}" tabindex="-1" aria-labelledby="editTypeInstitutionModalLabel{{ $item->code_type_institution }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTypeInstitutionModalLabel{{ $item->code_type_institution }}">
                        <i class="fas fa-edit me-2"></i>Modifier {{ $item->lib_type_institution }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('typeInstitution.update', $item->code_type_institution) }}" method="POST" id="editTypeInstitutionForm{{ $item->code_type_institution }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Type d'institution <span class="text-danger">*</span></label>
                                <input class="form-control form-control-lg @error('lib_type_institution') is-invalid @enderror"
                                       name="lib_type_institution" type="text" value="{{ $item->lib_type_institution }}" required>
                                @error("lib_type_institution")
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Catégorie <span class="text-danger">*</span></label>
                                <select name="code_type_categorie_ins" class="form-control form-control-lg @error('code_type_categorie_ins') is-invalid @enderror" required>
                                    <option value="">-- Sélectionner une catégorie --</option>
                                    @foreach($typeCategorieInstitutions as $categorie)
                                        <option value="{{ $categorie->code_type_categorie_ins }}" {{ $item->code_type_categorie_ins == $categorie->code_type_categorie_ins ? 'selected' : '' }}>
                                            {{ $categorie->lib_type_categorie_institution }}
                                        </option>
                                    @endforeach
                                </select>
                                @error("code_type_categorie_ins")
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
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-1"></i>Modifier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
</div>
</div>
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
                      type: 'error',
                      confirmButtonText: 'OK'
                  });
                  return;
              }

              Swal.fire({
                  title: 'Êtes-vous sûr ?',
                  html: 'Voulez-vous vraiment supprimer le type d\'institution <strong>' + libelle + '</strong> ?',
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
          var tableTypeInstitutions = null;

          // Fonction pour rechercher les types d'institution côté serveur
          function searchTypeInstitutionsServer() {
              var formData = $('#form-search-type-institutions').serialize();
              formData += '&_token={{ csrf_token() }}';

              $.ajax({
                  url: "{{ route('typeInstitution.filter') }}",
                  type: 'POST',
                  data: formData,
                  beforeSend: function() {
                      $('#tbody-type-institutions').html('<tr><td colspan="4" class="text-center"><i class="fa fa-spinner fa-spin"></i> Chargement...</td></tr>');
                      $('#count-results').text('');
                  },
                  success: function(response) {
                      try {
                          if (response.success && response.html) {
                              // Détruire DataTables complètement avant de modifier le contenu
                              if ($.fn.DataTable.isDataTable('#table-type-institutions')) {
                                  try {
                                      tableTypeInstitutions.destroy();
                                  } catch(e) {
                                      console.log('Erreur lors de la destruction de DataTables:', e);
                                  }
                                  tableTypeInstitutions = null;
                              }
                              // Vider complètement le tbody et le remplacer par les nouvelles données
                              $('#tbody-type-institutions').empty().html(response.html);

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
                                      var rows = $('#tbody-type-institutions tr');
                                      var hasData = rows.length > 0 && rows.first().find('td.text-center').length === 0;

                                      if (hasData && rows.length > 0) {
                                          tableTypeInstitutions = $('#table-type-institutions').DataTable({
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
                      var errorMessage = "Erreur lors de la recherche des types d'institution";
                      if (xhr.responseJSON && xhr.responseJSON.message) {
                          errorMessage = xhr.responseJSON.message;
                      } else if (xhr.responseJSON && xhr.responseJSON.error) {
                          errorMessage = xhr.responseJSON.error;
                      }
                      flashAlert("Erreur", "error", errorMessage);
                  }
              });
          }

          $(document).ready(function() {
              // Soumission du formulaire de recherche
              $('#form-search-type-institutions').on('submit', function(e) {
                  e.preventDefault();
                  searchTypeInstitutionsServer();
              });

              // Réinitialiser les filtres
              $('#btn-reset-filters-type-institutions').on('click', function() {
                  $('#form-search-type-institutions')[0].reset();
                  location.reload();
              });

              // Event listener pour les boutons de suppression
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

              // Réinitialiser le formulaire lors de l'ouverture du modal d'ajout
              $('#addTypeInstitutionModal').on('show.bs.modal', function() {
                  $('#addTypeInstitutionForm')[0].reset();
              });
          });
      </script>
@endsection
