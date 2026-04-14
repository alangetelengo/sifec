    <!-- Step 2 -->
    <h6>Epouse</h6>

    <section>
        <div class="row">
			<div class="d-flex justify-content-end align-items-center">
                <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".epouse-search-modal-lg"><i class="fa fa-search"></i> Faire une recherche de l'épouse</button> &nbsp;
				<button type="button" id="clear_epouse" class="btn btn-danger text-white"><i class="fa fa-trash"></i> Vider </button>
			</div>

			<!-- Zone d'affichage de l'identité trouvée pour l'épouse -->
			<div class="col-12 mt-3" id="identite_trouvee_epouse" style="display: none;">
				<div class="alert alert-success">
					<h6><i class="fa fa-user-check"></i> Épouse identifiée :</h6>
					<div class="row">
						<div class="col-md-4">
							<strong>Nom et Prénom :</strong><br>
							<span id="nom_prenom_epouse_trouve"></span>
						</div>
						<div class="col-md-4">
							<strong>Date de naissance :</strong><br>
							<span id="date_naissance_epouse_trouve"></span>
						</div>
						<div class="col-md-4">
							<strong>N° Acte :</strong><br>
							<span id="numero_acte_epouse_trouve"></span>
						</div>
					</div>
					<!-- Zone d'affichage de la situation matrimoniale -->
					<div class="row mt-3" id="situation_matrimoniale_epouse" style="display: none;">
						<div class="col-12">
							<div id="notification_situation_epouse"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="mb-2 col-sm-4">
                <label for="dewey">National</label>
                <input type="radio" id="nationaleepouse" name="originepouse" checked value="nationale">
                <label for="dewey">Etrangère</label>
                <input type="radio" id="etrangerepouse" name="originepouse" value="etrangere">
            </div>
			<hr>
		</div>

        <div class="row" id="bloc_mandant_epouse">
            <div class="ligne">
                <h4>INFORMATIONS DE LA MANDANTE</h4>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Nom(s)<span class="text-danger">*</span></label>
                <input type="text" name="nom_mandant_epouse" onkeyup="uppercase(this)" class="form-control" id="nom_mandant_epouse">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Prénom(s)</label>
                <input type="text" class="form-control" onkeyup="ucfirst(this)"  id="prenom_mandant_epouse" name="prenom_mandant_epouse" onkeyup="verif_lettre(this);" style="text-transform: capitalize">
            </div>
        </div>
        <div class="row">
            <div class="ligne">
                <h4>INFORMATIONS PERSONNELLES DE L'EPOUSE</h4>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Nom(s)<span class="text-danger">*</span></label>
                <input type="text" name="nom_epouse" onkeyup="uppercase(this)"  class="form-control   @error('nom_epoux') is-invalid @enderror" id="nom_epouse">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Prénom(s)</label>
                <input type="text" class="form-control" id="prenom_epouse" onkeyup="verif_lettre(this);" style="text-transform: capitalize">
            </div>
			<div class="mb-2 col-md-4">
                <label class="form-label">Date de naissance<span class="text-danger">*</span></label>
                <input type="date" style="padding-left:10px; border:2px solid silver; height:45px; border-radius:1px; width:100%"  id="date_naissance_epouse">
                <p style="background:red; padding:10px; font-size:13px;color:#FFF" id="notificationEpouseMineure">L'âge de l'épouse est inférieur à 18 ans. Une réquisition préalable est requise conformément à l'article 128 du code la de la famille.</p>
            </div>
        </div>
        <div class="row">
            <div class="mb-2 col-md-4 localiteepouse">
                <label class="form-label">Lieu de naissance <span class="text-danger"></span></label>
                 <select id="code_localite_epouse" class="form-control">
                    <option selected disabled>Choisissez</option>
                    @foreach ($lieuNaissances as $localite)
                        <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2 col-md-4 autrelieunaissanceepouse d-none">
                <label class="form-label">Lieu de naissance<span class="text-danger">*</span></label>
                <input type="text" name="lieu_naissance_epouse" class="form-control d-none" id="lieu_naissance_epouse">
                <select id="etranger_lieu_naissance_epouse" class="form-control">
                        <option value="">Choisissez</option>
                    @foreach ($countries as $countrie)
                        @if($countrie->name != 'Congo')
                        <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="mb-2 col-md-4 autrececnaissanceepouse d-none">
                <label class="form-label">Centre d'état civil de naissance<span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('cec_naissance_epouse') is-invalid @enderror" value="{{ old("cec_naissance_epouse") }}" id="cec_naissance_epouse" name="cec_naissance_epouse" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()">
                @error("cec_naissance_epouse")
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="mb-2 col-md-4 codececepouse">
                <label class="form-label">Centre d'état civil de naissance <span class="text-danger"></span></label>
                <select id="code_cec_epouse" class="form-select form-control">

                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Numéro d'acte naissance<span class="text-danger">*</span></label>
                <input type="text" name="num_acte_naissance_epouse" class="form-control"  id="num_acte_naissance_epouse" onkeyup="this.value=this.value.toUpperCase()">
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Date d'éditon de l'acte de naissance <span class="text-danger">*</span></label><br>
                <input type="date" style="padding-left:10px; border:2px solid silver; height:45px; border-radius:1px; width:100%"  id="date_emission_acte_naissance_epouse" onchange="validerDateActeNaissance('epouse')">
                <p style="background:red; padding:10px; font-size:13px; color:#FFF; display:none;" id="notificationDateActeEpouse">La date d'édition de l'acte ne peut pas être antérieure à la date de naissance.</p>
            </div>


            <div class="mb-2 col-md-4" id="etrangere">
                <label class="form-label">Nationalité <span class="text-danger"></span></label>
                 <select id="code_nationalite_epouse" class="form-control  @error('code_nationalite_epouse') is-invalid @enderror ">
                    @foreach ($nationalites as $nationalite)
                        <option value="{{ $nationalite->code_nationalite }}" {{ $nationalite->code_nationalite == "NAT_0001" ? "disabled" : "" }}>{{ $nationalite->lib_nationalite }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-4" id="congolaise">
                <label class="form-label">Nationalité<span class="text-danger"></span></label>
                 <select id="code_nationalite_epouse" disabled class="form-control  @error('code_nationalite_epouse') is-invalid @enderror ">
                    @foreach ($nationalites as $nationalite)
                        <option value="{{ $nationalite->code_nationalite }}" {{ $nationalite->code_nationalite == "NAT_0001" ? "selected" : "disabled" }}>{{ $nationalite->lib_nationalite }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Situation matrimoniale<span class="text-danger">*</span></label>
                <select name="sit_matrimoniale_epouse" id="sit_matrimoniale_epouse" class="form-control" onchange="verifierSituationMatrimonialeChange('epouse')">
                    <option value="">Choisissez</option>
                    @foreach ($situationMatrimoniales as $sitMat)
                    {{-- //il ne faut pas afficher les situations matrimoniales 3 et 4 --}}
                        @if($sitMat->code_situation_matrimoniale != "SMAT_0003" && $sitMat->code_situation_matrimoniale != "SMAT_0004")
                            <option value="{{ $sitMat->code_situation_matrimoniale }}">{{ $sitMat->lib_situation_matrimoniale }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="mb-2 col-md-4 pre_mariage_epouse" style="display: none">
                <label class="form-label">Date du pré-mariage <span class="text-danger">*</span></label>
                <input type="date"  max="{{ date('Y-m-d') }}" style="padding-left:10px; background:#e9ecef; border:2px solid silver; height:45px; border-radius:1px; width:100%"  id="date_pre_mariage_epouse">
            </div>
            {{-- <div class="mb-2 col-md-2 pre_mariage_epouse" style="display: none">
                <label class="form-label">Montant reçu (FCFA) <span class="text-danger">*</span></label>
                <input type="text" id="montant_dot" onkeyup="verif_nombre(this)" class="form-control form-control wide">
            </div> --}}
            <div class="mb-2 col-md-4 pre_mariage_epouse" style="display: none">
                <label class="form-label">Parent paternel ayant présidé <span class="text-danger">*</span></label>
                <input type="text" id="parent_paternel_epouse" class="form-control form-control wide">
            </div>
            <div class="mb-2 col-md-4 pre_mariage_epouse" style="display: none">
                <label class="form-label">Parent maternel ayant présidé <span class="text-danger">*</span></label>
                <input type="text" id="parent_maternel_epouse" class="form-control form-control wide">
            </div>

            <div class="mb-2 col-md-4 numactemariageepouse" style="display: none">
                <label class="form-label">Numéro acte de mariage<span class="text-danger">*</span></label>
                <input type="text" disabled id="numero_acte_mariage_epouse" class="form-control" />
            </div>
            <div class="mb-2 col-md-4 numactedecesepoux" style="display: none">
                <label class="form-label">Numéro acte de décès de l'époux<span class="text-danger">*</span></label>
                <input type="text" disabled id="numero_acte_deces_epoux" class="form-control" />
            </div>
            <div class="mb-2 col-md-4 numajugementdivorceepouse" style="display: none">
                <label class="form-label">Numéro du jugement du divorce<span class="text-danger">*</span></label>
                <input type="text" disabled id="numero_jugement_divorce_epouse" class="form-control" />
            </div>

            <div class="mb-2 col-md-4">
                <label class="form-label">Profession<span class="text-danger">*</span></label>
                <select id="code_profession_epouse"  name="code_profession_epouse" class="form-control   @error('code_profession_epouse') is-invalid @enderror ">
                    <option value="">Choisissez</option>
                    @foreach ($professions as $profession)
                    <option value="{{ $profession->code_profession }}">{{ $profession->lib_profession }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Type pièce d'identité</label>
                <select id="code_type_document_epouse" class="form-control form-control wide">
                        <option disabled selected>Choisissez</option>
                    @foreach ($typedocuments as $item)
                        <option value="{{ $item->code_type_document }}">{{ $item->lib_type_document  }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Numéro pièce d'identité</label>
                <input type="text" id="numero_document_epouse" class="form-control form-control wide" onkeyup="this.value=this.value.toUpperCase()">
            </div>

            <div class="row">
                <div class="ligne">
                    <h4>INFORMATIONS SUR L'ADRESSE
                       ( <span style="color:red!important">
                       <label class="radio-inline mr-3">Même adresse que l'époux ?</label></span>
                       <label class="radio-inline mr-3"><input type="radio" id="sameadress" name="adressepouse" value="1"> OUI</label>
                       <label class="radio-inline mr-3"><input type="radio" id="otheradress" name="adressepouse" value="0"> NON</label>)
                    </h4>
                </div>
            </div>
            <div class="row adresseepouse">
                <div class="mb-2 col-md-3">
                    <label class="form-label">Pays<span class="text-danger"></span></label>
                    <select id="domicile_pays_epouse" class="form-control required" disabled>
                        <option value="">Choisissez</option>
                        @foreach ($countries as $countrie)
                            <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2 col-md-3 domicile_ville_epouse">
                    <label class="form-label">Commune/District<span class="text-danger"></span></label>
                    <select class="form-control" id="domicile_ville_epouse" disabled>
                        <option value="">Choisir</option>
                        @foreach ($lieuNaissances as $localite)
                            <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2 col-md-3 autredomicile_ville_epouse d-none">
                    <label class="form-label">Ville<span class="text-danger"></span></label>
                    <input type="text" id="autredomicile_ville_epouse" class="form-control form-control wide" placeholder="Libellé de la ville" onkeyup="this.value=this.value.toUpperCase()">
                </div>

                <div class="mb-2 col-md-3 domicile_arrondissement_epouse">
                    <label class="form-label">Arr/Com. Urb<span class="text-danger"></span></label>
                    <select class="form-control" id="domicile_arrondissement_epouse" disabled>
                        <option value="">Choisir</option>
                    </select>
                </div>

                <div class="mb-2 col-md-3 domicile_quartier_epouse">
                    <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                    <select class="form-control" id="domicile_quartier_epouse" disabled>
                        <option value="">Choisir</option>
                    </select>
                </div>
                <div class="mb-2 col-md-3">
                    <label class="form-label">Type voie<span class="text-danger"></span></label>
                    <select class="form-control" id="domicile_typevoie_epouse" disabled>
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
                    <input type="text" class="form-control" id="domicile_numero_epouse" disabled placeholder="N° voie">
                </div>
                <div class="mb-2 col-md-3">
                    <label class="form-label">Nom voie<span class="text-danger"></span></label>
                    <input type="text" class="form-control" id="domicile_nomvoie_epouse" disabled placeholder="Nom voie" style="text-transform: capitalize">
                </div>
            </div>


            <div class="ligne">
                <h4>INFORMATIONS DES PARENTS</h4>
            </div>

            <div class="mb-2 col-md-3">
                <label class="form-label">Nom(s) et prénom(s) du Père<span class="text-danger">*</span></label>
                <input type="text" id="nom_pere_epouse" name="nom_pere_epouse" class="form-control" />
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Nom(s) et prénom(s) de la mère<span class="text-danger">*</span></label>
                <input type="text" id="nom_mere_epouse" name="nom_mere_epouse" class="form-control" />
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Chef de famille<span class="text-danger">*</span></label>
                <input type="text" id="chef_famille" name="chef_famille" class="form-control"/>
            </div>

            <div class="mb-2 col-md-3">
                <label class="form-label">Filiation<span class="text-danger">*</span></label>
                <select type="text" id="filiation" name="filiation" class="form-control">
                    <option value="">Choisissez</option>
                    @foreach ($filiations as $filiation)
                    <option value="{{ $filiation->code_filiation }}">{{ $filiation->lib_filiation }}</option>
                    @endforeach
				</select>
            </div>

            <div class="ligne">
                <h4>AUTRES INFORMATIONS</h4>
            </div>

            <div class="mb-2 col-md-4 origineEpouse">
                <label class="form-label">Autorisationss Ambassade<span class="text-danger">*</span></label>
                <input type="text" id="autorisation_ambassade_epouse" name="autorisation_ambassade_epouse" class="form-control"/>
            </div>

            <div class="mb-2 col-md-4 origineEpouse">
                <label class="form-label">Date d'émission de l'autorisation<span class="text-danger">*</span></label>
                <input type="date" style="padding-left:10px; border:2px solid silver; height:45px; border-radius:1px; width:100%" id="date_autorisation_ambassade_epouse"/>
            </div>

            <div class="mb-2 col-md-4 origineEpouse">
                <label class="form-label">Certificat de résidence<span class="text-danger">*</span></label>
                <input type="text" id="certificat_residence_epouse" name="certificat_residence_epouse" class="form-control" />
            </div>
            <div class="mb-2 col-md-4 origineEpouse">
                <label class="form-label">Date d'émission certificat de résidence<span class="text-danger">*</span></label>
                <input type="date" id="date_emission_certificat_residence_epouse" name="date_emission_certificat_residence_epoux" style="padding-left:10px; border:2px solid silver; height:45px; border-radius:1px; width:100%" />
            </div>
        </div>
    </section>
