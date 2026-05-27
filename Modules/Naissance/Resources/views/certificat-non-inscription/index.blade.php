@extends('layout.app')
@section('titre')
certificat de non inscription
@endsection
@section("styles")
<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
@endsection
@section('sous-titre')
    Liste des certificat de non inscription
@endsection
@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Liste des certificat de non inscription</h4>
            </div>
            <div class="card-body">
                <form id="formdata" class="validation-wizard wizard-circle">
                    @csrf
                    <div class="cni-verify-card">
                        <div class="cni-verify-card__head">
                            <div>
                                <h5><i class="fas fa-calendar-check me-2"></i>Vérification du délai légal</h5>
                                <p>Indiquez la date de naissance de l’enfant pour savoir si vous devez établir un certificat de non-inscription ou une déclaration classique.</p>
                            </div>
                        </div>
                        <div class="cni-verify-card__body">
                            <div class="cni-verify-field">
                                <label class="form-label" for="date_naissance_enfant">Date de naissance de l'enfant <span class="text-danger">*</span></label>
                                <input type="date"
                                       name="date_naissance_enfant"
                                       max="<?php echo date('Y-m-d'); ?>"
                                       min="1900-01-01"
                                       class="form-control"
                                       id="date_naissance_enfant"
                                       required
                                       onchange="validateDate(this)">
                                <div class="invalid-feedback d-block" id="date-error"></div>
                                <div class="form-text"><i class="fas fa-info-circle me-1 opacity-75"></i>La date ne peut pas être postérieure à aujourd’hui.</div>
                            </div>
                            <div id="cni-alerts-stack" class="cni-alerts-stack">
                                <div id="texte"></div>
                                <div class="validate"></div>
                            </div>
                            <div class="cni-verify-actions">
                                <button type="submit" class="btn cni-btn-verify" id="btn-submit" disabled>
                                    <i class="fas fa-arrow-right me-2"></i>Cliquez ici
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
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
                                                <br><small class="text-success"><i class="fas fa-gavel me-1"></i>Prêt pour la transcription de l'acte</small>
                                            @endif
                                        </td>
                                        <td>{{ $certificat->type_declaration  }}</td>
                                        <td>
                                            <div class="btn-group btn-group-xs">
                                                <a href="{{ route('certificatNonInscription.show', $certificat->code_declaration_naissance) }}"
                                                    class="btn btn-primary shadow btn-xs sharp me-1"
                                                    title="Voir détail">
                                                    <i class="fas fa-eye"></i>
                                                 </a>
                                                <a href="{{ route('declarationNaissance.voir.etat', ['id' => $certificat->code_declaration_naissance, 'from' => 'declaration']) }}"
                                                   class="btn btn-warning shadow btn-xs sharp me-1"
                                                   title="Voir document">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                                @if($dernierMouvement && in_array($dernierMouvement->code_mouvement, ['MOUV_0026', 'MOUV_0004']))
                                                    <a href="{{ route('declarationNaissance.edit',$certificat->code_declaration_naissance) }}" class="btn btn-info shadow btn-xs sharp me-1" title="Modifier"><i class="fas fa-pencil-alt"></i></a>
                                                    <form action="{{ route('declarationNaissance.destroy',$certificat->code_declaration_naissance) }}" method="POST" style="display: inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                        <button type="submit" class="btn btn-danger shadow btn-xs sharp" title="Supprimer"><i class="fa fa-trash"></i></button>
                                                </form>
                                                @endif
                                                @if($peutEnvoyer)
                                                    <button class="btn btn-primary btn-envoyer-tribunal shadow btn-xs sharp"
                                                        data-code="{{ $certificat->code_declaration_naissance }}"
                                                        data-piece-declarant="{{ $certificat->piece_declarant }}"
                                                        data-piece-pere="{{ $certificat->piece_pere }}"
                                                        data-piece-mere="{{ $certificat->piece_mere }}"
                                                        data-statut-pere="{{ optional($certificat->pere)->statut_personne ?? 'VIVANT' }}"
                                                        data-statut-mere="{{ optional($certificat->mere)->statut_personne ?? 'VIVANT' }}"
                                                        data-identiteDeclarant="{{ $certificat->declarant->nomcomplet() }}"
                                                        data-identitePere="{{ $certificat->pere ? $certificat->pere->nomcomplet() : '' }}"
                                                        data-identiteMere="{{ $certificat->mere ? $certificat->mere->nomcomplet() : '' }}"
                                                        title="{{ $dernierMouvementRenvoye ? 'Réenvoyer au tribunal' : 'Envoyer au tribunal' }}">
                                                        <i class="fas fa-paper-plane"></i>
                                                    </button>
                                                @elseif($dejaTraiteTribunal)

                                                     {{-- Télécharger le document importé (si déjà importé) --}}
                                                        @if($certificat->requisition != null)
                                                        <a href="{{ route('tribunal.voir_document', ['type' => 'naissance', 'id' => $certificat->code_declaration_naissance]) }}"
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

    $("#noninscript").hide();
    $("#inscript").hide();

    // Fonction de validation de date
    function validateDate(input) {
        const selectedDate = new Date(input.value);
        const today = new Date();
        today.setHours(23, 59, 59, 999); // Fin de journée pour permettre la date d'aujourd'hui

        const errorDiv = document.getElementById('date-error');

        // Réinitialiser les classes
        input.classList.remove('is-valid', 'is-invalid');
        errorDiv.textContent = '';

        if (!input.value) {
            return false;
        }

        if (selectedDate > today) {
            input.classList.add('is-invalid');
            errorDiv.textContent = 'La date de naissance ne peut pas être supérieure à aujourd\'hui';
            return false;
        }

        // Vérifier que la date n'est pas trop ancienne (optionnel)
        const minDate = new Date('1900-01-01');
        if (selectedDate < minDate) {
            input.classList.add('is-invalid');
            errorDiv.textContent = 'La date de naissance ne peut pas être antérieure à 1900';
            return false;
        }

        input.classList.add('is-valid');
        return true;
    }

    function calculAge(datenais){
        // var datechoisie = $("#date_naissance_enfant").val();
        var datechoisie_convertie = moment(moment(datenais, 'DD-MM-YYYY')).format('YYYY-MM-DD');
        var age_annee = moment().diff(moment(datenais, 'YYYYMMDD'), 'years');
        var age_mois = moment().diff(moment(datenais, 'YYYYMMDD'), 'month');
        console.log("L'age de l'enfant est: = "+age_annee);
    }


    function age() {
        var dateNaissance = $("#date_naissance_enfant").val();
        var age_annee = 0;
        var age_mois = 0;
        var datechoisie_convertie = moment(moment(dateNaissance, 'DD-MM-YYYY')).format('YYYY-MM-DD');
        var age_annee = moment().diff(moment(dateNaissance, 'YYYYMMDD'), 'years');
        var age_mois = moment().diff(moment(dateNaissance, 'YYYYMMDD'), 'month');
        var age_day = moment().diff(moment(dateNaissance, 'YYYYMMDD'), 'day');
        validateDeclaration(age_day,age_mois,age_annee);

    }

    function validateDeclaration(age_day,age_mois,age_annee){
        if(age_day > 30){
            $('#texte').html('Nombre de jours sans déclarer : '+age_day+' jours');
            $("#formdata").attr("action", "{{ route('certificatNonInscription.create') }}");
            $("#formdata").attr("method", "POST");
            $(".validate").html("Cliquer sur ce lien pour créer un certificat de non inscription: Le délais de déclaration est supérieur à 30 jours. Une réquisition requise conformément à l'article 80 du code de la famille.");
            // console.log("c'est une déclaration tardive qui ne necessite pas une réquisition du parquet.");
        }

        // else{
        //     $('#texte').html('Nombre de jour sans déclarer : '+age_day+' jour(s)');
        //     $("#formdata").attr("action", "{{ route('certificatNonInscription.create') }}");
        //     $("#formdata").attr("method", "POST");
        //     $(".validate").html("Cliquer sur ce lien pour créer une déclaration de naissance.");
        // }
        if(age_mois > 3){
            $('#texte').html('Nombre de mois sans déclarer : '+age_mois+' mois');
            $("#formdata").attr("action", "{{ route('certificatNonInscription.create') }}");
            $("#formdata").attr("method", "POST");
            $(".validate").html("Cliquer sur ce lien pour créer un certificat de non inscription: Le délais de déclaration est supérieur à 3 mois. Une réquisition ou un jugement est requis conformément à l'article 80 du code de la famille.");
            // console.log("c'est une déclaration tardive qui ne necessite pas une réquisition du parquet.");
        }
        // else{
        //     $('#texte').html('Nombre de jour sans déclarer : '+age_day+' jour(s)');
        //     $("#formdata").attr("action", "{{ route('certificatNonInscription.create') }}");
        //     $("#formdata").attr("method", "POST");
        //     $(".validate").html("Cliquer sur ce lien pour créer une déclaration de naissance.");
        // }
    }

    $(function() {
        $('#btn-submit').prop('disabled', true);

        $('#date_naissance_enfant').on('change', function() {
            const dateNaissance = $(this).val();
            if (!dateNaissance) {
                $('#texte').html('<div class="cni-callout cni-callout--action"><div class="cni-callout__icon"><i class="fas fa-hand-pointer"></i></div><div class="cni-callout__body"><strong>Date requise</strong>Veuillez sélectionner une date de naissance.</div></div>');
                $('.validate').empty();
                $('#btn-submit').prop('disabled', true);
                return;
            }

            // Validation de la date
            if (!validateDate(this)) {
                $('#texte').html('<div class="cni-callout cni-callout--deadline"><div class="cni-callout__icon"><i class="fas fa-times-circle"></i></div><div class="cni-callout__body"><strong>Date invalide</strong>Corrigez la date de naissance (elle ne peut pas être dans le futur ni avant 1900).</div></div>');
                $('.validate').empty();
                $('#btn-submit').prop('disabled', true);
                return;
            }
            const today = moment();
            const naissance = moment(dateNaissance, 'YYYY-MM-DD');
            const age_jours = today.diff(naissance, 'days');
            const age_mois = today.diff(naissance, 'months');
            const age_annee = today.diff(naissance, 'years');

            let message = '';
            let lien = '';
            let type = '';
            if (age_jours > 30) {
                message = '<div class="cni-callout cni-callout--deadline"><div class="cni-callout__icon"><i class="fas fa-clock"></i></div><div class="cni-callout__body"><strong>Délai dépassé</strong>Nombre de jours sans déclarer : <b>' + age_jours + ' jours</b>.<span class="cni-callout__sub">Le délai légal de déclaration est dépassé.</span></div></div>';
                lien = '<div class="cni-callout cni-callout--action"><div class="cni-callout__icon"><i class="fas fa-exclamation-triangle"></i></div><div class="cni-callout__body"><strong>Action requise</strong>Vous devez créer un <b>certificat de non inscription</b>.<span class="cni-callout__sub">Une réquisition est requise, conformément à la loi.</span></div></div>';
                type = 'certificat';
            }
            if (age_jours > 90) {
                message = '<div class="cni-callout cni-callout--deadline"><div class="cni-callout__icon"><i class="fas fa-clock"></i></div><div class="cni-callout__body"><strong>Délai dépassé</strong>Nombre de jours sans déclarer : <b>' + age_jours + ' jours</b>.<span class="cni-callout__sub">Le délai légal de déclaration est dépassé.</span></div></div>';
                lien = '<div class="cni-callout cni-callout--action"><div class="cni-callout__icon"><i class="fas fa-exclamation-triangle"></i></div><div class="cni-callout__body"><strong>Action requise</strong>Vous devez créer un <b>certificat de non inscription</b>.<span class="cni-callout__sub">Un jugement est requis, conformément à la loi.</span></div></div>';
                type = 'certificat';
            }
            // if (age_mois > 3) {
            //     message = '<div class="cni-callout cni-callout--deadline"><div class="cni-callout__icon"><i class="fas fa-clock"></i></div><div class="cni-callout__body"><strong>Délai largement dépassé</strong>Nombre de mois sans déclarer : <b>' + age_mois + ' mois</b>.<span class="cni-callout__sub">Un jugement est requis (article 80 du code de la famille).</span></div></div>';
            //     lien = '<div class="cni-callout cni-callout--action"><div class="cni-callout__icon"><i class="fas fa-file-signature"></i></div><div class="cni-callout__body"><strong>Étape suivante</strong>Créez un <b>certificat de non inscription</b> pour poursuivre la procédure.</div></div>';
            //     type = 'certificat';
            // }
            // if (age_mois < 3) {
            //     message = '<div class="cni-callout cni-callout--deadline"><div class="cni-callout__icon"><i class="fas fa-clock"></i></div><div class="cni-callout__body"><strong>Délai largement dépassé</strong>Nombre de mois sans déclarer : <b>' + age_mois + ' mois</b>.<span class="cni-callout__sub">Une réquisition est requise (article 80 du code de la famille).</span></div></div>';
            //     lien = '<div class="cni-callout cni-callout--action"><div class="cni-callout__icon"><i class="fas fa-file-signature"></i></div><div class="cni-callout__body"><strong>Étape suivante</strong>Créez un <b>certificat de non inscription</b> pour poursuivre la procédure.</div></div>';
            //     type = 'certificat';
            // }
            // if (age_jours <= 30 && age_mois <= 3) {
            //     message = '<div class="cni-callout cni-callout--info"><div class="cni-callout__icon"><i class="fas fa-info-circle"></i></div><div class="cni-callout__body"><strong>Délai respecté</strong>Nombre de jours depuis la naissance : <b>' + age_jours + ' jours</b>.</div></div>';
            //     lien = '<div class="cni-callout cni-callout--success"><div class="cni-callout__icon"><i class="fas fa-check-circle"></i></div><div class="cni-callout__body"><strong>Déclaration classique</strong>Vous pouvez créer une <b>déclaration de naissance</b> selon le circuit habituel.</div></div>';
            //     type = 'declaration';
            // }
            $('#texte').html(message);
            $('.validate').html(lien);
            $('#btn-submit').prop('disabled', false);
            $('#formdata').data('type', type);
        });

        $('#formdata').on('submit', function(e) {
            e.preventDefault();
            const type = $(this).data('type');
            const dateNaissance = $('#date_naissance_enfant').val();
            const dateInput = document.getElementById('date_naissance_enfant');

            if (!dateNaissance) {
                $('#texte').html('<div class="cni-callout cni-callout--action"><div class="cni-callout__icon"><i class="fas fa-hand-pointer"></i></div><div class="cni-callout__body"><strong>Date requise</strong>Veuillez sélectionner une date de naissance.</div></div>');
                return;
            }

            // Validation finale de la date
            if (!validateDate(dateInput)) {
                $('#texte').html('<div class="cni-callout cni-callout--deadline"><div class="cni-callout__icon"><i class="fas fa-times-circle"></i></div><div class="cni-callout__body"><strong>Date invalide</strong>Corrigez la date avant de continuer.</div></div>');
                return;
            }
            $('#btn-submit').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Redirection…');
            let url = '';
            if (type === 'certificat') {
                url = "{{ route('certificatNonInscription.create') }}";
            } else {
                url = "{{ route('declarationNaissance.create') }}";
            }
            // Redirection GET avec la date en query string
            window.location.href = url + '?date_naissance_enfant=' + encodeURIComponent(dateNaissance);
        });

        let codeTribunal = null;
        $(document).on('click', '.btn-envoyer-tribunal', function(){
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
            let url = "{{ route('certificatNonInscription.mouvement') }}";
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
