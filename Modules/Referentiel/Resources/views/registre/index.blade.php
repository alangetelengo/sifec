@extends('layout.app')
@section('titre')
Registre Etat civil
@endsection
@section('styles')

<link href="{{ asset('tpl/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css')}}" rel="stylesheet">
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">

@endsection

@section('corps')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des registres de l'état civil</h4>
                    @can("module.registre.create")
                    <button type="button" class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#modalCEC">
                        Ajouter
                    </button>
                    @endcan
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">

                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Registre</th>
                                            <th>Type registre</th>
                                            <th>Date ouverture</th>
                                            <th>Date fermeture</th>
                                            <th>Nombre d'acte transcrit</th>
                                            <th>Nombre d'acte prévu</th>
                                            <th>Statut</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=1; ?>
                                        @foreach ($registres as $registre)
                                        <tr width="100%">
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $registre->lib_registre }}</td>
                                            <td>{{ $registre->typeRegistre->lib_type_registre }}</td>
                                            <td>{{ date("d-m-Y", strtotime($registre->date_ouverture)) }}</td>
                                            <td>{{ date("d-m-Y", strtotime($registre->date_fermeture)) }}</td>
                                            <td>{{ $registre->nombre_acte_transcrit }}</td>
                                            <td>{{ $registre->nombre_acte_prevu}}</td>

                                            <td>
                                                @if($registre->statut == "0" && $registre->approbation_tribunal == null)
                                                    <span class="badge light badge-danger" style="font-size: 13px;font-weight:600;" title="registre en attente de validation">Encours de validation</span>
                                                @endif
                                                @if($registre->statut == "1" && $registre->approbation_tribunal != null)
                                                    <span class="badge light badge-success" style="font-size: 13px;font-weight:600;">Activé</span>
                                                @endif

                                                @if($registre->nombre_acte_transcrit == $registre->nombre_acte_prevu && $registre->approbation_tribunal != null)
                                                    <span class="badge light badge-warning" style="font-size: 13px;font-weight:600;" title="Ce registre est remplit">[Remplit]</span>
                                                @endif
                                                @if($registre->signature_cloture_cec != "")
                                                <span class="badge light badge-danger" style="font-size: 13px;font-weight:600;" title="Ce registre est clôturé">Clôturé</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-primary light sharp" data-bs-toggle="dropdown">
                                                        <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                    @if($registre->sceau == null)
                                                        @can('module.fonctionnalites.parapher')
                                                            <a href="{{ $registre->code_registre }}" class="dropdown-item show-validation-modal">Parapher</a>
                                                        @endcan
                                                    @endif
                                                    @if($registre->typeRegistre->code_type_registre == "TPRG_0001")
                                                        <a  href="{{ route('registre.naissance', $registre->code_registre) }}" target="_blank" class="dropdown-item">Consulter</i></a>
                                                    @endif
                                                    @if($registre->typeRegistre->code_type_registre == "TPRG_0002")
                                                        <a  href="{{ route('registre.mariage', $registre->code_registre) }}" target="_blank" class="dropdown-item">Consulter</i></a>
                                                    @endif
                                                    @if($registre->typeRegistre->code_type_registre == "TPRG_0004")
                                                        <a  href="{{ route('registre.deces', $registre->code_registre) }}" target="_blank" class="dropdown-item">Consulter</i></a>
                                                    @endif
                                                    @if($registre->statut == 1)
                                                        <a href="{{ $registre->code_registre }}" typeregistre="{{ $registre->typeRegistre->lib_type_registre }}" class="dropdown-item show-cloturer-modal">Clôturer</a>
                                                    @endif
                                                    @if(($registre->nombre_acte_prevu - $registre->nombre_acte_transcrit) == 0)
                                                        {{-- @can('module.fonctionnalites.parapher') --}}
                                                            <a href="{{ $registre->code_registre }}" typeregistre="{{ $registre->typeRegistre->lib_type_registre }}" class="dropdown-item show-add-leaflet-modal">Ajouter des feuillets</a>
                                                        {{-- @endcan --}}
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>N°</th>
                                            <th>Registre</th>
                                            <th>Type registre</th>
                                            <th>Date ouverture</th>
                                            <th>Date fermeture</th>
                                            {{-- <th>Nombre d'acte transcrit</th> --}}
                                            <th>Nombre d'acte prévu</th>
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


      <!-- Large modal -->
    <div class="modal fade" id="modalCEC" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        {{-- <div class="modal-dialog modal-lg"> --}}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Information du régistre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form  action="{{ route("registre.store") }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-2 col-md-12">
                                <label class="form-label">Type régistre <span class="text-danger">*</span></label>
                                <select name="code_type_registre" class="form-control form-control wide" id="codetyperegistre">
                                    <option disabled selected>Choisissez</option>
                                    @if(Auth::user()->affectationActive()->institution->lieu->localiteParent->pompes_funebres == 0)
                                        @foreach (Modules\Referentiel\Entities\TypeRegistre::all() as $item)
                                            <option value="{{ $item->code_type_registre }}">{{$item->lib_type_registre}}</option>
                                        @endforeach
                                        @else
                                        @foreach ($typeRegistres as $item)
                                            <option value="{{ $item->code_type_registre }}">{{$item->lib_type_registre}}</option>
                                        @endforeach
                                    @endif
                                </select>

                            {{-- </div>
                            <div class="mb-2 col-md-6"> --}}
                                <label class="form-label">Libéllé <span class="text-danger">*</span></label>
                                <input id="typeregistre" type="text" class="form-control" readonly class="form-control @error('lib_registre') is-invalid @enderror" value="{{ old("lib_registre") }}" required  name="lib_registre">
                                <input type="hidden" id="prefix" name="prefix">
                                @error("lib_registre")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            {{-- </div>
                            <div class="row"> --}}
                            <div class="mb-2 col-md-12">
                                <label class="form-label">Nombre d'acte prévu <span class="text-danger">*</span></label>
                                <input  class="form-control form-control-sm @error("nbre_acte_prevu") is-invalid @enderror " name="nbre_acte_prevu" type="number" >
                                @error("nbre_acte_prevu")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-2 col-md-12 d-none">
                                <label class="form-label">Etat <span class="text-danger">*</span></label>
                                <select id="statut" name="statut" class="form-control @error('statut') is-invalid @enderror" required>
                                    <option value="0" {{"statut"==old("statut") ? "selected":""}}>Désactivé</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-primary">Valider</button>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- DEBUT VALIDATION REGISTRE --}}
    <div class="modal fade" id="modal-registre-paraphage" data-bs-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span class="module-title"> </span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-2 col-md-6">
                            <input type="hidden" id="code_registre">
                            <label class="form-label">Code de validation<span class="text-danger">*</span></label>
                            <input type="text" class="form-control"  placeholder="" id="otp_paraphage" required>
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
    {{-- FIN DE VALIDATION REGISTRE --}}

    {{-- DEBUT CLÔTURER REGISTRE --}}
    <div class="modal fade" id="modal-registre-cloturer" data-bs-backdrop="static">
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
                            <label class="form-label">Clôture du registre </label>
                            <input type="text" readonly class="form-control" id="type_registre">
                        </div>
                        <div class="mb-2 col-md-12">
                            <input type="hidden" id="coderegistre">
                            <label class="form-label">Date de clôture<span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="date_cloture" required>
                        </div>

                        <span class="text-success"><i>Veuillez saisir la date de clôture du registre.</i></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info btn-sm text-white" id="btn-cloturer">Valider</button>
                    <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    {{-- FIN DE CLÔTURER REGISTRE --}}




     {{-- DEBUT AJOUT FEUILLETS DU REGISTRE --}}
     <div class="modal fade" id="modal-registre-add-leaflet" data-bs-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span class="module-title" id="libtyperegistre"> </span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-2 col-md-12">
                            <label class="form-label">Ajouter des feuillets du registre </label>
                            <input type="number" class="form-control" id="nbreFeuillets" min="1">
                        </div>

                        <span id="msg_erreur"><i style="color: red">Veuillez saisir le nombre de feuillets du registre.</i></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info btn-sm text-white" id="btn-add-feuillets">Valider</button>
                    <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    {{-- FIN DE AJOUT FEUILLETS DU REGISTRE --}}
@endsection
@section('scripts')
      <!-- Datatable -->
      <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

      <script>
        $(function() {
            $("#codetyperegistre").on("change", function() {

                var codetyperegistre = $(this).val();

                    if(codetyperegistre != null || codetyperegistre != ''){

                        var lib = $("#codetyperegistre option:selected").text();
                        $("#typeregistre").val("REGISTRE DE "+lib);
                        $("#prefix").val("R.A."+lib.substr(0,1)+"_");
                    }
                });

                //  Traitement envoi SMS avant validation du registre
                $("a.show-validation-modal").on("click", function() {
                    var code_registre = $(this).attr("href");
                    var url = "{{ route('registre.send.otp', ':id') }}";
                    url = url.replace(":id",code_registre);

                    $(".over-loader-page").fadeIn(600);

                    $.get(url, function(response){

                        if(response.code == 200){

                            $(".over-loader-page").fadeOut(600);
                            $("#code_registre").val(code_registre);
                            $("#modal-registre-paraphage").modal('show');
                        }else{
                            $(".over-loader-page").fadeOut(600);
                            flashAlert("Réponse","error",response.message);
                        }
                    });
                    return false;
                });

                $("#btn-validate").on("click",function(){
                    var code_registre = $("#code_registre").val();
                    var otp_paraphage = $("#otp_paraphage").val();
                    if(code_registre == "" || otp_paraphage == ""){
                        alert("Veuillez renseigner le OTP reçu par SMS");
                    }else{
                        $(this).attr("disabled",true);
                        $(this).html("Traitement en cours ...");
                        var url = "{{ route('registre.validate.otp') }}";
                        var data = {
                            code_registre:code_registre,
                            otp_paraphage:otp_paraphage
                        };

                        $.post(url,data,function(response){
                            $("#btn-validate").attr("disabled",false);
                            $("#btn-validate").html("Valider");
                            if(response.code == "200"){
                                // notification("success",response.message);
                                flashAlert("Réponse","success",response.message);
                                $("#modal-registre-paraphage").modal('hide');
                                setTimeout(() => {
                                    location.reload();
                                }, 4000);
                            }else{
                                // notification("error",response.message);
                                flashAlert("Réponse","error",response.message);
                            }
                        });

                    }
                    return false;
                });

            $("a.show-cloturer-modal").on("click", function(){
                var coderegistre = $(this).attr("href");
                var typeregistre = $(this).attr("typeregistre");
                $("#coderegistre").val(coderegistre);
                $("#type_registre").val(typeregistre);

                $("#modal-registre-cloturer").modal("show");
                return false;
            });

            $("#btn-cloturer").on("click",function(){
                var codereg = $("#coderegistre").val();
                var datecloture = $("#date_cloture").val();
                var route = "{{ route('registre.cloture') }}";
                var data = {
                    code_registre:codereg,
                    date_cloture:datecloture
                };

                // $(this).attr("disabled",true);
                // $(this).html("Traitement en cours ...");
                $.post(route, data, function(response){

                    if(response.code == "200"){
                        // notification("success",response.message);
                        flashAlert("Réponse","success",response.message);
                        $("#modal-registre-cloturer").modal('hide');
                        setTimeout(() => {
                            location.reload();
                        }, 4000);
                    }else{
                        // notification("error",response.message);
                        flashAlert("Réponse","error",response.message);
                    }
                });

                return false;
            });



             //Affichage modal ajout de feuillets du registre
             $("a.show-add-leaflet-modal").on("click", function(){
                $("#msg_erreur").hide();
                var coderegistre = $(this).attr("href");
                var typeregistre = $(this).attr("typeregistre");
                $("#coderegistre").val(coderegistre);
                $("#libtyperegistre").html("REGISTRE DE "+typeregistre);

                $("#modal-registre-add-leaflet").modal("show");
                return false;
            });

            //Traitement ajout de feuillets du registre
            $("#btn-add-feuillets").on("click",function(){
                var codereg = $("#coderegistre").val();
                var nbrefeuillets = $("#nbreFeuillets").val();
                var route = "{{ route('registre.add.feuillets') }}";
                var data = {
                    code_registre:codereg,
                    nbrefeuillets:nbrefeuillets
                };
                if(nbrefeuillets == "" || nbrefeuillets == null){
                    $("#msg_erreur").show(300);
                    return false;
                }

                $.post(route, data, function(response){

                    if(response.code == "200"){
                        // notification("success",response.message);
                        flashAlert("Réponse","success",response.message);
                        $("#modal-registre-add-leaflet").modal('hide');
                        setTimeout(() => {
                            location.reload();
                        }, 6000);
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
