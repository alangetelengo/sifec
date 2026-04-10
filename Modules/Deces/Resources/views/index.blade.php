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
@endsection
@section("corps")

<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
        <!-- row -->
        <div class="row">
            <div class="col-xl-12 col-xxl-12">
                <div class="card">

                    <div class="card-body">
                        <div id="smartwizard" class="form-wizard order-create">
                            <ul class="nav nav-wizard">
                                <li><a class="nav-link" href="#wizard_Service">
                                    <span>1</span>
                                </a></li>
                                <li><a class="nav-link" href="#wizard_Time">
                                    <span>2</span>
                                </a></li>
                                <li><a class="nav-link" href="#wizard_Details">
                                    <span>3</span>
                                </a></li>
                                <li><a class="nav-link" href="#wizard_Payment">
                                    <span>4</span>
                                </a></li>
                            </ul>
                            <div class="tab-content">

                                <div id="wizard_Service" class="tab-pane" role="tabpanel">

                                <div class="row">

                                    <div class="col-xl-6">

                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom07">Date décès
                                                <!-- <span class="text-danger">*</span> -->
                                            </label>
                                            <div class="col-lg-6">

                                                <input type="date" class="form-control" placeholder="Entrez la date de l'décès" >
                                                <div class="invalid-feedback">
                                                    Please enter a url.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom07">Heure décès
                                                <!-- <span class="text-danger">*</span> -->
                                            </label>
                                            <div class="col-lg-6">

                                                <input class="form-control" type="time"  placeholder="Entrez l'heure du décès">
                                                <div class="invalid-feedback">
                                                    Please enter a url.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom01">Nom
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="validationCustom01"  placeholder="Entrez le nom..." required>
                                                <div class="invalid-feedback">
                                                    Please enter a username.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom02">Prénom(s)
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="validationCustom02"  placeholder="Entrez le(s) prénom(s).." required>
                                                <div class="invalid-feedback">
                                                    Please enter a Email.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom05">Niveau d'instruction
                                                <!-- <span class="text-danger">*</span> -->
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="default-select wide form-control" id="validationCustom05">
                                                    <option  data-display="Select">Veuillez sélectionner</option>
                                                    <option value="html">Universiatire</option>
                                                    <option value="css">Sécondaire</option>
                                                    <option value="javascript">Primaire</option>
                                                    <option value="angular">Non Scolarisé</option>

                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select a one.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom05">Réligion
                                                <!-- <span class="text-danger">*</span> -->
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="default-select wide form-control" id="validationCustom05">
                                                    <option  data-display="Select">Veuillez sélectionner </option>
                                                    <option value="html">Universiatire</option>
                                                    <option value="css">Sécondaire</option>
                                                    <option value="javascript">Primaire</option>
                                                    <option value="angular">Non Scolarisé</option>

                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select a one.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom05">Nationalité
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="default-select wide form-control" id="validationCustom05">
                                                    <option  data-display="Select">Veuillez sélectionner</option>
                                                    <option value="html">Congolaise</option>
                                                    <option value="css">Gabonaise</option>
                                                    <option value="javascript">Camerounaise</option>
                                                    <option value="angular">Chinoise</option>
                                                    <option value="angular">Française</option>

                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select a one.
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-xl-6">
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom05">Profession
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="default-select wide form-control" id="validationCustom05">
                                                    <option  data-display="Select">Veuillez sélectionner</option>
                                                    <option value="html">Informaticien</option>
                                                    <option value="css">Médecin</option>
                                                    <option value="javascript">Juriste</option>
                                                    <option value="angular">Journaliste</option>
                                                    <option value="angular">Mécanicien</option>

                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select a one.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom05">Situation matri.
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="default-select wide form-control" id="validationCustom05">
                                                    <option  data-display="Select">Veuillez sélectionner</option>
                                                    <option value="html">Célibataire</option>
                                                    <option value="css">Marié(é)</option>
                                                    <option value="javascript">Divorcé(e)</option>
                                                    <option value="angular">Autre</option>


                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select a one.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom06">CEC de naissance
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="validationCustom06" placeholder="Entrez le CEC de naissance" required>
                                                <div class="invalid-feedback">
                                                    Please enter a Currency.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom07">NNIPP
                                                <!-- <span class="text-danger">*</span> -->
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="validationCustom07"  placeholder="Entrez le NNIPP " required>
                                                <div class="invalid-feedback">
                                                    Please enter a url.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom04">Adresse<span
                                                    class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <textarea class="form-control" id="validationCustom04"  rows="5" placeholder="Entrez votre adresse " required></textarea>
                                                <div class="invalid-feedback">
                                                    Please enter a Suggestions.
                                                </div>
                                            </div>
                                        </div>


                                        <!-- <div class="mb-3 row">
                                            <div class="col-lg-8 ms-auto">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>

                                </div>

                                <div id="wizard_Time" class="tab-pane" role="tabpanel">

                                    <div class="row">

                                    <div class="col-xl-6">

                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom07">Date de naissance
                                                <!-- <span class="text-danger">*</span> -->
                                            </label>
                                            <div class="col-lg-6">

                                                <input type="date" class="form-control" placeholder="Entrez la date de naissance" >
                                                <div class="invalid-feedback">
                                                    Please enter a url.
                                                </div>
                                            </div>
                                        </div>


                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom01">Nom conjoint(e)
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="validationCustom01"  placeholder="Entrez le nom ..." required>
                                                <div class="invalid-feedback">
                                                    Please enter a username.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom02">Prénom(s) conjoint(e)
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="validationCustom02"  placeholder="Entrez le(s) prénom(s) ..." required>
                                                <div class="invalid-feedback">
                                                    Please enter a Email.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom05">Lieu de survenance
                                                <!-- <span class="text-danger">*</span> -->
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="default-select wide form-control" id="validationCustom05">
                                                    <option  data-display="Select">Veuillez sélectionner</option>
                                                    <option value="html">Brazzaville</option>
                                                    <option value="css">Pointe-Noire</option>
                                                    <option value="javascript">Dolisie</option>
                                                    <option value="angular">OYO</option>

                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select a one.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom05">Lieu de décés
                                                <!-- <span class="text-danger">*</span> -->
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="default-select wide form-control" id="validationCustom05">
                                                    <option  data-display="Select">Veuillez sélectionner </option>
                                                    <option value="html">Brazzaville</option>
                                                    <option value="css">Pointe-Noire</option>
                                                    <option value="javascript">Dolisie</option>
                                                    <option value="angular">OYO</option>

                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select a one.
                                                </div>
                                            </div>
                                        </div>

                                       <!--  <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom05">Nationalité
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="default-select wide form-control" id="validationCustom05">
                                                    <option  data-display="Select">Veuillez sélectionner</option>
                                                    <option value="html">Congolaise</option>
                                                    <option value="css">Gabonaise</option>
                                                    <option value="javascript">Camerounaise</option>
                                                    <option value="angular">Chinoise</option>
                                                    <option value="angular">Française</option>

                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select a one.
                                                </div>
                                            </div>
                                        </div> -->

                                    </div>
                                    <div class="col-xl-6">
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom07">Date de mariage
                                                <!-- <span class="text-danger">*</span> -->
                                            </label>
                                            <div class="col-lg-6">

                                                <input type="date" class="form-control" placeholder="Entrez la date de mariage" >
                                                <div class="invalid-feedback">
                                                    Please enter a url.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom05">CEC du mariage.
                                                <!-- <span class="text-danger">*</span> -->
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="default-select wide form-control" id="validationCustom05">
                                                    <option  data-display="Select">Veuillez sélectionner</option>
                                                    <option value="html">POTO POTO</option>
                                                    <option value="css">MOUNGALIE</option>
                                                    <option value="javascript">OUENZE</option>
                                                    <option value="angular">TALANGAI</option>


                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select a one.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom05">Option du mariage.
                                                <!-- <span class="text-danger">*</span> -->
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="default-select wide form-control" id="validationCustom05">
                                                    <option  data-display="Select">Veuillez sélectionner</option>
                                                    <option value="html">Biens séparés</option>
                                                    <option value="css">Biens communs</option>


                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select a one.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom06">N° acte de mariage
                                                <!-- <span class="text-danger">*</span> -->
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="validationCustom06" placeholder="Entrez le n° de l'acte " required>
                                                <div class="invalid-feedback">
                                                    Please enter a Currency.
                                                </div>
                                            </div>
                                        </div>





                                        <!-- <div class="mb-3 row">
                                            <div class="col-lg-8 ms-auto">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div> -->
                                    </div>
                                    </div>

                                </div>

                                <div id="wizard_Details" class="tab-pane" role="tabpanel">

                                    <div class="row">

                                    <div class="col-xl-6">




                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom01">Nom déclarant
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="validationCustom01"  placeholder="Entrez le nom du déclarant..." required>
                                                <div class="invalid-feedback">
                                                    Please enter a username.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom02">Prénom(s) déclarant
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="validationCustom02"  placeholder="Entrez le(s) prénom(s) déclarant..." required>
                                                <div class="invalid-feedback">
                                                    Please enter a Email.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom05">Filiation
                                                <!-- <span class="text-danger">*</span> -->
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="default-select wide form-control" id="validationCustom05">
                                                    <option  data-display="Select">Veuillez sélectionner</option>
                                                    <option value="html">Père</option>
                                                    <option value="css">Mère</option>
                                                    <option value="javascript">Enfant</option>
                                                    <option value="angular">Frère</option>

                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select a one.
                                                </div>
                                            </div>
                                        </div>



                                       <!--  <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom05">Nationalité
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="default-select wide form-control" id="validationCustom05">
                                                    <option  data-display="Select">Veuillez sélectionner</option>
                                                    <option value="html">Congolaise</option>
                                                    <option value="css">Gabonaise</option>
                                                    <option value="javascript">Camerounaise</option>
                                                    <option value="angular">Chinoise</option>
                                                    <option value="angular">Française</option>

                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select a one.
                                                </div>
                                            </div>
                                        </div> -->

                                    </div>
                                    <div class="col-xl-6">
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom07">Date de naissance du déclarant
                                                <!-- <span class="text-danger">*</span> -->
                                            </label>
                                            <div class="col-lg-6">

                                                <input type="date" class="form-control" placeholder="Entrez la date de naissance du déclarant" >
                                                <div class="invalid-feedback">
                                                    Please enter a url.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom05">Lieu de naissance du déclarant.
                                                <!-- <span class="text-danger">*</span> -->
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="default-select wide form-control" id="validationCustom05">
                                                    <option  data-display="Select">Veuillez sélectionner</option>
                                                    <option value="html">POTO POTO</option>
                                                    <option value="css">MOUNGALIE</option>
                                                    <option value="javascript">OUENZE</option>
                                                    <option value="angular">TALANGAI</option>


                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select a one.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom04">Domicile du déclarant<span
                                                    class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <textarea class="form-control" id="validationCustom04"  rows="5" placeholder="Entrez le domicile du déclarant " required></textarea>
                                                <div class="invalid-feedback">
                                                    Please enter a Suggestions.
                                                </div>
                                            </div>
                                        </div>


                                        <!-- <div class="mb-3 row">
                                            <div class="col-lg-8 ms-auto">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div> -->
                                    </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-12">

                                            <div class="mb-3 row">
                                            <label class="col-lg-3 col-form-label" for="validationCustom04">Diagnostic<span
                                                    class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-11">
                                                <textarea class="form-control" id="validationCustom04"  rows="5" placeholder="Entrez le diagnostic " required></textarea>
                                                <div class="invalid-feedback">
                                                    Please enter a Suggestions.
                                                </div>
                                            </div>
                                        </div>

                                        </div>

                                    </div>

                                </div>

                                <div id="wizard_Payment" class="tab-pane" role="tabpanel">



                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="alert alert-success alert-dismissible fade show">
                                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                                                <strong>Déclaration </strong> prise en compte avec Success!.
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                                                </button>
                                            </div>

                                        </div>
                                    </div>


                                </div>

                            </div>
                        </div>
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

@endsection
