@extends('layout.app')
@section('titre')
Déclaration
@endsection
@section("styles")
<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
@endsection
@section('sous-titre')
    Liste des déclarations de naissance
@endsection
@section('corps')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Liste des déclarations de naissance</h4>
                <a href="{{ route("declarationNaissance.create") }}"><button type="button" class="btn btn-info m-t-2 float-end text-white" >Créer déclaration  <i class="fa fa-plus-circle"></i></button></a>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Déclarant</th>
                                        <th>Enfant: Nom</th>
                                        <th>Enfant: Prénom</th>
                                        <th>Enfant: Date naissance</th>
                                        <th>Enfant: Date déclaration</th>
                                        <th>Enfant: Sexe</th>
                                        <th>Type: Document</th>
                                        <th>Statut: Document</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($declarations as $dn)
                                    @php
                                        $dernierMouvement = null;
                                        $peutEnvoyer = false;
                                        $peutModifier = false;
                                        $peutSupprimer = false;
                                        $statutBadge = ['class' => 'badge-secondary', 'label' => 'Brouillon'];

                                        if (isset($dn->mouvements) && $dn->mouvements->count()) {
                                            $dernierMouvement = $dn->mouvements->sortByDesc('created_at')->first();
                                            switch ($dernierMouvement->code_mouvement) {
                                                case 'MOUV_0001':
                                                    $statutBadge = ['class' => 'badge-warning', 'label' => $dernierMouvement->lib_mouvement];
                                                    $peutEnvoyer = false;
                                                    $peutModifier = false;
                                                    $peutSupprimer = false;
                                                    break;
                                                case 'MOUV_0004':
                                                    $statutBadge = ['class' => 'badge-info', 'label' => $dernierMouvement->lib_mouvement];
                                                    $peutEnvoyer = true;
                                                    $peutModifier = true;
                                                    $peutSupprimer = true;
                                                    break;
                                                case 'MOUV_0015':
                                                    $statutBadge = ['class' => 'badge-info', 'label' => $dernierMouvement->lib_mouvement];
                                                    $peutEnvoyer = false;
                                                    $peutModifier = false;
                                                    $peutSupprimer = false;
                                                    break;
                                                case 'MOUV_0019':
                                                    $statutBadge = ['class' => 'badge-success', 'label' => $dernierMouvement->lib_mouvement];
                                                    $peutEnvoyer = false;
                                                    $peutModifier = false;
                                                    $peutSupprimer = false;
                                                    break;
                                                case 'MOUV_0024':
                                                    $statutBadge = ['class' => 'badge-primary', 'label' => $dernierMouvement->lib_mouvement];
                                                    $peutEnvoyer = true;
                                                    $peutModifier = true;
                                                    $peutSupprimer = true;
                                                    break;
                                                case 'MOUV_0016':
                                                    $statutBadge = ['class' => 'badge-dark', 'label' => $dernierMouvement->lib_mouvement];
                                                    $peutEnvoyer = false;
                                                    $peutModifier = false;
                                                    $peutSupprimer = false;
                                                    break;
                                                default:
                                                    $statutBadge = ['class' => 'badge-secondary', 'label' => $dernierMouvement->lib_mouvement ?? 'En cours'];
                                                    $peutEnvoyer = false;
                                                    $peutModifier = false;
                                                    $peutSupprimer = false;
                                            }
                                        } else {
                                            // Jamais envoyé
                                            $statutBadge = ['class' => 'badge-secondary', 'label' => 'Brouillon'];
                                            $peutEnvoyer = true;
                                            $peutModifier = true;
                                            $peutSupprimer = true;
                                        }
                                    @endphp
                                    <tr width="100%">
                                        <td>{{ $dn->code_declaration_naissance }}</td>
                                        <td>{{ $dn->declarant->nomcomplet() }}</td>
                                        <td>{{ $dn->enfant->nom }}</td>
                                        <td>{{ $dn->enfant->prenom }}</td>
                                        <td>{{ date('d-m-Y', strtotime($dn->enfant->date_naissance)) }}</td>
                                        <td>{{ $dn->date_heure_declaration ? date('d-m-Y H:i', strtotime($dn->date_heure_declaration)) : '-' }}</td>
                                        <td>{{ $dn->enfant->sexe == "M" ? "Masculin" : "Féminin" }}</td>
                                        @if($dn->type_declaration != "FICHE DE MATERNITE" )
                                            <td>
                                                {{ $dn->type_declaration }}
                                            </td>
                                        @else
                                            <td>
                                                <a href="{{ $dn->code_declaration_naissance }}" telephone="{{ $dn->mere->telephone_parent }}" class="badge badge badge-danger show-send-sms-to-parent" style="font-size: 13px;font-weight:600;">{{ $dn->type_declaration }}</a>
                                            </td>
                                        @endif
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
                                                <a href="{{ route('declarationNaissance.show',$dn->code_declaration_naissance) }}" class="btn btn-primary shadow btn-xs sharp me-1" title="Voir détail">
                                                    <i class="fas fa-user-check"></i>
                                                </a>
                                                {{-- Modifier --}}
                                                @if($peutModifier)
                                                    <a href="{{ route('declarationNaissance.edit',$dn->code_declaration_naissance) }}" class="btn btn-info shadow btn-xs sharp me-1" title="Modifier">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                @endif
                                                {{-- Envoyer --}}
                                                @if($peutEnvoyer)
                                                    <button class="btn btn-warning btn-envoyer-centre shadow btn-xs sharp me-1"
                                                        title="Envoyer la déclaration au centre d'état civil"
                                                        data-code="{{ $dn->code_declaration_naissance }}"
                                                        data-piece-declarant="{{ $dn->piece_declarant }}"
                                                        data-piece-pere="{{ $dn->piece_pere }}"
                                                        data-piece-mere="{{ $dn->piece_mere }}"
                                                        data-statut-pere="{{ optional($dn->pere)->statut_personne ?? 'VIVANT' }}"
                                                        data-statut-mere="{{ optional($dn->mere)->statut_personne ?? 'VIVANT' }}"
                                                        data-identiteDeclarant="{{ $dn->declarant->nomcomplet() }}"
                                                        data-identitePere="{{ $dn->pere ? $dn->pere->nomcomplet() : '' }}"
                                                        data-identiteMere="{{ $dn->mere ? $dn->mere->nomcomplet() : '' }}">
                                                        <i class="fas fa-paper-plane"></i>
                                                    </button>
                                                @endif
                                                {{-- Supprimer --}}
                                                @if($peutSupprimer)
                                                    <form action="{{ route('declarationNaissance.destroy',$dn->code_declaration_naissance) }}" method="POST" style="display: inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette déclaration ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger shadow btn-xs sharp" title="Supprimer"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                @endif
                                                {{-- Consulter le PDF pour impression --}}
                                                <a href="{{ route('declarationNaissance.etat',$dn->code_declaration_naissance) }}" target="_blank" class="btn btn-warning shadow btn-xs sharp me-1" title="Voir le document (PDF)">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Déclarant</th>
                                        <th>Enfant: Nom</th>
                                        <th>Enfant: Prénom</th>
                                        <th>Enfant: Date naissance</th>
                                        <th>Enfant: Date déclaration</th>
                                        <th>Enfant: Sexe</th>
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



{{-- DEBUT ENVOIS NOTIFICATION AU PARENT --}}
<div class="modal fade" id="modal-notification-send-parent" data-bs-backdrop="static">
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
                        <label class="form-label">Envoyer la notification au numéro</label>
                        <input type="hidden" id="codedn">
                        <input type="text" readonly class="form-control"  placeholder="" id="tepehoneparent">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info btn-sm text-white" id="btn-send-notification">Envoyer</button>
                <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
{{-- FIN ENVOIS NOTIFICATION AU PARENT --}}

{{-- Modal Envoi au centre d'état civil (à placer une seule fois en dehors de la boucle) --}}
<div class="modal fade" id="modal-envoyer-centre" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form-envoyer-centre">
            @csrf
            <input type="hidden" name="code_declaration_naissance" id="input-code-declaration">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Envoyer la déclaration au centre d'état civil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        Cette action va transmettre la déclaration au centre d'état civil pour la transcription de l'acte de naissance.<br>
                        <strong>Vérifiez que toutes les pièces d'identité sont jointes avant l'envoi.</strong>
                    </div>
                    <div class="mb-3">
                        <h6>Pièces d'identité requises</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Personne</th>
                                        <th>Nom</th>
                                        <th>Pièce jointe</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="piece-declarant">
                                        <td><strong>Déclarant</strong></td>
                                        <td id="declarant-nom">-</td>
                                        <td id="declarant-piece">-</td>
                                        <td id="declarant-status"><span class="badge badge-warning">Manquante</span></td>
                                    </tr>
                                    <tr id="piece-pere">
                                        <td><strong>Père</strong></td>
                                        <td id="pere-nom">-</td>
                                        <td id="pere-piece">-</td>
                                        <td id="pere-status"><span class="badge badge-warning">Manquante</span></td>
                                    </tr>
                                    <tr id="piece-mere">
                                        <td><strong>Mère</strong></td>
                                        <td id="mere-nom">-</td>
                                        <td id="mere-piece">-</td>
                                        <td id="mere-status"><span class="badge badge-warning">Manquante</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="alert-pieces-manquantes" class="alert alert-warning d-none">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Attention :</strong> Certaines pièces d'identité sont manquantes.
                        Il est recommandé de les ajouter avant l'envoi au centre d'état civil.
                    </div>
                    <div class="mb-2">
                        <label for="observation-centre" class="form-label">Observation (optionnel)</label>
                        <textarea id="observation-centre" name="observation" class="form-control" rows="3" placeholder="Ajoutez une observation pour le centre d'état civil..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning" id="btn-envoyer-final">
                        <i class="fas fa-paper-plane"></i> Envoyer
                    </button>
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


            //envoie de notification au parent de la mere de l'enfant
            $("a.show-send-sms-to-parent").on("click", function(){
                var codedn = $(this).attr('href');
                var tepehoneParent = $(this).attr('telephone');

                $("#tepehoneparent").val(tepehoneParent);
                $("#codedn").val(codedn);
                $("#modal-notification-send-parent").modal("show");
                return false;
            });

            $("#btn-send-notification").on('click', function(){
                var phone = $("#tepehoneparent").val();
                var codedn = $("#codedn").val();
                var url = "{{ route('fiche_maternite.send.notification', ':id') }}";
                url = url.replace(":id", codedn);

                    $.get(url, function(response){

                    if(response.code == "200"){

                        flashAlert("Réponse","success",response.message);
                        $("#modal-notification-send-parent").modal('hide');
                        // setTimeout(() => {
                        //     location.reload();
                        // }, 2000);
                    }else{
                        // notification("error",response.message);
                        flashAlert("Réponse","error",response.message);
                    }
                });
                return false;
            });

            // Gestion modale envoi au centre d'état civil depuis index
            let codeDeclaration = null;
            $('.btn-envoyer-centre').on('click', function(){
                if ($(this).hasClass('disabled')) {
                    toastr.warning('Cette déclaration a déjà été envoyée au centre d\'état civil.');
                    return;
                }
                // Récupération des infos de la ligne sélectionnée
                const declarantNom = $(this).attr('data-identiteDeclarant');
                const pereNom = $(this).attr('data-identitePere');
                const mereNom = $(this).attr('data-identiteMere');
                // Pour les pièces, on va les stocker dans des data-* sur le bouton (à faire côté Blade)
                const pieceDeclarant = $(this).data('piece-declarant') || '';
                const piecePere = $(this).data('piece-pere') || '';
                const pieceMere = $(this).data('piece-mere') || '';
                const statutPere = $(this).data('statut-pere') || 'VIVANT';
                const statutMere = $(this).data('statut-mere') || 'VIVANT';

                // Déclarant : toujours obligatoire. Père/Mère : requises seulement si vivants
                $('#declarant-nom').text(declarantNom);
                $('#declarant-piece').html(pieceDeclarant ? `<a href="/${pieceDeclarant}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
                $('#declarant-status').html(pieceDeclarant ? '<span class="badge badge-success">Présente</span>' : '<span class="badge badge-warning">Manquante</span>');

                $('#pere-nom').text(pereNom);
                $('#pere-piece').html(piecePere ? `<a href="/${piecePere}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
                $('#pere-status').html(statutPere === 'DECEDE'
                    ? (piecePere ? '<span class="badge badge-success">Présente</span>' : '<span class="badge bg-secondary">Optionnelle</span>')
                    : (piecePere ? '<span class="badge badge-success">Présente</span>' : '<span class="badge badge-warning">Manquante</span>'));
                $('#mere-nom').text(mereNom);
                $('#mere-piece').html(pieceMere ? `<a href="/${pieceMere}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
                $('#mere-status').html(statutMere === 'DECEDE'
                    ? (pieceMere ? '<span class="badge badge-success">Présente</span>' : '<span class="badge bg-secondary">Optionnelle</span>')
                    : (pieceMere ? '<span class="badge badge-success">Présente</span>' : '<span class="badge badge-warning">Manquante</span>'));

                let piecesManquantes = false;
                if (!pieceDeclarant || (statutPere === 'VIVANT' && !piecePere) || (statutMere === 'VIVANT' && !pieceMere)) {
                    piecesManquantes = true;
                    $('#alert-pieces-manquantes').removeClass('d-none');
                } else {
                    $('#alert-pieces-manquantes').addClass('d-none');
                }
                // Désactiver le bouton si pièce manquante
                $('#btn-envoyer-final').prop('disabled', piecesManquantes);

                codeDeclaration = $(this).data('code');
                $('#modal-envoyer-centre').modal('show');
                $('#input-code-declaration').val(codeDeclaration);
            });
            $('#form-envoyer-centre').on('submit', function(e){
                e.preventDefault();
                let url = "{{ route('declarationNaissance.mouvement') }}";
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(resp){
                        if(resp.code == "200"){
                            flashAlert("Réponse","success",resp.message);
                            $('#modal-envoyer-centre').modal('hide');
                            setTimeout(()=>location.reload(), 1000);
                        }else{
                            flashAlert("Réponse","error",resp.message);
                        }
                    },
                    error: function(xhr){
                        flashAlert("Erreur","error",xhr.responseJSON?.message || 'Erreur lors de l\'envoi');
                    }
                });
            });

        });
    </script>

@endsection
