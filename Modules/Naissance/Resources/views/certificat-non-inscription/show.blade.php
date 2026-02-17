@extends('layout.app')
@section('titre')
Détail du certificat de non inscription
@endsection
@section('sous-titre')
Détail du certificat N° {{ $certificat->numero_certificat }} de non inscription
@endsection
@section('corps')
@php
    use Illuminate\Support\Str;
    $typeDeclaration = $certificat->type_declaration;
    $article = 'la';
    if(Str::startsWith(Str::lower($typeDeclaration), 'certificat')) {
        $article = 'le';
    }
    // Mapping type_declaration => code mouvement d'envoi
    $mappingMouvement = [
        'DECLARATION DE NAISSANCE' => 'MOUV_0001',
        'DECLARATION TARDIVE' => 'MOUV_0001',
        'CERTIFICAT DE NON INSCRIPTION' => 'MOUV_0026',
        "CERTIFICAT DE DESTRUCTION DE L'ACTE" => 'MOUV_0027',
        'FICHE DE TRANSCRIPTION' => 'MOUV_0031',
    ];
    $codeMouvementEnvoi = $mappingMouvement[$typeDeclaration];
    $dummy = "XXXXXXXXXXXXXXXX";
    $tribunal = $certificat->institution->institutionParent->lib_institution;
@endphp

<div class="row">
    <div class="col-xl-12">
        <!-- BOUTONS D'ACTION EN HAUT -->
        <div class="mb-4 d-flex flex-wrap gap-2">
                @php
                $dernierMouvement = null;
                $peutEnvoyer = true;
                $peutModifier = true;
                $codesMouvements = $certificat->mouvements->pluck('code_mouvement')->toArray();

                if (isset($certificat->mouvements) && $certificat->mouvements && $certificat->mouvements->count()) {
                    $dernierMouvement = $certificat->mouvements->sortByDesc('created_at')->first();

                    // Si le dernier mouvement est un renvoi, on permet l'envoi et la modification
                    if ($dernierMouvement->code_mouvement == 'MOUV_0004') {
                        $peutEnvoyer = true;
                        $peutModifier = true;
                    }
                    // Sinon, on vérifie si la déclaration a déjà été envoyée
                    else if (in_array('MOUV_0001', $codesMouvements)) {
                        $peutEnvoyer = false;
                        $peutModifier = false;
                    }

                    // Si le dernier mouvement est une confirmation ou un acte produit, on bloque l'envoi
                    if (in_array($dernierMouvement->code_mouvement, ['MOUV_0019', 'MOUV_0015','MOUV_0006','MOUV_0011','MOUV_1019','MOUV_1001'])) {
                        $peutEnvoyer = false;
                        $peutModifier = false;
                    }
                    //MOUV_1001  MOUV_1019
                }
            @endphp
            @if($peutModifier)
                @if($certificat->num_jugement_placement_provisoir != "" && $certificat->num_fiche_placement != "")
                    <button class="btn btn-primary btn-piece" data-type="extrait_main_courante" data-nom="" data-url="{{ route('declarationNaissance.piece.store', [$certificat->code_declaration_naissance, 'type' => 'extrait_main_courante']) }}" data-piece="{{ $certificat->piece_extrait_main_courante ??  $dummy }}">
                        <i class="fa fa-file-alt"></i> Ajouter/Modifier l'extrait de la main courante
                    </button>
                @else
                    <button class="btn btn-primary btn-piece" data-type="declarant" data-nom="{{ $certificat->declarant->nom ??  $dummy }}" data-url="{{ route('declarationNaissance.piece.store', [$certificat->code_declaration_naissance, 'type' => 'declarant']) }}" data-piece="{{ $certificat->piece_declarant ??  $dummy }}">
                        <i class="fa fa-id-card"></i> Ajouter/Modifier pièce Déclarant
                    </button>
                    <button class="btn btn-primary btn-piece" data-type="pere" data-nom="{{ $certificat->pere->nom ??  $dummy }}" data-url="{{ route('declarationNaissance.piece.store', [$certificat->code_declaration_naissance, 'type' => 'pere']) }}" data-piece="{{ $certificat->piece_pere ??  $dummy }}">
                        <i class="fa fa-id-card"></i> Ajouter/Modifier pièce Père
                    </button>
                    <button class="btn btn-primary btn-piece" data-type="mere" data-nom="{{ $certificat->mere->nom ??  $dummy }}" data-url="{{ route('declarationNaissance.piece.store', [$certificat->code_declaration_naissance, 'type' => 'mere']) }}" data-piece="{{ $certificat->piece_mere ??  $dummy }}">
                        <i class="fa fa-id-card"></i> Ajouter/Modifier pièce Mère
                    </button>
                @endif
            @endif
            @if(Auth::user()->affectationActive()->code_institution == $certificat->code_institution_destinataire)

                @if($peutEnvoyer)
                    <button class="btn btn-warning btn-envoyer-centre{{ $peutEnvoyer ? '' : ' disabled' }}"
                        id="btn-envoyer-centre"
                        title="Envoyer le dossier au centre d'état civil"
                        data-code="{{ $certificat->code_declaration_naissance }}"
                        data-piece-declarant="{{ $certificat->piece_declarant }}"
                        data-piece-pere="{{ $certificat->piece_pere }}"
                        data-piece-mere="{{ $certificat->piece_mere }}"
                        data-piece-extrait-main-courante="{{ $certificat->piece_extrait_main_courante }}"
                        data-identiteDeclarant="{{ $certificat->declarant ? $certificat->declarant->nomcomplet() : '' }}"
                        data-identitePere="{{ $certificat->pere ? $certificat->pere->nomcomplet() : '' }}"
                        data-identiteMere="{{ $certificat->mere ? $certificat->mere->nomcomplet() : '' }}">
                        <i class="fa fa-paper-plane"></i> Envoyer au {{ $tribunal }}
                    </button>
                @else
                    <span class="text-danger ms-2">Dossier reçu.</span>
                @endif

            @else
                <button class="btn btn-warning btn-envoyer-centre{{ $peutEnvoyer ? '' : ' disabled' }}"
                    id="btn-envoyer-centre"
                    title="Envoyer le dossier au centre d'état civil"
                    data-code="{{ $certificat->code_declaration_naissance }}"
                    data-piece-declarant="{{ $certificat->piece_declarant }}"
                    data-piece-pere="{{ $certificat->piece_pere }}"
                    data-piece-mere="{{ $certificat->piece_mere }}"
                    data-piece-extrait-main-courante="{{ $certificat->piece_extrait_main_courante }}"
                    data-identiteDeclarant="{{ $certificat->declarant ? $certificat->declarant->nomcomplet() : '' }}"
                    data-identitePere="{{ $certificat->pere ? $certificat->pere->nomcomplet() : '' }}"
                    data-identiteMere="{{ $certificat->mere ? $certificat->mere->nomcomplet() : '' }}">
                    <i class="fa fa-paper-plane"></i> Envoyer au {{ $tribunal }}
                </button>
                @if(!$peutEnvoyer)
                    <span class="text-danger ms-2">Ce dossier a été envoyé.</span>
                @endif
            @endif
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Détails du certificat
                    <span class="badge bg-primary ms-2">{{ $certificat->type_declaration }}</span>
                </h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <tbody>
                        <tr>
                            <th style="width:40%"><i class="fa fa-hashtag text-primary me-1"></i> Numéro </th>
                            <td>{{ $certificat->numero_certificat }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-user text-primary me-1"></i> Nom de l'enfant</th>
                            <td>{{ $certificat->enfant->nom ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-user text-primary me-1"></i> Prénom de l'enfant</th>
                            <td>{{ $certificat->enfant->prenom ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-birthday-cake text-primary me-1"></i> Date de naissance</th>
                            <td>{{ isset($certificat->date_heure_naissance) ? date('d/m/Y', strtotime($certificat->date_heure_naissance)) : '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-birthday-cake text-primary me-1"></i> Heure de naissance</th>
                            <td> {{ isset($certificat->date_heure_naissance) ? date('H:i', strtotime($certificat->date_heure_naissance)) : '-' }} minute(s)</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-map-marker-alt text-primary me-1"></i> Lieu de naissance</th>
                            <td>{{ $certificat->enfant->lieu_naissance ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-user-tie text-primary me-1"></i> Déclarant</th>
                            <td>{{ $certificat->declarant->nomcomplet() ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-users text-primary me-1"></i> Filiation</th>
                            <td>{{ $certificat->filiation->lib_filiation ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-university text-primary me-1"></i> Centre d'état civil</th>
                            <td>{{ $certificat->institution ? $certificat->institution->lib_institution : '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-calendar-check text-primary me-1"></i> Date de création</th>
                            <td>{{ $certificat->created_at ? $certificat->created_at->format('d/m/Y H:i') : '-' }} minute(s)</td>
                        </tr>
                        @if($certificat->requisition || $certificat->jugement)
                        <tr>
                            <th><i class="fa fa-gavel text-primary me-1"></i> Document du tribunal</th>
                            <td>
                                @if($certificat->requisition)
                                    <span class="badge bg-info">{{ $certificat->requisition->typeRequisition ? $certificat->requisition->typeRequisition->lib_type_requisition : 'Réquisition' }}</span>
                                    @if($certificat->requisition->num_requisition)
                                        <br><small class="text-muted">N° {{ $certificat->requisition->num_requisition }}</small>
                                    @endif
                                    @if($certificat->requisition->date_requisition)
                                        <br><small class="text-muted">Date : {{ date('d/m/Y', strtotime($certificat->requisition->date_requisition)) }}</small>
                                    @endif
                                @elseif($certificat->jugement)
                                    <span class="badge bg-info">{{ $certificat->jugement->typeJugement ? $certificat->jugement->typeJugement->lib_type_jugement : 'Jugement' }}</span>
                                    @if($certificat->jugement->num_jugement)
                                        <br><small class="text-muted">N° {{ $certificat->jugement->num_jugement }}</small>
                                    @endif
                                    @if($certificat->jugement->date_jugement)
                                        <br><small class="text-muted">Date : {{ date('d/m/Y', strtotime($certificat->jugement->date_jugement)) }}</small>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <th><i class="fa fa-id-card text-primary me-1"></i> Pièce d'identité du déclarant</th>
                            <td>
                                @if($certificat->piece_declarant)
                                   <span class="badge bg-success">Présente</span>
                                   <a href="{{ asset($certificat->piece_declarant) }}" target="_blank" class="btn btn-warning btn-xs ms-2"><i class="fa fa-eye"></i> Voir</a>
                                @else
                                    <span class="text-muted">Non jointe</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-id-card text-primary me-1"></i> Pièce d'identité du père</th>
                            <td>
                                @if($certificat->piece_pere)
                                    <span class="badge bg-success">Présente</span>
                                    <a href="{{ asset($certificat->piece_pere) }}" target="_blank" class="btn btn-warning btn-xs ms-2"><i class="fa fa-eye"></i> Voir</a>
                                @else
                                    <span class="text-muted">Non jointe</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-id-card text-primary me-1"></i> Pièce d'identité de la mère</th>
                            <td>
                                @if($certificat->piece_mere)
                                    <span class="badge bg-success">Présente</span>
                                    <a href="{{ asset($certificat->piece_mere) }}" target="_blank" class="btn btn-warning btn-xs ms-2"><i class="fa fa-eye"></i> Voir</a>
                                @else
                                    <span class="text-muted">Non jointe</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Historique des mouvements -->
                @if($certificat->mouvements && $certificat->mouvements->count())
                <div class="mt-4">
                    <h5>Historique des mouvements</h5>
                    <ul class="list-group">
                        @foreach($certificat->mouvements->sortBy('created_at') as $mvt)
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
                    @if(Auth::user()->affectationActive()->institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins == "TCINS_0002")
                    <a href="{{ route('tribunal.document.index') }}" class="btn btn-info float-end"> <i class="fa fa-list"></i> Gestion des documents</a>
                    @endif
                    @if(Auth::user()->affectationActive()->institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins == "TCINS_0001")
                    <a href="{{ route('acteNaissance.index') }}" class="btn btn-warning float-end"> <i class="fa fa-edit"></i> Gestion des actes</a>
                    <a href="{{ route('certificatNonInscription.index') }}" class="btn btn-info float-end"> <i class="fa fa-list"></i> Retour à la liste</a>
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
                        <div id="piece-preview" class="mt-2"></div>
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

<!-- Modal Envoi au tribunal -->
<div class="modal fade" id="modal-envoyer-centre" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form-envoyer-centre">
            @csrf
            <input type="hidden" name="code_declaration_naissance" id="input-code-declaration">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Envoyer le dossier au tribunal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        Cette action va transmettre le dossier au tribunal pour une demande d'une réquisition ou d'un jugement.<br>
                        <strong>Êtes-vous sûr de vouloir continuer ?</strong>
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
                        Il est recommandé de les ajouter avant l'envoi au tribunal.
                    </div>
                    <div class="mb-2">
                        <label for="observation-centre" class="form-label">Observation (optionnel)</label>
                        <textarea id="observation-centre" name="observation" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning" id="btn-envoyer-final">Envoyer</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
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
        $('#piece-type-label').text(nom ? '('+nom+')' : '');
        if(piece){
            $('#piece-exist').show();
            // Aperçu dynamique
            let ext = piece.split('.').pop().toLowerCase();
            let previewHtml = '';
            if(['jpg','jpeg','png'].includes(ext)){
                previewHtml = `<img src="/${piece}" alt="Aperçu" style="max-width:100%;max-height:200px;">`;
            }else if(ext === 'pdf'){
                previewHtml = `<embed src="/${piece}" type="application/pdf" width="100%" height="200px" />`;
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
        let formData = new FormData(this);
        $.ajax({
            url: urlPiece,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(resp){
                if(resp.code == "200"){
                    flashAlert("Réponse","success",resp.message);
                    $('#modal-piece').modal('hide');
                    setTimeout(()=>location.reload(), 1000);
                }else{
                    flashAlert("Réponse","error",resp.message);
                }
            },
            error: function(xhr){
                flashAlert("Erreur","error",xhr.responseJSON?.message || 'Erreur lors de l\'upload');
            }
        });
    });

    // Gestion modale envoi au tribunal
    $('.btn-envoyer-centre').on('click', function(){
        if ($(this).hasClass('disabled')) {
            toastr.warning('Cette déclaration a déjà été envoyée au centre d\'état civil.');
            return;
        }
        // Remplir le tableau des pièces dans le modal
        const declarantNom = $(this).attr('data-identiteDeclarant');
        const pereNom = $(this).attr('data-identitePere');
        const mereNom = $(this).attr('data-identiteMere');
        const pieceDeclarant = $(this).data('piece-declarant') || '';
        const piecePere = $(this).data('piece-pere') || '';
        const pieceMere = $(this).data('piece-mere') || '';
        $('#declarant-nom').text(declarantNom);
        $('#declarant-piece').html(pieceDeclarant ? `<a href="/${pieceDeclarant}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
        $('#declarant-status').html(pieceDeclarant ? '<span class="badge badge-success">Présente</span>' : '<span class="badge badge-warning">Manquante</span>');
        $('#pere-nom').text(pereNom);
        $('#pere-piece').html(piecePere ? `<a href="/${piecePere}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
        $('#pere-status').html(piecePere ? '<span class="badge badge-success">Présente</span>' : '<span class="badge badge-warning">Manquante</span>');
        $('#mere-nom').text(mereNom);
        $('#mere-piece').html(pieceMere ? `<a href="/${pieceMere}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
        $('#mere-status').html(pieceMere ? '<span class="badge badge-success">Présente</span>' : '<span class="badge badge-warning">Manquante</span>');
        let piecesManquantes = false;
        if (!pieceDeclarant || !piecePere || !pieceMere) {
            piecesManquantes = true;
            $('#alert-pieces-manquantes').removeClass('d-none');
        } else {
            $('#alert-pieces-manquantes').addClass('d-none');
        }
        $('#btn-envoyer-final').prop('disabled', piecesManquantes);
        $('#modal-envoyer-centre').modal('show');
        $('#input-code-declaration').val($(this).data('code'));
    });
    $('#form-envoyer-centre').on('submit', function(e){
                e.preventDefault();
                let url = "{{ route('certificatNonInscription.mouvement') }}";
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
