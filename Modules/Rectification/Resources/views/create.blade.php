
@extends('layout.app')
@section('titre')
Rectification Acte
@endsection
@section("styles")
<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <style>
        @media print {
            body * { visibility: hidden; }
            #pdf-viewer-container-rectif,
            #pdf-viewer-container-rectif * { visibility: visible; }
            #pdf-viewer-container-rectif {
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
            #pdf-loading-rectif, #pdf-error-rectif { display: none !important; }
            #pdf-canvas-container-rectif canvas {
                max-width: 100% !important;
                height: auto !important;
                page-break-after: always;
                box-shadow: none !important;
            }
            #pdf-canvas-container-rectif canvas:last-child { page-break-after: avoid; }
            @page { margin: 1cm; }
        }
        #pdf-viewer-container-rectif {
            position: relative;
            min-height: 500px;
            background: #525659;
        }
        #pdf-canvas-container-rectif {
            text-align: center;
            padding: 20px 0;
        }
        #pdf-canvas-container-rectif canvas {
            display: block;
            margin: 0 auto 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            max-width: 100%;
        }
        #pdf-loading-rectif {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
        }

        /* Compléments charte formulaire SIFEC — rectification */
        .sifec-page-rectification-create .sifec-rectif-step-panel {
            border-top: 1px solid var(--pu-line);
            margin-top: 1.25rem;
            padding-top: 1.35rem;
            background: linear-gradient(180deg, #fafcfb 0%, #fff 12%);
            border-radius: 0 0 var(--pu-radius) var(--pu-radius);
            margin-left: -1.35rem;
            margin-right: -1.35rem;
            margin-bottom: -1.5rem;
            padding-left: 1.35rem;
            padding-right: 1.35rem;
            padding-bottom: 1.5rem;
        }
        .sifec-page-rectification-create .sifec-rectif-hint {
            border-radius: 10px;
            border: 1px solid #b8d4ce;
            background: linear-gradient(135deg, #f0f9f7 0%, #e8f4f1 100%);
            color: #1e4d42;
            font-size: 0.9rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
        }
        .sifec-page-rectification-create .sifec-rectif-table-wrap {
            border: 1px solid var(--pu-line);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(26, 46, 38, 0.06);
        }
        .sifec-page-rectification-create .sifec-rectif-table-wrap table thead th {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--pu-muted);
            font-weight: 700;
            border-bottom-width: 1px;
        }
        .sifec-page-rectification-create .sifec-rectif-actions-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.65rem;
            margin: 1rem 0 0.75rem;
        }
        @media (max-width: 767.98px) {
            .sifec-page-rectification-create .sifec-rectif-step-panel {
                margin-left: -1rem;
                margin-right: -1rem;
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }

        /* SweetAlert2 — confirmation suppression rectification (charte SIFEC) */
        .sifec-swal-rectif-popup {
            border-radius: 16px !important;
            border: 1px solid #e2e8e4 !important;
            box-shadow: 0 22px 55px rgba(26, 46, 38, 0.16) !important;
            padding: 0.25rem 0.5rem 1.25rem !important;
        }
        .sifec-swal-rectif-popup .swal2-title {
            padding: 0.85rem 0.5rem 0.35rem !important;
            font-size: 1.2rem !important;
            font-weight: 700 !important;
            color: #1a2e26 !important;
            letter-spacing: -0.02em;
        }
        .sifec-swal-rectif-popup .swal2-html-container {
            margin: 0.35rem 0.5rem 0.5rem !important;
            font-size: 0.95rem !important;
            line-height: 1.55 !important;
            color: #5c6d66 !important;
        }
        .sifec-swal-rectif-popup .swal2-icon.swal2-warning {
            border-color: #f59e0b !important;
            color: #d97706 !important;
            margin: 1.25rem auto 0.5rem !important;
        }
        .sifec-swal-rectif-popup .swal2-actions {
            margin: 1.15rem auto 0 !important;
            gap: 0.65rem !important;
            flex-wrap: wrap;
            justify-content: center;
        }
        .sifec-swal-rectif-popup .swal2-actions .swal2-confirm.btn-danger {
            background: #b91c1c !important;
            border-color: #b91c1c !important;
            color: #fff !important;
            font-weight: 600 !important;
            padding: 0.5rem 1.15rem !important;
            border-radius: 10px !important;
            box-shadow: 0 2px 10px rgba(185, 28, 28, 0.28);
            transition: background 0.15s, border-color 0.15s, transform 0.1s;
        }
        .sifec-swal-rectif-popup .swal2-actions .swal2-confirm.btn-danger:hover {
            background: #991b1b !important;
            border-color: #991b1b !important;
        }
        .sifec-swal-rectif-popup .swal2-actions .swal2-cancel {
            background: #fff !important;
            border: 1px solid #cfd8d3 !important;
            color: #1a2e26 !important;
            font-weight: 600 !important;
            padding: 0.5rem 1.15rem !important;
            border-radius: 10px !important;
            transition: background 0.15s, border-color 0.15s;
        }
        .sifec-swal-rectif-popup .swal2-actions .swal2-cancel:hover {
            background: #f4f7f5 !important;
            border-color: #b8c4bc !important;
        }
    </style>
@endsection

@section('corps')
<div class="page-sifec-form sifec-page-rectification-create">
    <div class="pu-card">
        <div class="card-header d-flex flex-wrap align-items-start justify-content-between gap-3">
            <div>
                <h4 class="mb-1">Rectification d’acte</h4>
                <p class="mb-0 small text-muted">Renseignez le réquerant et l’adresse, identifiez l’acte, puis saisissez les rubriques à corriger.</p>
            </div>
            <a href="{{ route('rectification.index') }}" class="btn btn-sm pu-btn-back text-decoration-none">
                <i class="fas fa-arrow-left me-1"></i> Liste des rectifications
            </a>
        </div>
        <div class="card-body">
                        <div class="row g-3">
                             <div class="col-12">
                                <div class="ligne"><h4><i class="fas fa-user me-1 opacity-75"></i> Informations du réquerant</h4></div>
                            </div>
                            <div class="mb-2 col-md-3">
                                <label class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"  placeholder="" id="nom_requerant" onkeyup="this.value = this.value.toUpperCase();">
                            </div>
                            <div class="mb-2 col-md-3">
                                <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"  placeholder="" id="prenom_requerant" onkeyup="this.value = this.value.replace(/\b\w/g, function(l) { return l.toUpperCase() })">
                            </div>

                            <div class="mb-2 col-md-3">
                                <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"  placeholder="" id="telephone_requerant">
                            </div>

                            <div class="mb-2 col-md-3">
                                <label class="form-label">Lien de parenté</label>
                                <select class="form-control" id="code_filiation_requerant">
                                    <option selected disabled>Sélectionner</option>
                                    @foreach ($filiations as $item)
                                        <option value="{{ $item->code_filiation }}">{{ $item->lib_filiation }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="ligne"><h4><i class="fas fa-map-marker-alt me-1 opacity-75"></i> Adresse du réquerant</h4></div>
                            </div>
                             <div class="mb-2 col-md-3">
                                <label class="form-label">Département <span class="text-danger">*</span></label>
                                <select class="form-control" id="code_departement">
                                    <option selected disabled>Sélectionner</option>
                                    @foreach ($localites as $item)
                                        <option value="{{ $item->code_localite }}">{{ $item->lib_localite }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2 col-md-3">
                                <label class="form-label">Commune/District <span class="text-danger">*</span></label>
                                <select class="form-control" id="sub_departement">
                                    <option selected disabled>Sélectionner</option>

                                </select>
                            </div>
                            <div class="mb-2 col-md-3">
                                <label class="form-label">Arrondissement/communauté <span class="text-danger">*</span></label>
                                <select class="form-control" id="sub_arrondissement">
                                    <option selected disabled>Sélectionner</option>

                                </select>
                            </div>
                            <div class="mb-2 col-md-3">
                                <label class="form-label">Quartier/village <span class="text-danger">*</span></label>
                                <select class="form-control" id="quartier">
                                    <option selected disabled>Sélectionner</option>

                                </select>
                            </div>

                            <div class="mb-2 col-md-3">
                                <label class="form-label">Type voie<span class="text-danger"></span></label>
                                <select class="form-control" id="domicile_typevoie_requerant">
                                    <option value="">Choisir</option>
                                    <option value="Avenue">Avenue</option>
                                    <option value="Boulevard">Boulevard</option>
                                    <option value="Impasse">Impasse</option>
                                    <option value="Rue">Rue</option>
                                    <option value="Autre">Autre</option>
                                </select>
                            </div>
                            <div class="mb-2 col-md-3">
                                <label class="form-label">N° voie<span class="text-danger"></span></label>
                                <input type="text" class="form-control" id="domicile_numero_requerant" placeholder="N° voie">
                            </div>
                            <div class="mb-2 col-md-3">
                                <label class="form-label">Nom voie<span class="text-danger"></span></label>
                                <input type="text" class="form-control" id="domicile_nomvoie_requerant" placeholder="Nom voie" style="text-transform: capitalize">
                            </div>


                            <div class="col-12">
                                <div class="ligne"><h4><i class="fas fa-file-alt me-1 opacity-75"></i> Acte à rectifier</h4></div>
                            </div>
                            <div class="mb-2 col-md-5 col-lg-4">
                                <label class="form-label">Type d'acte <span class="text-danger">*</span></label>
                                <select class="form-control" id="type_acte">
                                    <option selected disabled>Sélectionner</option>
                                    @foreach ($typesActe as $type)
                                           <option value="{{ $type->code_type_acte }}" class="">{{ $type->lib_type_acte }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2 col-md-4 col-lg-4">
                                <label class="form-label">Numéro de l'acte <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Numéro d'acte" id="numero_acte" autocomplete="off">
                            </div>

                             <div class="mb-2 col-md-3 col-lg-4 d-flex align-items-end">
                                <button type="button" class="btn pu-btn-submit w-100 py-2" id="btn_afficher_form"><i class="fas fa-search me-1"></i> Continuer</button>
                            </div>
                        </div>

                        <div class="row g-3 sifec-rectif-step-panel" id="form_rectification" style="display: none;">
                            <div class="col-12">
                                <div class="sifec-rectif-hint mb-0">
                                    <i class="fas fa-layer-group me-1"></i>
                                    <strong>Étape suivante :</strong> choisissez la rubrique, vérifiez l’ancienne valeur, saisissez la nouvelle valeur puis enregistrez. Vous pouvez enchaîner plusieurs rectifications pour le même acte avant d’imprimer la fiche.
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="ligne"><h4><i class="fas fa-edit me-1 opacity-75"></i> Détail de la rectification</h4></div>
                            </div>
                            <div class="mb-2 col-md-3">
                                <label class="form-label">Rubrique <span class="text-danger">*</span></label>
                                <select class="form-control" id="rubrique">
                                    <option value="" selected disabled>Sélectionner une rubrique</option>
                                    @foreach ($rubriques as $r)
                                        <option value="{{ $r->code_rubrique."-".$r->lib_technique."-".$r->entite_rubrique }}" 
                                                class="{{ $r->code_type_acte }}"
                                                data-entite="{{ $r->entite_rubrique }}">
                                            {{ $r->lib_rubrique }} ({{ ucfirst($r->entite_rubrique) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2 col-md-4 col-lg-3">
                                <label class="form-label">Ancienne valeur</label>
                                <input type="text" class="form-control bg-light" readonly id="anciennce_valeur" placeholder="—">
                            </div>

                             <div class="mb-2 col-md-4 col-lg-3">
                                <label class="form-label">Nouvelle valeur <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nouvelle_valeur" placeholder="Saisie selon la rubrique">
                            </div>

                            <div class="mb-2 col-md-12 col-lg-6">
                                <label class="form-label">Pièce justificative <span class="text-muted fw-normal">(facultatif)</span></label>
                                <input type="file" class="form-control" id="piece_justificative" name="piece_justificative" accept=".pdf,.jpg,.jpeg,.png">
                                <div class="form-text">Formats acceptés : PDF, JPG, PNG</div>
                            </div>

                            <div class="mb-2 col-md-6 col-lg-3 d-flex align-items-end">
                                <button type="button" class="btn pu-btn-submit w-100 py-2" id="btn_enregistrer_rectification"><i class="fas fa-save me-1"></i> Enregistrer</button>
                            </div>

                            <div class="col-12">
                                <div class="sifec-rectif-actions-bar">
                                    <button type="button" class="btn btn-primary px-3 py-2 rounded-3 fw-semibold shadow-sm" id="btn_imprimer_rectification">
                                        <i class="fas fa-print me-1"></i> Imprimer la fiche
                                    </button>
                                    <span class="small text-muted">Disponible dès qu’au moins une ligne figure dans le tableau ci-dessous.</span>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="sifec-rectif-table-wrap">
                                    <div class="table-responsive">
                                        <table id="example" class="display table table-hover table-striped align-middle mb-0" style="min-width: 845px">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Rubrique</th>
                                                    <th>Ancienne valeur</th>
                                                    <th>Nouvelle valeur</th>
                                                    <th class="text-end" style="width: 9rem;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody_rectification">
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted py-4">Aucune rectification enregistrée</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
        </div>
    </div>
</div>

{{-- Modal affichage PDF fiche de rectification (PDF.js comme acte.blade.php) --}}
<div class="modal fade modal-utilisateur-sifec" id="modal-pdf-rectification" tabindex="-1" aria-labelledby="modalPdfRectificationLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPdfRectificationLabel"><i class="fas fa-file-pdf text-danger me-2"></i>Fiche de rectification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body p-0">
                <div id="pdf-viewer-container-rectif">
                    <div id="pdf-loading-rectif">
                        <div class="spinner-border text-light" role="status"></div>
                        <p class="mt-2 mb-0 text-light">Chargement du document...</p>
                    </div>
                    <div id="pdf-error-rectif" class="alert alert-danger m-4 d-none"></div>
                    <div id="pdf-canvas-container-rectif"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary rounded-3 fw-semibold" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary rounded-3 fw-semibold px-3" id="btn-print-pdf-rectif">
                    <i class="fas fa-print me-1"></i> Imprimer
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
@section("scripts")
<!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>
{{-- PDF.js : même approche que acte/acte.blade.php --}}
    <script src="{{ asset('pdfjs/pdf.min.js') }}" onerror="this.onerror=null;this.src='https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js';"></script>
    <script>
    if (typeof pdfjsLib !== 'undefined') {
        pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('pdfjs/pdf.worker.min.js') }}";
    }
    function loadPdfWithJsRectif(url) {
        var loadingEl = document.getElementById("pdf-loading-rectif");
        var errorEl = document.getElementById("pdf-error-rectif");
        var containerEl = document.getElementById("pdf-canvas-container-rectif");
        if (!containerEl) return;
        containerEl.innerHTML = '';
        if (errorEl) { errorEl.classList.add("d-none"); errorEl.innerHTML = ''; }
        if (loadingEl) loadingEl.style.display = "block";
        if (typeof pdfjsLib === 'undefined') {
            if (loadingEl) loadingEl.style.display = "none";
            if (errorEl) { errorEl.classList.remove("d-none"); errorEl.innerHTML = '<strong>Erreur :</strong> La bibliothèque PDF.js n\'a pas pu être chargée.'; }
            return;
        }
        pdfjsLib.getDocument({ url: url, withCredentials: true }).promise.then(function(pdf) {
            if (loadingEl) loadingEl.style.display = "none";
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
                    return page.render({ canvasContext: ctx, viewport: viewport }).promise;
                });
            }
            var renderPromises = [];
            for (var i = 1; i <= numPages; i++) renderPromises.push(renderPage(i));
            return Promise.all(renderPromises);
        }).catch(function(err) {
            if (loadingEl) loadingEl.style.display = "none";
            if (errorEl) {
                errorEl.classList.remove("d-none");
                errorEl.innerHTML = '<strong>Erreur lors du chargement du PDF :</strong> ' + (err.message || err) + '<br><small>URL : ' + url + '</small>';
            }
        });
    }

        $(function(){

            $("#form_rectification").hide();
             $("#type_acte").focus(function(){
                $("#rubrique option").show();
            });

            // Gestionnaire du formulaire de rectification
            $("#btn_afficher_form").on("click", function(){
                var numeroActe = $("#numero_acte").val().trim();
                var typeacte = $("#type_acte").val();

                // Réinitialiser les champs
                $("#tbody_rectification").empty();
                $("#rubrique").val("").trigger("change");
                $("#anciennce_valeur").val("");
                $("#nouvelle_valeur").val("").attr("type", "tel");

                if (numeroActe === "") {
                    flashAlert("Réponse", "error", "Veuillez renseigner le numéro d'acte");
                    $("#numero_acte").focus();
                    return;
                }
                if (typeacte === "" || typeacte === null) {
                    flashAlert("Réponse", "error", "Veuillez sélectionner le type d'acte");
                    $("#type_acte").focus();
                    return;
                }
                // Appel de la fonction de recherche de l'acte
                rechercherActe(numeroActe, typeacte);
            });

            // Gestionnaire du filtre des rubriques selon le type d'acte
            $("#type_acte").change(function(){
                $("#form_rectification").hide();
                $("#rubrique").val("").trigger("change");
                $("#anciennce_valeur").val("");
                $("#nouvelle_valeur").val("").attr("type", "tel");
                var typeActe = $(this).val();
                if (typeActe === "" || typeActe === null) {
                    $("#rubrique option").show();
                    $("#rubrique option[value='']").prop("selected", true);
                } else {
                    // Afficher toutes les options puis masquer celles qui ne correspondent pas
                    $("#rubrique option").show();
                    $("#rubrique option[class!='']").each(function() {
                        if ($(this).attr("class") !== typeActe) {
                            $(this).hide();
                        }
                    });
                    // Réinitialiser la sélection
                    $("#rubrique option[value='']").prop("selected", true);
                }
            });
            // Récupération de l'ancienne valeur de la rubrique sélectionnée
            $("#rubrique").change(function(){
                var rubrique = $(this).val();
                var type_acte = $("#type_acte").val();
                var numero_acte = $("#numero_acte").val();

                // Réinitialiser les champs
                $("#anciennce_valeur").val("");
                $("#nouvelle_valeur").val("").attr("type", "tel");

                if (!rubrique || rubrique === "" || !type_acte || !numero_acte) {
                    return;
                }

                var route = "{{ route('rectification.recup-old-value') }}";
                $.ajax({
                    type: "POST",
                    url: route,
                    data: {
                        rubrique: rubrique,
                        numero_acte: numero_acte,
                        type_acte: type_acte,
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: "text",
                    success: function(reponse) {
                        var ancienneVal = (reponse || "").trim();
                        $("#anciennce_valeur").val(ancienneVal);
                        if (ancienneVal === "") {
                            flashAlert("Information", "warning", "Aucune valeur trouvée pour cette rubrique. Vous pouvez saisir une nouvelle valeur.");
                        }
                        // Définir le type de champ pour nouvelle_valeur selon lib_technique
                        var parts = rubrique.split("-");
                        if (parts.length >= 2) {
                            var libTechnique = parts[1];
                            var $nouvelleValeur = $("#nouvelle_valeur");
                            if (libTechnique === "date_naissance") {
                                $nouvelleValeur.attr("type", "date");
                            } else {
                                $nouvelleValeur.attr("type", "text");
                            }
                            // Focus sur le champ nouvelle valeur
                            $nouvelleValeur.focus();
                        }
                    },
                    error: function(xhr) {
                        var msg = "Erreur lors de la récupération de l'ancienne valeur. Veuillez vérifier que l'acte et la rubrique sont corrects.";
                        flashAlert("Erreur", "error", msg);
                        $("#anciennce_valeur").val("");
                        $("#nouvelle_valeur").val("").attr("type", "tel");
                    }
                });
            });

            // enregistrement de rectification btn_enregistrer_rectification
            $("#btn_enregistrer_rectification").on("click",function(){
                var route = "{{ route('rectification.store') }}";
                var rubrique = $("#rubrique").val();
                var typeActe = $("#type_acte").val();
                var numeroActe = $("#numero_acte").val();
                var anienneValeur = $("#anciennce_valeur").val();
                var newValeur = $("#nouvelle_valeur").val();
                //recupération des informations du réquerant
                var filiationRequerant = $("#code_filiation_requerant").val();
                var nomRequerant = $("#nom_requerant").val();
                var prenomRequerant = $("#prenom_requerant").val();
                var telephoneRequerant = $("#telephone_requerant").val();

                var communeDistrictRequerant = $("#sub_departement option:selected").text();
                var arrondRequerant = $("#sub_arrondissement option:selected").text();
                var quartierRequerant = $("#quartier option:selected").text();
                var domicileTypeVoieRequerant = $("#domicile_typevoie_requerant").val();
                var domicileNumeroRequerant = $("#domicile_numero_requerant").val();
                var domicileNomVoieRequerant = $("#domicile_nomvoie_requerant").val();


                // Vérification des champs requis
                if(typeActe === "" || numeroActe === "" || rubrique === "" || newValeur === ""){
                    flashAlert("Réponse", "error", "Veuillez renseigner le type d'acte, le numéro d'acte, la rubrique et la nouvelle valeur");
                    return;
                }
                // L'ancienne valeur peut être vide si le champ n'existe pas encore dans l'acte
                if(anienneValeur === ""){
                    anienneValeur = "-"; // Valeur par défaut pour indiquer qu'il n'y avait pas de valeur précédente
                }
                if(nomRequerant === "" || prenomRequerant === "" || telephoneRequerant === ""){
                    flashAlert("Réponse", "error", "Veuillez renseigner les informations du réquerant (nom, prénom, téléphone)");
                    return;
                }
                if(communeDistrictRequerant === "" || arrondRequerant === "" || quartierRequerant === ""){
                    flashAlert("Réponse", "error", "Veuillez renseigner l'adresse complète du réquerant (commune, arrondissement, quartier)");
                    return;
                }
                // Les champs de domicile sont optionnels selon les règles métier
                // if(domicileTypeVoieRequerant === "" || domicileNumeroRequerant === "" || domicileNomVoieRequerant === ""){
                //     flashAlert("Réponse", "error", "Veuillez renseigner les informations du domicile du réquerant");
                //     return;
                // }
                // Vérification que la rubrique est sélectionnée
                if(rubrique == null){
                    flashAlert("Réponse","error","Veuillez sélectionner une rubrique");
                    return;
                }
                // Vérification que le numéro d'acte est renseigné
                if(numeroActe == ""){
                    flashAlert("Réponse","error","Veuillez renseigner le numéro de l'acte");
                    return;
                }

                // Vérification que la nouvelle valeur est différente de l'ancienne valeur (si ancienne valeur existe)
                if(anienneValeur !== "-" && anienneValeur === newValeur){
                    flashAlert("Réponse", "error", "La nouvelle valeur doit être différente de l'ancienne valeur");
                    return;
                }

                 var librubrique = $("#rubrique option:selected").text();

                storeRectification(numeroActe,typeActe,anienneValeur,newValeur,rubrique,librubrique,nomRequerant,prenomRequerant,filiationRequerant,telephoneRequerant,communeDistrictRequerant,arrondRequerant,quartierRequerant,domicileTypeVoieRequerant,
                    domicileNumeroRequerant,domicileNomVoieRequerant);

            });

            //SUPPRESSION DE LA RECTIFICATION
            $(document).on("click",".btn-delete-rubrique",function(){
                var code = $(this).attr("code");

               //appel de la fonction de confirmation
                 deleteRectification(code);
            });


            // Impression de la rectification
            $(document).on("click", "#btn_imprimer_rectification", function() {
                var numeroActe = $("#numero_acte").val().trim();
                var typeActe = $("#type_acte").val();

                if (numeroActe === "") {
                    flashAlert("Réponse", "error", "Veuillez renseigner le numéro de l'acte pour l'impression.");
                    return;
                }
                if (typeActe === "" || typeActe === null) {
                    flashAlert("Réponse", "error", "Veuillez sélectionner le type d'acte pour l'impression.");
                    return;
                }

                // Vérifier qu'il y a au moins une rectification enregistrée
                var nbRectifications = $("#tbody_rectification tr").not(":has(td[colspan])").length;
                if (nbRectifications === 0) {
                    flashAlert("Réponse", "warning", "Aucune rectification enregistrée. Veuillez enregistrer au moins une rectification avant d'imprimer.");
                    return;
                }

                // Afficher le PDF dans le modal avec PDF.js (comme acte.blade.php)
                var route = "{{ route('rectification.etat', ':numeroActe') }}";
                route = route.replace(':numeroActe', encodeURIComponent(numeroActe));
                var modal = new bootstrap.Modal(document.getElementById('modal-pdf-rectification'));
                modal.show();
                document.getElementById('modal-pdf-rectification').addEventListener('shown.bs.modal', function onShown() {
                    document.getElementById('modal-pdf-rectification').removeEventListener('shown.bs.modal', onShown);
                    loadPdfWithJsRectif(route);
                }, { once: true });
            });

            // Imprimer le PDF affiché dans le modal (impression du contenu canvas)
            $(document).on("click", "#btn-print-pdf-rectif", function() {
                window.print();
            });

            var localiteEnfantsUrlTpl = @json(route('localite.children', ['code' => '__PARENT_CODE__']));

            //permet de remplir le select des communes en fonction du département selectionné
            $("#code_departement").change(function(){
                var codeDepartement = $(this).val();
                var route = localiteEnfantsUrlTpl.replace('__PARENT_CODE__', encodeURIComponent(codeDepartement)) + '?types=' + encodeURIComponent('TPLOC_0003,TPLOC_0002');
                $.get(route, function(response) {
                    $("#sub_departement").empty();
                    $("#sub_departement").append("<option selected disabled>Sélectionner</option>");
                    response.forEach(function(item){
                        $("#sub_departement").append("<option value='"+item.code_localite+"'>"+item.lib_localite+"</option>");
                    });
                });
            });

            //permet de remplir le select des arrondissements en fonction de la commune selectionnée
            $("#sub_departement").change(function(){
                var codeCommune = $(this).val();
                var route = localiteEnfantsUrlTpl.replace('__PARENT_CODE__', encodeURIComponent(codeCommune)) + '?types=' + encodeURIComponent('TPLOC_0004,TPLOC_0005');
                $.get(route, function(response) {
                    $("#sub_arrondissement").empty();
                    $("#sub_arrondissement").append("<option selected disabled>Sélectionner</option>");
                    response.forEach(function(item){
                        $("#sub_arrondissement").append("<option value='"+item.code_localite+"'>"+item.lib_localite+"</option>");
                    });
                });
            });

            //permet de remplir le select des quartiers en fonction de l'arrondissement selectionné
            $("#sub_arrondissement").change(function(){
                var codeArrondissement = $(this).val();
                var route = localiteEnfantsUrlTpl.replace('__PARENT_CODE__', encodeURIComponent(codeArrondissement)) + '?types=' + encodeURIComponent('TPLOC_0006,TPLOC_0007');
                $.get(route, function(response) {
                    $("#quartier").empty();
                    $("#quartier").append("<option selected disabled>Sélectionner</option>");
                    response.forEach(function(item){
                        $("#quartier").append("<option value='"+item.code_localite+"'>"+item.lib_localite+"</option>");
                    });
                });
            });

    });



    function rechercherActe(numeroacte, typeacte){
        var route = "{{ route('rectification.get.acte') }}";
        var data = {
            numero_acte: (numeroacte || '').toString().trim(),
            type_acte: (typeacte || '').toString().trim(),
            _token: "{{ csrf_token() }}"
        };
        var btnContinuer = document.getElementById('btn_afficher_form');
        if (btnContinuer) {
            sifecBtnLoading(btnContinuer, 'Recherche...');
        }
        $.ajax({
            type: "POST",
            url: route,
            data: data,
            dataType: "json",
            success: function (response) {
                if (response.code == "200"){
                    $("#form_rectification").show(300);
                    getDetailsRectification(data.numero_acte, data.type_acte);
                }
                if (response.code == "180" || response.code == "400"){
                    flashAlert("Réponse", "error", response.message);
                    $("#form_rectification").hide(300);
                }
                if (response.code == "403"){
                    flashAlert("Rectification non autorisée", "error", response.message);
                    $("#form_rectification").hide(300);
                }
            },
            error: function(xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Erreur lors de la recherche de l'acte.";
                flashAlert("Erreur", "error", msg);
                $("#form_rectification").hide(300);
            },
            complete: function() {
                if (btnContinuer) {
                    sifecBtnReset(btnContinuer, 'Continuer');
                }
            }
        });
    }

    function storeRectification(numeroActe,typeActe,anienneValeur,newValeur,rubrique,librubrique,nomRequerant,prenomRequerant,filiationRequerant,telephoneRequerant,communeDistrictRequerant,arrondRequerant,quartierRequerant,domicileTypeVoieRequerant,
                    domicileNumeroRequerant,domicileNomVoieRequerant){
        var route = "{{ route('rectification.store') }}";
        var formData = new FormData();
        formData.append("numero_acte", numeroActe);
        formData.append("type_acte", typeActe);
        formData.append("old_value", anienneValeur);
        formData.append("nouvelle_valeur", newValeur);
        formData.append("rubrique", rubrique);
        formData.append("lib_rubrique", librubrique);
        formData.append("nom_requerant", nomRequerant);
        formData.append("prenom_requerant", prenomRequerant);
        formData.append("filiation_requerant", filiationRequerant || "");
        formData.append("telephone_requerant", telephoneRequerant);
        formData.append("commune_district_requerant", communeDistrictRequerant);
        formData.append("arrond_requerant", arrondRequerant);
        formData.append("quartier_requerant", quartierRequerant);
        formData.append("domicile_type_voie_requerant", domicileTypeVoieRequerant || "");
        formData.append("domicile_numero_requerant", domicileNumeroRequerant || "");
        formData.append("domicile_nom_voie_requerant", domicileNomVoieRequerant || "");
        formData.append("_token", "{{ csrf_token() }}");
        var fileInput = document.getElementById("piece_justificative");
        if (fileInput && fileInput.files && fileInput.files[0]) {
            formData.append("piece_justificative", fileInput.files[0]);
        }
        var btnEnregistrer = document.getElementById('btn_enregistrer_rectification');
        if (btnEnregistrer) {
            sifecBtnLoading(btnEnregistrer, 'Enregistrement...');
        }
        $.ajax({
            type: "POST",
            url: route,
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) {
                console.log(response.data)
                //
                if(response.code == "200"){
                   flashAlert("Réponse","success",response.message);
                    getDetailsRectification(numeroActe, typeActe);
                    $("#piece_justificative").val("");
                }
                if(response.code == "500" || response.code == "400"){
                    flashAlert("Réponse","error",response.message);
                }
                if(response.code == "403"){
                    flashAlert("Rectification non autorisée", "error", response.message);
                }
            },
            error: function(xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Erreur lors de l'enregistrement.";
                flashAlert("Erreur", "error", msg);
            },
            complete: function() {
                if (btnEnregistrer) {
                    sifecBtnReset(btnEnregistrer, 'Enregistrer');
                }
            }
        });
    }

    //fonction de recuperation des details de la rectification d'un acte à partir du numero de l'acte saisie
    function getDetailsRectification(numeroActe,typeActe){
        var route = "{{ route('rectification.get.details') }}";
        var data = {numero_acte:numeroActe, type_acte:typeActe, _token: "{{ csrf_token() }}"};
        $.ajax({
            type: "POST",
            url: route,
            data: data,
            dataType: "json",
            success: function (response) {
                console.log(response);
                if(response.code == "200"){
                    $("#form_rectification").show(300);
                    $("#tbody_rectification").empty();
                    if (response.data && response.data.length > 0) {
                        response.data.forEach(function(item){
                            var ancienneVal = (item.ancienne_valeur && item.ancienne_valeur !== '-') ? item.ancienne_valeur : '-';
                            $("#tbody_rectification").append(
                                "<tr>" +
                                "<td><strong>" + (item.lib_rubrique || '-') + "</strong></td>" +
                                "<td>" + ancienneVal + "</td>" +
                                "<td><strong class='text-success'>" + (item.nouvelle_valeur || '-') + "</strong></td>" +
                                "<td class='text-end'>" +
                                "<button type='button' class='btn btn-outline-danger btn-sm btn-delete-rubrique' " +
                                "code='" + item.code_detail_rectification + "' " +
                                "onclick='deleteRectification(\"" + item.code_detail_rectification + "\")' " +
                                "title='Supprimer cette rectification'>" +
                                "<i class='fa fa-trash'></i> Supprimer" +
                                "</button>" +
                                "</td>" +
                                "</tr>"
                            );
                        });
                    } else {
                        $("#tbody_rectification").append(
                            "<tr><td colspan='4' class='text-center text-muted py-3'><em>Aucune rectification enregistrée pour cet acte</em></td></tr>"
                        );
                    }
                } else if (response.code == "500" || response.code == "400") {
                    flashAlert("Réponse", "error", response.message || "Erreur lors de la récupération des détails");
                }
            }
        });
    }



    // Formatage automatique de la nouvelle valeur selon lib_technique
    $("#nouvelle_valeur").on("input keyup", function(){
        var rubrique = $("#rubrique").val();
        if (!rubrique || rubrique === "") {
            return;
        }
        var parts = rubrique.split("-");
        if (parts.length < 2) {
            return;
        }
        var libTechnique = parts[1];
        var $input = $(this);
        var valeur = $input.val();

        // Formatage selon le type de champ
        if (libTechnique === "nom" || libTechnique === "lieu_naissance" || libTechnique === "nationalite") {
            $input.attr("type", "text");
            $input.val(valeur.toUpperCase());
        } else if (libTechnique === "prenom") {
            $input.attr("type", "text");
            // Première lettre de chaque mot en majuscule
            $input.val(valeur.replace(/\b\w/g, function(l) { return l.toUpperCase(); }));
        } else if (libTechnique === "sexe") {
            $input.attr("type", "text");
            // Formatage : Masculin ou Féminin (première lettre majuscule)
            var valUpper = valeur.toUpperCase();
            if (valUpper === "M" || valUpper.startsWith("MASC")) {
                $input.val("Masculin");
            } else if (valUpper === "F" || valUpper.startsWith("FEM")) {
                $input.val("Féminin");
            } else {
                $input.val(valeur.replace(/\b\w/g, function(l) { return l.toUpperCase(); }));
            }
        } else if (libTechnique === "date_naissance") {
            $input.attr("type", "date");
            // Pas de formatage automatique pour les dates
        } else {
            $input.attr("type", "text");
        }
    });


    /**
     * Confirmation SweetAlert2 — style SIFEC (boutons Bootstrap, carte arrondie).
     * Paramètres : titre, icon (warning|question|info), message, callback, opts optionnel
     * (confirmText, cancelText, destructive).
     */
    function flashAlertConfirmationWithCallback(titre, icon, message, callback, opts) {
        opts = opts || {};
        var confirmText = opts.confirmText || 'Confirmer';
        var cancelText = opts.cancelText || 'Annuler';
        var destructive = opts.destructive !== false;
        Swal.fire({
            title: titre,
            text: message,
            icon: icon,
            iconColor: icon === 'warning' ? '#d97706' : undefined,
            showCancelButton: true,
            focusCancel: true,
            reverseButtons: true,
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
            buttonsStyling: false,
            width: '28rem',
            padding: '1.25rem',
            customClass: {
                popup: 'sifec-swal-rectif-popup',
                actions: 'sifec-swal-rectif-actions',
                confirmButton: destructive ? 'swal2-confirm btn btn-danger' : 'swal2-confirm btn btn-success',
                cancelButton: 'swal2-cancel'
            }
        }).then(function (result) {
            if (result.isConfirmed) {
                callback();
            }
        });
    }


    // Fonction de suppression de la rectification
    function deleteRectification(code){
        flashAlertConfirmationWithCallback(
            "Supprimer cette rectification ?",
            "warning",
            "Cette action retirera la ligne du tableau. Vous pourrez en ajouter d’autres tant que la fiche n’est pas finalisée.",
            function() {
                var route = "{{ route('rectification.destroy',':id') }}";
                route = route.replace(':id', code);
                $.ajax({
                    type: "DELETE",
                    url: route,
                    data: {_token: "{{ csrf_token() }}"},
                    dataType: "json",
                    success: function (response) {
                        if(response.code == "200"){
                            flashAlert("Réponse", "success", response.message);
                            // Supprimer la ligne du tableau
                            $("button[code='"+code+"']").closest("tr").remove();
                            // Si le tableau est vide, afficher un message
                            if ($("#tbody_rectification tr").length === 0) {
                                $("#tbody_rectification").append(
                                    "<tr><td colspan='4' class='text-center text-muted'>Aucune rectification enregistrée</td></tr>"
                                );
                            }
                            // Recharger les détails pour mettre à jour
                            var numeroActe = $("#numero_acte").val().trim();
                            var typeActe = $("#type_acte").val();
                            if (numeroActe && typeActe) {
                                getDetailsRectification(numeroActe, typeActe);
                            }
                        } else {
                            flashAlert("Réponse", "error", response.message || "Erreur lors de la suppression");
                        }
                    },
                    error: function(xhr) {
                        var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Erreur lors de la suppression de la rectification.";
                        flashAlert("Erreur", "error", msg);
                    }
                });
            },
            { confirmText: 'Oui, supprimer', cancelText: 'Annuler', destructive: true }
        );
    }






</script>
@endsection
