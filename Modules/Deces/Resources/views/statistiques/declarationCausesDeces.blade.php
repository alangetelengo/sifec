@extends("layout.app")
@section("titre")
    Déclaration décès
@endsection
@section("sous-titre")
    Déclaration décès
@endsection
@section("corps")
        <!-- row -->
        <div class="row" id="validation">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Déclarations de décès par cause et zone, du mois de
                            @php
                            setlocale(LC_TIME, "fr_FR", "French");
                            echo utf8_encode(strftime("%B", strtotime(date('Y-m-d'))))." ".
                             utf8_encode(strftime("%B", strtotime(date('Y-m-d'))));
                            @endphp
                        </h4>
                    </div>
                    <div class="card wizard-content">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <a href="{{ route('statistiquesDeces.causeDeclarationEtat') }}" class="btn btn-sm btn-primary" target="_blanc">Visualiser Etat</a><br>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Zones</th>
                                                    <th>Causes</th>
                                                    <th>Nombre</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
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
                                                @endforeach
                                                <tr width="100%">
                                                    <td colspan="2"><strong>Total</strong></td>
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
