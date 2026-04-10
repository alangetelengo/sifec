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
                <div class="btn-group btn-group-xs">
                    @if(!$dd->acte)
                        @can('module.acteDeces.generate')
                            <button class="btn btn-success shadow btn-xs sharp me-1 btn-generer-single"
                                    data-id="{{ $dd->code_declaration_deces }}" title="Générer acte">
                                <i class="fas fa-file-alt"></i>
                            </button>
                        @endcan

                        @if($dd->type_declaration == "CERTIFICAT DE NON INSCRIPTION")
                            <a href="{{ route('certificatNonInscriptionDeces.displayCertificat',$dd->code_declaration_deces) }}" target="_blank" class="btn btn-warning shadow btn-xs sharp me-1" title="Voir document"><i class="fas fa-print"></i></a>
                            @if($dd->requisition != null)
                                <a href="{{ route('tribunal.voir_document', ['type' => 'deces', 'id' => $dd->code_declaration_deces]) }}"
                                    class="btn btn-info btn-xs text-start me-1" title="Télécharger le document importé">
                                    <i class="fas fa-download"></i>
                                </a>
                            @endif
                        @else
                            <a href="{{ route('declarationDeces.etat',$dd->code_declaration_deces) }}" target="_blank" class="btn btn-warning shadow btn-xs sharp me-1" title="Voir document"><i class="fas fa-print"></i></a>
                        @endif
                    @elseif($dd->acte && !$dd->acte->approbation_pompe_funebre)
                        <a href="{{ route('acteDeces.print.acte',$dd->code_declaration_deces) }}"
                           target="_blank"
                           class="btn btn-primary shadow btn-xs sharp me-1"
                           title="Voir l'acte">
                            <i class="fas fa-eye"></i>
                        </a>
                        @can('module.acteDeces.signature')
                            <button class="btn btn-primary shadow btn-xs sharp me-1 btn-validate-single"
                                    data-id="{{ $dd->code_declaration_deces }}" title="Valider acte">
                                <i class="fas fa-check-circle"></i>
                            </button>
                        @endcan
                    @elseif($dd->acte && $dd->acte->approbation_pompe_funebre && isset($dernierMouvement) && $dernierMouvement->code_mouvement == "MOUV_0016")
                        <a href="{{ route('acteDeces.print.acte',$dd->code_declaration_deces) }}"
                           target="_blank"
                           class="btn btn-primary shadow btn-xs sharp me-1"
                           title="Voir l'acte">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('acteDeces.displayCopie',$dd->code_declaration_deces) }}"
                           target="_blank"
                           class="btn btn-info shadow btn-xs sharp me-1"
                           title="Voir copie">
                            <i class="fas fa-copy"></i>
                        </a>
                        <a href="{{ route('acteDeces.displayExtrait',$dd->code_declaration_deces) }}"
                           target="_blank"
                           class="btn btn-warning shadow btn-xs sharp me-1"
                           title="Voir extrait">
                            <i class="fas fa-file-alt"></i>
                        </a>
                        @if(!isset($dernierMouvement) || $dernierMouvement->code_mouvement != "MOUV_0016")
                            <button class="btn btn-secondary shadow btn-xs sharp btn-retrait-acte"
                                    data-id="{{ $dd->acte->code_acte_deces }}" title="Enregistrer le retrait">
                                <i class="fas fa-archive"></i>
                            </button>
                        @endif
                    @elseif($dd->acte && $dd->acte->approbation_pompe_funebre && isset($dernierMouvement) && $dernierMouvement->code_mouvement == "MOUV_0017")
                        <a href="{{ route('acteDeces.print.acte',$dd->code_declaration_deces) }}"
                           target="_blank"
                           class="btn btn-primary shadow btn-xs sharp me-1"
                           title="Voir l'acte">
                            <i class="fas fa-eye"></i>
                        </a>
                    @elseif($dd->acte && $dd->acte->approbation_pompe_funebre)
                        <a href="{{ route('acteDeces.print.acte',$dd->code_declaration_deces) }}"
                           target="_blank"
                           class="btn btn-primary shadow btn-xs sharp me-1"
                           title="Voir l'acte">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('acteDeces.displayCopie',$dd->code_declaration_deces) }}"
                           target="_blank"
                           class="btn btn-info shadow btn-xs sharp me-1"
                           title="Voir copie">
                            <i class="fas fa-copy"></i>
                        </a>
                        <a href="{{ route('acteDeces.displayExtrait',$dd->code_declaration_deces) }}"
                           target="_blank"
                           class="btn btn-warning shadow btn-xs sharp me-1"
                           title="Voir extrait">
                            <i class="fas fa-file-alt"></i>
                        </a>
                        @if(!isset($dernierMouvement) || $dernierMouvement->code_mouvement != "MOUV_0016")
                            <button class="btn btn-danger shadow btn-xs sharp btn-retrait-acte"
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
        <td colspan="7" class="text-center">Aucun résultat trouvé</td>
    </tr>
@endif

