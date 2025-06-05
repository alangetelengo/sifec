@extends("layout.app")
@section("titre")
    Déclaration naissance
@endsection
@section("sous-titre")
    Déclaration naissance
@endsection
@section("corps")

        <!-- row -->
        <div class="row" id="validation">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Déclarations de naissance par sexe, du mois de
                            @php
                                setlocale(LC_TIME, "fr_FR", "French");
                                echo utf8_encode(strftime("%B", strtotime(date('Y-m-d'))));
                            @endphp
                        </h4>
                    </div>
                    <div class="card wizard-content">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <a href="{{ route('statistiquesNaissance.sexeDeclarationEtat') }}" class="btn btn-sm btn-primary" target="_blanc">Visualiser Etat</a><br>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Sexe</th>
                                                    <th>Nombre</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $total = 0;
                                                @endphp
                                                @foreach ($datas as $data)
                                                <tr width="100%">
                                                    <td>{{$data->sexe == "M" ? "Masculin":"Féminin"}}</td>
                                                    <td>{{ $data->TOTAL }}</td>
                                                    @php
                                                        $total += $data->TOTAL;
                                                    @endphp
                                                </tr>
                                                @endforeach
                                                <tr width="100%">
                                                    <td><strong>Total</strong></td>
                                                    <td><strong>{{ $total }}</strong></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>




@endsection
