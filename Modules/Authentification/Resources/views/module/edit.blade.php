@extends('layout.app')
@section('titre')
Modification du module
@endsection

@section('corps')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4> Modification du module</h4>
                </div>
                <div class="card-body">
                    <div class="basic-form">
                        <form method="POST" action="{{ route("module.update", $module->code_module) }}">
                            @method("PUT")
                            @csrf
                            <div class="row">
                                <div class="mb-2 col-md-12">
                                    <label class="form-label">Libéllé <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" class="form-control @error('lib_module') is-invalid @enderror" value="{{ $module->lib_module }}" name="lib_module">
                                    @error("lib_module")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-2 col-md-12">
                                    <label class="form-label">Description </label>
                                    <textarea rows="5" class="form-control form-control-sm @error("description_module") is-invalid @enderror" name="description_module">{{ $module->description_module }}</textarea>
                                    @error("description_module")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">

                                <div class="mb-2 col-md-6">
                                    <label class="form-label">Etat</label>
                                    <select name="etat_module" class="form-control form-control wide">
                                        @if ($module->etat_module == "Activé")
                                        <option value="{{ $module->etat_module }}" selected>Activé</option>
                                        <option value="Désactivé">Désactivé</option>
                                        @endif
                                        @if ($module->etat_module == "Désactivé")
                                        <option value="{{ $module->etat_module }}" selected>Désactivé</option>
                                        <option value="Activé">Activé</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <br>
                            <a href="{{ route("module.index") }}"><button type="button" class="btn btn-sm btn-danger">Retour</button></a>
                            <button type="submit" class="btn btn-sm btn-primary">Valider</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

