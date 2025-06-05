<form name="contactUsForm" id="contactUsForm"  method="post" action="javascript:void(0)" class="validation-wizard wizard-circle" enctype="multipart/form-data">
    <!-- Step 1 -->
    <h6>Enfant</h6>
    <div class="d-none">
        <input type="text" value="{{ $jugement->type_jugement ?? $type_declaration }}" id="type_declaration">
    </div>
    <section>

        @isset($jugement)
            @if ($jugement->type_jugement == "JUGEMENT SUPPLETIF" || $jugement->type_jugement == "JUGEMENT D'HOMOLOGATION")
                <div class="ligne">
                    @if($jugement->type_jugement == "JUGEMENT D'HOMOLOGATION")
                        <div class="d-flex justify-content-end align-items-center">
                            {{-- <button type="button" id="clear_pere" class="btn btn-danger  text-white" ></i> Vider </button> --}}
                            <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".search-enfant-modal-lg"  ><i class="fa fa-search"></i> Rechercher acte</button>
                        </div>
                    @endif
                    <hr>
                    <h4>INFORMATIONS DU JUGEMENT</h4>
                </div>
                <div class="row">
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Tribunal <span class="text-danger">*</span></label>
                        <select id="tribunal" name="tribunal" class="form-control" readOnly>
                            <option>{{$jugement->institutionUser->institution->lib_institution }}</option>
                        </select>
                    </div>

                    <div class="mb-2 col-md-3">
                        <label class="form-label">N° du jugement</label>
                        <input type="text" class="form-control" readonly value="{{ $jugement->num_jugement }}" id="num_jugement">
                        <input type="hidden" class="form-control" value="{{ $jugement->code_jugement }}" id="code_jugement">
                    </div>

                    <div class="mb-2 col-md-2">
                        <label class="form-label">Date du jugement <span class="text-danger">*</span></label>
                        <input type="date" name="date_jugement"  class="form-control" readonly  value="{{ $jugement->date_jugement }}" id="date_jugement">

                    </div>
                    @if($jugement->type_jugement == "JUGEMENT D'HOMOLOGATION")
                    <div class="mb-2 col-md-3">
                        <label class="form-label">Ancien acte <span class="text-danger">*</span></label>
                        <input type="text" name="numero_ancien_acte"  class="form-control" readonly value="{{ $jugement->numero_ancien_acte }}" id="numero_ancien_acte">
                    </div>
                    @endif
                </div>
            @endif
        @endisset()
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
                <input type="date" value="{{ $dateNaissance ?? "" }}" name="date_naissance_enfant" max="<?php  echo date("Y-m-d"); ?>" required min="<?php  $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 100 year'));?>" class="form-control  @error('date_naissance_enfant') is-invalid @enderror " id="date_naissance_enfant">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Heure de naissance <span class="text-danger">*</span></label>
                <input type="time" name="heure_naissance_enfant" class="form-control  @error('heure_naissance_enfant') is-invalid @enderror"  id="heure_naissance_enfant">
            </div>

            @if(Auth::user()->affectationactive()->institution->code_institution == "INS_0046" || Auth::user()->affectationactive()->institution->typeInstitution->code_type_institution == "TPINS_0005")
                <div class="mb-2 col-md-4">
                    <label class="form-label">Lieu de naissance <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="lieu_naissance_enfant" id="lieu_naissance_enfant" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()">
                </div>
            @else
                <div class="mb-2 col-md-4 d-none">
                    <label class="form-label">Lieu de naissance <span class="text-danger">*</span></label>
                    <select id="code_localite_enfant" class="form-control">
                        <option disabled selected>Choisissez</option>
                        @foreach ($localites as $localite)
                        <option value="{{ $localite->code_localite }}" {{ Auth::user()->affectationActive()->institution->lalocalite->localiteParent->code_localite == $localite->code_localite ? "selected" : "" }}>{{ $localite->lib_localite }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @php
                $UserInstitution = Auth::user()->affectationactive()->institution;
            @endphp
            @if($UserInstitution->typeInstitution->code_type_categorie_ins != 'TCINS_0003')
            <div class="mb-2 col-md-4">
                <label class="form-label">Lieu de survenance <span class="text-danger"></span> </label>
                <select  id="code_lieu_survenance" class="form-select form-control">
                    {{-- @if($UserInstitution->TypeInstitution->typeCategorieInstitution->code_type_categorie_ins == "TCINS_0001") --}}
                            <option disabled selected>Choisissez</option>
                            @foreach ($lieuSurvenances as $item)
                                <option value="{{ $item->code_lieu_survenance }}">{{ $item->lib_lieu_survenance }}</option>
                        @endforeach
                    {{-- @endif --}}
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
            <div class="mb-2 col-md-3">
                <label class="form-label">Situation matrimoniale des parents<span class="text-danger">*</span></label>
                <select id="code_situation_matrimoniale" name="code_situation_matrimoniale" class="form-control  @error('code_situation_matrimoniale') is-invalid @enderror ">
                    <option disabled selected>Choisissez</option>
                    @foreach ($situationMatrimoniales as $item)
                        <option value="{{ $item->code_situation_matrimoniale }}">{{ $item->lib_situation_matrimoniale }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Date déclaration</label>
                <input type="date" id="date_heure_declaration" value="{{ date("Y-m-d") }}" name="date_heure_declaration" class="form-control">
            </div>
            <div class="mb-2 col-md-3 d-none">
                <label class="form-label">Statut <span class="text-danger">*</span></label>
                <select id="statut_personne_enfant" name="statut_personne_enfant" class="form-control">
                    <option selected value="VIVANT">Vivant</option>
                    <option value="DECEDE">Décédé</option>
                </select>
            </div>
        </div>
    </section>


    @include("naissance::declaration.parent")
</form>



