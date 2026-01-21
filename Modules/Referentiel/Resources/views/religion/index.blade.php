@extends('layout.app')
@section('titre')
   Gestion des Religions
@endsection
@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endsection
@section('corps')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0"><i class="fas fa-pray me-2"></i>Gestion des Religions</h4>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addReligionModal">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter une religion
                </button>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Liste des religions</h5>
                </div>
                <div class="card-body">
                    <!-- Formulaire de filtre -->
                    <form id="form-search-religions">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Libellé de la religion</label>
                                <input type="text" class="form-control" name="lib_religion" id="filter-lib-religion" placeholder="Rechercher...">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i> Rechercher
                                </button>
                                <button type="button" class="btn btn-secondary" id="btn-reset-filters-religions">
                                    <i class="fas fa-redo me-1"></i> Réinitialiser
                                </button>
                                <span id="count-results" class="ms-3 text-muted"></span>
                            </div>
                        </div>
                    </form>

                    <!-- Tableau des religions -->
                    <div class="table-responsive mt-4">
                        <table id="table-religions" class="display table table-hover" style="min-width: 845px">
                            <thead class="table-light">
                                <tr>
                                    <th>N°</th>
                                    <th>Libellé</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-religions">
                                @php
                                    $religionsCount = $religions ? $religions->count() : 0;
                                @endphp
                                @if($religionsCount > 0)
                                    @foreach ($religions as $item)
                                    <tr>
                                        <td><span class="badge badge-primary">{{ $loop->iteration }}</span></td>
                                        <td><strong>{{ $item->lib_religion }}</strong></td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-primary shadow btn-xs sharp" data-bs-toggle="modal" data-bs-target="#editReligionModal{{ $item->code_religion }}" title="Modifier">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>
                                                <form action="{{ route('religion.destroy', $item->code_religion) }}" method="post" class="d-inline" id="deleteForm{{ $item->code_religion }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger shadow btn-xs sharp btn-delete" type="button" data-code="{{ $item->code_religion }}" data-libelle="{{ $item->lib_religion }}" title="Supprimer">
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
                                                <p class="text-muted">Aucune religion trouvée (Total: {{ $religionsCount }})</p>
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
    <div class="modal fade" id="addReligionModal" tabindex="-1" aria-labelledby="addReligionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addReligionModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Ajouter une religion
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('religion.store') }}" id="addReligionForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Libellé de la religion <span class="text-danger">*</span></label>
                                <input type="text" name="lib_religion" class="form-control form-control-lg @error('lib_religion') is-invalid @enderror"
                                       value="{{ old("lib_religion") }}" placeholder="Ex: Christianisme, Islam..." required>
                                @error("lib_religion")
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Saisissez le nom de la religion à enregistrer
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
    @foreach ($religions as $item)
    <div class="modal fade" id="editReligionModal{{ $item->code_religion }}" tabindex="-1" aria-labelledby="editReligionModalLabel{{ $item->code_religion }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editReligionModalLabel{{ $item->code_religion }}">
                        <i class="fas fa-edit me-2"></i>Modifier {{ $item->lib_religion }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('religion.update', $item->code_religion) }}" method="POST" id="editReligionForm{{ $item->code_religion }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Libellé de la religion <span class="text-danger">*</span></label>
                                <input class="form-control form-control-lg @error('lib_religion') is-invalid @enderror"
                                       name="lib_religion" type="text" value="{{ $item->lib_religion }}" required>
                                @error("lib_religion")
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
                  html: 'Voulez-vous vraiment supprimer la religion <strong>' + libelle + '</strong> ?',
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
          var tableReligions = null;

          // Fonction pour rechercher les religions côté serveur
          function searchReligionsServer() {
              var formData = $('#form-search-religions').serialize();
              formData += '&_token={{ csrf_token() }}';

              $.ajax({
                  url: "{{ route('religion.filter') }}",
                  type: 'POST',
                  data: formData,
                  beforeSend: function() {
                      $('#tbody-religions').html('<tr><td colspan="3" class="text-center"><i class="fa fa-spinner fa-spin"></i> Chargement...</td></tr>');
                      $('#count-results').text('');
                  },
                  success: function(response) {
                      try {
                          if (response.code === '200') {
                              $('#tbody-religions').html(response.data);
                              $('#count-results').text(response.count + ' résultat(s) trouvé(s)');
                              
                              // Réinitialiser DataTables si elle existe
                              if (tableReligions) {
                                  tableReligions.destroy();
                                  tableReligions = null;
                              }

                              // Réinitialiser DataTables après un court délai
                              setTimeout(function() {
                                  try {
                                      var rowCount = $('#tbody-religions tr').length;
                                      var firstRow = $('#tbody-religions tr').first();
                                      var isEmptyMessage = firstRow.find('td.text-center').length > 0;

                                      if (rowCount > 0 && !isEmptyMessage) {
                                          tableReligions = $('#table-religions').DataTable({
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
                                              "ordering": true
                                          });
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
                      var errorMessage = "Erreur lors de la recherche des religions";
                      if (xhr.responseJSON && xhr.responseJSON.message) {
                          errorMessage = xhr.responseJSON.message;
                      }
                      flashAlert("Erreur", "error", errorMessage);
                  }
              });
          }

          // Event listeners
          $(document).ready(function() {
              // Soumission du formulaire de recherche
              $('#form-search-religions').on('submit', function(e) {
                  e.preventDefault();
                  searchReligionsServer();
              });

              // Réinitialisation des filtres
              $('#btn-reset-filters-religions').on('click', function() {
                  $('#form-search-religions')[0].reset();
                  searchReligionsServer();
              });

              // Boutons de suppression
              $(document).on('click', '.btn-delete', function() {
                  var code = $(this).data('code');
                  var libelle = $(this).data('libelle');
                  confirmDelete(code, libelle);
              });

              // Réinitialiser le formulaire lors de l'ouverture du modal
              $('#addReligionModal').on('show.bs.modal', function() {
                  $('#addReligionForm')[0].reset();
              });
          });
      </script>
@endsection
