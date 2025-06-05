<form name="contactUsForm" id="contactUsForm"  method="post" action="javascript:void(0)" class="validation-wizard wizard-circle">
    <!-- Step 1 -->
    <h6>Enfant</h6>
    <div class="d-none">
        <input type="text" value="{{ $typeDeclaration }}" id="type_declaration">
        <input type="text" value="{{ $dn->code_declaration_naissance }}" id="code_declaration_naissance">
        <input type="text" value="{{ $dn->enfant->code_personne }}" id="code_enfant">
    </div>
    <section>
        <div class="row">
            <div class="mb-2 col-sm-4" id="hide_partielle"><label class="radio-inline mr-3"><input type="radio" id="partielle" value="adoption partielle" name="adoption" checked> Partielle</label></div>
            <div class="mb-2 col-sm-4" id="hide_pleniere"><label class="radio-inline mr-3" id="hide_pleniere"><input type="radio" id="pleniere" value="adoption pleniere" name="adoption"> Plénière</label></div>
        </div>
		 <div class="ligne">
                <h4>INFORMATIONS SUR L'IDENTITE</h4>
            </div>
        <div class="row">
            <div class="mb-2 col-md-4">
                <label class="form-label">Nom(s) enfant <span class="text-danger">*</span></label>
                <input type="text" name="nom_enfant"  class="form-control"  placeholder="Nom enfant" id="nom_enfant" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()" value="{{ $dn->enfant->nom }}">
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Prénom(s) enfant</label>
                <input type="text" class="form-control" placeholder="Prénom enfant" id="prenom_enfant" onkeyup="verif_lettre(this);" style="text-transform: capitalize" value="{{ $dn->enfant->prenom }}">
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Sexe <span class="text-danger">*</span></label>
                <select id="sexe_enfant" name="sexe_enfant" class="form-control  @error('sexe_enfant') is-invalid @enderror">
                    <option value="M" {{ $dn->enfant->sexe == "M" ? "selected" : "" }}>Masculin</option>
                    <option value="F" {{ $dn->enfant->sexe == "F" ? "selected" : "" }}>Féminin</option>
                </select>
            </div>
        </div>
		<div class="ligne">
                <h4>INFORMATIONS SUR LA NAISSANCE</h4>
            </div>
        <div class="row">
            <div class="mb-2 col-md-3">
                <label class="form-label">Date de naissance <span class="text-danger">*</span></label>
                <input type="date" value="{{ $dn->enfant->date_naissance }}" name="date_naissance_enfant" max="<?php  echo date("Y-m-d"); ?>" required min="<?php  $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 100 year'));?>" class="form-control  @error('date_naissance_enfant') is-invalid @enderror " id="date_naissance_enfant">
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Lieu de naissance <span class="text-danger">*</span></label>
                @if(Auth::user()->affectationactive()->institution->code_institution == "INS_0046")
                    <input type="text" class="form-control" name="lieu_naissance_enfant" id="lieu_naissance_enfant" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()" value="{{ $dn->enfant->lieu_naissance }}">
                @else
                    <select id="code_localite_enfant" class="form-control">
                        @foreach ($localites as $localite)
                            <option value="{{ $localite->code_localite }}" {{ $dn->enfant->code_localite == $localite->code_localite ? "selected" : "" }}>{{ $localite->lib_localite }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Heure de naissance <span class="text-danger">*</span></label>
                <input type="time" name="heure_naissance_enfant" class="form-control"  id="heure_naissance_enfant" value="{{ date("H:i", strtotime($dn->date_heure_naissance)) }}">
            </div>
            @php
                $UserInstitution = Auth::user()->affectationactive()->institution;
            @endphp
            @if($UserInstitution->typeInstitution->code_type_categorie_ins != 'TCINS_0003')
            <div class="mb-2 col-md-3 d-none">
                <label class="form-label">Lieu de survenance <span class="text-danger"></span> </label>
                <select  id="code_lieu_survenance" class="form-select form-control">
                    {{-- si c'est CEC --}}
                    {{-- @if($UserInstitution->TypeInstitution->typeCategorieInstitution->code_type_categorie_ins == "TCINS_0001") --}}
                    <option disabled selected>Choisissez</option>
                    @foreach ($lieuSurvenances as $item)
                        <option value="{{ $item->code_lieu_survenance }}" {{ $dn->lieuSurvenance->code_lieu_survenance == $item->code_lieu_survenance ? "selected" : "" }}>{{ $item->lib_lieu_survenance }}</option>
                    @endforeach
                    {{-- @endif --}}
                </select>
            </div>
            @endif
            @if(Auth::user()->affectationactive()->institution->TypeInstitution->typeCategorieInstitution->code_type_categorie_ins == "TCINS_0001")
               <div class="mb-2 col-md-3 formationsanitaire d-none">
                    <label class="form-label">Formation sanitaire de naissance</label>
                    <input type="text" id="formation_sanitaire_naissance" name="formation_sanitaire_naissance" class="form-control" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()" value="{{ $dn->formation_sanitaire_naissance }}">
                </div>
            @endif
            <div class="mb-2 col-md-3">
                <label class="form-label">Nationalité<span class="text-danger">*</span></label>
                <select id="code_nationalite_enfant" name="code_nationalite_enfant" class="form-control required  @error('code_nationalite_enfant') is-invalid @enderror ">
                        @foreach ($nationalites as $nationalite)
                        <option value="{{ $nationalite->code_nationalite }}"  {{ $dn->enfant->nationalite->code_nationalite == $nationalite->code_nationalite ? "selected" : "" }}>{{ $nationalite->lib_nationalite }}</option>
                    @endforeach
                </select>
            </div>
            @if($ageEnfant > 17)
            <div class="mb-2 col-md-4">
                <label class="form-label">Profession<span class="text-danger">*</span></label>
                <select id="profession_enfant" class="form-control form-control wide">
                    @foreach ($professions as $item)
                        <option value="{{ $item->code_profession }}"  {{ $dn->enfant->profession->code_profession == $item->code_profession ? "selected" : "" }}>{{ $item->lib_profession }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Niveau d'instruction</label>
                <select id="niveau_instruction_enfant" class="form-control form-control wide">
                    @foreach ($instructions as $item)
                        <option value="{{ $item }}" {{ $dn->enfant->niveau_instruction == $item ? "selected" : "" }}>{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Type pièce d'identité</label>
                <select id="code_type_document_enfant" class="form-control form-control wide">
                    @foreach ($typedocuments as $item)
                        @if($dn->enfant->document == null)
                            <option value="{{ $item->code_type_document }}" {{ $item->code_type_document == "TDOC_0018" ? "selected" : "" }}>{{ $item->lib_type_document  }}</option>
                        @else
                            <option value="{{ $item->code_type_document }}" >{{ $item->lib_type_document  }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Numéro pièce d'identité</label>
                <input type="text" id="numero_document_enfant" class="form-control form-control wide" placeholder="Numéro du document" onkeyup="this.value=this.value.toUpperCase()" value="{{ $dn->enfant->document->numero_document ?? "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX" }}">
            </div>
        </div>
        @endif
        @if($ageEnfant > 17)
        <div class="ligne">
            <h4>ADRESSE</h4>
        </div>
        <div class="row">
            <div class="mb-2 col-md-3">
                <label class="form-label">Pays<span class="text-danger"></span></label>
                <select id="domicile_pays_enfant" class="form-control required">
                    @foreach ($countries as $countrie)
                    @if($dn->enfant->adresses->last() != null)
                        <option value="{{ $countrie->name }}" {{ $dn->enfant->adresses->last()->lib_pays == $countrie->name ? "selected" : "" }}>{{ $countrie->name }}</option>
                    @else
                    <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                    @endif
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3 domicile_ville_enfant">
                <label class="form-label">Commune/District<span class="text-danger"></span></label>
                <select class="form-control" id="domicile_ville_enfant">
                    @if($dn->enfant->adresses->last() != null)
                        @foreach ($localites as $item)
                            <option value="{{ $item->code_localite }}" {{  $dn->enfant->adresses->last()->code_localite == $item->code_localite ? "selected" : "" }}>{{ $item->lib_localite }}</option>
                        @endforeach
                    @else
                        <option value="">Selectionnez</option>
                        @foreach ($localites as $item)
                            <option value="{{ $item->code_localite }}">{{ $item->lib_localite }}</option>
                        @endforeach
                    @endif

                </select>
            </div>
            @if($dn->enfant->adresses->last() != null)
                @if($dn->enfant->adresses->last()->lib_pays != "Congo")
                <div class="mb-2 col-md-3 autredomicile_ville_enfant">
                    <label class="form-label">Ville<span class="text-danger"></span></label>
                    <input type="text" id="autredomicile_ville_enfant" class="form-control form-control wide" placeholder="Libellé de la ville" onkeyup="this.value=this.value.toUpperCase()" value="{{ $dn->enfant->adresses->last()->lib_ville ?? "" }}">
                </div>
                @endif
            @endif
            <div class="mb-2 col-md-3 domicile_arrondissement_enfant">
                <label class="form-label">Arrondissement/Communauté urbaine<span class="text-danger"></span></label>
                <select class="form-control" id="domicile_arrondissement_enfant">

                    @if($dn->enfant->adresses->last() != null)
                        @foreach ($arrondissement as $item)
                            <option value="{{ $item->code_localite }}" {{  $dn->enfant->adresses->last()->code_arrondissement_comurbaine == $item->code_localite ? "selected" : "" }}>{{ $item->lib_localite }}</option>
                        @endforeach
                    @else
                    <option value="">Selectionnez</option>
                        @foreach ($arrondissement as $item)
                            <option value="{{ $item->code_localite }}">{{ $item->lib_localite }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="mb-2 col-md-3 domicile_quartier_enfant">
                <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                <select class="form-control" id="domicile_quartier_enfant">
                    @if($dn->enfant->adresses->last() != null)
                        @foreach ($quartierVillages as $localite)
                            <option value="{{ $localite->code_localite }}" {{  $dn->enfant->adresses->last()->code_quartier_village  == $localite->code_localite ? "selected" : "" }}>{{ $localite->lib_localite }}</option>
                        @endforeach
                    @else
                        <option value="">Selectionnez</option>
                        @foreach ($quartierVillages as $localite)
                            <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                        @endforeach
                    @endif

                </select>
            </div>

            <div class="mb-2 col-md-3">
                <label class="form-label">Type voie<span class="text-danger"></span></label>
                <select class="form-control" id="domicile_typevoie_enfant">
                    @if($dn->enfant->adresses->last() != null)
                    <option value="Avenue" {{ $dn->enfant->adresses->last()->type_voie == "Avenue" ? "selected" : "" }}>Avenue</option>
                    <option value="Boulevard" {{ $dn->enfant->adresses->last()->type_voie == "Boulevard" ? "selected" : "" }}>Boulevard</option>
                    <option value="Impasse" {{ $dn->enfant->adresses->last()->type_voie == "Impasse" ? "selected" : "" }}>Impasse</option>
                    <option value="Rue" {{ $dn->enfant->adresses->last()->type_voie == "Rue" ? "selected" : "" }}>Rue</option>
                    <option value="Autre" {{ $dn->enfant->adresses->last()->type_voie == "Autre" ? "selected" : "" }}>Autre</option>
                    @else
                    <option value="">Selectionnez</option>
                    <option value="Avenue">Avenue</option>
                    <option value="Boulevard">Boulevard</option>
                    <option value="Impasse">Impasse</option>
                    <option value="Rue">Rue</option>
                    <option value="Autre">Autre</option>
                    @endif

                </select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">N° voie<span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_numero_enfant" placeholder="N° voie" value="{{ $dn->enfant->adresses->last()->numero_rue ?? "" }}">
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Nom voie<span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_nomvoie_enfant" placeholder="Nom voie" style="text-transform: capitalize" value="{{ $dn->enfant->adresses->last()->nom_voie ?? "" }}">
            </div>

            <div class="ligne">
                <h4>CONTACTS</h4>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Indicatif<span class="text-danger">*</span></label>
                    <select name="code_pays_enfant" id="code_pays_enfant" class="form-control">
                        @foreach ($countries as $code)
                        @if($dn->enfant->adresses->last() != null)
                        <option value="{{ $code->dial_code }}" {{ $dn->enfant->adresses->last()->lib_pays == $code->name ? "selected" : "" }}>({{ $code->dial_code }}) {{ $code->name }}</option>
                        @else
                        <option value="{{ $code->dial_code }}">({{ $code->dial_code }}) {{ $code->name }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Téléphone</label>
                    <input type="number" min="0" minlength="9" maxlength="10" id="telephone_enfant" name="telephone_enfant" class="form-control" placeholder="Téléphone père" value="{{ $dn->enfant->telephone ?? "" }}">
                </div>

                <div class="mb-2 col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" id="email_enfant" class="form-control" name="email_enfant" placeholder="Email père" value="{{ $dn->enfant->user !='' ? $dn->enfant->user->email : "" }}">
                </div>
            </div>
        </div>
        @endif
        <div class="ligne">
            <h4>AUTRES INFORMATIONS</h4>
        </div>
        <div class="row">
            <div class="mb-2 col-md-3">
                <label class="form-label">Situation matrimoniale des parents<span class="text-danger">*</span></label>
                <select id="code_situation_matrimoniale" name="code_situation_matrimoniale" class="form-control">
                    <option disabled selected>Choisissez</option>
                    @foreach ($situationMatrimoniales as $item)
                        <option value="{{ $item->code_situation_matrimoniale }}" {{ $dn->sitMatParent->code_situation_matrimoniale == $item->code_situation_matrimoniale ? "selected" : "" }}>{{ $item->lib_situation_matrimoniale }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Date déclaration</label>
                <input type="date" id="date_heure_declaration" name="date_heure_declaration" class="form-control" value="{{ date("Y-m-d", strtotime($dn->date_heure_declaration)) }}">
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Statut <span class="text-danger">*</span></label>
                <select id="statut_personne_enfant" name="statut_personne_enfant" class="form-control">
                    <option value="VIVANT" {{ $dn->enfant->statut_personne == "VIVANT" ? "selected" : "" }}>Vivant</option>
                    <option value="DECEDE" {{ $dn->enfant->statut_personne == "DECEDE" ? "selected" : "" }}>Décédé</option>
                </select>
            </div>
        </div>
    </section>
    @include("naissance::adoption.parentedit")
</form>
