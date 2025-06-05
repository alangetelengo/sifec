@extends('layout.app')
@section('titre')
    Causes du décès
@endsection
@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">

@endsection
@section('corps')

    <div class="row">
        <div class="col-xl-5">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Créer une cause du décès</h4>
                </div>
                <div class="card-body">
                    <div class="basic-form">
                        <form method="POST" action="{{ route('causedeces.store') }}">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-12">
                                    <label class="form-label">Libéllé</label>
                                    <input  class="form-control form-control-sm @error("lib_cause_deces") is-invalid @enderror " name="lib_cause_deces" type="text" value="{{ old('lib_cause_deces') }}" placeholder="Cause du décès">
                                    @error("lib_cause_deces")
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
        <div class="col-xl-7">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des causes</h4>
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
                                @foreach ($causeDeces as $deces)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $deces->lib_cause_deces }}</td>
                                    <td>
                                        <div class="d-flex">

                                            <button type="button" class="btn btn-primary shadow btn-xs sharp me-1" data-bs-toggle="modal" data-bs-target=".bd-{{ $i }}-modal-sm"><i class="fas fa-pencil-alt"></i></button>

                                            <div class="modal fade bd-{{ $i }}-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Modification de {{ $deces->lib_cause_deces }}</h5>
                                                            <button type="button" class="btn btn-sm-close" data-bs-dismiss="modal">
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('causedeces.update',$deces->code_cause_deces) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="mb-3 col-md-12">
                                                                    <label class="form-label">Libéllé</label>
                                                                    <input  class="form-control form-control-sm" name="lib_cause_deces" type="text" value="{{ $deces->lib_cause_deces }}">
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



                                            <form action="{{ route('causedeces.destroy',$deces->code_cause_deces) }}" method="post">
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
