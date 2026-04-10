<h6>Père</h6>
<section>
    <div class="d-flex justify-content-end align-items-center">
        <button type="button" id="clear_pere" class="btn btn-danger  text-white" ></i> Vider </button>
        <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".search-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche du père</button>
    </div>
    <hr>
    <div class="ligne">
        <h4>INFORMATIONS SUR L'IDENTITE DU PERE</h4>
    </div>
    <div class="row">
        <div class="mb-2 col-md-4">
            <input type="hidden" id="code_pere">
            <span class="error" id="errordatenais"></span>
            <label class="form-label">Nom(s) père <span class="text-danger">*</span></label>
            <input type="text" class="form-control required  @error('nom_pere') is-invalid @enderror " name="nom_pere"  placeholder="Nom du père" id="nom_pere" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()">
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Prénom(s) du père </label>
            <input type="text" class="form-control" placeholder="Prénom du père" id="prenom_pere" onkeyup="verif_lettre(this);" style="text-transform: capitalize">
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Date de naissance de la mère<span class="text-danger">*</span></label>
            <input type="date" max="<?php $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 12 years'));?>"
            class="form-control @error('date_naissance_pere') is-invalid @enderror" id="date_naissance_pere" name="date_naissance_pere">
            @error("date_naissance_pere")
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
            <input type="checkbox" id="type_date_naissance_pere" value="ESTIME" name="type_date_naissance_pere"><label for="type_date_naissance_mere">date estimée</label>

        </div>

        <div class="mb-2 col-md-4">
            <label class="form-label">Lieu de naissance père</label>
            <input type="text" name="lieu_naissance_pere" class="form-control d-none" id="lieu_naissance_pere" placeholder="Lieu de naissance">
            <select id="code_localite_pere" class="form-control">
                <option disabled selected>Choisissez</option>
                @foreach ($localites as $localite)
                    <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-4 autrelieunaissancepere d-none">
            <label class="form-label">Lieu de naissance<span class="text-danger">*</span></label>
            <select id="etranger_lieu_naissance_pere" class="form-control">
                    <option value="">Choisissez</option>
                @foreach ($countries as $countrie)
                    @if($countrie->name != 'Congo')
                    <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
         <div class="mb-2 col-md-4">
            <label class="form-label">Nationalité du père<span class="text-danger">*</span></label>
            <select id="code_nationalite_pere" name="code_nationalite_pere" class="form-control required  @error('code_nationalite_pere') is-invalid @enderror ">
                    {{-- <option disabled selected>Choisissez</option> --}}
                @foreach ($nationalites as $nationalite)
                    <option value="{{ $nationalite->code_nationalite }}">{{ $nationalite->lib_nationalite }}</option>
                @endforeach
            </select>
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
            <label class="form-label">Niveau d'instruction du père</label>
            <select id="niveau_instruction_pere" class="form-control form-control wide">
                    <option disabled selected>Choisissez</option>
                @foreach ($instructions as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
            </select>
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
            <input type="text" id="numero_document_pere" class="form-control form-control wide" placeholder="Numéro du document" onkeyup="this.value=this.value.toUpperCase()">
        </div>


        <div class="ligne">
            <h4>ADRESSE</h4>
        </div>
        <div class="row">
            <div class="mb-2 col-md-3">
                <label class="form-label">Pays<span class="text-danger"></span></label>
                <select id="domicile_pays_pere" class="form-control required">
                    {{-- <option value="">Choisissez</option> --}}
                    @foreach ($countries as $countrie)
                        <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3 domicile_ville_pere">
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
                        {{-- <option value="">Selectionnez</option> --}}
                        @forelse ($countries as $code)
                            <option value="{{ $code->dial_code }}">({{ $code->dial_code }}) {{ $code->name }}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Téléphone père</label>
                    <input type="number" min="0" minlength="9" maxlength="10" id="telephone_pere" name="telephone_pere" class="form-control @error('telephone_pere') is-invalid @enderror " placeholder="Téléphone père">
                </div>

                <div class="mb-2 col-md-4">
                    <label class="form-label">E-mail personnel</label>
                    <input type="email" id="email_pere" class="form-control" name="email_pere" placeholder="E-mail personnel du père" autocomplete="email">
                </div>
                <div class="mb-2 col-md-4">
                    <label class="form-label">E-mail professionnel <span class="text-muted small">(optionnel)</span></label>
                    <input type="email" id="email_professionnel_pere" class="form-control" name="email_professionnel_pere" placeholder="E-mail professionnel du père" autocomplete="email">
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
            <h4>INFORMATIONS SUR L'IDENTITE DU MERE</h4>
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
            <label class="form-label">Date de naissance de la mère<span class="text-danger">*</span></label>
            <input type="date" max="<?php $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 12 years'));?>"
            class="form-control @error('date_naissance_mere') is-invalid @enderror" id="date_naissance_mere" name="date_naissance_mere">
            @error("date_naissance_mere")
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
            <input type="checkbox" id="type_date_naissance_mere" value="ESTIME" name="type_date_naissance_mere"><label for="type_date_naissance_mere">date estimée</label>

        </div>
    {{-- </div>
    <div class="row"> --}}
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
        <div class="mb-2 col-md-4 autrelieunaissancemere d-none">
            <label class="form-label">Lieu de naissance<span class="text-danger">*</span></label>
            <select id="etranger_lieu_naissance_mere" class="form-control">
                    <option value="">Choisissez</option>
                @foreach ($countries as $countrie)
                    @if($countrie->name != 'Congo')
                    <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Nationalité Mère<span class="text-danger">*</span></label>
            <select id="code_nationalite_mere" name="code_nationalite_mere" class="form-control required  @error('code_nationalite_mere') is-invalid @enderror ">
                    {{-- <option disabled selected>Choisissez</option> --}}
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
                {{-- <option value="">Choisissez</option> --}}
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
                {{-- <option value="">Selectionnez</option> --}}
                @forelse ($countries as $code)
                    <option value="{{ $code->dial_code }}">({{ $code->dial_code }}) {{ $code->name }}</option>
                @empty
                @endforelse
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Téléphone père</label>
            <input type="number" min="0" minlength="9" maxlength="10" id="telephone_mere" name="telephone_mere" class="form-control @error('telephone_mere') is-invalid @enderror " placeholder="Téléphone père">
        </div>

        <div class="mb-2 col-md-4">
            <label class="form-label">E-mail personnel</label>
            <input type="email" id="email_mere" class="form-control" name="email_mere" placeholder="E-mail personnel de la mère" autocomplete="email">
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">E-mail professionnel <span class="text-muted small">(optionnel)</span></label>
            <input type="email" id="email_professionnel_mere" class="form-control" name="email_professionnel_mere" placeholder="E-mail professionnel de la mère" autocomplete="email">
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
