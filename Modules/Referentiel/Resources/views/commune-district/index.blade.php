@extends('layout.app')
@section('titre')
Communes-Districts
@endsection
@section('styles')

<link href="{{ asset('tpl/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css')}}" rel="stylesheet">
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">
@endsection

@section('corps')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des communes-districts des départements</h4>
                    <button type="button" class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#modalDept">
                        Ajouter
                    </button>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="display" style="min-width: 845px">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Commune/District</th>
                                            <th>type Localité</th>
                                            <th>Département</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=1; ?>
                                        @foreach ($subDepartements as $item)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $item->lib_localite }}</td>
                                                <td>{{ $item->typeLocalite->lib_type_localite }}</td>
                                                <td>{{ $item->localiteParent != null ? $item->localiteParent->lib_localite : "-"}}</td>
                                                <td>
                                                    <div class="btn-group btn-group-xs">
                                                        <button type="button" class="btn btn-primary shadow btn-xs sharp me-1" data-bs-toggle="modal" data-bs-target=".bd-{{$item->code_localite}}-modal-sm"><i class="fas fa-pencil-alt"></i></button>
                                                        <div class="modal fade bd-{{$item->code_localite}}-modal-sm" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Modification de {{ $item->lib_localite }}</h5>
                                                                        <button type="button" class="btn btn-sm-close" data-bs-dismiss="modal">
                                                                        </button>
                                                                    </div>

                                                                    <form action="{{ route('communedistrict.update',$item->code_localite) }}" method="POST" enctype="multipart/form-data">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="mb-2 col-md-12">
                                                                                    <label class="form-label">Libéllé <span class="text-danger">*</span></label>
                                                                                    <input type="text" class="form-control" class="form-control" value="{{ $item->lib_localite }}" name="lib_localite">
                                                                                </div>
                                                                                <div class="mb-2 col-md-12">
                                                                                    <label class="form-label">Type localité <span class="text-danger">*</span></label>
                                                                                    <select name="code_type_localite" required class="form-control form-control wide">
                                                                                        @foreach ($typeLocalites as $typeloc)
                                                                                            <option value="{{ $typeloc->code_type_localite }}" {{ $typeloc->code_type_localite == $item->code_type_localite ? "selected" : "" }}>{{ $typeloc->lib_type_localite }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="mb-2 col-md-12">
                                                                                    <label class="form-label">Département <span class="text-danger">*</span></label>
                                                                                    <select name="code_localite_parent" class="form-control form-control wide">
                                                                                        @foreach ($departements as $dept)
                                                                                            <option value="{{ $dept->code_localite }}" {{ $item->code_localite_parent != null ? $item->code_localite_parent == $dept->code_localite ? "selected" : "" : "" }}>{{ $dept->lib_localite }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="submit" class="btn btn-sm btn-primary ">Modifier</button>
                                                                            <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Fermer</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="btn-group btn-group-xs">
                                                        <form action="{{ route("communedistrict.destroy",$item->code_localite) }}" method="post">
                                                            @csrf
                                                            @method("DELETE")
                                                            <button class="btn btn-danger shadow btn-xs sharp" type="submit"><i class="fa fa-trash"></i></button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Commune/District</th>
                                            <th>type Localité</th>
                                            <th>Département</th>
                                            <th>Action</th>
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


      <!-- Large modal -->
        <div class="modal fade" id="modalDept" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Information de la localité</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form  action="{{ route("communedistrict.store") }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="mb-2 col-md-12">
                                    <label class="form-label">Libéllé <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" class="form-control @error('lib_localite') is-invalid @enderror" value="{{ old("lib_localite") }}" required  name="lib_localite">
                                </div>
                                <div class="mb-2 col-md-12">
                                    <label class="form-label">Type localité <span class="text-danger">*</span></label>
                                    <select name="code_type_localite" required class="form-control form-control wide">
                                        <option value="">Choisissez</option>
                                        @foreach ($typeLocalites as $item)
                                            <option value="{{ $item->code_type_localite }}">{{ $item->lib_type_localite }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2 col-md-12">
                                    <label class="form-label">Département <span class="text-danger">*</span></label>
                                    <select name="code_localite_parent" required class="form-control form-control wide">
                                        <option value="" selected disabled>Selectionner</option>
                                        @foreach ($departements as $item)
                                            <option value="{{ $item->code_localite }}">{{ $item->lib_localite }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-primary">Valider</button>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Fermer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
@section('scripts')
      <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

@endsection
