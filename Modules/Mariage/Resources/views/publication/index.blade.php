@extends('layout.app')
@section('titre')
Publication mariage
@endsection
@section("styles")

<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
    <link href='https://css.gg/airplane.css' rel='stylesheet'>
@endsection
@section('sous-titre')
    Liste des publications de mariage
@endsection
@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Liste des publications de mariage</h4>

            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Identité Epoux</th>
                                        <th>Identité Epouse</th>
                                        <th>Date mariage</th>
                                        <th>Lieu mariage</th>
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $i=1;
                                @endphp
                                @forelse ($declarations as $dm)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $dm->epoux->nomcomplet() }}</td>
                                        <td>{{ $dm->epouse->nomcomplet() }}</td>
                                        <td>{{ date("d/m/Y", strtotime($dm->date_declaration_mariage)) }}</td>
                                        <td>{{ $dm->lieu_ceremonie_mariage }}</td>
                                        <td>
                                            @if($dm->acte != null && $dm->acte->approbation_tribunal == 1 && $dm->acte->approbation_mairie == 0)
                                            <span class="badge light badge-warning" style="font-size: 13px;font-weight:600;">En cours de publication</span>
                                            @endif
                                            @if($dm->acte != null && $dm->acte->approbation_tribunal == 1 && $dm->acte->approbation_mairie == 1)
                                            <td><span class="badge light badge-success" style="font-size: 13px;font-weight:600;">Mariage Célébré</span></td>
                                             @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-primary light sharp" data-bs-toggle="dropdown">
                                                    <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('publicationMariage.show',$dm->code_declaration_mariage) }}" target="_blank">Voir le ban</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Identité Epoux</th>
                                        <th>Identité Epouse</th>
                                        <th>Date mariage</th>
                                        <th>Lieu mariage</th>
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
</div>
</div>
</div>
@endsection
@section("scripts")

<!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

@endsection
