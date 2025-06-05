@extends("layout.app")
@section("titre")
    Statistiques décès
@endsection
@section("sous-titre")
    Statistiques décès
@endsection
@section("corps")

        <!-- row -->
        <div class="row" id="validation">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Déclarations de décès par tranches d'âge, du mois de
                            @php
                            setlocale(LC_TIME, "fr_FR", "French");
                            echo utf8_encode(strftime("%B", strtotime(date('Y-m-d'))))." ". utf8_encode(strftime("%Y", strtotime(date('Y-m-d'))));
                            @endphp
                        </h4>
                    </div>
                    <div class="card wizard-content">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <a href="{{ route('statistiquesDeces.decesparageEt') }}" class="btn btn-sm btn-primary" target="_blanc">Visualiser Etat</a><br>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Tranches d'âge</th>
                                                    <th>Nombre</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Moins de 18 ans</td>
                                                    <td>{{ $moinsde18 }}</td>
                                                </tr>
                                                <tr>
                                                    <td>De 18 à 29 ans</td>
                                                    <td>{{ $de18a29 }}</td>
                                                </tr>
                                                <tr>
                                                    <td>De 30 à 65 ans</td>
                                                    <td>{{ $de30a65 }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Plus 65 ans</td>
                                                    <td>{{ $plusde65 }}</td>
                                                </tr>
                                                {{-- @php
                                                    $total = 0;
                                                    $tab = [];
                                                @endphp
                                                @foreach ($datas as $data)
                                                @php
                                                    $array = implode("','",$tab);
                                                    $tab[] = $data->arrondissement;
                                                @endphp
                                                <tr width="100%">
                                                    @if (str_contains($array, $data->arrondissement) == 0)
                                                        <td>{{ $data->arrondissement }}</td>
                                                    @else
                                                    <td></td>
                                                    @endif
                                                    <td>{{$data->lib_cause_deces}}</td>
                                                    <td>{{ $data->TOTAL }}</td>
                                                    @php
                                                        $total += $data->TOTAL;
                                                    @endphp
                                                </tr>
                                                @endforeach--}}
                                                <tr width="100%">
                                                    <td colspan="2" class="text-center"><strong>Total {{ $total }}</strong></td>
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
