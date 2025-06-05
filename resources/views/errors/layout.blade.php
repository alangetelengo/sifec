<!DOCTYPE html>
<html lang="en" class="h-100">

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
	<meta property="og:image" content="https://tixia.dexignzone.com/xhtml/social-image.png" />
	<meta name="format-detection" content="telephone=no">
    <title>sifec | @yield('title')</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.ico') }}">
    {{--  <link href="./css/style.css" rel="stylesheet">  --}}
    <link href="{{ asset('tpl/css/style.css')}}" rel="stylesheet">
    <style>
        .btn-primary{
            background-color: #21B931;
            border: none;
        }
        .btn-primary:hover{
            background-color: #449D44;
        }
        .btn-primary:submitted{
            background-color: #449D44;
        }
        h3{
            color: #FFF;
            font-weight: bold;
            margin-top: 60%;
        }
        #lien{
            color: #FFF;
            margin-top: 40%;
            text-align: left;
        }
        #logo{
            width: 80%;
        }
    </style>


</head>

<body class="vh-100">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                                <div class="auth-form">
									<div class="text-center">
										<a href="#"><img src="{{ asset('images/Sifec-logo.png') }}" alt="SIFEC" id="logo"></a>
									</div>
                                    @yield('contenu')
                                      <a href="{{ route('dashboard.index') }}" class="btn btn-primary btn-block"> <i class="fa fa-home"></i> Accueil</a>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--**********************************
        Scripts
    ***********************************-->
    <script src="{{ asset('tpl/js/jquery.min.js')}}"></script>
    <script src="{{ asset('tpl/js/bootstrap.min.js')}}"></script>
  <!--BACKSTRETCH-->
  <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
  <script src="{{ asset('tpl/js/backstretch.min.js')}}"></script>
  <script>
    $.backstretch("tpl/images/sifec3.jpg", {
      speed: 100
    });
  </script>

</body>

</html>
