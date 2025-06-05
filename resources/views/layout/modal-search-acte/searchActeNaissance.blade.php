<div class="modal fade" id="modal-search-acte-naissance" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rechercher un acte de naissance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mb-2 col-md-9">
                        <label class="form-label">Numéro de l'acte<span class="text-danger">*</span></label>
                        <span style="color:red;font-size: 15px;"> <div id="error_niupp"></div></span>
                        <input type="text" class="form-control"lass="form-control"  placeholder="Saisir le numéro de l'acte" id="numero_acte" required>
                    </div>
                    <div class="mb-2 col-md-3">
                        <br><br>
                        <button type="submit" class="btn btn-info text-white" id="rechercher">Rechercher</button>
                    </div>
                </div>
                <div class="row" id="resultat">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Résultat de la recherche de l'acte n°  <span id="resultatnumeroacte"></span></h4>
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
