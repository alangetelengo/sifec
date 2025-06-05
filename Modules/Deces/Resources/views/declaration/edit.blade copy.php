@extends("layout.app")
@section("titre")
    Déclaration décès
@endsection
@section("sous-titre")
    Déclaration décès
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

        <!-- row -->
        <div class="row" id="validation">
            <div class="col-12">
                <div class="card wizard-content">
                    <div class="card-header">
                        {{-- <p> code:{{ $declaration->declarant->code_personne }}</p> --}}
                        <h4>Modification de la déclaration de décès n° <span class="btn btn-primary">{{$declaration->code_declaration_deces}}</span></h4>
                    </div>
                    @include('deces::declaration.fomeditdeces');

                    {{-- Modal défunt --}}
                    <div class="modal fade search-search-modal-lg" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="defuntmodal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Rechercher défunt</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Nom(s) défunt <span class="text-danger">*</span></label>
                                            <input type="text" required class="form-control required @error('nom_defunt_recherche') is-invalid @enderror" value="{{ old("nom_defunt_recherche") }}" placeholder="" id="nom_defunt_recherche">
                                            @error("nom_defunt_recherche")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Prénom(s) défunt</label>
                                            <input type="text" class="form-control @error('prenom_defunt_recherche') is-invalid @enderror" value="{{ old("prenom_defunt_recherche") }}" placeholder="" id="prenom_defunt_recherche">
                                            @error("prenom_defunt_recherche")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Sexe</label>
                                            <select name="sexe_defunt_recherche" required id="sexe_defunt_recherche" class="form-control">
                                                <option value="" disabled>Choisir</option>
                                                <option value="M" selected>Masculin</option>
                                                <option value="F">Féminin</option>
                                            </select>
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Téléphone </label>
                                            <input type="tel" class="form-control @error('telephone_defunt_recherche') is-invalid @enderror" value="{{ old("telephone_defunt_recherche") }}" id="telephone_defunt_recherche">
                                            @error("telephone_defunt_recherche")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <input type="hidden" value="VIVANT" id="statut_personne_defunt_recherche">

                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-info text-white" id="rechercher">Rechercher</button>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Résultat de la recherche</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div id="resultatDefunt"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal conjoint --}}
                    <div class="modal fade conjoint-search-modal-lg" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="conjointmodal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Rechercher conjoint</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Nom(s) défunt <span class="text-danger">*</span></label>
                                            <input type="text" require class="form-control"lass="form-control required @error('nom_conjoint_recherche') is-invalid @enderror" value="{{ old("nom_conjoint_recherche") }}" placeholder="" id="nom_conjoint_recherche">
                                            @error("nom_conjoint_recherche")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Prénom(s) défunt</label>
                                            <input type="text" class="form-control @error('prenom_conjoint_recherche') is-invalid @enderror" value="{{ old("prenom_conjoint_recherche") }}" placeholder="" id="prenom_conjoint_recherche">
                                            @error("prenom_conjoint_recherche")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Sexe</label>
                                            <select name="sexe_conjoint_recherche" required id="sexe_conjoint_recherche" class="form-control">
                                                <option value="" disabled>Choisir</option>
                                                <option value="M" selected>Masculin</option>
                                                <option value="F">Féminin</option>
                                            </select>
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Téléphone </label>
                                            <input type="tel" class="form-control @error('telephone_conjoint_recherche') is-invalid @enderror" value="{{ old("telephone_conjoint_recherche") }}" id="telephone_conjoint_recherche">
                                            @error("telephone_conjoint_recherche")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-info text-white" id="rechercherconjoint">Rechercher</button>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Résultat de la recherche</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div id="resultatConjoint"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Modal recherche d'un père --}}
                    <div class="modal fade pere-search-modal-lg" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="rmodal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Rechercher père</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Nom(s) père <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" required  lass="form-control required @error('nom_pere_recherche') is-invalid @enderror" value="{{ old("nom_pere_recherche") }}" placeholder="" id="nom_pere_recherche">
                                            @error("nom_pere_recherche")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Prénom(s) père</label>
                                            <input type="text" class="form-control @error('prenom_pere_recherche') is-invalid @enderror" value="{{ old("prenom_pere_recherche") }}" placeholder="" id="prenom_pere_recherche">
                                            @error("prenom_pere_recherche")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Sexe</label>
                                            <select name="sexe_pere_recherche" id="sexe_pere_recherche" required class="form-control">

                                                <option value="M" selected>Masculin</option>

                                            </select>
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Téléphone </label>
                                            <input type="tel" class="form-control @error('telephone_pere_recherche') is-invalid @enderror" value="{{ old("telephone_pere_recherche") }}" id="telephone_pere_recherche">
                                            @error("telephone_pere_recherche")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-info text-white" id="rechercherpere">Rechercher</button>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Résultat de la recherche</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div id="resultatPere"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal recherche d'une mère --}}
                    <div class="modal fade mere-search-modal-lg" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="meremodal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Rechercher mère</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Nom(s) mère <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"lass="form-control required @error('nom_mere_recherche') is-invalid @enderror" value="{{ old("nom_mere_recherche") }}" placeholder="" id="nom_mere_recherche">
                                            @error("nom_mere_recherche")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Prénom(s) mère</label>
                                            <input type="text" class="form-control @error('prenom_mere_recherche') is-invalid @enderror" value="{{ old("prenom_mere_recherche") }}" placeholder="" id="prenom_mere_recherche">
                                            @error("prenom_mere_recherche")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Sexe</label>
                                            <select name="sexe_mere_recherche" required id="sexe_mere_recherche" class="form-control">

                                                <option value="F" selected>Féminin</option>
                                            </select>
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Téléphone </label>
                                            <input type="tel" class="form-control @error('telephone_mere_recherche') is-invalid @enderror" value="{{ old("telephone_mere_recherche") }}" id="telephone_mere_recherche">
                                            @error("telephone_mere_recherche")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-info text-white" id="recherchermere">Rechercher</button>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Résultat de la recherche</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div id="resultatMere"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Modal recherche d'un déclarant --}}
                    <div class="modal fade declarant-search-modal-lg" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="declarantmodal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Rechercher déclarant</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Nom(s) déclarant <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"lass="form-control required @error('nom_declarant_recherche') is-invalid @enderror" value="{{ old("nom_declarant_recherche") }}" placeholder="" id="nom_declarant_recherche">
                                            @error("nom_declarant_recherche")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Prénom(s) déclarant</label>
                                            <input type="text" class="form-control @error('prenom_declarant_recherche') is-invalid @enderror" value="{{ old("prenom_declarant_recherche") }}" placeholder="" id="prenom_declarant_recherche">
                                            @error("prenom_declarant_recherche")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Sexe</label>
                                            <select name="sexe_declarant_recherche" required id="sexe_declarant_recherche" class="form-control">
                                                <option value="" disabled>Choisir</option>
                                                <option value="M" selected>Masculin</option>
                                                <option value="F">Féminin</option>
                                            </select>
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Téléphone </label>
                                            <input type="tel" class="form-control @error('telephone_declarant_recherche') is-invalid @enderror" value="{{ old("telephone_declarant_recherche") }}" id="telephone_declarant_recherche">
                                            @error("telephone_declarant_recherche")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <input type="hidden" value="VIVANT" id="statut_personne_declarant_recherche">

                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-info text-white" id="rechercherdeclarant">Rechercher</button>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Résultat de la recherche</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div id="resultatDeclarant"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


@endsection
@section("scripts")
{{-- @include("deces::declaration.js.edit") --}}
@endsection
