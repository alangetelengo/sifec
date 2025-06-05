@extends("layout.app")
@section("titre")
    {{ $type_declaration }}
@endsection

@section("corps")
    <!-- row -->
    <div class="row" id="validation">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $title }}</h4>
                </div>
                <div class="card wizard-content">
                    <div class="card-body">
                        <form name="contactUsForm" id="contactUsForm"  method="post" action="javascript:void(0)" class="validation-wizard wizard-circle" enctype="multipart/form-data">
                            <!-- Step 1 -->
                            <h6>Enfant</h6>
                            <div class="d-none">
                                <input type="text" value="{{ $type_declaration }}" id="type_declaration">
                            </div>
                            <section>
                                    <div class="ligne">
                                    <h4>INFORMATIONS SUR L'IDENTITE</h4>
                                </div>
                                <div class="row">
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Nom(s) enfant <span class="text-danger"></span></label>
                                        <input type="text" name="nom_enfant"  class="form-control"  placeholder="Nom enfant" id="nom_enfant" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()" value="{{ $dummy }}">
                                    </div>

                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Prénom(s) enfant</label>
                                        <input type="text" class="form-control" placeholder="Prénom enfant" id="prenom_enfant" onkeyup="verif_lettre(this);" style="text-transform: capitalize" value="{{ $dummy }}">
                                    </div>

                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Sexe <span class="text-danger">*</span></label>
                                        <select id="sexe_enfant" name="sexe_enfant" class="form-control  @error('sexe_enfant') is-invalid @enderror">
                                            <option value="" disabled selected>Selectionner</option>
                                            <option value="M">Masculin</option>
                                            <option value="F">Féminin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="ligne">
                                        <h4>INFORMATIONS SUR LA NAISSANCE</h4>
                                    </div>
                                <div class="row">
                                    <div class="mb-1 col-md-4">
                                        <label class="form-label">Date de naissance <span class="text-danger">*</span></label>
                                        <input type="date" value="{{ $dateNaissance ?? "" }}" name="date_naissance_enfant" max="<?php  echo date("Y-m-d"); ?>" required min="<?php  $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 100 year'));?>" class="form-control  @error('date_naissance_enfant') is-invalid @enderror " id="date_naissance_enfant">
                                    </div>
                                    <div class="mb-1 col-md-2">
                                        <label class="form-label">Heure de naissance <span class="text-danger">*</span></label>
                                        <input type="time" name="heure_naissance_enfant" class="form-control  @error('heure_naissance_enfant') is-invalid @enderror"  id="heure_naissance_enfant">
                                    </div>

                                    <div class="mb-1 col-md-2">
                                        <label class="form-label">Poids à la naissance (kg)<span class="text-danger">*</span></label>
                                        <input type="text" id="poids_enfant" name="poids_enfant" class="form-control" onkeyup="verif_nombre(this);">
                                    </div>
                                    <div class="mb-1 col-md-2">
                                        <label class="form-label">Taille à la naissance (cm)<span class="text-danger">*</span></label>
                                        <input type="text" id="taille_enfant" name="taille_enfant" class="form-control" onkeyup="verif_nombre(this);">
                                    </div>
                                    <div class="mb-1 col-md-2">
                                        <label class="form-label">Périmètre crânien (cm) <span class="text-danger">*</span></label>
                                        <input type="text" id="pc_enfant" name="pc_enfant" class="form-control" onkeyup="verif_nombre(this);">
                                    </div>

                                    <div class="mb-1 col-md-2 d-none">
                                        <label class="form-label">Lieu de naissance <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="lieu_naissance_enfant" id="lieu_naissance_enfant" value="{{ Auth::user()->affectationActive()->institution->lalocalite->localiteParent->lib_localite }}" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()">
                                        <select id="code_localite_enfant" class="form-control">
                                            <option disabled selected>Choisissez</option>
                                            @foreach ($localites as $localite)
                                                <option value="{{ $localite->code_localite }}" {{ Auth::user()->affectationActive()->institution->lalocalite->localiteParent->code_localite == $localite->code_localite ? "selected" : "" }}>{{ $localite->lib_localite }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-4 d-none">
                                        @php
                                            $UserInstitution = Auth::user()->affectationactive()->institution;
                                        @endphp
                                        <label class="form-label">Lieu de survenance <span class="text-danger"></span> </label>
                                        <select  id="code_lieu_survenance" class="form-select form-control">
                                            @if($UserInstitution->TypeInstitution->typeCategorieInstitution->code_type_categorie_ins == "TCINS_0003")
                                                @foreach ($lieuSurvenances as $item)
                                                    @if($item->code_lieu_survenance == "LSURV_0001")
                                                        <option value="{{ $item->code_lieu_survenance }}">{{ $item->lib_lieu_survenance }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="ligne">
                                    <h4>AUTRES INFORMATIONS</h4>
                                </div>
                                <div class="row">
                                    <div class="mb-2 col-md-3 d-none">
                                        <label class="form-label">Situation matrimoniale des parents<span class="text-danger"></span></label>
                                        <select id="code_situation_matrimoniale" name="code_situation_matrimoniale" class="form-control  @error('code_situation_matrimoniale') is-invalid @enderror ">
                                            <option value="SMAT_0008" selected>Choisissez</option>
                                            @foreach ($situationMatrimoniales as $item)
                                                <option value="{{ $item->code_situation_matrimoniale }}">{{ $item->lib_situation_matrimoniale }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-3">
                                        <label class="form-label">Nombre d'enfants (y compris le sujet)<span class="text-danger">*</span></label>
                                        <input type="number" name="nombre_enfants" min="1" value="1" class="form-control  @error('nombre_enfants') is-invalid @enderror " placeholder="0" id="nombre_enfants">
                                    </div>
                                    <div class="mb-2 col-md-3">
                                        <label class="form-label">Date d'émission</label>
                                        <input type="date" id="date_heure_declaration" name="date_heure_declaration" class="form-control">
                                    </div>
                                    <div class="mb-2 col-md-3">
                                        <label class="form-label">Etat de l'enfant <span class="text-danger">*</span></label>
                                        <select id="statut_personne_pere" name="statut_personne_pere" class="form-control">
                                            <option selected value="VIVANT">Né vivant</option>
                                            <option value="DECEDE">Mort in utero</option>
                                        </select>
                                    </div>
                                </div>
                            </section>
                            <h6>Père</h6>
                            <section>
                                <div class="d-flex justify-content-end align-items-center">
                                    <button type="button" id="clear_pere" class="btn btn-danger  text-white" ></i> Vider </button>
                                    <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".search-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche du père</button>
                                </div>
                                <hr>
                                <div class="ligne">
                                    <h4>INFORMATIONS PERSONNELLES</h4>
                                </div>
                                <div class="row">
                                    <div class="mb-2 col-md-4">
                                        <input type="hidden" id="code_pere">
                                        <span class="error" id="errordatenais"></span>
                                        <label class="form-label">Nom(s) père <span class="text-danger"></span></label>
                                        <input type="text" class="form-control" name="nom_pere"  placeholder="Nom du père" value="{{ $dummy }}" id="nom_pere" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()">
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Prénom(s) du père </label>
                                        <input type="text" class="form-control" placeholder="Prénom du père" value="{{ $dummy }}" id="prenom_pere" onkeyup="verif_lettre(this);" style="text-transform: capitalize">
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Date de naissance du père<span class="text-danger"></span></label>
                                        <input type="date" max="<?php echo date('Y-m-d', strtotime($jour. ' - 14 years')); ?>" min="<?php echo date('Y-m-d', strtotime($jour. ' - 100 years')); ?>" onchange="compare()"
                                        name="date_naissance_pere" class="form-control" id="date_naissance_pere">
                                        <input type="checkbox" id="type_date_naissance_pere" value="ESTIME" name="type_date_naissance_pere"><label for="type_date_naissance_pere">date estimée</label>
                                    </div>

                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Lieu de naissance du père</label>
                                        <input type="text" name="lieu_naissance_pere" class="form-control d-none" id="lieu_naissance_pere" placeholder="Lieu de naissance">
                                        <select id="code_localite_pere" class="form-control">
                                            <option selected value="LOC_4250">Choisissez</option>
                                            @foreach ($localites as $localite)
                                                <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                        <div class="mb-2 col-md-4">
                                        <label class="form-label">Nationalité du père<span class="text-danger">*</span></label>
                                        <select id="code_nationalite_pere" name="code_nationalite_pere" class="form-control">
                                            @foreach ($nationalites as $nationalite)
                                                <option value="{{ $nationalite->code_nationalite }}">{{ $nationalite->lib_nationalite }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Profession du père<span class="text-danger">*</span></label>
                                        <select id="profession_pere" class="form-control form-control wide">
                                                <option selected value="PROF_0010">Choisissez</option>
                                            @foreach ($professions as $item)
                                                <option value="{{ $item->code_profession }}">{{ $item->lib_profession }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Niveau d'instruction du père</label>
                                        <select id="niveau_instruction_pere" class="form-control form-control wide">
                                                <option selected value="NON DECLARE">Choisissez</option>
                                            @foreach ($instructions as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Type pièce d'identité</label>
                                        <select id="code_type_document_pere" class="form-control form-control wide">
                                                <option selected value="TDOC_0018">Choisissez</option>
                                            @foreach ($typedocuments as $item)
                                                <option value="{{ $item->code_type_document }}">{{ $item->lib_type_document  }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Numéro pièce d'identité</label>
                                        <input type="text" id="numero_document_pere" class="form-control form-control wide" placeholder="Numéro du document" value="{{ $dummy }}" onkeyup="this.value=this.value.toUpperCase()">
                                    </div>

                                    <div class="ligne">
                                        <h4>ADRESSE</h4>
                                    </div>
                                    <div class="row">
                                        <div class="mb-2 col-md-3">
                                            <label class="form-label">Pays<span class="text-danger"></span></label>
                                            <select id="domicile_pays_pere" class="form-control">
                                                {{-- <option value="">Choisissez</option> --}}
                                                @foreach ($countries as $countrie)
                                                    <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2 col-md-3 domicile_ville_pere d-none">
                                            <label class="form-label">Commune/District<span class="text-danger"></span></label>
                                            <select class="form-control" id="domicile_ville_pere">
                                                <option value="">Choisir</option>
                                                @foreach ($localites as $localite)
                                                    <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2 col-md-3 autredomicile_ville_pere d-none">
                                            <label class="form-label">Ville<span class="text-danger"></span></label>
                                            <input type="text" id="autredomicile_ville_pere" class="form-control form-control wide" placeholder="Libellé de la ville" onkeyup="this.value=this.value.toUpperCase()">
                                        </div>

                                        <div class="mb-2 col-md-3 domicile_arrondissement_pere d-none">
                                            <label class="form-label">Arr/Com. Urb<span class="text-danger"></span></label>
                                            <select class="form-control" id="domicile_arrondissement_pere">
                                                <option value="">Choisir</option>
                                                @foreach ($arrondissement as $localite)
                                                    <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-2 col-md-3 domicile_quartier_pere d-none">
                                            <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                                            <select class="form-control" id="domicile_quartier_pere">
                                                <option value="">Choisir</option>
                                                @foreach ($quartierVillages as $localite)
                                                    <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2 col-md-3">
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
                                        <div class="mb-2 col-md-3">
                                            <label class="form-label">N° voie<span class="text-danger"></span></label>
                                            <input type="text" class="form-control" id="domicile_numero_pere" placeholder="N° voie">
                                        </div>
                                        <div class="mb-2 col-md-3">
                                            <label class="form-label">Nom voie<span class="text-danger"></span></label>
                                            <input type="text" class="form-control" id="domicile_nomvoie_pere" placeholder="Nom voie" style="text-transform: capitalize">
                                        </div>

                                        <div class="ligne">
                                            <h4>CONTACTS</h4>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Indicatif<span class="text-danger">*</span></label>
                                                <select name="code_pays_pere" id="code_pays_pere" class="form-control">
                                                    @forelse ($countries as $code)
                                                        <option value="{{ $code->dial_code }}">({{ $code->dial_code }}) {{ $code->name }}</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Téléphone père</label>
                                                <input type="number" min="0" minlength="9" maxlength="15" id="telephone_pere" name="telephone_pere" class="form-control @error('telephone_pere') is-invalid @enderror " placeholder="Téléphone père">
                                            </div>

                                            <div class="mb-2 col-md-4">
                                                <label class="form-label">Email</label>
                                                <input type="email" id="email_pere" class="form-control" name="email_pere" placeholder="Email père">
                                            </div>

                                            <div class="ligne">
                                                <h4>AUTRES INFORMATIONS</h4>
                                            </div>

                                            <div class="mb-2 col-md-4">
                                                <label class="form-label">Statut <span class="text-danger">*</span></label>
                                                <select id="statut_personne_pere" name="statut_personne_pere" class="form-control">
                                                    <option selected value="VIVANT">Vivant</option>
                                                    <option value="DECEDE">Décédé</option>
                                                </select>
                                            </div>
                                        </div>
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
                                <div class="ligne">
                                        <h4>INFORMATIONS PERSONNELLES</h4>
                                    </div>
                                <div class="row">
                                    <div class="mb-2 col-md-4">
                                        <input type="hidden" id="code_mere">
                                        <label class="form-label">Nom(s) Mère <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control required" name="nom_mere"  placeholder="Nom Mère" id="nom_mere" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()">
                                    </div>

                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Prénom(s) Mère </label>
                                        <input type="text" class="form-control" placeholder="Prénom du Mère" id="prenom_mere" onkeyup="verif_lettre(this);" style="text-transform: capitalize">

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
                                        <input type="text" name="lieu_naissance_mere" class="form-control d-none" id="lieu_naissance_mere" placeholder="Lieu de naissance">
                                        <select id="code_localite_mere" class="form-control required">
                                            <option disabled selected>Choisissez</option>
                                            @foreach ($localites as $localite)
                                                <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Nationalité Mère<span class="text-danger">*</span></label>
                                        <select id="code_nationalite_mere" name="code_nationalite_mere" class="form-control required  @error('code_nationalite_mere') is-invalid @enderror ">
                                            @foreach ($nationalites as $nationalite)
                                                <option value="{{ $nationalite->code_nationalite }}">{{ $nationalite->lib_nationalite }}</option>
                                            @endforeach
                                        </select>
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

                                </div>
                                <div class="row">
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
                                        <input type="text" id="numero_document_mere" class="form-control form-control wide" placeholder="Numéro du document" onkeyup="this.value=this.value.toUpperCase()">
                                    </div>
                                </div>
                                <div class="ligne">
                                    <h4>ADRESSE
                                        ( <span style="color:red!important">
                                        <label class="radio-inline mr-3">Même adresse que le père ?</label></span>
                                        <label class="radio-inline mr-3"><input type="radio" id="sameadress" name="adresse" value="1"> OUI</label>
                                        <label class="radio-inline mr-3"><input type="radio" id="otheradress" name="adresse" value="1"> NON</label>)
                                        </h4>
                                </div>
                                <div class="row adressemere">
                                    <div class="mb-2 col-md-3">
                                        <label class="form-label">Pays<span class="text-danger"></span></label>
                                        <select id="domicile_pays_mere" class="form-control required" disabled>
                                            @foreach ($countries as $countrie)
                                                <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-3 domicile_ville_mere">
                                        <label class="form-label">Commune/District<span class="text-danger"></span></label>
                                        <select class="form-control" id="domicile_ville_mere" disabled>
                                            <option value="">Choisir</option>
                                            @foreach ($localites as $localite)
                                                <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-3 autredomicile_ville_mere d-none">
                                        <label class="form-label">Ville<span class="text-danger"></span></label>
                                        <input type="text" id="autredomicile_ville_mere" class="form-control form-control wide" placeholder="Libellé de la ville" onkeyup="this.value=this.value.toUpperCase()">
                                    </div>

                                    <div class="mb-2 col-md-3 domicile_arrondissement_mere">
                                        <label class="form-label">Arr/Com. Urb<span class="text-danger"></span></label>
                                        <select class="form-control" id="domicile_arrondissement_mere" disabled>
                                            <option value="">Choisir</option>
                                            @foreach ($arrondissement as $localite)
                                                <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-2 col-md-3 domicile_quartier_mere">
                                        <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                                        <select class="form-control" id="domicile_quartier_mere" disabled>
                                            <option value="">Choisir</option>
                                            @foreach ($quartierVillages as $localite)
                                                <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-3">
                                        <label class="form-label">Type voie<span class="text-danger"></span></label>
                                        <select class="form-control" id="domicile_typevoie_mere" disabled>
                                            <option value="">Choisir</option>
                                            <option value="Avenue">Avenue</option>
                                            <option value="Boulevard">Boulevard</option>
                                            <option value="Impasse">Impasse</option>
                                            <option value="Rue">Rue</option>
                                            <option value="Autre">Autre</option>
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-3">
                                        <label class="form-label">N° voie<span class="text-danger"></span></label>
                                        <input type="text" class="form-control" id="domicile_numero_mere" disabled placeholder="N° voie">
                                    </div>
                                    <div class="mb-2 col-md-3">
                                        <label class="form-label">Nom voie<span class="text-danger"></span></label>
                                        <input type="text" class="form-control" id="domicile_nomvoie_mere" disabled placeholder="Nom voie" style="text-transform: capitalize">
                                    </div>
                                </div>
                                <div class="ligne">
                                    <h4>CONTACTS</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Indicatif<span class="text-danger">*</span></label>
                                        <select name="code_pays_mere" id="code_pays_mere" class="form-control">
                                            @forelse ($countries as $code)
                                                <option value="{{ $code->dial_code }}">({{ $code->dial_code }}) {{ $code->name }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Téléphone mère</label>
                                        <input type="number" min="0" minlength="9" maxlength="15" id="telephone_mere" name="telephone_mere" class="form-control @error('telephone_mere') is-invalid @enderror " placeholder="Téléphone mère">
                                    </div>

                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Téléphone tuteur<span class="text-danger">*</span></label>
                                        <input type="number" id="telephone_parent" class="form-control" name="telephone_parent" placeholder="Téléphone tuteur">
                                    </div>

                                    <div class="ligne">
                                        <h4>AUTRES INFORMATIONS</h4>
                                    </div>

                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Statut <span class="text-danger">*</span></label>
                                        <select id="statut_personne_mere" name="statut_personne_mere" class="form-control">
                                            <option selected value="VIVANT">Vivant</option>
                                            <option value="DECEDE">Décédé</option>
                                        </select>
                                    </div>
                                </div>
                            </section>
                            <!-- Step 4 -->
                            <h6>Déclarant</h6>
                            <section class="d-none">
                                <div class="row">
                                    <div class="mb-2 col-sm-4" id="hide_pere">
                                        <label for="dewey">Père</label>
                                        <input type="radio" id="peredeclarant" name="autredeclarant" value="pere">
                                    </div>
                                    <div class="mb-2 col-sm-4" id="hide_mere">
                                        <label for="dewey">Mère</label>
                                        <input type="radio" id="meredeclarant" name="autredeclarant" checked value="mere">
                                    </div>
                                    <div class="mb-2 col-sm-4">
                                        <label for="dewey">Autre</label>
                                        <input type="radio" id="autredeclarant" name="autredeclarant"  value="autre">
                                    </div>

                                    <div class="ligne">
                                        <h4>INFORMATIONS PERSONNELLES</h4>
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Nom(s) déclarant <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"  placeholder="Nom du déclarant" id="nom_declarant" name="nom_declarant" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()" value="{{ $dummy }}">
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Prénom(s) du déclarant </label>
                                        <input type="text" class="form-control" placeholder="Prénom du déclarant" id="prenom_declarant" name="prenom_declarant" onkeyup="verif_lettre(this);" style="text-transform: capitalize" value="{{ $dummy }}">
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Sexe du déclarant<span class="text-danger">*</span></label>
                                        <select id="sexe_declarant" name="sexe_declarant" class="form-control">
                                            <option value="F">Selectionner</option>
                                            <option value="M">Masculin</option>
                                            <option value="F">Féminin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Date de naissance du déclarant<span class="text-danger">*</span></label>
                                        <input type="date" name="date_naissance_declarant" max="<?php $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 18 years'));?>" min="<?php echo date('Y-m-d', strtotime($jour. ' - 100 years')); ?>"
                                        class="form-control" id="date_naissance_declarant">
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Lieu de naissance </label>
                                        <input type="text" class="form-control d-none" name="lieu_naissance_declarant" id="lieu_naissance_declarant" placeholder="Lieu de naissance">
                                        <select id="code_localite_declarant" class="form-control">
                                            <option selected value="LOC_4250">Choisissez</option>
                                            @foreach ($localites as $localite)
                                                <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                        <div class="mb-2 col-md-4">
                                        <label class="form-label">Nationalité du déclarant<span class="text-danger">*</span></label>
                                        <select id="code_nationalite_declarant" name="code_nationalite_declarant" class="form-control required  @error('code_nationalite_declarant') is-invalid @enderror ">
                                            @foreach ($nationalites as $nationalite)
                                                <option value="{{ $nationalite->code_nationalite }}">{{ $nationalite->lib_nationalite }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Filiation <span class="text-danger">*</span></label>
                                        <select id="filiation" name="filiation" class="form-control">
                                                <option value="FIL_0002">Choisissez</option>
                                                @foreach ($filiations as $item)
                                                    <option class="{{$item->code_filiation }}" value="{{$item->code_filiation }}">{{ $item->lib_filiation }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Profession du déclarant</label>
                                        <select id="profession_declarant" name="profession_declarant" class="form-control">
                                                <option selected value="PROF_0010">Choisissez</option>
                                            @foreach ($professions as $item)
                                                <option value="{{ $item->code_profession }}">{{ $item->lib_profession }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Niveau d'instruction du déclarant</label>
                                        <select id="niveau_instruction_declarant" name="niveau_instruction_declarant" class="form-control form-control wide">
                                                <option value="NON DECLARE" selected>Choisissez</option>
                                            @foreach ($instructions as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Type pièce d'identité</label>
                                        <select id="code_type_document_declarant" name="code_type_document_declarant" class="form-control">
                                                <option value="TDOC_0018">Choisissez</option>
                                            @foreach ($typedocuments as $item)
                                                <option value="{{ $item->code_type_document }}">{{ $item->lib_type_document  }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Numéro pièce d'identité</label>
                                        <input type="text" id="numero_document_declarant" name="numero_document_declarant" class="form-control" placeholder="Numéro du document" onkeyup="this.value=this.value.toUpperCase()" value="{{ $dummy }}">
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label class="form-label">Statut <span class="text-danger">*</span></label>
                                        <select id="statut_personne_declarant" name="statut_personne_declarant" class="form-control">
                                            <option value="VIVANT">Vivant(e)</option>
                                        </select>
                                    </div>
                                    <div class="ligne">
                                        <h4>ADRESSE</h4>
                                    </div>
                                    <div class="row">
                                        <div class="mb-2 col-md-3">
                                            <label class="form-label">Pays<span class="text-danger"></span></label>
                                            <select id="domicile_pays_declarant" class="form-control">
                                                @foreach ($countries as $countrie)
                                                    <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2 col-md-3 domicile_ville_declarant">
                                            <label class="form-label">Commune/District<span class="text-danger"></span></label>
                                            <select class="form-control" id="domicile_ville_declarant">
                                                @foreach ($localites as $localite)
                                                    <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2 col-md-3 autredomicile_ville_declarant d-none">
                                            <label class="form-label">Ville<span class="text-danger"></span></label>
                                            <input type="text" id="autredomicile_ville_declarant" class="form-control form-control wide" placeholder="Libellé de la ville" onkeyup="this.value=this.value.toUpperCase()">
                                        </div>

                                        <div class="mb-2 col-md-3 domicile_arrondissement_declarant">
                                            <label class="form-label">Arr/Com. Urb<span class="text-danger"></span></label>
                                            <select class="form-control" id="domicile_arrondissement_declarant">
                                                <option value="">Choisir</option>
                                                @foreach ($arrondissement as $localite)
                                                    <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-2 col-md-3 domicile_quartier_declarant">
                                            <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                                            <select class="form-control" id="domicile_quartier_declarant">
                                                <option value="">Choisir</option>
                                                @foreach ($quartierVillages as $localite)
                                                    <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2 col-md-3">
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
                                        <div class="mb-2 col-md-3">
                                            <label class="form-label">N° voie<span class="text-danger"></span></label>
                                            <input type="text" class="form-control" id="domicile_numero_declarant" placeholder="N° voie">
                                        </div>
                                        <div class="mb-2 col-md-3">
                                            <label class="form-label">Nom voie<span class="text-danger"></span></label>
                                            <input type="text" class="form-control" id="domicile_nomvoie_declarant" placeholder="Nom voie" style="text-transform: capitalize">
                                        </div>
                                        <div class="ligne">
                                            <h4>CONTACTS</h4>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Indicatif<span class="text-danger">*</span></label>
                                                <select name="code_pays_declarant" id="code_pays_declarant" class="form-control">
                                                    @forelse ($countries as $code)
                                                        <option value="{{ $code->dial_code }}">({{ $code->dial_code }}) {{ $code->name }}</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Téléphone</label>
                                                <input type="number" min="0" minlength="9" maxlength="15" id="telephone_declarant" name="telephone_declarant" class="form-control @error('telephone_declarant') is-invalid @enderror " placeholder="Téléphone déclarant">
                                            </div>
                                            <div class="mb-2 col-md-4">
                                                <label class="form-label">Téléphone tuteur</label>
                                                <input type="text" id="telephone_parent" class="form-control" name="telephone_parent" placeholder="Téléphone tuteur">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include("naissance::declaration.search_parents")
@endsection
@include('naissance::fiche-maternite.js.create')

@section("corps")
<script>




</script>
@endsection
