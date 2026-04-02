@extends('layout.app')
@section('titre')
Actes de naissance
@endsection
@section("styles")
<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">

    <style>
        .modal-content {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 0.75rem 0.75rem 0 0;
        }

        .modal-footer {
            border-top: 1px solid #dee2e6;
            border-radius: 0 0 0.75rem 0.75rem;
        }

        .card.border-light {
            border: 1px solid #e9ecef !important;
            border-radius: 0.5rem;
        }
    </style>
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
                        <button class="btn btn-sm btn-success mb-2 generate-actes d-none">Générer les actes</button>
                        @endcan
                        @can('module.acteNaissance.signature')
                        <button class="btn btn-sm btn-primary mb-2 validate-actes d-none">Valider les actes</button>
                        <button class="btn btn-sm btn-primary mb-2 validate-on-acte d-none">Valider un acte</button>
                        @endcan
                        <button class="btn btn-sm btn-info mb-2 confirmer-documents d-none">Confirmer les dossiers</button>
                        <button class="btn btn-sm btn-warning mb-2 renvoyer-documents d-none">Renvoyer les dossiers</button>
                    </div>
                </div>
            </div>
            <div class="col-12">

                <div class="card">
                    <div class="card-body">
                        <div class="profile-tab">
                            <div class="custom-tab-1">
                                <ul class="nav nav-tabs nav-pills mb-3" id="naissanceTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a href="#liste-documents"
                                           class="nav-link active"
                                           id="tab-documents"
                                           data-bs-toggle="tab"
                                           role="tab"
                                           aria-controls="liste-documents"
                                           aria-selected="true">
                                            <i class="fas fa-clipboard-check me-1"></i>
                                            Documents à contrôler
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#gestion-actes"
                                           class="nav-link"
                                           id="tab-actes"
                                           data-bs-toggle="tab"
                                           role="tab"
                                           aria-controls="gestion-actes"
                                           aria-selected="false">
                                            <i class="fas fa-tasks me-1"></i>
                                            Gestion des actes
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div id="liste-documents" class="tab-pane fade active show">
                                        <div class="pt-3">
                                            <!-- Formulaire de recherche pour Documents à contrôler -->
                                            <div class="card mb-3">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">
                                                        <i class="fas fa-search me-2"></i>Recherche
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <form id="form-search-documents">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <label class="form-label">N° Déclaration</label>
                                                                <input type="text" class="form-control" name="numero_declaration" id="filter-numero-declaration-documents" placeholder="Rechercher...">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label">Date début</label>
                                                                <input type="date" class="form-control" name="date_debut" id="filter-date-debut-documents">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label">Date fin</label>
                                                                <input type="date" class="form-control" name="date_fin" id="filter-date-fin-documents">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label">Sexe</label>
                                                                <select class="form-control" name="sexe" id="filter-sexe-documents">
                                                                    <option value="">Tous</option>
                                                                    <option value="M">Masculin</option>
                                                                    <option value="F">Féminin</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label">Type de déclaration</label>
                                                                <select class="form-control" name="type_declaration" id="filter-type-documents">
                                                                    <option value="">Tous</option>
                                                                    @foreach($typesDeclaration ?? [] as $type)
                                                                        <option value="{{ $type }}">{{ $type }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label">Statut</label>
                                                                <select class="form-control" name="statut" id="filter-statut-documents">
                                                                    <option value="">Tous</option>
                                                                    <option value="dossier_recu">Dossier reçu</option>
                                                                    <option value="confirme">Confirmé</option>
                                                                    <option value="en_attente">En attente</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-12">
                                                                <button type="submit" class="btn btn-primary">
                                                                    <i class="fas fa-search me-1"></i> Rechercher
                                                                </button>
                                                                <button type="button" class="btn btn-secondary" id="btn-reset-filters-documents">
                                                                    <i class="fas fa-redo me-1"></i> Réinitialiser
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="table-documents-controle" class="table table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th><input type="checkbox" id="check-all-documents"></th>
                                                            <th>N° Déclaration</th>
                                                            <th>Enfant</th>
                                                            <th>Date naissance</th>
                                                            <th>Sexe</th>
                                                            <th>Type Document</th>
                                                            <th>Statut</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbody-documents-controle">
                                                        @include('naissance::acte.partials.table-documents', ['documents' => $documentsAControler])
                                                    </tbody>
                                                    {{-- tfoot retiré pour éviter les conflits avec DataTables --}}
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="gestion-actes" class="tab-pane fade">
                                        <div class="pt-3">
                                            <!-- Formulaire de recherche pour Gestion des actes -->
                                            <div class="card mb-3">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">
                                                        <i class="fas fa-search me-2"></i>Recherche
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <form id="form-search-actes">
                                                        <div class="row">

                                                            <div class="col-md-2">
                                                                <label class="form-label">NIUPP</label>
                                                                <input type="text" class="form-control" name="niupp" id="filter-niupp-actes" placeholder="Rechercher...">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label">Date début</label>
                                                                <input type="date" class="form-control" name="date_debut" id="filter-date-debut-actes">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label">Date fin</label>
                                                                <input type="date" class="form-control" name="date_fin" id="filter-date-fin-actes">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label">Sexe</label>
                                                                <select class="form-control" name="sexe" id="filter-sexe-actes">
                                                                    <option value="">Tous</option>
                                                                    <option value="M">Masculin</option>
                                                                    <option value="F">Féminin</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label">Statut</label>
                                                                <select class="form-control" name="statut" id="filter-statut-actes">
                                                                    <option value="">Tous</option>
                                                                    <option value="en_attente_generation">En attente de génération</option>
                                                                    <option value="en_attente_validation">En attente de validation</option>
                                                                    <option value="valide_non_retire">Validé, non rétiré</option>
                                                                    <option value="retire">Rétiré</option>
                                                                    <option value="annule">Annulé</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-12">
                                                                <button type="submit" class="btn btn-primary">
                                                                    <i class="fas fa-search me-1"></i> Rechercher
                                                                </button>
                                                                <button type="button" class="btn btn-secondary" id="btn-reset-filters-actes">
                                                                    <i class="fas fa-redo me-1"></i> Réinitialiser
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="table-actes-gestion" class="table table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th><input type="checkbox" id="check-all-actes"></th>
                                                            <th>N° Acte</th>
                                                            <th>Enfant</th>
                                                            <th>Date naissance</th>
                                                            <th>Sexe</th>
                                                            <th>Statut</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbody-actes-gestion">
                                                        @include('naissance::acte.partials.table-actes', ['actes' => $actesGestion])
                                                    </tbody>
                                                    {{-- tfoot retiré pour éviter les conflits avec DataTables --}}
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
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
                            <input id="code_declaration_naissance_generate" type="text" class="form-control cacher" readonly value="{{ $registre->code_registre }}">
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
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-shield-alt me-2"></i>Validation de l'acte de naissance
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="code_declaration_naissance_validate">
                <input type="hidden" id="validation_type">

                {{-- Alerte info --}}
                <div class="alert alert-info py-2 mb-3">
                    <i class="fas fa-info-circle me-1"></i>
                    <small>Un code à usage unique a été envoyé par SMS à l'officier d'état civil.
                        Ce code est valide pendant <strong>1 minute</strong>.</small>
                </div>

                <div class="row g-3">
                    {{-- Champ OTP --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">
                            Code de validation <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control form-control-lg text-center fw-bold"
                               id="otp_approbation_mairie"
                               name="otp_approbation_mairie"
                               placeholder="_ _ _ _ _ _ _ _"
                               maxlength="8"
                               inputmode="numeric"
                               pattern="[0-9]{4,8}"
                               autocomplete="one-time-code"
                               required>
                        <small class="text-muted">Saisissez les chiffres reçus par SMS.</small>
                    </div>

                    {{-- Countdown --}}
                    <div class="col-md-6 d-flex flex-column justify-content-center">
                        {{-- Timer actif --}}
                        <div id="otp-timer-block" class="mb-2">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="fas fa-clock text-warning"></i>
                                <span class="small">Code valide encore :
                                    <strong id="otp-countdown" class="text-warning fs-5">60s</strong>
                                </span>
                            </div>
                            <div class="progress" style="height:6px;">
                                <div id="otp-progress"
                                     class="progress-bar bg-warning progress-bar-striped progress-bar-animated"
                                     role="progressbar"
                                     style="width:100%;transition:width 1s linear;">
                                </div>
                            </div>
                        </div>
                        {{-- Timer expiré --}}
                        <div id="otp-expired-block" class="d-none mb-2">
                            <div class="alert alert-danger py-2 mb-1">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <strong>Code expiré.</strong> Veuillez demander un nouveau code.
                            </div>
                        </div>
                        {{-- Bouton renvoyer --}}
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-resend-otp" disabled>
                            <i class="fas fa-redo me-1"></i> Renvoyer le code
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" id="btn-validate">
                    <i class="fas fa-check me-1"></i> Valider
                </button>
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Fermer
                </button>
            </div>
        </div>
    </div>
</div>
{{-- FIN MODAL VALIDATION ACTE DE NAISSACE --}}


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
                        <textarea id="observation" cols="63" rows="5"></textarea>
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

{{-- DEBUT MODAL CONFIRMATION DOSSIERS EN GROUPE --}}
<div class="modal fade" id="modal-confirmation-dossiers-bulk" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation des dossiers sélectionnés</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Information :</strong> Cette action va confirmer que tous les dossiers sélectionnés sont conformes et prêts pour la génération des actes de naissance.
                </div>
                <div class="row">
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Observation (optionnel)</label>
                        <textarea id="observation-confirmation-bulk" class="form-control" rows="3" placeholder="Ajoutez une observation pour tous les dossiers..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-sm text-white" id="btn-confirmer-bulk-final">Confirmer</button>
                <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Annuler</button>
            </div>
        </div>
    </div>
</div>
{{-- FIN MODAL CONFIRMATION DOSSIERS EN GROUPE --}}

{{-- DEBUT MODAL ANNULATION ACTE --}}
<div class="modal fade" id="modal-annulation-acte" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Annulation de l'acte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Attention :</strong> Cette action va annuler définitivement l'acte de naissance. Cette opération est irréversible.
                </div>
                <div class="row">
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Code de la déclaration</label>
                        <input type="text" readonly class="form-control" id="code-declaration-annulation">
                    </div>
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Motif de l'annulation <span class="text-danger">*</span></label>
                        <select id="motif-annulation" class="form-control" required>
                            <option value="" disabled selected>Sélectionner un motif</option>
                            <option value="Erreur matérielle">Erreur matérielle</option>
                            <option value="Fausse déclaration">Fausse déclaration</option>
                            <option value="Documentation insuffisante">Documentation insuffisante</option>
                            <option value="Jugement de tribunal">Jugement de tribunal</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Observation</label>
                        <textarea id="observation-annulation" class="form-control" rows="3" placeholder="Détails sur l'annulation..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger btn-sm text-white" id="btn-annuler-final">Annuler l'acte</button>
                <button type="button" class="btn btn-sm btn-secondary text-white" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
{{-- FIN MODAL ANNULATION ACTE --}}

{{-- MODAL RENVOI EN GROUPE --}}
<div class="modal fade" id="modal-renvoi-dossiers-bulk" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Renvoi des dossiers sélectionnés</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Attention :</strong> Cette action va renvoyer tous les dossiers sélectionnés à l'institution précédente.
                </div>
                <div class="row">
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Motif du renvoi <span class="text-danger">*</span></label>
                        <select id="motif-renvoi-bulk" class="form-control" required>
                            <option value="" disabled selected>Selectionner</option>
                            <option value="erreur materielle">Erreur matérielle</option>
                            <option value="Ajouter nom/prenom">Ajouter nom/prénom</option>
                            <option value="rectifier nom/prenom">Rectifier nom/prénom</option>
                        </select>
                    </div>
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Observation</label>
                        <textarea id="observation-renvoi-bulk" class="form-control" rows="3" placeholder="Ajoutez une observation pour tous les dossiers..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning btn-sm text-white" id="btn-renvoyer-bulk-final">Renvoyer</button>
                <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Annuler</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL ANNULATION EN GROUPE --}}
<div class="modal fade" id="modal-annulation-actes-bulk" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Annulation des actes sélectionnés</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Attention :</strong> Cette action va annuler définitivement tous les actes sélectionnés. Cette opération est irréversible.
                </div>
                <div class="row">
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Motif de l'annulation <span class="text-danger">*</span></label>
                        <select id="motif-annulation-bulk" class="form-control" required>
                            <option value="" disabled selected>Sélectionner un motif</option>
                            <option value="Erreur matérielle">Erreur matérielle</option>
                            <option value="Fausse déclaration">Fausse déclaration</option>
                            <option value="Documentation insuffisante">Documentation insuffisante</option>
                            <option value="Jugement de tribunal">Jugement de tribunal</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Observation</label>
                        <textarea id="observation-annulation-bulk" class="form-control" rows="3" placeholder="Détails sur l'annulation..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger btn-sm text-white" id="btn-annuler-bulk-final">Annuler les actes</button>
                <button type="button" class="btn btn-sm btn-secondary text-white" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

{{-- DEBUT MODAL CONFIRMATION DOSSIER (singleton) --}}
<div class="modal fade" id="modal-confirmation-dossier" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation du dossier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Information :</strong> Cette action va confirmer que le dossier est conforme et prêt pour la génération de l'acte de naissance.
                </div>
                <div class="row">
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Code de la déclaration</label>
                        <input type="text" readonly class="form-control" id="code-declaration-confirmation">
                    </div>
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Observation (optionnel)</label>
                        <textarea id="observation-confirmation" class="form-control" rows="3" placeholder="Ajoutez une observation..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-sm text-white" id="btn-confirmer-final">Confirmer</button>
                <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Annuler</button>
            </div>
        </div>
    </div>
</div>
{{-- FIN MODAL CONFIRMATION DOSSIER --}}

<!-- Modal de suivi génération acte single -->
<div class="modal fade" id="modal-generer-single" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="fa fa-plus-circle me-2"></i>
                    Génération d'acte de naissance
                </h5>
                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="generer-single-message" class="text-center mb-4"></div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fa fa-book me-1"></i>
                            Registre <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa fa-hashtag"></i>
                            </span>
                            <input id="code_registre_naissance"
                                   type="text"
                                   class="form-control"
                                   readonly
                                   value="R.A.N-2026">
                        </div>
                        <small class="form-text text-muted">Registre automatiquement sélectionné</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fa fa-file-text me-1"></i>
                            Numéro du formulaire type <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa fa-barcode"></i>
                            </span>
                            <input id="generer-single-code"
                                   type="text"
                                   class="form-control"
                                   placeholder="Code de déclaration"
                                   readonly>
                        </div>
                        <small class="form-text text-muted">Numéro du formulaire type</small>
                    </div>

                    <div class="col-12">
                        <div class="card border-light">
                            <div class="card-body">
                                <h6 class="card-title text-primary">
                                    <i class="fa fa-exclamation-triangle me-1"></i>
                                    Informations importantes
                                </h6>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-1">
                                        <i class="fa fa-check text-success me-2"></i>
                                        L'acte sera généré avec un code unique
                                    </li>
                                    <li class="mb-1">
                                        <i class="fa fa-check text-success me-2"></i>
                                        Un feuillet de registre sera créé automatiquement pour la production de l'acte
                                    </li>
                                    <li class="mb-1">
                                        <i class="fa fa-check text-success me-2"></i>
                                        L'acte sera en attente de la validation par l'officier d'état civil
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-primary" id="btn-generer-single-confirm">
                    <i class="fa fa-cog me-1"></i>
                    Générer l'acte
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times me-1"></i>
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de validation OTP -->
<div class="modal fade" id="modal-validate-otp" tabindex="-1">
    <div class="modal-dialog">
        <form id="form-validate-otp">
            @csrf
            <input type="hidden" id="otp_code_declaration_naissance" name="code_declaration_naissance">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Validation de l'acte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label for="otp_approbation_mairie" class="form-label">Code OTP reçu</label>
                    <input type="text" class="form-control" id="otp_approbation_mairie" name="otp_approbation_mairie" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Valider</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
@section("scripts")
<!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>
    <script>



    var codesDocuments = [];
    var codesActes = [];
    var actesGeneres = [];
    var actesNonGeneres = [];

    // ── Gestion du timer OTP ─────────────────────────────────────────────
    var otpTimerInterval = null;
    var otpTimerSeconds  = 30;
    var otpExpired       = false;

    function startOtpTimer() {
        clearInterval(otpTimerInterval);
        otpExpired       = false;
        otpTimerSeconds  = 60;

        // Réinitialiser l'UI
        $('#otp-countdown').text('60s').css('color', '');
        $('#otp-progress').css('width', '100%')
                          .removeClass('bg-danger').addClass('bg-warning');
        $('#otp-timer-block').removeClass('d-none');
        $('#otp-expired-block').addClass('d-none');
        $('#btn-validate').prop('disabled', false);
        $('#btn-resend-otp').prop('disabled', true);
        $('#otp_approbation_mairie').prop('disabled', false).val('').trigger('focus');

        otpTimerInterval = setInterval(function () {
            otpTimerSeconds--;
            var pct = Math.round((otpTimerSeconds / 60) * 100);

            $('#otp-countdown').text(otpTimerSeconds + 's');
            $('#otp-progress').css('width', pct + '%');

            // Passage au rouge dans les 10 dernières secondes
            if (otpTimerSeconds <= 10) {
                $('#otp-countdown').css('color', '#DC241F');
                $('#otp-progress').removeClass('bg-warning').addClass('bg-danger');
            }

            if (otpTimerSeconds <= 0) {
                clearInterval(otpTimerInterval);
                otpExpired = true;
                $('#otp-timer-block').addClass('d-none');
                $('#otp-expired-block').removeClass('d-none');
                $('#btn-validate').prop('disabled', true);
                $('#btn-resend-otp').prop('disabled', false);
                $('#otp_approbation_mairie').prop('disabled', true);
            }
        }, 1000);
    }

    // Initialisation des DataTables sans pagination
    // Utiliser une traduction inline pour éviter les erreurs CORS
    var frenchLanguage = {
        "sEmptyTable": "Aucune donnée disponible dans le tableau",
        "sInfo": "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
        "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
        "sInfoFiltered": "(filtré à partir de _MAX_ éléments au total)",
        "sInfoPostFix": "",
        "sInfoThousands": ",",
        "sLengthMenu": "Afficher _MENU_ éléments",
        "sLoadingRecords": "Chargement...",
        "sProcessing": "Traitement...",
        "sSearch": "Rechercher :",
        "sZeroRecords": "Aucun élément correspondant trouvé",
        "oPaginate": {
            "sFirst": "Premier",
            "sLast": "Dernier",
            "sNext": "Suivant",
            "sPrevious": "Précédent"
        },
        "oAria": {
            "sSortAscending": ": activer pour trier la colonne par ordre croissant",
            "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
        }
    };

    var tableDocuments = null;
    var tableActes = null;

    // Initialiser DataTables seulement si la table a des données
    $(document).ready(function() {
        if ($('#table-documents-controle tbody tr').length > 0 && $('#table-documents-controle tbody tr:first').find('td.text-center').length === 0) {
            tableDocuments = $('#table-documents-controle').DataTable({
                "language": frenchLanguage,
                "paging": false,
                "searching": true,
                "info": false,
                "ordering": true,
                "autoWidth": false
            });
        }

        if ($('#table-actes-gestion tbody tr').length > 0 && $('#table-actes-gestion tbody tr:first').find('td.text-center').length === 0) {
            tableActes = $('#table-actes-gestion').DataTable({
                "language": frenchLanguage,
                "paging": false,
                "searching": true,
                "info": false,
                "ordering": true,
                "autoWidth": false
            });
        }
    });

    // Fonction pour rechercher les documents côté serveur
    function searchDocumentsServer() {
        var formData = $('#form-search-documents').serialize();
        formData += '&_token={{ csrf_token() }}';

        $.ajax({
            url: "{{ route('acteNaissance.filter.documents') }}",
            type: 'POST',
            data: formData,
            beforeSend: function() {
                $('#tbody-documents-controle').html('<tr><td colspan="8" class="text-center"><i class="fa fa-spinner fa-spin"></i> Chargement...</td></tr>');
            },
            success: function(response) {
                try {
                    if (response.code === '200') {
                        // Détruire DataTables complètement avant de modifier le contenu
                        if ($.fn.DataTable.isDataTable('#table-documents-controle')) {
                            try {
                                tableDocuments.destroy();
                            } catch(e) {
                                // Erreur silencieuse lors de la destruction de DataTables
                            }
                            tableDocuments = null;
                        }
                        // Vider complètement le tbody et le remplacer par les nouvelles données
                        $('#tbody-documents-controle').empty().html(response.data);

                        // Réinitialiser les checkboxes après le chargement
                        codesDocuments = [];
                        $("#check-all-documents").prop("checked", false);
                        if (typeof updateDocumentButtons === 'function') {
                            updateDocumentButtons();
                        }

                        // Réinitialiser DataTables avec les nouvelles données (même si vide)
                        setTimeout(function() {
                            try {
                                // Vérifier si la table a des données (plus d'une ligne ou pas de classe text-center)
                                var rows = $('#tbody-documents-controle tr');
                                var hasData = rows.length > 0 && rows.first().find('td.text-center').length === 0;

                                if (hasData && rows.length > 0) {
                                    tableDocuments = $('#table-documents-controle').DataTable({
                                        "language": frenchLanguage,
                                        "paging": false,
                                        "searching": true,
                                        "info": false,
                                        "ordering": true,
                                        "destroy": true,
                                        "autoWidth": false,
                                        "columnDefs": [
                                            { "orderable": false, "targets": [0, 7] } // Désactiver le tri sur les colonnes checkbox et actions
                                        ]
                                    });
                                } else {
                                    // Si pas de données réelles, ne pas initialiser DataTables pour éviter les erreurs
                                }
                            } catch(e) {
                                // Erreur silencieuse lors de l'initialisation de DataTables
                            }
                        }, 100);

                        var message = response.count + " résultat(s) trouvé(s)";
                        if (response.limite_atteinte) {
                            message += " (affichage limité à " + response.count_affiché + " résultats). Affinez vos critères pour voir tous les résultats.";
                            flashAlert("Attention", "warning", message);
                        } else {
                            flashAlert("Succès", "success", message);
                        }
                    } else {
                        flashAlert("Erreur", "error", response.message || "Erreur lors de la recherche");
                        $('#tbody-documents-controle').html('<tr><td colspan="8" class="text-center text-danger">Erreur lors de la recherche</td></tr>');
                    }
                } catch(e) {
                    flashAlert("Erreur", "error", "Erreur lors du traitement des résultats");
                    $('#tbody-documents-controle').html('<tr><td colspan="8" class="text-center text-danger">Erreur lors du traitement</td></tr>');
                }
            },
            error: function(xhr) {
                try {
                    if ($.fn.DataTable.isDataTable('#table-documents-controle')) {
                        tableDocuments.destroy();
                        tableDocuments = null;
                    }
                } catch(e) {
                    // Erreur silencieuse lors de la destruction de DataTables
                }
                $('#tbody-documents-controle').html('<tr><td colspan="8" class="text-center text-danger">Erreur lors du chargement</td></tr>');

                // Réinitialiser DataTables même en cas d'erreur
                setTimeout(function() {
                    try {
                        var rows = $('#tbody-documents-controle tr');
                        var hasData = rows.length > 0 && rows.first().find('td.text-center').length === 0;
                        if (hasData && rows.length > 0) {
                            tableDocuments = $('#table-documents-controle').DataTable({
                                "language": frenchLanguage,
                                "paging": false,
                                "searching": true,
                                "info": false,
                                "ordering": true,
                                "destroy": true,
                                "autoWidth": false
                            });
                        }
                    } catch(e) {
                        // Erreur silencieuse lors de l'initialisation de DataTables
                    }
                }, 100);

                var errorMessage = "Erreur lors de la recherche des documents";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    try {
                        var jsonResponse = JSON.parse(xhr.responseText);
                        if (jsonResponse.message) {
                            errorMessage = jsonResponse.message;
                        }
                    } catch(e) {}
                }
                flashAlert("Erreur", "error", errorMessage);
            }
        });
    }

    // Fonction pour rechercher les actes côté serveur
    function searchActesServer() {
        var formData = $('#form-search-actes').serialize();
        formData += '&_token={{ csrf_token() }}';

        $.ajax({
            url: "{{ route('acteNaissance.filter.actes') }}",
            type: 'POST',
            data: formData,
            beforeSend: function() {
                $('#tbody-actes-gestion').html('<tr><td colspan="7" class="text-center"><i class="fa fa-spinner fa-spin"></i> Chargement...</td></tr>');
            },
            success: function(response) {
                try {
                    if (response.code === '200') {
                        // Détruire DataTables complètement avant de modifier le contenu
                        if ($.fn.DataTable.isDataTable('#table-actes-gestion')) {
                            try {
                                tableActes.destroy();
                            } catch(e) {
                                // Erreur silencieuse lors de la destruction de DataTables
                            }
                            tableActes = null;
                        }
                        // Vider complètement le tbody et le remplacer par les nouvelles données
                        $('#tbody-actes-gestion').empty().html(response.data);

                        // Réinitialiser les checkboxes après le chargement
                        codesActes = [];
                        actesGeneres = [];
                        actesNonGeneres = [];
                        if (typeof updateActeButtons === 'function') {
                            updateActeButtons();
                        }

                        // Réinitialiser DataTables avec les nouvelles données (même si vide)
                        setTimeout(function() {
                            try {
                                // Vérifier si la table a des données (plus d'une ligne ou pas de classe text-center)
                                var rows = $('#tbody-actes-gestion tr');
                                var hasData = rows.length > 0 && rows.first().find('td.text-center').length === 0;

                                if (hasData && rows.length > 0) {
                                    tableActes = $('#table-actes-gestion').DataTable({
                                        "language": frenchLanguage,
                                        "paging": false,
                                        "searching": true,
                                        "info": false,
                                        "ordering": true,
                                        "destroy": true,
                                        "autoWidth": false
                                    });
                                } else {
                                    // Si pas de données réelles, ne pas initialiser DataTables pour éviter les erreurs
                                }
                            } catch(e) {
                                // Erreur silencieuse lors de l'initialisation de DataTables
                            }
                        }, 100);

                        var message = response.count + " résultat(s) trouvé(s)";
                        if (response.limite_atteinte) {
                            message += " (affichage limité à " + response.count_affiché + " résultats). Affinez vos critères pour voir tous les résultats.";
                            flashAlert("Attention", "warning", message);
                        } else {
                            flashAlert("Succès", "success", message);
                        }
                    } else {
                        flashAlert("Erreur", "error", response.message || "Erreur lors de la recherche");
                        $('#tbody-actes-gestion').html('<tr><td colspan="7" class="text-center text-danger">Erreur lors de la recherche</td></tr>');
                    }
                } catch(e) {
                    flashAlert("Erreur", "error", "Erreur lors du traitement des résultats");
                    $('#tbody-actes-gestion').html('<tr><td colspan="7" class="text-center text-danger">Erreur lors du traitement</td></tr>');
                }
            },
            error: function(xhr) {
                try {
                    if ($.fn.DataTable.isDataTable('#table-actes-gestion')) {
                        tableActes.destroy();
                        tableActes = null;
                    }
                } catch(e) {
                    // Erreur silencieuse lors de la destruction de DataTables
                }
                $('#tbody-actes-gestion').html('<tr><td colspan="7" class="text-center text-danger">Erreur lors du chargement</td></tr>');

                // Réinitialiser DataTables même en cas d'erreur
                setTimeout(function() {
                    try {
                        var rows = $('#tbody-actes-gestion tr');
                        var hasData = rows.length > 0 && rows.first().find('td.text-center').length === 0;
                        if (hasData && rows.length > 0) {
                            tableActes = $('#table-actes-gestion').DataTable({
                                "language": frenchLanguage,
                                "paging": false,
                                "searching": true,
                                "info": false,
                                "ordering": true,
                                "destroy": true,
                                "autoWidth": false
                            });
                        }
                    } catch(e) {
                        // Erreur silencieuse lors de l'initialisation de DataTables
                    }
                }, 100);

                var errorMessage = "Erreur lors de la recherche des actes";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    try {
                        var jsonResponse = JSON.parse(xhr.responseText);
                        if (jsonResponse.message) {
                            errorMessage = jsonResponse.message;
                        }
                    } catch(e) {}
                }
                flashAlert("Erreur", "error", errorMessage);
            }
        });
    }

    // Soumission du formulaire de recherche Documents
    $('#form-search-documents').on('submit', function(e) {
        e.preventDefault();
        searchDocumentsServer();
    });

    // Soumission du formulaire de recherche Actes
    $('#form-search-actes').on('submit', function(e) {
        e.preventDefault();
        searchActesServer();
    });

    // Réinitialiser les filtres Documents
    $('#btn-reset-filters-documents').on('click', function() {
        $('#form-search-documents')[0].reset();
        // Recharger les données initiales (20 derniers)
        location.reload();
    });

    // Réinitialiser les filtres Actes
    $('#btn-reset-filters-actes').on('click', function() {
        $('#form-search-actes')[0].reset();
        // Recharger les données initiales (20 derniers)
        location.reload();
    });
    // Initialiser la table des actes annulés si elle existe
    if ($('#table-actes-annules').length > 0) {
        $('#table-actes-annules').DataTable({
            "language": frenchLanguage
        });
    }
    $(function() {

    // Gestion des checkboxes pour les documents à contrôler
    $(document).on("change", "#check-all-documents", function(e) {
        e.preventDefault();
        e.stopPropagation();

        if ($(this).is(":checked")) {
            $(".checkbox-document").prop("checked", true);
            codesDocuments = [];
            $(".checkbox-document:checked").each(function() {
                codesDocuments.push($(this).val());
            });
        } else {
            $(".checkbox-document").prop("checked", false);
            codesDocuments = [];
        }
        updateDocumentButtons();
        return false;
    });
    // Utiliser la délégation d'événements pour les éléments dynamiques
    $(document).on("change", ".checkbox-document", function(e) {
        e.preventDefault();
        e.stopPropagation();

        codesDocuments = [];
        $(".checkbox-document:checked").each(function() {
            codesDocuments.push($(this).val());
        });

        // Mettre à jour le checkbox "check-all-documents" en fonction des checkboxes individuelles
        var totalCheckboxes = $(".checkbox-document").length;
        var checkedCheckboxes = $(".checkbox-document:checked").length;
        $("#check-all-documents").prop("checked", totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes);

        updateDocumentButtons();
        return false;
    });

    // Gestion des checkboxes pour les actes transcrits
    $("#check-all-actes").on("change", function() {
        if ($(this).is(":checked")) {
            $(".checkbox-acte").prop("checked", true);
            codesActes = [];
            actesGeneres = [];
            actesNonGeneres = [];
            $(".checkbox-acte:checked").each(function() {
                var value = $(this).val();
                codesActes.push(value.split('-')[0]);
                if (value.split('-')[1] == '1') {
                    actesGeneres.push(value.split('-')[0]);
                } else {
                    actesNonGeneres.push(value.split('-')[0]);
                }
            });
        } else {
            $(".checkbox-acte").prop("checked", false);
            codesActes = [];
            actesGeneres = [];
            actesNonGeneres = [];
        }
        updateActeButtons();
    });
    // Utiliser la délégation d'événements pour les éléments dynamiques
    $(document).on("change", ".checkbox-acte", function() {
        codesActes = [];
        actesGeneres = [];
        actesNonGeneres = [];
        $(".checkbox-acte:checked").each(function() {
            var value = $(this).val();
            codesActes.push(value.split('-')[0]);
            if (value.split('-')[1] == '1') {
                actesGeneres.push(value.split('-')[0]);
            } else {
                actesNonGeneres.push(value.split('-')[0]);
            }
        });
        updateActeButtons();
    });

    // Mise à jour des boutons pour les documents
    function updateDocumentButtons() {
        if (codesDocuments.length > 0) {
            $(".confirmer-documents").removeClass("d-none");
            $(".renvoyer-documents").removeClass("d-none");
        } else {
            $(".confirmer-documents").addClass("d-none");
            $(".renvoyer-documents").addClass("d-none");
        }
    }

    // Mise à jour des boutons pour les actes
    function updateActeButtons() {
        if (actesNonGeneres.length > 0) {
            $(".generate-actes").removeClass("d-none");
        } else {
            $(".generate-actes").addClass("d-none");
        }
        if (actesGeneres.length > 0) {
            $(".validate-actes").removeClass("d-none");
        } else {
            $(".validate-actes").addClass("d-none");
        }
    }

    // Confirmation d'un document individuel
    // Utiliser la délégation d'événements pour les éléments chargés dynamiquement
    $(document).on("click", ".btn-confirmer-document", function() {
        var codeDeclaration = $(this).data('id');
        $("#code-declaration-confirmation").val(codeDeclaration);
        $("#observation-confirmation").val('');
        $("#modal-confirmation-dossier").modal('show');
    });

    // Confirmation de plusieurs documents
    $(document).on("click", ".confirmer-documents", function(){
        if(codesDocuments.length == 0){
            flashAlert("Attention", "warning", "Veuillez sélectionner au moins un document à confirmer.");
            return;
        }
        $("#modal-confirmation-dossiers-bulk").modal('show');
    });

    // Renvoi d'un document individuel
    // Utiliser la délégation d'événements pour les éléments chargés dynamiquement
    $(document).on("click", ".btn-renvoyer-document", function(){
        var codeDeclaration = $(this).data('id');
        $("#codedeclarationback").val(codeDeclaration);
        $("#motif_renvoi").val("");
        $("#observation").val("");
        $("button.btn-send-back").removeClass("d-none");
        $("button.btn-edit-send-back").addClass("d-none");
        $("button.btn-delete-send-back").addClass("d-none");
        $("#modal-declaration-send-back").modal("show");
    });

    // Renvoi de plusieurs documents
    $(document).on("click", ".renvoyer-documents", function(){
        if(codesDocuments.length == 0){
            flashAlert("Attention", "warning", "Veuillez sélectionner au moins un document à renvoyer.");
            return;
        }
        $("#modal-renvoi-dossiers-bulk").modal('show');
    });

    // Annulation d'un acte individuel
    $(".btn-annuler-acte").on("click", function(){
        var codeDeclaration = $(this).data('id');
        $("#code-declaration-annulation").val(codeDeclaration);
        $("#modal-annulation-acte").modal('show');
    });
    $("#btn-annuler-final").on("click", function(){
        var codeDeclaration = $("#code-declaration-annulation").val();
        var motif = $("#motif-annulation").val();
        var observation = $("#observation-annulation").val();
        if(!motif){
            flashAlert("ALERTE","error",'Veuillez sélectionner un motif d\'annulation');
            return;
        }
        sifecBtnLoading(this, "Annulation...");
        var $btn = $(this);
        $.ajax({
            url: "{{ route('acteNaissance.annuler') }}",
            type: 'POST',
            data: {
                code_declaration_naissance: codeDeclaration,
                motif: motif,
                observation: observation,
                _token: '{{ csrf_token() }}'
            },
            success: function(resp){
                sifecBtnReset($btn[0], "Annuler l'acte");
                if(resp.code == "200"){
                    let msg = Array.isArray(resp.message) ? resp.message[0] : (typeof resp.message === 'object' && resp.message.reponse) ? resp.message.reponse : resp.message;
                    flashAlert("Réponse","success",msg);
                    $('#modal-annulation-acte').modal('hide');
                    setTimeout(()=>location.reload(), 1000);
                }else{
                    let msg = Array.isArray(resp.message) ? resp.message[0] : resp.message;
                    flashAlert("Réponse","error",msg);
                }
            },
            error: function(xhr){
                sifecBtnReset($btn[0], "Annuler l'acte");
                let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Erreur lors de l\'annulation de l\'acte';
                flashAlert("Erreur","error",msg);
            }
        });
    });



    // Validation d'actes en lot
    $("button.validate-actes").on("click", function(){
        if(actesGeneres.length > 0){
            var url = "{{ route('acteNaissance.send.otp.bulk') }}";
            var data = {codes:actesGeneres};
            $.post(url,data,function(response){
            if(response.code == "200"){

                $(".over-loader-page").fadeOut(600);
                $("#validation_type").val("bulk");
                $("#modal-validate-acte").modal('show');
                startOtpTimer();

            }else{
                $(".over-loader-page").fadeOut(600);
                //notification("error",response.message);
                // Gestion améliorée des messages d'erreur
                var messageErreur = traiterMessageErreur(response);
                flashAlert("Opération échouée","error",messageErreur);

            }
        });
        }
        return false;
    });

    // Validation OTP (singleton ou bulk)
    $("#btn-validate").on("click", function(){
        // ── Gardes côté client (UX) — la vraie sécurité est dans OtpService côté serveur ──
        var otp = $("#otp_approbation_mairie").val().trim();
        if (!otp) {
            flashAlert("Attention", "warning", "Veuillez saisir le code reçu par SMS.");
            return false;
        }
        if (!/^\d{4,8}$/.test(otp)) {
            flashAlert("Attention", "warning", "Le code doit être composé de chiffres uniquement (4 à 8 chiffres).");
            return false;
        }

        // Désactiver le bouton pour éviter la double soumission
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Validation...');

        var code_declaration_naissance = $("#code_declaration_naissance_validate").val();
        var validation_type = $("#validation_type").val();
        var otp_approbation_mairie = otp;
        var inputs = {
            codes : actesGeneres,
            code_declaration_naissance: code_declaration_naissance,
            otp_approbation_mairie: otp_approbation_mairie
        };
        /**
         * Extrait le texte lisible d'un message renvoyé par le serveur.
         * Formats supportés : string | ["msg"] | {reponse:"..."} | {error:"..."}
         */
        function extraireMsg(message) {
            if (!message) return 'Réponse inconnue du serveur.';
            if (typeof message === 'string')  return message;
            if (Array.isArray(message))       return message[0] || 'Erreur inconnue.';
            if (typeof message === 'object') {
                if (message.reponse) return message.reponse;
                if (message.error)   return message.error;
            }
            return JSON.stringify(message);
        }

        if(validation_type=="simple"){
            $.ajax({
                url: "{{ route('acteNaissance.validate.otp') }}",
                type: 'POST',
                data: {
                    code_declaration_naissance: inputs.code_declaration_naissance,
                    otp_approbation_mairie: inputs.otp_approbation_mairie
                },
                success: function(response){
                    var msg = extraireMsg(response.message);
                    if (response.code === '200') {
                        flashAlert("Succès", "success", msg);
                        clearInterval(otpTimerInterval);
                        $('#modal-validate-acte').modal('hide');
                        setTimeout(function(){ location.reload(); }, 1500);
                    } else {
                        flashAlert("Échec", "error", msg);
                        $('#btn-validate').prop('disabled', false)
                                         .html('<i class="fas fa-check me-1"></i> Valider');
                    }
                },
                error: function(xhr){
                    var msg = (xhr.responseJSON && xhr.responseJSON.message)
                        ? extraireMsg(xhr.responseJSON.message)
                        : 'Erreur lors de la validation de l\'acte.';
                    flashAlert("Erreur", "error", msg);
                    $('#btn-validate').prop('disabled', false)
                                     .html('<i class="fas fa-check me-1"></i> Valider');
                }
            });
        }else{
            $.ajax({
                url: "{{ route('acteNaissance.validate.otp.bulk') }}",
                type: 'POST',
                data: {
                    codes: inputs.codes,
                    otp_approbation_mairie: inputs.otp_approbation_mairie
                },
                success: function(response){
                    var msg = extraireMsg(response.message);
                    if (response.code === '200') {
                        flashAlert("Succès", "success", msg);
                        clearInterval(otpTimerInterval);
                        $('#modal-validate-acte').modal('hide');
                        setTimeout(function(){ location.reload(); }, 1500);
                    } else {
                        flashAlert("Échec", "error", msg);
                        $('#btn-validate').prop('disabled', false)
                                         .html('<i class="fas fa-check me-1"></i> Valider');
                    }
                },
                error: function(xhr){
                    var msg = (xhr.responseJSON && xhr.responseJSON.message)
                        ? extraireMsg(xhr.responseJSON.message)
                        : 'Erreur lors de la validation des actes.';
                    flashAlert("Erreur", "error", msg);
                    $('#btn-validate').prop('disabled', false)
                                     .html('<i class="fas fa-check me-1"></i> Valider');
                }
            });
        }
        return false;
    });

    // ── Renvoyer le code OTP ──────────────────────────────────────────────
    $("#btn-resend-otp").on("click", function() {
        var validationType = $("#validation_type").val();
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Envoi...');

        if (validationType === "simple") {
            var code = $("#code_declaration_naissance_validate").val();
            $.post("{{ route('acteNaissance.send.otp') }}", {
                code_declaration_naissance: code,
                _token: '{{ csrf_token() }}'
            }, function(response){
                if (response.code == "200") {
                    startOtpTimer();
                    flashAlert("Info", "success", "Un nouveau code a été envoyé par SMS.");
                } else {
                    let msg = response.message && response.message.error ? response.message.error : response.message;
                    flashAlert("Erreur", "error", msg);
                    $('#btn-resend-otp').prop('disabled', false).html('<i class="fas fa-redo me-1"></i> Renvoyer le code');
                }
            }).fail(function(){
                flashAlert("Erreur", "error", "Impossible d'envoyer le code.");
                $('#btn-resend-otp').prop('disabled', false).html('<i class="fas fa-redo me-1"></i> Renvoyer le code');
            });
        } else {
            var url  = "{{ route('acteNaissance.send.otp.bulk') }}";
            var data = { codes: actesGeneres, _token: '{{ csrf_token() }}' };
            $.post(url, data, function(response){
                if (response.code == "200") {
                    startOtpTimer();
                    flashAlert("Info", "success", "Un nouveau code a été envoyé par SMS.");
                } else {
                    let msg = typeof response.message === 'object' ? JSON.stringify(response.message) : response.message;
                    flashAlert("Erreur", "error", msg);
                    $('#btn-resend-otp').prop('disabled', false).html('<i class="fas fa-redo me-1"></i> Renvoyer le code');
                }
            }).fail(function(){
                flashAlert("Erreur", "error", "Impossible d'envoyer le code.");
                $('#btn-resend-otp').prop('disabled', false).html('<i class="fas fa-redo me-1"></i> Renvoyer le code');
            });
        }
    });

    // ── Nettoyage du timer à la fermeture du modal ────────────────────────
    $('#modal-validate-acte').on('hidden.bs.modal', function() {
        clearInterval(otpTimerInterval);
        otpExpired = false;
        $('#btn-validate').prop('disabled', false).html('<i class="fas fa-check me-1"></i> Valider');
    });

    // Renvoi individuel (modale)
    $("#btn-send-back").on("click", function(){
        var cdn = $("#codedeclarationback").val();
        var motif = $("#motif_renvoi").val();
        var observation = $("#observation").val();
        var route = "{{ route('acteNaissance.renvoyer') }}";
        var data = {
            code_declaration_naissance: cdn,
            motif_renvoi: motif,
            observation: observation
        };
        $.post(route, data, function(response){
            let msg = Array.isArray(response.message) ? response.message[0] : (typeof response.message === 'object' && response.message.reponse) ? response.message.reponse : response.message;
            flashAlert("Réponse","success",msg);
            $('#modal-declaration-send-back').modal('hide');
            setTimeout(()=>location.reload(), 1000);
        }).fail(function(xhr){
            let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Erreur lors du renvoi du dossier';
            flashAlert("Réponse","error",msg);
        });
        return false;
    });

    // Gestion des onglets
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        // Réinitialiser les sélections lors du changement d'onglet
        codesDocuments = [];
        codesActes = [];
        actesGeneres = [];
        actesNonGeneres = [];
        $(".checkbox-document").prop("checked", false);
        $(".checkbox-acte").prop("checked", false);
        $("#check-all-documents").prop("checked", false);
        $("#check-all-actes").prop("checked", false);
        updateDocumentButtons();
        updateActeButtons();
    });

    // Remplacement du JS de confirmation groupée
    $("#btn-confirmer-bulk-final").on("click", function(){
        var observation = $("#observation-confirmation-bulk").val();
        $.ajax({
            url: "{{ route('acteNaissance.confirmer.bulk') }}",
            type: 'POST',
            data: {
                codes: codesDocuments,
                observation: observation,
                _token: '{{ csrf_token() }}'
            },
            success: function(response){
                let msg = Array.isArray(response.message) ? response.message[0] : (typeof response.message === 'object' && response.message.reponse) ? response.message.reponse : response.message;
                flashAlert("Réponse","success",msg);
                $('#modal-confirmation-dossiers-bulk').modal('hide');
                setTimeout(()=>location.reload(), 1000);
            },
            error: function(xhr){
                let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Erreur lors de la confirmation des documents';
                flashAlert("Réponse","error",msg);
            }
        });
    });

    // Remplacement du JS de renvoi groupé
    $("#btn-renvoyer-bulk-final").on("click", function(){
        var motif = $("#motif-renvoi-bulk").val();
        var observation = $("#observation-renvoi-bulk").val();
        if(!motif){
            let msg = 'Veuillez sélectionner un motif de renvoi';
            flashAlert("ALERTE","error",msg);
            return;
        }
    $.ajax({
            url: "{{ route('acteNaissance.renvoyer.bulk') }}",
            type: 'POST',
            data: {
                codes: codesDocuments,
                motif_renvoi: motif,
                observation: observation,
                _token: '{{ csrf_token() }}'
            },
            success: function(response){
                let msg = Array.isArray(response.message) ? response.message[0] : (typeof response.message === 'object' && response.message.reponse) ? response.message.reponse : response.message;
                flashAlert("Réponse","success",msg);
                $('#modal-renvoi-dossiers-bulk').modal('hide');
                setTimeout(()=>location.reload(), 1000);
            },
            error: function(xhr){
                let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Erreur lors du renvoi des documents';
                flashAlert("Réponse","error",msg);
            }
        });
    });

    // Ajout du JS pour l'annulation groupée
    $(".annuler-actes").on("click", function(){
        if(actesGeneres.length == 0){
            flashAlert("Attention", "warning", "Veuillez sélectionner au moins un acte à annuler.");
            return;
        }
        $("#modal-annulation-actes-bulk").modal('show');
    });
    $("#btn-annuler-bulk-final").on("click", function(){
        var motif = $("#motif-annulation-bulk").val();
        var observation = $("#observation-annulation-bulk").val();
        if(!motif){
            let msg = 'Veuillez sélectionner un motif d\'annulation';
            flashAlert("ALERTE","error",msg);
            return;
        }
        $.ajax({
            url: "{{ route('acteNaissance.annuler.bulk') }}",
            type: 'POST',
            data: {
                codes: actesGeneres,
                motif: motif,
                observation: observation,
                _token: '{{ csrf_token() }}'
            },
            success: function(response){
                let msg = Array.isArray(response.message) ? response.message[0] : (typeof response.message === 'object' && response.message.reponse) ? response.message.reponse : response.message;
                flashAlert("Réponse","success",msg);
                $('#modal-annulation-actes-bulk').modal('hide');
                setTimeout(()=>location.reload(), 1000);
            },
            error: function(xhr){
                let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Erreur lors de l\'annulation des actes';
                flashAlert("Réponse","error",msg);
            }
        });
    });

    // Ajout du JS pour la confirmation singleton
    $("#btn-confirmer-final").on("click", function(){
        var $btn = $(this);
        var codeDeclaration = $("#code-declaration-confirmation").val();
        var observation = $("#observation-confirmation").val();
        sifecBtnLoading(this, "Confirmation...");
        $.ajax({
            url: "{{ route('acteNaissance.confirmer') }}",
            type: 'POST',
            data: {
                code_declaration_naissance: codeDeclaration,
                observation: observation,
                _token: '{{ csrf_token() }}'
            },
            success: function(resp){
                sifecBtnReset($btn[0], "Confirmer");
                if(resp.code == "200"){
                    let msg = Array.isArray(resp.message) ? resp.message[0] : (typeof resp.message === 'object' && resp.message.reponse) ? resp.message.reponse : resp.message;
                    flashAlert("Réponse","success",msg);
                    $('#modal-confirmation-dossier').modal('hide');
                    setTimeout(()=>location.reload(), 1000);
                }else{
                    let msg = Array.isArray(resp.message) ? resp.message[0] : resp.message;
                    flashAlert("Réponse","error",msg);
                }
            },
            error: function(xhr){
                sifecBtnReset($btn[0], "Confirmer");
                let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Erreur lors de la confirmation du dossier';
                flashAlert("Erreur","error",msg);
            }
        });
    });

     // Génération d'actes en lot
     $(document).on("click", "button.generate-actes", function(){
        if(actesNonGeneres.length > 0){

            var url = "{{ route('acteNaissance.generate.bulk') }}";
            var data = {codes:actesNonGeneres};
            $.post(url,data,function(response){
                if(response.code == "200"){
                    $(".over-loader-page").fadeOut(600);
                    flashAlert("Résultat","success",response.message.reponse);
                    $('#modal-generer-single').modal('hide');
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
                    flashAlert("Une erreur est survenue","error",outString);

                }
            });
        }
    return false;
    });

    // Génération d'un acte individuel
    $(document).on("click", ".btn-generer-single", function(){
        var code = $(this).data('id');
        $('#generer-single-code').val(code);
        $('#generer-single-message').html("");
        $('#btn-generer-single-confirm').prop('disabled', false).show();
        $('#modal-generer-single').modal('show');
    });

    $('#btn-generer-single-confirm').on('click', function(){
        var code = $('#generer-single-code').val();
        var url = "{{ route('acteNaissance.generate.single') }}";
        $(this).prop('disabled', true);
        $('#generer-single-message').html('<i class="fa fa-spinner fa-spin"></i> Génération en cours...');

        $.post(url, {code_declaration_naissance: code, _token: '{{ csrf_token() }}'})
        .done(function(response){
            if(response.code == "200"){
                // Message de succès avec gestion des différents formats de réponse
                let successMessage = response.message;
                if (typeof response.message === 'object' && response.message.reponse) {
                    successMessage = response.message.reponse;
                } else if (Array.isArray(response.message)) {
                    successMessage = response.message[0];
                }

                // Utiliser flashAlert pour le message de succès
                flashAlert("Succès", "success", successMessage);

                // Cacher le modal
                $('#modal-generer-single').modal('hide');

                // Recharger la page après un délai
                setTimeout(() => { location.reload(); }, 1000);
            }else{
                // Message d'erreur avec gestion des différents formats
                let errorMessage = response.message;
                if (typeof response.message === 'object' && response.message.reponse) {
                    errorMessage = response.message.reponse;
                } else if (Array.isArray(response.message)) {
                    errorMessage = response.message[0];
                }

                // Utiliser flashAlert pour le message d'erreur
                flashAlert("Erreur", "error", errorMessage);

                // Réactiver le bouton
                $('#btn-generer-single-confirm').prop('disabled', false);
            }
        })
        .fail(function(xhr){
            let errorMessage = 'Erreur lors de la génération de l\'acte';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }

            // Utiliser flashAlert pour le message d'erreur
            flashAlert("Erreur", "error", errorMessage);

            // Réactiver le bouton
            $('#btn-generer-single-confirm').prop('disabled', false);
        });
    });



    $(document).on("click", "#btn-retrait", function(e) {
        e.preventDefault();
        var niupp = $("#leniupp").val();
        var nominteresse = $("#nom_interesse").val();
        var prenominteresse = $("#prenom_interesse").val();
        var telephoneinteresse = $("#telephone_interesse").val();
        if(!nominteresse || !telephoneinteresse) {
            flashAlert("Erreur", "error", "Veuillez renseigner le nom et le téléphone de l'intéressé.");
            return;
        }
        $.ajax({
            url: "{{ route('acteNaissance.retrait') }}",
            type: 'POST',
            data: {
                niupp: niupp,
                nominteresse: nominteresse,
                prenominteresse: prenominteresse,
                telephoneinteresse: telephoneinteresse,
                _token: '{{ csrf_token() }}'
            },
            success: function(resp) {
                if(resp.code == "200"){
                    $(".over-loader-page").fadeOut(600);
                    flashAlert("Résultat","success",resp.message.reponse);
                    setTimeout(() => {
                        location.reload();
                    }, 1000);

                }else{
                    $(".over-loader-page").fadeOut(600);
                    //notification("error",resp.message);
                    // Gestion améliorée des messages d'erreur
                    var messageErreur = traiterMessageErreur(resp);
                    flashAlert("Opération échouée","error",messageErreur);
                }
            },
            error: function(xhr) {
                let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Erreur lors du retrait de l\'acte';
                flashAlert("Erreur", "error", msg);
            }
        });
    });

    // Ouvre la modale de validation OTP (singleton)
    $('.btn-validate-single').on('click', function() {
        var code = $(this).data('id');
        $.post("{{ route('acteNaissance.send.otp') }}", {code_declaration_naissance: code, _token: '{{ csrf_token() }}'}, function(response){
            if(response.code == "200"){
                $('#code_declaration_naissance_validate').val(code);
                $("#validation_type").val("simple");
                $("#modal-validate-acte").modal('show');
                startOtpTimer();
            }else{
                let msg = response.message && response.message.error ? response.message.error : response.message;
                flashAlert("Erreur", "error", msg);
            }
        }).fail(function(xhr){
            let msg = xhr.responseJSON?.message || 'Erreur lors de l\'envoi du code OTP';
            flashAlert("Erreur", "error", msg);
        });
    });


});
</script>
@endsection
