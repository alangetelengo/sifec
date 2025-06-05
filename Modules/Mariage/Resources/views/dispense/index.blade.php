

@extends('layout.app')
@section('titre')
Dispense de mariage
@endsection
@section("styles")

<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
    <link href='https://css.gg/airplane.css' rel='stylesheet'>
@endsection
@section('sous-titre')
    Liste des dispenses de mariage
@endsection
@section('corps')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Liste des dispenses de mariage</h4>
                
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>#</th>
										<th>Numéro dispense</th>
                                        <th>Numéro déclaration</th>
                                        <th>Nom Epoux</th>
                                        <th>Nom Epouse</th>
                                        <th>Date Mariage</th>
                                        <th>Lieu Mariage</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- loop data -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Numéro dispense</th>
                                        <th>Numéro déclaration</th>
                                        <th>Nom Epoux</th>
                                        <th>Nom Epouse</th>
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
@endsection
@section("scripts")

<!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

@endsection
