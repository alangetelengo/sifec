    <!-- Step 1 -->
    <h6>Epoux</h6>
    <section>

        <div class="d-none">
            <input type="hidden" value="DECLARATION DE MARIAGE" id="type_declaration">
        </div>
		<div class="row">
			<div class="d-flex justify-content-end align-items-center">
                <button type="button" class="btn btn-info  text-white"  data-bs-toggle="modal" data-bs-target=".epoux-search-modal-lg"><i class="fa fa-search"></i> Faire une recherche de l'époux</button> &nbsp;
				<button type="button" id="clear_epoux" class="btn btn-danger text-white"><i class="fa fa-trash"></i> Vider </button>
			</div>

			<!-- Zone d'affichage de l'identité trouvée pour l'époux -->
			<div class="col-12 mt-3" id="identite_trouvee_epoux" style="display: none;">
				<div class="alert alert-success">
					<h6><i class="fa fa-user-check"></i> Époux identifié :</h6>
					<div class="row">
						<div class="col-md-4">
							<strong>Nom et Prénom :</strong><br>
							<span id="nom_prenom_epoux_trouve"></span>
						</div>
						<div class="col-md-4">
							<strong>Date de naissance :</strong><br>
							<span id="date_naissance_epoux_trouve"></span>
						</div>
						<div class="col-md-4">
							<strong>N° Acte :</strong><br>
							<span id="numero_acte_epoux_trouve"></span>
						</div>
					</div>
					<!-- Zone d'affichage de la situation matrimoniale -->
					<div class="row mt-3" id="situation_matrimoniale_epoux" style="display: none;">
						<div class="col-12">
							<div id="notification_situation_epoux"></div>
						</div>
					</div>
				</div>
			</div>
            <div class="mb-2 col-sm-4">
                <label for="dewey">Nationale</label>
                <input type="radio" id="nationaleepoux" name="origine" checked value="nationale">
                <label for="dewey">Etrangère</label>
                <input type="radio" id="etrangerepoux" name="origine" value="etrangere">
            </div>
            <hr>
		</div>



        <div class="row" id="bloc_mandant_epoux">
            <div class="ligne">
                <h4>INFORMATIONS DU MANDANT</h4>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Nom(s)<span class="text-danger">*</span></label>
                <input type="text" name="nom_mandant_epoux" onkeyup="uppercase(this)" class="form-control" id="nom_mandant_epoux">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Prénom(s)</label>
                <input type="text" class="form-control" onkeyup="ucfirst(this)"  id="prenom_mandant_epoux" name="prenom_mandant_epoux" onkeyup="verif_lettre(this);" style="text-transform: capitalize">
            </div>

        </div>

        <div class="row">
            <div class="ligne">
                <h4>INFORMATIONS PERSONNELLES DE L'EPOUX</h4>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Nom(s)<span class="text-danger">*</span></label>
                <input type="text" name="nom_epoux" onkeyup="uppercase(this)" class="form-control" id="nom_epoux">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Prénom(s)</label>
                <input type="text" class="form-control" onkeyup="ucfirst(this)"  id="prenom_epoux" onkeyup="verif_lettre(this);" style="text-transform: capitalize">
            </div>
			<div class="mb-2 col-md-4">
                <label class="form-label">Date de naissance <span class="text-danger">*</span></label><br>
                <input type="date" style="padding-left:10px; border:2px solid silver; height:45px; border-radius:1px; width:100%"  id="date_naissance_epoux">
                <p style="background:red; padding:10px; font-size:13px; color:#FFF" id="notificationEpouxMineure">L'âge de l'époux est inférieur à 21 ans. Une réquisition préalable est requise conformément à l'article 128 du code la de la famille.</p>

            </div>
        </div>
        <div class="row">
            <div class="mb-2 col-md-4 localiteepoux">
                <label class="form-label">Lieu de naissance <span class="text-danger"></span></label>
                 <select id="code_localite_epoux" class="form-control">
                    <option selected disabled>Choisissez</option>
                    @foreach ($lieuNaissances as $localite)
                        <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2 col-md-4 autrelieunaissanceepoux d-none">
                <label class="form-label">Lieu de naissance<span class="text-danger">*</span></label>
                <input type="text" name="lieu_naissance_epoux" class="form-control d-none" id="lieu_naissance_epoux">
                <select id="etranger_lieu_naissance_epoux" class="form-control">
                        <option value="">Choisissez</option>
                    @foreach ($countries as $countrie)
                        @if($countrie->name != 'Congo')
                        <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="mb-2 col-md-4 autrececnaissanceepoux d-none">
                <label class="form-label">Centre d'état civil de naissance<span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('cec_naissance_epoux') is-invalid @enderror" value="{{ old("cec_naissance_epoux") }}" id="cec_naissance_epoux" name="cec_naissance_epoux" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()">
                @error("cec_naissance_epoux")
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="mb-2 col-md-4 codececepoux">
                <label class="form-label">Centre d'état civil de naissance <span class="text-danger"></span></label>
                <select id="code_cec_epoux" class="form-select form-control">

                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Numéro d'acte naissance <span class="text-danger">*</span></label>
                <input type="text" name="num_acte_naissance_epoux" class="form-control   @error('num_acte_naissance_epoux') is-invalid @enderror"  id="num_acte_naissance_epoux" onkeyup="this.value=this.value.toUpperCase()">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Date d'édition de l'acte de naissance <span class="text-danger">*</span></label><br>
                <input type="date" style="padding-left:10px; border:2px solid silver; height:45px; border-radius:1px; width:100%"  id="date_emission_acte_naissance_epoux" onchange="validerDateActeNaissance('epoux')">
                <p style="background:red; padding:10px; font-size:13px; color:#FFF; display:none;" id="notificationDateActeEpoux">La date d'édition de l'acte ne peut pas être antérieure à la date de naissance.</p>
            </div>

            <div class="mb-2 col-md-4" id="etranger">
                <label class="form-label">Nationalité <span class="text-danger"></span></label>
                 <select id="code_nationalite_epoux" class="form-control  @error('code_nationalite_epoux') is-invalid @enderror ">
                    @foreach ($nationalites as $nationalite)
                        <option value="{{ $nationalite->code_nationalite }}" {{ $nationalite->code_nationalite == "NAT_0001" ? "disabled" : "" }}>{{ $nationalite->lib_nationalite }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2 col-md-4" id="congolais">
                <label class="form-label">Nationalité<span class="text-danger"></span></label>
                 <select id="code_nationalite_epoux" disabled class="form-control  @error('code_nationalite_epoux') is-invalid @enderror ">
                    {{-- <option value="">Choisissez</option> --}}
                    @foreach ($nationalites as $nationalite)
                        <option value="{{ $nationalite->code_nationalite }}" {{ $nationalite->code_nationalite == "NAT_0001" ? "selected" : "disabled" }}>{{ $nationalite->lib_nationalite }}</option>
                    @endforeach
                </select>
            </div>



            <div class="mb-2 col-md-4">
                <label class="form-label">Profession<span class="text-danger">*</span></label>
                    <select id="code_profession_epoux" class="form-control   @error('code_profession_epoux') is-invalid @enderror ">
                        <option value="">Choisissez</option>
                    @foreach ($professions as $profession)
                        <option value="{{ $profession->code_profession }}">{{ $profession->lib_profession }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Type pièce d'identité</label>
                <select id="code_type_document_epoux" class="form-control form-control wide">
                        <option disabled selected>Choisissez</option>
                    @foreach ($typedocuments as $item)
                        <option value="{{ $item->code_type_document }}">{{ $item->lib_type_document  }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Numéro pièce d'identité</label>
                <input type="text" id="numero_document_epoux" class="form-control form-control wide" onkeyup="this.value=this.value.toUpperCase()">
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Situation matrimoniale <span class="text-danger">*</span></label>
                <select id="sit_matrimoniale_epoux" class="form-control" onchange="verifierSituationMatrimonialeChange('epoux')">
                    <option value="">Choisissez</option>
                    @foreach ($situationMatrimoniales as $sitMat)
                    {{-- //il ne faut pas afficher les situations matrimoniales 3 et 4 --}}
                        @if($sitMat->code_situation_matrimoniale != "SMAT_0003" && $sitMat->code_situation_matrimoniale != "SMAT_0004")
                            <option value="{{ $sitMat->code_situation_matrimoniale }}">{{ $sitMat->lib_situation_matrimoniale }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="mb-2 col-md-4 pre_mariage_epoux" style="display: none">
                <label class="form-label">Date du pré-mariage <span class="text-danger">*</span></label>
                <input type="date"  style="padding-left:10px; border:2px solid silver; height:45px; border-radius:1px; width:100%"  id="date_pre_mariage_epoux">
                <!-- <input type="date"  name="" id="date_mariage_valeur" placeholder="date de mariage officiel"/> -->
            </div>
            <div class="mb-2 col-md-4 pre_mariage_epoux" style="display: none">
                <label class="form-label">Parent paternel ayant présidé <span class="text-danger">*</span></label>
                <input type="text" id="parent_paternel_epoux" class="form-control form-control wide">
            </div>
            <div class="mb-2 col-md-4 pre_mariage_epoux" style="display: none">
                <label class="form-label">Parent maternel ayant présidé <span class="text-danger">*</span></label>
                <input type="text" id="parent_maternel_epoux" class="form-control form-control wide">
            </div>

            <div class="mb-2 col-md-4 numactemariageepoux" style="display: none">
                <label class="form-label">Numéro acte de mariage<span class="text-danger">*</span></label>
                <input type="text" id="numero_acte_mariage_epoux" class="form-control" onblur="verifierActeMariageEpoux(this.value)" onkeyup="clearVerificationTimeout()" onchange="verifierChangementActeMariage('epoux')" />
                <div id="notification_acte_mariage_epoux" class="mt-2" style="display: none;"></div>
            </div>
            <div class="mb-2 col-md-4 numactedecesepouse" style="display: none">
                <label class="form-label">Numéro acte de décès de l'épouse<span class="text-danger">*</span></label>
                <input type="text" disabled id="numero_acte_deces_epouse" class="form-control" />
            </div>
            <div class="mb-2 col-md-4 numajugementdivorceepoux" style="display: none">
                <label class="form-label">Numéro du jugement du divorce<span class="text-danger">*</span></label>
                <input type="text" disabled id="numero_jugement_divorce_epoux" class="form-control" />
            </div>

            <div class="row">

                <div class="ligne">
                    <h4>INFORMATIONS SUR L'ADRESSE</h4>
                </div>
                <div class="mb-2 col-md-3">
                    <label class="form-label">Pays<span class="text-danger"></span></label>
                    <select id="domicile_pays_epoux" class="form-control required">
                        @foreach ($countries as $countrie)
                            <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2 col-md-3 domicile_ville_epoux">
                    <label class="form-label">Commune/District<span class="text-danger"></span></label>
                    <select class="form-control" name="domicile_ville_epoux" id="domicile_ville_epoux">
                        <option value="">Choisir</option>
                        @foreach ($lieuNaissances as $localite)
                            <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2 col-md-3 autredomicile_ville_epoux d-none">
                    <label class="form-label">Ville<span class="text-danger"></span></label>
                    <input type="text" id="autredomicile_ville_epoux" class="form-control form-control wide" placeholder="Libellé de la ville" onkeyup="this.value=this.value.toUpperCase()">
                </div>
                <div class="mb-2 col-md-3 domicile_arrondissement_epoux">
                    <label class="form-label">Arr/Com. Urb<span class="text-danger"></span></label>
                    <select class="form-control" id="domicile_arrondissement_epoux">
                        <option value="">Choisir</option>
                    </select>
                </div>
                <div class="mb-2 col-md-3 domicile_quartier_epoux">
                    <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                    <select class="form-control" id="domicile_quartier_epoux">
                        <option value="">Choisir</option>
                    </select>
                </div>
                <div class="mb-2 col-md-3">
                    <label class="form-label">Type voie<span class="text-danger"></span></label>
                    <select class="form-control" id="domicile_typevoie_epoux">
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
                    <input type="text" class="form-control" id="domicile_numero_epoux" placeholder="N° voie">
                </div>
                <div class="mb-2 col-md-3">
                    <label class="form-label">Nom voie<span class="text-danger"></span></label>
                    <input type="text" class="form-control" id="domicile_nomvoie_epoux" placeholder="Nom voie" style="text-transform: capitalize">
                </div>
            </div>

            <div class="ligne">
                <h4>INFORMATIONS DES PARENTS</h4>
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Nom(s) et prénom(s) du père<span class="text-danger">*</span></label>
                <input type="text" id="nom_pere_epoux" name="nom_pere_epoux" class="form-control" />
            </div>
            <div class="mb-2 col-md-4">
                <label class="form-label">Nom(s) et prénom(s) de la mère<span class="text-danger">*</span></label>
                <input type="text" id="nom_mere_epoux" name="nom_mere_epoux" class="form-control" />
            </div>
            <div class="row">
                <div class="ligne">
                    <h4>AUTRES INFORMATIONS</h4>
                </div>
                <div class="mb-2 col-md-3 origineEpoux">
                    <label class="form-label">Autorisation Ambassade<span class="text-danger">*</span></label>
                    <input type="text" id="autorisation_ambassade_epoux" name="autorisation_ambassade_epoux" class="form-control"/>
                </div>
                <div class="mb-2 col-md-3 origineEpoux">
                    <label class="form-label">Date d'émission de l'autorisation<span class="text-danger">*</span></label>
                    <input type="date" style="padding-left:10px; border:2px solid silver; height:45px; border-radius:1px; width:100%" id="date_autorisation_ambassade_epoux"/>
                </div>
                <div class="mb-2 col-md-3 origineEpoux">
                    <label class="form-label">Certificat de résidence<span class="text-danger">*</span></label>
                    <input type="text" id="certificat_residence_epoux" name="certificat_residence_epoux" class="form-control" />
                </div>
                <div class="mb-2 col-md-3 origineEpoux">
                    <label class="form-label">Date du certificat de résidence<span class="text-danger">*</span></label>
                    <input type="date" style="padding-left:10px; border:2px solid silver; height:45px; border-radius:1px; width:100%" id="date_emission_certificat_residence_epoux"/>
                </div>
            </div>

            <div class="row">
                <div class="mb-2 col-md-4">
                    <label class="form-label">Nombre d'enfant</label>
                    <select name="nombre_enfant" id="nombre_enfant" class="form-control">
                        <option value="">Choisir</option>
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10" class="d-none">10</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-12" id="enfant1">
                    <div class="card">
                        <div class="card-header">
                          Enfant 1
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <label class="form-label"> Nom</label>
                                    <input type="text" class="form-control wide" placeholder="Nom" id="nom1">
                                </div>
                                <div class="col-3">
                                    <label class="form-label"> Prénom</label>
                                    <input type="text" class="form-control wide" placeholder="Prénom" id="prenom1">
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Sexe</label>
                                    <select name="" id="sexe1" class="form-control">
                                        <option value="">Choisir</option>
                                        <option value="M">M</option>
                                        <option value="F">F</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Date naissance</label>
                                    <input type="date" class="form-control wide" id="datenaiss1">
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Lieu de naissance</label>
                                    <select id="lieunaiss1" class="form-control">
                                        <option value="">Choisissez</option>
                                        @foreach ($lieuNaissances as $localite)
                                            <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12" id="enfant2">
                    <div class="card">
                        <div class="card-header">
                          Enfant 2
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <label class="form-label"> Nom</label>
                                    <input type="text" class="form-control wide" placeholder="Nom" id="nom2">
                                </div>
                                <div class="col-3">
                                    <label class="form-label"> Prénom</label>
                                    <input type="text" class="form-control wide" placeholder="Prénom" id="prenom2">
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Sexe</label>
                                    <select name="" id="sexe2" class="form-control">
                                        <option value="">Choisir</option>
                                        <option value="M">M</option>
                                        <option value="F">F</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Date naissance</label>
                                    <input type="date" class="form-control wide" id="datenaiss2">
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Lieu de naissance</label>
                                    <select id="lieunaiss2" class="form-control">
                                        <option value="">Choisissez</option>
                                        @foreach ($lieuNaissances as $localite)
                                            <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12" id="enfant3">
                    <div class="card">
                        <div class="card-header">
                          Enfant 3
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <label class="form-label"> Nom</label>
                                    <input type="text" class="form-control wide" placeholder="Nom" id="nom3">
                                </div>
                                <div class="col-3">
                                    <label class="form-label"> Prénom</label>
                                    <input type="text" class="form-control wide" placeholder="Prénom" id="prenom3">
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Sexe</label>
                                    <select name="" id="sexe3" class="form-control">
                                        <option value="">Choisir</option>
                                        <option value="M">M</option>
                                        <option value="F">F</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Date naissance</label>
                                    <input type="date" class="form-control wide" id="datenaiss3">
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Lieu de naissance</label>
                                    <select id="lieunaiss3" class="form-control">
                                        <option value="">Choisissez</option>
                                        @foreach ($lieuNaissances as $localite)
                                            <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12" id="enfant4">
                    <div class="card">
                        <div class="card-header">
                          Enfant 4
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <label class="form-label"> Nom</label>
                                    <input type="text" class="form-control wide" placeholder="Nom" id="nom4">
                                </div>
                                <div class="col-3">
                                    <label class="form-label"> Prénom</label>
                                    <input type="text" class="form-control wide" placeholder="Prénom" id="prenom4">
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Sexe</label>
                                    <select name="" id="sexe4" class="form-control">
                                        <option value="">Choisir</option>
                                        <option value="M">M</option>
                                        <option value="F">F</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Date naissance</label>
                                    <input type="date" class="form-control wide" id="datenaiss4">
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Lieu de naissance</label>
                                    <select id="lieunaiss4" class="form-control">
                                        <option value="">Choisissez</option>
                                        @foreach ($lieuNaissances as $localite)
                                            <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12" id="enfant5">
                    <div class="card">
                        <div class="card-header">
                          Enfant 5
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <label class="form-label"> Nom</label>
                                    <input type="text" class="form-control wide" placeholder="Nom" id="nom5">
                                </div>
                                <div class="col-3">
                                    <label class="form-label"> Prénom</label>
                                    <input type="text" class="form-control wide" placeholder="Prénom" id="prenom5">
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Sexe</label>
                                    <select name="" id="sexe5" class="form-control">
                                        <option value="">Choisir</option>
                                        <option value="M">M</option>
                                        <option value="F">F</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Date naissance</label>
                                    <input type="date" class="form-control wide" id="datenaiss5">
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Lieu de naissance</label>
                                    <select id="lieunaiss5" class="form-control">
                                        <option value="">Choisissez</option>
                                        @foreach ($lieuNaissances as $localite)
                                            <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12" id="enfant6">
                    <div class="card">
                        <div class="card-header">
                          Enfant 6
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <label class="form-label"> Nom</label>
                                    <input type="text" class="form-control wide" placeholder="Nom" id="nom6">
                                </div>
                                <div class="col-3">
                                    <label class="form-label"> Prénom</label>
                                    <input type="text" class="form-control wide" placeholder="Prénom" id="prenom6">
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Sexe</label>
                                    <select name="" id="sexe6" class="form-control">
                                        <option value="">Choisir</option>
                                        <option value="M">M</option>
                                        <option value="F">F</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Date naissance</label>
                                    <input type="date" class="form-control wide" id="datenaiss6">
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Lieu de naissance</label>
                                    <select id="lieunaiss6" class="form-control">
                                        <option value="">Choisissez</option>
                                        @foreach ($lieuNaissances as $localite)
                                            <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12" id="enfant7">
                    <div class="card">
                        <div class="card-header">
                          Enfant 7
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <label class="form-label"> Nom</label>
                                    <input type="text" class="form-control wide" placeholder="Nom" id="nom7">
                                </div>
                                <div class="col-3">
                                    <label class="form-label"> Prénom</label>
                                    <input type="text" class="form-control wide" placeholder="Prénom" id="prenom7">
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Sexe</label>
                                    <select name="" id="sexe7" class="form-control">
                                        <option value="">Choisir</option>
                                        <option value="M">M</option>
                                        <option value="F">F</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Date naissance</label>
                                    <input type="date" class="form-control wide" id="datenaiss7">
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Lieu de naissance</label>
                                    <select id="lieunaiss7" class="form-control">
                                        <option value="">Choisissez</option>
                                        @foreach ($lieuNaissances as $localite)
                                            <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12" id="enfant8">
                    <div class="card">
                        <div class="card-header">
                          Enfant 8
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <label class="form-label"> Nom</label>
                                    <input type="text" class="form-control wide" placeholder="Nom" id="nom8">
                                </div>
                                <div class="col-3">
                                    <label class="form-label"> Prénom</label>
                                    <input type="text" class="form-control wide" placeholder="Prénom" id="prenom8">
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Sexe</label>
                                    <select name="" id="sexe8" class="form-control">
                                        <option value="">Choisir</option>
                                        <option value="M">M</option>
                                        <option value="F">F</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Date naissance</label>
                                    <input type="date" class="form-control wide" id="datenaiss8">
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Lieu de naissance</label>
                                    <select id="lieunaiss8" class="form-control">
                                        <option value="">Choisissez</option>
                                        @foreach ($lieuNaissances as $localite)
                                            <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12" id="enfant9">
                    <div class="card">
                        <div class="card-header">
                          Enfant 9
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <label class="form-label"> Nom</label>
                                    <input type="text" class="form-control wide" placeholder="Nom" id="nom9">
                                </div>
                                <div class="col-3">
                                    <label class="form-label"> Prénom</label>
                                    <input type="text" class="form-control wide" placeholder="Prénom" id="prenom9">
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Sexe</label>
                                    <select name="" id="sexe9" class="form-control">
                                        <option value="">Choisir</option>
                                        <option value="M">M</option>
                                        <option value="F">F</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Date naissance</label>
                                    <input type="date" class="form-control wide" id="datenaiss9">
                                </div>
                                <div class="col-2">
                                    <label class="form-label"> Lieu de naissance</label>
                                    <select id="lieunaiss9" class="form-control">
                                        <option value="">Choisissez</option>
                                        @foreach ($lieuNaissances as $localite)
                                            <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
        </div>

    </section>
