@php
	$date = date("Y");
@endphp
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> SIFEC | Vérification 2FA</title>
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
										<form class="form-login" action="{{ route('two-factor.verify.post') }}" method="POST">
                                        @csrf

										<p class="text-center text-muted mb-4" style="margin-bottom: 20px;">
											<i class="fa fa-mobile-alt"></i> Entrez le code à 6 chiffres de votre application d'authentification
										</p>

                                        <div class="form-group">
											<h6>Code de vérification <br>
												<input type="text"
													   name="one_time_password"
													   class="form-control @error('one_time_password') is-invalid @enderror"
													   placeholder="000000"
													   maxlength="6"
													   pattern="[0-9]{6}"
													   style="font-size: 1.5em; letter-spacing: 0.3em; font-weight: bold; text-align: center;"
													   autofocus
													   required>
											</h6>
											@error('one_time_password')
                                                <div class="invalid-feed-back">
                                                <span class="text-danger">{{ $message }}</span>
                                                </div>
                                             @enderror
											<small class="form-text text-muted text-center d-block mt-2">
												<i class="fa fa-clock"></i> Code valide pendant 30 secondes
											</small>
										</div>
                                         <div class="row form-footer">
											 <div class="col-md-12 button-div">
                                                <button type="submit" class="btn btn-primary btn-block"> <i class="fa fa-shield-alt"></i> Vérifier et Se Connecter</button>
                                             </div>
                                             <div class="col-md-12 forget-paswd" style="margin-top:20px">
                                                 <button type="button" class="btn btn-link" style="text-decoration: none; color: #007bff; border: none; background: none; padding: 0;" data-toggle="modal" data-target="#recoveryModal">
													<i class="fa fa-key"></i> Utiliser un code de récupération
												 </button>
											 </div>
											 <div class="col-md-12 forget-paswd" style="margin-top:10px">
                                                 <a href="{{ route('login') }}" style="text-decoration: none; color: #6c757d;"> <i class="fa fa-arrow-left"></i> Retour à la connexion</a>
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

<!-- Modal Code de Récupération -->
<div class="modal fade" id="recoveryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('two-factor.verify-recovery') }}">
                @csrf
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="fa fa-key"></i> Code de Récupération</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <small>
                            <i class="fa fa-info-circle"></i> Entrez l'un de vos codes de récupération à 8 caractères que vous avez sauvegardés lors de l'activation de la 2FA.
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="recovery_code">Code de Récupération</label>
                        <input type="text"
                               name="recovery_code"
                               id="recovery_code"
                               class="form-control text-center text-uppercase"
                               placeholder="XXXXXXXX"
                               maxlength="8"
                               style="font-size: 1.5em; letter-spacing: 0.2em;"
                               required>
                        <small class="form-text text-muted">
                            <i class="fa fa-exclamation-triangle"></i> Ce code ne pourra plus être utilisé après cette connexion
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fa fa-times"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fa fa-sign-in-alt"></i> Vérifier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
<script src="{{ asset('assets-login/js/jquery-3.2.1.min.js')}}"></script>
<script src="{{ asset('assets-login/js/popper.min.js')}}"></script>
<script src="{{asset('assets-login/js/bootstrap.min.js')}}"></script>
<script>
// Auto-format pour le code 2FA
document.querySelector('input[name="one_time_password"]').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});

// Auto-format pour le code de récupération
if(document.getElementById('recovery_code')) {
    document.getElementById('recovery_code').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase();
    });
}
</script>
</html>
