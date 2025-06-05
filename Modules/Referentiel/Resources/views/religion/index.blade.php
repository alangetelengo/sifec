@extends('layout.app')
@section('titre')
    religions
@endsection
@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endsection
@section('corps')
    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h4>Créer une religion</h4>
                </div>
                <div class="card-body">
                    <div class="basic-form">
                        <form method="POST" action="{{ route('religion.store') }}">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-12">
                                    <label class="form-label">Libéllé</label>
                                    <input  class="form-control form-control-sm @error("lib_religion") is-invalid @enderror " name="lib_religion" type="text" value="{{ old('lib_religion') }}" placeholder="réligion">
                                    @error("lib_religion")
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary">Valider</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des réligions</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="display" style="min-width: 845px">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Libellé</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?= $i=1; ?>
                                @foreach ($religions as $religion)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $religion->lib_religion }}</td>
                                    <td>
                                        <div class="d-flex">

                                            <button type="button" class="btn btn-primary shadow btn-xs sharp me-1" data-bs-toggle="modal" data-bs-target=".bd-{{ $i }}-modal-sm"><i class="fas fa-pencil-alt"></i></button>

                                            <div class="modal fade bd-{{ $i }}-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Modification de {{ $religion->lib_religion }}</h5>
                                                            <button type="button" class="btn btn-sm-close" data-bs-dismiss="modal">
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('religion.update',$religion->code_religion) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="mb-3 col-md-12">
                                                                    <label class="form-label">Libéllé</label>
                                                                    <input  class="form-control form-control-sm" name="lib_religion" type="text" value="{{ $religion->lib_religion }}">
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



                                            <form action="{{ route('religion.destroy',$religion->code_religion) }}" method="post">
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
                                    <th>Libellé</th>
                                    <th>Actions</th>
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
