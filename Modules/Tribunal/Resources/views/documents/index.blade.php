@extends('layout.app')
@section('titre')
Dossiers reçus du centre d'état civil
@endsection
@section("styles")
<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
@endsection

@section('corps')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Liste des dossiers reçus de l'état civil</h4>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th style="min-width: 110px;">Module</th>
                                        <th style="min-width: 160px;">Identité principale</th>
                                        <th style="min-width: 180px;">Document reçu</th>
                                        <th style="min-width: 140px;">Date d'envoi</th>
                                        <th style="min-width: 100px;">Statut</th>
                                        <th style="min-width: 150px;">Document importé</th>
                                        <th style="min-width: 120px;">Statut envoi</th>
                                        <th style="min-width: 120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dossiers as $dossier)
                                        <tr>
                                            <td>{{ ucfirst($dossier['module']) }}</td>
                                            <td>{{ $dossier['identite'] }}</td>
                                            <td>{{ $dossier['type_declaration'] }}</td>
                                            @php
                                                // Déterminer si le document a été envoyé au centre d'état civil
                                                $mouvementsEnvoiCentre = ['MOUV_0009', 'MOUV_0010', 'MOUV_0011'];
                                                $documentEnvoye = $dossier['dernierMouvement'] && in_array($dossier['dernierMouvement']->code_mouvement, $mouvementsEnvoiCentre);
                                            @endphp
                                            <td>
                                                {{-- recuperer le created_at de mouvement par rapport au module et si le document est envoyé au centre d'état civil --}}
                                                @if($dossier['module'] === 'naissance')
                                                    @if($documentEnvoye)
                                                        {{ $dossier['dernierMouvement']->created_at->format('d/m/Y H:i') }}
                                                    @else
                                                       -
                                                    @endif
                                                @elseif($dossier['module'] === 'mariage')
                                                    @if($documentEnvoye)
                                                        {{ $dossier['dernierMouvement']->created_at->format('d/m/Y H:i') }}
                                                    @else
                                                       -
                                                    @endif
                                                @elseif($dossier['module'] === 'deces')
                                                    @if($documentEnvoye)
                                                        {{ $dossier['dernierMouvement']->created_at->format('d/m/Y H:i') }}
                                                    @else
                                                       -
                                                    @endif
                                                @endif
                                            <td>
                                                @if($dossier['tribunal_approuver'] === 'OUI')
                                                    <span class="badge bg-success">Validé</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Non validé</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($dossier['requisition'] && $dossier['requisition']->typeRequisition)
                                                    <span class="badge bg-info">
                                                        {{ $dossier['requisition']->typeRequisition->lib_type_requisition }}
                                                        @if($documentEnvoye)
                                                            <i class="fas fa-check-circle ms-1" title="Envoyé au centre d'état civil"></i>
                                                        @endif
                                                    </span>
                                                @elseif($dossier['jugement'] && $dossier['jugement']->typeJugement)
                                                    <span class="badge bg-info">
                                                        {{ $dossier['jugement']->typeJugement->lib_type_jugement }}
                                                        @if($documentEnvoye)
                                                            <i class="fas fa-check-circle ms-1" title="Envoyé au centre d'état civil"></i>
                                                        @endif
                                                    </span>
                                                @else
                                                    <span class="text-muted">Aucun</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($documentEnvoye)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-paper-plane me-1"></i>Envoyé
                                                    </span>
                                                @elseif($dossier['documentImporte'])
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-clock me-1"></i>En attente
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-hourglass-half me-1"></i>Non traité
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @include('tribunal::documents.partials.actions', [
                                                    'id' => $dossier['id'],
                                                    'module' => $dossier['module'],
                                                    'dernierMouvement' => $dossier['dernierMouvement'],
                                                    'documentImporte' => $dossier['documentImporte'],
                                                    'tribunal_approuver' => $dossier['tribunal_approuver'],
                                                    'statut' => $dossier['statut']
                                                ])
                                                {{-- Les boutons Analyser / Importer et Certificat PDF sont supprimés car le bouton d'importation suffit --}}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Aucun dossier reçu</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="min-width: 110px;">Module</th>
                                        <th style="min-width: 160px;">Identité principale</th>
                                        <th style="min-width: 180px;">Document reçu</th>
                                        <th style="min-width: 140px;">Date d'envoi</th>
                                        <th style="min-width: 100px;">Statut</th>
                                        <th style="min-width: 150px;">Document importé</th>
                                        <th style="min-width: 120px;">Statut envoi</th>
                                        <th style="min-width: 120px;">Actions</th>
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

{{-- Modals et scripts éventuels à inclure ici --}}
<div class="modal fade" id="modal-declaration-send">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-paper-plane me-2"></i>
                    <span id="modal-action-title">Action sur le dossier</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="codedeclaration">
                <!-- Informations du dossier -->
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Informations du dossier</strong>
                </div>

                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label fw-bold">
                            <i class="fas fa-file-alt me-1"></i>Document importé
                        </label>
                        <input type="text" readonly class="form-control bg-light" id="document-importe">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label fw-bold">
                            <i class="fas fa-folder me-1"></i>Module
                        </label>
                        <input type="text" readonly class="form-control bg-light" id="codemodule">
                    </div>
                </div>

                <!-- Section action -->
                <div class="alert" id="action-alert" style="display:none;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="action-description"></span>
                </div>

                <!-- Observation pour renvoi -->
                <div class="mb-3" id="observation-group" style="display:none;">
                    <label class="form-label fw-bold">
                        <i class="fas fa-comment me-1"></i>Motif du renvoi <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control" id="observation" rows="4"
                        placeholder="Veuillez indiquer le motif précis du renvoi au centre d'état civil (ex: informations manquantes, erreurs détectées, documents incomplets...)"
                        required></textarea>
                    <div class="form-text">
                        <i class="fas fa-lightbulb me-1"></i>
                        Cette observation sera transmise au centre d'état civil pour expliquer les raisons du renvoi.
                    </div>
                </div>

                <!-- Confirmation pour envoi -->
                <div class="mb-3" id="confirmation-group" style="display:none;">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Confirmation d'envoi</strong>
                        <p class="mb-0 mt-2">
                            Vous êtes sur le point d'envoyer <span id="type-document-nom"></span>
                            au centre d'état civil pour la transcription. Cette action finalise le traitement du dossier par le tribunal.
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-sm text-white" id="btn-send">
                    <i class="fas fa-paper-plane me-1"></i>
                    <span id="btn-send-text">Confirmer</span>
                </button>
                <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Annuler
                </button>
            </div>
        </div>
    </div>
</div>

{{-- DEBUT MODAL CONFIRMATION DOSSIER --}}
<div class="modal fade" id="modal-confirmation-document">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-confirmation-document">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation du dossier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Information :</strong> Cette action va confirmer que le dossier est conforme et prêt pour le traitement.
                    </div>
                    <div class="row">
                        <div class="mb-2 col-md-12">
                            <label class="form-label">Code du dossier</label>
                            <input type="text" readonly class="form-control" id="code-document-confirmation-modal">
                        </div>
                        <div class="mb-2 col-md-12" style="display:none;">
                            <input type="hidden" id="module-document-confirmation-modal">
                        </div>
                        <div class="mb-2 col-md-12">
                            <label class="form-label">Document reçu</label>
                            <input type="text" readonly class="form-control bg-light" id="document-recu-confirmation-modal">
                        </div>
                        <div class="mb-2 col-md-12">
                            <label class="form-label">Observation (optionnel)</label>
                            <textarea id="observation-confirmation" class="form-control" rows="3" placeholder="Ajoutez une observation..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-sm text-white" id="btn-confirmer-final">
                        <i class="fas fa-paper-plane me-1"></i>
                        <span id="btn-send-text">Confirmer</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- FIN MODAL CONFIRMATION DOSSIER --}}

@endsection
@section("scripts")
<!-- Datatable -->
<script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

<script>
    $(function() {
            // Ouvre le modal d'envoi/renvoi
            $(document).on("click", "a.show-to-send", function() {
                var codeDeclaration = $(this).data('id');
                var module = $(this).data('module');
                var action = $(this).data('action');

                // Récupérer le type de document importé depuis la colonne du tableau
                var documentImporte = $(this).closest("tr").find("td:nth-child(6) .badge").text().trim();
                if (!documentImporte) {
                    documentImporte = $(this).closest("tr").find("td:nth-child(6) .text-muted").text().trim();
                }

                // Remplir les informations du dossier
                $("#codemodule").val(module.charAt(0).toUpperCase() + module.slice(1));
                $("#codedeclaration").val(codeDeclaration);
                $("#btn-send").data('action', action);
                $("#document-importe").val(documentImporte || "Aucun document importé");

                // Configuration selon l'action
                if(action === 'renvoi') {
                    $("#modal-action-title").text("Renvoi du dossier au centre d'état civil");
                    $("#btn-send-text").text("Renvoyer le dossier");
                    $("#btn-send").removeClass("btn-primary").addClass("btn-warning");
                    $("#observation-group").show();
                    $("#confirmation-group").hide();
                    $("#observation").val("").removeClass("is-invalid");

                    $("#action-alert")
                        .removeClass("alert-success alert-info")
                        .addClass("alert-warning")
                        .show();
                    $("#action-description").html(
                        "<strong>Attention :</strong> Vous êtes sur le point de renvoyer ce dossier au centre d'état civil. Cette action est nécessaire si le dossier ne respecte pas les règles ou contient des erreurs."
                    );
                } else {
                    $("#modal-action-title").text("Envoi du document au centre d'état civil");
                    $("#btn-send-text").text("Envoyer le document");
                    $("#btn-send").removeClass("btn-warning").addClass("btn-primary");
                    $("#observation-group").hide();
                    $("#confirmation-group").show();
                    $("#action-alert").hide();

                    // Afficher le type de document spécifique dans le message
                    var typeDocument = documentImporte && documentImporte !== "Aucun document importé"
                        ? "<strong>" + documentImporte + "</strong>"
                        : "<strong>le document</strong>";
                    $("#type-document-nom").html(typeDocument);
                }


                $("#modal-declaration-send").modal("show");
                return false;
            });

            // Gestion de la soumission
            $("#btn-send").on("click", function(){
                var cdn = $("#codedeclaration").val();
                var module = $("#codemodule").val();
                var action = $(this).data('action');
                var observation = $("#observation-group").is(":visible") ? $("#observation").val().trim() : null;
                var typeDocument = $("#type-document-nom").text().trim();
                if($("#observation-group").is(":visible") && !observation) {
                    $("#observation").addClass("is-invalid");
                    flashAlert("Erreur", "error", "Veuillez indiquer le motif du renvoi.");
                    return false;
                } else {
                    $("#observation").removeClass("is-invalid");
                }

                var route = action === 'renvoi'
                    ? "{{ route('tribunal.renvoyer.certificat') }}"
                    : "{{ route('tribunal.document.envoyer_officiel') }}";

                var data = {
                    id: cdn,
                    module: module.toLowerCase(),
                    type_document: typeDocument.toLowerCase(),
                    _token: '{{ csrf_token() }}'
                };
                if(observation) data.observation = observation;

                var $btn = $(this);
                var originalText = $btn.html();
                $btn.attr("disabled", true);
                $btn.html('<i class="fas fa-spinner fa-spin me-1"></i>Traitement en cours...');

                $.post(route, data, function(response){
                    if(response.code == "200"){
                        flashAlert("Succès", "success", response.message);
                        $("#modal-declaration-send").modal('hide');
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    } else {
                        flashAlert("Erreur", "error", response.message);
                    }
                }).fail(function(xhr, status, error) {
                    flashAlert("Erreur", "error", "Une erreur est survenue lors du traitement. Veuillez réessayer.");
                }).always(function() {
                    $btn.attr("disabled", false);
                    $btn.html(originalText);
                });

                return false;
            });

            // Réinitialiser le modal
            $("#modal-declaration-send").on("hidden.bs.modal", function() {
                $("#observation").val("").removeClass("is-invalid");
                $("#btn-send").removeClass("btn-warning btn-primary").addClass("btn-primary");
                $("#action-alert").hide();
                $("#confirmation-group").hide();
            });
    });

    // Gestion du modal de confirmation de document (validation)
    $(function() {
        // Ouvre le modal de confirmation
        $(document).on('click', '.modal-confirmation-document', function(e) {
            e.preventDefault();

            // Récupérer le type de document reçu depuis la colonne du tableau
            var documentRecu = $(this).closest("tr").find("td:nth-child(3)").text().trim();

            $('#code-document-confirmation-modal').val($(this).data('id'));
            $('#module-document-confirmation-modal').val($(this).data('module'));
            $('#document-recu-confirmation-modal').val(documentRecu);
            $('#observation-confirmation').val('');
            $('#modal-confirmation-document').modal('show');
        });

        // Soumission AJAX de la confirmation
        $('#form-confirmation-document').on('submit', function(e) {
            e.preventDefault();
            var code = $('#code-document-confirmation-modal').val();
            var module = $('#module-document-confirmation-modal').val();
            var observation = $('#observation-confirmation').val();
            var url = "{{ route('tribunal.confirmation.document') }}";
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    code_document: code,
                    module: module,
                    observation: observation,
                    _token: '{{ csrf_token() }}'
                },
                success: function(resp) {
                    if(resp.code == '200') {
                        flashAlert('Réponse','success',resp.message[0]);
                        $('#modal-confirmation-document').modal('hide');
                        setTimeout(function(){ location.reload(); }, 1000);
                    } else {
                        flashAlert('Réponse','error',resp.message[0] || 'Erreur lors de la confirmation');
                    }
                },
                error: function(xhr) {
                    flashAlert('Erreur','error',xhr.responseJSON?.message || 'Erreur lors de la confirmation');
                }
            });
        });
    });

</script>
@endsection

