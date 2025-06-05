<html lang="fr">
@extends("layout.app")
@section("titre")
    Joindre-document
@endsection

@section("corps")
<div class="row" id="validation">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Joindre des documents</h4>
            </div>
            <div class="card wizard-content">
                <div class="card-body">
                    <div class="ligne">
                        <h4>{{ $dn->type_declaration }} N° <strong>{{ $dn->code_declaration_naissance }}</strong></h4>
                    </div>

                   <div class="row">
                        @if($dn->pere->statut_personne == "VIVANT")
                        <div class="mb-2 col-sm-4">
                             <label class="radio-inline mr-3">Père
                                <input type="radio" value="père" id="pere" name="parent" nomspere="{{ $dn->pere->nom." ".$dn->pere->prenom }}" datenais="{{ $dn->pere->date_naissance }}" lieunais="{{ $dn->pere->lieu_naissance }}" nat="{{ $dn->pere->nationalite->lib_nationalite }}" prof="{{ $dn->pere->profession->lib_profession }}" typepiece="{{ $dn->pere->document->typeDocument->lib_type_document }}" numeropiece="{{ $dn->pere->document->numero_document }}"   {{ $dn->pere->document->image_document != null ? "checked" : ""}}>
                            </label>
                        </div>

                        @endif
                        @if($dn->mere->statut_personne == "VIVANT")
                        <div class="mb-2 col-sm-4">
                             <label class="radio-inline mr-3">Mère
                                <input type="radio" id="mere" name="parent" value="mere" {{ $dn->mere->document->image_document != null ? "checked" : ""}}>
                            </label>
                        </div>

                        @endif
                        @if($dn->pere->personne_string  !=  $dn->declarant->personne_string  || $dn->declarant->personne_string  !=  $dn->mere->personne_string)
                        <div class="mb-2 col-sm-4">
                             <label class="radio-inline mr-3">Autre
                                <input type="radio" id="parent" name="parent"  value="autre" {{ ($dn->pere->image_document  !=  $dn->declarant->image_document ) && ($dn->declarant->image_document  !=  $dn->mere->image_document) ? "checked" : ""}}>
                            </label>
                        </div>

                        @endif
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- DEBUT ADD JOINT-PIECE --}}
<div class="modal fade" id="modalListePiece" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Joindre la pièce <span id="typeparent"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form1" action="{{ route('declarationNaissance.store.scannerpdf') }}" method="POST" enctype="multipart/form-data" target="_blank">
                @csrf
                <div class="modal-body">
                    <div id="div_importer_fichier" hidden>
                        <form  method="post" id="formPiece" action="{{ route('declarationNaissance.store.importer') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <h5>Ajouter une pièce</h5>
                                <hr>
                                <div class="mb-2 col-md-4">
                                    <label for="numeropiece" class="form-label">Référence de la pièce</label>

                                </div>
                                <div class="mb-2 col-md-4">
                                    <label for="typepiece" class="form-label"> <strong> Type de pièce d'identité</strong></label>

                                </div>

                                <div class="row">
                                    <div class="col-md-4 formate">
                                        <button type="submit" class="btn btn-success"><i class='bx bx-check-circle'></i> Valider l'importation</button> &ensp;
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row g-3">
                        <div class="row">
                            <div class="mb-2 col-md-6">
                                <label class="form-label">Noms </label>
                                <input type="text" class="form-control" readonly id="nomsparent">
                            </div>
                            <div class="mb-2 col-md-6">
                                <label class="form-label">Date de naissance </label>
                                <input type="date" class="form-control" readonly id="datenaisparent">
                            </div>
                            <div class="mb-2 col-md-6">
                                <label class="form-label">Lieu de naissance </label>
                                <input type="text" class="form-control" readonly id="lieunaisparent">
                            </div>
                            <div class="mb-2 col-md-6">
                                <label class="form-label">Nationalité </label>
                                <input type="text" class="form-control" readonly id="nationaliteparent">
                            </div>
                            <div class="mb-2 col-md-6">
                                <label class="form-label">Profession </label>
                                <input type="text" class="form-control" readonly id="professionparent">
                            </div>

                            <div class="mb-2 col-md-6">
                                <label class="form-label">Type pièce d'identité</label>
                                <input type="text" class="form-control" readonly id="codetypedocument" name="code_type_document">
                            </div>
                            <div class="mb-2 col-md-6">
                                <label class="form-label">Numéro pièce d'identité</label>
                                <input type="text" id="numerodocument" name="numero_document" readonly class="form-control" placeholder="Numéro du document" onkeyup="this.value=this.value.toUpperCase()">
                            </div>

                            <div class="col-md-6">
                                <br><br>
                                <button type="button" onclick="scanToPdfWithThumbnails();" class="btn btn-warning"><i class='bx bx-scan'></i> Scanner le document en <strong>pdf</strong>  </button> &ensp; &ensp;
                            </div>
                            {{-- <div class="col-md-4 formate">
                                <button type="button" onclick="submitFormWithScannedImages();" class="btn btn-success"><i class='bx bx-check-circle'></i> Valider le scannage </strong> </button> &ensp; &ensp;
                            </div> --}}

                            <div id="images" style="margin-top: 10px;">  </div>
                            <div id="server_response" style="color: red"> </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" id="btnImporter" onclick="btn_importer();" class="btn btn-success"><i class='bx bx-upload'></i> Importer le fichier</button> &ensp; --}}
                        <button type="button" onclick="submitFormWithScannedImages();" class="btn btn-success"><i class='bx bx-check-circle'></i> Valider le scannage </strong> </button> &ensp; &ensp;
                        <button type="button" id="btn_fermer" class="btn btn-danger" data-bs-dismiss="modal"><i class='bx bx-block'></i> Fermer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- FIN ADD JOINT-PIECE --}}
@endsection


@section("scripts")
<script>
        /** Upload scanned images by submitting the form */
        function submitFormWithScannedImages() {
        if (scanner.submitFormWithImages('form1', imagesScanned, function (response) {

            if(response.code == "200"){
                // notification("success",response.message);
                flashAlert("Réponse","success",response.message);
                document.getElementById('images').innerHTML = ''; // clear images
                imagesScanned = [];
                $("#modalListePiece").modal("hide");
                setTimeout(() => {
                    location.reload();
                }, 2000);
            }else{
                // notification("error",response.message);
                flashAlert("Réponse","error",response.message);
            }
            // if (xhr.readyState == 4) { // 4: request finished and response is ready
            //     if (xhr.responseText == "1") {
            //         // Vider les champs
            //         // document.getElementById("C_REF_PCES").value="";
            //         // document.getElementById("typePieces").value="";
            //         // // Actualisation de la liste
            //         // activerPiecesJointes();
            //         // success_noti('Enregistement de la pièce scannée effectué avec succès');
            //         document.getElementById('images').innerHTML = ''; // clear images
            //         imagesScanned = [];

            //     } else {
            //         console.log(xhr.responseText);
            //     }
            // }
        })) {
            document.getElementById('server_response').innerHTML = "Soumission, veuillez rester prêt ...";
        } else {
            document.getElementById('server_response').innerHTML = "Soumission du formulaire annulée. Veuillez d'abord scanner..";
            // console.log('Veuillez d\'abord scanner le document ...');
            return ;
        }
    }
    //************* Fin Scanner ***************

    $(function() {
        $("#pere").on("click", function(){
            var typepiece = $(this).attr("typepiece");
            var numeropiece = $(this).attr("numeropiece");
            var nomspere = $(this).attr("nomspere");
            var datenais = $(this).attr("datenais");
            var lieunais = $(this).attr("lieunais");
            var nationalite = $(this).attr("nat");
            var profession = $(this).attr("prof");

            $("#typeparent").html("du "+$(this).val());

            $("#codetypedocument").val(typepiece);
            $("#numerodocument").val(numeropiece);
            $("#nomsparent").val(nomspere);
            $("#datenaisparent").val(datenais);
            $("#lieunaisparent").val(lieunais);
            $("#nationaliteparent").val(nationalite);
            $("#professionparent").val(profession);

            $("#modalListePiece").modal("show");
            return false;
        });
    });
</script>
@endsection



{{-- @include('naissance::declaration.js.create') --}}
