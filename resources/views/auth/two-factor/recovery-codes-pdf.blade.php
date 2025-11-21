<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Codes de Récupération 2FA</title>
    <style>
        @page {
            margin: 20mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #007bff;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .warning {
            background: #fff3cd;
            border: 2px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .warning h3 {
            margin-top: 0;
            color: #856404;
        }
        .warning ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .warning li {
            margin: 5px 0;
        }
        .codes-container {
            margin-top: 30px;
        }
        .codes-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 20px;
        }
        .code-item {
            border: 2px solid #ddd;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
            background: #f8f9fa;
        }
        .code-number {
            font-weight: bold;
            color: #007bff;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .code-value {
            font-family: 'Courier New', monospace;
            font-size: 18px;
            font-weight: bold;
            color: #333;
            letter-spacing: 2px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        .user-info {
            background: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .user-info strong {
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔐 Codes de Récupération</h1>
        <h2>Double Authentification (2FA)</h2>
        <p>Générés le : {{ $date }}</p>
    </div>

    <div class="user-info">
        <strong>Utilisateur :</strong> {{ $user->email }}<br>
        <strong>Code utilisateur :</strong> {{ $user->code_user }}
    </div>

    <div class="warning">
        <h3>⚠️ IMPORTANT</h3>
        <ul>
            <li><strong>Sauvegardez ces codes dans un endroit sûr</strong></li>
            <li>Chaque code ne peut être utilisé <strong>qu'une seule fois</strong></li>
            <li>Utilisez-les si vous perdez l'accès à votre téléphone ou à votre application d'authentification</li>
            <li><strong>NE PARTAGEZ JAMAIS CES CODES avec quiconque !</strong></li>
            <li>Conservez une copie imprimée dans un endroit sûr</li>
        </ul>
    </div>

    <div class="codes-container">
        <h3 style="text-align: center; color: #007bff; margin-bottom: 20px;">Vos Codes de Récupération</h3>
        <div class="codes-grid">
            @foreach($recoveryCodes as $index => $code)
                <div class="code-item">
                    <div class="code-number">Code {{ $index + 1 }}</div>
                    <div class="code-value">{{ $code }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="footer">
        <p>Document généré par SIFEC - Système d'Information de l'État Civil</p>
        <p>Conservez ce document en lieu sûr</p>
    </div>
</body>
</html>

