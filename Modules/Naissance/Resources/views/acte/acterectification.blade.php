@extends('layout.app')
@section('titre')
Rectification de l'acte
@endsection
@section("styles")
<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
@endsection
@section('sous-titre')
    Rectification de l'acte
@endsection
@section('corps')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Rectification de l'acte</h4>
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
                                        <th>émis dépuis</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr width="100%">
                                        <td>{{ $acte->declaration->acte != null ? $acte->declaration->acte->niupp : "//" }}</td>
                                        <td>{{ $acte->declaration->enfant->nom }}</td>
                                        <td>{{ $acte->declaration->enfant->prenom }}</td>
                                        <td>{{ date("d-m-Y", strtotime($acte->declaration->enfant->date_naissance)) }}</td>
                                        <td>{{ $acte->declaration->enfant->sexe == "M" ? "Masculin" : "Féminin" }} </td>
                                        <td>{{ $DeferenceInDays }} mois</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form class="form row" action="#" method="POST">
                    @csrf
                    @method("PUT")
                    @if ($DeferenceInDays > 11)
                        <div class="mb-2 col-md-4">
                            <label class="form-label">N° de la réquisition <span class="text-danger">*</span></label>
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
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="daterequisition" class="form-control" required>
                        </div> <br><br>
                    @endif

                    <label for="" class="form-label">Modifier les informations: </label>
                    <hr>
                    <div class="mb-2 col-md-3">
                        <input class="form-check-input" type="checkbox" value="" id="enfant">
                        <label class="form-check-label" for="enfant">
                            Enfant
                        </label>
                    </div>
                    <div class="mb-2 col-md-3">
                        <input class="form-check-input" type="checkbox" value="" id="pere">
                        <label class="form-check-label" for="pere">
                            Père
                        </label>
                    </div>
                    <div class="mb-2 col-md-3">
                        <input class="form-check-input" type="checkbox" value="" id="mere">
                        <label class="form-check-label" for="mere">
                            Mère
                        </label>
                    </div>
                    <div class="mb-2 col-md-3">
                        <input class="form-check-input" type="checkbox" value="" id="declarant">
                        <label class="form-check-label" for="declarant">
                            Déclarant
                        </label>
                    </div>
                    <div class="row" id="bebe">
                        <div class="ligne">
                            <h4>INFORMATIONS SUR L'ENFANT</h4>
                        </div>
                        <div class="mb-2 col-md-4">
                            <label class="form-label">Nom(s) enfant <span class="text-danger">*</span></label>
                            <input type="text" name="nom_enfant"  class="form-control"  placeholder="Nom enfant" id="nom_enfant" onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()" value="{{ $acte->declaration->enfant->nom }}">
                        </div>

                        <div class="mb-2 col-md-4">
                            <label class="form-label">Prénom(s) enfant</label>
                            <input type="text" class="form-control" placeholder="Prénom enfant" id="prenom_enfant" onkeyup="verif_lettre(this);" style="text-transform: capitalize" value="{{ $acte->declaration->enfant->prenom }}">
                        </div>

                        <div class="mb-2 col-md-4">
                            <label class="form-label">Sexe <span class="text-danger">*</span></label>
                            <select id="sexe_enfant" name="sexe_enfant" class="form-control  @error('sexe_enfant') is-invalid @enderror">
                                <option value="M" {{ $acte->declaration->enfant->sexe == "M" ? "selected" : "" }}>Masculin</option>
                                <option value="F" {{ $acte->declaration->enfant->sexe == "F" ? "selected" : "" }}>Féminin</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-2 col-md-4">
                        <input type="submit" class="btn btn-primary btn-lg" value="Continuer">
                    </div>
                    </div>


                </form>
        </div>
    </div>
</div>

@endsection
@section("scripts")

<!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>
    <script>
        var bb = $("#bebe");
        bb.hide();
        // var enfant = $('#enfant').checked(true);

        // if (enfant.checked) {

        // }
        $('#enfant').change(function() {
            const enfantChecked = $('#enfant').is(':checked');
            // alert(enfantChecked);
            if (enfantChecked) {
                bb.show();
            }else{
                bb.hide();
            }
        })
    </script>



@endsection
