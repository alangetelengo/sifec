@extends('layout.app')
@section('titre')
Certificat de non inscription
@endsection
@section("styles")

<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
    <style>
        a{
            color: green!important;
        }
    </style>

@endsection

@section('corps')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Liste des certificats de non inscription des actes de décès</h4>
                <a href="{{ route("certificatTranscriptionDeces.create") }}"><button type="button" class="btn btn-info m-t-2 float-end text-white" >Créer certificat  <i class="fa fa-plus-circle"></i></button></a>
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
                                        <th>Actions</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1; ?>
                                    @foreach ($certificatTranscription as $certificat)
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
                                                <a href="{{ route('certificatTranscriptionDeces.displayCertificat',$certificat->code_declaration_deces) }}" target="_blank" class="btn btn-warning shadow btn-xs sharp me-1"><i class="fas fa-print" title="Afficher le certificat"></i></a>
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
                                        <th>Actions</th>
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
@section('scripts')
<script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>


<script>
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
