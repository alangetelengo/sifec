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
    <div class="card-header"> <h4> Tableau de bord {{ $mairie }} </h4> </div>
    <div class="card-body">

        <div class="row" id="carte">
            <div class="col-12">
                {{-- <h4>Institutions ratachées à la {{ $mairie }}: @foreach ($insts as $item)
                    <span>{{ $item->lib_institution }}</span>
                @endforeach</h4><br> --}}
                <div class="col-2">
                    <a href="{{ route('tableau.impression') }}" target="_blank" class="btn btn-primary btn-sm btn-block">Visualiser l'état</a>
                </div><br>
            </div>
            <div class="col-6">
                <table class="table table-striped table-bordered">
                    <tbody>
                    <tr>
                        <td colspan="5" style="background-color: #fff0f5; color: #1E88E5" class="text-center"><strong> DECLARATIONS DE NAISSANCE </strong></td>
                    </tr>
                    <tr>
                        <td class="text-center">SITUATION</td>
                        <td class="text-center">PRODUITS</td>
                        <td class="text-center">RECUES</td>
                        <td class="text-center">NON RECUES</td>
                    </tr>
                    <tr>
                        <td> CUMULEE</td>
                        <td class="text-center"> <strong><span>{{ $declarationcumul[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $denvoyercum[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $declarationcumul[0]->total - $denvoyercum[0]->total }}</span></strong> </td>
                    </tr>
                    <tr>
                        <td>L'ANNEE</td>
                        <td class="text-center"> <strong><span></span>{{ $declarationannee[0]->total }}</strong> </td>
                        <td class="text-center"> <strong><span>{{ $denvoyeran[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $declarationannee[0]->total - $denvoyeran[0]->total }}</span></strong> </td>
                        </tr>
                    <tr>
                        <td>DU MOIS</td>
                        <td class="text-center"> <strong><span>{{ $declarationmois[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $denvoyermois[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $declarationmois[0]->total - $denvoyermois[0]->total }}</span></strong> </td>
                    </tr>
                    <tr>
                        <td>DE LA SEMAINE</td>
                        <td class="text-center"> <strong><span>{{ $declarationsemaine[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $denvoyersemaine[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $declarationsemaine[0]->total - $denvoyersemaine[0]->total }}</span></strong> </td>
                        </tr>
                    <tr>
                        <td>DU JOUR</td>
                        <td class="text-center"> <strong><span>{{ $declarationjour[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $denvoyerjour[0]->total }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $declarationjour[0]->total - $denvoyerjour[0]->total }}</span></strong> </td>
                    </tr>


                    <tbody>
                </table>
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
                        <td class="text-center"> <strong><span>{{ $acteproduits }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $acteproduitsv }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $acteproduitsn }}</span></strong> </td>
                    </tr>
                    <tr>
                        <td>L'ANNEE</td>
                        <td class="text-center"> <strong><span></span>{{ $acteannee }}</strong> </td>
                        <td class="text-center"> <strong><span>{{ $acteanneev }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $acteanneen }}</span></strong> </td>
                        </tr>
                    <tr>
                        <td>DU MOIS</td>
                        <td class="text-center"> <strong><span>{{ $actemois }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $actemoisv }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $actemoisn }}</span></strong> </td>
                    </tr>
                    <tr>
                        <td>DE LA SEMAINE</td>
                        <td class="text-center"> <strong><span>{{ $actesemaine }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $actesemainev }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $actesemainen }}</span></strong> </td>
                        </tr>
                    <tr>
                        <td>DU JOUR</td>
                        <td class="text-center"> <strong><span>{{ $actesjour }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $actesjourv }}</span></strong> </td>
                        <td class="text-center"> <strong><span>{{ $actesjourn }}</span></strong> </td>
                    </tr>


                    <tbody>
                </table>
            </div>

            <div class="col-12"><br>
                <h4>Institutions ratachées à la {{ $mairie }}</h4>
                <div class="table-responsive">
                    <table id="example" class="display" style="min-width: 845px">
                        <thead>
                            <tr>
                                <th>Institution</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($insts as $item)
                            <tr width="100%">
                                <td>{{ $item->lib_institution }}</td>
                                <td><a href="{{ route('tableau.details', $item->code_institution) }}" class="btn btn-primary btn-sm btn-block" target="_blank">Détails</a></td>
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
