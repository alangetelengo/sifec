@extends('layout.app')

@section('titre')
    Modifier le mot de passe
@endsection

@section('styles')
<style>
    .page-pw-sifec {
        --pw-ink: #1a2e26;
        --pw-ink-muted: #5c6d66;
        --pw-green: #0f5132;
        --pw-green-soft: #e8f0eb;
        --pw-green-mid: #1b6f4a;
        --pw-cream: #fafaf8;
        --pw-paper: #ffffff;
        --pw-line: #e2e8e4;
        --pw-gold: #9a7b2c;
        --pw-danger: #9b2c2c;
        --pw-danger-soft: #fce8e8;
        --pw-shadow: 0 1px 3px rgba(26, 46, 38, 0.06);
        --pw-shadow-lg: 0 12px 40px rgba(26, 46, 38, 0.08);
        --pw-radius: 14px;
        --pw-radius-sm: 10px;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        margin: -0.35rem -0.5rem 0;
        padding: 0.5rem 0.5rem 2.5rem;
        min-height: calc(100vh - 100px);
        background: linear-gradient(180deg, var(--pw-cream) 0%, #eef1ee 100%);
    }

    .page-pw-sifec .pw-breadcrumb {
        font-size: 0.875rem;
        margin-bottom: 1rem;
        background: var(--pw-paper);
        border: 1px solid var(--pw-line);
        border-radius: var(--pw-radius-sm);
        padding: 0.65rem 1.15rem;
        box-shadow: var(--pw-shadow);
    }
    .page-pw-sifec .pw-breadcrumb .breadcrumb { margin-bottom: 0; }
    .page-pw-sifec .pw-breadcrumb .breadcrumb-item { color: #475569 !important; }
    .page-pw-sifec .pw-breadcrumb .breadcrumb-item a {
        color: var(--pw-green-mid) !important;
        font-weight: 600;
        text-decoration: none;
    }
    .page-pw-sifec .pw-breadcrumb .breadcrumb-item a:hover {
        color: var(--pw-green) !important;
        text-decoration: underline;
    }
    .page-pw-sifec .pw-breadcrumb .breadcrumb-item.active {
        color: var(--pw-ink) !important;
        font-weight: 700;
    }
    .page-pw-sifec .pw-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
        color: #94a3b8 !important;
    }

    .page-pw-sifec .pw-shell {
        position: relative;
        background: linear-gradient(180deg, var(--pw-paper) 0%, #fbfcfb 100%);
        border-radius: var(--pw-radius);
        padding: 1.75rem 1.75rem 2rem;
        box-shadow: var(--pw-shadow-lg);
        border: 1px solid var(--pw-line);
        overflow: hidden;
        max-width: 720px;
        margin: 0 auto;
    }
    .page-pw-sifec .pw-shell::before {
        content: '';
        position: absolute;
        inset: -18% -8% auto auto;
        width: 320px;
        height: 320px;
        background: radial-gradient(circle, rgba(15, 81, 50, 0.05) 0%, transparent 72%);
        pointer-events: none;
    }

    .page-pw-sifec .pw-header {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.25rem;
        padding-bottom: 1.2rem;
        border-bottom: 1px solid var(--pw-line);
        position: relative;
        z-index: 1;
    }
    .page-pw-sifec .pw-header h1 {
        font-size: 1.35rem;
        font-weight: 600;
        letter-spacing: -0.02em;
        color: var(--pw-ink);
        margin: 0 0 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }
    .page-pw-sifec .pw-header-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--pw-green-soft) 0%, #d8e8df 100%);
        color: var(--pw-green);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(15, 81, 50, 0.12);
    }
    .page-pw-sifec .pw-sub {
        font-size: 0.875rem;
        color: var(--pw-ink-muted);
        margin: 0;
        line-height: 1.5;
    }
    .page-pw-sifec .pw-sub::before {
        content: '';
        display: block;
        width: 36px;
        height: 3px;
        background: linear-gradient(90deg, var(--pw-gold), rgba(154, 123, 44, 0.2));
        border-radius: 2px;
        margin-bottom: 0.5rem;
    }

    .page-pw-sifec .btn-pw-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 1.15rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #fff !important;
        background: linear-gradient(135deg, var(--pw-green-mid) 0%, var(--pw-green) 100%);
        border: none;
        border-radius: var(--pw-radius-sm);
        box-shadow: 0 4px 14px rgba(15, 81, 50, 0.22);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        text-decoration: none !important;
        white-space: nowrap;
    }
    .page-pw-sifec .btn-pw-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(15, 81, 50, 0.28);
        color: #fff !important;
    }

    .page-pw-sifec .pw-user-strip {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem 1.15rem;
        background: linear-gradient(135deg, var(--pw-green-soft) 0%, #f0f6f2 100%);
        border: 1px solid rgba(15, 81, 50, 0.12);
        border-radius: var(--pw-radius-sm);
        margin-bottom: 1.35rem;
        position: relative;
        z-index: 1;
    }
    .page-pw-sifec .pw-user-strip-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: var(--pw-paper);
        color: var(--pw-green-mid);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
        box-shadow: var(--pw-shadow);
        border: 1px solid var(--pw-line);
    }
    .page-pw-sifec .pw-user-strip h2 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--pw-ink);
        margin: 0 0 0.35rem;
    }
    .page-pw-sifec .pw-user-strip p {
        font-size: 0.8125rem;
        color: var(--pw-ink-muted);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        flex-wrap: wrap;
    }
    .page-pw-sifec .pw-user-strip p + p { margin-top: 0.25rem; }

    .page-pw-sifec .pw-section {
        position: relative;
        z-index: 1;
        background: var(--pw-paper);
        border: 1px solid var(--pw-line);
        border-radius: var(--pw-radius-sm);
        padding: 1.2rem 1.25rem;
        margin-bottom: 1.15rem;
        box-shadow: var(--pw-shadow);
    }
    .page-pw-sifec .pw-section-title {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--pw-green-mid);
        margin-bottom: 1rem;
    }

    .page-pw-sifec .form-label {
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: var(--pw-ink-muted);
        margin-bottom: 0.4rem;
    }
    .page-pw-sifec .form-control {
        border-radius: 8px;
        border-color: var(--pw-line);
    }
    .page-pw-sifec .form-control:focus {
        border-color: var(--pw-green-mid);
        box-shadow: 0 0 0 3px rgba(27, 111, 74, 0.12);
    }
    .page-pw-sifec .input-group .btn-outline-secondary {
        border-color: var(--pw-line);
        color: var(--pw-ink-muted);
    }
    .page-pw-sifec .input-group .btn-outline-secondary:hover {
        background: var(--pw-green-soft);
        border-color: rgba(15, 81, 50, 0.25);
        color: var(--pw-green);
    }

    .page-pw-sifec .password-strength {
        height: 5px;
        border-radius: 3px;
        transition: all 0.3s ease;
        margin-top: 0.35rem;
    }
    .page-pw-sifec .strength-weak { background: linear-gradient(90deg, #dc2626, #f87171); }
    .page-pw-sifec .strength-medium { background: linear-gradient(90deg, #d97706, #fbbf24); }
    .page-pw-sifec .strength-strong { background: linear-gradient(90deg, var(--pw-green-mid), #22c55e); }

    .page-pw-sifec .password-requirements { font-size: 0.8125rem; }
    .page-pw-sifec .requirement {
        transition: color 0.25s ease;
        padding: 0.2rem 0;
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }
    .page-pw-sifec .requirement.met { color: var(--pw-green); }
    .page-pw-sifec .requirement.unmet { color: var(--pw-ink-muted); }
    .page-pw-sifec .requirement.met i { color: var(--pw-green-mid); }
    .page-pw-sifec .pw-req-box {
        background: linear-gradient(180deg, #f8faf9 0%, #f1f4f2 100%);
        border: 1px dashed rgba(15, 81, 50, 0.18);
        border-radius: var(--pw-radius-sm);
        padding: 1rem 1.1rem;
    }
    .page-pw-sifec .pw-req-box h3 {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--pw-ink);
        margin: 0 0 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }
    .page-pw-sifec .pw-req-box h3 i { color: var(--pw-green-mid); }

    .page-pw-sifec .pw-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        margin-top: 1.5rem;
        padding-top: 1.25rem;
        border-top: 1px solid var(--pw-line);
        position: relative;
        z-index: 1;
    }
    .page-pw-sifec .btn-pw-outline {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.55rem 1.1rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--pw-ink-muted) !important;
        background: var(--pw-paper);
        border: 1px solid var(--pw-line);
        border-radius: var(--pw-radius-sm);
        text-decoration: none !important;
    }
    .page-pw-sifec .btn-pw-outline:hover {
        background: #f1f5f4;
        border-color: #cbd5d1;
        color: var(--pw-ink) !important;
    }
    .page-pw-sifec .btn-pw-submit {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.35rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(135deg, var(--pw-green-mid), var(--pw-green));
        border: none;
        border-radius: var(--pw-radius-sm);
        box-shadow: 0 4px 14px rgba(15, 81, 50, 0.22);
    }
    .page-pw-sifec .btn-pw-submit:hover:not(:disabled) {
        color: #fff;
        filter: brightness(1.05);
    }
    .page-pw-sifec .btn-pw-submit:disabled {
        opacity: 0.55;
        cursor: not-allowed;
    }
    .page-pw-sifec .btn-pw-submit.sifec-btn-loading {
        opacity: 1;
        cursor: wait;
        pointer-events: none;
    }
</style>
@endsection

@section('corps')
<div class="container-fluid page-pw-sifec">
    <nav class="pw-breadcrumb" aria-label="Fil d'Ariane">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('utilisateur.index') }}">Utilisateurs</a></li>
            <li class="breadcrumb-item"><a href="{{ route('utilisateur.profile', $user->code_user) }}">Profil</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mot de passe</li>
        </ol>
    </nav>

    <div class="pw-shell">
        <header class="pw-header">
            <div>
                <h1>
                    <span class="pw-header-icon" aria-hidden="true"><i class="fas fa-key"></i></span>
                    Modifier le mot de passe
                </h1>
                <p class="pw-sub">
                    Saisissez le mot de passe actuel puis un nouveau mot de passe conforme aux exigences de sécurité.
                </p>
            </div>
            <a href="{{ route('utilisateur.profile', $user->code_user) }}" class="btn-pw-primary">
                <i class="fas fa-arrow-left"></i>
                Retour au profil
            </a>
        </header>

        <div class="pw-user-strip">
            <div class="pw-user-strip-icon" aria-hidden="true"><i class="fas fa-user-shield"></i></div>
            <div>
                <h2>{{ $user->personne->nom ?? '' }} {{ $user->personne->prenom ?? '' }}</h2>
                <p><i class="fas fa-envelope"></i> {{ $user->email }}</p>
                <p><i class="fas fa-building"></i> {{ $user->affectationActive()?->institution?->lib_institution ?? 'Non affecté' }}</p>
            </div>
        </div>

        <form action="{{ route('utilisateur.change-password.store', $user->code_user) }}" method="POST" id="changePasswordForm">
            @csrf

            <div class="pw-section">
                <div class="pw-section-title">Sécurisation du compte</div>

                <div class="mb-3">
                    <label for="current_password" class="form-label">Mot de passe actuel</label>
                    <div class="input-group">
                        <input type="password"
                               class="form-control @error('current_password') is-invalid @enderror"
                               id="current_password"
                               name="current_password"
                               required
                               autocomplete="current-password">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')" aria-label="Afficher ou masquer le mot de passe">
                            <i class="fas fa-eye" id="current_password_icon"></i>
                        </button>
                    </div>
                    @error('current_password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label">Nouveau mot de passe</label>
                    <div class="input-group">
                        <input type="password"
                               class="form-control @error('new_password') is-invalid @enderror"
                               id="new_password"
                               name="new_password"
                               required
                               minlength="8"
                               autocomplete="new-password"
                               onkeyup="checkPasswordStrength()">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password')" aria-label="Afficher ou masquer le mot de passe">
                            <i class="fas fa-eye" id="new_password_icon"></i>
                        </button>
                    </div>
                    <div class="mt-2">
                        <div class="password-strength" id="passwordStrength"></div>
                        <small class="text-muted" id="strengthText">Force du mot de passe</small>
                    </div>
                    @error('new_password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-0">
                    <label for="new_password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                    <div class="input-group">
                        <input type="password"
                               class="form-control @error('new_password_confirmation') is-invalid @enderror"
                               id="new_password_confirmation"
                               name="new_password_confirmation"
                               required
                               autocomplete="new-password"
                               onkeyup="checkPasswordMatch()">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password_confirmation')" aria-label="Afficher ou masquer le mot de passe">
                            <i class="fas fa-eye" id="new_password_confirmation_icon"></i>
                        </button>
                    </div>
                    <div class="mt-2">
                        <small id="matchIndicator" class="text-muted">
                            <i class="fas fa-info-circle"></i> Les mots de passe doivent correspondre
                        </small>
                    </div>
                    @error('new_password_confirmation')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="pw-req-box">
                <h3><i class="fas fa-shield-alt"></i> Exigences du mot de passe</h3>
                <div class="password-requirements">
                    <div class="requirement unmet" id="req-length">
                        <i class="fas fa-circle" style="font-size:0.45rem;"></i> Au moins 8 caractères
                    </div>
                    <div class="requirement unmet" id="req-uppercase">
                        <i class="fas fa-circle" style="font-size:0.45rem;"></i> Au moins une majuscule
                    </div>
                    <div class="requirement unmet" id="req-lowercase">
                        <i class="fas fa-circle" style="font-size:0.45rem;"></i> Au moins une minuscule
                    </div>
                    <div class="requirement unmet" id="req-number">
                        <i class="fas fa-circle" style="font-size:0.45rem;"></i> Au moins un chiffre
                    </div>
                    <div class="requirement unmet" id="req-special">
                        <i class="fas fa-circle" style="font-size:0.45rem;"></i> Au moins un caractère spécial
                    </div>
                </div>
            </div>

            <div class="pw-actions">
                <a href="{{ route('utilisateur.profile', $user->code_user) }}" class="btn-pw-outline">
                    <i class="fas fa-times"></i> Annuler
                </a>
                <button type="submit" class="btn-pw-submit" id="submitBtn" disabled>
                    <i class="fas fa-save"></i> Enregistrer le nouveau mot de passe
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
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

function checkPasswordStrength() {
    const password = document.getElementById('new_password').value;
    const strengthBar = document.getElementById('passwordStrength');
    const strengthText = document.getElementById('strengthText');

    let score = 0;

    if (password.length >= 8) score++;
    if (password.length >= 12) score++;
    if (/[a-z]/.test(password)) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;

    if (password.length === 0) {
        strengthBar.className = 'password-strength';
        strengthText.textContent = 'Force du mot de passe';
    } else if (score < 3) {
        strengthBar.className = 'password-strength strength-weak';
        strengthText.textContent = 'Force du mot de passe : faible';
    } else if (score < 5) {
        strengthBar.className = 'password-strength strength-medium';
        strengthText.textContent = 'Force du mot de passe : moyen';
    } else {
        strengthBar.className = 'password-strength strength-strong';
        strengthText.textContent = 'Force du mot de passe : fort';
    }

    checkRequirements();
    checkPasswordMatch();
}

function checkRequirements() {
    const password = document.getElementById('new_password').value;

    const requirements = {
        'req-length': password.length >= 8,
        'req-uppercase': /[A-Z]/.test(password),
        'req-lowercase': /[a-z]/.test(password),
        'req-number': /[0-9]/.test(password),
        'req-special': /[^A-Za-z0-9]/.test(password)
    };

    Object.keys(requirements).forEach(function (reqId) {
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

    const allRequirementsMet = document.querySelectorAll('.requirement.met').length === 5;
    const passwordsMatch = password === confirmation && password.length > 0;

    submitBtn.disabled = !(allRequirementsMet && passwordsMatch);
}

document.getElementById('changePasswordForm').addEventListener('submit', function (e) {
    const currentPassword = document.getElementById('current_password').value;
    const newPassword = document.getElementById('new_password').value;
    const confirmation = document.getElementById('new_password_confirmation').value;

    if (!currentPassword || !newPassword || !confirmation) {
        e.preventDefault();
        if (typeof flashAlert === 'function') {
            flashAlert('Champs requis', 'error', 'Tous les champs sont obligatoires.');
        }
        return false;
    }

    if (newPassword !== confirmation) {
        e.preventDefault();
        if (typeof flashAlert === 'function') {
            flashAlert('Erreur', 'error', 'Les mots de passe ne correspondent pas.');
        }
        return false;
    }

    if (newPassword.length < 8) {
        e.preventDefault();
        if (typeof flashAlert === 'function') {
            flashAlert('Mot de passe trop court', 'error', 'Le mot de passe doit contenir au moins 8 caractères.');
        }
        return false;
    }

    var btn = document.getElementById('submitBtn');
    if (btn && btn.getAttribute('data-sifec-submitting') !== '1') {
        btn.setAttribute('data-sifec-submitting', '1');
        if (!btn.getAttribute('data-sifec-html')) {
            btn.setAttribute('data-sifec-html', btn.innerHTML);
        }
        btn.disabled = true;
        btn.setAttribute('aria-busy', 'true');
        btn.classList.add('sifec-btn-loading');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2" aria-hidden="true"></i>Enregistrement en cours…';
    }
});
</script>
@endsection
