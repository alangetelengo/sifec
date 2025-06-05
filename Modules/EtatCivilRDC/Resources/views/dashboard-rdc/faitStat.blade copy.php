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
<!-- Daterange picker -->
<link href="{{ asset('tpl/vendor/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
<script src="{{ asset('tpl/js/mapdata.js') }}"></script>
<script src="{{ asset('tpl/js/countrymap.js') }}"></script>
@endsection
@section("corps")
<div class="row" style='opacity:1'>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h2>TABLEAU DE BORD DE SUIVI DES FAITS</h2>
                <input type="hidden" value="{{ $anneEncours }}" id="anneeEcours">
                <input type="hidden" value="{{ Auth::user()->affectationActive()->fonction->code_fonction }}" id="codefonction">
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="default-tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#home" style="text-transform: uppercase"><i class="la la-dashboard me-2" style="color: grey"></i> <span>SUIVI DES NAISSANCES</span></a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#rapport_detail" style="text-transform: uppercase"><i class="la la-list me-2" style="color: grey"></i> <span>SUIVI DES Décès</span></a>
                            </li> --}}
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


                                        <div class="input-group mb-3" style="width:28%; position:relative, top:200px; left:72%">
											<label class="input-group-text mb-0">Filtrer par période</label>

                                            <input id="filtreAnnee" class="form-control input-daterange-datepicker" type="text" name="daterange" value="" style="width:20%">
                                        </div>
                                        <h4 style="text-transform:uppercase;font-size:14px"><i class="flaticon-381-file"></i> CHIFFRES CLés</h4>
                                        <hr/>
                                        <div class="col-xl-6 col-lg-12 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="" style="">EFFECTIF TOTAL </h4>
                                                </div>
                                                <div class="card-body">
                                                    <center><img src="{{asset('images/loader.gif')}}" class="loader_img" style="width:60px"></center>
                                                    <div class="widget-stat card" style="background:#21B931!important" id="ind_montant_total">
                                                        <div class="card-body p-4">

                                                            <div class="media">
                                                                <span class="me-3" style="color:white">
                                                                    TOTAL
                                                                </span>
                                                                <div class="media-body text-white text-end">
                                                                    <p class="mb-1"></p>
                                                                    <h3 class="text-white">
                                                                         <span id="recetteAnnuelle"></span>
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
                                                    <h4 class="" id="titre_top_recette">RéPARTITION PAR SEXE</h4>
                                                </div>
                                                <div class="card-body" style="height:200px;overflow:hidden">
                                                    <center><img src="{{asset('images/loader.gif')}}" class="loader_img" style="width:60px">
                                                    <canvas id="pie_chart" style="display: block; height: 155px; width: 181px;" class="chartjs-render-monitor"></canvas>
                                                </center>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row"  style="padding:20px">
                                        <h4 style="text-transform:uppercase;font-size:14px;"><i class="flaticon-381-list"></i> évolutions des naissances </h4>
                                        <hr/>
                                        <div class="col-xl-12 col-lg-12 col-sm-12" id="bloc_bourg">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 id="titre_naissance_cec">évolution des naissances par commune</h4>
                                                </div>
                                                <div class="card-body">
                                                    <center><img src="{{asset('images/loader.gif')}}" class="loader_img" style="width:60px"></center>
                                                    <div id="ind_recette_mois" style="height:250px; overflow:hidden">
                                                        <canvas id="barChart_2"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-12 col-lg-12 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="" id='titre_recette_cec'>évolution des naissances par mois</h4>
                                                </div>
                                                <div class="card-body">
                                                    <center><img src="{{asset('images/loader.gif')}}" class="loader_img" style="width:60px"></center>
                                                    <div id="ind_recette_cec" style="height:250px; overflow:hidden">
                                                        <canvas id="barChart_3"></canvas>
                                                    </div>
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
                                                               {{-- @foreach ($topRecettes as $toprecette)--}}

                                                                <tr class="" style="background:#d5f7d9;">

                                                                    <td style="font-weight: 700; color:grey;font-size:12px">{{-- $toprecette->lib_institution --}}</td>
                                                                    <td><span class="montant">$ {{-- number_format($toprecette->total, 2,",",".") --}}</span></td>
                                                                    <td><span class="montant">$ {{-- number_format(0, 2,",",".") --}}</span></td>
                                                                </tr>
                                                               {{-- @endforeach--}}
                                                             </tbody>
                                                         </table>

                                                         {{-- <div id="detailTab"></div> --}}
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

  <!-- Daterangepicker -->
  <script src="{{ asset('tpl/vendor/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
  <script src="{{ asset('tpl/js/plugins-init/bs-daterange-picker-init.js') }}"></script>


   <script>
        //mise à jour des titres contextes RDC
        var codefonction = $("#codefonction").val();
        if(codefonction == "FONC_0002"){
            //personnalisation des titres :: cas du bourgmestre
            $("#bloc_bourg").hide();
           /* $("#titre_recette_cec").text("Répartition par type de documents");
            */

        }
        if(codefonction == "FONC_0023"){
            //personnalisation des titres :: cas du ministre
            $("#titre_naissance_cec").text("Evolution des recettes par province");


        }

        $("#ind_montant_total").hide();
        $("#ind_top_recette").hide();
        $("#ind_recette_cec").hide();
        $("#ind_recette_mois").hide();

        var tabMois = ["Jan", "Feb", "Mar", "Avr", "Mai", "Jun", "Jul","Aou","Sep","Oct","Nov","Déc"];

         // // //gradient bar chart
         var listeMois = [];
        // var recetteMois = [65, 59, 80, 81, 56, 55, 40];
         var recetteMois = [];


         // bar naissance par mois
         var listeMoisNaissance = [];
         var naissanceMois = [];

          //pie chart
        var montantTotal =  [];
        var libInstitutions = [];

        var periode = new Date();
        var periodeFormat = (periode.getMonth()+1)+'/'+periode.getDate()+'/'+periode.getFullYear();
        var p = periodeFormat+'-'+periodeFormat;
        //actualisation initialle de dashboard
        getRecetteAnnuelle(p,codefonction);

        //actualisation  du dashboard au changement d'années et période
        $(function(){
            $("#filtreAnnee").on("change", function(){
                //alert("ok");
                /*$('img.loarder_img').show();
                $("#ind_montant_total").hide();
                $("#ind_top_recette").hide();
                $("#ind_recette_cec").hide();
                $("#ind_recette_mois").hide();*/
                //récupération de la nouvelle période
                periodeChoisie = $(this).val();

                //réinitialisation des graphiques
                var listeMois = [];
                var recetteMois = [];
                var montantTotal =  [];
                var libInstitutions = [];
                 // bar naissance par mois
                var listeMoisNaissance = [];
                var naissanceMois = [];

                //actualisation des graphique
                getRecetteAnnuelle(periodeChoisie,codefonction);


            });

        });

        //fonction de récupération des indicateurs du dashboard

        function getRecetteAnnuelle(periode,codeFonction){
            var route = "{{ route('dashboard_gouv.fait.stats.get') }}";

            $.get(route,{"periode": periode,"codeFonction": codeFonction}, function (data) {
                //désactivation des loader au chargement des données
                $("img.loader_img").hide(300);
                //affichage des indicateurs
                $("#ind_montant_total").show(300);
                $("#ind_top_recette").show(300);
                $("#ind_recette_cec").show(300);
                $("#ind_recette_mois").show(300);

                listeMois = [];
                recetteMois = [];
                libInstitutions = [];
                montantTotal = [];
                naissanceMois = [];
                listeMoisNaissance = [];
                //dzSparkLine.init();
                //dzSparkLine.load();
                //affichage indicateur montant Total
                //alert(data.mt);
                $("#recetteAnnuelle").text(data.mt);
                //alert(data.tablisteRecettesParMois.length);
                //Indicateur top 3
                var table = '<table class="table header-border" style=""><tbody>';
                var table2 = '<table class="table header-border" style="">'+
                                '<thead>'
                                    '<tr style="background:#d5f7d9;">'+
                                        '<td style="font-weight: 700; color:grey;font-size:12px"></td>'+
                                        ' <td style="font-weight: 700; color:grey;font-size:10px" style="float:right">Documents(Extrait/Copie)</td>'+
                                        '<td style="font-weight: 700; color:grey;font-size:10px" style="float: right">Authentifications</td>'+
                                    '</tr>'+
                                '</thead><tbody>';

                /*console.log(data.tabTopRecettes[0]['libInstitution']);
                console.log(data.tabTopRecettes[1]['Prix']);*/

                //alert(table);
                if(data.tabTopRecettes.length > 0){
                    for( var i=0; i < data.tabTopRecettes.length ; i++){
                        table +='<tr style="background:#d5f7d9;">'+
                            '<td style="font-weight: 700; color:grey;font-size:12px">'+data.tabTopRecettes[i]['libInstitution']+'</td>'+
                            '<td><span class="montant">'+data.tabTopRecettes[i]['Prix']+'</span></td></tr>';


                        table2 +='<tr style="background:#d5f7d9;">'+
                            '<td style="font-weight: 700; color:grey;font-size:12px">'+data.tabTopRecettes[i]['libInstitution']+'</td>'+
                            '<td><span class="montant">'+data.tabTopRecettes[i]['Prix']+'</span></td>'+
                            '<td><span class="montant">'+data.tabTopRecettes[i]['auth']+'</span></td></tr>';
                    }
                }
                table += "</tbody></table>";

                table2 += "</tbody></table>";
                $("#topTroisRecettes").html(table);
                $("#detailTab").html(table2);

                //Indicateur recette par mois
                //alert(data.tablisteRecettesParMois[0]['lemois']);
                //alert(data.tablisteRecettesParMois.length);


                /*if(data.tablisteRecettesParMois.length > 0){

                    for( var i=0; i < data.tablisteRecettesParMois.length ; i++){
                        //var mois = tabMois[data.tablisteRecettesParMois[i]['lemois'] - 1];

                        listeMois.push(data.tablisteRecettesParMois[i]['lemois']);
                        recetteMois.push(data.tablisteRecettesParMois[i]['Tmontant']);
                    }
                    //alert(listeMois.length);
                }*/

                   //effectif des naissances par commune
                 for(var j=0; j < data.tabCommunesGouverneur.length ; j++){
                    console.log(data.tabCommunesGouverneur);
                    //alert(data.tabCommunesGouverneur[j]['localite']);
                    if(data.tablisteRecettesParMois.length > 0){
                        for(var i=0; i< data.tablisteRecettesParMois.length ; i++){
                            var cecCourant = data.tablisteRecettesParMois[i]['lemois'];
                            if(cecCourant == data.tabCommunesGouverneur[j]['localite']){
                                var inst = cecCourant;
                                var nb = data.tablisteRecettesParMois[i]['Tmontant']
                            }else{
                                var inst = data.tabCommunesGouverneur[j]['localite'];
                                var nb = 0;
                            }
                            listeMois.push(inst);
                            recetteMois.push(nb);
                        }
                    }
                 }


                 //
                if(data.tablisteRecettesParCec.length > 0){

                    for( var i=0; i < data.tablisteRecettesParCec.length ; i++){
                        libInstitutions.push(data.tablisteRecettesParCec[i]['institution']);
                        montantTotal.push(data.tablisteRecettesParCec[i]['total']);
                    }
                }

                 //effectif des naissances par mois
                 for(var j=0; j < tabMois.length ; j++){
                    if(data.tabNaissancesParMois.length > 0){
                        for(var i=0; i < data.tabNaissancesParMois.length ; i++){
                            var moisCourant = tabMois[data.tabNaissancesParMois[i]['mois'] - 1];
                            if(moisCourant == tabMois[j]){
                                var mois = moisCourant;
                                var nb = data.tabNaissancesParMois[i]['effectif'];
                            }else{
                                var mois = tabMois[j];
                                var nb = 0;

                            }
                            //alert(data.tabNaissancesParMois[i]['effectif']);
                            listeMoisNaissance.push(mois);
                            naissanceMois.push(nb);
                        }
                    }
                 }
                //rechargement des graphiques
                dzSparkLine.load();


            });
        }

        // //debut
        var dzSparkLine = function(){
            let draw = Chart.controllers.line.__super__.draw; //draw shadow

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

            //évolution des naissances par mois
            var barChart3 = function(){
                if(jQuery('#barChart_3').length > 0 ){

                    //gradient bar chart
                    const barChart_3 = document.getElementById("barChart_3").getContext('2d');
                    //generate gradient
                    const barChart_3gradientStroke = barChart_3.createLinearGradient(0, 0, 0, 250);
                    barChart_3gradientStroke.addColorStop(0, "rgba(0, 200, 0, 1)");
                    barChart_3gradientStroke.addColorStop(1, "rgba(0, 200, 0, 0.5)");

                    barChart_3.height = 100;

                    new Chart(barChart_3, {
                        type: 'bar',
                        data: {
                            defaultFontFamily: 'Poppins',
                            labels: listeMoisNaissance,
                            datasets: [
                                {
                                    label: "",
                                    data: naissanceMois,
                                    borderColor: barChart_3gradientStroke,
                                    borderWidth: "0",
                                    backgroundColor: barChart_3gradientStroke,
                                    hoverBackgroundColor: barChart_3gradientStroke
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
                //alert(jQuery('#pie_chart').length);
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
                            labels: libInstitutions
                        },
                        options: {
                            responsive: false,
                            legend: false,
                            maintainAspectRatio: false
                        }
                    });
                }
            }

            return {
                init:function(){
                },

                load:function(){
                    barChart2();
                    barChart3();
                    pieChart();
                },


            }

        }();

        jQuery(window).on('load',function(){
            dzSparkLine.load();
        });

        /* Document.ready Start */
        jQuery(document).ready(function() {
            $('[data-toggle="popover"]').popover(), Tixia.init();
            [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]')).map(function(e) {
                return new bootstrap.Popover(e)
            })
        });

        /* Window Load START */
        jQuery(window).on('load',function () {
            'use strict';
            Tixia.load();

        });
        /*  Window Load END */
        /* Window Resize START */
        jQuery(window).on('resize',function () {
            'use strict';
            Tixia.resize();
        });


        // //fin debut

   </script>
@endsection
