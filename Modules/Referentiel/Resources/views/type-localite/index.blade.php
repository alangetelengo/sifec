@extends('layout.app')
@section('titre')
   Types de localité
@endsection
@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style>
    .card-header-custom {
        background: linear-gradient(135deg, #009639 0%, #FCD116 50%, #CE1126 100%);
        color: white;
        border-radius: 10px 10px 0 0;
    }
    .btn-add-custom {
        background: linear-gradient(135deg, #009639 0%, #FCD116 50%, #CE1126 100%);
        border: none;
        color: white;
        padding: 10px 25px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-add-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 150, 57, 0.4);
        color: white;
        background: linear-gradient(135deg, #007a2f 0%, #e6c014 50%, #b80e20 100%);
    }
    .modal-header-custom {
        background: linear-gradient(135deg, #009639 0%, #FCD116 50%, #CE1126 100%);
        color: white;
        border-radius: 10px 10px 0 0;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }
    .badge-custom {
        padding: 8px 15px;
        border-radius: 20px;
        font-weight: 500;
    }
    .btn-primary {
        background-color: #009639;
        border-color: #009639;
    }
    .btn-primary:hover {
        background-color: #007a2f;
        border-color: #007a2f;
    }
    .text-info {
        color: #009639 !important;
    }
</style>
@endsection
@section('corps')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Gestion des Types de Localité</h4>
                <button type="button" class="btn btn-add-custom" data-bs-toggle="modal" data-bs-target="#addTypeLocaliteModal">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter un type
                </button>
            </div>
            <div class="card">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Liste des types de localité</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="display table table-hover" style="min-width: 845px">
                            <thead class="table-light">
                                <tr>
                                    <th>N°</th>
                                    <th>Type de localité</th>
                                    <th>Code</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1; ?>
                                @foreach ($typeLocalites as $item)
                                <tr>
                                    <td><span class="badge badge-custom badge-primary">{{ $i++ }}</span></td>
                                    <td><strong>{{ $item->lib_type_localite }}</strong></td>
                                    <td><code class="text-info">{{ $item->code_type_localite }}</code></td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-primary shadow btn-xs sharp" data-bs-toggle="modal" data-bs-target="#editTypeLocaliteModal{{ $item->code_type_localite }}" title="Modifier">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                            <form action="{{ route('typelocalite.destroy', $item->code_type_localite) }}" method="post" class="d-inline" id="deleteForm{{ $item->code_type_localite }}">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    class="btn btn-danger shadow btn-xs sharp btn-delete"
                                                    type="button"
                                                    data-code="{{ $item->code_type_localite }}"
                                                    data-libelle="{{ $item->lib_type_localite }}"
                                                    title="Supprimer">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th>N°</th>
                                    <th>Type de localité</th>
                                    <th>Code</th>
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
    <div class="modal fade" id="addTypeLocaliteModal" tabindex="-1" aria-labelledby="addTypeLocaliteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title" id="addTypeLocaliteModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Ajouter un type de localité
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('typelocalite.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Type de localité <span class="text-danger">*</span></label>
                            <input type="text" name="lib_type_localite" class="form-control form-control-lg @error('lib_type_localite') is-invalid @enderror"
                                   value="{{ old("lib_type_localite") }}" placeholder="Ex: Département, Commune, District..." required>
                            @error("lib_type_localite")
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>Le code sera généré automatiquement
                            </small>
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
    @foreach ($typeLocalites as $item)
    <div class="modal fade" id="editTypeLocaliteModal{{ $item->code_type_localite }}" tabindex="-1" aria-labelledby="editTypeLocaliteModalLabel{{ $item->code_type_localite }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title" id="editTypeLocaliteModalLabel{{ $item->code_type_localite }}">
                        <i class="fas fa-edit me-2"></i>Modifier {{ $item->lib_type_localite }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('typelocalite.update',$item->code_type_localite) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Type de localité <span class="text-danger">*</span></label>
                            <input class="form-control form-control-lg @error('lib_type_localite') is-invalid @enderror"
                                   name="lib_type_localite" type="text" value="{{ $item->lib_type_localite }}" required>
                            @error("lib_type_localite")
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Code</label>
                            <input class="form-control" type="text" value="{{ $item->code_type_localite }}" disabled>
                            <small class="form-text text-muted">
                                <i class="fas fa-lock me-1"></i>Le code ne peut pas être modifié
                            </small>
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
              console.log('confirmDelete appelé avec:', {code: code, libelle: libelle});

              var formId = 'deleteForm' + code;
              console.log('Recherche du formulaire avec ID:', formId);

              var form = document.getElementById(formId);
              console.log('Formulaire trouvé:', form);

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

              console.log('Affichage de la modal de confirmation');
              Swal.fire({
                  title: 'Êtes-vous sûr ?',
                  html: 'Voulez-vous vraiment supprimer le type de localité <strong>' + libelle + '</strong> ?',
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
                  console.log('result.value:', result.value);
                  console.log('result.isConfirmed:', result.isConfirmed);
                  console.log('result.dismiss:', result.dismiss);

                  // Vérifier si l'utilisateur a confirmé (result.value est true ou result.isConfirmed est true)
                  if (result.value === true || result.isConfirmed === true) {
                      console.log('Confirmation reçue, soumission du formulaire');
                      console.log('Formulaire à soumettre:', form);
                      console.log('Action du formulaire:', form.action);
                      form.submit();
                      console.log('Formulaire soumis');
                  } else {
                      console.log('Suppression annulée');
                  }
              }).catch((error) => {
                  console.error('Erreur dans la modal:', error);
              });
          }

          // Vérifier que la fonction est bien définie
          console.log('Fonction confirmDelete définie:', typeof confirmDelete);

          // Event listener pour les boutons de suppression (délégation d'événements)
          $(document).ready(function() {
              console.log('Initialisation des event listeners pour les boutons de suppression');

              // Utiliser la délégation d'événements pour gérer les clics sur les boutons de suppression
              $(document).on('click', '.btn-delete', function(e) {
                  e.preventDefault();
                  e.stopPropagation();

                  var button = $(this);
                  var code = button.data('code');
                  var libelle = button.data('libelle');

                  console.log('Bouton de suppression cliqué:', {code: code, libelle: libelle});

                  if (code && libelle) {
                      confirmDelete(code, libelle);
                  } else {
                      console.error('Attributs data-code ou data-libelle manquants');
                      Swal.fire({
                          title: 'Erreur',
                          text: 'Données manquantes pour la suppression',
                          icon: 'error',
                          confirmButtonText: 'OK'
                      });
                  }
              });
          });
      </script>
@endsection

