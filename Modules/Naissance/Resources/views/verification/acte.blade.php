@extends('layout.app')

@section('titre', 'Vérification de l\'acte')

@section('corps')
<div class="container py-4">
    <form method="GET" action="" class="d-none">
        <input type="text" name="verif_email" autocomplete="off">
    </form>
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Acte de naissance n° {{ $acte->niupp }}</h4>
        </div>
        <div class="card-body">
            <p class="text-muted">Information issue du registre de l'état civil.</p>
            <dl class="row">
                <dt class="col-sm-4">Statut</dt>
                <dd class="col-sm-8">
                    @if($acte->statut)
                        <span class="badge bg-danger">Acte annulé</span>
                    @else
                        <span class="badge bg-success">Acte valide</span>
                    @endif
                </dd>

                <dt class="col-sm-4">Enfant</dt>
                <dd class="col-sm-8">{{ $acte->declaration?->enfant?->nomcomplet() }}</dd>

                <dt class="col-sm-4">Date de naissance</dt>
                <dd class="col-sm-8">{{ $acte->declaration?->enfant?->date_naissance ? date('d/m/Y', strtotime($acte->declaration->enfant->date_naissance)) : '-' }}</dd>

                <dt class="col-sm-4">Déclarant</dt>
                <dd class="col-sm-8">{{ $acte->declaration?->declarant?->nomcomplet() }}</dd>

                <dt class="col-sm-4">Date de déclaration</dt>
                <dd class="col-sm-8">{{ $acte->declaration?->date_heure_declaration ? date('d/m/Y H:i', strtotime($acte->declaration->date_heure_declaration)) : '-' }}</dd>

                <dt class="col-sm-4">Date d'émission</dt>
                <dd class="col-sm-8">{{ $acte->date_emission ? date('d/m/Y H:i', strtotime($acte->date_emission)) : '-' }}</dd>
            </dl>
        </div>
    </div>
</div>
@endsection
