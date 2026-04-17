<div class="card an-filter-card shadow-none border">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
        <h2 class="card-title mb-0">
            <i class="fas fa-clipboard-list me-2 text-secondary"></i> Résultat
        </h2>
        @if($acte->retrait)
            <span class="badge bg-success rounded-pill px-3 py-2">Acte retiré</span>
        @else
            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">Aucun retrait enregistré</span>
        @endif
    </div>
    <div class="card-body pt-3">
        @if($acte->retrait)
            <div class="an-table-wrap">
                <table class="table table-hover an-data-table mb-0">
                    <thead>
                        <tr>
                            <th>N° acte</th>
                            <th>Enfant</th>
                            <th>Père</th>
                            <th>Mère</th>
                            <th>Retiré par</th>
                            <th>Contact</th>
                            <th>Date du retrait</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code class="small">{{ $acte->niupp }}</code></td>
                            <td>
                                <strong>{{ $acte->declaration->enfant->nom }}</strong>
                                <span class="text-capitalize">{{ $acte->declaration->enfant->prenom }}</span>
                            </td>
                            <td>
                                @if($acte->declaration->pere)
                                    {{ $acte->declaration->pere->nom }}
                                    <span class="text-capitalize">{{ $acte->declaration->pere->prenom }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($acte->declaration->mere)
                                    {{ $acte->declaration->mere->nom }}
                                    <span class="text-capitalize">{{ $acte->declaration->mere->prenom }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $acte->retrait->retirer_par }}</td>
                            <td><span class="font-monospace small">{{ $acte->retrait->telephone }}</span></td>
                            <td>{{ $acte->retrait->created_at?->format('d/m/Y à H:i') ?? '—' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div class="cni-callout cni-callout--info mb-0">
                <span class="cni-callout__icon" aria-hidden="true"><i class="fas fa-info-circle"></i></span>
                <div class="cni-callout__body">
                    <strong>Acte trouvé, sans enregistrement de retrait</strong>
                    <span class="cni-callout__sub">
                        N° acte <code>{{ $acte->niupp }}</code> — {{ $acte->declaration->enfant->nom }}
                        <span class="text-capitalize">{{ $acte->declaration->enfant->prenom }}</span>.
                        Si le retrait vient d’être effectué, les données peuvent prendre quelques instants à se mettre à jour.
                    </span>
                </div>
            </div>
            @can('module.acteNaissance.retrait.depuisConsultationCEC')
                @if($acte->signature_mairie)
                    <div class="mt-3 pt-2 border-top" style="border-color: var(--si-line, #e2e8e4) !important;">
                        <button type="button"
                                class="btn btn-warning text-white"
                                data-bs-toggle="modal"
                                data-bs-target="#modal-retrait-acte"
                                title="Enregistrer le retrait au guichet (habilitation CEC)">
                            <i class="fas fa-file-export me-1"></i>
                            Enregistrer le retrait
                        </button>
                        <p class="small text-muted mb-0 mt-2">
                            Action réservée aux agents disposant de l’habilitation «&nbsp;Enregistrer un retrait depuis la consultation&nbsp;» (CEC).
                        </p>
                    </div>
                @endif
            @endcan
        @endif
    </div>
</div>
