@extends("layout.app")
@section("titre")
    Déclaration tardive décès
@endsection
@section("sous-titre")
    Déclaration décès
@endsection
@section("styles")
<!-- Form step -->
<link href="{{ asset('tpl/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css') }}" rel="stylesheet">
<!-- Daterange picker -->
<link href="{{ asset('tpl/vendor/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
<!-- Clockpicker -->
<link href="{{ asset('tpl/vendor/clockpicker/css/bootstrap-clockpicker.min.css') }}" rel="stylesheet">
<!-- asColorpicker -->
<link href="{{ asset('tpl/vendor/jquery-asColorPicker/css/asColorPicker.min.css') }}" rel="stylesheet">
<!-- Material color picker -->
<link href="{{ asset('tpl/vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
<!-- Pick date -->
<link href="{{ asset('tpl/wizard/assets/node_modules/wizard/steps.css') }}" rel="stylesheet">
    <!--alerts CSS -->
    <link href="{{ asset('tpl/wizard/assets/node_modules/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endsection
@section("corps")

        <!-- row -->
        <div class="row" id="validation">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Créer une déclaration tardive de décès</h4>
                    </div>
                    <div class="card wizard-content">
                        <div class="card-body">



                            <form id="formdata" class="validation-wizard wizard-circle">
                                @csrf
                                <h4 id="texte"></h4>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Date de décès <span class="text-danger">*</span></label>
                                    <input type="date" name="date_deces" max="<?php echo date("Y-m-d"); ?>" onchange="age()" class="form-control" id="date_deces">
                                    <button type="submit"><span style="color:red;font-size: 15px;"><div class="validate"></div></span></button>
                                </div>
                            </form>
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
                                                    <th>Défunt: Nom</th>
                                                    <th>Défunt: Prénom</th>
                                                    <th>Défunt: Date du décès</th>
                                                    <th>Défunt: Sexe</th>
                                                    <th>Statut</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php $i = 1; ?>
                                                @foreach ($certificats as $certificat)
                                                <tr width="100%">
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{ $certificat->declarant->nom . ' '.$certificat->declarant->prenom }}</td>
                                                    <td>{{ $certificat->defunt->nom }}</td>
                                                    <td>{{ $certificat->defunt->prenom }}</td>
                                                    <td>{{ date("d-m-Y", strtotime($certificat->date_heure_deces)) }}</td>
                                                    <td>{{ $certificat->defunt->sexe == "M" ? "Masculin" : "Féminin" }}</td>
                                                    @if($certificat->mouvements()->get("statut")->last()->statut == "En cours")
                                                    <td><span class="badge light badge-danger" style="font-size: 13px;font-weight:600;">{{ $certificat->mouvements()->get("statut")->last()->statut }} de saisie</span></td>
                                                    @endif
                                                    @if($certificat->mouvements()->get("statut")->last()->statut == "Envoyée")
                                                    <td><span class="badge light badge-success" style="font-size: 13px;font-weight:600;">Transférée à l'institution supérieure </span></td>
                                                    @endif
                                                    <td>
                                                        @if($certificat->mouvements()->get("statut")->last()->statut == "En cours" || $certificat->mouvements()->get("statut")->last()->statut == "Renvoyée")
                                                        <div class="btn-group btn-group-xs">
                                                            <a href="{{ $certificat->code_declaration_deces }}" class="btn btn-warning show-to-send shadow btn-xs sharp me-1" title="Envoyer" ><i class="fas fa-plane"></i></a>
                                                        </div>
                                                        @endif
                                                        <div class="btn-group btn-group-xs">
                                                            <a href="{{ route('declarationDeces.etat',$certificat->code_declaration_deces) }}" target="_blank" class="btn btn-warning shadow btn-xs sharp me-1"><i class="fas fa-print"></i></a>

                                                            @if($certificat->acte !== null)
                                                            <a href="{{ route('acteDeces.display',$certificat->code_declaration_deces) }}" target="_blank" class="btn btn-success shadow btn-xs sharp me-1" title="Voir l'acte"><i class="fas fa-eye"></i></a>
                                                            @endif

                                                            <a href="{{ route('declarationDeces.edit',$certificat->code_declaration_deces) }}" class="btn btn-info shadow btn-xs sharp me-1"><i class="fas fa-pencil-alt"></i></a>

                                                            <form  action="{{ route('declarationDeces.destroy',$certificat->code_declaration_deces) }}" method="POST" style="display: inline-block">
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
                                                    <th>Défunt: Nom</th>
                                                    <th>Défunt: Prénom</th>
                                                    <th>Défunt: Date du décès</th>
                                                    <th>Défunt: Sexe</th>
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
                        {{-- <input type="hidden" id="code_declaration_deces"> --}}
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

<script src="{{ asset('tpl/vendor/jquery-smartwizard/dist/js/jquery.smartWizard.js') }}"></script>
    <script src="{{ asset('tpl/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
    <!-- Form validate init -->
    <script src="{{ asset('tpl/js/plugins-init/jquery.validate-init.js') }}"></script>

     <!-- Daterangepicker -->
     <script src="{{ asset("tpl/js/plugins-init/bs-daterange-picker-init.js") }}"></script>
     <!-- Clockpicker init -->
     <script src="{{ asset("tpl/js/plugins-init/clock-picker-init.js") }}"></script>
     <!-- asColorPicker init -->
     <script src="{{ asset("tpl/js/plugins-init/jquery-asColorPicker.init.js") }}"></script>
     <!-- Material color picker init -->
     <script src="{{ asset("tpl/js/plugins-init/material-date-picker-init.js") }}"></script>
     <!-- Pickdate -->
     <script src="{{ asset("tpl/js/plugins-init/pickadate-init.js") }}"></script>



    <!-- This Page JS -->
    <script src="{{ asset("tpl/wizard/assets/node_modules/wizard/jquery.steps.min.js") }}"></script>
    <script src="{{ asset("tpl/wizard/assets/node_modules/wizard/jquery.validate.min.js") }}"></script>
    <script src="{{ asset("tpl/wizard/assets/node_modules/sweetalert2/dist/sweetalert2.min.js") }}"></script>

    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

 <script>

    function datesDiff(a, b) {
        a = a.getTime();
        b = (b || new Date()).getTime();
        var c = a > b ? a : b,
            d = a > b ? b : a;
        return Math.abs(Math.ceil((c - d) / 86400000));
    }

    // function age() {
    //     var dateDeces = $("#date_deces").val();
    //     var date_debut = new Date(dateDeces);
    //     var date_fin = new Date();
    //     var nbreJour = 0;
    //     nbreJour = datesDiff(date_debut, date_fin);
    //     validateDeclaration(nbreJour,dateDeces);
    // }

    // function validateDeclaration(nbreJour,inputs){
    //     $('#texte').html('Nombre de jours sans déclarer : '+parseInt(nbreJour)+' jours');

    //     if (nbreJour > 3 && nbreJour <= 15) {

    //         $("#formdata").attr("action", "{{ route('declarationDeces.tardive') }}");
    //         $("#formdata").attr("method", "GET");

    //         $("#resultat").html("Cliquer sur ce lien pour créer une déclaration tardive: Le délais de déclaration est supérieur à 2 jours");
    //         console.log("c'est une déclaration tardive qui ne necessite pas une réquisition du parquet.");
    //     }
    //     if (nbreJour > 15) {

    //         $("#formdata").attr("action", "{{ route('certificatNonInscriptionDeces.create') }}");
    //         $("#formdata").attr("method", "GET");

    //         $("#resultat").html("Cliquer sur ce lien pour créer un certificat de non inscription: Le délais de déclaration est supérieur à 15 jours, une réquisition ou un jugement du parquet est requis");
    //         console.log("c'est une déclaration tardive qui ne necessite pas une réquisition du parquet.");

    //     }
    // }

    function calculAge(datenais){
        // var datechoisie = $("#date_naissance_enfant").val();
        var datechoisie_convertie = moment(moment(datenais, 'DD-MM-YYYY')).format('YYYY-MM-DD');
        var age_annee = moment().diff(moment(datenais, 'YYYYMMDD'), 'years');
        var age_mois = moment().diff(moment(datenais, 'YYYYMMDD'), 'month');
        console.log("L'age de l'enfant est: = "+age_annee);
    }


    function age() {
        var dateDeces = $("#date_deces").val();
        var deces_annee = 0;
        var deces_mois = 0;
        var datechoisie_convertie = moment(moment(dateDeces, 'DD-MM-YYYY')).format('YYYY-MM-DD');
        var deces_annee = moment().diff(moment(dateDeces, 'YYYYMMDD'), 'years');
        var deces_mois = moment().diff(moment(dateDeces, 'YYYYMMDD'), 'month');
        var deces_day = moment().diff(moment(dateDeces, 'YYYYMMDD'), 'day');
        validateDeclaration(deces_day,deces_mois,deces_annee);

    }

    function validateDeclaration(deces_day,deces_mois,deces_annee){
        if(deces_day > 15){
            $('#texte').html('Nombre de jours sans déclarer : '+deces_day+' jours');
            // $("#formdata").attr("action", "{{ route('declarationDeces.tardive') }}");
            $("#formdata").attr("action", "{{ route('certificatNonInscriptionDeces.create') }}");

            $("#formdata").attr("method", "POST");
            // $(".validate").html("Cliquer sur ce lien pour créer un certificat de non inscription: Le délais de déclaration est supérieur à 3 mois, une réquisition est réquise");
            $(".validate").html("Cliquer sur ce lien pour créer un certificat de non inscription conformement à l'article 80 du code de la famille: Le délais de déclaration étant supérieur à 15 jous, une réquisition est réquise conformement à l'article xx du code de la famille");
            // console.log("c'est une déclaration tardive qui ne necessite pas une réquisition du parquet.");
        }
        // if(deces_mois > 3){
        //     $('#texte').html('Nombre de mois sans déclarer : '+deces_mois+' mois');
        //     // $("#formdata").attr("action", "{{ route('declarationDeces.tardive') }}");
        //     $("#formdata").attr("action", "{{ route('certificatNonInscriptionDeces.create') }}");

        //     $("#formdata").attr("method", "POST");
        //     // $(".validate").html("Cliquer sur ce lien pour créer un certificat de non inscription: Le délais de déclaration est supérieur à 3 mois, une réquisition est réquise");
        //     $(".validate").html("Cliquer sur ce lien pour créer un certificat de non inscription: Le délais de déclaration est supérieur à 3 mois, une réquisition est réquise");
        //     // console.log("c'est une déclaration tardive qui ne necessite pas une réquisition du parquet.");
        // }
        else{
            $('#texte').html('Nombre de jour sans déclarer : '+deces_day+' jour(s)');
            $("#formdata").attr("action", "#");
            $("#formdata").attr("method", "POST");
            // $(".validate").html("Cliquer sur ce lien pour créer une déclaration de décès.");
        }
    }

    $(function(){
        $("a.show-to-send").on("click", function(){

            var codeDeclaration = $(this).attr('href');

            $("#code_declaration_deces").val(codeDeclaration);
            $("#codedeclaration").val(codeDeclaration);

            $("#modal-declaration-send").modal("show");
            return false;
        });

        $("#btn-send").on("click",function(){
            var cdd = $("#codedeclaration").val();
            var route = "{{ route('declarationDeces.mouvement') }}";
            var data = {
                code_declaration_deces:cdd
            };

            $(this).attr("disabled",true);
            $(this).html("Traitement en cours ...");
            $.post(route, data, function(response){

                if(response.code == "200"){
                    // notification("success",response.message);
                    flashAlert("Réponse","success",response.message);
                    $("#modal-declaration-send").modal('hide');
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }else{
                    // notification("error",response.message);
                    flashAlert("Réponse","error",response.message);
                }
            });

            return false;
        });
    });
</script>


@endsection
