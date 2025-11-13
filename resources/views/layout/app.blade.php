@php
    $user = Auth()->user();
    $notifications = $user ? $user->unreadNotifications : collect();
    $notificationsCount = $notifications->count();
@endphp
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
    <!-- Pick date -->
    <link href="{{ asset('tpl/wizard/assets/node_modules/wizard/steps.css') }}" rel="stylesheet">
    <!--alerts CSS -->
    <link href="{{ asset('tpl/wizard/assets/node_modules/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">

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

        .wizard-content .wizard.wizard-circle>.steps>li.current:before{
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




        /* Toastr notifications plus visibles */
        /* #toast-container > .toast {
            background-color: #83b088 !important;
            color: #222 !important;
            font-size: 1.2em;
            font-weight: bold;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
            opacity: 0.98 !important;
        }
        #toast-container > .toast-success {
            border-left: 8px solid #fff;
        }
        #toast-container > .toast-error {
            border-left: 8px solid #e74c3c;
        }
        #toast-container > .toast-warning {
            border-left: 8px solid #f39c12;
        }
        #toast-container > .toast-info {
            border-left: 8px solid #3498db;
        } */

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
     //récupération  du type catégorie et image d'accueil illustrative de l'institution de l'utilisateur connecté
        $typeCatIns = '';
        $urlImg = '';

        if ($user && $user->affectationActive()) {
            $affectation = $user->affectationActive();
            if ($affectation && $affectation->institution && $affectation->institution->typeInstitution) {
                $typeCategorieInstitution = $affectation->institution->typeInstitution->typeCategorieInstitution;
                if ($typeCategorieInstitution) {
                    $typeCatIns = $typeCategorieInstitution->lib_type_categorie_institution ?? '';
                    $urlImg = $typeCategorieInstitution->image_illustrative ?? '';
                }
            }
        }

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
                                    $fn = '';
                                    $libInstitution = '';
                                    $libFonction = '';

                                    if ($user && $user->affectationActive()) {
                                        $affectation = $user->affectationActive();
                                        if ($affectation && $affectation->fonction) {
                                            $fn = $affectation->fonction->code_fonction;
                                            $libFonction = $affectation->fonction->lib_fonction;
                                        }
                                        if ($affectation && $affectation->institution) {
                                            $libInstitution = $affectation->institution->lib_institution;
                                        }
                                    }

                                @endphp

                                {{ $libInstitution }} <br>
                                <small>{{ $libFonction }}</small><br>
                                @if($fn == "FONC_0004" || $fn == "FONC_0005" || $fn == "FONC_0017" || $fn == "FONC_0018")
                                    @if($user && $user->MyLocalites() != "")
                                        <h5>Arrondissement de  {{ $user->MyLocalites()}} </h5>
                                    @endif
                                @endif
                            </strong>
                        </div>

                        <ul class="navbar-nav header-right">

							<li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link bell bell-link success" href="#" role="button" data-bs-toggle="dropdown">
                                    <svg width="22" height="22" viewBox="0 0 23 22" fill="none"><path d="M20.4604 0.848846H3.31682C2.64742 0.849582 2.00565 1.11583 1.53231 1.58916C1.05897 2.0625 0.792727 2.70427 0.791992 3.37367V15.1562C0.792727 15.8256 1.05897 16.4674 1.53231 16.9407C2.00565 17.414 2.64742 17.6803 3.31682 17.681C3.53999 17.6812 3.75398 17.7699 3.91178 17.9277C4.06958 18.0855 4.15829 18.2995 4.15843 18.5226V20.3168C4.15843 20.6214 4.24112 20.9204 4.39768 21.1817C4.55423 21.4431 4.77879 21.6571 5.04741 21.8008C5.31602 21.9446 5.61861 22.0127 5.92292 21.998C6.22723 21.9833 6.52183 21.8863 6.77533 21.7173L12.6173 17.8224C12.7554 17.7299 12.9179 17.6807 13.0841 17.681H17.187C17.7383 17.68 18.2742 17.4993 18.7136 17.1664C19.1531 16.8334 19.472 16.3664 19.6222 15.8359L22.8965 4.05007C22.9998 3.67478 23.0152 3.28071 22.9413 2.89853C22.8674 2.51634 22.7064 2.15636 22.4707 1.8466C22.2349 1.53684 21.9309 1.28565 21.5822 1.1126C21.2336 0.93954 20.8497 0.849282 20.4604 0.848846ZM21.2732 3.60301L18.0005 15.3847C17.9499 15.5614 17.8432 15.7168 17.6964 15.8274C17.5496 15.938 17.3708 15.9979 17.187 15.9978H13.0841C12.5855 15.9972 12.098 16.1448 11.6836 16.4219L5.84165 20.3168V18.5226C5.84091 17.8532 5.57467 17.2115 5.10133 16.7381C4.62799 16.2648 3.98622 15.9985 3.31682 15.9978C3.09365 15.9977 2.87966 15.909 2.72186 15.7512C2.56406 15.5934 2.47534 15.3794 2.47521 15.1562V3.37367C2.47534 3.15051 2.56406 2.93652 2.72186 2.77871C2.87966 2.62091 3.09365 2.5322 3.31682 2.53206H20.4604C20.5905 2.53239 20.7187 2.56274 20.8352 2.62073C20.9516 2.67872 21.0531 2.7628 21.1318 2.86643C21.2104 2.97005 21.2641 3.09042 21.2886 3.21818C21.3132 3.34594 21.3079 3.47763 21.2732 3.60301Z" fill="#000"></path><path d="M5.84161 8.42333H10.0497C10.2729 8.42333 10.4869 8.33466 10.6448 8.17683C10.8026 8.019 10.8913 7.80493 10.8913 7.58172C10.8913 7.35851 10.8026 7.14445 10.6448 6.98661C10.4869 6.82878 10.2729 6.74011 10.0497 6.74011H5.84161C5.6184 6.74011 5.40433 6.82878 5.2465 6.98661C5.08867 7.14445 5 7.35851 5 7.58172C5 7.80493 5.08867 8.019 5.2465 8.17683C5.40433 8.33466 5.6184 8.42333 5.84161 8.42333Z" fill="#000"></path><path d="M13.4161 10.1066H5.84161C5.6184 10.1066 5.40433 10.1952 5.2465 10.3531C5.08867 10.5109 5 10.725 5 10.9482C5 11.1714 5.08867 11.3854 5.2465 11.5433C5.40433 11.7011 5.6184 11.7898 5.84161 11.7898H13.4161C13.6393 11.7898 13.8534 11.7011 14.0112 11.5433C14.169 11.3854 14.2577 11.1714 14.2577 10.9482C14.2577 10.725 14.169 10.5109 14.0112 10.3531C13.8534 10.1952 13.6393 10.1066 13.4161 10.1066Z" fill="#000"></path></svg>
                                    <span id="notif-badge" class="badge badge-danger" style="position:absolute;top:0;right:0;font-size:18px;z-index:99999;{{ $notificationsCount > 0 ? '' : 'display:none;' }}">
                                        {{ $notificationsCount > 0 ? $notificationsCount : '' }}
                                    </span>
                                    <div class="pulse-css"></div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" id="notif-dropdown-content">
                                    <h6 class="dropdown-header">Notifications</h6>
                                    <div id="notif-list">
                                        <span class="dropdown-item text-center">Chargement...</span>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item dropdown header-profile">
                                @if(Auth::check() && Auth::user())
                                    @php
                                        $currentUser = Auth::user();
                                        $civilite = 'M.';
                                        $nom = '';

                                        if ($currentUser->personne) {
                                            $civilite = $currentUser->personne->sexe === "M" ? "M." : "Mme";
                                            $nom = $currentUser->personne->nom;
                                        }
                                    @endphp

                                    <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" style="color: white">
                                        <small class="ms-3 text-black">{{ $civilite }} {{ $nom }}</small>
                                        <img src="{{asset('tpl/images/images.png')}}" class="rounded-circle" width="80"><p class="mb-0 text-success" style="margin-left:-10px; font-size:56px;">.</p>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end">

                                        <a href="{{ route("utilisateur.profile", $currentUser->code_user) }}" class="dropdown-item ai-icon">
                                            <svg id="icon-user1" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                            <span class="ms-2 text-black">Profile </span>
                                        </a>

                                        <a href="{{ route('two-factor.index') }}" class="dropdown-item ai-icon">
                                            <svg id="icon-shield" class="text-success" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                            <span class="ms-2 text-black">Double Authentification
                                                @if($currentUser->hasTwoFactorEnabled())
                                                    <i class="fas fa-check-circle text-success" title="2FA Activée"></i>
                                                @endif
                                            </span>
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
                                @endif
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
    <script src="{{ asset('tpl/js/moment.min.js') }}"></script>
	<script src="{{ asset('tpl/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('tpl/js/custom.min.js') }}"></script>
	<script src="{{ asset('tpl/js/deznav-init.js') }}"></script>
    <script src="{{asset('tpl/vendor/toastr/js/toastr.min.js')}}"></script>
    <script src="{{asset('sweetalert2.all.min.js')}}"></script>

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
    <!-- This Page JS -->
    <script src="{{ asset('tpl/wizard/assets/node_modules/wizard/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('tpl/wizard/assets/node_modules/sweetalert2/dist/sweetalert2.min.js') }}"></script>

    <script src="{{ asset('sifec-js/sifec.js') }}"></script>

    <script>
        // Scanner.js supprimé - Utiliser upload de fichiers à la place
        // Voir docs/MIGRATION_SCANNER_VERS_UPLOAD.md pour les instructions
    </script>
    @yield('scripts')

    <script>
        function refreshNotificationBadge() {
            $.get("{{ route('notifications.unreadCount') }}", function(data) {
                let badge = $('#notif-badge');
                if (data.count > 0) {
                    badge.text(data.count).show();
                } else {
                    badge.hide();
                }
            });
        }

        function refreshNotificationDropdown() {
            $.get("{{ route('notifications.unreadList') }}", function(data) {
                $('#notif-list').html(data.html);
            });
        }

        $(document).ready(function() {
            // Chargement initial
            refreshNotificationBadge();
            refreshNotificationDropdown();

            // Rafraîchit toutes les 30 secondes
            setInterval(refreshNotificationBadge, 30000);

            // Rafraîchit le dropdown à l'ouverture
            $('.notification_dropdown > a').on('click', function() {
                refreshNotificationDropdown();
            });
        });
    </script>

</body>
</html>
