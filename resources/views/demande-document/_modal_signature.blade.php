{{-- Modal de signature OTP --}}
<div class="modal fade" id="modal-signature-otp" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-shield-alt me-2"></i>Signature électronique des documents
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="codes-demandes-signature">

                {{-- Rappel métier --}}
                <div class="alert alert-info py-2 mb-3">
                    <p class="mb-2 small">
                        Vous allez signer <strong><span id="nb-demandes-signature">0</span> demande(s)</strong> de document(s).
                        Après signature, les documents pourront être téléchargés avec votre signature officielle.
                    </p>
                    <p class="mb-0 small text-muted">
                        <i class="fas fa-lock me-1"></i>
                        <strong>Sécurité :</strong> Le code OTP est envoyé par <strong>SMS et email</strong> et reste valide <strong>2 minutes</strong>.
                    </p>
                </div>

                <div id="otp-feedback" class="alert alert-warning py-2 small d-none mb-3" role="status"></div>

                <div class="row g-3">
                    {{-- Champ OTP --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">
                            Code de validation <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control form-control-lg text-center fw-bold"
                               id="input-otp"
                               name="input-otp"
                               placeholder="_ _ _ _ _ _"
                               maxlength="6"
                               inputmode="numeric"
                               pattern="[0-9]{6}"
                               autocomplete="one-time-code"
                               style="letter-spacing: 10px; font-size: 24px;"
                               required>
                        <small class="text-muted">Saisissez les <strong>6 chiffres</strong> reçus par SMS et email.</small>
                    </div>

                    {{-- Countdown --}}
                    <div class="col-md-6 d-flex flex-column justify-content-center">
                        {{-- Timer actif --}}
                        <div id="otp-timer-block" class="mb-2">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="fas fa-clock text-warning"></i>
                                <span class="small">Code valide encore :
                                    <strong id="otp-countdown" class="text-warning fs-5">2:00</strong>
                                </span>
                            </div>
                            <div class="progress" style="height:6px;">
                                <div id="otp-progress"
                                     class="progress-bar bg-warning progress-bar-striped progress-bar-animated"
                                     role="progressbar"
                                     style="width:100%;">
                                </div>
                            </div>
                        </div>
                        {{-- Timer expiré --}}
                        <div id="otp-expired-block" class="d-none mb-2">
                            <div class="alert alert-danger py-2 mb-1">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <strong>Code expiré.</strong> Utilisez « Renvoyer le code ».
                            </div>
                        </div>
                        {{-- Bouton renvoyer --}}
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-resend-otp" disabled>
                            <i class="fas fa-redo me-1"></i> Renvoyer le code
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" id="btn-valider-otp">
                    <i class="fas fa-signature me-1"></i> Signer
                </button>
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Fermer
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Les handlers pour le modal de signature (btn-valider-otp, btn-resend-otp, etc.) 
// sont maintenant définis dans les pages parentes (index.blade.php et show.blade.php)
// pour garantir que les variables et fonction startOtpTimer() soient disponibles
</script>
@endpush
