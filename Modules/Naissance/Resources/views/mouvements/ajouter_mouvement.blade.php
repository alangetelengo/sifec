@extends('layout.app')
@section('titre')
Ajouter un mouvement
@endsection
@section('corps')
<div class="row">
    <div class="col-xl-6 offset-xl-3">
        <div class="card">
            <div class="card-header">
                <h4>Ajouter un mouvement à la déclaration n° {{ $declaration->code_declaration_naissance }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('naissance.mouvements.store', $declaration->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Code mouvement <span class="text-danger">*</span></label>
                        <input type="text" name="code_mouvement" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Observation</label>
                        <textarea name="observation" class="form-control"></textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('naissance.mouvements.historique', $declaration->id) }}" class="btn btn-secondary">Retour</a>
                        <button type="submit" class="btn btn-success">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
