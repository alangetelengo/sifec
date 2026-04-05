@extends('layout.app')
@section('titre')
Acte de naissance
@endsection
@section("styles")
<style>
    @media print {
        /* Masquer toute la page (sidebar, header, boutons, etc.) */
        body * {
            visibility: hidden;
        }
        /* Afficher uniquement le conteneur PDF et son contenu */
        #pdf-viewer-container,
        #pdf-viewer-container * {
            visibility: visible;
        }
        #pdf-viewer-container {
            position: fixed !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            height: 100% !important;
            margin: 0 !important;
            padding: 20px !important;
            background: #fff !important;
            z-index: 9999 !important;
        }
        #pdf-loading, #pdf-error {
            display: none !important;
        }
        #pdf-canvas-container canvas {
            max-width: 100% !important;
            height: auto !important;
            page-break-after: always;
            box-shadow: none !important;
        }
        #pdf-canvas-container canvas:last-child {
            page-break-after: avoid;
        }
        @page { margin: 1cm; }
    }
    #pdf-viewer-container {
        position: relative;
        min-height: 800px;
        background: #525659;
    }
    #pdf-canvas-container {
        text-align: center;
        padding: 20px 0;
    }
    #pdf-canvas-container canvas {
        display: block;
        margin: 0 auto 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        max-width: 100%;
    }
    #pdf-loading {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #fff;
    }
</style>
@endsection

@section('corps')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4></h4>
                <div class="row">
                    <div id="dupcreer" class="d-flex gap-2 justify-content-end">
                        @if($acte && $acte->approbation_mairie != null)
                            @if($acte->retirer == 0)
                                <button type="button" class="btn btn-primary" id="btn-imprimer-acte" title="Imprimer l'acte de naissance">
                                    <i class="fas fa-print me-1"></i>
                                    Imprimer l'acte
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary" id="btn-imprimer-acte" disabled title="L'acte a été retiré, l'impression n'est plus autorisée">
                                    <i class="fas fa-print me-1"></i>
                                    Imprimer l'acte
                                </button>
                            @endif
                        @endif
                        @if($acte && $acte->retirer == 0 && $acte->approbation_mairie != null)
                            <button type="button"
                                    class="btn btn-warning text-white"
                                    id="btn-open-retrait-modal"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-retrait-acte"
                                    title="Enregistrer le retrait de cet acte">
                                <i class="fas fa-file-export me-1"></i>
                                Retirer l'acte
                            </button>
                        @elseif($acte && $acte->retirer == 1)
                            <span class="badge bg-danger fs-6">
                                <i class="fas fa-check-circle me-1"></i>
                                Acte retiré
                            </span>
                        @endif
                        <a href="{{ route("acteNaissance.index") }}">
                            <button type="button" class="btn btn-info text-white">
                                <i class="fas fa-list me-1"></i>
                                Liste des actes
                            </button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if($acte)
                            <input type="hidden" value="{{ $acte->code_declaration_naissance }}" id="cdn">
                            <input type="hidden" value="{{ $acte->retirer }}" id="retirer">
                            <div id="pdf-viewer-container">
                                <div id="pdf-loading">
                                    <div class="spinner-border text-light" role="status"></div>
                                    <p class="mt-2 mb-0 text-light">Chargement du document...</p>
                                </div>
                                <div id="pdf-error" class="alert alert-danger m-4 d-none"></div>
                                <div id="pdf-canvas-container"></div>
                            </div>
                        @else
                            <div class="alert alert-danger" role="alert">
                                <h4 class="alert-heading">Acte introuvable</h4>
                                <p>L'acte de naissance demandé n'a pas été trouvé dans le système.</p>
                                <hr>
                                <p class="mb-0">Veuillez vérifier le numéro d'acte et réessayer.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- DEBUT MODAL RETRAIT ACTE DE NAISSANCE --}}
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
                <!-- Alerte d'information -->
                <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <div>
                        <strong>Information :</strong> Cette action enregistrera le retrait de l'acte de naissance.
                        L'acte ne pourra plus être imprimé.
                    </div>
                </div>

                <!-- Formulaire de retrait -->
                <form id="form-retrait-acte" novalidate>
                <div class="row">
                        <!-- Numéro de l'acte -->
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

                        <!-- Date de retrait -->
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

                        <!-- Nom de l'intéressé -->
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

                        <!-- Prénom de l'intéressé -->
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

                        <!-- Téléphone de l'intéressé -->
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

                        <!-- Pièce d'identité -->
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

                        <!-- Numéro de pièce d'identité -->
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

                        <!-- Observations -->
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
{{-- FIN MODAL RETRAIT ACTE DE NAISSANCE --}}

@endsection
@section("scripts")
{{-- PDF.js : local en priorité (public/pdfjs/), CDN en repli --}}
<script src="{{ asset('pdfjs/pdf.min.js') }}" onerror="this.onerror=null;this.src='https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js';"></script>
<script>
    // Configurer le worker PDF.js (local en priorité, CDN en repli)
    if (typeof pdfjsLib !== 'undefined') {
        pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('pdfjs/pdf.worker.min.js') }}";
    }

    $(function() {
        @if($acte)
            var cdn = $("#cdn").val();
            if (cdn && typeof pdfjsLib !== 'undefined') {
                var route = "{{ route('acteNaissance.display', ':id') }}";
                route = route.replace(':id', cdn);
                loadPdfWithJs(route);
            } else if (cdn) {
                $("#pdf-loading").addClass("d-none");
                $("#pdf-error").removeClass("d-none").html('<strong>Erreur :</strong> La bibliothèque PDF.js n\'a pas pu être chargée.');
            }
        @endif

        // Bouton Imprimer : déclenche l'impression du document (PDF.js canvas)
        $("#btn-imprimer-acte").on("click", function() {
            if ($(this).prop("disabled")) return;
            window.print();
        });

        // Initialiser le modal de retrait
        initModalRetrait();
    });

    function loadPdfWithJs(url) {
        var loadingEl = document.getElementById("pdf-loading");
        var errorEl = document.getElementById("pdf-error");
        var containerEl = document.getElementById("pdf-canvas-container");

        pdfjsLib.getDocument({
            url: url,
            withCredentials: true
        }).promise.then(function(pdf) {
            loadingEl.style.display = "none";
            var numPages = pdf.numPages;

            function renderPage(pageNum) {
                return pdf.getPage(pageNum).then(function(page) {
                    var scale = 1.5;
                    var viewport = page.getViewport({ scale: scale });
                    var canvas = document.createElement("canvas");
                    var ctx = canvas.getContext("2d");
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;
                    containerEl.appendChild(canvas);

                    return page.render({
                        canvasContext: ctx,
                        viewport: viewport
                    }).promise;
                });
            }

            var renderPromises = [];
            for (var i = 1; i <= numPages; i++) {
                renderPromises.push(renderPage(i));
            }
            return Promise.all(renderPromises);
        }).catch(function(err) {
            loadingEl.style.display = "none";
            errorEl.classList.remove("d-none");
            errorEl.innerHTML = '<strong>Erreur lors du chargement du PDF :</strong> ' + (err.message || err) + '<br><small>URL : ' + url + '</small>';
        });
    }


    // Fonction pour initialiser le modal de retrait
    function initModalRetrait() {
        // Définir la date actuelle
        const now = new Date();
        const dateStr = now.toLocaleDateString('fr-FR', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        $('#date_retrait').val(dateStr);

        // Gestion de l'ouverture du modal
        $('#btn-open-retrait-modal').on('click', function() {
            openRetraitModal();
        });

        // Validation en temps réel
        $('#nom_interesse, #telephone_interesse').on('input', function() {
            validateField($(this));
        });

        // Validation du téléphone
        $('#telephone_interesse').on('blur', function() {
            validatePhone($(this));
        });

        // Gestion du bouton de retrait
        $('#btn-retrait').on('click', function(e) {
            e.preventDefault();
            handleRetraitActe();
        });

        // Réinitialiser le formulaire à la fermeture du modal
        $('#modal-retrait-acte').on('hidden.bs.modal', function() {
            resetRetraitForm();
        });
    }

    // Fonction pour ouvrir le modal de retrait
    function openRetraitModal() {
        @if($acte)
            // Remplir les données de l'acte
            $('#code_acte').val('{{ $acte->niupp }}');
            $('#leniupp').val('{{ $acte->niupp }}');

            // Définir la date actuelle
            const now = new Date();
            const dateStr = now.toLocaleDateString('fr-FR', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            $('#date_retrait').val(dateStr);

            // Réinitialiser les autres champs
            $('#nom_interesse').val('').removeClass('is-valid is-invalid');
            $('#prenom_interesse').val('').removeClass('is-valid is-invalid');
            $('#telephone_interesse').val('').removeClass('is-valid is-invalid');
            $('#piece_identite').val('');
            $('#numero_piece_identite').val('');
            $('#observations_retrait').val('');

            // Effacer les messages d'erreur
            $('.invalid-feedback').text('');

            // Focus sur le premier champ
            setTimeout(() => {
                $('#nom_interesse').focus();
            }, 500);
        @endif
    }

    // Validation d'un champ
    function validateField(field) {
        const value = field.val().trim();
        const fieldId = field.attr('id');
        const errorDiv = $(`#${fieldId}_error`);

        field.removeClass('is-invalid is-valid');
        errorDiv.text('');

        if (field.prop('required') && !value) {
            field.addClass('is-invalid');
            errorDiv.text('Ce champ est obligatoire');
            return false;
        }

        if (value) {
            field.addClass('is-valid');
        }
        return true;
    }

    // Validation spécifique du téléphone
    function validatePhone(field) {
        const phone = field.val().trim();
        const phoneRegex = /^[+]?[0-9\s\-\(\)]{8,20}$/;

        if (phone && !phoneRegex.test(phone)) {
            field.addClass('is-invalid');
            $('#telephone_interesse_error').text('Format de téléphone invalide');
            return false;
        }
        return true;
    }

    // Validation complète du formulaire
    function validateRetraitForm() {
        let isValid = true;

        // Valider les champs obligatoires
        const requiredFields = ['#nom_interesse', '#telephone_interesse'];
        requiredFields.forEach(fieldId => {
            if (!validateField($(fieldId))) {
                isValid = false;
            }
        });

        // Valider le téléphone spécifiquement
        if (!validatePhone($('#telephone_interesse'))) {
            isValid = false;
        }

        return isValid;
    }

    // Gestion du retrait d'acte
    function handleRetraitActe() {
        if (!validateRetraitForm()) {
            flashAlert("Erreur de validation", "error", "Veuillez corriger les erreurs dans le formulaire.");
            return;
        }

        const btn = $('#btn-retrait');
        sifecBtnLoading(btn[0], "Enregistrement...");

        const data = {
            niupp: $('#leniupp').val(),
            nominteresse: $('#nom_interesse').val().trim(),
            prenominteresse: $('#prenom_interesse').val().trim(),
            telephoneinteresse: $('#telephone_interesse').val().trim(),
            piece_identite: $('#piece_identite').val(),
            numero_piece_identite: $('#numero_piece_identite').val().trim(),
            observations: $('#observations_retrait').val().trim(),
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: "{{ route('acteNaissance.retrait') }}",
            type: 'POST',
            data: data,
            success: function(response) {
                if (response.code == "200") {
                    flashAlert("Succès", "success", response.message.reponse || response.message);
                    $('#modal-retrait-acte').modal('hide');
                setTimeout(() => {
                    location.reload();
                    }, 2000);
                } else {
                    handleRetraitError(response);
                }
            },
            error: function(xhr) {
                let errorMessage = 'Erreur lors du retrait de l\'acte';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                flashAlert("Erreur", "error", errorMessage);
            },
            complete: function() {
                sifecBtnReset(btn[0], "Enregistrer le retrait");
            }
        });
    }

    // Gestion des erreurs de retrait
    function handleRetraitError(response) {
        let errorMessage = '';

        if (typeof response.message === 'object') {
            if (response.message.error) {
                errorMessage = response.message.error;
            } else {
                // Construire la liste des erreurs
                const errors = [];
                for (const [key, value] of Object.entries(response.message)) {
                    if (Array.isArray(value)) {
                        errors.push(...value);
                    } else {
                        errors.push(value);
                    }
                }
                errorMessage = errors.length > 0 ? errors.join('<br>') : 'Une erreur est survenue';
            }
        } else {
            errorMessage = response.message || 'Une erreur est survenue';
        }

        flashAlert("Erreur", "error", errorMessage);
    }

    // Réinitialiser le formulaire
    function resetRetraitForm() {
        $('#form-retrait-acte')[0].reset();
        $('#form-retrait-acte .form-control').removeClass('is-valid is-invalid');
        $('#form-retrait-acte .invalid-feedback').text('');
        $('#date_retrait').val('');
    }

    // Fonction legacy pour compatibilité
    function valideRetraitActe(inputs) {
        // Cette fonction est maintenue pour la compatibilité
        handleRetraitActe();
    }
</script>
@endsection
