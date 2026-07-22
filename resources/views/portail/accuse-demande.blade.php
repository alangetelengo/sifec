<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accusé de réception — {{ $demande->code_demande_document }}</title>
    <style>
        :root { color-scheme: light; }
        body {
            margin: 0;
            font-family: Georgia, "Times New Roman", serif;
            background: linear-gradient(160deg, #eef2f6 0%, #d9e2ec 100%);
            color: #1a2332;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .card {
            width: 100%;
            max-width: 560px;
            background: #fff;
            border: 1px solid #c5d0db;
            box-shadow: 0 12px 32px rgba(26, 35, 50, 0.12);
            padding: 28px 32px 32px;
        }
        .brand {
            font-size: 13px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #3d5a80;
            margin-bottom: 8px;
        }
        h1 {
            font-size: 22px;
            margin: 0 0 8px;
            font-weight: 700;
        }
        .lead {
            margin: 0 0 22px;
            color: #4a5568;
            font-size: 15px;
            line-height: 1.45;
        }
        .ok {
            display: inline-block;
            background: #e8f5e9;
            color: #1b5e20;
            border: 1px solid #a5d6a7;
            padding: 6px 12px;
            font-size: 13px;
            margin-bottom: 18px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        th, td {
            text-align: left;
            padding: 10px 0;
            border-bottom: 1px solid #e8edf2;
            vertical-align: top;
        }
        th {
            width: 42%;
            color: #5a6a7a;
            font-weight: 600;
        }
        .code {
            font-family: Consolas, Monaco, monospace;
            font-size: 15px;
            letter-spacing: 0.04em;
        }
        .footer {
            margin-top: 22px;
            font-size: 12px;
            color: #6b7c8d;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="brand">SIFEC — E-Services</div>
        <h1>Demande enregistrée</h1>
        <p class="lead">Votre demande a bien été prise en compte. Conservez le code ci-dessous pour le suivi.</p>
        <div class="ok">Enregistrement réussi</div>
        <table>
            <tr>
                <th>Code demande</th>
                <td class="code">{{ $demande->code_demande_document }}</td>
            </tr>
            <tr>
                <th>Type de document</th>
                <td>{{ $demande->getLibelleTypeDocument() }} — {{ $demande->getLibelleTypeActe() }}</td>
            </tr>
            <tr>
                <th>Numéro d'acte</th>
                <td>{{ $demande->numero_acte ?: '—' }}</td>
            </tr>
            <tr>
                <th>Demandeur</th>
                <td>{{ trim(($demande->nom_demandeur ?? '').' '.($demande->prenom_demandeur ?? '')) ?: '—' }}</td>
            </tr>
            @if(filled($demande->email_demandeur))
            <tr>
                <th>E-mail</th>
                <td>{{ $demande->email_demandeur }}</td>
            </tr>
            @endif
            <tr>
                <th>Statut</th>
                <td>{{ $demande->statut ?: '—' }}</td>
            </tr>
            <tr>
                <th>Montant</th>
                <td>{{ $demande->prix !== null ? number_format((float) $demande->prix, 0, ',', ' ').' FCFA' : '—' }}</td>
            </tr>
            <tr>
                <th>Date</th>
                <td>{{ $demande->date_demande ? $demande->date_demande->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</td>
            </tr>
        </table>
        <p class="footer">
            @if(filled($demande->email_demandeur))
                Votre demande sera traitée au centre d'état civil sélectionné.
                Une fois le document signé électroniquement, le PDF vous sera envoyé en pièce jointe à
                <strong>{{ $demande->email_demandeur }}</strong>.
            @else
                Présentez-vous au centre d'état civil sélectionné pour la suite du traitement.
                Un document officiel vous sera remis après validation.
            @endif
        </p>
    </div>
</body>
</html>
