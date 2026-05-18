@php
    $qSuccess = request()->query('sifec_inline');
    $qError = request()->query('sifec_err_inline');
    $qNotice = request()->query('sifec_notice');
    $noticeMsgs = config('sifec_notices.messages', []);
    $tfFlash = \App\Support\SifecTransientFlashCookie::read(request());
@endphp

<style>
    /* Thème : .fade peut laisser opacity:0 si Bootstrap n’initialise pas l’alerte — forcer l’affichage des flash SIFEC */
    .sifec-session-flash .alert.fade { opacity: 1 !important; }
    /* Lisibilité renforcée des messages flash (certains thèmes rendent le texte trop pâle). */
    .sifec-session-flash .alert {
        font-size: 0.95rem;
        font-weight: 500;
    }
    .sifec-session-flash .alert-success {
        color: #0f5132 !important;
        background-color: #d1e7dd !important;
        border: 1px solid #a3cfbb !important;
    }
    .sifec-session-flash .alert-danger {
        color: #842029 !important;
        background-color: #f8d7da !important;
        border: 1px solid #f1aeb5 !important;
    }
    .sifec-session-flash .alert-warning {
        color: #664d03 !important;
        background-color: #fff3cd !important;
        border: 1px solid #ffe69c !important;
    }
    .sifec-session-flash .alert-info {
        color: #055160 !important;
        background-color: #cff4fc !important;
        border: 1px solid #9eeaf9 !important;
    }
    .sifec-session-flash .alert .btn-close {
        opacity: 0.7;
    }
</style>

{{-- Messages via query string (middleware AppendSifecFlashQueryToRedirects) : visibles sans session --}}
@if (is_string($qSuccess) && $qSuccess !== '')
    <div class="sifec-session-flash mb-2 position-relative" role="region" aria-label="Message de succès">
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
            <span class="me-2">{{ e($qSuccess) }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    </div>
@elseif (is_string($qNotice) && $qNotice !== '' && isset($noticeMsgs[$qNotice]))
    <div class="sifec-session-flash mb-2 position-relative" role="region" aria-label="Message de succès">
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
            <span class="me-2">{{ e($noticeMsgs[$qNotice]) }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    </div>
@elseif (is_string($qError) && $qError !== '')
    <div class="sifec-session-flash mb-2 position-relative" role="region" aria-label="Message d’erreur">
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
            <span class="me-2">{{ e($qError) }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    </div>
@elseif (session('success') || session('error') || session('warning') || session('info'))
    {{-- Session Laravel avant le cookie sifec_tf : évite qu’un ancien cookie masque un flash frais (ex. profil / signature). --}}
    <div class="sifec-session-flash mb-2 position-relative" role="region" aria-label="Messages">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
                <span class="me-2">{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
                <span class="me-2">{{ session('error') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
                <span class="me-2">{{ session('warning') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif
        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
                <span class="me-2">{{ session('info') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif
    </div>
@elseif ($tfFlash !== null && $tfFlash['type'] === 'success')
    <div class="sifec-session-flash mb-2 position-relative" role="region" aria-label="Message de succès">
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
            <span class="me-2">{{ e($tfFlash['message']) }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    </div>
@elseif ($tfFlash !== null && $tfFlash['type'] === 'error')
    <div class="sifec-session-flash mb-2 position-relative" role="region" aria-label="Message d’erreur">
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
            <span class="me-2">{{ e($tfFlash['message']) }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    </div>
@endif
