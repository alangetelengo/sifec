<h6>Adoptant</h6>
<section>
    <div class="d-flex justify-content-end align-items-center" id="search_adoptant">
        <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".adoptant-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche de l'adoptant</button>
    </div>
    <hr>

    <div class="row">
        <div class="ligne">
            <h4>INFORMATIONS PERSONNELLES</h4>
        </div>
        <div class="mb-2 col-md-4">
            <input type="hidden" id="code_adoptant" value="{{ $dn->declarant->code_personne }}">
            <label class="form-label">Nom(s) de l'adoptant <span class="text-danger">*</span></label>
            <input type="text" class="form-control required"  placeholder="Nom de l'adoptant" id="nom_adoptant" name="nom_adoptant" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()" value="{{ $dn->declarant->nom }}">
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Prénom(s) de l'adoptant </label>
            <input type="text" class="form-control" placeholder="Prénom de l'adoptant" id="prenom_adoptant" name="prenom_adoptant" onkeyup="verif_lettre(this);" style="text-transform: capitalize" value="{{ $dn->declarant->prenom }}">
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Sexe de l'adoptant<span class="text-danger">*</span></label>
            <select id="sexe_adoptant" name="sexe_adoptant" class="form-control required  @error('sexe_adoptant') is-invalid @enderror ">
                <option value="">Selectionner</option>
                <option value="M">Masculin</option>
                <option value="F">Féminin</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="mb-2 col-md-4">
            <label class="form-label">Date de naissance de l'adoptant<span class="text-danger">*</span></label>
            <input type="date" name="date_naissance_adoptant" max="<?php $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 18 years'));?>" min="<?php echo date('Y-m-d', strtotime($jour. ' - 100 years')); ?>"
            class="form-control required  @error('date_naissance_adoptant') is-invalid @enderror " id="date_naissance_adoptant">
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Lieu de naissance </label>
            <input type="text" class="form-control d-none" name="lieu_naissance_adoptant" id="lieu_naissance_adoptant" placeholder="Lieu de naissance">
            <select id="code_localite_adoptant" class="form-control">
                <option disabled selected>Choisissez</option>
                @foreach ($localites as $localite)
                    <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                @endforeach
            </select>
        </div>
         <div class="mb-2 col-md-4">
            <label class="form-label">Nationalité de l'adoptant<span class="text-danger">*</span></label>
            <select id="code_nationalite_adoptant" name="code_nationalite_adoptant" class="form-control required  @error('code_nationalite_adoptant') is-invalid @enderror ">
                    {{-- <option selected disabled>Choisissez</option> --}}
                @foreach ($nationalites as $nationalite)
                    <option value="{{ $nationalite->code_nationalite }}">{{ $nationalite->lib_nationalite }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
       <div class="mb-2 col-md-4">
            <label class="form-label">Filiation <span class="text-danger">*</span></label>
            <select id="filiation" name="filiation" class="form-control required  @error('filiation') is-invalid @enderror ">
                    <option>Choisissez</option>
                    @foreach ($filiations as $item)
                        <option value="{{$item->code_filiation }}">{{ $item->lib_filiation }}</option>
                    @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Profession de l'adoptant</label>
            <select id="profession_adoptant" name="profession_adoptant" class="form-control required  @error('profession_adoptant') is-invalid @enderror ">
                    <option selected disabled>Choisissez</option>
                @foreach ($professions as $item)
                    <option value="{{ $item->code_profession }}">{{ $item->lib_profession }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Niveau d'instruction de l'adoptant</label>
            <select id="niveau_instruction_adoptant" name="niveau_instruction_adoptant" class="form-control form-control wide">
                    <option disabled selected>Choisissez</option>
                @foreach ($instructions as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Type pièce d'identité</label>
            <select id="code_type_document_adoptant" name="code_type_document_adoptant" class="form-control required ">
                    <option>Choisissez</option>
                @foreach ($typedocuments as $item)
                    <option value="{{ $item->code_type_document }}">{{ $item->lib_type_document  }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Numéro pièce d'identité</label>
            <input type="text" id="numero_document_adoptant" name="numero_document_adoptant" class="form-control required" placeholder="Numéro du document" onkeyup="this.value=this.value.toUpperCase()">
        </div>

        <div class="mb-2 col-md-4">
            <label class="form-label">Statut <span class="text-danger">*</span></label>
            <select id="statut_personne_adoptant" name="statut_personne_adoptant" required class="form-control required ">
                <option value="VIVANT">Vivant(e)</option>
            </select>
        </div>
        <div class="ligne">
            <h4>ADRESSE</h4>
        </div>
        <div class="row">
            <div class="mb-2 col-md-3">
                <label class="form-label">Pays<span class="text-danger"></span></label>
                <select id="domicile_pays_adoptant" class="form-control required">
                    {{-- <option value="">Choisissez</option> --}}
                    @foreach ($countries as $countrie)
                        <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3 domicile_ville_adoptant">
                <label class="form-label">Commune/District<span class="text-danger"></span></label>
                <select class="form-control" id="domicile_ville_adoptant">
                    <option value="">Choisir</option>
                    @foreach ($localites as $localite)
                        <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3 autredomicile_ville_adoptant d-none">
                <label class="form-label">Ville<span class="text-danger"></span></label>
                <input type="text" id="autredomicile_ville_adoptant" class="form-control form-control wide" placeholder="Libellé de la ville" onkeyup="this.value=this.value.toUpperCase()">
            </div>

            <div class="mb-2 col-md-3 domicile_arrondissement_adoptant">
                <label class="form-label">Arr/Com. Urb<span class="text-danger"></span></label>
                <select class="form-control" id="domicile_arrondissement_adoptant">
                    <option value="">Choisir</option>
                    @foreach ($arrondissement as $localite)
                        <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2 col-md-3 domicile_quartier_adoptant">
                <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                <select class="form-control" id="domicile_quartier_adoptant">
                    <option value="">Choisir</option>
                    @foreach ($quartierVillages as $localite)
                        <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Type voie<span class="text-danger"></span></label>
                <select class="form-control" id="domicile_typevoie_adoptant">
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
                <input type="text" class="form-control" id="domicile_numero_adoptant" placeholder="N° voie">
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Nom voie<span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_nomvoie_adoptant" placeholder="Nom voie" style="text-transform: capitalize">
            </div>
            <div class="ligne">
                <h4>CONTACTS</h4>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Indicatif<span class="text-danger">*</span></label>
                    <select name="code_pays_adoptant" id="code_pays_adoptant" class="form-control">
                        {{-- <option value="">Selectionnez</option> --}}
                        @forelse ($countries as $code)
                            <option value="{{ $code->dial_code }}">({{ $code->dial_code }}) {{ $code->name }}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Téléphone de l'adoptant</label>
                    <input type="number" min="0" minlength="9" maxlength="15" id="telephone_adoptant" name="telephone_adoptant" class="form-control @error('telephone_adoptant') is-invalid @enderror " placeholder="Téléphone de l'adoptant">
                </div>
                <div class="mb-2 col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" id="email_adoptant" class="form-control" name="email_adoptant" placeholder="Email de l'adoptant">
                </div>
            </div>
            <div class="ligne">
                <h4>INFORMATIONS DU JUGEMENT</h4>
            </div>
            <div class="row">
                <div class="mb-2 col-md-3">
                    <label class="form-label">Numéro d'acte de naissance </label>
                    <input type="text" id="niupp" class="form-control" value="{{ $dn->acte->niupp }}" readonly>
                </div>
                <div class="mb-2 col-md-2">
                    <label class="form-label">Numéro du jugement </label>
                    <input type="text" id="numero_jugement" class="form-control" readonly value="{{ $dn->jugement->num_jugement }}">
                </div>
                <div class="mb-2 col-md-2">
                    <label class="form-label">Du </label>
                    <input type="date" value="{{ $dn->jugement->date_jugement }}" readonly class="form-control" id="date_jugement">
                </div>
                <div class="mb-2 col-md-5">
                    <label class="form-label">Au Tribunal de </label>
                    <select class="form-control" id="tribunal_jugement" readOnly>
                        <option>{{ $dn->jugement->institutionUser->institution->institutionParent->lib_institution }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</section>
