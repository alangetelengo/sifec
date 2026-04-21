@extends('layout.app')

@section('titre')
Nouvelle demande de document
@endsection

@section('styles')
<link href="{{ asset('tpl/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css') }}" rel="stylesheet">
<link href="{{ asset('tpl/wizard/assets/node_modules/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
<link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
<style>
    @include('authentification::partials.sifec-swal-delete-styles')
</style>
@endsection

@section('corps')
<div class="page-sifec-form">
<div class="an-shell">
<div class="an-body">
    <div class="row">
        <div class="col-xl-12">
            <div class="card wizard-content">
                <div class="card-header">
                    <h4>Enregistrer une demande de document (sur site)</h4>
                    <a href="{{ route('demandeDocument.index') }}">
                        <button type="button" class="btn btn-warning float-end text-white">
                            <i class="fa fa-list"></i> Retour à la liste
                        </button>
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('demandeDocument.store') }}" method="POST" id="form-demande">
                        @csrf

                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2 mb-3">1. Informations sur l'acte</h5>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Type d'acte <span class="text-danger">*</span></label>
                                <select name="code_type_acte" id="code_type_acte" class="form-control" required>
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($typesActes as $type)
                                        <option value="{{ $type->code_type_acte }}" {{ old('code_type_acte') == $type->code_type_acte ? 'selected' : '' }}>
                                            {{ $type->lib_type_acte }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('code_type_acte')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Type de document <span class="text-danger">*</span></label>
                                <select name="code_type_document_demande" id="code_type_document_demande" class="form-control" required>
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($typesDocuments as $type)
                                        <option value="{{ $type->code_type_document_demande }}" {{ old('code_type_document_demande') == $type->code_type_document_demande ? 'selected' : '' }}>
                                            {{ $type->lib_type_document_demande }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('code_type_document_demande')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Numéro d'acte <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="numero_acte" id="numero_acte" class="form-control" 
                                           value="{{ old('numero_acte') }}" required>
                                    <button type="button" id="btn-verifier-acte" class="btn btn-info">
                                        <i class="fas fa-search"></i> Vérifier
                                    </button>
                                </div>
                                @error('numero_acte')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <div id="info-acte" class="mt-2">
                                    @if(old('numero_acte'))
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i> Veuillez vérifier à nouveau l'acte
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row" id="section-demandeur" style="display:{{ old('nom_demandeur') ? 'flex' : 'none' }};">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2 mb-3 mt-4">2. Informations du demandeur</h5>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" name="nom_demandeur" class="form-control" 
                                       onkeyup="this.value=this.value.toUpperCase()" 
                                       value="{{ old('nom_demandeur') }}" required>
                                @error('nom_demandeur')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Prénom(s)</label>
                                <input type="text" name="prenom_demandeur" class="form-control" 
                                       style="text-transform: capitalize"
                                       value="{{ old('prenom_demandeur') }}">
                                @error('prenom_demandeur')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Sexe <span class="text-danger">*</span></label>
                                <select name="sexe_demandeur" class="form-control" required>
                                    <option value="">-- Sélectionner --</option>
                                    <option value="M" {{ old('sexe_demandeur') == 'M' ? 'selected' : '' }}>Masculin</option>
                                    <option value="F" {{ old('sexe_demandeur') == 'F' ? 'selected' : '' }}>Féminin</option>
                                </select>
                                @error('sexe_demandeur')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input type="tel" name="telephone_demandeur" class="form-control" value="{{ old('telephone_demandeur') }}" required>
                                @error('telephone_demandeur')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email_demandeur" class="form-control" value="{{ old('email_demandeur') }}">
                                @error('email_demandeur')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Observations</label>
                                <textarea name="observations" class="form-control" rows="3">{{ old('observations') }}</textarea>
                                @error('observations')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="border-top pt-3 mt-3">
                            <button type="submit" class="btn btn-success" id="btn-submit">
                                <i class="fas fa-save"></i> Enregistrer la demande
                            </button>
                            <a href="{{ route('demandeDocument.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('tpl/wizard/assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
<script>
$(document).ready(function() {
    let acteVerifie = false; // Variable pour tracker si l'acte a été vérifié avec succès
    
    // Vérification de l'acte avec spinner
    $('#btn-verifier-acte').on('click', function() {
        const numeroActe = $('#numero_acte').val();
        const codeTypeActe = $('#code_type_acte').val();
        
        if (!numeroActe || !codeTypeActe) {
            Swal.fire({
                icon: 'warning',
                title: 'Attention',
                text: 'Veuillez sélectionner le type d\'acte et saisir le numéro'
            });
            return;
        }
        
        const $btn = $(this);
        const originalHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Vérification...');
        
        $('#info-acte').html('<div class="spinner-border spinner-border-sm text-primary"></div> Vérification en cours...');
        
        // Réinitialiser le statut de vérification
        acteVerifie = false;
        
        $.ajax({
            url: '{{ route("demandeDocument.rechercherActe") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                numero_acte: numeroActe,
                code_type_acte: codeTypeActe
            },
            success: function(response) {
                if (response.success) {
                    acteVerifie = true; // Marquer comme vérifié
                    
                    $('#info-acte').html(
                        '<div class="alert alert-success">' +
                        '<i class="fas fa-check-circle"></i> Acte trouvé : ' +
                        response.acte.nom + ' ' + response.acte.prenom +
                        '</div>'
                    );
                    
                    // Afficher le formulaire du demandeur
                    $('#section-demandeur').slideDown(400);
                    
                    // Faire défiler vers le formulaire du demandeur
                    setTimeout(function() {
                        $('html, body').animate({
                            scrollTop: $('#section-demandeur').offset().top - 100
                        }, 500);
                    }, 450);
                } else {
                    acteVerifie = false;
                    
                    $('#info-acte').html(
                        '<div class="alert alert-danger">' +
                        '<i class="fas fa-times-circle"></i> ' + response.message +
                        '</div>'
                    );
                    
                    // Cacher le formulaire du demandeur si l'acte n'existe pas
                    $('#section-demandeur').slideUp(400);
                }
            },
            error: function() {
                acteVerifie = false;
                
                $('#info-acte').html(
                    '<div class="alert alert-danger">' +
                    '<i class="fas fa-times-circle"></i> Erreur lors de la vérification' +
                    '</div>'
                );
                
                // Cacher le formulaire du demandeur en cas d'erreur
                $('#section-demandeur').slideUp(400);
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    });

    // Réinitialiser le statut de vérification si l'utilisateur change le numéro d'acte ou le type
    $('#numero_acte, #code_type_acte').on('change', function() {
        if (acteVerifie) {
            acteVerifie = false;
            $('#info-acte').html(
                '<div class="alert alert-warning">' +
                '<i class="fas fa-exclamation-triangle"></i> Veuillez vérifier à nouveau l\'acte' +
                '</div>'
            );
            $('#section-demandeur').slideUp(400);
        }
    });

    // Validation et spinner sur le bouton de soumission
    $('#form-demande').on('submit', function(e) {
        // Vérifier si l'acte a été vérifié
        if (!acteVerifie) {
            e.preventDefault();
            
            Swal.fire({
                icon: 'error',
                title: 'Vérification requise',
                text: 'Veuillez d\'abord vérifier que l\'acte existe en cliquant sur le bouton "Vérifier"',
                confirmButtonText: 'OK'
            });
            
            return false;
        }
        
        var btn = document.getElementById('btn-submit');
        if (typeof sifecBtnLoading === 'function') {
            sifecBtnLoading(btn, 'Enregistrement…');
        } else {
            $(btn).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Enregistrement en cours…');
        }
    });
});
</script>
@endsection
