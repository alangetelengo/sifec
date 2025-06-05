@extends('layout.app')
@section('titre')
Liste des tribunaux
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
                    <h4>Liste des tribunaux</h4>
                    <button type="button" class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#modalTtribunal">
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
                                            <th>Sceau</th>
                                            <th>Tribunal</th>
                                            {{-- <th>Cour d'appel</th> --}}
                                            <th>Statut</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $i=1; ?>
                                        @foreach ($tribunaux as $tribunal)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>
                                                    @if($tribunal->sceau != null)
                                                    <img src='{{ asset("app/".$tribunal->sceau) }}' width="100px" height="100px" alt="">
                                                    @endif

                                                </td>
                                                <td>{{ $tribunal->lib_institution }}</td>
                                                {{-- <td>{{ $tribunal->courAppel->lib_cour_appel }}</td> --}}
                                                @if($tribunal->statut == "1")
                                                <td><span class="badge light badge-success" style="font-size: 13px;font-weight:600;">Activé</span></td>
                                                @endif
                                                @if($tribunal->statut == "0")
                                                <td><span class="badge light badge-danger" style="font-size: 13px;font-weight:600;">Désactivé</span></td>
                                                @endif
                                                <td>
                                                    <div class="btn-group btn-group-xs">
                                                        <button type="button" class="btn btn-primary shadow btn-xs sharp me-1" data-bs-toggle="modal" data-bs-target=".bd-{{$tribunal->code_institution}}-modal-sm"><i class="fas fa-pencil-alt"></i></button>
                                                        <div class="modal fade bd-{{$tribunal->code_institution}}-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Modification de {{ $tribunal->lib_institution }}</h5>
                                                                        <button type="button" class="btn btn-sm-close" data-bs-dismiss="modal">
                                                                        </button>
                                                                    </div>
                                                                    <form action="{{ route('tribunal.update',$tribunal->code_institution) }}" method="POST" enctype="multipart/form-data">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="mb-2 col-md-6">
                                                                                    <label class="form-label">Tribunal</label>
                                                                                    <input  class="form-control form-control-sm" required name="lib_institution" type="text" value="{{ $tribunal->lib_institution }}">
                                                                                </div>
                                                                                {{-- <div class="mb-2 col-md-6">
                                                                                    <label class="form-label">Cour d'appel <span class="text-danger">*</span></label>
                                                                                    <select id="code_cour_appel" name="code_cour_appel" required class="form-control form-control wide">
                                                                                        @foreach ($courAppels as $courAppel)
                                                                                            <option value="{{ $courAppel->code_cour_appel }}" {{$courAppel->code_cour_appel == $tribunal->code_cour_appel ? "selected":""}}>{{ $courAppel->lib_cour_appel  }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div> --}}
                                                                            </div>
                                                                            <div class="row">

                                                                                <div class="mb-2 col-md-6">
                                                                                    <label class="form-label">Sceau <span class="text-danger"></span></label>
                                                                                    <input type="file" class="form-control" name="sceau" id="sceau">
                                                                                    @if($tribunal->sceau != null)
                                                                                    <img src='{{ asset("app/".$tribunal->sceau) }}' alt="" width="100px" height="100px">
                                                                                    @endif
                                                                                </div>
                                                                                <div class="mb-2 col-md-6">
                                                                                    <label class="form-label">Etat <span class="text-danger">*</span></label>
                                                                                    <select id="statut" name="statut" class="form-control @error('statut') is-invalid @enderror" required>
                                                                                        <option value="1" {{"1"==$tribunal->statut ? "selected":""}}>Actif</option>
                                                                                        <option value="0" {{"0"==$tribunal->statut ? "selected":""}}>Inactif</option>
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
                                                        <form action="{{ route("tribunal.destroy",$tribunal->code_institution) }}" method="post">
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
                                            <th>Sceau</th>
                                            <th>Libéllé</th>
                                            {{-- <th>Cour d'appel</th> --}}
                                            <th>Statut</th>
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
      <div class="modal fade" id="modalTtribunal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Information dutribunal</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form  action="{{ route("tribunal.store") }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">

                    <div class="row">
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Libéllé <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" class="form-control @error('lib_institution') is-invalid @enderror" value="{{ old("lib_institution") }}" required  name="lib_institution">
                        </div>

                        <div class="mb-2 col-md-6">
                            <label class="form-label">Cour d'appel <span class="text-danger">*</span></label>
                            <select id="code_cour_appel" name="code_cour_appel" required class="form-control form-control wide">
                                <option value="">Choisissez</option>
                                @foreach ($courAppels as $item)
                                    <option value="{{ $item->code_cour_appel }}" >{{ $item->lib_cour_appel  }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Sceau <span class="text-danger"></span></label>
                            <input type="file" class="form-control" name="sceau" id="sceau">
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
