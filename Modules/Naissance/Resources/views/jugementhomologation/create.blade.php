<html lang="fr">
@extends("layout.app")
@section("titre")
    {{ $type_declaration }}
@endsection

@section("corps")
    <!-- row -->
    <div class="row" id="validation">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $title }}</h4>
                </div>
                <div class="card wizard-content">
                    <div class="card-body">
                        @include('naissance::declaration.form')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include("naissance::declaration.search_parents")

    {{-- @include("naissance::declaration.ajout_piece_parent") --}}
@endsection

@include('naissance::jugementhomologation.js.create')
