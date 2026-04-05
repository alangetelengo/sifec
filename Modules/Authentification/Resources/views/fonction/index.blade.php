@extends('layout.app')
@section('titre')
  Fonctions
@endsection
@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endsection
@section('corps')

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des fonctions</h4>
                    <button type="button" class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#modalAjoutFonction">
                        Ajouter
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="display" style="min-width: 845px">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Nom</th>
                                    <th>Permissions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fonctions as $i => $fonction)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $fonction->lib_fonction }}</td>
                                    <td>{{ implode(', ', $fonction->fonctionnalites->pluck('lib_fonctionnalite')->unique()->toArray()) }}</td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-primary shadow btn-xs sharp me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEdit{{ $fonction->code_fonction }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>

                                        <form style="display:inline-block"
                                            action="{{ route('fonction.destroy', $fonction->code_fonction) }}"
                                            method="POST"
                                            class="form-delete-fonction">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger shadow btn-xs sharp" type="submit">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>

                                        <a href="{{ route('fonction.assigner', $fonction->code_fonction) }}"
                                            class="btn btn-info shadow btn-xs sharp ms-1"
                                            title="Assigner des permissions">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>N°</th>
                                    <th>Nom</th>
                                    <th>Permissions</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Modal Ajout ─────────────────────────────────────────────────────── --}}
    <div class="modal fade" id="modalAjoutFonction"
        data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="labelAjoutFonction" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="labelAjoutFonction">Nouvelle fonction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form action="{{ route('fonction.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label">Libellé <span class="text-danger">*</span></label>
                            <input type="text" name="lib_fonction" required
                                class="form-control @error('lib_fonction') is-invalid @enderror"
                                value="{{ old('lib_fonction') }}"
                                placeholder="Nom de la fonction">
                            @error('lib_fonction')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-sm btn-primary">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Modals Édition (un par fonction, hors du tableau) ───────────────── --}}
    @foreach ($fonctions as $fonction)
    <div class="modal fade" id="modalEdit{{ $fonction->code_fonction }}"
        data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="labelEdit{{ $fonction->code_fonction }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="labelEdit{{ $fonction->code_fonction }}">
                        Modification — {{ $fonction->lib_fonction }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form action="{{ route('fonction.update', $fonction->code_fonction) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label">Libellé <span class="text-danger">*</span></label>
                            <input type="text" name="lib_fonction" required
                                class="form-control"
                                value="{{ $fonction->lib_fonction }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-sm btn-warning">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

@endsection
@section('scripts')
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

    <script>
        // Confirmation avant suppression
        $(document).on('submit', '.form-delete-fonction', function (e) {
            e.preventDefault();
            var form = this;
            Swal.fire({
                title: 'Confirmer la suppression',
                text: 'Cette fonction sera supprimée définitivement.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Supprimer',
                cancelButtonText: 'Annuler'
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
