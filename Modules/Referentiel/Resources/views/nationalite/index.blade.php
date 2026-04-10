@extends('layout.app')
@section('titre')
   Gestion des Nationalités
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
                <h4 class="mb-0"><i class="fas fa-flag me-2"></i>Gestion des Nationalités</h4>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNationaliteModal">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter une nationalité
                </button>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Liste des nationalités</h5>
                </div>
                <div class="card-body">
                    <!-- Formulaire de filtre -->
                    <form id="form-search-nationalites">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Libellé de la nationalité</label>
                                <input type="text" class="form-control" name="lib_nationalite" id="filter-lib-nationalite" placeholder="Rechercher...">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i> Rechercher
                                </button>
                                <button type="button" class="btn btn-secondary" id="btn-reset-filters-nationalites">
                                    <i class="fas fa-redo me-1"></i> Réinitialiser
                                </button>
                                <span id="count-results" class="ms-3 text-muted"></span>
                            </div>
                        </div>
                    </form>

                    <!-- Tableau des nationalités -->
                    <div class="table-responsive mt-4">
                        <table id="table-nationalites" class="display table table-hover" style="min-width: 845px">
                            <thead class="table-light">
                                <tr>
                                    <th>N°</th>
                                    <th>Libellé</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-nationalites">
                                @php
                                    $nationalitesCount = $nationalites ? $nationalites->count() : 0;
                                @endphp
                                @if($nationalitesCount > 0)
                                    @foreach ($nationalites as $item)
                                    <tr>
                                        <td><span class="badge badge-primary">{{ $loop->iteration }}</span></td>
                                        <td><strong>{{ $item->lib_nationalite }}</strong></td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-primary shadow btn-xs sharp" data-bs-toggle="modal" data-bs-target="#editNationaliteModal{{ $item->code_nationalite }}" title="Modifier">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>
                                                <form action="{{ route('nationalite.destroy', $item->code_nationalite) }}" method="post" class="d-inline" id="deleteForm{{ $item->code_nationalite }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger shadow btn-xs sharp btn-delete" type="button" data-code="{{ $item->code_nationalite }}" data-libelle="{{ $item->lib_nationalite }}" title="Supprimer">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Aucune nationalité trouvée (Total: {{ $nationalitesCount }})</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th>N°</th>
                                    <th>Libellé</th>
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
    <div class="modal fade" id="addNationaliteModal" tabindex="-1" aria-labelledby="addNationaliteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNationaliteModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Ajouter une nationalité
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('nationalite.store') }}" id="addNationaliteForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Libellé de la nationalité <span class="text-danger">*</span></label>
                                <input type="text" name="lib_nationalite" class="form-control form-control-lg @error('lib_nationalite') is-invalid @enderror"
                                       value="{{ old("lib_nationalite") }}" placeholder="Ex: Congolaise, Française..." required>
                                @error("lib_nationalite")
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Saisissez le nom de la nationalité à enregistrer
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
    @foreach ($nationalites as $item)
    <div class="modal fade" id="editNationaliteModal{{ $item->code_nationalite }}" tabindex="-1" aria-labelledby="editNationaliteModalLabel{{ $item->code_nationalite }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editNationaliteModalLabel{{ $item->code_nationalite }}">
                        <i class="fas fa-edit me-2"></i>Modifier {{ $item->lib_nationalite }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('nationalite.update', $item->code_nationalite) }}" method="POST" id="editNationaliteForm{{ $item->code_nationalite }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Libellé de la nationalité <span class="text-danger">*</span></label>
                                <input class="form-control form-control-lg @error('lib_nationalite') is-invalid @enderror"
                                       name="lib_nationalite" type="text" value="{{ $item->lib_nationalite }}" required>
                                @error("lib_nationalite")
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
          // Fonction de confirmation de suppression avec SweetAlert2
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
                  html: 'Voulez-vous vraiment supprimer la nationalité <strong>' + libelle + '</strong> ?',
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
          var tableNationalites = null;

          // Fonction pour rechercher les nationalités côté serveur
          function searchNationalitesServer() {
              var formData = $('#form-search-nationalites').serialize();
              formData += '&_token={{ csrf_token() }}';

              $.ajax({
                  url: "{{ route('nationalite.filter') }}",
                  type: 'POST',
                  data: formData,
                  beforeSend: function() {
                      $('#tbody-nationalites').html('<tr><td colspan="3" class="text-center"><i class="fa fa-spinner fa-spin"></i> Chargement...</td></tr>');
                      $('#count-results').text('');
                  },
                  success: function(response) {
                      try {
                          if (response.code === '200') {
                              // Détruire DataTables complètement avant de modifier le contenu
                              if ($.fn.DataTable.isDataTable('#table-nationalites')) {
                                  try {
                                  tableNationalites.destroy();
                                  } catch(e) {
                                      console.log('Erreur lors de la destruction de DataTables:', e);
                                  }
                                  tableNationalites = null;
                              }
                              // Vider complètement le tbody et le remplacer par les nouvelles données
                              $('#tbody-nationalites').empty().html(response.data);

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
                                      var rows = $('#tbody-nationalites tr');
                                      var hasData = rows.length > 0 && rows.first().find('td.text-center').length === 0;

                                      if (hasData && rows.length > 0) {
                                          tableNationalites = $('#table-nationalites').DataTable({
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
                      var errorMessage = "Erreur lors de la recherche des nationalités";
                      if (xhr.responseJSON && xhr.responseJSON.message) {
                          errorMessage = xhr.responseJSON.message;
                      } else if (xhr.responseJSON && xhr.responseJSON.error) {
                          errorMessage = xhr.responseJSON.error;
                      }
                      flashAlert("Erreur", "error", errorMessage);
                  }
              });
          }

          // Event listeners
          $(document).ready(function() {
              // Soumission du formulaire de recherche
              $('#form-search-nationalites').on('submit', function(e) {
                  e.preventDefault();
                  searchNationalitesServer();
              });

              // Réinitialiser les filtres
              $('#btn-reset-filters-nationalites').on('click', function() {
                  $('#form-search-nationalites')[0].reset();
                  location.reload();
              });

              // Boutons de suppression
              $(document).on('click', '.btn-delete', function() {
                  var code = $(this).data('code');
                  var libelle = $(this).data('libelle');
                  confirmDelete(code, libelle);
              });

              // Réinitialiser le formulaire lors de l'ouverture du modal
              $('#addNationaliteModal').on('show.bs.modal', function() {
                  $('#addNationaliteForm')[0].reset();
              });
          });
      </script>
@endsection
