@extends("layout.app")
@section("titre")
    Rectification actes
@endsection
@section("sous-titre")
    Rectification actes
@endsection
@section("corps")

        <!-- row -->
        <div class="row" id="validation">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Rectification de l'acte
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
                                    <form action="{{ route('acteNaissance.rectificationacte') }}" method="GET">
                                        <div class="row">
                                            <h4>INFORMATIONS DU JUGEMENT</h4>

                                            <div class="mb-2 col-md-4">
                                                <label class="form-label">Tribunal <span class="text-danger">*</span></label>
                                                <select id="tribunal" name="tribunal" class="form-control" readOnly>
                                                    <option>{{$jugement->institutionUser->institution->lib_institution }}</option>
                                                </select>
                                            </div>

                                            <div class="mb-2 col-md-3">
                                                <label class="form-label">N° du jugement</label>
                                                <input type="text" class="form-control" readonly value="{{ $jugement->num_jugement }}" id="num_jugement">
                                                <input type="hidden" class="form-control" value="{{ $jugement->code_jugement }}" id="code_jugement">
                                            </div>

                                            <div class="mb-2 col-md-2">
                                                <label class="form-label">Date du jugement <span class="text-danger">*</span></label>
                                                <input type="date" name="date_jugement"  class="form-control" readonly  value="{{ $jugement->date_jugement }}" id="date_jugement">

                                            </div>
                                            <div class="mb-2 col-md-3">
                                                <label class="form-label">Numéro de l'acte à rectifier <span class="text-danger">*</span></label>
                                                <input type="text" name="id"  class="form-control" readonly value="{{ $jugement->numero_ancien_acte }}">
                                            </div>

                                            <div class="col-2">
                                                <label for="">.</label>
                                                <button class="btn btn-primary btn-lg btn-block" type="submit">Rechercher l'acte</button>
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
