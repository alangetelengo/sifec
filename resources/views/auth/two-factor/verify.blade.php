@php
	$date = date("Y");
@endphp
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SIFEC | Vérification 2FA</title>
	<link href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('assets-login/images/fav.png') }}">
    <link rel="stylesheet" href="{{ asset('assets-login/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets-login/css/style.css') }}">
	<style>
	.sifec-2fa-spinner { display: inline-block; width: 1em; height: 1em; border: 2px solid currentColor; border-right-color: transparent; border-radius: 50%; animation: sifec-2fa-spin 0.6s linear infinite; vertical-align: -0.2em; margin-right: 0.35rem; }
	@keyframes sifec-2fa-spin { to { transform: rotate(360deg); } }
	#btn-verify-2fa-sifec:disabled { opacity: 0.7; cursor: not-allowed; }

	/* Même charte que la page de connexion (login.blade.php) */
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
	.form-login .forget-paswd a {
		color: #2f855a;
		font-size: 0.875rem;
		font-weight: 500;
		text-decoration: none;
		border-bottom: 1px solid transparent;
		transition: color 0.2s ease, border-color 0.2s ease;
	}
	.form-login .forget-paswd a:hover {
		color: #276749;
		border-bottom-color: rgba(39, 103, 73, 0.45);
	}
	.form-login .sifec-2fa-intro {
		font-size: 0.9rem;
		color: #4a5568;
		line-height: 1.5;
		margin-bottom: 1.25rem;
		text-align: center;
	}
	.form-login .sifec-2fa-intro i {
		color: #2f855a;
		margin-right: 0.35rem;
	}
	.form-login input[name="one_time_password"] {
		font-size: 1.35rem !important;
		letter-spacing: 0.35em !important;
		font-weight: 600 !important;
		text-align: center;
		padding: 0.75rem 0.5rem !important;
	}
	.form-login .sifec-2fa-hint {
		display: block;
		font-size: 0.8125rem;
		color: #718096;
		text-align: center;
		margin-top: 0.5rem;
	}
	.form-login .sifec-2fa-hint i {
		color: #2f855a;
		opacity: 0.85;
	}
	.form-login .forget-paswd .btn-link-sifec {
		color: #2f855a;
		font-size: 0.875rem;
		font-weight: 500;
		padding: 0;
		border: none;
		background: none;
		text-decoration: none;
		cursor: pointer;
	}
	.form-login .forget-paswd .btn-link-sifec:hover {
		color: #276749;
		text-decoration: underline;
	}
	.form-login .forget-paswd .sifec-back-login {
		color: #718096;
		font-size: 0.875rem;
		font-weight: 500;
		text-decoration: none;
		border-bottom: 1px solid transparent;
		transition: color 0.2s ease, border-color 0.2s ease;
	}
	.form-login .forget-paswd .sifec-back-login:hover {
		color: #4a5568;
		border-bottom-color: rgba(74, 85, 104, 0.35);
	}
	.sifec-2fa-modal .modal-content {
		border: none;
		border-radius: 12px;
		overflow: hidden;
		box-shadow: 0 12px 40px rgba(0, 0, 0, 0.18);
	}
	.sifec-2fa-modal .modal-header {
		background: linear-gradient(135deg, #276749 0%, #2f855a 50%, #38a169 100%);
		color: #fff;
		border-bottom: none;
		align-items: center;
	}
	.sifec-2fa-modal .modal-header .close {
		color: #fff;
		opacity: 0.92;
		text-shadow: none;
	}
	.sifec-2fa-modal .modal-body .form-control {
		border-radius: 8px;
		border: 1px solid #cbd5e0;
	}
	.sifec-2fa-modal .modal-body .form-control:focus {
		border-color: #2f855a;
		box-shadow: 0 0 0 3px rgba(47, 133, 90, 0.2);
		outline: none;
	}
	.sifec-2fa-modal .modal-footer .btn-secondary {
		border-radius: 8px;
	}
	.sifec-2fa-modal .modal-footer .btn-sifec-submit {
		border: none;
		border-radius: 8px;
		padding: 0.5rem 1rem;
		font-weight: 600;
		background: linear-gradient(135deg, #276749 0%, #2f855a 50%, #38a169 100%);
		color: #fff;
		box-shadow: 0 4px 12px rgba(47, 133, 90, 0.3);
	}
	.sifec-2fa-modal .modal-footer .btn-sifec-submit:hover {
		filter: brightness(1.06);
		color: #fff;
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
        <div class="container">
            <div class="row">
                <div class="col-md-12 login-card">
                    <div class="row">
                        <div class="col-md-12 mx-auto">
                            <div class="logo-cover" style="width: 100%">
                                <center>
                                    <img src="assets/images/logo-sifec.gif" alt="SIFEC" class="logo-mobile">
                                </center>
                            </div>
                            <div class="form-cover">
                                <h1 style="color:green; border-bottom: 2px solid green; margin-bottom: 20px;">Vérification 2FA</h1>
                                <form id="form-2fa-verify-sifec" class="form-login" action="{{ route('two-factor.verify.post') }}" method="POST" autocomplete="off">
                                    @csrf

                                    <p class="sifec-2fa-intro mb-0">
                                        <i class="fa fa-mobile-alt"></i>
                                        Saisissez le code à 6 chiffres affiché par votre application d’authentification.
                                    </p>

                                    <div class="form-group mb-3">
                                        <h6>Code de vérification</h6>
                                        <input type="text"
                                               name="one_time_password"
                                               class="form-control @error('one_time_password') is-invalid @enderror"
                                               placeholder="000000"
                                               maxlength="6"
                                               pattern="[0-9]{6}"
                                               inputmode="numeric"
                                               autocomplete="one-time-code"
                                               autofocus
                                               required>
                                        @error('one_time_password')
                                            <div class="invalid-feed-back">
                                                <span class="text-danger">{{ $message }}</span>
                                            </div>
                                        @enderror
                                        <small class="sifec-2fa-hint">
                                            <i class="fa fa-clock"></i> Le code est renouvelé environ toutes les 30 secondes.
                                        </small>
                                    </div>

                                    <div class="row form-footer">
                                        <div class="col-md-12 button-div">
                                            <button type="submit" id="btn-verify-2fa-sifec" class="btn btn-primary btn-block">
                                                <i class="fa fa-shield-alt"></i> Vérifier et se connecter
                                            </button>
                                        </div>
                                        <div class="col-md-12 forget-paswd" style="margin-top: 18px;">
                                            <button type="button" class="btn-link-sifec" data-toggle="modal" data-target="#recoveryModal">
                                                <i class="fa fa-key"></i> Utiliser un code de récupération
                                            </button>
                                        </div>
                                        <div class="col-md-12 forget-paswd text-center" style="margin-top: 10px;">
                                            <a href="{{ route('login') }}" class="sifec-back-login">
                                                <i class="fa fa-arrow-left"></i> Retour à la connexion
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <center>
                    <a href="#" class="text-copyright" style="text-align:center; text-transform:uppercase">© {{ $date }} - république du congo - Ministère de l'intérieur et de la décentralisation. Tous droits réservés.</a>
                </center>
            </div>
        </div>
    </div>

<!-- Modal code de récupération -->
<div class="modal fade sifec-2fa-modal" id="recoveryModal" tabindex="-1" role="dialog" aria-labelledby="recoveryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('two-factor.verify-recovery') }}" autocomplete="off">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="recoveryModalLabel"><i class="fa fa-key"></i> Code de récupération</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info border-0 mb-3" style="background: #e8f4f1; color: #1e4d42; border-radius: 8px;">
                        <small class="mb-0">
                            <i class="fa fa-info-circle"></i>
                            Entrez l’un de vos codes à 8 caractères enregistrés lors de l’activation de la double authentification.
                        </small>
                    </div>
                    <div class="form-group mb-0">
                        <label for="recovery_code" class="font-weight-bold text-dark" style="font-size: 0.875rem;">Code de récupération</label>
                        <input type="text"
                               name="recovery_code"
                               id="recovery_code"
                               class="form-control text-center text-uppercase mt-1"
                               placeholder="XXXXXXXX"
                               maxlength="8"
                               style="font-size: 1.25rem; letter-spacing: 0.2em;"
                               autocomplete="off"
                               required>
                        <small class="form-text text-muted mt-2 d-block">
                            <i class="fa fa-exclamation-triangle"></i> Ce code ne pourra plus être utilisé après cette connexion.
                        </small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fa fa-times"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-sifec-submit">
                        <i class="fa fa-sign-in-alt"></i> Vérifier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('assets-login/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('assets-login/js/popper.min.js') }}"></script>
<script src="{{ asset('assets-login/js/bootstrap.min.js') }}"></script>
<script>
document.getElementById('form-2fa-verify-sifec').addEventListener('submit', function() {
    var btn = document.getElementById('btn-verify-2fa-sifec');
    btn.disabled = true;
    btn.innerHTML = '<span class="sifec-2fa-spinner"></span> Vérification...';
});
document.querySelector('input[name="one_time_password"]').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});
if (document.getElementById('recovery_code')) {
    document.getElementById('recovery_code').addEventListener('input', function() {
        this.value = this.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase();
    });
}
</script>
</body>
</html>
