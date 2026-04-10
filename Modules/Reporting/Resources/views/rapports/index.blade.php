@extends('layout.app')

@section("styles")
<link href="{{ asset('tpl/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">

<style>
    .card-title{
        font-size: 12px;
        font-weight: bolder;
        text-transform: uppercase;
    }
  .card-header h4{

    font-weight: 1000;
    font-size:11px;
    color:rgb(86, 86, 86);
    text-transform: uppercase;
  }

  .table tbody tr td{
    padding: 6px;
    font-weight: bolder;

  }

  thead td{
    text-align: right;
    text-transform: uppercase;
  }
  .montant{
    float: right;

    padding:5px 5px 5px 5px;
    text-align: right;
    background-color:#21B931!important ;
    color: white;
    border-radius: 10px;
    font-weight: 800;
  }
.nav-item .active{
    background-color:#21B931!important ;
}
.nav-item .active span{
    color: white;
    font-weight: bolder;
}


</style>
<link href="{{ asset('tpl/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
<script src="{{ asset('tpl/js/mapdata-bz.js') }}"></script>
<script src="{{ asset('tpl/js/countrymap-bz.js') }}"></script>
@endsection
@section("corps")
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
<div class="row" style='opacity:1'>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h2>TABLEAU DE BORD DE SUIVI DES RECETTES</h2>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="default-tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#home" style="text-transform: uppercase"><i class="la la-dashboard me-2" style="color: grey"></i> <span>RAPPORT GLOBAL</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#rapport_detail" style="text-transform: uppercase"><i class="la la-list me-2" style="color: grey"></i> <span>RAPPORT DéTAILLé</span></a>
                                {{-- <a class="nav-link" data-bs-toggle="tab" href="{{ route('dashboard_gouv.detail') }}" style="text-transform: uppercase"><i class="la la-list me-2" style="color: grey"></i> <span>RAPPORT DéTAILLé</span></a> --}}
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="home" role="tabpanel">
                                <div class="pt-4">
                                    <div class="row"  style="padding:20px;">
                                        {{-- <h3 style="text-transform:uppercase;font-size:16px; font-weight:bolder; text-aligne:right; fl">
                                            <i class="flaticon-381-filter"></i> FILTRES
                                            <select id="periode-filtre">
                                                <option>Jour</option>
                                                <option>Mois</option>
                                                <option>Trimestre</option>
                                                <option>Semestre</option>
                                                <option>Année</option>


                                            </select>
                                        </h3> --}}

                                        <h4 style="text-transform:uppercase;font-size:14px"><i class="flaticon-381-file"></i> CHIFFRES CLés</h4>
                                        <hr/>
                                        <div class="col-xl-6 col-lg-12 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="" style="">TOTAL DES RECETTES</h4>
                                                </div>
                                                <div class="card-body">

                                                    <div class="widget-stat card" style="background:#21B931!important">
                                                        <div class="card-body p-4">

                                                            <div class="media">
                                                                <span class="me-3" style="color:white">
                                                                    TOTAL
                                                                </span>
                                                                <div class="media-body text-white text-end">
                                                                    <p class="mb-1"></p>
                                                                    <h3 class="text-white">$10.000</h3>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-12 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="">TOP 3 DES RECETTES</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table header-border" style="">

                                                            <tbody>
                                                                <tr class="" style="background:#d5f7d9;">

                                                                    <td style="font-weight: 700; color:grey;font-size:12px">LIMETE</td>
                                                                    <td><span class="montant">$1.500</span></td>
                                                                </tr>
                                                                <tr class="" style="background:#d5f7d9;">

                                                                    <td style="font-weight: 700; color:grey;font-size:12px">GOMBE</td>
                                                                    <td><span class="montant">$2.500</span></td>
                                                                </tr>
                                                                <tr class="" style="background:#d5f7d9;">

                                                                    <td style="font-weight: 700; color:grey;font-size:12px">MATETE</td>
                                                                    <td><span class="montant">$6.000</span></td>
                                                                </tr>


                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row"  style="padding:20px">
                                        <h4 style="text-transform:uppercase;font-size:14px;"><i class="flaticon-381-list"></i> répartition des recettes</h4>
                                        <hr/>
                                        <div class="col-xl-6 col-lg-12 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="">répartition des recettes</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                                                        <canvas id="pie_chart" style="display: block; height: 295px; width: 321px;" width="401" height="368" class="chartjs-render-monitor"></canvas>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-12 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="">évolution des recettes</h4>
                                                </div>
                                                <div class="card-body">
                                                    <canvas id="barChart_2"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="rapport_detail">
                                <div class="row" style="padding:30px">
                                        <div class="col-xl-7 col-lg-12 col-sm-12" style="height: 600px">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div id="map"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-5 col-lg-12 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="" style="">Commune de KINSHASA</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table header-border" style="">
                                                            <thead>
                                                                <tr class="" style="background:#d5f7d9;">

                                                                    <td style="font-weight: 700; color:grey;font-size:12px"></td>
                                                                    <td style="font-weight: 700; color:grey;font-size:12px" style="float:right">Documents</td>
                                                                    <td style="font-weight: 700; color:grey;font-size:12px" style="float: right">Authentifications</td>


                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr class="" style="background:#d5f7d9;">

                                                                    <td style="font-weight: 700; color:grey;font-size:12px">LIMETE</td>
                                                                    <td><span class="montant">$1.500</span></td>
                                                                    <td><span class="montant">$0.00</span></td>

                                                                </tr>
                                                                <tr class="" style="background:#d5f7d9;">

                                                                    <td style="font-weight: 700; color:grey;font-size:12px">GOMBE</td>
                                                                    <td><span class="montant">$2.500</span></td>
                                                                    <td><span class="montant">$0.00</span></td>
                                                                </tr>
                                                                <tr class="" style="background:#d5f7d9;">

                                                                    <td style="font-weight: 700; color:grey;font-size:12px">MATETE</td>
                                                                    <td><span class="montant">$6.000</span></td>
                                                                    <td><span class="montant">$0.00</span></td>
                                                                </tr>

                                                                <tr class="" style="background:#d5f7d9;">

                                                                    <td style="font-weight: 700; color:grey;font-size:12px">TOTAL</td>
                                                                    <td><span class="montant">$10.000</span></td>
                                                                    <td><span class="montant">$0.00</span></td>
                                                                </tr>


                                                            </tbody>
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

        </div>
    </div>
</div>
</div>
</div>
</div>
@endsection
@section("scripts")
  <!-- Chart ChartJS plugin files -->
  <script src="{{ asset('tpl/vendor/chart.js/Chart.bundle.min.js') }}"></script>
  <script src="{{ asset('tpl/js/plugins-init/chartjs-init.js') }}"></script>

  <script src="{{ asset('tpl/js/custom.min.js') }}"></script>
  <script src="{{ asset('tpl/js/deznav-init.js') }}"></script>

  <script type="text/javascript">
    simplemaps_countrymap.hooks.click_state = function(id){
        var city = simplemaps_countrymap_mapdata.state_specific[id].name;
        //recuperation des données de la ville

      alert(city);
    }
 </script>
@endsection
