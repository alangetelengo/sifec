@extends('layout.app')

@section('titre')
    Certificat numérique (.p12)
@endsection

@section('styles')
@include('referentiel::partials.sifec-ref-crud-styles')
@endsection

@section('corps')
<div class="sifec-ref-crud-page container-fluid px-0" style="max-width: 640px;">
    <div class="sl-hero mb-4">
        <h1 class="mb-1"><i class="fas fa-file-download me-2"></i>Certificat numérique (.p12)</h1>
        <p class="mb-0">Conservez la passphrase avant de télécharger le fichier.</p>
    </div>

    <div class="card sl-card">
        <div class="card-body">
            <dl class="row mb-3">
                <dt class="col-sm-4">Utilisateur</dt>
                <dd class="col-sm-8">{{ $user->email }}</dd>
                <dt class="col-sm-4">Fichier</dt>
                <dd class="col-sm-8"><code>{{ $filename }}</code></dd>
            </dl>

            <div class="alert alert-warning border-0">
                <div class="fw-semibold mb-1"><i class="fas fa-key me-1"></i>Passphrase du fichier .p12</div>
                <code class="fs-6 user-select-all">{{ $passphrase }}</code>
                <p class="small mb-0 mt-2">
                    Notez-la avant de télécharger. Elle est liée à <strong>ce</strong> fichier uniquement :
                    un nouveau téléchargement produira une autre passphrase.
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ $downloadUrl }}" class="btn btn-success fw-semibold" id="btnDownloadP12">
                    <i class="fas fa-download me-1"></i>Télécharger {{ $filename }}
                </a>
                <a href="{{ $profileUrl }}" class="btn btn-outline-secondary">
                    Retour au profil
                </a>
            </div>
            <p class="small text-muted mt-3 mb-0">
                Le lien de téléchargement expire après utilisation ou dans 10 minutes.
            </p>
        </div>
    </div>
</div>
@endsection
