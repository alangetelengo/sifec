
@extends('layout.app')
@section('titre')
Actes de naissance
@endsection
@section("styles")
<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
@endsection

@section('corps')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Liste des actes de naissance</h4>
                <div class="row">
                    <div id="dupcreer">
                        @can('module.acteNaissance.generate')
                        <button class="btn btn-sm btn-primary mb-2 generate-actes d-none">Générer les actes</button>
                        @endcan
                        @can('module.acteNaissance.signature')
                        <button class="btn btn-sm btn-primary mb-2 validate-actes d-none">Valider les actes</button>
                        <button class="btn btn-sm btn-primary mb-2 validate-on-acte d-none">Valider un acte</button>
                        @endcan
                        {{-- <button class="btn btn-sm btn-primary mb-2  chercheacte">Edition du duplicata</button> --}}
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
                                        <td><label for="check-all"><input type="checkbox" id="check-all"></label></td>
                                        <th>N°</th>
                                        <th>Enfant: Nom</th>
                                        <th>Enfant: Prénom</th>
                                        <th>Enfant: Date naissance</th>
                                        <th>Enfant: Sexe</th>
										<th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($declarations as $dn)
                                        @if($dn->mouvements->last()->statut == "Envoyée")
                                            <span class='d-none'>
                                                <input type="text" id="datenaiss" value="{{date('Y-m-d', strtotime($dn->date_heure_naissance))}}">
                                                <input type="text" id="datedecl" value="{{date('Y-m-d', strtotime($dn->date_heure_declaration))}}">
                                                <input type="text" id="numcertificat" value="{{$dn->numero_certificat}}">
                                            </span>
                                            <tr width="100%">
                                                <td>
                                                    {{-- <label for="single-check"><input type="checkbox" name="declarations[]" class="single-check" id="get-single-check" value="{{ $dn->code_declaration_naissance }}"></label> --}}
                                                    {{-- @if($dn->acte == null && $fonctionuser->code_fonction!="FONC_0002")<!--identification de la fonction :officier d'état-civil pour la validation-->
                                                    @endif --}}
                                                    {{-- @if($dn->acte != null && $dn->acte->signature_mairie == null )<!--identification de la fonction :agent Maire pour la génération de l'acte--> --}}
                                                    <label for="single-check"><input type="checkbox" name="declarations[]" class="single-check" id="get-single-check" value="{{ $dn->code_declaration_naissance }}-{{ $dn->acte == null ? '0':'1' }}"></label>
                                                    {{-- @endif --}}
                                                </td>
                                                <td>{{ $dn->acte != null ? $dn->acte->niupp : "//" }}</td>
                                                <td>{{ $dn->enfant->nom }}</td>
                                                <td>{{ $dn->enfant->prenom }}</td>
                                                <td>{{ date("d-m-Y", strtotime($dn->enfant->date_naissance)) }}</td>
                                                <td>{{ $dn->enfant->sexe == "M" ? "Masculin" : "Féminin" }} </td>

                                                @php
                                                    $annuler = false;
                                                    if ($dn->acte != null) {
                                                       $annuler = $dn->acte->deleted_at != null ? true : false;
                                                    }
                                                @endphp

                                                @if (!$annuler)
                                                    @if($dn->mouvements->last()->statut == "Renvoyée" && $dn->acte == null)
                                                    <td><a href="{{ $dn->code_declaration_naissance }}" cmouvtnais="{{ $dn->mouvements->last()->code_mouvement_naissance }}" obs="{{ $dn->mouvements->last()->observation }}" class="show-detail-renvoie" title="{{ $dn->mouvements->last()->motif_renvoi }}">
                                                        <span class="badge light badge-danger" style="font-size: 13px;font-weight:600;">document renvoyé </span></a>
                                                    </td>
                                                    @endif
                                                    @if($dn->mouvements->last()->statut == "Envoyée" && $dn->acte == null)
                                                    <!-- Affichage du statut de la déclaration :: en attente de transcription-->
                                                    <td style="width: 15%"><span class="badge light badge-danger light sharp" style="font-size: 13px;font-weight:600;">En attente de transcription de l'acte</span></td>
                                                    @endif
                                                    @if( $dn->acte != null && $dn->acte->signature_mairie == null)
                                                    <!-- Affichage du statut de la déclaration :: en attente d'approbation du maire-->
                                                        <td style="width: 15%"><span class="badge light badge-warning light sharp" style="font-size: 13px;font-weight:600;">Acte produit et en attente d'approbation de l'officier d'état civil</span></td>
                                                    @endif
                                                    @if($dn->mouvements->last()->statut == "Envoyée" && $dn->acte != null && $dn->acte->signature_mairie != null && $dn->acte->retirer == false)
                                                        <td style="width: 15%">
                                                            <a href="{{ $dn->acte->numeroActe->numero_acte }}" niupp="{{ $dn->acte->niupp }}" class="badge badge-primary show-acte-retrait-modal">Acte produit non rétiré</a>
                                                        </td>
                                                    @endif
                                                    @if($dn->mouvements->last()->statut == "Envoyée" && $dn->acte != null && $dn->acte->signature_mairie != null && $dn->acte->retirer == true)
                                                        <td style="width: 15%"><span class="badge light badge-success" style="font-size: 13px;font-weight:600;">Acte rétiré</span></td>
                                                    @endif

                                                    <td style="width: 15%">
                                                        <div class="btn-group btn-group-xs">
                                                            <a href="{{ route('declarationNaissance.etat',$dn->code_declaration_naissance) }}" target="_blank" class="btn btn-warning shadow btn-xs sharp me-1" title="Voir document"><i class="fas fa-print"></i></a>

                                                            @if($dn->acte == null)
                                                                <a href="{{ $dn->code_declaration_naissance }}" class="btn btn-danger show-to-send-back shadow btn-xs sharp me-1" title="Renvoyer" ><i class="fas fa-plane"></i></a>
                                                            @endif
                                                            @if($dn->acte != null)
                                                                <a href="{{ route('acteNaissance.print.acte',$dn->code_declaration_naissance) }}" target="_blank" class="btn btn-primary shadow btn-xs sharp me-1" title="Voir l'acte"><i class="fas fa-eye"></i></a>
                                                                <a href="{{ route('acteNaissance.copie',$dn->code_declaration_naissance) }}" target="_blank" class="btn btn-success shadow btn-xs sharp me-1" title="Voir copie"><i class="fa fa-eye"></i></a>
                                                                <a href="{{ route('acteNaissance.displayExtrait',$dn->code_declaration_naissance) }}" target="_blank" class="btn btn-success shadow btn-xs sharp me-1" title="Voir extrait"><i class="fa fa-eye"></i></a>
                                                            @endif
                                                        </div>
                                                    </td>

                                                @else
                                                    <td style="width: 15%"><span class="badge light badge-danger" style="font-size: 13px;font-weight:600;">Acte annulé</span></td>
                                                    <td style="width: 15%">
                                                        <div class="btn-group btn-group-xs">
                                                            <a href="{{ route('declarationNaissance.etat',$dn->code_declaration_naissance) }}" target="_blank" class="btn btn-warning shadow btn-xs sharp me-1" title="Voir document"><i class="fas fa-print"></i></a>
                                                            @if($dn->acte != null)
                                                                <a href="{{ route('acteNaissance.print.acte',$dn->code_declaration_naissance) }}" target="_blank" class="btn btn-primary shadow btn-xs sharp me-1" title="Voir l'acte"><i class="fas fa-eye"></i></a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                @endif

                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>N°</th>
                                        <th>Enfant: Nom</th>
                                        <th>Enfant: Prénom</th>
                                        <th>Enfant: Date naissance</th>
                                        <th>Enfant: Sexe</th>
										<th>Statut</th>
                                        {{-- <th>Statut: Statut</th> --}}
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
                            <label class="form-label">Numéro déclaration naissance <span class="text-danger">*</span></label>
                            <input id="code_declaration_naissance" type="text" class="form-control" class="form-control">
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

{{-- DEBUT MODAL VALIDATION ACTE DE NAISSANCE --}}
<div class="modal fade" id="modal-validate-acte" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Validation de l'acte de naissance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mb-2 col-md-6">
                        <input type="hidden" id="code_declaration_naissance">
                        <input type="hidden" id="validation_type">
                        <label class="form-label">Code de validation<span class="text-danger">*</span></label>
                        <input type="text" class="form-control"lass="form-control"  placeholder="" id="otp_approbation_mairie" required>
                    </div>
                    <span class="text-success"><i>Veuillez saisir le code de validation reçu par SMS.</i> Code non reçu ? <a href="#" class="resend_otp">Renvoyez le code de validation</a></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info btn-sm text-white" id="btn-validate">Valider</button>
                <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
{{-- FIN MODAL VALIDATION ACTE DE NAISSACE --}}
{{-- DEBUT MODAL RETRAIT ACTE DE NAISSANCE --}}
<div class="modal fade" id="modal-retrait-acte" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Retrait de l'acte de naissance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mb-2 col-md-6">
                        <label class="form-label">Numéro de l'acte<span class="text-danger">*</span></label>
                        <input type="text" id="code_acte" class="form-control" readonly>
                        <input type="hidden" id="leniupp">
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
                        <input type="hidden" class="form-control" id="codemouvementnaissance">
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
<!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>
    <script>

        $(function()
        {
            var codes = [];
            var actesGeneres = [];
            var actesNonGeneres = [];

            $("a.show-acte-modal").on("click",function(){
             var me = $(this);
             var action = me.attr("href");
             var modal = $("#modal-acte");

             $("#code_declaration_naissance").val(action).attr("readonly",true);
             modal.modal("show");
             return false;

         });

         $("button.generate").on("click", function () {

             var code_registre = $("#code_registre").val();
             var code_declaration_naissance = $("#code_declaration_naissance").val();
             var route = "{{ route('acteNaissance.generate') }}";

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
                url = url.replace(':id',code_declaration_naissance);
                $("#certificat").html("<p>Vous avez dépassé le délais, veuillez  générer un certificat de non inscription <br> Nombre de jours sans déclarer: "+parseInt(n_jours)+" jours </p> <a href="+url+" class='btn btn-primary'>Générer le certificat<a>");
                $(".cacher").addClass("d-none");
            } else {

                $.post(route, {code_declaration_naissance:code_declaration_naissance}, function(reponse) {

                if(reponse.code == "200"){
                console.log("success",reponse.message.success);
                $("#modal-acte").modal("hide");
                var url = "{{ route('acteNaissance.display',':id') }}";
                url = url.replace(':id',code_declaration_naissance);
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


            //gerer send otp pour la validation singleton
                $("#get-single-check").on("change", function(){

                    $("button.validate-actes").addClass("d-none");
                    $("button.validate-on-acte").removeClass("d-none");
                });

                $("button.validate-on-acte").on("click",function(){

                    //pour le singleton
                    var codeDn = $("#get-single-check").val(). substr(0,12);
                    $("#validation_type").val("simple");
                    $("#otp_approbation_mairie").val("");
                    if(codeDn != ""){
                        sendOtpSingle(codeDn);
                    }
                    return false;
                });

            //fin gerer send otp pour la validation singleton

         $("#btn-validate").on("click",function(){
            var code_declaration_naissance = $("#code_declaration_naissance").val();
            var validation_type = $("#validation_type").val();
            var otp_approbation_mairie = $("#otp_approbation_mairie").val();

            var inputs = {
                codes : actesGeneres,
                code_declaration_naissance:code_declaration_naissance,
                otp_approbation_mairie:otp_approbation_mairie
            };

            validateActes(validation_type,inputs,$(this));

            return false;
         });





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
                $("button.generate-actes").removeClass("d-none");

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
            $("#otp_approbation_mairie").val("");
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

         $("a.show-acte-retrait-modal").on("click", function(){
            var code_acte = $(this).attr("href");
            var niupp = $(this).attr("niupp");

            $("#code_acte").val(code_acte);
            $("#leniupp").val(niupp);
            $("#modal-retrait-acte").modal("show");
            return false;
        });
        $("#btn-retrait").on("click", function(){
            var nominteresse = $("#nom_interesse").val();
            var prenominteresse = $("#prenom_interesse").val();
            var telephoneinteresse = $("#telephone_interesse").val();
            var niupp = $("#leniupp").val();
            var donnees = {
                            niupp:niupp,
                            nominteresse:nominteresse,
                            prenominteresse:prenominteresse,
                            telephoneinteresse:telephoneinteresse
                        };
            valideRetraitActe(donnees);
            return false;
        });
    });

    //envoie de code de validation d'un seul acte
    function sendOtpSingle(lecode){
        $(".over-loader-page").fadeIn(600);
        var url = "{{ route('acteNaissance.send.otp') }}";
        var data = {codeDn:lecode};
        $.post(url,data,function(response){
            console.log(response);
            if(response.code == "200"){

                $(".over-loader-page").fadeOut(600);
                $("#validation_type").val("simple");
                $("#code_declaration_naissance").val(lecode);

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


    function sendOtpMultiple(codes){
        $(".over-loader-page").fadeIn(600);
        var url = "{{ route('acteNaissance.send.otp.bulk') }}";
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
                 flashAlert("ALERTE","error",outString);
                //flashAlert("Une erreur est suvernue","error",outString);

            }
        });
    }

    function generateActeMultiple(codes){
        $(".over-loader-page").fadeIn(600);
        var url = "{{ route('acteNaissance.generate.bulk') }}";
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
                outString+= `<li style='text-align:left;color:red; list-style:disc !important; font-size:16px'>${value}</li>`;
                }
                outString += "</ul>";
                flashAlert("ALERTE","error",outString);
                // flashAlert("Une erreur est suvernue","error",response.message.reponse);

            }
        });
    }


    function validateActes(type,inputs,trigger){

        if(type=="simple"){
            if(inputs.code_declaration_naissance == "" || inputs.otp_approbation_mairie == ""){
                alert("Veuillez renseigner le code déclaration de naissance et le code de validation reçu par SMS");
            }else{
                trigger.attr("disabled",true);
                trigger.html("Traitement en cours ...");
                var url = "{{ route('acteNaissance.validate.otp') }}";
                var data = {
                    code_declaration_naissance:inputs.code_declaration_naissance,
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
                        }, 8000);
                    }else{
                        // notification("error",response.message);
                        var outString = "<ul>";
                        for (const [key, value] of Object.entries(response.message))
                        {
                        outString+= `<li style='text-align:left;color:red; list-style:disc !important; font-size:12px'>${value}</li>`;
                        }
                        outString += "</ul>";
                         flashAlert("ALERTE","error",outString);
                        //flashAlert("Une erreur est suvernue","error",outString);
                    }
                });
            }
        }else{
            if(inputs.codes.length == 0 || inputs.otp_approbation_mairie == ""){
                alert("Veuillez renseigner le code de validation reçu par SMS");
            }else{
                trigger.attr("disabled",true);
                trigger.html("Traitement en cours ...");
                var url = "{{ route('acteNaissance.validate.otp.bulk') }}";
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


    function valideRetraitActe(inputs){
        var url = "{{ route('acteNaissance.retrait') }}";
        var data = {
                    niupp:inputs.niupp,
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

    $(function(){
        cacher();
        $("button.chercheacte").on("click",function(){

            var me = $(this);
            var action = me.attr("href");
            var modal = $("#modal-search-acte");

            modal.modal("show");

            return false;

        });

        // Rechercher la personne
        $('button#rechercher').on("click", function (event) {
            // event.preventDefault();
            // data = [];
            var nom = $("#nom_recherche");
            var prenom = $("#prenom_recherche");
            var lieu = $("#lieu_recherche");

            var data = {
                nom: nom.val(),
                prenom: prenom.val(),
                //sexe: sexe.val(),
                lieu: lieu.val()
            };

            var int = 0;
            var table = '<div class="table-responsive">'+
                            '<table id="example" class="table table-responsive-md table-hover">'+
                                '<thead>'+
                                    '<tr>'+
                                        '<th>#</th>'+
                                        '<th><strong>Nom et prénom</strong></th>'+
                                        '<th><strong>Lieu naissance</strong></th>'+
                                        '<th><strong>Centre d\'Etat Civil</strong></th>'+
                                ' </tr>'+
                                '</thead>'+
                                '<tbody>';

            //traitement ajax
            $.get("{{ route('ActeNaissance.search') }}",
                    data,
                    function(response){
                        if(response.personnes.length > 0){
                            console.log(response);
                            var routeGenerate = "{{ route('ActeNaissance.generate.duplicata',':id') }}";


                            for( var i=0; i < response.personnes.length ; i++){
                                routeGenerate = routeGenerate.replace(':id',response.personnes[i].niupp);
                                int ++;
                                table +='<tr class="tr" data-choix="'+response.personnes[i].niupp+'" data-nom="'+response.personnes[i].nom+'" data-prenom="'+response.personnes[i].prenom+'" data-lieu="'+response.personnes[i].lieu_naissance+'" data-institut="'+response.personnes[i].lib_institution+'">'+
                                            '<td><strong>'+int+'</strong></td>'+
                                            '<td>'+response.personnes[i].nom+" "+response.personnes[i].prenom+'</td>'+
                                            '<td>'+response.personnes[i].lieu_naissance+'</td>'+
                                            '<td>'+response.personnes[i].lib_institution+'</td>'+
                                            '<td><a class="btn btn-primary" href='+routeGenerate+' >Générer duplicata</a></td>';
                                            '</tr>';
                            }
                        }

                        $("#resultatrech").html(table);
                    });
        });

            function cacher() {
                $("#actetrouver").hide();
            }





        $("a.show-to-send-back").on("click", function(){

            var codeDeclaration = $(this).attr('href');
            $("#codedeclarationback").val("");
            $("#motif_renvoi").val("");
            $("#observation").val("");

            // $("#code_declaration_naissance_back").val(codeDeclaration);
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
            var route = "{{ route('declarationNaissance.mouvement') }}";
            var data = {
                code_declaration_naissance:cdn,
                motif_renvoi:motif,
                observation:observation
            };

            // $(this).attr("disabled",true);
            // $(this).html("Traitement en cours ...");
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
            var cmvtn = $(this).attr("cmouvtnais");
            var obs = $(this).attr("obs");

            $("#codedeclarationback").val(cdn);
            $("#motif_renvoi").val(motif);
            $("#observation").val(obs);
            $("#codemouvementnaissance").val(cmvtn);

            $("button.btn-send-back").addClass("d-none");
            $("button.btn-edit-send-back").removeClass("d-none");
            $("button.btn-delete-send-back").removeClass("d-none");
            $("#modal-declaration-send-back").modal("show");
            return false;
        });

        $("#btn-edit-send-back").on("click",function(){
            var cmvtn = $("#codemouvementnaissance").val();
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

            var cmvtn = $("#codemouvementnaissance").val();
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
