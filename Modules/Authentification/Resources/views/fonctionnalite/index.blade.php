@extends('layout.app')
@section('titre')
Liste des fonctionalités
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
                    <h4> Liste des fonctionnalités</h4>
                    <a href="{{ route("fonctionnalite.create") }}"><button type="button" class="btn btn-sm btn-warning">Créer une fonctionnalite</button></a>

                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="display" style="min-width: 845px">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Libéllé</th>
                                        {{--     <th>Libéllé thechnique</th> --}}
                                            <th>Desciption</th>
                                            <th>Module</th>
                                            <th>Statut</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i= 1; ?>
                                        @forelse ($fonctionnalites as $item)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $item->lib_fonctionnalite }}</td>
                                          {{--   <td>{{ $item->lib_technique }}</td> --}}
                                            <td>{{ $item->description_fonctionnalite }}</td>
                                            <td>{{ $item->module->lib_module }}</td>
                                            @if($item->etat_fonctionnalite == "Activé")
                                            <td><span class="badge light badge-success" style="font-size: 13px;font-weight:600;">{{ $item->etat_fonctionnalite}}</span></td>
                                            @endif
                                            @if($item->etat_fonctionnalite == "Désactivé")
                                            <td><span class="badge light badge-danger" style="font-size: 13px;font-weight:600;">{{ $item->etat_fonctionnalite}}</span></td>
                                            @endif
                                            <td>
                                                <div class="btn-group btn-group-xs">
                                                    <a href="{{ route('fonctionnalite.edit',$item->code_fonctionnalite) }}" class="btn btn-info shadow btn-xs sharp me-1"><i class="fas fa-pencil-alt"></i></a>
                                                </div>
                                                <div class="btn-group btn-group-xs">
                                                    <form action="{{ route("fonctionnalite.destroy", $item->code_fonctionnalite) }}" method="post">
                                                        @csrf
                                                        @method("DELETE")
                                                        <button class="btn btn-danger shadow btn-xs sharp" type="submit"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                            <div class="invalid-feedback">Aucune donnée disponible</div>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Libéllé</th>
                                           {{--  <th>Libéllé thechnique</th> --}}
                                            <th>Desciption</th>
                                            <th>Module</th>
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
@endsection
@section('scripts')
      <!-- Datatable -->
      <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>
@endsection
