@if($documents->count() > 0)
    @foreach ($documents as $dn)
    @php
        $dernierMouvement = null;
        if (isset($dn->mouvements) && $dn->mouvements && $dn->mouvements->count()) {
            $dernierMouvement = $dn->mouvements->sortByDesc('created_at')->first();
        }
        $codesMouvements = $dn->mouvements && $dn->mouvements->count() > 0 ? $dn->mouvements->pluck('code_mouvement')->toArray() : [];
        $acteProduit = collect(['MOUV_0014','MOUV_0015','MOUV_0016','MOUV_0017','MOUV_0023'])->intersect($codesMouvements)->isNotEmpty();
        $acteValide = $dn->acte && $dn->acte->statut == 1 && $dn->acte->approbation_mairie;
        $dejaConfirme = in_array('MOUV_0019', $codesMouvements);
    @endphp
    <tr width="100%" @if($dejaConfirme) class="table-light" @endif>
        <td>
            @if(!$dejaConfirme)
                <input type="checkbox" class="checkbox-document" value="{{ $dn->code_declaration_naissance }}">
            @else
                <span class="badge bg-success" style="font-size: 13px;font-weight:600;">Déjà validé</span>
            @endif
        </td>
        <td>{{ $dn->code_declaration_naissance }}</td>
        <td>{{ $dn->enfant->nomcomplet() }}</td>
        <td>{{ isset($dn->enfant->date_naissance) ? date('d/m/Y', strtotime($dn->enfant->date_naissance)) : '' }}</td>
        <td>{{ $dn->enfant->sexe == "M" ? "Masculin" : "Féminin" }}</td>
        <td>{{ $dn->type_declaration }}</td>
        <td style="width: 15%">
            @if($dernierMouvement)
                @if($dernierMouvement->code_mouvement == 'MOUV_0001' || $dernierMouvement->code_mouvement == 'MOUV_0035' || $dernierMouvement->code_mouvement == 'MOUV_0011' || $dernierMouvement->code_mouvement == 'MOUV_0024' || $dernierMouvement->code_mouvement == 'MOUV_0033')
                    <span class="badge light badge-warning" style="font-size: 13px;font-weight:600;">
                        Dossier reçu
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
                @if($dernierMouvement->code_mouvement == 'MOUV_0019')
                    <span class="badge light badge-success" style="font-size: 13px;font-weight:600;">
                        Confirmé par le CEC
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
                <a href="{{ route('declarationNaissance.show', $dn->code_declaration_naissance) }}"
                   class="btn btn-sm btn-info" title="Voir détail">
                    <i class="fas fa-eye"></i>
                </a>
                @if(in_array($dn->type_declaration, ['CERTIFICAT DE NAISSANCE', 'DECLARATION DE NAISSANCE'], true))
                    {{-- PDF certificat (mise en page formation sanitaire) : toujours pour ces types --}}
                    <a href="{{ route('declarationNaissance.etat', ['id' => $dn->code_declaration_naissance, 'contexte' => 'formation_sanitaire']) }}"
                       target="_blank"
                       rel="noopener"
                       class="btn btn-sm btn-warning"
                       title="Ouvrir le PDF du certificat de naissance">
                        <i class="fas fa-file-medical"></i>
                    </a>
                    @if($dn->type_declaration === 'DECLARATION DE NAISSANCE')
                        {{-- PDF déclaration telle que générée pour le centre d’état civil --}}
                        <a href="{{ route('declarationNaissance.etat', ['id' => $dn->code_declaration_naissance, 'contexte' => 'centre_etat_civil']) }}"
                           target="_blank"
                           rel="noopener"
                           class="btn btn-sm btn-success"
                           title="Ouvrir le PDF de la déclaration de naissance générée">
                            <i class="fas fa-file-alt"></i>
                        </a>
                    @endif
                @else
                    <a href="{{ route('declarationNaissance.etat', $dn->code_declaration_naissance) }}" target="_blank" class="btn btn-sm btn-warning" title="Voir le document (PDF)">
                        <i class="fas fa-print"></i>
                    </a>
                @endif

                @if($dn->type_declaration != 'DECLARATION DE NAISSANCE')
                    @if(isset($dn->requisition) && $dn->requisition || isset($dn->jugement) && $dn->jugement)
                        <a href="{{ route('tribunal.voir_document', ['type' => "naissance", 'id' =>  $dn->code_declaration_naissance]) }}"
                            class="btn btn-sm btn-info" title="Télécharger {{ $dn->requisition ? "la requisition importée" : "le jugement importé" }} par le tribunal">
                            <i class="fas fa-download"></i>
                        </a>
                    @endif
                @endif
                @if(!$dejaConfirme)
                <button type="button" class="btn btn-sm btn-success btn-confirmer-document"
                        data-id="{{ $dn->code_declaration_naissance }}" title="Confirmer le dossier">
                    <i class="fas fa-check"></i>
                </button>

                <button type="button" class="btn btn-sm btn-warning btn-renvoyer-document"
                        data-id="{{ $dn->code_declaration_naissance }}" title="Renvoyer">
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

