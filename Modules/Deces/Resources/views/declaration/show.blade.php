@extends('layout.app')
@section('titre')
Détail déclaration de décès
@endsection
@section('sous-titre')
Détail de la déclaration N° {{ $declaration->code_declaration_deces }}
@endsection
@section('corps')
@php
use Illuminate\Support\Str;
$typeDeclaration = $declaration->type_declaration;
$article = 'la';
if(Str::startsWith(Str::lower($typeDeclaration), 'certificat')) {
$article = 'le';
}
// Mapping type_declaration => code mouvement d'envoi
$mappingMouvement = [
'DECLARATION DE DECES' => 'MOUV_0002',
'DECLARATION TARDIVE' => 'MOUV_0002',
'CERTIFICAT DE CONSTATATION DE DECES' => 'MOUV_2006',
'CERTIFICAT DE NON INSCRIPTION' => 'MOUV_2007',
'CERTIFICAT DE TRANSCRIPTION' => 'MOUV_2011',
"CERTIFICAT DE DESTRUCTION DE L'ACTE" => 'MOUV_2008',
'FICHE DE TRANSCRIPTION' => 'MOUV_2009',
];
$codeMouvementEnvoi = $mappingMouvement[$typeDeclaration];
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

            // Vérifier si le dossier a été envoyé
            $dejaEnvoye = in_array('MOUV_0002', $codesMouvements) || in_array('MOUV_2006', $codesMouvements) || in_array('MOUV_2007', $codesMouvements) || in_array('MOUV_2008', $codesMouvements) || in_array('MOUV_2009', $codesMouvements);

            // Détecter le type d'institution de l'utilisateur connecté
            $userInstitutionType = Auth::user()->affectationActive()->institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins ?? '';
            $isFormationSanitaire = $userInstitutionType === 'TCINS_0003';
            $isTribunal = $userInstitutionType === 'TCINS_0002';
            $isCentre = $userInstitutionType === 'TCINS_0001';

            $userCodeInstitution = Auth::user()->affectationActive()->code_institution ?? '';
            $codeInstitutionEmetteur = $declaration->code_institution;
            $codeInstitutionDestinataire = $declaration->code_institution_destinataire;
            $utilisateurEstEmetteur = $userCodeInstitution === $codeInstitutionEmetteur;
            $utilisateurEstDestinataire = filled($codeInstitutionDestinataire)
            && $userCodeInstitution === $codeInstitutionDestinataire;

            // Message : « Dossier reçu de… » uniquement côté destinataire, pas côté émetteur (ex. centre d'hygiène).
            $messageStatut = '';
            if ($dejaEnvoye) {
            if ($isFormationSanitaire && $utilisateurEstEmetteur) {
            $messageStatut = ucfirst($article) . ' ' . strtolower($typeDeclaration) . ' a été ' . ($typeDeclaration == "CERTIFICAT DE CONSTATATION DE DECES" ? " envoyé" : " envoyée") . ' au centre d\'état civil';

            } elseif ($isTribunal) {
            $messageStatut = 'Dossier reçu de ' . ($declaration->institution->lib_institution ?? 'Centre d\'état civil');
            } elseif ($isCentre && $utilisateurEstDestinataire && ! $utilisateurEstEmetteur) {
            $messageStatut = 'Dossier reçu de : ' . ($declaration->institution->lib_institution ?? 'Centre émetteur');
            } elseif ($isCentre && $utilisateurEstEmetteur && ! $utilisateurEstDestinataire) {
            $messageStatut = ucfirst($article) . ' ' . strtolower($typeDeclaration) . ' a été ' . ($typeDeclaration == "CERTIFICAT DE CONSTATATION DE DECES" ? " envoyé" : " envoyée") . ' au centre d\'état civil';
            } elseif ($isCentre && $utilisateurEstEmetteur && $utilisateurEstDestinataire) {
            $messageStatut = ucfirst($article) . ' ' . strtolower($typeDeclaration) . ' enregistrée sur votre institution';
            }
            }

            if (isset($declaration->mouvements) && $declaration->mouvements && $declaration->mouvements->count()) {
            $dernierMouvement = $declaration->mouvements->sortByDesc('created_at')->first();

            // Si le dernier mouvement est un renvoi, on permet l'envoi et la modification
            if ($dernierMouvement->code_mouvement == 'MOUV_0004') {
            $peutEnvoyer = true;
            $peutModifier = true;
            }
            // Sinon, on vérifie si la déclaration a déjà été envoyée
            else if (in_array('MOUV_0002', $codesMouvements) || in_array('MOUV_2006', $codesMouvements)) {
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
            <button class="btn btn-primary btn-piece" data-type="declarant" data-nom="{{ $declaration->declarant->nom ?? '' }}" data-url="{{ route('declarationDeces.piece.store', [$declaration->code_declaration_deces, 'type' => 'declarant']) }}" data-piece="{{ $declaration->piece_declarant ?? '' }}" data-piece-url="{{ $declaration->piece_declarant ? asset($declaration->piece_declarant) : '' }}">
                <i class="fa fa-id-card"></i> Ajouter/Modifier pièce Déclarant
            </button>
            <button class="btn btn-primary btn-piece" data-type="pere" data-nom="{{ $declaration->pere->nom ?? '' }}" data-url="{{ route('declarationDeces.piece.store', [$declaration->code_declaration_deces, 'type' => 'pere']) }}" data-piece="{{ $declaration->piece_pere ?? '' }}" data-piece-url="{{ $declaration->piece_pere ? asset($declaration->piece_pere) : '' }}">
                <i class="fa fa-id-card"></i> Ajouter/Modifier pièce Père
            </button>
            <button class="btn btn-primary btn-piece" data-type="mere" data-nom="{{ $declaration->mere->nom ?? '' }}" data-url="{{ route('declarationDeces.piece.store', [$declaration->code_declaration_deces, 'type' => 'mere']) }}" data-piece="{{ $declaration->piece_mere ?? '' }}" data-piece-url="{{ $declaration->piece_mere ? asset($declaration->piece_mere) : '' }}">
                <i class="fa fa-id-card"></i> Ajouter/Modifier pièce Mère
            </button>
            @if($declaration->conjoint)
            <button class="btn btn-primary btn-piece" data-type="conjoint" data-nom="{{ $declaration->conjoint->nom ?? '' }}" data-url="{{ route('declarationDeces.piece.store', [$declaration->code_declaration_deces, 'type' => 'conjoint']) }}" data-piece="{{ $declaration->piece_conjoint ?? '' }}" data-piece-url="{{ $declaration->piece_conjoint ? asset($declaration->piece_conjoint) : '' }}">
                <i class="fa fa-id-card"></i> Ajouter/Modifier pièce Conjoint(e)
            </button>
            @endif
            @endif
            @php $phaseSignatureEnvoi = $declaration->phaseSignatureOrigine(); @endphp
            <button class="btn btn-warning btn-envoyer-centre{{ $peutEnvoyer ? '' : ' disabled' }}" id="btn-envoyer-centre" title="Envoyer {{ $article }} {{ $typeDeclaration }} au centre d'état civil" data-code="{{ $declaration->code_declaration_deces }}" data-phase="{{ $phaseSignatureEnvoi ?? '' }}" data-signe="{{ ($phaseSignatureEnvoi && $declaration->estSigneePhase($phaseSignatureEnvoi)) ? '1' : '0' }}" data-piece-declarant="{{ $declaration->piece_declarant }}" data-piece-defunt="{{ $declaration->piece_defunt }}" data-piece-pere="{{ $declaration->piece_pere }}" data-piece-mere="{{ $declaration->piece_mere }}" data-piece-conjoint="{{ $declaration->piece_conjoint }}" data-statut-pere="{{ optional($declaration->pere)->statut_personne ?? 'VIVANT' }}" data-statut-mere="{{ optional($declaration->mere)->statut_personne ?? 'VIVANT' }}" data-statut-conjoint="{{ optional($declaration->conjoint)->statut_personne ?? 'VIVANT' }}" data-identiteDeclarant="{{ $declaration->declarant ? $declaration->declarant->nomcomplet() : '' }}" data-identiteDefunt="{{ $declaration->defunt ? $declaration->defunt->nomcomplet() : '' }}" data-identitePere="{{ $declaration->pere ? $declaration->pere->nomcomplet() : '' }}" data-identiteMere="{{ $declaration->mere ? $declaration->mere->nomcomplet() : '' }}" data-identiteConjoint="{{ $declaration->conjoint ? $declaration->conjoint->nomcomplet() : '' }}">
                <i class="fa fa-paper-plane"></i>

                @if ($dejaEnvoye)
                @if ($isFormationSanitaire && $utilisateurEstEmetteur)
                {{ ucfirst($article) . ' ' . strtolower($typeDeclaration) . ' a été ' . ($typeDeclaration == "CERTIFICAT DE CONSTATATION DE DECES" ? "envoyé" : "envoyée") . ' au centre d\'état civil' }}

                @endif
                @if ($isTribunal)
                {{ 'Dossier reçu de ' . ($declaration->institution->lib_institution ?? 'Centre d\'état civil') }}
                @endif
                @if ($isCentre && $utilisateurEstDestinataire && ! $utilisateurEstEmetteur)
                Dossier reçu
                @endif
                @if ($isCentre && $utilisateurEstEmetteur && ! $utilisateurEstDestinataire)
                {{ ucfirst($article) . ' ' . strtolower($typeDeclaration) . ' a été ' . ($typeDeclaration == "CERTIFICAT DE CONSTATATION DE DECES" ? "envoyé" : "envoyée") . ' au centre d\'état civil' }}
                @endif
                @if ($isCentre && $utilisateurEstEmetteur && $utilisateurEstDestinataire)
                {{ ucfirst($article) . ' ' . strtolower($typeDeclaration) . ' enregistrée' }}
                @endif
                @else
                Envoyer {{ $article }} {{ $typeDeclaration }} au centre d'état civil
                @endif

            </button>
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Détails de la déclaration
                    <span class="badge bg-primary ms-2">{{ $declaration->type_declaration }}</span>
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
                            <th style="width:40%"><i class="fa fa-hashtag text-primary me-1"></i> Numéro déclaration</th>
                            <td>{{ $declaration->numero_declaration }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-user text-primary me-1"></i> Défunt</th>
                            <td>{{ $declaration->defunt ? $declaration->defunt->nomcomplet() : '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-user text-primary me-1"></i> Date de décès</th>
                            <td>{{ isset($declaration->date_heure_deces) ? date('d/m/Y H:i', strtotime($declaration->date_heure_deces)) : '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-map-marker-alt text-primary me-1"></i> Lieu de décès</th>
                            <td>{{ $declaration->lieu_deces ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-user-tie text-primary me-1"></i> Déclarant</th>
                            <td>{{ $declaration->declarant ? $declaration->declarant->nomcomplet() : '-' }}</td>
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
                        @if($declaration->conjoint)
                        <tr>
                            <th><i class="fa fa-user-friends text-primary me-1"></i> Conjoint(e)</th>
                            <td>{{ $declaration->conjoint ? $declaration->conjoint->nomcomplet() : '-' }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th><i class="fa fa-university text-primary me-1"></i> Centre d'état civil</th>
                            <td>{{ $declaration->institution ? $declaration->institution->lib_institution : '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-calendar-check text-primary me-1"></i> Date de création</th>
                            <td>{{ $declaration->created_at ? $declaration->created_at->format('d/m/Y H:i') : '-' }}</td>
                        </tr>
                        <!-- Pièces jointes -->
                        <tr>
                            <th><i class="fa fa-id-card text-primary me-1"></i> Pièce d'identité du déclarant</th>
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
                            <th><i class="fa fa-id-card text-primary me-1"></i> Pièce d'identité du défunt</th>
                            <td>
                                @if($declaration->piece_defunt)
                                <span class="badge bg-success">Présente</span>
                                <a href="{{ asset($declaration->piece_defunt) }}" target="_blank" class="btn btn-warning btn-xs ms-2"><i class="fa fa-eye"></i> Voir</a>
                                @else
                                <span class="text-muted">Optionnelle (non jointe)</span>
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
                        @if($declaration->conjoint)
                        <tr>
                            <th><i class="fa fa-id-card text-primary me-1"></i> Pièce d'identité du conjoint(e)</th>
                            <td>
                                @if($declaration->piece_conjoint)
                                <span class="badge bg-success">Présente</span>
                                <a href="{{ asset($declaration->piece_conjoint) }}" target="_blank" class="btn btn-warning btn-xs ms-2"><i class="fa fa-eye"></i> Voir</a>
                                @else
                                <span class="text-muted">{{ ($declaration->conjoint->statut_personne ?? 'VIVANT') === 'DECEDE' ? 'Optionnelle (non jointe)' : 'Non jointe' }}</span>
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
                            <span class="badge bg-warning">{{ $mvt->statut }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('declarationDeces.index') }}" class="btn btn-info float-end"> <i class="fa fa-list"></i> Retour à la liste</a>

                    @if(Auth::user()->affectationActive()->institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins == "TCINS_0001")
                    <a href="{{ route('acteDeces.index') }}" class="btn btn-warning float-end"> <i class="fa fa-edit"></i> Gestion des actes</a>
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
            <input type="hidden" name="code_declaration_deces" id="input-code-declaration">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Envoyer le dossier au centre d'état civil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        Cette action va transmettre le dossier au centre d'état civil pour la transcription de l'acte de décès.<br>
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
                                        <td id="declarant-status"><span class="badge bg-warning">Manquante</span></td>
                                    </tr>
                                    <tr id="piece-defunt">
                                        <td><strong>Défunt</strong></td>
                                        <td id="defunt-nom">-</td>
                                        <td id="defunt-piece">-</td>
                                        <td id="defunt-status"><span class="badge bg-warning">Manquante</span></td>
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
                                    @if($declaration->conjoint)
                                    <tr id="piece-conjoint">
                                        <td><strong>Conjoint(e)</strong></td>
                                        <td id="conjoint-nom">-</td>
                                        <td id="conjoint-piece">-</td>
                                        <td id="conjoint-status"><span class="badge bg-warning">Manquante</span></td>
                                    </tr>
                                    @endif
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
                            <span id="texte-signature-requise">La signature électronique est requise avant l'envoi.</span>
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
                    <button type="submit" class="btn btn-warning" id="btn-envoyer-final">Envoyer</button>
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
<script src="{{ asset('js/sifec-p12-sign.js') }}?v=20260720a"></script>
<script>
    window.SIFEC_CERT_SIGN_OBLIGATOIRE = {{ \App\Models\GuotSignelecConfig::certificatSignatureObligatoire() ? 'true' : 'false' }};
    window.SIFEC_PEUT_SIGNER_CERTIFICAT = {{ (
        Gate::allows('module.certificat.deces.signature')
        || Gate::allows('module.certificat.signature')
    ) ? 'true' : 'false' }};
    window.SIFEC_ROUTES_DECES = {
        mouvement: @json(route('declarationDeces.mouvement')),
        signPrepare: @json(route('declarationDeces.sign.prepare')),
        signFinalize: @json(route('declarationDeces.sign.finalize')),
        csrf: @json(csrf_token())
    };
</script>
<script>
    $(function() {
        // Gestion modale pièce d'identité
        let urlPiece = '';
        let typePiece = '';
        $('.btn-piece').on('click', function() {
            typePiece = $(this).data('type');
            urlPiece = $(this).data('url');
            let nom = $(this).data('nom');
            let piece = $(this).data('piece');
            let pieceUrl = $(this).data('piece-url') || '';
            $('#piece-type-label').text(nom ? '(' + nom + ')' : '');
            if (piece && pieceUrl) {
                $('#piece-exist').show();
                let ext = (piece.split('.').pop() || '').toLowerCase();
                let previewHtml = '';
                if (['jpg', 'jpeg', 'png'].includes(ext)) {
                    previewHtml = `<img src="${pieceUrl}" alt="Aperçu" class="img-fluid" style="max-height:220px; object-fit:contain;">`;
                } else if (ext === 'pdf') {
                    previewHtml = `<iframe src="${pieceUrl}" type="application/pdf" width="100%" height="220" class="border-0"></iframe>`;
                } else {
                    previewHtml = `<a href="${pieceUrl}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fa fa-external-link"></i> Ouvrir la pièce</a>`;
                }
                $('#piece-preview').html(previewHtml);
            } else {
                $('#piece-exist').hide();
                $('#piece-preview').html('');
            }
            $('#piece-file').val('');
            $('#modal-piece').modal('show');
        });
        // Soumission AJAX pièce (même comportement que declarationNaissance/show : chargement sur le bouton Enregistrer)
        $('#form-piece').on('submit', function(e) {
            e.preventDefault();
            var $btn = $(this).find('button[type="submit"]');
            sifecBtnLoading($btn[0], "Enregistrement...");
            let formData = new FormData(this);
            $.ajax({
                url: urlPiece
                , type: 'POST'
                , data: formData
                , processData: false
                , contentType: false
                , success: function(resp) {
                    sifecBtnReset($btn[0], "Enregistrer");
                    if (resp.code == "200") {
                        flashAlert("Réponse", "success", resp.message);
                        $('#modal-piece').modal('hide');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        flashAlert("Réponse", "error", resp.message);
                    }
                }
                , error: function(xhr) {
                    sifecBtnReset($btn[0], "Enregistrer");
                    flashAlert("Erreur", "error", xhr.responseJSON?.message || 'Erreur lors de l\'upload');
                }
            });
        });

        // Gestion modale envoi au centre d'état civil
        let codeDeclarationEnvoi = null;
        let phaseEnvoi = '';
        let dejaSigneEnvoi = false;

        function signatureEnvoiRequise() {
            return window.SIFEC_CERT_SIGN_OBLIGATOIRE === true
                && window.SIFEC_PEUT_SIGNER_CERTIFICAT === true
                && phaseEnvoi
                && !dejaSigneEnvoi;
        }
        function showCertSignError(msg) {
            $('#cert-sign-feedback').removeClass('d-none').text(msg);
            flashAlert('Échec', 'error', msg);
        }
        function envoyerDirect($btn, observation) {
            $.ajax({
                url: window.SIFEC_ROUTES_DECES.mouvement,
                type: 'POST',
                data: {
                    code_declaration_deces: codeDeclarationEnvoi,
                    observation: observation,
                    _token: window.SIFEC_ROUTES_DECES.csrf
                },
                success: function(resp) {
                    sifecBtnReset($btn[0], 'Envoyer');
                    if (String(resp.code) === '200') {
                        flashAlert('Réponse', 'success', resp.message);
                        $('#modal-envoyer-centre').modal('hide');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        flashAlert('Réponse', 'error', resp.message);
                    }
                },
                error: function(xhr) {
                    sifecBtnReset($btn[0], 'Envoyer');
                    flashAlert('Erreur', 'error', xhr.responseJSON?.message || 'Erreur lors de l\'envoi');
                }
            });
        }
        async function signerPuisEnvoyer($btn, observation) {
            var fileInput = document.getElementById('cert_p12_file');
            var pin = $('#cert_p12_pin').val();
            $('#cert-sign-feedback').addClass('d-none').empty();
            try {
                var prep = await $.ajax({
                    url: window.SIFEC_ROUTES_DECES.signPrepare,
                    type: 'POST',
                    data: { phase: phaseEnvoi, codes: [codeDeclarationEnvoi], observation: observation, _token: window.SIFEC_ROUTES_DECES.csrf }
                });
                if (String(prep.code) === '200' && prep.completed) {
                    sifecBtnReset($btn[0], 'Envoyer');
                    flashAlert('Succès', 'success', prep.message);
                    $('#modal-envoyer-centre').modal('hide');
                    setTimeout(() => location.reload(), 1000);
                    return;
                }
                if (String(prep.code) !== '200' || !prep.token || !prep.items?.length) {
                    sifecBtnReset($btn[0], 'Envoyer');
                    showCertSignError(prep?.message || 'Échec préparation.');
                    return;
                }
                var needSign = prep.items.some(function(it){ return !it.already_signed; });
                if (needSign) {
                    if (!fileInput?.files?.[0]) { sifecBtnReset($btn[0], 'Envoyer'); showCertSignError('Sélectionnez votre fichier certificat (.p12).'); return; }
                    if (!pin?.trim()) { sifecBtnReset($btn[0], 'Envoyer'); showCertSignError('Saisissez la passphrase.'); return; }
                    if (typeof window.SifecP12Sign === 'undefined') { sifecBtnReset($btn[0], 'Envoyer'); showCertSignError('Bibliothèque de signature non chargée.'); return; }
                }
                $btn.html('<i class="fas fa-spinner fa-spin"></i> Signature…');
                var p12Binary = needSign ? await window.SifecP12Sign.readP12File(fileInput.files[0]) : null;
                var signatures = [];
                for (var i = 0; i < prep.items.length; i++) {
                    var item = prep.items[i];
                    if (item.already_signed) {
                        signatures.push({ code_declaration: item.code_declaration, signature_hex: 'RESUME' });
                    } else {
                        signatures.push({
                            code_declaration: item.code_declaration,
                            signature_hex: await window.SifecP12Sign.signHashHex(p12Binary, pin, item.document_hash, prep.expected_serial || null)
                        });
                    }
                }
                $btn.html('<i class="fas fa-spinner fa-spin"></i> Envoi…');
                var fin = await $.ajax({
                    url: window.SIFEC_ROUTES_DECES.signFinalize,
                    type: 'POST',
                    data: { phase: phaseEnvoi, token: prep.token, signatures: signatures, observation: observation, _token: window.SIFEC_ROUTES_DECES.csrf }
                });
                sifecBtnReset($btn[0], 'Envoyer');
                if (String(fin.code) === '200') {
                    flashAlert('Succès', 'success', fin.message);
                    $('#modal-envoyer-centre').modal('hide');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showCertSignError(fin.message || 'Échec signature.');
                }
            } catch (err) {
                sifecBtnReset($btn[0], 'Envoyer');
                showCertSignError(err.message || 'Erreur signature électronique');
            }
        }

        $(document).on('click', '.btn-envoyer-centre', function() {
            if ($(this).hasClass('disabled')) {
                toastr.warning('Cette déclaration a déjà été envoyée au centre d\'état civil.');
                return;
            }
            codeDeclarationEnvoi = $(this).data('code');
            phaseEnvoi = String($(this).data('phase') || '');
            dejaSigneEnvoi = String($(this).data('signe')) === '1';
            $('#cert-sign-feedback').addClass('d-none').empty();
            $('#cert_p12_file').val('');
            $('#cert_p12_pin').val('');
            if (signatureEnvoiRequise()) {
                $('#bloc-signature-certificat').removeClass('d-none');
                $('#texte-signature-requise').text(phaseEnvoi === 'ch'
                    ? 'La signature électronique du certificat de constatation est requise avant l\'envoi.'
                    : 'La signature électronique du certificat de décès par le chef de service est requise avant l\'envoi.');
            } else {
                $('#bloc-signature-certificat').addClass('d-none');
            }
            // Remplir le tableau des pièces dans le modal
            const declarantNom = $(this).attr('data-identiteDeclarant');
            const defuntNom = $(this).attr('data-identiteDefunt');
            const pereNom = $(this).attr('data-identitePere');
            const mereNom = $(this).attr('data-identiteMere');
            const conjointNom = $(this).attr('data-identiteConjoint');
            const pieceDeclarant = $(this).data('piece-declarant') || '';
            const pieceDefunt = $(this).data('piece-defunt') || '';
            const piecePere = $(this).data('piece-pere') || '';
            const pieceMere = $(this).data('piece-mere') || '';
            const pieceConjoint = $(this).data('piece-conjoint') || '';
            const statutPere = $(this).data('statut-pere') || 'VIVANT';
            const statutMere = $(this).data('statut-mere') || 'VIVANT';
            const statutConjoint = $(this).data('statut-conjoint') || 'VIVANT';

            // Déclarant : pièce toujours obligatoire. Défunt : non requise. Père/Mère/Conjoint : requises seulement si vivants
            $('#declarant-nom').text(declarantNom);
            $('#declarant-piece').html(pieceDeclarant ? `<a href="/${pieceDeclarant}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
            $('#declarant-status').html(pieceDeclarant ? '<span class="badge bg-success">Présente</span>' : '<span class="badge bg-warning">Manquante</span>');

            $('#defunt-nom').text(defuntNom);
            $('#defunt-piece').html(pieceDefunt ? `<a href="/${pieceDefunt}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
            $('#defunt-status').html(pieceDefunt ? '<span class="badge bg-success">Présente</span>' : '<span class="badge bg-secondary">Optionnelle</span>');

            $('#pere-nom').text(pereNom);
            $('#pere-piece').html(piecePere ? `<a href="/${piecePere}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
            $('#pere-status').html(statutPere === 'DECEDE' ?
                (piecePere ? '<span class="badge bg-success">Présente</span>' : '<span class="badge bg-secondary">Optionnelle</span>') :
                (piecePere ? '<span class="badge bg-success">Présente</span>' : '<span class="badge bg-warning">Manquante</span>'));

            $('#mere-nom').text(mereNom);
            $('#mere-piece').html(pieceMere ? `<a href="/${pieceMere}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
            $('#mere-status').html(statutMere === 'DECEDE' ?
                (pieceMere ? '<span class="badge bg-success">Présente</span>' : '<span class="badge bg-secondary">Optionnelle</span>') :
                (pieceMere ? '<span class="badge bg-success">Présente</span>' : '<span class="badge bg-warning">Manquante</span>'));

            $('#conjoint-nom').text(conjointNom);
            if (!conjointNom) {
                $('#conjoint-piece').text('-');
                $('#conjoint-status').html('<span class="badge bg-secondary">Optionnelle</span>');
            } else {
                $('#conjoint-piece').html(pieceConjoint ? `<a href="/${pieceConjoint}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
                $('#conjoint-status').html(statutConjoint === 'DECEDE' ?
                    (pieceConjoint ? '<span class="badge bg-success">Présente</span>' : '<span class="badge bg-secondary">Optionnelle</span>') :
                    (pieceConjoint ? '<span class="badge bg-success">Présente</span>' : '<span class="badge bg-warning">Manquante</span>'));
            }

            // Pièce déclarant toujours obligatoire ; père et mère seulement s'ils sont vivants (défunt jamais requis)
            let piecesManquantes = false;
            if (!pieceDeclarant || (statutPere === 'VIVANT' && !piecePere) || (statutMere === 'VIVANT' && !pieceMere)) {
                piecesManquantes = true;
            }

            if (piecesManquantes) {
                $('#alert-pieces-manquantes').removeClass('d-none');
            } else {
                $('#alert-pieces-manquantes').addClass('d-none');
            }
            $('#btn-envoyer-final').prop('disabled', piecesManquantes);
            $('#input-code-declaration').val(codeDeclarationEnvoi);
            $('#modal-envoyer-centre').modal('show');
        });

        $('#form-envoyer-centre').on('submit', function(e) {
            e.preventDefault();
            var $btn = $('#btn-envoyer-final');
            var observation = $('#observation-centre').val();
            sifecBtnLoading($btn[0], 'Traitement…');
            if (signatureEnvoiRequise()) {
                signerPuisEnvoyer($btn, observation);
            } else {
                envoyerDirect($btn, observation);
            }
        });
    });

</script>
@endsection
