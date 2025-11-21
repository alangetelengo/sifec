@extends('layout.app')
@section('titre')
    Codes de Récupération
@endsection

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
    <!-- row -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Codes de Récupération</h4>
                    <a href="{{ route('two-factor.index') }}">
                        <button type="button" class="btn btn-info m-t-2 float-end text-white">
                            Retour <i class="fa fa-arrow-left"></i>
                        </button>
                    </a>
                </div>
                <div class="card-body">
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"><i class="fas fa-home"></i> Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('two-factor.index') }}"><i class="fas fa-shield-alt"></i> Double Authentification</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-key"></i> Codes de Récupération</li>
                        </ol>
                    </nav>

                    <!-- Avertissement -->
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-exclamation-triangle"></i> <strong>IMPORTANT !</strong></h5>
                        <ul class="mb-0">
                            <li>Sauvegardez ces codes dans un endroit sûr</li>
                            <li>Chaque code ne peut être utilisé qu'une seule fois</li>
                            <li>Utilisez-les si vous perdez l'accès à votre téléphone</li>
                            <li><strong>Ne partagez jamais ces codes !</strong></li>
                        </ul>
                    </div>
                    </div>

                    <!-- Codes de récupération -->
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-key"></i> Vos Codes de Récupération</h5>
                        </div>
                        <div class="card-body">
                        <div class="row" id="recoveryCodes-container">
                                @foreach($recoveryCodes as $index => $code)
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-body d-flex justify-content-between align-items-center">
                                                <span class="badge bg-primary me-3">{{ $index + 1 }}</span>
                                                <code style="font-size: 1.2em; font-weight: bold;">{{ $code }}</code>
                                                <button class="btn btn-sm btn-info" onclick="copyCode('{{ $code }}'); return false;" title="Copier ce code">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!-- Actions -->
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <a href="{{ route('two-factor.recovery-codes.print') }}" target="_blank" class="btn btn-primary w-100">
                                <i class="fas fa-print"></i> Imprimer (PDF)
                            </a>
                        </div>
                        <div class="col-md-4">
                            <button onclick="copyCodes()" class="btn btn-secondary w-100">
                                <i class="fas fa-copy"></i> Copier Tout
                            </button>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('two-factor.recovery-codes.download') }}" class="btn btn-info w-100">
                                <i class="fas fa-download"></i> Télécharger (PDF)
                            </a>
                        </div>
                    </div>
                    <!-- Régénération -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-danger mb-1"><i class="fas fa-sync"></i> Régénérer les codes</h6>
                                    <small class="text-muted">Les codes actuels seront invalidés</small>
                                </div>
                                <form method="POST" action="{{ route('two-factor.recovery-codes.regenerate') }}" id="regenerateForm">
                                    @csrf
                                    <button type="button" class="btn btn-danger" onclick="confirmRegenerate()">
                                        <i class="fas fa-sync"></i> Régénérer
                                    </button>
                                </form>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
// Récupérer les codes de récupération depuis le serveur
const recoveryCodes = @json($recoveryCodes);

// Copier un code individuel
function copyCode(code) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(code).then(function() {
            flashAlert('Succès', 'success', 'Code <strong>' + code + '</strong> copié avec succès !');
        }).catch(function(err) {
            console.error('Erreur lors de la copie:', err);
            fallbackCopy(code);
        });
    } else {
        fallbackCopy(code);
    }
}

// Fallback pour la copie (navigateurs plus anciens)
function fallbackCopy(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-9999px';
    document.body.appendChild(textArea);
    textArea.select();
    try {
        document.execCommand('copy');
        flashAlert('Succès', 'success', 'Code <strong>' + text + '</strong> copié avec succès !');
    } catch (err) {
        console.error('Erreur lors de la copie:', err);
        flashAlert('Erreur', 'error', 'Impossible de copier le code.');
    }
    document.body.removeChild(textArea);
}

// Copier tous les codes
function copyCodes() {
    const text = recoveryCodes.join('\n');
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(function() {
            flashAlert('Succès', 'success', 'Les <strong>' + recoveryCodes.length + ' codes</strong> ont été copiés !');
        }).catch(function(err) {
            console.error('Erreur lors de la copie:', err);
            fallbackCopy(text);
        });
    } else {
        fallbackCopy(text);
    }
}

// Note: Les fonctions printCodes() et downloadCodes() ont été remplacées par des routes PDF
// L'impression et le téléchargement utilisent maintenant dompdf via les routes :
// - route('two-factor.recovery-codes.print') pour l'impression
// - route('two-factor.recovery-codes.download') pour le téléchargement

// Confirmer la régénération des codes
function confirmRegenerate() {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Les anciens codes seront invalidés et vous devrez utiliser les nouveaux codes.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, régénérer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            // Soumettre le formulaire de régénération
            document.getElementById('regenerateForm').submit();
        }
    });
}

// Fonction flashAlert (SweetAlert2)
function flashAlert(title, type, message) {
    Swal.fire({
        title: title,
        icon: type,
        html: message,
        showCloseButton: true,
        confirmButtonText: 'OK',
        customClass: {
            confirmButton: 'btn btn-primary'
        },
        buttonsStyling: false
    });
}

// Initialisation au chargement de la page
$(document).ready(function() {
    console.log('Codes de récupération chargés :', recoveryCodes.length);
});
</script>
@endsection

