@extends('layout.app')

@section('titre', 'Activer la Double Authentification')

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
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"><i class="fas fa-home"></i> Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('two-factor.index') }}"><i class="fas fa-shield-alt"></i> Double Authentification</a></li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-qrcode"></i> Configuration</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Configurer la Double Authentification</h4>
                    <a href="{{ route('two-factor.index') }}">
                        <button type="button" class="btn btn-sm btn-primary float-end">
                            <i class="fa fa-arrow-left"></i> Retour
                        </button>
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- QR Code Section -->
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title"><i class="fas fa-mobile-alt"></i> Étape 1: Scanner le QR Code</h5>
                                </div>
                                <div class="card-body text-center">
                                    <p class="text-muted mb-4">Utilisez une application d'authentification sur votre téléphone</p>

                                    <!-- QR Code -->
                                    <div class="qr-code-container mb-4">
                                        <div class="p-4 bg-light rounded-3 shadow-sm d-inline-block">
                                            <img src="data:image/svg+xml;base64,{{ $qrCodeImage }}" alt="QR Code" class="img-fluid" style="max-width: 280px;">
                                        </div>
                                    </div>

                                    <!-- Secret Key -->
                                    <div class="alert alert-info border-0 shadow-sm">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="flex-grow-1">
                                                <small class="fw-bold"><i class="fas fa-info-circle"></i> Clé secrète :</small><br>
                                                <code class="user-select-all text-break">{{ $secret }}</code>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-secondary ms-2" onclick="copySecret()">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Apps recommandées -->
                                    <div class="mt-4">
                                        <h6 class="text-primary mb-3"><i class="fas fa-download"></i> Applications recommandées :</h6>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                                    <i class="fab fa-google text-danger fa-lg me-2"></i>
                                                    <small class="fw-bold">Google Authenticator</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                                    <i class="fab fa-microsoft text-info fa-lg me-2"></i>
                                                    <small class="fw-bold">Microsoft Authenticator</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                                    <i class="fas fa-shield-alt text-success fa-lg me-2"></i>
                                                    <small class="fw-bold">Authy</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                                    <i class="fas fa-key text-warning fa-lg me-2"></i>
                                                    <small class="fw-bold">1Password</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Verification Section -->
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title"><i class="fas fa-check-circle"></i> Étape 2: Vérifier le Code</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted mb-4">Entrez le code à 6 chiffres généré par l'application</p>

                                    <form method="POST" action="{{ route('two-factor.confirm') }}">
                                        @csrf
                                        <div class="form-group mb-4">
                                            <label for="one_time_password" class="form-label fw-bold">Code de vérification</label>
                                            <input type="text"
                                                   name="one_time_password"
                                                   id="one_time_password"
                                                   class="form-control form-control-lg text-center border-2"
                                                   placeholder="000000"
                                                   maxlength="6"
                                                   pattern="[0-9]{6}"
                                                   style="font-size: 2.5em; letter-spacing: 0.5em; font-weight: bold;"
                                                   autofocus
                                                   required>
                                            <small class="form-text text-muted mt-2">
                                                <i class="fas fa-clock"></i> Le code change toutes les 30 secondes
                                            </small>
                                        </div>

                                        <div class="d-grid gap-3">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="fas fa-check"></i> Confirmer et Activer
                                            </button>
                                            <a href="{{ route('two-factor.index') }}" class="btn btn-secondary">
                                                <i class="fas fa-arrow-left"></i> Retour
                                            </a>
                                        </div>
                                    </form>

                                        <div class="alert alert-warning mt-4">
                                            <strong><i class="fas fa-exclamation-triangle"></i> Important :</strong>
                                            <small class="d-block">Après activation, vous recevrez 8 codes de récupération à conserver précieusement.</small>
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

<script>
function copySecret() {
    const secret = '{{ $secret }}';
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(secret).then(function() {
            flashAlert('Succès', 'success', 'Clé secrète copiée dans le presse-papier !');
        }).catch(function() {
            fallbackCopySecret(secret);
        });
    } else {
        fallbackCopySecret(secret);
    }
}

function fallbackCopySecret(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-9999px';
    document.body.appendChild(textArea);
    textArea.select();
    try {
        document.execCommand('copy');
        flashAlert('Succès', 'success', 'Clé secrète copiée dans le presse-papier !');
    } catch (err) {
        console.error('Erreur lors de la copie:', err);
        flashAlert('Erreur', 'error', 'Impossible de copier la clé secrète');
    }
    document.body.removeChild(textArea);
}

// Auto-focus sur le champ de saisie et formater l'entrée
document.getElementById('one_time_password').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');

    // Auto-submit si 6 chiffres
    if (this.value.length === 6) {
        setTimeout(function() {
            document.querySelector('form').submit();
        }, 500);
    }
});
</script>
@endsection

