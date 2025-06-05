@extends('layout.app')
@section('titre')
Formulaire type
@endsection
@section("styles")

<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
    <link href='https://css.gg/airplane.css' rel='stylesheet'>
@endsection
@section('sous-titre')
    Liste des formulaires types de mariage
@endsection
@section('corps')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Liste des formulaires types de mariage</h4>
                <a href="{{ route("declarationMariage.create") }}"><button type="button" class="btn btn-info m-t-2 float-end text-white" >Créer formulaire type  <i class="fa fa-plus-circle"></i></button></a>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>Numéro déclaration</th>
                                        <th>Epoux</th>
                                        <th>Nationalité Epoux</th>
                                        <th>Epouse</th>
                                        <th>Nationalité Epouse</th>
                                        <th>Régime</th>
                                        <th>Date déclaration</th>
                                        <th>Statut Déclaration</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @foreach ($dms as $dm)
                                    <tr>
                                        <td>{{ $dm->code_declaration_mariage }}</td>
                                        <td>{{ $dm->epoux->nom." ".$dm->epoux->prenom }}</td>
                                        <td>{{ $dm->epoux->nationalite->lib_nationalite }}</td>
                                        <td>{{ $dm->epouse->nom." ".$dm->epouse->prenom }}</td>
                                        <td>{{ $dm->epouse->nationalite->lib_nationalite }}</td>
                                        <td>{{ $dm->regime->lib_regime ?? " Non déclaré" }}</td>
                                        <td>{{ date('d-m-Y', strtotime($dm->date_declaration_mariage)) }}</td>
                                        @if($dm->acte != null && $dm->signatureActe !=null && $dm->acte->approbation_mairie != null)
                                        <td><span class="badge light badge-success" style="font-size: 13px;font-weight:600;">Acte produit</span></td>
                                        @else
                                        <td><span class="badge light badge-danger" style="font-size: 13px;font-weight:600;">Encours de traitement </span></td>
                                        @endif
                                        <td>
                                            <div class="btn-group btn-group-xs">
                                                <a href="{{ route('etatMariage.declaration',$dm->code_declaration_mariage) }}" target="_blank" title="Voir la déclaration" class="btn btn-warning shadow btn-xs sharp me-1"><i class="fas fa-print"></i></a>

                                                @if($dm->acte != null && $dm->signatureActe !=null && $dm->acte->approbation_mairie != null)
                                                 <a href="{{ route('acteMariage.display',$dm->code_declaration_mariage) }}" target="_blank" class="btn btn-success shadow btn-xs sharp me-1" title="Voir l'acte"><i class="fas fa-eye"></i></a>
                                                @endif
                                                @if($dm->acte != null  && $dm->signatureActe ==null)
                                                    <a href="{{ route('declarationMariage.edit',$dm->code_declaration_mariage) }}" class="btn btn-info shadow btn-xs sharp me-1" title="Modifier la déclaration"><i class="fas fa-pencil-alt"></i></a>
                                                    <form  action="{{ route('declarationMariage.destroy',$dm->code_declaration_mariage) }}" method="POST" style="display: inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger shadow btn-xs sharp" title="Annuler"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                  @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Numéro déclaration</th>
                                        <th>Epoux</th>
                                        <th>Nationalité Epoux</th>
                                        <th>Epouse</th>
                                        <th>Nationalité Epouse</th>
                                        <th>Regime</th>
                                        <th>Date déclaration</th>
                                        <th>Statut Déclaration</th>
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
@section("scripts")

<!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

@endsection
