@extends("layout.app")
@section("titre")
    Transcription décès
@endsection
@section("sous-titre")
    Transcription décès
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
                    <div class="card-body">

                        {{-- <h4 class="card-title">Créé un certificat de transcription de décès</h4> --}}
                        <h4>Créer un certificat de non inscription de décès</h4>
                        {{-- <h6class="card-subtitle">Youcanusthevalidationlikewhatwedid</h6> --}}
                        <form  name="contactUsForm" id="contactUsForm" class="validation-wizard wizard-circle" method="post" action="javascript:void(0)">

                            <!-- Step 1 -->
                            <h6>Défunt</h6>

                           {{--  <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target=".search_mere-modal-lg">Faire une recherche</button>  --}}
                            <section>
                                <div class="d-flex justify-content-end align-items-center">
                                    <button type="button" id="clear_defunt" class="btn btn-danger  text-white" ></i> Vider </button>
                                    <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".search-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche du défunt</button>
                                </div>
                                <hr>
                                <div class="ligne">
                                    <h4>INFORMATIONS DU DECES</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input name="code_defunt" id="code_defunt" type="hidden" readonly>
                                            <label class="form-label" for="validationCustom07">Date décès <span class="text-danger">*</span>

                                            </label>

                                            <input type="date" class="form-control required  @error('date_defunt') is-invalid @enderror " placeholder="" id="date_deces"  name="date_deces"  max="{{ \Carbon\Carbon::now()->format('Y-m-d'); }}" >
                                                @error("date_deces")
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                        </div>
                                   </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                        <label class="form-label" for="validationCustom07">Heure décès <span class="text-danger">*</span>
                                        </label>
                                            <input class="form-control required  @error('heure_defunt') is-invalid @enderror" type="time"  placeholder="" name="heure_deces" id="heure_deces">
                                            @error("heure_deces")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                   </div>
                                   <div class="mb-2 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Lieu de survenance <span class="text-danger"></span> </label>
                                            <select name="lieu_survenance_code" id="lieu_survenance_code" class="form-select form-control required">
                                                    {{--  <option disabled selected>Choisissez</option>  --}}
                                                @foreach ($lieusurvenances as $lieusurvenance)
                                                    @if($lieusurvenance->code_lieu_survenance == "LSURV_0006")
                                                        <option value="{{ $lieusurvenance->code_lieu_survenance }}">{{ $lieusurvenance->lib_lieu_survenance }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-2 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Lieu de décés <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control  @error('lieu_deces') is-invalid @enderror" value="{{ old("lieu_deces") }}" id="lieu_deces" name="lieu_deces">
                                        </div>
                                    </div>
                                    {{-- Fin info décès --}}

                                    <div class="ligne">
                                        <h4>INFORMATIONS SUR L'IDENTITE</h4>
                                    </div>
                                   <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Nom(s) défunt <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control required  @error('nom_defunt') is-invalid @enderror" value="{{ old("nom_defunt") }}" placeholder="" id="nom_defunt" name="nom_defunt">
                                            @error("nom_defunt")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Prénom(s) défunt</label>
                                            <input type="text" class="form-control @error('prenom_defunt') is-invalid @enderror" value="{{ old("prenom_defunt") }}" placeholder="" id="prenom_defunt" name="prenom_defunt">
                                            @error("prenom_defunt")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Sexe <span class="text-danger">*</span></label>
                                            <select id="sexe_defunt" name="sexe_defunt" class="form-select form-control required">
                                                <option disabled selected>Choisissez</option>
                                                <option value="M">Masculin</option>
                                                <option value="F">Feminin</option>
                                            </select>
                                        </div>
                                   </div>
                                   <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Date de naissance <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control  @error('date_naissance_defunt') is-invalid @enderror" value="{{ old("date_naissance_defunt") }}" id="date_naissance_defunt" name="date_naissance_defunt" max="{{ \Carbon\Carbon::now()->format('Y-m-d'); }}">
                                            <input type="checkbox" id="type_date_naissance_defunt" value="ESTIME" name="type_date_naissance_defunt"><label for="type_date_naissance_defunt">date estimée</label>
                                            @error("date_naissance_defunt")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Réligion <span class="text-danger">*</span></label>
                                                <select name="code_religion_defunt" id="code_religion_defunt" class="form-select form-control">
                                                        <option disabled selected>Choisissez</option>
                                                    @foreach ($religions as $religion)
                                                        <option value="{{ $religion->code_religion }}">{{ $religion->lib_religion }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Centre d'état civil de naissance <span class="text-danger"></span></label>
                                                <input type="text" class="form-control  @error('cec_naissance') is-invalid @enderror" value="{{ old("cec_naissance") }}" id="cec_naissance" name="cec_naissance">
                                                @error("cec_naissance")
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Lieu de naissance <span class="text-danger">*</span></label>
                                                 <input type="text" class="form-control" id="lieu_naissance_defunt" placeholder="Lieu de naissance" name="lieu_naissance_defunt">
                                                 {{-- <select name="lieu_naissance_defunt" class="form-control required" id="lieu_naissance_defunt">
                                                    <option disabled selected>Choisissez</option>
                                                    @foreach ($localites as $localite)
                                                        <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                                    @endforeach
                                                </select> --}}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Numéro d'acte de naissance <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control  @error('num_acte_naissance') is-invalid @enderror" value="{{ old("num_acte_naissance") }}" id="num_acte_naissance" name="num_acte_naissance" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Profession <span class="text-danger">*</span></label>
                                                <select id="code_profession_defunt" name="code_profession_defunt" class="form-select form-control required">
                                                    <option disabled selected>Choisissez</option>
                                                    @foreach ($professions as $profession)
                                                        <option value="{{ $profession->code_profession }}">{{ $profession->lib_profession }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                       </div>
                                        <div class="mb-2 col-md-3">
                                            <label class="form-label">Niveau d'instruction du defunt</label>
                                            <select id="niveau_instruction_defunt" name="niveau_instruction_defunt" class="form-control form-control wide">
                                                    <option disabled selected>Choisissez</option>
                                                @foreach ($instructions as $item)
                                                    <option value="{{ $item }}">{{ $item }}</option>
                                                @endforeach
                                            </select>
                                            @error("niveau_instruction_defunt")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Situation matrimoniale </label>
                                                <select name="code_situation_matrimoniale_defunt" id="code_situation_matrimoniale_defunt" class="form-select form-control required">
                                                        <option disabled selected>Choisissez</option>
                                                        @foreach ($situationMatrimoniales as $item)
                                                            <option value="{{ $item->code_situation_matrimoniale }}">{{ $item->lib_situation_matrimoniale }}</option>
                                                        @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label class="form-label">Nationalité <span class="text-danger">*</span></label>
                                            <select id="code_nationalite_defunt" name="code_nationalite_defunt" class="form-select form-control required">
                                                <option disabled selected>Choisissez</option>
                                                @foreach ($nationalites as $nationalite)
                                                    <option value="{{ $nationalite->code_nationalite }}" >{{ $nationalite->lib_nationalite}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    </div>
                                {{-- fin identité --}}


                                <div class="ligne">
                                    <h4>INFORMATIONS SUR L'ADRESSE</h4>
                                </div>
                                <div class="mb-2 col-md-2">
                                    <label class="form-label">Pays<span class="text-danger"></span></label>
                                    <select id="domicile_pays_defunt" class="form-control required">
                                        <option value="">Choisissez</option>
                                        @foreach ($countries as $countrie)
                                            <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2 col-md-2">
                                    <label class="form-label">Commune/District<span class="text-danger"></span></label>
                                    <span id="departementcongo_defunt">
                                        <select class="form-control" name="domicile_ville_defunt" id="domicile_ville_defunt">
                                            <option value="">Choisir</option>
                                            @foreach ($localites as $localite)
                                                <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                            @endforeach
                                        </select>
                                    </span>
                                    <span id="autredepartement_defunt">
                                        <input type="text" class="form-control" id="domicile_ville_defunt" placeholder="Ville ou département">
                                    </span>
                                </div>
                                <div class="mb-2 col-md-2">
                                    <label class="form-label">Arr/Com. Urb<span class="text-danger"></span></label>
                                    <span id="arrondissement_defunt">
                                        <select class="form-control" id="domicile_arrondissement_defunt">
                                        </select>
                                    </span>
                                </div>
                                <div class="mb-2 col-md-2">
                                    <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                                    <select class="form-control" id="domicile_quartier_defunt">

                                    </select>
                                </div>
                                <div class="mb-2 col-md-2">
                                    <label class="form-label">Type voie<span class="text-danger"></span></label>
                                    <select class="form-control" id="domicile_typevoie_defunt">
                                        <option value="">Choisir</option>
                                        <option value="Avenue">Avenue</option>
                                        <option value="Boulevard">Boulevard</option>
                                        <option value="Impasse">Impasse</option>
                                        <option value="Rue">Rue</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                </div>
                                <div class="mb-2 col-md-2">
                                    <label class="form-label">N° voie<span class="text-danger"></span></label>
                                    <input type="text" class="form-control" id="domicile_numero_defunt" placeholder="N° voie">
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Nom voie<span class="text-danger"></span></label>
                                    <input type="text" class="form-control" id="domicile_nomvoie_defunt" placeholder="Nom voie">
                                </div>
                                </div>

                                <div class="row">



                                </div>
                                <div class="row">

                                </div>
                                <div class="row">


                                </div>
                                <div class="row">
                                </div>

                            </section>
                            <!-- Step 2 -->
                            <h6>Conjoint</h6>
                            <section>
                                <div class="d-flex justify-content-end align-items-center">
                                    <button type="button" id="clear_conjoint" class="btn btn-danger  text-white" ></i> Vider </button>
                                    <button type="button" id="search_conjoint" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".conjoint-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche du conjoint(e)</button>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="ligne">
                                        <h4>INFORMATIONS SUR L'IDENTITE</h4>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input name="code_conjoint" id="code_conjoint" type="hidden" readonly>
                                            <label class="form-label">Nom(s) Conjoint </label>
                                            <input type="text" class="form-control @error('nom_conjoint') is-invalid @enderror" value="{{ old("nom_conjoint") }}"  id="nom_conjoint" name="nom_conjoint">
                                            @error("nom_conjoint")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Prénom(s) Conjoint</label>
                                            <input type="text" class="form-control @error('prenom_conjoint') is-invalid @enderror" value="{{ old("prenom_conjoint") }}"  id="prenom_conjoint"  name="prenom_conjoint">
                                            @error("prenom_conjoint")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Sexe du conjoint<span class="text-danger">*</span></label>
                                        <select id="sexe_conjoint" name="sexe_conjoint" class="form-control form-control wide">
                                            <option value="">Selectionner</option>
                                            <option value="M">Masculin</option>
                                            <option value="F">Féminin</option>
                                        </select>
                                    </div>

                                     <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Date naissance Conjoint</label>
                                            <input  max="<?php  $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 18 years'));?>" type="date" class="form-control @error('date_naissance_conjoint') is-invalid @enderror" value="{{ old("date_naissance_conjoint") }}"  id="date_naissance_conjoint"  name="date_naissance_conjoint">
                                            <input type="checkbox" id="type_date_naissance_conjoint" value="ESTIME" name="type_date_naissance_conjoint"><label for="type_date_naissance_conjoint">date estimée</label>

                                            @error("date_naissance_conjoint")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                   </div>
                            </div>


                            <div class="row">
                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Lieu de naissance </label>
                                    {{-- <input type="text" class="form-control" name="lieu_naissance_conjoint" id="lieu_naissance_conjoint" placeholder="Lieu de naissance"> --}}
                                    <select name="lieu_naissance_conjoint" class="form-control required" id="lieu_naissance_conjoint">
                                        <option disabled>Choisissez</option>
                                        @foreach ($localites as $localite)
                                            <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Nationalité du conjoint<span class="text-danger">*</span></label>
                                    <select id="code_nationalite_conjoint" name="code_nationalite_conjoint" class="form-control form-control wide">
                                            <option>Choisissez</option>
                                        @foreach ($nationalites as $nationalite)
                                            <option value="{{ $nationalite->code_nationalite }}">{{ $nationalite->lib_nationalite }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Niveau d'instruction du conjoint</label>
                                    <select id="niveau_instruction_conjoint" name="niveau_instruction_conjoint" class="form-control form-control wide">
                                            <option disabled selected>Choisissez</option>
                                        @foreach ($instructions as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                    @error("niveau_instruction_conjoint")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Profession du conjoint</label>
                                    <select id="code_profession_conjoint" name="code_profession_conjoint" class="form-control form-control wide">
                                        <option>Choisissez</option>
                                        @foreach ($professions as $item)
                                            <option value="{{ $item->code_profession }}">{{ $item->lib_profession }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Fin identité --}}

                                <div class="ligne">
                                    <h4>INFORMATIONS SUR LES CONTACTS</h4>
                                </div>

                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Téléphone conjoint<span class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <select name="telephone_conjoint" id="telephone_conjoint" class="form-control">
                                                @forelse ($countries as $code)
                                                    <option value="{{ $code->dial_code }}">({{ $code->dial_code }}) {{ $code->name }}</option>
                                                @empty

                                                @endforelse

                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="number" min="0" minlength="9" maxlength="9" id="telephone_conjoint" name="telephone_conjoint" class="form-control @error('telephone_mere') is-invalid @enderror " placeholder="Téléphone Mère">
                                        </div>
                                    </div>
                                    @error("telephone_conjoint")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>


                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Statut <span class="text-danger">*</span></label>
                                    <select id="statut_personne_conjoint"  class="form-control form-control wide">
                                        <option value="VIVANT">Vivant(e)</option>
                                        <option value="DECEDE">Décédé(e)</option>
                                    </select>
                                </div>


                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Type pièce d'identité</label>
                                    <select id="code_type_document_conjoint" name="code_type_document_conjoint" class="form-control form-control wide">
                                            <option disabled selected>Choisissez</option>
                                        @foreach ($typedocuments as $item)
                                            <option value="{{ $item->code_type_document }}">{{ $item->lib_type_document  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Numéro pièce d'identité</label>
                                    <input type="text" id="numero_document_conjoint" name="numero_document_conjoint" class="form-control form-control wide" placeholder="Numéro du document">
                                </div>

                            </div>

                            <div class="ligne">
                                <h4>INFORMATIONS SUR L'ADRESSE</h4>
                            </div>

                            <div class="row">
                                <div class="mb-2 col-md-2">
                                    <label class="form-label">Pays<span class="text-danger"></span></label>
                                    <select id="domicile_pays_conjoint" class="form-control required">
                                        <option value="">Choisissez</option>
                                        @foreach ($countries as $countrie)
                                            <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2 col-md-2">
                                    <label class="form-label">Commune/District<span class="text-danger"></span></label>
                                    <span id="departementcongo_conjoint">
                                        <select class="form-control" name="domicile_ville_conjoint" id="domicile_ville_conjoint">
                                            <option value="">Choisir</option>
                                            @foreach ($localites as $localite)
                                                <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                            @endforeach
                                        </select>
                                    </span>
                                    <span id="autredepartement_conjoint">
                                        <input type="text" class="form-control" id="domicile_ville_conjoint" placeholder="Ville ou département">
                                    </span>
                                </div>
                                {{-- <div class="mb-2 col-md-2">
                                    <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                                    <input type="text" class="form-control" id="domicile_quartier_conjoint" placeholder="Quartier ou village">
                                </div> --}}
                                <div class="mb-2 col-md-2">
                                    <label class="form-label">Arr/Com. Urb<span class="text-danger"></span></label>
                                    <span id="arrondissement_conjoint">
                                        <select class="form-control" id="domicile_arrondissement_conjoint">
                                        </select>
                                    </span>
                                </div>
                                <div class="mb-2 col-md-2">
                                    <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                                    <select class="form-control" id="domicile_quartier_conjoint">

                                    </select>
                                </div>
                                <div class="mb-2 col-md-2">
                                    <label class="form-label">Type voie<span class="text-danger"></span></label>
                                    <select class="form-control" id="domicile_typevoie_conjoint">
                                        <option value="">Choisir</option>
                                        <option value="Avenue">Avenue</option>
                                        <option value="Boulevard">Boulevard</option>
                                        <option value="Impasse">Impasse</option>
                                        <option value="Rue">Rue</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                </div>
                                <div class="mb-2 col-md-2">
                                    <label class="form-label">N° voie<span class="text-danger"></span></label>
                                    <input type="text" class="form-control" id="domicile_numero_conjoint" placeholder="N° voie">
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Nom voie<span class="text-danger"></span></label>
                                    <input type="text" class="form-control" id="domicile_nomvoie_conjoint" placeholder="Nom voie">
                                </div>
                            </div>

                            <div class="ligne">
                                <h4>INFORMATIONS SUR LE MARIAGE</h4>
                            </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                        <label class="form-label">Date de mariage </label>
                                        <input type="date" class="form-control @error('date_mariage') is-invalid @enderror" value="{{ old("date_mariage") }}" placeholder="" id="date_mariage" name="date_mariage" max="{{ \Carbon\Carbon::now()->format('Y-m-d'); }}">
                                        @error("date_mariage")
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                        <label class="form-label">Centre d'état civil du mariage </label>
                                        {{-- <select id="cec_mariage" name="cec_mariage" class="form-select form-control ">
                                                <option disabled selected>Choisissez</option>
                                            @foreach ($religions as $religion)
                                                <option value="{{ $religion->code_religion }}">{{ $religion->lib_religion }}</option>
                                            @endforeach
                                        </select> --}}

                                        <input type="text" class="form-control  @error('cec_mariage') is-invalid @enderror" value="{{ old("cec_mariage") }}" id="cec_mariage" name="cec_mariage">
                                            @error("cec_mariage")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                       </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                        <label class="form-label"> Option du mariage.
                                            <!-- <span class="text-danger">*</span> -->
                                        </label>
                                        <select id="code_regime" name="code_regime" class="form-select form-control ">
                                            <option disabled selected>Choisissez</option>
                                            @foreach ($regimes as $regime)
                                                <option value="{{ $regime->code_regime }}">{{ $regime->lib_regime }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select a one.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">N° acte de mariage
                                            <!-- <span class="text-danger">*</span> -->
                                        </label>
                                            <input type="text" class="form-control" name="num_acte_mariage" id="num_acte_mariage" placeholder="" >
                                            <div class="invalid-feedback">
                                                Please enter a Currency.
                                            </div>
                                    </div>
                                   </div>
                                </div>
                            </section>
                             <h6>Père</h6>
                                <section>
                                    <div class="d-flex justify-content-end align-items-center" >
                                        <button type="button" id="clear_pere" class="btn btn-danger  text-white" ></i> Vider </button>
                                        <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".pere-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche du père</button>
                                    </div>
                                    <hr>
                                    <div class="ligne">
                                        <h4>INFORMATIONS SUR L'IDENTITE</h4>
                                    </div>
                                    <div class="row">
                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Nom(s) père <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('nom_pere') is-invalid @enderror" placeholder="Nom du père" name="nom_pere" id="nom_pere">
                                            @error("nom_pere")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Prénom(s) du père </label>
                                            <input type="text" class="form-control" placeholder="Prénom du père" name="prenom_pere" id="prenom_pere">

                                        </div>
                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Date de naissance du père<span class="text-danger">*</span></label>
                                            <input type="date" name="date_naissance_pere" max="<?php echo date('Y-m-d');?>" class="form-control @error('date_naissance_pere') is-invalid @enderror" id="date_naissance_pere">
                                            @error("date_naissance_pere")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                            <input type="checkbox" id="type_date_naissance_pere" value="ESTIME" name="type_date_naissance_pere"><label for="type_date_naissance_pere">date estimée</label>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Lieu de naissance père</label>
                                            {{-- <input type="text" name="lieu_naissance_pere" class="form-control" placeholder="Lieu de naissance"> --}}
                                            <select name="lieu_naissance_pere" class="form-control required" id="lieu_naissance_pere">
                                                <option disabled selected>Choisissez</option>
                                                @foreach ($localites as $localite)
                                                    <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Nationalité du père<span class="text-danger">*</span></label>
                                            <select name="code_nationalite_pere" id="code_nationalite_pere" class="form-control @error('code_nationalite_pere') is-invalid @enderror">
                                                    <option disabled selected>Choisissez</option>
                                                @foreach ($nationalites as $nationalite)
                                                    <option value="{{ $nationalite->code_nationalite }}">{{ $nationalite->lib_nationalite }}</option>
                                                @endforeach

                                            </select>
                                            @error("code_nationalite_pere")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Profession du père<span class="text-danger">*</span></label>
                                            <select name="code_profession_pere" id="code_profession_pere" class="form-control form-control wide">
                                                    <option disabled selected>Choisissez</option>
                                                @foreach ($professions as $item)
                                                    <option value="{{ $item->code_profession }}">{{ $item->lib_profession }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Niveau d'instruction du pere</label>
                                            <select id="niveau_instruction_pere" name="niveau_instruction_pere" class="form-control form-control wide">
                                                    <option disabled selected>Choisissez</option>
                                                @foreach ($instructions as $item)
                                                    <option value="{{ $item }}">{{ $item }}</option>
                                                @endforeach
                                            </select>
                                            @error("niveau_instruction_pere")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Statut <span class="text-danger">*</span></label>
                                            <select id="statut_personne_pere" name="statut_personne_pere" class="form-control @error('statut_personne_pere') is-invalid @enderror">
                                                <option value="VIVANT">Vivant(e)</option>
                                                <option value="DECEDE">Décédé(e)</option>
                                            </select>
                                            @error("statut_personne_pere")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="ligne">
                                        <h4>INFORMATIONS SUR LES CONTACTS</h4>
                                    </div>
                                    <div class="row">
                                            <div class="mb-2 col-md-4">
                                                <label class="form-label">Téléphone père<span class="text-danger">*</span></label>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <select name="code_pays_pere" id="code_pays_pere" class="form-control">
                                                            @forelse ($countries as $code)
                                                                <option value="{{ $code->dial_code }}">({{ $code->dial_code }}) {{ $code->name }}</option>
                                                            @empty

                                                            @endforelse

                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="number" min="0" minlength="9" maxlength="9" id="telephone_pere" name="telephone_pere" class="form-control @error('telephone_pere') is-invalid @enderror " placeholder="Téléphone père">
                                                    </div>
                                                </div>
                                                @error("telephone_pere")
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="mb-2 col-md-4">
                                                <label class="form-label">Type pièce d'identité</label>
                                                <select id="code_type_document_pere" name="code_type_document_pere" class="form-control form-control wide">
                                                        <option>Choisissez</option>
                                                    @foreach ($typedocuments as $item)
                                                        <option value="{{ $item->code_type_document }}">{{ $item->lib_type_document  }}</option>
                                                    @endforeach
                                                </select>

                                            </div>

                                            <div class="mb-2 col-md-4">
                                                <label class="form-label">Numéro pièce d'identité</label>
                                                <input type="text" name="numero_document_pere" id="numero_document_pere" class="form-control form-control wide" placeholder="Numéro du document">
                                            </div>

                                        <div class="ligne">
                                            <h4>INFORMATIONS SUR L'ADRESSE</h4>
                                        </div>
                                        <div class="mb-2 col-md-2">
                                            <label class="form-label">Pays<span class="text-danger"></span></label>
                                            <select id="domicile_pays_pere" class="form-control required">
                                                <option value="">Choisissez</option>
                                                @foreach ($countries as $countrie)
                                                    <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2 col-md-2">
                                            <label class="form-label">Commune/District<span class="text-danger"></span></label>
                                            <span id="departementcongo_pere">
                                                <select class="form-control" id="domicile_ville_pere">
                                                    <option value="">Choisir</option>
                                                    @foreach ($localites as $localite)
                                                        <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                                    @endforeach
                                                </select>
                                            </span>
                                            <span id="autredepartement_pere">
                                                <input type="text" class="form-control" id="domicile_ville_pere" placeholder="Ville ou département">
                                            </span>
                                        </div>
                                        <div class="mb-2 col-md-2">
                                            <label class="form-label">Arr/Com. Urb<span class="text-danger"></span></label>
                                            <span id="arrondissement_pere">
                                                <select class="form-control" id="domicile_arrondissement_pere">
                                                </select>
                                            </span>
                                        </div>
                                        <div class="mb-2 col-md-2">
                                            <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                                            <select class="form-control" id="domicile_quartier_pere">

                                            </select>
                                        </div>
                                        <div class="mb-2 col-md-2">
                                            <label class="form-label">Type voie<span class="text-danger"></span></label>
                                            <select class="form-control" id="domicile_typevoie_pere">
                                                <option value="">Choisir</option>
                                                <option value="Avenue">Avenue</option>
                                                <option value="Boulevard">Boulevard</option>
                                                <option value="Impasse">Impasse</option>
                                                <option value="Rue">Rue</option>
                                                <option value="Autre">Autre</option>
                                            </select>
                                        </div>
                                        <div class="mb-2 col-md-2">
                                            <label class="form-label">N° voie<span class="text-danger"></span></label>
                                            <input type="text" class="form-control" id="domicile_numero_pere" placeholder="N° voie">
                                        </div>
                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Nom voie<span class="text-danger"></span></label>
                                            <input type="text" class="form-control" id="domicile_nomvoie_pere" placeholder="Nom voie">
                                        </div>
                                    </div>
                                </section>

                                <h6>Mère</h6>
                                <section>
                                    <div class="d-flex justify-content-end align-items-center">
                                        <button type="button" id="clear_mere" class="btn btn-danger  text-white" ></i> Vider </button>
                                        <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".mere-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche de la mère</button>
                                    </div>
                                    <hr>
                                    <div class="ligne">
                                        <h4>INFORMATIONS SUR L'IDENTITE</h4>
                                    </div>
                                    <div class="row">
                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Nom(s) mère <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('nom_mere') is-invalid @enderror"  placeholder="Nom de la mère" id="nom_mere" name="nom_mere">
                                            @error("nom_mere")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Prénom(s) du mère </label>
                                            <input type="text" class="form-control" placeholder="Prénom de la mère" id="prenom_mere" class="prenom_mere">

                                        </div>
                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Date de naissance de la mère<span class="text-danger">*</span></label>
                                            <input type="date" max="<?php $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 12 years'));?>"
                                            class="form-control @error('date_naissance_mere') is-invalid @enderror" id="date_naissance_mere" name="date_naissance_mere">
                                            @error("date_naissance_mere")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                            <input type="checkbox" id="type_date_naissance_mere" value="ESTIME" name="type_date_naissance_mere"><label for="type_date_naissance_mere">date estimée</label>

                                        </div>
                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Lieu de naissance mère</label>
                                            {{-- <input type="text" class="form-control" id="lieu_naissance_mere" placeholder="Lieu de naissance"> --}}
                                            <select name="lieu_naissance_mere" class="form-control required" id="lieu_naissance_mere">
                                                <option disabled selected>Choisissez</option>
                                                @foreach ($localites as $localite)
                                                    <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Nationalité de la mère<span class="text-danger">*</span></label>
                                            <select id="code_nationalite_mere" name="code_nationalite_mere" class="form-control form-control wide">
                                                    <option disabled selected>Choisissez</option>
                                                @foreach ($nationalites as $nationalite)
                                                    <option value="{{ $nationalite->code_nationalite }}">{{ $nationalite->lib_nationalite }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Niveau d'instruction de la mere</label>
                                            <select id="niveau_instruction_mere" name="niveau_instruction_mere" class="form-control form-control wide">
                                                    <option disabled selected>Choisissez</option>
                                                @foreach ($instructions as $item)
                                                    <option value="{{ $item }}">{{ $item }}</option>
                                                @endforeach
                                            </select>
                                            @error("niveau_instruction_mere")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Profession de la mère</label>
                                            <select id="code_profession_mere" name="code_profession_mere" class="form-control form-control wide">
                                                    <option disabled selected>Choisissez</option>
                                                @foreach ($professions as $item)
                                                    <option value="{{ $item->code_profession }}">{{ $item->lib_profession }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Statut <span class="text-danger">*</span></label>
                                            <select id="statut_personne_mere" name="statut_personne_mere" class="form-control @error('statut_personne_mere') is-invalid @enderror">
                                                <option value="VIVANT">Vivant(e)</option>
                                                <option value="DECEDE">Décédé(e)</option>
                                            </select>
                                            @error("statut_personne_mere")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="ligne">
                                        <h4>INFORMATIONS SUR L'ADRESSE</h4>
                                    </div>
                                    <div class="row">
                                        <div class="mb-2 col-md-2">
                                            <label class="form-label">Pays<span class="text-danger"></span></label>
                                            <select id="domicile_pays_mere" class="form-control required">
                                                <option value="">Choisissez</option>
                                                @foreach ($countries as $countrie)
                                                    <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2 col-md-2">
                                            <label class="form-label">Commune/District<span class="text-danger"></span></label>
                                            <span id="departementcongo_mere">
                                                <select class="form-control" name="domicile_ville_mere" id="domicile_ville_mere">
                                                    <option value="">Choisir</option>
                                                    @foreach ($localites as $localite)
                                                        <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                                    @endforeach
                                                </select>
                                            </span>
                                            <span id="autredepartement_mere">
                                                <input type="text" class="form-control" id="domicile_ville_mere" placeholder="Ville ou département">
                                            </span>
                                        </div>
                                        {{-- <div class="mb-2 col-md-2">
                                            <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                                            <input type="text" class="form-control" id="domicile_quartier_mere" placeholder="Quartier ou village">
                                        </div> --}}
                                        <div class="mb-2 col-md-2">
                                            <label class="form-label">Arr/Com. Urb<span class="text-danger"></span></label>
                                            <span id="arrondissement_mere">
                                                <select class="form-control" id="domicile_arrondissement_mere">
                                                </select>
                                            </span>
                                        </div>
                                        <div class="mb-2 col-md-2">
                                            <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                                            <select class="form-control" id="domicile_quartier_mere">

                                            </select>
                                        </div>
                                        <div class="mb-2 col-md-2">
                                            <label class="form-label">Type voie<span class="text-danger"></span></label>
                                            <select class="form-control" id="domicile_typevoie_mere">
                                                <option value="">Choisir</option>
                                                <option value="Avenue">Avenue</option>
                                                <option value="Boulevard">Boulevard</option>
                                                <option value="Impasse">Impasse</option>
                                                <option value="Rue">Rue</option>
                                                <option value="Autre">Autre</option>
                                            </select>
                                        </div>
                                        <div class="mb-2 col-md-2">
                                            <label class="form-label">N° voie<span class="text-danger"></span></label>
                                            <input type="text" class="form-control" id="domicile_numero_mere" placeholder="N° voie">
                                        </div>
                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Nom voie<span class="text-danger"></span></label>
                                            <input type="text" class="form-control" id="domicile_nomvoie_mere" placeholder="Nom voie">
                                        </div>
                                </div>
                                <div class="ligne">
                                    <h4>INFORMATIONS SUR LES CONTACTS</h4>
                                </div>
                                    <div class="row">
                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Téléphone Mère<span class="text-danger">*</span></label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <select name="code_pays_mere" id="code_pays_mere" class="form-control">
                                                        @forelse ($countries as $code)
                                                            <option value="{{ $code->dial_code }}">({{ $code->dial_code }}) {{ $code->name }}</option>
                                                        @empty

                                                        @endforelse

                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="number" min="0" minlength="9" maxlength="9" id="telephone_mere" name="telephone_mere" class="form-control @error('telephone_mere') is-invalid @enderror " placeholder="Téléphone Mère">
                                                </div>
                                            </div>
                                            @error("telephone_mere")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                         <div class="mb-2 col-md-4">
                                            <label class="form-label">Type pièce d'identité</label>
                                            <select id="code_type_document_mere" name="code_type_document_mere" class="form-control form-control wide">
                                                    <option>Choisissez</option>
                                                @foreach ($typedocuments as $item)
                                                    <option value="{{ $item->code_type_document }}">{{ $item->lib_type_document  }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Numéro pièce d'identité</label>
                                            <input type="text" id="numero_document_mere" name="numero_document_mere" class="form-control form-control wide" placeholder="Numéro du document">
                                        </div>
                                    </div>
                                </section>
                            <!-- Step 3 -->
                            <h6>Déclarant</h6>
                            <section>
                                <div class="d-flex justify-content-end align-items-center" id="declarant_click">
                                    <button type="button" id="clear_declarant" class="btn btn-danger  text-white" ></i> Vider </button>
                                    <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".declarant-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche du déclarant</button>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="mb-2 col-sm-3" id="hide_pere">
                                        <label for="dewey">Père</label>
                                        <input type="radio" id="peredeclarant" name="autredeclarant" value="pere">
                                    </div>


                                    <div class="mb-2 col-sm-3" id="hide_mere">
                                        <label for="dewey">Mère</label>
                                        <input type="radio" id="meredeclarant" name="autredeclarant" value="mere">
                                    </div>

                                    <div class="mb-2 col-sm-3">
                                        <label for="dewey">Autre</label>
                                        <input type="radio" id="autredeclarant" name="autredeclarant"  value="autre">
                                    </div>

                                    <div id="conjoint_click"  class="mb-2 col-sm-3">
                                        <label for="dewey">Conjoint</label>
                                        <input type="radio" id="autredeclarant" name="autredeclarant"  value="conjoint">
                                    </div>

                                    <div class="ligne">
                                        <h4>INFORMATIONS SUR L'IDENTITE</h4>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Nom(s) déclarant <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control required @error('nom_declarant') is-invalid @enderror" value="{{ old("nom_declarant") }}" placeholder="" name="nom_declarant" id="nom_declarant">
                                            @error("nom_declarant")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                            <div class="invalid-feedback">
                                                Please enter a Currency.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                        <label class="form-label">Prénom(s) déclarant</label>
                                        <input type="text" class="form-control  @error('prenom_declarant') is-invalid @enderror" value="{{ old("prenom_declarant") }}" placeholder="" name="prenom_declarant" id="prenom_declarant">
                                        @error("prenom_declarant")
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                   </div>
                                   <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Date de naissance du déclarant </label>
                                            <input name="date_naissance_declarant" type="date" class="form-control" placeholder="" id="date_naissance_declarant"  max="{{ \Carbon\Carbon::now()->subYears(18)->format('Y-m-d'); }}" >
                                            <input type="checkbox" id="type_date_naissance_declarant" value="ESTIME" name="type_date_naissance_declarant"><label for="type_date_naissance_declarant">date estimée</label>

                                            @error("date_naissance_declarant")
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Lieu de naissance du déclarant <span class="text-danger">*</span></label>
                                         {{-- <input type="text" class="form-control" name="lieu_naissance_declarant" id="lieu_naissance_declarant" placeholder="Lieu de naissance"> --}}
                                         <select name="lieu_naissance_declarant" class="form-control required" id="lieu_naissance_declarant">
                                            <option disabled selected>Choisissez</option>
                                            @foreach ($localites as $localite)
                                                <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Nationalité du déclarant <span class="text-danger">*</span></label>
                                        <select id="code_nationalite_declarant" name="code_nationalite_declarant" class="form-select form-control required">
                                            <option disabled selected>Choisissez</option>
                                            @foreach ($nationalites as $nationalite)
                                                <option value="{{ $nationalite->code_nationalite }}" >{{ $nationalite->lib_nationalite}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                               </div>

                               <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Sexe <span class="text-danger">*</span></label>
                                    <select id="sexe_declarant" name="sexe_declarant" class="form-control  @error('sexe_declarant') is-invalid @enderror">
                                        <option disabled selected>Choisissez</option>
                                        <option value="M">Masculin</option>
                                        <option value="F">Feminin</option>
                                    </select>
                                    @error("sexe_declarant")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                           </div>


                            </div>

                            <div class="row">
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Niveau d'instruction du declarant</label>
                                    <select id="niveau_instruction_declarant" name="niveau_instruction_declarant" class="form-control form-control wide">
                                            <option disabled selected>Choisissez</option>
                                        @foreach ($instructions as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                    @error("niveau_instruction_declarant")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Profession déclarant <span class="text-danger">*</span></label>
                                        <select name="code_profession_declarant" id="code_profession_declarant" class="form-control  @error('code_profession_declarant') is-invalid @enderror">
                                            <option disabled selected>Choisissez</option>
                                            @foreach ($professions as $profession)
                                                <option value="{{ $profession->code_profession }}">{{ $profession->lib_profession }}</option>
                                            @endforeach
                                        </select>
                                        @error("code_profession_declarant")
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Filiation </label>
                                        <select name="code_filiation_declarant" id="code_filiation_declarant" class="form-select form-control required">
                                                <option disabled selected> Choisissez</option>
                                            @foreach ($filiations as $filiation)
                                                <option class="{{$filiation->code_filiation }}" value="{{ $filiation->code_filiation }}">{{ $filiation->lib_filiation }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Please enter a Currency.
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="ligne">
                                <h4>INFORMATIONS SUR LES CONTACTS</h4>
                            </div>
                            <div class="row">
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Téléphone déclarant</label>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <select name="code_pays_declarant" id="code_pays_declarant" class="form-control required">
                                                @forelse ($countries as $code)
                                                    <option value="{{ $code->dial_code }}">({{ $code->dial_code }}) {{ $code->name }}</option>
                                                @empty

                                                @endforelse

                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="number" min="0" minlength="9" maxlength="9" id="telephone_declarant" name="telephone_declarant" class="form-control required  @error('statut_personne_mere') is-invalid @enderror " placeholder="Téléphone déclarant">
                                        </div>
                                    </div>
                                    @error("statut_personne_mere")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Type pièce d'identité</label>
                                    <select id="code_type_document_declarant" name="code_type_document_declarant" class="form-control form-control wide">
                                            <option>Choisissez</option>
                                        @foreach ($typedocuments as $item)
                                            <option value="{{ $item->code_type_document }}">{{ $item->lib_type_document  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Numéro pièce d'identité</label>
                                    <input type="text" id="numero_document_declarant" name="numero_document_declarant" class="form-control form-control wide" placeholder="Numéro du document">
                                </div>
                            </div>


                            <div class="ligne">
                                <h4>INFORMATIONS SUR L'ADRESSE</h4>
                            </div>
                            <div class="row">
                                <div class="mb-2 col-md-2">
                                    <label class="form-label">Pays<span class="text-danger"></span></label>
                                    <select id="domicile_pays_declarant" class="form-control required">
                                        <option value="">Choisissez</option>
                                        @foreach ($countries as $countrie)
                                            <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2 col-md-2">
                                    <label class="form-label">Commune/District<span class="text-danger"></span></label>
                                    <span id="departementcongo_declarant">
                                        <select class="form-control" name="domicile_ville_declarant" id="domicile_ville_declarant">
                                            <option value="">Choisir</option>
                                            @foreach ($localites as $localite)
                                                <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                            @endforeach
                                        </select>
                                    </span>
                                    <span id="autredepartement_declarant">
                                        <input type="text" class="form-control" id="domicile_ville_declarant" placeholder="Ville ou département">
                                    </span>
                                </div>
                                {{-- <div class="mb-2 col-md-2">
                                    <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                                    <input type="text" class="form-control" id="domicile_quartier_declarant" placeholder="Quartier ou village">
                                </div> --}}
                                <div class="mb-2 col-md-2">
                                    <label class="form-label">Arr/Com. Urb<span class="text-danger"></span></label>
                                    <span id="arrondissement_declarant">
                                        <select class="form-control" id="domicile_arrondissement_declarant">
                                        </select>
                                    </span>
                                </div>
                                <div class="mb-2 col-md-2">
                                    <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                                    <select class="form-control" id="domicile_quartier_declarant">

                                    </select>
                                </div>
                                <div class="mb-2 col-md-2">
                                    <label class="form-label">Type voie<span class="text-danger"></span></label>
                                    <select class="form-control" id="domicile_typevoie_declarant">
                                        <option value="">Choisir</option>
                                        <option value="Avenue">Avenue</option>
                                        <option value="Boulevard">Boulevard</option>
                                        <option value="Impasse">Impasse</option>
                                        <option value="Rue">Rue</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                </div>
                                <div class="mb-2 col-md-2">
                                    <label class="form-label">N° voie<span class="text-danger"></span></label>
                                    <input type="text" class="form-control" id="domicile_numero_declarant" placeholder="N° voie">
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Nom voie<span class="text-danger"></span></label>
                                    <input type="text" class="form-control" id="domicile_nomvoie_declarant" placeholder="Nom voie">
                                </div>

                                <div class="ligne">
                                    <h4>INFORMATIONS SUR LES CAUSES DU DECES</h4>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{-- <label class="form-label"> Causes décès <span
                                            class="text-danger">*</span>
                                    </label>
                                        <select name="code_cause_deces" id="code_cause_deces" class="form-select form-control required" multiple>
                                            <option disabled selected>Choisissez</option>
                                            @foreach ($causesDeces as $cause_deces)
                                                <option value="{{ $cause_deces->code_cause_deces }}">{{ $cause_deces->lib_cause_deces }}</option>
                                            @endforeach
                                        </select> --}}
                                        {{-- <label class="form-label">Causes décès</label> --}}
										<select multiple class="default-select form-control wide mt-3" name="code_cause_deces" id="code_cause_deces">
                                            @foreach ($causesDeces as $cause_deces)
                                                <option value="{{ $cause_deces->code_cause_deces }}">{{ $cause_deces->lib_cause_deces }}</option>
                                            @endforeach
										</select>
                                    </div>
                                </div>
                            </div>

                            </section>
                        </form>
                    </div>

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
@include("deces::certificat-transcription.js.create")
@endsection










