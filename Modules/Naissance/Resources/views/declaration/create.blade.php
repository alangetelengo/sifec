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
                    <!-- retour à la liste selon le type de declaration -->
                    @if($type_declaration == 'CERTIFICAT DE NON INSCRIPTION')
                    <a href="{{ route('certificatNonInscription.index') }}" class="btn btn-info float-end"> <i class="fa fa-list"></i> Retour à la liste</a>
                    @elseif($type_declaration == 'CERTIFICAT DE DESTRUCTION DE L\'ACTE')
                    <a href="{{ route('certificatDestruction.index') }}" class="btn btn-info float-end"> <i class="fa fa-list"></i> Retour à la liste</a>
                    @else
                    <a href="{{ route('declarationNaissance.index') }}" class="btn btn-info float-end"> <i class="fa fa-list"></i> Retour à la liste</a>
                    @endif
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

@include('naissance::declaration.js.create')


