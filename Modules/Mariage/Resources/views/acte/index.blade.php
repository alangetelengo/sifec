@extends('layout.app')
@section('titre')
Actes de mariage
@endsection
@section("styles")

<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">

    <!-- Font Awesome pour les icônes -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        .action-buttons {
            min-width: 200px;
        }

        .btn-group-xs .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }

        .table td {
            vertical-align: middle;
        }

        .status-badge {
            white-space: nowrap;
        }

        .action-cell {
            white-space: nowrap;
        }

        .btn-sm {
            margin: 1px;
        }

        .d-flex.gap-1 {
            gap: 0.25rem !important;
        }

        .flex-wrap {
            flex-wrap: wrap;
        }

        /* Styles pour les modals */
        .modal-header.bg-warning {
            background: linear-gradient(135deg, #ffc107, #e0a800) !important;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .alert {
            border: none;
            border-radius: 0.5rem;
        }

        .card.border-light {
            border: 1px solid #e9ecef !important;
            border-radius: 0.5rem;
        }

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
<div class="an-body">
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Liste des actes de mariage</h4>
                <div class="row">
                    <div id="dupcreer">
                        @can('module.acteMariage.generate')
                        <button class="btn btn-sm btn-primary mb-2 generate-actes d-none">Générer les actes</button>
                        @endcan
                        @can('module.acteMariage.signature')
                        <button class="btn btn-sm btn-primary mb-2 validate-actes d-none">Valider les actes</button>
                        @endcan
                        @can('module.acteMariage.annuler')
                        <button class="btn btn-sm btn-danger mb-2 annuler-actes d-none">Annuler les actes</button>
                        @endcan
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
                                        <th><label for="check-all"><input type="checkbox" id="check-all"></label></th>
                                        <th>Identité Epoux</th>
                                        <th>Identité Epouse</th>
                                        <th>Régime</th>
                                        <th>Date d'enregistrement du formulaire type</th>
                                        <th>Date prévue mariage</th>
                                        <th>Statut: Formulaire type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i=1;
                                    @endphp
                                    @forelse ($declarations as $dm)
                                        <tr>
                                            <td>{{ $i++ }}
                                                <label for="single-check"><input type="checkbox" name="declaration[]" class="single-check" value="{{ $dm->code_declaration_mariage }}-{{ $dm->acte == null ? '0' : '1' }}"></label>

                                            </td>
                                            <td>{{ $dm->epoux->nomcomplet() }}</td>
                                            <td>{{ $dm->epouse->nomcomplet() }}</td>
                                            <td>{{ $dm->regime->lib_regime }}</td>
                                            <td>{{ date("d-m-Y", strtotime($dm->date_declaration_mariage)) }}</td>
                                            <td>{{ date("d-m-Y", strtotime($dm->date_prevue_mariage)) }}</td>
                                            <td class="status-badge">
                                                @if($dm->acte == null)
                                                    <span class="badge bg-danger">
                                                        <i class="fa fa-clock"></i> En attente de génération de l'acte
                                                    </span>
                                                @elseif($dm->acte->approbation_tribunal == 1 && $dm->acte->approbation_mairie == null)
                                                    <span class="badge bg-warning">
                                                        <i class="fa fa-hourglass-half"></i> En attente d'approbation
                                                    </span>
                                                @elseif($dm->acte->approbation_tribunal == 1 && $dm->acte->approbation_mairie != null)
                                                    <span class="badge bg-success">
                                                        <i class="fa fa-check-circle"></i> Acte produit
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="fa fa-question-circle"></i> Statut inconnu
                                                    </span>
                                                 @endif
                                            </td>
                                            <td class="action-cell">
                                                <div class="d-flex gap-1 flex-wrap action-buttons">
                                                    @if(!$dm->acte)
                                                        {{-- Pas d'acte : Bouton générer --}}
                                                        @can('module.acteMariage.generate')
                                                            <button class="btn btn-success btn-sm btn-generer-single"
                                                                    data-id="{{ $dm->code_declaration_mariage }}"
                                                                    title="Générer acte">
                                                                <i class="fas fa-file-alt"></i>
                                                    </button>
                                                        @endcan
                                                        <a href="{{ route('etatMariage.declaration',$dm->code_declaration_mariage) }}"
                                                           target="_blank"
                                                           class="btn btn-warning btn-sm"
                                                           title="Voir document">
                                                            <i class="fas fa-print"></i>
                                                        </a>
                                                    @elseif($dm->acte && !$dm->acte->approbation_mairie)
                                                        {{-- Acte non validé : Bouton voir + valider --}}
                                                        <a href="{{ route('acteMariage.display',$dm->code_declaration_mariage) }}"
                                                           target="_blank"
                                                           class="btn btn-primary btn-sm"
                                                           title="Voir l'acte PDF">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('acteMariage.print.acte',$dm->code_declaration_mariage) }}"
                                                           target="_blank"
                                                           class="btn btn-success btn-sm"
                                                           title="Voir l'acte">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @can('module.acteMariage.signature')
                                                            <button class="btn btn-primary btn-sm btn-validate-single"
                                                                    data-id="{{ $dm->code_declaration_mariage }}"
                                                                    title="Valider acte">
                                                                <i class="fas fa-check-circle"></i>
                                                            </button>
                                                        @endcan
                                                    @elseif($dm->acte && $dm->acte->approbation_mairie)
                                                        {{-- Acte validé : Boutons voir, copie, extrait, retrait, annuler --}}
                                                        <a href="{{ route('acteMariage.print.acte',$dm->code_declaration_mariage) }}"
                                                           target="_blank"
                                                           class="btn btn-primary btn-sm"
                                                           title="Voir l'acte">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('acteMariage.print.copie',$dm->code_declaration_mariage) }}"
                                                           target="_blank"
                                                           class="btn btn-info btn-sm"
                                                           title="Voir copie">
                                                            <i class="fas fa-copy"></i>
                                                        </a>
                                                        <a href="{{ route('acteMariage.print.extrait',$dm->code_declaration_mariage) }}"
                                                           target="_blank"
                                                           class="btn btn-warning btn-sm"
                                                           title="Voir extrait">
                                                            <i class="fas fa-file-alt"></i>
                                                        </a>
                                                        @if(!isset($dernierMouvement) || $dernierMouvement->code_mouvement != "MOUV_0016")
                                                            <button class="btn btn-secondary btn-sm btn-retrait-acte"
                                                                    data-id="{{ $dm->acte->code_acte_mariage }}"
                                                                    title="Enregistrer le retrait">
                                                                <i class="fas fa-archive"></i>
                                                            </button>
                                                        @endif
                                                        @can('module.acteMariage.annuler')
                                                            <button class="btn btn-danger btn-sm btn-annuler-acte"
                                                                    data-id="{{ $dm->code_declaration_mariage }}"
                                                                    title="Annuler l'acte">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        @endcan
                                                        @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">Aucun formulaire type trouvé</h5>
                                                    <p class="text-muted">Il n'y a actuellement aucun formulaire type à afficher.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Identité Epoux</th>
                                        <th>Identité Epouse</th>
                                        <th>Régime</th>
                                        <th>Date d'enregistrement du formulaire type</th>
                                        <th>Date prévue mariage</th>
                                        <th>Statut: Formulaire type</th>
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


    {{-- MODAL GENERATION ACTE DE MARIAGE --}}
    <div class="modal fade" id="modal-generer-single" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="fa fa-plus-circle me-2"></i>
                        Génération d'acte de mariage
                    </h5>
                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Fermer">
                    </button>
                </div>
                <div class="modal-body">
                    <div id="generer-single-message" class="text-center mb-4"></div>
                    <!-- Champ caché pour le code de déclaration -->
                    <input type="hidden" id="code_declaration_mariage">
                    <div class="row mt-3">
                        <div class="col-12 text-center mb-4">
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="fa fa-info-circle me-2"></i>
                                <span>Génération d'un acte de mariage pour le formulaire type sélectionné</span>
                        </div>
                        </div>

                        @if($registre != null)
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fa fa-book me-1"></i>
                                    Registre <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fa fa-hashtag"></i>
                                    </span>
                                    <input id="code_registre"
                                           type="text"
                                           class="form-control"
                                           readonly
                                           value="{{ $registre->code_registre }}">
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
                        @else
                            <div class="col-12">
                                <div class="alert alert-danger d-flex align-items-center">
                                    <i class="fa fa-exclamation-triangle me-3 fa-2x"></i>
                                    <div>
                                        <h6 class="alert-heading mb-1">Aucun registre disponible</h6>
                                        <p class="mb-0">Il n'y a actuellement aucun registre disponible pour la génération d'actes. Veuillez contacter l'Officier d'état civil.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    @if($registre != null)
                        <button type="button" class="btn btn-primary generate" id="btn-generer-single-confirm">
                            <i class="fa fa-cog me-1"></i>
                            Générer l'acte
                        </button>
                    @endif
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>


    {{-- FIN GENERATE ACTE DE MARIAGE --}}

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

    {{-- DEBUT MODAL VALIDATION ACTE DE MARIAGE (signature électronique .p12) --}}
    <div class="modal fade" id="modal-validate-acte" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-file-signature me-2"></i>Signature électronique de l'acte de mariage
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="code_declaration_mariage_validate">
                    <input type="hidden" id="validation_type">

                    <div class="alert alert-info py-2 mb-3">
                        <p class="mb-2 small fw-semibold">Comment procéder</p>
                        <ol class="small mb-2 ps-3">
                            <li>Vérifiez l'acte (ou la sélection) à valider.</li>
                            <li>Sélectionnez votre fichier certificat <strong>.p12</strong> et saisissez sa passphrase.</li>
                            <li>Cliquez sur <strong>Signer électroniquement</strong> : votre identité valide et scelle l'acte.</li>
                        </ol>
                        <p class="mb-0 small text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Aucun code SMS n'est demandé. Utilisez uniquement le certificat personnel téléchargé lors de votre enrôlement.
                            L'institution doit avoir son cachet institutionnel configuré.
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
                    <button type="button" class="btn btn-success btn-sm text-white" id="btn-validate">
                        <i class="fas fa-signature me-1"></i> Signer électroniquement
                    </button>
                    <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    {{-- FIN MODAL VALIDATION ACTE DE MARIAGE --}}



    {{-- DEBUT MODAL RETRAIT ACTE DE MARIAGE --}}
    <div class="modal fade" id="modal-retrait-acte" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Enregistrer le retrait de l'acte de mariage</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Code de l'acte <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="code_acte" readonly>
                        </div>
                        <div class="mb-2 col-md-6">
                            <label class="form-label">NIUPP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="leniupp" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Nom de l'intéressé <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nom_interesse" placeholder="Nom complet">
                        </div>
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Prénom de l'intéressé</label>
                            <input type="text" class="form-control" id="prenom_interesse" placeholder="Prénom">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Téléphone de l'intéressé <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="telephone_interesse" placeholder="Numéro de téléphone">
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
    {{-- FIN MODAL RETRAIT ACTE DE MARIAGE --}}

    {{-- DEBUT MODAL ANNULATION ACTE DE MARIAGE --}}
    <div class="modal fade" id="modal-annulation-acte" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Annuler l'acte de mariage</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Attention :</strong> Cette action va annuler définitivement l'acte de mariage. Cette opération est irréversible.
                    </div>
                    <div class="row">
                        <div class="mb-2 col-md-12">
                            <label class="form-label">Motif d'annulation <span class="text-danger">*</span></label>
                            <select class="form-control" id="motif-annulation" required>
                                <option value="">Sélectionner un motif</option>
                                <option value="Erreur administrative">Erreur administrative</option>
                                <option value="Demande du déclarant">Demande du déclarant</option>
                                <option value="Fraude détectée">Fraude détectée</option>
                                <option value="Document falsifié">Document falsifié</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-2 col-md-12">
                            <label class="form-label">Observation</label>
                            <textarea class="form-control" id="observation-annulation" rows="3" placeholder="Détails sur l'annulation"></textarea>
                        </div>
                    </div>
                    <input type="hidden" id="code-declaration-annulation">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger btn-sm text-white" id="btn-annuler-final">Annuler l'acte</button>
                    <button type="button" class="btn btn-sm btn-secondary text-white" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    {{-- FIN MODAL ANNULATION ACTE DE MARIAGE --}}

    {{-- DEBUT MODAL ANNULATION ACTES EN LOT --}}
    <div class="modal fade" id="modal-annulation-actes-bulk" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Annuler les actes de mariage</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Attention :</strong> Cette action va annuler définitivement tous les actes sélectionnés. Cette opération est irréversible.
                    </div>
                    <div class="row">
                        <div class="mb-2 col-md-12">
                            <label class="form-label">Motif d'annulation <span class="text-danger">*</span></label>
                            <select class="form-control" id="motif-annulation-bulk" required>
                                <option value="">Sélectionner un motif</option>
                                <option value="Erreur administrative">Erreur administrative</option>
                                <option value="Demande du déclarant">Demande du déclarant</option>
                                <option value="Fraude détectée">Fraude détectée</option>
                                <option value="Document falsifié">Document falsifié</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-2 col-md-12">
                            <label class="form-label">Observation</label>
                            <textarea class="form-control" id="observation-annulation-bulk" rows="3" placeholder="Détails sur l'annulation"></textarea>
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
    {{-- FIN MODAL ANNULATION ACTES EN LOT --}}
</div>
</div>
</div>
@endsection
@section("scripts")
<!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>
    <!-- Signature électronique .p12 -->
    <script src="{{ asset('js/vendor/forge.min.js') }}"></script>
    <script src="{{ asset('js/vendor/elliptic.min.js') }}"></script>
    <script src="{{ asset('js/sifec-p12-sign.js') }}?v=20260719a"></script>
    <script>

        $(function() {
            // Variables globales pour la gestion des actes
            const ActeManager = {
                codes: [],
                actesGeneres: [],
                actesNonGeneres: [],
                generationEnCours: false,

                // Initialisation
                init: function() {
                    this.bindEvents();
                },

                // Liaison des événements
                bindEvents: function() {
                    $("a.show-acte-modal").on("click", this.handleShowActeModal.bind(this));
                    // Suppression du gestionnaire dupliqué pour button.generate
                    $("#check-all").on("change", this.handleCheckAll.bind(this));
                    $("button.generate-actes").on("click", this.handleGenerateMultiple.bind(this));
                    $("button.validate-actes").on("click", this.handleValidateMultiple.bind(this));
                    $("#btn-validate").on("click", this.handleValidate.bind(this));
                },

                // Gestion de l'affichage du modal de génération d'acte
                handleShowActeModal: function(e) {
                    e.preventDefault();

                    // Récupérer l'élément cliqué
                    const $clickedElement = $(e.currentTarget);

                    // Récupérer le code de déclaration
                    let codeDeclaration = $clickedElement.attr("href");
                    if (!codeDeclaration) {
                        codeDeclaration = $clickedElement.data("code");
                    }
                    if (!codeDeclaration) {
                        codeDeclaration = $clickedElement.data("id");
                    }

                    // Réinitialiser le message et le bouton
                    $("#generer-single-message").html("");
                    $("button.generate").prop('disabled', false).show();

                    // Vérifier que le code est récupéré
                    if (codeDeclaration) {
                        // Afficher le modal
                        $("#modal-acte").modal("show");

                        // Remplir le champ après un délai pour s'assurer que le modal est chargé
                        setTimeout(() => {
                            $("#code_declaration_mariage").val(codeDeclaration).attr("readonly", true);
                        }, 300);
                    } else {
                        $("#modal-acte").modal("show");
                    }
                },


                // Méthodes de génération supprimées pour éviter la duplication
                // La génération est maintenant gérée par les gestionnaires d'événements uniques ci-dessous


                // Gestion de la sélection multiple
                handleCheckAll: function() {
                    const isChecked = $(this).is(":checked");
                    $(".single-check").prop("checked", isChecked);

                    this.updateSelectedActes();
                    this.updateButtonVisibility();
                },

                // Mise à jour des actes sélectionnés
                updateSelectedActes: function() {
                    this.codes = [];
                    this.actesGeneres = [];
                    this.actesNonGeneres = [];

                    $(".single-check:checked").each((index, element) => {
                        const value = $(element).val();
                        const codeDeclaration = value.substring(0, 12);
                        const hasActe = parseInt(value.substring(13)) === 1;

                        this.codes.push(codeDeclaration);

                        if (hasActe) {
                            this.actesGeneres.push(codeDeclaration);
                        } else {
                            this.actesNonGeneres.push(codeDeclaration);
                        }
                    });
                },

                // Mise à jour de la visibilité des boutons
                updateButtonVisibility: function() {
                    const $generateBtn = $("button.generate-actes");
                    const $validateBtn = $("button.validate-actes");

                    // Masquer tous les boutons d'abord
                    $generateBtn.addClass("d-none");
                    $validateBtn.addClass("d-none");

                    // Afficher les boutons selon la logique métier
                    if (this.actesNonGeneres.length > 0) {
                        $generateBtn.removeClass("d-none");
                    }

                    if (this.actesGeneres.length > 0) {
                        $validateBtn.removeClass("d-none");
                    }
                },


                // Génération multiple d'actes
                handleGenerateMultiple: function(e) {
                    e.preventDefault();
                    if (this.actesNonGeneres.length > 0) {
                        this.generateActesMultiple(this.actesNonGeneres, e.currentTarget);
                    }
                },

                // Validation multiple d'actes : ouvre le modal de signature .p12
                handleValidateMultiple: function(e) {
                    e.preventDefault();
                    if (this.actesGeneres.length > 0) {
                        $("#validation_type").val("bulk");
                        $("#code_declaration_mariage_validate").val("");
                        $("#guot-sign-feedback").addClass('d-none').empty();
                        $("#acte_p12_file").val('');
                        $("#acte_p12_pin").val('');
                        $("#modal-validate-acte").modal('show');
                    }
                },




                // Signature électronique .p12 de l'acte (singleton ou bulk) : prepare → sign → finalize
                handleValidate: async function(e) {
                    e.preventDefault();
                    const $btn = $("#btn-validate");
                    const resetBtn = () => $btn.prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer électroniquement');
                    const showErr = (msg) => {
                        $("#guot-sign-feedback").removeClass('d-none').text(msg);
                        flashAlert("Échec", "error", msg);
                    };

                    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Préparation…');
                    $("#guot-sign-feedback").addClass('d-none').empty();

                    const validationType = $("#validation_type").val();
                    const codes = validationType === "bulk"
                        ? this.actesGeneres.slice()
                        : [$("#code_declaration_mariage_validate").val()].filter(Boolean);

                    if (!codes.length) {
                        resetBtn();
                        showErr('Aucun acte à signer.');
                        return false;
                    }

                    const fileInput = document.getElementById('acte_p12_file');
                    const pin = $('#acte_p12_pin').val();
                    if (!fileInput || !fileInput.files || !fileInput.files[0]) {
                        resetBtn();
                        showErr('Sélectionnez votre fichier certificat (.p12).');
                        return false;
                    }
                    if (!pin || !String(pin).trim()) {
                        resetBtn();
                        showErr('Saisissez la passphrase de votre certificat.');
                        return false;
                    }
                    if (typeof window.SifecP12Sign === 'undefined') {
                        resetBtn();
                        showErr('Bibliothèque de signature non chargée. Rechargez la page.');
                        return false;
                    }

                    try {
                        const prep = await $.ajax({
                            url: "{{ route('acteMariage.sign.prepare') }}",
                            type: 'POST',
                            data: { codes: codes, _token: '{{ csrf_token() }}' }
                        });

                        if (String(prep.code) !== '200' || !prep.token || !prep.items || !prep.items.length) {
                            resetBtn();
                            showErr((prep && prep.message) ? prep.message : 'Échec de la préparation.');
                            return false;
                        }

                        $btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Signature locale…');
                        const p12Binary = await window.SifecP12Sign.readP12File(fileInput.files[0]);
                        const signatures = [];
                        for (let i = 0; i < prep.items.length; i++) {
                            const item = prep.items[i];
                            const signatureHex = await window.SifecP12Sign.signHashHex(
                                p12Binary,
                                pin,
                                item.document_hash,
                                prep.expected_serial || null
                            );
                            signatures.push({ code_declaration: item.code_declaration, signature_hex: signatureHex });
                        }

                        $btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Validation…');
                        const fin = await $.ajax({
                            url: "{{ route('acteMariage.sign.finalize') }}",
                            type: 'POST',
                            data: { token: prep.token, signatures: signatures, _token: '{{ csrf_token() }}' }
                        });

                        resetBtn();
                        const msg = typeof fin.message === 'string' ? fin.message : 'Réponse inconnue';
                        if (String(fin.code) === '200') {
                            flashAlert("Succès", "success", msg);
                            $('#modal-validate-acte').modal('hide');
                            setTimeout(() => location.reload(), 1200);
                            return false;
                        }
                        showErr(msg);
                    } catch (err) {
                        resetBtn();
                        let emsg = 'Erreur lors de la signature électronique';
                        if (err && err.responseJSON && err.responseJSON.message) {
                            emsg = typeof err.responseJSON.message === 'string' ? err.responseJSON.message : JSON.stringify(err.responseJSON.message);
                        } else if (err && err.message) {
                            emsg = err.message;
                        }
                        showErr(emsg);
                    }

                    return false;
                },

                // Méthodes utilitaires pour les appels API
                generateActesMultiple: function(codes, btnEl) {
                    $(".over-loader-page").fadeIn(600);
                    if (btnEl) {
                        sifecBtnLoading(btnEl, 'Génération...');
                    }
                    const url = "{{ route('acteMariage.generate.bulk') }}";

                    $.post(url, { codes: codes, _token: '{{ csrf_token() }}' })
                        .done((response) => {
                            if (response.code === "200") {
                                flashAlert("Résultat", "success", response.message.reponse);
                                $('#modal-generer-single').modal('hide');
                                setTimeout(() => location.reload(), 4000);
                            } else {
                                var outString = "<ul>";
                                for (const [key, value] of Object.entries(response.message)) {
                                    outString += `<li style='text-align:left;color:red; list-style:disc !important; font-size:12px'>${value}</li>`;
                                }
                                outString += "</ul>";
                                flashAlert("Une erreur est survenue", "error", outString);
                            }
                        })
                        .fail(() => {
                            flashAlert("Erreur", "error", "Erreur de communication avec le serveur");
                        })
                        .always(() => {
                            $(".over-loader-page").fadeOut(600);
                            if (btnEl) {
                                sifecBtnReset(btnEl);
                            }
                        });
                },

            };

            // Initialisation de l'application
            ActeManager.init();
        });

        // Variables globales pour la gestion des actes (comme dans le module Naissance)
        let actesGeneres = [];
        let actesNonGeneres = [];

        // Génération d'un acte individuel - Gestionnaire unique pour éviter la duplication
        $(document).on("click", ".btn-generer-single", function(){
            var code = $(this).data('id');
            $('#generer-single-code').val(code);
            $('#code_declaration_mariage').val(code);
            $('#generer-single-message').html("");
            $('#btn-generer-single-confirm').prop('disabled', false).show();
            $('#modal-generer-single').modal('show');
        });

        // Gestionnaire unique pour le bouton de confirmation avec protection contre les clics multiples
        $('#btn-generer-single-confirm').on('click', function(){
            // Protection contre les clics multiples
            if ($(this).prop('disabled')) {
                return false;
            }
            
            var code = $('#generer-single-code').val();
            var url = "{{ route('acteMariage.generate.single') }}";
            var btn = this;
            sifecBtnLoading(btn, 'Génération...');
            $('#generer-single-message').html('');
            
            $.post(url, {code_declaration_mariage: code, _token: '{{ csrf_token() }}'}, function(response){
                if(response.code == "200"){
                    sifecBtnReset(btn);
                    $('#generer-single-message').html('<span class="text-success">'+(response.message.reponse || response.message)+'</span>');
                    setTimeout(() => { location.reload(); }, 1200);
                }else if(response.code == "409"){
                    sifecBtnReset(btn);
                    $('#generer-single-message').html('<span class="text-warning">'+(response.message.reponse || response.message)+'</span>');
                    setTimeout(() => { location.reload(); }, 1500);
                }else if(response.code == "429"){
                    sifecBtnReset(btn);
                    $('#generer-single-message').html('<span class="text-info">'+(response.message.reponse || response.message)+'</span>');
                }else{
                    sifecBtnReset(btn);
                    $('#generer-single-message').html('<span class="text-danger">'+(response.message.reponse || response.message)+'</span>');
                }
            }).fail(function(xhr){
                sifecBtnReset(btn);
                let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Erreur lors de la génération de l\'acte';
                $('#generer-single-message').html('<span class="text-danger">'+msg+'</span>');
            });
        });

        // Signature d'un acte individuel : ouvre directement le modal de signature .p12
        $(document).on('click', '.btn-validate-single', function() {
            var code = $(this).data('id');
            $('#code_declaration_mariage_validate').val(code);
            $("#validation_type").val("simple");
            $("#guot-sign-feedback").addClass('d-none').empty();
            $("#acte_p12_file").val('');
            $("#acte_p12_pin").val('');
            $("#modal-validate-acte").modal('show');
        });

        // Nettoyage du modal de signature à la fermeture
        $('#modal-validate-acte').on('hidden.bs.modal', function() {
            $("#guot-sign-feedback").addClass('d-none').empty();
            $("#acte_p12_file").val('');
            $("#acte_p12_pin").val('');
            $('#btn-validate').prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer électroniquement');
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
                flashAlert("Erreur", "error", "Veuillez sélectionner un motif d'annulation");
                return;
            }
            var btn = this;
            sifecBtnLoading(btn, 'Traitement...');
            $.ajax({
                url: "{{ route('acteMariage.annuler') }}",
                type: 'POST',
                data: {
                    code_declaration_mariage: codeDeclaration,
                    motif: motif,
                    observation: observation,
                    _token: '{{ csrf_token() }}'
                },
                success: function(resp) {
                    sifecBtnReset(btn);
                    if(resp.code == "200"){
                        flashAlert("Résultat", "success", resp.message.reponse);
                        $('#modal-annulation-acte').modal('hide');
                        setTimeout(() => { location.reload(); }, 1000);
        }else{
                        flashAlert("Erreur", "error", resp.message);
                    }
                },
                error: function(xhr) {
                    sifecBtnReset(btn);
                    let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Erreur lors de l\'annulation de l\'acte';
                    flashAlert("Erreur", "error", msg);
                }
            });
        });

        // Annulation d'actes en lot
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
                flashAlert("Erreur", "error", "Veuillez sélectionner un motif d'annulation");
                return;
            }
            var btn = this;
            sifecBtnLoading(btn, 'Traitement...');
            $.ajax({
                url: "{{ route('acteMariage.annuler.bulk') }}",
                type: 'POST',
                data: {
                    codes: actesGeneres,
                    motif: motif,
                    observation: observation,
                    _token: '{{ csrf_token() }}'
                },
                success: function(resp) {
                    sifecBtnReset(btn);
                    if(resp.code == "200"){
                        flashAlert("Résultat", "success", resp.message.reponse);
                        $('#modal-annulation-actes-bulk').modal('hide');
                        setTimeout(() => { location.reload(); }, 1000);
            }else{
                        flashAlert("Erreur", "error", resp.message);
                    }
                },
                error: function(xhr) {
                    sifecBtnReset(btn);
                    let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Erreur lors de l\'annulation des actes';
                    flashAlert("Erreur", "error", msg);
                }
            });
        });

        // Retrait d'acte
        $(document).on("click", ".btn-retrait-acte", function() {
            var codeActe = $(this).data('id');
            $("#code_acte").val(codeActe);
            $("#leniupp").val(codeActe);
            $("#nom_interesse").val("");
            $("#prenom_interesse").val("");
            $("#telephone_interesse").val("");
            $("#modal-retrait-acte").modal("show");
        });

        $(document).on("click", "#btn-retrait", function(e) {
            e.preventDefault();
            var codeActe = $("#leniupp").val();
            var nominteresse = $("#nom_interesse").val();
            var prenominteresse = $("#prenom_interesse").val();
            var telephoneinteresse = $("#telephone_interesse").val();
            if(!nominteresse || !telephoneinteresse) {
                flashAlert("Erreur", "error", "Veuillez renseigner le nom et le téléphone de l'intéressé.");
                return;
            }
            var btn = this;
            sifecBtnLoading(btn, 'Enregistrement...');
            $.ajax({
                url: "{{ route('acteMariage.retrait') }}",
                type: 'POST',
                data: {
                    code_acte_mariage: codeActe,
                    nominteresse: nominteresse,
                    prenominteresse: prenominteresse,
                    telephoneinteresse: telephoneinteresse,
                    _token: '{{ csrf_token() }}'
                },
                success: function(resp) {
                    sifecBtnReset(btn);
                    if(resp.code == "200"){
                        flashAlert("Résultat", "success", resp.message.reponse);
                        $('#modal-retrait-acte').modal('hide');
                        setTimeout(() => { location.reload(); }, 1000);
                    }else{
                        flashAlert("Erreur", "error", resp.message);
                    }
                },
                error: function(xhr) {
                    sifecBtnReset(btn);
                    let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Erreur lors du retrait de l\'acte';
                    flashAlert("Erreur", "error", msg);
                }
            });
        });

        // Validation d'actes en lot : géré par ActeManager.handleValidateMultiple (pas de doublon ici)

        // Validation OTP (singleton ou bulk) - conforme au module Naissance
        // Handler #btn-validate géré exclusivement par ActeManager.handleValidate (ci-dessus)

        // Mise à jour des variables globales lors de la sélection
        $('.single-check').on('change', function() {
            updateActesArrays();
        });

        function updateActesArrays() {
            actesGeneres = [];
            actesNonGeneres = [];

            $('.single-check:checked').each(function() {
                var value = $(this).val();
                var codeDeclaration = value.substring(0, 12);
                var hasActe = parseInt(value.substring(13)) === 1;

                if (hasActe) {
                    actesGeneres.push(codeDeclaration);
                } else {
                    actesNonGeneres.push(codeDeclaration);
                }
            });
     }

    </script>

@endsection
