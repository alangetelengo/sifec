<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10pt; }
        h1 { font-size: 14pt; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 4px; text-align: left; }
        th { background: #eee; }
        .meta { margin-bottom: 8px; font-size: 9pt; color: #444; }
    </style>
</head>
<body>
    <h1>Historique des authentifications</h1>
    <p class="meta">Administration (code) : {{ $code }}</p>
    <p class="meta">Généré le {{ now()->format('d/m/Y H:i') }}</p>
    @if ($rows->isEmpty())
        <p>Aucune ligne enregistrée pour cette requête (table absente, vide ou critère non applicable).</p>
    @else
        <table>
            <thead>
                <tr>
                    @foreach (array_keys((array) $rows->first()) as $col)
                        <th>{{ $col }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $row)
                    <tr>
                        @foreach ((array) $row as $cell)
                            <td>{{ $cell }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
