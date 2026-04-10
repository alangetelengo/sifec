@extends("layout.app")
@section("titre")
    Déclaration naissance
@endsection
@section("sous-titre")
    Déclaration naissance
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
                <div class="card wizard-content">
                    <div class="card-body">
                        {{--  <h4 class="card-title">Step wizard with validation</h4>
                        <h6 class="card-subtitle">You can us the validation like what we did</h6>  --}}

                        <form action="#" class="validation-wizard wizard-circle">

                            <!-- Step 1 -->

                            <h6>Enfant</h6>

                           <span class="btn btn-primary" style="margin-bottom:2%; width: 100%">{{ $dn->code_declaration_naissance }}</span>

                           {{--  <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target=".search_mere-modal-lg">Faire une recherche</button>  --}}
                            <section>
                                <hr>
                                <div class="row">
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Nom(s) enfant <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nom_enfant" placeholder="Nom enfant" value="{{ $dn->enfant->nom }}" id="nom_enfant">

                                    </div>

                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Prénom(s) enfant</label>
                                        <input type="text" class="form-control" name="prenom_enfant" value="{{ $dn->enfant->prenom }}" placeholder="Prénom enfant" id="prenom_enfant">

                                    </div>

                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Sexe <span class="text-danger">*</span></label>
                                        <select id="sexe_enfant" name="sexe_enfant" class="form-control form-control wide">


                                            <option value="M" {{"M"==$dn->enfant->sexe ? "selected":"" }}>Masculin</option>
                                            <option value="F" {{"F"==$dn->enfant->sexe ? "selected":"" }}>Feminin</option>


                                        </select>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Date de naissance <span class="text-danger">*</span></label>
                                        <input type="date" name="date_naissance_enfant" value={{ $dn->enfant->date_naissance }} max="<?php echo date("Y-m-d"); ?>" min="<?php $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 3 month'));?>" onchange="compare()" class="form-control" id="date_naissance_enfant">

                                    </div>

                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Lieu de naissance <span class="text-danger">*</span></label>
                                        <input type="text" name="lieu_naissance_enfant" value={{ $dn->enfant->lieu_naissance }} class="form-control" id="lieu_naissance_enfant">

                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Heure de naissance <span class="text-danger">*</span></label>
                                        <input type="time" name="heure_naissance_enfant" value={{ date("h:i", strtotime($dn->enfant->date_naissance)) }} class="form-control"  id="heure_naissance_enfant">

                                    </div>
                                </div>
                                <div class="row">
                                    {{-- <div class="mb-2 col-md-4">
                                        <label class="form-label">Nationalité <span class="text-danger">*</span></label>
                                        <select id="code_nationalite_enfant" class="form-control form-control wide">
                                                <option disabled selected>Choisissez</option>
                                            @foreach ($nationalites as $nationalite)
                                                <option value="{{ $nationalite->code_nationalite }}">{{ $nationalite->lib_nationalite }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Lieu de survenance <span class="text-danger">*</span></label>
                                        <select id="code_lieu_survenance" name="lieu_survenance" class="form-control form-control wide">


                                            <option value={{ $dn->lieusurvenance->code_lieu_survenance }}>{{ $dn->lieusurvenance->lib_lieu_survenance   }}</option>

                                            @foreach ($lieuSurvenances as $item)
                                                <option value="{{ $item->code_lieu_survenance }}">{{ $item->lib_lieu_survenance }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Situation matrimoniale des parents</label>
                                        <select id="code_situation_matrimoniale" name="code_situation_matrimoniale" class="form-control form-control wide">

                                            <option value={{ $dn->code_situation_mat  }}>{{ $dn->sitMatParent->lib_situation_matrimoniale   }}</option>
                                            @foreach ($situationMatrimoniales as $item)
                                                <option value="{{ $item->code_situation_matrimoniale }}">{{ $item->lib_situation_matrimoniale }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Nombre d enfants (y compris le sujet)</label>
                                        <input type="number" name="nombre_enfant"  value={{$dn->nombre_enfant}} min="1" class="form-control" placeholder="0" id="nombre_enfants">

                                    </div>
                                </div>
                            </section>
                            <!-- Step 2 -->
                            <h6>Père</h6>
                            <section>
                                <div class="d-flex justify-content-end align-items-center">
                                    <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".search-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche du père</button>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Nom(s) père <span class="text-danger">*</span></label>
                                        <input type="text" name="nom_pere"  value={{$dn->pere->nom}} class="form-control"lass="form-control"  placeholder="Nom du père" id="nom_pere">
                                    </div>

                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Prénom(s) du père </label>
                                        <input type="text" name="prenom_pere" value={{$dn->pere->prenom}} class="form-control" placeholder="Prénom du père" id="prenom_pere">

                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Date de naissance du père<span class="text-danger">*</span></label>
                                        <input type="date" name="date_naissance_pere"  value={{$dn->pere->date_naissance}} max="<?php $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 14 years'));?>" min="<?php echo date('Y-m-d', strtotime($jour. ' - 100 years')); ?>" onchange="compare()" class="form-control" id="date_naissance_pere">
                                    </div>


                                   {{--  <div class="mb-2 col-md-4">
                                        <label class="form-label">Sexe<span class="text-danger">*</span></label>
                                        <select id="sexe_pere" class="form-control form-control wide">
                                            <option value="M">Masculin</option>
                                            <option value="F">Féminin</option>
                                        </select>
                                    </div> --}}
                                </div>
                                <div class="row">

                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Lieu de naissance père</label>
                                        <input type="text" name="lieu_naissance_pere"  value={{$dn->pere->lieu_naissance}} class="form-control" id="lieu_naissance_pere" placeholder="Lieu de naissance">
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Domicile du père<span class="text-danger">*</span></label>
                                        <input type="text" name="domicile_pere" class="form-control"  value={{$dn->pere->adresse}} id="domicile_pere" placeholder="Domicile du père">
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Nationalité du père<span class="text-danger">*</span></label>
                                        <select name="code_nationalite_pere" id="code_nationalite_pere" class="form-control form-control wide">
                                            <option value={{ $dn->pere->code_nationalite }}>{{ $dn->pere->nationalite->lib_nationalite }}</option>
                                            @foreach ($nationalites as $nationalite)
                                                <option value="{{ $nationalite->code_nationalite }}">{{ $nationalite->lib_nationalite }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Profession du père<span class="text-danger">*</span></label>
                                        <select id="profession_pere" name="profession_pere" class="form-control form-control wide">

                                            <option value="{{ $dn->pere->code_profession }}">{{ $dn->pere->profession->lib_profession }}</option>
                                            @foreach ($professions as $item)
                                                <option value="{{ $item->code_profession }}">{{ $item->lib_profession }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Téléphone père<span class="text-danger">*</span></label>
                                        <input type="text" name="telephone_pere" value={{$dn->pere->telephone}} id="telephone_pere" class="form-control form-control wide" placeholder="Téléphone mère">
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Niveau d instruction du père</label>
                                        <select name="niveau_instruction_pere" id="niveau_instruction_pere" class="form-control form-control wide">
                                            <option value={{ $dn->pere->niveau_instruction }}>{{  $dn->pere->niveau_instruction }}</option>
                                            @foreach ($instructions as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Type pièce d'identité</label>
                                        <select name="code_type_document_pere" id="code_type_document_pere" class="form-control form-control wide">


                                            @foreach ($typedocuments as $item)
                                                <option value="{{ $item->code_type_document }}" {{ $item->code_type_document==$dn->pere->document->code_type_document ? "selected":"" }}>{{ $item->lib_type_document  }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Numéro pièce d identité</label>
                                        <input type="text" name="numero_document_pere" value="{{ $dn->pere->document->numero_document }}" id="numero_document_pere" class="form-control form-control wide" placeholder="Numéro du document">
                                    </div>
                                </div>
                            </section>
                            <!-- Step 3 -->
                            <h6>Mère</h6>
                            <section>
                                <div class="d-flex justify-content-end align-items-center">
                                    <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".search-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche de la mère</button>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Nom(s) mère <span class="text-danger">*</span></label>
                                        <input type="text" name="nom_mere" value="{{$dn->mere->nom}}" class="form-control"  placeholder="Nom de la mère" id="nom_mere">
                                    </div>

                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Prénom(s) du mère </label>
                                        <input type="text" name="prenom_mere"  value="{{$dn->mere->prenom}}" class="form-control" placeholder="Prénom de la mère" id="prenom_mere">

                                    </div>
                                    <!-- <div class="mb-2 col-md-4">
                                        <label class="form-label">Sexe<span class="text-danger">*</span></label>
                                        <select id="sexe_mere" name="sexe_mere" class="form-control form-control wide">
                                            <option value="F">Féminin</option>
                                        </select>
                                    </div> -->
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Date de naissance de la mère<span class="text-danger">*</span></label>
                                        <input type="date" name="date_naissance_mere"  value="{{$dn->mere->date_naissance}}" max="<?php $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 12 years'));?>" min="<?php echo date('Y-m-d', strtotime($jour. ' - 100 years')); ?>" class="form-control" id="date_naissance_mere">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Lieu de naissance mère</label>
                                        <input type="text" name="lieu_naissance_mere"  value="{{$dn->mere->lieu_naissance}}" class="form-control" id="lieu_naissance_mere" placeholder="Lieu de naissance">
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Domicile de la mère<span class="text-danger">*</span></label>
                                        <input type="text" name="domicile_mere"  value="{{$dn->mere->adresse}}" class="form-control" id="domicile_mere" placeholder="Domicile mère">
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Nationalité de la mère<span class="text-danger">*</span></label>
                                        <select id="code_nationalite_mere" name="code_nationalite_mere" class="form-control form-control wide">
                                            <option value={{ $dn->mere->code_nationalite }}>{{ $dn->mere->nationalite->lib_nationalite }}</option>
                                            @foreach ($nationalites as $nationalite)
                                                <option value="{{ $nationalite->code_nationalite }}">{{ $nationalite->lib_nationalite }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="row">

                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Profession de la mère</label>
                                        <select id="profession_mere" name="profession_mere" class="form-control form-control wide">
                                            <option value="{{ $dn->mere->code_profession }}">{{ $dn->mere->profession->lib_profession }}</option>

                                            @foreach ($professions as $item)
                                                <option value="{{ $item->code_profession }}">{{ $item->lib_profession }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Téléphone mère</label>
                                        <input type="text" value="{{ $dn->mere->telephone }}" id="telephone_mere" name="telephone_mere" class="form-control form-control wide" placeholder="Téléphone mère">
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Niveau d'instruction du mère</label>
                                        <select id="niveau_instruction_mere" name="niveau_instruction_mere" class="form-control form-control wide">
                                            <option value={{ $dn->mere->niveau_instruction }}>{{  $dn->mere->niveau_instruction }}</option>
                                            @foreach ($instructions as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Type pièce d identité</label>
                                        <select id="code_type_document_mere" name="code_type_document_mere" class="form-control form-control wide">
                                            <option value={{ $dn->mere->document->code_type_document }}>{{ $dn->mere->document->code_type_document }}</option>
                                            @foreach ($typedocuments as $item)
                                                <option value="{{ $item->code_type_document }}">{{ $item->lib_type_document  }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Numéro pièce d identité</label>
                                        <input type="text" name="numero_document_mere" value="{{ $dn->mere->document->numero_document }}" id="numero_document_mere" class="form-control form-control wide" placeholder="Numéro du document">
                                    </div>
                                </div>
                            </section>
                            <!-- Step 4 -->
                            <h6>Déclarant</h6>

                            <section>
                                <div class="d-flex justify-content-end align-items-center">
                                    <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".search-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche du déclarant</button>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Nom(s) déclarant <span class="text-danger">*</span></label>
                                        <input type="text" name="nom_declarant" value="{{$dn->declarant->nom}}"  class="form-control"  placeholder="Nom du déclarant" id="nom_declarant">
                                    </div>

                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Prénom(s) du déclarant </label>
                                        <input type="text" name="prenom_declarant" value="{{$dn->declarant->prenom}}" class="form-control" placeholder="Prénom du déclarant" id="prenom_declarant">

                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Sexe du déclarant<span class="text-danger">*</span></label>
                                        <select id="sexe_declarant" name="sexe_declarant" class="form-control form-control wide">

                                            <option value="M" {{"M"==$dn->enfant->sexe ? "selected":"" }}>Masculin</option>
                                            <option value="F" {{"F"==$dn->enfant->sexe ? "selected":"" }}>Feminin</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Date de naissance du déclarant<span class="text-danger">*</span></label>
                                        <input type="date" name="date_naissance_declarant"  value="{{$dn->declarant->date_naissance}}" max="<?php $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 18 years'));?>" min="<?php echo date('Y-m-d', strtotime($jour. ' - 100 years')); ?>" class="form-control"  id="date_naissance_declarant">

                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Lieu de naissance </label>
                                        <input type="text" name="lieu_naissance_declarant" value="{{$dn->declarant->lieu_naissance}}" class="form-control" id="lieu_naissance_declarant" placeholder="Lieu de naissance">
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Filiation </label>
                                        <select id="filiation" name="filiation" class="form-control form-control wide">

                                            <option value={{ $dn->code_filiation }}>{{ $dn->filiation->lib_filiation }}</option>

                                            @foreach ($filiations as $item)
                                                <option value="{{ $item->code_filiation }}">{{ $item->lib_filiation }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Nationalité du déclarant<span class="text-danger">*</span></label>
                                        <select name="code_nationalite_declarant" id="code_nationalite_declarant" class="form-control form-control wide">
                                            <option value={{ $dn->declarant->code_nationalite }}>{{ $dn->declarant->nationalite->lib_nationalite }}</option>
                                            @foreach ($nationalites as $nationalite)
                                                <option value="{{ $nationalite->code_nationalite }}">{{ $nationalite->lib_nationalite }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Domicile du déclarant<span class="text-danger">*</span></label>
                                        <input type="text" value="{{ $dn->declarant->adresse }}" class="form-control" id="domicile_declarant" name="domicile_declarant" placeholder="Domicile du déclarant">
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Profession du déclarant</label>
                                        <select id="profession_declarant" name="profession_declarant" class="form-control form-control wide">
                                            <option value="{{ $dn->declarant->code_profession }}">{{ $dn->declarant->profession->lib_profession }}</option>
                                            @foreach ($professions as $item)
                                                <option value="{{ $item->code_profession }}">{{ $item->lib_profession }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Téléphone déclarant</label>
                                        <input type="text" name="telephone_declarant" value="{{ $dn->declarant->telephone }}" id="telephone_declarant" class="form-control form-control wide" placeholder="Téléphone déclarant">
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Email</label>
                                        <input type="email" id="email_declarant" class="form-control form-control wide" placeholder="Email déclarant">
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Type pièce d identité</label>
                                        <select name="code_type_document_declarant" id="code_type_document_declarant" class="form-control form-control wide">
                                            <option value="{{ $dn->declarant->document->code_type_document }}">{{ $dn->declarant->document->code_type_document }}</option>

                                            @foreach ($typedocuments as $item)
                                                <option value="{{ $item->code_type_document }}">{{ $item->lib_type_document  }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Numéro Pièce Identité</label>
                                        <input type="text" name="numero_document_declarant" value="{{ $dn->declarant->document->numero_document }}" id="numero_document_declarant" class="form-control form-control wide" placeholder="Numéro du document">
                                    </div>
                                </div>


                                <div class="mb-2 col-md-4">
                                    <a href="#" class="btn btn-info btn-block validate">Valider</a>
                                </div>

                            </section>

                        </form>


                    </div>


                    <div class="modal fade search-search-modal-lg" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Recherchert</h5>
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
                                            <input type="date" max="<?php echo date("Y-m-d"); ?>" class="form-control @error('date_naissance_pere') is-invalid @enderror" value="{{ old("date_naissance_pere") }}" id="date_naissance_pere">
                                            @error("date_naissance_pere")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label class="form-label">Lieu de naissance </label>
                                            <select id="code_localite" class="form-select form-control required">
                                                    <option>Choisissez</option>
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
</div>
@endsection
@section("scripts")
<script src="{{ asset('tpl/vendor/jquery-smartwizard/dist/js/jquery.smartWizard.js') }}"></script>
    <script src="{{ asset('tpl/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
    <!-- Form validate init -->
    <script src="{{ asset('tpl/js/plugins-init/jquery.validate-init.js') }}"></script>

     <!-- Daterangepicker -->
     <script src="{{ asset('tpl/js/plugins-init/bs-daterange-picker-init.js') }}"></script>
     <!-- Clockpicker init -->
     <script src="{{ asset('tpl/js/plugins-init/clock-picker-init.js') }}"></script>
     <!-- asColorPicker init -->
     <script src="{{ asset('tpl/js/plugins-init/jquery-asColorPicker.init.js') }}"></script>
     <!-- Material color picker init -->
     <script src="{{ asset('tpl/js/plugins-init/material-date-picker-init.js') }}"></script>
     <!-- Pickdate -->
     <script src="{{ asset('tpl/js/plugins-init/pickadate-init.js') }}"></script>



    <!-- This Page JS -->
    <script src="{{ asset('tpl/wizard/assets/node_modules/wizard/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('tpl/wizard/assets/node_modules/wizard/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('tpl/wizard/assets/node_modules/sweetalert2/dist/sweetalert2.min.js') }}"></script>

 {{--    contrôle de date --}}
        <script>








        </script>






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
                Swal.fire("Déclaration Enrégistrée !", "Déclarion est en cours de traitement, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.");

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
                return currentIndex > newIndex || !(3 === newIndex && Number($("#age-2").val()) < 18) && (currentIndex < newIndex && (form.find(".body:eq(" + newIndex + ") label.error").remove(), form.find(".body:eq(" + newIndex + ") .error").removeClass("error")), form.validate().settings.ignore = ":disabled,:hidden", form.valid())
            },
            onFinishing: function (event, currentIndex) {
                return form.validate().settings.ignore = ":disabled", form.valid()
            },
            onFinished: function (event, currentIndex) {
                Swal.fire("Déclaration Enrégistrée !", "Déclarion est en cours de traiatement, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.");
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

    <!-- Datatable -->

    <script>
        $(function(){
            $("a.validate").on("click",function()
            {
                // informations du père
                var nom_pere = $("#nom_pere");
                var prenom_pere = $("#prenom_pere");
                var date_naissance_pere = $("#date_naissance_pere");
                var lieu_naissance_pere = $("#lieu_naissance_pere");
                var domicile_pere = $("#domicile_pere");
                var telephone_pere = $("#telephone_pere");
                var profession_pere = $("#profession_pere");
                var code_nationalite_pere = $("#code_nationalite_pere");
                var niveau_instruction_pere = $("#niveau_instruction_pere");
                var code_type_document_pere = $("#code_type_document_pere");
                var numero_document_pere = $("#numero_document_pere");

                //information mere
                var nom_mere = $("#nom_mere");
                var prenom_mere = $("#prenom_mere");
                var date_naissance_mere = $("#date_naissance_mere");
                var lieu_naissance_mere = $("#lieu_naissance_mere");
                var domicile_mere = $("#domicile_mere");
                var telephone_mere = $("#telephone_mere");
                var profession_mere = $("#profession_mere");
                var code_nationalite_mere = $("#code_nationalite_mere");
                var niveau_instruction_mere = $("#niveau_instruction_mere");
                var code_type_document_mere = $("#code_type_document_mere");
                var numero_document_mere = $("#numero_document_mere");

                //déclarant
                var nom_declarant = $("#nom_declarant");
                var prenom_declarant = $("#prenom_declarant");
                var date_naissance_declarant = $("#date_naissance_declarant");
                var lieu_naissance_declarant = $("#lieu_naissance_declarant");
                var domicile_declarant = $("#domicile_declarant");
                var telephone_declarant = $("#telephone_declarant");
                var profession_declarant = $("#profession_declarant");
                var code_nationalite_declarant = $("#code_nationalite_declarant");
                var filiation = $("#filiation");
                var sexe_declarant = $("#sexe_declarant");
                var email = $("#email");
                var code_type_document_declarant = $("#code_type_document_declarant");
                var numero_document_declarant = $("#numero_document_declarant");

                // enfant
                var nom_enfant = $("#nom_enfant");
                var prenom_enfant = $("#prenom_enfant");
                var date_naissance_enfant = $("#date_naissance_enfant");
                var lieu_naissance_enfant = $("#lieu_naissance_enfant");
                var code_situation_matrimoniale = $("#code_situation_matrimoniale");
                var lieu_survenance = $("#code_lieu_survenance");

                //var code_nationalite_enfant = $("#code_nationalite_enfant");
                var sexe_enfant = $("#sexe_enfant");
                var heure_naissance_enfant = $("#heure_naissance_enfant");
                var nombre_enfants = $("#nombre_enfants");

                //champs obligatoires
                var champs = [nom_pere,
                             date_naissance_pere,
                             code_nationalite_pere,
                             domicile_pere,
                             telephone_pere,
                             profession_pere,
                              nom_mere,
                              date_naissance_mere,
                              profession_mere,
                              code_nationalite_mere,
                              telephone_mere,
                              domicile_mere,
                              nom_declarant,
                              filiation,
                              code_nationalite_declarant,
                              telephone_declarant,
                              date_naissance_declarant,
                              domicile_declarant,
                              sexe_declarant,
                              nom_enfant,
                              sexe_enfant,
                              date_naissance_enfant,
                              lieu_naissance_enfant,
                              lieu_survenance,
                              code_situation_matrimoniale,
                              heure_naissance_enfant,
                              code_type_document_declarant,
                              numero_document_declarant];

                var champsVides = [];

                for(var i = 0; i < champs.length; i++)
                {
                    if(champs[i].val() == "" || champs[i].val() == null)
                    {
                        champsVides.push(champs[i]);
                    }

                }

                //vérification des champs vides
                for(var i = 0; i < champsVides.length; i++)
                {
                    champsVides[i].addClass("is-invalid");
                }

                //si un champ obligatoire est null ou vide alors il ne passe pas à l'étape suivante
                if(champsVides.length > 0)
                {
                    return false;
                }

               // alert(champs);

                // alert(champsVides.length);

                var data =
                {
                    // données du père
                    nom_pere:nom_pere.val(),
                    prenom_pere:prenom_pere.val(),
                    date_naissance_pere:date_naissance_pere.val(),
                    lieu_naissance_pere:lieu_naissance_pere.val(),
                    domicile_pere:domicile_pere.val(),
                    profession_pere:profession_pere.val(),
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
                    profession_mere:profession_mere.val(),
                    code_nationalite_mere:code_nationalite_mere.val(),
                    niveau_instruction_mere:niveau_instruction_mere.val(),
                    telephone_mere:telephone_mere.val(),
                    code_type_document_mere:code_type_document_mere.val(),
                    numero_document_mere:numero_document_mere.val(),
                    // données du déclarant
                    nom_declarant:nom_declarant.val(),
                    prenom_declarant:prenom_declarant.val(),
                    date_naissance_declarant:date_naissance_declarant.val(),
                    lieu_naissance_declarant:lieu_naissance_declarant.val(),
                    domicile_declarant:domicile_declarant.val(),
                    profession_declarant:profession_declarant.val(),
                    code_nationalite_declarant:code_nationalite_declarant.val(),
                    filiation:filiation.val(),
                    telephone_declarant:telephone_declarant.val(),
                    email_declarant:email.val(),
                    sexe_declarant:sexe_declarant.val(),
                    code_type_document_declarant:code_type_document_declarant.val(),
                    numero_document_declarant:numero_document_declarant.val(),
                    // données de l'enfant
                    nom_enfant:nom_enfant.val(),
                    prenom_enfant:prenom_enfant.val(),
                    date_naissance_enfant:date_naissance_enfant.val(),
                    lieu_naissance_enfant:lieu_naissance_enfant.val(),
                    code_situation_matrimoniale:code_situation_matrimoniale.val(),
                    //code_nationalite_enfant:code_nationalite_enfant.val(),
                    sexe_enfant:sexe_enfant.val(),
                    heure_naissance_enfant:heure_naissance_enfant.val(),
                    lieu_survenance:lieu_survenance.val(),
                    nombre_enfant:nombre_enfants.val(),
                    _method:'PUT',
                    _token: '{{ csrf_token() }}'
                };


                //traitement ajax
                var code = "{{$dn->code_declaration_naissance}}";
                var route = "{{route('declarationNaissance.update',':id')}}";
                route = route.replace(':id',code);
                var btnValidatePub = this;
                sifecBtnLoading(btnValidatePub, 'Enregistrement...');
                $.post(route, data, function(response) {
                    if (response && response.declaration) {
                        console.log(response.declaration);
                    }
                }).fail(function(xhr) {
                    var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Erreur lors de l\'enregistrement.';
                    flashAlert('Erreur', 'error', msg);
                }).always(function() {
                    sifecBtnReset(btnValidatePub);
                });

                //return false;
            });
        });
      </script>
@endsection
