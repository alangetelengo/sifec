@extends('layout.app')
@section('titre')
   Catégories d'Institution
@endsection
@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endsection
@section('corps')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0"><i class="fas fa-building me-2"></i>Gestion des Catégories d'Institution</h4>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTypeCategorieInstitutionModal">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter une catégorie
                </button>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Liste des catégories d'institution</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table-type-categorie-institutions" class="display table table-hover" style="min-width: 845px">
                            <thead class="table-light">
                                <tr>
                                    <th>N°</th>
                                    <th>Catégorie</th>
                                    <th>Code</th>
                                    <th>Image</th>
                                    <th>Types associés</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($typeCategorieInstitutions as $index => $item)
                                <tr>
                                    <td><span class="badge badge-primary">{{ $loop->iteration }}</span></td>
                                    <td><strong>{{ $item->lib_type_categorie_institution }}</strong></td>
                                    <td><code>{{ $item->code_type_categorie_ins }}</code></td>
                                    <td>
                                        @if($item->image_illustrative)
                                            <img src='{{ asset("app/".$item->image_illustrative) }}' alt="Image" width="50px" height="50px" class="img-thumbnail">
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $item->typeInstitutions->count() }} type(s)</span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-primary shadow btn-xs sharp" data-bs-toggle="modal" data-bs-target="#editTypeCategorieInstitutionModal{{ $item->code_type_categorie_ins }}" title="Modifier">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                            <form action="{{ route('typeCategorieInstitution.destroy', $item->code_type_categorie_ins) }}" method="post" class="d-inline" id="deleteForm{{ $item->code_type_categorie_ins }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger shadow btn-xs sharp btn-delete" type="button" data-code="{{ $item->code_type_categorie_ins }}" data-libelle="{{ $item->lib_type_categorie_institution }}" title="Supprimer">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Aucune catégorie trouvée</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ajout -->
    <div class="modal fade" id="addTypeCategorieInstitutionModal" tabindex="-1" aria-labelledby="addTypeCategorieInstitutionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addTypeCategorieInstitutionModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Ajouter une catégorie d'institution
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('typeCategorieInstitution.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Libellé de la catégorie <span class="text-danger">*</span></label>
                            <input type="text" name="lib_type_categorie_institution" class="form-control form-control-lg @error('lib_type_categorie_institution') is-invalid @enderror" 
                                   value="{{ old('lib_type_categorie_institution') }}" placeholder="Ex: Centre d'État Civil, Tribunal..." required>
                            @error('lib_type_categorie_institution')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Image illustrative</label>
                            <input type="file" name="image_illustrative" class="form-control form-control-lg" accept="image/*">
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>Format: JPEG, PNG, JPG, GIF (max 2MB)
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
    @foreach ($typeCategorieInstitutions as $item)
    <div class="modal fade" id="editTypeCategorieInstitutionModal{{ $item->code_type_categorie_ins }}" tabindex="-1" aria-labelledby="editTypeCategorieInstitutionModalLabel{{ $item->code_type_categorie_ins }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="editTypeCategorieInstitutionModalLabel{{ $item->code_type_categorie_ins }}">
                        <i class="fas fa-edit me-2"></i>Modifier {{ $item->lib_type_categorie_institution }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('typeCategorieInstitution.update', $item->code_type_categorie_ins) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Libellé de la catégorie <span class="text-danger">*</span></label>
                            <input type="text" name="lib_type_categorie_institution" class="form-control form-control-lg @error('lib_type_categorie_institution') is-invalid @enderror" 
                                   value="{{ $item->lib_type_categorie_institution }}" required>
                            @error('lib_type_categorie_institution')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Image illustrative</label>
                            @if($item->image_illustrative)
                                <div class="mb-2">
                                    <img src='{{ asset("app/".$item->image_illustrative) }}' alt="Image actuelle" class="img-thumbnail" style="max-width: 150px;">
                                    <p class="text-muted small mb-0">Image actuelle</p>
                                </div>
                            @endif
                            <input type="file" name="image_illustrative" class="form-control form-control-lg" accept="image/*">
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>Laissez vide pour conserver l'image actuelle
                            </small>
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
    @endforeach
@endsection
@section('scripts')
<script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script>
    $(document).ready(function() {
        // Initialiser DataTables
        if ($('#table-type-categorie-institutions').length) {
            $('#table-type-categorie-institutions').DataTable({
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
                "paging": true,
                "searching": true,
                "info": true,
                "ordering": true
            });
        }

        // Fonction de confirmation de suppression
        function confirmDelete(code, libelle) {
            var formId = 'deleteForm' + code;
            var form = document.getElementById(formId);

            if (!form) {
                Swal.fire({
                    title: 'Erreur',
                    text: 'Formulaire de suppression non trouvé',
                    type: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            Swal.fire({
                title: 'Êtes-vous sûr ?',
                html: 'Voulez-vous vraiment supprimer la catégorie <strong>' + libelle + '</strong> ?',
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

        // Gestion de la suppression
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var button = $(this);
            var code = button.data('code');
            var libelle = button.data('libelle');

            if (code && libelle) {
                confirmDelete(code, libelle);
            }
        });
    });
</script>
@endsection

