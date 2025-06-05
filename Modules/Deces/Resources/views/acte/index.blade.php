@extends('layout.app')
@section('titre')
Actes de décès
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
                <h4>Liste des actes de décès.</h4>
                <div class="row">
                    <div id="dupcreer">
                        @can('module.acteDeces.generate')
                        <button class="btn btn-sm btn-primary mb-2 generate-actes d-none">Générer les actes</button>
                        @endcan
                        @can('module.acteDeces.signature')
                        <button class="btn btn-sm btn-primary mb-2 validate-actes d-none">Valider les actes</button>
                        @endcan
                        {{-- <button class="btn btn-sm btn-primary mb-2  chercheacte">Rechercher la personne</button> --}}
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
                                        <th>N°</th>
                                        <th>Nom & Prénom</th>
                                        <th>Sexe</th>
                                        <th>Date du décès</th>
                                        <th>Lieu du décès</th>
                                        <th>Type</th>
                                        <th>Déclarant</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($declarations as $dd)
                                        @if($dd->mouvements()->get("statut")->last()->statut == "Envoyée")
                                            <tr width="100%">
                                                <td>
                                                    @if($dd->acte == null)
                                                        <label for="single-check"><input type="checkbox"  name="declarations[]" class="single-check"
                                                        value="{{ $dd->code_declaration_deces }}-{{ $dd->acte == null ? "0":"1" }}">
                                                        </label>
                                                    @endif
                                                    @if($dd->acte != null && $dd->acte->approbation_pompe_funebre == 0)
                                                        <label for="single-check"><input type="checkbox"  name="declarations[]" class="single-check"
                                                            value="{{ $dd->code_declaration_deces }}-{{ $dd->acte == null ? "0":"1" }}">
                                                        </label>
                                                    @endif
                                                </td>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $dd->defunt->nom.' '.$dd->defunt->prenom }}</td>
                                                <td>{{ $dd->defunt->sexe == "M" ? "Masculin" : "Féminin" }}</td>
                                                <td>{{ date("d-m-Y", strtotime($dd->date_heure_deces)) }}</td>
                                                <td>
                                                    {{$dd->lieuSurvenance->lib_lieu_survenance}}
                                                </td>
                                                <td>
                                                    {{$dd->type_declaration}}
                                                </td>
                                                <td>{{ $dd->declarant->nom.' '.$dd->declarant->prenom }}</td>

                                                {{-- <td>
                                                    @if($dd->acte != null && $dd->acte->approbation_tribunal == 1 && $dd->acte->signature_pompe_funebre != null && $dd->acte->retirer == true)
                                                        <span class="badge light badge-success" style="font-size: 13px;font-weight:600;">Acte rétiré</span>
                                                    @else
                                                        <span class="badge light badge-warning" style="font-size: 13px;font-weight:600;">Acte non rétiré</span>
                                                    @endif
                                                </td> --}}


                                                @if($dd->mouvements->last()->statut == "Renvoyée")
                                                <td><a href="{{ $dd->code_declaration_deces }}" cmouvtdeces="{{ $dd->mouvements->last()->code_mouvement_deces }}" obs="{{ $dd->mouvements->last()->observation }}" class="show-detail-renvoie" title="{{ $dd->mouvements->last()->motif_renvoi }}">
                                                    <span class="badge light badge-danger" style="font-size: 13px;font-weight:600;">document renvoyé </span></a>
                                                </td>
                                                @endif
                                                @if($dd->acte == null)
                                                <!-- Affichage du statut de la déclaration :: en attente de transcription-->
                                                <td style="width: 15%"><span class="badge light badge-danger light sharp" style="font-size: 13px;font-weight:600;">En attente de transcription de l'acte</span></td>
                                                @endif
                                                @if( $dd->acte != null && $dd->acte->signature_pompe_funebre == null)
                                                <!-- Affichage du statut de la déclaration :: en attente d'approbation du maire-->
                                                    <td style="width: 15%"><span class="badge light badge-warning light sharp" style="font-size: 13px;font-weight:600;">Acte produit et en attente d'approbation de l'officier d'état civil</span></td>
                                                @endif
                                                @if($dd->acte != null && $dd->acte->signature_pompe_funebre != null && $dd->acte->retirer == false)
                                                    <td style="width: 15%">
                                                        <a href="{{ $dd->acte->numeroActe->numero_acte }}" codeactedeces="{{ $dd->acte->code_acte_deces }}" class="badge badge-danger show-acte-retrait-modal">Acte produit non rétiré</a>

                                                        {{-- <span class="badge light badge-danger" style="font-size: 13px;font-weight:600;">Acte non rétiré</span> --}}
                                                    </td>
                                                @endif
                                                @if($dd->mouvements->last()->statut == "Envoyée" && $dd->acte != null && $dd->acte->signature_pompe_funebre != null && $dd->acte->retirer == true)
                                                    <td style="width: 15%"><span class="badge light badge-success" style="font-size: 13px;font-weight:600;">Acte rétiré</span></td>

                                                @endif

                                                <td style="width: 15%">
                                                    <div class="btn-group btn-group-xs">
                                                        @if($dd->acte !== null)
                                                            <a class="btn btn-warning shadow btn-xs sharp me-1" href="{{ route('acteDeces.display',$dd->code_declaration_deces) }}" target="_blank" title="Voir acte"><i class="fa fa-eye"></i></a>
                                                            <a class="btn btn-warning shadow btn-xs sharp me-1" href="{{ route('acteDeces.displayCopie',$dd->code_declaration_deces) }}" target="_blank" title="Voir copie"><i class="fa fa-eye"></i></a>
                                                        @endif
                                                        @if($dd->acte == null)
                                                            <a href="{{ $dd->code_declaration_deces }}" class="btn btn-danger show-to-send-back shadow btn-xs sharp me-1" title="Renvoyer" ><i class="fas fa-plane"></i></a>
                                                            {{-- <a class="btn btn-warning shadow btn-xs sharp me-1" href="{{ route('declarationDeces.edit',$dd->code_declaration_deces) }}" title="Modifier"><i class="fa fa-edit"></i></a> --}}
                                                        @endif
                                                        @if($dd->type_declaration == "AUTORISATION DE TRANSFERT DE DEPOUILLE")
                                                        <a href="{{ route('declarationDeces.autorisationtransfertetat',$dd->code_declaration_deces) }}" target="_blank" class="btn btn-warning shadow btn-xs sharp me-1" title="Voir document"><i class="fas fa-print"></i></a>
                                                        @else
                                                        <a class="btn btn-warning shadow btn-xs sharp me-1" href="{{ route('declarationDeces.etat',$dd->code_declaration_deces) }}" target="_blank" title="Voir document"><i class="fa fa-eye"></i></a>
                                                        @endif
                                                        {{-- <a class="btn btn-warning shadow btn-xs sharp me-1" href="{{ route('declarationDeces.etat',$dd->code_declaration_deces) }}" target="_blank" title="Voir document"><i class="fa fa-eye"></i></a> --}}
                                                        {{-- @can("module.acteDeces.generate")
                                                            @if ($dd->acte == null && $dd->type_declaration == "DECLARATION DE DECES")
                                                                <a class="dropdown-item show-acte-modal" href="{{ $dd->code_declaration_deces }}">Générer acte</a>
                                                            @endif
                                                            @if ($dd->acte == null &&  $dd->type_declaration == "CERTIFICAT DE CONSTATATION DE DECES")
                                                                <a class="dropdown-item show-acte-modal" href="{{ $dd->code_declaration_deces }}">Générer acte</a>
                                                            @endif
                                                            @if ($dd->acte == null &&  $dd->type_declaration == "CERTIFICAT DE TRANSCRIPTION")
                                                                <a class="dropdown-item show-acte-modal" href="{{ $dd->code_declaration_deces }}">Générer acte</a>
                                                            @endif
                                                            @if ($dd->acte == null &&  $dd->type_declaration == "CERTIFICAT DE NON INSCRIPTION")
                                                                <a class="dropdown-item show-acte-modal" href="{{ $dd->code_declaration_deces }}">Générer acte</a>
                                                            @endif
                                                            <a class="btn btn-file shadow btn-xs sharp me-1" href="{{ route('declarationDeces.etat',$dd->code_declaration_deces) }}" target="_blank">Voir document</a>
                                                        @endcan --}}


                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>N°</th>
                                        <th>Nom & Prénom</th>
                                        <th>Sexe</th>
                                        <th>Date du décès</th>
                                        <th>Lieu du décès</th>
                                        <th>Type</th>
                                        <th>Déclarant</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
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




    {{-- DEBUT MODAL GENERER ACTE DE DECES --}}
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
                        @if($registre != null)
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Régistre <span class="text-danger">*</span></label>
                            <input id="code_registre" type="text" class="form-control" class="form-control" readonly value="{{ $registre->code_registre }}">
                        </div>
                        @endif

                        <div class="mb-2 col-md-6">
                            <label class="form-label">Numéro déclaration décès <span class="text-danger">*</span></label>
                            <input id="code_declaration_deces" type="text" class="form-control" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-primary light generate">Valider</button>
                    <button type="button" class="btn btn-sm btn-danger light" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    {{-- FIN MODAL GENERER ACTE DE DECES --}}

    {{-- DEBUT MODAL VALIDATION ACTE DE DECES --}}
    <div class="modal fade" id="modal-validate-acte" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Validation de l'acte de décès</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-2 col-md-6">
                            <input type="hidden" id="code_declaration_deces">
                            <input type="hidden" id="validation_type">
                            <label class="form-label">Code OTP<span class="text-danger">*</span></label>
                            <input type="text" class="form-control"lass="form-control"  placeholder="" id="otp_approbation_pompe_funebre" required>
                        </div>

                        <span class="text-success"><i>Veuillez saisir le code OTP reçu par SMS.</i> Code non reçu ? <a href="#" class="resend_otp">Renvoyez le code OTP</a></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info btn-sm text-white" id="btn-validate">Valider</button>
                    <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    {{-- FIN MODAL VALIDATION ACTE DE DECES --}}


{{-- DEBUT MODAL RETRAIT ACTE DE DECES --}}
<div class="modal fade" id="modal-retrait-acte" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Retrait de l'acte de décès</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mb-2 col-md-6">
                        <label class="form-label">Numéro de l'acte<span class="text-danger">*</span></label>
                        <input type="text" id="code_acte" class="form-control" readonly>
                        <input type="hidden" id="codeactedeces">
                    </div>
                    <div class="mb-2 col-md-6">
                        <label class="form-label">Nom de l'intéressé<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="" id="nom_interesse" required>
                    </div>
                    <div class="mb-2 col-md-6">
                        <label class="form-label">Prénom de l'intéressé<span class="text-danger"></span></label>
                        <input type="text" class="form-control" placeholder="" id="prenom_interesse">
                    </div>
                    <div class="mb-2 col-md-6">
                        <label class="form-label">Téléphone de l'intéressé<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="" id="telephone_interesse" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info btn-sm text-white" id="btn-retrait">Valider</button>
                <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
{{-- FIN MODAL RETRAIT ACTE DE NAISSACE --}}

{{-- DEBUT RENVOIS DECLARATION --}}
<div class="modal fade" id="modal-declaration-send-back" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span class="module-title"> Renvoyer le document</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Document n°</label>
                        <input type="text" readonly class="form-control"  placeholder="" id="codedeclarationback">
                        <input type="hidden" class="form-control" id="codemouvementdeces">
                    </div>

                    <div class="mb-2 col-md-12">
                        <label class="form-label">Motif du renvoi <span class="text-danger">*</span></label>
                        <select id="motif_renvoi" name="motif_renvoi" class="form-control" required>
                            <option value="" disabled selected>Selectionner</option>
                            <option value="erreur materielle">Erreur matérielle</option>
                            <option value="Ajouter nom/prenom">Ajouter nom/prénom</option>
                            <option value="rectifier nom/prenom">Rectifier nom/prénom</option>
                        </select>
                    </div>
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Observation</label>
                        <textarea id="observation" cols="96" rows="5"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info btn-sm text-white btn-send-back" id="btn-send-back">Renvoyer</button>
                <button type="submit" class="btn btn-info btn-sm text-white btn-edit-send-back" id="btn-edit-send-back">Modifier</button>
                <button type="submit" class="btn btn-warning btn-sm text-white btn-delete-send-back" id="btn-delete-send-back">Annuler le renvoie</button>

                <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
{{-- FIN RENVOIS DECLARATION --}}



@endsection

@section("scripts")
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>
    <script>


        $(function()
        {

            $("a.show-acte-retrait-modal").on("click", function(){
                var code_acte = $(this).attr("href");
                var codeactedeces = $(this).attr("codeactedeces");

                $("#code_acte").val(code_acte);
                $("#codeactedeces").val(codeactedeces);
                $("#modal-retrait-acte").modal("show");
                return false;
            });
            $("#btn-retrait").on("click", function(){
                var nominteresse = $("#nom_interesse").val();
                var prenominteresse = $("#prenom_interesse").val();
                var telephoneinteresse = $("#telephone_interesse").val();
                var codeactedeces = $("#codeactedeces").val();
                var donnees = {
                                codeactedeces:codeactedeces,
                                nominteresse:nominteresse,
                                prenominteresse:prenominteresse,
                                telephoneinteresse:telephoneinteresse
                            };
                valideRetraitActe(donnees);
                return false;
            });

            // notification("error","Je suis un message d'erreur");
            // flashAlert("Réponse","error","<h2>Je suis un message d'erreur</h2>");
            var codes = [];
            var actesGeneres = [];
            var actesNonGeneres = [];
            $("a.show-acte-modal").on("click",function(){
             var me = $(this);
             var action = me.attr("href");
             var modal = $("#modal-acte");

             $("#code_declaration_deces").val(action).attr("readonly",true);
             modal.modal("show");

             return false;

         });

          $("button.generate").on("click", function () {

             var code_registre = $("#code_registre").val();
             var code_declaration_deces = $("#code_declaration_deces").val();
             var route = "{{ route('acteDeces.generate') }}";

            var datenaiss = $("#datenaiss").val();
            var datedecl = $("#datedecl").val();
            var numcertif = $("#numcertificat").val();

            var daten = new Date(datenaiss);
            var dated = new Date(datedecl);
            // To calculate the time difference of two dates
            var Difference_In_Time = dated.getTime() - daten.getTime();
            var n_jours = Difference_In_Time / (1000 * 3600 * 24);


            if (n_jours > 30 && numcertif == "") {
                var url = "{{ route('certificatNonInscription.update',':id') }}";
                url = url.replace(':id',code_declaration_deces);
                $("#certificat").html("<p>Vous avez dépassé le délais, veuillez  générer un certificat de non inscription <br> Nombre de jours sans déclarer: "+parseInt(n_jours)+" jours </p> <a href="+url+" class='btn btn-primary'>Générer le certificat<a>");
                $(".cacher").addClass("d-none");
            } else {

                $.post(route, {code_declaration_deces:code_declaration_deces}, function(reponse) {

                if(reponse.code == "200"){
                console.log("success",reponse.message.success);
                $("#modal-acte").modal("hide");
                var url = "{{ route('acteDeces.display',':id') }}";
                url = url.replace(':id',code_declaration_deces);
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
            }

             return false;

         });

        //  Traitement envoi SMS avant validation de l'acte

         $("a.show-validation-modal").on("click",function(){

            var code_declaration_deces = $(this).attr("href");
            var url = "{{ route('acteDeces.send.otp',':id') }}";
            url = url.replace(':id',code_declaration_deces);

            //alert(url);

            $(".over-loader-page").fadeIn(600);

            $.get(url,function(response){
                if(response.code == "200"){
                    $(".over-loader-page").fadeOut(600);
                    $("#code_declaration_deces").val(code_declaration_deces)
                    $("#validation_type").val("simple");
                    $("#modal-validate-acte").modal('show');

                }else{
                    $(".over-loader-page").fadeOut(600);
                    //notification("error",response.message);
                    flashAlert("Réponse","error",response.message);
                }
            });

            return false;
         });

         $("#btn-validate").on("click",function(){
            var code_declaration_deces = $("#code_declaration_deces").val();
            var validation_type = $("#validation_type").val();
            var otp_approbation_pompe_funebre = $("#otp_approbation_pompe_funebre").val();

            var inputs = {
                codes : actesGeneres,
                code_declaration_deces:code_declaration_deces,
                otp_approbation_pompe_funebre:otp_approbation_pompe_funebre
            };

            validateActes(validation_type,inputs,$(this));
            return false;
         });


         function validateActes(type,inputs,trigger){
            if(type=="simple")
            {
                if(inputs.code_declaration_deces == "" || inputs.otp_approbation_pompe_funebre == "")
                {
                    alert("Veuillez renseigner le code déclaration de DECES et le code de validation reçu par SMS");
                }
                else
                {
                    trigger.attr("disabled",true);
                    trigger.html("Traitement en cours ...");
                    var url = "{{ route('acteDeces.validate.otp') }}";
                    var data =
                    {
                        code_declaration_deces:inputs.code_declaration_deces,
                        otp_approbation_pompe_funebre:inputs.otp_approbation_pompe_funebre
                    };

                    $.post(url,data,function(response)
                    {
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
                if(inputs.codes.length == 0 || inputs.otp_approbation_pompe_funebre == ""){
                    alert("Veuillez renseigner le code de validation reçu par SMS");
                }else{
                    trigger.attr("disabled",true);
                    trigger.html("Traitement en cours ...");
                    var url = "{{ route('acteDeces.validate.otp.bulk') }}";
                    var data = {
                        codes:inputs.codes,
                        otp_approbation_pompe_funebre:inputs.otp_approbation_pompe_funebre
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

         $("#check-all").on("change",function(){

            if($(this).is(":checked")){
                $(".single-check").attr("checked",true);

                $(".single-check").each(function(){
                    if($(this).is(":checked")){
                        codes.push($(this).val().substr(0,12));
                        if(parseInt($(this).val().substr(13)) == 1){
                            actesGeneres.push($(this).val().substr(0,12));
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
                    codes = codeDejas;
                }

                });
            }


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

         $("button.validate-actes").on("click",function(){
            $("#otp_approbation_pompe_funebre").val("");
            if(codes.length > 0){
                sendOtpMultiple(codes);
            }
            return false;
         });

         $("button.generate-actes").on("click",function(){
            if(actesNonGeneres.length > 0){
                generateActeMultiple(actesNonGeneres);
            }
            return false;
         });
     });


     function sendOtpMultiple(codes){
        $(".over-loader-page").fadeIn(600);
        var url = "{{ route('acteDeces.send.otp.bulk') }}";
        var data = {codes:codes};
        $.post(url,data,function(response){
            console.log(response);
            if(response.code == "200"){

                $(".over-loader-page").fadeOut(600);
                $("#validation_type").val("bulk");

                $("#modal-validate-acte").modal('show');

            }else{
                $(".over-loader-page").fadeOut(600);
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
        var url = "{{route('acteDeces.generate.bulk') }}";
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


    function valideRetraitActe(inputs){
        var url = "{{ route('acteDeces.retrait') }}";
        var data = {
                    codeactedeces:inputs.codeactedeces,
                    nominteresse:inputs.nominteresse,
                    prenominteresse:inputs.prenominteresse,
                    telephoneinteresse:inputs.telephoneinteresse
                };
        $.post(url,data, function(response){
            if(response.code = "200"){
                flashAlert("Réponse", "success", response.message);
                $("#modal-retrait-acte").modal("hide");
                setTimeout(() => {
                    location.reload();
                }, 3000);
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


/// Before me

           $(function(){
            // flashAlert("Salutation","success","<h1>Bonsoir à topus</h1>");
            $("a.show-acte-modal").on("click",function(){
                var me = $(this);
                var action = me.attr("href");
                var modal = $("#modal-acte");

                $("#code_declaration_deces").val(action).attr("readonly",true);
                modal.modal("show");

                return false;

            });

            $("button.generate").on("click", function () {

                var code_registre = $("#code_registre").val();
                var code_declaration_deces = $("#code_declaration_deces").val();
                var route = "{{ route('acteDeces.generate') }}";

                $.post(route, {code_registre:code_registre,code_declaration_deces:code_declaration_deces}, function(reponse) {

                    if(reponse.code == "200"){
                        console.log("success",reponse.message.success);
                        $("#modal-acte").modal("hide");
                        var url = "{{ route('acteDeces.display',':id') }}";
                        url = url.replace(':id',code_declaration_deces);
                        window.open(url);
                        location.reload();

                    }else{
                        var errors = reponse.message;
                        if(errors.length > 0){
                            console.log("erros",errors);
                        }
                    }

                },"json");

                return false;

            });



            // $("a.show-to-send-back").on("click", function(){

            //     var codeDeclaration = $(this).attr('href');

            //     // $("#code_declaration_deces_back").val(codeDeclaration);
            //     $("#codedeclarationback").val(codeDeclaration);

            //     $("#modal-declaration-send-back").modal("show");
            //     return false;
            // });

            // $("#btn-send-back").on("click",function(){
            //     var cdd = $("#codedeclarationback").val();
            //     var motif = $("#motif_renvoi").val();
            //     var route = "{{ route('declarationDeces.mouvement') }}";
            //     var data = {
            //         code_declaration_deces:cdd,
            //         motif_renvoi:motif
            //     };

            //     $(this).attr("disabled",true);
            //     $(this).html("Traitement en cours ...");
            //     $.post(route, data, function(response){

            //         if(response.code == "200"){
            //             // notification("success",response.message);
            //             flashAlert("Réponse","success",response.message);
            //             $("#modal-declaration-send").modal('hide');
            //             setTimeout(() => {
            //                 location.reload();
            //             }, 2000);
            //         }else{
            //             // notification("error",response.message);
            //             flashAlert("Réponse","error",response.message);
            //         }
            //     });

            //     return false;
            // });


            $("a.show-to-send-back").on("click", function(){

                var codeDeclaration = $(this).attr('href');
                $("#codedeclarationback").val("");
                $("#motif_renvoi").val("");
                $("#observation").val("");

                $("#codedeclarationback").val(codeDeclaration);

                $("button.btn-send-back").removeClass("d-none");
                $("button.btn-edit-send-back").addClass("d-none");
                $("button.btn-delete-send-back").addClass("d-none");
                $("#modal-declaration-send-back").modal("show");
                return false;
            });

            $("#btn-send-back").on("click",function(){
                var cdn = $("#codedeclarationback").val();
                var motif = $("#motif_renvoi").val();
                var observation = $("#observation").val();
                var route = "{{ route('declarationDeces.mouvement') }}";
                var data = {
                    code_declaration_deces:cdn,
                    motif_renvoi:motif,
                    observation:observation
                };

                $.post(route, data, function(response){

                    if(response.code == "200"){
                        // notification("success",response.message);
                        flashAlert("Réponse","success",response.message);
                        $("#modal-declaration-send").modal('hide');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }else{
                        // notification("error",response.message);
                        flashAlert("Réponse","error",response.message);
                    }
                });

                return false;
            });


            $("a.show-detail-renvoie").on("click", function(){
                var motif = $(this).attr("title");
                var cdn = $(this).attr("href");
                var cmvtn = $(this).attr("cmouvtdeces");
                var obs = $(this).attr("obs");

                $("#codedeclarationback").val(cdn);
                $("#motif_renvoi").val(motif);
                $("#observation").val(obs);
                $("#codemouvementdeces").val(cmvtn);

                $("button.btn-send-back").addClass("d-none");
                $("button.btn-edit-send-back").removeClass("d-none");
                $("button.btn-delete-send-back").removeClass("d-none");
                $("#modal-declaration-send-back").modal("show");
                return false;
            });

            $("#btn-edit-send-back").on("click",function(){
                var cmvtn = $("#codemouvementdeces").val();
                var motif = $("#motif_renvoi").val();
                var observation = $("#observation").val();
                var route = "{{ route('declarationNaissance.mouvement.edit', ':id') }}";
                route = route.replace(':id',cmvtn);
                var data = {
                    motif_renvoi:motif,
                    observation:observation
                };

                $(this).attr("disabled",true);
                $(this).html("Traitement en cours ...");

                $.ajax({
                    type: "PUT",
                    url: route,
                    data: data,
                    dataType: "json",
                    success: function (response) {
                        if(response.code == "200"){
                            // notification("success",response.message);
                            flashAlert("Réponse","success",response.message);
                            $("#modal-declaration-send-back").modal("show");
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }else{
                            // notification("error",response.message);
                            flashAlert("Réponse","error",response.message);
                        }
                    }
                });

                return false;
                });

                $("#btn-delete-send-back").on("click",function(){

                var cmvtn = $("#codemouvementdeces").val();
                var route = "{{ route('declarationNaissance.mouvement.delete', ':id') }}";
                route = route.replace(":id",cmvtn);
                $.ajax({
                    type: "DELETE",
                    url: route,
                    dataType: "json",
                    success: function (response) {
                        if(response.code == "200"){
                            // notification("success",response.message);
                            flashAlert("Réponse","success",response.message);
                            $("#modal-declaration-send-back").modal("show");
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }else{
                            // notification("error",response.message);
                            flashAlert("Réponse","error",response.message);
                        }
                    }
                });

                return false;
                });


        });
    </script>

@endsection
