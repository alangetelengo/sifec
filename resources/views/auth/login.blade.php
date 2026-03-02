@php
	$date = date("Y");
@endphp
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> SIFEC | Authentification</title>
	<link href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{asset('assets-login/images/fav.png')}}">
    <link rel="stylesheet" href="{{asset('assets-login/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets-login/css/style.css')}}">
	
</head>
<body>
    <div class="container-fluid bg-login">
        <div class="container" style="">
            <div class="row">
                <div class="col-md-12 login-card">
                    <div class="row">
                       
                              <div class="col-md-12 mx-auto">
                                  <div class="logo-cover" style="width: 100%">
										<center>
                                       <img src="assets/images/logo-sifec.gif" class="logo-mobile">
									   </center>
                                   </div>
                                    <div class="form-cover" style="width: 100%">
										<h1 style="color:green; border-bottom: 2px solid green; margin-bottom:20px">Authentification </h1>
										<form class="form-login" action="{{ route("dashboard.login") }}" method="POST" autocomplete="off">
                                        @csrf
										
										
                                        <div class="form-group">
											<h6>Nom utilisateur <br>
												<input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="off">
											</h6>
											 @error('email')
                                                <div class="invalid-feed-back">
                                                <span class="text-danger">{{ $message }}</span>
                                                </div>
                                             @enderror 
										</div>
										
										 <div class="form-group">
											<h6>Mot de passe <br>
												<input type="password" class="form-control @error('email') is-invalid @enderror" name="password" autocomplete="new-password">
                                           </h6>
											@error('password')
												<div class="invalid-feed-back">
												<span class="text-danger">{{ $message }}</span>
												</div>
											@enderror
										</div>
                                         <div class="row form-footer">
											 <div class="col-md-12 button-div">
                                                <button type="submit" class="btn btn-primary btn-block"> <i class="fa fa-lock"></i> Connexion</button>
                                             </div>
                                             <div class="col-md-12 forget-paswd" style="margin-top:20px">
                                                 <a href="">Mot de passe oublié ?</a>    
											 </div>
                                            
                                         </div>
										</form>
                                    </div>
                              
                          </div>
                   
                    </div>
                </div>
				
				<center><a href="#" class="text-copyright" style="text-align:center; text-transform:uppercase">© {{$date}} - république du congo - Ministère de l'intérieur et de la décentralisation. Tous droits réservés.</a></center>
						
				
            </div>
			
			
        </div>
    </div>

</body>
<script src="{{ asset('assets-login/js/jquery-3.2.1.min.js')}}"></script>
<script src="{{ asset('assets-login/js/popper.min.js')}}"></script>
<script src="{{asset('assets-login/js/bootstrap.min.js')}}"></script>
</html>
