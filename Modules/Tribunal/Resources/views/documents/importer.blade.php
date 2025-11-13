@extends("layout.app")
@section("titre")
    Importer un document
@endsection

@section("corps")
<div class="row" id="validation">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>
                    Importation du document pour
                    {{ $objet->type_declaration == "DISPENSE" ? " la demande de dispense " : strtolower($objet->type_declaration) }}
                    n° <strong style="color: red">{{ $objet->numero_certificat ?? $objet->numero_dispense }}</strong>
                </h4>
                <a href="{{ route('tribunal.document.index') }}" class="btn btn-primary float-end">
                    <i class="fa fa-list"></i> Liste des documents
                </a>
            </div>
            <div class="card wizard-content">
                <div class="card-body">
                    {{-- Affichage du certificat PDF --}}
                    @if(isset($objet->numero_certificat) || isset($objet->numero_acte) || isset($objet->numero_dispense))
                    <div class="mb-4">
                        {{-- <label class="form-label fw-bold">{{ $objet->type_declaration }}</label> --}}
                        <button type="button" class="btn btn-outline-secondary btn-sm ms-2" id="toggle-iframe">
                            <span id="toggle-iframe-text">Masquer le certificat</span>
                        </button>
                        <div id="pdf-iframe-container" class="mt-2">
                            <iframe src="{{ route('tribunal.certificat.pdf', ['id' => $objet->getKey(), 'module' => $type]) }}"
                                    width="100%" height="600px" style="border:1px solid #ccc;">
                                Ce navigateur ne supporte pas l'affichage PDF.
                            </iframe>
                        </div>
                    </div>
                    @endif
                    <div class="mb-4">
                        {{-- Affichage du document importé après upload --}}
                        @if(session('document_importe'))
                            <div class="mb-3">
                                <label class="form-label fw-bold">Document importé</label>
                                <iframe src="{{ asset(session('document_importe')) }}" width="100%" height="600px" style="border:1px solid #ccc;">
                                    Ce navigateur ne supporte pas l'affichage PDF.
                                </iframe>
                            </div>
                        @endif
                        <div class="ligne">
                            <h4>INFORMATIONS DU DOCUMENT À IMPORTER</h4>
                        </div>
                        <form action="{{ route('tribunal.document.store', ['type' => $type, 'id' => $objet->getKey()]) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="code_declaration" value="{{ $objet->getKey() }}">
                            <input type="hidden" name="mode" value="{{ $mode ?? 'declaration' }}">
                            <div class="row">
                                <div class="mb-2 col-md-5">
                                    <label class="form-label">Tribunal <span class="text-danger">*</span></label>
                                    <select name="cui" class="form-control" readonly>
                                        <option value="{{ Auth::user()->affectationActive()->cui }}">
                                            {{ Auth::user()->affectationActive()->institution->lib_institution }}
                                        </option>
                                    </select>
                                </div>
                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Type de document <span class="text-danger">*</span></label>
                                    <select name="type_document" id="typedocument" class="form-control" required>
                                        <option value="" selected>Selectionner</option>
                                        @if(isset($objet->type_declaration) && $objet->type_declaration == "CERTIFICAT DE NON INSCRIPTION")
                                            <option value="requisition">Réquisition</option>
                                            <option value="jugement">Jugement</option>
                                        @elseif(isset($objet->type_declaration) && in_array($objet->type_declaration, ["FICHE DE TRANSCRIPTION", "CERTIFICAT DE DESTRUCTION DE L'ACTE"]))
                                            <option value="requisition">Réquisition</option>
                                        @else
                                            <option value="requisition">Réquisition</option>
                                            <option value="jugement">Jugement</option>
                                        @endif
                                    </select>
                                    @error('type_document')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-4 typerequisition d-none">
                                    <label class="form-label">Type de réquisition <span class="text-danger">*</span></label>
                                    <select name="code_type_requisition" class="form-control code_type_requisition" disabled>
                                        <option value="" selected>Selectionner</option>
                                        @foreach ($typeRequisitions as $typerequisition)
                                            <option value="{{ $typerequisition->code_type_requisition }}">{{ $typerequisition->lib_type_requisition }}</option>
                                        @endforeach
                                    </select>
                                    @error('code_type_requisition')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-4 typejugement d-none">
                                    <label class="form-label">Type de jugement <span class="text-danger">*</span></label>
                                    <select name="code_type_jugement" class="form-control code_type_jugement" disabled>
                                        <option value="" selected>Selectionner</option>
                                        @foreach ($typeJugements as $typejugement)
                                            <option value="{{ $typejugement->code_type_jugement }}">{{ $typejugement->lib_type_jugement }}</option>
                                        @endforeach
                                    </select>
                                    @error('code_type_jugement')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">N° du document</label>
                                    <input type="text" class="form-control" placeholder="Numéro du document" name="num_document" value="{{ old('num_document') }}">
                                    @error('num_document')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Date du document <span class="text-danger">*</span></label>
                                    <input type="date" name="date_document" class="form-control" value="{{ old('date_document') }}">
                                    @error('date_document')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Document <span class="text-danger">*</span></label>
                                    <input type="file" name="document_importer"  class="form-control" value="{{ old('document_importer') }}" required>
                                    @error('document_importer')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-2 col-md-3 mt-5">
                                    <button type="submit" class="btn btn-sm btn-primary">Enregistrer</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("scripts")
<script>
    $(function(){
        // Gestion de l'affichage/masquage de l'iframe
        $("#toggle-iframe").click(function(){
            $("#pdf-iframe-container").toggle();
            if($("#pdf-iframe-container").is(":visible")){
                $("#toggle-iframe-text").text("Masquer le certificat");
            } else {
                $("#toggle-iframe-text").text("Afficher le certificat");
            }
        });
        // Gestion du type de document
        $("#typedocument").change(function(){
            var typeDoc = $(this).val();
            if(typeDoc != ""){
                if(typeDoc == "requisition"){
                    $("div.typejugement").addClass("d-none");
                    $("div.typerequisition").removeClass("d-none");
                    $("select.code_type_requisition").prop("disabled",false);
                    $("select.code_type_jugement").prop("disabled",true);
                }
                if(typeDoc == "jugement"){
                    $("div.typerequisition").addClass("d-none");
                    $("div.typejugement").removeClass("d-none");
                    $("select.code_type_jugement").prop("disabled",false);
                    $("select.code_type_requisition").prop("disabled",true);
                }
            } else {
                $("div.typerequisition").addClass("d-none");
                $("div.typejugement").addClass("d-none");
                $("select.code_type_jugement").prop("disabled",true);
                $("select.code_type_requisition").prop("disabled",true);
            }
        });
        // Masquer automatiquement le certificat PDF si un document vient d'être importé
        @if(session('document_importe'))
            $("#pdf-iframe-container").hide();
            $("#toggle-iframe-text").text("Afficher le certificat");
        @endif
    });
</script>
@endsection
