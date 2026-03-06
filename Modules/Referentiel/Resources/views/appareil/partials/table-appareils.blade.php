@php
    $typeIcons = [
        'ordinateur' => ['icon' => 'fa-desktop',    'class' => 'ordinateur', 'label' => 'Ordinateur'],
        'tablette'   => ['icon' => 'fa-tablet-alt', 'class' => 'tablette',   'label' => 'Tablette'],
        'smartphone' => ['icon' => 'fa-mobile-alt', 'class' => 'smartphone', 'label' => 'Smartphone'],
        'autre'      => ['icon' => 'fa-microchip',  'class' => 'autre',      'label' => 'Autre'],
    ];
@endphp

@if ($appareils->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="fas fa-laptop fa-3x mb-3" style="opacity:.3;"></i>
        <p class="mb-0">Aucun appareil enregistré.</p>
        <small>Cliquez sur « Enregistrer un appareil » pour commencer.</small>
    </div>
@else
<div class="table-responsive">
    <table id="table-appareils" class="display table table-hover table-sm w-100">
        <thead>
            <tr>
                <th>#</th>
                <th>Type</th>
                <th>Nom de l'appareil</th>
                <th>Adresse MAC</th>
                <th>Institution</th>
                <th>Enregistré le</th>
                <th>Statut</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($appareils as $i => $ap)
                @php
                    $ti = $typeIcons[$ap->type_appareil] ?? $typeIcons['autre'];
                @endphp
                <tr>
                    <td class="text-muted small">{{ $i + 1 }}</td>

                    <td class="text-center">
                        <i class="fas {{ $ti['icon'] }} app-type-icon {{ $ti['class'] }}" title="{{ $ti['label'] }}"></i>
                        <div style="font-size:.7rem;color:#888;">{{ $ti['label'] }}</div>
                    </td>

                    <td>
                        <div class="fw-semibold">{{ $ap->nom_appareil }}</div>
                        <div style="font-size:.75rem;color:#888;">{{ $ap->code_appareil }}</div>
                    </td>

                    <td>
                        <span class="mac-address">{{ $ap->adresse_mac }}</span>
                    </td>

                    <td>
                        @if ($ap->institution)
                            <span class="badge bg-light text-dark border" style="font-size:.78rem;">
                                <i class="fas fa-building me-1 text-success"></i>{{ $ap->institution->lib_institution }}
                            </span>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>

                    <td class="small">
                        {{ \Carbon\Carbon::parse($ap->date_enregistrement)->format('d/m/Y H:i') }}
                    </td>

                    <td class="text-center">
                        @if ($ap->statut)
                            <span class="badge-actif"><i class="fas fa-check-circle me-1"></i>Actif</span>
                        @else
                            <span class="badge-inactif"><i class="fas fa-ban me-1"></i>Désactivé</span>
                        @endif
                    </td>

                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center flex-wrap">
                            {{-- Détail --}}
                            <button type="button"
                                class="btn btn-sm btn-info btn-detail-appareil"
                                style="background:#17a2b8;color:#fff;border:none;"
                                title="Voir le détail"
                                data-code="{{ $ap->code_appareil }}"
                                data-nom="{{ $ap->nom_appareil }}"
                                data-mac="{{ $ap->adresse_mac }}"
                                data-type="{{ $ti['label'] }}"
                                data-institution="{{ $ap->institution?->lib_institution }}"
                                data-statut="{{ $ap->statut ? '1' : '0' }}"
                                data-date="{{ \Carbon\Carbon::parse($ap->date_enregistrement)->format('d/m/Y à H:i') }}">
                                <i class="fas fa-eye"></i>
                            </button>

                            {{-- Modifier --}}
                            <button type="button"
                                class="btn btn-sm btn-edit"
                                title="Modifier"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEdit{{ $ap->code_appareil }}">
                                <i class="fas fa-edit"></i>
                            </button>

                            {{-- Toggle statut --}}
                            <button type="button"
                                class="btn btn-sm btn-toggle {{ $ap->statut ? 'on' : 'off' }} btn-toggle-statut"
                                title="{{ $ap->statut ? 'Désactiver' : 'Activer' }}"
                                data-code="{{ $ap->code_appareil }}"
                                data-nom="{{ $ap->nom_appareil }}"
                                data-statut="{{ $ap->statut ? '1' : '0' }}">
                                <i class="fas {{ $ap->statut ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                            </button>

                            {{-- Supprimer --}}
                            <button type="button"
                                class="btn btn-sm btn-delete btn-delete-appareil"
                                title="Supprimer"
                                data-code="{{ $ap->code_appareil }}"
                                data-nom="{{ $ap->nom_appareil }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
