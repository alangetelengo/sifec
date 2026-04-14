@extends('layout.app')
@section('titre')
    Modifier — {{ $user->personne->nom ?? '' }}
@endsection
@section('corps')
<div class="page-utilisateur-form-sifec pu-edit-user-page">
<div class="row">
    <div class="col-12">
        <div class="card pu-card mb-3">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h4 class="mb-0">
                    <i class="fas fa-user-edit me-2"></i>Modifier —
                    {{ $user->personne->nom ?? '' }} {{ $user->personne->prenom ?? '' }}
                </h4>
                <div class="d-flex gap-2 flex-wrap align-items-center">
                    <a href="{{ route('utilisateur.profile', $user->code_user) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-eye me-1"></i> Profil
                    </a>
                    <a href="{{ route('utilisateur.profile.mise-a-jour', $user->code_user) }}" class="btn btn-sm btn-primary pu-btn-mise-a-jour">
                        <i class="fas fa-edit me-1"></i> Mise à jour des données
                    </a>
                    <a href="{{ route('utilisateur.index') }}" class="btn btn-sm pu-btn-back">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                </div>
            </div>
            <div class="card-body pb-2">
                <div class="ligne">
                    <h4 class="mb-3">INFORMATIONS D’ÉTAT CIVIL
                        <small class="text-muted ms-2" style="font-size:0.75rem;">
                            <i class="fas fa-lock"></i> Données verrouillées — registre civil
                        </small>
                    </h4>
                </div>
                <div class="row g-3 mb-2">
                    <div class="col-md-4">
                        <label class="form-label">Nom(s)</label>
                        <input type="text" class="form-control bg-light" value="{{ $user->personne->nom ?? '' }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Prénom(s)</label>
                        <input type="text" class="form-control bg-light" value="{{ $user->personne->prenom ?? '' }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sexe</label>
                        <input type="text" class="form-control bg-light"
                               value="{{ ($user->personne->sexe ?? '') == 'F' ? 'Féminin' : 'Masculin' }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date de naissance</label>
                        <input type="date" class="form-control bg-light" value="{{ $user->personne->date_naissance ?? '' }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Lieu de naissance</label>
                        <input type="text" class="form-control bg-light" value="{{ $user->personne->lieu_naissance ?? '' }}" readonly>
                    </div>
                </div>

                {{-- Même carte : document d’identité (formulaire séparé, même écran) --}}
                <div class="pu-edit-inline-doc">
                    <div class="ligne mb-3">
                        <h4 class="mb-0">
                            <i class="fas fa-id-card text-warning me-2"></i>NOUVEAU DOCUMENT D’IDENTITÉ
                            <small class="text-muted ms-2" style="font-size:0.75rem;">(formulaire indépendant)</small>
                        </h4>
                    </div>
                    <p class="text-muted small mb-3">Seul l’ajout d’une pièce est enregistré ici — sans modifier l’affectation ni le compte.</p>
                    <form method="POST" action="{{ route('utilisateur.update-document', $user->code_user) }}"
                          class="js-sifec-submit-once" id="formEditDocument">
                        @csrf
                        @method('PUT')
                        <div class="alert alert-warning py-2 mb-3">
                            <small><i class="fas fa-info-circle me-1"></i>
                                Remplir ce formulaire pour <strong>ajouter un nouveau document d’identité</strong> pour cet utilisateur.
                            </small>
                        </div>
                        <div class="row g-3 align-items-end">
                            <div class="col-12 col-lg-5">
                                <label class="form-label">Type de pièce <span class="text-danger">*</span></label>
                                <select name="code_type_document" required
                                        class="form-control @error('code_type_document') is-invalid @enderror">
                                    <option value="" disabled {{ old('code_type_document') ? '' : 'selected' }}>Sélectionner</option>
                                    @foreach ($typeDocuments as $item)
                                        <option value="{{ $item->code_type_document }}"
                                            {{ old('code_type_document') == $item->code_type_document ? 'selected' : '' }}>
                                            {{ $item->lib_type_document }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('code_type_document')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 col-lg-5">
                                <label class="form-label">Numéro de la pièce <span class="text-danger">*</span></label>
                                <input type="text" required
                                       class="form-control @error('numero_document') is-invalid @enderror"
                                       name="numero_document"
                                       value="{{ old('numero_document') }}"
                                       placeholder="Numéro du document">
                                @error('numero_document')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 col-lg-2 d-grid">
                                <button type="submit" class="btn btn-warning btn-submit-sifec">
                                    <i class="fas fa-save me-1"></i> Enregistrer le document
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Formulaire 2 : affectation --}}
        <div class="card pu-card pu-section-card pu-section-card--aff mb-3">
            <div class="card-header">
                <h5 class="pu-section-card__title">
                    <i class="fas fa-briefcase me-1" style="color:#0d6e8c;"></i> Affectation
                </h5>
                <p class="pu-section-card__hint">Fonction et centre d’état civil liés à l’affectation <strong>active</strong>.</p>
            </div>
            <div class="card-body pt-3">
                @if($user->affectationActive())
                <form method="POST" action="{{ route('utilisateur.update-affectation', $user->code_user) }}"
                      class="js-sifec-submit-once" id="formEditAffectation">
                    @csrf
                    @method('PUT')
                    @php
                        $selFonction = old('code_fonction', $user->affectationActive()?->fonction?->code_fonction);
                        $selInstitution = old('code_institution', $user->affectationActive()?->institution?->code_institution);
                    @endphp
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-lg-5">
                            <label class="form-label">Fonction <span class="text-danger">*</span></label>
                            <select name="code_fonction" required
                                    class="form-control @error('code_fonction') is-invalid @enderror">
                                <option value="" disabled {{ $selFonction ? '' : 'selected' }}>Sélectionner</option>
                                @foreach ($fonctions as $fn)
                                    <option value="{{ $fn->code_fonction }}"
                                        {{ $selFonction == $fn->code_fonction ? 'selected' : '' }}>
                                        {{ $fn->lib_fonction }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_fonction')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-lg-5">
                            <label class="form-label">Centre d’état civil <span class="text-danger">*</span></label>
                            <select name="code_institution" required
                                    class="form-control @error('code_institution') is-invalid @enderror">
                                <option value="" disabled {{ $selInstitution ? '' : 'selected' }}>Sélectionner</option>
                                @foreach ($institutions as $ins)
                                    <option value="{{ $ins->code_institution }}"
                                        {{ $selInstitution == $ins->code_institution ? 'selected' : '' }}>
                                        {{ $ins->lib_institution }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_institution')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-lg-2 d-grid">
                            <button type="submit" class="btn btn-primary btn-submit-sifec">
                                <i class="fas fa-save me-1"></i> Enregistrer l’affectation
                            </button>
                        </div>
                    </div>
                </form>
                @else
                <div class="alert alert-danger mb-0">
                    <i class="fas fa-exclamation-triangle me-1"></i> Aucune affectation active : la modification du poste n’est pas possible depuis cette page.
                </div>
                @endif
            </div>
        </div>

        {{-- Formulaire 3 : compte & coordonnées --}}
        <div class="card pu-card pu-section-card pu-section-card--compte mb-3">
            <div class="card-header">
                <h5 class="pu-section-card__title">
                    <i class="fas fa-user-cog text-success me-1"></i> Compte &amp; coordonnées
                </h5>
                <p class="pu-section-card__hint">E-mails, statut du compte, nationalité, adresse, téléphone et niveau d’instruction.</p>
            </div>
            <div class="card-body pt-3">
                <form method="POST" action="{{ route('utilisateur.update-compte', $user->code_user) }}"
                      class="js-sifec-submit-once" id="formEditCompte">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Nationalité <span class="text-danger">*</span></label>
                            <select name="code_nationalite" required
                                    class="form-control @error('code_nationalite') is-invalid @enderror">
                                <option value="" disabled>Sélectionner</option>
                                @foreach ($nationalites as $nat)
                                    <option value="{{ $nat->code_nationalite }}"
                                        {{ old('code_nationalite', $user->personne->code_nationalite ?? '') == $nat->code_nationalite ? 'selected' : '' }}>
                                        {{ $nat->lib_nationalite }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_nationalite')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Adresse / domicile <span class="text-danger">*</span></label>
                            <input type="text" name="adresse" required
                                   class="form-control @error('adresse') is-invalid @enderror"
                                   value="{{ old('adresse', $user->personne->adresse ?? '') }}"
                                   placeholder="Adresse">
                            @error('adresse')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="telephone"
                                   class="form-control @error('telephone') is-invalid @enderror"
                                   value="{{ old('telephone', $user->personne->telephone ?? '') }}"
                                   placeholder="Numéro">
                            @error('telephone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Adresse e-mail <span class="text-danger">*</span></label>
                            <input type="email" name="email" required autocomplete="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}"
                                   placeholder="exemple@domaine.com">
                            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="email_professionnel">E-mail professionnel</label>
                            <input type="email" id="email_professionnel" name="email_professionnel" autocomplete="email"
                                   class="form-control @error('email_professionnel') is-invalid @enderror"
                                   value="{{ old('email_professionnel', $user->email_professionnel) }}"
                                   placeholder="agent@ministere.gouv.cg">
                            <small class="text-muted d-block mt-1">Si renseigné, les messages d’activation 2FA sont envoyés à cette adresse.</small>
                            @error('email_professionnel')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Niveau d’instruction</label>
                            <select name="niveau_instruction" class="form-control">
                                <option value="">—</option>
                                @foreach ($niveauInstructions as $ni)
                                    <option value="{{ $ni }}"
                                        {{ old('niveau_instruction', $user->personne->niveau_instruction ?? '') == $ni ? 'selected' : '' }}>
                                        {{ $ni }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Statut du compte <span class="text-danger">*</span></label>
                            <select name="active" required class="form-control @error('active') is-invalid @enderror">
                                <option value="1" {{ old('active', $user->status ? '1' : '0') == '1' ? 'selected' : '' }}>Actif</option>
                                <option value="0" {{ old('active', $user->status ? '1' : '0') == '0' ? 'selected' : '' }}>Inactif</option>
                            </select>
                            @error('active')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="pu-form-actions mt-3 pt-3 border-top">
                        <a href="{{ route('utilisateur.profile', $user->code_user) }}" class="btn btn-sm pu-btn-cancel">
                            <i class="fas fa-times me-1"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-sm pu-btn-submit btn-submit-sifec">
                            <i class="fas fa-save me-1"></i> Enregistrer compte &amp; coordonnées
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
    document.querySelectorAll('form.js-sifec-submit-once').forEach(function (form) {
        form.addEventListener('submit', function () {
            var btn = form.querySelector('button[type="submit"].btn-submit-sifec');
            if (!btn || btn.getAttribute('data-sifec-submitting') === '1') return;
            btn.setAttribute('data-sifec-submitting', '1');
            if (!btn.getAttribute('data-sifec-html')) {
                btn.setAttribute('data-sifec-html', btn.innerHTML);
            }
            btn.disabled = true;
            btn.setAttribute('aria-busy', 'true');
            btn.classList.add('sifec-btn-loading');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1" aria-hidden="true"></i>Enregistrement…';
        });
    });
});
</script>
@endsection
