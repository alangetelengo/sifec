<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Double Authentification — SIFEC</title>
</head>
<body style="margin:0;padding:0;background:#f4f6f8;font-family:'Segoe UI',Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f8;padding:30px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.10);max-width:600px;">

                {{-- ===== BANDEAU DRAPEAU CONGO ===== --}}
                <tr>
                    <td style="padding:0;">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="background:#009A44;height:6px;"></td>
                                <td style="background:#F7B731;height:6px;"></td>
                                <td style="background:#DC241F;height:6px;"></td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- ===== EN-TÊTE ===== --}}
                <tr>
                    <td style="padding:32px 40px 24px;background:{{ $action === 'enabled' ? 'linear-gradient(135deg,#009A44 0%,#007A35 100%)' : 'linear-gradient(135deg,#4a5568 0%,#2d3748 100%)' }};text-align:center;">
                        <div style="font-size:42px;margin-bottom:10px;">
                            {{ $action === 'enabled' ? '🔐' : '🔓' }}
                        </div>
                        <h1 style="color:#ffffff;font-size:22px;font-weight:700;margin:0 0 6px;">
                            {{ $action === 'enabled' ? 'Double Authentification Activée' : 'Double Authentification Désactivée' }}
                        </h1>
                        <p style="color:rgba(255,255,255,0.85);font-size:13px;margin:0;">
                            SIFEC — Système d'Information de l'État Civil
                        </p>
                    </td>
                </tr>

                {{-- ===== CORPS ===== --}}
                <tr>
                    <td style="padding:32px 40px;">

                        {{-- Salutation --}}
                        <p style="color:#2d3748;font-size:16px;margin:0 0 8px;">
                            Bonjour <strong>{{ $user->personne->prenom ?? '' }} {{ $user->personne->nom ?? 'Utilisateur' }}</strong>,
                        </p>

                        @if($action === 'enabled')
                        <p style="color:#4a5568;font-size:14px;line-height:1.7;margin:0 0 24px;">
                            Votre administrateur a <strong style="color:#009A44;">activé la double authentification (2FA)</strong>
                            sur votre compte SIFEC. À partir de maintenant, à chaque connexion vous devrez entrer
                            un code à 6 chiffres généré par l'application <strong>Google Authenticator</strong> sur votre téléphone.
                        </p>

                        {{-- === ÉTAPE 1 : Télécharger l'app === --}}
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:20px;">
                            <tr>
                                <td style="background:#f0faf5;border-left:4px solid #009A44;border-radius:0 8px 8px 0;padding:16px 20px;">
                                    <p style="color:#009A44;font-weight:700;font-size:13px;margin:0 0 8px;text-transform:uppercase;letter-spacing:0.5px;">
                                        📱 Étape 1 — Installez Google Authenticator
                                    </p>
                                    <p style="color:#4a5568;font-size:13px;margin:0 0 6px;">Téléchargez l'application gratuite sur votre téléphone :</p>
                                    <p style="margin:4px 0;">
                                        <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2"
                                           style="color:#009A44;font-size:12px;text-decoration:none;">
                                            📲 Android — Google Play Store
                                        </a>
                                    </p>
                                    <p style="margin:4px 0;">
                                        <a href="https://apps.apple.com/app/google-authenticator/id388497605"
                                           style="color:#009A44;font-size:12px;text-decoration:none;">
                                            📲 iPhone — Apple App Store
                                        </a>
                                    </p>
                                </td>
                            </tr>
                        </table>

                        {{-- === ÉTAPE 2 : Scanner le QR code === --}}
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:20px;">
                            <tr>
                                <td style="background:#fff8e1;border-left:4px solid #F7B731;border-radius:0 8px 8px 0;padding:16px 20px;">
                                    <p style="color:#856404;font-weight:700;font-size:13px;margin:0 0 10px;text-transform:uppercase;letter-spacing:0.5px;">
                                        📷 Étape 2 — Configurez votre application
                                    </p>
                                    <p style="color:#4a5568;font-size:13px;margin:0 0 14px;">
                                        Ouvrez <strong>Google Authenticator</strong>, appuyez sur <strong>+</strong>
                                        puis choisissez <em>"Scanner un code QR"</em> et scannez le QR code ci-dessous :
                                    </p>

                                    @if($qrCodeUrl)
                                    {{-- QR Code --}}
                                    <table width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td align="center" style="padding:16px 0;">
                                                <div style="display:inline-block;background:#ffffff;border:3px solid #009A44;border-radius:12px;padding:12px;">
                                                    <img src="{{ $qrCodeUrl }}"
                                                         alt="QR Code Google Authenticator"
                                                         width="200" height="200"
                                                         style="display:block;border-radius:4px;">
                                                </div>
                                                <p style="color:#6b7280;font-size:11px;margin:8px 0 0;font-style:italic;">
                                                    Scannez ce QR code avec Google Authenticator
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                    @endif

                                    {{-- Clé manuelle --}}
                                    @if($rawSecret)
                                    <p style="color:#4a5568;font-size:12px;margin:10px 0 6px;">
                                        Si vous ne pouvez pas scanner le QR code, choisissez
                                        <em>"Saisir une clé de configuration"</em> et entrez cette clé manuellement :
                                    </p>
                                    <div style="background:#ffffff;border:2px dashed #F7B731;border-radius:8px;padding:12px 16px;text-align:center;">
                                        <code style="font-size:18px;font-weight:700;color:#2d3748;letter-spacing:3px;font-family:Consolas,monospace;">
                                            {{ $rawSecret }}
                                        </code>
                                        <p style="color:#9ca3af;font-size:10px;margin:4px 0 0;font-style:italic;">
                                            Clé secrète TOTP — Ne la partagez pas
                                        </p>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                        </table>

                        {{-- === ÉTAPE 3 : Connexion === --}}
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                            <tr>
                                <td style="background:#fef2f2;border-left:4px solid #DC241F;border-radius:0 8px 8px 0;padding:16px 20px;">
                                    <p style="color:#991b1b;font-weight:700;font-size:13px;margin:0 0 8px;text-transform:uppercase;letter-spacing:0.5px;">
                                        ✅ Étape 3 — Lors de chaque connexion
                                    </p>
                                    <ol style="color:#4a5568;font-size:13px;margin:0;padding-left:18px;line-height:1.8;">
                                        <li>Entrez votre identifiant et mot de passe SIFEC</li>
                                        <li>Ouvrez <strong>Google Authenticator</strong> sur votre téléphone</li>
                                        <li>Entrez le code à <strong>6 chiffres</strong> affiché pour SIFEC</li>
                                        <li>Le code change toutes les <strong>30 secondes</strong></li>
                                    </ol>
                                </td>
                            </tr>
                        </table>

                        {{-- === Codes de récupération === --}}
                        @if(count($recoveryCodes) > 0)
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                            <tr>
                                <td style="background:#f8f9fa;border:1px solid #dee2e6;border-radius:8px;padding:18px 20px;">
                                    <p style="color:#2d3748;font-weight:700;font-size:13px;margin:0 0 6px;text-transform:uppercase;letter-spacing:0.5px;">
                                        🔑 Vos codes de récupération d'urgence
                                    </p>
                                    <p style="color:#6b7280;font-size:12px;margin:0 0 14px;">
                                        Conservez-les dans un endroit sûr. Chacun ne peut être utilisé <strong>qu'une seule fois</strong>.
                                        Ils vous permettront de vous connecter si vous perdez l'accès à votre téléphone.
                                        <strong>Un fichier est joint à cet email.</strong>
                                    </p>
                                    <table width="100%" cellpadding="4" cellspacing="0">
                                        @foreach(array_chunk($recoveryCodes, 2) as $pair)
                                        <tr>
                                            @foreach($pair as $code)
                                            <td width="50%" style="padding:4px 6px;">
                                                <code style="background:#ffffff;border:1px solid #dee2e6;border-radius:4px;padding:5px 10px;font-size:13px;font-family:Consolas,monospace;color:#2d3748;display:block;text-align:center;letter-spacing:2px;">
                                                    {{ $code }}
                                                </code>
                                            </td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </table>
                                </td>
                            </tr>
                        </table>
                        @endif

                        @else
                        {{-- Désactivation --}}
                        <p style="color:#4a5568;font-size:14px;line-height:1.7;margin:0 0 24px;">
                            La double authentification (2FA) a été <strong style="color:#DC241F;">désactivée</strong>
                            sur votre compte SIFEC par votre administrateur.
                            Vous pouvez désormais vous connecter avec uniquement votre identifiant et mot de passe.
                        </p>
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                            <tr>
                                <td style="background:#fff8e1;border-left:4px solid #F7B731;border-radius:0 8px 8px 0;padding:14px 18px;">
                                    <p style="color:#856404;font-size:13px;margin:0;">
                                        ⚠️ Si vous n'êtes pas à l'origine de cette action, contactez immédiatement votre administrateur SIFEC.
                                    </p>
                                </td>
                            </tr>
                        </table>
                        @endif

                    </td>
                </tr>

                {{-- ===== PIED DE PAGE ===== --}}
                <tr>
                    <td style="padding:20px 40px;background:#f8f9fa;border-top:1px solid #e5e7eb;text-align:center;">
                        <p style="color:#9ca3af;font-size:11px;margin:0 0 4px;">
                            Cet email a été envoyé automatiquement par le système SIFEC. Ne pas répondre.
                        </p>
                        <p style="color:#9ca3af;font-size:11px;margin:0;">
                            République du Congo — Ministère de l'Intérieur — Direction Générale de l'Administration du Territoire
                        </p>
                    </td>
                </tr>

                {{-- Bas du drapeau --}}
                <tr>
                    <td style="padding:0;">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="background:#DC241F;height:4px;"></td>
                                <td style="background:#F7B731;height:4px;"></td>
                                <td style="background:#009A44;height:4px;"></td>
                            </tr>
                        </table>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>
