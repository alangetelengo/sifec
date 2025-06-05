<h6>Père</h6>

<section>

    <div class="ligne">
            <h4>INFORMATIONS PERSONNELLES</h4>
        </div>
    <div class="row">
        <div class="mb-2 col-md-4">
            <input type="hidden" id="code_pere">
            <label class="form-label">Nom(s) père <span class="text-danger">*</span></label>
            <input type="text" value="{{ $dummy }}" readonly class="form-control required  @error('nom_pere') is-invalid @enderror " name="nom_pere"  placeholder="Nom du père" id="nom_pere">
        </div>

        <div class="mb-2 col-md-4">
            <label class="form-label">Prénom(s) du père </label>
            <input type="text" value="{{ $dummy }}" readonly class="form-control" placeholder="Prénom du père" id="prenom_pere">
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Date de naissance du père<span class="text-danger">*</span></label>
            <input type="text" value="{{ $dummy }}" readonly name="date_naissance_pere" class="form-control">
            <input type="date" max="<?php $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 19 years'));?>" min="<?php echo date('Y-m-d', strtotime($jour. ' - 19 years')); ?>"
            class="form-control d-none"  id="date_naissance_pere" value="{{ date('Y-m-d', strtotime($jour. ' - 19 years')) }}">
        </div>
    </div>
    <div class="row">
        <div class="mb-2 col-md-4">
            <label class="form-label">Lieu de naissance père</label>
            <input type="text" readonly value="NON DECLARAE" name="lieu_naissance_pere" class="form-control d-none" id="lieu_naissance_pere" placeholder="Lieu de naissance">
            <select id="code_localite_pere" class="form-control required" readonly>
                <option value="LOC_4250">{{ $dummy }}</option>
            </select>
        </div>
         <div class="mb-2 col-md-4">
            <label class="form-label">Nationalité du père<span class="text-danger">*</span></label>
            <select id="code_nationalite_pere" name="code_nationalite_pere" class="form-control required  @error('code_nationalite_pere') is-invalid @enderror" readonly>
                    <option value="NAT_0001">{{ $dummy }}</option>
            </select>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Profession du père<span class="text-danger">*</span></label>
            <select id="profession_pere" class="form-control" readonly>
                    <option value="PROF_0010">{{ $dummy }}</option>
            </select>
        </div>

    </div>
     <div class="row">
        <div class="mb-2 col-md-4">
            <label class="form-label">Niveau d'instruction du père</label>
            <select id="niveau_instruction_pere" class="form-control form-control wide" readonly>
                    <option value="NON DECLARE">{{ $dummy }}</option>
            </select>
        </div>

        <div class="mb-2 col-md-4">
            <label class="form-label">Type pièce d'identité</label>
            <select id="code_type_document_pere" class="form-control form-control wide" readonly>
                    <option value="TDOC_0018">{{ $dummy }}</option>
            </select>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Numéro pièce d'identité</label>
            <input type="text" value="{{ $dummy }}" id="numero_document_pere" class="form-control form-control wide" placeholder="Numéro du document" readonly>
        </div>

     </div>

    <div class="ligne">
        <h4>ADRESSE & CONTACTS</h4>
    </div>
    <div class="row">
        <div class="mb-2 col-md-2">
            <label class="form-label">Pays<span class="text-danger"></span></label>
            <select id="domicile_pays_pere" class="form-control required" readonly>
                <option value="Congo">{{ $dummy }}</option>
            </select>
        </div>
        <div class="mb-2 col-md-2">
            <label class="form-label">Commune/District<span class="text-danger"></span></label>
            <span id="communeDistrict_pere">
                <select class="form-control" name="domicile_ville_pere" id="domicile_ville_pere" readonly>
                    <option value="{{ $dummy }}">{{ $dummy }}</option>
                </select>
            </span>
            <span id="autreCommuneDistrict_pere">
                <input type="text" value="{{ $dummy }}" class="form-control" id="autredomicile_ville_pere" placeholder="Ville ou département" readonly>
            </span>
        </div>

        <div class="mb-2 col-md-2">
            <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
            <select class="form-control" id="domicile_quartier_pere" readonly>
                <option value="{{ $dummy }}">{{ $dummy }}</option>
            </select>
        </div>
        <div class="mb-2 col-md-2">
            <label class="form-label">Type voie<span class="text-danger"></span></label>
            <select class="form-control" id="domicile_typevoie_pere" readonly>
                <option value="{{ $dummy }}">{{ $dummy }}</option>
            </select>
        </div>
        <div class="mb-2 col-md-2">
            <label class="form-label">N° voie<span class="text-danger"></span></label>
            <input type="text" value="{{ $dummy }}" class="form-control" id="domicile_numero_pere" placeholder="N° voie" readonly>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Nom voie<span class="text-danger"></span></label>
            <input type="text" value="{{ $dummy }}" class="form-control" id="domicile_nomvoie_pere" placeholder="Nom voie" readonly>
        </div>

        <div class="mb-2 col-md-4">
            <label class="form-label">Téléphone père<span class="text-danger">*</span></label>
            <div class="row">
                <div class="col-md-6">
                    <select name="code_pays_pere" id="code_pays_pere" class="form-control" readonly>
                        <option value="{{ $dummy }}">{{ $dummy }}</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input value="0000000" type="text" id="telephone_pere" readonly name="telephone_pere" class="form-control @error('telephone_pere') is-invalid @enderror " placeholder="Téléphone père">
                </div>
            </div>
        </div>

        <div class="mb-2 col-md-4">
            <label class="form-label">Email</label>
            <input type="text" value="{{ $dummy }}" id="email_pere" class="form-control" readonly name="email_pere" placeholder="Email père">
        </div>

        <div class="mb-2 col-md-4 d-noe">
            <label class="form-label">Statut <span class="text-danger">*</span></label>
            <select id="statut_personne_pere" name="statut_personne_pere" class="form-control" readonly>
                <option value="DECEDE">{{ $dummy }}</option>
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
    <div class="ligne">
            <h4>INFORMATIONS PERSONNELLES</h4>
        </div>
        <div class="row">
            <div class="mb-2 col-md-4">
                <input type="hidden" id="code_mere">
                <label class="form-label">Nom(s) de la mère <span class="text-danger">*</span></label>
                <input type="text" value="{{ $dummy }}" readonly class="form-control required  @error('nom_mere') is-invalid @enderror " name="nom_mere"  placeholder="Nom de la mère" id="nom_mere">
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Prénom(s) de la mère </label>
                <input type="text" value="{{ $dummy }}" readonly class="form-control" placeholder="Prénom de la mère" id="prenom_mere">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Date de naissance de la mère<span class="text-danger">*</span></label>
                <input type="text" value="{{ $dummy }}" readonly name="date_naissance_mere" class="form-control">
                <input type="date" max="<?php $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 19 years'));?>" min="<?php echo date('Y-m-d', strtotime($jour. ' - 19 years')); ?>"
                class="form-control d-none"  id="date_naissance_mere" value="{{ date('Y-m-d', strtotime($jour. ' - 19 years')) }}">
            </div>
        </div>
        <div class="row">
            <div class="mb-2 col-md-4">
                <label class="form-label">Lieu de naissance mère</label>
                <input type="text" readonly value="NON DECLARAE" name="lieu_naissance_mere" class="form-control d-none" id="lieu_naissance_mere" placeholder="Lieu de naissance">
                <select id="code_localite_mere" class="form-control required" readonly>
                    <option value="LOC_4250">{{ $dummy }}</option>
                </select>
            </div>
             <div class="mb-2 col-md-4">
                <label class="form-label">Nationalité de la mère<span class="text-danger">*</span></label>
                <select id="code_nationalite_mere" name="code_nationalite_mere" class="form-control required  @error('code_nationalite_mere') is-invalid @enderror" readonly>
                        <option value="NAT_0001">{{ $dummy }}</option>
                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Profession de la mère<span class="text-danger">*</span></label>
                <select id="profession_mere" class="form-control" readonly>
                        <option value="PROF_0010">{{ $dummy }}</option>
                </select>
            </div>

        </div>
         <div class="row">
            <div class="mb-2 col-md-4">
                <label class="form-label">Niveau d'instruction de la mère</label>
                <select id="niveau_instruction_mere" class="form-control form-control wide" readonly>
                        <option value="NON DECLARE">{{ $dummy }}</option>
                </select>
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Type pièce d'identité</label>
                <select id="code_type_document_mere" class="form-control form-control wide" readonly>
                        <option value="TDOC_0018">{{ $dummy }}</option>
                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Numéro pièce d'identité</label>
                <input type="text" value="{{ $dummy }}" id="numero_document_mere" class="form-control form-control wide" placeholder="Numéro du document" readonly>
            </div>

         </div>
    <div class="ligne">
        <h4>ADRESSE & CONTACTS</h4>
    </div>
    <div class="row">
        <div class="mb-2 col-md-2">
            <label class="form-label">Pays<span class="text-danger"></span></label>
            <select id="domicile_pays_mere" class="form-control required">
                <option value="Congo">{{ $dummy }}</option>
            </select>
        </div>
        <div class="mb-2 col-md-2">
            <label class="form-label">Commune/District<span class="text-danger"></span></label>
            <span id="communeDistrict_mere">
                <select class="form-control" name="domicile_ville_mere" id="domicile_ville_mere" readonly>
                    <option value="{{ $dummy }}">{{ $dummy }}</option>
                </select>
            </span>
            <span id="autreCommuneDistrict_mere">
                <input type="text" value="{{ $dummy }}" class="form-control" id="autredomicile_ville_mere" placeholder="Ville ou département" readonly>
            </span>
        </div>

        <div class="mb-2 col-md-2">
            <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
            <select class="form-control" id="domicile_quartier_mere" readonly>
                <option value="{{ $dummy }}">{{ $dummy }}</option>
            </select>
        </div>
        <div class="mb-2 col-md-2">
            <label class="form-label">Type voie<span class="text-danger"></span></label>
            <select class="form-control" id="domicile_typevoie_mere" readonly>
                <option value="{{ $dummy }}">{{ $dummy }}</option>
            </select>
        </div>
        <div class="mb-2 col-md-2">
            <label class="form-label">N° voie<span class="text-danger"></span></label>
            <input type="text" value="{{ $dummy }}" class="form-control" id="domicile_numero_mere" placeholder="N° voie" readonly>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Nom voie<span class="text-danger"></span></label>
            <input type="text" value="{{ $dummy }}" class="form-control" id="domicile_nomvoie_mere" placeholder="Nom voie" readonly>
        </div>

        <div class="mb-2 col-md-4">
            <label class="form-label">Téléphone Mère<span class="text-danger">*</span></label>
            <div class="row">
                <div class="col-md-6">
                    <select name="code_pays_mere" id="code_pays_mere" class="form-control" readonly>
                        <option value="{{ $dummy }}">{{ $dummy }}</option>

                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" value="0000000" id="telephone_mere" readonly name="telephone_mere" class="form-control" placeholder="Téléphone Mère">
                </div>
            </div>
        </div>

        <div class="mb-2 col-md-4">
            <label class="form-label">Email</label>
            <input type="text" value="{{ $dummy }}" id="email_mere" readonly class="form-control" name="email_mere" placeholder="Email mère">
        </div>
        <div class="mb-2 col-md-4 d-noe">
            <label class="form-label">Statut <span class="text-danger">*</span></label>
            <select id="statut_personne_mere" name="statut_personne_mere" class="form-control" readonly>
                <option value="DECEDE">{{ $dummy }}</option>
            </select>
        </div>
    </div>
</section>
<!-- Step 4 -->
<h6>Déclarant</h6>
<section>
    <div class="row">

        <div class="ligne">
            <h4>INFORMATIONS DU DECLARANT</h4>
        </div>
        <div class="mb-2 col-md-12">
            <label class="form-label">Nom(s) déclarant <span class="text-danger">*</span></label>
            <input type="text" class="form-control"  id="nom_declarant">
        </div>
        <div class="mb-2 col-md-4 d-none">
            <label class="form-label">Prénom(s) du déclarant </label>
            <input type="text" class="form-control" placeholder="Prénom du déclarant" id="prenom_declarant" name="prenom_declarant" onkeyup="verif_lettre(this);" style="text-transform: capitalize;">
        </div>
        <div class="mb-2 col-md-4 d-none">
            <label class="form-label">Sexe du déclarant<span class="text-danger">*</span></label>
            <select id="sexe_declarant" name="sexe_declarant" class="form-control  @error('sexe_declarant') is-invalid @enderror ">
                <option value="M" selected>Masculin</option>
                <option value="F">Féminin</option>
            </select>
        </div>
    </div>
    <div class="row d-none">
        <div class="mb-2 col-md-4">
            <label class="form-label d-none">Date de naissance du déclarant<span class="text-danger">*</span></label>
            <input type="date" max="<?php $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 19 years'));?>" min="<?php echo date('Y-m-d', strtotime($jour. ' - 19 years')); ?>"
            class="form-control"  id="date_naissance_declarant" value="{{ date('Y-m-d', strtotime($jour. ' - 19 years')) }}">
            <input type="checkbox" id="type_date_naissance_declarant" value="ESTIME" name="type_date_naissance_declarant"><label for="type_date_naissance_declarant">date estimée</label>

        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Lieu de naissance </label>
            <input type="text" class="form-control d-none" value="{{ Auth::user()->affectationactive()->institution->lieu->localiteParent->lib_localite }}" name="lieu_naissance_declarant" id="lieu_naissance_declarant" placeholder="Lieu de naissance">
            <select id="code_localite_declarant" class="form-control">
                @foreach ($localites as $localite)
                    <option value="{{ $localite->code_localite }}" {{ Auth::user()->affectationactive()->institution->lieu->localiteParent->code_localite == $localite->code_localite ? "selected" : "" }}>{{ $localite->lib_localite }}</option>
                @endforeach
            </select>
        </div>
         <div class="mb-2 col-md-4">
            <label class="form-label">Nationalité du déclarant<span class="text-danger">*</span></label>
            <select id="code_nationalite_declarant" name="code_nationalite_declarant" class="form-control required  @error('code_nationalite_declarant') is-invalid @enderror ">
                @foreach ($nationalites as $nationalite)
                    <option value="{{ $nationalite->code_nationalite }}" {{ $nationalite->code_nationalite == "NAT_0001" ? "selected" : "" }}>{{ $nationalite->lib_nationalite }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row d-none">
       <div class="mb-2 col-md-4">
            <label class="form-label">Filiation <span class="text-danger">*</span></label>
            <select id="filiation" name="filiation" class="form-control required  @error('filiation') is-invalid @enderror ">
                <option value="{{ $dummy }}">{{ $dummy }}</option>
            </select>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Profession du déclarant</label>
            <select id="profession_declarant" name="profession_declarant" class="form-control required  @error('profession_declarant') is-invalid @enderror ">
                    <option selected disabled>Choisissez</option>
                @foreach ($professions as $item)
                    <option value="{{ $item->code_profession }}" {{ $item->code_profession == "PROF_0188" ? "selected" : "" }}>{{ $item->lib_profession }}</option>
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
        <div class="mb-2 col-md-4">
            <label class="form-label">Type pièce d'identité</label>
            <select id="code_type_document_declarant" name="code_type_document_declarant" class="form-control required ">
                @foreach ($typedocuments as $item)
                    <option value="{{ $item->code_type_document }}" {{ $item->code_type_document == "TDOC_0001" ? "selected" : "" }}>{{ $item->lib_type_document  }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Numéro pièce d'identité</label>
            <input type="text" id="numero_document_declarant" name="numero_document_declarant" class="form-control" value="CASFR45200" readonly>
        </div>

        <div class="mb-2 col-md-4">
            <label class="form-label">Statut <span class="text-danger">*</span></label>
            <select id="statut_personne_declarant" name="statut_personne_declarant" required class="form-control required ">
                <option value="VIVANT">Vivant(e)</option>
            </select>
        </div>

        <div class="ligne">
            <h4>ADRESSE & CONTACTS</h4>
        </div>
        <div class="mb-2 col-md-2">
            <label class="form-label">Pays<span class="text-danger"></span></label>
            <select id="domicile_pays_declarant" class="form-control required">
                @foreach ($countries as $countrie)
                    <option value="{{ $countrie->name }}" {{ $countrie->name == "Congo" ? "selected" : "" }}>{{ $countrie->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-2">
            <label class="form-label">Ville/Département<span class="text-danger"></span></label>
            <span id="communeDistrict_declarant">
                <select class="form-control" name="domicile_ville_declarant" id="domicile_ville_declarant">
                    @foreach ($localites as $localite)
                        <option value="{{ $localite->code_localite }}" {{ Auth::user()->affectationactive()->institution->lieu->localiteParent->code_localite == $localite->code_localite ? "selected" : "" }}>{{ $localite->lib_localite }}</option>
                    @endforeach
                </select>
            </span>
            <span id="autreCommuneDistrict_declarant">
                <input type="text" class="form-control" id="domicile_ville_declarant" placeholder="Ville ou département">
            </span>
        </div>

        <div class="mb-2 col-md-2">
            <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
            {{-- <input type="text" class="form-control" id="domicile_quartier_declarant" placeholder="Quartier ou village"> --}}
            <select class="form-control" id="domicile_quartier_declarant">
            </select>
        </div>
        <div class="mb-2 col-md-2">
            <label class="form-label">Type voie<span class="text-danger"></span></label>
            <select class="form-control" id="domicile_typevoie_declarant">
                <option value="Avenue" selected>Avenue</option>
                <option value="Boulevard">Boulevard</option>
                <option value="Impasse">Impasse</option>
                <option value="Rue">Rue</option>
                <option value="Autre">Autre</option>
            </select>
        </div>
        <div class="mb-2 col-md-2">
            <label class="form-label">N° voie<span class="text-danger"></span></label>
            <input type="text" class="form-control" id="domicile_numero_declarant" value="180">
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Nom voie<span class="text-danger"></span></label>
            <input type="text" class="form-control" id="domicile_nomvoie_declarant" value="Denis SASSOU">
        </div>

        <div class="mb-2 col-md-4">
            <label class="form-label">Téléphone déclarant</label>

            <div class="row">
                <div class="col-md-6">
                    <select name="code_pays_declarant" id="code_pays_declarant" class="form-control required">
                        @forelse ($countries as $code)
                            <option value="{{ $code->dial_code }}" {{ $code->dial_code == "+242" ? "selected" : "" }}>({{ $code->dial_code }}) {{ $code->name }}</option>
                        @empty

                        @endforelse

                    </select>
                </div>
                <div class="col-md-6">
                    <input type="number" min="0" minlength="9" maxlength="9" id="telephone_declarant" value="06{{ substr(time(),3) }}" class="form-control required  @error('statut_personne_mere') is-invalid @enderror " placeholder="Téléphone déclarant">
                </div>
            </div>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Email</label>
            <input type="email" id="email_declarant" class="form-control" name="email_declarant" placeholder="Email déclarant">
        </div>


    </div>
</section>
