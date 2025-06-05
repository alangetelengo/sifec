@extends('layout.app')
@section('titre')
  fonctions
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
                    <button type="button" class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#modalFonction">
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
                                <?= $i=1; ?>
                                @foreach ($fonctions as $fonction)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $fonction->lib_fonction }}</td>
                                    <td>{{ implode(",",$fonction->fonctionnalites->pluck("lib_fonctionnalite")->unique()->toArray())}}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary shadow btn-xs sharp me-1" data-bs-toggle="modal" data-bs-target=".bd-{{ $i }}-modal-sm"><i class="fas fa-pencil-alt"></i></button>
                                        <div class="modal fade bd-{{ $i }}-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Modification de {{ $fonction->lib_fonction }}</h5>
                                                        <button type="button" class="btn btn-sm-close" data-bs-dismiss="modal">
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('fonction.update',$fonction->code_fonction) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3 col-md-12">
                                                                <label class="form-label">Libéllé</label>
                                                                <input  class="form-control form-control-sm" name="lib_fonction" type="text" value="{{ $fonction->lib_fonction }}">
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
                                        <form style="display: inline-block" action="{{ route('fonction.destroy',$fonction->code_fonction) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger shadow btn-xs sharp" type="submit"><i class="fa fa-trash"></i></button>
                                        </form>
                                        <a href="{{ route("fonction.assigner",$fonction->code_fonction) }}" class="btn btn-info shadow btn-xs sharp me-1" title="assignation"><i class="fas fa-plus"></i></a>
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

    <div class="modal fade" id="modalFonction" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Libéllé de la fonction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form  action="{{ route("fonction.store") }}"  method="POST">
                    @csrf

                    <div class="method"></div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-2 col-md-12">
                                <label class="form-label">Libéllé <span class="text-danger">*</span></label>
                                <input type="text" name="lib_fonction" required class="form-control @error('lib_fonction') is-invalid @enderror" value="{{ old("lib_fonction") }}"  id="lib_fonction">
                                @error("lib_fonction")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
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
      <!-- Datatable -->
      <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

      <script>
     /*    $(function(){
            $("a.show-edit-fonction").on("click", function() {
                var me = $(this);
                var lib_fonction = me.attr('data-fonction');
                var code_fonction = me.attr('data-code');
                var route = "{{ route('fonction.update', ':id') }}";
                route = route.replace(':id',code_fonction);

                $("#lib_fonction").val(lib_fonction);
                $(".modal-title").html("Modification "+ lib_fonction);
                $("form.action").attr(route);
                $("#method").html('@method("PUT")');
                var modal = $("#modalFonction").modal("show");

                return false;
            });
        }); */
      </script>
@endsection
