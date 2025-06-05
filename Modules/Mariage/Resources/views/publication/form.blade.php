<form name="contactUsForm" id="contactUsForm"  method="post" action="javascript:void(0)" class="validation-wizard wizard-circle">
    <!-- Step 1 -->
    <h6>Enfant</h6>
    <section>
        <div class="row">

            <div class="mb-2 col-md-4">
                <label class="form-label">Nom(s) enfant <span class="text-danger">*</span></label>
                <input type="text" name="nom_enfant"  class="form-control required  @error('nom_enfant') is-invalid @enderror"  placeholder="Nom enfant" id="nom_enfant">
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Prénom(s) enfant</label>
                <input type="text" class="form-control" placeholder="Prénom enfant" id="prenom_enfant">
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Sexe <span class="text-danger">*</span></label>
                <select id="sexe_enfant" name="sexe_enfant" class="form-control required  @error('sexe_enfant') is-invalid @enderror">
                    <option value="" disabled selected>Selectionner</option>
                    <option value="M">Masculin</option>
                    <option value="F">Féminin</option>
                </select>
            </div>

        </div>
        <div class="row">
            <div class="mb-2 col-md-4">
                <label class="form-label">Date de naissance <span class="text-danger">*</span></label>
                <input type="date" name="date_naissance_enfant" max="<?php  echo date("Y-m-d"); ?>" required min="<?php  $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 3 month'));?>" class="form-control required  @error('date_naissance_enfant') is-invalid @enderror " id="date_naissance_enfant">
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Lieu de naissance <span class="text-danger">*</span></label>
                <input type="text" name="lieu_naissance_enfant" class="form-control required  @error('lieu_naissance_enfant') is-invalid @enderror " required id="lieu_naissance_enfant">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Heure de naissance <span class="text-danger">*</span></label>
                <input type="time" name="heure_naissance_enfant" class="form-control required  @error('heure_naissance_enfant') is-invalid @enderror"  id="heure_naissance_enfant">
            </div>
        </div>
        <div class="row">

            <div class="mb-2 col-md-4">
                <label class="form-label">Situation matrimoniale des parents<span class="text-danger">*</span></label>
                <select id="code_situation_matrimoniale" name="code_situation_matrimoniale" class="form-control required  @error('code_situation_matrimoniale') is-invalid @enderror ">
                    <option disabled selected>Choisissez</option>
                    @foreach ($situationMatrimoniales as $item)
                        <option value="{{ $item->code_situation_matrimoniale }}">{{ $item->lib_situation_matrimoniale }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Nombre d'enfants (y compris le sujet)<span class="text-danger">*</span></label>
                <input type="number" name="nombre_enfants" min="1" value="1" class="form-control required  @error('nombre_enfants') is-invalid @enderror " placeholder="0" id="nombre_enfants">
            </div>
            <div class="mb-2 col-md-4" style="visibility: hidden">
                <label class="form-label" >Lieu de survenance <span class="text-danger">*</span></label>
                <select id="code_lieu_survenance" class="form-control form-control wide">
                       <!-- <option disabled selected>Choisissez</option>-->
                    @foreach ($lieuSurvenances as $item)
                        <option value="{{ $item->code_lieu_survenance }}">{{ $item->lib_lieu_survenance }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </section>
    <!-- Step 2 -->
    <h6>Père</h6>
    <section>
        <div class="d-flex justify-content-end align-items-center">
            <button type="button" id="clear_pere" class="btn btn-danger  text-white" ></i> Vider </button>
            <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".search-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche du père</button>
        </div>
        <hr>
        <div class="row">
            <div class="mb-2 col-md-4">
                <input type="hidden" id="code_pere">
                <label class="form-label">Nom(s) père <span class="text-danger">*</span></label>
                <input type="text" class="form-control required  @error('nom_pere') is-invalid @enderror " name="nom_pere"  placeholder="Nom du père" id="nom_pere">
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Prénom(s) du père </label>
                <input type="text" class="form-control" placeholder="Prénom du père" id="prenom_pere">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Date de naissance du père<span class="text-danger">*</span></label>
                <input type="date" max="<?php echo date('Y-m-d', strtotime($jour. ' - 14 years')); ?>" min="<?php echo date('Y-m-d', strtotime($jour. ' - 100 years')); ?>" onchange="compare()"
                name="date_naissance_pere" class="form-control required  @error('date_naissance_pere') is-invalid @enderror " id="date_naissance_pere">
                <input type="checkbox" id="type_date_naissance_pere" value="ESTIME" name="type_date_naissance_pere"><label for="type_date_naissance_pere">date estimée</label>
            </div>
        </div>
        <div class="row">
            <div class="mb-2 col-md-4">
                <label class="form-label">Lieu de naissance père</label>
                <input type="text" name="lieu_naissance_pere" class="form-control required  @error('lieu_naissance_pere') is-invalid @enderror " id="lieu_naissance_pere" placeholder="Lieu de naissance">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Profession du père<span class="text-danger">*</span></label>
                <select id="profession_pere" class="form-control form-control wide">
                        <option disabled selected>Choisissez</option>
                    @foreach ($professions as $item)
                        <option value="{{ $item->code_profession }}">{{ $item->lib_profession }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Nationalité du père<span class="text-danger">*</span></label>
                <select id="code_nationalite_pere" name="code_nationalite_pere" class="form-control required  @error('code_nationalite_pere') is-invalid @enderror ">
                        <option disabled selected>Choisissez</option>
                    @foreach ($nationalites as $nationalite)
                        <option value="{{ $nationalite->code_nationalite }}">{{ $nationalite->lib_nationalite }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="mb-2 col-md-2">
                <label class="form-label">Adr. N°<span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_numero_pere" placeholder="numero parcelle">

            </div>
            <div class="mb-2 col-md-2">
                <label class="form-label">Rue <span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_rue_pere" placeholder="rue ou avenue">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Quartier<span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_quartier_pere" placeholder="quartier">

            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Arron.<span class="text-danger"></span></label>
                <select name="domicile_arrondissement_pere" id="domicile_arrondissement_pere" class="form-control ">
                    <option>Choisissez</option>
                    @forelse ($arrondissement as $code)
                        <option value="{{ $code->code_arrondissement }}">{{ $code->lib_arrondissement }}</option>
                    @empty

                    @endforelse

                </select>
            </div>

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
                        <input type="number" min="0" minlength="9" maxlength="10" id="telephone_pere" name="telephone_pere" class="form-control @error('telephone_pere') is-invalid @enderror " placeholder="Téléphone père">
                    </div>
                </div>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Niveau d'instruction du père</label>
                <select id="niveau_instruction_pere" class="form-control form-control wide">
                        <option disabled selected>Choisissez</option>
                    @foreach ($instructions as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Email</label>
                <input type="email" id="email_pere" class="form-control" name="email_pere" placeholder="Email père">
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Type pièce d'identité</label>
                <select id="code_type_document_pere" class="form-control form-control wide">
                        <option disabled selected>Choisissez</option>
                    @foreach ($typedocuments as $item)
                        <option value="{{ $item->code_type_document }}">{{ $item->lib_type_document  }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Numéro pièce d'identité</label>
                <input type="text" id="numero_document_pere" class="form-control form-control wide" placeholder="Numéro du document">
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Statut <span class="text-danger">*</span></label>
                <select id="statut_personne_pere" name="statut_personne_pere" class="form-control">
                    <option selected value="VIVANT">Vivant</option>
                    <option value="DECEDE">Décédé</option>
                </select>
            </div>
        </div>
    </section>
    <!-- Step 3 -->
    <h6>Mère</h6>
    <section>
        <div class="d-flex justify-content-end align-items-center">
            <button type="button" id="clear_mere" class="btn btn-danger  text-white" ></i> Vider </button>
            <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".mere-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche de la mère</button>
        </div>
        <hr>
        <div class="row">
            <div class="mb-2 col-md-4">
                <input type="hidden" id="code_mere">
                <label class="form-label">Nom(s) Mère <span class="text-danger">*</span></label>
                <input type="text" class="form-control required" name="nom_mere"  placeholder="Nom Mère" id="nom_mere">
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Prénom(s) Mère </label>
                <input type="text" class="form-control" placeholder="Prénom du Mère" id="prenom_mere">

            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Date de naissance Mère<span class="text-danger">*</span></label>
                <input type="date" max="<?php echo date('Y-m-d', strtotime($jour. ' - 14 years')); ?>" min="<?php echo date('Y-m-d', strtotime($jour. ' - 100 years')); ?>" onchange="compare()"
                name="date_naissance_mere" class="form-control required  @error('date_naissance_mere') is-invalid @enderror " id="date_naissance_mere">
                <input type="checkbox" id="type_date_naissance_mere" value="ESTIME" name="type_date_naissance_mere"><label for="type_date_naissance_mere">date estimée</label>
            </div>
        </div>
        <div class="row">
            <div class="mb-2 col-md-4">
                <label class="form-label">Lieu de naissance Mère</label>
                <input type="text" name="lieu_naissance_mere" class="form-control required  @error('lieu_naissance_mere') is-invalid @enderror " id="lieu_naissance_mere" placeholder="Lieu de naissance">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Profession Mère<span class="text-danger">*</span></label>
                <select id="profession_mere" class="form-control form-control wide">
                        <option disabled selected>Choisissez</option>
                    @foreach ($professions as $item)
                        <option value="{{ $item->code_profession }}">{{ $item->lib_profession }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Nationalité Mère<span class="text-danger">*</span></label>
                <select id="code_nationalite_mere" name="code_nationalite_mere" class="form-control required  @error('code_nationalite_mere') is-invalid @enderror ">
                        <option disabled selected>Choisissez</option>
                    @foreach ($nationalites as $nationalite)
                        <option value="{{ $nationalite->code_nationalite }}">{{ $nationalite->lib_nationalite }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="mb-2 col-md-2">
                <label class="form-label">Adr. N°<span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_numero_mere" placeholder="numero parcelle">

            </div>
            <div class="mb-2 col-md-2">
                <label class="form-label">Rue <span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_rue_mere" placeholder="rue ou avenue">

            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Quartier<span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_quartier_mere" placeholder="quartier">

            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Arron.<span class="text-danger"></span></label>
                <select name="domicile_arrondissement_mere" id="domicile_arrondissement_mere" class="form-control ">
                    <option>Choisissez</option>
                    @forelse ($arrondissement as $code)
                        <option value="{{ $code->code_arrondissement }}">{{ $code->lib_arrondissement }}</option>
                    @empty

                    @endforelse

                </select>
            </div>

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
                        <input type="number" min="0" minlength="9" maxlength="10" id="telephone_mere" name="telephone_mere" class="form-control @error('telephone_mere') is-invalid @enderror " placeholder="Téléphone Mère">
                    </div>
                </div>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Niveau d'instruction de la Mère</label>
                <select id="niveau_instruction_mere" class="form-control form-control wide">
                        <option disabled selected>Choisissez</option>
                    @foreach ($instructions as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Email</label>
                <input type="email" id="email_mere" class="form-control" name="email_mere" placeholder="Email mère">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Type pièce d'identité</label>
                <select id="code_type_document_mere" class="form-control form-control wide">
                        <option disabled selected>Choisissez</option>
                    @foreach ($typedocuments as $item)
                        <option value="{{ $item->code_type_document }}">{{ $item->lib_type_document  }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Numéro pièce d'identité</label>
                <input type="text" id="numero_document_mere" class="form-control form-control wide" placeholder="Numéro du document">
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Statut <span class="text-danger">*</span></label>
                <select id="statut_personne_mere" name="statut_personne_mere" class="form-control required ">
                    <option selected value="VIVANT">Vivante</option>
                    <option value="DECEDE">Décédée</option>
                </select>
            </div>
        </div>
    </section>
    <!-- Step 4 -->
    <h6>Déclarant</h6>
    <section>
        <div class="d-flex justify-content-end align-items-center" id="search_declarant">
            <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".declarant-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche du déclarant</button>
        </div>
        <hr>
        <div class="row">
            <div class="mb-2 col-sm-4" id="hide_pere">
                <label for="dewey">Père</label>
                <input type="radio" id="peredeclarant" name="autredeclarant" checked value="pere">
            </div>


            <div class="mb-2 col-sm-4" id="hide_mere">
                <label for="dewey">Mère</label>
                <input type="radio" id="meredeclarant" name="autredeclarant" value="mere">
            </div>

            <div class="mb-2 col-sm-4">
                <label for="dewey">Autre</label>
                <input type="radio" id="autredeclarant" name="autredeclarant"  value="autre">
            </div>



            <div class="mb-2 col-md-4">
                <input type="hidden" id="code_declarant">
                <label class="form-label">Nom(s) déclarant <span class="text-danger">*</span></label>
                <input type="text" class="form-control required"  placeholder="Nom du déclarant" id="nom_declarant" name="nom_declarant">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Prénom(s) du déclarant </label>
                <input type="text" class="form-control" placeholder="Prénom du déclarant" id="prenom_declarant" name="prenom_declarant">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Sexe du déclarant<span class="text-danger">*</span></label>
                <select id="sexe_declarant" name="sexe_declarant" class="form-control required  @error('sexe_declarant') is-invalid @enderror ">
                    <option value="">Selectionner</option>
                    <option value="M">Masculin</option>
                    <option value="F">Féminin</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="mb-2 col-md-4">
                <label class="form-label">Date de naissance du déclarant<span class="text-danger">*</span></label>
                <input type="date" name="date_naissance_declarant" max="<?php $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 18 years'));?>" min="<?php echo date('Y-m-d', strtotime($jour. ' - 100 years')); ?>"
                class="form-control required  @error('date_naissance_declarant') is-invalid @enderror " id="date_naissance_declarant">
                <input type="checkbox" id="type_date_naissance_declarant" value="ESTIME" name="type_date_naissance_declarant"><label for="type_date_naissance_declarant">date estimée</label>

            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Lieu de naissance </label>
                <input type="text" class="form-control" name="lieu_naissance_declarant" id="lieu_naissance_declarant" placeholder="Lieu de naissance">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Filiation <span class="text-danger">*</span></label>
                <select id="filiation" name="filiation" class="form-control required  @error('filiation') is-invalid @enderror ">
                        <option>Choisissez</option>
                        @foreach ($filiations as $item)

                        {{--  @if ($item->code_filiation !="FIL_0001" && $item->code_filiation !="FIL_0002")  --}}
                            <option class="{{$item->code_filiation }}" value="{{$item->code_filiation }}">{{ $item->lib_filiation }}</option>
                        {{--  @endif  --}}
                        @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="mb-2 col-md-4">
                <label class="form-label">Nationalité du déclarant<span class="text-danger">*</span></label>
                <select id="code_nationalite_declarant" name="code_nationalite_declarant" class="form-control required  @error('code_nationalite_declarant') is-invalid @enderror ">
                        <option selected disabled>Choisissez</option>
                    @foreach ($nationalites as $nationalite)
                        <option value="{{ $nationalite->code_nationalite }}">{{ $nationalite->lib_nationalite }}</option>
                    @endforeach

                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Profession du déclarant</label>
                <select id="profession_declarant" name="profession_declarant" class="form-control required  @error('profession_declarant') is-invalid @enderror ">
                        <option selected disabled>Choisissez</option>
                    @foreach ($professions as $item)
                        <option value="{{ $item->code_profession }}">{{ $item->lib_profession }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Niveau d'instruction du déclarant</label>
                <select id="niveau_instruction_declarant" name="niveau_instruction_declarant" class="form-control form-control wide">
                        <option disabled selected>Choisissez</option>
                    @foreach ($instructions as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-2">
                <label class="form-label">Adr. N°<span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_numero_declarant" placeholder="numero parcelle">

            </div>
            <div class="mb-2 col-md-2">
                <label class="form-label">Rue <span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_rue_declarant" placeholder="rue ou avenue">

            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Quartier<span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_quartier_declarant" placeholder="quartier">

            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Arron.<span class="text-danger"></span></label>
                <select name="domicile_arrondissement_declarant" id="domicile_arrondissement_declarant" class="form-control required">
                    <option>Choisissez</option>
                    @forelse ($arrondissement as $code)
                        <option value="{{ $code->code_arrondissement }}">{{ $code->lib_arrondissement }}</option>
                    @empty

                    @endforelse
                </select>

            </div>

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
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Email</label>
                <input type="email" id="email_declarant" class="form-control" name="email_declarant" placeholder="Email déclarant">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Type pièce d'identité</label>
                <select id="code_type_document_declarant" name="code_type_document_declarant" class="form-control required ">
                        <option>Choisissez</option>
                    @foreach ($typedocuments as $item)
                        <option value="{{ $item->code_type_document }}">{{ $item->lib_type_document  }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Numéro pièce d'identité</label>
                <input type="text" id="numero_document_declarant" name="numero_document_declarant" class="form-control required" placeholder="Numéro du document">
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Statut <span class="text-danger">*</span></label>
                <select id="statut_personne_declarant" name="statut_personne_declarant" required class="form-control required ">
                    <option value="VIVANT">Vivant(e)</option>
                </select>
            </div>
        </div>
    </section>

</form>
