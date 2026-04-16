@extends('layout.app')
@section('titre')
    Modifier une entrée de menu
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
            <li class="breadcrumb-item active" aria-current="page">Modification</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-xl-12">
            <div class="card sff-card">
                <div class="card-header">
                    <h4>Modifier une entrée de menu</h4>
                    <p class="smi-header-meta mb-0">Code <code class="text-dark">{{ $menuItem->code_menu_item }}</code> — non modifiable. Les autres champs sont éditables.</p>
                </div>
                <div class="card-body">
                    <div class="basic-form">
                        <form class="js-sff-form" method="POST" action="{{ route('menu-item.update', $menuItem->code_menu_item) }}">
                            @csrf
                            @method('PUT')
                            @include('authentification::menu_item._form', ['menuItem' => $menuItem, 'parents' => $parents])
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
