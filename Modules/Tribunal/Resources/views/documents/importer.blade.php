@extends('layout.app')
@section('titre')
    Importer un document
@endsection
@section('sous-titre')
    {{ ucfirst($type) }} —
    @if($objet->type_declaration == 'DISPENSE')
        Demande de dispense
    @else
        {{ $objet->type_declaration ?? 'Dossier' }}
    @endif
    n° {{ $objet->numero_certificat ?? $objet->numero_dispense ?? $objet->numero_acte ?? $objet->getKey() }}
@endsection

@section('corps')
@php
    $numeroDossier = $objet->numero_certificat ?? $objet->numero_dispense ?? $objet->numero_acte ?? $objet->getKey();
@endphp
<div class="page-sifec-form">
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h4 class="mb-0">
                    Importation du document pour
                    @if($objet->type_declaration == 'DISPENSE')
                        la demande de dispense
                    @else
                        {{ \Illuminate\Support\Str::lower($objet->type_declaration ?? 'le dossier') }}
                    @endif
                    n° <strong class="text-danger">{{ $numeroDossier }}</strong>
                </h4>
                <a href="{{ route('tribunal.document.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fa fa-list me-1"></i> Liste des documents
                </a>
            </div>
            <div class="card-body">
                @if(isset($objet->numero_certificat) || isset($objet->numero_acte) || isset($objet->numero_dispense))
                    <div class="mb-4 p-3 rounded border bg-light">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                            <span class="form-label fw-bold mb-0">Certificat / dossier reçu (PDF)</span>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="toggle-iframe">
                                <span id="toggle-iframe-text">Masquer le certificat</span>
                            </button>
                        </div>
                        <div id="pdf-iframe-container" class="mt-2">
                            <iframe src="{{ route('tribunal.certificat.pdf', ['id' => $objet->getKey(), 'module' => $type]) }}"
                                    class="w-100 rounded"
                                    style="height: 600px; border: 1px solid #dee2e6;"
                                    title="Aperçu du certificat">
                                Ce navigateur ne supporte pas l'affichage PDF.
                            </iframe>
                        </div>
                    </div>
                @endif

                @if(session('document_importe'))
                    <div class="mb-4">
                        <label class="form-label fw-bold">Document importé</label>
                        <iframe src="{{ asset(session('document_importe')) }}"
                                class="w-100 rounded"
                                style="height: 600px; border: 1px solid #dee2e6;"
                                title="Document importé">
                            Ce navigateur ne supporte pas l'affichage PDF.
                        </iframe>
                    </div>
                @endif

                <h5 class="border-bottom pb-2 mb-4">Informations du document à importer</h5>

                <form id="form-importer-tribunal"
                      action="{{ route('tribunal.document.store', ['type' => $type, 'id' => $objet->getKey()]) }}"
                      method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="code_declaration" value="{{ $objet->getKey() }}">
                    <input type="hidden" name="mode" value="{{ $mode ?? 'declaration' }}">

                    <div class="row">
                        <div class="mb-3 col-md-5">
                            <label class="form-label">Tribunal <span class="text-danger">*</span></label>
                            <select name="cui" class="form-control" readonly>
                                <option value="{{ Auth::user()->affectationActive()->cui }}">
                                    {{ Auth::user()->affectationActive()->institution->lib_institution }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label">Type de document <span class="text-danger">*</span></label>
                            <select name="type_document" id="typedocument" class="form-control" required>
                                <option value="" selected>Sélectionner</option>
                                @if(isset($objet->type_declaration) && $objet->type_declaration == 'CERTIFICAT DE NON INSCRIPTION')
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
                        <div class="mb-3 col-md-4 typerequisition d-none">
                            <label class="form-label">Type de réquisition <span class="text-danger">*</span></label>
                            <select name="code_type_requisition" class="form-control code_type_requisition" disabled>
                                <option value="" selected>Sélectionner</option>
                                @foreach ($typeRequisitions as $typerequisition)
                                    <option value="{{ $typerequisition->code_type_requisition }}">{{ $typerequisition->lib_type_requisition }}</option>
                                @endforeach
                            </select>
                            @error('code_type_requisition')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4 typejugement d-none">
                            <label class="form-label">Type de jugement <span class="text-danger">*</span></label>
                            <select name="code_type_jugement" id="code_type_jugement" class="form-control code_type_jugement" disabled>
                                <option value="" selected>Sélectionner</option>
                                @foreach ($typeJugements as $typejugement)
                                    <option value="{{ $typejugement->code_type_jugement }}" data-libelle="{{ $typejugement->lib_type_jugement }}">{{ $typejugement->lib_type_jugement }}</option>
                                @endforeach
                            </select>
                            @error('code_type_jugement')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-6 champ-numero-ancien-acte d-none">
                            <label class="form-label">N° de l'ancien acte (NIUPP) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Ex: NAISS/2023/12345" name="numero_ancien_acte" id="numero_ancien_acte" value="{{ old('numero_ancien_acte') }}">
                            <small class="text-muted">Numéro de l'acte à annuler ou adopter</small>
                            @error('numero_ancien_acte')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">N° du document</label>
                            <input type="text" class="form-control" placeholder="Numéro du document" name="num_document" value="{{ old('num_document') }}">
                            @error('num_document')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Date du document <span class="text-danger">*</span></label>
                            <input type="date" name="date_document" class="form-control" value="{{ old('date_document') }}">
                            @error('date_document')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Document <span class="text-danger">*</span></label>
                            <input type="file" name="document_importer" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                            @error('document_importer')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-4 pt-2 border-top">
                        <a href="{{ route('tribunal.document.index') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-arrow-left me-1"></i> Retour à la liste
                        </a>
                        <button type="submit" class="btn btn-primary" id="btn-importer-submit">
                            <i class="fa fa-save me-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('scripts')
<script>
$(function(){
    $('#toggle-iframe').on('click', function(){
        $('#pdf-iframe-container').toggle();
        if ($('#pdf-iframe-container').is(':visible')) {
            $('#toggle-iframe-text').text('Masquer le certificat');
        } else {
            $('#toggle-iframe-text').text('Afficher le certificat');
        }
    });

    $('#typedocument').on('change', function(){
        var typeDoc = $(this).val();
        if (typeDoc !== '') {
            if (typeDoc === 'requisition') {
                $('div.typejugement').addClass('d-none');
                $('div.typerequisition').removeClass('d-none');
                $('select.code_type_requisition').prop('disabled', false);
                $('select.code_type_jugement').prop('disabled', true);
                $('div.champ-numero-ancien-acte').addClass('d-none');
                $('#numero_ancien_acte').prop('required', false);
            }
            if (typeDoc === 'jugement') {
                $('div.typerequisition').addClass('d-none');
                $('div.typejugement').removeClass('d-none');
                $('select.code_type_jugement').prop('disabled', false);
                $('select.code_type_requisition').prop('disabled', true);
            }
        } else {
            $('div.typerequisition').addClass('d-none');
            $('div.typejugement').addClass('d-none');
            $('select.code_type_jugement').prop('disabled', true);
            $('select.code_type_requisition').prop('disabled', true);
            $('div.champ-numero-ancien-acte').addClass('d-none');
            $('#numero_ancien_acte').prop('required', false);
        }
    });

    // Afficher/masquer le champ "numero_ancien_acte" selon le type de jugement
    $('#code_type_jugement').on('change', function(){
        var selectedOption = $(this).find('option:selected');
        var libelleJugement = selectedOption.data('libelle') || '';
        
        // Afficher le champ pour les jugements d'annulation et d'adoption
        if (libelleJugement.toUpperCase().includes('ANNULATION') || 
            libelleJugement.toUpperCase().includes('ADOPTION') ||
            libelleJugement.toUpperCase().includes('HOMOLOGATION')) {
            $('div.champ-numero-ancien-acte').removeClass('d-none');
            $('#numero_ancien_acte').prop('required', true);
        } else {
            $('div.champ-numero-ancien-acte').addClass('d-none');
            $('#numero_ancien_acte').prop('required', false);
        }
    });

    @if(session('document_importe'))
        $('#pdf-iframe-container').hide();
        $('#toggle-iframe-text').text('Afficher le certificat');
    @endif

    $('#form-importer-tribunal').on('submit', function(){
        var btn = document.getElementById('btn-importer-submit');
        if (btn) {
            sifecBtnLoading(btn, 'Enregistrement...');
        }
    });
});
</script>
@endsection
