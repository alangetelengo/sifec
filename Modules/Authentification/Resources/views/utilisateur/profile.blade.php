@extends("layout.app")
@section("titre")
    Profil - {{ $user->personne->nom.' '.$user->personne->prenom }}
@endsection

@section('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px 15px 0 0;
    }
    .profile-avatar {
        width: 120px;
        height: 120px;
        border: 5px solid white;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .stats-card {
        transition: transform 0.3s ease;
    }
    .stats-card:hover {
        transform: translateY(-5px);
    }
    .nav-tabs .nav-link {
        border-radius: 10px 10px 0 0;
        margin-right: 5px;
    }
    .nav-tabs .nav-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: transparent;
    }
    .info-item {
        padding: 15px;
        border-left: 4px solid #667eea;
        background: #f8f9fa;
        margin-bottom: 10px;
        border-radius: 0 10px 10px 0;
    }
    .security-badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
    }
    .cover-photo {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        height: 200px;
        border-radius: 15px 15px 0 0;
    }

    .profile-details {
        padding: 20px;
    }
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
</style>
@endsection

@section("corps")
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"><i class="fas fa-home"></i> Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('utilisateur.index') }}"><i class="fas fa-users"></i> Utilisateurs</a></li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-user"></i> Profil</li>
        </ol>
    </nav>

    <!-- En-tête du profil style template -->
    <div class="row">
        <div class="col-lg-12">
            <div class="profile card card-body px-3 pt-3 pb-0">
                <div class="profile-head">

                    <div class="profile-info">
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
                            @else
                                @if($user->personne && $user->personne->sexe == 'F')
                                    <div class="profile-avatar rounded-circle d-flex align-items-center justify-content-center text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                        <i class="fas fa-female fa-3x"></i>
                                    </div>
                                @else
                                    <div class="profile-avatar rounded-circle bg-primary d-flex align-items-center justify-content-center text-white">
                                        <i class="fas fa-male fa-3x"></i>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <div class="profile-details">
                            <div class="profile-name px-3 pt-2">
                                <h4 class="text-primary mb-0">{{ $user->personne->nom.' '.$user->personne->prenom }}</h4>
                                <p>{{ $user->affectationActive()?->fonction?->lib_fonction ?? "Fonction non définie" }}</p>
                            </div>
                            <div class="profile-email px-2 pt-2">
                                <h4 class="text-muted mb-0">{{ $user->email }}</h4>
                                <p>Email</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Colonne de gauche - Statistiques et informations -->
        <div class="col-xl-4">
            <div class="row">
                <!-- Statistiques -->
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="profile-statistics">
                                <div class="text-center">
                                    <div class="row">
                                        <div class="col">
                                            <h3 class="m-b-0">{{ $user->created_at->diffInDays(now()) }}</h3>
                                            <span>Jours actifs</span>
                                        </div>
                                        <div class="col">
                                            <h3 class="m-b-0">{{ $user->hasTwoFactorEnabled() ? '1' : '0' }}</h3>
                                            <span>2FA Activée</span>
                                        </div>
                                        <div class="col">
                                            <h3 class="m-b-0">{{ $user->status ? '1' : '0' }}</h3>
                                            <span>Statut</span>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <a href="{{ route('utilisateur.edit', $user->code_user) }}" class="btn btn-primary mb-1 me-1">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <a href="{{ route('utilisateur.assigner.permission', $user->code_user) }}" class="btn btn-info mb-1">
                                            <i class="fas fa-key"></i> Permissions
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations personnelles et professionnelles -->
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="profile-blog">
                                <h5 class="text-primary d-inline">Informations Personnelles & Professionnelles</h5>
                                <div class="mt-4">
                                    <div class="info-item">
                                        <strong><i class="fas fa-user text-primary me-2"></i>Nom complet :</strong><br>
                                        {{ $user->personne->nom.' '.$user->personne->prenom }}
                                    </div>
                                    @if($user->personne->telephone)
                                    <div class="info-item">
                                        <strong><i class="fas fa-phone text-primary me-2"></i>Téléphone :</strong><br>
                                        {{ $user->personne->telephone }}
                                    </div>
                                    @endif
                                    @if($user->personne->date_naissance)
                                    <div class="info-item">
                                        <strong><i class="fas fa-birthday-cake text-primary me-2"></i>Date de naissance :</strong><br>
                                        {{ \Carbon\Carbon::parse($user->personne->date_naissance)->format('d/m/Y') }}
                                    </div>
                                    @endif
                                    @if($user->personne->sexe)
                                    <div class="info-item">
                                        <strong><i class="fas fa-{{ $user->personne->sexe == 'F' ? 'female' : 'male' }} text-primary me-2"></i>Sexe :</strong><br>
                                        {{ $user->personne->sexe == 'F' ? 'Féminin' : 'Masculin' }}
                                    </div>
                                    @endif
                                    <div class="info-item">
                                        <strong><i class="fas fa-building text-primary me-2"></i>Institution :</strong><br>
                                        {{ $user->affectationActive()?->institution?->lib_institution ?? "Non affecté" }}
                                    </div>
                                    <div class="info-item">
                                        <strong><i class="fas fa-briefcase text-primary me-2"></i>Fonction :</strong><br>
                                        {{ $user->affectationActive()?->fonction?->lib_fonction ?? "Non définie" }}
                                    </div>
                                    <div class="info-item">
                                        <strong><i class="fas fa-calendar text-primary me-2"></i>Membre depuis :</strong><br>
                                        {{ $user->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne de droite - Onglets -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="profile-tab">
                        <div class="custom-tab-1">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a href="#profile-overview" data-bs-toggle="tab" class="nav-link active show">
                                        <i class="fas fa-user me-2"></i>Aperçu
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#profile-security" data-bs-toggle="tab" class="nav-link">
                                        <i class="fas fa-shield-alt me-2"></i>Sécurité
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#profile-activity" data-bs-toggle="tab" class="nav-link">
                                        <i class="fas fa-history me-2"></i>Activité
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <!-- Onglet Aperçu -->
                                <div id="profile-overview" class="tab-pane fade active show">
                                    <div class="my-post-content pt-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5 class="text-primary mb-3"><i class="fas fa-info-circle me-2"></i>Informations Générales</h5>
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <td><strong>Email :</strong></td>
                                                        <td>{{ $user->email }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Statut du compte :</strong></td>
                                                        <td>
                                                            <span class="badge {{ $user->status ? 'bg-success' : 'bg-danger' }}">
                                                                {{ $user->status ? 'Actif' : 'Inactif' }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Double Authentification :</strong></td>
                                                        <td>
                                                            <span class="badge {{ $user->hasTwoFactorEnabled() ? 'bg-success' : 'bg-danger' }}">
                                                                {{ $user->hasTwoFactorEnabled() ? 'Activée' : 'Désactivée' }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Créé le :</strong></td>
                                                        <td>{{ $user->created_at->format('d/m/Y à H:i') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Dernière connexion :</strong></td>
                                                        <td>{{ $user->updated_at->format('d/m/Y à H:i') }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <h5 class="text-primary mb-3"><i class="fas fa-briefcase me-2"></i>Affectation Actuelle</h5>
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <td><strong>Institution :</strong></td>
                                                        <td>{{ $user->affectationActive()?->institution?->lib_institution ?? "Non affecté" }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Fonction :</strong></td>
                                                        <td>{{ $user->affectationActive()?->fonction?->lib_fonction ?? "Non définie" }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Type :</strong></td>
                                                        <td>{{ $user->affectationActive()?->institution?->typeInstitution?->lib_type_institution ?? "Non défini" }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Onglet Sécurité -->
                                <div id="profile-security" class="tab-pane fade">
                                    <div class="pt-3">
                                        <h5 class="text-primary mb-3"><i class="fas fa-shield-alt me-2"></i>Paramètres de Sécurité</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card border-0 shadow-sm">
                                                    <div class="card-body text-center">
                                                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                                                        <h5>Double Authentification</h5>
                                                        <p class="text-muted">Statut actuel :
                                                            <span class="badge {{ $user->hasTwoFactorEnabled() ? 'bg-success' : 'bg-danger' }}">
                                                                {{ $user->hasTwoFactorEnabled() ? 'Activée' : 'Désactivée' }}
                                                            </span>
                                                        </p>
                                                        @if($user->hasTwoFactorEnabled())
                                                            <a href="{{ route('two-factor.index') }}" class="btn btn-info">
                                                                <i class="fas fa-cog"></i> Gérer la 2FA
                                                            </a>
                                                        @else
                                                            <a href="{{ route('two-factor.index') }}" class="btn btn-primary">
                                                                <i class="fas fa-shield-alt"></i> Activer la 2FA
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card border-0 shadow-sm">
                                                    <div class="card-body text-center">
                                                        <i class="fas fa-key fa-3x text-warning mb-3"></i>
                                                        <h5>Mot de passe</h5>
                                                        <p class="text-muted">Dernière modification : {{ $user->updated_at->format('d/m/Y') }}</p>
                                                        <a href="{{ route('utilisateur.change-password', $user->code_user) }}" class="btn btn-warning">
                                                            <i class="fas fa-edit"></i> Modifier le mot de passe
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Onglet Activité -->
                                <div id="profile-activity" class="tab-pane fade">
                                    <div class="pt-3">
                                        <h5 class="text-primary mb-3"><i class="fas fa-history me-2"></i>Activité Récente</h5>
                                        <div class="timeline">
                                            <div class="timeline-item">
                                                <div class="timeline-marker bg-primary"></div>
                                                <div class="timeline-content">
                                                    <h6>Compte créé</h6>
                                                    <p class="text-muted">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
                                                </div>
                                            </div>
                                            <div class="timeline-item">
                                                <div class="timeline-marker bg-success"></div>
                                                <div class="timeline-content">
                                                    <h6>Dernière connexion</h6>
                                                    <p class="text-muted">{{ $user->updated_at->format('d/m/Y à H:i') }}</p>
                                                </div>
                                            </div>
                                            @if($user->hasTwoFactorEnabled())
                                            <div class="timeline-item">
                                                <div class="timeline-marker bg-info"></div>
                                                <div class="timeline-content">
                                                    <h6>2FA activée</h6>
                                                    <p class="text-muted">Double authentification activée</p>
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
