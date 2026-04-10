<html lang="fr">
@extends("layout.app")
@section("titre")
    Déclaration naissance
@endsection


@section("corps")

<div class="page-sifec-form">
        <!-- row -->
        <div class="row" id="validation">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Enregistrer un certificat pour enfant trouvé (déclaration générée)</h4>
                        <a href="{{ route('certificatNonInscription.index') }}" class="btn btn-info float-end"><i class="fa fa-list"></i> Retour à la liste</a>
                    </div>
                    <div class="card wizard-content">
                        <div class="card-body">
                           @include('naissance::enfant-trouver.form')
                        </div>

                        @include("naissance::enfant-trouver.search_parents")

                    </div>
                </div>
            </div>
        </div>
</div>
@endsection
@include('naissance::enfant-trouver.js.create')
