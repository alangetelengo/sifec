{{-- @extends('layout.app')
@section('titre')
  Assignation des fonctionnalités
@endsection
@section('styles')
@endsection
@section('corps')
<div class="row">
    <!-- Column starts -->
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-block">
                <h3> Assignation des fonctionnalités à <strong>{{ $user->personne->nomcomplet() }}</strong></h3>
            </div>
            <div class="card-body">
                <div class="accordion accordion-primary" id="accordion-one">
                    <form action="{{ route("utilisateur.assigner.store",$user->code_user) }}" method="post">
                        @csrf
                        @forelse ($modules as $item)

                            <div class="accordion-item">
                                    <div class="accordion-header  rounded-lg" id="heading{{ $item->code_module }}" data-bs-toggle="collapse" data-bs-target="#{{ $item->code_module }}" aria-controls="collapse{{ $item->code_module }}"   aria-expanded="true" role="button">
                                        <span class="accordion-header-icon"></span>
                                        <span class="accordion-header-text">{{ $item->lib_module }}</span>
                                        <span class="accordion-header-indicator"></span>
                                    </div>
                                    <div id="{{ $item->code_module }}" class="collapse" aria-labelledby="heading{{ $item->code_module }}" data-bs-parent="#accordion-{{ $item->code_module }}">
                                        <div class="accordion-body-text">
                                            <div class="row">
                                                @forelse ($item->fonctionnalites as $f)
                                                    <h5 class="text-success bg-light"><strong>{{ $f->code_fonctionnalite_parent == "" ? $f->lib_fonctionnalite : "" }}</strong></h5>
                                                    <div class="col-md-6">
                                                        @if($f->code_fonctionnalite_parent != "")
                                                        <label for="{{ $f->code_fonctionnalite }}">
                                                            <input type="checkbox" name="fonctionnalites[]" {{ $fonction->fonctionnalites->pluck("code_fonctionnalite")->unique()->contains($f->code_fonctionnalite) ? "checked":"" }} value="{{ $f->code_fonctionnalite }}" id="{{ $f->code_fonctionnalite }}"> {{ $f->lib_fonctionnalite }}
                                                        </label>
                                                        @endif
                                                    </div>
                                                @empty

                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        @empty

                        @endforelse
                        <a href="{{ route("fonction.index") }}" class="btn btn-sm btn-danger">Retour</a>
                        <button type="submit" class="btn btn-sm btn-primary">Valider</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Column ends -->

</div>
@endsection
@section("scripts")
@endsection --}}
