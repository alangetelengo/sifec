@extends('layout.app')
@section('titre')
Jugements
@endsection
@section("styles")

<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endsection

@section('corps')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Jugements aux fins de reconstitution de l'acte de naissance</h4>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Déclarant</th>
                                        <th>Enfant: Nom</th>
                                        <th>Enfant: Prénom</th>
                                        <th>Enfant: Date naissance</th>
                                        <th>Enfant: Sexe</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($requisitions as $requisition)
                                    <tr width="100%">
                                        <td>{{ $i++}}</td>
                                        <td>{{ $requisition->declarant->nom.' '.$requisition->Declarant->prenom }}</td>
                                        <td>{{ $requisition->enfant->nom }}</td>
                                        <td>{{ $requisition->enfant->prenom }}</td>
                                        <td>{{ date("d-m-Y", strtotime($requisition->enfant->date_naissance)) }}</td>
                                        <td>{{ $requisition->enfant->sexe == "M" ? "Masculin" : "Féminin" }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-success light sharp" data-bs-toggle="dropdown">
                                                    <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                                </button>
                                                <div class="dropdown-menu">
                                                    @if($requisition->top_jugement == 1)
                                                        <a class="dropdown-item" href="{{route('requisition.etatJugement',$requisition->code_declaration_naissance)}}" target="_blank">Voir le jugement</a>
                                                    @else
                                                        <form action="{{ route('requisition.generateJugement',$requisition->code_declaration_naissance) }}" method="post">
                                                            @csrf
                                                            @method("PUT")
                                                            <button type="submit" class="dropdown-item">Générer le jugement</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Déclarant</th>
                                        <th>Enfant: Nom</th>
                                        <th>Enfant: Prénom</th>
                                        <th>Enfant: Date naissance</th>
                                        <th>Enfant: Sexe</th>
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
