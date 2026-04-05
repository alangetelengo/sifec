@extends("layout.app")
@section("titre")
    Profil — {{ $user->personne->nom.' '.$user->personne->prenom }}
@endsection

@section('styles')
<style>
    /* ── Palette ───────────────────────────────────────────────────────────
       Vert charte   : #009A44
       Vert sombre   : #1b4332  (hero)
       Vert moyen    : #2d6a4f  (card-header)
       Vert pâle     : #e8f5ee  (fond accentué)
       Gris ardoise  : #546e7a  (texte secondaire)
    ──────────────────────────────────────────────────────────────────────── */

    body { background-color: #f4f6f8; }

    /* Hero */
    .profile-hero {
        background: linear-gradient(135deg, #1b4332 0%, #2d6a4f 100%);
        border-radius: 0 0 20px 20px;
        padding: 2rem;
        color: #fff;
    }
    .profile-hero .avatar-wrap {
        width: 90px; height: 90px;
        border: 3px solid rgba(255,255,255,.5);
        border-radius: 50%;
        overflow: hidden;
        background: rgba(255,255,255,.15);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .profile-hero .avatar-wrap img { width: 100%; height: 100%; object-fit: cover; }
    .profile-hero .avatar-wrap .initials {
        font-size: 2rem; font-weight: 700; color: #fff;
        text-transform: uppercase;
    }
    .profile-hero .meta-line { font-size: .85rem; opacity: .8; margin-bottom: .25rem; }
    .profile-hero .badge-role {
        background: rgba(255,255,255,.2);
        border: 1px solid rgba(255,255,255,.3);
        color: #fff; font-size: .78rem;
        padding: 3px 10px; border-radius: 20px;
    }

    /* Stat cards */
    .stat-card {
        background: #fff;
        border-radius: 10px;
        border-left: 4px solid transparent;
        box-shadow: 0 1px 6px rgba(0,0,0,.07);
        padding: 1rem 1.25rem;
        display: flex; align-items: center; gap: 1rem;
    }
    .stat-card .stat-icon {
        width: 48px; height: 48px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem; flex-shrink: 0;
    }
    .stat-card .stat-value { font-size: 1.6rem; font-weight: 700; line-height: 1; }
    .stat-card .stat-label { font-size: .78rem; color: #6c757d; margin-top: 2px; }

    .stat-card.green  { border-left-color: #009A44; }
    .stat-card.blue   { border-left-color: #1976d2; }
    .stat-card.orange { border-left-color: #e65100; }
    .stat-card.purple { border-left-color: #6a1b9a; }

    .stat-card.green  .stat-icon { background: #e8f5ee; color: #009A44; }
    .stat-card.blue   .stat-icon { background: #e3f2fd; color: #1976d2; }
    .stat-card.orange .stat-icon { background: #fff3e0; color: #e65100; }
    .stat-card.purple .stat-icon { background: #f3e5f5; color: #6a1b9a; }

    .stat-card.green  .stat-value { color: #009A44; }
    .stat-card.blue   .stat-value { color: #1976d2; }
    .stat-card.orange .stat-value { color: #e65100; }
    .stat-card.purple .stat-value { color: #6a1b9a; }

    /* Section cards */
    .section-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 1px 6px rgba(0,0,0,.07);
        overflow: hidden;
    }
    .section-card .sc-header {
        background: #2d6a4f;
        color: #fff;
        padding: .65rem 1rem;
        font-size: .88rem; font-weight: 600;
    }
    .section-card .sc-header i { margin-right: .4rem; }

    /* Table inside cards */
    .info-table { width: 100%; border-collapse: collapse; }
    .info-table tr { border-bottom: 1px solid #f0f0f0; }
    .info-table tr:last-child { border-bottom: none; }
    .info-table td { padding: .55rem .85rem; font-size: .875rem; vertical-align: middle; }
    .info-table td.lbl { color: #546e7a; width: 42%; font-weight: 500; white-space: nowrap; }

    /* Tabs */
    .profile-tabs .nav-link {
        color: #546e7a; border: none; border-bottom: 2px solid transparent;
        padding: .6rem 1rem; font-size: .875rem; border-radius: 0;
        background: transparent;
    }
    .profile-tabs .nav-link:hover { color: #2d6a4f; border-bottom-color: #a8d5be; }
    .profile-tabs .nav-link.active { color: #009A44; border-bottom-color: #009A44; font-weight: 600; }
    .profile-tabs { border-bottom: 1px solid #e0e0e0; margin-bottom: 1.25rem; }

    /* Badges */
    .badge-status-on  { background: #e8f5ee; color: #1b4332; border: 1px solid #a8d5be; }
    .badge-status-off { background: #fce8e8; color: #7f1d1d; border: 1px solid #f5c6c6; }
    .badge-2fa-on     { background: #e8f5ee; color: #1b4332; border: 1px solid #a8d5be; }
    .badge-2fa-off    { background: #f3f4f6; color: #6b7280; border: 1px solid #d1d5db; }
    .badge-soft { padding: 4px 10px; border-radius: 20px; font-size: .78rem; font-weight: 600; }

    /* Action buttons in hero */
    .btn-hero-primary {
        background: rgba(255,255,255,.18);
        border: 1px solid rgba(255,255,255,.4);
        color: #fff; font-size: .8rem; border-radius: 6px;
        padding: 5px 14px; white-space: nowrap;
        transition: background .2s;
    }
    .btn-hero-primary:hover { background: rgba(255,255,255,.3); color: #fff; }

    /* Activity list */
    .activity-item {
        display: flex; align-items: flex-start; gap: .85rem;
        padding: .7rem 0; border-bottom: 1px solid #f0f0f0;
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-dot {
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: .85rem; flex-shrink: 0;
    }
    .activity-dot.green  { background: #e8f5ee; color: #009A44; }
    .activity-dot.blue   { background: #e3f2fd; color: #1976d2; }
    .activity-dot.teal   { background: #e0f7fa; color: #00838f; }

    /* Security action tiles */
    .sec-tile {
        background: #fff; border-radius: 10px;
        box-shadow: 0 1px 6px rgba(0,0,0,.07);
        padding: 1.5rem 1rem; text-align: center;
    }
    .sec-tile .sec-icon-wrap {
        width: 60px; height: 60px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; margin: 0 auto 1rem;
    }
</style>
@endsection

@section("corps")

{{-- Hero --}}
<div class="profile-hero mb-4">
    <div class="d-flex align-items-center gap-3 flex-wrap">
        {{-- Avatar --}}
        <div class="avatar-wrap">
            @if($user->personne && $user->personne->signature)
                <img src="{{ asset('app/'.$user->personne->signature) }}" alt="Avatar">
            @else
                <span class="initials">
                    {{ mb_substr($user->personne->nom, 0, 1) }}{{ mb_substr($user->personne->prenom, 0, 1) }}
                </span>
            @endif
        </div>

        {{-- Info --}}
        <div class="flex-grow-1">
            <h4 class="mb-1 text-white fw-bold">{{ $user->personne->nom.' '.$user->personne->prenom }}</h4>
            <span class="badge-role me-2">{{ $user->affectationActive()?->fonction?->lib_fonction ?? "Fonction non définie" }}</span>
            <div class="mt-2">
                <p class="meta-line mb-1"><i class="fas fa-envelope me-1"></i>{{ $user->email }}</p>
                <p class="meta-line mb-0"><i class="fas fa-building me-1"></i>{{ $user->affectationActive()?->institution?->lib_institution ?? "Non affecté" }}</p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="d-flex flex-column gap-2 flex-shrink-0">
            <a href="{{ route('utilisateur.edit', $user->code_user) }}" class="btn-hero-primary">
                <i class="fas fa-edit me-1"></i>Modifier
            </a>
            <a href="{{ route('utilisateur.assigner.permission', $user->code_user) }}" class="btn-hero-primary">
                <i class="fas fa-key me-1"></i>Permissions
            </a>
            <a href="{{ route('two-factor.index') }}" class="btn-hero-primary">
                <i class="fas fa-shield-alt me-1"></i>Sécurité
            </a>
        </div>
    </div>
</div>

<div class="container-fluid px-3">

    {{-- Stat cards --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card green">
                <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
                <div>
                    <div class="stat-value">{{ $user->created_at->diffInDays(now()) }}</div>
                    <div class="stat-label">Jours actifs</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card {{ $user->hasTwoFactorEnabled() ? 'green' : 'orange' }}">
                <div class="stat-icon"><i class="fas fa-shield-alt"></i></div>
                <div>
                    <div class="stat-value" style="font-size:1.1rem;">{{ $user->hasTwoFactorEnabled() ? 'Activée' : 'Désactivée' }}</div>
                    <div class="stat-label">Double Authentification</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card {{ $user->status ? 'green' : 'orange' }}">
                <div class="stat-icon"><i class="fas fa-user-check"></i></div>
                <div>
                    <div class="stat-value" style="font-size:1.1rem;">{{ $user->status ? 'Actif' : 'Inactif' }}</div>
                    <div class="stat-label">Statut du compte</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card purple">
                <div class="stat-icon"><i class="fas fa-calendar"></i></div>
                <div>
                    <div class="stat-value">{{ $user->created_at->format('Y') }}</div>
                    <div class="stat-label">Inscrit le {{ $user->created_at->format('d/m/Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Colonne gauche --}}
        <div class="col-xl-4">

            {{-- Infos personnelles --}}
            <div class="section-card mb-3">
                <div class="sc-header"><i class="fas fa-user-circle"></i>Informations Personnelles</div>
                <table class="info-table">
                    <tr>
                        <td class="lbl">Nom complet</td>
                        <td>{{ $user->personne->nom.' '.$user->personne->prenom }}</td>
                    </tr>
                    @if($user->personne->date_naissance)
                    <tr>
                        <td class="lbl">Date de naissance</td>
                        <td>{{ \Carbon\Carbon::parse($user->personne->date_naissance)->format('d/m/Y') }}</td>
                    </tr>
                    @endif
                    @if($user->personne->sexe)
                    <tr>
                        <td class="lbl">Sexe</td>
                        <td>{{ $user->personne->sexe == 'F' ? 'Féminin' : 'Masculin' }}</td>
                    </tr>
                    @endif
                    @if($user->personne->telephone)
                    <tr>
                        <td class="lbl">Téléphone</td>
                        <td>{{ $user->personne->telephone }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            {{-- Infos professionnelles --}}
            <div class="section-card mb-3">
                <div class="sc-header"><i class="fas fa-briefcase"></i>Informations Professionnelles</div>
                <table class="info-table">
                    <tr>
                        <td class="lbl">Institution</td>
                        <td>{{ $user->affectationActive()?->institution?->lib_institution ?? "Non affecté" }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Fonction</td>
                        <td>{{ $user->affectationActive()?->fonction?->lib_fonction ?? "Non définie" }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Type</td>
                        <td>{{ $user->affectationActive()?->institution?->typeInstitution?->lib_type_institution ?? "Non défini" }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Membre depuis</td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </div>

            {{-- Signature de l'agent --}}
            <div class="section-card">
                <div class="sc-header"><i class="fas fa-signature"></i>Signature de l'agent</div>
                <div class="p-3">

                    {{-- Aperçu de la signature actuelle --}}
                    <div class="text-center mb-3">
                        @if($user->personne && $user->personne->signature)
                            <div class="mb-2" style="background:#f8f9fa;border:1px dashed #ced4da;border-radius:8px;padding:12px;">
                                <img src="{{ asset('app/'.$user->personne->signature) }}"
                                     alt="Signature de {{ $user->personne->nom }}"
                                     id="sig-preview"
                                     style="max-height:100px;max-width:100%;object-fit:contain;">
                            </div>
                            <small class="text-muted"><i class="fas fa-check-circle text-success me-1"></i>Signature enregistrée</small>
                        @else
                            <div id="sig-placeholder" class="mb-2" style="background:#f8f9fa;border:2px dashed #ced4da;border-radius:8px;padding:24px;">
                                <i class="fas fa-signature fa-2x text-muted mb-2 d-block"></i>
                                <span class="text-muted small">Aucune signature enregistrée</span>
                            </div>
                            <img src="" alt="" id="sig-preview" class="d-none mb-2"
                                 style="max-height:100px;max-width:100%;object-fit:contain;border:1px dashed #ced4da;border-radius:8px;padding:8px;">
                        @endif
                    </div>

                    {{-- Formulaire d'upload --}}
                    <form action="{{ route('utilisateur.signature', $user->code_user) }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-2">
                            <label class="form-label small fw-semibold text-muted mb-1">
                                <i class="fas fa-upload me-1"></i>
                                {{ $user->personne?->signature ? 'Remplacer la signature' : 'Ajouter une signature' }}
                            </label>
                            <input type="file"
                                   class="form-control form-control-sm @error('signature') is-invalid @enderror"
                                   name="signature"
                                   id="sig-input"
                                   accept="image/png,image/jpeg">
                            <div class="form-text" style="font-size:.75rem;">
                                Formats acceptés : PNG, JPG — max 2 Mo
                            </div>
                            @error('signature')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-sm w-100 text-white"
                                style="background-color:#2d6a4f;">
                            <i class="fas fa-save me-1"></i>Enregistrer la signature
                        </button>
                    </form>
                </div>
            </div>

        </div>

        {{-- Colonne droite --}}
        <div class="col-xl-8">
            <div class="section-card">
                <div class="card-body p-3">

                    {{-- Onglets --}}
                    <ul class="nav profile-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#tab-overview" role="tab">
                                <i class="fas fa-info-circle me-1"></i>Aperçu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab-security" role="tab">
                                <i class="fas fa-shield-alt me-1"></i>Sécurité
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab-activity" role="tab">
                                <i class="fas fa-history me-1"></i>Activité
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">

                        {{-- Aperçu --}}
                        <div class="tab-pane fade show active" id="tab-overview" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="section-card">
                                        <div class="sc-header"><i class="fas fa-info-circle"></i>Informations Générales</div>
                                        <table class="info-table">
                                            <tr>
                                                <td class="lbl">Email</td>
                                                <td>{{ $user->email }}</td>
                                            </tr>
                                            <tr>
                                                <td class="lbl">Statut</td>
                                                <td>
                                                    <span class="badge-soft {{ $user->status ? 'badge-status-on' : 'badge-status-off' }}">
                                                        <i class="fas fa-circle me-1" style="font-size:.5rem;vertical-align:middle;"></i>
                                                        {{ $user->status ? 'Actif' : 'Inactif' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="lbl">2FA</td>
                                                <td>
                                                    <span class="badge-soft {{ $user->hasTwoFactorEnabled() ? 'badge-2fa-on' : 'badge-2fa-off' }}">
                                                        <i class="fas fa-{{ $user->hasTwoFactorEnabled() ? 'check' : 'times' }} me-1"></i>
                                                        {{ $user->hasTwoFactorEnabled() ? 'Activée' : 'Non activée' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="lbl">Créé le</td>
                                                <td>{{ $user->created_at->format('d/m/Y à H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="lbl">Dernière modif.</td>
                                                <td>{{ $user->updated_at->format('d/m/Y à H:i') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="section-card">
                                        <div class="sc-header"><i class="fas fa-briefcase"></i>Affectation Actuelle</div>
                                        <table class="info-table">
                                            <tr>
                                                <td class="lbl">Institution</td>
                                                <td>{{ $user->affectationActive()?->institution?->lib_institution ?? "Non affecté" }}</td>
                                            </tr>
                                            <tr>
                                                <td class="lbl">Fonction</td>
                                                <td>{{ $user->affectationActive()?->fonction?->lib_fonction ?? "Non définie" }}</td>
                                            </tr>
                                            <tr>
                                                <td class="lbl">Type</td>
                                                <td>{{ $user->affectationActive()?->institution?->typeInstitution?->lib_type_institution ?? "Non défini" }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Sécurité --}}
                        <div class="tab-pane fade" id="tab-security" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="sec-tile">
                                        <div class="sec-icon-wrap {{ $user->hasTwoFactorEnabled() ? '' : '' }}"
                                             style="background:{{ $user->hasTwoFactorEnabled() ? '#e8f5ee' : '#fff3e0' }};">
                                            <i class="fas fa-shield-alt"
                                               style="color:{{ $user->hasTwoFactorEnabled() ? '#009A44' : '#e65100' }};"></i>
                                        </div>
                                        <h6 class="fw-bold mb-1">Double Authentification</h6>
                                        <p class="text-muted small mb-3">
                                            Statut :
                                            <span class="badge-soft {{ $user->hasTwoFactorEnabled() ? 'badge-2fa-on' : 'badge-2fa-off' }}">
                                                {{ $user->hasTwoFactorEnabled() ? 'Activée' : 'Non activée' }}
                                            </span>
                                        </p>
                                        <a href="{{ route('two-factor.index') }}"
                                           class="btn btn-sm {{ $user->hasTwoFactorEnabled() ? 'btn-outline-success' : 'btn-outline-primary' }}">
                                            <i class="fas {{ $user->hasTwoFactorEnabled() ? 'fa-cog' : 'fa-shield-alt' }} me-1"></i>
                                            {{ $user->hasTwoFactorEnabled() ? 'Gérer la 2FA' : 'Activer la 2FA' }}
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="sec-tile">
                                        <div class="sec-icon-wrap" style="background:#fff8e1;">
                                            <i class="fas fa-lock" style="color:#f59e0b;"></i>
                                        </div>
                                        <h6 class="fw-bold mb-1">Mot de passe</h6>
                                        <p class="text-muted small mb-3">
                                            Dernière modification :<br>
                                            <strong>{{ $user->updated_at->format('d/m/Y') }}</strong>
                                        </p>
                                        <a href="{{ route('utilisateur.change-password', $user->code_user) }}"
                                           class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit me-1"></i>Modifier le mot de passe
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Activité --}}
                        <div class="tab-pane fade" id="tab-activity" role="tabpanel">
                            <div class="activity-item">
                                <div class="activity-dot green"><i class="fas fa-user-plus"></i></div>
                                <div>
                                    <div class="fw-semibold small">Compte créé</div>
                                    <div class="text-muted" style="font-size:.8rem;">{{ $user->created_at->format('d/m/Y à H:i') }}</div>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-dot blue"><i class="fas fa-sign-in-alt"></i></div>
                                <div>
                                    <div class="fw-semibold small">Dernière modification</div>
                                    <div class="text-muted" style="font-size:.8rem;">{{ $user->updated_at->format('d/m/Y à H:i') }}</div>
                                </div>
                            </div>
                            @if($user->hasTwoFactorEnabled())
                            <div class="activity-item">
                                <div class="activity-dot teal"><i class="fas fa-shield-alt"></i></div>
                                <div>
                                    <div class="fw-semibold small">2FA activée</div>
                                    <div class="text-muted" style="font-size:.8rem;">
                                        {{ $user->two_factor_verified_at ? $user->two_factor_verified_at->format('d/m/Y à H:i') : 'Récemment' }}
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
// Prévisualisation de la signature avant upload
document.getElementById('sig-input')?.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;

    const preview    = document.getElementById('sig-preview');
    const placeholder = document.getElementById('sig-placeholder');

    const reader = new FileReader();
    reader.onload = function (e) {
        preview.src = e.target.result;
        preview.classList.remove('d-none');
        if (placeholder) placeholder.classList.add('d-none');
    };
    reader.readAsDataURL(file);
});

function flashAlert(type, message) {
    const cls  = {success:'alert-success', error:'alert-danger', warning:'alert-warning', info:'alert-info'};
    const icon = {success:'fa-check-circle', error:'fa-exclamation-circle', warning:'fa-exclamation-triangle', info:'fa-info-circle'};
    const html = `<div class="alert ${cls[type]||'alert-info'} alert-dismissible fade show position-fixed"
                       style="top:20px;right:20px;z-index:9999;min-width:300px;">
                      <i class="fas ${icon[type]||'fa-info-circle'}"></i> ${message}
                      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                  </div>`;
    document.body.insertAdjacentHTML('beforeend', html);
    setTimeout(() => { const a = document.querySelector('.alert:last-of-type'); if(a) a.remove(); }, 3000);
}
</script>
@endsection
