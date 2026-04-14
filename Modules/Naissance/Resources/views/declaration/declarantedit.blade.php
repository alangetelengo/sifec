<h6>Déclarant</h6>
<section>
    <div class="d-flex justify-content-end align-items-center" id="search_declarant">
        <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".declarant-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche du déclarant</button>
    </div>
    <hr>

    <div class="row">
        <div class="mb-2 col-sm-4" id="hide_pere">
            <label for="dewey">Père</label>
            <input type="radio" id="peredeclarant" name="autredeclarant" value="pere" {{ $dn->declarant->personne_string  ==  $dn->pere->personne_string ? "checked" : ""}}>
        </div>


        <div class="mb-2 col-sm-4" id="hide_mere">
            <label for="dewey">Mère</label>
            <input type="radio" id="meredeclarant" name="autredeclarant" value="mere" {{ $dn->declarant->personne_string  ==  $dn->mere->personne_string ? "checked" : ""}}>
        </div>

        <div class="mb-2 col-sm-4">
            <label for="dewey">Autre</label>
            <input type="radio" id="autredeclarant" name="autredeclarant"  value="autre" {{ ($dn->declarant->personne_string  !=  $dn->pere->personne_string ) && ($dn->declarant->personne_string  !=  $dn->mere->personne_string) ? "checked" : ""}}>
        </div>

        <div class="ligne">
            <h4>INFORMATIONS PERSONNELLES</h4>
        </div>
        <div class="mb-2 col-md-4">
            <input type="hidden" id="code_declarant" value="{{ $dn->declarant->code_personne ?? '' }}">
            <label class="form-label">Nom(s) déclarant <span class="text-danger">*</span></label>
            <input type="text" class="form-control required"  placeholder="Nom du déclarant" id="nom_declarant" name="nom_declarant" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()" value="{{ $dn->declarant->nom ?? '' }}">
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Prénom(s) du déclarant </label>
            <input type="text" class="form-control" placeholder="Prénom du déclarant" id="prenom_declarant" name="prenom_declarant" onkeyup="verif_lettre(this);" style="text-transform: capitalize" value="{{ $dn->declarant->prenom ?? '' }}">
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Sexe du déclarant<span class="text-danger">*</span></label>
            <select id="sexe_declarant" name="sexe_declarant" class="form-control required  @error('sexe_declarant') is-invalid @enderror ">
                <option value="">Selectionner</option>
                <option value="M" {{ (isset($dn->declarant) && $dn->declarant->sexe == "M") ? "selected" : '' }}>Masculin</option>
                <option value="F" {{ (isset($dn->declarant) && $dn->declarant->sexe == "F") ? "selected" : '' }}>Féminin</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="mb-2 col-md-4">
            <label class="form-label">Date de naissance du déclarant<span class="text-danger">*</span></label>
            <input type="date" name="date_naissance_declarant" max="<?php $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 18 years'));?>" min="<?php echo date('Y-m-d', strtotime($jour. ' - 100 years')); ?>"
            class="form-control required  @error('date_naissance_declarant') is-invalid @enderror " id="date_naissance_declarant" value="{{ $dn->declarant->date_naissance ?? '' }}">
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Lieu de naissance </label>
            <input type="text" class="form-control d-none" name="lieu_naissance_declarant" id="lieu_naissance_declarant" placeholder="Lieu de naissance" value="{{ $dn->declarant->lieu_naissance ?? '' }}">
            <select id="code_localite_declarant" class="form-control">
                <option disabled selected>Choisissez</option>
                @foreach ($localites ?? [] as $localite)
                    <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                @endforeach
            </select>
        </div>
         <div class="mb-2 col-md-4">
            <label class="form-label">Nationalité du déclarant<span class="text-danger">*</span></label>
            <select id="code_nationalite_declarant" name="code_nationalite_declarant" class="form-control required  @error('code_nationalite_declarant') is-invalid @enderror ">
                    <option selected disabled>Choisissez</option>
                @foreach ($nationalites ?? [] as $nationalite)
                    <option value="{{ $nationalite->code_nationalite }}" {{ (isset($dn->declarant->nationalite) && $dn->declarant->nationalite->code_nationalite == $nationalite->code_nationalite) ? 'selected' : '' }}>{{ $nationalite->lib_nationalite }}</option>
                @endforeach

            </select>
        </div>
    </div>
    <div class="row">
       <div class="mb-2 col-md-4">
            <label class="form-label">Filiation <span class="text-danger">*</span></label>
            <select id="filiation" name="filiation" class="form-control required  @error('filiation') is-invalid @enderror ">
                    <option>Choisissez</option>
                    @foreach ($filiations ?? [] as $item)
                        <option class="{{$item->code_filiation }}" value="{{$item->code_filiation }}" {{ (isset($dn->declarant) && $dn->declarant->filiation == $item->code_filiation) ? 'selected' : '' }}>{{ $item->lib_filiation }}</option>
                    @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Profession du déclarant</label>
            <select id="profession_declarant" name="profession_declarant" class="form-control required  @error('profession_declarant') is-invalid @enderror ">
                    <option selected disabled>Choisissez</option>
                @foreach ($professions ?? [] as $item)
                    <option value="{{ $item->code_profession }}" {{ (isset($dn->declarant) && $dn->declarant->profession == $item->code_profession) ? 'selected' : '' }}>{{ $item->lib_profession }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Niveau d'instruction du déclarant</label>
            <select id="niveau_instruction_declarant" name="niveau_instruction_declarant" class="form-control form-control wide">
                    <option disabled selected>Choisissez</option>
                @foreach ($instructions ?? [] as $item)
                    <option value="{{ $item }}" {{ (isset($dn->declarant) && $dn->declarant->niveau_instruction == $item) ? 'selected' : '' }}>{{ $item }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Type pièce d'identité</label>
            <select id="code_type_document_declarant" name="code_type_document_declarant" class="form-control required ">
                    <option>Choisissez</option>
                @foreach ($typedocuments ?? [] as $item)
                    <option value="{{ $item->code_type_document }}" {{ (isset($dn->declarant) && $dn->declarant->document && $dn->declarant->document->code_type_document == $item->code_type_document) ? 'selected' : '' }}>{{ $item->lib_type_document  }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Numéro pièce d'identité</label>
            <input type="text" id="numero_document_declarant" name="numero_document_declarant" class="form-control required" placeholder="Numéro du document" onkeyup="this.value=this.value.toUpperCase()" value="{{ $dn->declarant->document->numero_document ?? '' }}">
        </div>

        <div class="mb-2 col-md-4">
            <label class="form-label">Statut <span class="text-danger">*</span></label>
            <select id="statut_personne_declarant" name="statut_personne_declarant" required class="form-control required ">
                <option value="VIVANT">Vivant(e)</option>
            </select>
        </div>
        <div class="ligne">
            <h4>ADRESSE</h4>
        </div>
        <div class="row">
            <div class="mb-2 col-md-3">
                <label class="form-label">Pays<span class="text-danger"></span></label>
                <select id="domicile_pays_declarant" class="form-control required">
                    <option value="">Choisissez</option>
                    @foreach ($countries ?? [] as $countrie)
                        <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3 domicile_ville_declarant">
                <label class="form-label">Commune/District<span class="text-danger"></span></label>
                @php
                    $selectedVilleDeclarant = optional($dn->declarant->adresses->last())->code_localite ?? '';
                    $villeDeclarantExiste = collect($localites ?? [])->pluck('code_localite')->contains($selectedVilleDeclarant);
                @endphp
                <select class="form-control" id="domicile_ville_declarant">
                    @if($selectedVilleDeclarant && !$villeDeclarantExiste)
                        <option value="{{ $selectedVilleDeclarant }}" selected>{{ optional($dn->declarant->adresses->last())->lib_ville ?? $selectedVilleDeclarant }}</option>
                    @endif
                    @foreach ($localites ?? [] as $localite)
                        <option value="{{ $localite->code_localite }}" {{ $selectedVilleDeclarant == $localite->code_localite ? 'selected' : '' }}>{{ $localite->lib_localite }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3 autredomicile_ville_declarant d-none">
                <label class="form-label">Ville<span class="text-danger"></span></label>
                <input type="text" id="autredomicile_ville_declarant" class="form-control form-control wide" placeholder="Libellé de la ville" onkeyup="this.value=this.value.toUpperCase()">
            </div>

           {{-- Arrondissement du déclarant --}}
            @php
                $selectedArrondissementDeclarant = optional($dn->declarant->adresses->last())->code_arrondissement_comurbaine ?? '';
                $arrondissementDeclarantExiste = collect($arrondissementsDropdown)->keys()->contains($selectedArrondissementDeclarant);
            @endphp
            <div class="mb-2 col-md-3 domicile_arrondissement_declarant">
                <label class="form-label">Arrondissement/Communauté urbaine<span class="text-danger"></span></label>
                <select class="form-control" id="domicile_arrondissement_declarant" name="domicile_arrondissement_declarant">
                    @if($selectedArrondissementDeclarant && !$arrondissementDeclarantExiste)
                        <option value="{{ $selectedArrondissementDeclarant }}" selected>{{ optional($dn->declarant->adresses->last())->lib_localite ?? $selectedArrondissementDeclarant }}</option>
                    @endif
                    @foreach ($arrondissementsDropdown as $code => $lib)
                        <option value="{{ $code }}" {{ (isset($arrondissementDeclarant) && $arrondissementDeclarant->code_localite == $code) || $selectedArrondissementDeclarant == $code ? 'selected' : '' }}>{{ $lib }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Quartier du déclarant --}}
            @php
                $selectedQuartierDeclarant = optional($dn->declarant->adresses->last())->code_quartier_village ?? '';
                $quartierDeclarantExiste = collect($quartiersDropdown)->keys()->contains($selectedQuartierDeclarant);
            @endphp
            <div class="mb-2 col-md-3 domicile_quartier_declarant">
                <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                <select class="form-control" id="domicile_quartier_declarant" name="domicile_quartier_declarant">
                    @if($selectedQuartierDeclarant && !$quartierDeclarantExiste)
                        <option value="{{ $selectedQuartierDeclarant }}" selected>{{ optional($dn->declarant->adresses->last())->lib_localite ?? $selectedQuartierDeclarant }}</option>
                    @endif
                    @foreach ($quartiersDropdown as $code => $lib)
                        <option value="{{ $code }}" {{ (isset($quartierDeclarant) && $quartierDeclarant->code_localite == $code) || $selectedQuartierDeclarant == $code ? 'selected' : '' }}>{{ $lib }}</option>
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
                        <option value="">Selectionnez</option>
                        @forelse ($countries ?? [] as $code)
                            <option value="{{ $code->dial_code }}">({{ $code->dial_code }}) {{ $code->name }}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Téléphone père</label>
                    <input type="number" min="0" minlength="9" maxlength="15" id="telephone_declarant" name="telephone_declarant" class="form-control @error('telephone_declarant') is-invalid @enderror " placeholder="Téléphone déclarant">
                </div>
                <div class="mb-2 col-md-4">
                    <label class="form-label">E-mail</label>
                    <input type="email" id="email_declarant" class="form-control" name="email_declarant" placeholder="E-mail du déclarant" autocomplete="email">
                </div>
            </div>
        </div>
    </div>
</section>
