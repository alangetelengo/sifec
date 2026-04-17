<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Carte du Congo — {{ $debut }} au {{ $fin }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; }
        h1 { font-size: 16px; margin: 0 0 8px; color: #1a2e26; }
        .muted { color: #555; font-size: 10px; margin-bottom: 14px; }
        h2 { font-size: 12px; margin: 14px 0 6px; border-bottom: 1px solid #ccc; padding-bottom: 3px; color: #0f5132; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #ccc; padding: 5px 6px; text-align: center; }
        th { background: #e8f0eb; font-weight: bold; }
        td:first-child, th:first-child { text-align: left; }
        .dep { font-weight: bold; color: #0f5132; }
    </style>
</head>
<body>
    <h1>Tableau de bord — Carte du Congo</h1>
    <p class="muted">Période du {{ \Carbon\Carbon::parse($debut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($fin)->format('d/m/Y') }} — Département : <span class="dep">{{ $departement_lib }}</span></p>

    <h2>Transcriptions hors territoire</h2>
    <table>
        <thead>
            <tr><th></th><th>Cumul période</th><th>Année en cours (dans période)</th><th>Mois en cours (dans période)</th></tr>
        </thead>
        <tbody>
            <tr>
                <td>Naissances</td>
                <td>{{ $transcriptions['naissance']['cumul'] ?? 0 }}</td>
                <td>{{ $transcriptions['naissance']['annee'] ?? 0 }}</td>
                <td>{{ $transcriptions['naissance']['mois'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Décès</td>
                <td>{{ $transcriptions['deces']['cumul'] ?? 0 }}</td>
                <td>{{ $transcriptions['deces']['annee'] ?? 0 }}</td>
                <td>{{ $transcriptions['deces']['mois'] ?? 0 }}</td>
            </tr>
        </tbody>
    </table>

    <h2>Synthèse nationale (actes)</h2>
    @php
        $natRows = [
            ['Cumulée (dans période)', $national['cumule'] ?? []],
            ['Année', $national['annee'] ?? []],
            ['Mois', $national['mois'] ?? []],
            ['Semaine', $national['semaine'] ?? []],
            ['Jour', $national['jour'] ?? []],
        ];
        $depRows = [
            ['Cumulée (dans période)', $departement['cumule'] ?? []],
            ['Année', $departement['annee'] ?? []],
            ['Mois', $departement['mois'] ?? []],
            ['Semaine', $departement['semaine'] ?? []],
            ['Jour', $departement['jour'] ?? []],
        ];
    @endphp
    <table>
        <thead>
            <tr>
                <th>Situation</th>
                <th>Naissance</th>
                <th>Mariage</th>
                <th>Divorce</th>
                <th>Décès</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($natRows as $nr)
                <tr>
                    <td>{{ $nr[0] }}</td>
                    <td>{{ data_get($nr[1], 'TOTALNAISSANCE', 0) }}</td>
                    <td>{{ data_get($nr[1], 'TOTALMARIAGE', 0) }}</td>
                    <td>{{ data_get($nr[1], 'TOTALDIVORCE', 0) }}</td>
                    <td>{{ data_get($nr[1], 'TOTALDECES', 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Synthèse département (actes)</h2>
    <table>
        <thead>
            <tr>
                <th>Situation</th>
                <th>Naissance</th>
                <th>Mariage</th>
                <th>Divorce</th>
                <th>Décès</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($depRows as $dr)
                <tr>
                    <td>{{ $dr[0] }}</td>
                    <td>{{ data_get($dr[1], 'TOTALNAISSANCE', 0) }}</td>
                    <td>{{ data_get($dr[1], 'TOTALMARIAGE', 0) }}</td>
                    <td>{{ data_get($dr[1], 'TOTALDIVORCE', 0) }}</td>
                    <td>{{ data_get($dr[1], 'TOTALDECES', 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="muted">Document généré par SIFEC le {{ now()->format('d/m/Y à H:i') }}.</p>
</body>
</html>
