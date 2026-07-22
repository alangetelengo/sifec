@extends('layout.app')
@section('titre')
Copie d'acte de mariage
@endsection
@section("styles")
<style>
    @media print {
        body * { visibility: hidden; }
        #pdf-viewer-container, #pdf-viewer-container * { visibility: visible; }
        #pdf-viewer-container {
            position: fixed !important; left: 0 !important; top: 0 !important;
            width: 100% !important; height: 100% !important; margin: 0 !important;
            padding: 20px !important; background: #fff !important; z-index: 9999 !important;
        }
        #pdf-loading, #pdf-error { display: none !important; }
        #pdf-canvas-container canvas {
            max-width: 100% !important; height: auto !important;
            page-break-after: always; box-shadow: none !important;
        }
        #pdf-canvas-container canvas:last-child { page-break-after: avoid; }
        @page { margin: 1cm; }
    }
    #pdf-viewer-container {
        position: relative; min-height: 800px; background: #525659;
    }
    #pdf-canvas-container { text-align: center; padding: 20px 0; }
    #pdf-canvas-container canvas {
        display: block; margin: 0 auto 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3); max-width: 100%;
    }
    #pdf-loading {
        position: absolute; top: 50%; left: 50%;
        transform: translate(-50%, -50%); color: #fff;
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
                        @if($acte)
                            <button type="button" class="btn btn-primary" id="btn-imprimer-copie" title="Imprimer la copie d'acte de mariage">
                                <i class="fas fa-print me-1"></i>Imprimer la copie
                            </button>
                        @endif
                        <a href="{{ route("acteMariage.index") }}">
                            <button type="button" class="btn btn-info text-white">
                                <i class="fas fa-list me-1"></i>Liste des actes
                            </button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if($acte)
                            <input type="hidden" value="{{ $acte->code_declaration_mariage }}" id="cdm">
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
                                <p>La copie d'acte de mariage demandée n'a pas été trouvée dans le système.</p>
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

@endsection
@section("scripts")
<script src="{{ asset('pdfjs/pdf.min.js') }}" onerror="this.onerror=null;this.src='https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js';"></script>
<script>
    if (typeof pdfjsLib !== 'undefined') {
        pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('pdfjs/pdf.worker.min.js') }}";
    }
    $(function() {
        @if($acte)
            var cdm = $("#cdm").val();
            if (cdm && typeof pdfjsLib !== 'undefined') {
                var route = "{{ route('acteMariage.copie', ':id') }}".replace(':id', cdm);
                loadPdfWithJs(route);
            } else if (cdm) {
                $("#pdf-loading").addClass("d-none");
                $("#pdf-error").removeClass("d-none").html('<strong>Erreur :</strong> La bibliothèque PDF.js n\'a pas pu être chargée.');
            }
        @endif
        $("#btn-imprimer-copie").on("click", function() {
            if ($(this).prop("disabled")) return;
            window.print();
        });
    });
    function loadPdfWithJs(url) {
        var loadingEl = document.getElementById("pdf-loading");
        var errorEl = document.getElementById("pdf-error");
        var containerEl = document.getElementById("pdf-canvas-container");
        pdfjsLib.getDocument({ url: url, withCredentials: true }).promise
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
                        return page.render({ canvasContext: ctx, viewport: viewport }).promise;
                    });
                }
                var renderPromises = [];
                for (var i = 1; i <= numPages; i++) renderPromises.push(renderPage(i));
                return Promise.all(renderPromises);
            })
            .catch(function(err) {
                loadingEl.style.display = "none";
                errorEl.classList.remove("d-none");
                errorEl.innerHTML = '<strong>Erreur lors du chargement du PDF :</strong> ' + (err.message || err) + '<br><small>URL : ' + url + '</small>';
            });
    }
</script>
@endsection
