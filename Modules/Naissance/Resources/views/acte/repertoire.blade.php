@extends("layout.app")
@section("titre")
    Répertoire actes
@endsection
@section("sous-titre")
    Répertoire actes
@endsection
@section("corps")

        <!-- row -->
        <div class="row" id="validation">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Répertoire alphabétique des actes de naissance
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
                                    {{-- <form action="{{ route('statistiquesNaissance.repertoire') }}" method="POST"> --}}
                                    <form action="{{ route('statistiquesNaissance.repertoire.resultat') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-3">
                                                <label for="">Du</label>
                                                <input type="date" name="dated" class="form-control">
                                            </div>
                                            <div class="col-3">
                                                <label for="">Au</label>
                                                <input type="date" name="datef" class="form-control">
                                            </div>
                                            <div class="col-2">
                                                <label for="">.</label>
                                                <button class="btn btn-primary btn-lg btn-block" type="submit">Afficher l'état</button>
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
