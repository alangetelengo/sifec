@extends('layout.app')

@section("styles")
 <link href="{{ asset('tpl/vendor/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">

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

  .table tbody tr:hover{
        background-color: white;
        color: black;
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
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
<div class="row" style='opacity:1'>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h2>TABLEAU DE BORD DE SUIVI DES RECETTES</h2>
                <input type="hidden" value="{{ $anneEncours }}" id="anneeEcours">
                <input type="hidden" value="{{ Auth::user()->affectationActive()->fonction->code_fonction }}" id="codefonction">
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="default-tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#home" style="text-transform: uppercase"><i class="la la-dashboard me-2" style="color: grey"></i> <span>RAPPORT GLOBAL</span></a>
                            </li>
                            @if(Auth::user()->affectationActive()->fonction->code_fonction == "FONC_0023")
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#rapport_detail" style="text-transform: uppercase"><i class="la la-list me-2" style="color: grey"></i> <span>RAPPORT DéTAILLé</span></a>
                            </li>
                            @endif
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="home" role="tabpanel">
                                <div class="pt-4">
                                    <div class="row"  style="padding:20px;">

                                        <div class="input-group mb-3" style="width:28%; position:relative, top:200px; left:72%">
											<label class="input-group-text mb-0">Filtrer par période</label>
                                            <input id="filtreAnnee" class="form-control input-daterange-datepicker" type="text" name="daterange" value="" style="width:20%">
                                        </div>
                                        <h4 style="text-transform:uppercase;font-size:14px"><i class="flaticon-381-file"></i> CHIFFRES CLés</h4>
                                        <hr/>
                                        <div class="col-xl-6 col-lg-12 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="" style="">TOTAL DES RECETTES</h4>
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
                                                    <h4 class="" id="titre_top_recette">TOP 3 DES RECETTES</h4>
                                                </div>
                                                <div class="card-body">
                                                    <center><img src="{{asset('images/loader.gif')}}" class="loader_img" style="width:60px"></center>
                                                    <div id="id_top_recette">
                                                        <div class="table-responsive">
                                                            <div id="topTroisRecettes"></div>
                                                        </div>
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
                                                    <h4 class="" id='titre_recette_cec'>répartition des recettes par institutions</h4>
                                                </div>
                                                <div class="card-body">

                                                    <center><img src="{{asset('images/loader.gif')}}" class="loader_img" style="width:60px">
                                                        <div id="container2"></div>
                                                    </center>

                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-12 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="">évolution des recettes par mois</h4>
                                                </div>
                                                <div class="card-body">
                                                    <center><img src="{{asset('images/loader.gif')}}" class="loader_img" style="width:60px"></center>
                                                    <div id="ind_recette_mois">
                                                        <div id="container"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="rapport_detail">
                                <div class="row" style="padding:30px">
                                        @include("etatcivilrdc::dashboard-rdc.carte-rdc")
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="" style="font-size:14px"><strong>PROVINCE DE : <span class="text-success" id="libelleProvince"></span> </strong></h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <div class="subprovinces d-none">
                                                            <select id="selectcommunes" class="form-control">

                                                            </select>
                                                        </div>
                                                         <br>
                                                         <div id="statCartes"></div>
                                                         <div id="statCartes2"></div>
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
  <!-- Daterangepicker -->
  <script src="{{ asset('tpl/vendor/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
  <script src="{{ asset('tpl/js/plugins-init/bs-daterange-picker-init.js') }}"></script>

  <!-- Chart ChartJS plugin files -->

  <script src="{{ asset('tpl/vendor/chart.js/Chart.bundle.min.js') }}"></script>
  {{-- <script src="{{ asset('tpl/js/plugins-init/chartjs-init.js') }}"></script> --}}
  <script src="{{ asset('tpl/js/custom.min.js') }}"></script>
  <script src="{{ asset('tpl/js/deznav-init.js') }}"></script>

   <script>
        var codefonction = $("#codefonction").val();
        $("#statCartes2").hide();
        $("#statCartes").hide();
        //debut
        // Departement
        function selectionDepart(province) {
            var table = '<table class="table header-border" style=""><tbody>';
            var id = province.id;
            var url = "{{ route('dashboard_gouv.stat.carte') }}";
            if(id != "KINSHASA"){
               $("div.subprovinces").removeClass("d-none");

               $("#statCartes2").show();
               $("#statCartes").hide();
               //recuperer les communes de la province selectionnée
                getCommune(id);
                //getArrondissement(id);
            }else{
                $("div.subprovinces").addClass("d-none");
                $("#statCartes2").hide();
                $("#statCartes").show();
            }

            //affichage du province
            $("#libelleProvince").html(id);



            $.get(url, {codeProvince: id,codefonction:codefonction}, function (data) {

                if(data.tabRecettes.length > 0){
                    for( var i=0; i < data.tabRecettes.length ; i++){
                        table +='<tr style="background:#d5f7d9;">'+
                            '<td style="font-weight: 700; color:grey;font-size:12px">'+data.tabRecettes[i]['libInstitution']+'</td>'+
                            '<td><span class="montant">'+data.tabRecettes[i]['Prix']+'</span></td></tr>';
                    }
                }
                table += "</tbody></table>";
                $("#statCartes").html(table);


            });


        };
        //fin

          //mise à jour des titres contextes RDC

        if(codefonction == "FONC_0002"){
            //personnalisation des titres :: cas du bourgmestre
            $("#titre_top_recette").text("Détails des recettes");
            $("#titre_recette_cec").text("Répartition par type de documents");

        }
        if(codefonction == "FONC_0023"){
            //personnalisation des titres :: cas du ministre
            $("#titre_top_recette").text("Top 3 des meilleures provinces");
            $("#titre_recette_cec").text("Répartition par province");
        }
        if(codefonction == "FONC_0022"){
            //personnalisation des titres :: cas du gouverneur
            $("#titre_top_recette").text("Top 3 des meilleures communes");
            $("#titre_recette_cec").text("Répartition par communes");
        }

        $("#ind_montant_total").hide();
        $("#ind_top_recette").hide();
        $("#ind_recette_cec").hide();
        $("#ind_recette_mois").hide();

        var tabMois = ["Jan", "Feb", "Mar", "Avr", "Mai", "Jun", "Jul","Aou","Sep","Oct","Nov","Dec"];

         // // //gradient bar chart
         var listeMois = [];
        // var recetteMois = [65, 59, 80, 81, 56, 55, 40];
         var recetteMois = [];

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

            //recherche les arrondissements d'une commune
            $("#selectcommunes").on("change", function(){
                var idcommune = $(this).val();

                if(idcommune){
                    getArrondissement(idcommune);
                }
            });


            $("#filtreAnnee").on("change", function(){
                //récupération de la nouvelle période
                periodeChoisie = $(this).val();

                //réinitialisation des graphiques
                var listeMois = [];
                var recetteMois = [];
                var montantTotal =  [];
                var libInstitutions = [];
                //actualisation des graphique
                getRecetteAnnuelle(periodeChoisie,codefonction);
            });

        });

        //recupere les communes de la province selectionnee
        function getCommune(libProvince){
            var url = "{{ route('dashboard_gouv.getCommune.province', ':id') }}";
            url = url.replace(":id",libProvince);
            $.get(url, function (response) {

                var option = "<option selected>Sélectionner la localité</option>";
                   response.forEach(element => {
                    option +='<option value="'+element.code_localite+'">'+element.lib_localite+'</option>';
                   });

                option += "</tbody></table>";
                $("#selectcommunes").html(option);
            });
        }
        //recupere les arrondissements de la commune de selectionnees
        function getArrondissement(idCommune){
            var url = "{{ route('dashboard_gouv.getArrondissement.commune', ':id') }}";
            url = url.replace(":id",idCommune);
            $.get(url, function (response) {
                var table = '<table class="table header-border" style=""><tbody>';
                console.log(response.arrondissements);

                if(response.arrondissements.length > 0){
                    for( var i=0; i < response.arrondissements.length ; i++){
                        var arrondissementCourant = response.arrondissements[i].code_localite;

                        if(response.tablisteRecettesParArrondissement.length > 0){
                            for( var j=0; j < response.tablisteRecettesParArrondissement.length ; j++){


                                console.log(response.tablisteRecettesParArrondissement[j]["codeLocalite"]);


                                if(arrondissementCourant == response.tablisteRecettesParArrondissement[j]["codeLocalite"]){
                                    table +='<tr style="background:#d5f7d9;">'+
                                        '<td style="font-weight: 700; color:grey;font-size:12px">'+response.arrondissements[i].lib_localite+'</td>'+
                                        '<td><span class="montant">'+response.tablisteRecettesParArrondissement[j]["total"]+'</span></td></tr>';
                                }else{
                                    table +='<tr style="background:lightgrey">'+
                                '<td style="font-weight: 700; color:grey;font-size:12px">'+response.arrondissements[i].lib_localite+'</td>'+
                                '<td><span class="montant">$ 0</span></td></tr>';
                                }
                            }
                        }else{
                            table +='<tr style="background:lightgrey">'+
                        '<td style="font-weight: 700; color:grey;font-size:12px">'+response.arrondissements[i].lib_localite+'</td>'+
                        '<td><span class="montant">$ 0</span></td></tr>';
                        }

                    }
                }
                table += "</tbody></table>";

                $("#statCartes2").html(table);
            });
        }

        //fonction de récupération des indicateurs du dashboard
        function getRecetteAnnuelle(periode,codeFonction){
            var route = "{{ route('dashboard_gouv.recette.annuelle') }}";


            $.get(route,{"periode": periode,"codeFonction": codeFonction}, function (data) {

                // console.log(data.tabTopRecettes);
                // return false;

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

                var dataPie = "[";
                var dataBarMois = "[";
                var dataBarRecette = "[";

                //affichage indicateur montant Total
                //alert(data.mt);
                $("#recetteAnnuelle").text(data.mt);
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

                //cas de ministre
                if(codeFonction == "FONC_0023"){
                    if(data.tabTopRecettes.length > 0){
                        for( var i=0; i < data.tabTopRecettes.length ; i++){
                            table +='<tr style="background:#d5f7d9;">'+
                                '<td style="font-weight: 700; color:grey;font-size:12px">'+data.tabTopRecettes[i]['libProvince']+'</td>'+
                                '<td><span class="montant">'+data.tabTopRecettes[i]['Prix']+'</span></td></tr>';


                            table2 +='<tr style="background:#d5f7d9;">'+
                                '<td style="font-weight: 700; color:grey;font-size:12px">'+data.tabTopRecettes[i]['libProvince']+'</td>'+
                                '<td><span class="montant">'+data.tabTopRecettes[i]['Prix']+'</span></td>'+
                                '<td><span class="montant">'+data.tabTopRecettes[i]['auth']+'</span></td></tr>';
                        }
                    }
                }else{

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
                }
                table += "</tbody></table>";

                table2 += "</tbody></table>";
                $("#topTroisRecettes").html(table);
                $("#detailTab").html(table2);



                //debut
                if(data.tablisteRecettesParMois.length > 0){

                    for( var i=0; i < data.tablisteRecettesParMois.length ; i++){
                        var mois = tabMois[data.tablisteRecettesParMois[i]['lemois'] - 1];

                        listeMois.push(mois);
                        recetteMois.push(data.tablisteRecettesParMois[i]['Tmontant']);
                    }

                    for(var i=0; i < listeMois.length ; i++){
                            var lastPosition = listeMois.length - 1;
                            //génération de la   liste des données à charger dans le graphique
                            if(i < lastPosition){
                                dataBarMois= dataBarMois +'"'+listeMois[i]+'",';
                                dataBarRecette = dataBarRecette + recetteMois[i]+',';
                            }else{
                                dataBarMois= dataBarMois +'"'+listeMois[i]+'"';
                                dataBarRecette = dataBarRecette +recetteMois[i];
                            }
                    }
                    dataBarMois = dataBarMois +"]";
                    dataBarRecette = dataBarRecette +"]";

                    const datasBarMois =  JSON.parse(dataBarMois);
                    const datasBarRecette =  JSON.parse(dataBarRecette);
                    //graphique bar
                    Highcharts.chart('container', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: '',
                            align: 'left'
                        },
                        subtitle: {
                            text:'',
                            align: 'left'
                        },
                        xAxis: {
                            categories: datasBarMois,
                            crosshair: true,
                            accessibility: {
                                description: 'Countries'
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: ''
                            }
                        },
                        tooltip: {
                            valuePrefix: '$'
                        },
                        plotOptions: {
                            column: {
                                pointPadding: 0.2,
                                borderWidth: 0
                            }
                        },
                        series: [
                            {
                                name: 'Recette par mois',
                                data: datasBarRecette
                            }

                        ]
                    });

                }

                // alert(data.tablisteRecettesParCec[0]['libProvince']);
                // console.log(data.tablisteRecettesParCec);
                //return false;
                var total = 0;
                if(data.tablisteRecettesParCec.length > 0){

                     //cas de ministre
                    if(codeFonction == "FONC_0023"){
                        for( var i=0; i < data.tablisteRecettesParCec.length ; i++){
                            libInstitutions.push(data.tablisteRecettesParCec[i]['libProvince']);
                            montantTotal.push(data.tablisteRecettesParCec[i]['total']);
                            total = total + data.tablisteRecettesParCec[i]['total'];
                        }
                    }else{
                        for( var i=0; i < data.tablisteRecettesParCec.length ; i++){
                            libInstitutions.push(data.tablisteRecettesParCec[i]['institution']);
                            montantTotal.push(data.tablisteRecettesParCec[i]['total']);
                            total = total + data.tablisteRecettesParCec[i]['total'];
                        }
                    }



                    for(var i=0; i < libInstitutions.length ; i++){
                            var lastPosition = libInstitutions.length - 1;
                            //calcul du pourcentage
                            var p = (montantTotal[i]*100)/total ;
                            //génération de la   liste des données à charger dans le graphique
                            if(i < lastPosition){
                                dataPie= dataPie + '{"name":"'+libInstitutions[i]+'", "y":'+ p +'},';
                            }else{
                                dataPie= dataPie + '{"name":"'+libInstitutions[i]+'", "y":'+ p +'}';
                            }
                    }
                    dataPie = dataPie +"]";

                    const datas =  JSON.parse(dataPie);

                    //graphiques pie
                    Highcharts.chart('container2', {
                            chart: {
                                plotBackgroundColor: null,
                                plotBorderWidth: null,
                                plotShadow: false,
                                type: 'pie'
                            },
                            title: {
                                text: '',
                                align: 'left'
                            },
                            tooltip: {
                                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                            },
                            accessibility: {
                                point: {
                                    valueSuffix: '%'
                                }
                            },
                            plotOptions: {
                                pie: {
                                    allowPointSelect: true,
                                    cursor: 'pointer',
                                    dataLabels: {
                                        enabled: false
                                    },
                                    showInLegend: true
                                }
                            },
                            series: [{
                                name: 'Recette :',
                                colorByPoint: true,

                                data: datas
                            }]
                    });


                }
                //fin




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
                            responsive: true,
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
