<?php

namespace App\Http\Controllers;

use BaconQrCode\Writer;
use Illuminate\Http\Request;
use App\Models\UserAuditTrail;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Log;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;

class TwoFactorController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->middleware('auth')->except(['showVerify', 'verify', 'verifyRecoveryCode']);
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
        $user = auth()->user();

        // Toujours générer un nouveau secret si la 2FA est désactivée ou si le secret n'existe pas
        // Cela permet de réactiver proprement la 2FA après une désactivation
        if (empty($user->google2fa_secret) || !$user->google2fa_enabled) {
            $secret = $this->google2fa->generateSecretKey();
            $user->google2fa_secret = encrypt($secret);
            $user->save();
        }

        // Générer le QR Code
        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            decrypt($user->google2fa_secret)
        );

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
     * Confirmer l'activation de la 2FA
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|numeric|digits:6'
        ]);

        $user = auth()->user();
        $secret = decrypt($user->google2fa_secret);

        $valid = $this->google2fa->verifyKey($secret, $request->one_time_password);

        if ($valid) {
            $user->google2fa_enabled = true;
            $user->two_factor_verified_at = now();
            $user->save();

            // Générer les codes de récupération
            $recoveryCodes = $user->generateRecoveryCodes();

            // Audit trail pour activation 2FA
            UserAuditTrail::log($user->code_user, '2fa_enabled', "Double authentification activée");

            toastr()->success("Double authentification activée avec succès!");

            return redirect()->route('two-factor.recovery-codes')
                ->with('recoveryCodes', $recoveryCodes);
        }

        toastr()->error("Code invalide. Veuillez réessayer.");
        return back();
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

            toastr()->warning("Vous vous êtes connecté avec un code de récupération. Il vous reste " . $user->getRemainingRecoveryCodesCount() . " code(s) de récupération.");

            return redirect()->intended('/');
        }

        toastr()->error("Code de récupération invalide.");
        return back();
    }
}

