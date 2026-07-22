@extends('layout.app')
@section('titre')
Actes de naissance
@endsection
@section("styles")
<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">

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
    </style>
@endsection

@section('corps')
<div class="page-sifec-index">
    <div class="an-shell">
        <header class="an-hero">
            <div class="an-hero-text">
                <h1>Liste des actes de naissance</h1>
            </div>
            <div id="dupcreer" class="an-toolbar">
                @can('module.acteNaissance.generate')
                <button type="button" class="btn btn-sm btn-success generate-actes d-none">Générer les actes</button>
                @endcan
                @can('module.acteNaissance.signature')
                <button type="button" class="btn btn-sm btn-primary validate-actes d-none">Valider les actes</button>
                <button type="button" class="btn btn-sm btn-primary validate-on-acte d-none">Valider un acte</button>
                @endcan
                <button type="button" class="btn btn-sm btn-info text-white confirmer-documents d-none">Confirmer les dossiers</button>
                <button type="button" class="btn btn-sm btn-warning text-dark renvoyer-documents d-none">Renvoyer les dossiers</button>
            </div>
        </header>

        <div class="an-body">
            <div class="an-tabs mb-3">
                <ul class="nav nav-pills" id="naissanceTabs" role="tablist">
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
            </div>

            <div class="tab-content">
                <div id="liste-documents" class="tab-pane fade show active">
                    <div class="an-hint an-hint--step1" role="note">
                        <span class="an-hint__icon" aria-hidden="true"><span class="fw-bold small">1</span></span>
                        <div>
                            <strong>Étape 1 — Validation au CEC.</strong>
                            Confirmez le dossier (certificat → déclaration de naissance), puis passez à l’onglet
                            <em>Gestion des actes</em> pour générer l’acte et la signature électronique.
                            Les dossiers déjà validés restent listés ici : utilisez les actions pour ouvrir les PDF (certificat, déclaration générée).
                        </div>
                    </div>

                    <div class="card an-filter-card shadow-none">
                        <div class="card-header">
                            <h2 class="card-title mb-0">
                                <i class="fas fa-search me-2 text-secondary"></i>Filtrer les documents
                            </h2>
                        </div>
                        <div class="card-body">
                            <form id="form-search-documents">
                                <div class="row g-2 g-md-3">
                                    <div class="col-6 col-md-4 col-lg-2">
                                        <label class="form-label" for="filter-numero-declaration-documents">N° déclaration</label>
                                        <input type="text" class="form-control" name="numero_declaration" id="filter-numero-declaration-documents" placeholder="Rechercher…">
                                    </div>
                                    <div class="col-6 col-md-4 col-lg-2">
                                        <label class="form-label" for="filter-date-debut-documents">Date début</label>
                                        <input type="date" class="form-control" name="date_debut" id="filter-date-debut-documents">
                                    </div>
                                    <div class="col-6 col-md-4 col-lg-2">
                                        <label class="form-label" for="filter-date-fin-documents">Date fin</label>
                                        <input type="date" class="form-control" name="date_fin" id="filter-date-fin-documents">
                                    </div>
                                    <div class="col-6 col-md-4 col-lg-2">
                                        <label class="form-label" for="filter-sexe-documents">Sexe</label>
                                        <select class="form-control" name="sexe" id="filter-sexe-documents">
                                            <option value="">Tous</option>
                                            <option value="M">Masculin</option>
                                            <option value="F">Féminin</option>
                                        </select>
                                    </div>
                                    <div class="col-6 col-md-4 col-lg-2">
                                        <label class="form-label" for="filter-type-documents">Type de déclaration</label>
                                        <select class="form-control" name="type_declaration" id="filter-type-documents">
                                            <option value="">Tous</option>
                                            @foreach($typesDeclaration ?? [] as $type)
                                                <option value="{{ $type }}">{{ $type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6 col-md-4 col-lg-2">
                                        <label class="form-label" for="filter-statut-documents">Statut</label>
                                        <select class="form-control" name="statut" id="filter-statut-documents">
                                            <option value="">Tous</option>
                                            <option value="dossier_recu">Dossier reçu</option>
                                            <option value="confirme">Confirmé</option>
                                            <option value="en_attente">En attente</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12 d-flex flex-wrap gap-2">
                                        <button type="submit" class="btn btn-primary an-btn-search text-white">
                                            <i class="fas fa-search me-1"></i>Rechercher
                                        </button>
                                        <button type="button" class="btn btn-secondary an-btn-reset" id="btn-reset-filters-documents">
                                            <i class="fas fa-redo me-1"></i>Réinitialiser
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="an-table-wrap">
                        <div class="table-responsive">
                            <table id="table-documents-controle" class="table table-hover an-data-table mb-0">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="check-all-documents" aria-label="Tout sélectionner"></th>
                                        <th>N° déclaration</th>
                                        <th>Enfant</th>
                                        <th>Date naissance</th>
                                        <th>Sexe</th>
                                        <th>Type document</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-documents-controle">
                                    @include('naissance::acte.partials.table-documents', ['documents' => $documentsAControler])
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="gestion-actes" class="tab-pane fade">
                    <div class="an-hint an-hint--step2" role="note">
                        <span class="an-hint__icon" aria-hidden="true"><span class="fw-bold small">2–3</span></span>
                        <div>
                            <strong>Étapes 2 et 3 — Acte et signature.</strong>
                            Colonne <em>Étape</em> : génération de l’acte, puis signature électronique par l’officier (certificat .p12).
                            Les dossiers les plus urgents (sans acte, puis acte non validé) apparaissent en tête sur l’accueil (20 derniers).
                        </div>
                    </div>

                    <div class="card an-filter-card shadow-none">
                        <div class="card-header">
                            <h2 class="card-title mb-0">
                                <i class="fas fa-search me-2 text-secondary"></i>Filtrer les actes
                            </h2>
                        </div>
                        <div class="card-body">
                            <form id="form-search-actes">
                                <div class="row g-2 g-md-3">
                                    <div class="col-6 col-md-4 col-lg-2">
                                        <label class="form-label" for="filter-niupp-actes">NIUPP</label>
                                        <input type="text" class="form-control" name="niupp" id="filter-niupp-actes" placeholder="Rechercher…">
                                    </div>
                                    <div class="col-6 col-md-4 col-lg-2">
                                        <label class="form-label" for="filter-date-debut-actes">Date début</label>
                                        <input type="date" class="form-control" name="date_debut" id="filter-date-debut-actes">
                                    </div>
                                    <div class="col-6 col-md-4 col-lg-2">
                                        <label class="form-label" for="filter-date-fin-actes">Date fin</label>
                                        <input type="date" class="form-control" name="date_fin" id="filter-date-fin-actes">
                                    </div>
                                    <div class="col-6 col-md-4 col-lg-2">
                                        <label class="form-label" for="filter-sexe-actes">Sexe</label>
                                        <select class="form-control" name="sexe" id="filter-sexe-actes">
                                            <option value="">Tous</option>
                                            <option value="M">Masculin</option>
                                            <option value="F">Féminin</option>
                                        </select>
                                    </div>
                                    <div class="col-6 col-md-4 col-lg-2">
                                        <label class="form-label" for="filter-statut-actes">Statut</label>
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
                                    <div class="col-12 d-flex flex-wrap gap-2">
                                        <button type="submit" class="btn btn-primary an-btn-search text-white">
                                            <i class="fas fa-search me-1"></i>Rechercher
                                        </button>
                                        <button type="button" class="btn btn-secondary an-btn-reset" id="btn-reset-filters-actes">
                                            <i class="fas fa-redo me-1"></i>Réinitialiser
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="an-table-wrap">
                        <div class="table-responsive">
                            <table id="table-actes-gestion" class="table table-hover an-data-table mb-0">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="check-all-actes" aria-label="Tout sélectionner"></th>
                                        <th>N° acte</th>
                                        <th>Enfant</th>
                                        <th>Date naissance</th>
                                        <th>Sexe</th>
                                        <th>Étape</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-actes-gestion">
                                    @include('naissance::acte.partials.table-actes', ['actes' => $actesGestion])
                                </tbody>
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
                <button type="button" class="btn btn-sm btn-primary generate cacher">Valider</button>
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Fermer</button>
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

{{-- DEBUT MODAL VALIDATION ACTE DE NAISSANCE (signature électronique) --}}
<div class="modal fade" id="modal-validate-acte" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-signature me-2"></i>Signature électronique de l’acte
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="code_declaration_naissance_validate">
                <input type="hidden" id="validation_type">

                <div class="alert alert-info py-2 mb-3">
                    <p class="mb-2 small fw-semibold">Comment procéder</p>
                    <ol class="small mb-2 ps-3">
                        <li>Vérifiez l’acte (ou la sélection) à valider.</li>
                        <li>Sélectionnez votre fichier certificat <strong>.p12</strong> et saisissez sa passphrase.</li>
                        <li>Cliquez sur <strong>Signer électroniquement</strong> : votre identité valide et scelle l’acte.</li>
                        <li>Le numéro d’acte (NIUPP) et le PDF officiel sont produits ; le déclarant peut être notifié.</li>
                    </ol>
                    <p class="mb-0 small text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Aucun code SMS n’est demandé. Utilisez uniquement le certificat personnel téléchargé lors de votre enrôlement.
                        L’institution doit avoir son cachet institutionnel configuré.
                    </p>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-7">
                        <label class="form-label small fw-semibold" for="acte_p12_file">Certificat électronique (.p12)</label>
                        <input type="file" class="form-control form-control-sm" id="acte_p12_file" accept=".p12,.pfx,application/x-pkcs12">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-semibold" for="acte_p12_pin">Passphrase</label>
                        <input type="password" class="form-control form-control-sm" id="acte_p12_pin" autocomplete="off" placeholder="Passphrase du certificat">
                    </div>
                </div>

                <div id="guot-sign-feedback" class="alert alert-warning py-2 small d-none mb-0" role="status"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" id="btn-validate">
                    <i class="fas fa-signature me-1"></i> Signer électroniquement
                </button>
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Fermer
                </button>
            </div>
        </div>
    </div>
</div>
{{-- FIN MODAL VALIDATION ACTE DE NAISSANCE --}}


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
                    <strong>Information :</strong> La confirmation signe électroniquement les déclarations de naissance sélectionnées
                    (responsable du centre d'état civil) puis les rend prêtes pour la génération des actes.
                </div>
                <div class="row">
                    <div class="col-md-7 mb-2">
                        <label class="form-label small fw-semibold" for="decl_bulk_p12_file">Certificat électronique (.p12)</label>
                        <input type="file" class="form-control form-control-sm" id="decl_bulk_p12_file" accept=".p12,.pfx,application/x-pkcs12">
                    </div>
                    <div class="col-md-5 mb-2">
                        <label class="form-label small fw-semibold" for="decl_bulk_p12_pin">Passphrase</label>
                        <input type="password" class="form-control form-control-sm" id="decl_bulk_p12_pin" autocomplete="off" placeholder="Passphrase du certificat">
                    </div>
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Observation (optionnel)</label>
                        <textarea id="observation-confirmation-bulk" class="form-control" rows="3" placeholder="Ajoutez une observation pour tous les dossiers..."></textarea>
                    </div>
                    <div class="col-md-12">
                        <div id="decl-bulk-sign-feedback" class="alert alert-warning py-2 small d-none mb-0" role="status"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-sm text-white" id="btn-confirmer-bulk-final">
                    <i class="fas fa-signature me-1"></i> Signer et confirmer
                </button>
                <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Annuler</button>
            </div>
        </div>
    </div>
</div>
{{-- FIN MODAL CONFIRMATION DOSSIERS EN GROUPE --}}

{{-- DEBUT MODAL ANNULATION ACTE (signature électronique) --}}
<div class="modal fade" id="modal-annulation-acte" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file-signature me-2"></i>Annulation de l'acte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Attention :</strong> Cette action annule définitivement l'acte de naissance. Opération irréversible — confirmation par certificat électronique (.p12) requise.
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
                    <div class="col-md-7 mb-2">
                        <label class="form-label small fw-semibold" for="annulation_p12_file">Certificat électronique (.p12)</label>
                        <input type="file" class="form-control form-control-sm" id="annulation_p12_file" accept=".p12,.pfx,application/x-pkcs12">
                    </div>
                    <div class="col-md-5 mb-2">
                        <label class="form-label small fw-semibold" for="annulation_p12_pin">Passphrase</label>
                        <input type="password" class="form-control form-control-sm" id="annulation_p12_pin" autocomplete="off" placeholder="Passphrase du certificat">
                    </div>
                    <div class="col-12">
                        <div id="annulation-sign-feedback" class="alert alert-warning py-2 small d-none mb-0" role="status"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm text-white" id="btn-annuler-final">
                    <i class="fas fa-signature me-1"></i> Annuler avec signature électronique
                </button>
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

{{-- MODAL ANNULATION EN GROUPE (signature électronique) --}}
<div class="modal fade" id="modal-annulation-actes-bulk" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file-signature me-2"></i>Annulation des actes sélectionnés</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Attention :</strong> Cette action annule définitivement tous les actes sélectionnés. Opération irréversible — confirmation par certificat électronique (.p12) requise.
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
                    <div class="col-md-7 mb-2">
                        <label class="form-label small fw-semibold" for="annulation_bulk_p12_file">Certificat électronique (.p12)</label>
                        <input type="file" class="form-control form-control-sm" id="annulation_bulk_p12_file" accept=".p12,.pfx,application/x-pkcs12">
                    </div>
                    <div class="col-md-5 mb-2">
                        <label class="form-label small fw-semibold" for="annulation_bulk_p12_pin">Passphrase</label>
                        <input type="password" class="form-control form-control-sm" id="annulation_bulk_p12_pin" autocomplete="off" placeholder="Passphrase du certificat">
                    </div>
                    <div class="col-12">
                        <div id="annulation-bulk-sign-feedback" class="alert alert-warning py-2 small d-none mb-0" role="status"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm text-white" id="btn-annuler-bulk-final">
                    <i class="fas fa-signature me-1"></i> Annuler avec signature électronique
                </button>
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
                    <strong>Information :</strong> La confirmation du dossier signe électroniquement la déclaration de naissance
                    (responsable du centre d'état civil) puis la rend prête pour la génération de l'acte.
                </div>
                <div class="row">
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Code de la déclaration</label>
                        <input type="text" readonly class="form-control" id="code-declaration-confirmation">
                    </div>
                    <div class="col-md-7 mb-2">
                        <label class="form-label small fw-semibold" for="decl_p12_file">Certificat électronique (.p12)</label>
                        <input type="file" class="form-control form-control-sm" id="decl_p12_file" accept=".p12,.pfx,application/x-pkcs12">
                    </div>
                    <div class="col-md-5 mb-2">
                        <label class="form-label small fw-semibold" for="decl_p12_pin">Passphrase</label>
                        <input type="password" class="form-control form-control-sm" id="decl_p12_pin" autocomplete="off" placeholder="Passphrase du certificat">
                    </div>
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Observation (optionnel)</label>
                        <textarea id="observation-confirmation" class="form-control" rows="3" placeholder="Ajoutez une observation..."></textarea>
                    </div>
                    <div class="col-md-12">
                        <div id="decl-sign-feedback" class="alert alert-warning py-2 small d-none mb-0" role="status"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-sm text-white" id="btn-confirmer-final">
                    <i class="fas fa-signature me-1"></i> Signer et confirmer
                </button>
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
                                   value="{{ $registre ? ($registre->lib_registre ?: $registre->getcode()) : 'Aucun registre actif' }}">
                        </div>
                        <small class="form-text text-muted">
                            @if ($registre)
                                Registre actif ({{ (int) $registre->nombre_acte_transcrit }}/{{ (int) $registre->nombre_acte_prevu }} feuillets)
                            @else
                                Aucun registre de naissance actif et paraphé pour ce centre
                            @endif
                        </small>
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

@endsection
@section("scripts")
<!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>
    <script src="{{ asset('js/vendor/forge.min.js') }}"></script>
    <script src="{{ asset('js/vendor/elliptic.min.js') }}"></script>
    <script src="{{ asset('js/sifec-p12-sign.js') }}?v=20260717c"></script>
    <script>



    var codesDocuments = [];
    var codesActes = [];
    var actesGeneres = [];
    var actesNonGeneres = [];

    // ── Gestion du timer OTP ─────────────────────────────────────────────
    var otpTimerInterval = null;
    var otpTimerSeconds  = 120;
    var otpTimerInitialTotal = 120;
    var otpExpired       = false;

    /** @param {number} [totalSeconds] durée initiale affichée (serveur : valid_for_seconds) */
    function startOtpTimer(totalSeconds) {
        var t = parseInt(totalSeconds, 10);
        if (!t || t < 1) {
            t = 120;
        }
        otpTimerInitialTotal = t;
        clearInterval(otpTimerInterval);
        otpExpired       = false;
        otpTimerSeconds  = t;

        // Réinitialiser l'UI
        $('#otp-feedback').addClass('d-none').empty();
        $('#otp-countdown').text(t + 's').css('color', '');
        $('#otp-progress').css('width', '100%')
                          .removeClass('bg-danger').addClass('bg-warning');
        $('#otp-timer-block').removeClass('d-none');
        $('#otp-expired-block').addClass('d-none');
        $('#btn-validate').prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer');
        $('#btn-resend-otp').prop('disabled', true);
        $('#otp_approbation_mairie').prop('disabled', false).val('').trigger('focus');

        otpTimerInterval = setInterval(function () {
            otpTimerSeconds--;
            var denom = otpTimerInitialTotal > 0 ? otpTimerInitialTotal : 1;
            var pct = Math.round((otpTimerSeconds / denom) * 100);

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
                "autoWidth": false,
                "columnDefs": [
                    { "orderable": false, "targets": [0, 7] }
                ]
            });
        }

        if ($('#table-actes-gestion tbody tr').length > 0 && $('#table-actes-gestion tbody tr:first').find('td.text-center').length === 0) {
            tableActes = $('#table-actes-gestion').DataTable({
                "language": frenchLanguage,
                "paging": false,
                "searching": true,
                "info": false,
                "ordering": true,
                "autoWidth": false,
                "columnDefs": [
                    { "orderable": false, "targets": [0, 7] }
                ]
            });
        }
    });

    // Fonction pour rechercher les documents côté serveur
    function searchDocumentsServer(submitBtn) {
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
                                "autoWidth": false,
                                "columnDefs": [
                                    { "orderable": false, "targets": [0, 7] }
                                ]
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
            },
            complete: function() {
                if (submitBtn) {
                    sifecBtnReset(submitBtn);
                }
            }
        });
    }

    // Fonction pour rechercher les actes côté serveur
    function searchActesServer(submitBtn) {
        var formData = $('#form-search-actes').serialize();
        formData += '&_token={{ csrf_token() }}';

        $.ajax({
            url: "{{ route('acteNaissance.filter.actes') }}",
            type: 'POST',
            data: formData,
            beforeSend: function() {
                $('#tbody-actes-gestion').html('<tr><td colspan="8" class="text-center"><i class="fa fa-spinner fa-spin"></i> Chargement...</td></tr>');
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
                                        "autoWidth": false,
                                        "columnDefs": [
                                            { "orderable": false, "targets": [0, 7] }
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
                        $('#tbody-actes-gestion').html('<tr><td colspan="8" class="text-center text-danger">Erreur lors de la recherche</td></tr>');
                    }
                } catch(e) {
                    flashAlert("Erreur", "error", "Erreur lors du traitement des résultats");
                    $('#tbody-actes-gestion').html('<tr><td colspan="8" class="text-center text-danger">Erreur lors du traitement</td></tr>');
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
                $('#tbody-actes-gestion').html('<tr><td colspan="8" class="text-center text-danger">Erreur lors du chargement</td></tr>');

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
                                "autoWidth": false,
                                "columnDefs": [
                                    { "orderable": false, "targets": [0, 7] }
                                ]
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
            },
            complete: function() {
                if (submitBtn) {
                    sifecBtnReset(submitBtn);
                }
            }
        });
    }

    // Soumission du formulaire de recherche Documents
    $('#form-search-documents').on('submit', function(e) {
        e.preventDefault();
        var submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            sifecBtnLoading(submitBtn, 'Recherche...');
        }
        searchDocumentsServer(submitBtn);
    });

    // Soumission du formulaire de recherche Actes
    $('#form-search-actes').on('submit', function(e) {
        e.preventDefault();
        var submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            sifecBtnLoading(submitBtn, 'Recherche...');
        }
        searchActesServer(submitBtn);
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

    // Annulation d'un acte — signature électronique .p12
    $(".btn-annuler-acte").on("click", function(){
        var codeDeclaration = $(this).data('id');
        $("#code-declaration-annulation").val(codeDeclaration);
        $("#annulation_p12_file").val('');
        $("#annulation_p12_pin").val('');
        $("#annulation-sign-feedback").addClass('d-none').empty();
        $("#modal-annulation-acte").modal('show');
    });

    function resetAnnulationSignBtn($btn, label) {
        $btn.prop('disabled', false).html('<i class="fas fa-signature me-1"></i> ' + (label || 'Annuler avec signature électronique'));
    }

    function showAnnulationSignError(feedbackSel, msg) {
        $(feedbackSel).removeClass('d-none').text(msg);
        flashAlert("Échec", "error", msg);
    }

    async function runAnnulationP12(options) {
        var $btn = options.$btn;
        var codes = options.codes;
        var motif = options.motif;
        var observation = options.observation || '';
        var fileInput = options.fileInput;
        var pin = options.pin;
        var feedbackSel = options.feedbackSel;
        var modalSel = options.modalSel;
        var btnLabel = options.btnLabel || 'Annuler avec signature électronique';

        $(feedbackSel).addClass('d-none').empty();

        if (!motif) {
            flashAlert("ALERTE", "error", 'Veuillez sélectionner un motif d\'annulation');
            return;
        }
        if (!codes || !codes.length) {
            showAnnulationSignError(feedbackSel, 'Aucun acte sélectionné.');
            return;
        }
        if (!fileInput || !fileInput.files || !fileInput.files[0]) {
            showAnnulationSignError(feedbackSel, 'Sélectionnez votre fichier certificat (.p12).');
            return;
        }
        if (!pin || !String(pin).trim()) {
            showAnnulationSignError(feedbackSel, 'Saisissez la passphrase de votre certificat.');
            return;
        }
        if (typeof window.SifecP12Sign === 'undefined') {
            showAnnulationSignError(feedbackSel, 'Bibliothèque de signature non chargée. Rechargez la page.');
            return;
        }

        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Préparation…');

        try {
            var prep = await $.ajax({
                url: "{{ route('acteNaissance.annulation.prepare') }}",
                type: 'POST',
                data: {
                    codes: codes,
                    motif: motif,
                    observation: observation,
                    _token: '{{ csrf_token() }}'
                }
            });

            if (String(prep.code) !== '200' || !prep.token || !prep.items || !prep.items.length) {
                resetAnnulationSignBtn($btn, btnLabel);
                showAnnulationSignError(feedbackSel, (prep && prep.message) ? prep.message : 'Échec de la préparation.');
                return;
            }

            $btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Signature locale…');
            var p12Binary = await window.SifecP12Sign.readP12File(fileInput.files[0]);
            var signatures = [];
            for (var i = 0; i < prep.items.length; i++) {
                var item = prep.items[i];
                var signatureHex = await window.SifecP12Sign.signHashHex(
                    p12Binary,
                    pin,
                    item.document_hash,
                    prep.expected_serial || null
                );
                signatures.push({
                    code_declaration: item.code_declaration,
                    signature_hex: signatureHex
                });
            }

            $btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Finalisation…');
            var fin = await $.ajax({
                url: "{{ route('acteNaissance.annulation.finalize') }}",
                type: 'POST',
                data: {
                    token: prep.token,
                    signatures: signatures,
                    _token: '{{ csrf_token() }}'
                }
            });

            resetAnnulationSignBtn($btn, btnLabel);
            var msg = typeof fin.message === 'string' ? fin.message : 'Réponse inconnue';
            if (String(fin.code) === '200') {
                flashAlert("Succès", "success", msg);
                $(modalSel).modal('hide');
                setTimeout(function(){ location.reload(); }, 1200);
                return;
            }
            showAnnulationSignError(feedbackSel, msg);
        } catch (err) {
            resetAnnulationSignBtn($btn, btnLabel);
            var emsg = 'Erreur lors de l\'annulation électronique';
            if (err && err.responseJSON && err.responseJSON.message) {
                emsg = typeof err.responseJSON.message === 'string'
                    ? err.responseJSON.message
                    : JSON.stringify(err.responseJSON.message);
            } else if (err && err.message) {
                emsg = err.message;
            }
            showAnnulationSignError(feedbackSel, emsg);
        }
    }

    $("#btn-annuler-final").on("click", async function(){
        var $btn = $(this);
        await runAnnulationP12({
            $btn: $btn,
            codes: [$("#code-declaration-annulation").val()].filter(Boolean),
            motif: $("#motif-annulation").val(),
            observation: $("#observation-annulation").val(),
            fileInput: document.getElementById('annulation_p12_file'),
            pin: $('#annulation_p12_pin').val(),
            feedbackSel: '#annulation-sign-feedback',
            modalSel: '#modal-annulation-acte'
        });
    });



    // Validation d'actes en lot — confirmation signature électronique (plus d'OTP)
    $("button.validate-actes").on("click", function(){
        if(actesGeneres.length > 0){
            $("#validation_type").val("bulk");
            $("#code_declaration_naissance_validate").val('');
            $("#acte_p12_file").val('');
            $("#acte_p12_pin").val('');
            $("#guot-sign-feedback").addClass('d-none').empty();
            $("#modal-validate-acte").modal('show');
        }
        return false;
    });

    function resetActeSignBtn($btn) {
        $btn.prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer électroniquement');
    }

    function showActeSignError(msg) {
        $("#guot-sign-feedback").removeClass('d-none').text(msg);
        flashAlert("Échec", "error", msg);
    }

    // Signature électronique .p12 (singleton ou bulk)
    $("#btn-validate").on("click", async function(){
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Préparation…');
        $("#guot-sign-feedback").addClass('d-none').empty();

        var validation_type = $("#validation_type").val();
        var codes = validation_type === "bulk"
            ? actesGeneres.slice()
            : [$("#code_declaration_naissance_validate").val()].filter(Boolean);

        if (!codes.length) {
            resetActeSignBtn($btn);
            showActeSignError('Aucun acte à signer.');
            return false;
        }

        var fileInput = document.getElementById('acte_p12_file');
        var pin = $('#acte_p12_pin').val();
        if (!fileInput || !fileInput.files || !fileInput.files[0]) {
            resetActeSignBtn($btn);
            showActeSignError('Sélectionnez votre fichier certificat (.p12).');
            return false;
        }
        if (!pin || !String(pin).trim()) {
            resetActeSignBtn($btn);
            showActeSignError('Saisissez la passphrase de votre certificat.');
            return false;
        }
        if (typeof window.SifecP12Sign === 'undefined') {
            resetActeSignBtn($btn);
            showActeSignError('Bibliothèque de signature non chargée. Rechargez la page.');
            return false;
        }

        try {
            var prep = await $.ajax({
                url: "{{ route('acteNaissance.sign.prepare') }}",
                type: 'POST',
                data: { codes: codes, _token: '{{ csrf_token() }}' }
            });

            if (String(prep.code) !== '200' || !prep.token || !prep.items || !prep.items.length) {
                resetActeSignBtn($btn);
                showActeSignError((prep && prep.message) ? prep.message : 'Échec de la préparation.');
                return false;
            }

            $btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Signature locale…');
            var p12Binary = await window.SifecP12Sign.readP12File(fileInput.files[0]);
            var signatures = [];
            for (var i = 0; i < prep.items.length; i++) {
                var item = prep.items[i];
                var signatureHex = await window.SifecP12Sign.signHashHex(
                    p12Binary,
                    pin,
                    item.document_hash,
                    prep.expected_serial || null
                );
                signatures.push({
                    code_declaration: item.code_declaration,
                    signature_hex: signatureHex
                });
            }

            $btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Validation…');
            var fin = await $.ajax({
                url: "{{ route('acteNaissance.sign.finalize') }}",
                type: 'POST',
                data: {
                    token: prep.token,
                    signatures: signatures,
                    _token: '{{ csrf_token() }}'
                }
            });

            resetActeSignBtn($btn);
            var msg = typeof fin.message === 'string' ? fin.message : 'Réponse inconnue';
            if (String(fin.code) === '200') {
                flashAlert("Succès", "success", msg);
                $('#modal-validate-acte').modal('hide');
                setTimeout(function(){ location.reload(); }, 1200);
                return false;
            }
            showActeSignError(msg);
        } catch (err) {
            resetActeSignBtn($btn);
            var emsg = 'Erreur lors de la signature électronique';
            if (err && err.responseJSON && err.responseJSON.message) {
                emsg = typeof err.responseJSON.message === 'string'
                    ? err.responseJSON.message
                    : JSON.stringify(err.responseJSON.message);
            } else if (err && err.message) {
                emsg = err.message;
            }
            showActeSignError(emsg);
        }

        return false;
    });

    // ── Renvoyer le code OTP (obsolète pour validation naissance) ──
    $("#btn-resend-otp").on("click", function() {
        return false;
    });

    // ── Nettoyage à la fermeture du modal ────────────────────────
    $('#modal-validate-acte').on('hidden.bs.modal', function() {
        $("#guot-sign-feedback").addClass('d-none').empty();
        $("#acte_p12_file").val('');
        $("#acte_p12_pin").val('');
        $('#btn-validate').prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer électroniquement');
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
            observation: observation,
            _token: '{{ csrf_token() }}'
        };
        var btnSendBack = this;
        sifecBtnLoading(btnSendBack, 'Envoi...');
        $.post(route, data, function(response){
            let msg = Array.isArray(response.message) ? response.message[0] : (typeof response.message === 'object' && response.message.reponse) ? response.message.reponse : response.message;
            flashAlert("Réponse","success",msg);
            $('#modal-declaration-send-back').modal('hide');
            setTimeout(()=>location.reload(), 1000);
        }).fail(function(xhr){
            let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Erreur lors du renvoi du dossier';
            flashAlert("Réponse","error",msg);
        }).always(function() {
            sifecBtnReset(btnSendBack);
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

    function showDeclBulkSignError(msg) {
        $("#decl-bulk-sign-feedback").removeClass('d-none').text(msg);
        flashAlert("Échec", "error", msg);
    }

    // Confirmation groupée : signature électronique .p12 des déclarations (CEC) puis confirmation.
    $("#btn-confirmer-bulk-final").on("click", async function(){
        var $btn = $(this);
        var observation = $("#observation-confirmation-bulk").val();
        var fileInput = document.getElementById('decl_bulk_p12_file');
        var pin = $('#decl_bulk_p12_pin').val();

        $("#decl-bulk-sign-feedback").addClass('d-none').empty();

        if (!codesDocuments.length) {
            showDeclBulkSignError('Aucun dossier sélectionné.');
            return false;
        }
        if (!fileInput || !fileInput.files || !fileInput.files[0]) {
            showDeclBulkSignError('Sélectionnez votre fichier certificat (.p12).');
            return false;
        }
        if (!pin || !String(pin).trim()) {
            showDeclBulkSignError('Saisissez la passphrase de votre certificat.');
            return false;
        }
        if (typeof window.SifecP12Sign === 'undefined') {
            showDeclBulkSignError('Bibliothèque de signature non chargée. Rechargez la page.');
            return false;
        }

        sifecBtnLoading($btn[0], 'Préparation…');
        try {
            var prep = await $.ajax({
                url: "{{ route('declarationNaissance.sign.prepare') }}",
                type: 'POST',
                data: { phase: 'cec', codes: codesDocuments, _token: '{{ csrf_token() }}' }
            });
            if (String(prep.code) !== '200' || !prep.token || !prep.items || !prep.items.length) {
                sifecBtnReset($btn[0], "Signer et confirmer");
                showDeclBulkSignError(prep && prep.message ? prep.message : 'Échec de la préparation.');
                return false;
            }

            $btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Signature locale…');
            var p12Binary = await window.SifecP12Sign.readP12File(fileInput.files[0]);
            var signatures = [];
            for (var i = 0; i < prep.items.length; i++) {
                var item = prep.items[i];
                var signatureHex = await window.SifecP12Sign.signHashHex(
                    p12Binary, pin, item.document_hash, prep.expected_serial || null
                );
                signatures.push({ code_declaration: item.code_declaration, signature_hex: signatureHex });
            }

            $btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Confirmation…');
            var fin = await $.ajax({
                url: "{{ route('declarationNaissance.sign.finalize') }}",
                type: 'POST',
                data: { phase: 'cec', token: prep.token, signatures: signatures, observation: observation, _token: '{{ csrf_token() }}' }
            });

            sifecBtnReset($btn[0], "Signer et confirmer");
            var msg = typeof fin.message === 'string' ? fin.message : 'Réponse inconnue';
            if (String(fin.code) === '200') {
                flashAlert("Réponse","success",msg);
                $('#modal-confirmation-dossiers-bulk').modal('hide');
                setTimeout(()=>location.reload(), 1200);
                return false;
            }
            showDeclBulkSignError(msg);
        } catch (err) {
            sifecBtnReset($btn[0], "Signer et confirmer");
            var emsg = 'Erreur lors de la signature électronique';
            if (err && err.responseJSON && err.responseJSON.message) {
                emsg = typeof err.responseJSON.message === 'string' ? err.responseJSON.message : JSON.stringify(err.responseJSON.message);
            } else if (err && err.message) {
                emsg = err.message;
            }
            showDeclBulkSignError(emsg);
        }
        return false;
    });

    $('#modal-confirmation-dossiers-bulk').on('hidden.bs.modal', function() {
        $("#decl-bulk-sign-feedback").addClass('d-none').empty();
        $("#decl_bulk_p12_file").val('');
        $("#decl_bulk_p12_pin").val('');
        $('#btn-confirmer-bulk-final').prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer et confirmer');
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
        var btnRenvBulk = this;
        sifecBtnLoading(btnRenvBulk, 'Envoi...');
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
            },
            complete: function() {
                sifecBtnReset(btnRenvBulk);
            }
        });
    });

    // Annulation groupée — signature électronique .p12
    $(".annuler-actes").on("click", function(){
        if(actesGeneres.length == 0){
            flashAlert("Attention", "warning", "Veuillez sélectionner au moins un acte à annuler.");
            return;
        }
        $("#annulation_bulk_p12_file").val('');
        $("#annulation_bulk_p12_pin").val('');
        $("#annulation-bulk-sign-feedback").addClass('d-none').empty();
        $("#modal-annulation-actes-bulk").modal('show');
    });

    $("#btn-annuler-bulk-final").on("click", async function(){
        var $btn = $(this);
        await runAnnulationP12({
            $btn: $btn,
            codes: actesGeneres.slice(),
            motif: $("#motif-annulation-bulk").val(),
            observation: $("#observation-annulation-bulk").val(),
            fileInput: document.getElementById('annulation_bulk_p12_file'),
            pin: $('#annulation_bulk_p12_pin').val(),
            feedbackSel: '#annulation-bulk-sign-feedback',
            modalSel: '#modal-annulation-actes-bulk'
        });
    });

    function showDeclSignError(msg) {
        $("#decl-sign-feedback").removeClass('d-none').text(msg);
        flashAlert("Échec", "error", msg);
    }

    // Confirmation individuelle : signature électronique .p12 de la déclaration (CEC) puis confirmation.
    $("#btn-confirmer-final").on("click", async function(){
        var $btn = $(this);
        var codeDeclaration = $("#code-declaration-confirmation").val();
        var observation = $("#observation-confirmation").val();
        var fileInput = document.getElementById('decl_p12_file');
        var pin = $('#decl_p12_pin').val();

        $("#decl-sign-feedback").addClass('d-none').empty();

        if (!codeDeclaration) {
            showDeclSignError('Aucune déclaration sélectionnée.');
            return false;
        }
        if (!fileInput || !fileInput.files || !fileInput.files[0]) {
            showDeclSignError('Sélectionnez votre fichier certificat (.p12).');
            return false;
        }
        if (!pin || !String(pin).trim()) {
            showDeclSignError('Saisissez la passphrase de votre certificat.');
            return false;
        }
        if (typeof window.SifecP12Sign === 'undefined') {
            showDeclSignError('Bibliothèque de signature non chargée. Rechargez la page.');
            return false;
        }

        sifecBtnLoading(this, "Préparation…");
        try {
            var prep = await $.ajax({
                url: "{{ route('declarationNaissance.sign.prepare') }}",
                type: 'POST',
                data: { phase: 'cec', codes: [codeDeclaration], _token: '{{ csrf_token() }}' }
            });

            if (String(prep.code) !== '200' || !prep.token || !prep.items || !prep.items.length) {
                sifecBtnReset($btn[0], "Signer et confirmer");
                showDeclSignError(prep && prep.message ? prep.message : 'Échec de la préparation.');
                return false;
            }

            $btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Signature locale…');
            var p12Binary = await window.SifecP12Sign.readP12File(fileInput.files[0]);
            var signatures = [];
            for (var i = 0; i < prep.items.length; i++) {
                var item = prep.items[i];
                var signatureHex = await window.SifecP12Sign.signHashHex(
                    p12Binary, pin, item.document_hash, prep.expected_serial || null
                );
                signatures.push({ code_declaration: item.code_declaration, signature_hex: signatureHex });
            }

            $btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Confirmation…');
            var fin = await $.ajax({
                url: "{{ route('declarationNaissance.sign.finalize') }}",
                type: 'POST',
                data: { phase: 'cec', token: prep.token, signatures: signatures, observation: observation, _token: '{{ csrf_token() }}' }
            });

            sifecBtnReset($btn[0], "Signer et confirmer");
            var msg = typeof fin.message === 'string' ? fin.message : 'Réponse inconnue';
            if (String(fin.code) === '200') {
                flashAlert("Réponse","success",msg);
                $('#modal-confirmation-dossier').modal('hide');
                setTimeout(()=>location.reload(), 1200);
                return false;
            }
            showDeclSignError(msg);
        } catch (err) {
            sifecBtnReset($btn[0], "Signer et confirmer");
            var emsg = 'Erreur lors de la signature électronique';
            if (err && err.responseJSON && err.responseJSON.message) {
                emsg = typeof err.responseJSON.message === 'string' ? err.responseJSON.message : JSON.stringify(err.responseJSON.message);
            } else if (err && err.message) {
                emsg = err.message;
            }
            showDeclSignError(emsg);
        }
        return false;
    });

    $('#modal-confirmation-dossier').on('hidden.bs.modal', function() {
        $("#decl-sign-feedback").addClass('d-none').empty();
        $("#decl_p12_file").val('');
        $("#decl_p12_pin").val('');
        $('#btn-confirmer-final').prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer et confirmer');
    });

     // Génération d'actes en lot
     $(document).on("click", "button.generate-actes", function(){
        if(actesNonGeneres.length > 0){

            var url = "{{ route('acteNaissance.generate.bulk') }}";
            var data = {codes:actesNonGeneres, _token: '{{ csrf_token() }}'};
            var btnGenBulk = this;
            sifecBtnLoading(btnGenBulk, 'Génération...');
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
            }).always(function() {
                sifecBtnReset(btnGenBulk);
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
        var btnGenOne = this;
        sifecBtnLoading(btnGenOne, 'Génération...');
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
            }
        })
        .fail(function(xhr){
            let errorMessage = 'Erreur lors de la génération de l\'acte';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }

            // Utiliser flashAlert pour le message d'erreur
            flashAlert("Erreur", "error", errorMessage);
        })
        .always(function() {
            sifecBtnReset(btnGenOne);
            $('#generer-single-message').empty();
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
        var btnRetrait = this;
        sifecBtnLoading(btnRetrait, 'Enregistrement...');
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
            },
            complete: function() {
                sifecBtnReset(btnRetrait);
            }
        });
    });

    // Ouvre la modale de signature électronique (singleton)
    $(document).on('click', '.btn-validate-single', function() {
        var code = $(this).data('id');
        $('#code_declaration_naissance_validate').val(code);
        $("#validation_type").val("simple");
        $("#acte_p12_file").val('');
        $("#acte_p12_pin").val('');
        $("#guot-sign-feedback").addClass('d-none').empty();
        $("#modal-validate-acte").modal('show');
    });


});
</script>
@endsection
