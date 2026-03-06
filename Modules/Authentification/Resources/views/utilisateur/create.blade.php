@extends('layout.app')
@section('titre')
    Nouvel Utilisateur
@endsection
@section('styles')
<style>
    /* ===== CHARTE COULEURS CONGO-BRAZZAVILLE ===== */
    :root {
        --congo-green:  #009A44;
        --congo-yellow: #F7B731;
        --congo-red:    #DC241F;
        --congo-green-dark: #007A35;
        --congo-green-light: #e8f5ee;
    }

    /* ===== BANDEAU DRAPEAU EN HAUT ===== */
    .page-flag-banner {
        height: 6px;
        background: linear-gradient(to right,
            var(--congo-green) 0%, var(--congo-green) 33.3%,
            var(--congo-yellow) 33.3%, var(--congo-yellow) 66.6%,
            var(--congo-red) 66.6%, var(--congo-red) 100%);
        border-radius: 4px 4px 0 0;
        margin-bottom: 0;
    }

    /* ===== EN-TÊTE PAGE ===== */
    .page-card-header {
        background: linear-gradient(135deg, var(--congo-green) 0%, var(--congo-green-dark) 100%);
        color: white;
        padding: 18px 24px;
        border-radius: 0 !important;
    }
    .page-card-header h4 { color: #fff; font-weight: 700; letter-spacing: 0.3px; }
    .page-card-header small { color: rgba(255,255,255,0.75); }

    /* ===== ALERTE INFO ===== */
    .info-alert {
        background: linear-gradient(135deg, #fffde7 0%, #fff9c4 100%);
        border-left: 4px solid var(--congo-yellow);
        border-radius: 6px;
        padding: 12px 16px;
    }
    .info-alert code {
        background: var(--congo-yellow);
        color: #333;
        padding: 2px 8px;
        border-radius: 4px;
        font-weight: 700;
    }

    /* ===== SECTIONS ===== */
    .section-card {
        border: none;
        border-radius: 10px;
        margin-bottom: 22px;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(0,0,0,0.09);
    }
    .section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 13px 20px;
        color: white;
        font-weight: 700;
        font-size: 0.95rem;
        letter-spacing: 0.3px;
    }
    .section-header.sh-green  { background: linear-gradient(135deg, var(--congo-green) 0%, var(--congo-green-dark) 100%); }
    .section-header.sh-yellow { background: linear-gradient(135deg, #e6a020 0%, #c8870a 100%); }
    .section-header.sh-red    { background: linear-gradient(135deg, var(--congo-red) 0%, #a81a15 100%); }

    .step-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 26px; height: 26px; border-radius: 50%;
        background: rgba(255,255,255,0.25); font-weight: 800;
        font-size: 0.85rem; flex-shrink: 0;
    }

    .section-body {
        padding: 22px 24px;
        background: #fff;
        border: 1px solid rgba(0,0,0,0.06);
        border-top: none;
        border-radius: 0 0 10px 10px;
    }

    /* ===== LABELS & CHAMPS ===== */
    .form-label {
        font-weight: 600;
        font-size: 0.825rem;
        color: #3d4852;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .required-star { color: var(--congo-red); }

    .form-control, .form-select {
        border: 1.5px solid #d0d9e0;
        border-radius: 6px;
        padding: 9px 12px;
        font-size: 0.9rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--congo-green);
        box-shadow: 0 0 0 3px rgba(0,154,68,0.15);
    }
    .form-control::placeholder { color: #aab2bd; font-size: 0.85rem; }

    /* ===== SELECT PICKER ===== */
    .bootstrap-select > .dropdown-toggle {
        border: 1.5px solid #d0d9e0 !important;
        border-radius: 6px !important;
        padding: 9px 12px !important;
        font-size: 0.9rem !important;
        background-color: #fff !important;
        color: #495057 !important;
    }
    .bootstrap-select > .dropdown-toggle:focus {
        border-color: var(--congo-green) !important;
        box-shadow: 0 0 0 3px rgba(0,154,68,0.15) !important;
        outline: none !important;
    }
    .bootstrap-select.show > .dropdown-toggle {
        border-color: var(--congo-green) !important;
    }
    .bootstrap-select .dropdown-menu .bs-searchbox input {
        border: 1.5px solid var(--congo-green) !important;
        border-radius: 5px !important;
    }
    .bootstrap-select .dropdown-menu li.selected > a {
        background-color: var(--congo-green) !important;
        color: #fff !important;
    }
    .bootstrap-select .dropdown-menu li a:hover {
        background-color: var(--congo-green-light) !important;
        color: var(--congo-green-dark) !important;
    }
    .bootstrap-select .filter-option-inner-inner { color: #495057; }
    .bootstrap-select .filter-option-inner-inner.placeholder-text { color: #aab2bd; }

    /* ===== INPUT GROUP ===== */
    .input-group-text {
        background: var(--congo-green-light);
        border: 1.5px solid #d0d9e0;
        color: var(--congo-green);
        font-size: 0.9rem;
    }
    .input-group .form-control { border-left: none; }
    .input-group:focus-within .input-group-text {
        border-color: var(--congo-green);
        background: rgba(0,154,68,0.12);
    }
    .input-group:focus-within .form-control { border-color: var(--congo-green); }

    /* ===== BOUTONS ===== */
    .btn-congo-primary {
        background: linear-gradient(135deg, var(--congo-green) 0%, var(--congo-green-dark) 100%);
        color: #fff; border: none; font-weight: 700;
        padding: 11px 32px; border-radius: 7px;
        transition: all 0.2s; letter-spacing: 0.4px;
    }
    .btn-congo-primary:hover {
        background: linear-gradient(135deg, var(--congo-green-dark) 0%, #005f28 100%);
        color: #fff; transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(0,154,68,0.3);
    }
    .btn-search-person {
        background: linear-gradient(135deg, var(--congo-yellow) 0%, #e09700 100%);
        color: #fff; border: none; font-weight: 600;
        border-radius: 7px; font-size: 0.875rem;
        transition: all 0.2s;
    }
    .btn-search-person:hover {
        background: linear-gradient(135deg, #e09700 0%, #c07800 100%);
        color: #fff; transform: translateY(-1px);
    }

    /* ===== BREADCRUMB ===== */
    .breadcrumb-item a { color: var(--congo-green) !important; text-decoration: none; }
    .breadcrumb-item a:hover { text-decoration: underline; }
    .breadcrumb-item.active { color: #6c757d; }
    .breadcrumb-item + .breadcrumb-item::before { color: var(--congo-green); }

    /* ===== MODAL ===== */
    .modal-flag-header {
        background: linear-gradient(135deg, var(--congo-green) 0%, var(--congo-green-dark) 100%);
        color: white;
        padding: 18px 24px;
        border-radius: 0;
    }
    .modal-content { border: none; border-radius: 10px; overflow: hidden; }
    .modal-flag-header::after {
        content: '';
        display: block;
        height: 4px;
        background: linear-gradient(to right, var(--congo-yellow) 0%, var(--congo-yellow) 50%, var(--congo-red) 50%, var(--congo-red) 100%);
        margin-top: 12px;
        border-radius: 2px;
    }

    /* ===== RESULTAT RECHERCHE ===== */
    .search-result-table th {
        background: var(--congo-green-light);
        color: var(--congo-green-dark);
        font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.3px;
    }
    .btn-select-person {
        background: var(--congo-green); color: #fff;
        border: none; border-radius: 5px; padding: 4px 12px;
        font-size: 0.82rem; font-weight: 600;
        transition: background 0.2s;
    }
    .btn-select-person:hover { background: var(--congo-green-dark); color: #fff; }

    /* ===== ACTIONS ===== */
    .actions-bar {
        background: linear-gradient(135deg, #f8f9fa 0%, #eef0f2 100%);
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 16px 20px;
    }
</style>
@endsection

@section('corps')
<div class="container-fluid px-0">

    {{-- Bandeau drapeau --}}
    <div class="page-flag-banner mb-3"></div>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"><i class="fas fa-home"></i> Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('utilisateur.index') }}"><i class="fas fa-users"></i> Utilisateurs</a></li>
            <li class="breadcrumb-item active"><i class="fas fa-user-plus"></i> Nouvel utilisateur</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm" style="border-radius:10px;overflow:hidden;">

        {{-- En-tête --}}
        <div class="page-card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i>Créer un nouvel utilisateur</h4>
                <small>Remplissez toutes les informations requises (<span class="text-warning">*</span> = obligatoire)</small>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-search-person btn-sm px-3"
                        data-bs-toggle="modal" data-bs-target="#modalPersonne">
                    <i class="fas fa-search me-1"></i> Personne existante
                </button>
                <a href="{{ route('utilisateur.index') }}" class="btn btn-secondary btn-sm px-3">
                    <i class="fas fa-arrow-left me-1"></i> Retour
                </a>
            </div>
        </div>

        <div class="card-body p-4">

            <!-- Info mot de passe -->
            <div class="info-alert mb-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-shield-alt fa-lg" style="color:var(--congo-yellow)"></i>
                    <div>
                        <strong>Mot de passe initial :</strong> Le compte sera créé avec le mot de passe par défaut
                        <code>123456</code>. L'utilisateur devra le modifier lors de sa première connexion.
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('utilisateur.store') }}"
                  enctype="multipart/form-data" id="createUserForm">
                @csrf
                <input type="hidden" id="code_personne" name="code_personne">

                {{-- ====== SECTION 1 : INFORMATIONS PERSONNELLES ====== --}}
                <div class="section-card">
                    <div class="section-header sh-green">
                        <span class="step-badge">1</span>
                        <i class="fas fa-user"></i>
                        <span>Informations Personnelles</span>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Nom(s) <span class="required-star">*</span></label>
                                <input type="text"
                                       class="form-control @error('nom') is-invalid @enderror"
                                       id="nom_personne" name="nom"
                                       value="{{ old('nom') }}"
                                       placeholder="Nom de famille"
                                       oninput="uppercase(this)">
                                @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Prénom(s)</label>
                                <input type="text"
                                       class="form-control @error('prenom') is-invalid @enderror"
                                       id="prenom_personne" name="prenom"
                                       value="{{ old('prenom') }}"
                                       placeholder="Prénom(s)">
                                @error('prenom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sexe <span class="required-star">*</span></label>
                                <select name="sexe" id="sexe_personne"
                                        class="form-select @error('sexe') is-invalid @enderror">
                                    <option value="">-- Choisissez --</option>
                                    <option value="M" {{ old('sexe')=='M'?'selected':'' }}>Masculin</option>
                                    <option value="F" {{ old('sexe')=='F'?'selected':'' }}>Féminin</option>
                                </select>
                                @error('sexe')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Date de naissance <span class="required-star">*</span></label>
                                <input type="date"
                                       class="form-control @error('date_naissance') is-invalid @enderror"
                                       id="date_naissance_personne" name="date_naissance"
                                       value="{{ old('date_naissance') }}"
                                       max="{{ date('Y-m-d', strtotime('-18 years')) }}">
                                @error('date_naissance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Lieu de naissance <span class="required-star">*</span></label>
                                <select id="code_localite" name="code_localite"
                                        class="selectpicker @error('code_localite') is-invalid @enderror"
                                        data-live-search="true"
                                        data-live-search-placeholder="Tapez pour rechercher..."
                                        data-size="7"
                                        title="-- Tapez ou sélectionnez --"
                                        data-style="form-control select-congo">
                                    @foreach ($localites as $localite)
                                        <option value="{{ $localite->code_localite }}"
                                            {{ old('code_localite')==$localite->code_localite?'selected':'' }}>
                                            {{ $localite->lib_localite }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('code_localite')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nationalité <span class="required-star">*</span></label>
                                <select name="code_nationalite" id="code_nationalite_personne"
                                        class="selectpicker @error('code_nationalite') is-invalid @enderror"
                                        data-live-search="true"
                                        data-live-search-placeholder="Tapez pour rechercher..."
                                        data-size="7"
                                        title="-- Tapez ou sélectionnez --"
                                        data-style="form-control select-congo">
                                    @foreach ($nationalites as $nat)
                                        <option value="{{ $nat->code_nationalite }}"
                                            {{ old('code_nationalite')==$nat->code_nationalite?'selected':'' }}>
                                            {{ $nat->lib_nationalite }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('code_nationalite')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Adresse / Domicile <span class="required-star">*</span></label>
                                <input type="text"
                                       class="form-control @error('adresse') is-invalid @enderror"
                                       id="adresse_personne" name="adresse"
                                       value="{{ old('adresse') }}"
                                       placeholder="Ex : Quartier Bacongo, Brazzaville">
                                @error('adresse')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Téléphone</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text"
                                           class="form-control @error('pseudo') is-invalid @enderror"
                                           id="pseudo_personne" name="pseudo"
                                           value="{{ old('pseudo') }}"
                                           placeholder="Ex : 06 XXX XX XX"
                                           oninput="verif_nombre(this)">
                                </div>
                                @error('pseudo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ====== SECTION 2 : PIÈCE D'IDENTITÉ ====== --}}
                <div class="section-card">
                    <div class="section-header sh-yellow">
                        <span class="step-badge">2</span>
                        <i class="fas fa-id-card"></i>
                        <span>Pièce d'Identité</span>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Type de pièce <span class="required-star">*</span></label>
                                <select name="code_type_document"
                                        class="selectpicker @error('code_type_document') is-invalid @enderror"
                                        data-live-search="true"
                                        data-live-search-placeholder="Tapez pour rechercher..."
                                        title="-- Choisir le type de pièce --"
                                        data-style="form-control select-congo">
                                    @foreach ($typeDocuments as $item)
                                        <option value="{{ $item->code_type_document }}"
                                            {{ old('code_type_document')==$item->code_type_document?'selected':'' }}>
                                            {{ $item->lib_type_document }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('code_type_document')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Numéro de la pièce <span class="required-star">*</span></label>
                                <input type="text"
                                       class="form-control @error('numero_document') is-invalid @enderror"
                                       name="numero_document"
                                       value="{{ old('numero_document') }}"
                                       placeholder="N° de la pièce d'identité">
                                @error('numero_document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ====== SECTION 3 : AFFECTATION & COMPTE ====== --}}
                <div class="section-card">
                    <div class="section-header sh-red">
                        <span class="step-badge">3</span>
                        <i class="fas fa-briefcase"></i>
                        <span>Affectation &amp; Compte</span>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Fonction <span class="required-star">*</span></label>
                                <select name="code_fonction" id="code_fonction_personne"
                                        class="selectpicker @error('code_fonction') is-invalid @enderror"
                                        data-live-search="true"
                                        data-live-search-placeholder="Tapez pour rechercher..."
                                        title="-- Choisir une fonction --"
                                        data-style="form-control select-congo">
                                    @foreach ($fonctions as $fn)
                                        <option value="{{ $fn->code_fonction }}"
                                            {{ old('code_fonction')==$fn->code_fonction?'selected':'' }}>
                                            {{ $fn->lib_fonction }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('code_fonction')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Type de centre d'état civil <span class="required-star">*</span></label>
                                <select name="code_type_institution" id="codetypeinstitution"
                                        class="selectpicker @error('code_type_institution') is-invalid @enderror"
                                        data-live-search="true"
                                        data-live-search-placeholder="Tapez pour rechercher..."
                                        title="-- Choisir le type --"
                                        data-style="form-control select-congo">
                                    @foreach ($typeInstitutions as $type)
                                        <option value="{{ $type->code_type_institution }}"
                                            {{ old('code_type_institution')==$type->code_type_institution?'selected':'' }}>
                                            {{ $type->lib_type_institution }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('code_type_institution')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 cecrattache d-none">
                                <label class="form-label">Centre d'état civil rattaché <span class="required-star">*</span></label>
                                <select name="code_institution" id="codeinstitution"
                                        class="selectpicker @error('code_institution') is-invalid @enderror"
                                        data-live-search="true"
                                        data-live-search-placeholder="Tapez pour rechercher..."
                                        title="-- Choisir l'institution --"
                                        data-style="form-control select-congo">
                                </select>
                                @error('code_institution')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Adresse email <span class="required-star">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           name="email"
                                           value="{{ old('email') }}"
                                           placeholder="exemple@domaine.com">
                                </div>
                                @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ====== BOUTONS ====== --}}
                <div class="actions-bar d-flex justify-content-between align-items-center">
                    <a href="{{ route('utilisateur.index') }}" class="btn btn-secondary px-4">
                        <i class="fas fa-times me-1"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-congo-primary px-5" id="submitBtn">
                        <i class="fas fa-save me-2"></i> Créer l'utilisateur
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ====== MODAL RECHERCHE PERSONNE ====== --}}
    <div class="modal fade" id="modalPersonne" data-bs-backdrop="static"
         tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-flag-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="text-white mb-1 fw-bold">
                                <i class="fas fa-search me-2"></i>Rechercher une personne existante
                            </h5>
                            <small class="text-white-50">
                                Si la personne existe déjà dans le système, elle sera pré-remplie automatiquement.
                            </small>
                        </div>
                        <button type="button" class="btn-close btn-close-white mt-1"
                                data-bs-dismiss="modal"></button>
                    </div>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Nom <span class="required-star">*</span></label>
                            <input type="text" class="form-control" id="modal_nom"
                                   placeholder="Nom de famille">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="modal_prenom"
                                   placeholder="Prénom(s)">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sexe <span class="required-star">*</span></label>
                            <select id="modal_sexe" class="form-select">
                                <option value="">-- Choisissez --</option>
                                <option value="M">Masculin</option>
                                <option value="F">Féminin</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date de naissance <span class="required-star">*</span></label>
                            <input type="date" class="form-control" id="modal_date_naissance"
                                   max="{{ date('Y-m-d', strtotime('-18 years')) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Téléphone <span class="required-star">*</span></label>
                            <input type="text" class="form-control" id="modal_telephone"
                                   placeholder="Numéro de téléphone">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="button" class="btn btn-congo-primary w-100 tosearch">
                                <i class="fas fa-search me-1"></i> Rechercher
                            </button>
                        </div>
                    </div>
                    <div id="resultatPersonne" class="mt-3"></div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function () {
    // Init selectpicker
    $('.selectpicker').selectpicker();

    // Charger institutions selon type d'institution sélectionné
    $('#codetypeinstitution').on('change', function () {
        var val = $(this).val();
        if (val) {
            $('div.cecrattache').removeClass('d-none');
            getInstitution(val);
        } else {
            $('div.cecrattache').addClass('d-none');
            $('#codeinstitution').html('<option value="">-- Choisir l\'institution --</option>').selectpicker('refresh');
        }
    });

    // Supprimer is-invalid à la saisie dans le modal
    $('#modalPersonne input, #modalPersonne select').on('input change', function() {
        $(this).removeClass('is-invalid');
    });

    // Recherche dans le modal
    $('.tosearch').on('click', function (e) {
        e.preventDefault();
        var hasError = false;
        ['#modal_nom', '#modal_date_naissance', '#modal_sexe', '#modal_telephone'].forEach(function(sel) {
            var el = $(sel);
            if (!el.val()) { el.addClass('is-invalid'); hasError = true; }
            else { el.removeClass('is-invalid'); }
        });
        if (hasError) return;

        var btn = $(this);
        btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Recherche...').prop('disabled', true);

        $.ajax({
            url: "{{ route('utilisateur.search') }}",
            data: {
                nom: $('#modal_nom').val(),
                prenom: $('#modal_prenom').val(),
                date_naissance: $('#modal_date_naissance').val(),
                sexe: $('#modal_sexe').val(),
                telephone: $('#modal_telephone').val()
            },
            success: function (res) {
                btn.html('<i class="fas fa-search me-1"></i> Rechercher').prop('disabled', false);
                renderResults(res.personnes);
            },
            error: function () {
                btn.html('<i class="fas fa-search me-1"></i> Rechercher').prop('disabled', false);
                $('#resultatPersonne').html('<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>Erreur lors de la recherche.</div>');
            }
        });
    });
});

function renderResults(personnes) {
    var $c = $('#resultatPersonne');
    if (!personnes || !personnes.length) {
        $c.html('<div class="alert alert-warning"><i class="fas fa-info-circle me-2"></i>Aucune personne trouvée avec ces critères.</div>');
        return;
    }
    var html = '<hr class="my-3"><p class="text-muted small"><i class="fas fa-list me-1"></i>' + personnes.length + ' résultat(s)</p>';
    html += '<div class="table-responsive"><table class="table table-hover table-bordered search-result-table align-middle">';
    html += '<thead><tr><th>#</th><th>Nom & Prénom</th><th>Naissance</th><th>Sexe</th><th>Téléphone</th><th>Action</th></tr></thead><tbody>';
    personnes.forEach(function(p, i) {
        var sexeBadge = p.sexe === 'F'
            ? '<span class="badge" style="background:var(--congo-red)">Féminin</span>'
            : '<span class="badge" style="background:var(--congo-green)">Masculin</span>';
        html += '<tr>'
             + '<td class="text-center fw-bold">' + (i+1) + '</td>'
             + '<td><strong>' + (p.nom||'') + '</strong> ' + (p.prenom||'') + '</td>'
             + '<td>' + (p.date_naissance||'-') + '</td>'
             + '<td>' + sexeBadge + '</td>'
             + '<td>' + (p.telephone||'-') + '</td>'
             + '<td><button type="button" class="btn-select-person" data-p=\'' + JSON.stringify(p).replace(/'/g, '&#39;') + '\'>Choisir</button></td>'
             + '</tr>';
    });
    html += '</tbody></table></div>';
    $c.html(html);

    $('.btn-select-person').on('click', function () {
        var p = $(this).data('p');
        $('#code_personne').val(p.code_personne||'');
        $('#nom_personne').val(p.nom||'');
        $('#prenom_personne').val(p.prenom||'');
        $('#date_naissance_personne').val(p.date_naissance||'');
        $('#sexe_personne').val(p.sexe||'');
        $('#adresse_personne').val(p.adresse||'');
        $('#pseudo_personne').val(p.telephone||'');
        $('#code_nationalite_personne').selectpicker('val', p.code_nationalite||'');
        $('#modalPersonne').modal('hide');
        // Feedback visuel
        $('#createUserForm').prepend(
            '<div class="alert alert-success alert-dismissible fade show">'
            + '<i class="fas fa-check-circle me-2"></i><strong>' + (p.nom||'') + ' ' + (p.prenom||'') + '</strong> — données pré-remplies. Vérifiez et complétez.'
            + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>'
        );
    });
}

function getInstitution(id) {
    $.get("{{ route('utilisateur.getinstitution') }}", { id: id }, function (data) {
        var opts = '<option value="">-- Choisir l\'institution --</option>';
        (data||[]).forEach(function(inst) {
            opts += '<option value="' + inst.code_institution + '">' + inst.lib_institution + '</option>';
        });
        $('#codeinstitution').html(opts).selectpicker('refresh');
    });
}
</script>
@endsection
