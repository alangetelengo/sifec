@extends("layout.app")
@section("titre")
    Déclaration décès
@endsection
@section("sous-titre")
    Mise à jour de la déclaration décès
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
                            <div class="d-flex justify-content-end align-items-center">
                                <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".search-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche</button>
                            </div>

                           {{--  <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target=".search_mere-modal-lg">Faire une recherche</button>  --}}
                            <section>

                                <div class="row">
                                    <div class="col-md-4">
                                    <div class="form-group">
                                    <label class="form-label" for="validationCustom07">Date décès
                                        <!-- <span class="text-danger">*</span> -->
                                    </label>
                                        <input type="date" class="form-control required  @error('date_defunt') is-invalid @enderror " value="{{ $declaration->date_heure_deces }}"  id="date_deces"  max="{{ \Carbon\Carbon::now()->format('Y-m-d'); }}" >
                                        @error("date_deces")
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                   </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                        <label class="form-label" for="validationCustom07">Heure décès
                                        <!-- <span class="text-danger">*</span> -->
                                    </label>
                                        <input class="form-control required  @error('heure_defunt') is-invalid @enderror" type="time"  placeholder="" name="heure_deces" id="heure_deces">
                                        @error("heure_deces")
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                   </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                        <label class="form-label" for="validationCustom07">CEC
                                        <!-- <span class="text-danger">*</span> -->
                                    </label>
                                        <input class="form-control required  @error('cec_defunt') is-invalid @enderror " type="text"  placeholder="" name="cec_defunt" id="cec_defunt">
                                        @error("nom_cec")
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
                                        <label class="form-label">Nom(s) défunt <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control required  @error('nom_defunt') is-invalid @enderror" value="{{ $declaration->defunt->nom }}"  placeholder="" id="nom_defunt" name="nom_defunt">
                                        @error("nom_defunt")
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                        <label class="form-label">Prénom(s) défunt</label>
                                        <input type="text" class="form-control required @error('prenom_defunt') is-invalid @enderror" value="{{ $declaration->defunt->prenom }}"   id="prenom_defunt" name="prenom_defunt">
                                        @error("prenom_defunt")
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                        <label class="form-label">Sexe <span class="text-danger">*</span></label>
                                        <select id="sexe_defunt" name="sexe_defunt" class="form-select form-control required">
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

                                        <label class="form-label">Date de naissance <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control  @error('date_naissance_defunt') is-invalid @enderror" value="{{ old("date_naissance_defunt") }}" id="date_naissance_defunt" name="date_naissance_defunt" max="{{ \Carbon\Carbon::now()->format('Y-m-d'); }}">
                                        @error("date_naissance_defunt")
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                        <label class="form-label">Lieu de naissance <span class="text-danger">*</span></label>
                                        {{--  <input type="text" class="form-control" id="lieu_naissance_pere" placeholder="Lieu de naissance">  --}}
                                        <select name="code_localite" id="code_localite" class="form-select form-control required">
                                                <option disabled selected>Choisissez</option>
                                            @foreach ($localites as $localite)
                                                <option value="{{$localite->code_localite}}">{{ $localite->lib_localite }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                    <div class="col-md-4">
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
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
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
                                    <div class="col-md-4">
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                        <label class="form-label">Réligion <span class="text-danger">*</span></label>
                                        <select name="code_religion_defunt" id="code_religion_defunt" class="form-select form-control required">
                                                <option disabled selected>Choisissez</option>
                                            @foreach ($religions as $religion)
                                                <option value="{{ $religion->code_religion }}">{{ $religion->lib_religion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                </div>

                                <div class="row">

                                    <div class="mb-2 col-md-4">
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
                                    <div class="mb-2 col-md-4">
                                        <div class="form-group">
                                        <label class="form-label">Lieu de décés <span class="text-danger">*</span></label>
                                        {{--  <input type="text" class="form-control" id="lieu_naissance_deces" placeholder="Lieu de naissance">  --}}
                                        <select name="code_localite" id="code_localite" class="form-select form-control required">
                                            <option disabled selected>Choisissez</option>
                                        @foreach ($localites as $localite)
                                            <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                        @endforeach
                                       </select>
                                    </div>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <div class="form-group">
                                    <label class="form-label"> Adresse défunt<span class="text-danger">*</span>
                                    </label>
                                        <textarea class="form-control" id="domicile_defunt" name="domicile_defunt"  rows="3"  required></textarea>
                                </div>
                                </div>

                                </div>

                            </section>
                            <!-- Step 2 -->
                            <h6>Conjoint</h6>
                            <section>

                                <div class="row">
                                    <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Nom(s) Conjoint </label>
                                        <input type="text" class="form-control"lass="form-control @error('nom_conjoint') is-invalid @enderror" value="{{ old("nom_conjoint") }}"  id="nom_conjoint" name="nom_conjoint">
                                        @error("nom_defunt")
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    </div>
                                    <div class="col-md-4">
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
                                     <div class="col-md-4">
                                        <div class="form-group">
                                        <label class="form-label">Date de mariage </label>
                                        <input type="date" class="form-control @error('date_mariage') is-invalid @enderror" value="{{ old("date_mariage") }}" placeholder="" id="date_mariage" name="date_mariage" max="{{ \Carbon\Carbon::now()->format('Y-m-d'); }}">
                                        @error("prenom_defunt")
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
                                        <label class="form-label">CEC du mariage </label>
                                        <select id="cec_mariage" name="cec_mariage" class="form-select form-control required">
                                                <option disabled selected>Choisissez</option>
                                            @foreach ($religions as $religion)
                                                <option value="{{ $religion->code_religion }}">{{ $religion->lib_religion }}</option>
                                            @endforeach
                                        </select>
                                       </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                        <label class="form-label"> Option du mariage.
                                            <!-- <span class="text-danger">*</span> -->
                                        </label>
                                            <select name="option_mariage" class="form-select form-control required" id="option_mariage">
                                                <option  data-display="Select">Veuillez sélectionner</option>
                                                <option value="Biens séparés">Biens séparés</option>
                                                <option value="Biens communs">Biens communs</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select a one.
                                            </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">N° acte de mariage
                                            <!-- <span class="text-danger">*</span> -->
                                        </label>
                                            <input type="text" class="form-control" name="num_acte_mariage" id="num_acte_mariage" placeholder="" required>
                                            <div class="invalid-feedback">
                                                Please enter a Currency.
                                            </div>
                                    </div>
                                   </div>
                                </div>
                            </section>
                            <!-- Step 3 -->
                            <h6>Déclarant</h6>
                            <section>

                                <div class="row">
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
                                            <input name="date_naissance_declarant" type="date" class="form-control" placeholder="" id="date_naissance_declarant" max="{{ \Carbon\Carbon::now()->format('Y-m-d'); }}" >
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
                                     {{--  <input type="text" class="form-control" id="lieu_naissance_pere" placeholder="Lieu de naissance">  --}}
                                    <select name="code_localite_declarant" id="code_localite_declarant" class="form-select form-control required">
                                        <option disabled selected>Choisissez</option>
                                    @foreach ($localites as $localite)
                                        <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                    @endforeach
                                   </select>
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
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label"> Diagnostic <span
                                            class="text-danger">*</span>
                                    </label>
                                        <textarea class="form-control" name="diagnostic_deces"  id="diagnostic_deces"  rows="5" placeholder="" ></textarea>
                                        @error("disgnotic_defunt")
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            </section>
                            {{--  <!-- Step 4 -->
                            <h6>Détails</h6>
                            <section>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="behName1" class="form-label">Behaviour :</label>
                                            <input type="text" class="form-control required" id="behName1">
                                        </div>
                                        <div class="form-group">
                                            <label for="participants1" class="form-label">Confidance</label>
                                            <input type="text" class="form-control required" id="participants1">
                                        </div>
                                        <div class="form-group">
                                            <label for="participants1" class="form-label">Result</label>
                                            <select class="form-select form-control required" id="participants1" name="location">
                                                <option value="">Select Result</option>
                                                <option value="Selected">Selected</option>
                                                <option value="Rejected">Rejected</option>
                                                <option value="Call Second-time">Call Second-time</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="decisions1" class="form-label">Comments</label>
                                            <textarea name="decisions" id="decisions1" rows="4" class="form-control"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" class="form-label">Rate Interviwer :</label>
                                            <div class="c-inputs-stacked">
                                                <label class="inline form-check block">
                                                    <input type="checkbox" class="form-check-input"> <span class="custom-control-indicator"></span> <span class="custom-control-description ml-0">1 star</span> </label>
                                                <label class="inline form-check block">
                                                    <input type="checkbox" class="form-check-input"> <span class="custom-control-indicator"></span> <span class="custom-control-description ml-0">2 star</span> </label>
                                                <label class="inline form-check block">
                                                    <input type="checkbox" class="form-check-input"> <span class="custom-control-indicator"></span> <span class="custom-control-description ml-0">3 star</span> </label>
                                                <label class="inline form-check block">
                                                    <input type="checkbox" class="form-check-input"> <span class="custom-control-indicator"></span> <span class="custom-control-description ml-0">4 star</span> </label>
                                                <label class="inline form-check block">
                                                    <input type="checkbox" class="form-check-input"> <span class="custom-control-indicator"></span> <span class="custom-control-description ml-0">5 star</span> </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>  --}}
                        </form>
                    </div>


                    <div class="modal fade search-search-modal-lg" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Rechercher le défunt</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Nom(s) défunt <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"lass="form-control @error('nom_defunt') is-invalid @enderror" value="{{ old("nom_defunt") }}" placeholder="" id="nom_defunt">
                                            @error("nom_defunt")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Prénom(s) défunt</label>
                                            <input type="text" class="form-control @error('prenom_pere') is-invalid @enderror" value="{{ old("prenom_defunt") }}" placeholder="" id="prenom_defunt">
                                            @error("prenom_defunt")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Date de naissance</label>
                                            <input type="date" class="form-control @error('date_naissance_pere') is-invalid @enderror" value="{{ old("date_naissance_pere") }}" id="date_naissance_pere">
                                            @error("date_naissance_pere")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Lieu de naissance </label>
                                            <select id="code_localite" class="form-select form-control required">
                                                    <option disabled selected>Choisissez</option>
                                                @foreach ($localites as $localite)
                                                    <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-info text-white">Rechercher</button>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Résultat de la recherche</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table id="example" class="display" style="min-width: 845px">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Nom</th>
                                                                    <th>Prénom</th>
                                                                    <th>Date naissance</th>
                                                                    <th>Sexe</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td></td>
                                                                </tr>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Nom</th>
                                                                    <th>Prénom</th>
                                                                    <th>Date naissance</th>
                                                                    <th>Sexe</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
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
                    title: "Enrégistrer la déclaration ?",
                    icon: 'question',
                    text: "Assurez-vous, puis confirmez !",
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonText: "Oui, Confirmer!",
                    cancelButtonText: "Non, Annuler!",
                    reverseButtons: !0
                }).then(function (e) {

                    if (e.value === true) {
                        let token = $('meta[name="csrf-token"]').attr('content');
                       // var _url = "{{route('declarationdeces.store')}}";

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
                        var lieu_deces = $("#code_localite");
                        var domicile_defunt = $("#domicile_defunt");
                        //information conjoint
                        var nom_conjoint = $("#nom_conjoint");
                        var prenom_conjoint = $("#prenom_conjoint");
                        var date_mariage = $("#date_mariage");
                        var cec_mariage = $("#cec_mariage");
                        var option_mariage = $("#option_mariage");
                        var num_acte_mariage = $("#num_acte_mariage");
                        //information déclarant
                        var nom_declarant = $("#nom_declarant");
                        var prenom_declarant = $("#prenom_declarant");
                        var date_naissance_declarant = $("#date_naissance_declarant");
                        var lieu_naissance_declarant = $("#code_localite_declarant");
                        var domicile_declarant = $("#domicile_declarant");
                        var filiation = $("#code_filiation_declarant");
                        //var telephone_declarant = $("#telephone_declarant");
                        //var profession_declarant = $("#profession_declarant");
                        //var nationalite_declarant = $("#nationalite_declarant");


                        $.ajax({
                            type: 'POST',
                            url: "{{route('declarationdeces.store')}}",
                            data: {
                                _token: token,
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
                                domicile_defunt: domicile_defunt.val(),
                                nom_conjoint: nom_conjoint.val(),
                                prenom_conjoint: prenom_conjoint.val(),
                                date_mariage: date_mariage.val(),
                                cec_mariage: cec_mariage.val(),
                                option_mariage: option_mariage.val(),
                                num_acte_mariage: num_acte_mariage.val(),
                                nom_declarant: nom_declarant.val(),
                                prenom_declarant: prenom_declarant.val(),
                                date_naissance_declarant: date_naissance_declarant.val(),
                                lieu_naissance_declarant: lieu_naissance_declarant.val(),
                                domicile_declarant: domicile_declarant.val(),
                                filiation: filiation.val()
                            },
                           // data: {_token: token},
                            success: function(response ) {
                                if (response.success==true) {
                                    swal.fire("Enrégistrée!", response.message, "success");
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

        }), $(".validation-wizard").validate({
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
                email: {
                    email: !0
                }
            }


        })



    </script>

@endsection
