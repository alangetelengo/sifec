@extends('layout.app')
@section('titre')
Certificat de destruction d'acte de naissance
@endsection
@section("styles")

<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
@endsection
@section('sous-titre')
    Liste des déclarations de destruction d'acte de naissance
@endsection
@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Liste des certificats de destruction</h4>
                <a href="{{ route("certificatDestruction.create") }}"><button type="button" class="btn btn-info m-t-2 float-end text-white" >Créer certificat de destruction  <i class="fa fa-plus-circle"></i></button></a>
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
                                        <th>Enfant: Sexe</th>
                                        <th>Statut</th>
                                        <th>Type: Document</th>
                                        <th>Action</th>
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

                                        if ($dernierMouvement) {
                                            switch ($dernierMouvement->code_mouvement) {
                                                case 'MOUV_0006': // Certificat envoyé au tribunal
                                                    $badgeClass = 'badge-info';
                                                    $statutLabel = $dernierMouvement->lib_mouvement;
                                                    break;
                                                case 'MOUV_0008': // Document renvoyé au centre
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
                                        <td>{{ $certificat->declarant->nom.' '.$certificat->Declarant->prenom }}</td>
                                        <td>{{ $certificat->enfant->nom }}</td>
                                        <td>{{ $certificat->enfant->prenom }}</td>
                                        <td>{{ date("d-m-Y", strtotime($certificat->enfant->date_naissance)) }}</td>
                                        <td>{{ $certificat->enfant->sexe == "M" ? "Masculin" : "Féminin" }}</td>
                                        <td>
                                            <span class="badge {{ $badgeClass }}" style="font-size: 13px;font-weight:600;">{{ $statutLabel }}</span>
                                            @if($dernierMouvement && $dernierMouvement->observation)
                                                <br><small>Observation : {{ $dernierMouvement->observation }}</small>
                                            @endif
                                            @if($dejaTraiteTribunal)
                                                <br><small class="text-success"><i class="fas fa-gavel me-1"></i>Prêt pour transcription</small>
                                            @endif
                                        </td>
                                        <td>{{ $certificat->type_declaration  }}</td>
                                        <td>
                                            <div class="btn-group btn-group-xs">
                                                <a href="{{ route('certificatDestruction.show', $certificat->code_declaration_naissance) }}"
                                                    class="btn btn-primary shadow btn-xs sharp me-1"
                                                    title="Voir détail">
                                                    <i class="fas fa-eye"></i>
                                                 </a>
                                                <a href="{{ route('declarationNaissance.etat',$certificat->code_declaration_naissance) }}" target="_blank" class="btn btn-warning shadow btn-xs sharp me-1" title="Voir document"><i class="fas fa-print"></i></a>
                                                @if((!$dejaEnvoyeTribunal && !$dejaTraiteTribunal) || ($dernierMouvement && $dernierMouvement->code_mouvement == 'MOUV_0008'))
                                                    <button class="btn btn-primary btn-envoyer-tribunal shadow btn-xs sharp me-1"
                                                        data-code="{{ $certificat->code_declaration_naissance }}"
                                                        data-piece-declarant="{{ $certificat->piece_declarant }}"
                                                        data-piece-pere="{{ $certificat->piece_pere }}"
                                                        data-piece-mere="{{ $certificat->piece_mere }}"
                                                        data-statut-pere="{{ optional($certificat->pere)->statut_personne ?? 'VIVANT' }}"
                                                        data-statut-mere="{{ optional($certificat->mere)->statut_personne ?? 'VIVANT' }}"
                                                        data-identiteDeclarant="{{ $certificat->declarant->nomcomplet() }}"
                                                        data-identitePere="{{ $certificat->pere ? $certificat->pere->nomcomplet() : '' }}"
                                                        data-identiteMere="{{ $certificat->mere ? $certificat->mere->nomcomplet() : '' }}"
                                                        title="Envoyer au tribunal">
                                                        <i class="fas fa-paper-plane"></i>
                                                    </button>
                                                @endif
                                                @if($dernierMouvement && in_array($dernierMouvement->code_mouvement, ['MOUV_0026', 'MOUV_0008']))
                                                    <a href="{{ route('declarationNaissance.edit',$certificat->code_declaration_naissance) }}" class="btn btn-info shadow btn-xs sharp me-1" title="Modifier"><i class="fas fa-pencil-alt"></i></a>
                                                    <form action="{{ route('declarationNaissance.destroy',$certificat->code_declaration_naissance) }}" method="POST" style="display: inline-block">
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
                                        <th>Déclarant</th>
                                        <th>Enfant: Nom</th>
                                        <th>Enfant: Prénom</th>
                                        <th>Enfant: Date naissance</th>
                                        <th>Enfant: Sexe</th>
                                        <th>Statut</th>
                                        <th>Type: Document</th>
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

{{-- Modal Envoi au tribunal (hors boucle) --}}
<div class="modal fade" id="modal-envoyer-tribunal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form-envoyer-tribunal">
            @csrf
            <input type="hidden" name="code_declaration_naissance" id="input-code-tribunal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Envoyer le dossier au tribunal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        Cette action va transmettre le dossier au tribunal pour une demande d'une réquisition<br>
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
    <script>
        $(function(){
            let codeTribunal = null;
        $('.btn-envoyer-tribunal').on('click', function(){
            codeTribunal = $(this).data('code');
            $('#input-code-tribunal').val(codeTribunal);

            // Récupération des infos de la ligne sélectionnée
            // On suppose que les attributs data-* sont ajoutés sur le bouton (à faire côté Blade)
            const declarantNom = $(this).attr('data-identiteDeclarant');
            const pereNom = $(this).attr('data-identitePere');
            const mereNom = $(this).attr('data-identiteMere');
            // Pour les pièces, il faut ajouter des data-piece-* sur le bouton côté Blade si possible
            const pieceDeclarant = $(this).data('piece-declarant') || '';
            const piecePere = $(this).data('piece-pere') || '';
            const pieceMere = $(this).data('piece-mere') || '';
            const statutPere = $(this).data('statut-pere') || 'VIVANT';
            const statutMere = $(this).data('statut-mere') || 'VIVANT';

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
            let url = "{{ route('certificatDestruction.mouvement') }}";
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
