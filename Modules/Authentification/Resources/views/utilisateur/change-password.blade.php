@extends('layout.app')
@section('titre')
    Modifier le Mot de Passe
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
    .password-strength {
        height: 4px;
        border-radius: 2px;
        transition: all 0.3s ease;
    }
    .strength-weak { background-color: #dc3545; }
    .strength-medium { background-color: #ffc107; }
    .strength-strong { background-color: #28a745; }
    .password-requirements {
        font-size: 0.875rem;
    }
    .requirement {
        transition: color 0.3s ease;
    }
    .requirement.met {
        color: #28a745;
    }
    .requirement.unmet {
        color: #6c757d;
    }
</style>
@endsection

@section('corps')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"><i class="fas fa-home"></i> Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('utilisateur.index') }}"><i class="fas fa-users"></i> Utilisateurs</a></li>
            <li class="breadcrumb-item"><a href="{{ route('utilisateur.profile', $user->code_user) }}"><i class="fas fa-user"></i> {{ $user->personne->nom ?? '' }}</a></li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-key"></i> Modifier le Mot de Passe</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h4>Modifier le Mot de Passe</h4>
                    <a href="{{ route('utilisateur.profile', $user->code_user) }}">
                        <button type="button" class="btn btn-info m-t-2 float-end text-white">
                            <i class="fa fa-arrow-left"></i> Retour au Profil
                        </button>
                    </a>
                </div>
                <div class="card-body">

                    <!-- Informations de l'utilisateur -->
                    <div class="alert alert-info border-0 shadow-sm mb-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user-shield fa-3x text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-1">
                                    <i class="fas fa-user"></i>
                                    {{ $user->personne->nom ?? '' }} {{ $user->personne->prenom ?? '' }}
                                </h5>
                                <p class="mb-1">
                                    <i class="fas fa-envelope"></i> {{ $user->email }}
                                </p>
                                <p class="mb-0">
                                    <i class="fas fa-building"></i> {{ $user->affectationActive()?->institution?->lib_institution ?? "Non affecté" }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire de modification -->
                    <form action="{{ route('utilisateur.change-password.store', $user->code_user) }}" method="POST" id="changePasswordForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-12">
                                <!-- Mot de passe actuel -->
                                <div class="form-group mb-4">
                                    <label for="current_password" class="form-label fw-bold">
                                        <i class="fas fa-lock text-primary"></i> Mot de passe actuel
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                               class="form-control @error('current_password') is-invalid @enderror"
                                               id="current_password"
                                               name="current_password"
                                               required
                                               autocomplete="current-password">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                            <i class="fas fa-eye" id="current_password_icon"></i>
                                        </button>
                                    </div>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Nouveau mot de passe -->
                                <div class="form-group mb-3">
                                    <label for="new_password" class="form-label fw-bold">
                                        <i class="fas fa-key text-success"></i> Nouveau mot de passe
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                               class="form-control @error('new_password') is-invalid @enderror"
                                               id="new_password"
                                               name="new_password"
                                               required
                                               minlength="8"
                                               autocomplete="new-password"
                                               onkeyup="checkPasswordStrength()">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password')">
                                            <i class="fas fa-eye" id="new_password_icon"></i>
                                        </button>
                                    </div>

                                    <!-- Indicateur de force du mot de passe -->
                                    <div class="mt-2">
                                        <div class="password-strength" id="passwordStrength"></div>
                                        <small class="text-muted" id="strengthText">Force du mot de passe</small>
                                    </div>

                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Confirmation du nouveau mot de passe -->
                                <div class="form-group mb-4">
                                    <label for="new_password_confirmation" class="form-label fw-bold">
                                        <i class="fas fa-check-circle text-info"></i> Confirmer le nouveau mot de passe
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                               class="form-control @error('new_password_confirmation') is-invalid @enderror"
                                               id="new_password_confirmation"
                                               name="new_password_confirmation"
                                               required
                                               autocomplete="new-password"
                                               onkeyup="checkPasswordMatch()">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password_confirmation')">
                                            <i class="fas fa-eye" id="new_password_confirmation_icon"></i>
                                        </button>
                                    </div>

                                    <!-- Indicateur de correspondance -->
                                    <div class="mt-2">
                                        <small id="matchIndicator" class="text-muted">
                                            <i class="fas fa-info-circle"></i> Les mots de passe doivent correspondre
                                        </small>
                                    </div>

                                    @error('new_password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Exigences du mot de passe -->
                                <div class="card border-0 bg-light mb-4">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">
                                            <i class="fas fa-shield-alt"></i> Exigences du mot de passe
                                        </h6>
                                        <div class="password-requirements">
                                            <div class="requirement" id="req-length">
                                                <i class="fas fa-circle"></i> Au moins 8 caractères
                                            </div>
                                            <div class="requirement" id="req-uppercase">
                                                <i class="fas fa-circle"></i> Au moins une majuscule
                                            </div>
                                            <div class="requirement" id="req-lowercase">
                                                <i class="fas fa-circle"></i> Au moins une minuscule
                                            </div>
                                            <div class="requirement" id="req-number">
                                                <i class="fas fa-circle"></i> Au moins un chiffre
                                            </div>
                                            <div class="requirement" id="req-special">
                                                <i class="fas fa-circle"></i> Au moins un caractère spécial
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Boutons -->
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('utilisateur.profile', $user->code_user) }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Annuler
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                        <i class="fas fa-save"></i> Modifier le Mot de Passe
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Toggle visibilité du mot de passe
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '_icon');

    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Vérifier la force du mot de passe
function checkPasswordStrength() {
    const password = document.getElementById('new_password').value;
    const strengthBar = document.getElementById('passwordStrength');
    const strengthText = document.getElementById('strengthText');

    let score = 0;
    let feedback = '';

    // Longueur
    if (password.length >= 8) score++;
    if (password.length >= 12) score++;

    // Complexité
    if (/[a-z]/.test(password)) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;

    // Mise à jour de l'indicateur
    if (score < 3) {
        strengthBar.className = 'password-strength strength-weak';
        feedback = 'Faible';
    } else if (score < 5) {
        strengthBar.className = 'password-strength strength-medium';
        feedback = 'Moyen';
    } else {
        strengthBar.className = 'password-strength strength-strong';
        feedback = 'Fort';
    }

    strengthText.textContent = 'Force du mot de passe : ' + feedback;

    // Vérifier les exigences
    checkRequirements();
    checkPasswordMatch();
}

// Vérifier les exigences du mot de passe
function checkRequirements() {
    const password = document.getElementById('new_password').value;

    const requirements = {
        'req-length': password.length >= 8,
        'req-uppercase': /[A-Z]/.test(password),
        'req-lowercase': /[a-z]/.test(password),
        'req-number': /[0-9]/.test(password),
        'req-special': /[^A-Za-z0-9]/.test(password)
    };

    Object.keys(requirements).forEach(reqId => {
        const element = document.getElementById(reqId);
        if (requirements[reqId]) {
            element.classList.remove('unmet');
            element.classList.add('met');
        } else {
            element.classList.remove('met');
            element.classList.add('unmet');
        }
    });
}

// Vérifier la correspondance des mots de passe
function checkPasswordMatch() {
    const password = document.getElementById('new_password').value;
    const confirmation = document.getElementById('new_password_confirmation').value;
    const indicator = document.getElementById('matchIndicator');
    const submitBtn = document.getElementById('submitBtn');

    if (confirmation.length === 0) {
        indicator.innerHTML = '<i class="fas fa-info-circle"></i> Les mots de passe doivent correspondre';
        indicator.className = 'text-muted';
        submitBtn.disabled = true;
        return;
    }

    if (password === confirmation) {
        indicator.innerHTML = '<i class="fas fa-check-circle text-success"></i> Les mots de passe correspondent';
        indicator.className = 'text-success';
    } else {
        indicator.innerHTML = '<i class="fas fa-times-circle text-danger"></i> Les mots de passe ne correspondent pas';
        indicator.className = 'text-danger';
    }

    // Activer/désactiver le bouton de soumission
    const allRequirementsMet = document.querySelectorAll('.requirement.met').length === 5;
    const passwordsMatch = password === confirmation && password.length > 0;

    submitBtn.disabled = !(allRequirementsMet && passwordsMatch);
}

// Validation du formulaire
document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
    const currentPassword = document.getElementById('current_password').value;
    const newPassword = document.getElementById('new_password').value;
    const confirmation = document.getElementById('new_password_confirmation').value;

    if (!currentPassword || !newPassword || !confirmation) {
        e.preventDefault();
        flashAlert('Erreur', 'error', 'Tous les champs sont requis');
        return false;
    }

    if (newPassword !== confirmation) {
        e.preventDefault();
        flashAlert('Erreur', 'error', 'Les mots de passe ne correspondent pas');
        return false;
    }

    if (newPassword.length < 8) {
        e.preventDefault();
        flashAlert('Erreur', 'error', 'Le mot de passe doit contenir au moins 8 caractères');
        return false;
    }
});
</script>
@endsection
