@extends('layout.app')
@section('titre')
Actes
@endsection
@section("styles")

<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">

@endsection

@section('corps')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Liste des déclarations de naissance</h4>
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
                                        {{-- <th>Statut</th> --}}
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($actes as $acte)
                                    <tr width="100%">
                                        <td>{{ $acte->niupp}}</td>
                                        <td>{{ $acte->declaration->declarant->nom.' '.$acte->declaration->Declarant->prenom }}</td>
                                        <td>{{ $acte->declaration->enfant->nom }}</td>
                                        <td>{{ $acte->declaration->enfant->prenom }}</td>
                                        <td>{{ date("d-m-Y", strtotime($acte->declaration->enfant->date_naissance)) }}</td>
                                        <td>{{ $acte->declaration->enfant->sexe == "M" ? "Masculin" : "Féminin" }}</td>

                                        <td>
                                            <div class="dropdown ms-auto text-right">
                                                <div class="btn-link show" data-bs-toggle="dropdown" aria-expanded="true">
                                                    <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                                </div>
                                                <form action="{{ route('copie.store')}}" method="POST">
                                                    @csrf
                                                    <input type="text" value="{{$acte->niupp}}" name="niupp" class="d-none">
                                                    <button type="submit" class="btn btn-primary">Crée la copie</button>                                                    
                                                </form>
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
                                       {{--  <th>Statut</th> --}}
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

    <script>
        $(function(){
            
        });
    </script>

@endsection
