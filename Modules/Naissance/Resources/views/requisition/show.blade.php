<html lang="fr">
@extends("layout.app")
@section("titre")
    Réquisition-document
@endsection

@section("corps")
    <!-- row -->
    <div class="row" id="validation">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    {{-- <h4>{{ $type_jugement }}</h4> --}}
                    <a href="{{ route("requisition.index") }}"><button type="button" class="btn btn-info m-t-2 float-end text-white" >Liste des requisitions</button></a>
                </div>
                <div class="card wizard-content">
                    <div class="card-body">
                        <div class="ligne"><h4>INFORMATIONS DE LA REQUISITION</h4></div>
                        <br>
                        <div class="row">
                            <iframe src="{{ asset("app/".$requisition->document_requisition) }}" frameborder="0" height="700" width="400"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

