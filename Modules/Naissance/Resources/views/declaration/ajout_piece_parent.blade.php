<div class="modal fade" id="modalListePiecePere" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Liste des pièces</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                {{-- <div class="row">
                    <div class="col-md-12">
                        <div id="pieceAffiche" class="table-responsive">
                            <table id="table" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Réf.</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Fichier</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <body>

                                </body>
                            </table>
                        </div>
                    </div>
                </div> <br> --}}
                <!---------------------------------->
                <div id="div_importer_fichier" hidden>
                    {{-- <form  method="post" id="formPiece" action="{{route("referentiel.tpce.store")}}" enctype="multipart/form-data"> --}}
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
                        <!---------------------------------->
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


                <!---------------------------------->
                <div id="div_scanner_fichier" hidden>
                    <form id="form1" action="{{ route('declarationNaissance.store.scannerpdf') }}" method="POST" enctype="multipart/form-data" target="_blank">
                        @csrf

                        <div class="row g-3">
                        <h5>Ajouter une pièce</h5>
                        <hr>

                        {{-- <div class="col-md-4 formate">
                            <label for="C_REF_PCES" class="form-label">Référence</label>
                            @error("C_REF_PCES")<span class="text-danger">{{ $message }}</span> @enderror
                            <input id="C_REF_PCES" name="C_REF_PCES" type="text" class="form-control @error("C_REF_PCES") is-invalid @enderror" placeholder="Saisissez une référence">
                            <span class="text-danger error-text C_REF_PCES_error"></span>
                        </div>
                        <!---------------------------------->
                        <div class="col-md-4 formate">
                            <label for="D_DT_PCES" class="form-label">Date pièce</label>
                            @error("D_DT_PCES")<span class="text-danger">{{ $message }}</span> @enderror
                            <input id="D_DT_PCES" name="D_DT_PCES" type="text" class="form-control datepicker @error("D_DT_PCES") is-invalid @enderror" placeholder="Saisissez la date" value="{{ old("D_DT_PCES")}}">
                            <span class="text-danger error-text D_DT_PCES_error"></span>
                        </div>
                        <!---------------------------------->
                        <div class="col-md-4 formate">
                            <label for="L_OBS_PCES" class="form-label"> <strong> Type </strong></label>
                            @error("L_OBS_PCES")<span class="text-danger">{{ $message }}</span> @enderror
                            <input list="listTypePiece" id="L_OBS_PCES" name="L_OBS_PCES" type="text" class="form-control" placeholder="Saisissez ou sélectionnez" onkeyup="this.value=this.value.toUpperCase()">
                            <datalist id="listTypePiece">

                            </datalist>
                            <span class="text-danger error-text L_OBS_PCES_error"></span>
                        </div>

                            <div class="col-md-4 formate" hidden>
                                <label for="tablePieces" class="form-label">table </label>
                                @error("tablePieces")<span class="text-danger">{{ $message }}</span> @enderror
                                <input id="tablePieces" name="tablePieces" type="text" class="form-control @error("tablePieces") is-invalid @enderror" placeholder="Saisissez une table" value="{{ old("tablePieces")}}" readonly>
                                <span class="text-danger error-text tablePieces_error"></span>
                            </div>
                        <div class="col-md-4 formate" hidden>
                            <label for="idPieces" class="form-label">Id pièce</label>
                            @error("idPieces")<span class="text-danger">{{ $message }}</span> @enderror
                            <input id="idPieces" name="idPieces" type="text" class="form-control @error("idPieces") is-invalid @enderror" placeholder="Saisissez une idPieces" value="{{ old("idPieces")}}" readonly>
                            <span class="text-danger error-text idPieces_error"></span>
                        </div>
                        <!---------------------------------->
                        <div class="col-md-4 formate" hidden>
                            <label for="modules" class="form-label">Module</label>
                            @error("modules")<span class="text-danger">{{ $message }}</span> @enderror
                            <input id="modules" name="modules" type="text" class="form-control @error("modules") is-invalid @enderror" placeholder="Saisissez le modules" value="{{ old("modules")}}" readonly>
                            <span class="text-danger error-text modules_error"></span>
                        </div>
                        <!---------------------------------->
                        <div class="col-md-4 formate" hidden>
                            <label for="typePieces" class="form-label"> <strong> Type piece </strong></label>
                            @error("typePieces")<span class="text-danger">{{ $message }}</span> @enderror
                            <input id="typePieces" name="typePieces" type="text" class="form-control" placeholder="Saisissez un type pièce" value="{{ old("typePieces")}}" readonly>
                            <span class="text-danger error-text typePieces_error"></span>
                        </div>
                        <br>
                        </div><br> --}}
                        <div class="row">
                            <div class="mb-2 col-md-6">
                                <label class="form-label">Type pièce d'identité</label>
                                <select id="code_type_document_pere" class="form-control form-control wide" readonly>
                                    @if($dn->pere?->document)
                                        <option value="{{ $dn->pere->document->numero_document ?? '' }}">{{ optional($dn->pere->document->typeDocument)->lib_type_document ?? '—' }}</option>
                                    @else
                                        <option value="">— Non renseigné (formulaire père) —</option>
                                    @endif
                                </select>
                            </div>
                            <div class="mb-2 col-md-6">
                                <label class="form-label">Numéro pièce d'identité</label>
                                <input type="text" name="numero_document_pere" class="form-control form-control wide" readonly placeholder="Numéro du document" onkeyup="this.value=this.value.toUpperCase()" value="{{ $dn->pere?->document?->numero_document ?? '' }}">
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
</div>



<div class="modal fade" id="modalListePieceMere" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Liste des pièces</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                {{-- <div class="row">
                    <div class="col-md-12">
                        <div id="pieceAffiche" class="table-responsive">
                            <table id="table" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Réf.</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Fichier</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <body>

                                </body>
                            </table>
                        </div>
                    </div>
                </div> <br> --}}
                <!---------------------------------->
                <div id="div_importer_fichier" hidden>
                    {{-- <form  method="post" id="formPiece" action="{{route("referentiel.tpce.store")}}" enctype="multipart/form-data"> --}}
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
                        <!---------------------------------->
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


                <!---------------------------------->
                <div id="div_scanner_fichier" hidden>
                    <form id="form1" action="{{ route('declarationNaissance.store.scannerpdf') }}" method="POST" enctype="multipart/form-data" target="_blank">
                        @csrf

                        <div class="row g-3">
                        <h5>Ajouter une pièce</h5>
                        <hr>

                        {{-- <div class="col-md-4 formate">
                            <label for="C_REF_PCES" class="form-label">Référence</label>
                            @error("C_REF_PCES")<span class="text-danger">{{ $message }}</span> @enderror
                            <input id="C_REF_PCES" name="C_REF_PCES" type="text" class="form-control @error("C_REF_PCES") is-invalid @enderror" placeholder="Saisissez une référence">
                            <span class="text-danger error-text C_REF_PCES_error"></span>
                        </div>
                        <!---------------------------------->
                        <div class="col-md-4 formate">
                            <label for="D_DT_PCES" class="form-label">Date pièce</label>
                            @error("D_DT_PCES")<span class="text-danger">{{ $message }}</span> @enderror
                            <input id="D_DT_PCES" name="D_DT_PCES" type="text" class="form-control datepicker @error("D_DT_PCES") is-invalid @enderror" placeholder="Saisissez la date" value="{{ old("D_DT_PCES")}}">
                            <span class="text-danger error-text D_DT_PCES_error"></span>
                        </div>
                        <!---------------------------------->
                        <div class="col-md-4 formate">
                            <label for="L_OBS_PCES" class="form-label"> <strong> Type </strong></label>
                            @error("L_OBS_PCES")<span class="text-danger">{{ $message }}</span> @enderror
                            <input list="listTypePiece" id="L_OBS_PCES" name="L_OBS_PCES" type="text" class="form-control" placeholder="Saisissez ou sélectionnez" onkeyup="this.value=this.value.toUpperCase()">
                            <datalist id="listTypePiece">

                            </datalist>
                            <span class="text-danger error-text L_OBS_PCES_error"></span>
                        </div>

                            <div class="col-md-4 formate" hidden>
                                <label for="tablePieces" class="form-label">table </label>
                                @error("tablePieces")<span class="text-danger">{{ $message }}</span> @enderror
                                <input id="tablePieces" name="tablePieces" type="text" class="form-control @error("tablePieces") is-invalid @enderror" placeholder="Saisissez une table" value="{{ old("tablePieces")}}" readonly>
                                <span class="text-danger error-text tablePieces_error"></span>
                            </div>
                        <div class="col-md-4 formate" hidden>
                            <label for="idPieces" class="form-label">Id pièce</label>
                            @error("idPieces")<span class="text-danger">{{ $message }}</span> @enderror
                            <input id="idPieces" name="idPieces" type="text" class="form-control @error("idPieces") is-invalid @enderror" placeholder="Saisissez une idPieces" value="{{ old("idPieces")}}" readonly>
                            <span class="text-danger error-text idPieces_error"></span>
                        </div>
                        <!---------------------------------->
                        <div class="col-md-4 formate" hidden>
                            <label for="modules" class="form-label">Module</label>
                            @error("modules")<span class="text-danger">{{ $message }}</span> @enderror
                            <input id="modules" name="modules" type="text" class="form-control @error("modules") is-invalid @enderror" placeholder="Saisissez le modules" value="{{ old("modules")}}" readonly>
                            <span class="text-danger error-text modules_error"></span>
                        </div>
                        <!---------------------------------->
                        <div class="col-md-4 formate" hidden>
                            <label for="typePieces" class="form-label"> <strong> Type piece </strong></label>
                            @error("typePieces")<span class="text-danger">{{ $message }}</span> @enderror
                            <input id="typePieces" name="typePieces" type="text" class="form-control" placeholder="Saisissez un type pièce" value="{{ old("typePieces")}}" readonly>
                            <span class="text-danger error-text typePieces_error"></span>
                        </div>
                        <br>
                        </div><br> --}}
                        <div class="row">
                            <div class="mb-2 col-md-6">
                                <label class="form-label">Type pièce d'identité</label>
                                <select id="code_type_document_mere" class="form-control form-control wide" readonly>
                                    @if($dn->mere?->document)
                                        <option value="{{ $dn->mere->document->numero_document ?? '' }}">{{ optional($dn->mere->document->typeDocument)->lib_type_document ?? '—' }}</option>
                                    @else
                                        <option value="">— Non renseigné (formulaire mère) —</option>
                                    @endif
                                </select>
                            </div>
                            <div class="mb-2 col-md-6">
                                <label class="form-label">Numéro pièce d'identité</label>
                                <input type="text" name="numero_document_mere" class="form-control form-control wide" readonly placeholder="Numéro du document" onkeyup="this.value=this.value.toUpperCase()" value="{{ $dn->mere?->document?->numero_document ?? '' }}">
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
</div>
