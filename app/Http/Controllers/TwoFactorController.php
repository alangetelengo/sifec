<?php

namespace App\Http\Controllers;

use BaconQrCode\Writer;
use Illuminate\Http\Request;
use App\Models\UserAuditTrail;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;

class TwoFactorController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->middleware('auth')->except(['showVerify', 'verify', 'verifyRecoveryCode']);
        $this->middleware(function ($request, $next) {
            if (! Gate::allows('module.menus.administration')) {
                abort(403, 'La configuration de la double authentification est réservée aux administrateurs.');
            }

            return $next($request);
        })->only([
            'index',
            'enable',
            'getCsrfToken',
            'confirm',
            'showRecoveryCodes',
            'downloadRecoveryCodesPdf',
            'printRecoveryCodesPdf',
            'regenerateRecoveryCodes',
            'disable',
        ]);
        $this->google2fa = new Google2FA();
    }

    /**
     * Afficher la page de configuration 2FA
     */
    public function index()
    {
        $user = auth()->user();

        return view('auth.two-factor.index', [
            'user' => $user,
            'twoFactorEnabled' => $user->hasTwoFactorEnabled()
        ]);
    }

    /**
     * Activer la 2FA
     */
    public function enable(Request $request)
    {
        // Vérifier l'authentification avant tout
        $user = auth()->user();

        if (!$user) {
            Log::channel('sifec')->error('=== ENABLE 2FA - UTILISATEUR NON AUTHENTIFIÉ ===', [
                'session_id' => $request->session()?->getId(),
                'url' => $request->fullUrl(),
            ]);

            return redirect()->route('login')->with('error', 'Votre session a expiré. Veuillez vous reconnecter.');
        }

        Log::channel('sifec')->info('=== ENABLE 2FA - PAGE CHARGÉE ===', [
            'user_code' => $user->code_user,
            'user_id' => $user->id ?? 'null (utilise code_user)',
            'user_email' => $user->email,
            'user_authenticated' => auth()->check(),
            'auth_id' => auth()->id(),
            'session_id' => $request->session()?->getId(),
            'csrf_token' => csrf_token(),
            'session_token' => $request->session()?->token(),
            'url' => $request->fullUrl(),
            'referer' => $request->header('referer'),
            'has_reset_param' => $request->has('reset'),
        ]);

        // $user déjà défini plus haut avec vérification d'authentification

        Log::channel('sifec')->info('=== ENABLE 2FA - ÉTAT INITIAL ===', [
            'user_code' => $user->code_user,
            'user_email' => $user->email,
            'user_authenticated' => auth()->check(),
            'auth_id' => auth()->id(),
            'google2fa_enabled' => $user->google2fa_enabled,
            'has_secret' => !empty($user->google2fa_secret),
            'has_recovery_codes' => !empty($user->recovery_codes),
            'two_factor_verified_at' => $user->two_factor_verified_at,
        ]);

        // IMPORTANT : Ne générer un nouveau secret QUE si :
        // 1. Le paramètre ?reset=1 est présent (demande explicite de réinitialisation)
        // 2. OU le secret n'existe pas (première activation)
        //
        // NE PAS générer un nouveau secret si la 2FA est désactivée mais qu'un secret existe déjà,
        // car l'utilisateur a peut-être déjà scanné le QR code et ne doit pas perdre sa configuration
        $shouldGenerateNewSecret = $request->has('reset')
            || empty($user->google2fa_secret);

        if ($shouldGenerateNewSecret) {
            $oldSecretExists = !empty($user->google2fa_secret);

            Log::channel('sifec')->info('=== ENABLE 2FA - GÉNÉRATION NOUVEAU SECRET ===', [
                'user_code' => $user->code_user,
                'user_email' => $user->email,
                'user_authenticated' => auth()->check(),
                'auth_id' => auth()->id(),
                'reason' => $request->has('reset') ? 'reset_requested' : 'no_secret',
                'old_secret_exists' => $oldSecretExists,
                'google2fa_enabled' => $user->google2fa_enabled,
            ]);

            // Générer un nouveau secret
            $secret = $this->google2fa->generateSecretKey();
            $user->google2fa_secret = encrypt($secret);

            // Si c'est une réinitialisation, désactiver la 2FA temporairement
            // Elle sera réactivée après confirmation du code
            if ($request->has('reset')) {
                $user->google2fa_enabled = false;
                $user->recovery_codes = null;
                $user->two_factor_verified_at = null;

                Log::channel('sifec')->info('=== ENABLE 2FA - RÉINITIALISATION COMPLÈTE ===', [
                    'user_id' => $user->id,
                    '2fa_reset' => true,
                ]);
            }

            $user->save();

            Log::channel('sifec')->info('=== ENABLE 2FA - NOUVEAU SECRET GÉNÉRÉ ===', [
                'user_code' => $user->code_user,
                'user_email' => $user->email,
                'user_authenticated' => auth()->check(),
                'auth_id' => auth()->id(),
                'secret_length' => strlen($secret),
                'google2fa_enabled' => $user->google2fa_enabled,
            ]);
        } else {
            // Récupérer le secret existant pour le log
            $existingSecret = decrypt($user->google2fa_secret);

            Log::channel('sifec')->info('=== ENABLE 2FA - RÉUTILISATION SECRET EXISTANT ===', [
                'user_code' => $user->code_user,
                'user_email' => $user->email,
                'user_authenticated' => auth()->check(),
                'auth_id' => auth()->id(),
                'google2fa_enabled' => $user->google2fa_enabled,
                'secret_length' => strlen($existingSecret),
                'secret_preview' => substr($existingSecret, 0, 10) . '...' . substr($existingSecret, -5),
                'reason' => 'secret_exists_keep_for_activation',
            ]);
        }

        // Récupérer le secret pour générer le QR Code
        $secret = decrypt($user->google2fa_secret);

        Log::channel('sifec')->info('=== ENABLE 2FA - GÉNÉRATION QR CODE ===', [
            'user_code' => $user->code_user,
            'user_email' => $user->email,
            'user_authenticated' => auth()->check(),
            'auth_id' => auth()->id(),
            'secret_length' => strlen($secret),
            'secret_preview' => substr($secret, 0, 10) . '...' . substr($secret, -5),
            'app_name' => config('app.name'),
        ]);

        // Générer le QR Code (libellé = e-mail ciblé pour la 2FA, pro prioritaire)
        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->twoFactorAccountLabel(),
            $secret
        );

        Log::channel('sifec')->info('=== ENABLE 2FA - QR CODE URL GÉNÉRÉ ===', [
            'user_id' => $user->id,
            'qr_code_url_preview' => substr($qrCodeUrl, 0, 100) . '...',
        ]);

        $writer = new Writer(
            new ImageRenderer(
                new RendererStyle(200),
                new SvgImageBackEnd()
            )
        );

        $qrCodeImage = base64_encode($writer->writeString($qrCodeUrl));

        return view('auth.two-factor.enable', [
            'qrCodeImage' => $qrCodeImage,
            'secret' => decrypt($user->google2fa_secret)
        ]);
    }

    /**
     * Obtenir un nouveau token CSRF
     */
    public function getCsrfToken(Request $request)
    {
        $oldToken = $request->session()?->token();
        $newToken = csrf_token();

        $user = auth()->user();
        Log::channel('sifec')->info('=== GET CSRF TOKEN ===', [
            'user_code' => $user?->code_user ?? 'null',
            'user_email' => $user?->email ?? 'null',
            'auth_check' => auth()->check(),
            'auth_id' => auth()->id(),
            'session_id' => $request->session()?->getId(),
            'old_token' => $oldToken,
            'new_token' => $newToken,
            'tokens_match' => $oldToken === $newToken,
            'url' => $request->fullUrl(),
            'referer' => $request->header('referer'),
        ]);

        return response()->json([
            'token' => $newToken
        ]);
    }

    /**
     * Confirmer l'activation de la 2FA
     */
    public function confirm(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            Log::channel('sifec')->error('=== CONFIRM 2FA - UTILISATEUR NON AUTHENTIFIÉ ===', [
                'session_id' => $request->session()?->getId(),
                'has_session' => $request->hasSession(),
                'auth_check' => auth()->check(),
                'url' => $request->fullUrl(),
            ]);

            return redirect()->route('login')->with('error', 'Votre session a expiré. Veuillez vous reconnecter.');
        }

        Log::channel('sifec')->info('=== CONFIRM 2FA - MÉTHODE APPELÉE ===', [
            'user_code' => $user->code_user,
            'user_email' => $user->email,
            'auth_check' => auth()->check(),
            'auth_id' => auth()->id(),
            'session_id' => $request->session()?->getId(),
            'has_session' => $request->hasSession(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'all_input' => $request->all(),
            'token_in_request' => $request->input('_token'),
            'session_token' => $request->session()?->token(),
            'csrf_token_current' => csrf_token(),
            'tokens_match' => $request->input('_token') === $request->session()?->token(),
            'referer' => $request->header('referer'),
            'user_agent' => $request->header('user-agent'),
            'ip' => $request->ip(),
        ]);

        // Vérifier à nouveau l'authentification avant la validation
        if (!auth()->check()) {
            Log::channel('sifec')->error('=== CONFIRM 2FA - DÉCONNEXION AVANT VALIDATION ===', [
                'session_id' => $request->session()?->getId(),
                'has_session' => $request->hasSession(),
                'url' => $request->fullUrl(),
            ]);

            return redirect()->route('login')->with('error', 'Votre session a expiré. Veuillez vous reconnecter.');
        }

        try {
        $request->validate([
            'one_time_password' => 'required|numeric|digits:6'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('sifec')->warning('=== CONFIRM 2FA - ERREUR DE VALIDATION ===', [
                'user_code' => $user->code_user,
                'errors' => $e->errors(),
                'input' => $request->all(),
            ]);

            // Vérifier que l'utilisateur est toujours authentifié
            if (!auth()->check()) {
                Log::channel('sifec')->error('=== CONFIRM 2FA - DÉCONNEXION APRÈS ERREUR VALIDATION ===', [
                    'session_id' => $request->session()?->getId(),
                ]);

                return redirect()->route('login')->with('error', 'Votre session a expiré. Veuillez vous reconnecter.');
            }

            throw $e; // Relancer l'exception pour le comportement normal
        }

        // Vérifier à nouveau l'authentification après la validation
        if (!auth()->check()) {
            Log::channel('sifec')->error('=== CONFIRM 2FA - DÉCONNEXION APRÈS VALIDATION ===', [
                'session_id' => $request->session()?->getId(),
            ]);

            return redirect()->route('login')->with('error', 'Votre session a expiré. Veuillez vous reconnecter.');
        }

        // $user déjà défini plus haut avec vérification

        Log::channel('sifec')->info('=== CONFIRM 2FA - VALIDATION RÉUSSIE ===', [
            'user_code' => $user->code_user,
            'auth_check' => auth()->check(),
            'one_time_password' => $request->one_time_password,
        ]);

        if (empty($user->google2fa_secret)) {
            Log::channel('sifec')->error('=== CONFIRM 2FA - PAS DE SECRET ===', [
                'user_code' => $user->code_user,
                'user_email' => $user->email,
            ]);

            toastr()->error("Aucun secret 2FA trouvé. Veuillez reconfigurer la 2FA.");
            return redirect()->route('two-factor.enable', ['reset' => 1]);
        }

        $secret = decrypt($user->google2fa_secret);

        Log::channel('sifec')->info('=== CONFIRM 2FA - VÉRIFICATION DU CODE ===', [
            'user_code' => $user->code_user,
            'user_email' => $user->email,
            'auth_check' => auth()->check(),
            'has_secret' => !empty($secret),
            'secret_length' => strlen($secret),
            'secret_preview' => !empty($secret) ? (substr($secret, 0, 10) . '...' . substr($secret, -5)) : 'empty',
            'code_received' => $request->one_time_password,
            'session_id' => $request->session()?->getId(),
        ]);

        // Vérifier à nouveau l'authentification avant la vérification du code
        if (!auth()->check()) {
            Log::channel('sifec')->error('=== CONFIRM 2FA - DÉCONNEXION AVANT VÉRIFICATION CODE ===', [
                'user_code' => $user->code_user,
                'session_id' => $request->session()?->getId(),
            ]);

            return redirect()->route('login')->with('error', 'Votre session a expiré. Veuillez vous reconnecter.');
        }

        // Vérifier le code avec une fenêtre de tolérance de 2 périodes (60 secondes)
        $valid = $this->google2fa->verifyKey($secret, $request->one_time_password, 2);

        Log::channel('sifec')->info('=== CONFIRM 2FA - RÉSULTAT VÉRIFICATION ===', [
            'user_code' => $user->code_user,
            'code_valid' => $valid,
            'code_received' => $request->one_time_password,
            'secret_preview' => substr($secret, 0, 10) . '...' . substr($secret, -5),
            'tolerance_window' => 2,
        ]);

        // Si le code est invalide, tester avec une fenêtre plus large pour debug
        if (!$valid) {
            $validWindow1 = $this->google2fa->verifyKey($secret, $request->one_time_password, 1);
            $validWindow2 = $this->google2fa->verifyKey($secret, $request->one_time_password, 3);
            $validWindow4 = $this->google2fa->verifyKey($secret, $request->one_time_password, 4);

            Log::channel('sifec')->warning('=== CONFIRM 2FA - TEST AVEC DIFFÉRENTES FENÊTRES ===', [
                'user_code' => $user->code_user,
                'code_received' => $request->one_time_password,
                'valid_window_1' => $validWindow1,
                'valid_window_2' => $validWindow2,
                'valid_window_4' => $validWindow4,
                'secret_preview' => substr($secret, 0, 10) . '...' . substr($secret, -5),
            ]);

            // Si ça fonctionne avec une fenêtre plus large, accepter quand même
            if ($validWindow4) {
                Log::channel('sifec')->info('=== CONFIRM 2FA - CODE VALIDÉ AVEC FENÊTRE ÉLARGIE ===', [
                    'user_code' => $user->code_user,
                    'code_received' => $request->one_time_password,
                ]);
                $valid = true;
            }
        }

        if ($valid) {
            // Vérifier à nouveau l'authentification avant de sauvegarder
            $currentUser = auth()->user();
            if (!$currentUser || $currentUser->code_user !== $user->code_user) {
                Log::channel('sifec')->error('=== CONFIRM 2FA - DÉCONNEXION OU CHANGEMENT UTILISATEUR ===', [
                    'user_code_original' => $user->code_user,
                    'user_code_current' => $currentUser?->code_user ?? 'null',
                    'auth_check' => auth()->check(),
                    'session_id' => $request->session()?->getId(),
                ]);

                return redirect()->route('login')->with('error', 'Votre session a expiré. Veuillez vous reconnecter.');
            }

            $user->google2fa_enabled = true;
            $user->two_factor_verified_at = now();
            $user->save();

            Log::channel('sifec')->info('=== CONFIRM 2FA - ACTIVATION RÉUSSIE ===', [
                'user_code' => $user->code_user,
                'user_email' => $user->email,
                'auth_check' => auth()->check(),
                'google2fa_enabled' => $user->google2fa_enabled,
                'two_factor_verified_at' => $user->two_factor_verified_at,
            ]);

            // Générer les codes de récupération
            $recoveryCodes = $user->generateRecoveryCodes();

            Log::channel('sifec')->info('=== CONFIRM 2FA - CODES DE RÉCUPÉRATION GÉNÉRÉS ===', [
                'user_id' => $user->id,
                'codes_count' => count($recoveryCodes),
            ]);

            // Audit trail pour activation 2FA
            UserAuditTrail::log($user->code_user, '2fa_enabled', "Double authentification activée");

            toastr()->success("Double authentification activée avec succès!");

            return redirect()->route('two-factor.recovery-codes')
                ->with('recoveryCodes', $recoveryCodes);
        }

        // Vérifier à nouveau l'authentification avant de rediriger
        if (!auth()->check()) {
            Log::channel('sifec')->error('=== CONFIRM 2FA - DÉCONNEXION AVANT REDIRECTION CODE INVALIDE ===', [
                'user_code' => $user->code_user,
                'session_id' => $request->session()?->getId(),
                'code_received' => $request->one_time_password,
            ]);

            return redirect()->route('login')->with('error', 'Votre session a expiré. Veuillez vous reconnecter.');
        }

        Log::channel('sifec')->warning('=== CONFIRM 2FA - CODE INVALIDE ===', [
            'user_code' => $user->code_user,
            'code_received' => $request->one_time_password,
            'auth_check' => auth()->check(),
            'session_id' => $request->session()?->getId(),
        ]);

        toastr()->error("Code invalide. Veuillez réessayer.");

        // Utiliser une redirection explicite au lieu de back() pour éviter les problèmes de session
        Log::channel('sifec')->info('=== CONFIRM 2FA - REDIRECTION VERS ENABLE (code invalide) ===', [
            'user_code' => $user->code_user,
            'auth_check' => auth()->check(),
        ]);

        // Passer un flag pour afficher le message d'aide dans la vue
        return redirect()->route('two-factor.enable')->with('code_invalid', true);
    }

    /**
     * Afficher les codes de récupération
     */
    public function showRecoveryCodes()
    {
        $user = auth()->user();
        $recoveryCodes = session('recoveryCodes', $user->getRecoveryCodes());

        return view('auth.two-factor.recovery-codes', [
            'recoveryCodes' => $recoveryCodes
        ]);
    }

    /**
     * Générer un PDF des codes de récupération
     */
    public function downloadRecoveryCodesPdf()
    {
        $user = auth()->user();
        $recoveryCodes = session('recoveryCodes', $user->getRecoveryCodes());

        if (empty($recoveryCodes)) {
            toastr()->error('Aucun code de récupération disponible.');
            return redirect()->route('two-factor.index');
        }

        $pdf = DomPDF::loadView('auth.two-factor.recovery-codes-pdf', [
            'recoveryCodes' => $recoveryCodes,
            'user' => $user,
            'date' => now()->format('d/m/Y à H:i:s')
        ]);

        $filename = 'codes-recuperation-2fa-' . $user->code_user . '-' . date('Ymd-His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Afficher le PDF des codes de récupération pour impression
     */
    public function printRecoveryCodesPdf()
    {
        $user = auth()->user();
        $recoveryCodes = session('recoveryCodes', $user->getRecoveryCodes());

        if (empty($recoveryCodes)) {
            toastr()->error('Aucun code de récupération disponible.');
            return redirect()->route('two-factor.index');
        }

        $pdf = DomPDF::loadView('auth.two-factor.recovery-codes-pdf', [
            'recoveryCodes' => $recoveryCodes,
            'user' => $user,
            'date' => now()->format('d/m/Y à H:i:s')
        ]);

        $filename = 'codes-recuperation-2fa-' . $user->code_user . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Régénérer les codes de récupération
     */
    public function regenerateRecoveryCodes(Request $request)
    {
        $user = auth()->user();

        if (!$user->hasTwoFactorEnabled()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => "La 2FA doit être activée d'abord."
                ], 400);
            }
            toastr()->error("La 2FA doit être activée d'abord.");
            return back();
        }

        $recoveryCodes = $user->generateRecoveryCodes();

        // Audit trail pour régénération des codes
        UserAuditTrail::log($user->code_user, 'recovery_codes_regenerated', "Codes de récupération régénérés");

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Codes de récupération régénérés avec succès!",
                'recoveryCodes' => $recoveryCodes
            ]);
        }

        toastr()->success("Codes de récupération régénérés avec succès!");

        return redirect()->route('two-factor.recovery-codes')
            ->with('recoveryCodes', $recoveryCodes);
    }

    /**
     * Désactiver la 2FA
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required'
        ]);

        // Vérifier le mot de passe
        if (!auth()->attempt(['email' => auth()->user()->email, 'password' => $request->password])) {
            toastr()->error("Mot de passe incorrect.");
            return back();
        }

        $user = auth()->user();
        $user->disableTwoFactor();

        // Audit trail pour désactivation 2FA
        //utilise Log::channel('sifec')->info('Double authentification désactivée');
        Log::channel('sifec')->info('Double authentification désactivée');
        UserAuditTrail::log($user->code_user, '2fa_disabled', "Double authentification désactivée");

        toastr()->success("Double authentification désactivée.");

        return redirect()->route('two-factor.index');
    }

    /**
     * Afficher la page de vérification 2FA lors de la connexion
     */
    public function showVerify()
    {
        if (!session('2fa:user:id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor.verify');
    }

    /**
     * Vérifier le code 2FA lors de la connexion
     */
    public function verify(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|numeric|digits:6'
        ]);

        $userId = session('2fa:user:id');
        $user = \App\Models\User::find($userId);

        if (!$user) {
            toastr()->error("Session expirée. Veuillez vous reconnecter.");
            return redirect()->route('login');
        }

        $secret = decrypt($user->google2fa_secret);
        $valid = $this->google2fa->verifyKey($secret, $request->one_time_password);

        if ($valid) {
            // Authentifier l'utilisateur
            auth()->login($user, session('2fa:remember'));

            // Mettre à jour la date de vérification
            $user->two_factor_verified_at = now();
            $user->save();

            // Nettoyer la session
            session()->forget(['2fa:user:id', '2fa:remember']);

            if ($user->must_change_password) {
                return redirect()->route('first-login-password.show')
                    ->with('first_login_notice', true);
            }

            toastr()->success("Connexion réussie!");
            return redirect()->intended('/');
        }

        toastr()->error("Code invalide. Veuillez réessayer.");
        return back();
    }

    /**
     * Vérifier avec un code de récupération
     */
    public function verifyRecoveryCode(Request $request)
    {
        $request->validate([
            'recovery_code' => 'required|string'
        ]);

        $userId = session('2fa:user:id');
        $user = \App\Models\User::find($userId);

        if (!$user) {
            toastr()->error("Session expirée. Veuillez vous reconnecter.");
            return redirect()->route('login');
        }

        if ($user->useRecoveryCode($request->recovery_code)) {
            // Authentifier l'utilisateur
            auth()->login($user, session('2fa:remember'));

            $user->two_factor_verified_at = now();
            $user->save();

            session()->forget(['2fa:user:id', '2fa:remember']);

            if ($user->must_change_password) {
                return redirect()->route('first-login-password.show')
                    ->with('first_login_notice', true);
            }

            toastr()->warning("Vous vous êtes connecté avec un code de récupération. Il vous reste " . $user->getRemainingRecoveryCodesCount() . " code(s) de récupération.");

            return redirect()->intended('/');
        }

        toastr()->error("Code de récupération invalide.");
        return back();
    }
}

