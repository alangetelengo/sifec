@extends("layout.app")
@section("titre")
    adoption
@endsection
@section("sous-titre")
    adoption
@endsection
@section("corps")

        <!-- row -->
        <div class="row" id="validation">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Adoption de l'enfant
                            @php
                                setlocale(LC_TIME, "fr_FR", "French");
                                // echo utf8_encode(strftime("%B", strtotime(date('Y-m-d'))));
                            @endphp
                        </h4>
                    </div>
                    <div class="card wizard-content">
                        <div class="col-12">

                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('declarationNaissance.adopter',$an->declaration->code_declaration_naissance) }}" method="post">
                                        @csrf
                                        @method("PUT")
                                        <div class="row">
                                            <div class="ligne">
                                            <h4>INFORMATIONS DU JUGEMENT</h4>
                                            </div>
                                            <div class="mb-2 col-md-4">
                                                <label class="form-label">Tribunal <span class="text-danger">*</span></label>
                                                <select class="form-control" readOnly>
                                                    <option>{{$jugement->institutionUser->institution->lib_institution }}</option>
                                                </select>
                                            </div>

                                            <div class="mb-2 col-md-3">
                                                <label class="form-label">N° du jugement</label>
                                                <input type="text" class="form-control" readonly value="{{ $jugement->num_jugement }}" id="num_jugement">
                                                <input type="hidden" class="form-control" name="code_jugement" value="{{ $jugement->code_jugement }}" id="code_jugement">
                                            </div>

                                            <div class="mb-2 col-md-2">
                                                <label class="form-label">Date du jugement <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" readonly  value="{{ $jugement->date_jugement }}" id="date_jugement">

                                            </div>
                                            <div class="mb-2 col-md-3">
                                                <label class="form-label">Numéro de l'acte <span class="text-danger">*</span></label>
                                                <input type="text" name="numero_acte_naissance"  class="form-control" readonly value="{{ $jugement->numero_ancien_acte }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="ligne">
                                                <h4>INFORMATIONS SUR L'ENFANT</h4>
                                            </div>
                                            <div class="mb-2 col-md-3">
                                                <label class="form-label">Nom(s) et Prénom(s) de l'enfant <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control"  readonly  onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()" value="{{ $an->declaration->enfant->nom.' - '.$an->declaration->enfant->prenom }}">
                                            </div>

                                            <div class="mb-1 col-md-2">
                                                <label class="form-label">Sexe</label>
                                                <select class="form-control" readonly>
                                                    <option>{{ $an->declaration->enfant->sexe == "M" ? "Masculin" : "Féminin" }}</option>
                                                </select>
                                            </div>
                                            <div class="mb-2 col-md-2">
                                                <label class="form-label">Date de naissance</label>
                                                <input type="date" class="form-control" readonly value="{{ $an->declaration->enfant->date_naissance }}">
                                            </div>
                                            <div class="mb-2 col-md-2">
                                                <label class="form-label">Lieu de naissance</label>
                                                <input type="text" class="form-control" readonly value="{{ $an->declaration->enfant->lieu_naissance }}">
                                            </div>
                                            <div class="mb-2 col-md-3">
                                                <label class="form-label">Centre d'état civil de naissance</label>
                                                <input type="text" class="form-control" readonly value="{{ $an->institutionUser->institution->lib_institution }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                             <div class="mb-2 col-md-3">
                                                <br><br>
                                                <a href="{{ route("jugement.index") }}"><button type="button" class="btn btn-sm btn-danger">Liste des jugements</button></a>
                                                {{-- <a href="javascript:void(0)" class="chercheacteAdopter"><button type="button" class="btn btn-sm btn-primary">Continuer</button></a> --}}
                                                <button type="submit" class="btn btn-sm btn-primary">Continuer</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
@endsection
