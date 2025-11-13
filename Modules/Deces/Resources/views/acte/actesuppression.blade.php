@extends('layout.app')
@section('titre')
Déclarations de naissance
@endsection
@section("styles")
<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
@endsection
@section('sous-titre')
    Annulation de l'acte
@endsection
@section('corps')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Annulation de l'acte</h4>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>N° acte</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Date naissance</th>
                                        <th>Sexe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr width="100%">
                                        <td>{{ $acte->declaration->acte != null ? $acte->declaration->acte->niupp : "//" }}</td>
                                        <td>{{ $acte->declaration->enfant->nom }}</td>
                                        <td>{{ $acte->declaration->enfant->prenom }}</td>
                                        <td>{{ date("d-m-Y", strtotime($acte->declaration->enfant->date_naissance)) }}</td>
                                        <td>{{ $acte->declaration->enfant->sexe == "M" ? "Masculin" : "Féminin" }} </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form class="form row" action="{{ route('acteNaissance.validersuppression', $acte->niupp) }}" method="POST">
                    @csrf
                    @method("PUT")
                    <div class="mb-2 col-md-4">
                        <label class="form-label">N° du jugement <span class="text-danger">*</span></label>
                        <input type="text" name="num_jugement" class="form-control" required>
                    </div>
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Tribunal <span class="text-danger">*</span></label>
                        @isset($tgis)
                        <select id="tribunal" name="tribunal" class="form-control" required>
                            <option value="" disabled selected>Selectionner</option>
                            @if (count($tgis)>0)
                                @foreach ($tgis as $tgi)
                                    <option value="{{$tgi->code_institution }}">{{$tgi->lib_institution }}</option>
                                @endforeach
                            @endif
                        </select>
                        @endisset
                    </div>
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Motif d'annulation <span class="text-danger">*</span></label>
                        <input type="text" name="motif" class="form-control" required>
                    </div>
                    <div class="mb-2 col-md-4">
                        <input type="submit" class="btn btn-danger btn-lg" value="Valider">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@section("scripts")

<!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>



@endsection
