

@extends('layout.app')
@section('titre')
Fiche de maternité
@endsection
@section("styles")
<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
@endsection
@section('sous-titre')
    {{ $title }}
@endsection
@section('corps')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>{{ $title }}</h4>
                <a href="{{ route('fiche_maternite.create') }}"><button type="button" class="btn btn-info m-t-2 float-end text-white"> {{ $button }}  <i class="fa fa-plus-circle"></i></button></a>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Enfant: Nom</th>
                                        <th>Enfant: Prénom</th>
                                        <th>Enfant: Date naissance</th>
                                        <th>Enfant: Sexe</th>
                                        <th>Etat: Enfant</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($declarations as $dn)

                                    <tr width="100%">
                                        <td>{{ $dn->code_declaration_naissance }}</td>
                                        <td>{{ $dn->enfant->nom }}</td>
                                        <td>{{ $dn->enfant->prenom }}</td>
                                        <td>{{ date("d-m-Y", strtotime($dn->enfant->date_naissance)) }}</td>
                                        <td>{{ $dn->enfant->sexe == "M" ? "Masculin" : "Féminin" }}</td>
                                        <td>{{ $dn->enfant->statut_personne }}</td>
                                        <td style="width: 15%">
                                            <div class="btn-group btn-group-xs">
                                                <a href="{{ route('declarationNaissance.edit',$dn->code_declaration_naissance) }}" class="btn btn-info shadow btn-xs sharp me-1" title="Modifier"><i class="fas fa-pencil-alt"></i></a>
                                                <a href="{{ route('declarationNaissance.etat',$dn->code_declaration_naissance) }}" target="_blank" class="btn btn-warning shadow btn-xs sharp me-1" title="Voir document"><i class="fas fa-print"></i></a>
                                                <form  action="{{ route('declarationNaissance.destroy',$dn->code_declaration_naissance) }}" method="POST" style="display: inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    {{-- <button type="submit" class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></button> --}}
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Enfant: Nom</th>
                                        <th>Enfant: Prénom</th>
                                        <th>Enfant: Date naissance</th>
                                        <th>Enfant: Sexe</th>
                                        <th>Etat: Enfant</th>
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

{{-- DEBUT DETAILS RENVOIE DECLARATION --}}
<div class="modal fade" id="modal-declaration-send-back" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span class="module-title"> Détail du renvoie</span></h5>
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
                        <select id="motif_renvoi" name="motif_renvoi" class="form-control" readonly>
                            {{-- <option value="" disabled selected>Selectionner</option>
                            <option value="erreur materielle">Erreur matérielle</option>
                            <option value="Ajouter nom/prenom">Ajouter nom/prénom</option>
                            <option value="rectifier nom/prenom">Rectifier nom/prénom</option> --}}
                        </select>
                    </div>
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Observation</label>
                        <textarea id="observation" cols="105" rows="5" readonly></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
{{-- FIN DETAILS RENVOIE DECLARATION --}}
{{-- DEBUT ADD JOINT-PIECE --}}
{{-- <div class="modal fade" id="modalListePiecePere" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Liste des pièces</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="div_importer_fichier" hidden>
                    <form  method="post" id="formPiece" action="{{ route('declarationNaissance.store.importer') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                        <h5>Ajouter une pièce</h5>
                        <hr>
                        <div class="col-md-4 formate">
                            <label for="C_REF_PCE" class="form-label">Référence</label>
                            @error("C_REF_PCE")<span class="text-danger">{{ $message }}</span> @enderror
                            <input id="C_REF_PCE" name="C_REF_PCE" type="text" class="form-control @error("C_REF_PCE") is-invalid @enderror" placeholder="Saisissez une référence">
                            <span class="text-danger error-text C_REF_PCE_error"></span>
                        </div>

                        <div class="col-md-4 formate">
                            <label for="D_DT_PCE" class="form-label">Date pièce</label>
                            @error("D_DT_PCE")<span class="text-danger">{{ $message }}</span> @enderror
                            <input id="D_DT_PCE" name="D_DT_PCE" type="text" class="form-control datepicker @error("D_DT_PCE") is-invalid @enderror" placeholder="Saisissez la date" value="{{ old("D_DT_PCE")}}">
                            <span class="text-danger error-text D_DT_PCE_error"></span>
                        </div>
                        <!---------------------------------->
                        <div class="col-md-4 formate">
                            <label for="L_OBS_PCE" class="form-label"> <strong> Type </strong></label>
                            @error("L_OBS_PCE")<span class="text-danger">{{ $message }}</span> @enderror
                            <input list="listTypePiece" id="L_OBS_PCE" name="L_OBS_PCE" type="text" class="form-control" placeholder="Saisissez ou sélectionnez" onkeyup="this.value=this.value.toUpperCase()">
                            <datalist id="listTypePiece">

                            </datalist>
                            <span class="text-danger error-text L_OBS_PCE_error"></span>
                        </div>


                        <div class="col-md-4 formate" hidden>
                            <label for="tablePiece" class="form-label">table </label>
                            @error("tablePiece")<span class="text-danger">{{ $message }}</span> @enderror
                            <input id="tablePiece" name="tablePiece" type="text" class="form-control @error("tablePiece") is-invalid @enderror" placeholder="Saisissez une table" value="{{ old("tablePiece")}}" readonly>
                            <span class="text-danger error-text tablePiece_error"></span>
                        </div>
                        <div class="col-md-4 formate" hidden>
                            <label for="idPiece" class="form-label">Id pièce</label>
                            @error("idPiece")<span class="text-danger">{{ $message }}</span> @enderror
                            <input id="idPiece" name="idPiece" type="text" class="form-control @error("idPiece") is-invalid @enderror" placeholder="Saisissez une idPiece" value="{{ old("idPiece")}}" readonly>
                            <span class="text-danger error-text idPiece_error"></span>
                        </div>
                        <!---------------------------------->
                        <div class="col-md-4 formate" hidden>
                            <label for="module" class="form-label">Module</label>
                            @error("module")<span class="text-danger">{{ $message }}</span> @enderror
                            <input id="module" name="module" type="text" class="form-control @error("module") is-invalid @enderror" placeholder="Saisissez le module" value="{{ old("module")}}" readonly>
                            <span class="text-danger error-text module_error"></span>
                        </div>
                        <!---------------------------------->
                        <div class="col-md-4 formate" hidden>
                            <label for="typePiece" class="form-label"> <strong> Type piece </strong></label>
                            @error("typePiece")<span class="text-danger">{{ $message }}</span> @enderror
                            <input id="typePiece" name="typePiece" type="text" class="form-control" placeholder="Saisissez un type pièce" value="{{ old("typePiece")}}" readonly>
                            <span class="text-danger error-text typePiece_error"></span>
                        </div>
                        <br>
                        </div><br>

                        <div class="row">
                            <div class="col-md-8 formate">
                                @error("file")<span class="text-danger">{{ $message }}</span> @enderror
                                <input id="file" name="file" type="file" class="form-control @error("file") is-invalid @enderror" accept="application/pdf" />
                                <span class="text-danger error-text file_error"></span>
                            </div>

                            <div class="col-md-4 formate">
                                <button type="submit" class="btn btn-success"><i class='bx bx-check-circle'></i> Valider l'importation</button> &ensp;
                            </div>
                        </div>

                    </form>

                </div>

                <div id="div_scanner_fichier" hidden>
                    <form id="form1" action="{{ route('declarationNaissance.store.scannerpdf') }}" method="POST" enctype="multipart/form-data" target="_blank">
                        @csrf

                        <div class="row g-3">
                        <h5>Ajouter une pièce</h5>
                        <hr>

                        <div class="row">

                            <div class="mb-2 col-md-6">
                                <label class="form-label">Type pièce d'identité</label>
                                <select id="code_type_document_pere" class="form-control form-control wide" readOnly>
                                    <option value="{{ $dn->pere->document->numero_document }}">{{ $dn->pere->document->typeDocument->lib_type_document }}</option>
                                </select>
                            </div>
                            <div class="mb-2 col-md-6">
                                <label class="form-label">Numéro pièce d'identité</label>
                                <input type="text" name="numero_document_pere" class="form-control form-control wide" reaonly placeholder="Numéro du document" onkeyup="this.value=this.value.toUpperCase()" value="{{ $dn->pere->document->numero_document }}">
                            </div>

                            <div class="col-md-8 formate">
                                <button type="button" onclick="scanToPdfWithThumbnails();" class="btn btn-warning"><i class='bx bx-scan'></i> Scanner le document en <strong>pdf</strong>  </button> &ensp; &ensp;
                            </div>
                            <div class="col-md-4 formate">
                                <button type="button" onclick="submitFormWithScannedImages();" class="btn btn-success"><i class='bx bx-check-circle'></i> Valider le scannage </strong> </button> &ensp; &ensp;
                            </div>

                            <div id="images" style="margin-top: 10px;">  </div>
                            <div id="server_response"> </div>
                        </div>
                    </form>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnImporter" onclick="btn_importer();" class="btn btn-success"><i class='bx bx-upload'></i> Importer le fichier</button> &ensp;
                <button type="button" id="btnScanner" onclick="btn_scanner();" class="btn btn-success"><i class='bx bx-scan'></i> Scanner le(s) fichiers</button> &ensp;
                <button type="button" id="btn_fermer" class="btn btn-danger" data-bs-dismiss="modal"><i class='bx bx-block'></i> Fermer</button>
            </div>

        </div>
    </div>
</div> --}}

{{-- FIN ADD JOINT-PIECE --}}






@endsection
@section("scripts")

<!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

    <script>
        $(function(){
            $("a.show-to-send").on("click", function(){

                var codeDeclaration = $(this).attr('href');

                // $("#code_declaration_naissance").val(codeDeclaration);
                $("#codedeclaration").val(codeDeclaration);

                $("#modal-declaration-send").modal("show");
                return false;
            });

            $("#btn-send").on("click",function(){
                var cdn = $("#codedeclaration").val();
                var route = "{{ route('declarationNaissance.mouvement') }}";
                var data = {
                    code_declaration_naissance:cdn
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


            $("a.show-detail-renvoie").on("click", function(){
                var motif = $(this).attr("title");
                var cdd = $(this).attr("href");
                var cmvtn = $(this).attr("cmouvtnais");
                var obs = $(this).attr("obs");

                $("#codedeclarationback").val(cdd);
                $("#observation").val(obs);
                $("#motif_renvoi").html("<option>"+motif+"</option>");

                $("#modal-declaration-send-back").modal("show");
                return false;
            });

            // //Debut Add piece joint
            $("a.show-piece-parent-modal").on("click", function(){

                var codeDeclaration = $(this).attr('href');

                // $("#code_declaration_naissance").val(codeDeclaration);
                $("#codedeclaration").val(codeDeclaration);

                $("#modal-add-piece-parent").modal("show");
                return false;
            });
            // //Fin Add piece joint

        });
    </script>

@endsection
