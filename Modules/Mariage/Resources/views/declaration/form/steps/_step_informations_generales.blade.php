    <!-- Step 4 -->
    <h6>Informations générales</h6>
    <section>
        <div class="row">
            <div class="mb-2 col-sm-3">
                <label class="radio-inline mr-3">Avez-vous pris connaissance des résultats des examens prénuptiaux ?</label>
                <label class="radio-inline mr-3"><input type="radio" class="examens_prenuptiaux" name="examens_prenuptiaux" value="1"> OUI</label>
                <label class="radio-inline mr-3"><input type="radio" class="examens_prenuptiaux" name="examens_prenuptiaux" value="0"> NON</label>
            </div>
            <div class="mb-2 col-md-2 optionmariage">
                <label class="form-label">Option de mariage<span class="text-danger">*</span></label>
                <select type="text" id="option_mariage" class="form-control" onchange="verifierOptionMariage()">
                    <option value="">Choisissez</option>
					@foreach ($optionmariages as $item)
                    <option value="{{ $item->code_option_mariage }}">{{ $item->lib_option_mariage }}</option>
                    @endforeach
				</select>
            </div>
            <div class="mb-2 col-md-4 showregime d-none">
                <label class="form-label">Régime de mariage<span class="text-danger">*</span></label>
                <select type="text" id="regime_mariage"  class="form-control regimes">

				</select>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Date de célébration du mariage<span class="text-danger">*</span></label>
                <input type="date"  id="date_ceremonie_mariage" style="padding-left:10px; border:2px solid silver; height:45px; border-radius:1px; width:100%" />
                <div class="mariage-legal-hint mariage-legal-hint--alert" id="notificationPreMariage" style="display:none" role="alert">
                    <div class="mariage-legal-hint__row">
                        <span class="mariage-legal-hint__icon" aria-hidden="true"><i class="fa fa-calendar-times"></i></span>
                        <p class="mariage-legal-hint__text">La date du pré-mariage ne peut pas être supérieure à la date du mariage.</p>
                    </div>
                </div>
                <div class="notification mariage-legal-hint" style="display:none" role="status">
                    <div class="mariage-legal-hint__row">
                        <span class="mariage-legal-hint__icon" aria-hidden="true"><i class="fa fa-gavel"></i></span>
                        <div>
                            <p class="mariage-legal-hint__text">La date de célébration est inférieure à 60 jours à compter de la date de déclaration : cette déclaration requiert une réquisition conformément à l&rsquo;article 144 du code de la famille.</p>
                            <span class="mariage-legal-hint__ref">Article 144</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-2 col-md-3">
                <label class="form-label">Lieu de célébration du mariage<span class="text-danger">*</span></label>
                <select type="text" id="lieu_ceremonie_mariage" class="form-control">
					<option disabled selected>Choisissez</option>
                    @foreach ($LieuCeremonie as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
				</select>
                <div class="notification2 mariage-legal-hint" style="display:none" role="status">
                    <div class="mariage-legal-hint__row">
                        <span class="mariage-legal-hint__icon" aria-hidden="true"><i class="fa fa-map-marker"></i></span>
                        <div>
                            <p class="mariage-legal-hint__text">Le lieu de la célébration du mariage choisi requiert une réquisition conformément à l&rsquo;article 151 du code de la famille.</p>
                            <span class="mariage-legal-hint__ref">Article 151</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Adresse détaillée de la cérémonie -->
            <div class="row adresse_ceremonie_details d-none">
                <div class="mb-2 col-md-3">
                    <label class="form-label">Pays<span class="text-danger"></span></label>
                    <select id="domicile_pays_ceremonie" class="form-control required">
                        {{-- <option value="">Choisissez</option> --}}
                        @foreach ($countries as $countrie)
                            <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2 col-md-3 domicile_ville_ceremonie">
                    <label class="form-label">Commune/District<span class="text-danger"></span></label>
                    <select class="form-control" id="domicile_ville_ceremonie">
                        <option value="">Choisir</option>
                        @foreach ($lieuNaissances as $localite)
                            <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2 col-md-3 autredomicile_ville_ceremonie d-none">
                    <label class="form-label">Ville<span class="text-danger"></span></label>
                    <input type="text" id="autredomicile_ville_ceremonie" class="form-control form-control wide" placeholder="Libellé de la ville" onkeyup="this.value=this.value.toUpperCase()">
                </div>
                <div class="mb-2 col-md-3 domicile_arrondissement_ceremonie">
                    <label class="form-label">Arr/Com. Urb<span class="text-danger"></span></label>
                    <select class="form-control" id="domicile_arrondissement_ceremonie">
                        <option value="">Choisir</option>
                    </select>
                </div>
                <div class="mb-2 col-md-3 domicile_quartier_ceremonie">
                    <label class="form-label">Quartier/Village<span class="text-danger"></span></label>
                    <select class="form-control" id="domicile_quartier_ceremonie">
                        <option value="">Choisir</option>
                    </select>
                </div>
                <div class="mb-2 col-md-3">
                    <label class="form-label">Type voie<span class="text-danger"></span></label>
                    <select class="form-control" id="domicile_typevoie_ceremonie">
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
                    <input type="text" class="form-control" id="domicile_numero_ceremonie" placeholder="N° voie">
                </div>
                <div class="mb-2 col-md-3">
                    <label class="form-label">Nom voie<span class="text-danger"></span></label>
                    <input type="text" class="form-control" id="domicile_nomvoie_ceremonie" placeholder="Nom voie" style="text-transform: capitalize">
                </div>
            </div>

            <div class="mb-2 col-md-2">
                <label class="form-label">Date de la déclaration <span class="text-danger"></span></label><br>
                <input type="date" max="<?= date('Y-m-d') ?>" style="padding-left:10px; border:2px solid silver; height:45px; border-radius:1px; width:100%"  id="date_declaration_mariage">
            </div>

        </div>
</section>
