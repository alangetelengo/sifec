@extends('layout.app')
@section('titre')
    Modifier — {{ $user->personne->nom ?? '' }}
@endsection
@section('styles')
<style>
    :root {
        --congo-green:  #009A44;
        --congo-yellow: #F7B731;
        --congo-red:    #DC241F;
        --congo-green-dark: #007A35;
        --congo-green-light: #e8f5ee;
    }
    .page-flag-banner {
        height: 6px;
        background: linear-gradient(to right,
            var(--congo-green) 0%, var(--congo-green) 33.3%,
            var(--congo-yellow) 33.3%, var(--congo-yellow) 66.6%,
            var(--congo-red) 66.6%, var(--congo-red) 100%);
        border-radius: 4px 4px 0 0;
    }
    .page-card-header {
        background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);
        color: white; padding: 18px 24px;
    }
    .page-card-header h4 { color: #fff; font-weight: 700; }
    .page-card-header small { color: rgba(255,255,255,0.75); }

    /* Bandeau résumé utilisateur */
    .user-summary {
        background: linear-gradient(135deg, var(--congo-green-light) 0%, #f0fff6 100%);
        border: 1px solid rgba(0,154,68,0.2);
        border-left: 5px solid var(--congo-green);
        border-radius: 8px; padding: 16px 20px;
    }
    .user-avatar-circle {
        width: 52px; height: 52px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; color: #fff; flex-shrink: 0;
    }

    /* Sections */
    .section-card {
        border: none; border-radius: 10px;
        margin-bottom: 22px; overflow: hidden;
        box-shadow: 0 3px 12px rgba(0,0,0,0.09);
    }
    .section-header {
        display: flex; align-items: center; gap: 12px;
        padding: 13px 20px; color: white;
        font-weight: 700; font-size: 0.95rem; letter-spacing: 0.3px;
    }
    .section-header.sh-teal   { background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%); }
    .section-header.sh-purple { background: linear-gradient(135deg, #6f42c1 0%, #52309a 100%); }
    .section-header.sh-orange { background: linear-gradient(135deg, #fd7e14 0%, #c96400 100%); }
    .step-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 26px; height: 26px; border-radius: 50%;
        background: rgba(255,255,255,0.25); font-weight: 800; font-size: 0.85rem; flex-shrink: 0;
    }
    .section-body {
        padding: 22px 24px; background: #fff;
        border: 1px solid rgba(0,0,0,0.06); border-top: none;
        border-radius: 0 0 10px 10px;
    }
    .form-label {
        font-weight: 600; font-size: 0.825rem;
        color: #3d4852; margin-bottom: 5px;
        text-transform: uppercase; letter-spacing: 0.4px;
    }
    .required-star { color: var(--congo-red); }
    .form-control, .form-select {
        border: 1.5px solid #d0d9e0; border-radius: 6px;
        padding: 9px 12px; font-size: 0.9rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #17a2b8;
        box-shadow: 0 0 0 3px rgba(23,162,184,0.15);
    }
    .form-control.readonly-field {
        background-color: #f4f6f8; color: #6c757d;
        cursor: not-allowed; border-style: dashed;
    }
    .locked-hint {
        font-size: 0.75rem; color: #9aabb5; margin-top: 3px;
        display: flex; align-items: center; gap: 4px;
    }

    /* Bootstrap select */
    .bootstrap-select > .dropdown-toggle {
        border: 1.5px solid #d0d9e0 !important; border-radius: 6px !important;
        padding: 9px 12px !important; font-size: 0.9rem !important;
        background-color: #fff !important; color: #495057 !important;
    }
    .bootstrap-select > .dropdown-toggle:focus {
        border-color: #17a2b8 !important;
        box-shadow: 0 0 0 3px rgba(23,162,184,0.15) !important; outline: none !important;
    }
    .bootstrap-select.show > .dropdown-toggle { border-color: #17a2b8 !important; }
    .bootstrap-select .dropdown-menu .bs-searchbox input {
        border: 1.5px solid #17a2b8 !important; border-radius: 5px !important;
    }
    .bootstrap-select .dropdown-menu li.selected > a { background-color: #17a2b8 !important; color: #fff !important; }
    .bootstrap-select .dropdown-menu li a:hover { background-color: #e8f7fa !important; color: #117a8b !important; }

    .input-group-text {
        background: #e8f7fa; border: 1.5px solid #d0d9e0; color: #17a2b8;
    }
    .input-group .form-control { border-left: none; }
    .input-group:focus-within .input-group-text { border-color: #17a2b8; background: rgba(23,162,184,0.1); }
    .input-group:focus-within .form-control { border-color: #17a2b8; }

    .btn-save {
        background: linear-gradient(135deg, #fd7e14 0%, #c96400 100%);
        color: #fff; border: none; font-weight: 700;
        padding: 11px 32px; border-radius: 7px; transition: all 0.2s;
    }
    .btn-save:hover {
        background: linear-gradient(135deg, #c96400 0%, #a05000 100%);
        color: #fff; transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(253,126,20,0.3);
    }
    .actions-bar {
        background: linear-gradient(135deg, #f8f9fa 0%, #eef0f2 100%);
        border: 1px solid #dee2e6; border-radius: 10px; padding: 16px 20px;
    }
    .breadcrumb-item a { color: var(--congo-green) !important; text-decoration: none; }
    .breadcrumb-item a:hover { text-decoration: underline; }
    .breadcrumb-item + .breadcrumb-item::before { color: var(--congo-green); }
</style>
@endsection

@section('corps')
<div class="container-fluid px-0">

    <div class="page-flag-banner mb-3"></div>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"><i class="fas fa-home"></i> Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('utilisateur.index') }}"><i class="fas fa-users"></i> Utilisateurs</a></li>
            <li class="breadcrumb-item"><a href="{{ route('utilisateur.profile', $user->code_user) }}">{{ $user->personne->nom ?? '' }}</a></li>
            <li class="breadcrumb-item active"><i class="fas fa-edit"></i> Modifier</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm" style="border-radius:10px;overflow:hidden;">

        {{-- En-tête --}}
        <div class="page-card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h4 class="mb-0"><i class="fas fa-user-edit me-2"></i>Modifier l'utilisateur</h4>
                <small>Les champs personnels sont verrouillés — seuls les champs modifiables sont éditables</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('utilisateur.profile', $user->code_user) }}" class="btn btn-sm"
                   style="background:rgba(255,255,255,0.2);color:#fff;border:1px solid rgba(255,255,255,0.4);">
                    <i class="fas fa-eye me-1"></i> Voir le profil
                </a>
                <a href="{{ route('utilisateur.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Retour
                </a>
            </div>
        </div>

        <div class="card-body p-4">

            <!-- Résumé utilisateur -->
            <div class="user-summary mb-4">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="user-avatar-circle"
                         style="background: {{ $user->personne->sexe=='F' ? 'linear-gradient(135deg,#f093fb,#f5576c)' : 'linear-gradient(135deg,var(--congo-green),var(--congo-green-dark))' }}">
                        <i class="fas fa-{{ $user->personne->sexe=='F' ? 'female' : 'male' }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mb-1 fw-bold" style="color:var(--congo-green-dark)">
                            {{ $user->personne->nom ?? '' }} {{ $user->personne->prenom ?? '' }}
                        </h5>
                        <div class="d-flex gap-3 flex-wrap">
                            <small class="text-muted"><i class="fas fa-envelope me-1"></i>{{ $user->email }}</small>
                            <small class="text-muted"><i class="fas fa-building me-1"></i>{{ $user->affectationActive()?->institution?->lib_institution ?? 'Non affecté' }}</small>
                            <small class="text-muted"><i class="fas fa-briefcase me-1"></i>{{ $user->affectationActive()?->fonction?->lib_fonction ?? 'Non défini' }}</small>
                        </div>
                    </div>
                    <span class="badge {{ $user->status ? 'bg-success' : 'bg-danger' }} px-3 py-2">
                        <i class="fas fa-circle me-1" style="font-size:0.6rem"></i>
                        {{ $user->status ? 'Compte actif' : 'Compte inactif' }}
                    </span>
                </div>
            </div>

            <form method="POST" action="{{ route('utilisateur.update', $user->code_user) }}"
                  enctype="multipart/form-data" id="editUserForm">
                @csrf
                @method('PUT')

                {{-- ====== SECTION 1 : INFORMATIONS PERSONNELLES (verrouillées) ====== --}}
                <div class="section-card">
                    <div class="section-header sh-teal">
                        <span class="step-badge">1</span>
                        <i class="fas fa-user"></i>
                        <span>Informations Personnelles</span>
                        <span class="ms-auto badge" style="background:rgba(255,255,255,0.2);font-size:0.75rem;">
                            <i class="fas fa-lock me-1"></i>Verrouillé — registre civil
                        </span>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Nom(s)</label>
                                <input type="text" class="form-control readonly-field"
                                       value="{{ $user->personne->nom ?? '' }}" name="nom" readonly>
                                <div class="locked-hint"><i class="fas fa-lock"></i> Non modifiable</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Prénom(s)</label>
                                <input type="text" class="form-control readonly-field"
                                       value="{{ $user->personne->prenom ?? '' }}" name="prenom" readonly>
                                <div class="locked-hint"><i class="fas fa-lock"></i> Non modifiable</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sexe</label>
                                <input type="text" class="form-control readonly-field"
                                       value="{{ $user->personne->sexe == 'F' ? 'Féminin' : 'Masculin' }}" readonly>
                                <input type="hidden" name="sexe" value="{{ $user->personne->sexe ?? '' }}">
                                <div class="locked-hint"><i class="fas fa-lock"></i> Non modifiable</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Date de naissance</label>
                                <input type="date" class="form-control readonly-field"
                                       value="{{ $user->personne->date_naissance ?? '' }}" name="date_naissance" readonly>
                                <div class="locked-hint"><i class="fas fa-lock"></i> Non modifiable</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Lieu de naissance</label>
                                <input type="text" class="form-control readonly-field"
                                       value="{{ $user->personne->lieu_naissance ?? '' }}" name="lieu_naissance" readonly>
                                <div class="locked-hint"><i class="fas fa-lock"></i> Non modifiable</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nationalité <span class="required-star">*</span></label>
                                <select name="code_nationalite" id="code_nationalite_personne"
                                        class="selectpicker @error('code_nationalite') is-invalid @enderror"
                                        data-live-search="true"
                                        data-live-search-placeholder="Tapez pour rechercher..."
                                        data-size="7"
                                        title="-- Sélectionner --"
                                        data-style="form-control">
                                    @foreach ($nationalites as $nat)
                                        <option value="{{ $nat->code_nationalite }}"
                                            {{ ($user->personne->code_nationalite ?? '') == $nat->code_nationalite ? 'selected' : '' }}>
                                            {{ $nat->lib_nationalite }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('code_nationalite')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Adresse / Domicile <span class="required-star">*</span></label>
                                <input type="text" class="form-control @error('adresse') is-invalid @enderror"
                                       id="adresse_personne" name="adresse"
                                       value="{{ old('adresse', $user->personne->adresse ?? '') }}"
                                       placeholder="Adresse domicile">
                                @error('adresse')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Téléphone</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text" class="form-control @error('telephone') is-invalid @enderror"
                                           id="telephone_personne" name="telephone"
                                           value="{{ old('telephone', $user->personne->telephone ?? '') }}"
                                           placeholder="Numéro de téléphone">
                                </div>
                                @error('telephone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ====== SECTION 2 : NOUVEAU DOCUMENT D'IDENTITÉ ====== --}}
                <div class="section-card">
                    <div class="section-header sh-purple">
                        <span class="step-badge">2</span>
                        <i class="fas fa-id-card"></i>
                        <span>Nouveau Document d'Identité</span>
                        <span class="ms-auto badge" style="background:rgba(255,255,255,0.2);font-size:0.75rem;">Facultatif</span>
                    </div>
                    <div class="section-body">
                        <div class="alert alert-warning py-2 mb-3 border-0"
                             style="background:#fff8e1;border-left:4px solid var(--congo-yellow) !important;border-radius:6px;">
                            <i class="fas fa-info-circle me-2" style="color:var(--congo-yellow)"></i>
                            <small>Remplir uniquement pour ajouter un nouveau document d'identité à cet utilisateur.</small>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Type de pièce <span class="required-star">*</span></label>
                                <select name="code_type_document"
                                        class="selectpicker @error('code_type_document') is-invalid @enderror"
                                        data-live-search="true"
                                        data-live-search-placeholder="Tapez pour rechercher..."
                                        title="-- Choisir le type --"
                                        data-style="form-control">
                                    @foreach ($typeDocuments as $item)
                                        <option value="{{ $item->code_type_document }}">{{ $item->lib_type_document }}</option>
                                    @endforeach
                                </select>
                                @error('code_type_document')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Numéro de la pièce <span class="required-star">*</span></label>
                                <input type="text"
                                       class="form-control @error('numero_document') is-invalid @enderror"
                                       name="numero_document" value="{{ old('numero_document') }}"
                                       placeholder="Numéro de la pièce d'identité">
                                @error('numero_document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ====== SECTION 3 : AFFECTATION & COMPTE ====== --}}
                <div class="section-card">
                    <div class="section-header sh-orange">
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
                                        data-style="form-control">
                                    @foreach ($fonctions as $fn)
                                        <option value="{{ $fn->code_fonction }}"
                                            {{ $user->affectationActive()?->fonction?->code_fonction == $fn->code_fonction ? 'selected' : '' }}>
                                            {{ $fn->lib_fonction }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('code_fonction')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Centre d'état civil <span class="required-star">*</span></label>
                                <select name="code_institution"
                                        class="selectpicker @error('code_institution') is-invalid @enderror"
                                        data-live-search="true"
                                        data-live-search-placeholder="Tapez pour rechercher..."
                                        title="-- Choisir l'institution --"
                                        data-style="form-control">
                                    @foreach ($institutions as $ins)
                                        <option value="{{ $ins->code_institution }}"
                                            {{ $user->affectationActive()?->institution?->code_institution == $ins->code_institution ? 'selected' : '' }}>
                                            {{ $ins->lib_institution }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('code_institution')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Adresse email <span class="required-star">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           name="email" value="{{ old('email', $user->email) }}"
                                           placeholder="exemple@domaine.com">
                                </div>
                                @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Niveau d'instruction</label>
                                <select name="niveau_instruction" class="form-select">
                                    <option value="">-- Non précisé --</option>
                                    @foreach ($niveauInstructions as $ni)
                                        <option value="{{ $ni }}"
                                            {{ old('niveau_instruction', $user->personne->niveau_instruction ?? '') == $ni ? 'selected' : '' }}>
                                            {{ $ni }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Statut du compte <span class="required-star">*</span></label>
                                <select name="active" class="form-select @error('active') is-invalid @enderror">
                                    <option value="1" {{ $user->status == '1' ? 'selected' : '' }}>✅ Actif</option>
                                    <option value="0" {{ $user->status == '0' ? 'selected' : '' }}>⛔ Inactif</option>
                                </select>
                                @error('active')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ====== BOUTONS ====== --}}
                <div class="actions-bar d-flex justify-content-between align-items-center">
                    <a href="{{ route('utilisateur.profile', $user->code_user) }}" class="btn btn-secondary px-4">
                        <i class="fas fa-times me-1"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-save px-5">
                        <i class="fas fa-save me-2"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function () {
    $('.selectpicker').selectpicker();
});
</script>
@endsection
