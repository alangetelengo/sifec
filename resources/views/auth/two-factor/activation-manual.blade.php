<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activation 2FA - SIFEC - {{ $email }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .content {
            padding: 40px;
        }

        .section {
            margin-bottom: 40px;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 15px;
            border-left: 5px solid #667eea;
        }

        .section-title {
            font-size: 1.8em;
            color: #667eea;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .qr-container {
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        #qrcode {
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            display: inline-block;
        }

        .secret-box {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }

        .secret-value {
            font-size: 2em;
            font-weight: bold;
            font-family: 'Courier New', monospace;
            letter-spacing: 5px;
            color: #333;
            margin: 15px 0;
            user-select: all;
            word-break: break-all;
        }

        .codes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .code-item {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.2s;
        }

        .code-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .code-number {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 10px;
        }

        .code-value {
            font-size: 1.5em;
            font-weight: bold;
            font-family: 'Courier New', monospace;
            color: #667eea;
            letter-spacing: 2px;
        }

        .button {
            display: inline-block;
            padding: 15px 30px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1em;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            margin: 5px;
        }

        .button:hover {
            background: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .button-success {
            background: #28a745;
        }

        .button-success:hover {
            background: #218838;
        }

        .alert {
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }

        .alert-warning {
            background: #fff3cd;
            border-left: 5px solid #ffc107;
            color: #856404;
        }

        @media print {
            body { background: white; }
            .button { display: none; }
            .container { box-shadow: none; }
        }

        .success-message {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="success-message" id="successMessage">✅ Copié dans le presse-papier !</div>

    <div class="container">
        <div class="header">
            <h1>🔐 Activation Double Authentification (2FA)</h1>
            <p><strong>SIFEC</strong></p>
            <p style="margin-top: 10px;">Utilisateur : <strong>{{ $email }}</strong></p>
        </div>

        <div class="content">
            <div class="section">
                <h2 class="section-title"><span>📱</span> Scanner le QR Code</h2>
                <div class="qr-container">
                    <div id="qrcode"></div>
                </div>
            </div>

            <div class="section">
                <h2 class="section-title"><span>🔑</span> Secret Manuel</h2>
                <div class="secret-box">
                    <p><strong>Clé Secrète :</strong></p>
                    <div class="secret-value" id="secretValue">{{ $secret }}</div>
                    <button class="button button-success" onclick="copySecret()">📋 Copier</button>
                </div>
            </div>

            <div class="section">
                <h2 class="section-title"><span>🔐</span> Codes de Récupération</h2>
                <div class="alert alert-warning">
                    <strong>⚠️ IMPORTANT :</strong> Conservez ces codes précieusement !
                </div>
                <div class="codes-grid">
                    @foreach($recoveryCodes as $index => $code)
                    <div class="code-item">
                        <div class="code-number">Code {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                        <div class="code-value">{{ $code }}</div>
                    </div>
                    @endforeach
                </div>
                <div style="text-align: center; margin-top: 30px;">
                    <button class="button button-success" onclick="copyCodes()">📋 Copier tous les codes</button>
                    <button class="button" onclick="window.print()">🖨️ Imprimer</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        const qrCodeUrl = '{{ $qrCodeUrl }}';
        new QRCode(document.getElementById("qrcode"), {
            text: qrCodeUrl,
            width: 280,
            height: 280,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });

        function copySecret() {
            navigator.clipboard.writeText('{{ $secret }}').then(() => showSuccess());
        }

        function copyCodes() {
            const codes = @json($recoveryCodes);
            let text = 'CODES DE RÉCUPÉRATION SIFEC\n{{ $email }}\n\n';
            codes.forEach((code, index) => {
                text += (index + 1).toString().padStart(2, '0') + '. ' + code + '\n';
            });
            navigator.clipboard.writeText(text).then(() => showSuccess());
        }

        function showSuccess() {
            const message = document.getElementById('successMessage');
            message.style.display = 'block';
            setTimeout(() => message.style.display = 'none', 3000);
        }
    </script>
</body>
</html>

