<form name="contactUsForm" id="contactUsForm"  method="post" action="javascript:void(0)" class="validation-wizard wizard-circle" enctype="multipart/form-data">
    <!-- Step 1 -->
    <h6>Enfant</h6>
    <div class="d-none">
        <input type="text" value="DECLARATION DE NAISSANCE" id="type_declaration">
        <input type="text" value="Personne morale" id="type_declarant">
        <input type="text" value="Enfant trouvé" id="personne_declaree">
    </div>
    <section>
		 <div class="ligne">
                <h4>INFORMATIONS SUR L'IDENTITE</h4>
            </div>
        <div class="row">

            <div class="mb-2 col-md-4">
                <label class="form-label">Nom(s) enfant <span class="text-danger">*</span></label>
                <input type="text" name="nom_enfant" value="{{ $dummy }}"  class="form-control required  @error('nom_enfant') is-invalid @enderror"  placeholder="Nom enfant" readonly id="nom_enfant">
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Prénom(s) enfant</label>
                <input type="text" class="form-control" value="{{ $dummy }}" placeholder="Prénom enfant" id="prenom_enfant" onkeyup="verif_lettre(this);" style="text-transform: capitalize;">
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Sexe <span class="text-danger">*</span></label>
                <select id="sexe_enfant" name="sexe_enfant" class="form-control required  @error('sexe_enfant') is-invalid @enderror">
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
            <div class="mb-2 col-md-3">
                <label class="form-label">Date de naissance <span class="text-danger">*</span></label>
                <input type="date" name="date_naissance_enfant" class="form-control" id="date_naissance_enfant">
            </div>

            <div class="mb-2 col-md-3">
                <label class="form-label">Lieu de naissance <span class="text-danger">*</span></label>
                <input type="text" class="form-control d-none" id="lieu_naissance_enfant" value="NON DECLARE">
                <select id="code_localite_enfant" class="form-control required">
                    @foreach ($localites as $localite)
                        @if($localite->code_localite == "LOC_4250")
                            <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Heure de naissance <span class="text-danger">*</span></label>
                <input type="time" value="00:00" name="heure_naissance_enfant" class="form-control"  id="heure_naissance_enfant">
            </div>
			@if(Auth::user()->affectationactive()->institution->TypeInstitution->code_type_institution == "TPINS_0002")
               <div class="mb-2 col-md-3">
                <label class="form-label">Formation sanitaire de naissance</label>
                <input type="text" value="{{ $dummy }}" id="formation_sanitaire_naissance" name="formation_sanitaire_naissance" class="form-control" readonly>
            </div>
            @endif
            <div class="mb-2 col-md-3">
                <label class="form-label">Lieu de survenance <span class="text-danger">*</span></label>
                <select id="code_lieu_survenance" class="form-control required" readonly>
                    @foreach ($lieuSurvenances as $item)
                        <option value="{{ $item->code_lieu_survenance }}" {{ $item->code_lieu_survenance == "LSURV_0007" ? "selected" : "" }}>{{ $item->lib_lieu_survenance }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Date déclaration</label>
                <input type="text" value="{{ date("d-m-Y") }}" class="form-control">
                <input type="hidden" value="{{ date("Y-d-m") }}" id="date_heure_declaration">
            </div>
        </div>
		<div class="ligne">
                <h4>AUTRES INFORMATIONS</h4>
            </div>
        <div class="row">
            <div class="mb-2 col-md-4 d-none">
                <label class="form-label">Situation matrimoniale des parents<span class="text-danger">*</span></label>
                <select id="code_situation_matrimoniale" name="code_situation_matrimoniale" class="form-control required  @error('code_situation_matrimoniale') is-invalid @enderror" readonly>
                    <option value="SMAT_0008">{{ $dummy }}</option>
                </select>
            </div>
            <div class="mb-2 col-md-4 d-none">
                <label class="form-label">Nombre d'enfants (y compris le sujet)<span class="text-danger">*</span></label>
                <input type="text" value="1" name="nombre_enfants"class="form-control required  @error('nombre_enfants') is-invalid @enderror " placeholder="0" id="nombre_enfants" readonly>
            </div>

            <div class="mb-2 col-md-3">
                <label class="form-label">Lieu de placement <span class="text-danger">*</span></label>
                <input type="text" id="lieu_placement" class="form-control" value="{{ old("lieu_placement") }}">
            </div>
            <div class="mb-2 col-md-3 d-none">
                <label class="form-label">Extrait main courante <span class="text-danger">*</span></label>
                <input type="file" id="extrait_main_courante" class="form-control" value="{{ old("extrait_main_courante") }}">
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">num du fiche de placement <span class="text-danger">*</span></label>
                <input type="text" id="num_fiche_placement" class="form-control" value="{{ old("num_fiche_placement") }}">
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label" title="num du jugement de placement provisoir">num du jugement de placement <span class="text-danger">*</span></label>
                <input type="text" id="num_jugement_placement_provisoir" class="form-control" value="{{ old("num_jugement_placement_provisoir") }}">
            </div>
            <div class="mb-2 col-md-4 d-none">
                <label class="form-label">Statut <span class="text-danger">*</span></label>
                <select id="statut_personne_enfant" class="form-control">
                    <option selected value="VIVANT">Vivant</option>
                    <option value="DECEDE">Décédé</option>
                </select>
            </div>
        </div>
    </section>
    @include("naissance::enfant-trouver.parent")
</form>
