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
                    <div class="card-body">
                        {{--  <h4 class="card-title">Step wizard with validation</h4>
                        <h6 class="card-subtitle">You can us the validation like what we did</h6>  --}}
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

                                </div>

                                <div class="row">

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
                                            @error("date_naissance_defunt")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

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

                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Lieu de naissance <span class="text-danger">*</span></label>
                                             <input type="text" class="form-control" id="code_localite" placeholder="Lieu de naissance" name="code_localite">

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
                                        <select id="profession_defunt" name="profession_defunt" class="form-select form-control required">
                                            <option disabled selected>Choisissez</option>
                                            @foreach ($professions as $profession)
                                                <option value="{{ $profession->code_profession }}">{{ $profession->lib_profession }}</option>
                                            @endforeach
                                        </select>
                                    </div>
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
                                </div>

                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="form-group">
                                        <label class="form-label">Nationalité <span class="text-danger">*</span></label>
                                        <select id="nationalite_defunt" name="nationalite_defunt" class="form-select form-control required">
                                            <option disabled selected>Choisissez</option>
                                            @foreach ($nationalites as $nationalite)
                                                <option value="{{ $nationalite->code_nationalite }}" >{{ $nationalite->lib_nationalite}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                   </div>
                                   <div class="mb-3 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label"> Adresse défunt<span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control  @error('domicile_defunt') is-invalid @enderror" value="{{ old("domicile_defunt") }}" id="domicile_defunt" name="domicile_defunt">
                                        </div>
                                    </div>

                                    <div class="mb-2 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Lieu de survenance <span class="text-danger">*</span> </label>
                                            <select name="lieu_survenance_code" id="lieu_survenance_code" class="form-select form-control required">
                                                    <option disabled selected>Choisissez</option>
                                                @foreach ($lieusurvenances as $lieusurvenance)
                                                    <option value="{{ $lieusurvenance->code_lieu_survenance }}">{{ $lieusurvenance->lib_lieu_survenance }}</option>
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



                                </div>

                                <div class="row">

                                </div>
                                <div class="row">


                                </div>
                                <div class="row">
                                    {{-- @php
                                        dd($causesDeces);
                                    @endphp --}}
                                    {{-- <div class="col-xl-6 col-lg-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Checkbox</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-xl-4 col-xxl-6 col-6">
                                                        <div class="form-check custom-checkbox mb-3">
                                                            <input type="checkbox" class="form-check-input" id="customCheckBox1" required>
                                                            <label class="form-check-label" for="customCheckBox1">Checkbox 1</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-xxl-6 col-6">
                                                        <div class="form-check custom-checkbox mb-3 checkbox-info">
                                                            <input type="checkbox" class="form-check-input" checked id="customCheckBox2" required>
                                                            <label class="form-check-label" for="customCheckBox2">Checkbox 2</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-xxl-6 col-6">
                                                        <div class="form-check custom-checkbox mb-3 checkbox-success">
                                                            <input type="checkbox" class="form-check-input" checked id="customCheckBox3" required>
                                                            <label class="form-check-label" for="customCheckBox3">Checkbox 3</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-xxl-6 col-6">
                                                        <div class="form-check custom-checkbox mb-3 checkbox-warning">
                                                            <input type="checkbox" class="form-check-input" checked id="customCheckBox4" required>
                                                            <label class="form-check-label" for="customCheckBox4">Checkbox 4</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-xxl-6 col-6">
                                                        <div class="form-check custom-checkbox mb-3 checkbox-danger">
                                                            <input type="checkbox" class="form-check-input" checked id="customCheckBox5" required>
                                                            <label class="form-check-label" for="customCheckBox5">Checkbox 5</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-check custom-checkbox mb-3 check-xs">
                                                            <input type="checkbox" class="form-check-input" checked id="customCheckBox6" required>
                                                            <label class="form-check-label" for="customCheckBox6"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-check custom-checkbox mb-3 checkbox-info">
                                                            <input type="checkbox" class="form-check-input" checked id="customCheckBox7" required>
                                                            <label class="form-check-label" for="customCheckBox7"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-check custom-checkbox mb-3 checkbox-success check-lg">
                                                            <input type="checkbox" class="form-check-input" checked id="customCheckBox8" required>
                                                            <label class="form-check-label" for="customCheckBox8"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-check custom-checkbox mb-3 checkbox-warning check-xl">
                                                            <input type="checkbox" class="form-check-input" checked id="customCheckBox9" required>
                                                            <label class="form-check-label" for="customCheckBox9"></label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div> --}}
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
                                    <div class="col-md-3">
                                    <div class="form-group">
                                        <input name="code_conjoint" id="code_conjoint" type="hidden" readonly>
                                        <label class="form-label">Nom(s) Conjoint </label>
                                        <input type="text" class="form-control"lass="form-control @error('nom_conjoint') is-invalid @enderror" value="{{ old("nom_conjoint") }}"  id="nom_conjoint" name="nom_conjoint">
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
                                    <input type="text" class="form-control" name="lieu_naissance_conjoint" id="lieu_naissance_conjoint" placeholder="Lieu de naissance">
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
                                    <label class="form-label">Profession du conjoint</label>
                                    <select id="profession_conjoint" name="profession_conjoint" class="form-control form-control wide">
                                        <option>Choisissez</option>
                                        @foreach ($professions as $item)
                                            <option value="{{ $item->code_profession }}">{{ $item->lib_profession }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Téléphone conjoint</label>
                                    <input type="number" min="0" minlength="9" maxlength="9" id="telephone_conjoint" name="telephone_conjoint" class="form-control form-control wide" placeholder="Téléphone déclarant">
                                </div>
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
                                    <div class="d-flex justify-content-end align-items-center">
                                        <button type="button" id="clear_pere" class="btn btn-danger  text-white" ></i> Vider </button>
                                        <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".pere-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche du père</button>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Nom(s) père <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Nom du père" name="nom_pere" id="nom_pere">
                                        </div>

                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Prénom(s) du père </label>
                                            <input type="text" class="form-control" placeholder="Prénom du père" name="prenom_pere" id="prenom_pere">

                                        </div>
                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Date de naissance du père<span class="text-danger">*</span></label>
                                            <input type="date" name="date_naissance_pere" max="<?php echo date('Y-m-d');?>" class="form-control" id="date_naissance_pere">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Lieu de naissance père</label>
                                            <input type="text" name="lieu_naissance_pere" class="form-control" id="lieu_naissance_pere" placeholder="Lieu de naissance">
                                        </div>
                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Domicile du père<span class="text-danger">*</span></label>
                                            <input type="text" name="domicile_pere" class="form-control" id="domicile_pere" placeholder="Domicile du père">
                                        </div>
                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Nationalité du père<span class="text-danger">*</span></label>
                                            <select name="code_nationalite_pere" id="code_nationalite_pere" class="form-control form-control wide">
                                                    <option disabled selected>Choisissez</option>
                                                @foreach ($nationalites as $nationalite)
                                                    <option value="{{ $nationalite->code_nationalite }}">{{ $nationalite->lib_nationalite }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
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
                                            <label class="form-label">Téléphone père<span class="text-danger">*</span></label>
                                            <input type="text" name="telephone_pere" id="telephone_pere" class="form-control form-control wide" placeholder="Téléphone mère">
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
                                    </div>
                                </section>

                                <h6>Mère</h6>
                                <section>
                                    <div class="d-flex justify-content-end align-items-center">
                                        <button type="button" id="clear_mere" class="btn btn-danger  text-white" ></i> Vider </button>
                                        <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".mere-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche de la mère</button>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Nom(s) mère <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"  placeholder="Nom de la mère" id="nom_mere" name="nom_mere">
                                        </div>

                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Prénom(s) du mère </label>
                                            <input type="text" class="form-control" placeholder="Prénom de la mère" id="prenom_mere" class="prenom_mere">

                                        </div>
                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Date de naissance de la mère<span class="text-danger">*</span></label>
                                            <input type="date" max="<?php $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 12 years'));?>" min="<?php echo date('Y-m-d', strtotime($jour. ' - 100 years')); ?>" class="form-control" id="date_naissance_mere">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Lieu de naissance mère</label>
                                            <input type="text" class="form-control" id="lieu_naissance_mere" placeholder="Lieu de naissance">
                                        </div>
                                        <div class="mb-2 col-md-4">
                                            <label class="form-label">Domicile de la mère<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="domicile_mere" name="domicile_mere" placeholder="Domicile mère">
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

                                    </div>
                                    <div class="row">
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
                                            <label class="form-label">Téléphone mère</label>
                                            <input type="text" id="telephone_mere" name="telephone_mere" class="form-control form-control wide" placeholder="Téléphone mère">
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
                                <div class="d-flex justify-content-end align-items-center">
                                    <button type="button" id="clear_declarant" class="btn btn-danger  text-white" ></i> Vider </button>
                                    <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".declarant-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche du déclarant</button>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="mb-2 col-sm-3">
                                        <label for="dewey">Père</label>
                                        <input type="radio" id="peredeclarant" name="autredeclarant" value="pere">
                                    </div>


                                    <div class="mb-2 col-sm-3">
                                        <label for="dewey">Mère</label>
                                        <input type="radio" id="meredeclarant" name="autredeclarant" value="mere">
                                    </div>

                                    <div class="mb-2 col-sm-3">
                                        <label for="dewey">Autre</label>
                                        <input type="radio" id="autredeclarant" name="autredeclarant"  value="autre">
                                    </div>

                                    <div id="conjoint_click" class="mb-2 col-sm-3">
                                        <label for="dewey">Conjoint</label>
                                        <input type="radio" id="autredeclarant" name="autredeclarant"  value="conjoint">
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                        <label class="form-label">Nom(s) déclarant <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control required @error('nom_declarant') is-invalid @enderror" value="{{ old("nom_declarant") }}" placeholder="" name="nom_declarant" id="nom_declarant">
                                        @error("nom_defunt")
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
                                         <input type="text" class="form-control" name="lieu_naissance_declarant" id="lieu_naissance_declarant" placeholder="Lieu de naissance">
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
                                    <select id="sexe_declarant" name="sexe_declarant" class="form-select form-control required">
                                        <option disabled selected>Choisissez</option>
                                        <option value="M">Masculin</option>
                                        <option value="F">Feminin</option>
                                    </select>
                                </div>
                           </div>


                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Profession déclarant <span class="text-danger">*</span></label>
                                        <select name="code_profession_declarant" id="code_profession_declarant" class="form-select form-control required">
                                            <option disabled selected>Choisissez</option>
                                            @foreach ($professions as $profession)
                                                <option value="{{ $profession->code_profession }}">{{ $profession->lib_profession }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label"> Domicile du déclarant <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control  required" name="domicile_declarant" id="domicile_declarant"  rows="2" placeholder="" ></textarea>
                                        <div class="invalid-feedback">
                                            Please enter a Currency.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Filiation </label>
                                        <select name="code_filiation_declarant" id="code_filiation_declarant" class="form-select form-control required">
                                                <option disabled selected> Choisissez</option>
                                            @foreach ($filiations as $filiation)
                                                <option value="{{ $filiation->code_filiation }}">{{ $filiation->lib_filiation }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Please enter a Currency.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Téléphone déclarant <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control required" id="telephone_declarant">
                                    </div>
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
                                        <label class="form-label">Causes décès</label>
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
                                                <option value="" disabled>Choisir</option>
                                                <option value="M" selected>Masculin</option>
                                                <option value="F">Féminin</option>
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
                                                <option value="" disabled>Choisir</option>
                                                <option value="M">Masculin</option>
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
<script src="{{ asset('tpl/vendor/jquery-smartwizard/dist/js/jquery.smartWizard.js') }}"></script>
<script src="{{ asset('tpl/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
<!-- Form validate init -->
<script src="{{ asset('tpl/js/plugins-init/jquery.validate-init.js') }}"></script>

     <!-- Daterangepicker -->
     <script src="{{ asset("tpl/js/plugins-init/bs-daterange-picker-init.js") }}"></script>
     <!-- Clockpicker init -->
     <script src="{{ asset("tpl/js/plugins-init/clock-picker-init.js") }}"></script>
     <!-- asColorPicker init -->
     <script src="{{ asset("tpl/js/plugins-init/jquery-asColorPicker.init.js") }}"></script>
     <!-- Material color picker init -->
     <script src="{{ asset("tpl/js/plugins-init/material-date-picker-init.js") }}"></script>
     <!-- Pickdate -->
     <script src="{{ asset("tpl/js/plugins-init/pickadate-init.js") }}"></script>

    <!-- This Page JS -->
    <script src="{{ asset("tpl/wizard/assets/node_modules/wizard/jquery.steps.min.js") }}"></script>
    <script src="{{ asset("tpl/wizard/assets/node_modules/wizard/jquery.validate.min.js") }}"></script>
    <script src="{{ asset("tpl/wizard/assets/node_modules/sweetalert2/dist/sweetalert2.min.js") }}"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script>

    <script>
        //Custom design form example
        $(".tab-wizard").steps({
            headerTag: "h6",
            bodyTag: "section",
            transitionEffect: "fade",
            titleTemplate: '<span class="step">#index#</span> #title#',
            labels: {
                finish: "Submit"
            },
            onFinished: function (event, currentIndex) {
                Swal.fire("Déclaration Enrégistrée !", "Déclarion est en cours de traiatement, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.");

            }
        });

        // Rechercher un défunt
        $('#rechercher').on("click", function (event) {
            event.preventDefault();

            var nom = $("#nom_defunt_recherche");
            var prenom = $("#prenom_defunt_recherche");
            var sexe = $("#sexe_defunt_recherche");
            var telephone = $("#telephone_defunt_recherche");

            var data = {
                nom: nom.val(),
                prenom: prenom.val(),
                sexe: sexe.val(),
                telephone: telephone.val()
            };

            var int = 0;

            var table = '<div class="table-responsive">'+
                            '<table class="table table-responsive-md table-hover">'+
                                '<thead>'+
                                    '<tr>'+
                                        '<th>#</th>'+
                                        '<th><strong>Nom et prénom</strong></th>'+
                                        '<th><strong>Sexe</strong></th>'+
                                        '<th><strong>Téléphone</strong></th>'+
                                ' </tr>'+
                                '</thead>'+
                                '<tbody>';

            //traitement ajax
            $.ajax({
                    url: "{{ route('declarationNaissance.recherchePersonne') }}",
                    type: "POST",
                    data: data,

                    success: function(response){

                        if(response.personnes.length > 0){

                            for( var i=0; i < response.personnes.length ; i++){
                                int ++;
                                table +='<tr class="tr" data-choix="'+response.personnes[i].code_personne+'" data-nom="'+response.personnes[i].nom+'" data-prenom="'+response.personnes[i].prenom+'" data-date_naissance="'+response.personnes[i].date_naissance+'" data-sexe="'+response.personnes[i].sexe+'" data-adresse="'+response.personnes[i].adresse+'" data-telephone="'+response.personnes[i].telephone+'" data-code_nationalite="'+response.personnes[i].code_nationalite+'" data-code_profession="'+response.personnes[i].code_profession+'" data-lieu_naissance="'+response.personnes[i].lieu_naissance+'" data-code_localite="'+response.personnes[i].code_localite+'" data-niveau_instruction="'+response.personnes[i].niveau_instruction+'" data-nom="'+response.personnes[i].nom+'">'+
                                            '<td><strong>'+int+'</strong></td>'+
                                            '<td>'+response.personnes[i].nom+" "+response.personnes[i].prenom+'</td>'+
                                            '<td>'+response.personnes[i].sexe+'</td>'+
                                            '<td>'+response.personnes[i].telephone+'</td>';
                            }
                        }
                        table += "</tr></tbody></table></div>";
                        $("#resultatDefunt").html(table);

                        $("tr.tr").on("click", function ()
                        {
                            var choix = $(this).data('choix');
                            var nom = $(this).data('nom');
                            var prenom = $(this).data('prenom');
                            var date_naissance = $(this).data('date_naissance');
                            var sexe = $(this).data('sexe');
                            var adresse = $(this).data('adresse');
                            var telephone = $(this).data('telephone');
                            var code_nationalite = $(this).data('code_nationalite');
                            var code_profession = $(this).data('code_profession');
                            var lieu_naissance = $(this).data('lieu_naissance');
                            var niveau_instruction = $(this).data('niveau_instruction');

                            $("#code_defunt").val(choix);
                            $("#nom_defunt").val(nom);
                            $("#prenom_defunt").val(prenom);
                            $("#date_naissance_defunt").val(date_naissance);
                            $("#sexe_defunt").val(sexe);
                            $("#domicile_defunt").val(adresse);
                            $("#telephone_defunt").val(telephone);
                            $("#nationalite_defunt").val(code_nationalite);
                            $("#profession_defunt").val(code_profession);
                            $("#code_localite").val(lieu_naissance);
                            $("#niveau_instruction_defunt").val(niveau_instruction);
                            document.getElementById('nom_defunt').readOnly = true;
                            document.getElementById('prenom_defunt').readOnly = true;
                            document.getElementById('sexe_defunt').disabled = true;
                            document.getElementById('date_naissance_defunt').readOnly = true;
                            document.getElementById('domicile_defunt').readOnly = true;

                            document.getElementById('code_localite').readOnly = true;
                            document.getElementById('nationalite_defunt').disabled = true;
                            document.getElementById('profession_defunt').disabled = true;

                            $("#defuntmodal").modal('hide');
                            console.log(response.personnes);
                        });

                    }
                });
        });

        // Rechercher un conjoint
        $('#rechercherconjoint').on("click", function (event) {
            event.preventDefault();

            var nom = $("#nom_conjoint_recherche");
            var prenom = $("#prenom_conjoint_recherche");
            var sexe = $("#sexe_conjoint_recherche");
            var telephone = $("#telephone_conjoint_recherche");

            var data = {
                nom: nom.val(),
                prenom: prenom.val(),
                sexe: sexe.val(),
                telephone: telephone.val()
            };

            var int = 0;

            var table = '<div class="table-responsive">'+
                            '<table class="table table-responsive-md table-hover">'+
                                '<thead>'+
                                    '<tr>'+
                                        '<th>#</th>'+
                                        '<th><strong>Nom et prénom</strong></th>'+
                                        '<th><strong>Sexe</strong></th>'+
                                        '<th><strong>Téléphone</strong></th>'+
                                ' </tr>'+
                                '</thead>'+
                                '<tbody>';

            //traitement ajax
            $.ajax({
                    url: "{{ route('declarationNaissance.recherchePersonne') }}",
                    type: "POST",
                    data: data,

                    success: function(response){

                        if(response.personnes.length > 0){

                            for( var i=0; i < response.personnes.length ; i++){
                                int ++;
                                table +='<tr class="tr" data-choix="'+response.personnes[i].code_personne+'" data-nom="'+response.personnes[i].nom+'" data-prenom="'+response.personnes[i].prenom+'" data-date_naissance="'+response.personnes[i].date_naissance+'" data-sexe="'+response.personnes[i].sexe+'" data-profession="'+response.personnes[i].profession+'" data-telephone="'+response.personnes[i].telephone+'" data-code_nationalite="'+response.personnes[i].code_nationalite+'" data-code_profession="'+response.personnes[i].code_profession+'" data-lieu_naissance="'+response.personnes[i].lieu_naissance+'" data-niveau_instruction="'+response.personnes[i].niveau_instruction+'" data-nom="'+response.personnes[i].nom+'">'+
                                            '<td><strong>'+int+'</strong></td>'+
                                            '<td>'+response.personnes[i].nom+" "+response.personnes[i].prenom+'</td>'+
                                            '<td>'+response.personnes[i].sexe+'</td>'+
                                            '<td>'+response.personnes[i].telephone+'</td>';
                            }
                        }
                        table += "</tr></tbody></table></div>";
                        $("#resultatConjoint").html(table);

                        $("tr.tr").on("click", function (){
                        var choix = $(this).data('choix');
                        var nom = $(this).data('nom');
                        var prenom = $(this).data('prenom');
                        var date_naissance = $(this).data('date_naissance');
                        var sexe = $(this).data('sexe');

                        var nationalite = $(this).data('code_nationalite');
                        var telephone = $(this).data('telephone');
                        var code_profession = $(this).data('code_profession');
                        var lieu_naissance = $(this).data('lieu_naissance');

                        $("#code_conjoint").val(choix);
                        $("#nom_conjoint").val(nom);
                        $("#prenom_conjoint").val(prenom);
                        $("#date_naissance_conjoint").val(date_naissance);
                        $("#sexe_conjoint").val(sexe);

                        $("#code_nationalite_conjoint").val(nationalite);
                        $("#telephone_conjoint").val(telephone);
                        $("#lieu_naissance_conjoint").val(lieu_naissance);
                        $("#profession_conjoint").val(code_profession);

                        document.getElementById('nom_conjoint').readOnly = true;
                        document.getElementById('prenom_conjoint').readOnly = true;
                        document.getElementById('date_naissance_conjoint').readOnly = true;
                        document.getElementById('lieu_naissance_conjoint').readOnly = true;
                        document.getElementById('sexe_conjoint').disabled = true;

                        document.getElementById('telephone_conjoint').readOnly = true;
                        document.getElementById('code_nationalite_conjoint').disabled = true;
                        document.getElementById('profession_conjoint').disabled = true;

                        $("#conjointmodal").modal('hide');
                        console.log(response.personnes);

                    });

                    }
                });
        });

         // Rechercher un père
         $('#rechercherpere').on("click", function (event) {
            event.preventDefault();
            // data = [];
            var nom = $("#nom_pere_recherche");
            var prenom = $("#prenom_pere_recherche");
            var sexe = $("#sexe_pere_recherche");
            var telephone = $("#telephone_pere_recherche");

            var data = {
                nom: nom.val(),
                prenom: prenom.val(),
                sexe: sexe.val(),
                telephone: telephone.val()
            };

            var int = 0;

            var table = '<div class="table-responsive">'+
                            '<table id="example" class="table table-responsive-md table-hover">'+
                                '<thead>'+
                                    '<tr>'+
                                        '<th>#</th>'+
                                        '<th><strong>Nom et prénom</strong></th>'+
                                        '<th><strong>Sexe</strong></th>'+
                                        '<th><strong>Téléphone</strong></th>'+
                                ' </tr>'+
                                '</thead>'+
                                '<tbody>';

            //traitement ajax
            $.ajax({
                    url: "{{ route('declarationNaissance.recherchePersonne') }}",
                    type: "POST",
                    data: data,

                    success: function(response){

                        if(response.personnes.length > 0){

                            for( var i=0; i < response.personnes.length ; i++){
                                int ++;
                                table +='<tr class="tr" data-choix="'+response.personnes[i].code_personne+'" data-nom="'+response.personnes[i].nom+'" data-prenom="'+response.personnes[i].prenom+'" data-date_naissance="'+response.personnes[i].date_naissance+'" data-sexe="'+response.personnes[i].sexe+'" data-adresse="'+response.personnes[i].adresse+'" data-telephone="'+response.personnes[i].telephone+'" data-code_nationalite="'+response.personnes[i].code_nationalite+'" data-code_profession="'+response.personnes[i].code_profession+'" data-lieu_naissance="'+response.personnes[i].lieu_naissance+'" data-niveau_instruction="'+response.personnes[i].niveau_instruction+'" data-nom="'+response.personnes[i].nom+'">'+
                                            '<td><strong>'+int+'</strong></td>'+
                                            '<td>'+response.personnes[i].nom+" "+response.personnes[i].prenom+'</td>'+
                                            '<td>'+response.personnes[i].sexe+'</td>'+
                                            '<td>'+response.personnes[i].telephone+'</td>'+
                                            '</tr>';
                            }
                        }
                        table += "</tbody><thead><tr><th>#</th><th>Nom et prénom</th><th>Sexe</th><th>Téléphone</th></tr></thead></table></div>";
                        $("#resultatPere").html(table);

                        $("tr.tr").on("click", function (){
                            var choix = $(this).data('choix');
                            var nom = $(this).data('nom');
                            var prenom = $(this).data('prenom');
                            var date_naissance = $(this).data('date_naissance');
                            var sexe = $(this).data('sexe');
                            var adresse = $(this).data('adresse');
                            var telephone = $(this).data('telephone');
                            var code_nationalite = $(this).data('code_nationalite');
                            var code_profession = $(this).data('code_profession');
                            var lieu_naissance = $(this).data('lieu_naissance');
                            var niveau_instruction = $(this).data('niveau_instruction');

                            $("#nom_pere").val(nom);
                            $("#prenom_pere").val(prenom);
                            $("#date_naissance_pere").val(date_naissance);
                            $("#sexe_pere").val(sexe);
                            $("#domicile_pere").val(adresse);
                            $("#telephone_pere").val(telephone);
                            $("#code_nationalite_pere").val(code_nationalite);
                            $("#code_profession_pere").val(code_profession);
                            $("#lieu_naissance_pere").val(lieu_naissance);
                            $("#niveau_instruction_pere").val(niveau_instruction);

                            document.getElementById('nom_pere').readOnly = true;
                            document.getElementById('prenom_pere').readOnly = true;
                            document.getElementById('date_naissance_pere').readOnly = true;
                            document.getElementById('domicile_pere').readOnly = true;
                            document.getElementById('lieu_naissance_pere').readOnly = true;
                            //document.getElementById('sexe_pere').disabled = true;

                            document.getElementById('telephone_pere').readOnly = true;
                            document.getElementById('code_nationalite_pere').disabled = true;
                            document.getElementById('code_profession_pere').disabled = true;

                            $("#rmodal").modal('hide');
                            // console.log(response.personnes);

                            getdocument(choix);

                        });

                    }
                });
        });

        // Rechercher une mère
        $('#recherchermere').on("click", function (event) {
            event.preventDefault();

            var nom = $("#nom_mere_recherche");
            var prenom = $("#prenom_mere_recherche");
            var sexe = $("#sexe_mere_recherche");
            var telephone = $("#telephone_mere_recherche");

            var data = {
                nom: nom.val(),
                prenom: prenom.val(),
                sexe: sexe.val(),
                telephone: telephone.val()
            };

            var int = 0;

            var table = '<div class="table-responsive">'+
                            '<table class="table table-responsive-md table-hover">'+
                                '<thead>'+
                                    '<tr>'+
                                        '<th>#</th>'+
                                        '<th><strong>Nom et prénom</strong></th>'+
                                        '<th><strong>Sexe</strong></th>'+
                                        '<th><strong>Téléphone</strong></th>'+
                                ' </tr>'+
                                '</thead>'+
                                '<tbody>';

            //traitement ajax
            $.ajax({
                    url: "{{ route('declarationNaissance.recherchePersonne') }}",
                    type: "POST",
                    data: data,

                    success: function(response){

                        if(response.personnes.length > 0){

                            for( var i=0; i < response.personnes.length ; i++){
                                int ++;
                                table +='<tr class="tr" data-choix="'+response.personnes[i].code_personne+'" data-nom="'+response.personnes[i].nom+'" data-prenom="'+response.personnes[i].prenom+'" data-date_naissance="'+response.personnes[i].date_naissance+'" data-sexe="'+response.personnes[i].sexe+'" data-adresse="'+response.personnes[i].adresse+'" data-telephone="'+response.personnes[i].telephone+'" data-code_nationalite="'+response.personnes[i].code_nationalite+'" data-code_profession="'+response.personnes[i].code_profession+'" data-lieu_naissance="'+response.personnes[i].lieu_naissance+'" data-niveau_instruction="'+response.personnes[i].niveau_instruction+'" data-nom="'+response.personnes[i].nom+'">'+
                                            '<td><strong>'+int+'</strong></td>'+
                                            '<td>'+response.personnes[i].nom+" "+response.personnes[i].prenom+'</td>'+
                                            '<td>'+response.personnes[i].sexe+'</td>'+
                                            '<td>'+response.personnes[i].telephone+'</td>';
                            }
                        }
                        table += "</tr></tbody></table></div>";
                        $("#resultatMere").html(table);

                        $("tr.tr").on("click", function (){
                            var choix = $(this).data('choix');
                            var nom = $(this).data('nom');
                            var prenom = $(this).data('prenom');
                            var date_naissance = $(this).data('date_naissance');
                            var sexe = $(this).data('sexe');
                            var adresse = $(this).data('adresse');
                            var telephone = $(this).data('telephone');
                            var code_nationalite = $(this).data('code_nationalite');
                            var code_profession = $(this).data('code_profession');
                            var lieu_naissance = $(this).data('lieu_naissance');
                            var niveau_instruction = $(this).data('niveau_instruction');

                            $("#nom_mere").val(nom);
                            $("#prenom_mere").val(prenom);
                            $("#date_naissance_mere").val(date_naissance);
                            $("#sexe_mere").val(sexe);
                            $("#domicile_mere").val(adresse);
                            $("#telephone_mere").val(telephone);
                            $("#code_nationalite_mere").val(code_nationalite);
                            $("#code_profession_mere").val(code_profession);
                            $("#lieu_naissance_mere").val(lieu_naissance);
                            $("#niveau_instruction_mere").val(niveau_instruction);

                            document.getElementById('nom_mere').readOnly = true;
                            document.getElementById('prenom_mere').readOnly = true;
                            document.getElementById('date_naissance_mere').readOnly = true;
                            document.getElementById('domicile_mere').readOnly = true;
                            document.getElementById('lieu_naissance_mere').readOnly = true;
                            document.getElementById('code_profession_mere').disabled = true;

                            document.getElementById('telephone_mere').readOnly = true;
                            document.getElementById('code_nationalite_mere').disabled = true;

                            $("#meremodal").modal('hide');
                            // getdocumentmere(choix);
                            // console.log(response.personnes);

                        });

                    }
                });
        });
        // Rechercher un déclarant
    $('#rechercherdeclarant').on("click", function (event) {
        event.preventDefault();

        var nom = $("#nom_declarant_recherche");
        var prenom = $("#prenom_declarant_recherche");
        var sexe = $("#sexe_declarant_recherche");
        var telephone = $("#telephone_declarant_recherche");

        var data = {
            nom: nom.val(),
            prenom: prenom.val(),
            sexe: sexe.val(),
            telephone: telephone.val()
        };

        var int = 0;

        var table = '<div class="table-responsive">'+
                        '<table class="table table-responsive-md table-hover">'+
                            '<thead>'+
                                '<tr>'+
                                    '<th>#</th>'+
                                    '<th><strong>Nom et prénom</strong></th>'+
                                    '<th><strong>Sexe</strong></th>'+
                                    '<th><strong>Téléphone</strong></th>'+
                            ' </tr>'+
                            '</thead>'+
                            '<tbody>';

        //traitement ajax
        $.ajax({
                url: "{{ route('declarationNaissance.recherchePersonne') }}",
                type: "POST",
                data: data,

                success: function(response){

                    if(response.personnes.length > 0){

                        for( var i=0; i < response.personnes.length ; i++){
                                int ++;
                                table +='<tr class="tr" data-choix="'+response.personnes[i].code_personne+'" data-nom="'+response.personnes[i].nom+'" data-prenom="'+response.personnes[i].prenom+'" data-date_naissance="'+response.personnes[i].date_naissance+'" data-sexe="'+response.personnes[i].sexe+'" data-adresse="'+response.personnes[i].adresse+'" data-telephone="'+response.personnes[i].telephone+'" data-code_nationalite="'+response.personnes[i].code_nationalite+'" data-code_profession="'+response.personnes[i].code_profession+'" data-code_localite="'+response.personnes[i].code_localite+'" data-niveau_instruction="'+response.personnes[i].niveau_instruction+'" data-nom="'+response.personnes[i].nom+'">'+
                                            '<td><strong>'+int+'</strong></td>'+
                                            '<td>'+response.personnes[i].nom+" "+response.personnes[i].prenom+'</td>'+
                                            '<td>'+response.personnes[i].sexe+'</td>'+
                                            '<td>'+response.personnes[i].telephone+'</td>';
                            }
                    }
                    table += "</tr></tbody></table></div>";
                    $("#resultatDeclarant").html(table);

                    $("tr.tr").on("click", function (){
                            var choix = $(this).data('choix');
                            var nom = $(this).data('nom');
                            var prenom = $(this).data('prenom');
                            var date_naissance = $(this).data('date_naissance');
                            var sexe = $(this).data('sexe');
                            var adresse = $(this).data('adresse');
                            var telephone = $(this).data('telephone');
                            var code_nationalite = $(this).data('code_nationalite');
                            var code_profession = $(this).data('code_profession');
                            var lieu_naissance = $(this).data('lieu_naissance');
                            var niveau_instruction = $(this).data('niveau_instruction');

                            $("#nom_declarant").val(nom);
                            $("#prenom_declarant").val(prenom);
                            $("#date_naissance_declarant").val(date_naissance);
                            $("#sexe_declarant").val(sexe);
                            $("#domicile_declarant").val(adresse);
                            $("#telephone_declarant").val(telephone);
                            $("#code_nationalite_declarant").val(code_nationalite);
                            $("#code_profession_declarant").val(code_profession);
                            $("#lieu_naissance_declarant").val(lieu_naissance);
                            $("#niveau_instruction_declarant").val(niveau_instruction);

                            document.getElementById('nom_declarant').readOnly = true;
                            document.getElementById('prenom_declarant').readOnly = true;
                            document.getElementById('date_naissance_declarant').readOnly = true;
                            document.getElementById('sexe_declarant').disabled = true;
                            document.getElementById('domicile_declarant').readOnly = true;
                            document.getElementById('telephone_declarant').readOnly = true;

                            document.getElementById('code_nationalite_declarant').disabled = true;
                            document.getElementById('code_profession_declarant').disabled = true;
                            document.getElementById('lieu_naissance_declarant').readOnly = true;
                            ///document.getElementById('lieu_naissance_declarant').readOnly = true;

                            $("#declarantmodal").modal('hide');
                            console.log(response.personnes);
                        });

                }
            });
    });

        var form = $(".validation-wizard").show();
        $(".validation-wizard").steps({
            headerTag: "h6",
            bodyTag: "section",
            transitionEffect: "fade",
            titleTemplate: '<span class="step">#index#</span> #title#',
            labels: {
                finish: "Enrégistrer"
            },
            onStepChanging: function (event, currentIndex, newIndex) {
                return currentIndex > newIndex || !(2 === newIndex && Number($("#age-2").val()) < 18) && (currentIndex < newIndex && (form.find(".body:eq(" + newIndex + ") label.error").remove(), form.find(".body:eq(" + newIndex + ") .error").removeClass("error")), form.validate().settings.ignore = ":disabled,:hidden", form.valid())
            },
            onFinishing: function (event, currentIndex) {
                return form.validate().settings.ignore = ":disabled", form.valid()
            },
            onFinished: function (event, currentIndex) {
                Swal.fire({
                    width:2500,
                    position: 'top',
                    title: "Enrégistrer la déclaration ?",
                    icon: 'question',
                    //text: "Assurez-vous, puis confirmez ! \n\n",
html:
     "<input type='button' value='Imprimer cette page' class=\"btn btn-primary\" onClick='printDiv(\"printcontent\")'><div id='printcontent'><br><table style='border:1px solid black; width:100%; padding:10px; text-align:left'>"

//DECLARATION
    +"<tr><td style='padding:5px'>Date décès</td><td style='padding:5px'><span style='font-weight:bold;'>"+ dateFrench(document.getElementById("date_deces").value) +" </span></td><td style='padding:5px'>Heure</td><td style='padding:5px'><span style='font-weight:bold;'> "+document.getElementById("heure_deces").value+"</span></td><td style='padding:5px'>Lieu de décès</td><td style='padding:5px'> <span style='font-weight:bold;'>"+document.getElementById("lieu_deces").value+"</span></td></tr>"

//DEFUNT
    +"<tr><td style='padding:10px' colspan='6'><hr></td></tr><tr><td style='font-weight:bold; padding:5px'>1)DEFUNT<br></td><td style='padding:5px'>Nom<br><span style='font-weight:bold;'>"+ document.getElementById("nom_defunt").value +" </span></td><td style='padding:5px'>Prenom<br><span style='font-weight:bold;'> "+document.getElementById("prenom_defunt").value+"</span></td><td style='padding:5px'>Sexe<br><span style='font-weight:bold;'>"+document.getElementById("sexe_defunt").value+"</span></td><td style='padding:5px'>Date naissance<br><span style='font-weight:bold;'>"+document.getElementById("date_naissance_defunt").value+"</span></td><td style='padding:5px'>Lieu<br><span style='font-weight:bold;'>"+document.getElementById("cec_naissance").value+"</span></td></tr>"
    +"<tr><td style='font-weight:bold; padding:5px'></td><td style='padding:5px'>Acte naissance<BR><span style='font-weight:bold;'>"+document.getElementById("num_acte_naissance").value+"</span></td><td style='padding:5px'>Mairie<br><span style='font-weight:bold;'>"+document.getElementById("cec_naissance").value+"</span></td><td style='padding:5px'>Profession<br><span style='font-weight:bold;'>"+document.getElementById( "profession_defunt" ).options[ document.getElementById( "profession_defunt" ).selectedIndex ].text+"</span></td><td style='padding:5px'>Adresse<br><span style='font-weight:bold;'>"+document.getElementById("domicile_defunt").value+"</span></td><td style='padding:5px'>Régime<br><span style='font-weight:bold;'>"+document.getElementById( "code_regime" ).options[ document.getElementById( "code_regime" ).selectedIndex ].text+"</span></td></tr>"
    +"<tr><td style='font-weight:bold; padding:5px'></td><td style='padding:5px'>Sit. Matrimoniale<br><span style='font-weight:bold;'>"+document.getElementById( "code_situation_matrimoniale_defunt" ).options[ document.getElementById( "code_situation_matrimoniale_defunt" ).selectedIndex ].text +" </span></td><td style='padding:5px'>Réligion<br><span style='font-weight:bold;'> "+document.getElementById( "code_religion_defunt" ).options[ document.getElementById( "code_religion_defunt" ).selectedIndex ].text+"</span></td><td style='padding:5px'>Nationalité<br><span style='font-weight:bold;'>"+document.getElementById( "nationalite_defunt" ).options[ document.getElementById( "nationalite_defunt" ).selectedIndex ].text+"</span></td></tr>"


//EPOUSE
    +"<tr><td style='padding:10px' colspan='6'><hr></td></tr><tr><td style='font-weight:bold; padding:5px'>2)EPOUSE </td><td style='padding:5px'>Nom<br><span style='font-weight:bold;'>"+ document.getElementById("nom_conjoint").value +" </span></td><td style='padding:5px'>Prenom<br><span style='font-weight:bold;'> "+document.getElementById("prenom_conjoint").value+"</span></td><td style='padding:5px'>Date naissance<br><span style='font-weight:bold;'>"+dateFrench(document.getElementById("date_naissance_conjoint").value)+"</span></td><td style='padding:5px'>Acte Mariage<br><span style='font-weight:bold;'>"+document.getElementById("num_acte_mariage").value+"</span></td><td style='padding:5px'>Date Mariage<br><span style='font-weight:bold;'>"+dateFrench(document.getElementById("date_mariage").value)+"</span></td><td style='padding:5px'>CEC<br><span style='font-weight:bold;'>"+document.getElementById("cec_mariage").value+"</span></td></tr>"
    +"<tr><td style='font-weight:bold; padding:5px'>3)PERE : </td><td style='padding:5px'>Nom et prénom <br><span style='font-weight:bold;'>"+document.getElementById("nom_pere").value+" "+document.getElementById("prenom_pere").value+"</span></td><td style='font-weight:bold; padding:5px'>4)MERE </td><td style='padding:5px'>Nom et prenom <br><span style='font-weight:bold;'>"+document.getElementById("nom_mere").value+" "+document.getElementById("prenom_mere").value+"</span></td></tr>"

  //DECLARANT
    +"<tr><td style='padding:10px' colspan='6'><hr></td></tr><td style='font-weight:bold; padding:5px'>3)DECLARANT</td><td style='padding:5px'>Nom<br><span style='font-weight:bold;'>"+ document.getElementById("nom_declarant").value +" </span></td><td style='padding:5px'>Prenom<br><span style='font-weight:bold;'> "+document.getElementById("prenom_declarant").value+"</span></td><td style='padding:5px'>Date naissance<br><span style='font-weight:bold;'>"+dateFrench(document.getElementById("date_naissance_declarant").value)+"</span></td><td style='padding:5px'>Sexe<br><span style='font-weight:bold;'> "+document.getElementById("sexe_declarant").value+"</span></td><td style='padding:5px'>Lieu<br><span style='font-weight:bold;'>"+document.getElementById("lieu_naissance_declarant").value+"</span></td><td style='padding:5px'>Adresse<br><span style='font-weight:bold;'>"+document.getElementById("domicile_declarant").value+"</span></td><tr>"
    +"<tr><td style='font-weight:bold; padding:5px'></td><td style='padding:5px'>Cause de décès <br><span style='font-weight:bold;'>"+document.getElementById( "code_cause_deces" ).options[ document.getElementById( "code_cause_deces" ).selectedIndex ].text+"</span></td><td style='padding:5px'>Filiation<br><span style='font-weight:bold;'>"+document.getElementById( "code_filiation_declarant" ).options[ document.getElementById( "code_filiation_declarant" ).selectedIndex ].text+"</span></td><td style='padding:5px'>Téléphone<br><span style='font-weight:bold;'>"+document.getElementById("telephone_declarant").value+"</span></td><td style='padding:5px'>Profession<br><span style='font-weight:bold;'>"+document.getElementById( "code_profession_declarant" ).options[ document.getElementById( "code_profession_declarant" ).selectedIndex ].text+"</span></td><td style='padding:5px'>Nationalite<br><span style='font-weight:bold;'>"+document.getElementById("code_nationalite_declarant").options[ document.getElementById( "code_nationalite_declarant" ).selectedIndex ].text+"</span></tr>"


    +"<tr><td style='padding:5px;' colspan=11><hr style='border:none;'></td></tr></table><bR><br>Assurez-vous, puis confirmez !</div> ",
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonText: "Oui, Confirmer!",
                    cancelButtonText: "Non, Annuler!",
                    reverseButtons: !0
                }).then(function (e)
                {

                    if (e.value === true)
                    {
                        let token = $('meta[name="csrf-token"]').attr('content');

                        //information du défunt
                        var heure_deces = $("#heure_deces");
                        var date_deces= $("#date_deces");
                        var nom_defunt = $("#nom_defunt");
                        var prenom_defunt = $("#prenom_defunt");
                        var sexe_defunt = $("#sexe_defunt");
                        var date_naissance_defunt = $("#date_naissance_defunt");
                        var lieu_naissance_defunt = $("#code_localite");
                        var profession_defunt = $("#profession_defunt");
                        var code_situation_matrimoniale_defunt= $("#code_situation_matrimoniale_defunt");
                        var nationalite_defunt = $("#nationalite_defunt");
                        var code_religion_defunt = $("#code_religion_defunt");
                        var lieu_survenance_code = $("#lieu_survenance_code");
                        var lieu_deces = $("#lieu_deces");
                        var domicile_defunt = $("#domicile_defunt");
                        var num_acte_naissance = $("#num_acte_naissance");
                        var cec_naissance = $("#cec_naissance");


                        // informations du père
                        var nom_pere = $("#nom_pere");
                        var prenom_pere = $("#prenom_pere");
                        var date_naissance_pere = $("#date_naissance_pere");
                        var lieu_naissance_pere = $("#lieu_naissance_pere");
                        var domicile_pere = $("#domicile_pere");
                        var telephone_pere = $("#telephone_pere");
                        var code_profession_pere = $("#code_profession_pere");
                        var code_nationalite_pere = $("#code_nationalite_pere");
                        var niveau_instruction_pere = $("#niveau_instruction_pere");
                        var code_type_document_pere = $("#code_type_document_pere");
                        var numero_document_pere = $("#numero_document_pere");

                        //information mère
                        var nom_mere = $("#nom_mere");
                        var prenom_mere = $("#prenom_mere");
                        var date_naissance_mere = $("#date_naissance_mere");
                        var lieu_naissance_mere = $("#lieu_naissance_mere");
                        var domicile_mere = $("#domicile_mere");
                        var telephone_mere = $("#telephone_mere");
                        var code_profession_mere = $("#code_profession_mere");
                        var code_nationalite_mere = $("#code_nationalite_mere");
                        var niveau_instruction_mere = $("#niveau_instruction_mere");
                        var code_type_document_mere = $("#code_type_document_mere");
                        var numero_document_mere = $("#numero_document_mere");

                        //information conjoint
                        var nom_conjoint = $("#nom_conjoint");
                        var prenom_conjoint = $("#prenom_conjoint");
                        var date_mariage = $("#date_mariage");
                        var cec_mariage = $("#cec_mariage");
                        var code_regime = $("#code_regime");
                        var sexe_conjoint = $("#sexe_conjoint");
                        var num_acte_mariage = $("#num_acte_mariage");
                        var date_naissance_conjoint = $("#date_naissance_conjoint");

                        var code_nationalite_conjoint =  $("#code_nationalite_conjoint");
                        var telephone_conjoint = $("#telephone_conjoint");
                        var lieu_naissance_conjoint = $("#lieu_naissance_conjoint");
                        var profession_conjoint = $("#profession_conjoint");

                        //information déclarant
                        var nom_declarant = $("#nom_declarant");
                        var prenom_declarant = $("#prenom_declarant");
                        var date_naissance_declarant = $("#date_naissance_declarant");
                        var lieu_naissance_declarant = $("#lieu_naissance_declarant");
                        var domicile_declarant = $("#domicile_declarant");
                        var sexe_declarant = $("#sexe_declarant");
                        var filiation = $("#code_filiation_declarant");
                        var telephone_declarant = $("#telephone_declarant");
                        var code_profession_declarant = $("#code_profession_declarant");
                        var code_nationalite_declarant = $("#code_nationalite_declarant");
                        var code_cause_deces = $("#code_cause_deces");


                        $.ajax({
                            type: 'POST',
                            url: "{{route('declarationDeces.store')}}",
                            data: {
                                heure_deces:heure_deces.val(),
                                date_deces: date_deces.val(),
                                nom_defunt: nom_defunt.val(),
                                prenom_defunt: prenom_defunt.val(),
                                sexe_defunt: sexe_defunt.val(),
                                date_naissance_defunt: date_naissance_defunt.val(),
                                lieu_naissance_defunt: lieu_naissance_defunt.val(),
                                profession_defunt: profession_defunt.val(),
                                code_situation_matrimoniale_defunt: code_situation_matrimoniale_defunt.val(),
                                nationalite_defunt: nationalite_defunt.val(),
                                code_religion_defunt: code_religion_defunt.val(),
                                lieu_survenance_code: lieu_survenance_code.val(),
                                lieu_deces: lieu_deces.val(),
                                sexe_conjoint: sexe_conjoint.val(),
                                domicile_defunt: domicile_defunt.val(),
                                nom_conjoint: nom_conjoint.val(),
                                prenom_conjoint: prenom_conjoint.val(),
                                profession_conjoint:profession_conjoint.val(),
                                lieu_naissance_conjoint:lieu_naissance_conjoint.val(),
                                code_nationalite_conjoint:code_nationalite_conjoint.val(),
                                telephone_conjoint:telephone_conjoint.val(),
                                date_naissance_conjoint: date_naissance_conjoint.val(),
                                date_mariage: date_mariage.val(),
                                cec_mariage: cec_mariage.val(),
                                code_regime: code_regime.val(),
                                num_acte_mariage: num_acte_mariage.val(),

                                  // données du père
                                  nom_pere:nom_pere.val(),
                                  prenom_pere:prenom_pere.val(),
                                  date_naissance_pere:date_naissance_pere.val(),
                                  lieu_naissance_pere:lieu_naissance_pere.val(),
                                  domicile_pere:domicile_pere.val(),
                                  code_profession_pere:code_profession_pere.val(),
                                  code_nationalite_pere:code_nationalite_pere.val(),
                                  niveau_instruction_pere:niveau_instruction_pere.val(),
                                  telephone_pere:telephone_pere.val(),
                                  code_type_document_pere:code_type_document_pere.val(),
                                  numero_document_pere:numero_document_pere.val(),

                                  // données de la mère
                                  nom_mere:nom_mere.val(),
                                  prenom_mere:prenom_mere.val(),
                                  date_naissance_mere:date_naissance_mere.val(),
                                  lieu_naissance_mere:lieu_naissance_mere.val(),
                                  domicile_mere:domicile_mere.val(),
                                  code_profession_mere:code_profession_mere.val(),
                                  code_nationalite_mere:code_nationalite_mere.val(),
                                  niveau_instruction_mere:niveau_instruction_mere.val(),
                                  telephone_mere:telephone_mere.val(),
                                  code_type_document_mere:code_type_document_mere.val(),
                                  numero_document_mere:numero_document_mere.val(),

                                nom_declarant: nom_declarant.val(),
                                prenom_declarant: prenom_declarant.val(),
                                sexe_declarant: sexe_declarant.val(),
                                date_naissance_declarant: date_naissance_declarant.val(),
                                lieu_naissance_declarant: lieu_naissance_declarant.val(),
                                domicile_declarant: domicile_declarant.val(),
                                filiation: filiation.val(),
                                code_profession_declarant: code_profession_declarant.val(),
                                telephone_declarant: telephone_declarant.val(),
                                code_nationalite_declarant: code_nationalite_declarant.val(),
                                code_cause_deces: code_cause_deces.val(),
                                num_acte_naissance: num_acte_naissance.val(),
                                cec_naissance: cec_naissance.val()
                            },
                           // data: {_token: token},
                            success: function(response ) {
                                if (response.success==true) {
                                    swal.fire("Enrégistrée!", response.message, "success");
                                    var url = "{{ route('declarationDeces.index') }}";
                                  window.open(url);
                                    location.reload();
                                } else {
                                    swal.fire("Erreur!", response.message, "error");
                                }
                            },
                            error: function (resp) {
                                swal.fire("Erreur!", "Sumething went wrong.", "error");
                            }
                        });

                    } else {
                        e.dismiss;
                    }

                }, function (dismiss) {
                    return false;
                });

            }

        }), $("#contactUsForm").validate({
            ignore: "input[type=hidden]",
            errorClass: "text-danger",
            successClass: "text-success",
            highlight: function (element, errorClass) {
                $(element).removeClass(errorClass)
            },
            unhighlight: function (element, errorClass) {
                $(element).removeClass(errorClass)
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element)
            },
            rules: {
                heure_deces: {
                    required: true,
                   // minlength: 50
                },
                num_acte_naissance:{
                    required: true,
                },
                date_deces: {
                    required: true,
                },
                nom_defunt: {
                required: true,
                maxlength: 50
                },
                sexe_defunt: {
                required: true,
                },
                date_naissance_defunt: {
                    required: true,
                },
                code_localite: {
                    required: true,
                },
                profession_defunt: {
                    required: true,
                },
                code_situation_matrimoniale_defunt: {
                    required: true,
                },
                nationalite_defunt: {
                    required: true,
                },
                code_religion_defunt: {
                    required: true,
                },
                lieu_survenance_code: {
                    required: true,
                },
                lieu_deces: {
                    required: true,
                },
                domicile_defunt: {
                required: true,
                maxlength:300
                },
                nom_declarant: {
                    required: true,
                    maxlength: 50
                },
                date_naissance_declarant: {
                    required: true,
                },
                lieu_naissance_declarant: {
                    required: true,
                },
                domicile_declarant: {
                    required: true,
                },
                code_filiation_declarant: {
                    required: true,
                },
                },
                messages: {
                num_acte_naissance:{
                    required: "Veuillez saisir le numero de l\'acte de naissance",
                },
                date_deces: {
                required: "Veuillez saisir la date du décès",
                //maxlength: "Votre nom ne doit comporter 50 caractères."
                },
                heure_deces: {
                    required: "Veuillez saisir l'heure du décès",
                    //maxlength: "Votre nom ne doit comporter 50 caractères."
                },

                nom_defunt: {
                required: "Veuillez saisir le nom du défunt",
                maxlength: "Le nom ne doit pas dépasser 50 caractères."
                },
                sexe_defunt: {
                required: "Veuillez choisir le sexe du défunt.",
                },
                date_naissance_defunt: {
                    required: "Veuillez saisir la date de naissance du défunt.",
                },
                code_localite: {
                    required: "Veuillez saisir le lieu de naissance du défunt.",
                },
                profession_defunt: {
                    required: "Veuillez choisir la profession du défunt.",
                },
                code_situation_matrimoniale_defunt: {
                    required: "Veuillez choisir la situation matrimoniale du défunt.",
                },
                nationalite_defunt: {
                    required: "Veuillez choisir la nationalité du défunt.",
                },
                code_religion_defunt: {
                    required: "Veuillez choisir la réligion du défunt.",
                },
                lieu_survenance_code: {
                    required: "Veuillez choisir le lieu de survenance du décès.",
                },
                lieu_deces: {
                    required: "Veuillez choisir le lieu de décés.",
                },
                domicile_defunt: {
                required: "Veuillez entrer l'adresse du défunt",
                maxlength: "L'adresse ne doit dépasser 300 caractères."
                },
                nom_declarant: {
                    required: "Veuillez saisir le nom du déclarant.",
                    maxlength: "Le nom ne doit pas dépasser 50 caractères."
                },
                date_naissance_declarant: {
                    required: "Veuillez saisir la date de naissance du déclarant.",
                },
                lieu_naissance_declarant: {
                    required: "Veuillez saisir le lieu de naissance du déclarant.",
                },
                domicile_declarant: {
                    required: "Veuillez saisir l'adresse du déclarant.",
                },
                code_filiation_declarant: {
                    required: "Veuillez choisir la filiation du déclarant.",
                },
                },


        })
    </script>
    <script>
        $(document).ready(function()
        {

            /*$("#autredeclarant").change(function()
            {
                var declarant = $("#autredeclarant").val();
                alert(declarant);

                if(declarant != "" || declarant != null){
                    if(declarant == "FIL_0001"){
                        nom_declarant = nom_declarant.val(nom_pere.val());
                        prenom_declarant = prenom_declarant.val(prenom_pere.val());
                        date_naissance_declarant = date_naissance_declarant.val(date_naissance_pere.val());
                        lieu_naissance_declarant = lieu_naissance_declarant.val(lieu_naissance_pere.val());
                        domicile_declarant = domicile_declarant.val(domicile_pere.val());
                        telephone_declarant = telephone_declarant.val(telephone_pere.val());
                        profession_declarant = profession_declarant.val(profession_pere.val());
                        code_nationalite_declarant = code_nationalite_declarant.val(code_nationalite_pere.val());
                        filiation = filiation.val(declarant);
                        sexe_declarant = sexe_declarant.val("M");
                        code_type_document_declarant = code_type_document_declarant.val(code_type_document_pere.val());
                        numero_document_declarant = numero_document_declarant.val(numero_document_pere.val());
                    }else if(declarant == "FIL_0002"){
                        nom_declarant = nom_declarant.val(nom_mere.val());
                        prenom_declarant = prenom_declarant.val(prenom_mere.val());
                        date_naissance_declarant = date_naissance_declarant.val(date_naissance_mere.val());
                        lieu_naissance_declarant = lieu_naissance_declarant.val(lieu_naissance_mere.val());
                        domicile_declarant = domicile_declarant.val(domicile_mere.val());
                        telephone_declarant = telephone_declarant.val(telephone_mere.val());
                        profession_declarant = profession_declarant.val(profession_mere.val());
                        code_nationalite_declarant = code_nationalite_declarant.val(code_nationalite_mere.val());
                        filiation = filiation.val(declarant);
                        sexe_declarant = sexe_declarant.val("F");
                        code_type_document_declarant = code_type_document_declarant.val(code_type_document_mere.val());
                        numero_document_declarant = numero_document_declarant.val(numero_document_mere.val());
                    }else{
                        nom_declarant = nom_declarant.val("");
                        prenom_declarant = prenom_declarant.val("");
                        date_naissance_declarant = date_naissance_declarant.val("");
                        lieu_naissance_declarant = lieu_naissance_declarant.val("");
                        domicile_declarant = domicile_declarant.val("");
                        telephone_declarant = telephone_declarant.val("");
                        profession_declarant = profession_declarant.val("");
                        code_nationalite_declarant = code_nationalite_declarant.val("");
                        filiation = filiation.val(declarant);
                        sexe_declarant = sexe_declarant.val("");
                        code_type_document_declarant = code_type_document_declarant.val("");
                        numero_document_declarant = numero_document_declarant.val("");
                    }

                }

            });*/


            //Traitement input


            $('#clear_defunt').click(function()
            {
                $('#nom_defunt').val("");
                document.getElementById('nom_defunt').readOnly = false;

                $('#prenom_defunt').val("");
                document.getElementById('prenom_defunt').readOnly = false;

                $('#code_localite').val("");
                document.getElementById('code_localite').readOnly = false;

                $('#date_naissance_defunt').val("");
                document.getElementById('date_naissance_defunt').readOnly = false;

                $('#domicile_defunt').val("");
                document.getElementById('domicile_defunt').readOnly = false;

                var e = document.getElementById("sexe_defunt");
                e.options[e.selectedIndex].text="Masculin";
                e.options[e.selectedIndex].value="M";
                document.getElementById('sexe_defunt').disabled = false;


                var e = document.getElementById("profession_defunt");

                document.getElementById('profession_defunt').disabled = false;

                var e = document.getElementById("nationalite_defunt");

                document.getElementById('nationalite_defunt').disabled = false;

            });

            $('#clear_pere').click(function()
            {
                $('#nom_pere').val("");
                document.getElementById('nom_pere').readOnly = false;

                $('#prenom_pere').val("");
                document.getElementById('prenom_pere').readOnly = false;

                $('#code_localite').val("");
                document.getElementById('code_localite').readOnly = false;

                $('#date_naissance_pere').val("");
                document.getElementById('date_naissance_pere').readOnly = false;

                $('#domicile_pere').val("");
                document.getElementById('domicile_pere').readOnly = false;

                $('#lieu_naissance_pere').val("");
                document.getElementById('lieu_naissance_pere').readOnly = false;

                $('#telephone_pere').val("");
                document.getElementById('telephone_pere').readOnly = false;

                document.getElementById('code_profession_pere').disabled = false;

                document.getElementById('code_nationalite_pere').disabled = false;

            });


            $('#clear_mere').click(function()
            {
                $('#nom_mere').val("");
                document.getElementById('nom_mere').readOnly = false;

                $('#prenom_mere').val("");
                document.getElementById('prenom_mere').readOnly = false;

                $('#code_localite').val("");
                document.getElementById('code_localite').readOnly = false;

                $('#date_naissance_mere').val("");
                document.getElementById('date_naissance_mere').readOnly = false;

                $('#domicile_mere').val("");
                document.getElementById('domicile_mere').readOnly = false;

                $('#lieu_naissance_mere').val("");
                document.getElementById('lieu_naissance_mere').readOnly = false;

                $('#telephone_mere').val("");
                document.getElementById('telephone_mere').readOnly = false;

                document.getElementById('code_profession_mere').disabled = false;

                document.getElementById('code_nationalite_mere').disabled = false;

            });

            $('#clear_declarant').click(function()
            {
                $('#nom_declarant').val("");
                document.getElementById('nom_declarant').readOnly = false;

                $('#prenom_declarant').val("");
                document.getElementById('prenom_declarant').readOnly = false;

                $('#date_naissance_declarant').val("");
                document.getElementById('date_naissance_declarant').readOnly = false;

                $('#lieu_naissance_declarant').val("");
                document.getElementById('lieu_naissance_declarant').readOnly = false;

                $('#telephone_declarant').val("");
                document.getElementById('telephone_declarant').readOnly = false;

                $('#domicile_declarant').val("");
                document.getElementById('domicile_declarant').readOnly = false;

                $('#numero_document_declarant').val("");
                //document.getElementById('numero_document_declarant').readOnly = true;


                //traitement select

                document.getElementById('sexe_declarant').disabled = false;

                document.getElementById('code_filiation_declarant').disabled = false;

                document.getElementById('code_nationalite_declarant').disabled = false;

                document.getElementById('code_profession_declarant').disabled = false;

                //document.getElementById('code_type_document_declarant').disabled = true;

            });



            $('#clear_conjoint').click(function()
            {
                $('#nom_conjoint').val("");
                document.getElementById('nom_conjoint').readOnly = false;

                $('#prenom_conjoint').val("");
                document.getElementById('prenom_conjoint').readOnly = false;

                $('#lieu_naissance_conjoint').val("");
                document.getElementById('lieu_naissance_conjoint').readOnly = false;

                $('#date_naissance_conjoint').val("");
                document.getElementById('date_naissance_conjoint').readOnly = false;

               // {{--  $('#domicile_conjoint').val("");
                //document.getElementById('domicile_conjoint').readOnly = false;  --}}

                $('#telephone_conjoint').val("");
                document.getElementById('telephone_conjoint').readOnly = false;

              //  var e = document.getElementById("sexe_conjoint");

                document.getElementById('sexe_conjoint').disabled = false;

                //var e = document.getElementById("profession_conjoint");

                document.getElementById('profession_conjoint').disabled = false;

                //var e = document.getElementById("code_nationalite_conjoint");

                document.getElementById('code_nationalite_conjoint').disabled = false;

            });



           $('.validation-wizard').click(function()
            {
                if($('#code_situation_matrimoniale_defunt').val()!="SMAT_0001")
                {

                  document.getElementById('nom_conjoint').readOnly = true;
                  document.getElementById('prenom_conjoint').readOnly = true;
                  document.getElementById('date_naissance_conjoint').readOnly = true;
                  document.getElementById('date_mariage').readOnly = true;
                  document.getElementById('cec_mariage').readOnly = true;
                  document.getElementById('lieu_naissance_conjoint').readOnly = true;
                  document.getElementById('telephone_conjoint').readOnly = true;
                  document.getElementById('code_nationalite_conjoint').disabled = true;
                  document.getElementById('sexe_conjoint').disabled = true;
                  document.getElementById('profession_conjoint').disabled = true;
                  document.getElementById('code_regime').disabled = true;
                  document.getElementById('num_acte_mariage').disabled = true;
                  document.getElementById('search_conjoint').style.visibility = 'hidden';
                  document.getElementById('clear_conjoint').style.visibility = 'hidden';
                  document.getElementById('conjoint_click').style.visibility = 'hidden';


                }else{
                    if($('#code_conjoint').val()==="")
                    {
                        document.getElementById('nom_conjoint').readOnly = false;
                        document.getElementById('prenom_conjoint').readOnly = false;
                        document.getElementById('date_naissance_conjoint').readOnly = false;

                        document.getElementById('lieu_naissance_conjoint').readOnly = false;
                        document.getElementById('telephone_conjoint').readOnly = false;
                        document.getElementById('code_nationalite_conjoint').disabled = false;
                        document.getElementById('sexe_conjoint').disabled = false;
                        document.getElementById('profession_conjoint').disabled = false;


                    }
                    document.getElementById('date_mariage').readOnly = false;
                    document.getElementById('cec_mariage').readOnly = false;
                    document.getElementById('code_regime').disabled = false;
                    document.getElementById('num_acte_mariage').disabled = false;
                    document.getElementById('search_conjoint').style.visibility = 'visible';
                    document.getElementById('clear_conjoint').style.visibility = 'visible';

                    document.getElementById('conjoint_click').style.visibility = 'visible';
                }});
/*

                if($('#peredeclarant').is(':checked'))
                {
                $('#nom_declarant').val($('#nom_pere').val());
                document.getElementById('nom_declarant').readOnly = true;

                $('#prenom_declarant').val($('#prenom_pere').val());
                document.getElementById('prenom_declarant').readOnly = true;

                $('#date_naissance_declarant').val($('#date_naissance_pere').val());
                document.getElementById('date_naissance_declarant').readOnly = true;

                $('#lieu_naissance_declarant').val($('#lieu_naissance_pere').val());
                document.getElementById('lieu_naissance_declarant').readOnly = true;

                $('#telephone_declarant').val($('#telephone_pere').val());
                document.getElementById('telephone_declarant').readOnly = true;

                $('#domicile_declarant').val($('#domicile_pere').val());
                document.getElementById('domicile_declarant').readOnly = true;

                $('#numero_document_declarant').val($('#numero_document_pere').val());
                //document.getElementById('numero_document_declarant').readOnly = true;


                //traitement select
                var e = document.getElementById("sexe_declarant");
                e.options[e.selectedIndex].text="Masculin";
                e.options[e.selectedIndex].value="M";
                document.getElementById('sexe_declarant').disabled = false;


                var e = document.getElementById("code_filiation_declarant");
                e.options[e.selectedIndex].text="PERE";
                e.options[e.selectedIndex].value="FIL_0001";
                document.getElementById('code_filiation_declarant').disabled = false;

                var ee = document.getElementById("code_nationalite_declarant");
                var pe = document.getElementById("code_nationalite_pere");

                ee.options[ee.selectedIndex].text= pe.options[pe.selectedIndex].text;
                ee.options[ee.selectedIndex].value=pe.options[pe.selectedIndex].value;
                document.getElementById('code_nationalite_declarant').disabled = false;

                var ee = document.getElementById("code_profession_declarant");
                var pe = document.getElementById("code_profession_pere");

                ee.options[ee.selectedIndex].text= pe.options[pe.selectedIndex].text;
                ee.options[ee.selectedIndex].value=pe.options[pe.selectedIndex].value;
                document.getElementById('code_profession_declarant').disabled = false;

                var ee = document.getElementById("code_type_document_declarant");
                var pe = document.getElementById("code_type_document_pere");

                ee.options[ee.selectedIndex].text= pe.options[pe.selectedIndex].text;
                ee.options[ee.selectedIndex].value=pe.options[pe.selectedIndex].value;
                document.getElementById('code_type_document_declarant').disabled = false;



            }
            });
*/

           /* $('input:radio[name="autredeclarant"]').change(function()
            {
               ///si on coche père
               if ($(this).val() === 'autre')
                {
                    $('#nom_declarant').val("");
                    document.getElementById('nom_declarant').readOnly = false;

                    $('#prenom_declarant').val("");
                    document.getElementById('prenom_declarant').readOnly = false;

                    $('#date_naissance_declarant').val("");
                    document.getElementById('date_naissance_declarant').readOnly = false;

                    $('#lieu_naissance_declarant').val("");
                    document.getElementById('lieu_naissance_declarant').readOnly = false;

                    $('#telephone_declarant').val("");
                    document.getElementById('telephone_declarant').readOnly = false;

                    $('#domicile_declarant').val("");
                    document.getElementById('domicile_declarant').readOnly = false;

                    $('#numero_document_declarant').val("");
                    document.getElementById('numero_document_declarant').readOnly = false;


                    //traitement select
                    var e = document.getElementById("sexe_declarant");
                    e.options[e.selectedIndex].text="Masculin";
                    e.options[e.selectedIndex].value="M";
                    document.getElementById('sexe_declarant').disabled = false;


                    var e = document.getElementById("code_filiation_declarant");

                    e.options[e.selectedIndex].value="";
                    document.getElementById('code_filiation_declarant').disabled = false;

                    var ee = document.getElementById("code_profession_declarant");


                    ee.options[ee.selectedIndex].value="";
                    document.getElementById('code_profession_declarant').disabled = false;

                    var ee = document.getElementById("code_type_document_declarant");


                    ee.options[ee.selectedIndex].value="";
                    document.getElementById('code_type_document_declarant').disabled = false;


                    var ee = document.getElementById("code_nationalite_declarant");


                    ee.options[ee.selectedIndex].value="";
                    document.getElementById('code_nationalite_declarant').disabled = false;
                }
               if ($(this).val() === 'pere')
                {
                    //Traitement input
                    $('#nom_declarant').val($('#nom_pere').val());
                    document.getElementById('nom_declarant').readOnly = true;

                    $('#prenom_declarant').val($('#prenom_pere').val());
                    document.getElementById('prenom_declarant').readOnly = true;

                    $('#date_naissance_declarant').val($('#date_naissance_pere').val());
                    document.getElementById('date_naissance_declarant').readOnly = true;

                    $('#lieu_naissance_declarant').val($('#lieu_naissance_pere').val());
                    document.getElementById('lieu_naissance_declarant').readOnly = true;

                    $('#telephone_declarant').val($('#telephone_pere').val());
                    document.getElementById('telephone_declarant').readOnly = true;

                    $('#domicile_declarant').val($('#domicile_pere').val());
                    document.getElementById('domicile_declarant').readOnly = true;

                   $('#numero_document_declarant').val($('#numero_document_pere').val());
                   // document.getElementById('numero_document_declarant').readOnly = true;


                    //traitement select
                    var e = document.getElementById("sexe_declarant");
                    e.options[e.selectedIndex].text="Masculin";
                    e.options[e.selectedIndex].value="M";
                    document.getElementById('sexe_declarant').disabled = true;


                    var e = document.getElementById("code_filiation_declarant");
                    e.options[e.selectedIndex].text="PERE";
                    e.options[e.selectedIndex].value="FIL_0001";
                    document.getElementById('code_filiation_declarant').disabled = false;

                    var ee = document.getElementById("code_profession_declarant");
                    var pe = document.getElementById("code_profession_pere");

                    ee.options[ee.selectedIndex].text= pe.options[pe.selectedIndex].text;
                    ee.options[ee.selectedIndex].value=pe.options[pe.selectedIndex].value;
                    document.getElementById('code_profession_declarant').disabled = false;

                    var ee = document.getElementById("code_type_document_declarant");
                    var pe = document.getElementById("code_type_document_pere");

                    ee.options[ee.selectedIndex].text= pe.options[pe.selectedIndex].text;
                    ee.options[ee.selectedIndex].value=pe.options[pe.selectedIndex].value;
                    document.getElementById('code_type_document_declarant').disabled = false;


                    var ee = document.getElementById("code_nationalite_declarant");
                    var pe = document.getElementById("code_nationalite_pere");

                    ee.options[ee.selectedIndex].text= pe.options[pe.selectedIndex].text;
                    ee.options[ee.selectedIndex].value=pe.options[pe.selectedIndex].value;
                    document.getElementById('code_nationalite_declarant').disabled = false;

                }

                if ($(this).val() === 'mere')
                {
                    //Traitement input
                    $('#nom_declarant').val($('#nom_mere').val());
                    document.getElementById('nom_declarant').readOnly = true;

                    $('#prenom_declarant').val($('#prenom_mere').val());
                    document.getElementById('prenom_declarant').readOnly = true;

                    $('#date_naissance_declarant').val($('#date_naissance_mere').val());
                    document.getElementById('date_naissance_declarant').readOnly = true;

                    $('#lieu_naissance_declarant').val($('#lieu_naissance_mere').val());
                    document.getElementById('lieu_naissance_declarant').readOnly = true;

                    $('#telephone_declarant').val($('#telephone_mere').val());
                    document.getElementById('telephone_declarant').readOnly = true;

                    $('#domicile_declarant').val($('#domicile_mere').val());
                    document.getElementById('domicile_declarant').readOnly = true;

                    $('#numero_document_declarant').val($('#numero_document_mere').val());
                    document.getElementById('numero_document_declarant').readOnly = false;


                    //traitement select
                    var e = document.getElementById("sexe_declarant");
                    e.options[e.selectedIndex].text="Feminin";
                e.options[e.selectedIndex].value="F";
                    document.getElementById('sexe_declarant').disabled = false;


                    var e = document.getElementById("code_filiation_declarant");
                    e.options[e.selectedIndex].text="MERE";
                    e.options[e.selectedIndex].value="FIL_0002";
                    document.getElementById('code_filiation_declarant').disabled = true;

                    var ee = document.getElementById("code_profession_declarant");
                    var pe = document.getElementById("code_profession_mere");

                    ee.options[ee.selectedIndex].text= pe.options[pe.selectedIndex].text;
                    ee.options[ee.selectedIndex].value=pe.options[pe.selectedIndex].value;
                    document.getElementById('code_profession_declarant').disabled = false;

                   var ee = document.getElementById("code_type_document_declarant");
                   var pe = document.getElementById("code_type_document_mere");

                    ee.options[ee.selectedIndex].text= pe.options[pe.selectedIndex].text;
                    ee.options[ee.selectedIndex].value=pe.options[pe.selectedIndex].value;
                    document.getElementById('code_type_document_declarant').disabled = false;


                    var ee = document.getElementById("code_nationalite_declarant");
                    var pe = document.getElementById("code_nationalite_mere");

                    ee.options[ee.selectedIndex].text= pe.options[pe.selectedIndex].text;
                    ee.options[ee.selectedIndex].value=pe.options[pe.selectedIndex].value;
                    document.getElementById('code_nationalite_declarant').disabled = false;

                }
                if ($(this).val() === 'conjoint')
                {
                    //Traitement input
                    $('#nom_declarant').val($('#nom_conjoint').val());
                    document.getElementById('nom_declarant').readOnly = true;

                    $('#prenom_declarant').val($('#prenom_conjoint').val());
                    document.getElementById('prenom_declarant').readOnly = true;

                    $('#date_naissance_declarant').val($('#date_naissance_conjoint').val());
                    document.getElementById('date_naissance_declarant').readOnly = true;

                    $('#lieu_naissance_declarant').val($('#lieu_naissance_conjoint').val());
                    document.getElementById('lieu_naissance_declarant').readOnly = true;

                    $('#telephone_declarant').val($('#telephone_conjoint').val());
                    document.getElementById('telephone_declarant').readOnly = true;

                    $('#domicile_declarant').val($('#domicile_conjoint').val());
                    document.getElementById('domicile_declarant').readOnly = true;

                   $('#numero_document_declarant').val($('#numero_document_conjoint').val());
                  //  document.getElementById('numero_document_declarant').readOnly = true;


                    //traitement select
                    var e = document.getElementById("sexe_declarant");
                    var pe = document.getElementById("sexe_conjoint");

                    ee.options[ee.selectedIndex].text= pe.options[pe.selectedIndex].text;
                    ee.options[ee.selectedIndex].value=pe.options[pe.selectedIndex].value;
                    document.getElementById('sexe_declarant').disabled = false;


                    var e = document.getElementById("code_filiation_declarant");
                    e.options[e.selectedIndex].text="AUTRE";
                    e.options[e.selectedIndex].value="FIL_0008";
                    document.getElementById('code_filiation_declarant').disabled = false;

                    var ee = document.getElementById("code_profession_declarant");
                    var pe = document.getElementById("profession_conjoint");

                    ee.options[ee.selectedIndex].text= pe.options[pe.selectedIndex].text;
                    ee.options[ee.selectedIndex].value=pe.options[pe.selectedIndex].value;
                    document.getElementById('code_profession_declarant').disabled = false;

                    var ee = document.getElementById("code_type_document_declarant");
                    var pe = document.getElementById("code_type_document_conjoint");

                    ee.options[ee.selectedIndex].text= pe.options[pe.selectedIndex].text;
                    ee.options[ee.selectedIndex].value=pe.options[pe.selectedIndex].value;
                   document.getElementById('code_type_document_declarant').disabled = false;


                    var ee = document.getElementById("code_nationalite_declarant");
                    var pe = document.getElementById("code_nationalite_conjoint");

                    ee.options[ee.selectedIndex].text= pe.options[pe.selectedIndex].text;
                    ee.options[ee.selectedIndex].value=pe.options[pe.selectedIndex].value;
                    document.getElementById('code_nationalite_declarant').disabled = false;



                }
            });*/
            var nom_pere = $("#nom_pere");
            var prenom_pere = $("#prenom_pere");
            var date_naissance_pere = $("#date_naissance_pere");
            var lieu_naissance_pere = $("#lieu_naissance_pere");
            var domicile_pere = $("#domicile_pere");
            var telephone_pere = $("#telephone_pere");
            var code_profession_pere = $("#code_profession_pere");
            var code_nationalite_pere = $("#code_nationalite_pere");
            var niveau_instruction_pere = $("#niveau_instruction_pere");
            var code_type_document_pere = $("#code_type_document_pere");
            var numero_document_pere = $("#numero_document_pere");

            //information mère
            var nom_mere = $("#nom_mere");
            var prenom_mere = $("#prenom_mere");
            var date_naissance_mere = $("#date_naissance_mere");
            var lieu_naissance_mere = $("#lieu_naissance_mere");
            var domicile_mere = $("#domicile_mere");
            var telephone_mere = $("#telephone_mere");
            var code_profession_mere = $("#code_profession_mere");
            var code_nationalite_mere = $("#code_nationalite_mere");
            var niveau_instruction_mere = $("#niveau_instruction_mere");
            var code_type_document_mere = $("#code_type_document_mere");
            var numero_document_mere = $("#numero_document_mere");

            //information conjoint
            var nom_conjoint = $("#nom_conjoint");
            var prenom_conjoint = $("#prenom_conjoint");
            var date_mariage = $("#date_mariage");
            var cec_mariage = $("#cec_mariage");
            var code_regime = $("#code_regime");
            var sexe_conjoint = $("#sexe_conjoint");
            var num_acte_mariage = $("#num_acte_mariage");
            var date_naissance_conjoint = $("#date_naissance_conjoint");

            var code_nationalite_conjoint =  $("#code_nationalite_conjoint");
            var telephone_conjoint = $("#telephone_conjoint");
            var lieu_naissance_conjoint = $("#lieu_naissance_conjoint");
            var profession_conjoint = $("#profession_conjoint");

            //information déclarant
            var nom_declarant = $("#nom_declarant");
            var prenom_declarant = $("#prenom_declarant");
            var date_naissance_declarant = $("#date_naissance_declarant");
            var lieu_naissance_declarant = $("#lieu_naissance_declarant");
            var domicile_declarant = $("#domicile_declarant");
            var sexe_declarant = $("#sexe_declarant");
            var code_filiation_declarant = $("#code_filiation_declarant");
            var telephone_declarant = $("#telephone_declarant");
            var code_profession_declarant = $("#code_profession_declarant");
            var code_nationalite_declarant = $("#code_nationalite_declarant");
            var code_cause_deces = $("#code_cause_deces");

            var code_type_document_declarant = $("#code_type_document_declarant");
            var numero_document_declarant = $("#numero_document_declarant");

            $('input:radio[name="autredeclarant"]').change(function(){

                var declarant = $(this).val();

                if(declarant != "" || declarant != null)
                {
                    //alert(nom_pere.val());
                    if(declarant == "pere")
                    {
                       sexe_declarant = sexe_declarant.val("M");
                        //alert(sexe_declarant);
                       nom_declarant.val(nom_pere.val());
                       prenom_declarant.val(prenom_pere.val());
                       date_naissance_declarant.val(date_naissance_pere.val());
                       lieu_naissance_declarant.val(lieu_naissance_pere.val());
                       domicile_declarant.val(domicile_pere.val());
                       telephone_declarant.val(telephone_pere.val());
                       code_profession_declarant.val(code_profession_pere.val());
                       $("#code_profession_declarant option:selected").text();
                       code_nationalite_declarant.val(code_nationalite_pere.val());
                       $("#code_nationalite_declarant option:selected").text();
                       //$("#sexe_declarant option[value=M]").attr('selected','selected');
                       $("#sexe_declarant option:selected").text();
                       code_filiation_declarant = code_filiation_declarant.val("FIL_0001");
                       $("#code_filiation_declarant option:selected").text();
                       code_type_document_declarant.val(code_type_document_pere.val());
                       $("#code_type_document_declarant option:selected").text();
                       numero_document_declarant.val(numero_document_pere.val());

                       document.getElementById('nom_declarant').readOnly = true;
                       document.getElementById('lieu_naissance_declarant').readOnly = true;
                       document.getElementById('code_nationalite_declarant').disabled = true;
                       document.getElementById('sexe_declarant').disabled = true;
                       document.getElementById('prenom_declarant').readOnly = true;
                       document.getElementById('code_filiation_declarant').disabled = true;
                       document.getElementById('date_naissance_declarant').readOnly = true;

                    }

                    else if(declarant == "mere")
                    {
                        sexe_declarant = sexe_declarant.val("F");
                        //alert(sexe_declarant);
                        nom_declarant.val(nom_mere.val());
                        prenom_declarant.val(prenom_mere.val());
                        date_naissance_declarant.val(date_naissance_mere.val());
                        lieu_naissance_declarant.val(lieu_naissance_mere.val());
                        domicile_declarant.val(domicile_mere.val());
                        telephone_declarant.val(telephone_mere.val());
                        code_profession_declarant.val(code_profession_mere.val());
                        $("#code_profession_declarant option:selected").text();
                        code_nationalite_declarant.val(code_nationalite_mere.val());
                        $("#code_nationalite_declarant option:selected").text();
                       // $("#sexe_declarant option[value=F]").attr('selected','selected');
                        $("#sexe_declarant option:selected").text();
                        code_filiation_declarant = code_filiation_declarant.val("FIL_0002");
                        $("#code_filiation_declarant option:selected").text();
                        code_type_document_declarant.val(code_type_document_mere.val());
                        $("#code_type_document_declarant option:selected").text();
                        numero_document_declarant.val(numero_document_mere.val());

                        document.getElementById('nom_declarant').readOnly = true;
                        document.getElementById('lieu_naissance_declarant').readOnly = true;
                        document.getElementById('code_nationalite_declarant').disabled = true;
                        document.getElementById('sexe_declarant').disabled = true;
                        document.getElementById('prenom_declarant').readOnly = true;
                        document.getElementById('code_filiation_declarant').disabled = true;
                        document.getElementById('date_naissance_declarant').readOnly = true;

                    }

                    else if(declarant == "conjoint")
                    {

                        nom_declarant.val(nom_conjoint.val());
                        prenom_declarant.val(prenom_conjoint.val());
                        date_naissance_declarant.val(date_naissance_conjoint.val());
                        lieu_naissance_declarant.val(lieu_naissance_conjoint.val());
                        domicile_declarant.val(domicile_defunt.val());
                        telephone_declarant.val(telephone_conjoint.val());
                        code_profession_declarant.val(code_profession_conjoint.val());
                        $("#code_profession_declarant option:selected").text();
                        code_nationalite_declarant.val(code_nationalite_conjoint.val());
                        $("#code_nationalite_declarant option:selected").text();
                        sexe_declarant = sexe_declarant.val(sexe_conjoint.val());
                        $("#sexe_declarant option:selected").text();
                        code_filiation_declarant = code_filiation_declarant.val("FIL_0008");
                        $("#code_filiation_declarant option:selected").text();

                        document.getElementById('nom_declarant').readOnly = true;
                        document.getElementById('lieu_naissance_declarant').readOnly = true;
                        document.getElementById('code_nationalite_declarant').disabled = true;
                        document.getElementById('sexe_declarant').disabled = true;
                        document.getElementById('prenom_declarant').readOnly = true;
                        document.getElementById('code_filiation_declarant').disabled = true;
                        document.getElementById('date_naissance_declarant').readOnly = true;
                    }

                    else{
                        nom_declarant.val("");
                        prenom_declarant.val("");
                        date_naissance_declarant.val("");
                        lieu_naissance_declarant.val("");
                        domicile_declarant.val("");
                        telephone_declarant.val("");

                        code_profession_declarant.val("");
                        $("#code_profession_declarant option:selected").text();

                        code_nationalite_declarant.val("");
                        $("#code_nationalite_declarant option:selected").text();

                        sexe_declarant = sexe_declarant.val("M");
                        $("#sexe_declarant option:selected").text();

                        code_filiation_declarant = code_filiation_declarant.val("");
                        $("#code_filiation_declarant option:selected").text();

                        code_type_document_declarant.val("");
                        $("#code_type_document_declarant option:selected").text();
                        numero_document_declarant.val("");


                        document.getElementById('nom_declarant').readOnly = false;
                        document.getElementById('lieu_naissance_declarant').readOnly = false;
                        document.getElementById('code_nationalite_declarant').disabled = false;
                        document.getElementById('sexe_declarant').disabled = false;
                        document.getElementById('prenom_declarant').readOnly = false;
                        document.getElementById('code_filiation_declarant').disabled = false;
                        document.getElementById('date_naissance_declarant').readOnly = false;
 }

                }

            });

        });
    </script>
    <script>
        function dateFrench(dat){
            var date = new Date(dat);
            return date.getDate()+ "/"+(date.getMonth() + 1 )+"/"+date.getFullYear();
          }
        </script>


    <script>

        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            w=window.open();
            w.document.write(printContents);
            w.print();
            w.close();
        }
    </script>
@endsection
