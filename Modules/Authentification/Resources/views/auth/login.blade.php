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
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="format-detection" content="telephone=no">
    <title>Connexion </title>
    <!-- Favicon icon -->

    <link href="{{ asset('tpl/css/style.css')}}" rel="stylesheet">

</head>

<body class="vh-100">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
									<div class="text-center mb-3">
										Logo sifec
									</div>
                                    <h4 class="text-center mb-4">Connexion</h4>
                                    <form action="{{ route("custom.auth") }}" method="POST" autocomplete="off">
                                        @csrf
                                        <div class="form-group">
                                            <label class="mb-1"><strong>Email</strong></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="off">
                                            @error('email')
                                            <div class="invalid-feed-back">
                                                <span class="text-danger">{{ $message }}</span>
                                            </div>
                                            @enderror                                        </div>
                                        <div class="form-group">
                                            <label class="mb-1"><strong>Password</strong></label>
                                            <input type="password" class="form-control @error('email') is-invalid @enderror" name="password" autocomplete="new-password">
                                            @error('password')
                                            <div class="invalid-feed-back">
                                                <span class="text-danger">{{ $message }}</span>
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-block">Connecter</button>
                                        </div>
                                    </form>
                                </div>
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
    <!-- Required vendors -->
    <script src="{{ asset('tpl/vendor/global/global.min.js') }}"></script>
	<script src="{{ asset('tpl/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('tpl/js/custom.min.js') }}"></script>
    <script src="{{ asset('tpl/js/deznav-init.js') }}"></script>

</body>

</html>
