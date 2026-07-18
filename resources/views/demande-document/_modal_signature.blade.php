{{-- Modal de signature électronique .p12 (délivrance copie / extrait) --}}
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

                <div class="alert alert-info py-2 mb-3">
                    <p class="mb-2 small">
                        Vous allez signer <strong><span id="nb-demandes-signature">0</span> demande(s)</strong>
                        avec votre <strong>certificat électronique (.p12)</strong>
                        (officier d’état civil en fonction — signature de délivrance).
                    </p>
                    <ol class="small mb-0 ps-3">
                        <li>Vérifiez les documents sélectionnés</li>
                        <li>Sélectionnez votre fichier <strong>.p12</strong> et saisissez la passphrase</li>
                        <li>Cliquez sur <strong>Signer électroniquement</strong></li>
                    </ol>
                </div>

                <div id="otp-feedback" class="alert alert-danger py-2 small d-none mb-3" role="alert"></div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold" for="demande_p12_file">
                            Certificat électronique (.p12) <span class="text-danger">*</span>
                        </label>
                        <input type="file"
                               class="form-control"
                               id="demande_p12_file"
                               accept=".p12,.pfx,application/x-pkcs12">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold" for="demande_p12_pin">
                            Passphrase <span class="text-danger">*</span>
                        </label>
                        <input type="password"
                               class="form-control"
                               id="demande_p12_pin"
                               autocomplete="off"
                               placeholder="Passphrase du certificat">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" id="btn-valider-otp">
                    <i class="fas fa-signature me-1"></i> Signer électroniquement
                </button>
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Fermer
                </button>
            </div>
        </div>
    </div>
</div>
