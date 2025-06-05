@extends('layout.app')
@section('titre')
Certificat non inscription
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
                <h4>Liste des certificats de non inscription des actes de décès</h4>
                {{-- <a href="{{ route('certificatNonInscription.create')}}"><button type="button" class="btn btn-info m-t-2 float-end text-white" >Créer un certificat de non inscription  <i class="fa fa-plus-circle"></i></button></a> --}}
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
                                        <th>Défunt: Nom</th>
                                        <th>Défunt: Prénom</th>
                                        <th>Défunt: Date du décès</th>
                                        <th>Défunt: Sexe</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1; ?>
                                    @foreach ($certificatNonInscriptions as $certificat)
                                    <tr width="100%">
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $certificat->declarant->nom . ' '.$certificat->declarant->prenom }}</td>
                                        <td>{{ $certificat->defunt->nom }}</td>
                                        <td>{{ $certificat->defunt->prenom }}</td>
                                        <td>{{ date("d-m-Y", strtotime($certificat->date_heure_deces)) }}</td>
                                        <td>{{ $certificat->defunt->sexe == "M" ? "Masculin" : "Féminin" }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-success light sharp" data-bs-toggle="dropdown">
                                                    <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a href="{{ route('certificatNonInscriptionDeces.displayCertificat',$certificat->code_declaration_deces) }}" target="_blanck">Afficher le certificat</a>
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
                                        <th>Défunt: Nom</th>
                                        <th>Défunt: Prénom</th>
                                        <th>Défunt: Date du décès</th>
                                        <th>Défunt: Sexe</th>
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
<script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

@endsection
