@extends("layout.app")
@section("titre")
    annulation de l'acte
@endsection
@section("sous-titre")
    annulation de l'acte
@endsection
@section("corps")

        <!-- row -->
        <div class="row" id="validation">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Annulation de l'acte
                            @php
                                setlocale(LC_TIME, "fr_FR", "French");
                                // echo utf8_encode(strftime("%B", strtotime(date('Y-m-d'))));
                            @endphp
                        </h4>
                    </div>
                    <div class="card wizard-content">
                        <div class="col-12">
                            @if($an->deleted_at != null)
                                <h4 style="color: red">  CET ACTE DE NAISSANCE EST DEJA ANNULE </h4>
                            @endif

                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('acteNaissance.valider.annulation',$jugement->numero_ancien_acte) }}" method="post">
                                        @csrf
                                        @method("PUT")
                                        <div class="row">
                                            <div class="ligne">
                                            <h4>INFORMATIONS DU JUGEMENT</h4>
                                            </div>

                                            <div class="mb-2 col-md-4">
                                                <label class="form-label">Tribunal <span class="text-danger">*</span></label>
                                                <select id="tribunal" name="tribunal" class="form-control" readOnly>
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
                                                <input type="date" name="date_jugement"  class="form-control" readonly  value="{{ $jugement->date_jugement }}" id="date_jugement">

                                            </div>
                                            <div class="mb-2 col-md-3">
                                                <label class="form-label">Numéro de l'acte à annuler <span class="text-danger">*</span></label>
                                                <input type="text" name="id"  class="form-control" readonly value="{{ $jugement->numero_ancien_acte }}">
                                            </div>
                                            @if($an->deleted_at == null)
                                            <div class="mb-2 col-md-4">
                                                <label class="form-label">Motif d'annulation <span class="text-danger">*</span></label>
                                                <input type="text" name="motif" class="form-control" required>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="row">
                                             <div class="mb-2 col-md-3">
                                                <br><br>
                                                <a href="{{ route("jugement.index") }}"><button type="button" class="btn btn-sm btn-danger">Liste des jugements</button></a>
                                                @if($an->deleted_at == null)
                                                <button type="submit" class="btn btn-sm btn-primary">Valider</button>
                                                @endif
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
