@extends('layout.app')
@section('titre')
    professions
@endsection
@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endsection
@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Créer une profession</h4>
                </div>
                <div class="card-body">
                    <div class="basic-form">
                        <form method="POST" action="{{ route('profession.store') }}">
                            @csrf
                            <div class="row">
                                <div class="mb-3">
                                    <label class="form-label">Libéllé</label>
                                    <input  class="form-control form-control-sm @error("lib_profession") is-invalid @enderror " name="lib_profession" type="text" value="{{ old('lib_profession') }}" placeholder="Profession">
                                    @error("lib_profession")
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
                    <h4>Liste des professions</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="display">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Libellé</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?= $i=1; ?>
                                @foreach ($professions as $profession)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $profession->lib_profession }}</td>
                                    <td>
                                        <div class="d-flex">

                                            <button type="button" class="btn btn-primary shadow btn-xs sharp me-1" data-bs-toggle="modal" data-bs-target=".bd-{{ $i }}-modal-sm"><i class="fas fa-pencil-alt"></i></button>

                                            <div class="modal fade bd-{{ $i }}-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Modification de {{ $profession->lib_profession }}</h5>
                                                            <button type="button" class="btn btn-sm-close" data-bs-dismiss="modal">
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('profession.update',$profession->code_profession) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="mb-3 col-md-12">
                                                                    <label class="form-label">Libéllé</label>
                                                                    <input  class="form-control form-control-sm" name="lib_profession" type="text" value="{{ $profession->lib_profession }}">
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



                                            <form action="{{ route('profession.destroy',$profession->code_profession) }}" method="post">
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
</div>
</div>
</div>
@endsection
@section('scripts')
      <!-- Datatable -->
      <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>
@endsection
