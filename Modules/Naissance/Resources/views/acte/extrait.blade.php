@extends('layout.app')
@section('titre')
Extrait d'acte de naissance
@endsection
@section("styles")


@endsection

@section('corps')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4></h4>
                <div class="row">
                    <div id="dupcreer">
                        <button class="btn btn-sm btn-primary mb-2  chercheacte">Imprimer</button>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <input type="hidden" value="{{ $acte->code_declaration_naissance }}" id="cdn">
                        <div id="pdfViewer"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

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

        var cdn = $("#cdn").val();
        // kendo.alert("ok");
        var route = "{{ route('acteNaissance.displayExtrait', ':id') }}";
        route = route.replace(':id',cdn);
        $.when(
            $.getScript("{{ asset('kendo-library/pdf.js') }}"),
            $.getScript("{{ asset('kendo-library/kendo-style/worker.js') }}")
        )
        .done(function () {
            window.pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('kendo-library/kendo-style/worker.js') }}";
        }).then(function(){
            $("#pdfViewer").kendoPDFViewer({
                pdfjsProcessing: {
                    file: route
                },
                width: "100%",
                height: 1200
            });
            $('a[title="Print"]').hide();
            $('a[title="Download"]').hide();
            $('a[title="Open"]').hide();
            $(".k-toolbar").hide();

        });
    });
</script>
@endsection
