@extends('layout.app')
@section('titre')
    Accueil
@endsection
@section('styles')
<link rel="stylesheet" type="text/css" href="{{asset('carte/css/map.css')}}">
@endsection
@section('corps')

<div class="card">
    @php
        setlocale(LC_TIME, "fr_FR", "French");
    @endphp
    <div class="card-header"> <h4> TABLEAU DE BORD: {{ Auth()->user()->AffectationActive()->institution->lib_institution }}</h4> </div>
    <div class="card-body">

        <div class="row" id="carte">
            <div class="col-12">
                <div class="col-2">
                    <a href="{{ route('tableau.impressionprefet') }}" target="_blank" class="btn btn-primary btn-sm btn-block">Visualiser l'état</a>
                </div><br>
            </div>
            <div class="col-6">
                <table class="table table-striped table-bordered">
                    <tbody>
                    <tr>
                        <td colspan="5" style="background-color: #fff0f5; color: #1E88E5" class="text-center"><strong> ACTES DE NAISSANCE </strong></td>
                    </tr>
                    <tr>
                        <td class="text-center">SITUATION</td>
                        <td class="text-center">PRODUITS</td>
                        <td class="text-center">VALIDES</td>
                        <td class="text-center">NON VALIDES</td>
                    </tr>
                    <tr>
                        <td> CUMULEE</td>
                        <td class="text-center"> <strong><span>{{ $acteproduits[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $acteproduitsv[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $acteproduitsn[0]->total }}</span></strong> </td>
                    </tr>
                    <tr>
                        <td>L'ANNEE</td>
                        <td class="text-center"> <strong><span></span>{{ $acteannee[0]->total }}</strong> </td>
                        <td class="text-center"> <strong><span>{{ $acteanneev[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $acteanneen[0]->total }}</span></strong> </td>
                        </tr>
                    <tr>
                        <td>DU MOIS</td>
                        <td class="text-center"> <strong><span>{{ $actemois[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $actemoisv[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $actemoisn[0]->total }}</span></strong> </td>
                    </tr>
                    <tr>
                        <td>DE LA SEMAINE</td>
                        <td class="text-center"> <strong><span>{{ $actesemaine[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $actesemainev[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $actesemainen[0]->total }}</span></strong> </td>
                        </tr>
                    <tr>
                        <td>DU JOUR</td>
                        <td class="text-center"> <strong><span>{{ $actesjour[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $actesjourv[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $actesjourn[0]->total }}</span></strong> </td>
                    </tr>


                    <tbody>
                </table>
            </div>
            <div class="col-6">
                <table class="table table-striped table-bordered">
                    <tbody>
                    <tr>
                        <td colspan="5" style="background-color: #fff0f5; color: #1E88E5" class="text-center"><strong> ACTES DE DECES </strong></td>
                    </tr>
                    <tr>
                        <td class="text-center">SITUATION</td>
                        <td class="text-center">PRODUITS</td>
                        <td class="text-center">VALIDES</td>
                        <td class="text-center">NON VALIDES</td>
                    </tr>
                    <tr>
                        <td> CUMULEE</td>
                        <td class="text-center"> <strong><span>{{ $dacteproduits[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $dacteproduitsv[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $dacteproduitsn[0]->total }}</span></strong> </td>
                    </tr>
                    <tr>
                        <td>L'ANNEE</td>
                        <td class="text-center"> <strong><span></span>{{ $dacteannee[0]->total }}</strong> </td>
                        <td class="text-center"> <strong><span>{{ $dacteanneev[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $dacteanneen[0]->total }}</span></strong> </td>
                        </tr>
                    <tr>
                        <td>DU MOIS</td>
                        <td class="text-center"> <strong><span>{{ $dactemois[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $dactemoisv[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $dactemoisn[0]->total }}</span></strong> </td>
                    </tr>
                    <tr>
                        <td>DE LA SEMAINE</td>
                        <td class="text-center"> <strong><span>{{ $dactesemaine[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $dactesemainev[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $dactesemainen[0]->total }}</span></strong> </td>
                        </tr>
                    <tr>
                        <td>DU JOUR</td>
                        <td class="text-center"> <strong><span>{{ $dactesjour[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $dactesjourv[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $dactesjourn[0]->total }}</span></strong> </td>
                    </tr>


                    <tbody>
                </table>
            </div>
            <div class="col-12"><br>
                <h4>TOUTES LES INSTITUTIONS: {{ Auth()->user()->AffectationActive()->institution->lib_institution }}</h4>
                <div class="table-responsive">
                    <table id="example" class="display" style="min-width: 845px">
                        <thead>
                            <tr>
                                <th>Institution</th>
                                {{-- <th></th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mesinstitutions as $mesinstitution)
                            <tr width="100%">
                                <td>{{ $mesinstitution }}</td>
                                {{-- <td><a href="{{ route('tableau.details', $item->code_institution) }}" class="btn btn-primary btn-sm btn-block" target="_blank">Détails</a></td> --}}
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                {{-- <th>Institution</th> --}}
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>

    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

<script>

    (function($) {
        var dzChartlist = function(){

        // var screenWidth = $(window).width();



        }();

    })(jQuery);


</script>

@endsection
