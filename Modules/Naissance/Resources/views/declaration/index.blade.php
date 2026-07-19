@extends('layout.app')
@section('titre')
Déclaration
@endsection
@section("styles")
<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
    <style>
    @include('authentification::partials.sifec-swal-delete-styles')
    </style>
@endsection
@section('sous-titre')
    {{ $title }}
@endsection
@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>{{ $title }}</h4>
                <a href="{{ route("declarationNaissance.create") }}"><button type="button" class="btn btn-info m-t-2 float-end text-white" >{{ $button }}  <i class="fa fa-plus-circle"></i></button></a>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display declaration-naissance-list" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Déclarant</th>
                                        <th>Enfant: Nom</th>
                                        <th>Enfant: Prénom</th>
                                        <th>Enfant: Date naissance</th>
                                        <th>Enfant: Date déclaration</th>
                                        <th>Enfant: Sexe</th>
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
                                                case 'MOUV_0035':
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
                                                case 'MOUV_0034':
                                                    $statutBadge = ['class' => 'badge-success', 'label' => $dernierMouvement->lib_mouvement];
                                                    $peutEnvoyer = false;
                                                    $peutModifier = false;
                                                    $peutSupprimer = false;
                                                    break;
                                                case 'MOUV_0024':
                                                case 'MOUV_0033':
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
                                        <td style="width: 18%">
                                            <div class="d-flex flex-wrap align-items-center gap-2">
                                                {{-- Voir le détail --}}
                                                <a href="{{ route('declarationNaissance.show',$dn->code_declaration_naissance) }}" class="btn btn-primary shadow btn-xs sharp" title="Voir détail">
                                                    <i class="fas fa-user-check"></i>
                                                </a>
                                                {{-- Modifier --}}
                                                @if($peutModifier)
                                                    <a href="{{ route('declarationNaissance.edit',$dn->code_declaration_naissance) }}" class="btn btn-info shadow btn-xs sharp" title="Modifier">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                @endif
                                                {{-- Envoyer --}}
                                                @if($peutEnvoyer)
                                                    <button class="btn btn-warning btn-envoyer-centre shadow btn-xs sharp"
                                                        title="Envoyer la déclaration au centre d'état civil"
                                                        data-code="{{ $dn->code_declaration_naissance }}"
                                                        data-type="{{ $dn->type_declaration }}"
                                                        data-signe="{{ filled($dn->sig_fs_proof_id) ? '1' : '0' }}"
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

                                                {{-- Consulter le PDF --}}
                                                @if(in_array($dn->type_declaration, ['CERTIFICAT DE NAISSANCE', 'DECLARATION DE NAISSANCE']))
                                                    <a href="{{ route('declarationNaissance.voir.etat', ['id' => $dn->code_declaration_naissance, 'contexte' => 'formation_sanitaire', 'from' => 'declaration']) }}" class="btn btn-warning shadow btn-xs sharp" title="Voir le certificat de naissance">
                                                        <i class="fas fa-file-medical"></i>
                                                    </a>
                                                    @if($dn->type_declaration == 'DECLARATION DE NAISSANCE')
                                                    <a href="{{ route('declarationNaissance.voir.etat', ['id' => $dn->code_declaration_naissance, 'contexte' => 'centre_etat_civil', 'from' => 'declaration']) }}" class="btn btn-success shadow btn-xs sharp" title="Voir la déclaration de naissance">
                                                        <i class="fas fa-file-alt"></i>
                                                    </a>
                                                    @endif
                                                @else
                                                    <a href="{{ route('declarationNaissance.voir.etat', ['id' => $dn->code_declaration_naissance, 'from' => 'declaration']) }}" class="btn btn-warning shadow btn-xs sharp" title="Voir le document (PDF)">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                @endif
                                                @if($peutSupprimer)
                                                    @php
                                                        $libelleSuppression = trim(($dn->enfant->prenom ?? '') . ' ' . ($dn->enfant->nom ?? ''));
                                                        if ($libelleSuppression === '') {
                                                            $libelleSuppression = $dn->code_declaration_naissance;
                                                        }
                                                    @endphp
                                                    <form action="{{ route('declarationNaissance.destroy',$dn->code_declaration_naissance) }}" method="POST" class="d-inline" id="deleteForm{{ $dn->code_declaration_naissance }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger shadow btn-xs sharp btn-delete" title="Supprimer"
                                                            data-code="{{ $dn->code_declaration_naissance }}"
                                                            data-libelle="{{ e($libelleSuppression) }}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
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
                                        <th>Déclarant</th>
                                        <th>Enfant: Nom</th>
                                        <th>Enfant: Prénom</th>
                                        <th>Enfant: Date naissance</th>
                                        <th>Enfant: Date déclaration</th>
                                        <th>Enfant: Sexe</th>
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
                    <div id="bloc-signature-certificat" class="d-none">
                        <div class="alert alert-secondary py-2 small mb-2">
                            <i class="fas fa-file-signature me-1"></i>
                            La signature électronique du certificat par un responsable est requise avant l'envoi.
                            Sélectionnez votre certificat <strong>.p12</strong> et saisissez sa passphrase.
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-7">
                                <label class="form-label small fw-semibold" for="cert_p12_file">Certificat électronique (.p12)</label>
                                <input type="file" class="form-control form-control-sm" id="cert_p12_file" accept=".p12,.pfx,application/x-pkcs12">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label small fw-semibold" for="cert_p12_pin">Passphrase</label>
                                <input type="password" class="form-control form-control-sm" id="cert_p12_pin" autocomplete="off" placeholder="Passphrase du certificat">
                            </div>
                        </div>
                        <div id="cert-sign-feedback" class="alert alert-warning py-2 small d-none mb-0" role="status"></div>
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
</div>
</div>
</div>
@endsection
@section("scripts")

<!-- Signature électronique .p12 -->
    <script src="{{ asset('js/vendor/forge.min.js') }}"></script>
    <script src="{{ asset('js/vendor/elliptic.min.js') }}"></script>
    <script src="{{ asset('js/sifec-p12-sign.js') }}?v=20260717c"></script>

<!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

    <script>
        window.SIFEC_CERT_SIGN_OBLIGATOIRE = {{ \App\Models\GuotSignelecConfig::certificatSignatureObligatoire() ? 'true' : 'false' }};
    </script>

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
                var data = { code_declaration_naissance:cdn };
                var $btn = $(this);
                sifecBtnLoading(this, "Traitement en cours...");
                $.post(route, data, function(response){
                    sifecBtnReset($btn[0], "Envoyer");
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

            // Gestion modale envoi au centre d'état civil depuis index
            let codeDeclaration = null;
            let typeDeclarationEnvoi = null;
            let dejaSigneEnvoi = false;

            // Signature du certificat requise ? (paramètre admin + type certificat + non déjà signé)
            function certificatSignatureRequise() {
                return window.SIFEC_CERT_SIGN_OBLIGATOIRE === true
                    && typeDeclarationEnvoi === 'CERTIFICAT DE NAISSANCE'
                    && !dejaSigneEnvoi;
            }

            function showCertSignError(msg) {
                $("#cert-sign-feedback").removeClass('d-none').text(msg);
                flashAlert("Échec", "error", msg);
            }

            $(document).on('click', '.btn-envoyer-centre', function(){
                if ($(this).hasClass('disabled')) {
                    toastr.warning('Cette déclaration a déjà été envoyée au centre d\'état civil.');
                    return;
                }
                typeDeclarationEnvoi = $(this).data('type');
                dejaSigneEnvoi = String($(this).data('signe')) === '1';
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
                // Afficher le bloc signature .p12 uniquement si requis
                $('#cert-sign-feedback').addClass('d-none').empty();
                $('#cert_p12_file').val('');
                $('#cert_p12_pin').val('');
                if (certificatSignatureRequise()) {
                    $('#bloc-signature-certificat').removeClass('d-none');
                } else {
                    $('#bloc-signature-certificat').addClass('d-none');
                }
                $('#modal-envoyer-centre').modal('show');
                $('#input-code-declaration').val(codeDeclaration);
            });

            // Envoi direct (sans signature requise)
            function envoyerDirect($btn, observation) {
                $.ajax({
                    url: "{{ route('declarationNaissance.mouvement') }}",
                    type: 'POST',
                    data: {
                        code_declaration_naissance: codeDeclaration,
                        observation: observation,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(resp){
                        sifecBtnReset($btn[0], "Envoyer");
                        if(resp.code == "200"){
                            flashAlert("Réponse","success",resp.message);
                            $('#modal-envoyer-centre').modal('hide');
                            setTimeout(()=>location.reload(), 1000);
                        }else{
                            flashAlert("Réponse","error",resp.message);
                        }
                    },
                    error: function(xhr){
                        sifecBtnReset($btn[0], "Envoyer");
                        flashAlert("Erreur","error",xhr.responseJSON?.message || 'Erreur lors de l\'envoi');
                    }
                });
            }

            // Signature .p12 du certificat puis envoi au CEC (une seule étape)
            async function signerPuisEnvoyer($btn, observation) {
                var fileInput = document.getElementById('cert_p12_file');
                var pin = $('#cert_p12_pin').val();
                $('#cert-sign-feedback').addClass('d-none').empty();

                if (!fileInput || !fileInput.files || !fileInput.files[0]) {
                    sifecBtnReset($btn[0], "Envoyer");
                    showCertSignError('Sélectionnez votre fichier certificat (.p12).');
                    return;
                }
                if (!pin || !String(pin).trim()) {
                    sifecBtnReset($btn[0], "Envoyer");
                    showCertSignError('Saisissez la passphrase de votre certificat.');
                    return;
                }
                if (typeof window.SifecP12Sign === 'undefined') {
                    sifecBtnReset($btn[0], "Envoyer");
                    showCertSignError('Bibliothèque de signature non chargée. Rechargez la page.');
                    return;
                }

                try {
                    var prep = await $.ajax({
                        url: "{{ route('declarationNaissance.sign.prepare') }}",
                        type: 'POST',
                        data: { phase: 'fs', codes: [codeDeclaration], _token: '{{ csrf_token() }}' }
                    });
                    if (String(prep.code) !== '200' || !prep.token || !prep.items || !prep.items.length) {
                        sifecBtnReset($btn[0], "Envoyer");
                        showCertSignError(prep && prep.message ? prep.message : 'Échec de la préparation.');
                        return;
                    }

                    $btn.html('<i class="fas fa-spinner fa-spin"></i> Signature locale…');
                    var p12Binary = await window.SifecP12Sign.readP12File(fileInput.files[0]);
                    var signatures = [];
                    for (var i = 0; i < prep.items.length; i++) {
                        var item = prep.items[i];
                        var signatureHex = await window.SifecP12Sign.signHashHex(
                            p12Binary, pin, item.document_hash, prep.expected_serial || null
                        );
                        signatures.push({ code_declaration: item.code_declaration, signature_hex: signatureHex });
                    }

                    $btn.html('<i class="fas fa-spinner fa-spin"></i> Envoi…');
                    var fin = await $.ajax({
                        url: "{{ route('declarationNaissance.sign.finalize') }}",
                        type: 'POST',
                        data: { phase: 'fs', token: prep.token, signatures: signatures, observation: observation, _token: '{{ csrf_token() }}' }
                    });

                    sifecBtnReset($btn[0], "Envoyer");
                    var msg = typeof fin.message === 'string' ? fin.message : 'Réponse inconnue';
                    if (String(fin.code) === '200') {
                        flashAlert("Réponse","success",msg);
                        $('#modal-envoyer-centre').modal('hide');
                        setTimeout(()=>location.reload(), 1200);
                        return;
                    }
                    showCertSignError(msg);
                } catch (err) {
                    sifecBtnReset($btn[0], "Envoyer");
                    var emsg = 'Erreur lors de la signature électronique';
                    if (err && err.responseJSON && err.responseJSON.message) {
                        emsg = typeof err.responseJSON.message === 'string' ? err.responseJSON.message : JSON.stringify(err.responseJSON.message);
                    } else if (err && err.message) {
                        emsg = err.message;
                    }
                    showCertSignError(emsg);
                }
            }

            $('#form-envoyer-centre').on('submit', function(e){
                e.preventDefault();
                var $btn = $('#btn-envoyer-final');
                var observation = $('#observation-centre').val();
                sifecBtnLoading($btn[0], "Traitement…");
                if (certificatSignatureRequise()) {
                    signerPuisEnvoyer($btn, observation);
                } else {
                    envoyerDirect($btn, observation);
                }
            });

            $(document).on('click', '.declaration-naissance-list .btn-delete', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var $btn = $(e.target).closest('.btn-delete');
                var code = $btn.data('code');
                var libelle = $btn[0] && $btn[0].getAttribute ? $btn[0].getAttribute('data-libelle') : $btn.data('libelle');
                var formId = 'deleteForm' + code;
                var form = document.getElementById(formId);
                if (!form) {
                    return;
                }
                Swal.fire({
                    title: 'Confirmer la suppression',
                    html: 'Voulez-vous vraiment supprimer la déclaration <strong>' + $('<div>').text(code).html() + '</strong>' +
                        (libelle ? ' concernant <strong>' + $('<div>').text(libelle).html() + '</strong>' : '') + ' ?',
                    icon: 'warning',
                    showCancelButton: true,
                    focusCancel: true,
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        popup: 'sifec-swal-delete',
                        confirmButton: 'btn btn-danger shadow-sm px-4 fw-semibold',
                        cancelButton: 'btn btn-outline-secondary shadow-sm px-4 fw-semibold'
                    },
                    confirmButtonText: 'Supprimer',
                    cancelButtonText: 'Annuler',
                    confirmButtonAriaLabel: 'Confirmer la suppression',
                    cancelButtonAriaLabel: 'Annuler'
                }).then(function (result) {
                    if (result && (result.value === true || result.isConfirmed === true)) {
                        form.submit();
                    }
                });
            });

        });
    </script>

@endsection
