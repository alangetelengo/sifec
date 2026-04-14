    <!-- Step 3 -->
    <h6>Témoins</h6>
    <section>

        <div class="row">
		 <h6><strong>TEMOINS EPOUX</strong></h6>
		 <hr>

		 <!-- Boutons de recherche pour les témoins époux -->
		 <div class="row mb-3">
			<div class="col-md-6">
				<div class="d-flex justify-content-between align-items-center">
					<h6><i class="fa fa-user"></i> Témoin 1 (Mari)</h6>
					<div>
						<button type="button" class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target=".temoin-epoux-1-search-modal-lg">
							<i class="fa fa-search"></i> Rechercher
						</button>
						<button type="button" class="btn btn-danger btn-sm text-white" onclick="viderTemoinEpoux1()">
							<i class="fa fa-trash"></i> Vider
						</button>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="d-flex justify-content-between align-items-center">
					<h6><i class="fa fa-user"></i> Témoin 2 (Femme)</h6>
					<div>
						<button type="button" class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target=".temoin-epoux-2-search-modal-lg">
							<i class="fa fa-search"></i> Rechercher
						</button>
						<button type="button" class="btn btn-danger btn-sm text-white" onclick="viderTemoinEpoux2()">
							<i class="fa fa-trash"></i> Vider
						</button>
					</div>
				</div>
			</div>
		 </div>

            <div class="ligne">
                <h4>INFORMATIONS PERSONNELLES</h4>
            </div>
            <div class="mb-2 col-md-3">
                <input type="hidden" id="code_mere">
                <label class="form-label">Nom(s) du mari<span class="text-danger">*</span></label>
                <input type="text" class="form-control" onkeyup="uppercase(this)" id="nom_t_epoux_1">
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Prénom(s) du mari</label>
                <input type="text" class="form-control"  id="prenom_t_epoux_1" onkeyup="verif_lettre(this);" style="text-transform: capitalize">
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Date de naissance du mari<span class="text-danger">*</span></label><br>
                <input type="date" id="date_naissance_t_epoux_1" style="padding-left:10px; border:2px solid silver; height:45px; border-radius:1px; width:100%">
            </div>

            <div class="mb-2 col-md-3 localiteepouxt1">
                <label class="form-label">Lieu de naissance <span class="text-danger"></span></label>
                <select id="code_localite_t_epoux_1" class="form-control">
                    <option value="">Choisissez</option>
                    @foreach ($lieuNaissances as $localite)
                        <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2 col-md-3 autrelieunaissancetemoinepoux1 d-none">
                <label class="form-label">Autre lieu de naissance<span class="text-danger">*</span></label>
                <input type="text" name="lieu_naissance_t_epoux_1" class="form-control" id="lieu_naissance_t_epoux_1">
            </div>

            <div class="mb-2 col-md-3">
                <label class="form-label">Nationalité du mari<span class="text-danger"></span></label>
                 <select id="code_nationalite_t_epoux_1" class="form-control">
                    @foreach ($nationalites as $nationalite)
                        <option value="{{ $nationalite->code_nationalite }}" {{ $nationalite->code_nationalite == "NAT_0001" ? "selected" : "" }}>{{ $nationalite->lib_nationalite }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Profession du mari<span class="text-danger">*</span></label>
                <select id="code_profession_t_epoux_1"  name="code_profession_t_epoux_1" class="form-control   @error('code_profession_t_epoux_1') is-invalid @enderror ">
                    <option value="">Choisissez</option>
                   @foreach ($professions as $profession)
                       <option value="{{ $profession->code_profession }}">{{ $profession->lib_profession }}</option>
                   @endforeach
               </select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Type pièce d'identité</label>
                <select id="code_type_document_t_epoux_1" class="form-control form-control wide">
                        <option disabled selected>Choisissez</option>
                    @foreach ($typedocuments as $item)
                        <option value="{{ $item->code_type_document }}">{{ $item->lib_type_document  }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Numéro pièce d'identité du mari</label>
                <input type="text" id="numero_document_t_epoux_1" class="form-control form-control wide" onkeyup="this.value=this.value.toUpperCase()">
            </div>

            <div class="ligne2">
            </div>

			<div class="mb-2 col-md-3">
                <input type="hidden">
                <label class="form-label">Nom(s) de la femme<span class="text-danger">*</span></label>
                <input type="text" class="form-control " name="nom_t_epoux_2" onkeyup="uppercase(this)"  id="nom_t_epoux_2">
            </div>

            <div class="mb-2 col-md-3">
                <label class="form-label">Prénom(s) de la femme</label>
                <input type="text" class="form-control"  id="prenom_t_epoux_2" onkeyup="verif_lettre(this);" style="text-transform: capitalize">
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Date de naissance de la femme<span class="text-danger">*</span></label><br>
                <input type="date" style="padding-left:10px; border:2px solid silver; height:45px; border-radius:1px; width:100%"  name="date_naissance_t_epoux_2"  id="date_naissance_t_epoux_2">
            </div>

            <div class="mb-2 col-md-3 localiteepouxt2">
                <label class="form-label">Lieu de naissance <span class="text-danger"></span></label>
                <select id="code_localite_t_epoux_2" class="form-control">
                    <option value="">Choisissez</option>
                    @foreach ($lieuNaissances as $localite)
                        <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2 col-md-3 autrelieunaissancetemoinepoux2 d-none">
                <label class="form-label">Autre lieu de naissance<span class="text-danger">*</span></label>
                <input type="text" name="lieu_naissance_t_epoux_2" class="form-control" id="lieu_naissance_t_epoux_2">
            </div>

            <div class="mb-2 col-md-3">
                <label class="form-label">Nationalité de la femme<span class="text-danger"></span></label>
                 <select id="code_nationalite_t_epoux_2" class="form-control  @error('code_nationalite_t_epoux_2') is-invalid @enderror ">
                    @foreach ($nationalites as $nationalite)
                        <option value="{{ $nationalite->code_nationalite }}" {{ $nationalite->code_nationalite == "NAT_0001" ? "selected" : "" }}>{{ $nationalite->lib_nationalite }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Profession de la femme<span class="text-danger">*</span></label>
                <select id="code_profession_t_epoux_2"  class="form-control">
                    <option value="">Choisissez</option>
                   @foreach ($professions as $profession)
                       <option value="{{ $profession->code_profession }}">{{ $profession->lib_profession }}</option>
                   @endforeach
               </select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Type pièce d'identité de la femme</label>
                <select id="code_type_document_t_epoux_2" class="form-control form-control wide">
                        <option disabled selected>Choisissez</option>
                    @foreach ($typedocuments as $item)
                        <option value="{{ $item->code_type_document }}">{{ $item->lib_type_document  }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Numéro pièce d'identité de la femme</label>
                <input type="text" id="numero_document_t_epoux_2" class="form-control form-control wide" onkeyup="this.value=this.value.toUpperCase()">
            </div>

            <div class="ligne">
                <h4>INFORMATIONS SUR L'ADRESSE</h4>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Pays<span class="text-danger"></span></label>
                <select id="domicile_pays_temoins_epoux" class="form-control required">
                    {{-- <option value="">Choisissez</option> --}}
                    @foreach ($countries as $countrie)
                        <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3 domicile_ville_temoins_epoux">
                <label class="form-label">Commune/District<span class="text-danger"></span></label>
                <select class="form-control"  id="domicile_ville_temoins_epoux">
                    <option value="">Choisir</option>
                    @foreach ($lieuNaissances as $localite)
                        <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3 autredomicile_ville_temoins_epoux d-none">
                <label class="form-label">Ville<span class="text-danger"></span></label>
                <input type="text" id="autredomicile_ville_temoins_epoux" class="form-control form-control wide" placeholder="Libellé de la ville" onkeyup="this.value=this.value.toUpperCase()">
            </div>
            <div class="mb-2 col-md-3 domicile_arrondissement_temoins_epoux">
                <label class="form-label">Arr/Com. Urb<span class="text-danger"></span></label>
                <select class="form-control" id="domicile_arrondissement_temoins_epoux">
                    <option value="">Choisir</option>
                </select>
            </div>
            <div class="mb-2 col-md-3 domicile_quartier_temoins_epoux">
                <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                <select class="form-control" id="domicile_quartier_temoins_epoux">
                    <option value="">Choisir</option>
                </select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Type voie<span class="text-danger"></span></label>
                <select class="form-control" id="domicile_typevoie_temoins_epoux">
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
                <input type="text" class="form-control" id="domicile_numero_temoins_epoux" placeholder="N° voie">
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Nom voie<span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_nomvoie_temoins_epoux" placeholder="Nom voie" style="text-transform: capitalize">
            </div>

		</div>


		<div class="row" style="margin-top:50px">
            <h6><strong>Témoins Epouse</strong></h6>
            <hr>

            <!-- Boutons de recherche pour les témoins épouse -->
			<div class="row mb-3">
				<div class="col-md-6">
					<div class="d-flex justify-content-between align-items-center">
						<h6><i class="fa fa-user"></i> Témoin 1 (Mari)</h6>
						<div>
							<button type="button" class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target=".temoin-epouse-1-search-modal-lg">
								<i class="fa fa-search"></i> Rechercher
							</button>
							<button type="button" class="btn btn-danger btn-sm text-white" onclick="viderTemoinEpouse1()">
								<i class="fa fa-trash"></i> Vider
							</button>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="d-flex justify-content-between align-items-center">
						<h6><i class="fa fa-user"></i> Témoin 2 (Femme)</h6>
						<div>
							<button type="button" class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target=".temoin-epouse-2-search-modal-lg">
								<i class="fa fa-search"></i> Rechercher
							</button>
							<button type="button" class="btn btn-danger btn-sm text-white" onclick="viderTemoinEpouse2()">
								<i class="fa fa-trash"></i> Vider
							</button>
						</div>
					</div>
				</div>
			</div>

            <div class="ligne">
                <h4>INFORMATIONS PERSONNELLES</h4>
            </div>
            <div class="mb-2 col-md-3">
                <input type="hidden" id="code_mere">
                <label class="form-label">Nom(s) du mari<span class="text-danger">*</span></label>
                <input type="text" class="form-control " name="nom_t_epouse_1" onkeyup="uppercase(this)" id="nom_t_epouse_1">
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Prénom(s) du mari</label>
                <input type="text" class="form-control"  id="prenom_t_epouse_1" onkeyup="verif_lettre(this);" style="text-transform: capitalize">
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Date de naissance du mari<span class="text-danger">*</span></label><br>
                <input type="date" style="padding-left:10px; border:2px solid silver; height:45px; border-radius:1px; width:100%"  id="date_naissance_t_epouse_1">
            </div>

            <div class="mb-2 col-md-3 localiteepouset1">
                <label class="form-label">Lieu de naissance <span class="text-danger"></span></label>
                <select id="code_localite_t_epouse_1" class="form-control">
                    <option value="">Choisissez</option>
                    @foreach ($lieuNaissances as $localite)
                        <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2 col-md-3 autrelieunaissancetemoinepouse1 d-none">
                <label class="form-label">Autre lieu de naissance<span class="text-danger">*</span></label>
                <input type="text" name="lieu_naissance_t_epouse_1" class="form-control" id="lieu_naissance_t_epouse_1">
            </div>

            <div class="mb-2 col-md-3">
                <label class="form-label">Nationalité du mari<span class="text-danger"></span></label>
                <select id="code_nationalite_t_epouse_1" class="form-control">
                    @foreach ($nationalites as $nationalite)
                        <option value="{{ $nationalite->code_nationalite }}" {{ $nationalite->code_nationalite == "NAT_0001" ? "selected" : "" }}>{{ $nationalite->lib_nationalite }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Profession du mari<span class="text-danger">*</span></label>
                <select id="code_profession_t_epouse_1"  name="code_profession_t_epouse_1" class="form-control">
                    <option value="">Choisissez</option>
                    @foreach ($professions as $profession)
                        <option value="{{ $profession->code_profession }}">{{ $profession->lib_profession }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Type pièce d'identité du mari</label>
                <select id="code_type_document_t_epouse_1" class="form-control form-control wide">
                        <option disabled selected>Choisissez</option>
                    @foreach ($typedocuments as $item)
                        <option value="{{ $item->code_type_document }}">{{ $item->lib_type_document  }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Numéro pièce d'identité du mari</label>
                <input type="text" id="numero_document_t_epouse_1" class="form-control form-control wide" onkeyup="this.value=this.value.toUpperCase()">
            </div>
            {{-- INFOS FEMME DU t --}}
            <div class="ligne2">
            </div>
            <div class="mb-2 col-md-3">
                <input type="hidden" id="code_mere">
                <label class="form-label">Nom(s) de la femme<span class="text-danger">*</span></label>
                <input type="text" class="form-control " name="nom_t_epouse_2" onkeyup="uppercase(this)"  id="nom_t_epouse_2">
            </div>

            <div class="mb-2 col-md-3">
                <label class="form-label">Prénom(s) de la femme</label>
                <input type="text" class="form-control"  id="prenom_t_epouse_2" onkeyup="verif_lettre(this);" style="text-transform: capitalize">
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Date de naissance de la femme<span class="text-danger">*</span></label><br>
                <input type="date" style="padding-left:10px; border:2px solid silver; height:45px; border-radius:1px; width:100%" id="date_naissance_t_epouse_2">
            </div>

            <div class="mb-2 col-md-3 localiteepouset2">
                <label class="form-label">Lieu de naissance <span class="text-danger"></span></label>
                <select id="code_localite_t_epouse_2" class="form-control">
                    <option value="">Choisissez</option>
                    @foreach ($lieuNaissances as $localite)
                        <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2 col-md-3 autrelieunaissancetemoinepouse2 d-none">
                <label class="form-label">Autre lieu de naissance<span class="text-danger">*</span></label>
                <input type="text" name="lieu_naissance_t_epouse_2" class="form-control" id="lieu_naissance_t_epouse_2">
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Nationalité de la femme<span class="text-danger"></span></label>
                <select id="code_nationalite_t_epouse_2" class="form-control">
                    @foreach ($nationalites as $nationalite)
                        <option value="{{ $nationalite->code_nationalite }}" {{ $nationalite->code_nationalite == "NAT_0001" ? "selected" : "" }}>{{ $nationalite->lib_nationalite }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Profession de la femme<span class="text-danger">*</span></label>
                <select id="code_profession_t_epouse_2"  class="form-control">
                    <option value="">Choisissez</option>
                    @foreach ($professions as $profession)
                        <option value="{{ $profession->code_profession }}">{{ $profession->lib_profession }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Type pièce d'identité de la femme</label>
                <select id="code_type_document_t_epouse_2" class="form-control form-control wide">
                        <option disabled selected>Choisissez</option>
                    @foreach ($typedocuments as $item)
                        <option value="{{ $item->code_type_document }}">{{ $item->lib_type_document  }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Numéro pièce d'identité de la femme</label>
                <input type="text" id="numero_document_t_epouse_2" class="form-control form-control wide" onkeyup="this.value=this.value.toUpperCase()">
            </div>

            <div class="ligne">
                <h4>INFORMATIONS SUR L'ADRESSE</h4>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Pays<span class="text-danger"></span></label>
                <select id="domicile_pays_temoins_epouse" class="form-control required">
                    {{-- <option value="">Choisissez</option> --}}
                    @foreach ($countries as $countrie)
                        <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3 domicile_ville_temoins_epouse">
                <label class="form-label">Commune/District<span class="text-danger"></span></label>
                <select class="form-control"  id="domicile_ville_temoins_epouse">
                    <option value="">Choisir</option>
                    @foreach ($lieuNaissances as $localite)
                        <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-3 autredomicile_ville_temoins_epouse d-none">
                <label class="form-label">Ville<span class="text-danger"></span></label>
                <input type="text" id="autredomicile_ville_temoins_epouse" class="form-control form-control wide" placeholder="Libellé de la ville" onkeyup="this.value=this.value.toUpperCase()">
            </div>
            <div class="mb-2 col-md-3 domicile_arrondissement_temoins_epouse">
                <label class="form-label">Arr/Com. Urb<span class="text-danger"></span></label>
                <select class="form-control" id="domicile_arrondissement_temoins_epouse">
                    <option value="">Choisir</option>
                </select>
            </div>
            <div class="mb-2 col-md-3 domicile_quartier_temoins_epouse">
                <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                <select class="form-control" id="domicile_quartier_temoins_epouse">
                    <option value="">Choisir</option>
                </select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Type voie<span class="text-danger"></span></label>
                <select class="form-control" id="domicile_typevoie_temoins_epouse">
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
                <input type="text" class="form-control" id="domicile_numero_temoins_epouse" placeholder="N° voie">
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Nom voie<span class="text-danger"></span></label>
                <input type="text" class="form-control" id="domicile_nomvoie_temoins_epouse" placeholder="Nom voie" style="text-transform: capitalize">
            </div>
		</div>

	</section>
