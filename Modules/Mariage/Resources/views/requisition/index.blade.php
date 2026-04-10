

@extends('layout.app')
@section('titre')
Dispense de mariage
@endsection
@section("styles")

<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
    {{-- <link href='https://css.gg/airplane.css' rel='stylesheet'> --}}
@endsection
@section('sous-titre')
    Liste des requisitions de mariage
@endsection
@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Liste des requisitions de mariage</h4>

            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>#</th>
										<th>Numéro requisition</th>
                                        <th>Epoux</th>
                                        <th>Epouse</th>
                                        <th>Date Mariage</th>
                                        <th>Lieu Mariage</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($requisitions as $requisition)
                                    <tr width="100%">
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $requisition->numero_dispense}}</td>
                                        <td>{{ $requisition->epoux->nomcomplet() }}</td>
                                        <td>{{ $requisition->epouse->nomcomplet() }}</td>
                                        <td>{{ date("d-m-Y", strtotime($requisition->date_prevue_mariage)) }}</td>
                                        <td>{{ $requisition->lieu_ceremonie_mariage }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-success light sharp" data-bs-toggle="dropdown">
                                                    <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                                </button>
                                                <div class="dropdown-menu">
                                                    @if ($requisition->top_requisition == 1)
                                                        <a class="dropdown-item" href="{{route('etatMariage.displayRequisition',$requisition->code_declaration_mariage)}}" target="_blank">Voir la réquisition</a>
                                                    @else
                                                        <form action="{{ route('etatMariage.generateRequisition',$requisition->code_declaration_mariage) }}" method="post">
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
                                        <th>Numéro requisition</th>
                                        <th>Epoux</th>
                                        <th>Epouse</th>
                                        <th>Date Mariage</th>
                                        <th>Lieu Mariage</th>
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
