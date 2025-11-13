@extends('layout.app')

@section('titre', 'Double Authentification')

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
</style>
@endsection

@section('corps')
<div class="container-fluid">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"><i class="fas fa-home"></i> Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('utilisateur.profile', auth()->user()->code_user) }}"><i class="fas fa-user"></i> Mon Profil</a></li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-shield-alt"></i> Double Authentification</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Double Authentification (2FA)</h4>
                    <a href="{{ route('utilisateur.profile', auth()->user()->code_user) }}">
                        <button type="button" class="btn btn-sm btn-primary float-end">
                            <i class="fa fa-arrow-left"></i> Retour au Profil
                        </button>
                    </a>
                </div>
                <div class="card-body">

                    @if($twoFactorEnabled)
                        <!-- 2FA Activée -->
                        <div class="alert alert-success">
                            <h5><i class="fas fa-check-circle"></i> Double authentification activée</h5>
                            <p class="mb-0">Votre compte est protégé par une authentification à deux facteurs.</p>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <i class="fas fa-key fa-3x text-primary mb-3"></i>
                                        <h5>Codes de Récupération</h5>
                                        <p class="text-muted">Gérez vos codes de récupération</p>
                                        <a href="{{ route('two-factor.recovery-codes') }}" class="btn btn-primary">
                                            <i class="fas fa-key"></i> Gérer les Codes
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <i class="fas fa-qrcode fa-3x text-warning mb-3"></i>
                                        <h5>Reconfigurer</h5>
                                        <p class="text-muted">Obtenir un nouveau QR code</p>
                                        <a href="{{ route('two-factor.enable') }}" class="btn btn-warning">
                                            <i class="fas fa-qrcode"></i> Reconfigurer
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="text-center mt-4">
                            <h5 class="text-danger">Désactiver la 2FA</h5>
                            <p class="text-muted">Attention : Désactiver la 2FA réduit la sécurité de votre compte.</p>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#disableModal">
                                <i class="fas fa-times-circle"></i> Désactiver
                            </button>
                        </div>
                    @else
                        <!-- 2FA Désactivée -->
                        <div class="alert alert-warning">
                            <h5><i class="fas fa-exclamation-triangle"></i> Double authentification désactivée</h5>
                            <p class="mb-0">Votre compte n'est pas protégé par une authentification à deux facteurs.</p>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-8">
                                <h5 class="text-primary mb-3"><i class="fas fa-info-circle"></i> Pourquoi activer la 2FA ?</h5>
                                <ul>
                                    <li class="mb-2"><i class="fas fa-shield-alt text-success"></i> <strong>Sécurité Renforcée</strong> - Protège votre compte même si votre mot de passe est compromis</li>
                                    <li class="mb-2"><i class="fas fa-lock text-primary"></i> <strong>Données Sensibles</strong> - Protection des données d'état civil et documents officiels</li>
                                    <li class="mb-2"><i class="fas fa-user-shield text-warning"></i> <strong>Comptes Administrateurs</strong> - Requis pour les comptes avec privilèges élevés</li>
                                    <li class="mb-2"><i class="fas fa-mobile-alt text-info"></i> <strong>Facile à Utiliser</strong> - Compatible avec toutes les applications d'authentification</li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <i class="fas fa-lock fa-4x text-primary mb-3"></i>
                                        <h5>Activer la 2FA</h5>
                                        <p class="text-muted">Protégez votre compte</p>
                                        <a href="{{ route('two-factor.enable') }}" class="btn btn-primary btn-lg">
                                            <i class="fas fa-shield-alt"></i> Activer
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de désactivation -->
<div class="modal fade" id="disableModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('two-factor.disable') }}">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Désactiver la 2FA</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-danger"><strong>Attention !</strong> Êtes-vous sûr de vouloir désactiver la double authentification ?</p>
                    <p>Votre compte sera moins sécurisé.</p>
                    <div class="form-group">
                        <label for="password">Confirmez avec votre mot de passe</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Désactiver la 2FA</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

