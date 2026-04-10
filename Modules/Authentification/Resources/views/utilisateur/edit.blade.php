@extends('layout.app')
@section('titre')
    Modifier — {{ $user->personne->nom ?? '' }}
@endsection
@section('corps')
<div class="page-utilisateur-form-sifec">
<div class="row">
    <div class="col-12">
        <div class="card pu-card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h4 class="mb-0">
                    <i class="fas fa-user-edit me-2"></i>Modifier —
                    {{ $user->personne->nom ?? '' }} {{ $user->personne->prenom ?? '' }}
                </h4>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('utilisateur.profile', $user->code_user) }}" class="btn btn-sm pu-btn-profile">
                        <i class="fas fa-eye me-1"></i> Voir le profil
                    </a>
                    <a href="{{ route('utilisateur.index') }}" class="btn btn-sm pu-btn-back">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                </div>
            </div>
            <div class="card-body">

                <form method="POST" action="{{ route('utilisateur.update', $user->code_user) }}"
                      enctype="multipart/form-data" id="editUserForm">
                    @csrf
                    @method('PUT')

                    {{-- ── INFORMATIONS PERSONNELLES (verrouillées) ──────────────────────── --}}
                    <div class="ligne">
                        <h4>INFORMATIONS PERSONNELLES
                            <small class="text-muted ms-2" style="font-size:0.75rem;">
                                <i class="fas fa-lock"></i> Données verrouillées — registre civil
                            </small>
                        </h4>
                    </div>
                    <div class="row">
                        <div class="mb-2 col-md-4">
                            <label class="form-label">Nom(s)</label>
                            <input type="text" class="form-control bg-light"
                                   value="{{ $user->personne->nom ?? '' }}" name="nom" readonly>
                        </div>
                        <div class="mb-2 col-md-4">
                            <label class="form-label">Prénom(s)</label>
                            <input type="text" class="form-control bg-light"
                                   value="{{ $user->personne->prenom ?? '' }}" name="prenom" readonly>
                        </div>
                        <div class="mb-2 col-md-4">
                            <label class="form-label">Sexe</label>
                            <input type="text" class="form-control bg-light"
                                   value="{{ $user->personne->sexe == 'F' ? 'Féminin' : 'Masculin' }}" readonly>
                            <input type="hidden" name="sexe" value="{{ $user->personne->sexe ?? '' }}">
                        </div>
                        <div class="mb-2 col-md-4">
                            <label class="form-label">Date de naissance</label>
                            <input type="date" class="form-control bg-light"
                                   value="{{ $user->personne->date_naissance ?? '' }}" name="date_naissance" readonly>
                        </div>
                        <div class="mb-2 col-md-4">
                            <label class="form-label">Lieu de naissance</label>
                            <input type="text" class="form-control bg-light"
                                   value="{{ $user->personne->lieu_naissance ?? '' }}" name="lieu_naissance" readonly>
                        </div>
                        <div class="mb-2 col-md-4">
                            <label class="form-label">Nationalité <span class="text-danger">*</span></label>
                            <select name="code_nationalite" id="code_nationalite_personne"
                                    class="form-control @error('code_nationalite') is-invalid @enderror">
                                <option value="" disabled selected>Selectionner</option>
                                @foreach ($nationalites as $nat)
                                    <option value="{{ $nat->code_nationalite }}"
                                        {{ ($user->personne->code_nationalite ?? '') == $nat->code_nationalite ? 'selected' : '' }}>
                                        {{ $nat->lib_nationalite }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_nationalite')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Adresse / Domicile <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('adresse') is-invalid @enderror"
                                   name="adresse"
                                   value="{{ old('adresse', $user->personne->adresse ?? '') }}"
                                   placeholder="Adresse domicile">
                            @error('adresse')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Téléphone</label>
                            <input type="text" class="form-control @error('telephone') is-invalid @enderror"
                                   name="telephone"
                                   value="{{ old('telephone', $user->personne->telephone ?? '') }}"
                                   placeholder="Numéro de téléphone">
                            @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- ── NOUVEAU DOCUMENT D'IDENTITÉ (facultatif) ───────────────────── --}}
                    <div class="ligne"><h4>NOUVEAU DOCUMENT D'IDENTITÉ <small class="text-muted">(facultatif)</small></h4></div>
                    <div class="alert alert-warning py-2 mb-2">
                        <small><i class="fas fa-info-circle me-1"></i>
                            Remplir uniquement pour ajouter un nouveau document d'identité.
                        </small>
                    </div>
                    <div class="row">
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Type de pièce</label>
                            <select name="code_type_document"
                                    class="form-control @error('code_type_document') is-invalid @enderror">
                                <option value="" disabled selected>Selectionner</option>
                                @foreach ($typeDocuments as $item)
                                    <option value="{{ $item->code_type_document }}">
                                        {{ $item->lib_type_document }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_type_document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Numéro de la pièce</label>
                            <input type="text"
                                   class="form-control @error('numero_document') is-invalid @enderror"
                                   name="numero_document"
                                   value="{{ old('numero_document') }}"
                                   placeholder="Numéro de la pièce d'identité">
                            @error('numero_document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- ── AFFECTATION & COMPTE ─────────────────────────────────────────── --}}
                    <div class="ligne"><h4>AFFECTATION &amp; COMPTE</h4></div>
                    <div class="row">
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Fonction <span class="text-danger">*</span></label>
                            <select name="code_fonction"
                                    class="form-control @error('code_fonction') is-invalid @enderror">
                                <option value="" disabled selected>Selectionner</option>
                                @foreach ($fonctions as $fn)
                                    <option value="{{ $fn->code_fonction }}"
                                        {{ $user->affectationActive()?->fonction?->code_fonction == $fn->code_fonction ? 'selected' : '' }}>
                                        {{ $fn->lib_fonction }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_fonction')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Centre d'état civil <span class="text-danger">*</span></label>
                            <select name="code_institution"
                                    class="form-control @error('code_institution') is-invalid @enderror">
                                <option value="" disabled selected>Selectionner</option>
                                @foreach ($institutions as $ins)
                                    <option value="{{ $ins->code_institution }}"
                                        {{ $user->affectationActive()?->institution?->code_institution == $ins->code_institution ? 'selected' : '' }}>
                                        {{ $ins->lib_institution }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_institution')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Adresse email <span class="text-danger">*</span></label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email"
                                   value="{{ old('email', $user->email) }}"
                                   placeholder="exemple@domaine.com">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-2 col-md-3">
                            <label class="form-label">Niveau d'instruction</label>
                            <select name="niveau_instruction" class="form-control">
                                <option value="" disabled selected>Selectionner</option>
                                @foreach ($niveauInstructions as $ni)
                                    <option value="{{ $ni }}"
                                        {{ old('niveau_instruction', $user->personne->niveau_instruction ?? '') == $ni ? 'selected' : '' }}>
                                        {{ $ni }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2 col-md-3">
                            <label class="form-label">Statut du compte <span class="text-danger">*</span></label>
                            <select name="active" class="form-control @error('active') is-invalid @enderror">
                                <option value="1" {{ $user->status == '1' ? 'selected' : '' }}>Actif</option>
                                <option value="0" {{ $user->status == '0' ? 'selected' : '' }}>Inactif</option>
                            </select>
                            @error('active')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- ── BOUTONS ──────────────────────────────────────────────────────── --}}
                    <div class="pu-form-actions">
                        <a href="{{ route('utilisateur.profile', $user->code_user) }}" class="btn btn-sm pu-btn-cancel">
                            <i class="fas fa-times me-1"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-sm pu-btn-submit" id="editUserSubmitBtn">
                            <i class="fas fa-save me-1"></i> Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('editUserForm');
    var btn = document.getElementById('editUserSubmitBtn');
    if (!form || !btn) return;
    form.addEventListener('submit', function () {
        if (btn.getAttribute('data-sifec-submitting') === '1') return;
        btn.setAttribute('data-sifec-submitting', '1');
        if (!btn.getAttribute('data-sifec-html')) {
            btn.setAttribute('data-sifec-html', btn.innerHTML);
        }
        btn.disabled = true;
        btn.setAttribute('aria-busy', 'true');
        btn.classList.add('sifec-btn-loading');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1" aria-hidden="true"></i>Enregistrement en cours…';
    });
});
</script>
@endsection
