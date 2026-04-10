@extends('layout.app')
@section('titre')
livrets des familles
@endsection
@section("styles")

<link href="{{ asset('tpl/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css')}}" rel="stylesheet">
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">
@endsection

@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Liste des livrets des familles</h4>
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
                                        <th>Régime</th>
                                        {{-- <th>Statut: Déclaration</th> --}}
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i=1;
                                    @endphp
                                    @forelse ($acteMariages as $am)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $am->declaration->epoux->nomcomplet() }}</td>
                                            <td>{{ $am->declaration->epouse->nomcomplet() }}</td>
                                            <td>{{ $am->declaration->regime->lib_regime }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-primary light sharp" data-bs-toggle="dropdown">
                                                        <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="{{ route('etatMariage.livretFamille',$am->code_acte_mariage) }}">Voir le livret</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <span class="">Aucune donnée trouvée</span>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Identité Epoux</th>
                                        <th>Identité Epouse</th>
                                        <th>Régime</th>
                                        {{-- <th>Statut: Déclaration</th> --}}
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
<script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

@endsection
