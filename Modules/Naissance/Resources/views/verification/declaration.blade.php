@extends('layout.app')

@section('titre', "Vérification de la déclaration")

@section('corps')
<div class="container py-4">
    <form method="GET" action="" class="d-none">
        <input type="text" name="verif_email" autocomplete="off">
    </form>
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Déclaration n° {{ $declaration->code_declaration_naissance }}</h4>
        </div>
        <div class="card-body">
            <p class="text-muted">Information vérifiée à partir du registre de l'état civil.</p>
            <dl class="row">
                <dt class="col-sm-4">Type</dt>
                <dd class="col-sm-8">{{ $declaration->type_declaration }}</dd>

                <dt class="col-sm-4">Date de déclaration</dt>
                <dd class="col-sm-8">{{ $declaration->date_heure_declaration ? date('d/m/Y H:i', strtotime($declaration->date_heure_declaration)) : '-' }}</dd>

                <dt class="col-sm-4">Enfant</dt>
                <dd class="col-sm-8">{{ $declaration->enfant?->nomcomplet() }}</dd>

                <dt class="col-sm-4">Date de naissance</dt>
                <dd class="col-sm-8">{{ $declaration->enfant?->date_naissance ? date('d/m/Y', strtotime($declaration->enfant->date_naissance)) : '-' }}</dd>

                <dt class="col-sm-4">Déclarant</dt>
                <dd class="col-sm-8">{{ $declaration->declarant?->nomcomplet() }}</dd>

                <dt class="col-sm-4">Père</dt>
                <dd class="col-sm-8">{{ $declaration->pere?->nomcomplet() ?? '-' }}</dd>

                <dt class="col-sm-4">Mère</dt>
                <dd class="col-sm-8">{{ $declaration->mere?->nomcomplet() ?? '-' }}</dd>
            </dl>
        </div>
    </div>
</div>
@endsection
