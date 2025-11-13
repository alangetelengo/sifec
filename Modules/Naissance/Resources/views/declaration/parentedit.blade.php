<h6>Père</h6>
<section>
    <div class="d-flex justify-content-end align-items-center">
        <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".search-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche du père</button>
    </div>
    <hr>
    <div class="ligne">
        <h4>INFORMATIONS PERSONNELLES</h4>
    </div>
    <div class="row">
        <div class="mb-2 col-md-4">
            <input type="hidden" id="code_pere" value="{{ $dn->pere->code_personne ?? '' }}">
            <span class="error" id="errordatenais"></span>
            <label class="form-label">Nom(s) père <span class="text-danger">*</span></label>
            <input type="text" class="form-control required" name="nom_pere"  placeholder="Nom du père" id="nom_pere" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()" value="{{ $dn->pere->nom ?? '' }}">
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Prénom(s) du père </label>
            <input type="text" class="form-control" placeholder="Prénom du père" id="prenom_pere" onkeyup="verif_lettre(this);" style="text-transform: capitalize" value="{{ $dn->pere->prenom ?? '' }}">
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Date de naissance du père<span class="text-danger">*</span></label>
            <input type="date" max="<?php echo date('Y-m-d', strtotime($jour. ' - 14 years')); ?>" min="<?php echo date('Y-m-d', strtotime($jour. ' - 100 years')); ?>" onchange="compare()"
            name="date_naissance_pere" class="form-control required  @error('date_naissance_pere') is-invalid @enderror " id="date_naissance_pere" value="{{ $dn->pere->date_naissance ?? '' }}">
            <input type="checkbox" id="type_date_naissance_pere" value="ESTIME" name="type_date_naissance_pere"><label for="type_date_naissance_pere">date estimée</label>
        </div>

        <div class="mb-2 col-md-4">
            <label class="form-label">Lieu de naissance père</label>
            <input type="text" name="lieu_naissance_pere" class="form-control d-none" id="lieu_naissance_pere" placeholder="Lieu de naissance" value="{{ $dn->pere->lieu_naissance ?? '' }}">
            <select id="code_localite_pere" class="form-control">
                @foreach ($localites as $localite)
                    <option value="{{ $localite->code_localite }}" {{ (isset($dn->pere->localite) && $dn->pere->localite->code_localite == $localite->code_localite) ? 'selected' : '' }}>{{ $localite->lib_localite }}</option>
                @endforeach
            </select>
        </div>
         <div class="mb-2 col-md-4">
            <label class="form-label">Nationalité du père<span class="text-danger">*</span></label>
            <select id="code_nationalite_pere" name="code_nationalite_pere" class="form-control required  @error('code_nationalite_pere') is-invalid @enderror ">
                @foreach ($nationalites as $nationalite)
                    <option value="{{ $nationalite->code_nationalite }}" {{ (isset($dn->pere->nationalite) && $dn->pere->nationalite->code_nationalite == $nationalite->code_nationalite) ? 'selected' : '' }}>{{ $nationalite->lib_nationalite }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Profession du père<span class="text-danger">*</span></label>
            <select id="profession_pere" class="form-control form-control wide">
                @foreach ($professions as $item)
                    <option value="{{ $item->code_profession }}" {{ (isset($dn->pere->profession) && $dn->pere->profession->code_profession == $item->code_profession) ? 'selected' : '' }}>{{ $item->lib_profession }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-2 col-md-4">
            <label class="form-label">Niveau d'instruction du père</label>
            <select id="niveau_instruction_pere" class="form-control form-control wide">
                @foreach ($instructions as $item)
                    <option value="{{ $item }}" {{ (isset($dn->pere) && $dn->pere->niveau_instruction == $item) ? 'selected' : '' }}>{{ $item }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Type pièce d'identité</label>
            <select id="code_type_document_pere" class="form-control form-control wide">
                @foreach ($typedocuments as $item)
                    <option value="{{ $item->code_type_document }}" {{ (isset($dn->pere->document) && $dn->pere->document->code_type_document == $item->code_type_document) ? 'selected' : '' }}>{{ $item->lib_type_document  }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Numéro pièce d'identité</label>
            <input type="text" id="numero_document_pere" class="form-control form-control wide" placeholder="Numéro du document" onkeyup="this.value=this.value.toUpperCase()" value="{{ $dn->pere->document->numero_document ?? '' }}">
        </div>
        <div class="ligne">
            <h4>ADRESSE</h4>
        </div>
        <div class="row adressepere">
            <div class="mb-2 col-md-3">
                <label class="form-label">Pays<span class="text-danger"></span></label>
                <select id="domicile_pays_pere" class="form-control">
                    @foreach ($countries as $countrie)
                        <option value="{{ $countrie->name }}" {{ $dn->pere->adresses->last()->lib_pays == $countrie->name ? "selected" : "" }}>{{ $countrie->name }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Commune/District du père --}}
            @php
                $selectedVillePere = optional($dn->pere->adresses->last())->code_localite ?? '';
                $villePereExiste = collect($communesDropdown)->keys()->contains($selectedVillePere);
            @endphp
            <div class="mb-2 col-md-3 domicile_ville_pere">
                <label class="form-label">Commune/District<span class="text-danger"></span></label>
                <select class="form-control" id="domicile_ville_pere" name="domicile_ville_pere">
                    @if($selectedVillePere && !$villePereExiste)
                        <option value="{{ $selectedVillePere }}" selected>{{ optional($dn->pere->adresses->last())->lib_ville ?? $selectedVillePere }}</option>
                    @endif
                    @foreach ($communesDropdown as $code => $lib)
                        <option value="{{ $code }}" {{ (isset($communeDeclarant) && $communeDeclarant->code_localite == $code) || $selectedVillePere == $code ? 'selected' : '' }}>{{ $lib }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Arrondissement du père --}}
            @php
                $selectedArrondissementPere = optional($dn->pere->adresses->last())->code_arrondissement_comurbaine ?? '';
                $arrondissementPereExiste = collect($arrondissementsDropdown)->keys()->contains($selectedArrondissementPere);
            @endphp
            <div class="mb-2 col-md-3 domicile_arrondissement_pere">
                <label class="form-label">Arrondissement/Communauté urbaine<span class="text-danger"></span></label>
                <select class="form-control" id="domicile_arrondissement_pere" name="domicile_arrondissement_pere">
                    @if($selectedArrondissementPere && !$arrondissementPereExiste)
                        <option value="{{ $selectedArrondissementPere }}" selected>{{ optional($dn->pere->adresses->last())->lib_localite ?? $selectedArrondissementPere }}</option>
                    @endif
                    @foreach ($arrondissementsDropdown as $code => $lib)
                        <option value="{{ $code }}" {{ (isset($arrondissementDeclarant) && $arrondissementDeclarant->code_localite == $code) || $selectedArrondissementPere == $code ? 'selected' : '' }}>{{ $lib }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Quartier du père --}}
            @php
                $selectedQuartierPere = optional($dn->pere->adresses->last())->code_quartier_village ?? '';
                $quartierPereExiste = collect($quartiersDropdown)->keys()->contains($selectedQuartierPere);
            @endphp
            <div class="mb-2 col-md-3 domicile_quartier_pere">
                <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                <select class="form-control" id="domicile_quartier_pere" name="domicile_quartier_pere">
                    @if($selectedQuartierPere && !$quartierPereExiste)
                        <option value="{{ $selectedQuartierPere }}" selected>{{ optional($dn->pere->adresses->last())->lib_localite ?? $selectedQuartierPere }}</option>
                    @endif
                    @foreach ($quartiersDropdown as $code => $lib)
                        <option value="{{ $code }}" {{ (isset($quartierDeclarant) && $quartierDeclarant->code_localite == $code) || $selectedQuartierPere == $code ? 'selected' : '' }}>{{ $lib }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Type voie<span class="text-danger"></span></label>
                <select class="form-control" id="domicile_typevoie_pere">
                    {{-- <option value="">Choisir</option> --}}
                    <option value="Avenue" {{ (optional($dn->pere->adresses->last())->type_voie ?? '') == 'Avenue' ? 'selected' : '' }}>Avenue</option>
                    <option value="Boulevard" {{ (optional($dn->pere->adresses->last())->type_voie ?? '') == 'Boulevard' ? 'selected' : '' }}>Boulevard</option>
                    <option value="Impasse" {{ (optional($dn->pere->adresses->last())->type_voie ?? '') == 'Impasse' ? 'selected' : '' }}>Impasse</option>
                    <option value="Rue" {{ (optional($dn->pere->adresses->last())->type_voie ?? '') == 'Rue' ? 'selected' : '' }}>Rue</option>
                    <option value="Autre" {{ (optional($dn->pere->adresses->last())->type_voie ?? '') == 'Autre' ? 'selected' : '' }}>Autre</option>
                </select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">N° voie<span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_numero_pere" placeholder="N° voie" value="{{ optional($dn->pere->adresses->last())->numero_rue ?? '' }}">
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Nom voie<span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_nomvoie_pere" placeholder="Nom voie" style="text-transform: capitalize" value="{{ optional($dn->pere->adresses->last())->nom_voie ?? '' }}">
            </div>
        </div>
        <div class="ligne">
            <h4>CONTACTS</h4>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label class="form-label">Indicatif<span class="text-danger">*</span></label>
                <select name="code_pays_pere" id="code_pays_pere" class="form-control">
                    @forelse ($countries as $code)
                        <option value="{{ $code->dial_code }}" {{ (optional($dn->pere->adresses->last())->lib_pays ?? '') == $code->name ? 'selected' : '' }}>({{ $code->dial_code }}) {{ $code->name }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Téléphone père</label>
                <input type="number" min="0" minlength="9" maxlength="15" id="telephone_pere" name="telephone_pere" class="form-control" placeholder="Téléphone père" value="{{ $dn->pere->telephone ?? '' }}">
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Email</label>
                <input type="email" id="email_pere" class="form-control" name="email_pere" placeholder="Email père" value="{{ $dn->pere->user->email ?? '' }}">
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
</section>
<!-- Step 3 -->
<h6>Mère</h6>
<section>
    <div class="d-flex justify-content-end align-items-center">
        <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".mere-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche de la mère</button>
    </div>
    <hr>
    <div class="ligne">
            <h4>INFORMATIONS PERSONNELLES</h4>
        </div>
    <div class="row">
        <div class="mb-2 col-md-4">
            <input type="hidden" id="code_mere" value="{{ $dn->mere->code_personne }}">
            <label class="form-label">Nom(s) Mère <span class="text-danger">*</span></label>
            <input type="text" class="form-control required" name="nom_mere"  placeholder="Nom Mère" id="nom_mere" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()" value="{{ $dn->mere->nom }}">
        </div>

        <div class="mb-2 col-md-4">
            <label class="form-label">Prénom(s) Mère </label>
            <input type="text" class="form-control" placeholder="Prénom du Mère" id="prenom_mere" onkeyup="verif_lettre(this);" style="text-transform: capitalize" value="{{ $dn->mere->prenom }}">

        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Date de naissance Mère<span class="text-danger">*</span></label>
            <input type="date" max="<?php echo date('Y-m-d', strtotime($jour. ' - 14 years')); ?>" min="<?php echo date('Y-m-d', strtotime($jour. ' - 100 years')); ?>" onchange="compare()"
            name="date_naissance_mere" class="form-control required  @error('date_naissance_mere') is-invalid @enderror " id="date_naissance_mere" value="{{ $dn->mere->date_naissance }}">
            <input type="checkbox" id="type_date_naissance_mere" value="ESTIME" name="type_date_naissance_mere"><label for="type_date_naissance_mere">date estimée</label>
        </div>
    </div>
    <div class="row">
        <div class="mb-2 col-md-4">
            <label class="form-label">Lieu de naissance Mère</label>
            <input type="text" name="lieu_naissance_mere" class="form-control d-none" id="lieu_naissance_mere" placeholder="Lieu de naissance" value="{{ $dn->mere->lieu_naissance }}">
            <select id="code_localite_mere" class="form-control">
                @foreach ($localites as $localite)
                    <option value="{{ $localite->code_localite }}" {{ $dn->mere->code_localite == $localite->code_localite ? "selected" : "" }}>{{ $localite->lib_localite }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Nationalité Mère<span class="text-danger">*</span></label>
            <select id="code_nationalite_mere" name="code_nationalite_mere" class="form-control required  @error('code_nationalite_mere') is-invalid @enderror ">
                @foreach ($nationalites as $nationalite)
                    <option value="{{ $nationalite->code_nationalite }}"  {{ $dn->mere->nationalite->code_nationalite == $nationalite->code_nationalite ? "selected" : "" }}>{{ $nationalite->lib_nationalite }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Profession Mère<span class="text-danger">*</span></label>
            <select id="profession_mere" class="form-control form-control wide">
                @foreach ($professions as $item)
                    <option value="{{ $item->code_profession }}"  {{ $dn->mere->profession->code_profession == $item->code_profession ? "selected" : "" }}>{{ $item->lib_profession }}</option>
                @endforeach
            </select>
        </div>

    </div>
    <div class="row">
        <div class="mb-2 col-md-4">
            <label class="form-label">Niveau d'instruction de la Mère</label>
            <select id="niveau_instruction_mere" class="form-control form-control wide">
                @foreach ($instructions as $item)
                    <option value="{{ $item }}" {{ $dn->mere->niveau_instruction == $item ? "selected" : "" }}>{{ $item }}</option>
                @endforeach
            </select>
        </div>
         <div class="mb-2 col-md-4">
            <label class="form-label">Type pièce d'identité</label>
            <select id="code_type_document_mere" class="form-control form-control wide">
                @foreach ($typedocuments as $item)
                    <option value="{{ $item->code_type_document }}" {{ $dn->mere->document->code_type_document == $item->code_type_document ? "selected" : "" }}>{{ $item->lib_type_document  }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Numéro pièce d'identité</label>
            <input type="text" id="numero_document_mere" class="form-control form-control wide" placeholder="Numéro du document" onkeyup="this.value=this.value.toUpperCase()" value="{{ $dn->mere->document->numero_document }}">
        </div>
    </div>
    <div class="ligne">
        <h4>ADRESSE
            ( <span style="color:red!important">
            <label class="radio-inline mr-3">Même adresse que le père ?</label></span>
            <label class="radio-inline mr-3"><input type="radio" id="sameadress" name="adresse" value="1" {{ $dn->pere->adresse == $dn->mere->adresse ? "checked" : ""}}> OUI</label>
            <label class="radio-inline mr-3"><input type="radio" id="otheradress" name="adresse" value="1" {{ $dn->pere->adresse != $dn->mere->adresse ? "checked" : ""}}> NON</label>)
         </h4>
    </div>
    <div class="row adressemere">
        <div class="mb-2 col-md-3">
            <label class="form-label">Pays<span class="text-danger"></span></label>
            <select id="domicile_pays_mere" class="form-control">
                @foreach ($countries as $countrie)
                    <option value="{{ $countrie->name }}" {{ $dn->mere->adresses->last()->lib_pays == $countrie->name ? "selected" : "" }}>{{ $countrie->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-3 domicile_ville_mere">
            {{-- Commune/District de la mère --}}
            @php
                $selectedVilleMere = optional($dn->mere->adresses->last())->code_localite ?? '';
                $villeMereExiste = collect($communesDropdown)->keys()->contains($selectedVilleMere);
            @endphp
            <label class="form-label">Commune/District<span class="text-danger"></span></label>
            <select class="form-control" id="domicile_ville_mere" name="domicile_ville_mere">
                @if($selectedVilleMere && !$villeMereExiste)
                    <option value="{{ $selectedVilleMere }}" selected>{{ optional($dn->mere->adresses->last())->lib_ville ?? $selectedVilleMere }}</option>
                @endif
                @foreach ($communesDropdown as $code => $lib)
                    <option value="{{ $code }}" {{ (isset($communeMere) && $communeMere->code_localite == $code) || $selectedVilleMere == $code ? 'selected' : '' }}>{{ $lib }}</option>
                @endforeach
            </select>
        </div>
        @if($dn->pere->adresses->last()->lib_pays != "Congo")
        <div class="mb-2 col-md-3 autredomicile_ville_mere">
            <label class="form-label">Ville<span class="text-danger"></span></label>
            <input type="text" id="autredomicile_ville_mere" class="form-control form-control wide" placeholder="Libellé de la ville" onkeyup="this.value=this.value.toUpperCase()" value="{{ $dn->mere->adresses->last()->lib_ville }}">
        </div>
        @endif

        <div class="mb-2 col-md-3 domicile_arrondissement_mere">
            {{-- Arrondissement de la mère --}}
            @php
                $selectedArrondissementMere = optional($dn->mere->adresses->last())->code_arrondissement_comurbaine ?? '';
                $arrondissementMereExiste = collect($arrondissementsDropdown)->keys()->contains($selectedArrondissementMere);
            @endphp
            <label class="form-label">Arrondissement/Communauté urbaine<span class="text-danger"></span></label>
            <select class="form-control" id="domicile_arrondissement_mere" name="domicile_arrondissement_mere">
                @if($selectedArrondissementMere && !$arrondissementMereExiste)
                    <option value="{{ $selectedArrondissementMere }}" selected>{{ optional($dn->mere->adresses->last())->lib_localite ?? $selectedArrondissementMere }}</option>
                @endif
                @foreach ($arrondissementsDropdown as $code => $lib)
                    <option value="{{ $code }}" {{ (isset($arrondissementMere) && $arrondissementMere->code_localite == $code) || $selectedArrondissementMere == $code ? 'selected' : '' }}>{{ $lib }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-2 col-md-3 domicile_quartier_mere">
            {{-- Quartier de la mère --}}
            @php
                $selectedQuartierMere = optional($dn->mere->adresses->last())->code_quartier_village ?? '';
                $quartierMereExiste = collect($quartiersDropdown)->keys()->contains($selectedQuartierMere);
            @endphp
            <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
            <select class="form-control" id="domicile_quartier_mere" name="domicile_quartier_mere">
                @if($selectedQuartierMere && !$quartierMereExiste)
                    <option value="{{ $selectedQuartierMere }}" selected>{{ optional($dn->mere->adresses->last())->lib_localite ?? $selectedQuartierMere }}</option>
                @endif
                @foreach ($quartiersDropdown as $code => $lib)
                    <option value="{{ $code }}" {{ (isset($quartierMere) && $quartierMere->code_localite == $code) || $selectedQuartierMere == $code ? 'selected' : '' }}>{{ $lib }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-3">
            <label class="form-label">Type voie<span class="text-danger"></span></label>
            <select class="form-control" id="domicile_typevoie_mere">
                <option value="Avenue" {{ $dn->mere->adresses->last()->type_voie == "Avenue" ? "selected" : "" }}>Avenue</option>
                    <option value="Boulevard" {{ $dn->mere->adresses->last()->type_voie == "Boulevard" ? "selected" : "" }}>Boulevard</option>
                    <option value="Impasse" {{ $dn->mere->adresses->last()->type_voie == "Impasse" ? "selected" : "" }}>Impasse</option>
                    <option value="Rue" {{ $dn->mere->adresses->last()->type_voie == "Rue" ? "selected" : "" }}>Rue</option>
                    <option value="Autre" {{ $dn->mere->adresses->last()->type_voie == "Autre" ? "selected" : "" }}>Autre</option>
            </select>
        </div>
        <div class="mb-2 col-md-3">
            <label class="form-label">N° voie<span class="text-danger"></span></label>
            <input type="text" class="form-control" id="domicile_numero_mere" placeholder="N° voie" value="{{ $dn->mere->adresses->last()->numero_rue}}">
        </div>
        <div class="mb-2 col-md-3">
            <label class="form-label">Nom voie<span class="text-danger"></span></label>
            <input type="text" class="form-control" id="domicile_nomvoie_mere" placeholder="Nom voie" style="text-transform: capitalize" value="{{ $dn->mere->adresses->last()->nom_voie}}">
        </div>
    </div>
    <div class="ligne">
        <h4>CONTACTS</h4>
    </div>
    <div class="row">
        <div class="col-md-3">
            <label class="form-label">Indicatif<span class="text-danger">*</span></label>
            <select name="code_pays_mere" id="code_pays_mere" class="form-control">
                @forelse ($countries as $code)
                    <option value="{{ $code->dial_code }}" {{ $dn->mere->adresses->last()->lib_pays == $code->name ? "selected" : "" }}>({{ $code->dial_code }}) {{ $code->name }}</option>
                @empty
                @endforelse
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Téléphone mère</label>
            <input type="number" min="0" minlength="9" maxlength="15" id="telephone_mere" name="telephone_mere" class="form-control @error('telephone_mere') is-invalid @enderror " placeholder="Téléphone mère" value="{{ $dn->mere->telephone }}">
        </div>
        @if($typeDeclaration == "FICHE DE MATERNITE")
        <div class="col-md-3">
            <label class="form-label">Téléphone tuteur</label>
            <input type="number" min="0" minlength="9" maxlength="15" id="telephone_parent" name="telephone_parent" class="form-control @error('telephone_mere') is-invalid @enderror " placeholder="Téléphone mère" value="{{ $dn->mere->telephone }}">
        </div>
        @endif
        <div class="mb-2 col-md-3">
            <label class="form-label">Email</label>
            <input type="email" id="email_mere" class="form-control" name="email_mere" placeholder="Email mère" value="{{ $dn->mere->user !='' ? $dn->mere->user->email : "" }}">
        </div>

        <div class="ligne">
            <h4>AUTRES INFORMATIONS</h4>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Nombre d'enfants (y compris le sujet)<span class="text-danger">*</span></label>
            <input type="number" name="nombre_enfant" min="1" class="form-control" id="nombre_enfant" value="{{ $dn->nombre_enfant }}">
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

@include('naissance::declaration.declarantedit')
