<html lang="fr">
@extends("layout.app")
@section("titre")
    Déclaration naissance
@endsection


@section("corps")

        <!-- row -->
        <div class="row" id="validation">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Créer une déclaration de naissance pour enfant trouvé</h4>
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
@endsection
@include('naissance::enfant-trouver.js.create')
