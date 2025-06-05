@extends('layout.app')
@section('titre')
    Statisqtiques
@endsection
@section('styles')
{{-- <link rel="stylesheet" type="text/css" href="{{asset('carte/css/map.css')}}"> --}}
@endsection
@section('corps')

<div class="card">
    <div class="card-body">
        <div class="row">
            <div>
                <form action="{{ route('dashboard.statgenredep') }}" method="GET">
                    <div class="row">
                        <div class="col-3">
                            {{-- <label for="">Année</label> --}}
                            <select name="annee" class="form-control">
                                <option value="">Choisir l'anné</option>
                                @for ($i = 0; $i < 3; $i++)
                                    <option value="{{ date('Y') - $i }}">{{ date('Y') - $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-3">
                            {{-- <label for="">.</label> --}}
                            <button type="submit" class="btn btn-primary form-control">Afficher</button>
                        </div>
                    </div>
                </form>
                <br>
            </div>
            <div class="col-lg-12 text-left"
                style="background-color:white;padding-left:20px;padding-right:20px;margin-top:-15px;">
                <div>
                    <br>
                    <br>
                    <h4>NAISSANCE PAR DEPARTEMENT ET SEXE DE l'ANNEE {{ $annee }}</h4>
                </div>
                <div>
                    <table class="table table-striped table-bordered">
                        <tr>
                            <td class="text-center"><strong>DEPARTEMENT</strong></td>
                            <td class="text-center"><strong>HOMME</strong></td>
                            <td class="text-center"><strong>FEMME</strong></td>
                            <td class="text-center"><strong>TOTAL</strong></td>
                        </tr>
                        {{-- @dd($brazzaf) --}}
                        <tr>
                            <td class="text-center">BRAZZAVILLE</td>
                            <td class="text-center">{{ $brazzah[0]->GENRE }}</td>
                            <td class="text-center">{{ $brazzaf[0]->GENRE }}</td>
                            <td class="text-center">{{ $brazzah[0]->GENRE + $brazzaf[0]->GENRE }}</td>
                        </tr>
                        <tr>
                            <td class="text-center">POINTE-NOIRE</td>
                            <td class="text-center">{{ $pnh[0]->GENRE }}</td>
                            <td class="text-center">{{ $pnf[0]->GENRE }}</td>
                            <td class="text-center">{{ $pnh[0]->GENRE + $pnf[0]->GENRE }}</td>
                        </tr>
                        <tr>
                            <td class="text-center">LIKOUALA</td>
                            <td class="text-center">{{ $likoualah[0]->GENRE }}</td>
                            <td class="text-center">{{ $likoualaf[0]->GENRE }}</td>
                            <td class="text-center">{{ $likoualah[0]->GENRE + $likoualaf[0]->GENRE }}</td>
                        </tr>
                        <tr>
                            <td class="text-center">SANGHA</td>
                            <td class="text-center">{{ $sanghah[0]->GENRE }}</td>
                            <td class="text-center">{{ $sanghaf[0]->GENRE }}</td>
                            <td class="text-center">{{ $sanghah[0]->GENRE + $sanghaf[0]->GENRE }}</td>
                        </tr>
                        <tr>
                            <td class="text-center">CUVETTE-OUEST</td>
                            <td class="text-center">{{ $cuvetteoh[0]->GENRE }}</td>
                            <td class="text-center">{{ $cuvetteof[0]->GENRE }}</td>
                            <td class="text-center">{{ $cuvetteoh[0]->GENRE + $cuvetteof[0]->GENRE }}</td>
                        </tr>
                        <tr>
                            <td class="text-center">CUVETTE</td>
                            <td class="text-center">{{ $cuvetteh[0]->GENRE }}</td>
                            <td class="text-center">{{ $cuvettef[0]->GENRE }}</td>
                            <td class="text-center">{{ $cuvetteh[0]->GENRE + $cuvettef[0]->GENRE }}</td>
                        </tr>
                        <tr>
                            <td class="text-center">PLATEAUX</td>
                            <td class="text-center">{{ $plateauh[0]->GENRE }}</td>
                            <td class="text-center">{{ $plateauf[0]->GENRE }}</td>
                            <td class="text-center">{{ $plateauh[0]->GENRE + $plateauf[0]->GENRE }}</td>
                        </tr>
                        <tr>
                            <td class="text-center">POOL</td>
                            <td class="text-center">{{ $poolh[0]->GENRE }}</td>
                            <td class="text-center">{{ $poolf[0]->GENRE }}</td>
                            <td class="text-center">{{ $poolh[0]->GENRE + $poolf[0]->GENRE }}</td>
                        </tr>
                        <tr>
                            <td class="text-center">LEKOUMOU</td>
                            <td class="text-center">{{ $lekoumouh[0]->GENRE }}</td>
                            <td class="text-center">{{ $lekoumouf[0]->GENRE }}</td>
                            <td class="text-center">{{ $lekoumouh[0]->GENRE + $lekoumouf[0]->GENRE }}</td>
                        </tr>
                        <tr>
                            <td class="text-center">BOUENZA</td>
                            <td class="text-center">{{ $bouenzah[0]->GENRE }}</td>
                            <td class="text-center">{{ $bouenzaf[0]->GENRE }}</td>
                            <td class="text-center">{{ $bouenzah[0]->GENRE + $bouenzaf[0]->GENRE }}</td>
                        </tr>
                        <tr>
                            <td class="text-center">NIARI</td>
                            <td class="text-center">{{ $niarih[0]->GENRE }}</td>
                            <td class="text-center">{{ $niarif[0]->GENRE }}</td>
                            <td class="text-center">{{ $niarih[0]->GENRE + $niarif[0]->GENRE }}</td>
                        </tr>
                        <tr>
                            <td class="text-center">KIOULOU</td>
                            <td class="text-center">{{ $kouilouh[0]->GENRE }}</td>
                            <td class="text-center">{{ $kouilouf[0]->GENRE }}</td>
                            <td class="text-center">{{ $kouilouh[0]->GENRE + $kouilouf[0]->GENRE }}</td>
                        </tr>
                    </table>

                </div>

            </div>
        </div>



    </div>
</div>

@endsection
