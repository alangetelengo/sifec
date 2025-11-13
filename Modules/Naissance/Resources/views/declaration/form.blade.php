<form name="contactUsForm" id="contactUsForm"  method="post" action="javascript:void(0)" class="validation-wizard wizard-circle" enctype="multipart/form-data">
    <!-- Step 1 -->
    <h6>Enfant</h6>
    <div class="d-none">
        <input type="text" value="{{ $type_declaration }}" id="type_declaration">
        <input type="text" value="Personne physique" id="type_declarant">
        <input type="text" value="Enfant normal" id="personne_declaree">

    </div>
    <section>
        @if ($type_declaration == "JUGEMENT SUPLETIF")
            <div class="ligne">
                <h4>INFORMATIONS DU JUGEMENT</h4>
            </div>
            <div class="row">
                <div class="mb-2 col-md-4">
                    <label class="form-label">Tribunal <span class="text-danger">*</span></label>
                    @isset($tgis)
                        <select id="tribunal" name="tribunal" class="form-control">
                            <option value="" disabled selected>Selectionner</option>
                            @if (count($tgis)>0)
                                @foreach ($tgis as $tgi)
                                    <option value="{{$tgi->code_institution }}">{{$tgi->lib_institution }}</option>
                                @endforeach
                            @endif
                        </select>
                    @endisset
                </div>
                <div class="mb-2 col-md-4">
                    <label class="form-label">N° du jugement</label>
                    <input type="text" class="form-control" placeholder="Numéro du jugement" id="num_jugement">
                </div>
                <div class="mb-2 col-md-4">
                    <label class="form-label">Date du jugement <span class="text-danger">*</span></label>
                    <input type="date" name="date_jugement"  class="form-control"  placeholder="Nom enfant" id="date_jugement">
                </div>
            </div>
        @endif
        <div class="ligne">
            <h4>INFORMATIONS SUR L'IDENTITE</h4>
        </div>
        <div class="row">
            <div class="mb-2 col-md-4">
                <label class="form-label">Nom(s) enfant <span class="text-danger">*</span></label>
                <input type="text" name="nom_enfant"  class="form-control  @error('nom_enfant') is-invalid @enderror"  placeholder="Nom enfant" id="nom_enfant" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Prénom(s) enfant</label>
                <input type="text" class="form-control" placeholder="Prénom enfant" id="prenom_enfant" onkeyup="verif_lettre(this);" style="text-transform: capitalize">
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
            <div class="mb-2 col-md-4">
                <label class="form-label">Date de naissance <span class="text-danger">*</span></label>
                <input type="date" value="{{ $dateNaissance ?? '' }}" name="date_naissance_enfant" max="<?php  echo date('Y-m-d'); ?>" required min="<?php  $jour=date('Y-m-d'); echo date('Y-m-d', strtotime($jour. ' - 100 year'));?>" class="form-control  @error('date_naissance_enfant') is-invalid @enderror " id="date_naissance_enfant" @if(isset($dateNaissance) && $dateNaissance) readonly @endif>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Heure de naissance <span class="text-danger">*</span></label>
                <input type="time" name="heure_naissance_enfant" class="form-control  @error('heure_naissance_enfant') is-invalid @enderror"  id="heure_naissance_enfant">
            </div>
            <div class="mb-2 col-md-4 d-none">
                <label class="form-label">Lieu de naissance <span class="text-danger">*</span></label>
                @if(Auth::user()->affectationactive()->institution->code_institution == "INS_0046")
                    <input type="text" class="form-control" name="lieu_naissance_enfant" id="lieu_naissance_enfant" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()">
                @else
                    <select id="code_localite_enfant" class="form-control">
                        <option disabled selected>Choisissez</option>
                        @foreach ($localites as $localite)
                        <option value="{{ $localite->code_localite }}" {{ Auth::user()->affectationActive()->institution->lalocalite->localiteParent->code_localite == $localite->code_localite ? "selected" : "" }}>{{ $localite->lib_localite }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
            @php
                $UserInstitution = Auth::user()->affectationactive()->institution;
            @endphp
            @if($UserInstitution->typeInstitution->code_type_categorie_ins != 'TCINS_0003')
            <div class="mb-2 col-md-4">
                <label class="form-label">Lieu de survenance <span class="text-danger"></span> </label>
                <select  id="code_lieu_survenance" class="form-select form-control">
                        <option disabled selected>Choisissez</option>
                        @foreach ($lieuSurvenances as $item)
                            <option value="{{ $item->code_lieu_survenance }}">{{ $item->lib_lieu_survenance }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            @if(Auth::user()->affectationactive()->institution->TypeInstitution->typeCategorieInstitution->code_type_categorie_ins == "TCINS_0001")
               <div class="mb-2 col-md-4 formationsanitaire d-none">
                    <label class="form-label">Formation sanitaire de naissance</label>
                    <input type="text" id="formation_sanitaire_naissance" name="formation_sanitaire_naissance" class="form-control" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()">
                </div>
            @endif
        </div>
        <div class="ligne">
            <h4>AUTRES INFORMATIONS</h4>
        </div>
        <div class="row">
            <div class="mb-2 col-md-4">
                <label class="form-label">Situation matrimoniale des parents<span class="text-danger">*</span></label>
                <select id="code_situation_matrimoniale" name="code_situation_matrimoniale" class="form-control  @error('code_situation_matrimoniale') is-invalid @enderror ">
                    <option disabled selected>Choisissez</option>
                    @foreach ($situationMatrimoniales as $item)
                        <option value="{{ $item->code_situation_matrimoniale }}">{{ $item->lib_situation_matrimoniale }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Date déclaration</label>
                <input type="date" id="date_heure_declaration" name="date_heure_declaration" class="form-control">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Statut <span class="text-danger">*</span></label>
                <select id="statut_personne_enfant" name="statut_personne_enfant" class="form-control">
                    <option selected value="VIVANT">Vivant</option>
                    <option value="DECEDE">Décédé</option>
                </select>
            </div>
        </div>


     @if($ageEnfant >= 18 || $type_declaration == "CERTIFICAT DE DESTRUCTION DE L'ACTE")
        <h4>ADRESSE</h4>
        <div class="row">
            <div class="mb-2 col-md-3">
                <label class="form-label">Pays<span class="text-danger"></span></label>
                <select name="domicile_pays_enfant" id="domicile_pays_enfant" class="form-control required">
                    @foreach ($countries as $countrie)
                        <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3 domicile_ville_enfant">
                <label class="form-label">Commune/District<span class="text-danger"></span></label>
                <select name="domicile_ville_enfant" id="domicile_ville_enfant" class="form-control">
                    <option value="">Choisir</option>
                    @foreach ($localites as $localite)
                        <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2 col-md-3 autredomicile_ville_enfant d-none">
                <label class="form-label">Ville<span class="text-danger"></span></label>
                <input type="text" id="autredomicile_ville_enfant" class="form-control form-control wide" placeholder="Libellé de la ville" onkeyup="this.value=this.value.toUpperCase()">
            </div>

            <div class="mb-2 col-md-3 domicile_arrondissement_enfant d-none">
                <label class="form-label">Arr/Com. Urb<span class="text-danger"></span></label>
                <select name="domicile_arrondissement_enfant" id="domicile_arrondissement_enfant" class="form-control">
                    <option value="">Choisir</option>
                    @foreach ( $arrondissements as $localite)
                        <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2 col-md-3 domicile_quartier_enfant">
                <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                <select name="domicile_quartier_enfant" id="domicile_quartier_enfant" class="form-control">
                    <option value="">Choisir</option>
                    @foreach ( $quartiers as $localite)
                        <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Type voie<span class="text-danger"></span></label>
                <select name="domicile_typevoie_enfant" id="domicile_typevoie_enfant" class="form-control">
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
                <input type="text" name="domicile_numero_enfant" id="domicile_numero_enfant" class="form-control" placeholder="N° voie">
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Nom voie<span class="text-danger"></span></label>
                <input type="text" name="domicile_nomvoie_enfant" id="domicile_nomvoie_enfant" class="form-control" placeholder="Nom voie" style="text-transform: capitalize">
            </div>

            <div class="ligne">
                <h4>CONTACTS</h4>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Indicatif<span class="text-danger">*</span></label>
                    <select name="code_pays_enfant" id="code_pays_enfant" class="form-control">

                        @forelse ($countries as $code)
                            <option value="{{ $code->dial_code }}">({{ $code->dial_code }}) {{ $code->name }}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Téléphone enfant</label>
                    <input type="number" min="0" minlength="9" maxlength="15" id="telephone_enfant" name="telephone_enfant" class="form-control @error('telephone_enfant') is-invalid @enderror " placeholder="Téléphone enfant">
                </div>

                <div class="mb-2 col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" id="email_enfant" class="form-control" name="email_enfant" placeholder="Email enfant">
                </div>

                <div class="ligne">
                    <h4>AUTRES INFORMATIONS</h4>
                </div>

                <div class="mb-2 col-md-4">
                    <label class="form-label">Statut <span class="text-danger">*</span></label>
                    <select id="statut_personne_enfant" name="statut_personne_enfant" class="form-control">
                        <option selected value="VIVANT">Vivant</option>
                        <option value="DECEDE">Décédé</option>
                    </select>
                </div>
            </div>
        </div>
    @endif
</section>
    @include("naissance::declaration.parent")
    @include('naissance::declaration.declarant')
</form>



