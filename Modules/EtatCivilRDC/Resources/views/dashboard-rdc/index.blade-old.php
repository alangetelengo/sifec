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
                                                                        $ {{ number_format($mt,2,",",".") }}
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
                                                        <table class="table header-border" style="">

                                                            <tbody>
                                                                @foreach ($topRecettes as $toprecette)

                                                                <tr class="" style="background:#d5f7d9;">

                                                                    <td style="font-weight: 700; color:grey;font-size:12px">{{ $toprecette->lib_institution }}</td>
                                                                    <td><span class="montant">$ {{ number_format($toprecette->total, 2,",",".") }}</span></td>
                                                                </tr>
                                                                @endforeach
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
@endsection
@section("scripts")
  <!-- Chart ChartJS plugin files -->
  <script src="{{ asset('tpl/vendor/chart.js/Chart.bundle.min.js') }}"></script>
  {{-- <script src="{{ asset('tpl/js/plugins-init/chartjs-init.js') }}"></script> --}}

  <script src="{{ asset('tpl/js/custom.min.js') }}"></script>
  <script src="{{ asset('tpl/js/deznav-init.js') }}"></script>

  <script type="text/javascript">

    simplemaps_countrymap.hooks.click_state = function(id){
        var city = simplemaps_countrymap_mapdata.state_specific[id].name;
        //recuperation des données de la ville

    //   alert();
    }

    $(function(){
        var anneeEncours = $("#anneeEcours").val();

        $("#filtreAnnee").on("change", function(){
            var filtreAnnee = $(this).val();

            anneeEncours = filtreAnnee;
            alert(filtreAnnee);
        });
    });


    //     var anneeEncours = new Date().getFullYear();


    //     // var listeRecette =  getRecetteCec(anneeEncours);
    //     $("#filtreAnnee").on("change", function(){
    //         var filtreAnnee = $(this).val();

    //     });
    // });

    // function getRecetteCec(annee)
    // {
    //     var url = "{{ route('dashboard_gouv.recette.cec', ':id') }}";

    //     url = url.replace(":id", annee);
    //     $.get(url, function(response){


    //     });

    // }



    //ajout
    // (function($) {




/* "use strict" */


/* function draw() {

} */

var dzSparkLine = function(){
let draw = Chart.controllers.line.__super__.draw; //draw shadow

var screenWidth = $(window).width();

// var barChart1 = function(){
//     if(jQuery('#barChart_1').length > 0 ){
//         const barChart_1 = document.getElementById("barChart_1").getContext('2d');

//         barChart_1.height = 100;

//         new Chart(barChart_1, {
//             type: 'bar',
//             data: {
//                 defaultFontFamily: 'Poppins',
//                 labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
//                 datasets: [
//                     {
//                         label: "My First dataset",
//                         data: [65, 59, 80, 81, 56, 55, 40],
//                         borderColor: 'rgba(34, 47, 185, 1)',
//                         borderWidth: "0",
//                         backgroundColor: 'rgba(34, 47, 185, 1)'
//                     }
//                 ]
//             },
//             options: {
//                 legend: false,
//                 scales: {
//                     yAxes: [{
//                         ticks: {
//                             beginAtZero: true
//                         }
//                     }],
//                     xAxes: [{
//                         // Change here
//                         barPercentage: 0.5
//                     }]
//                 }
//             }
//         });
//     }
// }
var barChart2 = function(){
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
                // labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
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

// var barChart3 = function(){
//     //stalked bar chart
//     if(jQuery('#barChart_3').length > 0 ){
//         const barChart_3 = document.getElementById("barChart_3").getContext('2d');
//         //generate gradient
//         const barChart_3gradientStroke = barChart_3.createLinearGradient(50, 100, 50, 50);
//         barChart_3gradientStroke.addColorStop(0, "rgba(34, 47, 185, 1)");
//         barChart_3gradientStroke.addColorStop(1, "rgba(34, 47, 185, 0.5)");

//         const barChart_3gradientStroke2 = barChart_3.createLinearGradient(50, 100, 50, 50);
//         barChart_3gradientStroke2.addColorStop(0, "rgba(33, 183, 49, 1)");
//         barChart_3gradientStroke2.addColorStop(1, "rgba(33, 183, 49, 1)");

//         const barChart_3gradientStroke3 = barChart_3.createLinearGradient(50, 100, 50, 50);
//         barChart_3gradientStroke3.addColorStop(0, "rgba(255, 38, 37, 1)");
//         barChart_3gradientStroke3.addColorStop(1, "rgba(255, 38, 37, 1)");

//         barChart_3.height = 100;

//         let barChartData = {
//             defaultFontFamily: 'Poppins',
//             labels: ['Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat', 'Sun'],
//             datasets: [{
//                 label: 'Red',
//                 backgroundColor: barChart_3gradientStroke,
//                 hoverBackgroundColor: barChart_3gradientStroke,
//                 data: [
//                     '12',
//                     '12',
//                     '12',
//                     '12',
//                     '12',
//                     '12',
//                     '12'
//                 ]
//             }, {
//                 label: 'Green',
//                 backgroundColor: barChart_3gradientStroke2,
//                 hoverBackgroundColor: barChart_3gradientStroke2,
//                 data: [
//                     '12',
//                     '12',
//                     '12',
//                     '12',
//                     '12',
//                     '12',
//                     '12'
//                 ]
//             }, {
//                 label: 'Blue',
//                 backgroundColor: barChart_3gradientStroke3,
//                 hoverBackgroundColor: barChart_3gradientStroke3,
//                 data: [
//                     '12',
//                     '12',
//                     '12',
//                     '12',
//                     '12',
//                     '12',
//                     '12'
//                 ]
//             }]

//         };

//         new Chart(barChart_3, {
//             type: 'bar',
//             data: barChartData,
//             options: {
//                 legend: {
//                     display: false
//                 },
//                 title: {
//                     display: false
//                 },
//                 tooltips: {
//                     mode: 'index',
//                     intersect: false
//                 },
//                 responsive: true,
//                 scales: {
//                     xAxes: [{
//                         stacked: true,
//                     }],
//                     yAxes: [{
//                         stacked: true
//                     }]
//                 }
//             }
//         });
//     }
// }
// var lineChart1 = function(){


//     if(jQuery('#lineChart_1').length > 0 ){


//     //basic line chart
//         const lineChart_1 = document.getElementById("lineChart_1").getContext('2d');

//         Chart.controllers.line = Chart.controllers.line.extend({
//             draw: function () {
//                 draw.apply(this, arguments);
//                 let nk = this.chart.chart.ctx;
//                 let _stroke = nk.stroke;
//                 nk.stroke = function () {
//                     nk.save();
//                     nk.shadowColor = 'rgba(255, 0, 0, .2)';
//                     nk.shadowBlur = 10;
//                     nk.shadowOffsetX = 0;
//                     nk.shadowOffsetY = 10;
//                     _stroke.apply(this, arguments)
//                     nk.restore();
//                 }
//             }
//         });

//         lineChart_1.height = 100;

//         new Chart(lineChart_1, {
//             type: 'line',
//             data: {
//                 defaultFontFamily: 'Poppins',
//                 labels: ["Jan", "Febr", "Mar", "Apr", "May", "Jun", "Jul"],
//                 datasets: [
//                     {
//                         label: "My First dataset",
//                         data: [25, 20, 60, 41, 66, 45, 80],
//                         borderColor: 'rgba(34, 47, 185, 1)',
//                         borderWidth: "2",
//                         backgroundColor: 'transparent',
//                         pointBackgroundColor: 'rgba(34, 47, 185, 1)'
//                     }
//                 ]
//             },
//             options: {
//                 legend: false,
//                 scales: {
//                     yAxes: [{
//                         ticks: {
//                             beginAtZero: true,
//                             max: 100,
//                             min: 0,
//                             stepSize: 20,
//                             padding: 10
//                         }
//                     }],
//                     xAxes: [{
//                         ticks: {
//                             padding: 5
//                         }
//                     }]
//                 }
//             }
//         });

//     }
// }

// var lineChart2 = function(){
//     //gradient line chart
//     if(jQuery('#lineChart_2').length > 0 ){

//         const lineChart_2 = document.getElementById("lineChart_2").getContext('2d');
//         //generate gradient
//         const lineChart_2gradientStroke = lineChart_2.createLinearGradient(500, 0, 100, 0);
//         lineChart_2gradientStroke.addColorStop(0, "rgba(34, 47, 185, 1)");
//         lineChart_2gradientStroke.addColorStop(1, "rgba(34, 47, 185, 0.5)");

//         Chart.controllers.line = Chart.controllers.line.extend({
//             draw: function () {
//                 draw.apply(this, arguments);
//                 let nk = this.chart.chart.ctx;
//                 let _stroke = nk.stroke;
//                 nk.stroke = function () {
//                     nk.save();
//                     nk.shadowColor = 'rgba(0, 0, 128, .2)';
//                     nk.shadowBlur = 10;
//                     nk.shadowOffsetX = 0;
//                     nk.shadowOffsetY = 10;
//                     _stroke.apply(this, arguments)
//                     nk.restore();
//                 }
//             }
//         });

//         lineChart_2.height = 100;

//         new Chart(lineChart_2, {
//             type: 'line',
//             data: {
//                 defaultFontFamily: 'Poppins',
//                 labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
//                 datasets: [
//                     {
//                         label: "My First dataset",
//                         data: [25, 20, 60, 41, 66, 45, 80],
//                         borderColor: lineChart_2gradientStroke,
//                         borderWidth: "2",
//                         backgroundColor: 'transparent',
//                         pointBackgroundColor: 'rgba(34, 47, 185, 0.5)'
//                     }
//                 ]
//             },
//             options: {
//                 legend: false,
//                 scales: {
//                     yAxes: [{
//                         ticks: {
//                             beginAtZero: true,
//                             max: 100,
//                             min: 0,
//                             stepSize: 20,
//                             padding: 10
//                         }
//                     }],
//                     xAxes: [{
//                         ticks: {
//                             padding: 5
//                         }
//                     }]
//                 }
//             }
//         });
//     }
// }
// var lineChart3 = function(){
//     //dual line chart
//     if(jQuery('#lineChart_3').length > 0 ){
//         const lineChart_3 = document.getElementById("lineChart_3").getContext('2d');
//         //generate gradient
//         const lineChart_3gradientStroke1 = lineChart_3.createLinearGradient(500, 0, 100, 0);
//         lineChart_3gradientStroke1.addColorStop(0, "rgba(34, 47, 185, 1)");
//         lineChart_3gradientStroke1.addColorStop(1, "rgba(34, 47, 185, 0.5)");

//         const lineChart_3gradientStroke2 = lineChart_3.createLinearGradient(500, 0, 100, 0);
//         lineChart_3gradientStroke2.addColorStop(0, "rgba(255, 92, 0, 1)");
//         lineChart_3gradientStroke2.addColorStop(1, "rgba(255, 92, 0, 1)");

//         Chart.controllers.line = Chart.controllers.line.extend({
//             draw: function () {
//                 draw.apply(this, arguments);
//                 let nk = this.chart.chart.ctx;
//                 let _stroke = nk.stroke;
//                 nk.stroke = function () {
//                     nk.save();
//                     nk.shadowColor = 'rgba(0, 0, 0, 0)';
//                     nk.shadowBlur = 10;
//                     nk.shadowOffsetX = 0;
//                     nk.shadowOffsetY = 10;
//                     _stroke.apply(this, arguments)
//                     nk.restore();
//                 }
//             }
//         });

//         lineChart_3.height = 100;

//         new Chart(lineChart_3, {
//             type: 'line',
//             data: {
//                 defaultFontFamily: 'Poppins',
//                 labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
//                 datasets: [
//                     {
//                         label: "My First dataset",
//                         data: [25, 20, 60, 41, 66, 45, 80],
//                         borderColor: lineChart_3gradientStroke1,
//                         borderWidth: "2",
//                         backgroundColor: 'transparent',
//                         pointBackgroundColor: 'rgba(34, 47, 185, 0.5)'
//                     }, {
//                         label: "My First dataset",
//                         data: [5, 20, 15, 41, 35, 65, 80],
//                         borderColor: lineChart_3gradientStroke2,
//                         borderWidth: "2",
//                         backgroundColor: 'transparent',
//                         pointBackgroundColor: 'rgba(254, 176, 25, 1)'
//                     }
//                 ]
//             },
//             options: {
//                 legend: false,
//                 scales: {
//                     yAxes: [{
//                         ticks: {
//                             beginAtZero: true,
//                             max: 100,
//                             min: 0,
//                             stepSize: 20,
//                             padding: 10
//                         }
//                     }],
//                     xAxes: [{
//                         ticks: {
//                             padding: 5
//                         }
//                     }]
//                 }
//             }
//         });
//     }
// }
// var lineChart03 = function(){
//     //dual line chart
//     if(jQuery('#lineChart_3Kk').length > 0 ){
//         const lineChart_3Kk = document.getElementById("lineChart_3Kk").getContext('2d');
//         //generate gradient

//         Chart.controllers.line = Chart.controllers.line.extend({
//             draw: function () {
//                 draw.apply(this, arguments);
//                 let nk = this.chart.chart.ctx;
//                 let _stroke = nk.stroke;
//                 nk.stroke = function () {
//                     nk.save();
//                     nk.shadowColor = 'rgba(0, 0, 0, 0)';
//                     nk.shadowBlur = 10;
//                     nk.shadowOffsetX = 0;
//                     nk.shadowOffsetY = 10;
//                     _stroke.apply(this, arguments)
//                     nk.restore();
//                 }
//             }
//         });

//         lineChart_3Kk.height = 100;

//         new Chart(lineChart_3Kk, {
//             type: 'line',
//             data: {
//                 defaultFontFamily: 'Poppins',
//                 labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
//                 datasets: [
//                     {
//                         label: "My First dataset",
//                         data: [90, 60, 80, 50, 60, 55, 80],
//                         borderColor: 'rgba(58,122,254,1)',
//                         borderWidth: "3",
//                         backgroundColor: 'rgba(0,0,0,0)',
//                         pointBackgroundColor: 'rgba(0, 0, 0, 0)'
//                     }
//                 ]
//             },
//             options: {
//                 legend: false,
//                 elements: {
//                         point:{
//                             radius: 0
//                         }
//                 },
//                 scales: {
//                     yAxes: [{
//                         ticks: {
//                             beginAtZero: true,
//                             max: 100,
//                             min: 0,
//                             stepSize: 20,
//                             padding: 10
//                         },
//                         borderWidth:3,
//                         display:false,
//                         lineTension:0.4,
//                     }],
//                     xAxes: [{
//                         ticks: {
//                             padding: 5
//                         },

//                     }]
//                 }
//             }
//         });
//     }

// }
// var areaChart1 = function(){
//     //basic area chart
//     if(jQuery('#areaChart_1').length > 0 ){
//         const areaChart_1 = document.getElementById("areaChart_1").getContext('2d');

//         areaChart_1.height = 100;

//         new Chart(areaChart_1, {
//             type: 'line',
//             data: {
//                 defaultFontFamily: 'Poppins',
//                 labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
//                 datasets: [
//                     {
//                         label: "My First dataset",
//                         data: [25, 20, 60, 41, 66, 45, 80],
//                         borderColor: 'rgba(0, 0, 1128, .3)',
//                         borderWidth: "1",
//                         backgroundColor: 'rgba(34, 47, 185, .5)',
//                         pointBackgroundColor: 'rgba(0, 0, 1128, .3)'
//                     }
//                 ]
//             },
//             options: {
//                 legend: false,
//                 scales: {
//                     yAxes: [{
//                         ticks: {
//                             beginAtZero: true,
//                             max: 100,
//                             min: 0,
//                             stepSize: 20,
//                             padding: 10
//                         }
//                     }],
//                     xAxes: [{
//                         ticks: {
//                             padding: 5
//                         }
//                     }]
//                 }
//             }
//         });
//     }
// }
// var areaChart2 = function(){
//     //gradient area chart
//     if(jQuery('#areaChart_2').length > 0 ){
//         const areaChart_2 = document.getElementById("areaChart_2").getContext('2d');
//         //generate gradient
//         const areaChart_2gradientStroke = areaChart_2.createLinearGradient(0, 1, 0, 500);
//         areaChart_2gradientStroke.addColorStop(0, "rgba(255, 38, 37, 0.2)");
//         areaChart_2gradientStroke.addColorStop(1, "rgba(255, 38, 37, 0)");

//         areaChart_2.height = 100;

//         new Chart(areaChart_2, {
//             type: 'line',
//             data: {
//                 defaultFontFamily: 'Poppins',
//                 labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
//                 datasets: [
//                     {
//                         label: "My First dataset",
//                         data: [25, 20, 60, 41, 66, 45, 80],
//                         borderColor: "#ff2625",
//                         borderWidth: "4",
//                         backgroundColor: areaChart_2gradientStroke
//                     }
//                 ]
//             },
//             options: {
//                 legend: false,
//                 scales: {
//                     yAxes: [{
//                         ticks: {
//                             beginAtZero: true,
//                             max: 100,
//                             min: 0,
//                             stepSize: 20,
//                             padding: 5
//                         }
//                     }],
//                     xAxes: [{
//                         ticks: {
//                             padding: 5
//                         }
//                     }]
//                 }
//             }
//         });
//     }
// }

// var areaChart3 = function(){
//     //gradient area chart
//     if(jQuery('#areaChart_3').length > 0 ){
//         const areaChart_3 = document.getElementById("areaChart_3").getContext('2d');

//         areaChart_3.height = 100;

//         new Chart(areaChart_3, {
//             type: 'line',
//             data: {
//                 defaultFontFamily: 'Poppins',
//                 labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
//                 datasets: [
//                     {
//                         label: "My First dataset",
//                         data: [25, 20, 60, 41, 66, 45, 80],
//                         borderColor: 'rgb(34, 47, 185)',
//                         borderWidth: "1",
//                         backgroundColor: 'rgba(34, 47, 185, .5)'
//                     },
//                     {
//                         label: "My First dataset",
//                         data: [5, 25, 20, 41, 36, 75, 70],
//                         borderColor: 'rgb(255, 92, 0)',
//                         borderWidth: "1",
//                         backgroundColor: 'rgba(255, 92, 0, .5)'
//                     }
//                 ]
//             },
//             options: {
//                 legend: false,
//                 scales: {
//                     yAxes: [{
//                         ticks: {
//                             beginAtZero: true,
//                             max: 100,
//                             min: 0,
//                             stepSize: 20,
//                             padding: 10
//                         }
//                     }],
//                     xAxes: [{
//                         ticks: {
//                             padding: 5
//                         }
//                     }]
//                 }
//             }
//         });
//     }
// }

// var radarChart = function(){
//     if(jQuery('#radar_chart').length > 0 ){
//         //radar chart
//         const radar_chart = document.getElementById("radar_chart").getContext('2d');

//         const radar_chartgradientStroke1 = radar_chart.createLinearGradient(500, 0, 100, 0);
//         radar_chartgradientStroke1.addColorStop(0, "rgba(54, 185, 216, .5)");
//         radar_chartgradientStroke1.addColorStop(1, "rgba(75, 255, 162, .5)");

//         const radar_chartgradientStroke2 = radar_chart.createLinearGradient(500, 0, 100, 0);
//         radar_chartgradientStroke2.addColorStop(0, "rgba(68, 0, 235, .5");
//         radar_chartgradientStroke2.addColorStop(1, "rgba(68, 236, 245, .5");

//         // radar_chart.height = 100;
//         new Chart(radar_chart, {
//             type: 'radar',
//             data: {
//                 defaultFontFamily: 'Poppins',
//                 labels: [["Eating", "Dinner"], ["Drinking", "Water"], "Sleeping", ["Designing", "Graphics"], "Coding", "Cycling", "Running"],
//                 datasets: [
//                     {
//                         label: "My First dataset",
//                         data: [65, 59, 66, 45, 56, 55, 40],
//                         borderColor: '#f21780',
//                         borderWidth: "1",
//                         backgroundColor: radar_chartgradientStroke2
//                     },
//                     {
//                         label: "My Second dataset",
//                         data: [28, 12, 40, 19, 63, 27, 87],
//                         borderColor: '#f21780',
//                         borderWidth: "1",
//                         backgroundColor: radar_chartgradientStroke1
//                     }
//                 ]
//             },
//             options: {
//                 legend: false,
//                 maintainAspectRatio: false,
//                 scale: {
//                     ticks: {
//                         beginAtZero: true
//                     }
//                 }
//             }
//         });
//     }
// }


// var anneeEncours = new Date().getFullYear();

var urlRecetteAnnuelle= "{{ route('dashboard_gouv.recette.annuelle', ':id') }}";
urlRecetteAnnuelle = urlRecetteAnnuelle.replace(":id",anneeEncours);
var mt = $("#recetteAnnuelle");

//affichage des stats pour l'année
$.get(urlRecetteAnnuelle, function(){
    var
});

var url = "{{ route('dashboard_gouv.recette.cec', ':id') }}";
var url2 = "{{ route('dashboard_gouv.recette.mois', ':id') }}";


url = url.replace(":id", anneeEncours);
url2 = url2.replace(":id", anneeEncours);

var montantTotal =  [];
var libInstituions = [];

var listeMois = [];
var recetteMois = [];
var tabMois = ["Jan", "Feb", "Mar", "Avr", "Mai", "Jun", "Jul","Aou","Sep","Oct","Nov","Dec"];

$.get(url, function(response){
    //console.log(response);
    $.each(response, function (index, value) {
        montantTotal.push(value.total);
    });
    $.each(response, function (index, value) {
        libInstituions.push(value.lib_institution);
    });
});

$.get(url2, function(data){
    //console.log(data);
    $.each(data, function (index, value) {
        listeMois.push(tabMois[value.mois-1]);
    });
    $.each(data, function (index, value) {
        recetteMois.push(value.total);
    });
});


var pieChart = function(){
    //pie chart
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
// var doughnutChart = function(){
//     if(jQuery('#doughnut_chart').length > 0 ){
//         //doughut chart
//         const doughnut_chart = document.getElementById("doughnut_chart").getContext('2d');
//         // doughnut_chart.height = 100;
//         new Chart(doughnut_chart, {
//             type: 'doughnut',
//             data: {
//                 weight: 5,
//                 defaultFontFamily: 'Poppins',
//                 datasets: [{
//                     data: [45, 25, 20],
//                     borderWidth: 3,
//                     borderColor: "rgba(255,255,255,1)",
//                     backgroundColor: [
//                         "rgba(34, 47, 185, 1)",
//                         "rgba(33, 183, 49, 1)",
//                         "rgba(255, 38, 37, 1)"
//                     ],
//                     hoverBackgroundColor: [
//                         "rgba(34, 47, 185, 0.9)",
//                         "rgba(33, 183, 49, .9)",
//                         "rgba(255, 38, 37, .9)"
//                     ]

//                 }],
//                 // labels: [
//                 //     "green",
//                 //     "green",
//                 //     "green",
//                 //     "green"
//                 // ]
//             },
//             options: {
//                 weight: 1,
//                  cutoutPercentage: 70,
//                 responsive: true,
//                 maintainAspectRatio: false
//             }
//         });
//     }
// }
// var polarChart = function(){
//     if(jQuery('#polar_chart').length > 0 ){
//         //polar chart
//         const polar_chart = document.getElementById("polar_chart").getContext('2d');
//         // polar_chart.height = 100;
//         new Chart(polar_chart, {
//             type: 'polarArea',
//             data: {
//                 defaultFontFamily: 'Poppins',
//                 datasets: [{
//                     data: [15, 18, 9, 6, 19],
//                     borderWidth: 0,
//                     backgroundColor: [
//                         "rgba(34, 47, 185, 1)",
//                         "rgba(33, 183, 49, 1)",
//                         "rgba(255, 38, 37, 1)",
//                         "rgba(39, 129, 213, 1)",
//                         "rgba(255, 92, 0, 1)"
//                     ]

//                 }]
//             },
//             options: {
//                 responsive: true,
//                 maintainAspectRatio: false
//             }
//         });

//     }
// }



/* Function ============ */
return {
    init:function(){
    },


    load:function(){
        // barChart1();
        barChart2();
        // barChart3();
        // lineChart1();
        // lineChart2();
        // lineChart3();
        // lineChart03();
        // areaChart1();
        // areaChart2();
        // areaChart3();
        // radarChart();
        pieChart();
        // doughnutChart();
        // polarChart();
    },

    resize:function(){
        // barChart1();
        barChart2();
        // barChart3();
        // lineChart1();
        // lineChart2();
        // lineChart3();
        // lineChart03();
        // areaChart1();
        // areaChart2();
        // areaChart3();
        // radarChart();
        pieChart();
        // doughnutChart();
        // polarChart();
    }
}

}();

jQuery(document).ready(function(){
});

jQuery(window).on('load',function(){
    dzSparkLine.load();
});

jQuery(window).on('resize',function(){
    dzSparkLine.resize();
    setTimeout(function(){ dzSparkLine.resize(); }, 1000);
});

// })(jQuery);


    //fin ajout
 </script>
@endsection
