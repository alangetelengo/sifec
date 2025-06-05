<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="admin, dashboard" />
	<meta name="author" content="DexignZone" />
	<meta name="robots" content="index, follow" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="tixia : tixia School Admission Admin  Bootstrap 5 Template" />
	<meta property="og:title" content="tixia : tixia School Admission Admin  Bootstrap 5 Template" />
	<meta property="og:description" content="tixia : tixia School Admission Admin  Bootstrap 5 Template" />
	<meta name="format-detection" content="telephone=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>sifec | @yield('titre')</title>

    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.ico') }}">
	<link rel="stylesheet" href="{{ asset('tpl/vendor/chartist/css/chartist.min.css')}}">
    <link href="{{ asset('tpl/vendor/bootstrap/css/bootstrap.css')}}" rel="stylesheet">

    <link href="{{ asset('tpl/vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}" rel="stylesheet">
	<link href="{{ asset('tpl/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
    <link href="{{ asset('tpl/css/style.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('tpl/vendor/toastr/css/toastr.min.css')}}" type="text/css">

    <!-- Form step -->
    <link href="{{ asset('tpl/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css') }}" rel="stylesheet">
    <!-- Daterange picker -->
    <link href="{{ asset('tpl/vendor/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
    <!-- Clockpicker -->
    <link href="{{ asset('tpl/vendor/clockpicker/css/bootstrap-clockpicker.min.css') }}" rel="stylesheet">
    <!-- asColorpicker -->
    <link href="{{ asset('tpl/vendor/jquery-asColorPicker/css/asColorPicker.min.css') }}" rel="stylesheet">
    <!-- Material color picker -->
    <link href="{{ asset('tpl/vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
    <!-- Pick date -->
    <link href="{{ asset('tpl/wizard/assets/node_modules/wizard/steps.css') }}" rel="stylesheet">
    <!--alerts CSS -->
    <link href="{{ asset('tpl/wizard/assets/node_modules/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('scanner/scanner.css') }}">

    @yield('styles')
    <style>
        .over-loader-page{
            height: auto;
            width: 100%;
            position: absolute;
            display: none;
            overflow: hidden;
            bottom: 0;
            top:0;
            left: 0;
            right: 0;
            z-index: 99999999999999999999999999999999;
            background: rgba(0,0,0,0.6);
        }

        .loader-content{
            width: 500px;
            height: 500px;
            margin: 0 auto;
            margin-top: 20%;
            padding: 20px;

        }

        .loader-content h1{
            text-align: center;
            font-weight: bold;
            color: #ffffff;
        }

        .loader-content i{
            color: #95928a;
            font-size: 5em;
        }
         .text-black, .dropdown-item{
            color: #000!important;
        }
        .wizard-content .wizard>.steps>ul>li>a{
            color: #000!important;
        }
        a{
            color: #FFF!important;
        }
        .mm-active, .wizard-content .wizard.wizard-circle>.steps .step, .wizard-content .wizard>.actions>ul>li>a{
            background-color: #21B931!important;
        }
        .line{
            background-color: #21B931!important;
        }
        .btn-info, .btn-primary{
            background-color: #21B931!important;
            border: none;

        }
        .btn-info:hover, .btn-primary:hover, .wizard-content .wizard>.actions>ul>li>a:hover{
            background-color: #449D44!important;
            border: none;
        }
        .wizard-content .wizard>.steps>ul>li.current .step {
            background-color: #FFF!important;
            border-color: #21B931!important;
            color: #21B931!important;
        }
        .wizard-content .wizard>.steps>ul>li.done .step {
            border-color: #21B931!important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover, .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #21B931!important;
        }
        .button.btn.btn-primary{
            color: #FFF!important;
        }

        .wizard-content .wizard.wizard-circle>.steps>ul>li.done:after{
            background-color: #21B931!important;
        }
        .wizard-content .wizard.wizard-circle>.steps>ul>li.current:before{
            background-color: #21B931!important;
        }
        .wizard-content .wizard.wizard-circle>.steps>ul>li.done:before{
            background-color: #21B931!important;
        }
        a.nav-link, p>a{
            color: #000!important;
        }

        /* TRANSPARENCE DU CONTENU DE CHAQUE PAGE */
        div.container-fluid > div.row{
            opacity: 0.85;
        }

    </style>

    <script>
        function verif_lettre(lalettre)
        {
            // var lettres = new RegExp("[0-9]");
            var lettres =  RegExp("^[a-z A-Z é î ï è ë ' ô ç -]*$");
            var verif;
            var points = 0;

            for(x = 0; x < lalettre.value.length; x++)
            {
                verif = lettres.test(lalettre.value.charAt(x));
                if(lalettre.value.charAt(x) == "."){points++;}
                if(points > 1){verif = false; points = 1;}
                if(verif == false){lalettre.value = lalettre.value.substr(0,x) + lalettre.value.substr(x+1,lalettre.value.length-x+1); x--;}
            }
        }

    </script>


</head>
<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    {{-- LOADER ALANGE SETH --}}

        <div class="over-loader-page">
            <div class="loader-content">
                <h1>Chargement en cours... <i class="fa fa-spinner fa-spin"></i></h1>
            </div>
        </div>

    {{-- END LOADER --}}

    <!--**********************************
        Main wrapper start
    ***********************************-->
    @php
    $user = Auth()->user();
     //r�cup�ration  du type cat�gorie et image d'accueil illustrative de l'institution de l'utilisateur connect�
        $typeCatIns = $user->AffectationActive()->institution->typeInstitution->typeCategorieInstitution->lib_type_categorie_institution;
        $urlImg = $user->AffectationActive()->institution->typeInstitution->typeCategorieInstitution->image_illustrative;

    @endphp

    <div id="main-wrapper" style="background-repeat:no-repeat;background-position:center;background-image:url({{asset($urlImg)}});">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header" style="background-color:#FFF!important;">
            <a href="{{ url('/') }}" class="brand-logo">
            {{-- <img src="{{asset('images/armoiries_sifec.png')}}"  width="60"> --}}
            <img src="{{asset('assets-login/images/logo-sifec-app.gif')}}"  width="90%">
            </a>

            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>

        <div class="header" style="background-color:#FFF!important;">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <strong style="color: #000">
                                @php
                                    $user = Auth()->user();
                                    $fn = $user->AffectationActive()->fonction->code_fonction;

                                @endphp

                                {{ $user->AffectationActive()->institution->lib_institution}} <br>
                                <small>{{ $user->AffectationActive()->fonction->lib_fonction}}</small><br>
                                @if($fn == "FONC_0004" || $fn == "FONC_0005" || $fn == "FONC_0017" || $fn == "FONC_0018")
                                    @if($user->MyLocalites() != "")
                                        <h5>Arrondissement de  {{ $user->MyLocalites()}} </h5>
                                    @endif
                                @endif
                            </strong>
                        </div>

                        <ul class="navbar-nav header-right">

							<li class="nav-item dropdown notification_dropdown">
                                {{-- {{ Auth()->user()-> }} --}}
                                <a class="nav-link bell bell-link success" href="#">
                                    <svg width="22" height="22" viewBox="0 0 23 22" fill="none"><path d="M20.4604 0.848846H3.31682C2.64742 0.849582 2.00565 1.11583 1.53231 1.58916C1.05897 2.0625 0.792727 2.70427 0.791992 3.37367V15.1562C0.792727 15.8256 1.05897 16.4674 1.53231 16.9407C2.00565 17.414 2.64742 17.6803 3.31682 17.681C3.53999 17.6812 3.75398 17.7699 3.91178 17.9277C4.06958 18.0855 4.15829 18.2995 4.15843 18.5226V20.3168C4.15843 20.6214 4.24112 20.9204 4.39768 21.1817C4.55423 21.4431 4.77879 21.6571 5.04741 21.8008C5.31602 21.9446 5.61861 22.0127 5.92292 21.998C6.22723 21.9833 6.52183 21.8863 6.77533 21.7173L12.6173 17.8224C12.7554 17.7299 12.9179 17.6807 13.0841 17.681H17.187C17.7383 17.68 18.2742 17.4993 18.7136 17.1664C19.1531 16.8334 19.472 16.3664 19.6222 15.8359L22.8965 4.05007C22.9998 3.67478 23.0152 3.28071 22.9413 2.89853C22.8674 2.51634 22.7064 2.15636 22.4707 1.8466C22.2349 1.53684 21.9309 1.28565 21.5822 1.1126C21.2336 0.93954 20.8497 0.849282 20.4604 0.848846ZM21.2732 3.60301L18.0005 15.3847C17.9499 15.5614 17.8432 15.7168 17.6964 15.8274C17.5496 15.938 17.3708 15.9979 17.187 15.9978H13.0841C12.5855 15.9972 12.098 16.1448 11.6836 16.4219L5.84165 20.3168V18.5226C5.84091 17.8532 5.57467 17.2115 5.10133 16.7381C4.62799 16.2648 3.98622 15.9985 3.31682 15.9978C3.09365 15.9977 2.87966 15.909 2.72186 15.7512C2.56406 15.5934 2.47534 15.3794 2.47521 15.1562V3.37367C2.47534 3.15051 2.56406 2.93652 2.72186 2.77871C2.87966 2.62091 3.09365 2.5322 3.31682 2.53206H20.4604C20.5905 2.53239 20.7187 2.56274 20.8352 2.62073C20.9516 2.67872 21.0531 2.7628 21.1318 2.86643C21.2104 2.97005 21.2641 3.09042 21.2886 3.21818C21.3132 3.34594 21.3079 3.47763 21.2732 3.60301Z" fill="#000"></path><path d="M5.84161 8.42333H10.0497C10.2729 8.42333 10.4869 8.33466 10.6448 8.17683C10.8026 8.019 10.8913 7.80493 10.8913 7.58172C10.8913 7.35851 10.8026 7.14445 10.6448 6.98661C10.4869 6.82878 10.2729 6.74011 10.0497 6.74011H5.84161C5.6184 6.74011 5.40433 6.82878 5.2465 6.98661C5.08867 7.14445 5 7.35851 5 7.58172C5 7.80493 5.08867 8.019 5.2465 8.17683C5.40433 8.33466 5.6184 8.42333 5.84161 8.42333Z" fill="#000"></path><path d="M13.4161 10.1066H5.84161C5.6184 10.1066 5.40433 10.1952 5.2465 10.3531C5.08867 10.5109 5 10.725 5 10.9482C5 11.1714 5.08867 11.3854 5.2465 11.5433C5.40433 11.7011 5.6184 11.7898 5.84161 11.7898H13.4161C13.6393 11.7898 13.8534 11.7011 14.0112 11.5433C14.169 11.3854 14.2577 11.1714 14.2577 10.9482C14.2577 10.725 14.169 10.5109 14.0112 10.3531C13.8534 10.1952 13.6393 10.1066 13.4161 10.1066Z" fill="#000"></path></svg>
									<div class="pulse-css"></div>
                                </a>
							</li>

                            <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link success" href="#" role="button" data-bs-toggle="dropdown">
                                    <svg width="22" height="22" viewBox="0 0 26 26" fill="none"><path d="M21.75 14.8385V12.0463C21.7471 9.88552 20.9385 7.80353 19.4821 6.20735C18.0258 4.61116 16.0264 3.61555 13.875 3.41516V1.625C13.875 1.39294 13.7828 1.17038 13.6187 1.00628C13.4546 0.842187 13.2321 0.75 13 0.75C12.7679 0.75 12.5454 0.842187 12.3813 1.00628C12.2172 1.17038 12.125 1.39294 12.125 1.625V3.41534C9.97361 3.61572 7.97429 4.61131 6.51794 6.20746C5.06159 7.80361 4.25291 9.88555 4.25 12.0463V14.8383C3.26257 15.0412 2.37529 15.5784 1.73774 16.3593C1.10019 17.1401 0.751339 18.1169 0.75 19.125C0.750764 19.821 1.02757 20.4882 1.51969 20.9803C2.01181 21.4724 2.67904 21.7492 3.375 21.75H8.71346C8.91521 22.738 9.45205 23.6259 10.2331 24.2636C11.0142 24.9013 11.9916 25.2497 13 25.2497C14.0084 25.2497 14.9858 24.9013 15.7669 24.2636C16.548 23.6259 17.0848 22.738 17.2865 21.75H22.625C23.321 21.7492 23.9882 21.4724 24.4803 20.9803C24.9724 20.4882 25.2492 19.821 25.25 19.125C25.2486 18.117 24.8998 17.1402 24.2622 16.3594C23.6247 15.5786 22.7374 15.0414 21.75 14.8385ZM6 12.0463C6.00232 10.2113 6.73226 8.45223 8.02974 7.15474C9.32723 5.85726 11.0863 5.12732 12.9212 5.125H13.0788C14.9137 5.12732 16.6728 5.85726 17.9703 7.15474C19.2677 8.45223 19.9977 10.2113 20 12.0463V14.75H6V12.0463ZM13 23.5C12.4589 23.4983 11.9316 23.3292 11.4905 23.0159C11.0493 22.7026 10.716 22.2604 10.5363 21.75H15.4637C15.284 22.2604 14.9507 22.7026 14.5095 23.0159C14.0684 23.3292 13.5411 23.4983 13 23.5ZM22.625 20H3.375C3.14298 19.9999 2.9205 19.9076 2.75644 19.7436C2.59237 19.5795 2.50014 19.357 2.5 19.125C2.50076 18.429 2.77757 17.7618 3.26969 17.2697C3.76181 16.7776 4.42904 16.5008 5.125 16.5H20.875C21.571 16.5008 22.2382 16.7776 22.7303 17.2697C23.2224 17.7618 23.4992 18.429 23.5 19.125C23.4999 19.357 23.4076 19.5795 23.2436 19.7436C23.0795 19.9076 22.857 19.9999 22.625 20Z" fill="#000"></path></svg>
                                    <div class="pulse-css"></div>
                                </a>
                            </li>
                            <li class="nav-item dropdown header-profile">

                                <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" style="color: white">
                                    <small class="ms-3 text-black">{{ Auth()->user()->personne->sexe === "M" ?  "M." : "Mme" }} {{ Auth()->user()->personne->nom}}</small>
                                    <img src="{{asset('tpl/images/images.png')}}" class="rounded-circle" width="80"><p class="mb-0 text-success" style="margin-left:-10px; font-size:56px;">.</p>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end">

                                    <a href="{{ route("utilisateur.profile", Auth()->user()->code_user) }}" class="dropdown-item ai-icon">
                                        <svg id="icon-user1" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                        <span class="ms-2 text-black">Profile </span>
                                    </a>

                                    <a class="dropdown-item ai-icon" href="{{ route('logout') }}" onclick="event.preventDefault();

                                        document.getElementById('logout-form').submit();">


                                        <svg id="icon-logout" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                        <span class="ms-2 text-black">Déconnexion </span>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>

                        </ul>
					</div>
                </nav>
            </div>
        </div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        @include('layout.sidebar')
        <!--**********************************
            Sidebar end
        ***********************************-->

		<!--**********************************
            EventList
        ***********************************-->

            {{-- j'ai enlevé --}}

		<!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body" style="min-height:450px">
            <!-- row -->

			<div class="container-fluid" style="min-height:450px">
				@yield('corps')
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->

        <!--**********************************
            Footer start
        ***********************************-->

        <!--**********************************
            Footer end
        ***********************************-->

		<!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->


    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    {{-- DEBUT MODAL RECHERCHE ACTE DE NAISSANCE --}}
        @include("layout.modal-search-acte.searchActeNaissance")

    {{-- FIN MODAL RECHERCHE ACTE DE NAISSACE --}}
    <!--**********************************
        Scripts
    ***********************************-->

    <!-- Required vendors -->
    <script src="{{ asset('tpl/vendor/global/global.min.js') }}"></script>
	<script src="{{ asset('tpl/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('tpl/vendor/chart.js/Chart.bundle.min.js') }}"></script>
	<script src="{{ asset('tpl/vendor/bootstrap-datetimepicker/js/moment.js') }}"></script>
	<script src="{{ asset('tpl/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('tpl/js/custom.min.js') }}"></script>
    <script src="{{ asset('tpl/vendor/chart.js/Chart.bundle.min.js') }}"></script>
	<script src="{{ asset('tpl/js/deznav-init.js') }}"></script>
    <script src="{{asset('tpl/vendor/toastr/js/toastr.min.js')}}"></script>
    <script src="{{asset('sweetalert2.all.min.js')}}"></script>
    <script src="{{ asset('scanner/scanner.js') }}"></script>


    <script>
        $(function(){
            $("#resultat").hide();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("a.chercheacte").on("click",function(){
                var modal = $("#modal-search-acte-naissance");
                modal.modal("show");
                return false;
            });

             // Rechercher l'acte
            $('button#rechercher').on("click", function () {
                var niupp = $("#numero_acte").val();
                var url = "{{ route('acteNaissance.find.acte') }}";
                var table = '<div class="table-responsive">'+
                            '<table id="example" class="table table-responsive-md table-hover">'+
                                '<thead>'+
                                    '<tr>'+
                                        '<th><strong>Nom et prénom</strong></th>'+
                                        '<th><strong>Date de naissance</strong></th>'+
                                        '<th><strong>Sexe</strong></th>'+
                                        '<th><strong>Lieu naissance</strong></th>'+
                                        '<th><strong>Centre d\'Etat Civil de naissance</strong></th>'+
                                        '<th><strong>Actions</strong></th>'+
                                ' </tr>'+
                                '</thead>'+
                                '<tbody>';

                if(niupp == "" || niupp == null){
                    // $("button#rechercher").prop("disabled",true);
                    $("#error_niupp").html("veuillez saisir le numéro de l'acte");
                    return false;
                }
                // $("button#rechercher").prop("disabled",false);
                $.post(url,{niupp:niupp}, function(response) {
                    $("#error_niupp").html("");
                    $("#resultatnumeroacte").html(niupp); //affichage du numero de l'acte
                   if(response.code == "200"){
                        $("#numero_acte").val("");
                        var genereCopie = "{{ route('acteNaissance.print.copie',':id') }}";
                        var genereExtrait = "{{ route('acteNaissance.print.extrait',':id') }}";
                        genereCopie = genereCopie.replace(":id",response.cdn);
                        genereExtrait = genereExtrait.replace(":id",response.cdn);
                        alert(response.nomPrenom);

                        table +='<tr class="tr">'+
                                    '<td>'+response.nomPrenom+'</td>'+
                                    '<td>'+response.dateNaissance+'</td>'+
                                    '<td>'+response.sexe+'</td>'+
                                    '<td>'+response.lieuNaissance+'</td>'+
                                    '<td>'+response.cec+'</td>'+
                                    '<td><div class="btn-group btn-group-xs">'+
                                        '<a class="btn btn-sm btn-success show-generate-copie" href='+genereCopie+'>Générer Copie</a>&nbsp;&nbsp;&nbsp;&nbsp;'+
                                        '<a class="btn btn-sm btn-warning" href='+genereExtrait+' target="_blank">Générer Extrait</a>'+
                                    '</div></td>'+

                                '</tr>';

                        $("#resultat").show();
                        $("#resultatrech").html(table);

                    }
                    if(response.code == "180"){
                        $("#resultat").hide();
                        flashAlert(response.message);
                    }
                 });


                return false;
            });

        });

        function notification(type,message,title=null){
           switch (type) {
            case "success":
                toastr.success(message,title);
                break;

            case "warning":
                toastr.warning(message,title);
                break;

            case "error":
                toastr.error(message,title);
                break;

            default:
                break;
           }
        }

        function flashAlert(htmlTitle,type,htmlContent){
            Swal.fire({
                title: htmlTitle,
                type: type, //info,error,danger,warning,success
                html: htmlContent,
                showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonText: 'Ok',
                confirmButtonAriaLabel: 'Thumbs up, great!',
                cancelButtonText: 'Annuler',
                cancelButtonAriaLabel: 'Thumbs down',
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
                cancelButtonClass: 'btn btn-info ml-1'
            });
        }

        function verif_nombre(champ)
        {
            var chiffres = new RegExp("[0-9-.]");
            var verif;
            var points = 0;
            for(x = 0; x < champ.value.length; x++)
            {
                verif = chiffres.test(champ.value.charAt(x));
                if(champ.value.charAt(x) == "."){points++;}
                    if(points > 1){verif = false; points = 1;}
                if(verif == false){champ.value = champ.value.substr(0,x) + champ.value.substr(x+1,champ.value.length-x+1); x--;}
            }

        }


        function uppercase(obj){
            obj.value = obj.value.toUpperCase();
        }
        function ucfirst(obj) {
            // alert(obj.value)
            obj.value = obj.value[0].toUpperCase() + obj.value.slice(1);
        }


    </script>



    <script src="{{ asset('tpl/vendor/jquery-smartwizard/dist/js/jquery.smartWizard.js') }}"></script>
    <script src="{{ asset('tpl/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
    <!-- Form validate init -->
    <script src="{{ asset('tpl/js/plugins-init/jquery.validate-init.js') }}"></script>
     <!-- Daterangepicker -->
     <script src="{{ asset('tpl/js/plugins-init/bs-daterange-picker-init.js') }}"></script>
     <!-- Clockpicker init -->
     <script src="{{ asset('tpl/js/plugins-init/clock-picker-init.js') }}"></script>
     <!-- asColorPicker init -->
     <script src="{{ asset('tpl/js/plugins-init/jquery-asColorPicker.init.js') }}"></script>
     <!-- Material color picker init -->
     <script src="{{ asset('tpl/js/plugins-init/material-date-picker-init.js') }}"></script>
     <!-- Pickdate -->
     <script src="{{ asset('tpl/js/plugins-init/pickadate-init.js') }}"></script>
    <!-- This Page JS -->
    <script src="{{ asset('tpl/wizard/assets/node_modules/wizard/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('tpl/wizard/assets/node_modules/wizard/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('tpl/wizard/assets/node_modules/sweetalert2/dist/sweetalert2.min.js') }}"></script>

    <script src="{{ asset('tpl/js/moment.min.js') }}"></script>
    <script src="{{ asset('sifec-js/sifec.js') }}"></script>

    <script>


        //************* Scanner ***************/
        function scanToLocalDisk() {
            scanner.scan(displayResponseOnPage,
                {
                    "output_settings": [
                        {
                            "type": "save",
                            "format": "pdf",
                            "save_path": "${TMP}\\${TMS}${EXT}"
                        }
                    ]
                }
            );
        }

        //  Scan: output PDF original and JPG thumbnails
        function scanToPdfWithThumbnails() {
            // if(document.getElementById('C_REF_PCES').value == "" || document.getElementById('typePieces').value == ""){
            //     console.log('Les champs Référence & Type sont obligatoires avant le scannage');
            //     return ;
            // }
            scanner.scan(displayImagesOnPage,
                {
                    "output_settings": [
                        {
                            "type": "return-base64",
                            "format": "pdf",
                            // "pdf_text_line": "By ${USERNAME} on ${DATETIME}"
                            "pdf_text_line": "By ${USERNAME}"
                        },
                        {
                            "type": "return-base64-thumbnail",
                            "format": "jpg",
                            "thumbnail_height": 200
                        }
                    ]
                }
            );
        }

        //  Processes the scan result
        function displayImagesOnPage(successful, mesg, response) {
            if (!successful) { // On error
                console.error('Failed: ' + mesg);
                return;
            }

            if (successful && mesg != null && mesg.toLowerCase().indexOf('user cancel') >= 0) { // User cancelled.
                console.info('User cancelled');
                return;
            }

            //------------------------------------------------------------------------------------------------
            var scannedImages = scanner.getScannedImages(response, true, false); // returns an array of ScannedImage
            for(var i = 0; (scannedImages instanceof Array) && i < scannedImages.length; i++) {
                var scannedImage = scannedImages[i];
                processOriginal(scannedImage);
            }

            var thumbnails = scanner.getScannedImages(response, false, true); // returns an array of ScannedImage
            for(var i = 0; (thumbnails instanceof Array) && i < thumbnails.length; i++) {
                var thumbnail = thumbnails[i];
                processThumbnail(thumbnail);
            }
        }

        //  Images scanned so far.
        var imagesScanned = [];
        //  Processes a ScannedImage

        // Processes an original
        function processOriginal(scannedImage) {
            imagesScanned.push(scannedImage);
        }

        //  Processes a thumbnail
        function processThumbnail(scannedImage) {
            var elementImg = scanner.createDomElementFromModel( {
                'name': 'img',
                'attributes': {
                    'class': 'scanned',
                    'src': scannedImage.src
                }
            });
            document.getElementById('images').appendChild(elementImg);
        }



       // Btn ajouter la piece
        function btn_importer(){
            afficherQuelquesInput(["div_importer_fichier","btnScanner"]);
            cacherQuelquesInput(["div_scanner_fichier","btnImporter"]);
        }

        // // Btn annuler la piece
        function btn_scanner(){
            afficherQuelquesInput(["div_scanner_fichier","btnImporter"]);
            cacherQuelquesInput(["div_importer_fichier","btnScanner"]);
        }
    </script>
    @yield('scripts')


</body>
</html>
