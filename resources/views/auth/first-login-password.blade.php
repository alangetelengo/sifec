@php
	$date = date("Y");
@endphp
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIFEC | Première connexion</title>
    @include('partials.sifec-strip-flash-query')
	<link href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{asset('assets-login/images/fav.png')}}">
    <link rel="stylesheet" href="{{asset('assets-login/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets-login/css/style.css')}}">
    @include('partials.flasher-assets-head')
	<style>
	.sifec-login-spinner { display: inline-block; width: 1em; height: 1em; border: 2px solid currentColor; border-right-color: transparent; border-radius: 50%; animation: sifec-spin 0.6s linear infinite; vertical-align: -0.2em; margin-right: 0.35rem; }
	@keyframes sifec-spin { to { transform: rotate(360deg); } }
	#btn-first-login-sifec:disabled { opacity: 0.7; cursor: not-allowed; }

	/* Même présentation que la page de connexion (resources/views/auth/login.blade.php) */
	.form-cover {
		max-width: 420px;
		margin-left: auto;
		margin-right: auto;
		padding: 1.75rem 1.5rem 1.5rem;
		background: rgba(255, 255, 255, 0.92);
		border-radius: 12px;
		box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.06);
		backdrop-filter: blur(6px);
	}
	.form-cover h1 {
		font-size: 1.5rem;
		font-weight: 600;
		letter-spacing: 0.02em;
		padding-bottom: 0.65rem;
	}
	.form-login .form-group {
		margin-bottom: 1.25rem;
	}
	.form-login .form-group h6 {
		font-size: 0.8125rem;
		font-weight: 600;
		color: #2d3748;
		margin-bottom: 0.5rem;
		line-height: 1.4;
	}
	.form-login .form-control {
		border-radius: 8px;
		border: 1px solid #cbd5e0;
		padding: 0.65rem 0.85rem;
		font-size: 0.9375rem;
		transition: border-color 0.2s ease, box-shadow 0.2s ease;
	}
	.form-login .form-control:focus {
		border-color: #2f855a;
		box-shadow: 0 0 0 3px rgba(47, 133, 90, 0.2);
		outline: none;
	}
	.form-login .form-control.is-invalid {
		border-color: #e53e3e;
	}
	.form-login .form-control.is-invalid:focus {
		box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.15);
	}
	.form-login .invalid-feed-back {
		margin-top: 0.35rem;
		font-size: 0.8125rem;
	}
	.form-login .form-footer {
		margin-top: 0.5rem;
	}
	.form-login .button-div .btn-primary {
		border: none;
		border-radius: 8px;
		padding: 0.7rem 1rem;
		font-weight: 600;
		font-size: 0.9375rem;
		letter-spacing: 0.03em;
		background: linear-gradient(135deg, #276749 0%, #2f855a 50%, #38a169 100%);
		box-shadow: 0 4px 14px rgba(47, 133, 90, 0.35);
		transition: transform 0.15s ease, box-shadow 0.2s ease, filter 0.2s ease;
	}
	.form-login .button-div .btn-primary:hover:not(:disabled) {
		transform: translateY(-1px);
		box-shadow: 0 6px 20px rgba(47, 133, 90, 0.4);
		filter: brightness(1.05);
	}
	.form-login .button-div .btn-primary:active:not(:disabled) {
		transform: translateY(0);
	}
	.form-login .forget-paswd a,
	.form-login .forget-paswd button.btn-link {
		color: #2f855a;
		font-size: 0.875rem;
		font-weight: 500;
		text-decoration: none;
		border-bottom: 1px solid transparent;
		transition: color 0.2s ease, border-color 0.2s ease;
		background: none;
		border-top: none;
		border-left: none;
		border-right: none;
		padding: 0;
		cursor: pointer;
	}
	.form-login .forget-paswd a:hover,
	.form-login .forget-paswd button.btn-link:hover {
		color: #276749;
		border-bottom-color: rgba(39, 103, 73, 0.45);
	}
	@media (max-width: 576px) {
		.form-cover {
			padding: 1.25rem 1rem;
			border-radius: 10px;
		}
	}
	</style>
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
										<h1 style="color:green; border-bottom: 2px solid green; margin-bottom:20px">Première connexion</h1>
										@include('partials.session-flash')
										<p class="text-center text-muted small mb-2" style="font-size: 0.875rem;">
											Compte : <strong>{{ Auth::user()->email }}</strong>
										</p>
										<p class="text-center text-muted small mb-3" style="font-size: 0.8125rem;">
											Saisissez le <strong>mot de passe provisoire</strong>, puis votre <strong>nouveau mot de passe</strong> (au moins 8 caractères).
										</p>

										@if (session('first_login_notice'))
											<div class="alert alert-info small mb-3 py-2">
												Ce compte utilise encore le mot de passe par défaut : vous devez le modifier pour continuer.
											</div>
										@endif

										<form id="form-first-login-sifec" class="form-login" action="{{ route('first-login-password.update') }}" method="POST" autocomplete="off">
                                        @csrf

                                        <div class="form-group">
											<h6>Mot de passe provisoire <br>
												<input type="password" name="current_password"
													class="form-control @error('current_password') is-invalid @enderror"
													placeholder="Mot de passe fourni (ex. provisoire)"
													autocomplete="current-password"
													required>
											</h6>
											@error('current_password')
												<div class="invalid-feed-back">
													<span class="text-danger">{{ $message }}</span>
												</div>
											@enderror
										</div>

										<div class="form-group">
											<h6>Nouveau mot de passe <br>
												<input type="password" name="new_password"
													class="form-control @error('new_password') is-invalid @enderror"
													placeholder="Au moins 8 caractères"
													autocomplete="new-password"
													required>
											</h6>
											@error('new_password')
												<div class="invalid-feed-back">
													<span class="text-danger">{{ $message }}</span>
												</div>
											@enderror
										</div>

										<div class="form-group">
											<h6>Confirmer le nouveau mot de passe <br>
												<input type="password" name="new_password_confirmation"
													class="form-control"
													placeholder="Répétez le nouveau mot de passe"
													autocomplete="new-password"
													required>
											</h6>
										</div>

                                         <div class="row form-footer">
											 <div class="col-md-12 button-div">
                                                <button type="submit" id="btn-first-login-sifec" class="btn btn-primary btn-block"><i class="fa fa-lock"></i> Enregistrer et continuer</button>
                                             </div>
                                         </div>
										</form>

										<div class="form-login forget-paswd text-center" style="margin-top:20px">
											<form method="POST" action="{{ route('logout') }}" class="d-inline">
												@csrf
												<button type="submit" class="btn btn-link">Se déconnecter</button>
											</form>
										</div>
                                    </div>

                          </div>

                    </div>
                </div>

				<center><a href="#" class="text-copyright" style="text-align:center; text-transform:uppercase">© {{$date}} - république du congo - Ministère de l'intérieur et de la décentralisation. Tous droits réservés.</a></center>


            </div>


        </div>
    </div>

<script src="{{ asset('assets-login/js/jquery-3.2.1.min.js')}}"></script>
<script src="{{ asset('assets-login/js/popper.min.js')}}"></script>
<script src="{{ asset('assets-login/js/bootstrap.min.js')}}"></script>
@include('partials.flasher-assets-scripts')
@include('partials.session-toastr')
@flasher_render
<script>
document.getElementById('form-first-login-sifec').addEventListener('submit', function() {
    var btn = document.getElementById('btn-first-login-sifec');
    btn.disabled = true;
    btn.innerHTML = '<span class="sifec-login-spinner"></span> Enregistrement...';
});
</script>
</body>
</html>
