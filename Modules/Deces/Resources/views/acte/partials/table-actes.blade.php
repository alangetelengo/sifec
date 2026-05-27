@if($actes->count() > 0)
    @foreach ($actes as $dd)
        @php
            $dernierMouvement = null;
            if ($dd->mouvements && $dd->mouvements->count() > 0) {
                $dernierMouvement = $dd->mouvements->sortByDesc('created_at')->first();
            }
            $codesMouvements = ($dd->mouvements && $dd->mouvements->count() > 0) ? $dd->mouvements->pluck('code_mouvement')->toArray() : [];
            $acteGenere = $dd->acte != null;
            $approbationPompeFunebre = $dd->acte ? ($dd->acte->approbation_pompe_funebre ?? null) : null;
            $acteValide = $dd->acte && !empty($approbationPompeFunebre) && $approbationPompeFunebre !== '0';
            if (!$acteGenere) {
                $etapeLabel = '2 · Génération';
                $etapeClass = 'bg-warning text-dark';
            } elseif (is_null($approbationPompeFunebre) || $approbationPompeFunebre === '' || $approbationPompeFunebre === '0') {
                $etapeLabel = '3 · Signature';
                $etapeClass = 'bg-primary';
            } elseif (isset($dernierMouvement) && $dernierMouvement->code_mouvement == 'MOUV_0016') {
                $etapeLabel = '4 · Retrait';
                $etapeClass = 'bg-success';
            } elseif (isset($dernierMouvement) && $dernierMouvement->code_mouvement == 'MOUV_0017') {
                $etapeLabel = '4 · Annulé';
                $etapeClass = 'bg-danger';
            } else {
                $etapeLabel = '3 · Signé';
                $etapeClass = 'bg-success';
            }
        @endphp
        <tr width="100%" @if($acteValide) class="table-light" @endif>
            <td>
                @if($acteValide)
                    <span class="badge bg-success" style="font-size: 13px;font-weight:600;">Acte déjà validé</span>
                @elseif($acteGenere)
                    <input type="checkbox" class="checkbox-acte" value="{{ $dd->code_declaration_deces }}-1" disabled>
                @else
                    <input type="checkbox" class="checkbox-acte" value="{{ $dd->code_declaration_deces }}-0">
                @endif
            </td>
            <td>{{ $dd->acte ? $dd->acte->code_acte_deces : '//' }}</td>
            <td>{{ $dd->defunt->nomcomplet() }}</td>
            <td>{{ isset($dd->date_heure_deces) ? date('d/m/Y', strtotime($dd->date_heure_deces)) : '' }}</td>
            <td>{{ $dd->defunt->sexe == "M" ? "Masculin" : "Féminin" }}</td>
            <td style="white-space: nowrap">
                <span class="badge {{ $etapeClass }}">{{ $etapeLabel }}</span>
            </td>
            <td style="width: 15%">
                @if(!$dd->acte)
                    <span class="badge badge-danger">En attente de génération de l'acte</span>
                @elseif($dd->acte && (is_null($approbationPompeFunebre) || $approbationPompeFunebre === '' || $approbationPompeFunebre === '0'))
                    <span class="badge badge-warning">Acte généré, en attente de validation de l'officier</span>
                @elseif($approbationPompeFunebre && $approbationPompeFunebre !== '' && $dernierMouvement && $dernierMouvement->code_mouvement == "MOUV_0016")
                    <span class="badge badge-success">Acte rétiré</span>
                @elseif($approbationPompeFunebre && $approbationPompeFunebre !== '' && $dernierMouvement && $dernierMouvement->code_mouvement == "MOUV_0017")
                    <span class="badge badge-danger">Acte annulé</span>
                @elseif($approbationPompeFunebre && $approbationPompeFunebre !== '')
                    <span class="badge badge-info">Acte validé, non rétiré</span>
                @endif
            </td>
            <td>
                <div class="d-flex flex-wrap align-items-center gap-1">
                    @if(!$dd->acte)
                        @can('module.acteDeces.generate')
                            <button type="button" class="btn btn-sm btn-success btn-generer-single"
                                    data-id="{{ $dd->code_declaration_deces }}" title="Générer acte">
                                <i class="fas fa-file-alt"></i>
                            </button>
                        @endcan

                        @if($dd->type_declaration == "CERTIFICAT DE NON INSCRIPTION")
                            <a href="{{ route('certificatNonInscriptionDeces.displayCertificat',$dd->code_declaration_deces) }}"
                               target="_blank"
                               rel="noopener"
                               class="btn btn-sm btn-warning"
                               title="Voir document">
                                <i class="fas fa-print"></i>
                            </a>
                            @if($dd->requisition != null)
                                <a href="{{ route('tribunal.voir_document', ['type' => 'deces', 'id' => $dd->code_declaration_deces]) }}"
                                    class="btn btn-sm btn-info"
                                    title="Télécharger le document importé">
                                    <i class="fas fa-download"></i>
                                </a>
                            @endif
                        @else
                            <a href="{{ route('declarationDeces.voir.etat', ['id' => $dd->code_declaration_deces, 'from' => 'acte']) }}"
                               class="btn btn-sm btn-warning"
                               title="Voir document">
                                <i class="fas fa-print"></i>
                            </a>
                        @endif
                    @elseif($dd->acte && !$dd->acte->approbation_pompe_funebre)
                        <a href="{{ route('acteDeces.print.acte',$dd->code_declaration_deces) }}"
                           target="_blank"
                           rel="noopener"
                           class="btn btn-sm btn-primary"
                           title="Voir l'acte">
                            <i class="fas fa-eye"></i>
                        </a>
                        @can('module.acteDeces.signature')
                            <button type="button" class="btn btn-sm btn-primary btn-validate-single"
                                    data-id="{{ $dd->code_declaration_deces }}" title="Valider acte">
                                <i class="fas fa-check-circle"></i>
                            </button>
                        @endcan
                    @elseif($dd->acte && $dd->acte->approbation_pompe_funebre && isset($dernierMouvement) && $dernierMouvement->code_mouvement == "MOUV_0016")
                        <a href="{{ route('acteDeces.print.acte',$dd->code_declaration_deces) }}"
                           target="_blank"
                           rel="noopener"
                           class="btn btn-sm btn-primary"
                           title="Voir l'acte">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('acteDeces.displayCopie',$dd->code_declaration_deces) }}"
                           target="_blank"
                           rel="noopener"
                           class="btn btn-sm btn-info"
                           title="Voir copie">
                            <i class="fas fa-copy"></i>
                        </a>
                        <a href="{{ route('acteDeces.displayExtrait',$dd->code_declaration_deces) }}"
                           target="_blank"
                           rel="noopener"
                           class="btn btn-sm btn-warning"
                           title="Voir extrait">
                            <i class="fas fa-file-alt"></i>
                        </a>
                    @elseif($dd->acte && $dd->acte->approbation_pompe_funebre && isset($dernierMouvement) && $dernierMouvement->code_mouvement == "MOUV_0017")
                        <a href="{{ route('acteDeces.print.acte',$dd->code_declaration_deces) }}"
                           target="_blank"
                           rel="noopener"
                           class="btn btn-sm btn-primary"
                           title="Voir l'acte">
                            <i class="fas fa-eye"></i>
                        </a>
                    @elseif($dd->acte && $dd->acte->approbation_pompe_funebre)
                        <a href="{{ route('acteDeces.print.acte',$dd->code_declaration_deces) }}"
                           target="_blank"
                           rel="noopener"
                           class="btn btn-sm btn-primary"
                           title="Voir l'acte">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('acteDeces.displayCopie',$dd->code_declaration_deces) }}"
                           target="_blank"
                           rel="noopener"
                           class="btn btn-sm btn-info"
                           title="Voir copie">
                            <i class="fas fa-copy"></i>
                        </a>
                        <a href="{{ route('acteDeces.displayExtrait',$dd->code_declaration_deces) }}"
                           target="_blank"
                           rel="noopener"
                           class="btn btn-sm btn-warning"
                           title="Voir extrait">
                            <i class="fas fa-file-alt"></i>
                        </a>
                        @if(!isset($dernierMouvement) || $dernierMouvement->code_mouvement != "MOUV_0016")
                            <button type="button" class="btn btn-sm btn-danger btn-retrait-acte"
                                    data-id="{{ $dd->acte->code_acte_deces }}" title="Enregistrer le retrait">
                                <i class="fas fa-archive"></i>
                            </button>
                        @endif
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
