@extends('layout.app')
@section('titre')
Actes de mariage
@endsection
@section("styles")

<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endsection

@section('corps')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Liste des actes de mariage</h4>
                <div class="row">
                    <div id="dupcreer">
                        @can('module.acteMariage.generate')
                        <button class="btn btn-sm btn-primary mb-2 generate-actes d-none">Générer les actes</button>
                        @endcan
                        @can('module.acteMariage.signature')
                        <button class="btn btn-sm btn-primary mb-2 validate-actes d-none">Valider les actes</button>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th><label for="check-all"><input type="checkbox" id="check-all"></label></th>
                                        <th>Identité Epoux</th>
                                        <th>Identité Epouse</th>
                                        <th>Régime</th>
                                        <th>Date déclaration mariage</th>
                                        <th>Date prévue mariage</th>
                                        <th>Option mariage</th>
                                        <th>Statut: Déclaration</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i=1;
                                    @endphp
                                    @forelse ($declarations as $dm)
                                        <tr>
                                            <td>{{ $i++ }}
                                                <label for="single-check"><input type="checkbox" name="declaration[]" class="single-check" value="{{ $dm->code_declaration_mariage }}-{{ $dm->acte == null ? '0' : '1' }}"></label>

                                            </td>
                                            <td>{{ $dm->epoux->nomcomplet() }}</td>
                                            <td>{{ $dm->epouse->nomcomplet() }}</td>
                                            <td>{{ $dm->regime->lib_regime }}</td>
                                            <td>{{ date("d-m-Y", strtotime($dm->date_declaration_mariage)) }}</td>
                                            <td>{{ date("d-m-Y", strtotime($dm->date_prevue_mariage)) }}</td>
                                            <td>{{ $dm->optionMariage->lib_option_mariage }}</td>
                                            <td>
                                                @if($dm->acte ==null)
                                                    <span class="badge light badge-danger light sharp" style="font-size: 13px;font-weight:600;">En attente de production de l'acte</span>
                                                @endif
                                                @if($dm->acte != null && $dm->acte->approbation_tribunal == 1 && $dm->acte->approbation_mairie == null)
                                                    <span class="badge light badge-warning light sharp" style="font-size: 13px;font-weight:600;">En attente d'approbation de l'officier d'état civil</span>
                                                @endif

                                                @if($dm->acte != null && $dm->acte->approbation_tribunal == 1 && $dm->acte->approbation_mairie != null)
                                                    <span class="badge light badge-success light sharp" style="font-size: 13px;font-weight:600;">Acte produit</span>
                                                 @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-primary light sharp" data-bs-toggle="dropdown">
                                                        <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        @if($dm->acte != null)
                                                            <a class="dropdown-item" href="{{ route('acteMariage.display',$dm->code_declaration_mariage) }}" target="_blank">Voir l'acte</a>
                                                        @else
                                                            <a class="dropdown-item show-acte-modal" href="{{ $dm->code_declaration_mariage }}">Générer acte</a>
                                                            <a class="dropdown-item" href="{{ route('etatMariage.declaration',$dm->code_declaration_mariage) }}" target="_blank">Voir le formulaire type</a>
                                                        @endif
                                                        @if($dm->acte != null && $dm->acte->approbation_tribunal == 1 && $dm->acte->approbation_mairie == 0)
                                                            <form action="{{ route('acteMariage.mariage.approuver',$dm->acte->code_acte_mariage) }}" method="post">
                                                                @csrf
                                                                @method("PUT")
                                                                <button type="submit" class="dropdown-item" >Valider</button>
                                                            </form>
                                                        @endif

                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <span class="">Aucune donnée trouvée</span>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Identité Epoux</th>
                                        <th>Identité Epouse</th>
                                        <th>Régime</th>
                                        <th>Date déclaration mariage</th>
                                        <th>Date prévue mariage</th>
                                        <th>Option mariage</th>
                                        <th>Statut: Déclaration</th>
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


    {{-- GENERATE ACTE DE MARIAGE --}}
    <div class="modal fade" id="modal-acte" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span class="module-title">  </span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-12 text-center">
                            <h4 id="certificat"></h4>
                        </div>
                        @if($registre != null)
                            <div class="mb-2 col-md-6 cacher">
                                <label class="form-label">Régistre <span class="text-danger">*</span></label>
                                <input id="code_registre" type="text" class="form-control" class="form-control" readonly value="{{ $registre->code_registre }}">
                            </div>
                            <div class="mb-2 col-md-6 cacher">
                                <label class="form-label">Numéro déclaration mariage <span class="text-danger">*</span></label>
                                <input id="code_declaration_mariage" type="text" class="form-control" class="form-control">
                            </div>
                        @else
                        <label class="form-label cacher"> <span class="text-danger">Aucun registre disponible</span></label>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-primary light generate cacher">Valider</button>
                    <button type="button" class="btn btn-sm btn-danger light" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>


    {{-- FIN GENERATE ACTE DE MARIAGE --}}

    {{-- DEBUT MODAL RECHERCHE ACTE DE NAISSANCE --}}
    <div class="modal fade" id="modal-search-acte" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rechercher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Nom(s) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control"lass="form-control"  placeholder="" id="nom_recherche" required>

                        </div>

                        <div class="mb-2 col-md-6">
                            <label class="form-label">Prénom(s)</label>
                            <input type="text" class="form-control"  placeholder="" id="prenom_recherche">

                        </div>
                    </div>
                    <div class="row">

                        <div class="mb-2 col-md-6">
                            <label class="form-label">Lieu de naissance </label>
                            <input type="tel" class="form-control"  id="lieu_recherche">

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info text-white" id="rechercher">Rechercher</button>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Résultat de la recherche</h4>
                                </div>
                                <div class="card-body">
                                    <div id="resultatrech"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    {{-- FIN MODAL RECHERCHE ACTE DE NAISSACE --}}

    {{-- DEBUT MODAL VALIDATION ACTE DE MARIAGE --}}
    <div class="modal fade" id="modal-validate-acte" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Validation de l'acte de mariage</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-2 col-md-6">
                            <input type="hidden" id="code_declaration_mariage">
                            <input type="hidden" id="validation_type">
                            <label class="form-label">Code de validation<span class="text-danger">*</span></label>
                            <input type="text" class="form-control"lass="form-control"  placeholder="" id="otp_approbation_mairie" required>
                        </div>

                        <span class="text-success"><i>Veuillez saisir le code de validation reçu par SMS.</i> Code non reçu ? <a href="#" class="resend_otp">Renvoyez le code OTP</a></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info btn-sm text-white" id="btn-validate">Valider</button>
                    <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    {{-- FIN MODAL VALIDATION ACTE DE MARIAGE --}}
@endsection
@section("scripts")
<!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>
    <script>

        $(function()
        {
            // notification("error","Je suis un message d'erreur");
            // flashAlert("Réponse","error","<h2>Je suis un message d'erreur</h2>");
            var codes = [];
            var actesGeneres = [];
            var actesNonGeneres = [];
            $("a.show-acte-modal").on("click",function(){
                var me = $(this);
                var action = me.attr("href");
                var modal = $("#modal-acte");

                $("#code_declaration_mariage").val(action).attr("readonly",true);
                modal.modal("show");

                return false;

            });

            $("button.generate").on("click", function(){
                    var code_registre = $("#code_registre").val();
                    var code_declaration_mariage = $("#code_declaration_mariage").val();
                    var route = "{{ route('acteMariage.generate') }}";


                    $.post(route, {code_declaration_mariage:code_declaration_mariage}, function(reponse){
                        if(reponse.code == "200"){
                            $("#modal-acte").modal("hide");
                        console.log("success",reponse.message.success);

                        var url = "{{ route('acteMariage.display',':id') }}";
                        url = url.replace(':id',code_declaration_mariage);
                        notification("success",reponse.message.reponse);
                        setTimeout(() => {
                            window.open(url);
                        }, 3000);

                    }else{
                        var errors = reponse.message;
                        if(errors.length > 0){

                            console.log("erros",errors);
                        }
                    }
                },"json");

                return false;
            });

            $("#check-all").on("change", function(){
                if($(this).is(":checked")){
                    $(".single-check").attr("checked",true);

                    $(".single-check").each(function(){
                        if($(this).is(":checked")){
                            codes.push($(this).val().substr(0,12));

                            if(parseInt($(this).val().substr(13)) == 1){
                                actesGeneres.push($(this).val().substr(0,12));
                                // console.log(actesGeneres)
                            }else{
                                actesNonGeneres.push($(this).val().substr(0,12));
                            }

                        }

                    });
                }else{
                    $(".single-check").attr("checked",false);
                    $(".single-check").each(function(){
                        var c = $(this).val();

                        if(codes.length > 0){
                            var codeDejas = codes.filter(function(elem){
                                return elem != c;
                            });
                            codes = codeDejas
                        }

                    });
                }

                // if(actesGeneres.length > 0 && actesNonGeneres.length > 0){
                //     $("button.generate-actes").removeClass("d-none");
                //     $("button.validate-actes").addClass("d-none");
                // }else if(actesGeneres.length > 0 && actesNonGeneres.length == 0){
                //     $("button.validate-actes").removeClass("d-none");
                //     $("button.generate-actes").addClass("d-none");
                // }else if(actesGeneres.length == 0 && actesNonGeneres.length > 0){
                //     $("button.generate-actes").removeClass("d-none");
                //     $("button.validate-actes").addClass("d-none");
                // }else{
                //     $("button.generate-actes").removeClass("d-none");
                //     $("button.validate-actes").removeClass("d-none");
                // }

                if(actesGeneres.length > 0 && actesNonGeneres.length > 0){
                    $("button.generate-actes").removeClass("d-none");
                    $("button.validate-actes").addClass("d-none");

                }else if(actesGeneres.length > 0 && actesNonGeneres.length == 0){
                    $("button.validate-actes").removeClass("d-none");
                    $("button.generate-actes").addClass("d-none");

                }else if(actesGeneres.length == 0 && actesNonGeneres.length > 0){
                    $("button.generate-actes").removeClass("d-none");
                    $("button.validate-actes").addClass("d-none");

                }else{
                    $("button.generate-actes").removeClass("d-none");
                    $("button.validate-actes").removeClass("d-none");

                }

            });

            $("button.generate-actes").on("click",function(){

                if(actesNonGeneres.length > 0){
                    generateActeMultiple(actesNonGeneres);
                }
                return false;
            });


            $("button.validate-actes").on("click",function(){

                if(codes.length > 0){
                    sendOtpMultiple(codes);
                }
                return false;
            });



            $("#btn-validate").on("click",function(){

            var code_declaration_mariage = $("#code_declaration_mariage").val();
            var validation_type = $("#validation_type").val();
            var otp_approbation_mairie = $("#otp_approbation_mairie").val();

            console.log(validation_type);

            var inputs = {
                codes : actesGeneres,
                code_declaration_mariage:code_declaration_mariage,
                otp_approbation_mairie:otp_approbation_mairie
            };

            validateActes(validation_type,inputs,$(this));

            return false;
         });




        });

        function sendOtpMultiple(codes){
            $(".over-loader-page").fadeIn(600);
            var url = "{{ route('acteMariage.send.otp.bulk') }}";
            var data = {codes:codes};
            $.post(url,data,function(response){
                console.log(response);
                if(response.code == "200"){

                    $(".over-loader-page").fadeOut(600);
                    $("#validation_type").val("bulk");

                    $("#modal-validate-acte").modal('show');

                }else{
                    $(".over-loader-page").fadeOut(600);
                    //notification("error",response.message);
                    var outString = "<ul>";
                    for (const [key, value] of Object.entries(response.message))
                    {
                    outString+= `<li style='text-align:left;color:red; list-style:disc !important; font-size:12px'>${value}</li>`;
                    }
                    outString += "</ul>";
                    flashAlert("Une erreur est suvernue","error",outString);
                }
            });
        }

        function generateActeMultiple(codes){
            $(".over-loader-page").fadeIn(600);
            var url = "{{ route('acteMariage.generate.bulk') }}";
            var data = {codes:codes};
            $.post(url,data,function(response){
                console.log(response);
                if(response.code == "200"){
                    $(".over-loader-page").fadeOut(600);
                    flashAlert("Résultat","success",response.message.reponse);
                    setTimeout(() => {
                        location.reload();
                    }, 4000);

                }else{
                    $(".over-loader-page").fadeOut(600);
                    //notification("error",response.message);
                    var outString = "<ul>";
                    for (const [key, value] of Object.entries(response.message))
                    {
                    outString+= `<li style='text-align:left;color:red; list-style:disc !important; font-size:12px'>${value}</li>`;
                    }
                    outString += "</ul>";
                    flashAlert("Une erreur est suvernue","error",outString);

                }
            });
        }



        function validateActes(type,inputs,trigger){
        if(type=="simple"){
            if(inputs.code_declaration_mariage == "" || inputs.otp_approbation_mairie == ""){
                alert("Veuillez renseigner le code du formulaire type et le code de validation reçu par SMS");
            }else{
                trigger.attr("disabled",true);
                trigger.html("Traitement en cours ...");
                var url = "{{ route('acteMariage.validate.otp') }}";
                var data = {
                    code_declaration_mariage:inputs.code_declaration_mariage,
                    otp_approbation_mairie:inputs.otp_approbation_mairie
                };

                $.post(url,data,function(response){
                    trigger.attr("disabled",false);
                    trigger.html("Valider");

                    if(response.code == "200"){
                        // notification("success",response.message);
                        flashAlert("Réponse","success",response.message);
                        $("#modal-validate-acte").modal('hide');
                        setTimeout(() => {
                            location.reload();
                        }, 4000);
                    }else{
                        // notification("error",response.message);
                        var outString = "<ul>";
                        for (const [key, value] of Object.entries(response.message))
                        {
                        outString+= `<li style='text-align:left;color:red; list-style:disc !important; font-size:12px'>${value}</li>`;
                        }
                        outString += "</ul>";
                        flashAlert("Une erreur est suvernue","error",outString);
                    }
                });
            }
        }else{
            if(inputs.codes.length == 0 || inputs.otp_approbation_mairie == ""){
                alert("Veuillez renseigner le code de validation reçu par SMS");
            }else{
                trigger.attr("disabled",true);
                trigger.html("Traitement en cours ...");
                var url = "{{ route('acteMariage.validate.otp.bulk') }}";
                var data = {
                    codes:inputs.codes,
                    otp_approbation_mairie:inputs.otp_approbation_mairie
                };

                $.post(url,data,function(response){
                    trigger.attr("disabled",false);
                    trigger.html("Valider");
                    console.log(response);

                    if(response.code == "200"){
                        // notification("success",response.message);
                        flashAlert("Réponse","success",response.message);
                        $("#modal-validate-acte").modal('hide');
                        setTimeout(() => {
                            location.reload();
                        }, 4000);
                    }else{
                        // notification("error",response.message);
                        var outString = "<ul>";
                        for (const [key, value] of Object.entries(response.message))
                        {
                        outString+= `<li style='text-align:left;color:red; list-style:disc !important; font-size:12px'>${value}</li>`;
                        }
                        outString += "</ul>";
                        flashAlert("Une erreur est suvernue","error",outString);
                    }
                });
            }
        }

     }

    </script>

@endsection
