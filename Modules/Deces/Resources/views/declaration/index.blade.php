@extends('layout.app')
@section('titre')
Actes Décès
@endsection
@section("styles")

<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">

@endsection

@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>{{ $title ?? 'Liste des déclarations de décès' }}</h4>
                <a href="{{ route("declarationDeces.create") }}"><button type="button" class="btn btn-warning m-t-2 float-end text-white" >{{ $button ?? 'Enregistrer un certificat de décès' }}  <i class="fa fa-plus-circle"></i></button></a>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nom & Prénom</th>
                                        <th>Sexe</th>
                                        <th>Date</th>
                                        <th>Décédé(e) A</th>
                                        <th>Déclarant</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($declarations as $dd)
                                        @php
                                            $dernierMouvement = null;
                                            $peutEnvoyer = false;
                                            $peutModifier = false;
                                            $peutSupprimer = false;
                                            $statutBadge = ['class' => 'badge-secondary', 'label' => 'Brouillon'];

                                            if (isset($dd->mouvements) && $dd->mouvements->count()) {
                                                $dernierMouvement = $dd->mouvements->sortByDesc('created_at')->first();
                                                switch ($dernierMouvement->code_mouvement) {
                                                    case 'MOUV_0002':
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
                                                    case 'MOUV_0032':
                                                    case 'MOUV_2005':
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
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $dd->defunt->nom.' '.$dd->defunt->prenom }}</td>
                                            <td>{{ $dd->defunt->sexe == "M" ? "Masculin" : "Féminin" }}</td>
                                            <td>{{ date("d-m-Y", strtotime($dd->date_heure_deces)) }}</td>
                                            <td>{{$dd->lieu_deces}}</td>
                                            <td>{{ $dd->declarant->nom.' '.$dd->declarant->prenom }}</td>

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
                                                    <a href="{{ route('declarationDeces.show',$dd->code_declaration_deces) }}" class="btn btn-primary shadow btn-xs sharp me-1" title="Voir détail">
                                                        <i class="fas fa-user-check"></i>
                                                    </a>
                                                    {{-- Modifier --}}
                                                    @if($peutModifier)
                                                        <a href="{{ route('declarationDeces.edit',$dd->code_declaration_deces) }}" class="btn btn-info shadow btn-xs sharp me-1" title="Modifier">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </a>
                                                    @endif
                                                    {{-- Envoyer --}}
                                                    @if($peutEnvoyer)
                                                        <button class="btn btn-warning btn-envoyer-centre shadow btn-xs sharp me-1"
                                                            title="Envoyer la déclaration au centre d'état civil"
                                                            data-code="{{ $dd->code_declaration_deces }}"
                                                            data-phase="fs"
                                                            data-signe="{{ filled($dd->sig_fs_proof_id) ? '1' : '0' }}"
                                                            data-piece-defunt="{{ $dd->piece_defunt }}"
                                                            data-piece-declarant="{{ $dd->piece_declarant }}"
                                                            data-piece-pere="{{ $dd->piece_pere }}"
                                                            data-piece-mere="{{ $dd->piece_mere }}"
                                                            data-statut-pere="{{ optional($dd->pere)->statut_personne ?? 'VIVANT' }}"
                                                            data-statut-mere="{{ optional($dd->mere)->statut_personne ?? 'VIVANT' }}"
                                                            data-identitedefunt="{{ $dd->defunt->nomcomplet() }}"
                                                            data-identitedeclarant="{{ $dd->declarant->nomcomplet() }}"
                                                            data-identitepere="{{ $dd->pere ? $dd->pere->nomcomplet() : '' }}"
                                                            data-identitemere="{{ $dd->mere ? $dd->mere->nomcomplet() : '' }}">
                                                            <i class="fas fa-paper-plane"></i>
                                                        </button>
                                                    @endif
                                                    {{-- Supprimer --}}
                                                    @if($peutSupprimer)
                                                        <form action="{{ route('declarationDeces.destroy',$dd->code_declaration_deces) }}" method="POST" style="display: inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette déclaration ?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger shadow btn-xs sharp" title="Supprimer"><i class="fa fa-trash"></i></button>
                                                        </form>
                                                    @endif
                                                    {{-- Consulter le PDF --}}
                                                    @php
                                                        $contexteCertificat = $dd->contexteCertificatOrigine();
                                                        $afficherDeclarationPf = $dd->type_declaration === 'DECLARATION DE DECES'
                                                            && filled($dd->type_declaration_origine);
                                                    @endphp
                                                    @if($dd->estDocumentCertificatOrigine())
                                                        <a href="{{ route('declarationDeces.voir.etat', ['id' => $dd->code_declaration_deces, 'contexte' => $contexteCertificat, 'from' => 'declaration']) }}"
                                                           class="btn btn-warning shadow btn-xs sharp me-1"
                                                           title="{{ $contexteCertificat === 'centre_hygiene' ? 'Voir le certificat de constatation' : 'Voir le certificat de décès' }}">
                                                            <i class="fas fa-file-medical"></i>
                                                        </a>
                                                        @if($afficherDeclarationPf)
                                                            <a href="{{ route('declarationDeces.voir.etat', ['id' => $dd->code_declaration_deces, 'contexte' => 'pompe_funebre', 'from' => 'declaration']) }}"
                                                               class="btn btn-success shadow btn-xs sharp me-1"
                                                               title="Voir la déclaration de décès">
                                                                <i class="fas fa-file-alt"></i>
                                                            </a>
                                                        @endif
                                                    @else
                                                        <a href="{{ route('declarationDeces.voir.etat', ['id' => $dd->code_declaration_deces, 'from' => 'declaration']) }}"
                                                           class="btn btn-warning shadow btn-xs sharp me-1"
                                                           title="Voir le document (PDF)">
                                                            <i class="fas fa-print"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Nom & Prénom</th>
                                        <th>Sexe</th>
                                        <th>Date & Heure</th>
                                        <th>Décédé(e) A</th>
                                        <th>Déclarant</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
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
    <div class="modal-dialog modal-lg" role="document">
        <form id="form-envoyer-centre">
            @csrf
            <input type="hidden" name="code_declaration_deces" id="codedeclaration">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Envoyer la déclaration au centre d'état civil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        Cette action va transmettre la déclaration au centre d'état civil pour traitement.<br>
                        <strong>Êtes-vous sûr de vouloir continuer ?</strong>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Transmission de la déclaration N°</label>
                        <input type="text" readonly class="form-control" id="code-declaration-display">
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
                                    <tr id="piece-defunt-centre">
                                        <td><strong>Défunt</strong></td>
                                        <td id="defunt-nom-centre">-</td>
                                        <td id="defunt-piece-centre">-</td>
                                        <td id="defunt-status-centre"><span class="badge badge-warning">Manquante</span></td>
                                    </tr>
                                    <tr id="piece-declarant-centre">
                                        <td><strong>Déclarant</strong></td>
                                        <td id="declarant-nom-centre">-</td>
                                        <td id="declarant-piece-centre">-</td>
                                        <td id="declarant-status-centre"><span class="badge badge-warning">Manquante</span></td>
                                    </tr>
                                    <tr id="piece-pere-centre">
                                        <td><strong>Père</strong></td>
                                        <td id="pere-nom-centre">-</td>
                                        <td id="pere-piece-centre">-</td>
                                        <td id="pere-status-centre"><span class="badge badge-warning">Manquante</span></td>
                                    </tr>
                                    <tr id="piece-mere-centre">
                                        <td><strong>Mère</strong></td>
                                        <td id="mere-nom-centre">-</td>
                                        <td id="mere-piece-centre">-</td>
                                        <td id="mere-status-centre"><span class="badge badge-warning">Manquante</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="alert-pieces-manquantes-centre" class="alert alert-warning d-none">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Attention :</strong> Certaines pièces d'identité sont manquantes.
                        Il est recommandé de les ajouter avant l'envoi au centre d'état civil.
                    </div>
                    <div id="bloc-signature-certificat" class="d-none">
                        <div class="alert alert-secondary py-2 small mb-2">
                            <i class="fas fa-file-signature me-1"></i>
                            La signature électronique du certificat de décès par le chef de service est requise avant l'envoi.
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-7">
                                <label class="form-label small fw-semibold" for="cert_p12_file">Certificat (.p12) <span class="text-danger">*</span></label>
                                <input type="file" class="form-control form-control-sm" id="cert_p12_file" accept=".p12,.pfx,application/x-pkcs12">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label small fw-semibold" for="cert_p12_pin">Passphrase <span class="text-danger">*</span></label>
                                <input type="password" class="form-control form-control-sm" id="cert_p12_pin" autocomplete="off" placeholder="Passphrase">
                            </div>
                        </div>
                        <div id="cert-sign-feedback" class="alert alert-warning py-2 small d-none mb-0"></div>
                    </div>
                    <div class="mb-2">
                        <label for="observation-centre" class="form-label">Observation (optionnel)</label>
                        <textarea id="observation-centre" name="observation" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info" id="btn-send">
                        <i class="fas fa-paper-plane"></i> Envoyer
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </div>
        </form>
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
                        <input type="hidden" class="form-control" id="codemouvementdeces">
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
</div>
</div>
</div>
@endsection
@section("scripts")

<script src="{{ asset('js/vendor/forge.min.js') }}"></script>
<script src="{{ asset('js/vendor/elliptic.min.js') }}"></script>
<script src="{{ asset('js/sifec-p12-sign.js') }}?v=20260720a"></script>
<!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

    <script>
        window.SIFEC_CERT_SIGN_OBLIGATOIRE = {{ \App\Models\GuotSignelecConfig::certificatSignatureObligatoire() ? 'true' : 'false' }};
        window.SIFEC_PEUT_SIGNER_CERTIFICAT = {{ (
            Gate::allows('module.certificat.deces.signature')
            || Gate::allows('module.certificat.signature')
        ) ? 'true' : 'false' }};
    </script>
    <script>
        $(function(){
            let codeDeclarationEnvoi = null;
            let phaseEnvoi = 'fs';
            let dejaSigneEnvoi = false;

            function certificatSignatureRequise() {
                return window.SIFEC_CERT_SIGN_OBLIGATOIRE === true
                    && window.SIFEC_PEUT_SIGNER_CERTIFICAT === true
                    && phaseEnvoi === 'fs'
                    && !dejaSigneEnvoi;
            }
            function showCertSignError(msg) {
                $("#cert-sign-feedback").removeClass('d-none').text(msg);
                flashAlert("Échec", "error", msg);
            }
            function envoyerDirect($btn, observation) {
                $.ajax({
                    url: "{{ route('declarationDeces.mouvement') }}",
                    type: 'POST',
                    data: { code_declaration_deces: codeDeclarationEnvoi, observation: observation, _token: '{{ csrf_token() }}' },
                    success: function(resp){
                        sifecBtnReset($btn[0], '<i class="fas fa-paper-plane"></i> Envoyer');
                        if(String(resp.code) === '200'){
                            flashAlert("Réponse","success",resp.message);
                            $("#modal-declaration-send").modal('hide');
                            setTimeout(()=>location.reload(), 1200);
                        } else {
                            flashAlert("Réponse","error",resp.message);
                        }
                    },
                    error: function(xhr){
                        sifecBtnReset($btn[0], '<i class="fas fa-paper-plane"></i> Envoyer');
                        flashAlert("Erreur","error", xhr.responseJSON?.message || 'Erreur lors de l\'envoi');
                    }
                });
            }
            async function signerPuisEnvoyer($btn, observation) {
                var fileInput = document.getElementById('cert_p12_file');
                var pin = $('#cert_p12_pin').val();
                $('#cert-sign-feedback').addClass('d-none').empty();
                try {
                    var prep = await $.ajax({ url: "{{ route('declarationDeces.sign.prepare') }}", type: 'POST', data: { phase: phaseEnvoi, codes: [codeDeclarationEnvoi], observation: observation, _token: '{{ csrf_token() }}' } });
                    if (String(prep.code) === '200' && prep.completed) {
                        sifecBtnReset($btn[0], '<i class="fas fa-paper-plane"></i> Envoyer');
                        flashAlert("Succès","success", prep.message);
                        $("#modal-declaration-send").modal('hide');
                        setTimeout(()=>location.reload(), 1200);
                        return;
                    }
                    if (String(prep.code) !== '200' || !prep.token || !prep.items?.length) {
                        sifecBtnReset($btn[0], '<i class="fas fa-paper-plane"></i> Envoyer');
                        showCertSignError(prep?.message || 'Échec préparation.');
                        return;
                    }
                    var needSign = prep.items.some(function(it){ return !it.already_signed; });
                    if (needSign) {
                        if (!fileInput?.files?.[0]) { sifecBtnReset($btn[0], '<i class="fas fa-paper-plane"></i> Envoyer'); showCertSignError('Sélectionnez votre fichier certificat (.p12).'); return; }
                        if (!pin?.trim()) { sifecBtnReset($btn[0], '<i class="fas fa-paper-plane"></i> Envoyer'); showCertSignError('Saisissez la passphrase.'); return; }
                        if (typeof window.SifecP12Sign === 'undefined') { sifecBtnReset($btn[0], '<i class="fas fa-paper-plane"></i> Envoyer'); showCertSignError('Bibliothèque de signature non chargée.'); return; }
                    }
                    $btn.html('<i class="fas fa-spinner fa-spin"></i> Signature…');
                    var p12Binary = needSign ? await window.SifecP12Sign.readP12File(fileInput.files[0]) : null;
                    var signatures = [];
                    for (var i = 0; i < prep.items.length; i++) {
                        var item = prep.items[i];
                        if (item.already_signed) {
                            signatures.push({ code_declaration: item.code_declaration, signature_hex: 'RESUME' });
                        } else {
                            signatures.push({ code_declaration: item.code_declaration, signature_hex: await window.SifecP12Sign.signHashHex(p12Binary, pin, item.document_hash, prep.expected_serial || null) });
                        }
                    }
                    $btn.html('<i class="fas fa-spinner fa-spin"></i> Envoi…');
                    var fin = await $.ajax({ url: "{{ route('declarationDeces.sign.finalize') }}", type: 'POST', data: { phase: phaseEnvoi, token: prep.token, signatures: signatures, observation: observation, _token: '{{ csrf_token() }}' } });
                    sifecBtnReset($btn[0], '<i class="fas fa-paper-plane"></i> Envoyer');
                    if (String(fin.code) === '200') { flashAlert("Succès","success", fin.message); $("#modal-declaration-send").modal('hide'); setTimeout(()=>location.reload(), 1200); }
                    else { showCertSignError(fin.message || 'Échec signature.'); }
                } catch (err) {
                    sifecBtnReset($btn[0], '<i class="fas fa-paper-plane"></i> Envoyer');
                    showCertSignError(err.message || 'Erreur signature électronique');
                }
            }

            $(document).on("click", ".btn-envoyer-centre", function(){
                codeDeclarationEnvoi = $(this).data('code');
                phaseEnvoi = $(this).data('phase') || 'fs';
                dejaSigneEnvoi = String($(this).data('signe')) === '1';

                // Récupération des données du bouton
                const defuntNom = $(this).data('identitedefunt') || '-';
                const declarantNom = $(this).data('identitedeclarant') || '-';
                const pereNom = $(this).data('identitepere') || '-';
                const mereNom = $(this).data('identitemere') || '-';

                const pieceDefunt = $(this).data('piece-defunt') || '';
                const pieceDeclarant = $(this).data('piece-declarant') || '';
                const piecePere = $(this).data('piece-pere') || '';
                const pieceMere = $(this).data('piece-mere') || '';
                const statutPere = $(this).data('statut-pere') || 'VIVANT';
                const statutMere = $(this).data('statut-mere') || 'VIVANT';

                $("#codedeclaration").val(codeDeclarationEnvoi);
                $("#code-declaration-display").val(codeDeclarationEnvoi);
                $('#cert-sign-feedback').addClass('d-none').empty();
                $('#cert_p12_file').val('');
                $('#cert_p12_pin').val('');
                if (certificatSignatureRequise()) { $('#bloc-signature-certificat').removeClass('d-none'); }
                else { $('#bloc-signature-certificat').addClass('d-none'); }

                $('#defunt-nom-centre').text(defuntNom);
                $('#defunt-piece-centre').html(pieceDefunt ? `<a href="/${pieceDefunt}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
                $('#defunt-status-centre').html(pieceDefunt ? '<span class="badge badge-success">Présente</span>' : '<span class="badge bg-secondary">Optionnelle</span>');

                $('#declarant-nom-centre').text(declarantNom);
                $('#declarant-piece-centre').html(pieceDeclarant ? `<a href="/${pieceDeclarant}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
                $('#declarant-status-centre').html(pieceDeclarant ? '<span class="badge badge-success">Présente</span>' : '<span class="badge badge-warning">Manquante</span>');

                $('#pere-nom-centre').text(pereNom);
                $('#pere-piece-centre').html(piecePere ? `<a href="/${piecePere}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
                $('#pere-status-centre').html(statutPere === 'DECEDE'
                    ? (piecePere ? '<span class="badge badge-success">Présente</span>' : '<span class="badge bg-secondary">Optionnelle</span>')
                    : (piecePere ? '<span class="badge badge-success">Présente</span>' : '<span class="badge badge-warning">Manquante</span>'));
                $('#mere-nom-centre').text(mereNom);
                $('#mere-piece-centre').html(pieceMere ? `<a href="/${pieceMere}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
                $('#mere-status-centre').html(statutMere === 'DECEDE'
                    ? (pieceMere ? '<span class="badge badge-success">Présente</span>' : '<span class="badge bg-secondary">Optionnelle</span>')
                    : (pieceMere ? '<span class="badge badge-success">Présente</span>' : '<span class="badge badge-warning">Manquante</span>'));

                let piecesManquantes = false;
                if (!pieceDeclarant || (statutPere === 'VIVANT' && !piecePere) || (statutMere === 'VIVANT' && !pieceMere)) {
                    $('#alert-pieces-manquantes-centre').removeClass('d-none');
                } else {
                    $('#alert-pieces-manquantes-centre').addClass('d-none');
                }

                $("#modal-declaration-send").modal("show");
                return false;
            });

            $('#form-envoyer-centre').on('submit', function(e){
                e.preventDefault();
                var $btn = $('#btn-send');
                var observation = $('#observation-centre').val();
                sifecBtnLoading($btn[0], 'Traitement…');
                if (certificatSignatureRequise()) { signerPuisEnvoyer($btn, observation); }
                else { envoyerDirect($btn, observation); }
            });

            $("a.show-detail-renvoie").on("click", function(){
                var motif = $(this).attr("title");
                var cdd = $(this).attr("href");
                var cmvtn = $(this).attr("cmouvtdeces");
                var obs = $(this).attr("obs");

                $("#codedeclarationback").val(cdd);
                $("#observation").val(obs);
                $("#motif_renvoi").html("<option>"+motif+"</option>");

                $("#modal-declaration-send-back").modal("show");
                return false;
            });

        });
    </script>
@endsection
