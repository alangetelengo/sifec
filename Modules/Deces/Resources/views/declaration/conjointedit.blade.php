@if ($dd->situationMat->code_situation_matrimoniale!="SMAT_0001")
    {{-- désactivation par défaut de tous les champs du formulaire --}}
    <script>

                    $("input.cec_mariage").addClass("d-none");
                    document.getElementById('code_localite_conjoint').disabled = true;
                    document.getElementById('email_conjoint').disabled = true;
                    document.getElementById('nom_conjoint').disabled = true;
                    document.getElementById('prenom_conjoint').disabled = true;
                    document.getElementById('date_naissance_conjoint').disabled = true;
                    document.getElementById('date_mariage').disabled = true;
                    document.getElementById('cec_mariage').disabled = true;
                    document.getElementById('lieu_naissance_conjoint').disabled = true;
                    document.getElementById('code_pays_conjoint').disabled = true;
                    document.getElementById('telephone_conjoint').disabled = true;
                    document.getElementById('code_nationalite_conjoint').disabled = true;
                    document.getElementById('sexe_conjoint').disabled = true;
                    document.getElementById('domicile_pays_conjoint').disabled = true;
                    document.getElementById('code_type_document_conjoint').disabled = true;
                    document.getElementById('statut_personne_conjoint').disabled = true;
                    document.getElementById('code_profession_conjoint').disabled = true;
                    document.getElementById('code_regime').disabled = true;
                    document.getElementById('niveau_instruction_conjoint').disabled = true;
                    document.getElementById('domicile_numero_conjoint').disabled = true;
                    document.getElementById('domicile_nomvoie_conjoint').disabled = true;
                    document.getElementById('domicile_quartier_conjoint').disabled = true;
                    document.getElementById('domicile_typevoie_conjoint').disabled = true;
                    document.getElementById('domicile_arrondissement_conjoint').disabled = true;
                    document.getElementById('domicile_pays_conjoint').disabled = true;
                    document.getElementById('num_acte_mariage').disabled = true;
                    document.getElementById('lieu_mariage').disabled = true;
                    document.getElementById('liste_cec').disabled = true;
                    document.getElementById('statut_personne_conjoint').disabled = true;
                    document.getElementById('code_type_document_conjoint').disabled = true;
                    document.getElementById('numero_document_conjoint').disabled = true;
                    // document.getElementById('liste_cec').disabled = false;
                    document.getElementById('search_conjoint').style.visibility = 'hidden';
                    document.getElementById('clear_conjoint').style.visibility = 'hidden';
                    document.getElementById('conjoint_click').style.visibility = 'hidden';

            </script>     
@endif
<h6>Conjoint</h6>
<section>
    <div class="d-flex justify-content-end align-items-center">
        <button type="button" id="clear_conjoint" class="btn btn-danger  text-white" ></i> Vider </button>
        <button type="button" id="search_conjoint" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".conjoint-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche du conjoint(e)</button>
    </div>
    <hr>
    <div class="ligne">
        <h4>INFORMATIONS SUR L'IDENTITE DU CONJOINT</h4>
    </div>
    <div class="row">
        <div class="col-md-3">
            <input name="code_conjoint" id="code_conjoint" type="hidden" readonly>
            <label class="form-label">Nom(s) Conjoint </label>
            <input type="text" class="form-control @error('nom_conjoint') is-invalid @enderror" value="@if($dd->conjoint!=null){{ $dd->conjoint->nom }}@endif"  id="nom_conjoint" name="nom_conjoint" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()">
            @error("nom_conjoint")
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-3">
            <label class="form-label">Prénom(s) Conjoint</label>
            <input type="text" class="form-control" value="@if($dd->conjoint!=null){{ $dd->conjoint->prenom }}@endif"  id="prenom_conjoint" onkeyup="verif_lettre(this);" style="text-transform: capitalize">
        </div>
        <div class="col-md-3">
            <label class="form-label">Sexe du conjoint<span class="text-danger">*</span></label>
            <select id="sexe_conjoint" name="sexe_conjoint" class="form-control form-control wide">
                <option disabled selected>Sélectionner</option>
                <option value="M" @if(($dd->conjoint!=null) && ($dd->conjoint->sexe=="M")) selected @endif>Masculin</option>
                <option value="F" @if(($dd->conjoint!=null) && ($dd->conjoint->sexe=="F")) selected @endif>Féminin</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Date naissance Conjoint</label>
            <input  max="<?php  $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 18 years'));?>" type="date" class="form-control @error('date_naissance_conjoint') is-invalid @enderror" value="@if($dd->conjoint!=null) {{ $dd->conjoint->date_naissance }} @endif"  id="date_naissance_conjoint"  name="date_naissance_conjoint">
            <input type="checkbox" id="type_date_naissance_conjoint" value="ESTIME" name="type_date_naissance_conjoint"><label for="type_date_naissance_conjoint">date estimée</label>
        </div>
    </div>
    <div class="row">
        <div class="mb-2 col-md-3">
            <label class="form-label">Lieu de naissance </label>
            <input type="text" class="form-control d-none" id="lieu_naissance_conjoint" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()">
            <select class="form-control" id="code_localite_conjoint">
                <option disabled selected>Choisissez</option>
                @foreach ($localites as $localite)
                    <option value="{{ $localite->code_localite }}" @if(($dd->conjoint!=null) && ($dd->conjoint->lieu_naissance==$localite->lib_localite)) selected @endif>{{ $localite->lib_localite }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-4 autrelieunaissanceconjoint d-none">
            <label class="form-label">Lieu de naissance<span class="text-danger">*</span></label>
            <select id="etranger_lieu_naissance_conjoint" class="form-control">
                    <option value="">Choisissez</option>
                @foreach ($countries as $countrie)
                    @if($countrie->name != 'Congo')
                    <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-3">
            <label class="form-label">Nationalité du conjoint<span class="text-danger">*</span></label>
            <select id="code_nationalite_conjoint" class="form-control form-control wide">
                    <option>Choisissez</option>
                @foreach ($nationalites as $nationalite)
                    <option value="{{ $nationalite->code_nationalite }}"  @if(($dd->conjoint!=null) && ($dd->conjoint->code_nationalite == $nationalite->code_nationalite)) {{ "selected" }} @endif >{{ $nationalite->lib_nationalite }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-3">
            <label class="form-label">Niveau d'instruction du conjoint</label>
            <select id="niveau_instruction_conjoint" class="form-control form-control wide">
                    <option disabled>Choisissez</option>
                @foreach ($instructions as $item)
                    <option value="{{ $item }}"  @if(($dd->conjoint!=null) && ($dd->conjoint->niveau_instruction == $item)) {{ "selected" }} @endif>{{ $item }}</option>
                @endforeach
            </select>
            @error("niveau_instruction_conjoint")
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-2 col-md-3">
            <label class="form-label">Profession du conjoint</label>
            <select id="code_profession_conjoint" name="code_profession_conjoint" class="form-control form-control wide">
                <option>Choisissez</option>
                @foreach ($professions as $item)
                    <option value="{{ $item->code_profession }}"  @if(($dd->conjoint!=null) && ($dd->conjoint->code_profession == $item->code_profession)) {{ "selected" }} @endif>{{ $item->lib_profession }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-3">
            <label class="form-label">Type pièce d'identité</label>
            <select id="code_type_document_conjoint" name="code_type_document_conjoint" class="form-control form-control wide">
                    <option disabled selected>Choisissez</option>
                @foreach ($typedocuments as $item)
                    <option value="{{ $item->code_type_document }}" @if(($dd->conjoint!=null) && ($dd->conjoint->document->typeDocument->code_type_document == $item->code_type_document)) {{ "selected" }} @endif>{{ $item->lib_type_document  }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-3">
            <label class="form-label">Numéro pièce d'identité</label>
            <input type="text" id="numero_document_conjoint" name="numero_document_conjoint" value="@if($dd->conjoint!=null) {{ $dd->conjoint->document->numero_document }} @endif" class="form-control form-control wide" placeholder="Numéro du document" onkeyup="this.value=this.value.toUpperCase()">
        </div>
    </div>

    <div class="ligne">
        <h4>ADRESSE</h4>
    </div>

    <div class="row">
        <div class="mb-2 col-md-3">
            <label class="form-label">Pays<span class="text-danger"></span></label>
            <select id="domicile_pays_conjoint" class="form-control required">
                <option value="">Choisissez</option>
                 @foreach ($countries as $countrie)
                    @if(($dd->conjoint!=null) && ($dd->conjoint->adresses->last() != null))
                        <option value="{{ $countrie->name }}" {{ $dd->conjoint->adresses->last()->lib_pays == $countrie->name ? "selected" : "" }}>{{ $countrie->name }}</option>
                    @else
                    <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-3 domicile_ville_conjoint d-none">
            <label class="form-label">Commune/District<span class="text-danger"></span></label>
            <select class="form-control" id="domicile_ville_conjoint">

                @if(($dd->conjoint!=null) && ($dd->conjoint->adresses->last() != null))
                    @foreach ($localites as $item)
                        <option value="{{ $item->code_localite }}" {{  $dd->conjoint->adresses->last()->code_localite == $item->code_localite ? "selected" : "" }}>{{ $item->lib_localite }}</option>
                    @endforeach
                @else
                    <option value="">Selectionnez</option>
                    @foreach ($localites as $item)
                        <option value="{{ $item->code_localite }}">{{ $item->lib_localite }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="mb-2 col-md-3 autredomicile_ville_conjoint d-none">
            <label class="form-label">Ville<span class="text-danger"></span></label>
            <input type="text" id="autredomicile_ville_conjoint" class="form-control form-control wide" placeholder="Libellé de la ville" onkeyup="this.value=this.value.toUpperCase()">
        </div>

        <div class="mb-2 col-md-3 domicile_arrondissement_conjoint d-none">
            <label class="form-label">Arr/Com. Urb<span class="text-danger"></span></label>
            <select class="form-control" id="domicile_arrondissement_conjoint">
                <option value="">Choisir</option>
                @foreach ($arrondissement as $localite)
                    <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-2 col-md-3 domicile_quartier_conjoint d-none">
            <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
            <select class="form-control" id="domicile_quartier_conjoint">
                <option value="">Choisir</option>
                @foreach ($quartierVillages as $localite)
                    <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-3">
            <label class="form-label">Type voie<span class="text-danger"></span></label>
            <select id="domicile_typevoie_conjoint" class="form-control">
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
            <input type="text" class="form-control" id="domicile_numero_conjoint" placeholder="N° voie">
        </div>
        <div class="mb-2 col-md-3">
            <label class="form-label">Nom voie<span class="text-danger"></span></label>
            <input type="text" class="form-control" id="domicile_nomvoie_conjoint" placeholder="Nom voie" style="text-transform: capitalize">
        </div>

        <div class="ligne">
            <h4>CONTACTS</h4>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label class="form-label">Indicatif<span class="text-danger">*</span></label>
                <select name="code_pays_conjoint" id="code_pays_conjoint" class="form-control">
                    <option value="">Selectionnez</option>
                    @forelse ($countries as $code)
                        <option value="{{ $code->dial_code }}">({{ $code->dial_code }}) {{ $code->name }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Téléphone père</label>
                <input type="number" min="0" minlength="9" maxlength="10" id="telephone_conjoint" name="telephone_conjoint" class="form-control @error('telephone_conjoint') is-invalid @enderror " placeholder="Téléphone père">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">E-mail personnel</label>
                <input type="email" id="email_conjoint" class="form-control" name="email_conjoint" placeholder="E-mail personnel du conjoint" autocomplete="email">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">E-mail professionnel <span class="text-muted small">(optionnel)</span></label>
                <input type="email" id="email_professionnel_conjoint" class="form-control" name="email_professionnel_conjoint" placeholder="E-mail professionnel du conjoint" autocomplete="email">
            </div>
        </div>
    </div>

    <div class="ligne">
        <h4>INFORMATIONS SUR LE MARIAGE</h4>
    </div>
    <div class="row">
        <div class="col-md-3">
            <label class="form-label">Lieu du mariage </label>
            <select id="lieu_mariage" class="form-select form-control">
                <option disabled selected>Choisissez</option>
                <option value="etranger">Etranger</option>
                <option value="congo">Congo</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Centre d'état civil du mariage </label>
            <select id="liste_cec" class="form-select form-control">
                    <option disabled selected>Choisissez</option>
                @foreach ($cecMariage as $institution)
                    <option value="{{ $institution->code_institution }}">{{ $institution->lib_institution }}</option>
                @endforeach
            </select>
            <input type="text" class="form-control autre_cec  @error('cec_mariage') is-invalid @enderror" value="{{ old("cec_mariage") }}" id="cec_mariage" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()">
            @error("cec_mariage")
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-3">
            <label class="form-label">Date de mariage </label>
            <input type="date" class="form-control @error('date_mariage') is-invalid @enderror" value="{{ old("date_mariage") }}" placeholder="" id="date_mariage" name="date_mariage" max="{{ \Carbon\Carbon::now()->format('Y-m-d'); }}">
            @error("date_mariage")
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-3">
            <label class="form-label"> Option du mariage</label>
            <select id="code_regime" name="code_regime" class="form-select form-control ">
                <option disabled selected>Choisissez</option>
                @foreach ($regimes as $regime)
                    <option value="{{ $regime->code_regime }}">{{ $regime->lib_regime }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">N° acte de mariage</label>
            <input type="text" class="form-control" name="num_acte_mariage" id="num_acte_mariage" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()">
        </div>
        <div class="ligne">
            <h4>AUTRES INFORMATIONS</h4>
        </div>

        <div class="mb-2 col-md-4">
            <label class="form-label">Statut <span class="text-danger">*</span></label>
            <select id="statut_personne_conjoint" class="form-control">
                <option selected value="VIVANT">Vivant</option>
                <option value="DECEDE">Décédé</option>
            </select>
        </div>
    </div>
</section>
