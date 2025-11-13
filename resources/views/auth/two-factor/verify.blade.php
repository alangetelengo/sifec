@extends('layout.app')

@section('titre', 'Vérification 2FA')

@section('styles')
<style>
    .breadcrumb-item a {
        color: #007bff !important;
        text-decoration: none;
    }
    .breadcrumb-item a:hover {
        color: #0056b3 !important;
        text-decoration: underline;
    }
    .breadcrumb-item.active {
        color: #6c757d;
    }
</style>
@endsection

@section('corps')
<div class="container-fluid">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Connexion</a></li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-shield-alt"></i> Vérification 2FA</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="fas fa-shield-alt"></i> Vérification en Deux Étapes</h4>
                </div>
                <div class="card-body">

                    <p class="text-center text-muted mb-4">
                        <i class="fas fa-mobile-alt"></i> Entrez le code à 6 chiffres de votre application d'authentification
                    </p>


                    <form method="POST" action="{{ route('two-factor.verify.post') }}">
                        @csrf
                        <div class="form-group mb-4">
                            <input type="text"
                                   name="one_time_password"
                                   class="form-control form-control-lg text-center"
                                   placeholder="000000"
                                   maxlength="6"
                                   pattern="[0-9]{6}"
                                   style="font-size: 2.5em; letter-spacing: 0.5em; font-weight: bold;"
                                   autofocus
                                   required>
                            <small class="form-text text-muted text-center d-block mt-2">
                                <i class="fas fa-clock"></i> Code valide pendant 30 secondes
                            </small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt"></i> Vérifier et Se Connecter
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="text-muted small mb-2">
                            <i class="fas fa-question-circle"></i> Vous ne pouvez pas accéder à votre application ?
                        </p>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#recoveryModal">
                            <i class="fas fa-key"></i> Utiliser un code de récupération
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="text-muted small">
                            <i class="fas fa-arrow-left"></i> Retour à la connexion
                        </a>
                    </div>
                </div>
            </div>
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
                    <h5 class="modal-title"><i class="fas fa-key"></i> Code de Récupération</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <small>
                            <i class="fas fa-info-circle"></i> Entrez l'un de vos codes de récupération à 8 caractères que vous avez sauvegardés lors de l'activation de la 2FA.
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
                            <i class="fas fa-exclamation-triangle"></i> Ce code ne pourra plus être utilisé après cette connexion
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-sign-in-alt"></i> Vérifier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-format pour le code 2FA
document.querySelector('input[name="one_time_password"]').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});

// Auto-format pour le code de récupération
document.getElementById('recovery_code').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase();
});
</script>
@endsection

