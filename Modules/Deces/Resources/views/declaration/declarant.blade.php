<h6>Déclarant</h6>
<section>
    <div class="d-flex justify-content-end align-items-center" id="declarant_click">
        <button type="button" id="clear_declarant" class="btn btn-danger  text-white" ></i> Vider </button>
        <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".declarant-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche du déclarant</button>
    </div>
    <hr>
    <div class="row">

        <div class="mb-2 col-sm-3" id="hide_pere">
            <label for="dewey">Père</label>
            <input type="radio" id="peredeclarant" name="autredeclarant" value="pere">
        </div>


        <div class="mb-2 col-sm-3" id="hide_mere">
            <label for="dewey">Mère</label>
            <input type="radio" id="meredeclarant" name="autredeclarant" value="mere">
        </div>

        <div class="mb-2 col-sm-3">
            <label for="dewey">Autre</label>
            <input type="radio" id="autredeclarant" name="autredeclarant"  value="autre">
        </div>

        <div id="conjoint_click"  class="mb-2 col-sm-3">
            <label for="dewey">Conjoint</label>
            <input type="radio" id="autredeclarant" name="autredeclarant"  value="conjoint">
        </div>
    </div>
    <div class="ligne">
        <h4>INFORMATIONS SUR L'IDENTITE DU DECLARANT</h4>
    </div>
    <div class="row">
        <div class="col-md-4">
            <label class="form-label">Nom(s) déclarant <span class="text-danger">*</span></label>
            <input type="text" class="form-control required @error('nom_declarant') is-invalid @enderror" value="{{ old("nom_declarant") }}" placeholder="" name="nom_declarant" id="nom_declarant" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()">
            @error("nom_declarant")
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Prénom(s) déclarant</label>
            <input type="text" class="form-control  @error('prenom_declarant') is-invalid @enderror" value="{{ old("prenom_declarant") }}" placeholder="" name="prenom_declarant" id="prenom_declarant" onkeyup="verif_lettre(this);" style="text-transform: capitalize">
            @error("prenom_declarant")
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Date de naissance du déclarant </label>
            <input name="date_naissance_declarant" type="date" class="form-control" placeholder="" id="date_naissance_declarant"  max="{{ \Carbon\Carbon::now()->subYears(18)->format('Y-m-d'); }}">
            <input type="checkbox" id="type_date_naissance_declarant" value="ESTIME" name="type_date_naissance_declarant"><label for="type_date_naissance_declarant">date estimée</label>
            @error("date_naissance_declarant")
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Lieu de naissance du déclarant <span class="text-danger">*</span></label>
            <input type="text" class="form-control d-none" id="lieu_naissance_declarant" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()">
            <select class="form-control" id="code_localite_declarant">
                <option disabled selected>Choisissez</option>
                @foreach ($localites as $localite)
                    <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-4 autrelieunaissancedeclarant d-none">
            <label class="form-label">Lieu de naissance<span class="text-danger">*</span></label>
            <select id="etranger_lieu_naissance_declarant" class="form-control">
                    <option value="">Choisissez</option>
                @foreach ($countries as $countrie)
                    @if($countrie->name != 'Congo')
                    <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Nationalité du déclarant <span class="text-danger">*</span></label>
            <select id="code_nationalite_declarant" name="code_nationalite_declarant" class="form-select form-control required">
                <option disabled selected>Choisissez</option>
                @foreach ($nationalites as $nationalite)
                    <option value="{{ $nationalite->code_nationalite }}" >{{ $nationalite->lib_nationalite}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Sexe <span class="text-danger">*</span></label>
            <select id="sexe_declarant" name="sexe_declarant" class="form-control  @error('sexe_declarant') is-invalid @enderror">
                <option disabled selected>Choisissez</option>
                <option value="M">Masculin</option>
                <option value="F">Feminin</option>
            </select>
            @error("sexe_declarant")
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Niveau d'instruction du declarant</label>
            <select id="niveau_instruction_declarant" name="niveau_instruction_declarant" class="form-control form-control wide">
                    <option disabled selected>Choisissez</option>
                @foreach ($instructions as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
            </select>
            @error("niveau_instruction_declarant")
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Profession déclarant <span class="text-danger">*</span></label>
            <select name="code_profession_declarant" id="code_profession_declarant" class="form-control  @error('code_profession_declarant') is-invalid @enderror">
                <option disabled selected>Choisissez</option>
                @foreach ($professions as $profession)
                    <option value="{{ $profession->code_profession }}">{{ $profession->lib_profession }}</option>
                @endforeach
            </select>
            @error("code_profession_declarant")
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Filiation </label>
            <select name="code_filiation_declarant" id="code_filiation_declarant" class="form-select form-control required">
                    <option disabled selected> Choisissez</option>
                @foreach ($filiations as $filiation)
                    <option class="{{$filiation->code_filiation }}" value="{{ $filiation->code_filiation }}">{{ $filiation->lib_filiation }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Type pièce d'identité</label>
            <select id="code_type_document_declarant" name="code_type_document_declarant" class="form-control form-control wide">
                    <option>Choisissez</option>
                @foreach ($typedocuments as $item)
                    <option value="{{ $item->code_type_document }}">{{ $item->lib_type_document  }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Numéro pièce d'identité</label>
            <input type="text" id="numero_document_declarant" name="numero_document_declarant" class="form-control form-control wide" placeholder="Numéro du document" onkeyup="this.value=this.value.toUpperCase()">
        </div>
    </div>
    {{-- <div class="ligne d-none">
        <h4>ADRESSE</h4>
    </div>
    <div class="row d-none">
        <div class="mb-2 col-md-2">
            <label class="form-label">Pays<span class="text-danger"></span></label>
            <select id="domicile_pays_declarant" class="form-control required">
                <option value="">Choisissez</option>
                @foreach ($countries as $countrie)
                    <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-2">
            <label class="form-label">Commune/District<span class="text-danger"></span></label>
            <span id="departementcongo_declarant">
                <select class="form-control" name="domicile_ville_declarant" id="domicile_ville_declarant">
                    <option value="">Choisir</option>
                    @foreach ($localites as $localite)
                        <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                    @endforeach
                </select>
            </span>
            <span id="autredepartement_declarant">
                <input type="text" class="form-control" id="domicile_ville_declarant" placeholder="Ville ou département">
            </span>
        </div>
        <div class="mb-2 col-md-2">
            <label class="form-label">Arr/Com. Urb<span class="text-danger"></span></label>
            <span id="arrondissement_declarant">
                <select class="form-control" id="domicile_arrondissement_declarant">
                </select>
            </span>
        </div>
        <div class="mb-2 col-md-2">
            <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
            <select class="form-control" id="domicile_quartier_declarant">

            </select>
        </div>
        <div class="mb-2 col-md-2">
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
        <div class="mb-2 col-md-2">
            <label class="form-label">N° voie<span class="text-danger"></span></label>
            <input type="text" class="form-control" id="domicile_numero_declarant" placeholder="N° voie">
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Nom voie<span class="text-danger"></span></label>
            <input type="text" class="form-control" id="domicile_nomvoie_declarant" placeholder="Nom voie" onkeyup="this.value.toUpperCase()">
        </div>

        <div class="ligne">
            <h4>INFORMATIONS SUR LES CAUSES DU DECES</h4>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <select multiple class="default-select form-control wide mt-3" name="code_cause_deces" id="code_cause_deces">
                    @foreach ($causesDeces as $cause_deces)
                        <option value="{{ $cause_deces->code_cause_deces }}">{{ $cause_deces->lib_cause_deces }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div> --}}
    <div class="ligne">
        <h4>ADRESSE</h4>
    </div>
    <div class="row">
        <div class="mb-2 col-md-3">
            <label class="form-label">Pays<span class="text-danger"></span></label>
            <select id="domicile_pays_declarant" class="form-control required">
                <option value="">Choisissez</option>
                @foreach ($countries as $countrie)
                    <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-3 domicile_ville_declarant">
            <label class="form-label">Commune/District<span class="text-danger"></span></label>
            <select class="form-control" id="domicile_ville_declarant">
                <option value="">Choisir</option>
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
        <div class="col-md-4">
            <label class="form-label">Indicatif<span class="text-danger">*</span></label>
            <select name="code_pays_declarant" id="code_pays_declarant" class="form-control">
                <option value="">Selectionnez</option>
                @forelse ($countries as $code)
                    <option value="{{ $code->dial_code }}">({{ $code->dial_code }}) {{ $code->name }}</option>
                @empty
                @endforelse
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Téléphone déclarant</label>
            <input type="number" min="0" minlength="9" maxlength="10" id="telephone_declarant" name="telephone_declarant" class="form-control @error('telephone_declarant') is-invalid @enderror " placeholder="Téléphone déclarant">
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Email</label>
            <input type="email" id="email_declarant" class="form-control" name="email_declarant" placeholder="Email déclarant">
        </div>
        <div class="ligne">
            <h4>INFORMATIONS SUR LES CAUSES DU DECES</h4>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <select multiple class="default-select form-control wide mt-3" name="code_cause_deces" id="code_cause_deces">
                    @foreach ($causesDeces as $cause_deces)
                        <option value="{{ $cause_deces->code_cause_deces }}">{{ $cause_deces->lib_cause_deces }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</section>
