@extends('layout.app')

@section('titre')
Détail demande {{ $demande->code_demande_document }}
@endsection

@section('styles')
<link href="{{ asset('tpl/wizard/assets/node_modules/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
<link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
<style>
    @include('authentification::partials.sifec-swal-delete-styles')
    .info-row {
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }
    .info-label {
        font-weight: 600;
        color: #666;
    }
    .timeline-item {
        padding: 15px;
        border-left: 3px solid #007bff;
        margin-bottom: 15px;
        background: #f8f9fa;
    }
    
    /* Styles pour les boutons d'action */
    .d-flex.gap-2 > * {
        margin-right: 10px;
        margin-bottom: 10px;
    }
    
    .btn-lg {
        padding: 12px 24px;
        font-size: 16px;
        font-weight: 500;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-lg:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    .btn-lg:active {
        transform: translateY(0);
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    
    .btn-lg i {
        font-size: 18px;
    }
    
    .btn-info {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    }
    
    .btn-info:hover {
        background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
    }
    
    .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #218838 100%);
    }
    
    .btn-success:hover {
        background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
    }
    
    .btn-dark {
        background: linear-gradient(135deg, #343a40 0%, #23272b 100%);
    }
    
    .btn-dark:hover {
        background: linear-gradient(135deg, #23272b 0%, #1d2124 100%);
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    }
    
    .btn-danger:hover {
        background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
    }
</style>
@endsection

@section('corps')
<div class="page-sifec-detail">
<div class="an-shell">
<div class="an-body">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4>Détail de la demande {{ $demande->code_demande_document }}</h4>
                    <a href="{{ route('demandeDocument.index') }}">
                        <button type="button" class="btn btn-warning float-end text-white">
                            <i class="fa fa-list"></i> Retour à la liste
                        </button>
                    </a>
                </div>

                <div class="card-body">
                    <div class="row">
                        {{-- Informations générales --}}
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3">Informations générales</h5>
                            
                            <div class="info-row row">
                                <div class="col-5 info-label">Code demande:</div>
                                <div class="col-7">{{ $demande->code_demande_document }}</div>
                            </div>
                            
                            <div class="info-row row">
                                <div class="col-5 info-label">Statut:</div>
                                <div class="col-7">
                                    @if($demande->estEnAttentePaiement())
                                        <span class="badge badge-warning">{{ $demande->statut }}</span>
                                    @elseif($demande->estEnTraitement())
                                        <span class="badge badge-primary">{{ $demande->statut }}</span>
                                    @elseif($demande->estEnAttenteSignature())
                                        <span class="badge badge-info">{{ $demande->statut }}</span>
                                    @elseif($demande->estTraitee())
                                        <span class="badge badge-success">{{ $demande->statut }}</span>
                                    @elseif($demande->estLivree())
                                        <span class="badge badge-dark">{{ $demande->statut }}</span>
                                    @elseif($demande->estRejetee())
                                        <span class="badge badge-danger">{{ $demande->statut }}</span>
                                    @elseif($demande->estExpiree())
                                        <span class="badge badge-secondary">{{ $demande->statut }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="info-row row">
                                <div class="col-5 info-label">Origine:</div>
                                <div class="col-7">
                                    @if($demande->estPortail())
                                        <span class="badge badge-info">Portail en ligne</span>
                                    @else
                                        <span class="badge badge-secondary">Sur site</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="info-row row">
                                <div class="col-5 info-label">Type d'acte:</div>
                                <div class="col-7">{{ $demande->getLibelleTypeActe() }}</div>
                            </div>
                            
                            <div class="info-row row">
                                <div class="col-5 info-label">Type de document:</div>
                                <div class="col-7">{{ $demande->getLibelleTypeDocument() }}</div>
                            </div>
                            
                            <div class="info-row row">
                                <div class="col-5 info-label">Numéro d'acte:</div>
                                <div class="col-7"><strong>{{ $demande->numero_acte }}</strong></div>
                            </div>
                            
                            <div class="info-row row">
                                <div class="col-5 info-label">Prix:</div>
                                <div class="col-7"><strong>{{ number_format($demande->prix, 0, ',', ' ') }} FCFA</strong></div>
                            </div>
                            
                            <div class="info-row row">
                                <div class="col-5 info-label">Date demande:</div>
                                <div class="col-7">{{ $demande->date_demande?->format('d/m/Y à H:i') }}</div>
                            </div>
                            
                            @if($demande->date_traitement)
                            <div class="info-row row">
                                <div class="col-5 info-label">Date traitement:</div>
                                <div class="col-7">{{ $demande->date_traitement->format('d/m/Y à H:i') }}</div>
                            </div>
                            @endif
                            
                            @if($demande->date_signature)
                            <div class="info-row row">
                                <div class="col-5 info-label">Date signature:</div>
                                <div class="col-7">{{ $demande->date_signature->format('d/m/Y à H:i') }}</div>
                            </div>
                            @endif

                            @if($demande->document_valide_de && $demande->document_valide_jusquau)
                            <div class="info-row row">
                                <div class="col-5 info-label">Validité du document:</div>
                                <div class="col-7">
                                    du {{ $demande->document_valide_de->format('d/m/Y') }}
                                    au {{ $demande->document_valide_jusquau->format('d/m/Y') }}
                                    @if($demande->documentEstEncoreValide())
                                        <span class="badge badge-success ms-1">En cours</span>
                                    @elseif($demande->estTraitee() || $demande->estLivree())
                                        <span class="badge badge-warning ms-1">Périmé (en attente passage à « Expirée »)</span>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if((int) ($demande->compteur_renouvellement ?? 0) > 0)
                            <div class="info-row row">
                                <div class="col-5 info-label">Renouvellements:</div>
                                <div class="col-7">{{ $demande->compteur_renouvellement }}</div>
                            </div>
                            @endif
                            
                            @if($demande->date_livraison)
                            <div class="info-row row">
                                <div class="col-5 info-label">Date livraison:</div>
                                <div class="col-7">{{ $demande->date_livraison->format('d/m/Y à H:i') }}</div>
                            </div>
                            @endif
                        </div>

                        {{-- Informations demandeur et signataire --}}
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3">Demandeur</h5>
                            
                            <div class="info-row row">
                                <div class="col-5 info-label">Nom complet:</div>
                                <div class="col-7">{{ $demande->getNomCompletDemandeur() }}</div>
                            </div>
                            
                            <div class="info-row row">
                                <div class="col-5 info-label">Téléphone:</div>
                                <div class="col-7">{{ $demande->telephone_demander }}</div>
                            </div>
                            
                            <div class="info-row row">
                                <div class="col-5 info-label">Email:</div>
                                <div class="col-7">{{ $demande->email_demandeur ?? 'Non renseigné' }}</div>
                            </div>
                            
                            @if($demande->signataire)
                            <h5 class="border-bottom pb-2 mb-3 mt-4">Signataire</h5>
                            
                            <div class="info-row row">
                                <div class="col-5 info-label">Officier:</div>
                                <div class="col-7">{{ optional(optional($demande->signataire->user)->personne)->nomcomplet() }}</div>
                            </div>
                            
                            <div class="info-row row">
                                <div class="col-5 info-label">Signature:</div>
                                <div class="col-7">
                                    @if($demande->signature_officier)
                                        <img src="{{ asset('app/'.$demande->signature_officier) }}" style="max-height: 80px;" alt="Signature">
                                    @endif
                                </div>
                            </div>
                            @endif
                            
                            @if($demande->observations)
                            <h5 class="border-bottom pb-2 mb-3 mt-4">Observations</h5>
                            <div class="alert alert-secondary">
                                {{ $demande->observations }}
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="border-top pt-4">
                                <h5 class="mb-4">
                                    <i class="fas fa-tools text-primary"></i> Actions disponibles
                                </h5>
                                <div class="d-flex flex-wrap gap-2">
                                    @if($demande->estEnTraitement())
                                        <button type="button" class="btn btn-info text-white btn-lg" data-bs-toggle="modal" data-bs-target="#modal-generer-pdf">
                                            <i class="fas fa-file-pdf me-2"></i> Générer le PDF
                                        </button>
                                    @endif
                                    
                                    @if($demande->estEnAttenteSignature())
                                        @can($demande->getPermissionSignature())
                                            <button type="button" class="btn btn-primary btn-lg btn-signer-unique" 
                                                    data-code="{{ $demande->code_demande_document }}">
                                                <i class="fas fa-signature me-2"></i> Signer le document
                                            </button>
                                        @else
                                            <div class="alert alert-warning d-inline-flex align-items-center" style="margin: 0; padding: 10px 15px;">
                                                <i class="fas fa-lock me-2"></i> 
                                                <span>En attente de signature par un agent autorisé pour {{ $demande->getLibelleTypeDocument() }} de {{ $demande->getLibelleTypeActe() }}</span>
                                            </div>
                                        @endcan
                                    @endif
                                    
                                    @if($demande->chemin_document && file_exists($demande->chemin_document))
                                        <a href="{{ route('demandeDocument.pdf', $demande->code_demande_document) }}" 
                                           class="btn btn-success btn-lg" target="_blank">
                                            <i class="fas fa-download me-2"></i> Télécharger PDF
                                        </a>
                                    @endif
                                    
                                    @if($demande->estTraitee())
                                        <button type="button" class="btn btn-dark btn-lg" data-bs-toggle="modal" data-bs-target="#modal-livrer">
                                            <i class="fas fa-check-circle me-2"></i> Marquer comme livrée
                                        </button>
                                    @endif

                                    @if($demande->estExpiree())
                                        <form method="POST" action="{{ route('demandeDocument.renouveler', $demande->code_demande_document) }}" class="d-inline"
                                              onsubmit="return confirm('Remettre cette demande en traitement pour générer un nouveau PDF et signer à nouveau ?');">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-lg text-dark">
                                                <i class="fas fa-redo me-2"></i> Préparer un nouveau cycle (PDF + signature)
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if(!$demande->estLivree() && !$demande->estRejetee() && !$demande->estExpiree())
                                        <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#modal-rejeter">
                                            <i class="fas fa-times-circle me-2"></i> Rejeter la demande
                                        </button>
                                    @endif
                                </div>
                                
                                @if($demande->estExpiree())
                                    <div class="alert alert-secondary mt-3" role="alert">
                                        <i class="fas fa-hourglass-end"></i> Document expiré (hors période de validité). Utilisez « Préparer un nouveau cycle » pour une nouvelle signature.
                                    </div>
                                @elseif($demande->documentPerimeSansChangementStatut())
                                    <div class="alert alert-warning mt-3" role="alert">
                                        <i class="fas fa-exclamation-triangle"></i> La période de validité est dépassée ; le statut « Expirée » sera appliqué lors de la prochaine exécution de la tâche planifiée (ou exécutez <code>php artisan demande-document:marquer-expirees</code>).
                                    </div>
                                @elseif($demande->estLivree())
                                    <div class="alert alert-success mt-3" role="alert">
                                        <i class="fas fa-check-circle"></i> Cette demande a été livrée. Aucune action supplémentaire n'est possible.
                                    </div>
                                @elseif($demande->estRejetee())
                                    <div class="alert alert-danger mt-3" role="alert">
                                        <i class="fas fa-ban"></i> Cette demande a été rejetée. Aucune action supplémentaire n'est possible.
                                    </div>
                                @elseif($demande->estEnAttentePaiement())
                                    <div class="alert alert-warning mt-3" role="alert">
                                        <i class="fas fa-hourglass-half"></i> Cette demande est en attente de paiement.
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
</div>

{{-- Modal de génération PDF --}}
<div class="modal fade" id="modal-generer-pdf" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 10px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
            <form id="form-generer-pdf">
                @csrf
                <div class="modal-header bg-info text-white" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                    <h5 class="modal-title">
                        <i class="fas fa-file-pdf me-2"></i> Générer le PDF
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body" style="padding: 25px;">
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle me-2"></i> Le document sera généré et passera en attente de signature de l'officier d'état civil.
                    </div>
                    <p class="mb-0">
                        <strong>Type de document :</strong> {{ $demande->getLibelleTypeDocument() }}<br>
                        <strong>Acte concerné :</strong> {{ $demande->getLibelleTypeActe() }} N° {{ $demande->numero_acte }}
                    </p>
                </div>
                <div class="modal-footer" style="border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; background-color: #f8f9fa;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-info text-white" id="btn-confirmer-generation">
                        <i class="fas fa-check me-2"></i> Oui, générer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal de livraison --}}
<div class="modal fade" id="modal-livrer" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 10px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
            <form id="form-livrer">
                @csrf
                <div class="modal-header bg-dark text-white" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                    <h5 class="modal-title">
                        <i class="fas fa-check-circle me-2"></i> Marquer comme livrée
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body" style="padding: 25px;">
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-info-circle me-2"></i> Cette action marquera la demande comme livrée au demandeur.
                    </div>
                    <p class="mb-0">
                        Confirmez-vous que le document a été remis à <strong>{{ $demande->getNomCompletDemandeur() }}</strong> ?
                    </p>
                </div>
                <div class="modal-footer" style="border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; background-color: #f8f9fa;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-dark" id="btn-confirmer-livraison">
                        <i class="fas fa-check me-2"></i> Oui, confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal de rejet --}}
<div class="modal fade" id="modal-rejeter" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 10px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
            <form action="{{ route('demandeDocument.rejeter', $demande->code_demande_document) }}" 
                  method="POST" id="form-rejet">
                @csrf
                <div class="modal-header bg-danger text-white" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i> Rejeter la demande
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body" style="padding: 25px;">
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-info-circle me-2"></i> Cette action est irréversible. Veuillez fournir une explication claire.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Motif du rejet <span class="text-danger">*</span></label>
                        <textarea name="motif" class="form-control" rows="5" required 
                                  placeholder="Expliquez la raison du rejet de manière détaillée..." 
                                  style="border-radius: 8px;"></textarea>
                        <small class="text-muted">
                            <i class="fas fa-lightbulb me-1"></i> Le demandeur recevra une notification avec ce motif.
                        </small>
                    </div>
                </div>
                <div class="modal-footer" style="border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; background-color: #f8f9fa;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-danger" id="btn-confirmer-rejet">
                        <i class="fas fa-check me-2"></i> Confirmer le rejet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
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
    // Signature unique - Envoyer OTP AVANT d'ouvrir le modal (comme validation actes)
    $('.btn-signer-unique').on('click', function() {
        const code = $(this).data('code');
        const codes = [code];
        
        // Stocker les codes et le nombre
        $('#codes-demandes-signature').val(JSON.stringify(codes));
        $('#nb-demandes-signature').text(1);
        
        // Envoyer l'OTP AVANT d'ouvrir le modal
        const $btn = $(this);
        const originalHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Envoi OTP...');
        
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
                    message = 'Vous n\'avez pas les droits nécessaires pour signer ce document';
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
                        text: response.message || 'Le document a été signé avec succès',
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

@push('scripts')
<script>
// Les handlers pour le modal de signature sont maintenant définis dans les pages parentes (index.blade.php et show.blade.php)
// afin de garantir que la fonction startOtpTimer() soit disponible au bon moment
</script>
@endpush

    $('#form-generer-pdf').on('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $form = $(this);
        const $btn = $('#btn-confirmer-generation');
        const originalHtml = $btn.html();
        const url = '{{ route("demandeDocument.genererPdf", $demande->code_demande_document) }}';
        
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Génération en cours...');
        
        $.ajax({
            url: url,
            type: 'POST',
            data: $form.serialize(),
            success: function(response) {
                $('#modal-generer-pdf').modal('hide');
                
                // Afficher notification de succès
                if (typeof flash !== 'undefined') {
                    flash('success', 'Document généré avec succès. En attente de signature.');
                }
                
                // Recharger la page après un court délai
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html(originalHtml);
                
                let errorMsg = 'Une erreur est survenue lors de la génération du PDF';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                
                // Afficher notification d'erreur
                if (typeof flash !== 'undefined') {
                    flash('error', errorMsg);
                } else {
                    alert(errorMsg);
                }
                
                console.error('Erreur AJAX génération PDF:', xhr);
            }
        });
    });
    
    // Livraison avec AJAX
    $('#form-livrer').on('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $form = $(this);
        const $btn = $('#btn-confirmer-livraison');
        const originalHtml = $btn.html();
        const url = '{{ route("demandeDocument.livree", $demande->code_demande_document) }}';
        
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Traitement...');
        
        $.ajax({
            url: url,
            type: 'POST',
            data: $form.serialize(),
            success: function(response) {
                $('#modal-livrer').modal('hide');
                
                // Afficher notification de succès
                if (typeof flash !== 'undefined') {
                    flash('success', 'Demande marquée comme livrée.');
                }
                
                // Recharger la page après un court délai
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html(originalHtml);
                
                let errorMsg = 'Une erreur est survenue';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                
                // Afficher notification d'erreur
                if (typeof flash !== 'undefined') {
                    flash('error', errorMsg);
                } else {
                    alert(errorMsg);
                }
                
                console.error('Erreur AJAX livraison:', xhr);
            }
        });
    });
    
    // Spinner sur rejet
    $('#form-rejet').on('submit', function() {
        const $btn = $('#btn-confirmer-rejet');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Rejet en cours...');
    });
});
</script>
@endsection
