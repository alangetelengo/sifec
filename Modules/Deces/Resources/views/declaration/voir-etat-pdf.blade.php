@extends('layout.app')
@section('titre')
    {{ $titrePage }}
@endsection
@section('styles')
@endsection

@section('corps')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">{{ $titrePage }}</h4>
                <div class="row mt-2">
                    <div class="d-flex flex-wrap align-items-center gap-2 justify-content-between w-100 px-1">
                        @if($retour === 'acte')
                            <a href="{{ route('acteDeces.index') }}" class="btn btn-sm btn-info text-white mb-2">
                                <i class="fas fa-arrow-left me-1"></i> Gestion des actes
                            </a>
                        @else
                            <a href="{{ route('declarationDeces.index') }}" class="btn btn-sm btn-info text-white mb-2">
                                <i class="fas fa-arrow-left me-1"></i> Liste des certificats de décès
                            </a>
                        @endif
                        <div class="d-flex gap-2">
                            <button type="button"
                                    class="btn btn-sm btn-success mb-2"
                                    id="btn-imprimer-declaration-pdf"
                                    title="Imprimer le document">
                                <i class="fas fa-print me-1"></i> Imprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <input type="hidden" id="cdd-declaration" value="{{ $ddc->code_declaration_deces }}">
                        <div id="pdfViewerDeclaration"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<link href="{{ asset('kendo-library/kendo-style/kendo.common.min.css')}}" rel="stylesheet">
<link href="{{ asset('kendo-library/kendo-style/kendo.blueopal.min.css')}}" rel="stylesheet">
<script src="{{ asset('kendo-library/kendo-js/kendo.all.min.js')}}"></script>
<script>
    function sifecPrintAuthenticatedPdf(url) {
        fetch(url, { credentials: 'same-origin', headers: { 'Accept': 'application/pdf,*/*' } })
            .then(function (res) {
                var ct = (res.headers.get('Content-Type') || '').toLowerCase();
                if (!res.ok || ct.indexOf('application/pdf') === -1) {
                    return res.text().then(function (body) {
                        var msg = (body || '').trim().substring(0, 300) || ('Erreur HTTP ' + res.status);
                        throw new Error(msg);
                    });
                }
                return res.blob();
            })
            .then(function (blob) {
                var blobUrl = URL.createObjectURL(blob);
                var iframe = document.createElement('iframe');
                iframe.setAttribute('style', 'position:fixed;width:0;height:0;border:0;right:0;bottom:0;opacity:0;pointer-events:none');
                iframe.setAttribute('title', 'Impression PDF');
                iframe.src = blobUrl;
                document.body.appendChild(iframe);
                iframe.onload = function () {
                    try {
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();
                    } catch (e) {
                        console.warn('[sifecPrintAuthenticatedPdf]', e);
                    }
                    setTimeout(function () {
                        if (iframe.parentNode) {
                            iframe.parentNode.removeChild(iframe);
                        }
                        URL.revokeObjectURL(blobUrl);
                    }, 120000);
                };
            })
            .catch(function (err) {
                alert('Impossible d’imprimer le document : ' + (err && err.message ? err.message : String(err)));
            });
    }

    $(function () {
        var pdfUrl = @json($pdfUrl);
        $.when(
            $.getScript("{{ asset('kendo-library/pdf.js') }}"),
            $.getScript("{{ asset('kendo-library/kendo-style/worker.js') }}")
        )
        .done(function () {
            window.pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('kendo-library/kendo-style/worker.js') }}";
        }).then(function () {
            $("#pdfViewerDeclaration").kendoPDFViewer({
                pdfjsProcessing: {
                    file: pdfUrl
                },
                width: "100%",
                height: 1200
            });
            $('a[title="Print"]').hide();
            $('a[title="Download"]').hide();
            $('a[title="Open"]').hide();
            $(".k-toolbar").hide();
        });

        $("#btn-imprimer-declaration-pdf").on("click", function () {
            sifecPrintAuthenticatedPdf(pdfUrl);
        });
    });
</script>
@endsection
