@if($documents->count() > 0)
    @foreach ($documents as $dd)
        @php
            $dernierMouvement = null;
            if (isset($dd->mouvements) && $dd->mouvements && $dd->mouvements->count()) {
                $dernierMouvement = $dd->mouvements->sortByDesc('created_at')->first();
            }
            $codesMouvements = $dd->mouvements ? $dd->mouvements->pluck('code_mouvement')->toArray() : [];
            $acteProduit = collect(['MOUV_0014','MOUV_0015','MOUV_0016','MOUV_0017','MOUV_0023'])->intersect($codesMouvements)->isNotEmpty();
            $acteValide = $dd->acte && $dd->acte->statut == 1 && $dd->acte->approbation_pompe_funebre;
            $dejaConfirme = in_array('MOUV_0019', $codesMouvements);
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
                    @if($dernierMouvement->code_mouvement == 'MOUV_0002' || $dernierMouvement->code_mouvement == 'MOUV_2006')
                        <span class="badge light badge-warning" style="font-size: 13px;font-weight:600;">
                            Dossier reçu
                        </span>
                    @else
                        <span class="badge light badge-secondary" style="font-size: 13px;font-weight:600;">
                            {{ $dernierMouvement->lib_mouvement }}
                        </span>
                    @endif

                    @if($dernierMouvement->code_mouvement == 'MOUV_0004')
                        <span class="badge light badge-info" style="font-size: 13px;font-weight:600;">
                            {{ $dernierMouvement->lib_mouvement }}
                        </span>
                    @endif

                    @if($dernierMouvement->code_mouvement == 'MOUV_0016')
                        <span class="badge light badge-success" style="font-size: 13px;font-weight:600;">
                            {{ $dernierMouvement->lib_mouvement }}
                        </span>
                    @endif
                    @if($dernierMouvement->code_mouvement == 'MOUV_0017')
                        <span class="badge light badge-secondary" style="font-size: 13px;font-weight:600;">
                            {{ $dernierMouvement->lib_mouvement}}
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
                <div class="btn-group btn-group-xs">
                    <a href="{{ route('declarationDeces.show', $dd->code_declaration_deces) }}"
                       class="btn btn-info shadow btn-xs sharp me-1" title="Voir détail">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('declarationDeces.etat', $dd->code_declaration_deces) }}"
                       target="_blank" class="btn btn-warning shadow btn-xs sharp me-1" title="Voir la déclaration ou le certificat">
                        <i class="fas fa-file-pdf"></i>
                    </a>
                    @if(isset($dd->requisition) && $dd->requisition || isset($dd->jugement) && $dd->jugement)
                        <a href="{{ route('tribunal.voir_document', ['type' => "deces", 'id' =>  $dd->code_declaration_deces]) }}"
                            class="btn btn-info btn-xs text-start me-1" title="Télécharger {{ $dd->requisition ? "la requisition importée" : "le jugement importé" }} par le tribunal">
                            <i class="fas fa-download"></i>
                        </a>
                    @endif
                    @if(!$dejaConfirme)
                        <button class="btn btn-success shadow btn-xs sharp me-1 btn-confirmer-document"
                                data-id="{{ $dd->code_declaration_deces }}" title="Confirmer le dossier">
                            <i class="fas fa-check"></i>
                        </button>

                        <button class="btn btn-warning shadow btn-xs sharp btn-renvoyer-document"
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

