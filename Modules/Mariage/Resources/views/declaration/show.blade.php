@extends('layout.app')
@section('titre')
Détail formulaire type de mariage
@endsection
@section('sous-titre')
Détail du formulaire type d N° {{ $declaration->code_declaration_mariage }}
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
                $peutConfirmer = false;
                $isDispense = $declaration->type_declaration == "DISPENSE";
                $codesMouvements = $declaration->mouvements->pluck('code_mouvement')->toArray();

                // Vérifier si le dossier a été envoyé au tribunal
                $dejaEnvoyeAuTribunal = $declaration->mouvements->contains('code_mouvement', 'MOUV_2008');

                // Vérifier si le dossier a été renvoyé par le tribunal
                $dossierRenvoie = $declaration->mouvements->contains('code_mouvement', 'MOUV_0004');

                // Vérifier si le dossier a un acte généré (MOUV_0005)
                $acteGenere = $declaration->mouvements->contains('code_mouvement', 'MOUV_0005');

                // Pour les dispenses : si envoyé au tribunal OU acte généré, on ne peut plus envoyer/renvoyer
                $dispenseBloquee = $isDispense && ($dejaEnvoyeAuTribunal || $acteGenere);

                // Détecter le type d'institution de l'utilisateur connecté
                $userInstitutionType = Auth::user()->affectationActive()->institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins ?? '';
                $isTribunal = $userInstitutionType === 'TCINS_0002';
                $isCentre = $userInstitutionType === 'TCINS_0001';

                // Message à afficher selon le type d'institution
                $messageStatut = '';
                if ($dejaEnvoyeAuTribunal && !$dossierRenvoie) {
                    if ($isTribunal) {
                        $messageStatut = 'Dossier reçu de ' . ($declaration->institution->lib_institution ?? 'Centre d\'état civil');
                    } elseif ($isCentre) {
                        $messageStatut = 'Demande de dispense envoyée au tribunal';
                    }
                } elseif ($acteGenere) {
                    $messageStatut = 'Acte généré et envoyé à la signature';
                }

                if (isset($declaration->mouvements) && $declaration->mouvements && $declaration->mouvements->count()) {
                    $dernierMouvement = $declaration->mouvements->sortByDesc('created_at')->first();

                    // Si déjà envoyé au tribunal ET pas renvoyé, OU si acte généré, empêcher toute modification
                    if (($dejaEnvoyeAuTribunal && !$dossierRenvoie) || $acteGenere) {
                        $peutEnvoyer = false;
                        $peutModifier = false;
                        $peutConfirmer = false;
                    } else {
                        // Logique différente selon le type de déclaration
                        if ($isDispense) {
                            // Pour les dispenses : envoi direct au tribunal
                            $peutEnvoyer = true; // Peut envoyer au tribunal
                            $peutModifier = true;
                        } else {
                            // Pour les autres types : validation du dossier
                            if (in_array($dernierMouvement->code_mouvement, ['MOUV_0019', 'MOUV_0009', 'MOUV_0010', 'MOUV_0011'])) {
                                $peutEnvoyer = false; // Dossier déjà validé
                                $peutModifier = false;
                                $peutConfirmer = false;
                            } else {
                                $peutEnvoyer = false; // Ne peut pas envoyer directement
                                $peutModifier = true;
                                $peutConfirmer = true; // Peut confirmer le dossier
                            }
                        }

                        // Si le dernier mouvement est un renvoi (MOUV_0004), on peut renvoyer
                        if ($dernierMouvement->code_mouvement == 'MOUV_0004') {
                            $peutEnvoyer = $isDispense; // Seulement pour les dispenses
                            $peutModifier = true; // Peut modifier les pièces
                            $peutConfirmer = !$isDispense; // Confirmation pour non-dispense
                        }
                    }
                } else {
                    // Aucun mouvement : statut initial
                    if (($dejaEnvoyeAuTribunal && !$dossierRenvoie) || $acteGenere) {
                        $peutEnvoyer = false;
                        $peutModifier = false;
                        $peutConfirmer = false;
                    } else {
                        $peutEnvoyer = $isDispense; // Seulement pour les dispenses
                        $peutModifier = true;
                        $peutConfirmer = !$isDispense; // Confirmation pour non-dispense
                    }
                }
            @endphp
            @if($peutModifier)
                <div class="d-flex flex-wrap gap-2 mb-3">
                    @if(($declaration->epoux->statut_personne ?? 'VIVANT') !== 'DECEDE')
                    <button class="btn btn-primary btn-piece" data-type="epoux" data-nom="{{ $declaration->epoux->nom ?? '' }}" data-url="{{ route('declarationMariage.piece.store', [$declaration->code_declaration_mariage, 'type' => 'epoux']) }}" data-piece="{{ $declaration->piece_epoux ?? '' }}" data-piece-url="{{ $declaration->piece_epoux ? asset($declaration->piece_epoux) : '' }}">
                        <i class="fa fa-id-card"></i> Pièce Époux
                    </button>
                    @endif
                    @if(($declaration->epouse->statut_personne ?? 'VIVANT') !== 'DECEDE')
                    <button class="btn btn-primary btn-piece" data-type="epouse" data-nom="{{ $declaration->epouse->nom ?? '' }}" data-url="{{ route('declarationMariage.piece.store', [$declaration->code_declaration_mariage, 'type' => 'epouse']) }}" data-piece="{{ $declaration->piece_epouse ?? '' }}" data-piece-url="{{ $declaration->piece_epouse ? asset($declaration->piece_epouse) : '' }}">
                        <i class="fa fa-id-card"></i> Pièce Épouse
                    </button>
                    @endif
                </div>

                {{-- Boutons pour les témoins individuels --}}
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <h6 class="w-100 text-muted">Pièces d'identité des témoins :</h6>

                    @if($declaration->temoinHommeEpoux)
                        <button class="btn btn-info btn-piece" data-type="temoin_homme_epoux" data-nom="{{ $declaration->temoinHommeEpoux->nom ?? 'Témoin homme époux' }}" data-url="{{ route('declarationMariage.piece.store', [$declaration->code_declaration_mariage, 'type' => 'temoin_homme_epoux']) }}" data-piece="{{ $declaration->piece_temoin_homme_epoux ?? '' }}" data-piece-url="{{ $declaration->piece_temoin_homme_epoux ? asset($declaration->piece_temoin_homme_epoux) : '' }}">
                            <i class="fa fa-id-card"></i> Témoin homme époux
                        </button>
                    @endif

                    @if($declaration->temoinFemmeEpoux)
                        <button class="btn btn-info btn-piece" data-type="temoin_femme_epoux" data-nom="{{ $declaration->temoinFemmeEpoux->nom ?? 'Témoin femme époux' }}" data-url="{{ route('declarationMariage.piece.store', [$declaration->code_declaration_mariage, 'type' => 'temoin_femme_epoux']) }}" data-piece="{{ $declaration->piece_temoin_femme_epoux ?? '' }}" data-piece-url="{{ $declaration->piece_temoin_femme_epoux ? asset($declaration->piece_temoin_femme_epoux) : '' }}">
                            <i class="fa fa-id-card"></i> Témoin femme époux
                        </button>
                    @endif

                    @if($declaration->temoinHommeEpouse)
                        <button class="btn btn-info btn-piece" data-type="temoin_homme_epouse" data-nom="{{ $declaration->temoinHommeEpouse->nom ?? 'Témoin homme épouse' }}" data-url="{{ route('declarationMariage.piece.store', [$declaration->code_declaration_mariage, 'type' => 'temoin_homme_epouse']) }}" data-piece="{{ $declaration->piece_temoin_homme_epouse ?? '' }}" data-piece-url="{{ $declaration->piece_temoin_homme_epouse ? asset($declaration->piece_temoin_homme_epouse) : '' }}">
                            <i class="fa fa-id-card"></i> Témoin homme épouse
                        </button>
                    @endif

                    @if($declaration->temoinFemmeEpouse)
                        <button class="btn btn-info btn-piece" data-type="temoin_femme_epouse" data-nom="{{ $declaration->temoinFemmeEpouse->nom ?? 'Témoin femme épouse' }}" data-url="{{ route('declarationMariage.piece.store', [$declaration->code_declaration_mariage, 'type' => 'temoin_femme_epouse']) }}" data-piece="{{ $declaration->piece_temoin_femme_epouse ?? '' }}" data-piece-url="{{ $declaration->piece_temoin_femme_epouse ? asset($declaration->piece_temoin_femme_epouse) : '' }}">
                            <i class="fa fa-id-card"></i> Témoin femme épouse
                        </button>
                    @endif

                </div>
            @else
                @if(($dejaEnvoyeAuTribunal && !$dossierRenvoie) || $acteGenere)
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle"></i>
                        <strong>Information :</strong>
                        @if($acteGenere)
                            Acte généré et envoyé à la signature. Aucune modification n'est autorisée.
                        @else
                            Ce dossier a déjà été envoyé au tribunal. Aucune modification n'est autorisée.
                        @endif
                    </div>
                @elseif($dossierRenvoie)
                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Dossier renvoyé :</strong> Ce dossier a été renvoyé par le tribunal. Vous pouvez modifier les pièces jointes et le renvoyer.
                        @if($dernierMouvement && $dernierMouvement->observation)
                            <br><strong>Observation :</strong> {{ $dernierMouvement->observation }}
                        @endif
                    </div>
                @endif
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <div>
                        <strong>Époux :</strong>
                        @if(($declaration->epoux->statut_personne ?? 'VIVANT') === 'DECEDE')
                            <span class="text-muted">Non requise (décédé)</span>
                        @elseif($declaration->piece_epoux)
                            <a href="{{ asset($declaration->piece_epoux) }}" target="_blank" class="btn btn-warning btn-xs"><i class="fa fa-eye"></i> Voir la pièce</a>
                        @else
                            <span class="text-muted">Aucune pièce jointe</span>
                        @endif
                    </div>
                    <div>
                        <strong>Épouse :</strong>
                        @if(($declaration->epouse->statut_personne ?? 'VIVANT') === 'DECEDE')
                            <span class="text-muted">Non requise (décédée)</span>
                        @elseif($declaration->piece_epouse)
                            <a href="{{ asset($declaration->piece_epouse) }}" target="_blank" class="btn btn-warning btn-xs"><i class="fa fa-eye"></i> Voir la pièce</a>
                        @else
                            <span class="text-muted">Aucune pièce jointe</span>
                        @endif
                    </div>

                    {{-- Affichage des pièces des témoins individuels --}}
                    @if($declaration->temoinHommeEpoux && $declaration->piece_temoin_homme_epoux)
                        <div>
                            <strong>Témoin homme époux :</strong>
                            <a href="{{ asset($declaration->piece_temoin_homme_epoux) }}" target="_blank" class="btn btn-warning btn-xs"><i class="fa fa-eye"></i> Voir</a>
                        </div>
                    @endif

                    @if($declaration->temoinFemmeEpoux && $declaration->piece_temoin_femme_epoux)
                        <div>
                            <strong>Témoin femme époux :</strong>
                            <a href="{{ asset($declaration->piece_temoin_femme_epoux) }}" target="_blank" class="btn btn-warning btn-xs"><i class="fa fa-eye"></i> Voir</a>
                        </div>
                    @endif

                    @if($declaration->temoinHommeEpouse && $declaration->piece_temoin_homme_epouse)
                        <div>
                            <strong>Témoin homme épouse :</strong>
                            <a href="{{ asset($declaration->piece_temoin_homme_epouse) }}" target="_blank" class="btn btn-warning btn-xs"><i class="fa fa-eye"></i> Voir</a>
                        </div>
                    @endif

                    @if($declaration->temoinFemmeEpouse && $declaration->piece_temoin_femme_epouse)
                        <div>
                            <strong>Témoin femme épouse :</strong>
                            <a href="{{ asset($declaration->piece_temoin_femme_epouse) }}" target="_blank" class="btn btn-warning btn-xs"><i class="fa fa-eye"></i> Voir</a>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Boutons d'action selon le type de déclaration --}}
            @if($dossierRenvoie && $peutModifier)
                {{-- Priorité au renvoi : si le dossier a été renvoyé, on affiche le bouton de renvoi --}}
                <button class="btn btn-warning btn-renvoyer-tribunal"
                    id="btn-renvoyer-tribunal"
                    title="Renvoyer le dossier modifié au tribunal"
                    data-code="{{ $declaration->code_declaration_mariage }}">
                    <i class="fa fa-paper-plane"></i> Renvoyer au tribunal
                </button>
            @elseif($isDispense)
                {{-- Pour les dispenses : envoi au tribunal (si pas bloqué) --}}
                @if($peutEnvoyer && !$dispenseBloquee)
                    <button class="btn btn-warning btn-envoyer-tribunal"
                        id="btn-envoyer-tribunal"
                        title="Envoyer la demande de dispense au tribunal"
                        data-code="{{ $declaration->code_declaration_mariage }}">
                        <i class="fa fa-gavel"></i> Envoyer au tribunal
                    </button>
                @else
                    @if($messageStatut)
                        <span class="text-success ms-2">{{ $messageStatut }}.</span>
                    @elseif($dispenseBloquee)
                        <span class="text-success ms-2">Demande de dispense traitée.</span>
                    @else
                        <span class="text-success ms-2">Demande de dispense envoyée au tribunal.</span>
                    @endif
                @endif
            @else
                {{-- Pour les autres types : confirmation du dossier --}}
                @if($peutConfirmer)
                    <button class="btn btn-success btn-confirmer-document"
                        id="btn-confirmer-document"
                        title="Confirmer le dossier de mariage"
                        data-code="{{ $declaration->code_declaration_mariage }}">
                        <i class="fa fa-check"></i> Confirmer le dossier
                    </button>
                @else
                    <span class="text-success ms-2">Dossier validé et complet.</span>
                @endif
            @endif
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Détails du formulaire type
                    {{-- <span class="badge bg-primary ms-2">{{ $declaration->type_declaration }}</span> --}}
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
                            <th style="width:40%"><i class="fa fa-hashtag text-primary me-1"></i> Numéro</th>
                            <td>{{ $declaration->code_declaration_mariage }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-user text-primary me-1"></i> Nom de l'époux</th>
                            <td>{{ $declaration->epoux->nom ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-user text-primary me-1"></i> Prénom de l'époux</th>
                            <td>{{ $declaration->epoux->prenom ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-user text-primary me-1"></i> Nom de l'épouse</th>
                            <td>{{ $declaration->epouse->nom ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-user text-primary me-1"></i> Prénom de l'épouse</th>
                            <td>{{ $declaration->epouse->prenom ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-heart text-primary me-1"></i> Date de mariage</th>
                            <td>{{ isset($declaration->date_prevue_mariage) ? date('d/m/Y', strtotime($declaration->date_prevue_mariage)) : '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-map-marker-alt text-primary me-1"></i> Lieu de mariage</th>
                            <td>{{ $declaration->lieu_ceremonie_mariage ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th><i class="fa fa-university text-primary me-1"></i> Centre d'état civil</th>
                            <td>{{ $declaration->institution ? $declaration->institution->lib_institution : '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-calendar-check text-primary me-1"></i> Date de création</th>
                            <td>{{ $declaration->created_at ? $declaration->created_at->format('d/m/Y H:i') : '-' }}</td>
                        </tr>
                        @if($declaration->requisition || $declaration->jugement)
                        <tr>
                            <th><i class="fa fa-gavel text-primary me-1"></i> Document du tribunal</th>
                            <td>
                                @if($declaration->requisition)
                                    <span class="badge bg-info">{{ $declaration->requisition->typeRequisition ? $declaration->requisition->typeRequisition->lib_type_requisition : 'Réquisition' }}</span>
                                    @if($declaration->requisition->num_requisition)
                                        <br><small class="text-muted">N° {{ $declaration->requisition->num_requisition }}</small>
                                    @endif
                                    @if($declaration->requisition->date_requisition)
                                        <br><small class="text-muted">Date : {{ date('d/m/Y', strtotime($declaration->requisition->date_requisition)) }}</small>
                                    @endif
                                @elseif($declaration->jugement)
                                    <span class="badge bg-info">{{ $declaration->jugement->typeJugement ? $declaration->jugement->typeJugement->lib_type_jugement : 'Jugement' }}</span>
                                    @if($declaration->jugement->num_jugement)
                                        <br><small class="text-muted">N° {{ $declaration->jugement->num_jugement }}</small>
                                    @endif
                                    @if($declaration->jugement->date_jugement)
                                        <br><small class="text-muted">Date : {{ date('d/m/Y', strtotime($declaration->jugement->date_jugement)) }}</small>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <th><i class="fa fa-id-card text-primary me-1"></i> Pièce d'identité de l'époux</th>
                            <td>
                                @if(($declaration->epoux->statut_personne ?? 'VIVANT') === 'DECEDE')
                                    <span class="text-muted">Non requise (personne décédée)</span>
                                @elseif($declaration->piece_epoux)
                                    <span class="badge bg-success">Présente</span>
                                    <a href="{{ asset($declaration->piece_epoux) }}" target="_blank" class="btn btn-warning btn-xs ms-2"><i class="fa fa-eye"></i> Voir</a>
                                @else
                                    <span class="text-muted">Non jointe</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-id-card text-primary me-1"></i> Pièce d'identité de l'épouse</th>
                            <td>
                                @if(($declaration->epouse->statut_personne ?? 'VIVANT') === 'DECEDE')
                                    <span class="text-muted">Non requise (personne décédée)</span>
                                @elseif($declaration->piece_epouse)
                                    <span class="badge bg-success">Présente</span>
                                    <a href="{{ asset($declaration->piece_epouse) }}" target="_blank" class="btn btn-warning btn-xs ms-2"><i class="fa fa-eye"></i> Voir</a>
                                @else
                                    <span class="text-muted">Non jointe</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Pièces des témoins individuels --}}
                        @if($declaration->temoinHommeEpoux)
                        <tr>
                            <th><i class="fa fa-id-card text-primary me-1"></i> Pièce témoin homme époux</th>
                            <td>
                                @if($declaration->piece_temoin_homme_epoux)
                                    <span class="badge bg-success">Présente</span>
                                    <a href="{{ asset($declaration->piece_temoin_homme_epoux) }}" target="_blank" class="btn btn-warning btn-xs ms-2"><i class="fa fa-eye"></i> Voir</a>
                                @else
                                    <span class="text-muted">Non jointe</span>
                                @endif
                            </td>
                        </tr>
                        @endif

                        @if($declaration->temoinFemmeEpoux)
                        <tr>
                            <th><i class="fa fa-id-card text-primary me-1"></i> Pièce témoin femme époux</th>
                            <td>
                                @if($declaration->piece_temoin_femme_epoux)
                                    <span class="badge bg-success">Présente</span>
                                    <a href="{{ asset($declaration->piece_temoin_femme_epoux) }}" target="_blank" class="btn btn-warning btn-xs ms-2"><i class="fa fa-eye"></i> Voir</a>
                                @else
                                    <span class="text-muted">Non jointe</span>
                                @endif
                            </td>
                        </tr>
                        @endif

                        @if($declaration->temoinHommeEpouse)
                        <tr>
                            <th><i class="fa fa-id-card text-primary me-1"></i> Pièce témoin homme épouse</th>
                            <td>
                                @if($declaration->piece_temoin_homme_epouse)
                                    <span class="badge bg-success">Présente</span>
                                    <a href="{{ asset($declaration->piece_temoin_homme_epouse) }}" target="_blank" class="btn btn-warning btn-xs ms-2"><i class="fa fa-eye"></i> Voir</a>
                                @else
                                    <span class="text-muted">Non jointe</span>
                                @endif
                            </td>
                        </tr>
                        @endif

                        @if($declaration->temoinFemmeEpouse)
                        <tr>
                            <th><i class="fa fa-id-card text-primary me-1"></i> Pièce témoin femme épouse</th>
                            <td>
                                @if($declaration->piece_temoin_femme_epouse)
                                    <span class="badge bg-success">Présente</span>
                                    <a href="{{ asset($declaration->piece_temoin_femme_epouse) }}" target="_blank" class="btn btn-warning btn-xs ms-2"><i class="fa fa-eye"></i> Voir</a>
                                @else
                                    <span class="text-muted">Non jointe</span>
                                @endif
                            </td>
                        </tr>
                        @endif
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
                            @if($mvt->code_mouvement == 'MOUV_0015')
                                <span class="badge bg-info">{{ $mvt->statut ?? 'Acte produit non rétiré' }}</span>
                            @else
                                <span class="badge bg-warning">{{ $mvt->statut }}</span>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="mt-4">



                    @if ($isTribunal)
                    <a href="{{ route('tribunal.document.index') }}" class="btn btn-info float-end"> <i class="fa fa-edit"></i> Gestion des documents</a>
                    @elseif ($isCentre)
                        <a href="{{ route('declarationMariage.index') }}" class="btn btn-warning float-end"> <i class="fa fa-edit"></i> liste des formulaires types</a>
                        <a href="{{ route('acteMariage.index') }}" class="btn btn-primary float-end"> <i class="fa fa-edit"></i> Gestion des actes</a>
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
                        <span class="text-success">Pièce déjà enregistrée :</span>
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

<!-- Modal Envoi au tribunal (pour dispenses) -->
<div class="modal fade" id="modal-envoyer-tribunal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form-envoyer-tribunal">
            @csrf
            <input type="hidden" name="code_declaration_mariage" id="input-code-declaration-tribunal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Envoyer la demande de dispense au tribunal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Attention :</strong> Cette demande de dispense sera envoyée au tribunal pour validation.
                    </div>
                    <div class="row">
                        <div class="mb-2 col-md-12">
                            <label class="form-label">Code de la déclaration</label>
                            <input type="text" readonly class="form-control" id="code-declaration-tribunal">
                        </div>
                        <div class="mb-2 col-md-12">
                            <label class="form-label">Observation (optionnel)</label>
                            <textarea id="observation-tribunal" name="observation" class="form-control" rows="3" placeholder="Ajoutez une observation pour le tribunal..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning" id="btn-envoyer-tribunal-final">Envoyer au tribunal</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Confirmation de document (pour non-dispenses) -->
<div class="modal fade" id="modal-confirmation-document" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form-confirmation-document">
            @csrf
            <input type="hidden" name="code_declaration_mariage" id="input-code-declaration-confirmation">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation du dossier de mariage</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Information :</strong> Cette action va confirmer que le dossier est conforme et prêt pour la suite du traitement.
                    </div>
                    <div class="row">
                        <div class="mb-2 col-md-12">
                            <label class="form-label">Code de la déclaration</label>
                            <input type="text" readonly class="form-control" id="code-declaration-confirmation">
                        </div>
                        <div class="mb-2 col-md-12">
                            <label class="form-label">Observation (optionnel)</label>
                            <textarea id="observation-confirmation" name="observation" class="form-control" rows="3" placeholder="Ajoutez une observation..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="btn-confirmer-final">Confirmer</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Renvoi au tribunal (après modification) -->
<div class="modal fade" id="modal-renvoyer-tribunal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form-renvoyer-tribunal">
            @csrf
            <input type="hidden" name="code_declaration_mariage" id="input-code-declaration-renvoyer">
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
                            <label class="form-label">Code de la déclaration</label>
                            <input type="text" readonly class="form-control" id="code-declaration-renvoyer">
                        </div>
                        <div class="mb-2 col-md-12">
                            <label class="form-label">Observation (optionnel)</label>
                            <textarea id="observation-renvoyer" name="observation" class="form-control" rows="3" placeholder="Ajoutez une observation pour le tribunal..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btn-renvoyer-final">Renvoyer au tribunal</button>
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

    // Gestion de l'envoi au tribunal (pour dispenses)
    $('.btn-envoyer-tribunal').on('click', function() {
        var codeDeclaration = $(this).data('code');
        $("#code-declaration-tribunal").val(codeDeclaration);
        $("#input-code-declaration-tribunal").val(codeDeclaration);
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

    // Gestion de la confirmation de document (pour non-dispenses)
    $(".btn-confirmer-document").on("click", function() {
        var codeDeclaration = $(this).data('code');
        $("#code-declaration-confirmation").val(codeDeclaration);
        $("#input-code-declaration-confirmation").val(codeDeclaration);
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

    // Gestion du renvoi au tribunal (après modification)
    $('.btn-renvoyer-tribunal').on('click', function() {
        var codeDeclaration = $(this).data('code');
        $("#code-declaration-renvoyer").val(codeDeclaration);
        $("#input-code-declaration-renvoyer").val(codeDeclaration);
        $("#observation-renvoyer").val('');
        $("#modal-renvoyer-tribunal").modal('show');
    });

    // Renvoi final au tribunal
    $("#btn-renvoyer-final").on("click", function(){
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
