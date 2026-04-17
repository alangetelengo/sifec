@if($actes->count() > 0)
    @foreach ($actes as $dn)
    @php
        $dernierMouvement = null;
        if (isset($dn->mouvements) && $dn->mouvements && $dn->mouvements->count()) {
            $dernierMouvement = $dn->mouvements->sortByDesc('created_at')->first();
        }
        $codesMouvements = $dn->mouvements && $dn->mouvements->count() > 0 ? $dn->mouvements->pluck('code_mouvement')->toArray() : [];
        $acteGenere = $dn->acte != null;
        $acteValide = $dn->acte && $dn->acte->approbation_mairie;
        if (!$acteGenere) {
            $etapeLabel = '2 · Génération';
            $etapeClass = 'bg-warning text-dark';
        } elseif (! $dn->acte->approbation_mairie || $dn->acte->approbation_mairie === '') {
            $etapeLabel = '3 · Signature';
            $etapeClass = 'bg-primary';
        } elseif (isset($dernierMouvement) && $dernierMouvement->code_mouvement == 'MOUV_0016') {
            $etapeLabel = '4 · Retrait';
            $etapeClass = 'bg-success';
        } elseif (isset($dernierMouvement) && $dernierMouvement->code_mouvement == 'MOUV_0017') {
            $etapeLabel = '4 · Annulé';
            $etapeClass = 'bg-danger';
        } else {
            // Ne pas utiliser bg-secondary : le thème définit --bs-secondary très clair (#f8f9fa),
            // ce qui avec .badge { color: #fff } rend le libellé illisible (cellule « vide »).
            $etapeLabel = '3 · Signé';
            $etapeClass = 'bg-success';
        }
    @endphp
    <tr width="100%" @if($acteValide) class="table-light" @endif>
        <td>
            @if($acteValide)
                <span class="badge bg-success" style="font-size: 13px;font-weight:600;">Acte déjà validé</span>
            @elseif($acteGenere)
                <input type="checkbox" class="checkbox-acte" value="{{ $dn->code_declaration_naissance }}-1" disabled>
            @else
                <input type="checkbox" class="checkbox-acte" value="{{ $dn->code_declaration_naissance }}-0">
            @endif
        </td>
        <td>{{ $dn->acte ? $dn->acte->niupp : '//' }}</td>
        <td>{{ $dn->enfant->nomcomplet() }}</td>
        <td>{{ isset($dn->enfant->date_naissance) ? date('d/m/Y', strtotime($dn->enfant->date_naissance)) : '' }}</td>
        <td>{{ $dn->enfant->sexe == "M" ? "Masculin" : "Féminin" }}</td>
        <td style="white-space: nowrap">
            <span class="badge {{ $etapeClass }}">{{ $etapeLabel }}</span>
        </td>
        <td style="width: 15%">
            @if(!$dn->acte)
                <span class="badge badge-danger">En attente de génération de l'acte</span>
            @elseif($dn->acte && !$dn->acte->approbation_mairie)
                <span class="badge badge-warning">Acte généré, en attente de validation de l'officier</span>
            @elseif($dn->acte && $dn->acte->approbation_mairie && isset($dernierMouvement) && $dernierMouvement->code_mouvement == "MOUV_0016")
                <span class="badge badge-success">Acte rétiré</span>
            @elseif($dn->acte && $dn->acte->approbation_mairie && isset($dernierMouvement) && $dernierMouvement->code_mouvement == "MOUV_0017")
                <span class="badge badge-danger">Acte annulé</span>
            @elseif($dn->acte && $dn->acte->approbation_mairie)
                <span class="badge badge-info">Acte validé, non rétiré</span>
            @endif
        </td>
        <td>
            <div class="d-flex flex-wrap align-items-center gap-1">
                @if(!$dn->acte)
                    @can('module.acteNaissance.generate')
                        <button type="button" class="btn btn-sm btn-success btn-generer-single"
                                data-id="{{ $dn->code_declaration_naissance }}" title="Générer acte">
                            <i class="fas fa-file-alt"></i>
                        </button>
                        @if(isset($dn->requisition) && $dn->requisition || isset($dn->jugement) && $dn->jugement)
                            <a href="{{ route('tribunal.voir_document', ['type' => "naissance", 'id' =>  $dn->code_declaration_naissance]) }}"
                                class="btn btn-sm btn-info" title="Télécharger {{ $dn->requisition ? "la requisition importée" : "le jugement importé" }} par le tribunal">
                                <i class="fas fa-download"></i>
                            </a>
                        @endif
                    @endcan
                    <a href="{{ route('declarationNaissance.etat',$dn->code_declaration_naissance) }}" target="_blank" rel="noopener" class="btn btn-sm btn-warning" title="Voir document"><i class="fas fa-print"></i></a>
                @elseif($dn->acte && !$dn->acte->approbation_mairie)
                    <a href="{{ route('acteNaissance.print.acte',$dn->code_declaration_naissance) }}"
                       target="_blank"
                       rel="noopener"
                       class="btn btn-sm btn-primary"
                       title="Voir l'acte">
                        <i class="fas fa-eye"></i>
                    </a>
                    @can('module.acteNaissance.signature')
                        <button type="button" class="btn btn-sm btn-primary btn-validate-single"
                                data-id="{{ $dn->code_declaration_naissance }}" title="Valider acte">
                            <i class="fas fa-check-circle"></i>
                        </button>
                    @endcan
                @elseif($dn->acte && $dn->acte->approbation_mairie && isset($dernierMouvement) && $dernierMouvement->code_mouvement == "MOUV_0016")
                    <a href="{{ route('acteNaissance.print.acte',$dn->code_declaration_naissance) }}"
                       target="_blank"
                       rel="noopener"
                       class="btn btn-sm btn-primary"
                       title="Voir l'acte">
                        <i class="fas fa-eye"></i>
                    </a>
                    {{-- <a href="{{ route('acteNaissance.print.copie', $dn->code_declaration_naissance) }}"
                       target="_blank"
                       rel="noopener"
                       class="btn btn-sm btn-info"
                       title="Voir copie">
                        <i class="fas fa-copy"></i>
                    </a> --}}
                    <a href="{{ route('acteNaissance.print.extrait', $dn->code_declaration_naissance) }}"
                       target="_blank"
                       rel="noopener"
                       class="btn btn-sm btn-warning"
                       title="Voir extrait">
                        <i class="fas fa-file-alt"></i>
                    </a>

                @elseif($dn->acte && $dn->acte->approbation_mairie && isset($dernierMouvement) && $dernierMouvement->code_mouvement == "MOUV_0017")
                    <a href="{{ route('acteNaissance.print.acte',$dn->code_declaration_naissance) }}"
                       target="_blank"
                       rel="noopener"
                       class="btn btn-sm btn-primary"
                       title="Voir l'acte">
                        <i class="fas fa-eye"></i>
                    </a>
                @elseif($dn->acte && $dn->acte->approbation_mairie)
                    <a href="{{ route('acteNaissance.print.acte',$dn->code_declaration_naissance) }}"
                       target="_blank"
                       rel="noopener"
                       class="btn btn-sm btn-primary"
                       title="Voir l'acte">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('acteNaissance.print.copie', $dn->code_declaration_naissance) }}"
                       target="_blank"
                       rel="noopener"
                       class="btn btn-sm btn-info"
                       title="Voir copie">
                        <i class="fas fa-copy"></i>
                    </a>
                    <a href="{{ route('acteNaissance.print.extrait', $dn->code_declaration_naissance) }}"
                       target="_blank"
                       rel="noopener"
                       class="btn btn-sm btn-warning"
                       title="Voir extrait">
                        <i class="fas fa-file-alt"></i>
                    </a>

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

