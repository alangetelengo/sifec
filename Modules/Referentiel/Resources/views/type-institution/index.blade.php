@extends('layout.app')
@section('titre')
   Types d'institution
@endsection
@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">

@endsection
@section('corps')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Créer un type d'institution</h4>
                </div>
                <div class="card-body">
                    <div class="basic-form">
                        <form method="POST" action="{{ route('typeInstitution.store') }}">
                            @csrf
                            <div class="row">
                                <div class="mb-2 col-md-12">
                                    <label class="form-label">Type d'institution<span class="text-danger">*</span></label>

                                    <input type="text" name="lib_type_institution" class="form-control @error('lib_type_institution') is-invalid @enderror" value="{{ old("lib_type_institution") }}">
                                    @error("lib_type_institution")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-2 col-md-12">
                                    <label class="form-label">Catégorie<span class="text-danger">*</span></label>
                                    <input type="text" name="lib_categorie" class="form-control @error('lib_categorie') is-invalid @enderror" value="{{ old("lib_categorie") }}">
                                    @error("lib_categorie")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2"><button type="submit" class="btn btn-sm btn-primary">Valider</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des types d'institution</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="display" style="min-width: 845px">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Catégorie</th>
                                    <th>Type d'institution</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?= $i=1; ?>
                                @foreach ($typeInstitutions as $item)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $item->lib_categorie }}</td>
                                    <td>{{ $item->lib_type_institution }}</td>
                                    <td>
                                        <div class="d-flex">

                                            <button type="button" class="btn btn-primary shadow btn-xs sharp me-1" data-bs-toggle="modal" data-bs-target=".bd-{{ $i }}-modal-sm"><i class="fas fa-pencil-alt"></i></button>
                                            <div class="modal fade bd-{{ $i }}-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Modification de {{ $item->lib_type_institution }}</h5>
                                                            <button type="button" class="btn btn-sm-close" data-bs-dismiss="modal">
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('typeInstitution.update',$item->code_type_institution) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="mb-3 col-md-12">
                                                                    <label class="form-label">Type d'institution</label>
                                                                    <input  class="form-control form-control-sm" name="lib_type_institution" type="text" value="{{ $item->lib_type_institution }}">
                                                                </div>
                                                                <div class="mb-3 col-md-12">
                                                                    <label class="form-label">Catégorie</label>
                                                                    <input  class="form-control form-control-sm" name="lib_categorie" type="text" value="{{ $item->lib_categorie }}">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Fermer</button>
                                                                <button type="submit" class="btn btn-sm btn-warning ">Modifier</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <form action="{{ route('typeInstitution.destroy',$item->code_type_institution) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger shadow btn-xs sharp" type="submit"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>N°</th>
                                    <th>Catégorie</th>
                                    <th>Type d'institution</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
      <!-- Datatable -->
      <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>
@endsection
