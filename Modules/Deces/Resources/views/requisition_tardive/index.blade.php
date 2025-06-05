@extends('layout.app')
@section('titre')
Réquisitions
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
                <h4>Requisitions aux fins de déclaration tardive de décès</h4>
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
                                        <th>Défunt(e): Nom</th>
                                        <th>Défunt(e): Prénom</th>
                                        <th>Défunt(e): Date deces</th>
                                        <th>Défunt(e): Sexe</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requisitions as $requisition)
                                    <tr width="100%">
                                        <td>{{ $requisition->numero_req}}/{{date("Y", strtotime($requisition->created_at))}}</td>
                                        <td>{{ $requisition->declarant->nom.' '.$requisition->Declarant->prenom }}</td>
                                        <td>{{ $requisition->defunt->nom }}</td>
                                        <td>{{ $requisition->defunt->prenom }}</td>
                                        <td>{{ date("d-m-Y", strtotime($requisition->date_heure_deces)) }}</td>
                                        <td>{{ $requisition->defunt->sexe == "M" ? "Masculin" : "Féminin" }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-success light sharp" data-bs-toggle="dropdown">
                                                    <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                                </button>
                                                <div class="dropdown-menu">
                                                    @if($requisition->top_requisition == 1)

                                                <a class="dropdown-item" href="{{route('RequisitionTardiveDeces.etat',$requisition->code_declaration_deces)}}" target="_blank">Voir la réquisition</a>
                                                @else
                                                <a class="dropdown-item" href="{{route('declarationDeces.etat',$requisition->code_declaration_deces)}}" target="_blank">Voir la déclaration tardive</a>

                                                <form action="{{ route('RequisitionTardiveDeces.generateRequisition',$requisition->code_declaration_deces) }}" method="post">
                                                    @csrf
                                                    @method("PUT")
                                                    <button type="submit" class="dropdown-item">Générer la réquisition</button>
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
                                        <th>Enfant: Date deces</th>
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
