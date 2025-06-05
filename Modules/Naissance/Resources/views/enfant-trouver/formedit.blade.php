<form name="contactUsForm" id="contactUsForm" method="POST" action="javascript:void(0)" class="validation-wizard wizard-circle">
    @method("PUT")
    @csrf
    <!-- Step 1 -->
    <h6>Enfant</h6>
    <section>
	<div class="ligne">
                <h4>INFORMATIONS SUR L'IDENTITE</h4>
            </div>
        <div class="row">
            <div class="mb-2 col-md-4">
                <label class="form-label">Nom(s) enfant <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nom_enfant" placeholder="Nom enfant" value="{{ $dn->enfant->nom }}" id="nom_enfant">

            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Prénom(s) enfant</label>
                <input type="text" class="form-control" name="prenom_enfant" value="{{ $dn->enfant->prenom }}" placeholder="Prénom enfant" id="prenom_enfant">

            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Sexe <span class="text-danger">*</span></label>
                <select id="sexe_enfant" name="sexe_enfant" class="form-control form-control wide">
                    <option value="M" {{"M"==$dn->enfant->sexe ? "selected":"" }}>Masculin</option>
                    <option value="F" {{"F"==$dn->enfant->sexe ? "selected":"" }}>Feminin</option>
                </select>
            </div>
        </div>
		<div class="ligne">
                <h4>INFORMATIONS SUR LA NAISSANCE</h4>
            </div>
        <div class="row">
            <div class="mb-2 col-md-4">
                <label class="form-label">Date de naissance <span class="text-danger">*</span></label>
                <input type="date" name="date_naissance_enfant" value={{ $dn->enfant->date_naissance }} max="<?php echo date("Y-m-d"); ?>" min="<?php $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 3 month'));?>" onchange="compare()" class="form-control" id="date_naissance_enfant">

            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Lieu de naissance <span class="text-danger">*</span></label>
                <input type="text" name="lieu_naissance_enfant" value="{{ $dn->enfant->lieu_naissance }}" class="form-control" id="lieu_naissance_enfant">

            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Heure de naissance <span class="text-danger">*</span></label>
                <input type="time" name="heure_naissance_enfant" value="{{ date(':i', strtotime($dn->enfant->date_naissance)) }}" class="form-control"  id="heure_naissance_enfant">

            </div>
			
        </div>
		
		<div class="ligne">
                <h4>AUTRES INFORMATIONS</h4>
            </div>
        <div class="row">

            <div class="mb-2 col-md-4">
                <label class="form-label">Situation matrimoniale des parents</label>
                <select id="code_situation_matrimoniale" name="code_situation_matrimoniale" class="form-control form-control wide">

                    <option value={{ $dn->code_situation_mat  }}>{{ $dn->sitMatParent->lib_situation_matrimoniale   }}</option>
                    @foreach ($situationMatrimoniales as $item)
                        <option value="{{ $item->code_situation_matrimoniale }}">{{ $item->lib_situation_matrimoniale }}</option>
                    @endforeach

                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Nombre d'enfants (y compris le sujet)</label>
                <input type="number" name="nombre_enfant"  value={{$dn->nombre_enfant}} min="1" class="form-control" placeholder="0" id="nombre_enfants">

            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Date déclaration</label>
                <input type="date" name="date_heure_declaration" value={{ $dn->date_heure_declaration }} max="<?php echo date("Y-m-d"); ?>" min="<?php $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 3 month'));?>" onchange="compare()" class="form-control" id="date_heure_declaration">
            </div>
            @if(Auth::user()->affectationactive()->institution->TypeInstitution->code_type_institution == "TPINS_0002")
               <div class="mb-2 col-md-4">
                <label class="form-label">Formation sanitaire de naissance</label>
                <input type="text" id="formation_sanitaire_naissance" name="formation_sanitaire_naissance" class="form-control" value="{{ $dn->formation_sanitaire_naissance }}">
            </div>
            @endif
			
			<div class="mb-2 col-md-4" style="visibility: hidden">
                <label class="form-label" >Lieu de survenance <span class="text-danger">*</span></label>
                <select id="code_lieu_survenance" class="form-control form-control wide">
                       <!-- <option disabled selected>Choisissez</option>-->
                    @foreach ($lieuSurvenances as $item)
                        <option value="{{ $item->code_lieu_survenance }}">{{ $item->lib_lieu_survenance }}</option>
                    @endforeach
                </select>
            </div>
           
        </div>
    </section>
    <!-- Step 2 -->
    <h6>Père</h6>
    <section>
        <div class="d-flex justify-content-end align-items-center">
            <button type="button" id="clear_pere" class="btn btn-danger  text-white" ></i> Vider </button>
            <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".search-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche du père</button>
        </div>
        <hr>
		<div class="ligne">
                <h4>INFORMATIONS PERSONNELLES</h4>
            </div>
        <div class="row">
            <div class="mb-2 col-md-4">
                <input type="hidden" id="code_pere">
                <label class="form-label">Nom(s) père <span class="text-danger">*</span></label>
                <input type="text" name="nom_pere" value={{$dn->pere->nom}} class="form-control"lass="form-control"  placeholder="Nom du père" id="nom_pere">
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Prénom(s) du père </label>
                <input type="text" name="prenom_pere" value={{$dn->pere->prenom}} class="form-control" placeholder="Prénom du père" id="prenom_pere">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Date de naissance du père<span class="text-danger">*</span></label>
                <input type="date" max="<?php echo date('Y-m-d', strtotime($jour. ' - 14 years')); ?>" min="<?php echo date('Y-m-d', strtotime($jour. ' - 100 years')); ?>" onchange="compare()"
                name="date_naissance_pere" value={{$dn->pere->date_naissance}} class="form-control required  @error('date_naissance_pere') is-invalid @enderror " id="date_naissance_pere">
                <input type="checkbox" id="type_date_naissance_pere" value="ESTIME" name="type_date_naissance_pere"><label for="type_date_naissance_pere">date estimée</label>
            </div>
        </div>
        <div class="row">
            <div class="mb-2 col-md-4">
                <label class="form-label">Lieu de naissance père</label>
                <input type="text" name="lieu_naissance_pere" value={{$dn->pere->lieu_naissance}} class="form-control required  @error('lieu_naissance_pere') is-invalid @enderror " id="lieu_naissance_pere" placeholder="Lieu de naissance">
            </div>
			
			<div class="mb-2 col-md-4">
                <label class="form-label">Nationalité du père<span class="text-danger">*</span></label>
                <select name="code_nationalite_pere" id="code_nationalite_pere" class="form-control form-control wide">
                    <option value={{ $dn->pere->code_nationalite }}>{{ $dn->pere->nationalite->lib_nationalite }}</option>
                    @foreach ($nationalites as $nationalite)
                        <option value="{{ $nationalite->code_nationalite }}">{{ $nationalite->lib_nationalite }}</option>
                    @endforeach
                </select>
            </div>
			
            <div class="mb-2 col-md-4">
                <label class="form-label">Profession du père<span class="text-danger">*</span></label>
                <select id="profession_pere" name="profession_pere" class="form-control form-control wide">

                    <option value="{{ $dn->pere->code_profession }}">{{ $dn->pere->profession->lib_profession }}</option>
                    @foreach ($professions as $item)
                        <option value="{{ $item->code_profession }}">{{ $item->lib_profession }}</option>
                    @endforeach
                </select>
            </div>
			 <div class="mb-2 col-md-4">
                <label class="form-label">Niveau d instruction du père</label>
                <select name="niveau_instruction_pere" id="niveau_instruction_pere" class="form-control form-control wide">
                    <option value={{ $dn->pere->niveau_instruction }}>{{  $dn->pere->niveau_instruction }}</option>
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
                            <option value="{{ $item->code_type_document }}" {{ $item->code_type_document==$dn->pere->document->code_type_document ? "selected":"" }}>{{ $item->lib_type_document  }}</option>
                        @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Numéro pièce d'identité</label>
                <input type="text" id="numero_document_pere" class="form-control form-control wide" value="{{ $dn->pere->document->numero_document }}" placeholder="Numéro du document">
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Statut <span class="text-danger">*</span></label>
                <select id="statut_personne_pere" name="statut_personne_pere" class="form-control">
                    <option selected value="VIVANT">Vivant</option>
                    <option value="DECEDE">Décédé</option>
                </select>
            </div>
            
        </div>
        <div class="row">
            {{-- <div class="mb-2 col-md-2">
                <label class="form-label">Numéro<span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_numero_pere" value="{{ $dn->pere->adresses[0]->numero_rue }}" placeholder="numero parcelle">

            </div>
            <div class="mb-2 col-md-2">
                <label class="form-label" title="Rue/Avenue/camps">Rue <span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_rue_pere" value="{{ $dn->pere->adresses[0]->avenue }}" placeholder="Rue/Avenue/camps">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_quartier_pere" value="{{ $dn->pere->adresses[0]->quartier }}" placeholder="Quartier/Village">

            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Arrondissement/District/Commune<span class="text-danger"></span></label>
                <input type="text" name="domicile_arrondissement_pere" class="form-control" id="domicile_arrondissement_pere" value="{{ $dn->pere->adresses[0]->code_arrondissement }}" placeholder="Arrondissement/District/Commune">
            </div> --}}

				<div class="ligne">
                <h4>ADRESSE & CONTACTS</h4>
            </div>
            <div class="mb-2 col-md-2">
                <label class="form-label">Pays<span class="text-danger"></span></label>
                <select id="domicile_pays_pere" class="form-control required">
                    {{-- <option value="{{ $dn->pere->adresses[0]->lib_pays }}">{{ $dn->pere->adresses[0]->lib_pays }}</option> --}}
                    @foreach ($countries as $countrie)
                        <option value="{{ $countrie->name }}" {{ $dn->pere->adresses[0]->lib_pays != $countrie->name ? '':'selected' }}>{{ $countrie->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-2">
                <label class="form-label">Ville/Département<span class="text-danger"></span></label>
                <span id="departementcongo_pere">
                    <select class="form-control" name="domicile_ville_pere" id="domicile_ville_pere">
                        {{-- <option value="">Choisir</option> --}}
                        @foreach ($departements as $departement)
                            <option value="{{ $departement->lib_departement }}" {{ $dn->pere->adresses[0]->lib_ville != $departement->lib_departement  ? '':'selected' }}>{{ $departement->lib_departement }}</option>
                        @endforeach
                    </select>
                </span>
                <span id="autredepartement_pere">
                    <input type="text" value="{{ $dn->pere->adresses[0]->lib_ville }}" class="form-control" id="domicile_ville_pere" placeholder="Ville ou département">
                </span>
            </div>
            <div class="mb-2 col-md-2">
                <label class="form-label">District/Arrondissement<span class="text-danger"></span></label>
                <input type="text" value="{{ $dn->pere->adresses[0]->district_arrondissement }}" class="form-control" id="domicile_district_pere" placeholder="District ou arrondissement">
            </div>
            <div class="mb-2 col-md-2">
                <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                <input type="text" class="form-control" value="{{ $dn->pere->adresses[0]->quartier }}" id="domicile_quartier_pere" placeholder="Quartier ou village">
            </div>
            <div class="mb-2 col-md-2">
                <label class="form-label">Type voie<span class="text-danger"></span></label>
                <select class="form-control" value="{{ $dn->pere->adresses[0]->type_voie }}" id="domicile_typevoie_pere">
                    <option value="Avenue" {{ $dn->pere->adresses[0]->type_voie == 'Avenue' ? 'selected':''  }}>Avenue</option>
                    <option value="Boulevard" {{ $dn->pere->adresses[0]->type_voie == 'Boulevard' ? 'selected':''  }}>Boulevard</option>
                    <option value="Impasse" {{ $dn->pere->adresses[0]->type_voie == 'Impasse' ? 'selected':''  }}>Impasse</option>
                    <option value="Rue" {{ $dn->pere->adresses[0]->type_voie == 'Rue' ? 'selected':''  }}>Rue</option>
                    <option value="Autre" {{ $dn->pere->adresses[0]->type_voie == 'Autre' ? 'selected':''  }}>Autre</option>
                </select>
            </div>
            <div class="mb-2 col-md-2">
                <label class="form-label">N° voie<span class="text-danger"></span></label>
                <input type="text" class="form-control" value="{{ $dn->pere->adresses[0]->numero_rue }}" id="domicile_numero_pere" placeholder="N° voie">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Nom voie<span class="text-danger"></span></label>
                <input type="text" class="form-control" value="{{ $dn->pere->adresses[0]->avenue }}" id="domicile_nomvoie_pere" placeholder="Nom voie">
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Téléphone père<span class="text-danger">*</span></label>
                <div class="row">
                    <div class="col-md-6">
                        <select name="code_pays_pere" id="code_pays_pere" class="form-control">
                            @forelse ($countries as $code)
                                <option value="{{ $code->dial_code }}">({{ $code->dial_code }}) {{ $code->name }}</option>
                            @empty

                            @endforelse

                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="number" min="0" minlength="9" maxlength="10" value={{$dn->pere->telephone}} id="telephone_pere" name="telephone_pere" class="form-control @error('telephone_pere') is-invalid @enderror " placeholder="Téléphone père">
                    </div>
                </div>
            </div>
           
            <div class="mb-2 col-md-4">
                <label class="form-label">Email</label>
                <input type="email" id="email_pere" class="form-control" name="email_pere" placeholder="Email père">
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
                <label class="form-label">Nom(s) Mère <span class="text-danger">*</span></label>
                <input type="text" class="form-control required" value="{{$dn->mere->nom}}" name="nom_mere"  placeholder="Nom Mère" id="nom_mere">
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Prénom(s) Mère </label>
                <input type="text" class="form-control" value="{{$dn->mere->prenom}}" placeholder="Prénom du Mère" id="prenom_mere">

            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Date de naissance Mère<span class="text-danger">*</span></label>
                <input type="date" value="{{$dn->mere->date_naissance}}" max="<?php echo date('Y-m-d', strtotime($jour. ' - 14 years')); ?>" min="<?php echo date('Y-m-d', strtotime($jour. ' - 100 years')); ?>" onchange="compare()"
                name="date_naissance_mere" class="form-control required  @error('date_naissance_mere') is-invalid @enderror " id="date_naissance_mere">
                <input type="checkbox" id="type_date_naissance_mere" value="ESTIME" name="type_date_naissance_mere"><label for="type_date_naissance_mere">date estimée</label>
            </div>
        </div>
        <div class="row">
            <div class="mb-2 col-md-4">
                <label class="form-label">Lieu de naissance Mère</label>
                <input type="text" name="lieu_naissance_mere" value="{{$dn->mere->lieu_naissance}}" class="form-control required  @error('lieu_naissance_mere') is-invalid @enderror " id="lieu_naissance_mere" placeholder="Lieu de naissance">
            </div>
			<div class="mb-2 col-md-4">
                <label class="form-label">Nationalité Mère<span class="text-danger">*</span></label>
                <select id="code_nationalite_mere" name="code_nationalite_mere" class="form-control required  @error('code_nationalite_mere') is-invalid @enderror ">
                    <option value={{ $dn->mere->code_nationalite }}>{{ $dn->mere->nationalite->lib_nationalite }}</option>
                    @foreach ($nationalites as $nationalite)
                        <option value="{{ $nationalite->code_nationalite }}">{{ $nationalite->lib_nationalite }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Profession Mère<span class="text-danger">*</span></label>
                <select id="profession_mere" class="form-control form-control wide">
                        <option value="{{ $dn->mere->code_profession }}">{{ $dn->mere->profession->lib_profession }}</option>
                        @foreach ($professions as $item)
                            <option value="{{ $item->code_profession }}">{{ $item->lib_profession }}</option>
                        @endforeach
                </select>
            </div>
			
			 <div class="mb-2 col-md-4">
                <label class="form-label">Niveau d'instruction de la Mère</label>
                <select id="niveau_instruction_mere" class="form-control form-control wide">
                    <option value={{ $dn->mere->niveau_instruction }}>{{  $dn->mere->niveau_instruction }}</option>
                    @foreach ($instructions as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
            </div>
			     <div class="mb-2 col-md-4">
                <label class="form-label">Type pièce d'identité</label>
                <select id="code_type_document_mere" class="form-control form-control wide">
                    <option value={{ $dn->mere->document->code_type_document }}>{{ $dn->mere->document->code_type_document }}</option>
                    @foreach ($typedocuments as $item)
                        <option value="{{ $item->code_type_document }}" {{ $item->code_type_document == $dn->mere->document->code_type_document ? 'selected':'' }}>{{ $item->lib_type_document  }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Numéro pièce d'identité</label>
                <input type="text" id="numero_document_mere" value="{{ $dn->mere->document->numero_document }}" class="form-control form-control wide" placeholder="Numéro du document">
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Statut <span class="text-danger">*</span></label>
                <select id="statut_personne_mere" name="statut_personne_mere" class="form-control required ">
                    <option selected value="VIVANT">Vivante</option>
                    <option value="DECEDE">Décédée</option>
                </select>
            </div>
            
        </div>
        <div class="row">
            {{-- <div class="mb-2 col-md-2">
                <label class="form-label">Numéro<span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_numero_mere" value="{{ $dn->mere->adresses[0]->numero_rue }}" placeholder="numéro parcelle">

            </div>
            <div class="mb-2 col-md-2">
                <label class="form-label" title="Rue/Avenue/camps">Rue <span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_rue_mere" value="{{ $dn->mere->adresses[0]->avenue }}" placeholder="Rue/Avenue/camps">

            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_quartier_mere" value="{{ $dn->mere->adresses[0]->quartier }}" placeholder="Quartier/Village">

            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Arrondissement/District/Commune<span class="text-danger"></span></label>
                <input type="text" name="domicile_arrondissement_mere" class="form-control" id="domicile_arrondissement_mere" value="{{ $dn->mere->adresses[0]->code_arrondissement }}" placeholder="Arrondissement/District/Commune">
            </div> --}}
			
			
<div class="ligne">
                <h4>ADRESSE & CONTACTS</h4>
            </div>

            <div class="mb-2 col-md-2">
                <label class="form-label">Pays<span class="text-danger"></span></label>
                <select id="domicile_pays_mere" class="form-control required">
                    {{-- <option value="{{ $dn->mere->adresses[0]->lib_pays }}">{{ $dn->mere->adresses[0]->lib_pays }}</option> --}}
                    @foreach ($countries as $countrie)
                        <option value="{{ $countrie->name }}" {{ $dn->mere->adresses[0]->lib_pays != $countrie->name ? '':'selected' }}>{{ $countrie->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-2">
                <label class="form-label">Ville/Département<span class="text-danger"></span></label>
                <span id="departementcongo_mere">
                    <select class="form-control" name="domicile_ville_mere" id="domicile_ville_mere">
                        {{-- <option value="">Choisir</option> --}}
                        @foreach ($departements as $departement)
                            <option value="{{ $departement->lib_departement }}" {{ $dn->mere->adresses[0]->lib_ville != $departement->lib_departement  ? '':'selected' }}>{{ $departement->lib_departement }}</option>
                        @endforeach
                    </select>
                </span>
                <span id="autredepartement_mere">
                    <input type="text" value="{{ $dn->mere->adresses[0]->lib_ville }}" class="form-control" id="domicile_ville_mere" placeholder="Ville ou département">
                </span>
            </div>
            <div class="mb-2 col-md-2">
                <label class="form-label">District/Arrondissement<span class="text-danger"></span></label>
                <input type="text" value="{{ $dn->mere->adresses[0]->district_arrondissement }}" class="form-control" id="domicile_district_mere" placeholder="District ou arrondissement">
            </div>
            <div class="mb-2 col-md-2">
                <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                <input type="text" class="form-control" value="{{ $dn->mere->adresses[0]->quartier }}" id="domicile_quartier_mere" placeholder="Quartier ou village">
            </div>
            <div class="mb-2 col-md-2">
                <label class="form-label">Type voie<span class="text-danger"></span></label>
                <select class="form-control" value="{{ $dn->mere->adresses[0]->type_voie }}" id="domicile_typevoie_mere">
                    <option value="Avenue" {{ $dn->mere->adresses[0]->type_voie == 'Avenue' ? 'selected':''  }}>Avenue</option>
                    <option value="Boulevard" {{ $dn->mere->adresses[0]->type_voie == 'Boulevard' ? 'selected':''  }}>Boulevard</option>
                    <option value="Impasse" {{ $dn->mere->adresses[0]->type_voie == 'Impasse' ? 'selected':''  }}>Impasse</option>
                    <option value="Rue" {{ $dn->mere->adresses[0]->type_voie == 'Rue' ? 'selected':''  }}>Rue</option>
                    <option value="Autre" {{ $dn->mere->adresses[0]->type_voie == 'Autre' ? 'selected':''  }}>Autre</option>
                </select>
            </div>
            <div class="mb-2 col-md-2">
                <label class="form-label">N° voie<span class="text-danger"></span></label>
                <input type="text" class="form-control" value="{{ $dn->mere->adresses[0]->numero_rue }}" id="domicile_numero_mere" placeholder="N° voie">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Nom voie<span class="text-danger"></span></label>
                <input type="text" class="form-control" value="{{ $dn->mere->adresses[0]->avenue }}" id="domicile_nomvoie_mere" placeholder="Nom voie">
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Téléphone Mère<span class="text-danger">*</span></label>
                <div class="row">
                    <div class="col-md-6">
                        <select name="code_pays_mere" id="code_pays_mere" class="form-control">
                            @forelse ($countries as $code)
                                <option value="{{ $code->dial_code }}">({{ $code->dial_code }}) {{ $code->name }}</option>
                            @empty

                            @endforelse

                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="number" value="{{ $dn->mere->telephone }}" min="0" minlength="9" maxlength="10" id="telephone_mere" name="telephone_mere" class="form-control @error('telephone_mere') is-invalid @enderror " placeholder="Téléphone Mère">
                    </div>
                </div>
            </div>
           
            <div class="mb-2 col-md-4">
                <label class="form-label">Email</label>
                <input type="email" id="email_mere" class="form-control" name="email_mere" placeholder="Email mère">
            </div>
       
        </div>
    </section>
    <!-- Step 4 -->
    <h6>Déclarant</h6>
    <section>
        <div class="d-flex justify-content-end align-items-center" id="search_declarant">
            <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".declarant-search-modal-lg"  ><i class="fa fa-search"></i> Faire une recherche du déclarant</button>
        </div>
        <hr>
        <div class="row">
            <div class="mb-2 col-sm-4" id="hide_pere">
                <label for="dewey">Père</label>
                <input type="radio" id="peredeclarant" name="autredeclarant" checked value="pere">
            </div>


            <div class="mb-2 col-sm-4" id="hide_mere">
                <label for="dewey">Mère</label>
                <input type="radio" id="meredeclarant" name="autredeclarant" value="mere">
            </div>

            <div class="mb-2 col-sm-4">
                <label for="dewey">Autre</label>
                <input type="radio" id="autredeclarant" name="autredeclarant"  value="autre">
            </div>


			<div class="ligne">
                <h4>INFORMATIONS PERSONNELLES</h4>
            </div>
            <div class="mb-2 col-md-4">
                <input type="hidden" id="code_declarant">
                <label class="form-label">Nom(s) déclarant <span class="text-danger">*</span></label>
                <input type="text" class="form-control required" value="{{$dn->declarant->nom}}"  placeholder="Nom du déclarant" id="nom_declarant" name="nom_declarant">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Prénom(s) du déclarant </label>
                <input type="text" class="form-control" value="{{$dn->declarant->prenom}}" placeholder="Prénom du déclarant" id="prenom_declarant" name="prenom_declarant">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Sexe du déclarant<span class="text-danger">*</span></label>
                <select id="sexe_declarant" name="sexe_declarant" class="form-control form-control wide">

                    <option value="M" {{"M"==$dn->enfant->sexe ? "selected":"" }}>Masculin</option>
                    <option value="F" {{"F"==$dn->enfant->sexe ? "selected":"" }}>Feminin</option>

                </select>
            </div>
        </div>
        <div class="row">
            <div class="mb-2 col-md-4">
                <label class="form-label">Date de naissance du déclarant<span class="text-danger">*</span></label>
                <input type="date" value="{{$dn->declarant->date_naissance}}" name="date_naissance_declarant" max="<?php $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 18 years'));?>" min="<?php echo date('Y-m-d', strtotime($jour. ' - 100 years')); ?>"
                class="form-control required  @error('date_naissance_declarant') is-invalid @enderror " id="date_naissance_declarant">
                <input type="checkbox" id="type_date_naissance_declarant" value="ESTIME" name="type_date_naissance_declarant"><label for="type_date_naissance_declarant">date estimée</label>

            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Lieu de naissance du déclarant</label>
                <input type="text" class="form-control" value="{{$dn->declarant->lieu_naissance}}" name="lieu_naissance_declarant" id="lieu_naissance_declarant" placeholder="Lieu de naissance">
            </div>
			<div class="mb-2 col-md-4">
                <label class="form-label">Nationalité du déclarant<span class="text-danger">*</span></label>
                <select id="code_nationalite_declarant" name="code_nationalite_declarant" class="form-control required  @error('code_nationalite_declarant') is-invalid @enderror ">
                    <option value={{ $dn->declarant->code_nationalite }}>{{ $dn->declarant->nationalite->lib_nationalite }}</option>
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
                    <option value={{ $dn->code_filiation }}>{{ $dn->filiation->lib_filiation }}</option>

                    @foreach ($filiations as $item)                                                                                                                                                                                                        
                        <option value="{{ $item->code_filiation }}">{{ $item->lib_filiation }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-2 col-md-4">
                <label class="form-label">Profession du déclarant</label>
                <select id="profession_declarant" name="profession_declarant" class="form-control required  @error('profession_declarant') is-invalid @enderror ">
                    <option value="{{ $dn->declarant->code_profession }}">{{ $dn->declarant->profession->lib_profession }}</option>
                    @foreach ($professions as $item)
                        <option value="{{ $item->code_profession }}">{{ $item->lib_profession }}</option>
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
            {{-- <div class="mb-2 col-md-2">
                <label class="form-label">Numéro<span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_numero_declarant" value="{{ $dn->declarant->adresses[0]->numero_rue }}" placeholder="numero parcelle">

            </div>
            <div class="mb-2 col-md-2">
                <label class="form-label" title="Rue/Avenue/camps">Rue <span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_rue_declarant" value="{{ $dn->declarant->adresses[0]->avenue }}" placeholder="Rue/Avenue/camps">

            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_quartier_declarant" value="{{ $dn->declarant->adresses[0]->quartier }}" placeholder="Quartier/Village">

            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Arrondissement/District/Commune<span class="text-danger"></span></label>
                <input type="text" class="form-control" name="domicile_arrondissement_declarant" id="domicile_arrondissement_declarant" value="{{ $dn->declarant->adresses[0]->code_arrondissement }}" placeholder="Arrondissement/District/Commune">
            </div> --}}

<div class="ligne">
                <h4>ADRESSE & CONTACTS</h4>
            </div>
            <div class="mb-2 col-md-2">
                <label class="form-label">Pays<span class="text-danger"></span></label>
                <select id="domicile_pays_declarant" class="form-control required">
                    {{-- <option value="{{ $dn->declarant->adresses[0]->lib_pays }}">{{ $dn->declarant->adresses[0]->lib_pays }}</option> --}}
                    @foreach ($countries as $countrie)
                        <option value="{{ $countrie->name}}" {{ $dn->declarant->adresses[0]->lib_pays != $countrie->name ? '':'selected' }}>{{ $countrie->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-2">
                <label class="form-label">Ville/Département<span class="text-danger"></span></label>
                <span id="departementcongo_declarant">
                    <select class="form-control" name="domicile_ville_declarant" id="domicile_ville_declarant">
                        {{-- <option value="">Choisir</option> --}}
                        @foreach ($departements as $departement)
                            <option value="{{ $departement->lib_departement }}" {{ $dn->declarant->adresses[0]->lib_ville != $departement->lib_departement  ? '':'selected' }}>{{ $departement->lib_departement }}</option>
                        @endforeach
                    </select>
                </span>
                <span id="autredepartement_declarant">
                    <input type="text" value="{{ $dn->declarant->adresses[0]->lib_ville }}" class="form-control" id="domicile_ville_declarant" placeholder="Ville ou département">
                </span>
            </div>
            <div class="mb-2 col-md-2">
                <label class="form-label">District/Arrondissement<span class="text-danger"></span></label>
                <input type="text" value="{{ $dn->declarant->adresses[0]->district_arrondissement }}" class="form-control" id="domicile_district_declarant" placeholder="District ou arrondissement">
            </div>
            <div class="mb-2 col-md-2">
                <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                <input type="text" class="form-control" value="{{ $dn->declarant->adresses[0]->quartier }}" id="domicile_quartier_declarant" placeholder="Quartier ou village">
            </div>
            <div class="mb-2 col-md-2">
                <label class="form-label">Type voie<span class="text-danger"></span></label>
                <select class="form-control" id="domicile_typevoie_declarant">
                    <option value="Avenue" {{ $dn->declarant->adresses[0]->type_voie == 'Avenue' ? 'selected':''  }}>Avenue</option>
                    <option value="Boulevard" {{ $dn->declarant->adresses[0]->type_voie == 'Boulevard' ? 'selected':''  }}>Boulevard</option>
                    <option value="Impasse" {{ $dn->declarant->adresses[0]->type_voie == 'Impasse' ? 'selected':''  }}>Impasse</option>
                    <option value="Rue" {{ $dn->declarant->adresses[0]->type_voie == 'Rue' ? 'selected':''  }}>Rue</option>
                    <option value="Autre" {{ $dn->declarant->adresses[0]->type_voie == 'Autre' ? 'selected':''  }}>Autre</option>
                </select>
            </div>
            <div class="mb-2 col-md-2">
                <label class="form-label">N° voie<span class="text-danger"></span></label>
                <input type="text" class="form-control" value="{{ $dn->declarant->adresses[0]->numero_rue }}" id="domicile_numero_declarant" placeholder="N° voie">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Nom voie<span class="text-danger"></span></label>
                <input type="text" class="form-control" value="{{ $dn->declarant->adresses[0]->avenue }}" id="domicile_nomvoie_declarant" placeholder="Nom voie">
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Téléphone déclarant</label>

                <div class="row">
                    <div class="col-md-6">
                        <select name="code_pays_declarant" id="code_pays_declarant" class="form-control required">
                            @forelse ($countries as $code)
                                <option value="{{ $code->dial_code }}">({{ $code->dial_code }}) {{ $code->name }}</option>
                            @empty

                            @endforelse

                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="number" value="{{ $dn->declarant->telephone }}" min="0" minlength="9" maxlength="9" id="telephone_declarant" name="telephone_declarant" class="form-control required  @error('statut_personne_mere') is-invalid @enderror " placeholder="Téléphone déclarant">
                    </div>
                </div>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Email</label>
                <input type="email" id="email_declarant" class="form-control" name="email_declarant" placeholder="Email déclarant">
            </div>
           
        </div>
    </section>

</form>
