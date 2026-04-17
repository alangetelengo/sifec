{{-- Modal partagé : impression acte + consultation actes retirés --}}
<div class="modal fade" id="modal-retrait-acte" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-file-export me-2"></i>
                    Retrait de l'acte de naissance
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer">
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <div>
                        <strong>Information :</strong> Cette action enregistrera le retrait de l'acte de naissance.
                        L'acte ne pourra plus être imprimé.
                    </div>
                </div>

                <form id="form-retrait-acte" novalidate>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-file-alt me-1"></i>
                                Numéro de l'acte
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-hashtag text-muted"></i>
                                </span>
                                <input type="text" id="code_acte" class="form-control" readonly>
                                <input type="hidden" id="leniupp">
                            </div>
                            <div class="form-text">Numéro unique de l'acte de naissance</div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Date de retrait
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-calendar text-muted"></i>
                                </span>
                                <input type="text" id="date_retrait" class="form-control" readonly>
                            </div>
                            <div class="form-text">Date automatique du retrait</div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-user me-1"></i>
                                Nom de l'intéressé
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-user text-muted"></i>
                                </span>
                                <input type="text"
                                       class="form-control"
                                       id="nom_interesse"
                                       placeholder="Saisissez le nom de famille"
                                       required
                                       style="text-transform: uppercase;"
                                       maxlength="255">
                            </div>
                            <div class="invalid-feedback" id="nom_interesse_error"></div>
                            <div class="form-text">Nom de famille de la personne qui retire l'acte</div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-user me-1"></i>
                                Prénom de l'intéressé
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-user text-muted"></i>
                                </span>
                                <input type="text"
                                       class="form-control"
                                       id="prenom_interesse"
                                       placeholder="Saisissez le prénom"
                                       style="text-transform: capitalize;"
                                       maxlength="255">
                            </div>
                            <div class="form-text">Prénom de la personne qui retire l'acte</div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-phone me-1"></i>
                                Téléphone de l'intéressé
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-phone text-muted"></i>
                                </span>
                                <input type="tel"
                                       class="form-control"
                                       id="telephone_interesse"
                                       placeholder="Ex: +242 06 123 456"
                                       required
                                       maxlength="20"
                                       pattern="[+]?[0-9\s\-\(\)]{8,20}">
                            </div>
                            <div class="invalid-feedback" id="telephone_interesse_error"></div>
                            <div class="form-text">Numéro de téléphone valide (8-20 caractères)</div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-id-card me-1"></i>
                                Pièce d'identité
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-id-card text-muted"></i>
                                </span>
                                <select class="form-select" id="piece_identite">
                                    <option value="">Sélectionnez le type</option>
                                    <option value="CNI">Carte Nationale d'Identité</option>
                                    <option value="PASSEPORT">Passeport</option>
                                    <option value="PERMIS">Permis de conduire</option>
                                    <option value="AUTRE">Autre</option>
                                </select>
                            </div>
                            <div class="form-text">Type de pièce d'identité présentée</div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-hashtag me-1"></i>
                                Numéro de pièce d'identité
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-hashtag text-muted"></i>
                                </span>
                                <input type="text"
                                       class="form-control"
                                       id="numero_piece_identite"
                                       placeholder="Numéro de la pièce d'identité"
                                       maxlength="50">
                            </div>
                            <div class="form-text">Numéro de la pièce d'identité présentée</div>
                        </div>

                        <div class="mb-3 col-12">
                            <label class="form-label fw-bold">
                                <i class="fas fa-comment me-1"></i>
                                Observations
                            </label>
                            <textarea class="form-control"
                                      id="observations_retrait"
                                      rows="3"
                                      placeholder="Observations particulières sur le retrait (optionnel)"
                                      maxlength="500"></textarea>
                            <div class="form-text">Observations particulières sur le retrait</div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Annuler
                </button>
                <button type="button" class="btn btn-primary" id="btn-retrait">
                    <i class="fas fa-check me-1"></i>
                    <span class="btn-text">Enregistrer le retrait</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </div>
</div>
