@extends('layout.app')
@section('titre')
Détail {{ strtolower($certificat->type_declaration) }}
@endsection
@section('sous-titre')
Détail {{ strtolower($certificat->type_declaration) }} N° {{ $certificat->numero_certificat }}
@endsection
@section('corps')
<div class="row">
    <div class="col-xl-12">
        <!-- BOUTONS D'ACTION EN HAUT -->
        <div class="mb-4 d-flex flex-wrap gap-2">
                        @php
                $dernierMouvement = null;
                $peutEnvoyer = true;
                $peutModifier = true;
                $codesMouvements = $certificat->mouvements->pluck('code_mouvement')->toArray();

                // Vérifier si le dossier a été envoyé au tribunal
                $dejaEnvoyeAuTribunal = $certificat->mouvements->contains('code_mouvement', 'MOUV_0006');

                // Détecter le type d'institution de l'utilisateur connecté
                $userInstitutionType = Auth::user()->affectationActive()->institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins ?? '';
                $isTribunal = $userInstitutionType === 'TCINS_0002';
                $isCentre = $userInstitutionType === 'TCINS_0001';

                // Message à afficher selon le type d'institution
                $messageStatut = '';
                if ($dejaEnvoyeAuTribunal) {
                    if ($isTribunal) {
                        $messageStatut = 'Dossier reçu de ' . ($certificat->institution->lib_institution ?? 'Centre d\'état civil');
                    } elseif ($isCentre) {
                        $messageStatut = 'Certificat envoyé au tribunal';
                    }
                }

                if (isset($certificat->mouvements) && $certificat->mouvements && $certificat->mouvements->count()) {
                    $dernierMouvement = $certificat->mouvements->sortByDesc('created_at')->first();

                    // Si le dernier mouvement est un envoi (MOUV_0006), on ne peut plus envoyer
                    if ($dernierMouvement->code_mouvement == 'MOUV_0006') {
                        $peutEnvoyer = false;
                        $peutModifier = false;
                    }

                    // Si le dernier mouvement est un renvoi (MOUV_0004), on peut renvoyer
                    if ($dernierMouvement->code_mouvement == 'MOUV_0004') {
                        $peutEnvoyer = true;
                        $peutModifier = true;
                    }
                }
            @endphp
            @if($peutModifier)
                <button class="btn btn-primary btn-piece" data-type="defunt" data-nom="{{ $certificat->defunt->nom ?? '' }}" data-url="{{ route('declarationDeces.piece.store', [$certificat->code_declaration_deces, 'type' => 'defunt']) }}" data-piece="{{ $certificat->piece_defunt ?? '' }}" data-piece-url="{{ $certificat->piece_defunt ? asset($certificat->piece_defunt) : '' }}">
                    <i class="fa fa-id-card"></i> Ajouter/Modifier pièce Défunt
                </button>
                <button class="btn btn-primary btn-piece" data-type="declarant" data-nom="{{ $certificat->declarant->nom ?? '' }}" data-url="{{ route('declarationDeces.piece.store', [$certificat->code_declaration_deces, 'type' => 'declarant']) }}" data-piece="{{ $certificat->piece_declarant ?? '' }}" data-piece-url="{{ $certificat->piece_declarant ? asset($certificat->piece_declarant) : '' }}">
                    <i class="fa fa-id-card"></i> Ajouter/Modifier pièce Déclarant
                </button>
                <button class="btn btn-primary btn-piece" data-type="pere" data-nom="{{ $certificat->pere->nom ?? '' }}" data-url="{{ route('declarationDeces.piece.store', [$certificat->code_declaration_deces, 'type' => 'pere']) }}" data-piece="{{ $certificat->piece_pere ?? '' }}" data-piece-url="{{ $certificat->piece_pere ? asset($certificat->piece_pere) : '' }}">
                    <i class="fa fa-id-card"></i> Ajouter/Modifier pièce Père
                </button>
                <button class="btn btn-primary btn-piece" data-type="mere" data-nom="{{ $certificat->mere->nom ?? '' }}" data-url="{{ route('declarationDeces.piece.store', [$certificat->code_declaration_deces, 'type' => 'mere']) }}" data-piece="{{ $certificat->piece_mere ?? '' }}" data-piece-url="{{ $certificat->piece_mere ? asset($certificat->piece_mere) : '' }}">
                    <i class="fa fa-id-card"></i> Ajouter/Modifier pièce Mère
                </button>
            @else
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <div>
                        <strong>Défunt :</strong>
                        @if($certificat->piece_defunt)
                            <a href="/{{ $certificat->piece_defunt }}" target="_blank" class="btn btn-warning btn-xs"><i class="fa fa-eye"></i> Voir la pièce</a>
                        @else
                            <span class="text-muted">Aucune pièce jointe</span>
                        @endif
                    </div>
                    <div>
                        <strong>Déclarant :</strong>
                        @if($certificat->piece_declarant)
                            <a href="/{{ $certificat->piece_declarant }}" target="_blank" class="btn btn-warning btn-xs"><i class="fa fa-eye"></i> Voir la pièce</a>
                        @else
                            <span class="text-muted">Aucune pièce jointe</span>
                        @endif
                    </div>
                    <div>
                        <strong>Père :</strong>
                        @if($certificat->piece_pere)
                            <a href="/{{ $certificat->piece_pere }}" target="_blank" class="btn btn-warning btn-xs"><i class="fa fa-eye"></i> Voir la pièce</a>
                        @else
                            <span class="text-muted">Aucune pièce jointe</span>
                        @endif
                    </div>
                    <div>
                        <strong>Mère :</strong>
                        @if($certificat->piece_mere)
                            <a href="/{{ $certificat->piece_mere }}" target="_blank" class="btn btn-warning btn-xs"><i class="fa fa-eye"></i> Voir la pièce</a>
                        @else
                            <span class="text-muted">Aucune pièce jointe</span>
                        @endif
                    </div>
                </div>
            @endif
            @if($peutEnvoyer)
            <button class="btn btn-warning btn-envoyer-centre"
                id="btn-envoyer-centre"
                title="{{ $dernierMouvement && $dernierMouvement->code_mouvement == 'MOUV_0004' ? 'Réenvoyer au tribunal' : 'Envoyer au tribunal' }}"
                data-code="{{ $certificat->code_declaration_deces }}"
                data-piece-defunt="{{ $certificat->piece_defunt }}"
                data-piece-declarant="{{ $certificat->piece_declarant }}"
                data-piece-pere="{{ $certificat->piece_pere }}"
                data-piece-mere="{{ $certificat->piece_mere }}"
                data-identiteDefunt="{{ $certificat->defunt->nomcomplet() }}"
                data-identiteDeclarant="{{ $certificat->declarant->nomcomplet() }}"
                data-identitePere="{{ $certificat->pere->nomcomplet() }}"
                data-identiteMere="{{ $certificat->mere->nomcomplet() }}">
                <i class="fa fa-paper-plane"></i> Envoyer le dossier au tribunal
            </button>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    @if($messageStatut)
                        {{ $messageStatut }}
                    @else
                        Ce certificat a déjà été envoyé au tribunal
                    @endif
                </div>
            @endif

            {{-- Bouton pour voir le document importé par le tribunal --}}
            @if($certificat->requisition || $certificat->jugement)
                <a href="{{ route('tribunal.voir_document', ['type' => 'deces', 'id' => $certificat->code_declaration_deces]) }}"
                    class="btn btn-info" title="Voir le document importé par le tribunal">
                    <i class="fas fa-eye"></i>
                    @if($certificat->requisition)
                        Voir la réquisition
                    @elseif($certificat->jugement)
                        Voir le jugement
                    @else
                        Voir le document
                    @endif
                </a>
            @endif
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Détails du certificat
                    <span class="badge bg-primary ms-2">{{ $certificat->type_declaration }}</span>
                </h4>
                @if($messageStatut)
                    <div class="mt-2">
                        <span class="badge bg-info">
                            <i class="fas fa-info-circle"></i> {{ $messageStatut }}
                        </span>
                    </div>
                @endif
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <tbody>
                        <tr>
                            <th style="width:40%"><i class="fa fa-hashtag text-primary me-1"></i> Numéro  {{ $certificat->type_declaration }}</th>
                            <td>{{ $certificat->numero_certificat }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-user text-primary me-1"></i> Nom du défunt</th>
                            <td>{{ $certificat->defunt->nom ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-user text-primary me-1"></i> Prénom du défunt</th>
                            <td>{{ $certificat->defunt->prenom ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-calendar-times text-primary me-1"></i> Date de décès</th>
                            <td>{{ isset($certificat->defunt->date_heure_deces) ? date('d/m/Y', strtotime($certificat->defunt->date_heure_deces)) : '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-map-marker-alt text-primary me-1"></i> Lieu de décès</th>
                            <td>{{ $certificat->lieu_deces ?? '-' }}</td>
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
                            <td>{{ $certificat->created_at ? $certificat->created_at->format('d/m/Y H:i') : '-' }}</td>
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
                            <th><i class="fa fa-id-card text-primary me-1"></i> Pièce d'identité du défunt</th>
                            <td>
                                @if($certificat->piece_defunt)
                                   <span class="badge bg-success">Présente</span>
                                   <a href="/{{ $certificat->piece_defunt }}" target="_blank" class="btn btn-warning btn-xs ms-2"><i class="fa fa-eye"></i> Voir</a>
                                @else
                                    <span class="text-muted">Non jointe</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-id-card text-primary me-1"></i> Pièce d'identité du déclarant</th>
                            <td>
                                @if($certificat->piece_declarant)
                                   <span class="badge bg-success">Présente</span>
                                   <a href="/{{ $certificat->piece_declarant }}" target="_blank" class="btn btn-warning btn-xs ms-2"><i class="fa fa-eye"></i> Voir</a>
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
                                    <a href="/{{ $certificat->piece_pere }}" target="_blank" class="btn btn-warning btn-xs ms-2"><i class="fa fa-eye"></i> Voir</a>
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
                                    <a href="/{{ $certificat->piece_mere }}" target="_blank" class="btn btn-warning btn-xs ms-2"><i class="fa fa-eye"></i> Voir</a>
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
                    <!-- retour à la liste selon le type de certificat -->
                    @if($certificat->type_declaration == 'CERTIFICAT DE NON INSCRIPTION')
                        @if(Auth::user()->affectationActive()->institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins == "TCINS_0002")
                        <a href="{{ route('tribunal.document.index') }}" class="btn btn-info float-end"> <i class="fa fa-list"></i> Gestion des documents</a>
                        @else
                        <a href="{{ route('certificatNonInscriptionDeces.index') }}" class="btn btn-info float-end"> <i class="fa fa-list"></i> Retour à la liste</a>
                        @endif
                    @elseif($certificat->type_declaration == 'CERTIFICAT DE DESTRUCTION DE L\'ACTE')
                    <a href="{{ route('certificatDestruction.index') }}" class="btn btn-info float-end"> <i class="fa fa-list"></i> Retour à la liste</a>
                    @else
                    <a href="{{ route('declarationDeces.index') }}" class="btn btn-info float-end"> <i class="fa fa-list"></i> Retour à la liste</a>
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

<!-- Modal Envoi au tribunal -->
<div class="modal fade" id="modal-envoyer-tribunal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form-envoyer-tribunal">
            @csrf
            <input type="hidden" name="code_declaration_deces" id="input-code-tribunal">
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
                                    <tr id="piece-defunt-tribunal">
                                        <td><strong>Défunt</strong></td>
                                        <td id="defunt-nom-tribunal">-</td>
                                        <td id="defunt-piece-tribunal">-</td>
                                        <td id="defunt-status-tribunal"><span class="badge badge-warning">Manquante</span></td>
                                    </tr>
                                    <tr id="piece-declarant-tribunal">
                                        <td><strong>Déclarant</strong></td>
                                        <td id="declarant-nom-tribunal">-</td>
                                        <td id="declarant-piece-tribunal">-</td>
                                        <td id="declarant-status-tribunal"><span class="badge badge-warning">Manquante</span></td>
                                    </tr>
                                    <tr id="piece-pere-tribunal">
                                        <td><strong>Père</strong></td>
                                        <td id="pere-nom-tribunal">-</td>
                                        <td id="pere-piece-tribunal">-</td>
                                        <td id="pere-status-tribunal"><span class="badge badge-warning">Manquante</span></td>
                                    </tr>
                                    <tr id="piece-mere-tribunal">
                                        <td><strong>Mère</strong></td>
                                        <td id="mere-nom-tribunal">-</td>
                                        <td id="mere-piece-tribunal">-</td>
                                        <td id="mere-status-tribunal"><span class="badge badge-warning">Manquante</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="alert-pieces-manquantes-tribunal" class="alert alert-warning d-none">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Attention :</strong> Certaines pièces d'identité sont manquantes.
                        Il est recommandé de les ajouter avant l'envoi au tribunal.
                    </div>
                    <div class="mb-2">
                        <label for="observation-tribunal" class="form-label">Observation (optionnel)</label>
                        <textarea id="observation-tribunal" name="observation" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning" id="btn-envoyer-tribunal-final">
                        <i class="fas fa-gavel"></i> Envoyer
                    </button>
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
            flashAlert("Réponse","warning",'Cette déclaration a déjà été envoyée au centre d\'état civil.');
            return;
        }
        let codeTribunal = $(this).data('code');
        $('#input-code-tribunal').val(codeTribunal);

        // Récupération des infos de la ligne sélectionnée
        const defuntNom = $(this).attr('data-identiteDefunt');
        const declarantNom = $(this).attr('data-identiteDeclarant');
        const pereNom = $(this).attr('data-identitePere');
        const mereNom = $(this).attr('data-identiteMere');
        // Pour les pièces, il faut ajouter des data-piece-* sur le bouton côté Blade si possible
        const pieceDefunt = $(this).data('piece-defunt') || '';
        const pieceDeclarant = $(this).data('piece-declarant') || '';
        const piecePere = $(this).data('piece-pere') || '';
        const pieceMere = $(this).data('piece-mere') || '';

        // Remplir le tableau du modal
        $('#defunt-nom-tribunal').text(defuntNom);
        $('#defunt-piece-tribunal').html(pieceDefunt ? `<a href="/${pieceDefunt}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
        $('#defunt-status-tribunal').html(pieceDefunt ? '<span class="badge badge-success">Présente</span>' : '<span class="badge badge-warning">Manquante</span>');

        $('#declarant-nom-tribunal').text(declarantNom);
        $('#declarant-piece-tribunal').html(pieceDeclarant ? `<a href="/${pieceDeclarant}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
        $('#declarant-status-tribunal').html(pieceDeclarant ? '<span class="badge badge-success">Présente</span>' : '<span class="badge badge-warning">Manquante</span>');

        $('#pere-nom-tribunal').text(pereNom);
        $('#pere-piece-tribunal').html(piecePere ? `<a href="/${piecePere}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
        $('#pere-status-tribunal').html(piecePere ? '<span class="badge badge-success">Présente</span>' : '<span class="badge badge-warning">Manquante</span>');

        $('#mere-nom-tribunal').text(mereNom);
        $('#mere-piece-tribunal').html(pieceMere ? `<a href="/${pieceMere}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
        $('#mere-status-tribunal').html(pieceMere ? '<span class="badge badge-success">Présente</span>' : '<span class="badge badge-warning">Manquante</span>');

        // Vérification des pièces
        let piecesManquantes = false;
        if (!pieceDefunt || !pieceDeclarant || !piecePere || !pieceMere) {
            piecesManquantes = true;
            $('#alert-pieces-manquantes-tribunal').removeClass('d-none');
        } else {
            $('#alert-pieces-manquantes-tribunal').addClass('d-none');
        }
        // Désactiver le bouton si pièce manquante
        $('#btn-envoyer-tribunal-final').prop('disabled', piecesManquantes);

        $('#modal-envoyer-tribunal').modal('show');
    });
    $('#form-envoyer-tribunal').on('submit', function(e){
                e.preventDefault();
                let url = "{{ route('certificatNonInscriptionDeces.mouvement') }}";
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(resp){
                        if(resp.code == "200"){
                            flashAlert("Réponse","success",resp.message);
                            $('#modal-envoyer-tribunal').modal('hide');
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
