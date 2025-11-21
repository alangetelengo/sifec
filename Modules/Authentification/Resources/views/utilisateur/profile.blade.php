@extends("layout.app")
@section("titre")
    Profil - {{ $user->personne->nom.' '.$user->personne->prenom }}
@endsection

@section('styles')
<style>
    .breadcrumb-item a {
        color: #007bff !important;
        text-decoration: none;
    }
    .breadcrumb-item a:hover {
        color: #0056b3 !important;
        text-decoration: underline;
    }
    .breadcrumb-item.active {
        color: #6c757d;
    }

    .profile-header-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .profile-avatar {
        width: 140px;
        height: 140px;
        border: 5px solid white;
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        transition: transform 0.3s ease;
    }

    .profile-avatar:hover {
        transform: scale(1.05);
    }

    .profile-avatar-icon {
        width: 140px;
        height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 5px solid white;
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        transition: transform 0.3s ease;
    }

    .profile-avatar-icon:hover {
        transform: scale(1.05);
    }

    .stats-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
        border-left-color: #007bff;
    }


    .nav-tabs .nav-link {
        border-radius: 8px 8px 0 0;
        margin-right: 5px;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link:hover {
        background-color: #f8f9fa;
    }

    .nav-tabs .nav-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: transparent;
    }

    .timeline-item {
        position: relative;
        padding-left: 40px;
        padding-bottom: 30px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }

    .timeline-item:last-child::before {
        display: none;
    }

    .timeline-marker {
        position: absolute;
        left: 0;
        top: 5px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .timeline-content {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 3px solid #007bff;
    }

    .badge-lg {
        padding: 8px 16px;
        font-size: 0.95rem;
        font-weight: 600;
    }

    .action-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .action-card:hover {
        transform: translateY(-5px);
        border-color: #007bff;
        box-shadow: 0 8px 20px rgba(0,123,255,0.15);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .profile-info-section {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
</style>
@endsection

@section("corps")
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3 mt-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"><i class="fas fa-home"></i> Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('utilisateur.index') }}"><i class="fas fa-users"></i> Utilisateurs</a></li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-user"></i> Profil de {{ $user->personne->nom.' '.$user->personne->prenom }}</li>
        </ol>
    </nav>

    <!-- En-tête du profil amélioré -->
    <div class="row mb-4">
        <div class="col-xl-12">
            <div class="card profile-header-card border-0">
                <div class="card-body text-white p-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="profile-photo">
                                @if($user->personne && $user->personne->signature)
                                    @php
                                        $signaturePath = $user->personne->signature;
                                        if (strpos($signaturePath, 'signature/') === 0) {
                                            $signaturePath = str_replace('signature/', 'signatures/', $signaturePath);
                                        }
                                        if (strpos($signaturePath, 'signatures/') === 0 || strpos($signaturePath, 'storage/') === 0) {
                                            $imageUrl = asset($signaturePath);
                                        } else {
                                            $imageUrl = asset('storage/'.$signaturePath);
                                        }
                                    @endphp
                                    <img src="{{ $imageUrl }}" alt="Photo de profil" class="profile-avatar rounded-circle img-fluid" style="object-fit: cover;">
                                @else
                                    @if($user->personne && $user->personne->sexe == 'F')
                                        <div class="profile-avatar-icon rounded-circle" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                            <i class="fas fa-female fa-4x text-white"></i>
                                        </div>
                                    @else
                                        <div class="profile-avatar-icon rounded-circle bg-primary">
                                            <i class="fas fa-male fa-4x text-white"></i>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="col">
                            <h2 class="mb-2 text-white"><strong>{{ $user->personne->nom.' '.$user->personne->prenom }}</strong></h2>
                            <p class="mb-2 text-white-50"><i class="fas fa-briefcase me-2"></i>{{ $user->affectationActive()?->fonction?->lib_fonction ?? "Fonction non définie" }}</p>
                            <p class="mb-2 text-white-50"><i class="fas fa-envelope me-2"></i>{{ $user->email }}</p>
                            <p class="mb-0 text-white-50"><i class="fas fa-building me-2"></i>{{ $user->affectationActive()?->institution?->lib_institution ?? "Non affecté" }}</p>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex flex-column gap-2">
                                <a href="{{ route('utilisateur.edit', $user->code_user) }}" class="btn btn-primary btn-sm shadow-sm">
                                    <i class="fas fa-edit me-2"></i>Modifier
                                </a>
                                <a href="{{ route('utilisateur.assigner.permission', $user->code_user) }}" class="btn btn-info btn-sm shadow-sm">
                                    <i class="fas fa-key me-2"></i>Permissions
                                </a>
                                <a href="{{ route('two-factor.index') }}" class="btn btn-success btn-sm shadow-sm">
                                    <i class="fas fa-shield-alt me-2"></i>Sécurité
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="stat-number">{{ $user->created_at->diffInDays(now()) }}</div>
                    <h6 class="text-muted mb-0"><i class="fas fa-calendar-day me-2"></i>Jours actifs</h6>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="stat-number">{{ $user->hasTwoFactorEnabled() ? '1' : '0' }}</div>
                    <h6 class="text-muted mb-0">
                        <i class="fas fa-shield-alt me-2 {{ $user->hasTwoFactorEnabled() ? 'text-success' : 'text-danger' }}"></i>
                        2FA {{ $user->hasTwoFactorEnabled() ? 'Activée' : 'Désactivée' }}
                    </h6>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <span class="badge {{ $user->status ? 'bg-success' : 'bg-danger' }} badge-lg">
                        {{ $user->status ? 'Actif' : 'Inactif' }}
                    </span>
                    <h6 class="text-muted mb-0 mt-2"><i class="fas fa-user-check me-2"></i>Statut du compte</h6>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="stat-number">{{ $user->created_at->format('Y') }}</div>
                    <h6 class="text-muted mb-0"><i class="fas fa-calendar me-2"></i>Année d'inscription</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Colonne de gauche - Informations personnelles -->
        <div class="col-xl-4">
            <!-- Informations personnelles et professionnelles -->
            <div class="card profile-info-section mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-user-circle me-2"></i>Informations Personnelles</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <tbody>
                            <tr>
                                <td width="40%" class="bg-light"><strong><i class="fas fa-user text-primary me-2"></i>Nom complet</strong></td>
                                <td>{{ $user->personne->nom.' '.$user->personne->prenom }}</td>
                            </tr>
                            @if($user->personne->date_naissance)
                            <tr>
                                <td class="bg-light"><strong><i class="fas fa-birthday-cake text-primary me-2"></i>Date de naissance</strong></td>
                                <td>{{ \Carbon\Carbon::parse($user->personne->date_naissance)->format('d/m/Y') }}</td>
                            </tr>
                            @endif
                            @if($user->personne->sexe)
                            <tr>
                                <td class="bg-light"><strong><i class="fas fa-{{ $user->personne->sexe == 'F' ? 'female' : 'male' }} text-primary me-2"></i>Sexe</strong></td>
                                <td>{{ $user->personne->sexe == 'F' ? 'Féminin' : 'Masculin' }}</td>
                            </tr>
                            @endif
                            @if($user->personne->telephone)
                            <tr>
                                <td class="bg-light"><strong><i class="fas fa-phone text-primary me-2"></i>Téléphone</strong></td>
                                <td>{{ $user->personne->telephone }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Informations professionnelles -->
            <div class="card profile-info-section">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-briefcase me-2"></i>Informations Professionnelles</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <tbody>
                            <tr>
                                <td width="40%" class="bg-light"><strong><i class="fas fa-building text-success me-2"></i>Institution</strong></td>
                                <td>{{ $user->affectationActive()?->institution?->lib_institution ?? "Non affecté" }}</td>
                            </tr>
                            <tr>
                                <td class="bg-light"><strong><i class="fas fa-briefcase text-success me-2"></i>Fonction</strong></td>
                                <td>{{ $user->affectationActive()?->fonction?->lib_fonction ?? "Non définie" }}</td>
                            </tr>
                            <tr>
                                <td class="bg-light"><strong><i class="fas fa-tag text-success me-2"></i>Type d'institution</strong></td>
                                <td>{{ $user->affectationActive()?->institution?->typeInstitution?->lib_type_institution ?? "Non défini" }}</td>
                            </tr>
                            <tr>
                                <td class="bg-light"><strong><i class="fas fa-calendar text-success me-2"></i>Membre depuis</strong></td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Colonne de droite - Onglets -->
        <div class="col-xl-8">
            <div class="card profile-info-section">
                <div class="card-header bg-white border-bottom">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#profile-overview" role="tab">
                                <i class="fas fa-info-circle me-2"></i>Aperçu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#profile-security" role="tab">
                                <i class="fas fa-shield-alt me-2"></i>Sécurité
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#profile-activity" role="tab">
                                <i class="fas fa-history me-2"></i>Activité
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Onglet Aperçu -->
                        <div class="tab-pane fade show active" id="profile-overview" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Informations Générales</h5>
                                        </div>
                                        <div class="card-body p-0">
                                            <table class="table table-hover mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td width="40%" class="bg-light"><strong><i class="fas fa-envelope text-primary me-2"></i>Email</strong></td>
                                                        <td>{{ $user->email }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bg-light"><strong><i class="fas fa-user-check text-primary me-2"></i>Statut</strong></td>
                                                        <td>
                                                            <span class="badge {{ $user->status ? 'bg-success' : 'bg-danger' }} badge-lg">
                                                                {{ $user->status ? 'Actif' : 'Inactif' }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bg-light"><strong><i class="fas fa-shield-alt text-primary me-2"></i>2FA</strong></td>
                                                        <td>
                                                            <span class="badge {{ $user->hasTwoFactorEnabled() ? 'bg-success' : 'bg-danger' }} badge-lg">
                                                                {{ $user->hasTwoFactorEnabled() ? 'Activée' : 'Désactivée' }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bg-light"><strong><i class="fas fa-calendar-plus text-primary me-2"></i>Créé le</strong></td>
                                                        <td>{{ $user->created_at->format('d/m/Y à H:i') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bg-light"><strong><i class="fas fa-clock text-primary me-2"></i>Dernière connexion</strong></td>
                                                        <td>{{ $user->updated_at->format('d/m/Y à H:i') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-success text-white">
                                            <h5 class="card-title mb-0"><i class="fas fa-briefcase me-2"></i>Affectation Actuelle</h5>
                                        </div>
                                        <div class="card-body p-0">
                                            <table class="table table-hover mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td width="40%" class="bg-light"><strong><i class="fas fa-building text-success me-2"></i>Institution</strong></td>
                                                        <td>{{ $user->affectationActive()?->institution?->lib_institution ?? "Non affecté" }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bg-light"><strong><i class="fas fa-briefcase text-success me-2"></i>Fonction</strong></td>
                                                        <td>{{ $user->affectationActive()?->fonction?->lib_fonction ?? "Non définie" }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bg-light"><strong><i class="fas fa-tag text-success me-2"></i>Type</strong></td>
                                                        <td>{{ $user->affectationActive()?->institution?->typeInstitution?->lib_type_institution ?? "Non défini" }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Onglet Sécurité -->
                        <div class="tab-pane fade" id="profile-security" role="tabpanel">
                            <h5 class="text-primary mb-4"><i class="fas fa-shield-alt me-2"></i>Paramètres de Sécurité</h5>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card action-card border-0 shadow-sm h-100">
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                <i class="fas fa-shield-alt fa-4x {{ $user->hasTwoFactorEnabled() ? 'text-success' : 'text-danger' }}"></i>
                                            </div>
                                            <h5>Double Authentification</h5>
                                            <p class="text-muted mb-3">Statut actuel :
                                                <span class="badge {{ $user->hasTwoFactorEnabled() ? 'bg-success' : 'bg-danger' }} badge-lg">
                                                    {{ $user->hasTwoFactorEnabled() ? 'Activée' : 'Désactivée' }}
                                                </span>
                                            </p>
                                            <a href="{{ route('two-factor.index') }}" class="btn {{ $user->hasTwoFactorEnabled() ? 'btn-info' : 'btn-primary' }}">
                                                <i class="fas {{ $user->hasTwoFactorEnabled() ? 'fa-cog' : 'fa-shield-alt' }} me-2"></i>
                                                {{ $user->hasTwoFactorEnabled() ? 'Gérer la 2FA' : 'Activer la 2FA' }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="card action-card border-0 shadow-sm h-100">
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                <i class="fas fa-key fa-4x text-warning"></i>
                                            </div>
                                            <h5>Mot de passe</h5>
                                            <p class="text-muted mb-3">Dernière modification :<br>{{ $user->updated_at->format('d/m/Y') }}</p>
                                            <a href="{{ route('utilisateur.change-password', $user->code_user) }}" class="btn btn-warning">
                                                <i class="fas fa-edit me-2"></i>Modifier le mot de passe
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Onglet Activité -->
                        <div class="tab-pane fade" id="profile-activity" role="tabpanel">
                            <h5 class="text-primary mb-4"><i class="fas fa-history me-2"></i>Activité Récente</h5>
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6><i class="fas fa-user-plus text-primary me-2"></i>Compte créé</h6>
                                        <p class="text-muted mb-0">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6><i class="fas fa-sign-in-alt text-success me-2"></i>Dernière connexion</h6>
                                        <p class="text-muted mb-0">{{ $user->updated_at->format('d/m/Y à H:i') }}</p>
                                    </div>
                                </div>
                                @if($user->hasTwoFactorEnabled())
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <h6><i class="fas fa-shield-alt text-info me-2"></i>2FA activée</h6>
                                        <p class="text-muted mb-0">Double authentification activée le {{ $user->two_factor_verified_at ? $user->two_factor_verified_at->format('d/m/Y à H:i') : 'Récemment' }}</p>
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
</div>
@endsection

@section('scripts')
<script>
// Flash alert function
function flashAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' :
                      type === 'error' ? 'alert-danger' :
                      type === 'warning' ? 'alert-warning' : 'alert-info';
    const icon = type === 'success' ? 'fa-check-circle' :
                 type === 'error' ? 'fa-exclamation-circle' :
                 type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';

    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed"
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="fas ${icon}"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', alertHtml);

    setTimeout(() => {
        const alert = document.querySelector('.alert:last-of-type');
        if (alert) {
            alert.remove();
        }
    }, 3000);
}
</script>
@endsection
