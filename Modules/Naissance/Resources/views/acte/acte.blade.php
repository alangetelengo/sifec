@extends('layout.app')
@section('titre')
Acte de naissance
@endsection
@section("styles")
<style>
    @media print {
        /* Forcer tout le contenu sur une seule page */
        * {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        /* Masquer les éléments de navigation */
        .k-toolbar,
        .k-pdf-viewer-toolbar,
        .k-pdf-viewer-navigation,
        .k-pdf-viewer-controls {
            display: none !important;
        }

        /* Forcer le PDF viewer à une seule page */
        .k-pdf-viewer {
            height: 100vh !important;
            max-height: 100vh !important;
            overflow: hidden !important;
            page-break-inside: avoid !important;
        }

        /* Ajuster le contenu PDF */
        .k-pdf-viewer .k-content,
        .k-pdf-viewer-content,
        .k-pdf-viewer .k-pdf-page,
        .k-pdf-viewer canvas {
            max-height: 100vh !important;
            height: auto !important;
            width: 100% !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        /* Masquer les en-têtes et pieds de page du navigateur */
        @page {
            margin: 0 !important;
            size: A4 !important;
        }

        /* Forcer le body à une seule page */
        body {
            margin: 0 !important;
            padding: 0 !important;
            height: 100vh !important;
            overflow: hidden !important;
        }

        /* Masquer les éléments non essentiels */
        .card-header,
        .btn,
        .modal,
        .alert {
            display: none !important;
        }

        /* Ajuster le conteneur principal */
        .card,
        .card-body {
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            box-shadow: none !important;
        }
    }

    /* Styles pour l'affichage normal */
    .k-pdf-viewer {
        border: 1px solid #ddd;
        border-radius: 4px;
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
                        @if($acte && $acte->retirer == 0 && $acte->approbation_mairie !=null)
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
                            <div id="pdfViewer"></div>
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
                        L'acte ne pourra plus être modifié après le retrait.
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

<!---- debut inclusion kendoUi --->
<link href="{{ asset('kendo-library/kendo-style/kendo.common.min.css')}}" rel="stylesheet">
<link href="{{ asset('kendo-library/kendo-style/kendo.blueopal.min.css')}}" rel="stylesheet">
{{-- <script src="{{ asset('kendo-library/kendo-js/jquery.min.js')}}"></script> --}}
<script src="{{ asset('kendo-library/kendo-js/kendo.all.min.js')}}"></script>

 <!---- fin inclusion kendoUi --->
 <script>
    $(function() {
        @if($acte)
            var cdn = $("#cdn").val();
            var retirer = $("#retirer").val();

            if(cdn) {
                var route = "{{ route('acteNaissance.display', ':id') }}";
                route = route.replace(':id', cdn);

                console.log("Tentative de chargement du PDF depuis:", route);

                // Vérifier que la route est accessible avant de charger le PDF Viewer
                $.ajax({
                    url: route,
                    method: 'HEAD',
                    success: function(data, textStatus, jqXHR) {
                        console.log("Route PDF accessible, Content-Type:", jqXHR.getResponseHeader('Content-Type'));
                        loadPDFViewer(route, retirer);
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.error("Erreur lors de la vérification de la route PDF:", textStatus, errorThrown);
                        var errorMsg = 'Impossible de charger le PDF';
                        if (xhr.status === 404) {
                            errorMsg = 'Acte introuvable (404)';
                        } else if (xhr.status === 500) {
                            errorMsg = 'Erreur serveur lors de la génération du PDF (500)';
                        } else if (xhr.status === 0) {
                            errorMsg = 'Impossible de se connecter au serveur';
                        }
                        $("#pdfViewer").html(
                            '<div class="alert alert-danger m-4" role="alert">' +
                            '<h4 class="alert-heading">Erreur lors du chargement du PDF</h4>' +
                            '<p>' + errorMsg + '</p>' +
                            '<hr>' +
                            '<p class="mb-0">Statut HTTP: ' + (xhr.status || 'Inconnu') + '</p>' +
                            '<p class="mb-0 mt-2">Veuillez vérifier les logs ou contacter l\'administrateur.</p>' +
                            '<p class="mb-0 mt-2"><small>Route: ' + route + '</small></p>' +
                            '</div>'
                        );
                    }
                });
            }

            function loadPDFViewer(route, retirer) {
                $.when(
                    $.getScript("{{ asset('kendo-library/pdf.js') }}"),
                    $.getScript("{{ asset('kendo-library/kendo-style/worker.js') }}")
                )
                .done(function () {
                    window.pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('kendo-library/kendo-style/worker.js') }}";
                }).then(function(){
                    console.log("Initialisation du PDF Viewer pour:", route);
                    $("#pdfViewer").kendoPDFViewer({
                        pdfjsProcessing: {
                            file: route,
                            error: function(e) {
                                console.error("Erreur lors du traitement du PDF:", e);
                                var errorMessage = 'Erreur inconnue';
                                if (e.error) {
                                    errorMessage = e.error.message || e.error.toString();
                                } else if (e.xhr) {
                                    errorMessage = 'Erreur HTTP: ' + (e.xhr.status || 'Inconnu');
                                }

                                // Afficher un message d'erreur à l'utilisateur
                                $("#pdfViewer").html(
                                    '<div class="alert alert-danger m-4" role="alert">' +
                                    '<h4 class="alert-heading">Erreur lors du traitement du PDF</h4>' +
                                    '<p>Le PDF n\'a pas pu être traité par le visualiseur.</p>' +
                                    '<hr>' +
                                    '<p class="mb-0"><strong>Erreur:</strong> ' + errorMessage + '</p>' +
                                    '<p class="mb-0 mt-2">Veuillez vérifier les logs dans <code>storage/logs/sifec.log</code> ou contacter l\'administrateur.</p>' +
                                    '<p class="mb-0 mt-2"><small>Route: ' + route + '</small></p>' +
                                    '<p class="mb-0 mt-2"><small>Code déclaration: ' + cdn + '</small></p>' +
                                    '</div>'
                                );
                            }
                        },
                        width: "100%",
                        height: 1200
                    });
                    $('a[title="Download"]').hide();
                    $('a[title="Open"]').hide();
                    if(retirer == 0){
                        $('a[title="Print"]').show();
                        $(".k-toolbar").show();
                    }else{
                        $('a[title="Print"]').hide();
                        $(".k-toolbar").hide();
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Erreur lors du chargement des scripts PDF:", textStatus, errorThrown);
                    $("#pdfViewer").html(
                        '<div class="alert alert-danger m-4" role="alert">' +
                        '<h4 class="alert-heading">Erreur lors du chargement des bibliothèques PDF</h4>' +
                        '<p>Impossible de charger les bibliothèques nécessaires pour afficher le PDF.</p>' +
                        '<hr>' +
                        '<p class="mb-0">Erreur: ' + textStatus + ' - ' + errorThrown + '</p>' +
                        '<p class="mb-0 mt-2">Veuillez rafraîchir la page ou contacter l\'administrateur.</p>' +
                        '</div>'
                    );
                });
            }

        @endif

        // Initialiser le modal de retrait
        initModalRetrait();
    });


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
        const btnText = btn.find('.btn-text');
        const spinner = btn.find('.spinner-border');

        // Désactiver le bouton et afficher le spinner
        btn.prop('disabled', true);
        btnText.addClass('d-none');
        spinner.removeClass('d-none');

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
                // Réactiver le bouton
                btn.prop('disabled', false);
                btnText.removeClass('d-none');
                spinner.addClass('d-none');
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
