@extends('layout.app')
@section('titre')
Détail de la déclaration
@endsection
@section('corps')
<div class="row">
    <div class="col-xl-8 offset-xl-2">
        <div class="card">
            <div class="card-header">
                <h4>Détail de la déclaration ({{ ucfirst($type) }})</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Type de déclaration :</label>
                    <div>{{ $declaration->type_declaration ?? '' }}</div>
                </div>
                @if($type === 'naissance')
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nom de l'enfant :</label>
                        <div>{{ $declaration->enfant->nom ?? '' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Prénom de l'enfant :</label>
                        <div>{{ $declaration->enfant->prenom ?? '' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Date de naissance :</label>
                        <div>{{ $declaration->enfant->date_naissance ?? '' }}</div>
                    </div>
                @else
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nom du défunt :</label>
                        <div>{{ $declaration->defunt->nom ?? '' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Prénom du défunt :</label>
                        <div>{{ $declaration->defunt->prenom ?? '' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Date de décès :</label>
                        <div>{{ $declaration->defunt->date_deces ?? '' }}</div>
                    </div>
                @endif
                <div class="mb-3">
                    <label class="form-label fw-bold">Pièces jointes :</label>
                    <ul>
                        @if($declaration->requisition && $declaration->requisition->document_requisition)
                            <li>Réquisition : <a href="{{ asset($declaration->requisition->document_requisition) }}" target="_blank">Voir</a></li>
                        @endif
                        @if($declaration->jugement && $declaration->jugement->document_jugement)
                            <li>Jugement : <a href="{{ asset($declaration->jugement->document_jugement) }}" target="_blank">Voir</a></li>
                        @endif
                    </ul>
                </div>
                <div class="d-flex justify-content-end">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Retour</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
