@extends('layout.app')
@section('titre')
Modification d'une fonctionnalité
@endsection
@section('styles')
@include('authentification::partials.sifec-fonctionnalite-form-styles')
@endsection
@section('corps')
<div class="page-fonctionnalite-form-sifec">
    <div class="row">
        <div class="col-xl-12">
            <div class="card sff-card">
                <div class="card-header">
                    <h4>Modification d'une fonctionnalité</h4>
                </div>
                <div class="card-body">
                    <div class="basic-form">
                        <form class="js-sff-form" action="{{ route('fonctionnalite.update', $fonctionnalite->code_fonctionnalite) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="mb-2 col-md-6">
                                    <label class="form-label">Libellé <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $fonctionnalite->lib_fonctionnalite }}" name="lib_fonctionnalite" required>
                                </div>
                                <div class="mb-2 col-md-6">
                                    <label class="form-label">Libellé technique <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $fonctionnalite->lib_technique }}" name="lib_technique" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-2 col-md-6">
                                    <label class="form-label">Fonctionnalité parent</label>
                                    <select name="code_fonctionnalite_parent" class="form-control form-control wide @error('code_fonctionnalite_parent') is-invalid @enderror">
                                        <option value="">Sélectionner</option>
                                        @foreach ($fonctionnalites as $item)
                                            @if($item->parent == null)
                                            <option value="{{ $item->code_fonctionnalite }}" {{ $item->code_fonctionnalite == $fonctionnalite->code_fonctionnalite_parent ? 'selected' : '' }}>{{ $item->lib_fonctionnalite }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('code_fonctionnalite_parent')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-6">
                                    <label class="form-label">Description</label>
                                    <textarea rows="5" class="form-control form-control-sm" name="description_fonctionnalite">{{ $fonctionnalite->description_fonctionnalite }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-2 col-md-6">
                                    <label class="form-label">Module <span class="text-danger">*</span></label>
                                    <select name="code_module" class="form-control form-control wide" required>
                                        <option value="">Sélectionner</option>
                                        @foreach ($modules as $module)
                                            <option value="{{ $module->code_module }}" {{ $fonctionnalite->code_module == $module->code_module ? 'selected' : '' }}>{{ $module->lib_module }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-2 col-md-6">
                                    <label class="form-label">État</label>
                                    <select name="etat_fonctionnalite" class="form-control form-control wide">
                                        @if ($fonctionnalite->etat_fonctionnalite == 'Activé')
                                        <option value="{{ $fonctionnalite->etat_fonctionnalite }}" selected>Activé</option>
                                        <option value="Désactivé">Désactivé</option>
                                        @endif
                                        @if ($fonctionnalite->etat_fonctionnalite == 'Désactivé')
                                        <option value="{{ $fonctionnalite->etat_fonctionnalite }}" selected>Désactivé</option>
                                        <option value="Activé">Activé</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="sff-actions">
                                <a href="{{ route('fonctionnalite.index') }}" class="btn btn-sm sff-btn-back">Retour</a>
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
