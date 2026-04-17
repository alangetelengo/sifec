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
                        @if($acte && $acte->retirer == 0 && $acte->approbation_mairie != null && \Illuminate\Support\Facades\Gate::any([
                            'module.acteNaissance.retrait.depuisConsultationCEC',
                            'module.acteNaissance.generate',
                            'module.acteNaissance.signature',
                        ]))
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

@if($acte && \Illuminate\Support\Facades\Gate::any([
    'module.acteNaissance.retrait.depuisConsultationCEC',
    'module.acteNaissance.generate',
    'module.acteNaissance.signature',
]))
    @include('naissance::acte.partials.modal-retrait-acte')
@endif

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
    });

    function loadPdfWithJs(url) {
        var loadingEl = document.getElementById("pdf-loading");
        var errorEl = document.getElementById("pdf-error");
        var containerEl = document.getElementById("pdf-canvas-container");

        // GET explicite : détecter texte d’erreur (403/404/500) au lieu de laisser PDF.js parser du HTML
        fetch(url, { credentials: 'same-origin', headers: { 'Accept': 'application/pdf,*/*' } })
            .then(function(res) {
                var ct = (res.headers.get('Content-Type') || '').toLowerCase();
                if (!res.ok || ct.indexOf('application/pdf') === -1) {
                    return res.text().then(function(body) {
                        var msg = (body || '').trim() || ('Erreur HTTP ' + res.status);
                        throw new Error(msg);
                    });
                }
                return res.arrayBuffer();
            })
            .then(function(arrayBuffer) {
                return pdfjsLib.getDocument({ data: arrayBuffer }).promise;
            })
            .then(function(pdf) {
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
            })
            .catch(function(err) {
            loadingEl.style.display = "none";
            errorEl.classList.remove("d-none");
            errorEl.innerHTML = '<strong>Impossible d’afficher l’acte :</strong> ' + (err.message || err) + '<br><small>URL : ' + url + '</small>';
        });
    }
</script>
@if($acte && \Illuminate\Support\Facades\Gate::any([
    'module.acteNaissance.retrait.depuisConsultationCEC',
    'module.acteNaissance.generate',
    'module.acteNaissance.signature',
]))
    @include('naissance::acte.partials.retrait-acte-form-scripts', ['acte' => $acte])
@endif
@endsection
