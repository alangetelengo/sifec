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

                                            @if(session('code_invalid') || $errors->has('one_time_password'))
                                                <div class="alert alert-danger">
                                                    <strong><i class="fas fa-times-circle"></i> Code invalide</strong>
                                                    <p class="mb-2 mt-2">Le code saisi ne correspond pas. Vérifiez que :</p>
                                                    <ul class="mb-2">
                                                        <li>Vous avez scanné le <strong>QR code affiché ci-contre</strong> (pas un ancien)</li>
                                                        <li>L'<strong>heure de votre téléphone</strong> est synchronisée</li>
                                                        <li>Le code n'a pas <strong>expiré</strong> (il change toutes les 30 secondes)</li>
                                                    </ul>
                                                    <div class="d-grid">
                                                        <a href="{{ route('two-factor.enable', ['reset' => 1]) }}" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-sync-alt"></i> Générer un nouveau QR code
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif

                                            <a href="{{ route('two-factor.index') }}" class="btn btn-secondary">
                                                <i class="fas fa-arrow-left"></i> Retour
                                            </a>
                                        </div>
                                    </form>

                                        <div class="alert alert-info mt-4">
                                            <strong><i class="fas fa-info-circle"></i> Instructions :</strong>
                                            <ul class="mb-2 mt-2">
                                                <li>Scannez le QR code ci-contre avec votre application d'authentification</li>
                                                <li>Saisissez le code à 6 chiffres affiché par l'application</li>
                                                <li>Le code change toutes les 30 secondes - utilisez le code actuel</li>
                                                <li>Après activation, vous recevrez 8 codes de récupération à conserver précieusement</li>
                                            </ul>
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

// Fonction pour logger côté client (console uniquement)
function logClient(message, data) {
    console.log('[CLIENT LOG]', message, data || '');
}

// SUPPRIMÉ : Fonction refreshCsrfToken() - Plus nécessaire
// Le token CSRF reste valide pendant toute la durée de la session (120 minutes)
// Pas besoin de le rafraîchir avant la soumission
// Tous les événements sont attachés dans DOMContentLoaded ci-dessous

// Protection contre la déconnexion automatique
var isSubmitting = false;
var preventNavigation = false;

// Détecter les tentatives de navigation/redirection
window.addEventListener('beforeunload', function(e) {
    if (isSubmitting || preventNavigation) {
        logClient('Tentative de navigation détectée pendant la soumission', {
            'is_submitting': isSubmitting,
            'prevent_navigation': preventNavigation
        });
        // Ne pas empêcher la navigation normale, juste logger
    }
});

// Détecter les changements de visibilité de la page
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        logClient('Page cachée', {
            'is_submitting': isSubmitting
        });
    } else {
        logClient('Page visible', {
            'is_submitting': isSubmitting
        });
    }
});

// Initialiser tout au chargement de la page (JavaScript vanilla)
document.addEventListener('DOMContentLoaded', function() {
    // Log initial
    var initialToken = document.querySelector('input[name="_token"]')?.value;
    var metaToken = document.querySelector('meta[name="csrf-token"]')?.content;

    logClient('Page chargée - État initial', {
        'form_token': initialToken ? initialToken.substring(0, 20) + '...' : 'null',
        'meta_token': metaToken ? metaToken.substring(0, 20) + '...' : 'null',
        'tokens_match': initialToken === metaToken,
        'url': window.location.href
    });

    // Vérifier que les éléments existent avant d'attacher les événements
    var passwordInput = document.getElementById('one_time_password');
    var form = document.querySelector('form');

    if (!passwordInput) {
        console.error('[ERREUR] Le champ one_time_password n\'existe pas dans le DOM');
        return;
    }

    if (!form) {
        console.error('[ERREUR] Le formulaire n\'existe pas dans le DOM');
        return;
    }

    // Auto-focus sur le champ de saisie et formater l'entrée
    passwordInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');

        // DÉSACTIVÉ TEMPORAIREMENT : Auto-submit causait des problèmes de déconnexion
        // L'utilisateur doit maintenant cliquer manuellement sur le bouton "Confirmer et Activer"
        // TODO: Réactiver une fois le problème de déconnexion résolu

        /*
        // Auto-submit si 6 chiffres
        if (this.value.length === 6) {
            var codeValue = this.value;
            var currentToken = document.querySelector('input[name="_token"]')?.value;

            logClient('6 chiffres saisis, soumission du formulaire dans 500ms', {
                'code': codeValue,
                'token': currentToken ? currentToken.substring(0, 20) + '...' : 'null'
            });

            // Marquer comme en cours de soumission
            isSubmitting = true;
            preventNavigation = true;

            // Soumettre directement sans rafraîchir le token
            // Le token CSRF reste valide pendant toute la session (120 minutes)
            var formElement = document.querySelector('form');
            setTimeout(function() {
                if (!formElement) {
                    logClient('ERREUR: Formulaire non trouvé', {});
                    isSubmitting = false;
                    preventNavigation = false;
                    return;
                }

                logClient('SOUMISSION DU FORMULAIRE (auto-submit)', {
                    'code': codeValue,
                    'token': currentToken ? currentToken.substring(0, 20) + '...' : 'null',
                    'form_action': formElement.action,
                    'form_method': formElement.method
                });

                // Marquer comme auto-submit pour éviter le double traitement
                formElement.setAttribute('data-auto-submit', 'true');

                // Soumettre le formulaire
                try {
                    formElement.submit();
                } catch (error) {
                    logClient('ERREUR lors de la soumission', {
                        'error': error.toString()
                    });
                    isSubmitting = false;
                    preventNavigation = false;
                }
            }, 500);
        }
        */

        // Afficher un message visuel quand 6 chiffres sont saisis
        if (this.value.length === 6) {
            logClient('6 chiffres saisis - Cliquez sur "Confirmer et Activer"', {
                'code': this.value
            });

            // Mettre en surbrillance le bouton pour encourager le clic
            var submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.style.boxShadow = '0 0 20px rgba(40, 167, 69, 0.8)';
                submitButton.style.transform = 'scale(1.05)';
                setTimeout(function() {
                    submitButton.style.boxShadow = '';
                    submitButton.style.transform = '';
                }, 1000);
            }
        }
    });

    // Gérer la soumission manuelle du formulaire (bouton)
    var formSubmitted = false;
    form.addEventListener('submit', function(e) {
        var submitter = e.originalEvent?.submitter || e.submitter;
        var isAutoSubmit = form.hasAttribute('data-auto-submit');
        var codeValue = passwordInput?.value;
        var currentToken = document.querySelector('input[name="_token"]')?.value;

        logClient('Événement submit déclenché', {
            'is_auto_submit': isAutoSubmit,
            'submitter': submitter ? submitter.type : 'unknown',
            'code': codeValue || 'empty',
            'token': currentToken ? currentToken.substring(0, 20) + '...' : 'null',
            'form_action': form.action,
            'form_method': form.method,
            'is_submitting': isSubmitting
        });

        // Si le formulaire est déjà en cours de soumission, empêcher la double soumission
        if (formSubmitted || isSubmitting) {
            logClient('Formulaire déjà soumis, annulation de la double soumission', {
                'form_submitted': formSubmitted,
                'is_submitting': isSubmitting
            });
            e.preventDefault();
            return;
        }

        // Marquer comme soumis pour éviter les doubles soumissions
        formSubmitted = true;
        isSubmitting = true;
        preventNavigation = true;

        logClient('Formulaire en cours de soumission', {
            'form_action': form.action
        });

        // Le token CSRF reste valide, pas besoin de le rafraîchir
        // Laisser la soumission normale se faire
    });
});
</script>
@endsection

