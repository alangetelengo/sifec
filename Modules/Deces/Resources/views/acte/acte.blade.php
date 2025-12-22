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
                            file: route,
                            error: function(e) {
                                console.error("Erreur lors du traitement du PDF:", e);
                                // Afficher un message d'erreur à l'utilisateur
                                $("#pdfViewer").html(
                                    '<div class="alert alert-danger m-4" role="alert">' +
                                    '<h4 class="alert-heading">Erreur lors du chargement du PDF</h4>' +
                                    '<p>Impossible de charger le PDF de l\'acte de décès.</p>' +
                                    '<hr>' +
                                    '<p class="mb-0">Erreur: ' + (e.error ? e.error.message : 'Erreur inconnue') + '</p>' +
                                    '<p class="mb-0 mt-2">Veuillez réessayer ou contacter l\'administrateur.</p>' +
                                    '<p class="mb-0 mt-2"><small>Route: ' + route + '</small></p>' +
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
    });
</script>
@endsection
