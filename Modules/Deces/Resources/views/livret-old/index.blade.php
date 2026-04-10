@extends('layout.app')
@section('titre')
Liste des régistres d'état civil
@endsection
@section('styles')

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
                <div class="card-header"> <h4>Liste des régistres des déces de la bompe funebre</h4> </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display table table-bordered table-hover table-striped">
                                <thead>
                                    <tr class="text-center">
                                        <th>N°</th>
                                        <th>régistre</th>
                                        <th>Type <br> régistre</th>
                                        <th>Date <br> ouverture</th>
                                        <th>Date <br> fermeture</th>
                                        <th>Nombre <br> d'acte transcrit</th>
                                        <th>Nombre <br> d'acte prévu</th>
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                      $i=1;  
                                    @endphp
                                    @foreach ($registres as $registre)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $registre->lib_registre }}</td>
                                        <td>{{ $registre->typeRegistre->lib_type_registre }}</td>
                                        <td class="text-center">{{ date("d-m-Y", strtotime($registre->date_ouverture)) }}</td>
                                        <td class="text-center">{{ date("d-m-Y", strtotime($registre->date_fermeture)) }}</td>
                                        <td class="text-center">{{ $registre->nombre_acte_transcrit }}</td>
                                        <td class="text-center">{{ $registre->nombre_acte_prevu}}</td>
                                        @if($registre->statut == "1")
                                        <td><span class="badge light badge-success" style="font-size: 13px;font-weight:600;">Activé</span></td>
                                        @endif
                                        @if($registre->statut == "0")
                                        <td><span class="badge light badge-danger" style="font-size: 13px;font-weight:600;">Désactivé</span></td>
                                        @endif
                                        <td class="text-center">
                                            <a href="{{ route('livretDeces.shows', $registre->code_registre) }}" target="_blank" title="Consulter les actes du registre" class="btn btn-warning shadow btn-xs sharp me-1"><i class="fas fa-eye"></i></a>    
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>N°</th>
                                        <th>régistre</th>
                                        <th>Type régistre</th>
                                        <th>Date ouverture</th>
                                        <th>Date fermeture</th>
                                        <th>Nombre d'acte transcrit</th>
                                        <th>Nombre d'acte prévu</th>
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
@endsection
@section('scripts')
      <!-- Datatable -->
      <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>
@endsection
