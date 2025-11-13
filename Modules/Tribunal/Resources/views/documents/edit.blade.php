@extends('layout.app')
@section('titre')
Modifier le document importé
@endsection
@section('corps')
<div class="row">
    <div class="col-xl-8 offset-xl-2">
        <div class="card">
            <div class="card-header">
                <h4>Modifier le document importé pour le certificat n° {{ $declaration->numero_certificat ?? $declaration->getKey() }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('tribunal.update_document', ['type' => $type, 'id' => $declaration->getKey()]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label>Type de document</label>
                        <input type="text" class="form-control" value="{{ $documentType }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label>Numéro du document</label>
                        <input type="text" class="form-control" name="num_document" value="{{ $document->num_requisition ?? $document->num_jugement ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label>Date du document</label>
                        <input type="date" class="form-control" name="date_document" value="{{ $document->date_requisition ?? $document->date_jugement ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label>Document actuel</label>
                        @if($document->document_requisition ?? $document->document_jugement)
                            <a href="{{ asset($document->document_requisition ?? $document->document_jugement) }}" target="_blank">Voir le document</a>
                        @else
                            <span class="text-danger">Aucun document importé</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label>Remplacer le document (pdf, jpg, png)</label>
                        <input type="file" class="form-control" name="document_importer" accept=".pdf,.jpg,.png">
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Retour</a>
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
