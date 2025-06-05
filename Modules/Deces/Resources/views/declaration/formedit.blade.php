<section>
    <div class="d-flex justify-content-end align-items-center">
        <button type="button" id="clear_defunt" class="btn btn-danger  text-white" ></i> Vider </button>
        <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".defunt-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche du défunt</button>
    </div>
    <hr>
    <div class="ligne">
        <h4>INFORMATIONS SUR L'IDENTITE DU DEFUNT</h4>
    </div>
    <div class="row">
        <div class="col-md-3">
            <label class="form-label">Nom(s) défunt <span class="text-danger">*</span></label>
            <input type="text" class="form-control required  @error('nom_defunt') is-invalid @enderror" value="{{ $dd->defunt->nom }}" placeholder="" id="nom_defunt" name="nom_defunt" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()">
            @error("nom_defunt")
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Prénom(s) défunt</label>
            <input type="text" class="form-control" value="{{ $dd->defunt->prenom }}" placeholder="" id="prenom_defunt" name="prenom_defunt" onkeyup="verif_lettre(this);" style="text-transform: capitalize">
        </div>
        <div class="col-md-3">
            <label class="form-label">Sexe <span class="text-danger">*</span></label>
            <select id="sexe_defunt" name="sexe_defunt" class="form-select form-control required">
                <option value="M" {{ $dd->defunt->sexe == "M" ? "selected" : ""  }}>Masculin</option>
                <option value="F" {{ $dd->defunt->sexe == "F" ? "selected" : ""  }}>Feminin</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Date de naissance <span class="text-danger">*</span></label>
            <input type="date" class="form-control  @error('date_naissance_defunt') is-invalid @enderror" value="{{ $dd->defunt->date_naissance }}" id="date_naissance_defunt" name="date_naissance_defunt" max="{{ \Carbon\Carbon::now()->format('Y-m-d'); }}">
            <input type="checkbox" id="type_date_naissance_defunt" value="ESTIME" name="type_date_naissance_defunt"><label for="type_date_naissance_defunt">date estimée</label>
            @error("date_naissance_defunt")
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="row">
            <div class="col-md-3">
                <label class="form-label">Lieu de naissance <span class="text-danger">*</span></label>
                <input type="text" class="form-control " id="lieu_naissance_defunt" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()" value="{{ $dd->defunt->lieu_naissance }}">
                <select class="form-control d-none" id="code_localite_defunt">
                    <option disabled selected>Choisissez</option>
                    @foreach ($localites as $localite)
                    <option value="{{ $localite->code_localite }}" {{ $dd->defunt->code_localite == $localite->code_localite ? "selected" : "" }}>{{ $localite->lib_localite }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3 autrelieunaissancedefunt d-none">
                <label class="form-label">Lieu de naissance<span class="text-danger">*</span></label>
                <input type="text" name="lieu_naissance_defunt" class="form-control d-none" id="autre_lieu_naissance_defunt">
                <select id="etranger_lieu_naissance_defunt" class="form-control">
                        <option value="">Choisissez</option>
                    @foreach ($countries as $countrie)
                        @if($countrie->name != 'Congo')
                        <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3 autrececnaissancedefunt d-none">
                <label class="form-label">Centre d'état civil de naissance<span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('cec_naissance_defunt') is-invalid @enderror" value="{{ $dd->cec_naissance }}" id="cec_naissance_defunt" name="cec_naissance_defunt" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()">
                @error("cec_naissance_defunt")
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="col-md-3 codececdefunt">
                <label class="form-label">Centre d'état civil de naissance <span class="text-danger"></span></label>
                <select id="code_cec_defunt" class="form-select form-control">
                    @foreach ($cecMariage as $item)
                    <option value="{{ $item->code_institution }}" {{ $dd->cec_naissance == $item->lib_institution ? "selected" : "" }}>{{ $item->lib_institution }}</option>
                @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Numéro d'acte de naissance <span class="text-danger">*</span></label>
                <input type="text" class="form-control  @error('num_acte_naissance_defunt') is-invalid @enderror" value="{{ $dd->num_acte_naissance }}" id="num_acte_naissance_defunt" name="num_acte_naissance" required onkeyup="this.value=this.value.toUpperCase()">
            </div>
            <div class="col-md-3">
                <label class="form-label">Profession <span class="text-danger">*</span></label>
                <select id="code_profession_defunt" name="code_profession_defunt" class="form-select form-control required">
                    <option disabled selected>Choisissez</option>
                    @foreach ($professions as $profession)
                        <option value="{{ $profession->code_profession }}" {{ $dd->defunt->profession->code_profession == $profession->code_profession ? "selected" : "" }}>{{ $profession->lib_profession }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Niveau d'instruction du defunt</label>
                <select id="niveau_instruction_defunt" name="niveau_instruction_defunt" class="form-control form-control wide">
                        <option disabled selected>Choisissez</option>
                    @foreach ($instructions as $item)
                        <option value="{{ $item }}" {{ $dd->defunt->niveau_instruction == $item ? "selected" : "" }}>{{ $item }}</option>
                    @endforeach
                </select>
                @error("niveau_instruction_defunt")
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Situation matrimoniale </label>
                <select name="code_situation_matrimoniale_defunt" id="code_situation_matrimoniale_defunt" class="form-select form-control required">
                        <option disabled selected>Choisissez</option>
                        @foreach ($situationMatrimoniales as $item)
                            <option value="{{ $item->code_situation_matrimoniale }}" {{ $dd->situationMat->code_situation_matrimoniale == $item->code_situation_matrimoniale ? "selected" : "" }}>{{ $item->lib_situation_matrimoniale }}</option>
                        @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Nationalité <span class="text-danger">*</span></label>
                <select id="code_nationalite_defunt" name="code_nationalite_defunt" class="form-select form-control required">
                    <option disabled selected>Choisissez</option>
                    @foreach ($nationalites as $nationalite)
                        <option value="{{ $nationalite->code_nationalite }}" {{ $dd->defunt->nationalite->code_nationalite == $nationalite->code_nationalite ? "selected" : "" }}>{{ $nationalite->lib_nationalite}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Réligion <span class="text-danger">*</span></label>
                <select name="code_religion_defunt" id="code_religion_defunt" class="form-select form-control">
                        <option disabled selected>Choisissez</option>
                    @foreach ($religions as $religion)
                        <option value="{{ $religion->code_religion }}" {{ $dd->religion->code_religion == $religion->code_religion ? "selected" : "" }}>{{ $religion->lib_religion }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="ligne">
        <h4>INFORMATIONS DU DECES</h4>
    </div>
    <div class="row">
        <div class="col-md-3">
            <input name="code_defunt" id="code_defunt" type="hidden" readonly>
            <label class="form-label" for="validationCustom07">Date décès <span class="text-danger">*</span></label>
            <input type="date" class="form-control required  @error('date_defunt') is-invalid @enderror " id="date_deces"  name="date_deces"  max="{{ \Carbon\Carbon::now()->format('Y-m-d'); }}" value="{{ date("Y-m-d", strtotime($dd->date_heure_deces)) }}">
            @error("date_deces")
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label" for="validationCustom07">Heure décès <span class="text-danger">*</span></label>
            <input class="form-control required  @error('heure_defunt') is-invalid @enderror" type="time"  name="heure_deces" id="heure_deces"  value="{{ date("H:m", strtotime($dd->date_heure_deces)) }}">
            @error("heure_deces")
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="mb-2 col-md-3">
            <label class="form-label">Lieu de survenance <span class="text-danger">*</span> </label>
            <select id="lieu_survenance_code" class="form-select form-control">
                {{-- cas des centres d'hygiènes --}}
                @if(Auth::user()->affectationactive()->institution->code_type_institution == "TPINS_0019")
                    <option disabled selected>Choisissez</option>
                    @foreach ($lieusurvenances as $lieusurvenance)
                        @if($lieusurvenance->code_lieu_survenance != "LSURV_0001" && $lieusurvenance->code_lieu_survenance != "LSURV_0004"&& $lieusurvenance->code_lieu_survenance != "LSURV_0005" && $lieusurvenance->code_lieu_survenance != "LSURV_0006")
                        <option value="{{ $lieusurvenance->code_lieu_survenance }}" {{ $dd->lieuSurvenance->code_lieu_survenance == $lieusurvenance->code_lieu_survenance ? "selected" : "" }}>{{ $lieusurvenance->lib_lieu_survenance }}</option>
                        @endif
                    @endforeach
                    {{-- cas des formations sanitaires --}}
                @elseif(Auth::user()->affectationactive()->institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins == "TCINS_0003")
                    @foreach ($lieusurvenances as $lieusurvenance)
                        @if($lieusurvenance->code_lieu_survenance == "LSURV_0001")
                            <option value="{{ $lieusurvenance->code_lieu_survenance }}" {{ $dd->lieuSurvenance->code_lieu_survenance == $lieusurvenance->code_lieu_survenance ? "selected" : "" }}>{{ $lieusurvenance->lib_lieu_survenance }}</option>
                        @endif
                    @endforeach
                {{-- @elseif ()
                @foreach ($lieusurvenances as $lieusurvenance)
                @if($lieusurvenance->code_lieu_survenance == "LSURV_0006")
                        <option value="{{ $lieusurvenance->code_lieu_survenance }}">{{ $lieusurvenance->lib_lieu_survenance }}</option>
                    @endif
                @endforeach --}}
                @else
                {{-- cas des pompes funebres et mairies --}}
                        <option disabled selected>Choisissez</option>
                    @foreach ($lieusurvenances as $lieusurvenance)
                        <option value="{{ $lieusurvenance->code_lieu_survenance }}" {{ $dd->lieuSurvenance->code_lieu_survenance == $lieusurvenance->code_lieu_survenance ? "selected" : "" }}>{{ $lieusurvenance->lib_lieu_survenance }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="mb-2 col-md-3">
            <label class="form-label">Lieu de décés <span class="text-danger">*</span></label>
            @if(Auth::user()->affectationactive()->institution->code_type_institution == "TPINS_0019"){{-- cas des centres d'hygiènes --}}
            <select id="code_lieu_deces" class="form-select form-control">
                <option disabled selected>Choisissez</option>
                @foreach ($lieuxDeces as $lieuD)
                    <option value="{{ $lieuD->code_localite }}" {{ $dd->lieu_deces == $lieuD->lib_localite ? "selected" : "" }}>{{ $lieuD->lib_localite }}</option>
                @endforeach
            </select>
            @else
            <input type="text" class="form-control  @error('lieu_deces') is-invalid @enderror" value="{{ $dd->lieu_deces }}" id="lieu_deces" name="lieu_deces" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()">
            @endif
        </div>
    </div>


    <div class="ligne">
        <h4>ADRESSE DU DEFUNT</h4>
    </div>
    <div class="row">
        <div class="mb-2 col-md-3">
            <label class="form-label">Pays<span class="text-danger"></span></label>
            <select id="domicile_pays_defunt" class="form-control">
                @foreach ($countries as $countrie)
                @if($dd->defunt->adresses->last() != null)
                    <option value="{{ $countrie->name }}" {{ $dd->defunt->adresses->last()->lib_pays == $countrie->name ? "selected" : "" }}>{{ $countrie->name }}</option>
                @else
                <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                @endif
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-md-3 domicile_ville_defunt">
            <label class="form-label">Commune/District<span class="text-danger"></span></label>
            <select class="form-control" id="domicile_ville_defunt">
                @if($dd->defunt->adresses->last() != null)
                    @foreach ($localites as $item)
                        <option value="{{ $item->code_localite }}" {{  $dd->defunt->adresses->last()->code_localite == $item->code_localite ? "selected" : "" }}>{{ $item->lib_localite }}</option>
                    @endforeach
                @else
                    <option value="">Selectionnez</option>
                    @foreach ($localites as $item)
                        <option value="{{ $item->code_localite }}">{{ $item->lib_localite }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="mb-2 col-md-3 autredomicile_ville_defunt">
            <label class="form-label">Ville<span class="text-danger"></span></label>
            <input type="text" id="autredomicile_ville_defunt" class="form-control form-control wide" placeholder="Libellé de la ville" onkeyup="this.value=this.value.toUpperCase()">
        </div>

        <div class="mb-2 col-md-3 domicile_arrondissement_defunt">
            <label class="form-label">Arr/Com. Urb<span class="text-danger"></span></label>
            <select class="form-control" id="domicile_arrondissement_defunt">
                @if($dd->defunt->adresses->last() != null)
                    @foreach ($arrondissement as $item)
                        <option value="{{ $item->code_localite }}" {{  $dd->defunt->adresses->last()->code_arrondissement_comurbaine == $item->code_localite ? "selected" : "" }}>{{ $item->lib_localite }}</option>
                    @endforeach
                @else
                <option value="">Selectionnez</option>
                    @foreach ($arrondissement as $item)
                        <option value="{{ $item->code_localite }}">{{ $item->lib_localite }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        @if($dd->defunt->adresses->last() != null)
            @if($dd->defunt->adresses->last()->lib_pays != "Congo")
            <div class="mb-2 col-md-3 autredomicile_ville_defunt">
                <label class="form-label">Ville<span class="text-danger"></span></label>
                <input type="text" id="autredomicile_ville_defunt" class="form-control form-control wide" placeholder="Libellé de la ville" onkeyup="this.value=this.value.toUpperCase()" value="{{ $dd->defunt->adresses->last()->lib_ville ?? "" }}">
            </div>
            @endif
        @endif
        <div class="mb-2 col-md-3 domicile_quartier_defunt">
            <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
            <select class="form-control" id="domicile_quartier_defunt">
                @if($dd->defunt->adresses->last() != null)
                    @foreach ($quartierVillages as $localite)
                        <option value="{{ $localite->code_localite }}" {{  $dd->defunt->adresses->last()->code_quartier_village  == $localite->code_localite ? "selected" : "" }}>{{ $localite->lib_localite }}</option>
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
            <select class="form-control" id="domicile_typevoie_defunt">
                @if($dd->defunt->adresses->last() != null)
                <option value="Avenue" {{ $dd->defunt->adresses->last()->type_voie == "Avenue" ? "selected" : "" }}>Avenue</option>
                <option value="Boulevard" {{ $dd->defunt->adresses->last()->type_voie == "Boulevard" ? "selected" : "" }}>Boulevard</option>
                <option value="Impasse" {{ $dd->defunt->adresses->last()->type_voie == "Impasse" ? "selected" : "" }}>Impasse</option>
                <option value="Rue" {{ $dd->defunt->adresses->last()->type_voie == "Rue" ? "selected" : "" }}>Rue</option>
                <option value="Autre" {{ $dd->defunt->adresses->last()->type_voie == "Autre" ? "selected" : "" }}>Autre</option>
                @else
                <option value="">Selectionnez</option>
                <option value="Avenue">Avenue</option>
                <option value="Boulevard">Boulevard</option>
                <option value="Impasse">Impasse</option>
                <option value="Rue">Rue</option>
                <option value="Autre">Autre</option>
                @endif>
            </select>
        </div>
        <div class="mb-2 col-md-3">
            <label class="form-label">N° voie<span class="text-danger"></span></label>
            <input type="text" class="form-control" id="domicile_numero_defunt" placeholder="N° voie" value="{{ $dd->defunt->adresses->last()->numero_rue ?? "" }}">
        </div>
        <div class="mb-2 col-md-3">
            <label class="form-label">Nom voie<span class="text-danger"></span></label>
            <input type="text" class="form-control" id="domicile_nomvoie_defunt" placeholder="Nom voie" style="text-transform: capitalize" value="{{ $dd->defunt->adresses->last()->nom_voie ?? "" }}">
        </div>
    </div>
</section>
