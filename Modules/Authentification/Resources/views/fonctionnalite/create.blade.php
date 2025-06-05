@extends('layout.app')
@section('titre')
Créer une personne
@endsection
@section('styles')
@endsection
@section('corps')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4> Ajouter un fonctionnalité</h4>
                </div>
                <div class="card-body">
                    <div class="basic-form">
                        <form method="POST" action="{{ route("fonctionnalite.store") }}">
                            @csrf
                            <div class="row">
                                <div class="mb-2 col-md-6">
                                    <label class="form-label">Libéllé <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" class="form-control @error('lib_fonctionnalite') is-invalid @enderror" value="{{ old("lib_fonctionnalite") }}" name="lib_fonctionnalite">
                                    @error("lib_fonctionnalite")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-6">
                                    <label class="form-label">Libéllé technique <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" class="form-control @error('lib_technique') is-invalid @enderror" value="{{ old("lib_technique") }}" name="lib_technique">
                                    @error("lib_technique")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-2 col-md-6">
                                    <label class="form-label">Fonctionnalité parent</label>
                                    <select name="code_fonctionnalite_parent" class="form-control form-control wide @error("code_fonctionnalite_parent") is-invalid @enderror">
                                        <option value="">Selectionner</option>
                                        @foreach ($fonctionnalites as $fonctionnalite)
                                            <option value="{{ $fonctionnalite->code_fonctionnalite }}">{{ $fonctionnalite->lib_fonctionnalite }}</option>
                                        @endforeach
                                    </select>
                                    @error("code_fonctionnalite_parent")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-6">
                                    <label class="form-label">Description </label>
                                    <textarea rows="5" class="form-control form-control-sm @error("description_fonctionnalite") is-invalid @enderror" name="description_fonctionnalite">{{ old("description_fonctionnalite") }}</textarea>
                                    @error("description_fonctionnalite")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-2 col-md-6">
                                    <label class="form-label">Module <span class="text-danger">*</span></label>
                                    <select name="code_module" class="form-control form-control wide @error("code_module") is-invalid @enderror">
                                        <option value="">Selectionner</option>
                                        @foreach ($modules as $module)
                                            <option value="{{ $module->code_module }}">{{ $module->lib_module }}</option>
                                        @endforeach
                                    </select>
                                    @error("code_module")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="mb-2 col-md-6">
                                    <label class="form-label">Statut <span class="text-danger">*</span></label>
                                    <select name="etat_fonctionnalite" class="form-control form-control wide @error("etat_fonctionnalite") is-invalid @enderror">
                                        <option value="">Choisissez</option>
                                        <option value="Activé">Activer</option>
                                        <option value="Désactivé">Désactiver</option>
                                    </select>
                                    @error("etat_fonctionnalite")
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <br>
                            <a href="{{ route("fonctionnalite.index") }}"><button type="button" class="btn btn-sm btn-danger">Retour</button></a>
                            <button type="submit" class="btn btn-sm btn-primary">Valider</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')

@endsection
