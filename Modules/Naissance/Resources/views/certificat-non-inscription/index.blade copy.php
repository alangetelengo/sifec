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
    Liste des déclarations de naissance
@endsection
@section('corps')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Liste des déclarations de naissance</h4>
            </div>
            <div class="card-body">
                <form id="formdata" class="validation-wizard wizard-circle">
                    @csrf
                    <h4 id="texte"></h4>
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Date de naissance de l'enfant <span class="text-danger">*</span></label>
                        <input type="date" name="date_naissance_enfant" max="<?php echo date("Y-m-d"); ?>" onchange="age()" class="form-control" id="date_naissance_enfant">
                        <button type="submit"><span style="color:red;font-size: 15px;"><div class="validate"></div></span></button>
                    </div>
                </form>
                {{-- <div class="card-header"> --}}
                {{-- </div> --}}
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Déclarant</th>
                                        <th>Enfant: Nom</th>
                                        <th>Enfant: Prénom</th>
                                        <th>Enfant: Date naissance</th>
                                        <th>Enfant: Sexe</th>
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($certificats as $certificat)
                                    <tr width="100%">
                                        <td>{{ $certificat->numero_certificat }}</td>
                                        <td>{{ $certificat->declarant->nom.' '.$certificat->Declarant->prenom }}</td>
                                        <td>{{ $certificat->enfant->nom }}</td>
                                        <td>{{ $certificat->enfant->prenom }}</td>
                                        <td>{{ date("d-m-Y", strtotime($certificat->enfant->date_naissance)) }}</td>
                                        <td>{{ $certificat->enfant->sexe == "M" ? "Masculin" : "Féminin" }}</td>

                                        @if($certificat->mouvements()->get("statut")->last()->statut == "En cours")
                                        <td><span class="badge light badge-danger" style="font-size: 13px;font-weight:600;">{{ $certificat->mouvements()->get("statut")->last()->statut }} de saisie</span></td>
                                        @endif
                                        @if($certificat->mouvements()->get("statut")->last()->statut == "Envoyée")
                                        <td><span class="badge light badge-success" style="font-size: 13px;font-weight:600;">Transférée à l'institution supérieure </span></td>
                                        @endif
                                        <td>
                                            @if($certificat->mouvements()->get("statut")->last()->statut == "En cours" || $certificat->mouvements()->get("statut")->last()->statut == "Renvoyée")
                                            <div class="btn-group btn-group-xs">
                                                <a href="{{ $certificat->code_declaration_naissance }}" class="btn btn-warning show-to-send shadow btn-xs sharp me-1" title="Envoyer" ><i class="fas fa-plane"></i></a>
                                            </div>
                                            @endif
                                            <div class="btn-group btn-group-xs">
                                                <a href="{{ route('certificatNonInscription.etat',$certificat->code_declaration_naissance) }}" target="_blank" class="btn btn-warning shadow btn-xs sharp me-1" title="Voir"><i class="fas fa-print"></i></a>

                                                <a href="{{ route('declarationNaissance.edit',$certificat->code_declaration_naissance) }}" class="btn btn-info shadow btn-xs sharp me-1"><i class="fas fa-pencil-alt"></i></a>
                                               <form  action="{{ route('declarationNaissance.destroy',$certificat->code_declaration_naissance) }}" method="POST" style="display: inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Déclarant</th>
                                        <th>Enfant: Nom</th>
                                        <th>Enfant: Prénom</th>
                                        <th>Enfant: Date naissance</th>
                                        <th>Enfant: Sexe</th>
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- DEBUT ENVOIS DECLARATION --}}
<div class="modal fade" id="modal-declaration-send" data-bs-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span class="module-title"> </span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mb-2 col-md-12">
                        {{-- <input type="hidden" id="code_declaration_naissance"> --}}
                        <label class="form-label">Transmission de la déclaration N°</label>
                        <input type="text" readonly class="form-control"  placeholder="" id="codedeclaration">
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info btn-sm text-white" id="btn-send">Envoyer</button>
                <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
{{-- FIN ENVOIS DECLARATION --}}
@endsection
@section("scripts")

<!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>
 <script>

    $("#noninscript").hide();
    $("#inscript").hide();

    function calculAge(datenais){
        // var datechoisie = $("#date_naissance_enfant").val();
        var datechoisie_convertie = moment(moment(datenais, 'DD-MM-YYYY')).format('YYYY-MM-DD');
        var age_annee = moment().diff(moment(datenais, 'YYYYMMDD'), 'years');
        var age_mois = moment().diff(moment(datenais, 'YYYYMMDD'), 'month');
        console.log("L'age de l'enfant est: = "+age_annee);
    }

    // function datesDiff(a, b) {
    //     a = a.getTime();
    //     b = (b || new Date()).getTime();
    //     var c = a > b ? a : b,
    //         d = a > b ? b : a;
    //     return Math.abs(Math.ceil((c - d) / 86400000));
    // }

    function age() {
        var dateNaissance = $("#date_naissance_enfant").val();


        // var date_debut = new Date(dateNaissance);
        // var date_fin = new Date();
        // var nbreJour = 0;
        // nbreJour = datesDiff(date_debut, date_fin);
        // validateDeclaration(nbreJour,dateNaissance);
        // calculAge(dateNaissance);
        // console.log("L'age de l'enfant est: ="+calculerAge(dateNaissance));
        var age_annee = 0;
        var age_mois = 0;
        var datechoisie_convertie = moment(moment(dateNaissance, 'DD-MM-YYYY')).format('YYYY-MM-DD');
        var age_annee = moment().diff(moment(dateNaissance, 'YYYYMMDD'), 'years');
        var age_mois = moment().diff(moment(dateNaissance, 'YYYYMMDD'), 'month');
        validateDeclaration(age_mois,age_annee);

    }

    function validateDeclaration(age_mois,age_annee){
        if (age_annee < 15) {

            if(age_mois > 3){
                $('#texte').html('Nombre de mois sans déclarer : '+age_mois+' mois');
                $("#formdata").attr("action", "{{ route('certificatNonInscription.create') }}");
                $("#formdata").attr("method", "POST");
                $(".validate").html("Cliquer sur ce lien pour créer un certificat de non inscription: Le délais de déclaration est supérieur à 3 mois, une réquisition est requise");
                // console.log("c'est une déclaration tardive qui ne necessite pas une réquisition du parquet.");
            }

        }
        else if(age_annee > 14){
            $('#texte').html('Nombre d\'année sans déclarer : '+age_annee+' ans');
            $("#formdata").attr("action", "{{ route('certificatNonInscription.create') }}");
            $("#formdata").attr("method", "POST");
            $(".validate").html("Cliquer sur ce lien pour créer un certificat de non inscription: Le délais de déclaration est supérieur à 15 ans, un jugement supplétif est requise");
            // console.log("c'est une déclaration tardive qui ne necessite pas une réquisition du parquet.");
        }
        else{
            $("#texte").html("")
            $(".validate").html("")
        }
    }
</script>


@endsection
