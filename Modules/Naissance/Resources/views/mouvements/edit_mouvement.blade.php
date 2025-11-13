@extends('layout.app')
@section('titre')
Modifier un mouvement
@endsection
@section('corps')
<div class="row">
    <div class="col-xl-6 offset-xl-3">
        <div class="card">
            <div class="card-header">
                <h4>Modifier le mouvement du {{ $mouvement->date_mouvement }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('naissance.mouvements.update', $mouvement->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label>Code mouvement <span class="text-danger">*</span></label>
                        <input type="text" name="code_mouvement" class="form-control" value="{{ $mouvement->code_mouvement }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Observation</label>
                        <textarea name="observation" class="form-control">{{ $mouvement->observation }}</textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('naissance.mouvements.historique', $mouvement->code_declaration_naissance) }}" class="btn btn-secondary">Retour</a>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
