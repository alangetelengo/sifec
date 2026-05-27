@if($documents->count() > 0)
    @foreach ($documents as $dd)
        @php
            $dernierMouvement = null;
            if (isset($dd->mouvements) && $dd->mouvements && $dd->mouvements->count()) {
                $dernierMouvement = $dd->mouvements->sortByDesc('created_at')->first();
            }
            $codesMouvements = $dd->mouvements ? $dd->mouvements->pluck('code_mouvement')->toArray() : [];
            $dejaConfirme = in_array('MOUV_0019', $codesMouvements);
            $dossierRecu = in_array('MOUV_0002', $codesMouvements) || in_array('MOUV_2006', $codesMouvements);
            $contexteCertificat = $dd->contexteCertificatOrigine();
            $afficherDeclarationPf = $dd->type_declaration === 'DECLARATION DE DECES'
                && ($dejaConfirme || filled($dd->type_declaration_origine));
        @endphp
        <tr width="100%" @if($dejaConfirme) class="table-light" @endif>
            <td>
                @if(!$dejaConfirme)
                    <input type="checkbox" class="checkbox-document" value="{{ $dd->code_declaration_deces }}">
                @else
                    <span class="badge bg-success" style="font-size: 13px;font-weight:600;">Déjà validé</span>
                @endif
            </td>
            <td>{{ $dd->code_declaration_deces }}</td>
            <td>{{ $dd->defunt->nomcomplet() }}</td>
            <td>{{ isset($dd->date_heure_deces) ? date('d/m/Y', strtotime($dd->date_heure_deces)) : '' }}</td>
            <td>{{ $dd->defunt->sexe == "M" ? "Masculin" : "Féminin" }}</td>
            <td>{{ $dd->type_declaration }}</td>
            <td style="width: 15%">
                @if($dernierMouvement)
                    @if($dossierRecu && !$dejaConfirme)
                        <span class="badge light badge-warning" style="font-size: 13px;font-weight:600;">
                            Dossier reçu
                        </span>
                    @elseif($dernierMouvement->code_mouvement == 'MOUV_0004')
                        <span class="badge light badge-info" style="font-size: 13px;font-weight:600;">
                            {{ $dernierMouvement->lib_mouvement }}
                        </span>
                    @elseif($dernierMouvement->code_mouvement == 'MOUV_0019')
                        <span class="badge light badge-success" style="font-size: 13px;font-weight:600;">
                            Confirmé par la PF
                        </span>
                    @elseif($dernierMouvement->code_mouvement == 'MOUV_2012')
                        <span class="badge light badge-success" style="font-size: 13px;font-weight:600;">
                            {{ $dernierMouvement->lib_mouvement }}
                        </span>
                    @else
                        <span class="badge light badge-secondary" style="font-size: 13px;font-weight:600;">
                            {{ $dernierMouvement->lib_mouvement }}
                        </span>
                    @endif
                    <br>
                    @if($dernierMouvement->observation)
                        <small>Observation : {{ $dernierMouvement->observation }}</small>
                    @endif
                    @if($dernierMouvement->motif_renvoi)
                        <br><small>Motif : {{ $dernierMouvement->motif_renvoi }}</small>
                    @endif
                @endif
            </td>
            <td>
                <div class="d-flex flex-wrap align-items-center gap-1">
                    <a href="{{ route('declarationDeces.show', $dd->code_declaration_deces) }}"
                       class="btn btn-sm btn-info" title="Voir détail">
                        <i class="fas fa-eye"></i>
                    </a>

                    @if($dd->estDocumentCertificatOrigine())
                        <a href="{{ route('declarationDeces.voir.etat', ['id' => $dd->code_declaration_deces, 'contexte' => $contexteCertificat, 'from' => 'acte']) }}"
                           class="btn btn-sm btn-warning"
                           title="{{ $contexteCertificat === 'centre_hygiene' ? 'Ouvrir le PDF du certificat de constatation' : 'Ouvrir le PDF du certificat de décès' }}">
                            <i class="fas fa-file-medical"></i>
                        </a>
                        @if($afficherDeclarationPf)
                            <a href="{{ route('declarationDeces.voir.etat', ['id' => $dd->code_declaration_deces, 'contexte' => 'pompe_funebre', 'from' => 'acte']) }}"
                               class="btn btn-sm btn-success"
                               title="Ouvrir le PDF de la déclaration de décès générée">
                                <i class="fas fa-file-alt"></i>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('declarationDeces.voir.etat', ['id' => $dd->code_declaration_deces, 'from' => 'acte']) }}"
                           class="btn btn-sm btn-warning" title="Voir le document (PDF)">
                            <i class="fas fa-print"></i>
                        </a>
                    @endif

                    @if(isset($dd->requisition) && $dd->requisition || isset($dd->jugement) && $dd->jugement)
                        <a href="{{ route('tribunal.voir_document', ['type' => 'deces', 'id' => $dd->code_declaration_deces]) }}"
                            class="btn btn-sm btn-info" title="Télécharger {{ $dd->requisition ? 'la requisition importée' : 'le jugement importé' }} par le tribunal">
                            <i class="fas fa-download"></i>
                        </a>
                    @endif

                    @if(!$dejaConfirme)
                        <button type="button" class="btn btn-sm btn-success btn-confirmer-document"
                                data-id="{{ $dd->code_declaration_deces }}" title="Confirmer le dossier">
                            <i class="fas fa-check"></i>
                        </button>

                        <button type="button" class="btn btn-sm btn-warning btn-renvoyer-document"
                                data-id="{{ $dd->code_declaration_deces }}" title="Renvoyer">
                            <i class="fas fa-undo"></i>
                        </button>
                    @endif
                </div>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="8" class="text-center">Aucun résultat trouvé</td>
    </tr>
@endif
