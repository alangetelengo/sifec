@extends('layout.app')
@section('titre')
Fiche de rectification reçues
@endsection
@section('styles')

<link href="{{ asset('tpl/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css')}}" rel="stylesheet">
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<style>
    .rectification-fiches-dt td.rectif-col-action,
    .rectification-fiches-dt th.rectif-col-action {
        vertical-align: middle;
        min-width: 12rem;
    }
    .rectification-fiches-dt .rectif-actions {
        display: inline-flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-end;
        gap: 0.35rem;
    }
</style>

@endsection

@section('corps')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4> Liste des fiches de rectification reçues</h4>
                    {{-- <a href="{{ route("rectification.create") }}"><button type="button" class="btn btn-sm btn-warning">Créer une fiche de rectification</button></a> --}}
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="display rectification-fiches-dt" style="min-width: 845px">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Numéro</th>
                                            <th>Numéro d'acte à rectifier</th>
                                            <th>Type acte</th>
                                            <th>Réquisition</th>
                                            <th>Réquerant</th>
                                            <th>Statut</th>
                                            <th class="text-end rectif-col-action">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rectificationsData as $data)
                                            @php
                                                $rectification = $data['rectification'];
                                                $dernierMouvement = $data['dernierMouvement'];
                                                $documentImporte = $data['documentImporte'];
                                                $module = $data['module'];
                                                $id = $data['id'];

                                                // Mouvements Tribunal
                                                $mouvementsTribunal = [
                                                    'MOUV_2001', 'MOUV_2002', 'MOUV_2003', 'MOUV_0011',
                                                    'MOUV_1001', 'MOUV_1019', 'MOUV_0209'
                                                ];
                                                // Mouvements de validation/confirmation tribunal pour l'import
                                                $mouvementsValidationTribunal = ['MOUV_1019', 'MOUV_2003', 'MOUV_0209'];
                                                // Mouvements d'import tribunal pour l'envoi
                                                $mouvementsImportTribunal = ['MOUV_1001'];
                                                // Mouvements d'envoi au centre d'état civil (document déjà envoyé)
                                                $mouvementsEnvoiCentre = ['MOUV_2003', 'MOUV_0011'];

                                                // Déterminer si le document a déjà été envoyé au centre d'état civil
                                                $documentDejaEnvoye = $dernierMouvement && in_array($dernierMouvement->code_mouvement, $mouvementsEnvoiCentre);
                                                // Déterminer si on peut afficher les boutons Valider/Renvoyer
                                                $peutValiderRenvoyer = $dernierMouvement &&
                                                                      in_array($dernierMouvement->code_mouvement, $mouvementsTribunal) &&
                                                                      !in_array($dernierMouvement->code_mouvement, $mouvementsValidationTribunal) &&
                                                                      !$documentImporte &&
                                                                      !$documentDejaEnvoye;
                                                // Déterminer si on peut importer
                                                $peutImporter = !$documentImporte &&
                                                               !$documentDejaEnvoye &&
                                                               $dernierMouvement &&
                                                               in_array($dernierMouvement->code_mouvement, $mouvementsValidationTribunal);
                                                // Déterminer si on peut envoyer
                                                $peutEnvoyer = $documentImporte &&
                                                               !$documentDejaEnvoye &&
                                                               $dernierMouvement &&
                                                               in_array($dernierMouvement->code_mouvement, $mouvementsImportTribunal);
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $rectification->numero_rectification }}</td>
                                                <td>{{ $rectification->numero_acte }}</td>
                                                <td>{{ $rectification->typeActe->lib_type_acte}}</td>
                                                <td>{{ $rectification->requisition != null ? "En attente du tribunal" : "En cours de traitement" }}</td>
                                                <td>{{ $rectification->nom_prenom_requerant ?? "-" }}</td>
                                                <td>
                                                    @if($rectification->mouvementRectification && $rectification->mouvementRectification->count() > 0)
                                                        <span class="badge light badge-success" style="font-size: 13px;font-weight:600;">
                                                            {{ $rectification->mouvementRectification->last()->lib_mouvement }}
                                                        </span>
                                                    @else
                                                        <span class="badge light badge-secondary" style="font-size: 13px;font-weight:600;">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('rectification.etat',$rectification->numero_acte) }}" target="_blank" class="btn btn-sm btn-primary" title="Voir la fiche de rectification"><i class="fas fa-eye"></i></a>
                                                    {{-- Télécharger le document importé (si déjà importé) --}}
                                                    @if($documentImporte)
                                                        <a href="{{ route('tribunal.voir_document', ['type' => $module, 'id' => $id]) }}"
                                                           class="btn btn-info btn-xs text-start me-1" title="Télécharger le document importé">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @endif

                                                     {{-- Bouton Valider le document reçu (ouvre un modal de confirmation) --}}
                                                        @if($peutValiderRenvoyer)
                                                        <a href="#" class="btn btn-success btn-xs text-start me-1 modal-confirmation-document" title="Valider le document reçu" data-id="{{ $id }}" data-module="{{ $module }}" data-action="modal-confirmation-document">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    @endif
                                                                                                        {{-- Envoyer la réponse au centre d'état civil (après import ET si pas encore envoyé) --}}
                                                    @if($peutEnvoyer)
                                                        <a href="#" class="btn btn-primary btn-xs text-start me-1 show-to-send" style="font-size: 13px;font-weight:600;" title="Envoyer le dossier traité au centre d'état civil" data-id="{{ $id }}" data-module="{{ $module }}" data-action="envoi">
                                                            <i class="fas fa-paper-plane"></i>
                                                        </a>
                                                    @endif
                                                    {{-- Indicateur si le document a déjà été envoyé --}}
                                                    @if($documentDejaEnvoye)
                                                        <span class="badge bg-success" title="Document déjà envoyé au centre d'état civil">
                                                            <i class="fas fa-check-circle me-1"></i>Envoyé
                                                        </span>
                                                    @endif
                                                    @if($peutImporter)
                                                        <a href="{{ route('tribunal.document.importer', ['type' => $module, 'code' => $id, 'mode' => 'acte']) }}"
                                                            class="btn btn-info btn-xs text-start me-1" title="Importer le document">
                                                            <i class="fas fa-upload"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-to-send" tabindex="-1" aria-labelledby="modalSendLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalSendLabel">
                        <i class="fas fa-paper-plane"></i> Confirmation d’envoi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning mb-3" role="alert">
                        Êtes-vous sûr de vouloir envoyer la fiche de rectification&nbsp;?
                    </div>
                    <ul class="list-group mb-3">
                        <li class="list-group-item">
                            <strong>Numéro de la fiche:</strong>
                            <span id="numerefiche" class="text-primary"></span>
                        </li>
                        <!-- Ajoute ici d'autres infos si besoin, par exemple : -->
                        <li class="list-group-item"><strong>Requérant :</strong> <span id="nomrequerant"></span></li>
                    </ul>
                    <input type="hidden" id="coderec">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info btn-sm text-white" id="btn-send">
                        <i class="fas fa-paper-plane"></i> Envoyer
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
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
                        <strong>Information :</strong> Cette action va confirmer que le dossier est conforme et prêt pour la génération de l'acte de naissance.
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
@section('scripts')
    <!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

    <script>
        $(function(){
            $("a.show-to-send").on("click", function(){
                var coderectification = $(this).attr("href");
                var numerefiche = $(this).attr("numerefiche");
                var nomrequerant = $(this).attr("requerant");

                $("#numerefiche").html(numerefiche);
                $("#nomrequerant").html(nomrequerant);
                $("#coderec").val(coderectification);
                $("#modal-to-send").modal("show");
                return false;
            });

            $("#btn-send").on("click",function(){
                var codeRect = $("#coderec").val();
                var route = "{{ route('rectification.send',':id') }}";
                route = route.replace(':id', codeRect);

                // $(this).attr("disabled",true);
                // $(this).html("Traitement en cours ...");
                $.get(route, function(response){

                    if(response.code == "200"){
                        // notification("success",response.message);
                        flashAlert("Réponse","success",response.message);
                        $("#modal-to-send").modal('hide');
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


            // Gestion du modal de confirmation de document (validation)
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
