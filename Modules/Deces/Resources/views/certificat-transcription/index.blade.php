@extends('layout.app')
@section('titre')
Certificat de transcription
@endsection
@section("styles")

<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
    <style>
        a{
            color: green!important;
        }
    </style>

@endsection

@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Liste des certificats de transcription des actes de décès</h4>
                <a href="{{ route("certificatTranscriptionDeces.create") }}"><button type="button" class="btn btn-info m-t-2 float-end text-white" >Créer certificat  <i class="fa fa-plus-circle"></i></button></a>
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
                                @php
                                    $i=1;
                                @endphp
                                @foreach ($certificats as $certificat)
                                @php
                                    $dernierMouvement = $certificat->mouvements->sortByDesc('created_at')->first();
                                    $badgeClass = 'badge-secondary';
                                    $statutLabel = $dernierMouvement && isset($dernierMouvement->lib_mouvement) ? $dernierMouvement->lib_mouvement : 'En cours';

                                    // Déterminer si le certificat a déjà été envoyé au tribunal
                                    $codesMouvements = $certificat->mouvements->pluck('code_mouvement')->toArray();
                                    $dejaEnvoyeTribunal = in_array('MOUV_0006', $codesMouvements);
                                    $dejaTraiteTribunal = in_array('MOUV_0009', $codesMouvements) ||
                                                         in_array('MOUV_0010', $codesMouvements) ||
                                                         in_array('MOUV_0011', $codesMouvements);

                                    // Vérifier si le dernier mouvement est un renvoi (MOUV_0004)
                                    $dernierMouvementRenvoye = $dernierMouvement && $dernierMouvement->code_mouvement == 'MOUV_0004';

                                    // On peut envoyer si jamais envoyé OU si renvoyé (et pas encore traité)
                                    $peutEnvoyer = (!$dejaEnvoyeTribunal && !$dejaTraiteTribunal) || ($dernierMouvementRenvoye && !$dejaTraiteTribunal);

                                    if ($dernierMouvement) {
                                        switch ($dernierMouvement->code_mouvement) {
                                            case 'MOUV_0006': // Certificat envoyé au tribunal
                                                $badgeClass = 'badge-info';
                                                $statutLabel = $dernierMouvement->lib_mouvement;
                                                break;
                                            case 'MOUV_0004': // Document renvoyé au centre
                                                $badgeClass = 'badge-warning';
                                                $statutLabel = $dernierMouvement->lib_mouvement;
                                                break;
                                            case 'MOUV_0009': // Réquisition envoyée au centre
                                            case 'MOUV_0010': // Jugement envoyé au centre
                                            case 'MOUV_0011': // Document transmis au centre
                                                $badgeClass = 'badge-success';
                                                $statutLabel = $dernierMouvement->lib_mouvement;
                                                break;
                                            case 'MOUV_0026': // Certificat enregistré
                                                $badgeClass = 'badge-success';
                                                $statutLabel = $dernierMouvement->lib_mouvement;
                                                break;
                                            default:
                                                $badgeClass = 'badge-secondary';
                                                $statutLabel = $dernierMouvement->lib_mouvement ?? 'En cours';
                                        }
                                    }
                                @endphp
                                <tr width="100%">
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $certificat->defunt->nomcomplet() }}</td>
                                    <td>{{ $certificat->defunt->sexe == "M" ? "Masculin" : "Féminin" }}</td>
                                    <td>{{ date("d-m-Y", strtotime($certificat->defunt->date_heure_deces)) }}</td>
                                    <td>{{ $certificat->lieu_deces }}</td>
                                    <td>{{ $certificat->declarant->nomcomplet() }}</td>
                                    <td>
                                        <span class="badge {{ $badgeClass }}" style="font-size: 13px;font-weight:600;">{{ $statutLabel }}</span>
                                        @if($dernierMouvement && $dernierMouvement->observation)
                                            <br><small>Observation : {{ $dernierMouvement->observation }}</small>
                                        @endif
                                        @if($dejaTraiteTribunal)
                                            <br><small class="text-success"><i class="fas fa-gavel me-1"></i>Prêt pour la transcription de l'acte</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-xs">
                                            <a href="{{ route('certificatNonInscriptionDeces.show', $certificat->code_declaration_deces) }}"
                                                class="btn btn-primary shadow btn-xs sharp me-1"
                                                title="Voir détail">
                                                <i class="fas fa-eye"></i>
                                             </a>
                                            <a href="{{ route('certificatNonInscriptionDeces.displayCertificat',$certificat->code_declaration_deces) }}" target="_blank" class="btn btn-warning shadow btn-xs sharp me-1" title="Voir document"><i class="fas fa-print"></i></a>
                                            @if($dernierMouvement && in_array($dernierMouvement->code_mouvement, ['MOUV_0026', 'MOUV_0004']))
                                                <a href="{{ route('declarationDeces.edit',$certificat->code_declaration_deces) }}" class="btn btn-info shadow btn-xs sharp me-1" title="Modifier"><i class="fas fa-pencil-alt"></i></a>
                                                <form action="{{ route('declarationDeces.destroy',$certificat->code_declaration_deces) }}" method="POST" style="display: inline-block">
                                                @csrf
                                                @method('DELETE')
                                                    <button type="submit" class="btn btn-danger shadow btn-xs sharp" title="Supprimer"><i class="fa fa-trash"></i></button>
                                            </form>
                                            @endif
                                            @if($peutEnvoyer)
                                                <button class="btn btn-primary btn-envoyer-tribunal shadow btn-xs sharp"
                                                    data-code="{{ $certificat->code_declaration_deces }}"
                                                    data-piece-defunt="{{ $certificat->piece_defunt }}"
                                                    data-piece-declarant="{{ $certificat->piece_declarant }}"
                                                    data-piece-pere="{{ $certificat->piece_pere }}"
                                                    data-piece-mere="{{ $certificat->piece_mere }}"
                                                    data-statut-pere="{{ optional($certificat->pere)->statut_personne ?? 'VIVANT' }}"
                                                    data-statut-mere="{{ optional($certificat->mere)->statut_personne ?? 'VIVANT' }}"
                                                    data-identiteDeclarant="{{ $certificat->declarant->nomcomplet() }}"
                                                    data-identiteDefunt="{{ $certificat->defunt->nomcomplet() }}"
                                                    data-identitePere="{{ $certificat->pere ? $certificat->pere->nomcomplet() : '' }}"
                                                    data-identiteMere="{{ $certificat->mere ? $certificat->mere->nomcomplet() : '' }}"
                                                    title="{{ $dernierMouvementRenvoye ? 'Réenvoyer au tribunal' : 'Envoyer au tribunal' }}">
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                            @elseif($dejaTraiteTribunal)

                                                 {{-- Télécharger le document importé (si déjà importé) --}}
                                                    @if($certificat->requisition != null)
                                                    <a href="{{ route('tribunal.voir_document', ['type' => 'deces', 'id' => $certificat->code_declaration_deces]) }}"
                                                        class="btn btn-info btn-xs text-start me-1" title="Télécharger le document importé">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @endif
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
                                    <th>Date</th>
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

{{-- Modal Envoi au tribunal (hors boucle) --}}
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
</div>
</div>
</div>
@endsection
@section("scripts")
<!-- Datatable -->
<script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

<!-- Moment.js pour les calculs de dates -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script>

$("#noninscript").hide();
$("#inscript").hide();

let jours_deces = 0;  // Déclaration globale de jours_deces

$(function() {
    $('#btn-submit').prop('disabled', true);

    $('#date_deces').on('change', function() {
        const dateDeces = $(this).val();
        if (!dateDeces) {
            $('#texte').html('<div class="alert alert-warning"><strong>⚠️ Attention :</strong> Veuillez choisir une date de décès.</div>');
            $('#btn-submit').prop('disabled', true);
            return;
        }
        const today = moment();
        const deces = moment(dateDeces);
        jours_deces = today.diff(deces, 'days');  // Mise à jour de la variable globale


        let message = '';
        let lien = '';
        let type = '';
        if (jours_deces <= 1) {
            message = '<div class="alert alert-info"><strong>ℹ️ Information :</strong> Nombre de jours depuis le décès : <b>' + jours_deces + ' jour(s)</b>.</div>';
            lien = '<div class="alert alert-success"><strong>✅ Bonne nouvelle :</strong> Vous pouvez créer une <b>déclaration de décès classique</b>.</div>';
            type = 'declaration';
        }
        else if (jours_deces > 1 && jours_deces <= 15) {
            message = '<div class="alert alert-warning"><strong>⚠️ Attention :</strong> Nombre de jours depuis le décès : <b>' + jours_deces + ' jours</b>.<br>\
            <span style="color:#b94a48;">Délai de déclaration dépassé mais inférieur à 15 jours.</span></div>';
            lien = '<div class="alert alert-warning"><strong>⚠️ Action requise :</strong> Vous devez créer un <b>certificat de transcription de décès</b>.<br>Une réquisition est requise conformément à l\'article 60 du code de la famille.</div>';
            type = 'certificat';
        }
        else if (jours_deces > 15) {
            message = '<div class="alert alert-danger"><strong>⏰ Délai largement dépassé !</strong> Nombre de jours depuis le décès : <b>' + jours_deces + ' jours</b>.<br>\
            <span style="color:#b94a48;">Le délai légal de déclaration de décès est largement dépassé.</span></div>';
            lien = '<div class="alert alert-warning"><strong>⚠️ Action requise :</strong> Vous devez créer un <b>certificat de transcription de décès</b>.<br>Une réquisition ou un jugement est requis conformément à l\'article 60 du code de la famille.</div>';
            type = 'certificat';
        }
        $('#texte').html(message);
        $('.validate').html(lien);
        $('#btn-submit').prop('disabled', false);
        $('#formdata').data('type', type);
    });

    $('#formdata').on('submit', function(e) {
        e.preventDefault();
        const type = $(this).data('type');
        const dateDeces = $('#date_deces').val();
        if (!dateDeces) {
            $('#texte').html('<div class="alert alert-warning"><strong>⚠️ Attention :</strong> Veuillez choisir une date de décès.</div>');
            return;
        }
        $('#btn-submit').prop('disabled', true).text('Redirection...');
        let url = '';
        if (jours_deces <= 1) {
            url = "{{ route('certificatNonInscriptionDeces.create') }}";
        } else {
            // Pour les cas > 1 jour, on redirige toujours vers le certificat de transcription
            url = "{{ route('certificatNonInscriptionDeces.create') }}";
        }
        // Redirection GET avec la date en query string
        window.location.href = url + '?date_deces=' + encodeURIComponent(dateDeces);
    });

    let codeTribunal = null;
    $('.btn-envoyer-tribunal').on('click', function(){
        codeTribunal = $(this).data('code');
        $('#input-code-tribunal').val(codeTribunal);

        // Récupération des infos de la ligne sélectionnée
        // On suppose que les attributs data-* sont ajoutés sur le bouton (à faire côté Blade)
        const defuntNom = $(this).attr('data-identiteDefunt');
        const declarantNom = $(this).attr('data-identiteDeclarant');
        const pereNom = $(this).attr('data-identitePere');
        const mereNom = $(this).attr('data-identiteMere');
        // Pour les pièces, il faut ajouter des data-piece-* sur le bouton côté Blade si possible
        const pieceDefunt = $(this).data('piece-defunt') || '';
        const pieceDeclarant = $(this).data('piece-declarant') || '';
        const piecePere = $(this).data('piece-pere') || '';
        const pieceMere = $(this).data('piece-mere') || '';
        const statutPere = $(this).data('statut-pere') || 'VIVANT';
        const statutMere = $(this).data('statut-mere') || 'VIVANT';

        $('#defunt-nom-tribunal').text(defuntNom);
        $('#defunt-piece-tribunal').html(pieceDefunt ? `<a href="/${pieceDefunt}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
        $('#defunt-status-tribunal').html(pieceDefunt ? '<span class="badge badge-success">Présente</span>' : '<span class="badge bg-secondary">Optionnelle</span>');

        $('#declarant-nom-tribunal').text(declarantNom);
        $('#declarant-piece-tribunal').html(pieceDeclarant ? `<a href="/${pieceDeclarant}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
        $('#declarant-status-tribunal').html(pieceDeclarant ? '<span class="badge badge-success">Présente</span>' : '<span class="badge badge-warning">Manquante</span>');

        $('#pere-nom-tribunal').text(pereNom);
        $('#pere-piece-tribunal').html(piecePere ? `<a href="/${piecePere}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
        $('#pere-status-tribunal').html(statutPere === 'DECEDE'
            ? (piecePere ? '<span class="badge badge-success">Présente</span>' : '<span class="badge bg-secondary">Optionnelle</span>')
            : (piecePere ? '<span class="badge badge-success">Présente</span>' : '<span class="badge badge-warning">Manquante</span>'));
        $('#mere-nom-tribunal').text(mereNom);
        $('#mere-piece-tribunal').html(pieceMere ? `<a href="/${pieceMere}" target="_blank" class="text-success fw-bold">Afficher la pièce</a>` : '-');
        $('#mere-status-tribunal').html(statutMere === 'DECEDE'
            ? (pieceMere ? '<span class="badge badge-success">Présente</span>' : '<span class="badge bg-secondary">Optionnelle</span>')
            : (pieceMere ? '<span class="badge badge-success">Présente</span>' : '<span class="badge badge-warning">Manquante</span>'));

        let piecesManquantes = false;
        if (!pieceDeclarant || (statutPere === 'VIVANT' && !piecePere) || (statutMere === 'VIVANT' && !pieceMere)) {
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
        var $btn = $('#btn-envoyer-tribunal-final');
        sifecBtnLoading($btn[0], "Envoi...");
        let url = "{{ route('certificatNonInscriptionDeces.mouvement') }}";
        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            success: function(resp){
                sifecBtnReset($btn[0], "Envoyer");
                if(resp.code == "200"){
                    flashAlert("Réponse","success",resp.message);
                    $('#modal-envoyer-tribunal').modal('hide');
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
    });
});
</script>
@endsection
