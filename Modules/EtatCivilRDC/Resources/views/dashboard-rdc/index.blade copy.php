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
<script src="{{ asset('tpl/js/mapdata.js') }}"></script>
<script src="{{ asset('tpl/js/countrymap.js') }}"></script>
@endsection
@section("corps")
<div class="row" style='opacity:1'>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h2>TABLEAU DE BORD DE SUIVI DES RECETTES</h2>
                <input type="hidden" value="{{ $anneEncours }}" id="anneeEcours">
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


                                        <div class="input-group mb-3" style="width:20%; position:relative, top:200px; left:80%">
											<label class="input-group-text mb-0">Filtrer par année</label>
                                            <!-- <select class="default-select  form-control wide">
                                                <option selected>Choose...</option>
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </select> -->
											<select class="form-select wide" aria-label="Default select example" id="filtreAnnee">

                                                  @foreach ($listeAnnees as $item)
                                                  <option value="{{ $item->annee }}">{{ $item->annee }}</option>
                                                  @endforeach
											</select>
                                        </div>
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
                                                                    <h3 class="text-white">
                                                                        $ <span id="recetteAnnuelle"></span>
                                                                    </h3>
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
                                                        <div id="topTroisRecettes"></div>
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
                                        <div class="col-xl-6 col-lg-12 col-sm-12" style="height: 600px">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div id="map"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-12 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="" style="">Commune de KINSHASA - DISTRICT DE NGALIEMA</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table header-border" style="">
                                                            <thead>
                                                                <tr class="" style="background:#d5f7d9;">

                                                                    <td style="font-weight: 700; color:grey;font-size:12px"></td>
                                                                    <td style="font-weight: 700; color:grey;font-size:10px" style="float:right">Documents(Extrait/Copie)</td>
                                                                    <td style="font-weight: 700; color:grey;font-size:10px" style="float: right">Authentifications</td>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($topRecettes as $toprecette)

                                                                <tr class="" style="background:#d5f7d9;">

                                                                    <td style="font-weight: 700; color:grey;font-size:12px">{{ $toprecette->lib_institution }}</td>
                                                                    <td><span class="montant">$ {{ number_format($toprecette->total, 2,",",".") }}</span></td>
                                                                    <td><span class="montant">$ {{ number_format(0, 2,",",".") }}</span></td>
                                                                </tr>
                                                                @endforeach
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
@endsection
@section("scripts")
  <!-- Chart ChartJS plugin files -->
  <script src="{{ asset('tpl/vendor/chart.js/Chart.bundle.min.js') }}"></script>
  {{-- <script src="{{ asset('tpl/js/plugins-init/chartjs-init.js') }}"></script> --}}
  <script src="{{ asset('tpl/js/custom.min.js') }}"></script>
  <script src="{{ asset('tpl/js/deznav-init.js') }}"></script>

   <script>
        var anneeEncours = $("#anneeEcours").val();
        getRecetteAnnuelle(anneeEncours);

        //pie chart
        var montantTotal =  [25, 15];
        var libInstituions = ["MAIRIE DE MAKELEKELE","COMMUNE DE NGALIEMA"];
        // var libInstituions = [];

        // // //gradient bar chart
        // var listeMois = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"];
        var listeMois = ["Oct"];
        // var recetteMois = [65, 59, 80, 81, 56, 55, 40];
        var recetteMois = [40];

        var tabMois = ["Jan", "Feb", "Mar", "Avr", "Mai", "Jun", "Jul","Aou","Sep","Oct","Nov","Dec"];

        $(function(){
            $("#filtreAnnee").on("change", function(){
                anneeChoisie = $(this).val();
                getRecetteAnnuelle(anneeChoisie);
                //actualisation des graphiques
                //pie chart
           montantTotal =  [13, 13];
         libInstituions = ["MAIRIE DE MAKELEKELE","COMMUNE DE NGALIEMA"];
        // var libInstituions = [];

        // // //gradient bar chart
        // var listeMois = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"];
         listeMois = ["Oct"];
        // var recetteMois = [65, 59, 80, 81, 56, 55, 40];
        recetteMois = [26];


            });

        });

        function getRecetteAnnuelle(annee){
            var route = "{{ route('dashboard_gouv.recette.annuelle',':id') }}"
            route = route.replace(":id", annee);

            $.get(route, function (data) {
                //affichage indicateur montant Total
                $("#recetteAnnuelle").text(data.mt);
                //Indicateur top 3
                var table = '<table class="table header-border" style=""><tbody>';
                console.log(data.tabTopRecettes[0]['libInstitution']);
                console.log(data.tabTopRecettes[1]['Prix']);


                if(data.tabTopRecettes.length > 0){
                    for( var i=0; i < data.tabTopRecettes.length ; i++){
                        table +='<tr style="background:#d5f7d9;">'+
                            '<td style="font-weight: 700; color:grey;font-size:12px">'+data.tabTopRecettes[i]['libInstitution']+'</td>'+
                            '<td><span class="montant">'+data.tabTopRecettes[i]['Prix']+'</span></td>';
                    }
                }
                table += "</tr></tbody></table>";
                $("#topTroisRecettes").html(table);

                //Indicateur recette par mois
                if(data.tablisteRecettesParMois.length > 0){
                    for( var i=0; i < data.tablisteRecettesParMois.length ; i++){
                        listeMois = listeMois.push(data.tablisteRecettesParMois[i]['lemois']);
                        recetteMois = recetteMois.push(data.tablisteRecettesParMois[i]['Tmontant']);
                    }
                }

                // jQuery(window).on('load',function(){
                //     dzSparkLine.load();
                // });


            });
        }

        // //debut
        var dzSparkLine = function(){
            let draw = Chart.controllers.line.__super__.draw; //draw shadow

            var screenWidth = $(window).width();


            var barChart2 = function(){
                if(jQuery('#barChart_2').length > 0 ){

                //gradient bar chart
                    const barChart_2 = document.getElementById("barChart_2").getContext('2d');
                    //generate gradient
                    const barChart_2gradientStroke = barChart_2.createLinearGradient(0, 0, 0, 250);
                    barChart_2gradientStroke.addColorStop(0, "rgba(0, 200, 0, 1)");
                    barChart_2gradientStroke.addColorStop(1, "rgba(0, 200, 0, 0.5)");

                    barChart_2.height = 100;

                    new Chart(barChart_2, {
                        type: 'bar',
                        data: {
                            defaultFontFamily: 'Poppins',
                            labels: listeMois,
                            datasets: [
                                {
                                    label: "",
                                    data: recetteMois,
                                    borderColor: barChart_2gradientStroke,
                                    borderWidth: "0",
                                    backgroundColor: barChart_2gradientStroke,
                                    hoverBackgroundColor: barChart_2gradientStroke
                                }
                            ]
                        },
                        options: {
                            legend: false,
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }],
                                xAxes: [{
                                    // Change here
                                    barPercentage: 0.5
                                }]
                            }
                        }
                    });
                }
            }


            var pieChart = function(){
                //pie chart
                if(jQuery('#pie_chart').length > 0 ){
                    //pie chart
                    const pie_chart = document.getElementById("pie_chart").getContext('2d');
                    // pie_chart.height = 100;
                    new Chart(pie_chart, {
                        type: 'pie',
                        data: {
                            defaultFontFamily: 'Poppins',
                            datasets: [{
                                data: montantTotal,
                                borderWidth: 0,
                                backgroundColor: [
                                    "rgba(0, 200, 0, .9)",
                                    "rgba(0, 200, 0, .7)",
                                    "rgba(0, 200 ,0, .5)",
                                    "rgba(0,0,0,0.07)"
                                ],
                                hoverBackgroundColor: [
                                    "rgba(0,200,0, .9)",
                                    "rgba(0,200,0, .7)",
                                    "rgba(0,200,0, .5)",
                                    "rgba(0,0,0,0.07)"
                                ]

                            }],
                            labels: libInstituions
                        },
                        options: {
                            responsive: true,
                            legend: false,
                            maintainAspectRatio: false
                        }
                    });
                }
            }
            /* Function ============ */
            return {
                init:function(){
                },
                load:function(){
                    barChart2();
                    pieChart();
                },

                resize:function(){
                    barChart2();
                    pieChart();
                }
            }

        }();

        jQuery(window).on('load',function(){
            dzSparkLine.load();
        });

        jQuery(window).on('resize',function(){
            dzSparkLine.resize();
            setTimeout(function(){ dzSparkLine.resize(); }, 1000);
        });
        // //fin debut

   </script>
@endsection
