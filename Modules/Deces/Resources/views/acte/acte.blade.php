@extends('layout.app')
@section('titre')
Acte de décès
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
                        <a href="{{ route("acteDeces.index") }}"><button type="button" class="btn btn-info m-t-2 float-end text-white" >Liste des actes</button></a>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if($acte)
                            <input type="hidden" value="{{ $acte->code_declaration_deces }}" id="cdn">
                            <input type="hidden" value="{{ $acte->retirer ?? 0 }}" id="retirer">
                            <div id="pdfViewer"></div>
                        @else
                            <div class="alert alert-danger" role="alert">
                                <h4 class="alert-heading">Acte introuvable</h4>
                                <p>L'acte de décès demandé n'a pas été trouvé dans le système.</p>
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
                // kendo.alert("ok");
                var route = "{{ route('acteDeces.display', ':id') }}";
                route = route.replace(':id', cdn);
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
                    $('a[title="Download"]').hide();
                    $('a[title="Open"]').hide();
                    if(retirer == 0){
                        $('a[title="Print"]').show();
                        $(".k-toolbar").show();
                    }else{
                        $('a[title="Print"]').hide();
                        $(".k-toolbar").hide();
                    }
                });
            }
        @endif
    });
</script>
@endsection
