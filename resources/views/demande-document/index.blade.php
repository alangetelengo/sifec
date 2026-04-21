@extends('layout.app')

@section('titre')
Gestion des demandes de documents
@endsection

@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ asset('tpl/wizard/assets/node_modules/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
<link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
<style>
    @include('authentification::partials.sifec-swal-delete-styles')
    .badge-status {
        font-size: 11px;
        padding: 5px 10px;
    }
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .stats-number {
        font-size: 32px;
        font-weight: bold;
    }
    .stats-label {
        font-size: 14px;
        opacity: 0.9;
    }
    
    /* Amélioration des onglets */
    .nav-tabs {
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 20px;
    }
    .nav-tabs .nav-item {
        margin-bottom: -2px;
    }
    .nav-tabs .nav-link {
        border: none;
        background: transparent;
        color: #6c757d;
        padding: 12px 24px;
        font-weight: 500;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
    }
    .nav-tabs .nav-link:hover {
        color: #0d6efd;
        border-bottom-color: #0d6efd;
        background: rgba(13, 110, 253, 0.05);
    }
    .nav-tabs .nav-link.active {
        color: #0d6efd;
        border-bottom-color: #0d6efd;
        background: rgba(13, 110, 253, 0.08);
        font-weight: 600;
    }
    .nav-tabs .nav-link i {
        margin-right: 8px;
    }
    
    /* Amélioration des boutons d'actions */
    .action-buttons {
        display: inline-flex;
        gap: 4px;
        align-items: center;
    }
    .action-buttons .btn {
        padding: 6px 12px;
        border-radius: 6px;
        transition: all 0.2s ease;
        border: none;
    }
    .action-buttons .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .action-buttons .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .action-buttons .btn-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
    .action-buttons .btn-success {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
</style>
@endsection

@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4>Gestion des demandes de documents</h4>
                    <a href="{{ route('demandeDocument.create') }}">
                        <button type="button" class="btn btn-info m-t-2 float-end text-white">
                            Nouvelle demande sur site <i class="fa fa-plus-circle"></i>
                        </button>
                    </a>
                </div>

                {{-- Statistiques --}}
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <div class="stats-number">{{ $stats['en_traitement'] }}</div>
                                <div class="stats-label">En traitement</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                <div class="stats-number">{{ $stats['en_attente_signature'] }}</div>
                                <div class="stats-label">En attente de signature</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                                <div class="stats-number">{{ $stats['traitees_aujourdhui'] }}</div>
                                <div class="stats-label">Traitées aujourd'hui</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card" style="background: linear-gradient(135deg, #868f96 0%, #596164 100%);">
                                <div class="stats-number">{{ $stats['expirees'] }}</div>
                                <div class="stats-label">Expirées</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filtres --}}
                <div class="card-body border-top">
                    @include('demande-document._filters')
                </div>

                {{-- Onglets Portail / Sur site --}}
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ $origine == 'portail' ? 'active' : '' }}" 
                               href="{{ route('demandeDocument.index', array_merge(request()->all(), ['origine' => 'portail'])) }}">
                                <i class="fas fa-globe"></i> Demandes portail
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $origine == 'sur_site' ? 'active' : '' }}" 
                               href="{{ route('demandeDocument.index', array_merge(request()->all(), ['origine' => 'sur_site'])) }}">
                                <i class="fas fa-building"></i> Demandes sur site
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $origine == 'tous' ? 'active' : '' }}" 
                               href="{{ route('demandeDocument.index', array_merge(request()->all(), ['origine' => 'tous'])) }}">
                                <i class="fas fa-list"></i> Toutes
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content mt-3">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select-all"></th>
                                        <th>Code</th>
                                        <th>Date</th>
                                        <th>Demandeur</th>
                                        <th>Type acte</th>
                                        <th>Type document</th>
                                        <th>N° Acte</th>
                                        <th>Origine</th>
                                        <th>Statut</th>
                                        <th>Prix</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($demandes as $demande)
                                        <tr>
                                            <td>
                                                @if($demande->estEnAttenteSignature() && Gate::allows($demande->getPermissionSignature()))
                                                    <input type="checkbox" class="demande-checkbox" value="{{ $demande->code_demande_document }}">
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('demandeDocument.show', $demande->code_demande_document) }}">
                                                    {{ $demande->code_demande_document }}
                                                </a>
                                            </td>
                                            <td>{{ $demande->date_demande?->format('d/m/Y H:i') }}</td>
                                            <td>{{ $demande->getNomCompletDemandeur() }}</td>
                                            <td>{{ $demande->getLibelleTypeActe() }}</td>
                                            <td>{{ $demande->getLibelleTypeDocument() }}</td>
                                            <td>{{ $demande->numero_acte }}</td>
                                            <td>
                                                @if($demande->estPortail())
                                                    <span class="badge badge-info">Portail</span>
                                                @else
                                                    <span class="badge badge-secondary">Sur site</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($demande->estEnAttentePaiement())
                                                    <span class="badge badge-warning badge-status">En attente paiement</span>
                                                @elseif($demande->estEnTraitement())
                                                    <span class="badge badge-primary badge-status">En traitement</span>
                                                @elseif($demande->estEnAttenteSignature())
                                                    <span class="badge badge-info badge-status">À signer</span>
                                                @elseif($demande->estTraitee())
                                                    <span class="badge badge-success badge-status">Traitée</span>
                                                @elseif($demande->estLivree())
                                                    <span class="badge badge-dark badge-status">Livrée</span>
                                                @elseif($demande->estRejetee())
                                                    <span class="badge badge-danger badge-status">Rejetée</span>
                                                @elseif($demande->estExpiree())
                                                    <span class="badge badge-secondary badge-status">Expirée</span>
                                                @endif
                                            </td>
                                            <td>{{ number_format($demande->prix, 0, ',', ' ') }} FCFA</td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="{{ route('demandeDocument.show', $demande->code_demande_document) }}" 
                                                       class="btn btn-primary btn-sm" title="Détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    @if($demande->estEnTraitement())
                                                        <button type="button" 
                                                                class="btn btn-warning btn-sm btn-generer-pdf" 
                                                                data-code="{{ $demande->code_demande_document }}"
                                                                title="Générer PDF">
                                                            <i class="fas fa-file-pdf"></i>
                                                        </button>
                                                    @endif
                                                    
                                                    @if($demande->chemin_document && file_exists($demande->chemin_document))
                                                        <a href="{{ route('demandeDocument.pdf', $demande->code_demande_document) }}" 
                                                           class="btn btn-success btn-sm" title="Télécharger PDF" target="_blank">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center">Aucune demande trouvée</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-3">
                            {{ $demandes->links() }}
                        </div>

                        {{-- Actions groupées --}}
                        @if(
                            Gate::allows('module.acteNaissance.signature.extrait') ||
                            Gate::allows('module.acteNaissance.signature.copie') ||
                            Gate::allows('module.acteMariage.signature.extrait') ||
                            Gate::allows('module.acteMariage.signature.copie') ||
                            Gate::allows('module.acteDeces.signature.extrait') ||
                            Gate::allows('module.acteDeces.signature.copie') ||
                            Gate::allows('module.acteDivorce.signature.extrait') ||
                            Gate::allows('module.acteDivorce.signature.copie')
                        )
                            <div class="mt-3 border-top pt-3">
                                <button type="button" id="btn-signer-batch" class="btn btn-success" disabled>
                                    <i class="fas fa-signature"></i> Signer les demandes sélectionnées
                                </button>
                                <small class="text-muted ms-2">Seules les demandes pour lesquelles vous avez les droits seront signées</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

{{-- Modal de signature OTP --}}
@include('demande-document._modal_signature')

@endsection

@section('scripts')
<script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('tpl/wizard/assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
<script>
// Variables globales pour le timer OTP
let otpTimerInterval = null;
let otpTimeRemaining = 120; // 2 minutes
let resendAttempts = 0;
const MAX_RESEND = 3;

// Fonction pour démarrer le timer OTP (définie AVANT utilisation)
function startOtpTimer(seconds) {
    console.log('⏰ startOtpTimer appelé avec', seconds, 'secondes');
    
    otpTimeRemaining = seconds;
    const totalSeconds = seconds;
    
    if (otpTimerInterval) {
        clearInterval(otpTimerInterval);
        console.log('✅ Ancien timer nettoyé');
    }
    
    // Réinitialiser l'UI
    $('#otp-timer-block').removeClass('d-none').show();
    $('#otp-expired-block').addClass('d-none').hide();
    $('#btn-valider-otp').prop('disabled', false);
    $('#btn-resend-otp').prop('disabled', true);
    
    // Réinitialiser les couleurs de la barre
    $('#otp-progress').css('width', '100%')
                      .removeClass('bg-danger')
                      .addClass('bg-warning');
    $('#otp-countdown').css('color', '');
    
    console.log('🎨 UI réinitialisée');
    
    otpTimerInterval = setInterval(function() {
        otpTimeRemaining--;
        
        // Mise à jour de l'affichage (format mm:ss)
        const minutes = Math.floor(otpTimeRemaining / 60);
        const secs = otpTimeRemaining % 60;
        $('#otp-countdown').text(`${minutes}:${secs.toString().padStart(2, '0')}`);
        
        // Mise à jour de la barre de progression
        const denom = totalSeconds > 0 ? totalSeconds : 1;
        const percent = Math.round((otpTimeRemaining / denom) * 100);
        $('#otp-progress').css('width', percent + '%');
        
        if (otpTimeRemaining % 10 === 0) {
            console.log('⏱️  Timer:', otpTimeRemaining, 's restantes, barre:', percent + '%');
        }
        
        // Passage au rouge dans les 10 dernières secondes
        if (otpTimeRemaining <= 10) {
            $('#otp-countdown').css('color', '#DC241F');
            $('#otp-progress').removeClass('bg-warning').addClass('bg-danger');
        }
        
        // Si expiré
        if (otpTimeRemaining <= 0) {
            clearInterval(otpTimerInterval);
            $('#otp-timer-block').addClass('d-none').hide();
            $('#otp-expired-block').removeClass('d-none').show();
            $('#btn-valider-otp').prop('disabled', true);
            $('#btn-resend-otp').prop('disabled', false);
            console.log('❌ Timer expiré');
        }
    }, 1000);
    
    console.log('✅ setInterval démarré, ID:', otpTimerInterval);
}

$(document).ready(function() {
    // Initialiser DataTable
    $('#example').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json'
        },
        order: [[2, 'desc']],
        pageLength: 25,
        columnDefs: [
            { orderable: false, targets: [0, 10] }
        ]
    });

    // Gestion de la sélection
    $('#select-all').on('change', function() {
        $('.demande-checkbox').prop('checked', $(this).prop('checked'));
        updateSignerButton();
    });

    $('.demande-checkbox').on('change', function() {
        updateSignerButton();
    });

    function updateSignerButton() {
        const nbSelected = $('.demande-checkbox:checked').length;
        $('#btn-signer-batch').prop('disabled', nbSelected === 0);
        
        if (nbSelected > 0) {
            $('#btn-signer-batch').html(`<i class="fas fa-signature"></i> Signer ${nbSelected} demande(s) sélectionnée(s)`);
        } else {
            $('#btn-signer-batch').html('<i class="fas fa-signature"></i> Signer les demandes sélectionnées');
        }
    }

    // Ouvrir le modal de signature - ENVOYER OTP AVANT (comme validation actes)
    $('#btn-signer-batch').on('click', function() {
        const codes = $('.demande-checkbox:checked').map(function() {
            return $(this).val();
        }).get();
        
        if (codes.length === 0) {
            return;
        }
        
        // Stocker les codes et le nombre
        $('#codes-demandes-signature').val(JSON.stringify(codes));
        $('#nb-demandes-signature').text(codes.length);
        
        // Envoyer l'OTP AVANT d'ouvrir le modal
        const $btn = $(this);
        const originalHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Envoi OTP...');
        
        $.ajax({
            url: '{{ route("demandeDocument.initierSignature") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                demandes: codes
            },
            success: function(response) {
                $btn.prop('disabled', false).html(originalHtml);
                
                if (response.success) {
                    // Ouvrir le modal APRÈS avoir reçu l'OTP
                    $('#modal-signature-otp').modal('show');
                    
                    // DÉMARRER LE TIMER IMMÉDIATEMENT (fix Bootstrap 5 event issue)
                    setTimeout(function() {
                        console.log('🚀 Démarrage du timer OTP...');
                        startOtpTimer(120);
                        resendAttempts = 0;
                        $('#input-otp').val('').trigger('focus');
                    }, 500);
                    
                    // En développement, afficher l'OTP
                    @if(config('app.debug'))
                        if (response.otp) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Code OTP (dev)',
                                text: response.otp,
                                timer: 5000,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false
                            });
                        }
                    @endif
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html(originalHtml);
                
                let message = 'Erreur lors de la génération de l\'OTP';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.status === 403) {
                    message = 'Vous n\'avez pas les droits nécessaires pour signer ces documents';
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: message
                });
            }
        });
    });

    // Validation OTP - Handler principal (doit être dans le script principal)
    $('#btn-valider-otp').on('click', function() {
        console.log('🔐 Click sur btn-valider-otp détecté');
        
        const otp = $('#input-otp').val().trim();
        
        if (!otp) {
            Swal.fire({
                icon: 'warning',
                title: 'Attention',
                text: 'Veuillez saisir le code à 6 chiffres reçu par SMS et email'
            });
            return;
        }
        
        if (!/^\d{6}$/.test(otp)) {
            Swal.fire({
                icon: 'warning',
                title: 'Attention',
                text: 'Le code doit comporter exactement 6 chiffres (0 à 9)'
            });
            return;
        }
        
        const $btn = $(this);
        const originalHtml = $btn.html();
        
        console.log('✅ Validation OTP en cours...');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Signature...');
        
        $.ajax({
            url: '{{ route("demandeDocument.validerSignature") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                otp: otp
            },
            success: function(response) {
                console.log('📩 Réponse AJAX:', response);
                
                if (response.success) {
                    $('#otp-feedback').addClass('d-none').empty();
                    Swal.fire({
                        icon: 'success',
                        title: 'Signature effectuée',
                        text: response.message || 'Les documents ont été signés avec succès',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        clearInterval(otpTimerInterval);
                        $('#modal-signature-otp').modal('hide');
                        location.reload();
                    });
                } else {
                    // Afficher le feedback avec tentatives restantes si disponible
                    if (response.remaining_attempts !== undefined) {
                        $('#otp-feedback').removeClass('d-none').text(
                            response.message + ' — Tentatives restantes : ' + response.remaining_attempts
                        );
                    } else {
                        $('#otp-feedback').addClass('d-none').empty();
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: response.message
                    });
                    $btn.prop('disabled', false).html(originalHtml);
                }
            },
            error: function(xhr) {
                console.error('❌ Erreur AJAX:', xhr);
                
                let message = 'Erreur lors de la validation de l\'OTP';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                
                $('#otp-feedback').addClass('d-none').empty();
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: message
                });
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    });
    
    // Renvoyer le code OTP
    $('#btn-resend-otp').on('click', function() {
        if (resendAttempts >= MAX_RESEND) {
            Swal.fire({
                icon: 'error',
                title: 'Limite atteinte',
                text: 'Vous avez atteint le nombre maximum de renvois (3)'
            });
            return;
        }
        
        const codes = JSON.parse($('#codes-demandes-signature').val());
        const $btn = $(this);
        const originalHtml = $btn.html();
        
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Renvoi...');
        
        $.ajax({
            url: '{{ route("demandeDocument.initierSignature") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                demandes: codes,
                resend: true
            },
            success: function(response) {
                if (response.success) {
                    resendAttempts++;
                    startOtpTimer(120);
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Code renvoyé',
                        text: 'Un nouveau code a été envoyé par SMS et email',
                        timer: 2000,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false
                    });
                    
                    @if(config('app.debug'))
                        if (response.otp) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Code OTP (dev)',
                                text: response.otp,
                                timer: 5000,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false
                            });
                        }
                    @endif
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: response.message
                    });
                    $btn.prop('disabled', false).html(originalHtml);
                }
            },
            error: function(xhr) {
                let message = 'Erreur lors du renvoi de l\'OTP';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: message
                });
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    });
    
    // Réinitialiser le modal à la fermeture
    $('#modal-signature-otp').on('hidden.bs.modal', function() {
        if (otpTimerInterval) {
            clearInterval(otpTimerInterval);
        }
        
        otpTimeRemaining = 120;
        resendAttempts = 0;
        
        $('#input-otp').val('');
        $('#otp-feedback').addClass('d-none').empty();
        $('#otp-timer-block').removeClass('d-none').show();
        $('#otp-expired-block').addClass('d-none').hide();
        $('#btn-valider-otp').prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer');
        $('#btn-resend-otp').prop('disabled', true).html('<i class="fas fa-redo me-1"></i> Renvoyer le code');
        
        // Réinitialiser la barre de progression
        $('#otp-progress').css('width', '100%')
                          .removeClass('bg-danger')
                          .addClass('bg-warning');
        $('#otp-countdown').text('2:00').css('color', '');
    });

    // Génération PDF avec AJAX
    $(document).on('click', '.btn-generer-pdf', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $btn = $(this);
        const code = $btn.data('code');
        const originalHtml = $btn.html();
        const url = '{{ route("demandeDocument.genererPdf", ":code") }}'.replace(':code', code);
        
        Swal.fire({
            title: 'Générer le PDF?',
            text: "Le document sera généré et passera en attente de signature",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, générer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
                
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès',
                            text: 'Document généré avec succès',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false).html(originalHtml);
                        
                        let errorMsg = 'Une erreur est survenue';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.responseText) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                errorMsg = response.message || errorMsg;
                            } catch(e) {
                                console.error('Erreur parsing:', e);
                            }
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: errorMsg
                        });
                        
                        console.error('Erreur AJAX:', xhr);
                    }
                });
            }
        });
    });
});
</script>
@endsection
