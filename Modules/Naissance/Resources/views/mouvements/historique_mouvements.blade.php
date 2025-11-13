@extends('layout.app')
@section('titre')
Historique des mouvements
@endsection
@section('corps')
<div class="row">
    <div class="col-xl-10 offset-xl-1">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Historique des mouvements pour la déclaration n° {{ $declaration->code_declaration_naissance }}</h4>
                <a href="{{ route('naissance.mouvements.create', $declaration->id) }}" class="btn btn-success btn-sm">
                    <i class="fa fa-plus"></i> Ajouter un mouvement
                </a>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Code mouvement</th>
                            <th>Agent</th>
                            <th>Observation</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mouvements as $mvt)
                            <tr>
                                <td>{{ $mvt->date_mouvement }}</td>
                                <td>{{ $mvt->code_mouvement }}</td>
                                <td>{{ $mvt->cui }}</td>
                                <td>{{ $mvt->observation }}</td>
                                <td>
                                    <a href="{{ route('naissance.mouvements.edit', $mvt->id) }}" class="btn btn-info btn-xs">Modifier</a>
                                    <form action="{{ route('naissance.mouvements.destroy', $mvt->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Supprimer ce mouvement ?')">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">Aucun mouvement enregistré</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Retour</a>
            </div>
        </div>
    </div>
</div>
@endsection
