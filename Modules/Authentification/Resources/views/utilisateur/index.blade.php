@extends('layout.app')
@section('titre')
    Utilisateurs
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
                <h4>Liste des utilisateurs</h4>
                <a href="{{ route("utilisateur.create") }}"><button type="button" class="btn btn-sm btn-warning m-t-2 float-end text-white" >Créer un utilisateur  <i class="fa fa-plus-circle"></i></button></a>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Agent</th>
                                        <th>Login</th>
                                        <th>Centre état civil</th>
                                        <th>Fonction</th>
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i=1;
                                    @endphp
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $user->personne->nom." ".$user->personne->prenom }}</td>
                                            <td>{{ $user->email }}</td>

                                            <td>{{ $user->affectationActive()->institution->lib_institution ?? "" }}</td>
                                            <td>{{ $user->affectationActive()->fonction->lib_fonction ?? "" }}</td>
                                            <td>
                                                @if ($user->status == 1)
                                                    <span class="badge light badge-success" style="font-size: 13px;font-weight:600;">Activé</span>
                                                @else
                                                    <span class="badge light badge-danger" style="font-size: 13px;font-weight:600;">Désactivé</span>
                                                @endif
                                            </td>

                                           {{--  <td>{{ $user->institutionUser->institution->lib_institution }}</td>
                                            <td>{{ $user->institutionUser->institution->localite->lib_localite }}</td>
 --}}
                                            <td>
                                                <div class="btn-group btn-group-xs">
                                                    <a href="{{ route('utilisateur.profile',$user->code_user) }}" class="btn btn-info shadow btn-xs sharp me-1" title="profile"><i class="fa fa-eye"></i></a>
                                                    <a href="{{ route('utilisateur.edit',$user->code_user) }}" class="btn btn-warning shadow btn-xs sharp me-1" title="modifier"><i class="fa fa-edit"></i></a>
                                                    <a href="{{ route("utilisateur.assigner.permission",$user->code_user) }}" class="btn btn-info shadow btn-xs sharp me-1" title="assignation-permission"><i class="fas fa-plus"></i></a>

                                                   <form action="{{ route('utilisateur.destroy',$user->code_user) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger shadow btn-xs sharp" title="supprimer"><i class="fa fa-trash"></i></button>
                                                </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Agent</th>
                                        <th>Login</th>
                                        <th>Centre état civil</th>
                                        <th>Fonction</th>
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
