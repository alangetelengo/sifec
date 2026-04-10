<html lang="fr">
@extends("layout.app")
@section("titre")
    Modifier déclaration naissance
@endsection

@section("styles")
<!-- Form step -->
<link href="{{ asset('tpl/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css') }}" rel="stylesheet">
<!-- Daterange picker -->
<link href="{{ asset('tpl/vendor/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
<!-- Clockpicker -->
<link href="{{ asset('tpl/vendor/clockpicker/css/bootstrap-clockpicker.min.css') }}" rel="stylesheet">
<!-- asColorpicker -->
<link href="{{ asset('tpl/vendor/jquery-asColorPicker/css/asColorPicker.min.css') }}" rel="stylesheet">
<!-- Material color picker -->
<link href="{{ asset('tpl/vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
<!-- Pick date -->
<link href="{{ asset('tpl/wizard/assets/node_modules/wizard/steps.css') }}" rel="stylesheet">
<!--alerts CSS -->
<link href="{{ asset('tpl/wizard/assets/node_modules/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
<!-- Custom CSS -->
<link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
@endsection

@section("corps")

<div class="page-sifec-form">
        <!-- row -->
        <div class="row" id="validation">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            {{ $title }} N° <strong class="btn btn-sm btn-warning">{{ $dn->code_declaration_naissance }}</strong>

                            {{-- <input type="text" class="d-none" id="codepere" value="{{ $dn->pere->code_personne }}">
                            <input type="text" class="d-none" id="typedocumentpere" value="{{ $dn->pere->document->code_type_document }}">
                            <input type="text" class="d-none" id="numerodocumentpere" value="{{ $dn->pere->document->numero_document }}">
                            <input type="tex" id="imagepere" class="d-none" value="{{ $dn->pere->document->image_document }}">

                            <input type="text" class="d-none" id="codemere" value="{{ $dn->mere->code_personne }}">
                            <input type="text" class="d-none" id="typedocumentmere" value="{{ $dn->mere->document->code_type_document }}">
                            <input type="text" class="d-none" id="numerodocumentmere" value="{{ $dn->mere->document->numero_document }}">
                            <input type="text" name="" id="imagemere" class="d-none" value="{{ $dn->mere->document->image_document }}">

                            <input type="text" class="d-none" id="codedeclarant" value="{{ $dn->declarant->code_personne }}">
                            <input type="text" class="d-none" id="typedocumentdeclarant" value="{{ $dn->declarant->document->code_type_document }}">
                            <input type="text" class="d-none" id="numerodocumentdeclarant" value="{{ $dn->declarant->document->numero_document }}">
                            <input type="text" name="" id="imagedeclarant" class="d-none" value="{{ $dn->declarant->document->image_document }}">

                            <button type="button" class="btn btn-primary choix" data-bs-toggle="modal" parent="pere" data-bs-target="#pieceparent">
                            Joindre la pièce du père
                            </button>
                            <button type="button" class="btn btn-primary choix" data-bs-toggle="modal" parent="mere" data-bs-target="#pieceparent">
                                Joindre la pièce de la mère
                            </button>
                            @if($dn->declarant->code_personne != $dn->pere->code_personne && $dn->declarant->code_personne != $dn->mere->code_personne)
                            <button type="button" class="btn btn-primary choix" data-bs-toggle="modal" parent="declarant" data-bs-target="#pieceparent">
                                Joindre la pièce du déclarant
                            </button>
                            @endif --}}
                        </h4>

                        @if($dn->type_declaration === 'CERTIFICAT DE NON INSCRIPTION')
                            <a href="{{ route('certificatNonInscription.index') }}" class="btn btn-primary float-end">
                                <i class="fa fa-list"></i> Liste des certificats de non-inscription
                            </a>
                        @elseif($dn->type_declaration === "CERTIFICAT DE DESTRUCTION DE L'ACTE")
                            <a href="{{ route('certificatDestruction.index') }}" class="btn btn-primary float-end">
                                <i class="fa fa-list"></i> Liste des certificats de destruction
                            </a>
                        @elseif(in_array($dn->type_declaration, ['CERTIFICAT DE TRANSCRIPTION', 'FICHE DE TRANSCRIPTION'], true))
                            <a href="{{ route('certificatTranscription.index') }}" class="btn btn-primary float-end">
                                <i class="fa fa-list"></i> Liste des fiches / certificats de transcription
                            </a>
                        @else
                            <a href="{{ route('declarationNaissance.index') }}" class="btn btn-primary float-end">
                                <i class="fa fa-list"></i> Liste des déclarations
                            </a>
                        @endif
                    </div>
                    <div class="card wizard-content">
                        <div class="card-body">
                           @include('naissance::declaration.formedit')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="pieceparent" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="pieceparentLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="pieceparentLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="min-width: 845px">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Réf.</th>
                                                <th>Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody">

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div> <br>
                        <!---------------------------------->
                        {{-- <div id="div_importer_fichier" hidden>
                            <form  method="post" id="formPiece" action="{{ route('declarationNaissance.store.importer') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-3">
                                <hr>
                                    <div class="col-md-6 formate">
                                        <label for="code_type_document" class="form-label"> <strong> Type pièce d'identité </strong></label>
                                        @error("code_type_document")<span class="text-danger">{{ $message }}</span> @enderror
                                        <select id="reftypedocumentparent" name="code_type_document" class="form-control form-control wide">
                                                <option disabled selected>Choisissez</option>
                                            @foreach ($typedocuments as $item)
                                                <option value="{{ $item->code_type_document }}">{{ $item->lib_type_document  }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text code_type_document_error"></span>
                                    </div>
                                    <input id="codeparent" name="codeparent" type="text" class="d-none">
                                    <div class="col-md-6 formate">
                                        <label for="numero_document_parent" class="form-label">Numéro pièce d'identité</label>
                                            @error("numero_document_parent")<span class="text-danger">{{ $message }}</span> @enderror
                                        <input id="refdocumentparent" name="numero_document" type="text" class="form-control @error("numero_document_parent") is-invalid @enderror" placeholder="Saisissez le numéro du document" onkeyup="this.value=this.value.toUpperCase()">
                                        <span class="text-danger error-text numero_document_error"></span>
                                    </div>

                                    <div class="col-md-6 formate">
                                        <br>
                                        @error("file")<span class="text-danger">{{ $message }}</span> @enderror
                                        <input id="file" name="file" type="file" class="form-control @error("file") is-invalid @enderror" accept="application/pdf" />
                                        <span class="text-danger error-text file_error"></span>
                                    </div>

                                    <div class="col-md-4 formate">
                                        <br>
                                        <button type="submit" class="btn btn-success"><i class='bx bx-check-circle'></i> Valider l'importation</button> &ensp;
                                    </div>
                                </div>

                            </form>

                        </div> --}}

                        <!---------------------------------->
                        <div id="div_scanner_fichier" hidden>
                            <form id="form1" action="{{ route('declarationNaissance.store.scannerpdf') }}" method="POST" enctype="multipart/form-data" target="_blank">
                                @csrf
                                <div class="row g-3">
                                <h5>Ajouter une pièce</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6 formate">
                                        <label for="code_type_document" class="form-label"> <strong> Type pièce d'identité </strong></label>
                                        @error("code_type_document")<span class="text-danger">{{ $message }}</span> @enderror
                                        <select id="reftypedocumentparent" name="code_type_document" class="form-control form-control wide">
                                                <option disabled selected>Choisissez</option>
                                            @foreach ($typedocuments as $item)
                                                <option value="{{ $item->code_type_document }}">{{ $item->lib_type_document  }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text code_type_document_error"></span>
                                    </div>
                                    <input id="codeparent" name="codeparent" type="hidden">
                                    <div class="col-md-6 formate">
                                        <label for="numero_document_parent" class="form-label">Numéro pièce d'identité</label>
                                            @error("numero_document_parent")<span class="text-danger">{{ $message }}</span> @enderror
                                        <input id="refdocumentparent" name="numero_document" type="text" class="form-control @error("numero_document_parent") is-invalid @enderror" placeholder="Saisissez le numéro du document" onkeyup="this.value=this.value.toUpperCase()">
                                        <span class="text-danger error-text numero_document_error"></span>
                                    </div>


                                    <div class="col-md-8 formate">
                                        <br>
                                        <button type="button" onclick="scanToPdfWithThumbnails();" class="btn btn-warning"><i class='bx bx-scan'></i> Scanner le document en <strong>pdf</strong>  </button> &ensp; &ensp;
                                    </div>
                                    <div class="col-md-4 formate">
                                        <br>
                                        <button type="submit" class="btn btn-success"><i class='bx bx-check-circle'></i> Valider le scannage </strong> </button> &ensp; &ensp;
                                    </div>

                                    <div id="images" style="margin-top: 10px;">  </div>
                                    <div id="server_response"> </div>
                                </div>
                            </form>

                        </div>

                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" id="btnImporter" onclick="btn_importer();" class="btn btn-primary"><i class='bx bx-upload'></i> Importer le fichier</button> &ensp; --}}
                        <button type="button" id="btnScanner" onclick="btn_scanner();" class="btn btn-primary"><i class='bx bx-scan'></i> Scanner la pièce</button> &ensp;
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class='bx bx-block'></i> Fermer</button>
                    </div>
                </div>
            </div>
        </div>


        @include("naissance::declaration.ajout_piece_parent")
</div>
@endsection
@section("scripts")


@include("naissance::declaration.js.edit")
@endsection
