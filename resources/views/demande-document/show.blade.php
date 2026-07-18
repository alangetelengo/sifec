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
                                    
                                    @if(!$demande->estLivree() && !$demande->estRejetee() && !$demande->estExpiree() && !$demande->estSignee() && !$demande->estTraitee())
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
                        <i class="fas fa-info-circle me-2"></i>
                        Le PDF sera généré <strong>sans signature</strong>, puis passera en
                        <strong>attente de signature de délivrance</strong> par l’officier d’état civil
                        <strong>en fonction</strong> (pas nécessairement celui qui a signé l’acte d’origine).
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

@include('demande-document._modal_signature')
@endsection

@section('scripts')
<script src="{{ asset('js/vendor/forge.min.js') }}"></script>
<script src="{{ asset('js/vendor/elliptic.min.js') }}"></script>
<script src="{{ asset('js/sifec-p12-sign.js') }}?v=20260718a"></script>
<script src="{{ asset('tpl/wizard/assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
<script>
function showDemandeSignError(msg) {
    $('#otp-feedback').removeClass('d-none').text(msg);
    if (typeof Swal !== 'undefined') {
        Swal.fire({ icon: 'error', title: 'Erreur', text: msg });
    }
}

function openDemandeSignModal(codes) {
    $('#codes-demandes-signature').val(JSON.stringify(codes));
    $('#nb-demandes-signature').text(codes.length);
    $('#otp-feedback').addClass('d-none').empty();
    $('#demande_p12_file').val('');
    $('#demande_p12_pin').val('');
    $('#btn-valider-otp').prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer électroniquement');
    $('#modal-signature-otp').modal('show');
}

async function runDemandeP12Sign($btn) {
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Préparation…');
    $('#otp-feedback').addClass('d-none').empty();

    let codes = [];
    try { codes = JSON.parse($('#codes-demandes-signature').val() || '[]'); } catch (e) { codes = []; }
    if (!codes.length) {
        $btn.prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer électroniquement');
        showDemandeSignError('Aucune demande à signer.');
        return;
    }

    const fileInput = document.getElementById('demande_p12_file');
    const pin = $('#demande_p12_pin').val();
    if (!fileInput || !fileInput.files || !fileInput.files[0]) {
        $btn.prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer électroniquement');
        showDemandeSignError('Sélectionnez votre fichier certificat (.p12).');
        return;
    }
    if (!pin || !String(pin).trim()) {
        $btn.prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer électroniquement');
        showDemandeSignError('Saisissez la passphrase de votre certificat.');
        return;
    }
    if (typeof window.SifecP12Sign === 'undefined') {
        $btn.prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer électroniquement');
        showDemandeSignError('Bibliothèque de signature non chargée. Rechargez la page.');
        return;
    }

    try {
        const prep = await $.ajax({
            url: '{{ route("demandeDocument.sign.prepare") }}',
            type: 'POST',
            data: { demandes: codes, _token: '{{ csrf_token() }}' }
        });
        if (!prep.success || !prep.token || !prep.items || !prep.items.length) {
            $btn.prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer électroniquement');
            showDemandeSignError((prep && prep.message) ? prep.message : 'Échec de la préparation.');
            return;
        }

        $btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Signature locale…');
        const p12Binary = await window.SifecP12Sign.readP12File(fileInput.files[0]);
        const signatures = [];
        for (let i = 0; i < prep.items.length; i++) {
            const item = prep.items[i];
            const signatureHex = await window.SifecP12Sign.signHashHex(
                p12Binary, pin, item.document_hash, prep.expected_serial || null
            );
            signatures.push({ code_demande: item.code_demande, signature_hex: signatureHex });
        }

        $btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Validation…');
        const fin = await $.ajax({
            url: '{{ route("demandeDocument.sign.finalize") }}',
            type: 'POST',
            data: { token: prep.token, signatures: signatures, _token: '{{ csrf_token() }}' }
        });

        $btn.prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer électroniquement');
        if (fin.success) {
            $('#modal-signature-otp').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Signature effectuée',
                text: fin.message || 'Documents signés électroniquement',
                timer: 2200,
                showConfirmButton: false
            }).then(function() { location.reload(); });
        } else {
            showDemandeSignError(fin.message || 'Échec de la signature.');
        }
    } catch (err) {
        $btn.prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer électroniquement');
        let emsg = 'Erreur lors de la signature électronique';
        if (err && err.responseJSON && err.responseJSON.message) emsg = err.responseJSON.message;
        else if (err && err.message) emsg = err.message;
        showDemandeSignError(emsg);
    }
}
$(document).ready(function() {
    $('.btn-signer-unique').on('click', function() {
        const code = $(this).data('code');
        if (!code) return;
        openDemandeSignModal([String(code)]);
    });

    $('#btn-valider-otp').on('click', function() {
        runDemandeP12Sign($(this));
    });

    $('#modal-signature-otp').on('hidden.bs.modal', function() {
        $('#otp-feedback').addClass('d-none').empty();
        $('#demande_p12_file').val('');
        $('#demande_p12_pin').val('');
        $('#btn-valider-otp').prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer électroniquement');
    });
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
                const msg = (response && response.message)
                    ? response.message
                    : 'Document généré. En attente de signature de l\'officier d\'état civil en fonction.';

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'PDF généré',
                        text: msg,
                        timer: 2200,
                        showConfirmButton: false
                    }).then(function() {
                        window.location.reload();
                    });
                } else {
                    window.location.reload();
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html(originalHtml);

                let errorMsg = 'Une erreur est survenue lors de la génération du PDF';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Erreur', text: errorMsg });
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
