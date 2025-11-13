 Modal époux
<div class="modal fade epoux-search-modal-lg" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-search"></i> Rechercher l'époux</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label class="form-label">Numéro d'acte de naissance <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" required class="form-control" placeholder="Ex: 2024/001/NAI/001" id="numero_acte_naissance_epoux">
                            <button type="button" class="btn btn-info text-white" id="rechercherEpoux">
                                <i class="fa fa-search"></i> Rechercher
                            </button>
                        </div>
                        <small class="text-muted">Saisissez le numéro complet de l'acte de naissance</small>
                    </div>
                </div>

                <!-- Zone d'affichage des résultats -->
                <div class="row mt-3" id="resultats_epoux" style="display: none;">
                    <div class="col-12">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="fa fa-user"></i> Identité trouvée</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Nom et Prénom:</strong><br>
                                        <span id="identite_epoux" class="text-primary"></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Date de naissance:</strong><br>
                                        <span id="date_naissance_result_epoux"></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Sexe:</strong><br>
                                        <span id="sexe_result_epoux"></span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <strong>Lieu de naissance:</strong><br>
                                        <span id="lieu_naissance_result_epoux"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Parents:</strong><br>
                                        <span id="parents_result_epoux"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success text-white" id="confirmer_epoux" style="display: none;">
                    <i class="fa fa-check"></i> Confirmer et remplir le formulaire
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
 {{-- Modal épouse --}}
<div class="modal fade epouse-search-modal-lg" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-search"></i> Rechercher l'épouse</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label class="form-label">Numéro d'acte de naissance <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" required class="form-control" placeholder="Ex: 2024/001/NAI/001" id="numero_acte_naissance_epouse">
                            <button type="button" class="btn btn-info text-white" id="rechercherEpouse">
                                <i class="fa fa-search"></i> Rechercher
                            </button>
                        </div>
                        <small class="text-muted">Saisissez le numéro complet de l'acte de naissance</small>
                    </div>
                </div>

                <!-- Zone d'affichage des résultats -->
                <div class="row mt-3" id="resultats_epouse" style="display: none;">
                    <div class="col-12">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="fa fa-user"></i> Identité trouvée</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Nom et Prénom:</strong><br>
                                        <span id="identite_epouse" class="text-primary"></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Date de naissance:</strong><br>
                                        <span id="date_naissance_result_epouse"></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Sexe:</strong><br>
                                        <span id="sexe_result_epouse"></span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <strong>Lieu de naissance:</strong><br>
                                        <span id="lieu_naissance_result_epouse"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Parents:</strong><br>
                                        <span id="parents_result_epouse"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success text-white" id="confirmer_epouse" style="display: none;">
                    <i class="fa fa-check"></i> Confirmer et remplir le formulaire
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>


<!-- Modales de recherche pour les témoins -->

<!-- Modal recherche Témoin 1 Époux -->
<div class="modal fade temoin-epoux-1-search-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rechercher Témoin 1 Époux</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> <strong>Note importante :</strong> Seules les personnes âgées de plus de 18 ans peuvent être témoins de mariage.
                </div>
                <div class="row">
                    <div class="mb-2 col-md-6">
                        <label class="form-label">Nom(s) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nom_recherche_temoin_epoux_1" placeholder="Entrez le nom">
                    </div>
                    <div class="mb-2 col-md-6">
                        <label class="form-label">Prénom(s)</label>
                        <input type="text" class="form-control" id="prenom_recherche_temoin_epoux_1" placeholder="Entrez le prénom">
                    </div>
                </div>
                <div class="row">
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Sexe</label>
                        <select class="form-control" id="sexe_recherche_temoin_epoux_1">
                            <option value="">Tous</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Date de naissance</label>
                        <input type="date" class="form-control" id="date_naissance_recherche_temoin_epoux_1">
                    </div>
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Lieu de naissance</label>
                        <select class="form-control" id="lieu_recherche_temoin_epoux_1">
                            <option value="">Tous</option>
                            @foreach ($lieuNaissances as $localite)
                                <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-info text-white" onclick="rechercherTemoin('epoux', 1)">
                        <i class="fa fa-search"></i> Rechercher
                    </button>
                </div>
                <div id="resultats_recherche_temoin_epoux_1" class="mt-3" style="display: none;">
                    <!-- Résultats de recherche -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal recherche Témoin 2 Époux -->
<div class="modal fade temoin-epoux-2-search-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rechercher Témoin 2 Époux</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> <strong>Note importante :</strong> Seules les personnes âgées de plus de 18 ans peuvent être témoins de mariage.
                </div>
                <div class="row">
                    <div class="mb-2 col-md-6">
                        <label class="form-label">Nom(s) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nom_recherche_temoin_epoux_2" placeholder="Entrez le nom">
                    </div>
                    <div class="mb-2 col-md-6">
                        <label class="form-label">Prénom(s)</label>
                        <input type="text" class="form-control" id="prenom_recherche_temoin_epoux_2" placeholder="Entrez le prénom">
                    </div>
                </div>
                <div class="row">
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Sexe</label>
                        <select class="form-control" id="sexe_recherche_temoin_epoux_2">
                            <option value="">Tous</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Date de naissance</label>
                        <input type="date" class="form-control" id="date_naissance_recherche_temoin_epoux_2">
                    </div>
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Lieu de naissance</label>
                        <select class="form-control" id="lieu_recherche_temoin_epoux_2">
                            <option value="">Tous</option>
                            @foreach ($lieuNaissances as $localite)
                                <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-info text-white" onclick="rechercherTemoin('epoux', 2)">
                        <i class="fa fa-search"></i> Rechercher
                    </button>
                </div>
                <div id="resultats_recherche_temoin_epoux_2" class="mt-3" style="display: none;">
                    <!-- Résultats de recherche -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal recherche Témoin 1 Épouse -->
<div class="modal fade temoin-epouse-1-search-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rechercher Témoin 1 Épouse</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> <strong>Note importante :</strong> Seules les personnes âgées de plus de 18 ans peuvent être témoins de mariage.
                </div>
                <div class="row">
                    <div class="mb-2 col-md-6">
                        <label class="form-label">Nom(s) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nom_recherche_temoin_epouse_1" placeholder="Entrez le nom">
                    </div>
                    <div class="mb-2 col-md-6">
                        <label class="form-label">Prénom(s)</label>
                        <input type="text" class="form-control" id="prenom_recherche_temoin_epouse_1" placeholder="Entrez le prénom">
                    </div>
                </div>
                <div class="row">
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Sexe</label>
                        <select class="form-control" id="sexe_recherche_temoin_epouse_1">
                            <option value="">Tous</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Date de naissance</label>
                        <input type="date" class="form-control" id="date_naissance_recherche_temoin_epouse_1">
                    </div>
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Lieu de naissance</label>
                        <select class="form-control" id="lieu_recherche_temoin_epouse_1">
                            <option value="">Tous</option>
                            @foreach ($lieuNaissances as $localite)
                                <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-info text-white" onclick="rechercherTemoin('epouse', 1)">
                        <i class="fa fa-search"></i> Rechercher
                    </button>
                </div>
                <div id="resultats_recherche_temoin_epouse_1" class="mt-3" style="display: none;">
                    <!-- Résultats de recherche -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal recherche Témoin 2 Épouse -->
<div class="modal fade temoin-epouse-2-search-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rechercher Témoin 2 Épouse</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> <strong>Note importante :</strong> Seules les personnes âgées de plus de 18 ans peuvent être témoins de mariage.
                </div>
                <div class="row">
                    <div class="mb-2 col-md-6">
                        <label class="form-label">Nom(s) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nom_recherche_temoin_epouse_2" placeholder="Entrez le nom">
                    </div>
                    <div class="mb-2 col-md-6">
                        <label class="form-label">Prénom(s)</label>
                        <input type="text" class="form-control" id="prenom_recherche_temoin_epouse_2" placeholder="Entrez le prénom">
                    </div>
                </div>
                <div class="row">
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Sexe</label>
                        <select class="form-control" id="sexe_recherche_temoin_epouse_2">
                            <option value="">Tous</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Date de naissance</label>
                        <input type="date" class="form-control" id="date_naissance_recherche_temoin_epouse_2">
                    </div>
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Lieu de naissance</label>
                        <select class="form-control" id="lieu_recherche_temoin_epouse_2">
                            <option value="">Tous</option>
                            @foreach ($lieuNaissances as $localite)
                                <option value="{{ $localite->code_localite }}">{{ $localite->lib_localite }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-info text-white" onclick="rechercherTemoin('epouse', 2)">
                        <i class="fa fa-search"></i> Rechercher
                    </button>
                </div>
                <div id="resultats_recherche_temoin_epouse_2" class="mt-3" style="display: none;">
                    <!-- Résultats de recherche -->
                </div>
            </div>
        </div>
    </div>
</div>
