@extends('layout.app')
@section('titre')
Détail déclaration de naissance
@endsection
@section('sous-titre')
Détail de la déclaration N° {{ $declaration->code_declaration_naissance }}
@endsection
@section('corps')
@php
    $dummy = "XXXXXXXXXXXXXXXX";
@endphp

<div class="row">
    <div class="col-xl-12">
        <!-- BOUTONS D'ACTION EN HAUT -->
        <div class="mb-4 d-flex flex-wrap gap-2">
                @php
                $dernierMouvement = null;
                $peutEnvoyer = true;
                $peutModifier = true;
                $codesMouvements = $declaration->mouvements->pluck('code_mouvement')->toArray();

                if (isset($declaration->mouvements) && $declaration->mouvements && $declaration->mouvements->count()) {
                    $dernierMouvement = $declaration->mouvements->sortByDesc('created_at')->first();

                    // Si le dernier mouvement est un renvoi, on permet l'envoi et la modification
                    if ($dernierMouvement->code_mouvement == 'MOUV_0004') {
                        $peutEnvoyer = true;
                        $peutModifier = true;
                    }
                    // Sinon, on vérifie si la déclaration a déjà été envoyée
                    else if (in_array('MOUV_0001', $codesMouvements, true) || in_array('MOUV_0035', $codesMouvements, true)) {
                        $peutEnvoyer = false;
                        $peutModifier = false;
                    }

                    // Si le dernier mouvement est une confirmation ou un acte produit, on bloque l'envoi
                    if (in_array($dernierMouvement->code_mouvement, ['MOUV_0019', 'MOUV_0015'])) {
                        $peutEnvoyer = false;
                        $peutModifier = false;
                    }
                }
            @endphp
            @if($peutModifier)
                @if($declaration->num_jugement_placement_provisoir != "" && $declaration->num_fiche_placement != "")
                    <button class="btn btn-primary btn-piece" data-type="extrait_main_courante" data-nom="" data-url="{{ route('declarationNaissance.piece.store', [$declaration->code_declaration_naissance, 'type' => 'extrait_main_courante']) }}" data-piece="{{ $declaration->piece_extrait_main_courante ??  $dummy }}" data-piece-url="{{ $declaration->piece_extrait_main_courante ? asset($declaration->piece_extrait_main_courante) : '' }}">
                        <i class="fa fa-file-alt"></i> Ajouter/Modifier l'extrait de la main courante
                    </button>
                @else
                    <button class="btn btn-primary btn-piece" data-type="declarant" data-nom="{{ $declaration->declarant->nom ??  $dummy }}" data-url="{{ route('declarationNaissance.piece.store', [$declaration->code_declaration_naissance, 'type' => 'declarant']) }}" data-piece="{{ $declaration->piece_declarant ??  $dummy }}" data-piece-url="{{ $declaration->piece_declarant ? asset($declaration->piece_declarant) : '' }}">
                        <i class="fa fa-id-card"></i> Ajouter/Modifier pièce Déclarant
                    </button>
                    <button class="btn btn-primary btn-piece" data-type="pere" data-nom="{{ $declaration->pere->nom ??  $dummy }}" data-url="{{ route('declarationNaissance.piece.store', [$declaration->code_declaration_naissance, 'type' => 'pere']) }}" data-piece="{{ $declaration->piece_pere ??  $dummy }}" data-piece-url="{{ $declaration->piece_pere ? asset($declaration->piece_pere) : '' }}">
                        <i class="fa fa-id-card"></i> Ajouter/Modifier pièce Père
                    </button>
                    <button class="btn btn-primary btn-piece" data-type="mere" data-nom="{{ $declaration->mere->nom ??  $dummy }}" data-url="{{ route('declarationNaissance.piece.store', [$declaration->code_declaration_naissance, 'type' => 'mere']) }}" data-piece="{{ $declaration->piece_mere ??  $dummy }}" data-piece-url="{{ $declaration->piece_mere ? asset($declaration->piece_mere) : '' }}">
                        <i class="fa fa-id-card"></i> Ajouter/Modifier pièce Mère
                    </button>
                @endif
            @endif
            @if(Auth::user()->affectationActive()->code_institution == $declaration->code_institution_destinataire)
                <span class="text-danger ms-2">Dossier reçu.</span>
            @else
                <button class="btn btn-warning btn-envoyer-centre{{ $peutEnvoyer ? '' : ' disabled' }}"
                    id="btn-envoyer-centre"
                    title="Envoyer le dossier au centre d'état civil"
                    data-code="{{ $declaration->code_declaration_naissance }}"
                    data-type="{{ $declaration->type_declaration }}"
                    data-signe="{{ filled($declaration->sig_fs_proof_id) ? '1' : '0' }}"
                    data-piece-declarant="{{ $declaration->piece_declarant }}"
                    data-piece-pere="{{ $declaration->piece_pere }}"
                    data-piece-mere="{{ $declaration->piece_mere }}"
                    data-piece-extrait-main-courante="{{ $declaration->piece_extrait_main_courante }}"
                    data-statut-pere="{{ optional($declaration->pere)->statut_personne ?? 'VIVANT' }}"
                    data-statut-mere="{{ optional($declaration->mere)->statut_personne ?? 'VIVANT' }}"
                    data-identiteDeclarant="{{ $declaration->declarant ? $declaration->declarant->nomcomplet() : '' }}"
                    data-identitePere="{{ $declaration->pere ? $declaration->pere->nomcomplet() : '' }}"
                    data-identiteMere="{{ $declaration->mere ? $declaration->mere->nomcomplet() : '' }}">
                    <i class="fa fa-paper-plane"></i> Envoyer le dossier au centre d'état civil
                </button>
                @if(!$peutEnvoyer)
                    <span class="text-danger ms-2">Ce dossier a déjà été envoyé.</span>
                @endif
            @endif
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Détails
                    <span class="badge bg-primary ms-2">{{ $declaration->libelleAffichageType() }}</span>
                </h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <tbody>
                        <tr>
                            <th style="width:40%"><i class="fa fa-hashtag text-primary me-1"></i> Numéro déclaration</th>
                            <td>{{ $declaration->code_declaration_naissance }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-tag text-primary me-1"></i> Type enregistré</th>
                            <td>{{ $declaration->type_declaration }}</td>
                        </tr>
                        @if(filled($declaration->type_declaration_origine))
                        <tr>
                            <th><i class="fa fa-code-branch text-primary me-1"></i> Origine métier</th>
                            <td>{{ $declaration->type_declaration_origine }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th><i class="fa fa-baby text-primary me-1"></i> Nouveau-né</th>
                            <td>{{ $declaration->enfant ? $declaration->enfant->nomcomplet() : '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-baby text-primary me-1"></i> Sexe</th>
                            <td>{{ $declaration->enfant ? $declaration->enfant->sexe == 'M' ? 'Masculin' : 'Féminin' : '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-calendar text-primary me-1"></i> Date de naissance</th>
                            <td>{{ isset($declaration->date_heure_naissance) ? date('d/m/Y H:i', strtotime($declaration->date_heure_naissance)) : '-' }} minute(s)</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-map-marker-alt text-primary me-1"></i> Lieu de naissance</th>
                            <td>{{ $declaration->enfant->lieu_naissance ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-university text-primary me-1"></i> Lieu de survenance</th>
                            <td>{{ $declaration->lieuSurvenance->lib_lieu_survenance ?? $dummy }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-user-tie text-primary me-1"></i> Déclarant</th>
                            <td>{{ $declaration->declarant ? $declaration->declarant->nomcomplet() : '-' }}</td>
                        </tr>
                        @php
                            $emailsDeclarant = $declaration->declarant?->emailsDepuisPremierContact() ?? [];
                        @endphp
                        <tr>
                            <th><i class="fa fa-envelope text-primary me-1"></i> E-mail(s) du déclarant</th>
                            <td>
                                @if($declaration->declarant)
                                    @forelse($emailsDeclarant as $libelle => $adresse)
                                        <div class="mb-1"><span class="text-muted">{{ $libelle }} :</span> <a href="mailto:{{ $adresse }}">{{ $adresse }}</a></div>
                                    @empty
                                        <span class="text-muted">Non renseigné (contact ou e-mails absents)</span>
                                    @endforelse
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-users text-primary me-1"></i> Filiation</th>
                            <td>{{ $declaration->filiation->lib_filiation ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-male text-primary me-1"></i> Père</th>
                            <td>{{ $declaration->pere ? $declaration->pere->nomcomplet() : '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-female text-primary me-1"></i> Mère</th>
                            <td>{{ $declaration->mere ? $declaration->mere->nomcomplet() : '-' }}</td>
                        </tr>


                        <tr>
                            <th><i class="fa fa-calendar-check text-primary me-1"></i> Date de création</th>
                            <td>{{ $declaration->created_at ? $declaration->created_at->format('d/m/Y H:i') : '-' }}</td>
                        </tr>
                        <!-- Pièces jointes : le déclarant peut être le père, la mère ou toute autre personne ; on utilise le statut de chaque personne concernée -->
                        <tr>
                            <th><i class="fa fa-id-card text-primary me-1"></i> Pièce d'identité du déclarant<br><small class="text-muted fw-normal">(père, mère ou autre personne)</small></th>
                            <td>
                                @if($declaration->piece_declarant)
                                    <span class="badge bg-success">Présente</span>
                                    <a href="{{ asset($declaration->piece_declarant) }}" target="_blank" class="btn btn-warning btn-xs ms-2"><i class="fa fa-eye"></i> Voir</a>
                                @else
                                    <span class="text-muted">{{ ($declaration->declarant->statut_personne ?? 'VIVANT') === 'DECEDE' ? 'Optionnelle (non jointe)' : 'Non jointe' }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-id-card text-primary me-1"></i> Pièce d'identité du père</th>
                            <td>
                                @if($declaration->piece_pere)
                                    <span class="badge bg-success">Présente</span>
                                    <a href="{{ asset($declaration->piece_pere) }}" target="_blank" class="btn btn-warning btn-xs ms-2"><i class="fa fa-eye"></i> Voir</a>
                                @else
                                    <span class="text-muted">{{ ($declaration->pere->statut_personne ?? 'VIVANT') === 'DECEDE' ? 'Optionnelle (non jointe)' : 'Non jointe' }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-id-card text-primary me-1"></i> Pièce d'identité de la mère</th>
                            <td>
                                @if($declaration->piece_mere)
                                    <span class="badge bg-success">Présente</span>
                                    <a href="{{ asset($declaration->piece_mere) }}" target="_blank" class="btn btn-warning btn-xs ms-2"><i class="fa fa-eye"></i> Voir</a>
                                @else
                                    <span class="text-muted">{{ ($declaration->mere->statut_personne ?? 'VIVANT') === 'DECEDE' ? 'Optionnelle (non jointe)' : 'Non jointe' }}</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Historique des mouvements -->
                @if($declaration->mouvements && $declaration->mouvements->count())
                <div class="mt-4">
                    <h5>Historique des mouvements</h5>
                    <ul class="list-group">
                        @foreach($declaration->mouvements->sortBy('created_at') as $mvt)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-arrow-right text-primary me-1"></i>
                                <strong>{{ $mvt->lib_mouvement }}</strong>
                                <span class="text-muted">({{ $mvt->created_at ? $mvt->created_at->format('d/m/Y H:i') : '-' }})</span>
                                @if($mvt->observation)
                                    <br><small>Obs. : {{ $mvt->observation }}</small>
                                @endif
                            </span>
                            <span class="badge bg-warning">{{ $mvt->statut }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('declarationNaissance.index') }}" class="btn btn-info float-end"> <i class="fa fa-list"></i> Retour à la liste</a>

                    @if(Auth::user()->affectationActive()->institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins == "TCINS_0001")
                    <a href="{{ route('acteNaissance.index') }}" class="btn btn-warning float-end"> <i class="fa fa-edit"></i> Gestion des actes</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pièce d'identité -->
<div class="modal fade" id="modal-piece" tabindex="-1">
    <div class="modal-dialog">
        <form id="form-piece" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter/Modifier la pièce d'identité <span id="piece-type-label"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="piece-exist" class="mb-2" style="display:none;">
                        <span class="text-success">Pièce déjà enregistrée :</span>
                        <div class="mt-1 small text-muted">Aperçu</div>
                        <div id="piece-preview" class="mt-1 border rounded p-2 bg-light" style="min-height:120px;"></div>
                    </div>
                    <div class="mb-2">
                        <label for="piece-file" class="form-label">Fichier (PDF/JPG/PNG)</label>
                        <input type="file" class="form-control" id="piece-file" name="piece" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">Enregistrer</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Envoi au centre d'état civil -->
<div class="modal fade" id="modal-envoyer-centre" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form-envoyer-centre">
            @csrf
            <input type="hidden" name="code_declaration_naissance" id="input-code-declaration">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Envoyer le dossier au centre d'état civil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        Cette action va transmettre le dossier au centre d'état civil pour la transcription de l'acte de naissance.<br>
                        <strong>Êtes-vous sûr de vouloir continuer ?</strong>
                    </div>
                    <div class="mb-3">
                        @if($declaration->num_jugement_placement_provisoir != "" && $declaration->num_fiche_placement != "")
                            <h6>Document de placement provisoire</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Document</th>
                                            <th>Numéro</th>
                                            <th>Pièce jointe</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="piece-extrait-main-courante">
                                            <td><strong>Extrait de main courante</strong></td>
                                            <td id="extrait-main-courante-num">-</td>
                                            <td id="extrait-main-courante-piece">-</td>
                                            <td id="extrait-main-courante-status"><span class="badge bg-warning">Manquante</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @else
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
                                            <td id="declarant-status"><span class="badge bg-warning">Manquante</span></td>
                                        </tr>
                                        <tr id="piece-pere">
                                            <td><strong>Père</strong></td>
                                            <td id="pere-nom">-</td>
                                            <td id="pere-piece">-</td>
                                            <td id="pere-status"><span class="badge bg-warning">Manquante</span></td>
                                        </tr>
                                        <tr id="piece-mere">
                                            <td><strong>Mère</strong></td>
                                            <td id="mere-nom">-</td>
                                            <td id="mere-piece">-</td>
                                            <td id="mere-status"><span class="badge bg-warning">Manquante</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    @if($declaration->num_jugement_placement_provisoir != "" && $declaration->num_fiche_placement != "")
                        <div id="alert-pieces-manquantes" class="alert alert-info d-none">
                            <i class="fas fa-info-circle"></i>
                            <strong>Information :</strong> Pour les cas de placement provisoire, l'envoi peut se faire sans contraintes sur les pièces d'identité.
                        </div>
                    @else
                        <div id="alert-pieces-manquantes" class="alert alert-warning d-none">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Attention :</strong> Certaines pièces d'identité sont manquantes.
                            Il est recommandé de les ajouter avant l'envoi au centre d'état civil.
                        </div>
                    @endif
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
                    <div id="bloc-signature-non-autorisee" class="alert alert-danger border-0 small d-none mb-3">
                        <i class="fas fa-ban me-1"></i>
                        La signature électronique est <strong>obligatoire</strong>, mais votre profil n’a pas le droit
                        « Signer un certificat de naissance ». Demandez à un responsable habilité de procéder à l’envoi.
                    </div>
                    <div class="mb-2">
                        <label for="observation-centre" class="form-label">Observation (optionnel)</label>
                        <textarea id="observation-centre" name="observation" class="form-control" rows="3"></textarea>
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
@section('scripts')
<script src="{{ asset('js/vendor/forge.min.js') }}"></script>
<script src="{{ asset('js/vendor/elliptic.min.js') }}"></script>
<script src="{{ asset('js/sifec-p12-sign.js') }}?v=20260717c"></script>
<script>
    window.SIFEC_CERT_SIGN_OBLIGATOIRE = {{ \App\Models\GuotSignelecConfig::certificatSignatureObligatoire() ? 'true' : 'false' }};
    window.SIFEC_PEUT_SIGNER_CERTIFICAT = {{ (
        \Illuminate\Support\Facades\Gate::allows('module.certificat.naissance.signature')
        || \Illuminate\Support\Facades\Gate::allows('module.certificat.signature')
    ) ? 'true' : 'false' }};
</script>
<script>
$(function(){
    // Gestion modale pièce d'identité
    let urlPiece = '';
    let typePiece = '';
    $('.btn-piece').on('click', function(){
        typePiece = $(this).data('type');
        urlPiece = $(this).data('url');
        let nom = $(this).data('nom');
        let piece = $(this).data('piece');
        let pieceUrl = $(this).data('piece-url') || '';
        $('#piece-type-label').text(nom ? '('+nom+')' : '');
        if(piece && pieceUrl){
            $('#piece-exist').show();
            let ext = (piece.split('.').pop() || '').toLowerCase();
            let previewHtml = '';
            if(['jpg','jpeg','png'].includes(ext)){
                previewHtml = `<img src="${pieceUrl}" alt="Aperçu" class="img-fluid" style="max-height:220px; object-fit:contain;">`;
            } else if(ext === 'pdf'){
                previewHtml = `<iframe src="${pieceUrl}" type="application/pdf" width="100%" height="220" class="border-0"></iframe>`;
            } else {
                previewHtml = `<a href="${pieceUrl}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fa fa-external-link"></i> Ouvrir la pièce</a>`;
            }
            $('#piece-preview').html(previewHtml);
        }else{
            $('#piece-exist').hide();
            $('#piece-preview').html('');
        }
        $('#piece-file').val('');
        $('#modal-piece').modal('show');
    });
    // Soumission AJAX pièce
    $('#form-piece').on('submit', function(e){
        e.preventDefault();
        var $btn = $(this).find('button[type="submit"]');
        sifecBtnLoading($btn[0], "Enregistrement...");
        let formData = new FormData(this);
        $.ajax({
            url: urlPiece,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(resp){
                sifecBtnReset($btn[0], "Enregistrer");
                if(resp.code == "200"){
                    flashAlert("Réponse","success",resp.message);
                    $('#modal-piece').modal('hide');
                    setTimeout(()=>location.reload(), 1000);
                }else{
                    flashAlert("Réponse","error",resp.message);
                }
            },
            error: function(xhr){
                sifecBtnReset($btn[0], "Enregistrer");
                flashAlert("Erreur","error",xhr.responseJSON?.message || 'Erreur lors de l\'upload');
            }
        });
    });

    // Gestion modale envoi au centre d'état civil (+ signature .p12 comme sur l'index)
    let codeDeclaration = null;
    let typeDeclarationEnvoi = null;
    let dejaSigneEnvoi = false;

    function certificatSignatureAttendue() {
        return window.SIFEC_CERT_SIGN_OBLIGATOIRE === true
            && typeDeclarationEnvoi === 'CERTIFICAT DE NAISSANCE'
            && !dejaSigneEnvoi;
    }
    function certificatSignatureRequise() {
        return certificatSignatureAttendue() && window.SIFEC_PEUT_SIGNER_CERTIFICAT === true;
    }
    function showCertSignError(msg) {
        $("#cert-sign-feedback").removeClass('d-none').text(msg);
        flashAlert("Échec", "error", msg);
    }

    $(document).on('click', '.btn-envoyer-centre', function(){
        if ($(this).hasClass('disabled')) {
            toastr.warning('Cette déclaration a été envoyée au centre d\'état civil.');
            return;
        }

        typeDeclarationEnvoi = $(this).data('type');
        dejaSigneEnvoi = String($(this).data('signe')) === '1';
        codeDeclaration = $(this).data('code');

        const declarantNom = $(this).attr('data-identiteDeclarant');
        const pereNom = $(this).attr('data-identitePere');
        const mereNom = $(this).attr('data-identiteMere');
        const pieceDeclarant = $(this).data('piece-declarant') || '';
        const piecePere = $(this).data('piece-pere') || '';
        const pieceMere = $(this).data('piece-mere') || '';
        const statutPere = $(this).data('statut-pere') || 'VIVANT';
        const statutMere = $(this).data('statut-mere') || 'VIVANT';

        @if($declaration->num_jugement_placement_provisoir != "" && $declaration->num_fiche_placement != "")
            const pieceExtraitMainCourante = $(this).data('piece-extrait-main-courante') || '';
            $('#extrait-main-courante-num').text('{{ $declaration->num_fiche_placement }}');
            $('#extrait-main-courante-piece').html(pieceExtraitMainCourante ? `<a href="/${pieceExtraitMainCourante}" target="_blank" class="text-success fw-bold">Afficher le document</a>` : '-');
            $('#extrait-main-courante-status').html(pieceExtraitMainCourante ? '<span class="badge bg-success">Présent</span>' : '<span class="badge bg-warning">Manquant</span>');
        @else
            $('#declarant-nom').text(declarantNom);
            $('#declarant-piece').html(pieceDeclarant ? `<a href="/${pieceDeclarant}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
            $('#declarant-status').html(pieceDeclarant ? '<span class="badge bg-success">Présente</span>' : '<span class="badge bg-warning">Manquante</span>');

            $('#pere-nom').text(pereNom);
            $('#pere-piece').html(piecePere ? `<a href="/${piecePere}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
            $('#pere-status').html(statutPere === 'DECEDE'
                ? (piecePere ? '<span class="badge bg-success">Présente</span>' : '<span class="badge bg-secondary">Optionnelle</span>')
                : (piecePere ? '<span class="badge bg-success">Présente</span>' : '<span class="badge bg-warning">Manquante</span>'));

            $('#mere-nom').text(mereNom);
            $('#mere-piece').html(pieceMere ? `<a href="/${pieceMere}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
            $('#mere-status').html(statutMere === 'DECEDE'
                ? (pieceMere ? '<span class="badge bg-success">Présente</span>' : '<span class="badge bg-secondary">Optionnelle</span>')
                : (pieceMere ? '<span class="badge bg-success">Présente</span>' : '<span class="badge bg-warning">Manquante</span>'));
        @endif

        let piecesManquantes = false;
        @if($declaration->num_jugement_placement_provisoir != "" && $declaration->num_fiche_placement != "")
            piecesManquantes = false;
        @else
            if (!pieceDeclarant || (statutPere === 'VIVANT' && !piecePere) || (statutMere === 'VIVANT' && !pieceMere)) {
                piecesManquantes = true;
            }
        @endif

        if (piecesManquantes) {
            $('#alert-pieces-manquantes').removeClass('d-none');
        } else {
            $('#alert-pieces-manquantes').addClass('d-none');
        }

        $('#cert-sign-feedback').addClass('d-none').empty();
        $('#cert_p12_file').val('');
        $('#cert_p12_pin').val('');
        if (certificatSignatureRequise()) {
            $('#bloc-signature-certificat').removeClass('d-none');
            $('#bloc-signature-non-autorisee').addClass('d-none');
            $('#btn-envoyer-final').prop('disabled', piecesManquantes);
        } else if (certificatSignatureAttendue()) {
            $('#bloc-signature-certificat').addClass('d-none');
            $('#bloc-signature-non-autorisee').removeClass('d-none');
            $('#btn-envoyer-final').prop('disabled', true);
        } else {
            $('#bloc-signature-certificat').addClass('d-none');
            $('#bloc-signature-non-autorisee').addClass('d-none');
            $('#btn-envoyer-final').prop('disabled', piecesManquantes);
        }

        $('#modal-envoyer-centre').modal('show');
        $('#input-code-declaration').val(codeDeclaration);
    });

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
        } else if (certificatSignatureAttendue()) {
            sifecBtnReset($btn[0], "Envoyer");
            flashAlert("Signature requise", "error", "Vous n'êtes pas habilité à signer ce certificat. Un responsable doit procéder à l'envoi.");
        } else {
            envoyerDirect($btn, observation);
        }
    });
});
</script>
@endsection
