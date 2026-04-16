@extends('layout.app')
@section('titre')
    Nouvelle entrée de menu
@endsection
@section('styles')
@include('authentification::partials.sifec-menu-item-form-styles')
@endsection
@section('corps')
<div class="page-menu-item-form-sifec">
    <nav class="smi-breadcrumb" aria-label="Fil d'Ariane">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('module.index') }}">Administration</a></li>
            <li class="breadcrumb-item"><a href="{{ route('menu-item.index') }}">Menu latéral</a></li>
            <li class="breadcrumb-item active" aria-current="page">Création</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-xl-12">
            <div class="card sff-card">
                <div class="card-header">
                    <h4>Ajouter une entrée de menu</h4>
                    <p class="smi-header-meta mb-0">Code, libellé, parent, liens et règles d’affichage (permission Gate, visibilité par fonction).</p>
                </div>
                <div class="card-body">
                    <div class="basic-form">
                        <form class="js-sff-form" method="POST" action="{{ route('menu-item.store') }}">
                            @csrf
                            @include('authentification::menu_item._form', ['menuItem' => null, 'parents' => $parents])
                            <div class="sff-actions">
                                <a href="{{ route('menu-item.index') }}" class="btn btn-sm sff-btn-back">Retour</a>
                                <button type="submit" class="btn btn-sm sff-btn-submit">Valider</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@include('authentification::partials.sifec-form-submit-loading')
@endsection
