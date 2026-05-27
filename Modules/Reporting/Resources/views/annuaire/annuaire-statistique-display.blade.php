@extends('layout.app')
@section('titre')
Annuaire statistique — {{ $titreFait }}
@endsection
@section('corps')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Annuaire statistique — {{ $titreFait }}</h4>
                <div class="row">
                    <div class="d-flex flex-wrap align-items-center gap-2 justify-content-end w-100 px-1">
                        <a href="{{ route('reporting.faits.annuaire.statistique', ['type_fait' => $typeFait]) }}" class="btn btn-sm btn-info text-white mb-2">
                            <i class="fas fa-arrow-left me-1"></i> Retour au formulaire
                        </a>
                        <button type="button" class="btn btn-sm btn-success mb-2" id="btn-exporter-annuaire" title="Exporter en PDF">
                            <i class="fas fa-file-pdf me-1"></i> Exporter en PDF
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <input type="hidden" value="{{ $pdfRoute }}" id="pdfRoute">
                        <input type="hidden" value="{{ $pdfFilename }}" id="pdfFilename">
                        <div id="pdfViewer"></div>
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
function sifecExportAuthenticatedPdf(url, filename) {
    fetch(url, { credentials: 'same-origin', headers: { 'Accept': 'application/pdf,*/*' } })
        .then(function (res) {
            var ct = (res.headers.get('Content-Type') || '').toLowerCase();
            if (!res.ok || ct.indexOf('application/pdf') === -1) {
                return res.text().then(function (body) {
                    throw new Error((body || '').trim().substring(0, 300) || ('Erreur HTTP ' + res.status));
                });
            }
            return res.blob();
        })
        .then(function (blob) {
            var blobUrl = URL.createObjectURL(blob);
            var link = document.createElement('a');
            link.href = blobUrl;
            link.download = filename || 'annuaire-statistique.pdf';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            setTimeout(function () { URL.revokeObjectURL(blobUrl); }, 1000);
        })
        .catch(function (err) {
            alert('Impossible d\'exporter le document : ' + (err && err.message ? err.message : String(err)));
        });
}

$(function () {
    var route = $('#pdfRoute').val();
    var filename = $('#pdfFilename').val();
    $.when(
        $.getScript("{{ asset('kendo-library/pdf.js') }}"),
        $.getScript("{{ asset('kendo-library/kendo-style/worker.js') }}")
    ).done(function () {
        window.pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('kendo-library/kendo-style/worker.js') }}";
    }).then(function () {
        $('#pdfViewer').kendoPDFViewer({
            pdfjsProcessing: { file: route },
            width: '100%',
            height: 1300
        });
        $('a[title="Print"]').hide();
        $('a[title="Download"]').hide();
        $('a[title="Open"]').hide();
    });

    $('#btn-exporter-annuaire').on('click', function () {
        sifecExportAuthenticatedPdf(route, filename);
    });
});
</script>
@endsection
