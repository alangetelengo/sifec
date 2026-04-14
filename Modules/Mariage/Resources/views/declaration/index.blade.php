@extends('layout.app')
@section('titre')
Formulaires types
@endsection
@section("styles")
<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
    @include('mariage::partials._formulaire_type_header_ui')
@endsection
@section('sous-titre')
    Liste des formulaires types
@endsection
@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
    <header class="an-hero an-hero--sifec-green">
        <div class="an-hero-text">
            <h1>
                <span class="an-hero-icon" aria-hidden="true"><i class="fas fa-file-alt"></i></span>
                Liste des formulaires types
            </h1>
            <p>Consultez et pilotez les dossiers de déclaration de mariage (statuts, confirmation, envoi au tribunal).</p>
        </div>
        <div class="an-toolbar">
            <a href="{{ route('declarationMariage.create') }}" class="an-hero-btn-primary">
                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                <span>Créer un formulaire type</span>
            </a>
        </div>
    </header>
<div class="an-body">
<div class="row">
    <div class="col-xl-12">
        <div class="card mariage-ft-card">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Epoux: Nom</th>
                                        <th>Epoux: Prénom</th>
                                        <th>Epouse: Nom</th>
                                        <th>Epouse: Prénom</th>
                                        <th>Date déclaration</th>
                                        <th>Type: Document</th>
                                        <th>Statut: Document</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @foreach ($dms as $dm)
                                    @php
                                        $dernierMouvement = null;
                                        $peutEnvoyer = false;
                                        $peutModifier = false;
                                        $peutSupprimer = false;
                                        $peutConfirmer = false;
                                        $dejaEnvoyeAuTribunal = false;
                                        $statutBadge = ['class' => 'badge-secondary', 'label' => 'Brouillon'];

                                        // Déterminer le type de déclaration
                                        $isDispense = $dm->type_declaration == "DISPENSE";

                                        // Vérifier si le dossier a déjà été envoyé au tribunal (pour dispenses)
                                        $dejaEnvoyeAuTribunal = false;
                                        $dossierRenvoie = false;
                                        if (isset($dm->mouvements) && $dm->mouvements->count()) {
                                            $dejaEnvoyeAuTribunal = $dm->mouvements->contains('code_mouvement', 'MOUV_2008');
                                            $dossierRenvoie = $dm->mouvements->contains('code_mouvement', 'MOUV_0004');
                                        }

                                        if (isset($dm->mouvements) && $dm->mouvements->count()) {
                                            $dernierMouvement = $dm->mouvements->sortByDesc('created_at')->first();
                                            switch ($dernierMouvement->code_mouvement) {
                                                case 'MOUV_0001':
                                                    $statutBadge = ['class' => 'badge-warning', 'label' => $dernierMouvement->lib_mouvement];
                                                    if ($isDispense) {
                                                        $peutEnvoyer = !$dejaEnvoyeAuTribunal; // Dispense peut être envoyée au tribunal si pas déjà envoyé
                                                        $peutConfirmer = false; // Pas besoin de confirmer
                                                    } else {
                                                        $peutEnvoyer = false; // Autres types ne peuvent pas envoyer directement
                                                        $peutConfirmer = true; // Peut confirmer
                                                    }
                                                    $peutModifier = true; // Peut modifier
                                                    $peutSupprimer = true; // Peut supprimer
                                                    break;
                                                case 'MOUV_0004':
                                                    $statutBadge = ['class' => 'badge-warning', 'label' => $dernierMouvement->lib_mouvement];
                                                    // Pour tous les types en cas de renvoi, on peut renvoyer au tribunal
                                                    $peutEnvoyer = true; // Peut renvoyer au tribunal après modification
                                                    $peutConfirmer = !$isDispense; // Confirmation pour non-dispense
                                                    $peutModifier = true; // Peut modifier les pièces
                                                    $peutSupprimer = true; // Peut supprimer
                                                    break;
                                                case 'MOUV_0009':
                                                    $statutBadge = ['class' => 'badge-success', 'label' => $dernierMouvement->lib_mouvement];
                                                    if ($isDispense) {
                                                        $peutEnvoyer = !$dejaEnvoyeAuTribunal; // Dispense peut être envoyée au tribunal si pas déjà envoyé
                                                        $peutConfirmer = false; // Pas besoin de confirmer
                                                    } else {
                                                        $peutEnvoyer = false; // Autres types ne peuvent pas envoyer directement
                                                        $peutConfirmer = false; // Déjà confirmé
                                                    }
                                                    $peutModifier = false; // Ne peut plus modifier
                                                    $peutSupprimer = false; // Ne peut plus supprimer
                                                    break;
                                                case 'MOUV_0015':
                                                    $statutBadge = ['class' => 'badge-info', 'label' => $dernierMouvement->lib_mouvement];
                                                    if ($isDispense) {
                                                        $peutEnvoyer = !$dejaEnvoyeAuTribunal; // Dispense peut être envoyée au tribunal si pas déjà envoyé
                                                        $peutConfirmer = false; // Pas besoin de confirmer
                                                    } else {
                                                        $peutEnvoyer = false; // Autres types ne peuvent pas envoyer directement
                                                        $peutConfirmer = true; // Peut confirmer
                                                    }
                                                    $peutModifier = true; // Peut modifier
                                                    $peutSupprimer = false; // Ne peut plus supprimer
                                                    break;
                                                case 'MOUV_0019':
                                                    $statutBadge = ['class' => 'badge-success', 'label' => $dernierMouvement->lib_mouvement];
                                                    if ($isDispense) {
                                                        $peutEnvoyer = !$dejaEnvoyeAuTribunal; // Dispense peut être envoyée au tribunal si pas déjà envoyé
                                                        $peutConfirmer = false; // Pas besoin de confirmer
                                                    } else {
                                                        $peutEnvoyer = false; // Autres types ne peuvent pas envoyer directement
                                                        $peutConfirmer = false; // Déjà confirmé
                                                    }
                                                    $peutModifier = false; // Ne peut plus modifier
                                                    $peutSupprimer = false; // Ne peut plus supprimer
                                                    break;
                                                case 'MOUV_0024':
                                                    $statutBadge = ['class' => 'badge-primary', 'label' => $dernierMouvement->lib_mouvement];
                                                    if ($isDispense) {
                                                        $peutEnvoyer = !$dejaEnvoyeAuTribunal; // Dispense peut être envoyée au tribunal si pas déjà envoyé
                                                        $peutConfirmer = false; // Pas besoin de confirmer
                                                    } else {
                                                        $peutEnvoyer = false; // Autres types ne peuvent pas envoyer directement
                                                        $peutConfirmer = true; // Peut confirmer
                                                    }
                                                    $peutModifier = true; // Peut modifier
                                                    $peutSupprimer = true; // Peut supprimer
                                                    break;
                                                case 'MOUV_0016':
                                                    $statutBadge = ['class' => 'badge-dark', 'label' => $dernierMouvement->lib_mouvement];
                                                    $peutEnvoyer = false; // Ne peut plus envoyer
                                                    $peutConfirmer = false; // Ne peut plus confirmer
                                                    $peutModifier = false; // Ne peut plus modifier
                                                    $peutSupprimer = false; // Ne peut plus supprimer
                                                    break;
                                                default:
                                                    $statutBadge = ['class' => 'badge-secondary', 'label' => $dernierMouvement->lib_mouvement ?? 'En cours'];
                                                    if ($isDispense) {
                                                        $peutEnvoyer = !$dejaEnvoyeAuTribunal; // Dispense peut être envoyée au tribunal si pas déjà envoyé
                                                        $peutConfirmer = false; // Pas besoin de confirmer
                                                    } else {
                                                        $peutEnvoyer = false; // Autres types ne peuvent pas envoyer directement
                                                        $peutConfirmer = true; // Peut confirmer
                                                    }
                                                    $peutModifier = true; // Par défaut peut modifier
                                                    $peutSupprimer = true; // Par défaut peut supprimer
                                            }
                                        } else {
                                            // Jamais envoyé - statut brouillon
                                            $statutBadge = ['class' => 'badge-secondary', 'label' => 'Brouillon'];
                                            if ($isDispense) {
                                                $peutEnvoyer = !$dejaEnvoyeAuTribunal; // Dispense peut être envoyée au tribunal si pas déjà envoyé
                                                $peutConfirmer = false; // Pas besoin de confirmer
                                            } else {
                                                $peutEnvoyer = false; // Autres types ne peuvent pas envoyer directement
                                                $peutConfirmer = true; // Peut confirmer
                                            }
                                            $peutModifier = true; // Peut modifier
                                            $peutSupprimer = true; // Peut supprimer
                                        }

                                        // Debug: Afficher les informations pour debug
                                        // dd($dm->mouvements->pluck('code_mouvement')->toArray(), $dernierMouvement, $peutEnvoyer, $peutModifier, $peutSupprimer);
                                    @endphp
                                    <tr width="100%">
                                        <td>{{ $dm->code_declaration_mariage }}</td>
                                        <td>{{ $dm->epoux->nom }}</td>
                                        <td>{{ $dm->epoux->prenom }}</td>
                                        <td>{{ $dm->epouse->nom }}</td>
                                        <td>{{ $dm->epouse->prenom }}</td>
                                        <td>{{ date("d-m-Y", strtotime($dm->date_declaration_mariage)) }}</td>
                                        <td>{{ $dm->type_declaration }}</td>
                                        <td>
                                            <span class="badge light {{ $statutBadge['class'] }}" style="font-size: 13px;font-weight:600;">
                                                {{ $statutBadge['label'] }}
                                            </span>
                                            @if($dernierMouvement && $dernierMouvement->observation)
                                                <br><small>Observation : {{ $dernierMouvement->observation }}</small>
                                            @endif
                                            @if($dernierMouvement && $dernierMouvement->motif_renvoi)
                                                <br><small>Motif : {{ $dernierMouvement->motif_renvoi }}</small>
                                            @endif
                                        </td>
                                        <td style="width: 18%">
                                            <div class="btn-group btn-group-xs">
                                                {{-- Voir le détail --}}
                                                <a href="{{ route('declarationMariage.show',$dm->code_declaration_mariage) }}" class="btn btn-primary shadow btn-xs sharp me-1" title="Voir détail">
                                                    <i class="fas fa-user-check"></i>
                                                </a>

                                                {{-- Modifier --}}
                                                @if($peutModifier)
                                                    <a href="{{ route('declarationMariage.edit',$dm->code_declaration_mariage) }}" class="btn btn-info shadow btn-xs sharp me-1" title="Modifier">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                @endif

                                                {{-- Confirmer/Valider --}}
                                                @if($peutConfirmer)
                                                    <button class="btn btn-success btn-confirmer-document shadow btn-xs sharp me-1"
                                                        title="Confirmer le formulaire type"
                                                        data-code="{{ $dm->code_declaration_mariage }}">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif

                                                {{-- Envoyer au tribunal (pour les dispenses et renvois) --}}
                                                @if($peutEnvoyer && $isDispense && !$dejaEnvoyeAuTribunal)
                                                    <button class="btn btn-warning btn-envoyer-tribunal shadow btn-xs sharp me-1"
                                                        title="Envoyer la demande de dispense au tribunal"
                                                        data-code="{{ $dm->code_declaration_mariage }}">
                                                        <i class="fas fa-gavel"></i>
                                                    </button>
                                                @elseif($peutEnvoyer && $dossierRenvoie)
                                                    <button class="btn btn-primary btn-renvoyer-tribunal shadow btn-xs sharp me-1"
                                                        title="Renvoyer le dossier modifié au tribunal"
                                                        data-code="{{ $dm->code_declaration_mariage }}">
                                                        <i class="fas fa-paper-plane"></i>
                                                    </button>
                                                @endif


                                                {{-- Consulter le PDF pour impression --}}
                                                <a href="{{ route('etatMariage.declaration',$dm->code_declaration_mariage) }}" target="_blank" class="btn btn-warning shadow btn-xs sharp me-1" title="Voir document (PDF)">
                                                    <i class="fas fa-print"></i>
                                                </a>


                                                {{-- Supprimer --}}
                                                @if($peutSupprimer)
                                                    <form action="{{ route('declarationMariage.destroy',$dm->code_declaration_mariage) }}" method="POST" style="display: inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette déclaration ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger shadow btn-xs sharp" title="Supprimer"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                  @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Epoux: Nom</th>
                                        <th>Epoux: Prénom</th>
                                        <th>Epouse: Nom</th>
                                        <th>Epouse: Prénom</th>
                                        <th>Date déclaration</th>
                                        <th>Type: Document</th>
                                        <th>Statut: Document</th>
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
                        <label class="form-label">Transmission du formulaire type N°</label>
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
                        <input type="hidden" class="form-control" id="codemouvementmariage">
                    </div>

                    <div class="mb-2 col-md-12">
                        <label class="form-label">Motif du renvoi <span class="text-danger">*</span></label>
                        <select id="motif_renvoi" name="motif_renvoi" class="form-control" readonly>
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

{{-- DEBUT MODAL CONFIRMATION DOCUMENT --}}
<div class="modal fade" id="modal-confirmation-document" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation du document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Information :</strong> Cette action va confirmer que le document est conforme et prêt pour la suite du traitement.
                </div>
                <div class="row">
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Code du formulaire type</label>
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
{{-- FIN MODAL CONFIRMATION DOCUMENT --}}

{{-- DEBUT MODAL ENVOI AU TRIBUNAL --}}
<div class="modal fade" id="modal-envoyer-tribunal" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Envoyer le formulaire type au tribunal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Numéro du formulaire type</label>
                        <input type="text" readonly class="form-control" id="code-declaration-tribunal">
                    </div>
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Observation (optionnel)</label>
                        <textarea id="observation-tribunal" class="form-control" rows="3" placeholder="Ajoutez une observation pour le tribunal..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning btn-sm text-white" id="btn-envoyer-tribunal-final">Envoyer au tribunal</button>
                <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Annuler</button>
            </div>
        </div>
    </div>
</div>
{{-- FIN MODAL ENVOI AU TRIBUNAL --}}

{{-- DEBUT MODAL RENVOI AU TRIBUNAL --}}
<div class="modal fade" id="modal-renvoyer-tribunal" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Renvoyer le dossier modifié au tribunal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Attention :</strong> Ce dossier a été modifié et sera renvoyé au tribunal pour nouvelle validation.
                </div>
                <div class="row">
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Numéro du formulaire type</label>
                        <input type="text" readonly class="form-control" id="code-declaration-renvoyer">
                    </div>
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Observation (optionnel)</label>
                        <textarea id="observation-renvoyer" class="form-control" rows="3" placeholder="Ajoutez une observation pour le tribunal..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm text-white" id="btn-renvoyer-tribunal-final">Renvoyer au tribunal</button>
                <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Annuler</button>
            </div>
        </div>
    </div>
</div>
{{-- FIN MODAL RENVOI AU TRIBUNAL --}}
</div>
</div>
</div>
@endsection
@section("scripts")

<!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

    <script>


        $(function(){
            $("a.show-to-send").on("click", function(){
                var codeDeclaration = $(this).attr('href');
                $("#codedeclaration").val(codeDeclaration);
                $("#modal-declaration-send").modal("show");
                return false;
            });

            $("#btn-send").on("click",function(){
                var cdm = $("#codedeclaration").val();
                var route = "{{ route('declarationMariage.confirmer', ':id') }}";
                route = route.replace(':id', cdm);
                var data = {
                    code_declaration_mariage: cdm,
                    observation: '',
                    _token: '{{ csrf_token() }}'
                };

                var btn = this;
                sifecBtnLoading(btn, "Enregistrement...");
                $.post(route, data, function(response){
                    sifecBtnReset(btn, "Envoyer");
                    if(response.code == "200"){
                       flashAlert("Réponse", "success", response.message);
                        $("#modal-declaration-send").modal('hide');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }else{
                        flashAlert("Erreur", "error", response.message);
                    }
                }).fail(function(xhr){
                    sifecBtnReset(btn, "Envoyer");
                    flashAlert("Erreur", "error", xhr.responseJSON?.message || 'Erreur lors de la confirmation');
                });

                return false;
            });

            $("a.show-detail-renvoie").on("click", function(){
                var motif = $(this).attr("title");
                var cdd = $(this).attr("href");
                var cmvtm = $(this).attr("cmouvtmariage");
                var obs = $(this).attr("obs");

                $("#codedeclarationback").val(cdd);
                $("#observation").val(obs);
                $("#motif_renvoi").html("<option>"+motif+"</option>");

                $("#modal-declaration-send-back").modal("show");
                return false;
            });

            // Gestion de la confirmation de document
            $(".btn-confirmer-document").on("click", function() {
                var codeDeclaration = $(this).data('code');
                $("#code-declaration-confirmation").val(codeDeclaration);
                $("#observation-confirmation").val('');
                $("#modal-confirmation-document").modal('show');
            });

            // Confirmation finale du document
            $("#btn-confirmer-final").on("click", function(){
                var codeDeclaration = $("#code-declaration-confirmation").val();
                var observation = $("#observation-confirmation").val();
                var route = "{{ route('declarationMariage.confirmer', ':id') }}";
                route = route.replace(':id', codeDeclaration);

                var data = {
                    code_declaration_mariage: codeDeclaration,
                    observation: observation,
                    _token: '{{ csrf_token() }}'
                };

                var btn = this;
                sifecBtnLoading(btn, "Enregistrement...");

                $.post(route, data, function(response){
                    sifecBtnReset(btn, "Confirmer");

                    if(response.code == "200"){
                        flashAlert("Réponse","success",response.message);
                        $("#modal-confirmation-document").modal('hide');
                        setTimeout(()=>location.reload(), 1000);
                    }else{
                        flashAlert("Réponse","error",response.message);
                    }
                }).fail(function(xhr){
                    sifecBtnReset(btn, "Confirmer");
                    flashAlert("Erreur", "error", xhr.responseJSON?.message || 'Erreur lors de la confirmation');

                });

                return false;
            });

            // Gestion de l'envoi au tribunal
            $('.btn-envoyer-tribunal').on('click', function() {
                var codeDeclaration = $(this).data('code');
                $("#code-declaration-tribunal").val(codeDeclaration);
                $("#observation-tribunal").val('');
                $("#modal-envoyer-tribunal").modal('show');
            });

            // Envoi final au tribunal
            $("#btn-envoyer-tribunal-final").on("click", function(){
                var codeDeclaration = $("#code-declaration-tribunal").val();
                var observation = $("#observation-tribunal").val();
                var route = "{{ route('declarationMariage.envoyerTribunal', ':id') }}";
                route = route.replace(':id', codeDeclaration);

                var data = {
                    observation: observation,
                    _token: '{{ csrf_token() }}'
                };

                var btn = this;
                sifecBtnLoading(btn, "Envoi...");

                $.post(route, data, function(response){
                    sifecBtnReset(btn, "Envoyer au tribunal");

                    if(response.code == "200"){
                       flashAlert("Réponse", "success", response.message);
                        $("#modal-envoyer-tribunal").modal('hide');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }else{
                        flashAlert("Erreur", "error", response.message);
                    }
                }).fail(function(xhr){
                    sifecBtnReset(btn, "Envoyer au tribunal");
                    flashAlert("Erreur", "error", xhr.responseJSON?.message || 'Erreur lors de l\'envoi au tribunal');
                });

                return false;
            });

            // Gestion du renvoi au tribunal (après modification)
            $('.btn-renvoyer-tribunal').on('click', function() {
                var codeDeclaration = $(this).data('code');
                $("#code-declaration-renvoyer").val(codeDeclaration);
                $("#observation-renvoyer").val('');
                $("#modal-renvoyer-tribunal").modal('show');
            });

            // Renvoi final au tribunal
            $("#btn-renvoyer-tribunal-final").on("click", function(){
                var codeDeclaration = $("#code-declaration-renvoyer").val();
                var observation = $("#observation-renvoyer").val();
                var route = "{{ route('declarationMariage.envoyerTribunal', ':id') }}";
                route = route.replace(':id', codeDeclaration);

                var data = {
                    observation: observation,
                    _token: '{{ csrf_token() }}'
                };

                var btn = this;
                sifecBtnLoading(btn, "Envoi...");

                $.post(route, data, function(response){
                    sifecBtnReset(btn, "Renvoyer au tribunal");

                    if(response.code == "200"){
                       flashAlert("Réponse", "success", response.message);
                        $("#modal-renvoyer-tribunal").modal('hide');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }else{
                        flashAlert("Erreur", "error", response.message);
                    }
                }).fail(function(xhr){
                    sifecBtnReset(btn, "Renvoyer au tribunal");
                    flashAlert("Erreur", "error", xhr.responseJSON?.message || 'Erreur lors du renvoi au tribunal');
                });

                return false;
            });

        });
    </script>

@endsection
